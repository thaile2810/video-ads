<?php
namespace Zmovies\Controller\Frontend;

class Product{
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
		if(get_query_var('eps')!=''){
		    $zController->getView('product/view.php','/frontend' );
		}else{
		  $zController->getView('product/display.php','/frontend' );
		}
		
		
	}
	
	
}








