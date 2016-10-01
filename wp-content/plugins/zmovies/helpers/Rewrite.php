<?php
namespace Zmovies\Helper;

class Rewrite{

	public $_options = array();

	public function __construct($options = array()){
		$this->_options = $options;
		add_filter('query_vars', array($this,'set'));
		add_action('init', array($this,'add_rules'));
		//add_filter( 'page_rewrite_rules', array($this,'rewrite_my_page') );
		//add_action('pre_get_posts', array($this,'add_rules'));
	}

	public function set($vars){


		$vars[] = "action";
		$vars[] = "eps";
		return $vars;

	}
	
	public function rewrite_my_page($rewrite_rule){
		
		return $rewrite_rule;
	}
	
	public function add_rules(){
		//wp_rewrite - wp_query - wp
		global $wp_rewrite, $wp_query,$zController;
		
		
		$regex	  = 'anime-movie/([^/]*)/?([^/]*)/?';
		$redirect = 'index.php?zmovies=$matches[1]&eps=$matches[2]';
		add_rewrite_rule($regex, $redirect,'top');
		
		
		flush_rewrite_rules(false);
	}

	public function rewrite_res_category(){
		//echo '<br/>' . $redirectUrl;

	}
}