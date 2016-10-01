<?php
namespace Zendvn\View\Helper;
class FormLabel{
	
	/*
	 * $name 	: Tên của phần tử button
	 * $attr 	: Các thuộc tính của phần tử button 
	 * 		   	  id - style - width - class - value ...
	 * $options	: Các phần sẽ bổ xung khi phát sinh trường hợp mới
	 * 			  [type]: button - submit - reset
	 */
	
	public static function create($value = '', $attr = array(), $options = null){
	
		$strAttr = '';
        if(count($attr)> 0){
            foreach ($attr as $key => $val){
                if($key != "type" && $key != 'value'){
                    $strAttr .= ' ' . $key . '="' . $val . '" ';
                }
            }
        }
        return '<label ' . $strAttr . ' >' . $value . ':</label>';
	}

}
