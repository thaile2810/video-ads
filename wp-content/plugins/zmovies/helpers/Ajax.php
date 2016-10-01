<?php
namespace Zmovies\Helper;

class Ajax{
    
    public $_options = null;
    
    public function __construct($options = null){
        $this->_options = $options;
    }
    
    public function create($obj, $func){
        add_action('wp_ajax_process', array($obj,$func));
    }
    
}