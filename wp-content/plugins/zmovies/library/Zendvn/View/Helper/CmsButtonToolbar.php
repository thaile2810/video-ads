<?php
namespace Zendvn\View\Helper;

class CmsButtonToolbar{
	
// 	public static function create($name, $icon, $type, $showTotal = null, $view ,$link = null){
	public static function create($name, $attr ,$link = null){
		$showTotal	= !empty($attr['showTotal']) ? $attr['showTotal'] : 'no';
		$icon		= !empty($attr['icon']) ? $attr['icon'] : 'default';
		$type		= !empty($attr['type']) ? $attr['type'] : 'default';
		$link		= !empty($link) ? $link : 'javascript:;';
		
		return sprintf('<a data-show-item="%s" data-type="%s" class="btn btn-app" href="%s">
							<i class="fa %s"></i>%s
						</a>', $showTotal, $type, $link, $icon, $name); 
	}
}