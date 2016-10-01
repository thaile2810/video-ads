<?php
namespace Zendvn\View\Helper;
class DataRadio{

    
    private static $_instance;
    private $_option;
    
    private $_default;
    private $_table;
    private $_description;
    
    private $_data;
    private $_sql = null;
    
    public static function getInstance($option = null){
        if( null == self::$_instance ) {
            self::$_instance = new DataRadio($option);
        }
        return self::$_instance;
    }
    private function __construct($option = null){
        self::setOption($option);
        self::buildSql();
    }
    /*
     * Options Array Structure
     * $options = array( 'default'	    => array('your_key'=>'your_value'),
     * 					 'table' 	    => 'table_name',
     * 					 'key'		    => 'field_name',
     * 					 'value'	    => 'field_name',	 *
     * 					 'description'	=> 'field_name',	 
     * 					 'condition'	=> ''
     * 					);
     */
    private function setOption($option = null){
        $this->_option = $option;
    }
    private function getOption($key = null){
        return $key == null ? $this->_option : $this->_option[$key];
    }
    private function buildSql(){
        if(isset($this->_option) && is_array($this->_option)){
            if(isset($this->_option['default'])){
            
            }
            
            $this->_sql = 'SELECT ' . $this->_option['key'] . ',' . $this->_option['value'];
            
            if(isset($this->_option['description'])){
                $this->_sql .= ',' . $this->_option['description'];
            }
            
            $this->_sql .= ' FROM ' . $this->_option['table'];
            
            if(isset($this->_option['condition'])){
                // Nothing todo
                //$sql .= ' WHERE ' . $this->_option['condition'];
            }
        }
    }
    public function getData(){
        global $wpdb;
        $result = $wpdb->get_results($this->_sql, ARRAY_A);
        echo __METHOD__;
        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }
}
