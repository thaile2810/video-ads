<?php
namespace Zmovies\Helper;

class Settings{

	public $_options = array();
	
	public $_settings = array();
		
	public function __construct($options = array() ){
	    $this->_options = $options;
		$this->_settings = get_option(ZMOVIES_SETTING_OPTION,array());
		
	}

	public function getAds($position = null){
	    if($position == null || !isset($this->_settings[$position])) return false;
	    
	    return str_replace("\\", "", $this->_settings[$position]);
	}
	
	public function getSliders($name = null){
	    return @$this->_settings['sliders'];
	    
	}
	
	public function getFbAPI(){
	    return @$this->_settings['facebook']['app-id'];
	}
	
	public function checkTabs(){
	    $flag = false;
	    
	    if(count(@$this->_settings['name_tabs'])>0) $flag = true;
	    
	    return $flag;
	}
}