<?php
namespace Zendvn\View\Helper;
class FormNestedSelectbox{

	/*
	* $name 	: Tên của phần tử Selectbox
	* $attr 	: Các thuộc tính của phần tử textbox
	* 		   	  Id - style - width - class - value ...
	* 			 
	* $options	: Phần tử sẽ chứa một mảng value và label của <option>
	*/
	
	public static function create($name = '', $value = '', $attr = array(), $options = null){
	
		$html = '';
	
		//1. Tạo chuỗi thuộc tính từ tham số $attr
		$strAttr = '';
		if(count($attr)> 0){
			foreach ($attr as $key => $val){
				if($key != "type" && $key != 'value'){
					$strAttr .= ' ' . $key . '="' . $val . '" ';
				}
			}
		}
		
		//2. Kiểm tra giá trị của $value
		$strValue = '';
		if(is_array($value)){		
			$strValue = implode("|", $value);
		}else{
			$strValue = $value;
		}
		//echo $strValue;
		
		//3. Tạo value và label của <option>
		$strOption = '';
		if(count($options)){
		    //
			foreach ($options as $val){
				$selected = '';
				if(preg_match('/^(' . $strValue .')$/i', $val['id'])){
					$selected = ' selected="selected" ';
				}
				$strOption .= '<option value="' . $val['id'] . '" ' . $selected . ' >' . str_repeat('------|', $val['level']) . ' ' . $val['name'] . '</option>';
			}
		}
		
		//Tạo phần tử HTML
		$html = '<select name="'. $name . '" ' . $strAttr . ' >'
				. $strOption
				. '</select>';
		
		return $html;
	}
	
}
