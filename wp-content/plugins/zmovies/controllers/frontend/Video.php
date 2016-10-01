<?php
namespace Zmovies\Controller\Frontend;

class Video{
	private $_dataDefault = array();
	
	public function __construct(){
		
        global $zController;		
        $action = $zController->getParams('action');				
		switch ($action){						
			default: $this->display(); break;
		}
	}
	
	public function display(){
		global $zController,$wp_rewrite,$wpdb;	
	    $zController->getView('video/display.php','/frontend' );
	}
	
	
}








