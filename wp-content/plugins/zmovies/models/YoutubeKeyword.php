<?php
namespace Zmovies\Models;
class YoutubeKeyword{
    
    private $_actions = [];
    
    public $_key = 'zvideos_youtube_keyword';
    
    public function __construct(){

        add_action('manage_edit-' . $this->_key . '_columns', array($this,'headColumns'));
        add_filter('manage_' . $this->_key . '_custom_column', array($this,'contentColumns'),10, 3);
        add_filter('manage_' . $this->_key . '_column', array($this,'contentColumns'),10, 3);
    }
    public function contentColumns( $out, $column_name, $terms_id ) {
      
         
        /* if (@$column_name == 'keywords') {
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
            }elseif($status == 2 ){
                $status = 0;
                $src = $zController->getImageUrl('/icons/pending.png');
            }else{
                $status = 1;
                $src = $zController->getImageUrl('/icons/inactive.png');
            }
            
            $meta = array('term_id' => $terms_id, 'meta_key' => $this->metaKey('status'),'meta_value' => $status);
            
            $html       = '<a class="z-tax-status" data-meta=\'' . json_encode($meta) . '\'><img src="' . $src . '"></a>';
            
            echo $html;
        }
        if (@$column_name == 'cb') {
            echo 'test';
        }
    }
    
    public function headColumns($headColumns) {
        $headColumns = [
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            // 'description' => __('Description'),
            'slug' => __('Slug'),
            'status' => __('Status'),
//             'keywords' => __('Count videos'),
            //'posts' => __('Posts')
        ];
        return $headColumns;
    }
    
	public function create(){
	
		$labels = [
    				'name'				=> 'Youtube Keywords',
    				'singular' 			=> 'Youtube Keyword',
    				'menu_name'			=> 'Youtube Keywords',
    				//'all_items'		=> chua xac dinh
    				//'view_item'		=> chua xac dinh
    				'edit_item'			=> 'Edit Youtube Keyword',
    				'update_item'		=> 'Update Youtube Keyword',
    				'add_new_item'		=> 'Add new Youtube Keyword',
    				//'new_item_name'	=> chua xac dinh
    				//'parent_item'		=> chua xac dinh
    				//'parent_item_colon'	=> chua xac dinh
    				'search_items'		=> 'Search Youtube Keywords',
    				'popular_items'		=> 'Youtube Keywords are using',
    				'separate_items_with_commas' => 'Separate tags with commas 123',
    				'choose_from_most_used' => 'Choose from the most used tags 123',
    				'not_found'			=> 'No youtube keyword found',
    	
		          ];
		$args = [
				'labels' 				=> $labels,
				'public'				=> true,
		    
				'show_ui'				=> true,// disable in edit page
				'show_in_nav_menus'	    => false,// disable in admin page
				'show_in_quick_edit'	=> false,// disable in quick edit
				//'show_tagcloud'			=> true,
				'hierarchical'			=> true,
				'show_admin_column'		=> false,
				'query_var'				=> true,
				'rewrite'				=> ['slug' => 'zyoutube-keyword','with_front'=>false],
		      ];
		register_taxonomy($this->_key, 'post',$args);
	
	}
	public function add_form_fields(){

	    global $htmlObj;
	    
	    // ========================= Default Category ========================
	    $lbl           = __('Default Category',ZMOVIES_DOMAIN_LANGUAGE);
	    $value         = '';
	    $id            = $this->inputId('category');
	     
	    $description   = __('Category of keyword.',ZMOVIES_DOMAIN_LANGUAGE);
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
	    $metaKey       = $this->metaKey('status');
	    echo sprintf('<div class="form-field term-%s-wrap">
	                       <label for="%s">%s</label>%s<p>%s</p>
                        </div>',$metaKey,$id,$lbl,$input,$description);
	    
	    add_action( 'admin_footer-edit-tags.php', array($this,'removeField'), 10, 2 );
	}
	public function edit_form_fields($term) {
	     
	    global $htmlObj;
	    
	    // ========================= Default Category ========================
	    $metaValue     = get_term_meta($term->term_id,$this->metaKey('category'),true);
	    $lbl           = __('Default Category',ZMOVIES_DOMAIN_LANGUAGE);
	    $value         = esc_attr($metaValue) ? esc_attr($metaValue) : '';
	    $id            = $this->inputId('category');
	    	
	    $description   = __('Category of keyword.',ZMOVIES_DOMAIN_LANGUAGE);
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
	public function save( $term_id ) {
	
	    global $zController;
	    if (!empty($zController->getParams($this->_key))){
	        foreach ($zController->getParams($this->_key) as $metaKey => $metaValue){
	            update_term_meta($term_id,$this->metaKey($metaKey), $metaValue);
	        }
	         
	    }
	}
	
    public function bulkAction() {
	    
		global $taxnow,$post_type,$zController;
		
		if($taxnow == $this->_key) {
            $wp_list_table  	= _get_list_table('WP_Terms_List_Table'); 
            $terms              = (array)@$_REQUEST['delete_tags'];
            $action             = $wp_list_table->current_action();
            if($action ==  $this->getActionName('update-video') && !empty($terms)){
            	check_admin_referer('bulk-tags');
            	
            	foreach ($terms as $term_id){
            	    update_term_meta($term_id, $this->metaKey('status'), 2);
            	}
            	
            	$this->autoRun();
            	
            	$referer        = wp_get_referer();
            	$location = 'edit-tags.php?taxonomy=' . $this->_key;
            	if ('post' != $post_type )
            	    $location .= '&post_type=' . $post_type;
            	if ( $referer && false !== strpos( $referer, 'edit-tags.php' ) ) {
            	    $location = $referer;
            	}
                
            	$location = add_query_arg(['zmessage' => $this->_key, 'ids' => join(',', $terms)], $location);
            	
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
	/* public function save( $term_id ) {
	    global $zController;
	    if (!empty($zController->getParams($this->_key))){
	        foreach ($zController->getParams($this->_key) as $metaKey => $metaValue){
	            update_term_meta($term_id,$this->metaKey($metaKey), $metaValue);
	        }
	        
	    }
	} */
	public function inputId($id){
	    return $this->_key . '[' . $id . ']';
	}
	
	public function metaKey($id){
	    return $this->_key . '_' . $id;
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
	public function daily($autoStd = null,$options = null){
	    
	    global $wpdb;
	
	    $update = $wpdb->update($wpdb->termmeta, ['meta_value' => 2], ['meta_key' => $this->metaKey('status'),'meta_value' => 1]);
	    
	    
	    if($update){// nếu có data mới add event
	    	$this->autoRun();
        }
	}
	
	public function autoRun(){
	    global $zController;
	    
	    global $wpdb;
	    $sql = "SELECT t.term_id,t.name FROM $wpdb->terms AS t
                                    INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                                    INNER JOIN $wpdb->termmeta AS tm ON t.term_id = tm.term_id
                                    WHERE tt.taxonomy IN ('". $this->_key ."')
                                    AND tm.meta_key = '".$this->metaKey('status')."'
                                    AND tm.meta_value = 2
                                    ORDER BY t.term_id ASC
                                        LIMIT 1";

	    $terms = $wpdb->get_row($sql, ARRAY_A );

	    // $filename = ZMOVIES_FILE_PATH . DS . 'logs' . DS . 'keyword.log';
	    // file_put_contents($filename, json_encode($terms) . "\n", FILE_APPEND | LOCK_EX);
        if($terms && isset($terms['term_id'])){
    	    $model = $zController->getModel('CronEvent');
    	    $model->add_cron('now', '_oneoff', $this->_key, array($terms));
        }
        return false;
	}
}