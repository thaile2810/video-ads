<?php
namespace Zmovies\Ext\ShortCodes;

class SearchBar{
	
	private $_post;
	public function __construct($options = array()){
	    global $zController;
	    
	    add_action('wp_enqueue_scripts', array($this,'js'));
		add_shortcode('zmovie_search_bar', array($this,'show'));
		//$zController->ajax($this, 'searchAjax');
	}
	
	public function show($atts){
		/* echo '<pre>';
		print_r($atts);
		echo '</pre>'; */
		global $zController,$wpdb, $post;
		if(!empty($atts['width'])) $style ='width: ' . $atts['width'] . ';';
	    $html = '<form method="get" class="form-search hidden-xs hidden-sm" 
	                   action="' . get_site_url() . '/" id="zmovie_search_bar" style="' . $style . '">
                	  <div class="form-group">
		<div class="input-group">
	  		<span class="screen-reader-text">Search for:</span>
	    	<input type="text" class="form-control search-query zmovie_search_input" placeholder="Enter keyworks here..." value="" name="s" autocomplete="off">
	    	<span class="input-group-btn">
	      		<button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="Search"><span class="glyphicon glyphicon-search"></span></button>
	    	</span>
	    </div>
	</div>
	                <div class="show-results"></div>
                </form>';	
		
		return $html;
	}
	
	public function js(){
	    global $zController;
	    
	    wp_register_script('zmovies_search_bar', $zController->getJsUrl('search_bar.js'), array('jquery'),'1.0',true);
	    wp_enqueue_script('zmovies_search_bar');
	}
	


}
