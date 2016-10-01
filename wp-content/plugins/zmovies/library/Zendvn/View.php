<?php
namespace Zendvn;
class View{
    public $_params;
    /**
     * A reference to an instance of this class.
     */
    private static $instance;
    /**
     * Returns an instance of this class.
     */
    public static function getInstance($params) {
    
        if( null == self::$instance ) {
            self::$instance = new View($params);
        }
        return self::$instance;
    }
    /**
     * Initializes
     */

    public function __construct($params){
        $this->_params = $params;
    }
    
    public function __call($helper, $arguments){
        $name       = @$arguments[0];
        $value      = @$arguments[1];
        $attr       = @$arguments[2];
        $options    = @$arguments[3];
        $helper     = __NAMESPACE__ . '\View\Helper\\' . ucfirst($helper);
        return $helper::create($name, $value, $attr, $options,$this);
    }
    public function setParams($params = null){
        $this->_params = $params;
    }
    public function setView($view = null){
        $view = $view == null ? $this->_params['_action'] : $view;
        $file =  ZENDVN_TEMPLATE_PATH . DS . $this->_params['_prefix'] . DS . $this->_params['_controller'] . DS . $view . '.php';
        if(file_exists($file)){
            require_once $file;
        }
    }
    
    public function render($fileName, $dir = 'render'){
        $file = ZENDVN_TEMPLATE_PATH . DS . $this->_params['_prefix'] . DS
                . $this->_params['_controller'] . DS . $dir . DS . $fileName;
        if(file_exists($file)){
            return require_once $file;
        }
         
    }
    
    public function partial($fileName, $dir = null){
        $file = ZENDVN_TEMPLATE_PATH . DS . $this->_params['_prefix'] .
        DS . $this->_params['_controller'] . DS . $dir . DS . $fileName;
        if(file_exists($file)){
            require_once $file;
        }
    }
    public function addFileCss($handle){
        wp_enqueue_style($handle);
    }
    public function setJs($handle){
        wp_enqueue_script($handle);
    }
    public function url($params = null){
       $url     = site_url() . '/';
       $flag    = false;
       if(isset($_REQUEST['page_id'])){
           $url .= '?page_id=' . $_REQUEST['page_id'];
           $flag = true;
       }else{
           $pagename = get_query_var('pagename');
           $url .= $pagename;
       }
       if(!empty($params)){
           foreach ($params as $k => $v){
               $url .= $flag ? '&' : '?';
               $url .= $k . '=' . $v;
               $flag = true;
           }
       }
       return $url;
    }
    public function cmsUrl($params = null){
        $url     = site_url() . '/';
        $flag    = false;
        if(!empty($params)){
            foreach ($params as $k => $v){
                $url .= $flag ? '&' : '?';
                $url .= $k . '=' . $v;
                $flag = true;
            }
        }
        return $url;
    }
}