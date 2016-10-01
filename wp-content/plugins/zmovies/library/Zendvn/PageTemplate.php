<?php
namespace Zendvn;

class PageTemplate {

    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;


    /**
     * Returns an instance of this class.
     */
    public static function get_instance() {

        if( null == self::$instance ) {
            self::$instance = new PageTemplate();
        }
        return self::$instance;
    }

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {

        $this->templates = array();

        add_filter('page_attributes_dropdown_pages_args', array( $this, 'register_project_templates'));

        add_filter('wp_insert_post_data', array( $this, 'register_project_templates'));

        $this->templates = array(
                                'zendvn-quiz'     => 'Zendvn Quiz',
                                );
    }

    public function register_project_templates( $atts ) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );

        return $atts;

    }

    /**
     * Checks if the template is assigned to the page
     */
    /* public function view_project_template( $template ) {

        global $post;

        if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true )])){
            return $template;
        }

        $file = plugin_dir_path(__FILE__). get_post_meta($post->ID, '_wp_page_template', true);

        // Just to be safe, we check if the file exist first
        if( file_exists( $file ) ) {
            return $file;
        }else { echo $file; }

        return $template;
    } */


}

