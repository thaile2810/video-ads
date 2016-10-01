<?php
namespace Zmovies\Controller\Backend;

class AutoUpdate{
	
	public function __construct($options = null){		
	   global $zController;		  
	   $this->dispatch_function();	
	}
	
	public function dispatch_function(){
	    global $zController;
	    $action = $zController->getParams('action');
	    switch ($action){
	        default			: $this->display(); break;
	    }
	
	}
	
	public function display(){
	    global $zController;
	    if($zController->isPost()){
	        $data             = $zController->getParams(ZMOVIES_SETTING_OPTION . '-auto-update');	  
	        
 	        update_option(ZMOVIES_SETTING_OPTION . '-auto-update', $data);
 	        
	        $url = 'admin.php?page=' . $_REQUEST['page'] . '&msg=1';
	        wp_redirect($url);
	    }

	    $zController->getView('/auto-update/data_form.php','/backend');
	}
		
}