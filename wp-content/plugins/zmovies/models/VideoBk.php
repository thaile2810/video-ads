<?php
namespace Zmovies\Models;

use Zendvn\Api\Youtube;
if(!function_exists('convert_to_screen')){
    require_once ABSPATH . 'wp-admin/includes/template.php';
}

if(!class_exists('WP_List_Table')){
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
 
class Video{
    
    public $_key = 'zvideos_post';
    
    public function __construct(){
        $this->registerStatus();
        add_action('manage_post_posts_columns', array($this,'headColumns'));
    }
    public function headColumns($headColumns) {
        unset($headColumns['comments']);
        return $headColumns;
    }
	/*=============================================
	 * Lưu các giá trị metabox vào trong
	 *=============================================*/
	public function save($post_id){
	    global $zController;
	    
	    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	     
	    if(!current_user_can('edit_posts')) return $post_id;
	    set_time_limit(3600);
	    foreach ($zController->getParams($this->_key) as $metaKey => $metaValue){
	        if($metaKey === 'yid'){
	            
	            $ytube         = new Youtube();
	            $video_yid     = $this->url2Id($metaValue);
	            
	            $videos = $ytube->video($video_yid, $this->getFields());
	            
	            if($zController->getParams('publish')){
	                $post_status = 'publish';
	            }else{
	                $post_status = $zController->getParams('post_status') ? $zController->getParams('post_status') : 'publish';
	            }
	            
	            if(!empty($videos['items'])){
	                $params    = ['item' => current($videos['items']), 'post_status' => $post_status];
	                $this->apiAdd($params);
	            }
	        }
	    }
	}
	
	public function getFields(){
	    $fields = 'id,snippet/title';
	    
	    /* if(empty(@$_REQUEST['post_title'])){
	        $fields .= ',snippet/title';
	    } */
	    if(empty(@$_REQUEST['content'])){
	        $fields .= ',snippet/description';
	    }
	    /* if(empty(@$_REQUEST['tax_input']['post_tag'])){
	        $fields .= ',snippet/tags';
	    } */
	    if(isset($_REQUEST['tax_input']['zvideos_channel']) && empty(current(@$_REQUEST['tax_input']['zvideos_channel']))){
	        $fields .= ',snippet/channelId,snippet/channelTitle';
	    }else if(!isset($_REQUEST['tax_input']['zvideos_channel'])){
	        $fields .= ',snippet/channelId,snippet/channelTitle';
	    }
	    if(empty(@$_REQUEST['post_category']) || empty(current(@$_REQUEST['post_category']))){
	        $fields .= ',snippet/categoryId';
	    }
	    
	    return 'items(' . $fields . ')';
	}
	
	public function editForm($post){
	    
	    global $htmlObj;
	    $lbl           = __('Youtube Url',ZMOVIES_DOMAIN_LANGUAGE);
	    $id            = $this->inputId('yid');
	    $postId        = $this->metaKey('yid');
	    $url           = get_post_meta($post->ID, $this->metaKey('yid'),true);
	    

	    $value         = esc_attr($url) ? esc_attr($url) : '';
	    $input         = $htmlObj->textbox($id,$value,array('size'=>'30', 'spellcheck' => 'true', 'autocomplete' => 'off', 'class' => 'zplaceholder'));
	    $cls           = $url ? 'class="screen-reader-text"' : '';
	    
	    echo  sprintf('<div id="%sdiv" class="zctp">
        	       <div id="%swrap">
        	    	  <label id="%s-prompt-text" for="%s" %s>%s</label>
        	    	  %s
        	       </div>
    	       </div>',$postId,$postId,$postId,$id,$cls,$lbl,$input);
	}
	
	public function postTypeObject(){
	
	    global $post_type_object,$wp_post_types;
	    $post_type_object = $wp_post_types[ 'post' ];
	
	    $post_type_object->label                           = 'Videos';
	    $post_type_object->labels->name                    = 'Videos';
	    $post_type_object->labels->singular_name           = 'Video';
	    $post_type_object->labels->add_new_item            = 'Add New Video';
	    $post_type_object->labels->edit_item               = 'Edit Video';
	    $post_type_object->labels->new_item                = 'New Video';
	    $post_type_object->labels->view_item               = 'View Video';
	    $post_type_object->labels->search_items            = 'Search Videos';
	    $post_type_object->labels->not_found               = 'No videos found.';
	    $post_type_object->labels->not_found_in_trash      = 'No videos found in Trash.';
	    $post_type_object->labels->all_items               = 'All Videos';
	    $post_type_object->labels->errors                  = 'Video Errors';
	    $post_type_object->labels->insert_into_item        = 'Insert into video';
	    $post_type_object->labels->uploaded_to_this_item   = 'Uploaded to this video';
	    $post_type_object->labels->filter_items_list       = 'Filter videos list';
	    $post_type_object->labels->items_list_navigation   = 'Videos list navigation';
	    $post_type_object->labels->items_list              = 'Videos list';
	    $post_type_object->labels->menu_name               = 'Videos';
	    $post_type_object->labels->name_admin_bar          = 'Video';
	}
	
	public function playlistAddVideo($params, &$settings = null){
	    
	    if(empty($params['yurl'])) return false;
	    
	    $ytube = new Youtube();
	    $items = $ytube->playlistItems($params['yurl'],@$params['nextPageToken'], 50);
	    
        if(!empty($items) && !empty($items['items'])){
            set_time_limit(3600);
	        foreach ($items['items'] as $video){
	            $videos = $ytube->video($video['snippet']['resourceId']['videoId']);
	            if(isset($videos['items'])){
	                $params['item'] = current($videos['items']);
	                $this->apiAdd($params, $this->getFields());
	            }
	        }
	        if(isset($items['nextPageToken'])){
	            $params['nextPageToken'] = $items['nextPageToken'];
	            $this->playlistAddVideo($params, $settings);
	        }
	    }
	}
	public function keywordAddVideo($params,$settings = null){
	    
	    if(empty($params['name'])) return false;
	    
	    $settings = $this->getOption('keyword');
	    $maxResults = (isset($settings['total']) && (int) @$settings['total'] > 0) ? @$settings['total'] : 50;
	    
	    $ytube = new Youtube();
	    $items = $ytube->search($params['name'],$maxResults,@$settings['order']);
	    
	    if(!empty($items) && !empty($items['items'])){
	        set_time_limit(3600);
	        foreach ($items['items'] as $video){
	            $videos = $ytube->video($video['id']['videoId'], $this->getFields());
                if(isset($videos['items'])){
	                $params['item'] = current($videos['items']);
	                $this->apiAdd($params);
	            }
	        }
	    }
	}
	public function getOption($key){
	    $options = get_option(ZMOVIES_SETTING_OPTION . '-auto-update', []);
	    return @$options[$key];
	}
	public function apiAdd($params, $options = null){
	    
	    if(empty($params['item'])) return false;
	    
	    global $zController;
	    
	    $item      = $params['item'];
	    $term_id   = $params['term_id'];
	    $snippet   = $item['snippet'];

        if(@$snippet['title'] != 'Private video' ){

            //set_time_limit(3600);
            if($this->isYoutubeExit('yid', @$item['id']) == false || @$_REQUEST['action'] == 'fr-editpost'){
                    
                if(@$snippet['channelId']){
                    $channel_id = [$this->setChannel(@$snippet['channelId'],@$snippet['channelTitle'])];
                    
                    /* $filename = ZMOVIES_FILE_PATH . DS . 'logs' . DS . 'apiAdd.log';
                    file_put_contents($filename, date('Y-m-d H:i:s') . "\n", FILE_APPEND | LOCK_EX);
                    file_put_contents($filename, $snippet['channelId'] . "\n", FILE_APPEND | LOCK_EX);
                    file_put_contents($filename, $snippet['channelTitle'] . "\n", FILE_APPEND | LOCK_EX);
                    file_put_contents($filename, json_encode($channel_id) . "\n", FILE_APPEND | LOCK_EX); */
                }else{
                    $channel_id = @$_REQUEST['tax_input']['zvideos_channel'];
                }
                
                if(@$snippet['categoryId']){
                    $category_id = [$this->setCategory(@$snippet['categoryId'])];
                }else{
                    $category_id = @$_REQUEST['post_category'];
                }
                
                if(!empty(@$_REQUEST['post_title'])){
                    $title = @$_REQUEST['post_title'];
                }else{
                    $title = wp_strip_all_tags(@$snippet['title']);
                }
                if(!empty(@$_REQUEST['content'])){
                    $content = wp_strip_all_tags(@$_REQUEST['content']);
                }else{
                    $content = wp_strip_all_tags($snippet['description']);
                }
                /* $tags_input = @$snippet['tags'];
                if(!empty(@$_REQUEST['tax_input']['post_tag'])){
                     $tags_input = @$_REQUEST['tax_input']['post_tag'];
                } */
                $post_status = isset($params['post_status']) ? $params['post_status'] : 'publish';
                if(isset($_REQUEST['video_16']) && $_REQUEST['action'] = 'z-upload'){
                	$post_status = 'video_16';
                }
                $post_date = current_time('mysql');
                $data = [
                    'post_title'    => $title,
                    'post_type'     => 'post',
                    'post_status'   => $post_status,
                    //isset($params['post_status']) ? $params['post_status'] : 'publish'
//                     'tags_input'    => $tags_input,
                    //'tax_input'     => ['zvideos_channel' => $channel_id],
                    'post_category' => $category_id,
                    'post_date'     => $post_date,
                    'post_content'  => $content,
                    'post_author'  => $this->getPostAuthor(),
                ];

                // neu ko co dong nay se bi vong lap vo tan do wp_update_post goi lai save_post
                remove_action('save_post', [$this,'save']);
                remove_action('wp_insert_post_empty_content', [$this,'save']);
            	
                if(@$_REQUEST['post_ID']){
                    $data['post_ID'] = @$_REQUEST['post_ID'];
                    $post_ID = wp_update_post($data);
                }elseif(@$_REQUEST['video']){
                    $data['ID'] = @$_REQUEST['video'];
                    if (!wp_is_post_revision($data['ID']) ){
                    	$post_ID = wp_update_post($data);
                    }
                }else{
                    $post_ID = wp_insert_post($data);
                }
                
                if(!is_wp_error($post_ID)){
                    update_post_meta($post_ID, $this->metaKey('yid'), $item['id']);
                    wp_set_post_terms($post_ID, $channel_id, 'zvideos_channel', true);
                    wp_set_post_terms($post_ID, $term_id, 'zvideos_playlist', true);
                    $this->saveImage($item['id'],date('Y-m-d', strtotime($post_date)));
                }
            }
        }
	}

	public function getPostAuthor(){
		$setting_default_user = get_option(ZMOVIES_SETTING_OPTION)['video']['default_systerm_user'];
		$current_user = get_current_user_id();
		if($current_user != ''){
			return $current_user;
		}elseif($setting_default_user != ''){
			return $setting_default_user;
		}else{
			global $wpdb;
			$sql = "SELECT SQL_CALC_FOUND_ROWS $wpdb->users.ID
					FROM $wpdb->users
					WHERE 1=1";
			$list_user = $data = $wpdb->get_results($sql);
			$list_user_id = [];
			foreach ($list_user as $key => $value) {
				$list_user_id[] = $value->ID;
			}
			return min($list_user_id);
		}
	}
	
	public function isYoutubeExit($key = 'yid', $yid){
	    global $wpdb;
	    
	    //$videoUrl       = 'https://www.youtube.com/watch?v=' . $videoId;
	    return $wpdb->get_row('SELECT post_id FROM ' . $wpdb->postmeta .
                	        ' WHERE meta_key =  "' . $this->metaKey($key) . '" ' . 
                	        'AND meta_value = "' . $yid . '"'
                	        , ARRAY_A );
	}
	public function setCategory($ycid){
	    
	    global $zController;
	    $cid = 0;
	    
	    $model  = $zController->getModel('Category');
	    
	    $term = $model->getTerm(['ycid' => $ycid] ,['task' => 'record-exit']);
	    
	    if(!empty($term)){
	        $cid = $term['term_id'];
	    }else{
	        $term = $model->addCategoryFromYoutube($ycid);
	        if(is_array($term)){
	            $cid    = $term['term_id'];
	        }
	    }
	    
	    return $cid;
	}
	public function setChannel($ycnid,$ycnTitle){
	    global $zController;
        
        $model  = $zController->getModel('Channel');
        $term   = $model->getMeta('ycnid', $ycnid);
        if(!empty($term)){
            $channel_id = $term['term_id'];
        }else{
            $channel_id = $model->addFromYoutube($ycnid,$ycnTitle);
        }
        
        return $channel_id;
	}
	public function getMeta($key = 'yid', $value){	    
	    global $wpdb;
	    return $wpdb->get_row('SELECT post_id FROM ' . $wpdb->postmeta . 
	        ' WHERE meta_key =  "' . $this->metaKey($key) . 
	        '" AND meta_value = "' . $value . '"', ARRAY_A );
	}
	private function saveImage($yid,$post_date){
	    
	    $datePath  = str_replace('-',DS, $post_date);
        $image      = ZMOVIES_UPLOAD_PATH . DS . $datePath . DS;
        if(!file_exists($image . $yid . '.jpg')){
            
            $post_date = explode('-', $post_date);
            $year   = $post_date[0];
            $month  = $post_date[1];
            $day    = $post_date[2];
            /* $zvideo = WP_CONTENT_DIR . DS . 'uploads' . DS . 'zvideo';
            if(!file_exists($zvideo)){// thư mục root video
                mkdir($zvideo,0755); //CMOD
            } */
            $yFolder = ZMOVIES_UPLOAD_PATH . DS . $year;// thư mục năm
            if(!file_exists($yFolder)){
                mkdir($yFolder,0755); //CMOD
            }
            $mFolder = $yFolder . DS . $month;// thư mục tháng
            if(!file_exists($mFolder)){
                mkdir($mFolder,0755); //CMOD
            }
            $dFolder = $mFolder . DS . $day;// thư mục ngày
            if(!file_exists($dFolder)){
                mkdir($dFolder,0755); //CMOD
            }
            
            $url = sprintf('http://img.youtube.com/vi/%s/%s',$yid,'0.jpg');
            @copy($url, $image . $yid . '.jpg');
            
            //wp_delete_post($post['ID'],true);
            // xóa ảnh và thư mục
            /* $file = ZMOVIES_FILE_PATH . DS . 'video' . DS . $datePath . DS . 'thumb' . $yid . DS;
            array_map('unlink', glob("$file/*.*"));
            @rmdir($file); */
            
        }
	}
	public function inputId($id){
	    return $this->_key . '[' . $id . ']';
	}
	
	public function metaKey($id){
	    return $this->_key . '_' . $id;
	}
    public function url2Id($yurl){
	    //return str_replace('https://www.youtube.com/watch?v=', '', $videoUrl);
	    if(strlen($yurl) == 11) return $yurl;
        $rx = '~
                ^(?:https?://)?              # Optional protocol
                 (?:www\.)?                  # Optional subdomain
                 (?:youtube\.com|youtu\.be)  # Mandatory domain name
                 /watch\?v=([^&]+)           # URI with video id as capture group 1
                 ~x';
        
        $has_match = preg_match($rx, $yurl, $matches);
        
        if($has_match && $matches[1] && strlen($matches[1]) == 11){
            return $matches[1];
        }
        return false;
	}
	
	public function getYoutubeUrl($pid = null){
	    if($pid != null){
	        return get_post_meta($pid,$this->metaKey('yid'),true);
	    }
	}
	
	public function getYoutubeId($pid = null){
	    return str_replace('https://www.youtube.com/watch?v=', '', $this->getYoutubeUrl($pid));
	}
	/* 
	 *  Get image url from video id
	 *  
	 *  @pid: Interger Post ID
	 *  @size: String size(default|hqdefault|maxresdefault|mqdefault|sddefault)
	 *         Default is "default"
	 *  @return image url
	 *  
	 *  */
	public function getImageUrl($pid, $date = ''){
	    
	    $yUrl = $this->getYoutubeUrl($pid);
	    
	    $datePath = str_replace('-',DS, $date);
	    $dateUrl = str_replace('-','/', $date);
	    
	    if(!empty($yUrl)){
            $yid = $this->url2Id($yUrl);
            $this->saveImage($yid, $datePath);
            return ZMOVIES_UPLOAD_URL . '/' . $dateUrl . '/' . $yid . '.jpg';
	    }
	    
	    return false;
	}
	
	public function validate() {
	    echo '<script language="javascript" type="text/javascript">
            jQuery(document).ready(function($) {
               $("#post").submit(function(){
                  if($("input[name=\'zvideos_post[yid]\'").val() == "") {
                     alert("You must put something in the youtube url!");
                     return false;
                  }
               });
            });</script>';
	    /*  else {
	     if(tinyMCE.getContent() == "") {
	     alert("You must put something in the post body!");
	     return false;
	     }
	     } */
	}
	public function empty_filter($text) {
	    return '';
	}
	
	# if any field is empty, forcibly empty the fields so that it will fail post publishing
	public function check_empty_title() {
	    if(isset($_POST['publish']) && $_POST['publish'] == "Publish") {
	        if(empty($_POST['post_title']) || empty($_POST['content'])) {
// 	            add_filter('content_save_pre', 'empty_filter');
// 	            add_filter('excerpt_save_pre', 'empty_filter');
// 	            add_filter('title_save_pre', 'empty_filter');
	        }
	    }
	}

	/**
     * Register 
     * @Author  : Zendvn
     * @Version : 1.0
     */

	public function registerStatus(){
	    register_post_status( 'report_16', array(
	        'label'                     => _x( 'Report 16+', 'post' ),
	        'public'                    => true,
	        'show_in_admin_all_list'    => true,
	        'show_in_admin_status_list' => true,
	        'label_count'               => _n_noop( 'Report 16+ <span class="count">(%s)</span>', 'Report 16+ <span class="count">(%s)</span>' )
	    ) );
	    register_post_status( 'video_16', array(
	        'label'                     => _x( 'Video 16+', 'post' ),
	        'public'                    => true,
	        'show_in_admin_all_list'    => true,
	        'show_in_admin_status_list' => true,
	        'label_count'               => _n_noop( 'Video 16+ <span class="count">(%s)</span>', 'Video 16+ <span class="count">(%s)</span>' )
	    ) );
	}

    /**
     * Display post status
     * @Author  : Zendvn
     * @Version : 1.0
     */
	public function displayPostStatus( $states ) {
	    global $post;
	    $arg = get_query_var('post_status');

	    if($arg != 'pending'){
	        if($post->post_status == 'report_16'){
	            return array('Report 16+');
	        }
	        if($post->post_status == 'video_16'){
	            return array('Video 16+');
	        }
	    }
	    return $states;
	}

	/**
	 * Custom display post status
	 * @Author  : Zendvn
	 * @Version : 1.0
	 */
	public function appendPostStatus(){
	    global $post;
	    $complete      = '';
	    $completeNew   = '';
	    $label         = '';
	    $save          = '';
	     
	    if($post->post_type == 'post'){
	        if($post->post_status == 'report_16'){
	            $completeNew = ' selected="selected"';
	            $label = '<span id="post-status-display"> Report 16+</span>';
	            $save  = '$("#save-post").val("Save Report 16+"); ';
	        }

	        if($post->post_status == 'video_16'){
	            $completeNew2 = ' selected="selected"';
	            $label2 = '<span id="post-status-display">Video 16+</span>';
	            $save2  = '$("#save-post").val("Save Video 16+"); ';
	        }

	        echo '
                <script>
                jQuery(document).ready(function($){
                    $("select#post_status").append(\'<option value="report_16" '.$completeNew.'>Report 16+</option>\');
                    $(".misc-pub-section label").append(\''.$label.'\');
                    ' . $save . ' 
                    $("select#post_status").append(\'<option value="video_16" '.$completeNew2.'>Video 16+</option>\');
                    $(".misc-pub-section label").append(\''.$label2.'\');
                    ' . $save2 . ' 
                    $(".save-post-status").click(function(){
                        var val = $("select#post_status option:selected").val();
                        var lab = $("select#post_status option:selected").text();
                        $("#post_status").val(val);
                        $("#save-post").val("Save " + lab);
                    });
                    
                });
                </script>
                ';
	    }
	}

	/**
	 * Edit quickview post status
	 * @Author  : Zendvn
	 * @Version : 1.0
	 */
	public function appendQuickEditStatus() {
	    echo "<script>
            jQuery(document).ready( function($) {
                $( 'select[name=\"_status\"]' ).append( '<option value=\"report_16\">Report 16+</option>' );
                $( 'select[name=\"_status\"]' ).append( '<option value=\"video_16\">Video 16+</option>' );
        	});
        	</script>";
	}

	public function custom_bulk_admin_footer() {
 
		global $post_type;
		 
		if($post_type == 'post') {
		    ?>
		    <script type="text/javascript">
		      	jQuery(document).ready(function() {
			        jQuery('<option>').val('video_16').text('<?php _e('Move to Video 16+');?>').appendTo("select[name='action']");
			        jQuery('<option>').val('video_16').text('<?php _e('Move to Video 16+');?>').appendTo("select[name='action2']");
		      	});
		    </script>
		    <?php
		}
	}

	public function custom_bulk_admin_notices() {
		global $typenow, $pagenow;
  		
  		if($pagenow == 'edit.php' && $typenow == 'post' && isset($_REQUEST['zmessage'])) {
    		$message = sprintf( '%s Post updated.', count(explode(',', $_REQUEST['ids'])) );
    		echo "<div class='updated'><p>{$message}</p></div>";
  		}
	}

	public function custom_bulk_action() {
		global $typenow, $pagenow;

		$location = 'edit.php';
		if( $pagenow == 'edit.php' && $typenow == 'post' ) {
			$wp_list_table = _get_list_table('WP_Posts_List_Table');
	  		$action = $wp_list_table->current_action();
	  		if($action == 'video_16'){
	  			check_admin_referer('bulk-posts');
	  			foreach ($_REQUEST['post'] as $key => $value) {
		  			$my_post = ['ID' => $value, 'post_status' => 'video_16'];
		  			wp_update_post( $my_post );
		  		}
		  		//$paged = $_GET['paged'] ? $_GET['paged']: 0; 
		  		$location = add_query_arg(['zmessage' => 'move_16', 'ids' => join(',', $_REQUEST['post'])], $location);
		  		if($location) {
                    if(!empty($_REQUEST['paged'])){
                        $location = add_query_arg('paged', (int)$_REQUEST['paged'], $location );
                    }
                    wp_redirect( $location );
                    exit();
                }
	  		}
	  	}
	}


}