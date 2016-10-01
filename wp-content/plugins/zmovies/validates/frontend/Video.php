<?php
/**

 */
namespace Zmovies\Frontend\Validate;
use Zendvn\Validator\Validate;

class Video extends Validate{	
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
	
	public function isVaild(){	    
	    if(!empty($this->_arrData['post_url'])){
	        global $zController;
	        $model = $zController->getModel('Video');
	        $post_url = $model->getMeta('url',$this->_arrData['post_url']);
	        if(!empty($post_url)){	           
	            $this->_arrError['post_url']     = __('Url is exist!',ZMOVIES_DOMAIN_LANGUAGE);
	            $this->_arrData['post_url']      = '';	           
	        }

	    } 
	   
	    $flag = false;
	    if(count($this->_arrError) > 0){
	        $flag = true;
	    }
	    return $flag; 
	}
}