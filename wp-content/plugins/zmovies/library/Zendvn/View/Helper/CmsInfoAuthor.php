<?php
namespace Zendvn\View\Helper;

class CmsInfoAuthor{
	
	public static function create($time, $author, $options = null){
		return sprintf('<p>%s</p><span><i class="fa fa-fw fa-user"></i>%s</span>', $time, $author);
	}
}