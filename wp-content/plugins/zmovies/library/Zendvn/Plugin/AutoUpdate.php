<?php
namespace Zendvn\Plugin;

class AutoUpdate{
    
    public static $instance;
    
    private function __construct($pluginFile) {
        self::$instance = $this;
        //http://www.paulund.co.uk/create-cron-jobs-in-wordpress
        register_activation_hook($pluginFile, [self::$instance,'activation']);
        register_deactivation_hook($pluginFile, [self::$instance,'deactivation']);
        
        $this->setAction();
        $this->setScheduleEvent();
    }
    
    private function getScheduleEvent(){
        return [
                'zvideo_update_hourly'          => ['recurrence' => 'hourly','func' => 'updateVideoHourly'],
                'zvideo_update_daily_user'      => ['recurrence' => 'daily','func' => 'dailyYoutubeUser'],
                'zvideo_update_daily_keyword'   => ['recurrence' => 'daily','func' => 'dailyKeyword'],
                'zvideo_update_daily_channel'   => ['recurrence' => 'daily','func' => 'dailyChannel'],
                'zvideo_update_daily_playlist'  => ['recurrence' => 'daily','func' => 'dailyPlaylist'],
                ];
    }
    public function updateVideoHourly(){
    
        $options = get_option(ZMOVIES_SETTING_OPTION, []);
        $max = (int)@$options['video']['max_video_id'];
        $jump = (int)@$options['video']['video_jump'];
        if($max > 0){
            $video_jump = (int)@$options['video']['video_jump'] > 0 ? (int)@$options['video']['video_jump'] : 5;
            $options['video']['max_video_id'] = $max + $video_jump;
            update_option(ZMOVIES_SETTING_OPTION, $options);
        }
    
    }
    private function setScheduleEvent(){
        $sEvent = $this->getScheduleEvent();
        foreach ($sEvent as $hook => $event){
            add_action($hook, [self::$instance, $event['func']]);
        }
    }
    // Do not turn of this function
    private function getAction(){
        return [
                'zvideos_youtube_user',
                'zvideos_youtube_keyword',
                'zvideos_playlist',
                'zvideos_channel'
                ];
    }
    private function setAction(){
        $actions = $this->getAction();
        foreach ($actions as $action){
            add_action($action, [self::$instance, $action]);
        }
    }
    public static function getInstance($pluginFile){
        if (self::$instance === null) {
            self::$instance = new self($pluginFile);
        }
        return self::$instance;
    }
    public function activation(){
        $sEvent = $this->getScheduleEvent();
        foreach ($sEvent as $hook => $event){
            wp_schedule_event(current_time('timestamp'), $event['recurrence'], $hook);
        }
    }
    public function deactivation($param) {
        $sEvent = $this->getScheduleEvent();
        foreach ($sEvent as $hook => $event){
            wp_clear_scheduled_hook($hook);
        }
    }
    
    public function zvideos_youtube_keyword($args){
        
        if(!isset($args['term_id']) || empty($args['term_id'])) return false;
        
        global $zController;
        $model = $zController->getModel('Video');
        $model->keywordAddVideo($args);
        
        $model = $zController->getModel('YoutubeKeyword');
        update_term_meta($args['term_id'],$model->metaKey('status'), 1);
        $model->autoRun();
    }
    
    public function dailyKeyword(){
        $options = get_option(ZMOVIES_SETTING_OPTION . '-auto-update', []);
        
        if(@$options['keyword']['status']){
            $day = date('N',current_time('timestamp'));
            if(@$options['keyword']['updated'] == 0 || @$options['keyword']['updated'] == $day){
                global $zController;
                $model = $zController->getModel('YoutubeKeyword');
                $model->daily();
            }
        }
    }
    
    public function zvideos_playlist($args){
        if(!isset($args['term_id']) || empty($args['term_id'])) return false;
        
        global $zController;
        $modelVideo = $zController->getModel('Video');
        $modelVideo->playlistAddVideo($args);
    
        $modelPlaylist = $zController->getModel('Playlist');
        update_term_meta($args['term_id'],$modelPlaylist->metaKey('status'), 1);
        $modelPlaylist->autoRun();
    }
    
    public function dailyPlaylist(){
        $options = get_option(ZMOVIES_SETTING_OPTION . '-auto-update', []);
        
        if($options['playlist']['status']){
            $day = date('N',current_time('timestamp'));
            if($options['playlist']['updated'] == 0 || $options['playlist']['updated'] == $day){
                global $zController;
                $model = $zController->getModel('Playlist');
                $model->daily();
            }
        }
    }
    
    public function zvideos_channel($args){
        
        if(!isset($args['term_id']) || empty($args['term_id'])) return false;
    
        global $zController;
        $modelPlaylist = $zController->getModel('Playlist');
        $modelPlaylist->channelAddPlaylist($args);
    
        $modelChannel = $zController->getModel('Channel');
        update_term_meta($args['term_id'],$modelChannel->metaKey('status'), 1);
        $modelChannel->autoRun();
    }
    public function dailyChannel(){
        $options = get_option(ZMOVIES_SETTING_OPTION . '-auto-update', []);
        if(@$options['channel']['status']){
            $day = date('N',current_time('timestamp'));
            if(@$options['channel']['updated'] == 0 || @$options['channel']['updated'] == $day){
                global $zController;
                $model = $zController->getModel('Channel');
                $model->daily();
            }
        }
    }
    public function zvideos_youtube_user($args){
        if(!isset($args['term_id']) || empty($args['term_id'])) return false;
    
        global $zController;
        $modelChannel = $zController->getModel('Channel');
        $modelChannel->userAddChannel($args);
    
        $modelYoutubeUser = $zController->getModel('YoutubeUser');
        update_term_meta($args['term_id'],$modelYoutubeUser->metaKey('status'), 1);
        $modelYoutubeUser->autoRun();
    }
    public function dailyYoutubeUser(){
        $options = get_option(ZMOVIES_SETTING_OPTION . '-auto-update', []);
        if(@$options['user']['status']){
            $day = date('N',current_time('timestamp'));
            if(@$options['user']['updated'] == 0 || @$options['user']['updated'] == $day){
                global $zController;
                $model = $zController->getModel('YoutubeUser');
                $model->daily();
            }
        }
    }
}

