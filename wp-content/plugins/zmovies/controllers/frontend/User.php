<?php
namespace Zmovies\Controller\Frontend;

class User{
	private $_dataDefault = array();
	
	public function __construct(){		
	   global $zController;		
		$action = $zController->getParams('action');				
		switch ($action){						
			default: $this->display(); break;
		}
	}
	
	
	public function display(){
		global $zController;	
		if($zController->isPost()){
		    $validate = $zController->getValidate('Video', array('prefix' => 'frontend'));		    
		    if($validate->isVaild()){
		        $zController->_error = $validate->getMessageErrors();
		        $zController->_data = $validate->getData();
		    }else{
		        $params = $zController->getParams();
		        $model  = $zController->getModel('Video');
		        $model->addVideo($params);	
		        $url .= get_permalink() . '?msg=1';
		        wp_redirect($url);
		    }
		    
		}
		$zController->getView('user/display.php','/frontend' );

	}
	
	
}








