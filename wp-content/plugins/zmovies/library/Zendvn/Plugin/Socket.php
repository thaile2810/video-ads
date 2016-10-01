<?php
namespace Zendvn\Plugin;

class Socket{

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

    static function sendRequest($hostname, $port = 80, $path){
        set_time_limit(1);
        $fp = fsockopen($hostname, $port, $errno, $errstr);

        if(!$fp){
            $filename = ZMOVIES_FILE_PATH . DS . 'logs' . DS . 'socket-error.log';
            file_put_contents($filename, date('Y-m-d H:i:s') . "\n", FILE_APPEND | LOCK_EX);
        }else{
            
//             $filename = ZMOVIES_FILE_PATH . DS . 'logs' . DS . 'socket-access.log';
//             file_put_contents($filename, date('Y-m-d H:i:s') . "\n", FILE_APPEND | LOCK_EX);
            
            $out = "GET /" . $path . " HTTP/1.1\r\n";
            $out .= "Host: " . $hostname . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            /* while (!feof($fp)) {
             echo fgets($fp, 128);
            } */
            fclose($fp);
            return true;
        }
    }
    
    public function cli(){
        if (PHP_SAPI !== 'cli') {
            die('Only use this controller from command line!');
        }
        ini_set('default_socket_timeout', -1);
        ini_set('max_execution_time', -1);
        ini_set('mysql.connect_timeout', -1);
        ini_set('memory_limit', -1);
        ini_set('output_buffering', 0);
        ini_set('zlib.output_compression', 0);
        ini_set('implicit_flush', 1);
    }
}

