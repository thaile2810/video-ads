<?php
namespace Zendvn\System;

class SiteOffline {
    public static $instance;
    
    private function __construct($pluginFile) {
        self::$instance = $this;
        //http://www.paulund.co.uk/create-cron-jobs-in-wordpress
        //register_activation_hook($pluginFile, [self::$instance,'activation']);
    
        //add_filter('cron_schedules', [self::$instance, 'addCronTime']);
    }
    
    public static function getInstance($pluginFile){
        if (self::$instance === null) {
            self::$instance = new self($pluginFile);
        }
        return self::$instance;
    }
    public function checkActiveSite(){
        if(is_admin()){//Don't show on admin side of the site
            return;
        }
        
        $options = get_option(ZMOVIES_SETTING_OPTION,[]);
        if (@$options['offline']['status'] === false){
            return;
        }
        if (@$options['offline']['status'] == 1){
            if (!current_user_can('edit_posts') && !in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ))) {
                $protocol = "HTTP/1.0";
                if ("HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"]) {
                    $protocol = "HTTP/1.1";
                }
                header("$protocol 503 Service Unavailable", true, 503);
                header("Retry-After: 3600");
                $site_conent = html_entity_decode($options['offline']['content'], ENT_COMPAT,"UTF-8");
                echo $site_conent; 
                exit();
            } 
        }
    }
}