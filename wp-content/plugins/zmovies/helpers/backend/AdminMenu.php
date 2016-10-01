<?php

namespace Zmovies\Helper;

class AdminMenu{

    public function __construct($options = null){
        
        //Them menu vao hệ thống
        add_action('admin_menu', array($this ,'createMenus'));
        
        //Chỉnh sửa hệ thống menu trong vùng admin
        add_action('admin_menu', array($this,'modifyMenus'),101012312);
    }
    
    /*
     * Hàm tạo hệ thống menu cho vùng Admin
     */
    public function createMenus(){
       
        global $zController;
        
        $menuSlug = $zController->getMenuSlug();
		
		$iconUrl = $zController->getImageUrl('/icons/Videos-icon.png');
		
		add_menu_page('ZVideos', 'ZVideos', 'manage_options', $menuSlug,
						array($this,'dispatch_function'),$iconUrl,'3.123');
		//Dashboard
		add_submenu_page($menuSlug, __('Dashboard',ZMOVIES_DOMAIN_LANGUAGE),__('Dashboard',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
						$menuSlug,array($this,'dispatch_function'));
		
		//MOVIES menu
		add_submenu_page($menuSlug, __('Video Categories',ZMOVIES_DOMAIN_LANGUAGE),__('Video Categories',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		                 $menuSlug . '-video-category',array($this,'dispatch_function'));
		
		//MOVIES CATEGORY menu
		add_submenu_page($menuSlug, __('Videos',ZMOVIES_DOMAIN_LANGUAGE),__('Videos',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		                 $menuSlug . '-video',array($this,'dispatch_function'));
        add_submenu_page($menuSlug, __('Videos Report 16+',ZMOVIES_DOMAIN_LANGUAGE),__('Videos Report 16+',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
                         $menuSlug . '-video-report-16',array($this,'dispatch_function'));
        add_submenu_page($menuSlug, __('Videos Report Die',ZMOVIES_DOMAIN_LANGUAGE),__('Videos Report Die',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
                         $menuSlug . '-video-report-die',array($this,'dispatch_function'));
		
		/* add_submenu_page($menuSlug, __('Video Tags',ZMOVIES_DOMAIN_LANGUAGE),__('Video Tags',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		                 $menuSlug . '-video-tag',array($this,'dispatch_function')); */
		
		add_submenu_page($menuSlug, __('Youtube Users',ZMOVIES_DOMAIN_LANGUAGE),__('Youtube Users',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		    $menuSlug . '-youtube-user',array($this,'dispatch_function'));
		
		add_submenu_page($menuSlug, __('Youtube Keywords',ZMOVIES_DOMAIN_LANGUAGE),__('Youtube Keywords',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		    $menuSlug . '-youtube-keyword',array($this,'dispatch_function'));
		
		add_submenu_page($menuSlug, __('Channels',ZMOVIES_DOMAIN_LANGUAGE),__('Channels',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		    $menuSlug . '-channel',array($this,'dispatch_function'));
		
		add_submenu_page($menuSlug, __('Playlists',ZMOVIES_DOMAIN_LANGUAGE),__('Playlists',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		                 $menuSlug . '-playlist',array($this,'dispatch_function'));
		
		//TAG menu
		add_submenu_page($menuSlug, __('Ads',ZMOVIES_DOMAIN_LANGUAGE),__('Ads',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		                  $menuSlug . '-ads',array($this,'dispatch_function'));
		
		add_submenu_page($menuSlug, __('ShortCode',ZMOVIES_DOMAIN_LANGUAGE),__('Shortcodes',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		                  $menuSlug . '-short-code',array($this,'dispatch_function'));
		
		add_submenu_page($menuSlug, __('Cron Event',ZMOVIES_DOMAIN_LANGUAGE),__('Cron Event',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		$menuSlug . '-cron-event',array($this,'dispatch_function'));
		
		
		/* add_submenu_page($menuSlug, __('Player Settings',ZMOVIES_DOMAIN_LANGUAGE),__('Player Settings',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		    $menuSlug . '-player-setting',array($this,'dispatch_function')); */
		
		add_submenu_page($menuSlug, __('Auto Update',ZMOVIES_DOMAIN_LANGUAGE),__('Auto Update',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		                  $menuSlug . '-auto-update',array($this,'dispatch_function'));
		
		add_submenu_page($menuSlug, __('Settings',ZMOVIES_DOMAIN_LANGUAGE),__('Settings',ZMOVIES_DOMAIN_LANGUAGE), 'manage_options',
		                  $menuSlug . '-settings',array($this,'dispatch_function'));
		
    }
    
    
    /*
     * Hàm điều hướng đến các Controller khi nhấn vào các Menu link
     */
    public function dispatch_function(){
        
        global $zController;
        
        
        $page       = $zController->getParams('page');
        $menuSlug   = $zController->getMenuSlug();
       
        switch ($page) {
            case $menuSlug . '-short-code':
                $obj = $zController->getController('ShortCode','/backend',array('hello'));
                break;
            case $menuSlug . '-ads':
                $obj = $zController->getController('Ads','/backend',array('hello'));
                break;
            case $menuSlug . '-settings':
                $obj = $zController->getController('Setting','/backend',array('hello'));
                break;
            /* case $menuSlug . '-player-setting':
                $obj = $zController->getController('PlayerSetting','/backend',array('hello'));
                break; */
            case $menuSlug . '-auto-update':
                $obj = $zController->getController('AutoUpdate','/backend',array('hello'));
            break;
            case $menuSlug . '-cron-event':
                $obj = $zController->getController('CronEvent','/backend',array('hello'));
                break;
            default:      
                $obj = $zController->getController('Dashboard','/backend',array('hello'));
                break;
        }
    }
    
    /*
     * Hàm chỉnh sửa lại hệ thống menu trong Admin 
     */
    public function modifyMenus(){
        global $menu, $submenu;
        
        //===============================================
        // THAY ĐỔI LINK CHO CUSTOM TAXONOMY 
        // VÀ CUSTOM POST
        //===============================================
        $zvideos_manager = @$submenu['zvideos-manager'];
       
        if(count($zvideos_manager) > 0){
            foreach ($zvideos_manager as $key => $val){
                if($val[2] == 'zvideos-manager-video')
                    $zvideos_manager[$key][2] = 'edit.php';
                
                if($val[2] == 'zvideos-manager-video-category')
                    $zvideos_manager[$key][2] = 'edit-tags.php?taxonomy=category';
                
                /* if($val[2] == 'zvideos-manager-video-tag')
                    $zvideos_manager[$key][2] = 'edit-tags.php?taxonomy=post_tag'; */
                
                if($val[2] == 'zvideos-manager-channel')
                    $zvideos_manager[$key][2] = 'edit-tags.php?taxonomy=zvideos_channel&post_type=post';
                    
                if($val[2] == 'zvideos-manager-playlist')
                    $zvideos_manager[$key][2] = 'edit-tags.php?taxonomy=zvideos_playlist&post_type=post';
                
                if($val[2] == 'zvideos-manager-youtube-user')
                    $zvideos_manager[$key][2] = 'edit-tags.php?taxonomy=zvideos_youtube_user&post_type=post';

                if($val[2] == 'zvideos-manager-youtube-keyword')
                    $zvideos_manager[$key][2] = 'edit-tags.php?taxonomy=zvideos_youtube_keyword&post_type=post';
                if($val[2] == 'zvideos-manager-video-report-16')
                    $zvideos_manager[$key][2] = 'edit.php?post_status=report_16&post_type=post';
                if($val[2] == 'zvideos-manager-video-report-die')
                    $zvideos_manager[$key][2] = 'edit.php?post_status=report_die&post_type=post';
                
            }
        }
        
        $submenu['zvideos-manager'] = $zvideos_manager;        
        
        //REMOVE CUSTOM POST GROUP
        //remove_menu_page('edit.php?post_type=zvideos');
        remove_menu_page('edit.php');
        
    }
  
    public function removeMenu(){
        
    }
    
    public function removeSubMenu(){
        
    }
}