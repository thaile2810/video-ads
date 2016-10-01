<?php

namespace Zmovies\Backend\Validate;
use Zendvn\Validator\Validate;

class ShortCode extends Validate{
	
	public function __construct($arrParam = null, $options = null){
	    
	    if(check_admin_referer($arrParam['action'],'security_code')){
    	    $configs   = require_once ZMOVIES_CONFIG_PATH . DS . strtolower($options['prefix']) . DS . 'validator' 
    	                 . DS . strtolower($options['filename']) . '.php' ;
    	    $config    = isset($options['segment']) ? $configs[$options['segment']] : $configs['default'];    	       	    
    	    parent::__construct($arrParam, $config);
	    }
	}
	public function getData($name = 'all'){
	   
	    return $name == 'all' ? $this->_arrData : $this->_arrData[$name];
	}
	
}