<?php
namespace Zmovies\Helper;
class CreatePage{
	
	private $_templatePage;
	
	public function __construct(){		
		add_filter('page_attributes_dropdown_pages_args', array($this,'register_template'));		
		add_filter('wp_insert_post_data', array($this,'register_template'));		
		$this->_templatePage = array(
                'user.php' 	    => __('Add Video', ZMOVIES_DOMAIN_LANGUAGE),		            
        );	
	}
	
	public function register_template($attrs){
		//echo '<br/>' . __METHOD__;
		
		$cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());
		
		$templates = wp_get_theme()->get_page_templates();
		
		$templates = $this->remove_templates($templates);
		
		$templates = array_merge($templates,$this->_templatePage);
		
		wp_cache_delete($cache_key,'themes');
		
		wp_cache_add($cache_key, $templates,'themes', 1800);
		
		return $attrs;
	}
	
	private function remove_templates($templates){
		$remove = array(
					'event-template.php',
					);
		
		foreach ($templates as $key => $val){
			if (in_array($key, $remove)) {
				unset($templates[$key]);
			}
		}
		
		return $templates;
	}
}
  