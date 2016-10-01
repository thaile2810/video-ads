<?php
namespace Zendvn;
class Bootstrap{
    
    private $_prefix;
    public $_params;
    public $_menu_slug;
    
    public function __construct($prefix){
        ob_start();
        $this->_prefix      = $prefix;
        $this->_menu_slug   = ZENDVN_PLUGIN_NAME;
        $this->$prefix ();
    }
    
    public function __call($action, $arguments){
        $this->loadPage();
    }
    
    public function frontend(){
        add_filter('template_include', array($this,'loadTemplate'));
    }
    public function backend(){
        
    }
    public function footer(){
        session_start();
        add_action('wp_footer', array($this,'loadFooter'));
    }
    public function loadTemplate($template_file){
            
        $controller     = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : 'index';
        $action         = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';
        $arrParam       = array(
                                '_menu_slug'    => $this->_menu_slug,
                                '_prefix'       => $this->_prefix,
                                '_controller'   => $controller,
                                '_action'       => $action,
                                );
        $this->_params      = array_merge($arrParam, $_REQUEST, $_FILES);
        
        self::loadPage();
        return '';
    }
    public function loadFooter(){
    
        $controller     = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : 'index';
        $action         = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';
        $arrParam       = array(
            '_menu_slug'    => $this->_menu_slug,
            '_prefix'       => $this->_prefix,
            '_controller'   => $controller,
            '_action'       => $action,
        );
        $this->_params      = array_merge($arrParam, $_REQUEST, $_FILES);
        self::loadPage();
        $this->call();
    }
    public function loadPage(){
        
        $action = preg_replace_callback('/-(.?)/', function($matches) {
            return ucfirst($matches[1]);
        }, $this->_params['_action']);
    
        $controller = preg_replace_callback('/-(.?)/', function($matches) {
            return ucfirst($matches[1]);
        }, $this->_params['_controller']);

        $controllerFile     = ZENDVN_CONTROLLER_PATH . DS . $this->_prefix . DS . ucfirst($controller) . 'Controller.php';
        if(file_exists($controllerFile)){
            require_once $controllerFile;
            $controllerClass    = ucfirst($controller) . 'Controller';
            $controllerObj      = new $controllerClass ($this->_params);
            $controllerObj->$action ();
        }else{
            echo '<br />Zendvn Plugin Error: ' . $controllerFile;
        }
    }
    
}