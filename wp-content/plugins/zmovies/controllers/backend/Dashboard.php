<?php
namespace Zmovies\Controller\Backend;

class Dashboard{
	
	public function __construct($options = null){		
        global $zController;		  
        $action = $zController->getParams('action');
	    switch ($action){
	        default			: $this->display(); break;
	    }
	}
	
	public function display(){
	
	    global $zController;	
	    if($zController->getParams('action') == -1 || $zController->isPost()){
	        $url = $this->createUrl();
	        wp_redirect($url);
	    }
	    $zController->getView('/dashbroad/display.php','/backend');
	}
	

}