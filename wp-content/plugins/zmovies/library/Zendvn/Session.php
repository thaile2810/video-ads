<?php
namespace Zendvn;
class Session{
    
    private $_key;
    public function __construct($key,$options = null){
        $this->_key = $key;
        $this->start();
    }
    
    private function start(){
        $version = phpversion();
        $version = (int)str_replace('.', '', $version);
        if($version >= '540'){
            if (session_status() == PHP_SESSION_NONE) {//PHP >= 5.4.0
                session_start();
            }
        }else{
            if(session_id() == '') {//PHP < 5.4.0
                session_start();
            }
        }
    }
    public function setValue($value = array()){
        $_SESSION['__ZENDVN_WP_CHAT'][$this->_key] = $value;
    }
    public function setExpirationSeconds($seconds){
        $_SESSION['__ZENDVN_WP_CHAT'][$this->_key]['ENT'] = time() + $seconds;
    }
    public function getValue($key = null){
        return $key == null ? $_SESSION[$this->_key] : $_SESSION[$key];
    }
    
    public function unsetAll($key = null){
        if($key == null){
            unset($_SESSION['__ZENDVN_WP_CHAT'][$this->_key]);
        }else{
            unset($_SESSION['__ZENDVN_WP_CHAT'][$key]);
        }
    }
}