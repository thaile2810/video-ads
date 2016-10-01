<?php
namespace Zendvn\View\Helper;

class PaginationControl{
    public static function create($params, $view){
        echo __METHOD__;
        echo '<pre>';
            print_r($params);
        echo '</pre>';
        
    }
    
}