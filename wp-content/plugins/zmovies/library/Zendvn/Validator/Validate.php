<?php
namespace Zendvn\Validator;

use Zend\Validator;
class Validate{
    
    protected  $_arrError;
    protected $_arrData;
    
    public function __construct($arrData,$config){
        $this->_arrData = $arrData;
        if(is_array($config) && count($config) > 0){
            foreach ($config as $key => $val){
                if(isset($this->_arrData[$key])  && isset($config[$key])) $this->process($key, $this->_arrData[$key], $val);
            }
        }
    }
	private function process($key,$value,$validate){
	    
	    $validator	= new Validator\ValidatorChain();
	    $flag = false;
	    if($validate['type'] == 'file'){
	        if($validate['required']){
	            $flag = true;
	        }elseif(isset($value['name']) && (!empty($value['name']) || $value['name'] != '')){
	            $flag = true;
	        }else{
	            $this->_arrData[$key] = '';
	        }
	    }else{
	        if($validate['required']){
	            $flag = true;
	        }elseif(!empty($value) || $value != ''){
	            $flag = true;
	        }
	    }
	    if($flag){
    	    foreach ($validate['validators'] as $val){
    	        $class = $val['name'];
    	        if(isset($val['options']) && is_array($val['options'])){
    	            $validator->attach(new $class($val['options']));
    	        }else{
    	            $validator->attach(new $class());
    	        }
    	    }
    	    if (!$validator->isValid($value)) {
    	        $messages                  = $validator->getMessages();
    	        $this->_arrError[$key]     = current($messages);
    	        $this->_arrData[$key]      = '';
    	    }
	    }
	}
	public function isVaild(){
	    $flag = false;
	    if(count($this->_arrError) > 0){
	        $flag = true;
	    }
	    return $flag;
	}
	
	public function getMessageErrors(){
	    return $this->_arrError;
	}
	public function getData(){
	    return $this->_arrData;
	}
}