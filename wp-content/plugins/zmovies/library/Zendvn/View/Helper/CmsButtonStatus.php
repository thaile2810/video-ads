<?php
namespace Zendvn\View\Helper;

class CmsButtonStatus{
	
	public static function create($id, $status, $options = null){
	
		$classStatus	= ($status == 1) ? 'success' : 'default';
		
		return sprintf('<a href="javascript:;" onclick="javascript:changeStatus(\'%s\',\'%s\')" class="label label-%s"><i class="fa fa-check"></i></a>', 
						$id, $status, $classStatus);
	}
}