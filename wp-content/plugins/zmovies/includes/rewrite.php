<?php
class Zendvn_Mp_Rewrite{
	
	public function __construct($options = array()){
		//echo '<br/>' . __METHOD__;
		
		add_action('init', array($this,'add_rules'));
		add_action('init', array($this,'add_tags_rule'));
		add_action('init', array($this,'change_author_permalinks'));
		add_filter('query_vars', array($this,'insert_query_vars'));
		
		register_deactivation_hook($options['file'], array($this,'plugin_deactivation'));
	}
	
	public function plugin_deactivation(){
		flush_rewrite_rules(false);
	}
	
	public function change_author_permalinks(){
		global $wp_rewrite;
		
		///author/%author%
		$wp_rewrite->author_structure = '/tac-gia/%author%';
		flush_rewrite_rules(false);
		
	}
	
	public function add_tags_rule(){
		add_rewrite_tag('%zproduct%', '([^/]+)');
		add_permastruct('zproduct', 'book-detail/%zproduct%');
		
		add_rewrite_tag('%book-category%', '([^/]+)');
		add_permastruct('book-category', 'chuyen-de/%book-category%');
		flush_rewrite_rules(false);
	}
	
	public function add_rules(){
		//wp_rewrite - wp_query - wp

		$regex		= '([^/]*)/page/?([^/]*)/?'; ///articles/page/2/
		$redirect = 'index.php?pagename=$matches[1]&paged=$matches[2]';//slug
		add_rewrite_rule($regex, $redirect,'top');
		
		$regex		= '([^/]*)/?([^/]*)/?';
		$redirect = 'index.php?pagename=$matches[1]&article=$matches[2]';//slug
		add_rewrite_rule($regex, $redirect,'top');
		
		flush_rewrite_rules(false);
	}
	
	public function insert_query_vars($vars){
		$vars[] = 'article';
		return $vars;
	}
}