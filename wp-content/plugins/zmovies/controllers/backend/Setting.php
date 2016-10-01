<?php
namespace Zmovies\Controller\Backend;

class Setting{
	
	public function __construct($options = null){
		
		global $zController;		
		$this->dispatch_function();
	
	}
	
	/*
	 * Hàm điều hướng các hành động trong Controller
	 */
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

            $params = $zController->getParams(ZMOVIES_SETTING_OPTION);
            $params['posts_per_page'] = $zController->getParams('posts_per_page');
            
            $params['offline']['content'] = htmlentities(stripslashes($params['offline']['content']), ENT_COMPAT, "UTF-8");
            $params['author']['content'] = htmlentities(stripslashes($params['offline']['content']), ENT_COMPAT, "UTF-8");

            update_option(ZMOVIES_SETTING_OPTION, $params);

            update_option('posts_per_page', $params['posts_per_page']);

	        $url = 'admin.php?page=' . $_REQUEST['page'] . '&msg=1';
	        
            wp_redirect($url);
	    }
        $zController->_data = get_option(ZMOVIES_SETTING_OPTION,[]);
        $zController->_data['posts_per_page'] = get_option('posts_per_page');
	    $zController->getView('/setting/data_form.php','/backend');
	}
	

	

}