<?php
namespace Zmovies\Models;

if(!function_exists('convert_to_screen')){
    require_once ABSPATH . 'wp-admin/includes/template.php';
}

if(!class_exists('WP_List_Table')){
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
 
class Tag{
    
    public $_key = 'post_tag';
    
    public function __construct(){
        add_action('manage_edit-' . $this->_key . '_columns', array($this,'headColumns'));
        add_filter('manage_' . $this->_key . '_custom_column', array($this,'contentColumns'),10, 3);
    }
    
    public function contentColumns( $out, $column_name, $terms_id ) {
         
        if (@$column_name == 'videos') {
            $the_query = new \WP_Query(array(
                                            'post_type' => 'post',
                                            //'post_status' => array( 'pending', 'draft', 'new', 'trash' ),
                                            'tax_query' => array(
                                                array(
                                                    'taxonomy' => $this->_key,
                                                    'field' => 'id',
                                                    'terms' => $terms_id
                                                )
                                            )
                                        ));
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
        $post_tag                                       = $wp_taxonomies[ 'post_tag' ];
	
        $post_tag->label                                = 'Video Tags';
        $post_tag->labels->name                         = 'Video Tags';
        $post_tag->labels->singular_name                = 'Video Tag';
        $post_tag->labels->search_items                 = 'Search Video Tags';
        $post_tag->labels->popular_items                = 'Popular Video Tags';
        $post_tag->labels->all_items                    = 'All Video Tags';
        $post_tag->labels->parent_item                  = '';
        $post_tag->labels->parent_item_colon            = '';
        $post_tag->labels->edit_item                    = 'Edit Video Tag';
        $post_tag->labels->view_item                    = 'View Video Tag';
        $post_tag->labels->update_item                  = 'Update Video Tag';
        $post_tag->labels->add_new_item                 = 'Add New Video Tag';
        $post_tag->labels->new_item_name                = 'New Video Tag Name';
        $post_tag->labels->separate_items_with_commas   = 'Separate video tags with commas';
        $post_tag->labels->add_or_remove_items          = 'Add or remove video tags';
        $post_tag->labels->choose_from_most_used        = 'Choose from the most used video tags';
        $post_tag->labels->not_found                    = 'No video tags found.';
        $post_tag->labels->no_terms                     = 'No video tags';
        $post_tag->labels->items_list_navigation        = 'Video tags list navigation';
        $post_tag->labels->items_list                   = 'Video tags list';
        $post_tag->labels->menu_name                    = 'Video Tags';
        $post_tag->labels->name_admin_bar               = 'post_tag';
	}
	
}