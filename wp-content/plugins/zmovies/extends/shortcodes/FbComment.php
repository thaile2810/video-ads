<?php
namespace Zmovies\Ext\ShortCodes;

class FbComment{
	
	private $_post;
	
	private $_fbAPI = null;
	
	private $_fbOption = array();
	
	public function __construct($options = array()){
	    
		$this->_fbAPI = $options;
		$this->_fbOption = [
		                  //'data-colorscheme'  => 'dark',
		                  'data-href'         => 'http://developers.facebook.com/docs/plugins/comments/',
		                  'data-numposts'     => 10,
		                  'data-order-by'     => 'social',
		                  'data-width'        => '100%',
		              ];
		add_shortcode('zvideo_fb_comment', array($this,'show'));
		add_action('wp_footer',array($this,'fb_js_sdk'));
		add_action('wp_head',array($this,'fb_app_id_meta'));
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
	    	
		$html = '<div class="fb-comments" ' . $fbAtts . ' ></div>';
		
		return $html;
	}
	
	public function fb_app_id_meta(){
	    echo '<meta property="fb:app_id" content="'. $this->_fbAPI .'"/>';
	}
	
	public function fb_js_sdk(){
	    
	    $html = "<div id=\"fb-root\"></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = \"//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=" . $this->_fbAPI . "\";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>";
	    echo $html;
	}

}
