<?php
namespace Zmovies\Controller\Backend;

class Tag{
	
	public function __construct(){
	    
	    global $zController;
	    
	    $phpFile = basename($_SERVER['SCRIPT_NAME']);
	    if($phpFile == 'edit-tags.php' && $zController->getParams('taxonomy') == 'post_tag'){
	        $model = $zController->getModel('Tag');
	        
    		add_action('admin_init', array($model,'resetTaxConfig'));
	    }
	}
	
}