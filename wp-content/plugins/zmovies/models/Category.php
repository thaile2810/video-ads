<?php
namespace Zmovies\Models;

use Zendvn\Api\Youtube;
if(!function_exists('convert_to_screen')){
    require_once ABSPATH . 'wp-admin/includes/template.php';
}

if(!class_exists('WP_List_Table')){
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Category{
    
    public $_key = 'category';
    
    public function __construct(){
        add_action('manage_edit-' . $this->_key . '_columns', array($this,'headColumns'));
        add_action('manage_edit-' . $this->_key . '_sortable_column', array($this,'sortableColumns'),10, 3);
        add_filter('manage_' . $this->_key . '_custom_column', array($this,'contentColumns'),10, 3);
    }
    public function sortableColumns($sortableColumns) {
        /* echo '<pre>';
        print_r($sortableColumns);
        echo '</pre>'; */
        $sortableColumns['videos'] = 'Count videos';
        return $sortableColumns;
    }
    public function contentColumns( $out, $column_name, $terms_id ) {
         
        if (@$column_name == 'videos') {
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
        }
    }
    
    public function headColumns($headColumns) {
        $headColumns = [
                        'cb' => '<input type="checkbox" />',
                        'name' => __('Name'),
                        // 'description' => __('Description'),
                        'slug' => __('Slug'),
                        'videos' => __('Count videos'),
                        //'posts' => __('Posts')
                        ];
        return $headColumns;
    }
	public function resetTaxConfig(){
	
	    global $wp_taxonomies;;
	    $category = $wp_taxonomies[ 'category' ];
	
	    $category->label                           = 'Video categories';
	    $category->labels->name                    = 'Video categories';
	    $category->labels->singular_name           = 'Video category';
	    $category->labels->add_new_item            = 'Add New Video Category';
	    $category->labels->edit_item               = 'Edit Video category';
	    $category->labels->new_item                = 'New Video category';
	    $category->labels->view_item               = 'View Video category';
	    $category->labels->search_items            = 'Search Video categories';
	    $category->labels->not_found               = 'No video category found.';
	    $category->labels->not_found_in_trash      = 'No video category found in Trash.';
	    $category->labels->all_items               = 'All Video categories';
	    $category->labels->archives                = 'Video Category Archives';
	    $category->labels->insert_into_item        = 'Insert into video category';
	    $category->labels->uploaded_to_this_item   = 'Uploaded to this video category';
	    $category->labels->filter_items_list       = 'Filter video categories list';
	    $category->labels->items_list_navigation   = 'Video categories list navigation';
	    $category->labels->items_list              = 'Video categories list';
	    $category->labels->menu_name               = 'Video category';
	    $category->labels->name_admin_bar          = 'Video category';
	    $category->labels->update_item             = 'Updated Video Category';
	    $category->labels->new_item_name           = 'New Video Category Name';
	    $category->labels->no_terms                = 'No Video Categories';
	    
	}
	
	public function add_form_fields(){
	    global $htmlObj;
	     
	    $lbl           = __('Youtube category id',ZMOVIES_DOMAIN_LANGUAGE);
	    $value         = '';
	    $id            = $this->inputId('ycid');
	    $input         = $htmlObj->textbox($id,$value,array('size'=>'40', 'aria-required' => 'true'));
	    $description   = __('Id of youtube category.',ZMOVIES_DOMAIN_LANGUAGE);
	    $taxId         = $this->metaKey('ycid');
	    echo sprintf('<div class="form-field form-required term-%s-wrap">
	                       <label for="%s">%s</label>%s<p>%s</p><p class="z-error"></p>
                        </div>',$taxId,$id,$lbl,$input,$description);
	    
	    add_action( 'admin_footer-edit-tags.php', array($this,'removeField'), 10, 2 );
	}
	
	public function edit_form_fields($term) {
	    global $htmlObj;
	     
	    $t_id          = $term->term_id;
	    $metaValue     = get_term_meta($t_id,$this->metaKey('ycid'),true);
	     
	    $lbl           = __('Youtube category id',ZMOVIES_DOMAIN_LANGUAGE);
	    $id            = $this->inputId('ycid');
	    $value         = esc_attr($metaValue) ? esc_attr($metaValue) : '';
	    $input         = $htmlObj->textbox($id,$value,array('size'=>'40'));
	    $description   = __('Id of youtube category.',ZMOVIES_DOMAIN_LANGUAGE);
	     
	    echo sprintf('<tr class="form-field">
            		      <th scope="row" valign="top"><label for="%s">%s</label></th>
            			 <td>%s<p class="description">%s</p></td>
            		  </tr>',$id,$lbl,$input,$description);
	    
	    add_action( 'admin_footer-edit-tags.php', array($this,'removeField'), 10, 2 );
	}
	
	public function save($term_id) {
	    global $zController;
	    if (!empty($zController->getParams($this->_key))){
	        foreach ($zController->getParams($this->_key) as $metaKey => $metaValue){
	            update_term_meta($term_id,$this->metaKey($metaKey), $metaValue);
	        }
	    }
	}
	/* 
	 * Add category form youtube category id
	 * 
	 * @ycid: Youtube category id
	 * 
	 * @return: array|WP_Error An array containing the `term_id` and `term_taxonomy_id`,
 *                        {@see WP_Error} otherwise
	 * 
	 *  */
	public function addCategoryFromYoutube($ycid){
	    
	    $ytube         = new Youtube();
	    $categories    = $ytube->videoCategories($ycid);
	    $category      = current(current($categories));
	     
	    $term = wp_insert_term($category['snippet']['title'], 'category');
	    if(is_array($term)){
            update_term_meta($term['term_id'],$this->metaKey('ycid'), $category['id']);
	    }elseif(is_wp_error($term)){
	        $term = ['term_id' => @$term->error_data['term_exists']];
	    }
	    return $term;
	}
	
	public function getTerm($params = null, $options = null){
	     
	    global $wpdb;
	    $result = [];
	    if($options['task'] == 'auto-update'){
	        
	        $sql = "SELECT t.term_id,tm.meta_value FROM $wpdb->terms AS t
                    	            INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                    	            INNER JOIN $wpdb->termmeta AS tm ON t.term_id = tm.term_id
                    	            WHERE tt.taxonomy IN ('" . $this->_key . "')
                    	            ORDER BY t.name ASC";
	        
	        $result = $wpdb->get_results($sql, ARRAY_A );
	    }
	    if($options['task'] == 'record-exit'){
	         
	        $sql = "SELECT t.term_id FROM $wpdb->termmeta AS t
	        WHERE t.meta_key ='" . $this->metaKey('ycid') . " '
	        AND t.meta_value ='" . $params['ycid'] . "'";
	    
	        $result = $wpdb->get_row($sql, ARRAY_A );
	    }
	    
	    return $result;
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
	public function inputId($id){
	    return $this->_key . '[' . $id . ']';
	}
	
	public function metaKey($id){
	    return $this->_key . '_' . $id;
	}
}