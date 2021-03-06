<?php
namespace Zmovies\Models;

use Zendvn\Api\Youtube;
class PlaylistBk{
	
    public $_key = 'zvideos_playlist';
    private $_actions = [];
    
    public function __construct(){
        add_action('manage_edit-' . $this->_key . '_columns', array($this,'headColumns'));
        add_filter('manage_' . $this->_key . '_custom_column', array($this,'contentColumns'),10, 3);
    }
    public function contentColumns( $out, $column_name, $terms_id ) {
         
        /* if (@$column_name == 'videos') {
            $the_query = new \WP_Query( array(
                'post_type' => 'post',
                //'post_status' => array( 'pending', 'draft', 'new', 'trash' ),
                'tax_query' => array(
                    array(
                        'taxonomy' => $this->_key,
                        'field' => 'id',
                        'terms' => $terms_id
                    )
                )
            ) );
            echo $the_query->found_posts;
        } */
        if (@$column_name == 'status') {
            global $zController;
            $status = get_term_meta($terms_id,$this->metaKey('status'), true);
            
            if($status == 1 ){
                $status = 0;
                $src = $zController->getImageUrl('/icons/active.png');
            }else{
                $status = 1;
                $src = $zController->getImageUrl('/icons/inactive.png');
            }
            
            $meta = array('term_id' => $terms_id, 'meta_key' => $this->metaKey('status'),'meta_value' => $status);
            
            $html       = '<a class="z-tax-status" data-meta=\'' . json_encode($meta) . '\'><img src="' . $src . '"></a>';
            
            echo $html;
        }
    }
    
    public function headColumns($headColumns) {
        $headColumns = [
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            // 'description' => __('Description'),
            'slug' => __('Slug'),
            'status' => __('Status'),
            //'videos' => __('Count videos'),
            //'posts' => __('Posts')
        ];
        return $headColumns;
    }
    
