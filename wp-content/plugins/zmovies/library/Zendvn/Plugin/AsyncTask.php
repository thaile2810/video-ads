<?php
namespace Zendvn\Plugin;

class AsyncTask{

    public static $instance;

    private function __construct($pluginFile) {
        self::$instance = $this;
    }

    public static function getInstance($pluginFile){
        if (self::$instance === null) {
            self::$instance = new self($pluginFile);
        }
        return self::$instance;
    }

    static function execute($cmd){
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen('start /B '. $cmd, "r"));
        }else{
            exec($cmd . " > /dev/null &");
        }
    }
    
    static function doInBackground($url){
        /* if (PHP_SAPI !== 'cli') {
            die('Only use this controller from command line!');
        }
        ini_set('default_socket_timeout', -1);
        ini_set('max_execution_time', -1);
        ini_set('mysql.connect_timeout', -1);
        ini_set('memory_limit', -1);
        ini_set('output_buffering', 0);
        ini_set('zlib.output_compression', 0);
        ini_set('implicit_flush', 1); */
        //php -q htdocs/license/test.php
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen('start /B '. $url, "r"));
        }else{
            exec($url . " > /dev/null &");
        }
    }
    
    static function createUrl($url){
        
        return $url;        
    }
}

