<?php

namespace Zendvn\Inc;

class Controller{
	
	public $_error = array();
	
	public $_data = array();
	
	public $_rewriteSlug = array();
	
	public function __construct($options = array()){
		
	}
	
	public function getLanguageDomain(){
	    return ZMOVIES_DOMAIN_LANGUAGE;
	}
	
	public function ajax($obj, $func){	    
	    
	    if(is_admin() == 1){	    
	       add_action('wp_ajax_' . $func, array($obj,$func));
	    }else{
	        add_action('wp_head',array($this,'add_ajax_library'));
	        add_action('wp_ajax_' . $func,array($obj,$func));
	        add_action('wp_ajax_nopriv_' . $func, array($obj,$func));
	    }
	}
	
	public function add_ajax_library(){
	
	    $ajax_nonce = wp_create_nonce('ajax-security-code');
	
	    $html = '<script type="text/javascript">';
	    $html .= ' var ajaxurl = "' . admin_url('admin-ajax.php') . '"; ';
	    $html .= ' var security_code = "' . $ajax_nonce . '"; ';
	    $html .= '</script>';
	
	    echo $html;
	}
	
	public function getMenuSlug(){
		return ZMOVIES_MENU_SLUG;
	}
	
	public function setRewriteSlug($args = array()){
		$this->_rewriteSlug = $args;
	}
	public function isPost(){
		$flag = ($_SERVER['REQUEST_METHOD']=='POST')?true:false;
		return $flag;
	}
	
	public function getParams($name = null){
		
	    //$_file_upload = $_FILES; 
		if($name == null || empty($name)){
		    $arr = $_REQUEST;
		    $arr['_file_upload'] = $_FILES;
			return $arr;
		}else{
			$val = (isset($_REQUEST[$name]))?$_REQUEST[$name]:'';
			return $val;
		}
	}
	
	public function getConfig($filename = '', $dir = '',$params = array()){
	
		$obj = new \stdClass();
	
		$file =  realpath(ZMOVIES_CONFIG_PATH . $dir . DS . $filename . '.php');
	
		if(file_exists($file)){
			require_once $file;
			$controllerName = "\Zmovies\Configs\\{$filename}";
			$obj = new $controllerName ($params);
		}
		return $obj;
	}
	
	public function getController($filename = '', $dir = '', $params = array()){
		
		$obj = new \stdClass();
		
		$file =  realpath(ZMOVIES_CONTROLLER_PATH . $dir . DS . $filename . '.php');
		
		if($dir == 'backend'){
			
		}else if($dir == 'frontend'){
			
		}
		if(file_exists($file)){
			require_once $file;
			if($dir == '/backend'){
				$controllerName = "\Zmovies\Controller\Backend\\{$filename}";
			}else if($dir == '/frontend'){
				$controllerName = "\Zmovies\Controller\Frontend\\{$filename}";
			}
			$obj = new $controllerName($params);
		}
		return $obj;
	}
	

	public function getWidget($filename = '', $dir = '', $params = array()){
	
		$obj = new \stdClass();
	
		$file =  realpath(ZMOVIES_WIDGET_PATH . $dir . DS . $filename . '.php');
	
		if(file_exists($file)){
			require_once $file;
			$widget = "\Zmovies\Ext\Widget\\{$filename}";
			
			
			return $widget;
			//$obj = new $widget($params);
		}
		//return $widget;
	}
	

	public function getShortCode($filename = '', $dir = '', $params = array()){
	
		$obj = new \stdClass();
	
		$file =  realpath(ZMOVIES_SHORTCODE_PATH . $dir . DS . $filename . '.php');
		
		if(file_exists($file)){
			require_once $file;			
			$shortCodes = "\Zmovies\Ext\ShortCodes\\{$filename}";			
			$obj = new $shortCodes($params);
		}
		return $obj;
	}
	
	
	public function getModel($filename = '', $dir = '', $params = array()){

		$obj = new \stdClass();
		
		$file =  realpath(ZMOVIES_MODELS_PATH . $dir . DS . $filename . '.php');
		if(file_exists($file)){
			require_once $file;
			$modelName = "\Zmovies\Models\\{$filename}";
			$obj = new $modelName($params);
		}
		return $obj;
	}
	
	public function getHelper($filename = '', $dir = '', $params = array()){

		$obj = new \stdClass();
		
		$file =  realpath(ZMOVIES_HELPER_PATH . $dir . DS . $filename . '.php');
		
		if(file_exists($file)){
			require_once $file;
			$helperName = "\Zmovies\Helper\\{$filename}";
			$obj = new $helperName ($params);
		}
		return $obj;
	}
	
	public function getView($filename = '', $dir = ''){
		
		$file =  realpath(ZMOVIES_TEMPLATE_PATH . $dir . DS . $filename);
		
		if(file_exists($file)){
			require $file;		
		}
	}
	
	public function getValidate($filename = '', $options = array()){

		$obj                  = new \stdClass();
		$options['prefix']    = isset($options['prefix']) ? $options['prefix'] : 'backend';
		$file                 =  realpath(ZMOVIES_VALIDATE_PATH . DS . $options['prefix'] . DS . $filename . '.php');
		
		if(file_exists($file)){
			require_once $file;
			$validateName        =  'Zmovies\\' . ucfirst($options['prefix']) . '\Validate\\' . $filename;
			$options['filename'] = $filename;
			$obj                 = new $validateName ($this->getParams(), $options);
		}
		return $obj;
	}
	
	public function getCssUrl($filename = '', $dir = ''){
		
		if(file_exists(ZMOVIES_PUBLIC_PATH . DS . 'css' . DS . $dir . DS . $filename)){
			return ZMOVIES_CSS_URL . $dir . '/' . $filename;
		}
		return false;
	}
	
	public function getImageUrl($filename = '', $dir = ''){

		if(file_exists(ZMOVIES_PUBLIC_PATH . DS . 'images' . DS . $dir . DS . $filename)){
			return ZMOVIES_IMAGES_URL . $dir . '/' . $filename;
		}
		
		return false;
	}
	
	public function getJsUrl($filename = '', $dir = ''){

		if(file_exists(ZMOVIES_PUBLIC_PATH . DS . 'js' . DS . $dir . DS . $filename)){
			return ZMOVIES_JS_URL . $dir . '/' . $filename;
		}
		return false;
	}
}