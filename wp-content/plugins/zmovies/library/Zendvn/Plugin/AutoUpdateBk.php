<?php
namespace Zendvn\Plugin;

class AutoUpdateBk{
    
    public static $instance;
    
    private function __construct($pluginFile) {
        self::$instance = $this;
        //http://www.paulund.co.uk/create-cron-jobs-in-wordpress
        register_activation_hook($pluginFile, [self::$instance,'activation']);
        register_deactivation_hook($pluginFile, [self::$instance,'deactivation']);
        add_action('zvideo_update_hourly', [self::$instance, 'updateVideoHourly']);
        add_action('zvideo_update_daily', [self::$instance, 'updateVideoDaily']);
        
        add_action('zvideo_process_img', [self::$instance, 'processImage']);
        //add_action('zvideo_check_tmp', [self::$instance, 'checkVideoTmp']);
        
        //add_filter('cron_schedules', [self::$instance, 'addCronTime']);
        //$this->processImage();
    }
    
    public static function getInstance($pluginFile){
        if (self::$instance === null) {
            self::$instance = new self($pluginFile);
        }
        return self::$instance;
    }
    public function activation(){
        wp_schedule_event(current_time('timestamp'), 'hourly', 'zvideo_update_hourly');
        wp_schedule_event(current_time('timestamp'), 'daily', 'zvideo_update_daily');
        
        wp_schedule_event(current_time('timestamp'), 'daily', 'zvideo_process_img');
        //wp_schedule_event(current_time('timestamp'), 'daily', 'zvideo_check_tmp');
    }
    public function updateVideoHourly(){
        global $zController,$wpdb;
        $model = $zController->getModel('Video');
        
        $options = get_option(ZMOVIES_SETTING_OPTION, []);
        $max = (int)@$options['max_video_id'];
        $jump = (int)@$options['video_jump'];
        if($max > 0){
            $video_jump = (int)@$options['video_jump'] > 0 ? (int)@$options['video_jump'] : 5;
            $options['max_video_id'] = $max + $video_jump;
            update_option(ZMOVIES_SETTING_OPTION, $options);
        }
        /* if ($max > 0 && $jump >0){
            $sql = "SELECT ID FROM $wpdb->posts WHERE ID BETWEEN {$max} AND {$jump}";
            $data = @$wpdb->get_results($wpdb->prepare($sql));
            $date = get_the_date('Y-m-d');
            foreach ($data as $key => $value){
               $flag = $model->checkExistThumb(get_the_ID(),'mqdefault',$date);
                if($flag == true){
                    wp_delete_post($value->ID);
                    delete_post_meta($value->ID,'zvideos_post_url');
                }
            }
        } */
        
        //file_put_contents(ZMOVIES_PLUGIN_PATH . 'auto-update.log', $video_jump . ' - ' . $max . "\n", FILE_APPEND | LOCK_EX);
        
    }
    public function updateVideoDaily(){
        global $zController;
        
        $options = get_option(ZMOVIES_SETTING_OPTION . '-auto-update', []);
        
        $category      = $zController->getModel('Category');
        
        $categories    = $category->getTerm(null,['task' => 'auto-update']);
        $categories    = array_column($categories, 'term_id', 'meta_value');
        
        if(@$options['user']['status']){
            $this->user($options['user'],$categories);
        }
        
        if(@$options['channel']['status']){
            $this->channel($options['channel'],$categories);
        }
        
        if(@$options['playlist']['status']){
            $this->playlist($options['playlist'],$categories);
        }
        
        if(@$options['keyword']['status']){
            $this->keyword($options['keyword'],$categories);
        }
    }
    public function processImage(){
        global $zController;
        $model = $zController->getModel('Video');
        $model->processImage();
    }
    public function deactivation($param) {
        wp_clear_scheduled_hook('zvideo_update_hourly');
        wp_clear_scheduled_hook('zvideo_update_daily');
        wp_clear_scheduled_hook('zvideo_process_img');
        //wp_clear_scheduled_hook('zvideo_check_tmp');
    }
    
    
    public function addCronTime($schedules){
    
        $schedules['sixsec'] = [
                                //'interval' => 21600, // Every 6 hours
                                'interval' => 5, // second setup
                                'display'  => __( 'Every 6 hours' ),
                                ];
    
        return $schedules;
    }
    
    
    public function user($settings,$categories){
        $day = date('N',current_time('timestamp'));
        if(@$settings['updated'] == 0 || @$settings['updated'] == $day){
            global $zController;
        
            $model = $zController->getModel('YoutubeUser');
            $model->autoUpdateVideos($settings,$categories);
        }
    }
    
    public function channel($settings,$categories){
        $day = date('N',current_time('timestamp'));
        if(@$settings['updated'] == 0 || @$settings['updated'] == $day){
            global $zController;
        
            $model = $zController->getModel('Channel');
            $model->autoUpdateVideos($settings,$categories);
        }
    }
    
    public function playlist($settings,$categories){
        $day = date('N',current_time('timestamp'));
    
        if(@$settings['updated'] == 0 || @$settings['updated'] == $day){
            global $zController;
        
            $model = $zController->getModel('Playlist');
            $model->autoUpdateVideos($settings,$categories);
        }
    }
    
    public function keyword($settings,$categories){
        $day = date('N',current_time('timestamp'));
        
        if($settings['updated'] == 0 || $settings['updated'] == $day){
            global $zController;
    
            $model = $zController->getModel('YoutubeKeyword');
            $model->autoUpdateVideos($settings,$categories);
        }
    
    }
    
    public function sendRequest($hostname, $port = 80, $path){
        $fp = fsockopen($hostname, $port, $errno, $errstr);
        
        if(!$fp){
            
        }else{
            $out = "GET /" . $path . " HTTP/1.1\r\n";
            $out .= "Host: " . $hostname . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            /* while (!feof($fp)) {
                echo fgets($fp, 128);
            } */
            fclose($fp);
            return true;
        }
    }
}

