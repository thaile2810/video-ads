<?php

namespace Zmovies\Helper;

class CssJs{

    public function __construct($options = null){
        
        //Thêm tập tin CSS vào hệ thống
        add_action('admin_enqueue_scripts'	, array($this,'add_css_file'));
        
        //Thêm tập tin JS vào hệ thống
		add_action('admin_enqueue_scripts'	, array($this,'add_js_file'));
    }
    
    /*
     * Hàm thêm tập tin CSS vào hệ thống
     */
    public function add_css_file(){
       
        global $zController;        
        
        wp_register_style('zmovies_backend', $zController->getCssUrl('backend.css'), array(),'1.0');
        wp_enqueue_style('zmovies_backend');
        
    }
    
    /*
     * Hàm thêm tập tin JS vào hệ thống
     */
    public function add_js_file(){
         
        global $zController;
        
        //echo '<br/>' . __METHOD__;
        
        $page       = $zController->getParams('page');
        $menuSlug   = $zController->getMenuSlug();
        
        switch ($page) {
            case $menuSlug . '-short-code':
                /*  wp_register_script('zmovie-chosen', $zController->getJsUrl(' chosen.jquery.min.js'), array('jquery'),'1.0',false);
                 wp_enqueue_script('zmovie-chosen');
                 
                 wp_register_script('zmovie-filter-movies', $zController->getJsUrl('zmovie-filter-movies.js'), array('jquery'),'1.0',true);
                 wp_enqueue_script('zmovie-filter-movies');
                 
                 wp_register_style('zmovies_chosen', $zController->getCssUrl('chosen.css'), array(),'1.0');
                 wp_enqueue_style('zmovies_chosen'); */
                
                break;
        
            default:
                ;
                break;
        }
        
        
        wp_register_script('zadmin-function', $zController->getJsUrl('admin-function.js'), array('jquery'),'1.0',false);
	    wp_enqueue_script('zadmin-function');
    
    
    }
    
    
    
}