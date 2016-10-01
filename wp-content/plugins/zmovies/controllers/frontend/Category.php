<?php
namespace Zmovies\Controller\Frontend;

class Category{
	private $_dataDefault = array();
	
	public function __construct(){
		
		$this->dispath_function();	
	}
	
	public function dispath_function(){
		global $zController;
		
		$action = $zController->getParams('action');
				
		switch ($action){			
			
			default: $this->display(); break;
		}
	}
	
	public function display(){
		global $zController,$wp_rewrite,$wpdb;
		
		$zController->getView('category/display.php','/frontend' );
		
		
	}
	
	
}








