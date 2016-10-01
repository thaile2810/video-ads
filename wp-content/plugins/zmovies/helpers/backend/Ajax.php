<?php
namespace Zmovies\Helper;

class Ajax{
    
    public $_options = null;
    
    public function __construct($options = null){
        $this->_options = $options;
        add_action('wp_ajax_process', array($this,'process'));
    }
    
    public function process(){
        
        global $zController;
        $options = $this->_options;
        
        echo '<br/>' . __METHOD__;
        
        die();
    }
    
}