<?php
namespace Zendvn\View\Helper;

class CmsUrl{
	
	public static function create($page, $options = null){
		
	    if(is_admin()){
	        $url = admin_url() . $page . '?page=' . ZENDVN_PLUGIN_NAME;
	        if($options['controller']){
	            $url .= '-' . $options['controller'];
	            unset($options['controller']);
	        }
	        if(count($options) > 0){
	            foreach ($options as $param => $value){
	                $url .= '&' . $param . '=' . $value;
	            }
	        }
	    }else{
	        $url = site_url();
	    }
	    return $url;
	}
	public function adminUrl(){
	    $url = admin_url();
	}
}