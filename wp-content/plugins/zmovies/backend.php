<?php
namespace Zmovies\Ttp;

class Backend{
	
	private $_menuSlug = 'zendvn-res-manager';
	
	private $_page = '';
	
	public function __construct(){
		
		global $zController;
		
		//Suppor redirect in WP
		add_action('admin_init', array($this,'do_output_buffer'));
		//Get menu slug in Admin
		$this->_menuSlug =  $zController->getMenuSlug();
		
		//Get page param in URL
		if(isset($_GET['page'])) $this->_page = $_GET['page'];
		
		//Load các tập tin CSS và JS vào hệ thống
		$zController->getHelper('CssJs','/backend');
		
		//Load Custom Post và Custom Taxonomy
		$this->load_custom_type();
		
		//Thiết lập hệ thống menu mới trong vùng Admin
		$zController->getHelper('AdminMenu','/backend');
	}
	
	/*
	 * Hàm load các Controller chứa Custom Post và Custom Taxonomy
	 */
	public function load_custom_type(){
	    global $zController;	    
	    $model = $zController->getModel('Video');
	    $phpFile   = basename($_SERVER['SCRIPT_NAME']);
	    $post_type  = $zController->getParams('post_type');
        $screen     = $zController->getParams('screen');
        $taxonomy   = $zController->getParams('taxonomy');
	    $flagJs    = false;
	   
        $zController->getController('Category','/backend');
//         $zController->getController('Tag','/backend');
        $zController->getController('YoutubeUser','/backend');
        $zController->getController('YoutubeKeyword','/backend');
        $zController->getController('Channel','/backend');
        $zController->getController('Playlist','/backend');
        if($post_type != 'page') $zController->getController('Video','/backend');
        
        $flagJs = true;
        // post - quick edit
        if($phpFile == 'admin-ajax.php'){
            
            $action     = $zController->getParams('action');
            $func     = $zController->getParams('func');
            
            if(isset($_REQUEST['_inline_edit']) && $_REQUEST['_inline_edit']){
            	$model  = $zController->getModel('Video');
            	$post_id 		= $zController->getParams('post_ID');
            	$post_status 	= $zController->getParams('_status');
            	$model->action_pre_quick_edit_update($post_id, $post_status);
            }

            if($screen == 'edit-post' &&  $post_type == 'post' && $action == 'inline-save'){
           
        		add_filter('display_post_states', [$model,'displayPostStatus']);
        		//remove_filter('display_post_states', [$model,'displayPostStatus']);
    		
            }
            if($action == 'process' && $func == 'search'){
                $model  = $zController->getModel('Video');
            }
            
            if(($taxonomy = 'zvideos_youtube_user') && ($post_type == 'post'||$action == 'delete-tag')){
                $zController->getController('YoutubeUser','/backend');
            }
            if(($taxonomy = 'zvideos_youtube_keyword') && ($post_type == 'post'||$action == 'delete-tag')){
                $zController->getController('YoutubeKeyword','/backend');
            }
            if(($taxonomy = 'zvideos_chanel') && ($post_type == 'post'||$action == 'delete-tag')){
                $zController->getController('Channel','/backend');
            }
        }
        
	    if($flagJs)add_action('admin_enqueue_scripts', array($this,'add_js_file'));
	}
	/*
	 * Thêm tập tin js hỗ trợ cho Admin menu
	 */
	
	public function add_js_file(){
	    global $zController;	
	    wp_register_script('zmovies_admin_menu', $zController->getJsUrl('admin_menu.js'), array('jquery'),'1.1',true);
	    wp_enqueue_script('zmovies_admin_menu');

	    wp_register_script('zmovies_shortcode_js', $zController->getJsUrl('admin_shortcode.js'), array('jquery'),'1.1',true);
	    wp_enqueue_script('zmovies_shortcode_js');
	    
	    wp_enqueue_script('post');
	    // add global zvideo
	    wp_localize_script('zmovies_admin_menu', 'zvideo', array('ajaxurl' => admin_url( 'admin-ajax.php' )));
	}
	
	public function add_css_file(){

	    global $zController;
	    	
	    wp_register_style('zmovies-fe', $zController->getCssUrl('zmovies-fe.css'), array(),'1.0');
	    wp_enqueue_style('zmovies-fe');

	}
	
	/*
	 * Hàm hỗ trợ chống lỗi redirect trong WP
	 */
	public function do_output_buffer(){
		ob_start();
	}

}

















