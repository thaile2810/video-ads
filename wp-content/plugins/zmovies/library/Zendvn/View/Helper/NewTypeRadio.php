<?php
namespace Zendvn\View\Helper;
class NewTypeRadio{

	/*
	* $name 	: Tên của phần tử Radio
	* $attr 	: Các thuộc tính của phần tử Radio
	* 		   	  Id - style - width - class - value ...
	* 			 
	* $options	: Các phần sẽ bổ xung khi phát sinh trường hợp mới
	*  			  [data]: là phần tử sẽ chứa một mảng value và label của phần tử radio
	*  			  [separator]: Giá trị phân cách của các nút radio
	*/
	
	public static function create($name = '', $value = '', $attr = array(), $options = null){
	
		$html = '';
	
		//1. Tạo chuỗi thuộc tính từ tham số $attr
		$strAttr = '';
		$id = '';
		if(count($attr)> 0){
			foreach ($attr as $key => $val){
			    if($key == 'id'){
			        $id = $val;
			    }elseif($key != "type" && $key != 'value'){
			        $strAttr .= ' ' . $key . '="' . $val . '" ';
			    }
			}
		}
		
		
		//2. Kiểm tra giá trị của $value
		$strValue = $value;
		
		//3.Kiểm tra ký tự phân cách giữa các nút radio
		if(!isset($options['separator'])){
			$options['separator'] = ' ';
		}
		
		//4. Tạo các nút radio
		$html = '';
		$data = $options['data'];
		
		if(count($data)){
			foreach ($data as $val){
				$checked = '';
				if(preg_match('/^(' . $strValue .')$/i', $val['id'])){
					$checked = ' checked="checked" ';
				}	
				$dataContent    = 'data-content="' . $val['content'] . '"';
				$dataPrice      = 'data-price="' . $val['price'] . '"';		
				$vid             = $id ? $id . '-' . $val['id'] : $val['id'];
				$html  .= '<label for="' . $vid . '"><input type="radio" id="' . $vid . '" name="' . $name . '" ' . $checked . ' value="' . $val['id'] . '" ' . $dataContent . ' ' . $dataPrice . ' ' . $strAttr . ' />' 
						  . $val['name']  . '</label>' . $options['separator'];
			}
		}
			
		return $html;
	}
	
}
