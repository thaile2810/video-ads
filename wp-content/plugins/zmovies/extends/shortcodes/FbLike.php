<?php
namespace Zmovies\Ext\ShortCodes;

class FbLike{
	
	private $_post;	
	
	private $_dataAttrs;	

	
	private $_fbOption = array();
	
	public function __construct($options = array()){	 
		$this->_fbOption = [
		                  'data-href'         => 'http://zend.vn',
		                  'data-layout'       => 'button_count',
		                  'data-action'       => 'like',
		                  'data-show-faces'   => 'false',
		                  'data-share'        => 'true',
 		                  ];

		add_shortcode('zvideos_fb_like', array($this,'show'));
		
	}
	
	public function show($atts){


        //echo '<br/>' . __METHOD__;		
        foreach ($this->_fbOption as $key => $val){
		    if(isset($atts[$key])){
		        $this->_fbOption[$key] = $atts[$key];
		    }
		}
		
		$fbAtts = '';
		
		foreach ($this->_fbOption as $key => $val){
		    $fbAtts .=  ' ' .  $key . '="' . $val . '" ';
		}
		$html = '<div class="fb-like fb_iframe_widget"  ' . $fbAtts . ' ></div>';
		
		return $html;
	}

	

}
