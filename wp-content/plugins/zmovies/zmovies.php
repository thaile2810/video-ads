<?php
use Zendvn\Plugin\AutoUpdate;
use Zendvn\System\SiteOffline;
/*
Plugin Name: Zvideo
Description: Kênh Video Giải Trí Tổng Hợp
Author: Zendvn Team
Version: 1.0
*/
//error_reporting(-1);
require_once 'define.php';
require_once ZMOVIES_LIBRARY_PATH . DS . 'autoload.php';
require_once ZMOVIES_INCLUDE_PATH . '/Controller.php';
require_once ZMOVIES_INCLUDE_PATH . '/html.php';

global $zController, $htmlObj,$wp_admin_bar;
$zController 	= new \Zendvn\Inc\Controller;
$htmlObj 		= new \Zendvn\Inc\ZendvnHtml;
//$zController->getHelper('CreatePage');

//AutoUpdate::getInstance(__FILE__);


$auto = AutoUpdate::getInstance(__FILE__);
//$auto->dailyUser();
// die();

//Remove post_tags
add_action( 'init', 'unregister_tags' );
function unregister_tags() {
    unregister_taxonomy_for_object_type( 'post_tag', 'post' );
}
function check_site_offline(){
    $site_offline = SiteOffline::getInstance(__FILE__);
    $site_offline->checkActiveSite();
}
if(is_admin()){    
    global $customPost;    
    //Khởi tạo đối tượng CustomPost
    require_once ZMOVIES_INCLUDE_PATH . '/CustomPost.php';
    $customPost = new \Zendvn\Inc\CustomPost;     
	require_once 'backend.php';
	new \Zmovies\Ttp\Backend;
	
	
}else{
     
         
    global $zSettings;
    $zSettings = $zController->getHelper('Settings');  
    
    require_once 'frontend.php';
    new \Zmovies\Ttp\Frontend;
    
     $zController->getShortCode('Movies');

    if(!empty($zSettings->getFbAPI())){
        $zController->getShortCode('FbComment','',$zSettings->getFbAPI());
        $zController->getShortCode('FbLike','',$zSettings->getFbAPI());
        $zController->getShortCode('FbShare','',$zSettings->getFbAPI());
        $zController->getShortCode('FanPage');
    }
//     $zController->getHelper('Rewrite');
   
    add_action('init','check_site_offline');
}
$zController->getController('Ajax','/frontend');
$zController->getHelper('CustomRole', '', __FILE__);

error_reporting(0);

