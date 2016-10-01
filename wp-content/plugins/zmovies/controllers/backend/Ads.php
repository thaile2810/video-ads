<?php
namespace Zmovies\Controller\Backend;

class Ads{
	
	public function __construct($options = null){		
	   global $zController;		  
	   $this->dispatch_function();	
	}
	
	public function dispatch_function(){
	    global $zController;
	    $action = $zController->getParams('action');
	    switch ($action){
	        case 'add'		: $this->add();        break;	    
	        case 'edit'		: $this->edit();       break;	    
	        case 'delete'	: $this->delete();     break;	    
	        case 'sortitems': $this->sort_items(); break;	    
	        case 'active'	:
	        case 'inactive'	:
	            $this->status(); break;
	    
	        default			: $this->display(); break;
	    }
	}
	
	public function createUrl(){
	    global $zController;
	    $url = 'admin.php?page=' . $zController->getParams('page');
	    
	    //filter_status
	    if($zController->getParams('filter_status') != '0'){
	        $url .= '&filter_status=' . $zController->getParams('filter_status');
	    }
	    
	    if($zController->getParams('filter_area') != '0'){
	        $url .= '&filter_area=' . $zController->getParams('filter_area');
	    }
	    
	    if(mb_strlen($zController->getParams('s'))){
	        $url .= '&s=' . str_replace(' ', '+', $zController->getParams('s'));
	    }
	    return $url;
	}
	
	/*
	 * Hiển thị danh sách của các Items trong bảng
	 */
	public function display(){
	
	    global $zController;	
	    if($zController->getParams('action') == -1 || $zController->isPost()){
	        $url = $this->createUrl();
	        wp_redirect($url);
	    }
	    $zController->getView('/ads/display.php','/backend');
	}
	
	/*
	 * Thêm một Item mới vào trong bảng
	 */
	public function add(){	
	    global $zController;	
	    if($zController->isPost()){	
	        $url = 'admin.php?page=' . $_REQUEST['page'];
	        if($zController->getParams('cancel') == 'Back'){
	            wp_redirect($url);
	        }else{
	            $validate = $zController->getValidate('Ads', array('prefix' => 'backend'));		           
	            if($validate->isVaild()){
	                $zController->_error = $validate->getMessageErrors();
	                $zController->_data = $validate->getData();
	            }else{
	                $model = $zController->getModel('Ads');
	                $model->save_item($validate->getData());
	                $url .= $zController->getParams('save') == 'Save' ? '&action=' . $zController->getParams('action') . '&msg=1' : '&msg=1';
	                wp_redirect($url);
	            }
	        }	
	    }
	    $zController->getView('/ads/data_form.php','/backend');
	}
	
	public function edit(){	
	    global $zController;	    	
	    $model = $zController->getModel('Ads');
	    if($zController->isPost()){
	        $url = 'admin.php?page=' . $_REQUEST['page'];
	        if($zController->getParams('cancel') == 'Back'){
	            wp_redirect($url);
	        }else{
	            $validate = $zController->getValidate('Ads', array('prefix' => 'backend'));
	            if($validate->isVaild()){
	                $zController->_error = $validate->getMessageErrors();
	                $zController->_data = $validate->getData();
	            }else{
	                $model->save_item($validate->getData());	               
	                $url .= $zController->getParams('save') == 'Save' ? '&action=' . $zController->getParams('action') . '&id=' . $zController->getParams('id') . '&msg=1' : '&msg=1';
	                wp_redirect($url);
	            }
	        }
	    }else{
	        $zController->_data = $model->getItem($zController->getParams());
	    }
	    $zController->getView('/ads/data_form.php','/backend');
	}
	
	public function delete(){
	    global $zController;
	    $arrParam = $zController->getParams();
	    $paged = max(1,$arrParam['paged']);
	    if(isset($arrParam['id'])){
	        if(!is_array($arrParam['id'])){
	            $action 	= 'delete_id_' . $arrParam['id'];
	            check_admin_referer($action,'security_code');
	        }else{
	            wp_verify_nonce('_wpnonce');
	        }
	        $model = $zController->getModel('Ads');
	        $model->deleteItem($arrParam);
	        
	        $url = 'admin.php?page=' . $_REQUEST['page']. '&msg=1';
	    }else{
	        $url = 'admin.php?page=' . $_REQUEST['page'];
	    }	    	    	        
	    wp_redirect($url);
	}
	

	public function status(){
	
	    global $zController;
	    $arrParam = $zController->getParams();	
	    $paged = max(1,$arrParam['paged']);
	    if(isset($arrParam['id'])){
	        if(!is_array($arrParam['id'])){
	            $action 	= $arrParam['action'] . '_id_' . $arrParam['id'];
	            check_admin_referer($action,'security_code');
	        }else{
	            wp_verify_nonce('_wpnonce');
	        }
	        $model = $zController->getModel('Ads');
	        $model->changeStatus($arrParam);
	       
	        $url = 'admin.php?page=' . $_REQUEST['page'] . '&paged=' . $paged . '&msg=1';
	    }else{
	        $url = 'admin.php?page=' . $_REQUEST['page'] . '&paged=' . $paged;
	    }
	   
	   
 	    wp_redirect($url);
	
	}
	

}