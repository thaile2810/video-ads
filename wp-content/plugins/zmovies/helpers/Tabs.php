<?php
namespace Zmovies\Helper;

class Tabs{

	public $_options = array();
	
	public $_settings = array();
		
	public function __construct($options = array() ){
	    $this->_options = $options;
		$this->_settings = get_option('zmovies_settings',array());
		
	}

	public function showTabs($params = null){
        $name_tabs = $this->_settings['name_tabs'];
        $link_tabs = $this->_settings['link_tabs'];
        
        $html = '';
        foreach ($name_tabs as $key => $val){
	       $html .='<a class="btn btn-default btn-xs" href="' . $link_tabs[$key] . '" role="button">' . $val . '</a>';
        }
        
        return $html;
	    
	}

}