<?php
namespace Zmovies\Ttp;

class Frontend{
	
	public $_options;
	
	private $_cssFlag = false;
	
	public function __construct($options = []){
		//echo '<br/>' . __METHOD__;
 		
 		$this->_options = $options;
		
 		//add_action('wp_head',[$this,'player_key']);
		add_action('init', [$this,'init']);
		
		
		add_action('wp_enqueue_scripts', [$this,'add_js_file']);
		
// 		add_action('pre_get_posts', [$this,'home_page']);
// 	    add_filter('template_include', [$this,'load_template']);
//  		add_action('pre_get_posts', [$this,'getIdBySlug']);
	}
	public function init(){
	    global $zController;
	    
	    $this->do_output_buffer();
	    
	    $model = $zController->getModel('Video');
	    
	    $model = $zController->getModel('YoutubeUser');
	    $model->create();
	    
	    $model = $zController->getModel('Channel');
	    $model->create();
	    
	    $model = $zController->getModel('Playlist');
	    $model->create();
	    
	    $model = $zController->getModel('YoutubeKeyword');
	    $model->create();
	    
// 	    $model = $zController->getModel('Tag');
// 	    $model->create();
	}
	public function home_page($query){  
	    
	    $queryFlag = false;
	    if($query->is_home() && $query->is_main_query()){
	        $queryFlag = true;	    
	    }
	    if(!empty(get_search_query())){
	        $queryFlag = true;
	    }
	
	    if($queryFlag == true){
	        $query->set('post_type', 'post');
	    }
	}
	
	public function setKeywords($str){
		global $zController,$wp_query;
	
		if(!is_front_page()){
			$rewriteSlug = $zController->_rewriteSlug;
				
			if(isset($rewriteSlug['district'])){
				$seo = $rewriteSlug['seo'];				
				if(isset($seo['keywords'])){
					return $seo['keywords'];
				}
			}
		}
		return $str;
			
	}
	
	public function setDescription($str){
		global $zController,$wp_query;
		
		if(!is_front_page()){				
			$rewriteSlug = $zController->_rewriteSlug;
			
			if(isset($rewriteSlug['district'])){
				$seo = $rewriteSlug['seo'];			
				if(isset($seo['description'])){
					return $seo['description'];					
				} 
			}
		}
		return $str;
			
	}
	
	public function setTitle($title, $sep){
		
		global $zController;
		
		if(!is_front_page()){
			
			$rewriteSlug = $zController->_rewriteSlug;	
			 
			if(isset($rewriteSlug['district'])){
				$seo = $rewriteSlug['seo'];
				if(!isset($seo['title']) || $seo['title'] == '' ){
					$titlePage = single_cat_title('',false);
				}else{
					$titlePage = $seo['title'];
				}				
				return $titlePage;
			}
			
		}
		return $title;
		
		
	}
	
	public function getIdBySlug(){
		
		global $zController;
		
		if(count($zController->_rewriteSlug) == 0 && get_query_var('res_category') != ''){
			//echo '<br/>' . __METHOD__;
			$rewriteSlug = $zController->getHelper('GetIdBySlug')->get();
			$zController->setRewriteSlug($rewriteSlug);
		}
		
	}

	public function do_output_buffer(){
		ob_start();
	}

	public function add_js_file(){
		global $zController;
	    wp_register_script('zvideos_search', $zController->getJsUrl('zvideo-search.js'), ['jquery','jquery-ui-autocomplete'],null,true);
	    wp_enqueue_script('zvideos_search');
	    
		wp_register_script('zvideos_jwplayer', ZMOVIES_JS_PLAYER . '/jwplayer.js',  ['zvideos_search'],'1.0',false);
		wp_enqueue_script('zvideos_jwplayer');
		
		// add global zvideo
		wp_localize_script('zvideos_jwplayer', 'zvideo', array('ajaxurl' => admin_url( 'admin-ajax.php' )));
		/* wp_register_script('zvideos_player', $zController->getJsUrl('zvideo-player.js'), ['zvideos_jwplayer'],'1.2',true);
		wp_enqueue_script('zvideos_player'); */
	}
	public function autocompleteSearchForm(){
// 	    global $zController;
	     
// 	    wp_register_script('zvideos_search', $zController->getJsUrl('zvideo-search.js'), ['jquery','jquery-ui-autocomplete'],null,true);
// 	    wp_enqueue_script('zvideos_search');
	}
	public function player_key(){
        $html = '';
	   
        $html = '<script>jwplayer.key="ViNOqCRLyJo3TOuGe1B+nfrOMBmJy7qPIiAF7w==";</script>';
	  
        echo $html;
	}
	
	public function modify_admin_bar($wp_admin_bar){
	
		/* $wp_admin_bar->remove_node('new-slide');
		$wp_admin_bar->remove_node('new-avada_portfolio');
		$wp_admin_bar->remove_node('new-avada_faq');
		$wp_admin_bar->remove_node('new-themefusion_elastic'); */
	}
	
	public function add_css_file(){
		//if($this->_cssFlag == true){
			global $zController;
			wp_register_style('jquery-ui', 'http://code.jquery.com/ui/1.9.0/themes/smoothness/jquery-ui.css', [],'1.0');
			wp_register_style('zvideo-fe', $zController->getCssUrl('zvideo-fe.css'), ['jquery-ui'],'1.0');
			wp_enqueue_style('zvideo-fe');
			
		//}
	}
	
    public function load_template($templates){
        
		global $wp_query, $post,$zController;
		global $zController;
/* 		echo '<pre>';
		    print_r($templates);
		echo '</pre>'; */
		
		$zController->getController('Channel','/backend');
		$zController->getController('Playlist','/backend');
		$zController->getController('Video','/backend');
		
		/* echo '<br>' . __METHOD__;
		echo '<br/>' . get_query_var('zvideos_category'); */
		
		if(is_page() == 1){
				
			$page_template = get_post_meta($post->ID,'_wp_page_template', true);
				
			$file = ZENDVN_RES_TEMPLATE_PATH . '/frontend/' . $page_template;
			echo '<br/>' . $file;
			if(file_exists($file)){
				$this->_cssFlag = true;
				return $file;
			}
	
		}
		
		$arrVars = ['zvideos_channel','zvideos_playlist','zvideos_youtube_user','zvideos_types'];
		
		$flagShow = false;
		foreach ($arrVars as $val){
		    if(get_query_var($val) != ''){
		        $flagShow = true; 
		        break;
		    }		    
		}
		
		if(!empty(get_search_query()))  $flagShow = true; 
		
		if(is_front_page() == 1) $flagShow = true; 
				
		if($flagShow == true){	    
			$file = ZMOVIES_TEMPLATE_PATH . '/frontend/' . 'zvideo_category.php';			
			if(file_exists($file)){				
				return $file;
			}
		}
		
		
		if(is_page()){
		  
		    $page_template = get_post_meta($post->ID,'_wp_page_template', true);    
		    $file = ZMOVIES_TEMPLATE_PATH . '/frontend/' . $page_template;		   
		    if(file_exists($file)){
		        return $file;
		    }
		    
		}else if(get_query_var('name') != 'post'){
		    
		    $file = ZMOVIES_TEMPLATE_PATH . '/frontend/' . 'zvideo.php';
		    if(file_exists($file)){
		        return $file;
		    }
		}
		
		return $templates;
	}
		
}
