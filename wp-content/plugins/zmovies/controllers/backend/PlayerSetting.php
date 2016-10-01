<?php
namespace Zmovies\Controller\Backend;

class PlayerSetting{
	
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
	
	/*
	 * Hiển thị danh sách của các Items trong bảng
	 */
	public function display(){	
	    global $zController;	
	    if($zController->isPost()){	    
	        
	        $key = ZMOVIES_SETTING_OPTION . '-player';
	        
	        $validate = $zController->getValidate('PlayerSetting', array('prefix' => 'backend'));
	        if($validate->isVaild()){
	            $zController->_error = $validate->getMessageErrors();
	            $zController->_data = $validate->getData();
	        }else{
	            update_option($key, $zController->getParams($key));
	            $url = 'admin.php?page=' . $_REQUEST['page'] . '&msg=1';
	            wp_redirect($url);
	        }  
	    }	
	    $zController->getView('/player-setting/data_form.php','/backend');
	}
}













