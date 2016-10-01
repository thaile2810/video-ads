<?php
namespace Zmovies\Controller\Backend;

class ShortCode{
	
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
	        case 'add'		: $this->add(); break;
	        	
	        case 'edit'		: $this->edit(); break;
	        	
	        case 'delete'	: $this->delete(); break;
	        	
	        case 'sortitems': $this->sort_items(); break;
	        	
	        case 'active'	:
	        case 'inactive'	:
	                           $this->status(); break;
	            	
	        default			: $this->display(); break;
	    }
	
	}
	
	/*
	 * Hiển thị danh sách của các Items trong bảng
	 */
	public function display(){
	
	    global $zController;
	
	    if($zController->getParams('action') == -1){
	        $url = $this->createUrl();
	        wp_redirect($url);
	    }
	
	    $zController->getView('/short-code/display.php','/backend');
	}
	

	/*
	 * Thêm một Item mới vào trong bảng
	 */
	public function add(){
	    	
	    global $zController;
	
	    if($zController->isPost()){
	       // echo '<pre>';
	       //      print_r($zController->getParams());
	       // echo '</pre>';
	       // die();
	        $url = 'admin.php?page=' . $_REQUEST['page'];
	        if($zController->getParams('cancel') == 'Back'){
	            wp_redirect($url);
	        }else{
	            $validate = $zController->getValidate('ShortCode', array('prefix' => 'backend'));
	             
	            if($validate->isVaild()){
	                $zController->_error = $validate->getMessageErrors();
	                $zController->_data = $validate->getData();
	            }else{
	                $model = $zController->getModel('ShortCode');
	                $model->save_item($validate->getData());
	                $url .= $zController->getParams('save') == 'Save' ? '&action=' . $zController->getParams('action') . '&msg=1' : '&msg=1';
	                wp_redirect($url);
	            }
	        }
	         
	    }
	    $zController->getView('/short-code/data_form.php','/backend');
	}
	
	/*
	 * Sửa một Item đã tồn tại
	 */
	public function edit(){
	
	    global $zController;
	
	    $model = $zController->getModel('ShortCode');
	    if($zController->isPost()){
	        $url = 'admin.php?page=' . $_REQUEST['page'];
	        if($zController->getParams('cancel') == 'Back'){
	            wp_redirect($url);
	        }else{
	            $validate = $zController->getValidate('ShortCode', array('prefix' => 'backend'));
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
	    $zController->getView('/short-code/data_form.php','/backend');
	}
	
	/*
	 * Xóa Items trong bảng
	 */
	public function delete(){
	
	    global $zController;
	    $arrParam = $zController->getParams();
	
	    if(!is_array($arrParam['id'])){
	        $action 	= 'delete_id_' . $arrParam['id'];
	        check_admin_referer($action,'security_code');
	    }else{
	        wp_verify_nonce('_wpnonce');
	    }
	
	    $model = $zController->getModel('ShortCode');
	    $model->deleteItem($arrParam);
	
	    $paged = max(1,$arrParam['paged']);
	    $url = 'admin.php?page=' . $_REQUEST['page']. '&msg=1';
	    wp_redirect($url);
	}
	
	/*
	 * Thay đổi trạng thái của 
	 */
	public function status(){
	
	    global $zController;
	    $arrParam = $zController->getParams();
	
	    if(!is_array($arrParam['id'])){
	        $action 	= $arrParam['action'] . '_id_' . $arrParam['id'];
	        check_admin_referer($action,'security_code');
	    }else{
	        wp_verify_nonce('_wpnonce');
	    }
	
	    $model = $zController->getModel('ShortCode');
	    $model->changeStatus($arrParam);
	
	    $paged = max(1,$arrParam['paged']);
	    $url = 'admin.php?page=' . $_REQUEST['page'] . '&paged=' . $paged . '&msg=1';
	    wp_redirect($url);
	
	}
	
    /*
     * Sắp xếp các phần tử trong bảng
     */
	public function sort_items(){
	    global $zController;
	
	    if($zController->isPost()){
	        	
	        $ids = $zController->getParams('id');
	        if(count($ids)>0){
	            $model = $zController->getModel('ShortCode');
	            $model->sort_items($zController->getParams());
	        }
	        	
	        $url = 'admin.php?page=' . $_REQUEST['page'] . '&msg=1';
	        wp_redirect($url);
	    }
	
	}
	
	
	/*
	 * Tạo URL cho các trường hợp Filter
	 */
	public function createUrl(){
	    global $zController;
	
	    $url = 'admin.php?page=' . $zController->getParams('page');
	
	    //filter_status
	    if($zController->getParams('filter_status') != '0'){
	        $url .= '&filter_status=' . $zController->getParams('filter_status');
	    }
	
	    if(mb_strlen($zController->getParams('s'))){
	        $s = trim($zController->getParams('s'));
	        $url .= '&s=' . preg_replace('# +#', "+", $s);
	    }
	
	    return (string)$url;
	}
	
	

}