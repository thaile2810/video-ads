<?php
namespace Zendvn;
class Controller{
	
    public $_params = array();
    public $_view;
    public $_namespace;
	
	public function __construct($params = null, $options = array()){
	    $this->_params = $params;
	    $this->_namespace = 'ss' . md5($this->_params['_prefix'] . $this->_params['_controller']);
	    $this->_view = View::getInstance($this->_params);
	}
	
	public function isPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
	}
	
	public function getParams($name = null){
	    return ($name == null || empty($name)) ? $this->_params : $this->_params[$name];
	}
	
	public function getModel($filename = '', $dir = 'Table'){

		$obj = new \stdClass();
	
	    $file =  ZENDVN_MODEL_PATH . DS . $dir . DS . $filename . '.php';
	
	    if(file_exists($file)){
	        require_once $file;
	        //$modelName = ZENDVN_PREFIX . $filename . '_Model';
	        $obj = new $filename ();
	    }
	    return $obj;
	}
	public function getTable($filename = '', $dir = 'Table'){
	
	    $obj = new \stdClass();
	
	    $file =  ZENDVN_MODEL_PATH . DS . $dir . DS . $filename . '.php';
	
	    if(file_exists($file)){
	        require_once $file;
	        //$modelName = ZENDVN_PREFIX . $filename . '_Model';
	        $obj = new $filename ();
	    }
	    return $obj;
	}
	
	public function getHelper($filename = '', $dir = ''){

		$obj = new \stdClass();
		
		$file =  ZENDVN_SP_HELPER_PATH . $dir . DS . $filename . '.php';
		
		if(file_exists($file)){
			require_once $file;
			$helperName = ZENDVN_PREFIX . $filename . '_Helper';
			$obj = new $helperName ();
		}
		return $obj;
	}
	
	public function setView($view = null){
	    $this->_view->setView($view);
	}
	
	public function getValidator($filename = ''){

		$obj = new \stdClass();
		
		$file =  ZENDVN_FORM_PATH  . DS . $this->_params['_prefix'] . DS . $filename . '.php';
		
		if(file_exists($file)){
			require_once $file;
			$validateName = 'Zendvn\\' . $this->_params['_prefix'] . '\Form\\' . $filename;
			$obj = new $validateName ($this->_params);
		}
		return $obj;
	}
	
	public function getCssUrl($filename = '', $dir = ''){
		
		$url = ZENDVN_CSS_URL . $dir . '/' . $filename;
		
		$headers = @get_headers($url);
		$flag = stripos($headers[0], "200 OK")?true:false;
		
		if($flag == true){
			return $url;
		}
		
		return false;
	}
	
	public function getImageUrl($filename = '', $dir = ''){

		$url = ZENDVN_IMAGE_URL . $dir . '/' . $filename;
		
		$headers = @get_headers($url);
		$flag = stripos($headers[0], "200 OK")?true:false;
		
		if($flag == true){
			return $url;
		}
		
		return false;
	}
	
	public function getJsUrl($filename = '', $dir = ''){

		$url = ZENDVN_JS_URL . $dir . '/' . $filename;
		
		$headers = @get_headers($url);
		$flag = stripos($headers[0], "200 OK")?true:false;
		
		if($flag == true){
			return $url;
		}
		
		return false;
	}
	
	
}