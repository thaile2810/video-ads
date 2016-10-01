<?php
namespace Zmovies\Controller\Backend;

class Video{
	
	public function __construct(){
	    
	    global $zController,$post_type;
	    
	    $phpFile = basename($_SERVER['SCRIPT_NAME']);
	    
	    if($phpFile == 'post.php' || $phpFile == 'post-new.php' || $phpFile == 'edit.php'){
	        //$model = $zController->getModel('Video');
	        $model = $zController->getModel('Video');
	        add_action('admin_head', array($model,'validate'), 1);
	        //add_action("load-post.php", array($model,'check_empty_title'), 1);
	        if($this->checkPostType() !== 'page'){
        		add_action('admin_init', array($model,'postTypeObject'));
        		add_action('edit_form_after_title', array($model,'editForm') );
	        }
    		if($zController->isPost()){
    			add_action( 'pre_post_update', [$model,'action_pre_post_update'], 10, 2 );
                add_action('save_post', array($model,'save'),10,3 ); 
//                 add_filter('wp_insert_post_empty_content', array($model,'post_empty_content'),10,3 );
    		} 

	    	add_action('admin_footer-edit.php', array($model,'appendQuickEditStatus'));
		    add_action('admin_footer-post.php', array($model,'appendPostStatus'));
		    add_action('admin_footer-post-new.php', array($model,'appendPostStatus'));
		    add_filter('display_post_states', array($model,'displayPostStatus'));
		    add_action('admin_footer-edit.php', array($model,'custom_bulk_admin_footer'));
		    add_action('load-edit.php', array($model,'custom_bulk_action'));
		    add_action('admin_notices', array($model, 'custom_bulk_admin_notices'));
    		//remove_filter('display_post_states', [$model,'displayPostStatus']);
	    }
	}
	
	public function checkPostType(){
	    $post_type = false;
	    $post = false;
	    if(isset($_GET['post'])){
	        $post_id = $post_ID = (int) $_GET['post'];
	    }elseif(isset($_POST['post_ID'])){
	        $post_id = $post_ID = (int) $_POST['post_ID'];
	         
	    }else{
	        $post_id = $post_ID = 0;
	    }
	    if($post_id){
	        $post = get_post( $post_id );
	    }
	    if($post){
	        $post_type = $post->post_type;
	    }
	    
	    return $post_type;
	}
}