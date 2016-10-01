<?php
namespace Zmovies\Helper;

class JwPlayer{

	private $_options   = [];
	
	private $_settings  = [];
	
	private $_key;
		
	public function __construct($options = array() ){
	    $this->_key    = ZMOVIES_SETTING_OPTION . '-player';
	    $this->_options = $options;
	    $this->getSettingOption();
		
	}
	
	public function getSettingOption(){
	    $this->_settings = get_option($this->_key, [
                                        	        'width'        => 640,
                                        	        'height'       => 480,
                                        	        'show_logo'    => 0,
                                        	        'auto_play'    => 0,
                                        	       ]);
	    
	}
	
	public function play($post_id){
	    
	    if(!$post_id) return;
	    
	    global $zController;
	    
	    $width         = $this->_settings['width'] ? intval($this->_settings['width']) . 'px' : '640px';
	    $height        = $this->_settings['height'] ? intval($this->_settings['height']) . 'px' : '480px';
	    
	    $modelVideo    = $zController->getModel('Video');
	    $videoUrl      = get_post_meta($post_id, $modelVideo->metaKey('url') ,true);
	    $videoId       = $modelVideo->url2Id($videoUrl);
	    
	    if(!$this->_settings['show_logo']){
	        //echo $videoUrl = 'https://www.youtube.com/embed/' . $videoId . '?modestbranding=1';
	    }
	    $imgPath       = ZMOVIES_FILE_PATH . DS . 'video' . DS . 'thumb' . $videoId . DS . 'default.jpg';
	    $imgUrl        = ZMOVIES_IMAGES_URL . '/images/anime-movies2.jpg';
	    if(file_exists($imgPath)){
	        $imgUrl      = ZMOVIES_FILE_URL . '/video/thumb' . $videoId . '/sddefault.jpg';
	    }
	    
	    
	    $xhtml = '<script type="text/javascript">(function($){
                	$(\'a[data-eps="s0-m0"]\').addClass("movie-view");
                	var playerInstance = jwplayer("player");
                	playerInstance
                			.setup({
                				file : "' . $videoUrl . '",
                				image : "' . $imgUrl . '",
                				width : "' . $width . '",
                				height: "' . $height . '", 
                				type  : "mp4",
                    			});
                    })(jQuery);</script>';
	    
	    echo $xhtml;
	}
}