	public function create(){
	
		$labels = array(
				'name'                          => 'Video Playlists',
				'singular'                      => 'Video Playlist',
				'menu_name'                     => 'Video Playlists',
				//'all_items'                   => chua xac dinh
				//'view_item'                   => chua xac dinh
				'edit_item'                     => 'Edit playlist',
				'update_item'                   => 'Update playlist',
				'add_new_item'		            => 'Add new playlist',
				//'new_item_name'               => chua xac dinh
				//'parent_item'                 => chua xac dinh
				//'parent_item_colon'           => chua xac dinh
				'search_items'		            => 'Search playlists',
				'popular_items'                 => 'Playlists are using',
				'separate_items_with_commas'    => 'Separate tags with commas 123',
				'choose_from_most_used'         => 'Choose from the most used tags 123',
				'not_found'                     => 'No playlist found',
	
		);
		$args = array(
				'labels' 				=> $labels,
				'public'				=> true,
		    
				'show_ui'				=> true,// disable in edit page
				'show_in_nav_menus'	    => false,// disable in admin page
				'show_in_quick_edit'	=> false,// disable in quick edit
				//'show_tagcloud'			=> true,
				'hierarchical'			=> true,
				'show_admin_column'		=> false,
				'query_var'				=> true,
				'rewrite'				=> array('slug' => 'zplaylist','with_front'=>false),
		);
		register_taxonomy($this->_key, 'post',$args);
	
	}
	public function add_form_fields(){
	    global $htmlObj;
	    
	    $lbl           = __('Youtube playlist',ZMOVIES_DOMAIN_LANGUAGE);
	    $value         = '';
	    $id            = $this->inputId('url');
	    $input         = $htmlObj->textbox($id,$value,array('size'=>'40', 'aria-required' => 'true'));
	    $description   = __('Link of youtube playlist.',ZMOVIES_DOMAIN_LANGUAGE);
	    $taxId         = $this->metaKey('url');
	    echo sprintf('<div class="form-field form-required term-%s-wrap">
	                       <label for="%s">%s</label>%s<p>%s</p><p class="z-error"></p>
                        </div>',$taxId,$id,$lbl,$input,$description);
	    
	    // ========================= Default Category ========================
	    $lbl           = __('Default Category',ZMOVIES_DOMAIN_LANGUAGE);
	    $value         = '';
	    $id            = $this->inputId('category');
	     
	    $description   = __('Category of playlist.',ZMOVIES_DOMAIN_LANGUAGE);
	    $metaKey       = $this->metaKey('category');
	     
	    $args          = array(
	        //'show_count' => 1,
	        'echo' => 0,
	        'hide_empty' => 0,
	        'selected' => $value,
	        'name' => $id,
	        'id' => $metaKey,
	        'option_none_value' => 0,
	        'show_option_none' => 'Follow video',
	    );
	     
	    $input         = wp_dropdown_categories($args);
	    echo sprintf('<div class="form-field term-%s-wrap">
	                       <label for="%s">%s</label>%s<p>%s</p>
                        </div>',$metaKey,$metaKey,$lbl,$input,$description);
	    // ===================================================================
	    
	    $data = [0 => 'Inactive' , 1 => 'Active'];
	    $lbl           = __('Status',ZMOVIES_DOMAIN_LANGUAGE);
	    $value         = '';
	    $id            = $this->inputId('status');
	    $input         = $htmlObj->selectbox($id,$value,null , ['data' => $data]);
	    $description   = __('Status of keyword.',ZMOVIES_DOMAIN_LANGUAGE);
	    $metaKey         = $this->metaKey('status');
	    echo sprintf('<div class="form-field term-%s-wrap">
	                       <label for="%s">%s</label>%s<p>%s</p>
                        </div>',$metaKey,$id,$lbl,$input,$description);
	    
	    add_action( 'admin_footer-edit-tags.php', array($this,'removeField'), 10, 2 );
	}
	
	function edit_form_fields($term) {
	    global $htmlObj;
	    
	    $t_id          = $term->term_id;
	    $metaValue     = get_term_meta($t_id,$this->metaKey('url'),true);
	    
	    $lbl           = __('Youtube playlist',ZMOVIES_DOMAIN_LANGUAGE);
	    $id            = $this->inputId('url');
	    $value         = esc_attr($metaValue) ? esc_attr($metaValue) : '';
	    $input         = $htmlObj->textbox($id,$value,array('size'=>'40'));
	    $description   = __('Link of youtube playlist.',ZMOVIES_DOMAIN_LANGUAGE);
	    
		echo sprintf('<tr class="form-field">
            		      <th scope="row" valign="top"><label for="%s">%s</label></th>
            			 <td>%s<p class="description">%s</p></td>
            		  </tr>',$id,$lbl,$input,$description);
		
		// ========================= Default Category ========================
		$metaValue     = get_term_meta($term->term_id,$this->metaKey('category'),true);
		$lbl           = __('Default Category',ZMOVIES_DOMAIN_LANGUAGE);
		$value         = esc_attr($metaValue) ? esc_attr($metaValue) : '';
		$id            = $this->inputId('category');
			
		$description   = __('Category of playlist.',ZMOVIES_DOMAIN_LANGUAGE);
		$metaKey         = $this->metaKey('category');
			
		$args = array(
		    //'show_count' => 1,
		    'echo' => 0,
		    'hide_empty' => 0,
		    'selected' => $value,
		    'name' => $id,
		    'id' => $metaKey,
		    'option_none_value' => 0,
		    'show_option_none' => 'Follow video',
		);
			
		$input         = wp_dropdown_categories($args);
		echo sprintf('<tr class="form-field">
            		      <th scope="row" valign="top"><label for="%s">%s</label></th>
            			 <td>%s<p class="description">%s</p></td>
            		  </tr>',$metaKey,$lbl,$input,$description);
		// ===================================================================
		
		$data = [0 => 'Inactive' , 1 => 'Active'];
		$t_id          = $term->term_id;
		$metaValue     = get_term_meta($t_id,$this->metaKey('status'),true);
		
		$lbl           = __('Status',ZMOVIES_DOMAIN_LANGUAGE);
		$id            = $this->inputId('status');
		$value         = esc_attr($metaValue) ? esc_attr($metaValue) : '';
		$input         = $htmlObj->selectbox($id,$value,null , ['data' => $data]);
		$description   = __('Status of keyword.',ZMOVIES_DOMAIN_LANGUAGE);
		
		echo sprintf('<tr class="form-field">
            		      <th scope="row" valign="top"><label for="%s">%s</label></th>
            			 <td>%s<p class="description">%s</p></td>
            		  </tr>',$id,$lbl,$input,$description);
		
		add_action( 'admin_footer-edit-tags.php', array($this,'removeField'), 10, 2 );
	}
	
	public function channelAddPlaylist($params, $settings){
	    
	    if(empty($params['channel_yid'])) return false;
	    $ytube         = new Youtube();
	    $playlists     = $ytube->channel($params['channel_yid'],@$params['nextPageToken']);
	    
        if(!empty($playlists['items'])){
	        global $wpdb,$zController;
	        $model = $zController->getModel('Video');
	        // disable time limit
	        set_time_limit(0);
	        foreach ($playlists['items'] as $key => $playlist){
	            if($playlist['kind'] == 'youtube#playlist'){
	
	                $playlist_yid  = $playlist['id'];
	                $snippet       = $playlist['snippet'];
	                $url           = 'https://www.youtube.com/playlist?list=' . $playlist_yid;
	                $term          = $this->getMeta('url', $url);
	                
	                //$playlist_id = @$term['term_id'];
	                if(empty($term)){
	                    $item  = ['title' => $snippet['title'], 'parrent' => -1];
	                    $term = wp_insert_term( $item['title'], $this->_key, $item );
	                    if($term && !is_wp_error($term)){
	                        update_term_meta($term['term_id'],$this->metaKey('url'), $url);
	                        update_term_meta($term['term_id'],$this->metaKey('status'), 1);
	                        //$playlist_id = @$term['term_id'];
	                    }/* elseif(is_wp_error($term)){
	                        $playlist_id = $term->error_data['term_exists'];
	                    } */
	                }
	               
// 	                $tmpParram = [
//                                 'categories'    => $params['categories'],
//                                 /* 'user_id'       => @$params['user_id'], */
//         	                    /* 'channel_id'    => @$params['channel_id'],
//                                 'playlist_id'   => @$playlist_id, */
//                                 'playlist_yid'  => $playlist['id'],
//             	                ];
// 	                $model->playlistAddVideo($tmpParram, $settings);
	            }
	        }
	        if(isset($playlists['nextPageToken'])){
// 	            if($pageToken != null) die();
	            $params['nextPageToken'] = $playlists['nextPageToken'];
	            $this->channelAddPlaylist($params,$settings);
	        }
	    }
	}
	public function getMeta($key = 'url', $value){
	    global $wpdb;
	    return $wpdb->get_row("SELECT term_id FROM $wpdb->termmeta WHERE meta_key = '" . $this->metaKey($key) . "' AND meta_value = '" . $value . "'", ARRAY_A );
	}
	public function save( $term_id ) {
	    global $zController;
	    if (!empty($zController->getParams($this->_key))){
            foreach ($zController->getParams($this->_key) as $metaKey => $metaValue){
                update_term_meta($term_id,$this->metaKey($metaKey), $metaValue);
            }
	    }
	}
	
	public function customBulkAction(){
	    $bulk = new \Zendvn\Taxonomy\BulkAction($this->_key);
	    $bulk->register([
	        'menu_text'    => 'Update videos',
	        'admin_notice' => 'Update videos',
	        'action_name'  => $this->getActionName('update-video'),
	    ]);
	    add_action('admin_footer-edit-tags.php', [&$bulk, 'bulkFooter']);
	    add_action('load-edit-tags.php', [&$this, 'bulkAction']);
	    add_action('admin_notices', [&$this, 'bulkNotice']);
	    $this->_actions = $bulk->getAction();
	}
	public function bulkAction() {
	     
	    global $taxnow,$post_type;
	
	    if($taxnow == $this->_key) {
	        
	        $wp_list_table  = _get_list_table('WP_Terms_List_Table');
	        $location       = false;
	        $referer        = wp_get_referer();
	
	        $tags           = (array)@$_REQUEST['delete_tags'];
	        $action         = $wp_list_table->current_action();
	        
	        if($action ==  $this->getActionName('update-video') && !empty($tags)){
	            check_admin_referer( 'bulk-tags' );
	            global $zController;
	            // disable time limit
	            set_time_limit(0);
	            
	            $options       = get_option(ZMOVIES_SETTING_OPTION . '-auto-update', []);
	            $setting       = @$options['playlist'];
	            $category      = $zController->getModel('Category');
	            
	            $categories    = $category->getTerm(null,['task' => 'auto-update']);
	            $categories    = array_column($categories, 'term_id', 'meta_value');
	            $params        = ['categories' => $categories];
	            $model         = $zController->getModel('Video');
	            foreach($tags as $tag_ID ) {
	                
	                $status = get_term_meta($tag_ID,$this->metaKey('status'),true);
	                if($status){
	                    $url = get_term_meta($tag_ID,$this->metaKey('url'),true);
    	                if(!empty($url)){
    	                    $playlist_yid = $this->url2Id($url);
    	                    if(!empty($playlist_yid)){
    	                        $params['playlist_yid'] = $playlist_yid;
    	                        /* $params['playlist_id'] = $tag_ID; */
    	                        $model->playlistAddVideo($params,$setting);
    	                        //wp_delete_term($tag_ID, $this->_key);
    	                    }
    	                }
	                }
	            }
	            
	            $location = 'edit-tags.php?taxonomy=' . $this->_key;
	            if ( 'post' != $post_type )
	                $location .= '&post_type=' . $post_type;
	                if ( $referer && false !== strpos( $referer, 'edit-tags.php' ) ) {
	                    $location = $referer;
	                }
	
	                $location = add_query_arg(['zmessage' => $this->_key, 'ids' => join(',', $tags)], $location);
	                 
	                if(!$location && !empty($_REQUEST['_wp_http_referer'])) {
	                    $location = remove_query_arg( ['_wp_http_referer', '_wpnonce'], wp_unslash($_SERVER['REQUEST_URI']));
	                }
	                 
	                if($location) {
	                    if(!empty($_REQUEST['paged'])){
	                        $location = add_query_arg('paged', (int)$_REQUEST['paged'], $location );
	                    }
	                    wp_redirect( $location );
	                }
	                exit;
	        }
	    }
	}
	public function bulkNotice() {
	    global $post_type, $pagenow;
	
	    if($pagenow == 'edit-tags.php' && $post_type == 'post') {
	        if(isset($_REQUEST['zmessage']) && $_REQUEST['taxonomy'] == $this->_key){
	            $message = $this->_actions[$this->getActionName('update-video')]['admin_notice'];
	            if(!empty($message)) {
	                echo "<div class=\"updated\"><p>{$message}</p></div>";
	            }
	        }
	    }
	}
	public function getActionName($name = '') {
	    return str_replace('_', '-', $this->_key . '-' . $name);
	}
	public function inputId($id){
	    return $this->_key . '[' . $id . ']';
	}
	
	public function metaKey($id){
	    return $this->_key . '_' . $id;
	}
	
	public function isYoutubePlaylist($yurl){
	   return $this->url2Id($yurl);
	}
	public function url2Id($yurl){
	    //https://www.youtube.com/playlist?list=PLv6GftO355AsmgFEoUx_XHfN14FAI3SrN;
	    //^(?:https?://)?(?:www\.)?(?:youtube\.com|youtu\.be)/(.*)?([^list=]*)
	    //([^list\=.*]+) 
	    $rx = '~
                ^(?:https?://)?              # Optional protocol
                 (?:www\.)?                  # Optional subdomain
                 (?:youtube\.com|youtu\.be)  # Mandatory domain name
                 /.*\?list\=([^/]+)      # URI with video id as capture group 1
                 ~x';

	    $has_match = preg_match($rx, $yurl, $matches);
	    if($has_match && $matches[1] && strlen($matches[1]) == 34){
	         return $matches[1];        
	    }
	    return false;
	}
	public function taxExit($url){
	    global $wpdb;
	    return $wpdb->get_row("SELECT term_id FROM $wpdb->termmeta WHERE meta_key = '" . $this->metaKey('url') . "' AND meta_value = '" . $url . "'", ARRAY_A);
	
	}
	public function removeField(){
	    echo '<script type="text/javascript">
    	        jQuery(document).ready( function($) {
    	            $("#tag-description").parent().remove();
    	            $("#parent").parent().remove();
                    $(".form-table .term-parent-wrap").remove();
    	            $(".form-table .term-description-wrap").remove();
    	        });
    	        </script>';
	}
	public function autoUpdateVideos($settings,$categories){
	    global $wpdb;
	    $sql = "SELECT tm.meta_value,t.term_id FROM $wpdb->terms AS t
    	        INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                INNER JOIN $wpdb->termmeta AS tm ON t.term_id = tm.term_id
                INNER JOIN $wpdb->termmeta AS tm2 ON t.term_id = tm2.term_id
    	        WHERE tt.taxonomy IN ('" . $this->_key . "')
    	        AND tm.meta_key = '" . $this->metaKey('url') . "'
                AND tm2.meta_key = '" . $this->metaKey('status') . "'
                AND tm2.meta_value = 1
    	        ORDER BY t.name ASC";
	    
	    $terms = $wpdb->get_results($sql, ARRAY_A );
	    $terms = array_column($terms, 'meta_value','term_id');
	     
	    if(!empty($terms)){
	        global $zController;
	        $model     = $zController->getModel('Video');
	        $params    = ['categories' => $categories];
	         
	        set_time_limit(0);// disable time limit
	         
	        foreach ($terms as $term_id => $url){
	            $params['playlist_yid'] = $this->url2Id($url);
	            if(!empty($params['playlist_yid'])){
	                $model->playlistAddVideo($params, $settings);
	                //wp_delete_term($term_id, $this->_key);
	            }
	        }
	    }
	}
}