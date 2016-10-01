<?php
namespace Zmovies\Ext\ShortCodes;

class FanPage{
	
	private $_post;	
	
	private $_fbOption = array();
	
	public function __construct($options = array()){	    
	
		$this->_fbOption = [
		                  'data-href'                 => 'https://www.facebook.com/zendvngroup',
		                  'data-width'                => '100%',
		                  'data-small-header'         => "true",
		                  'data-adapt-container-width'=> "true",
		                  'data-hide-cover'           => "false",
		                  'data-show-facepile'        => "true",
		                  'data-show-posts'           => "false",
		                  ];
		add_shortcode('zvideos_fanpage', array($this,'show'));
	
	}
	
	public function show($atts){
		
	    $options = get_option(ZMOVIES_SETTING_OPTION);
	    
	    if(!empty($options['fb_link'])){
	        $this->_fbOption['data-href'] = $options['fb_link'];
	    }
		
		foreach ($this->_fbOption as $key => $val){
		    if(isset($atts[$key])){
		        $this->_fbOption[$key] = $atts[$key];
		    }
		}
		
		$fbAtts = '';
		foreach ($this->_fbOption as $key => $val){
		    $fbAtts .=  ' ' .  $key . '="' . $val . '" ';
		}
		
	    	
		$html = '<div class="fb-page"  ' . $fbAtts . ' ></div>';
		
		return $html;
	}

}
