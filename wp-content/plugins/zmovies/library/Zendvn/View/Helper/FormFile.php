<?php
namespace Zendvn\View\Helper;
class FormFile{
	
	/*
	 * $name 	: Tên của phần tử Fileupload
	 * $attr 	: Các thuộc tính của phần tử Fileupload 
	 * 		   	  Id - style - width - class - value ...
	 * $options	: Các phần sẽ bổ xung khi phát sinh trường hợp mới
	 */
	
	public static function create($name = '', $value = '', $attr = array(), $options = null){
	
		$html = '';
		
		//1. Tạo chuỗi thuộc tính từ tham số $attr
		$strAttr = '';
		$id = 'id="' . $name . '"';
		if(count($attr)> 0){
			foreach ($attr as $key => $val){
                if($key == 'id'){
			        $id = 'id="' . $val . '"';
			    }elseif($key != "type" && $key != 'value'){
					$strAttr .= ' ' . $key . '="' . $val . '" ';
				}
			}
		}
		
		//Tạo phần tử HTML
		$html = '<input ' . $id . ' type="file" name="'. $name . '" ' . $strAttr . ' value="' . $value . '" />';
	
		return $html;
	}

}
