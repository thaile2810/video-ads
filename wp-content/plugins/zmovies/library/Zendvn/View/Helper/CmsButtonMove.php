<?php
namespace Zendvn\View\Helper;

class CmsButtonMove {
	
	public static function create($id, $type = 'up', $ssFilter, $data, $view, $options = null){
	
		if(substr($ssFilter['order_by'], -4,4) == 'left' && $ssFilter['order'] == 'ASC') {
			
			$icon = 'fa-arrow-up';
			if($type != 'up'){
				$type = 'down';
				$icon = 'fa-arrow-down';
			}
			
			if($data['child'] == $data['parent']) return '<span>&nbsp;</span>';
			
			return sprintf('<span><a href="#" onclick="javascript:moveNode(\'%s\',\'%s\')" class="label label-primary"><i class="fa fa-fw %s"></i></a></span>', 
						$id, $type, $icon);
		}
		
		return null;
		
	}
}