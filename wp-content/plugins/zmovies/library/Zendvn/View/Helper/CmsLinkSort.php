<?php
namespace Zendvn\View\Helper;

class CmsLinkSort{
	
	public static function create($name, $column, $ssFilter, $options = null){
	
		$order	= ($ssFilter['order'] == 'ASC') ? 'DESC' : 'ASC';
	
		$class	= 'sorting ' . @$options['class'];
		if($ssFilter['order_by'] == $column){
			$class	= @$options['class'] . ' sorting_' . strtolower($ssFilter['order']);
		}
	
		$style = null;
		if(!empty($options['style'])){
			$style	= 'style=' . $options['style'];
		}
		
		return sprintf('<th class="%s" %s>
							<a href="javascript:;" onclick="javascript:sortList(\'%s\', \'%s\')">%s</a>
					   </th>', $class, $style, $column, $order, $name);
	}
}