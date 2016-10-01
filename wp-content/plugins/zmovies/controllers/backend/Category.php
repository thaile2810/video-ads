<?php
namespace Zmovies\Controller\Backend;

class Category{
	
	public function __construct(){
	    
	    global $zController;
	    
	    $phpFile = basename($_SERVER['SCRIPT_NAME']);
	    $model = $zController->getModel('Category');
	    if($phpFile == 'edit-tags.php'  && $zController->getParams('taxonomy') == $model->_key){
    		add_action('admin_init', array($model,'resetTaxConfig'));
    		
    		add_action($model->_key . '_add_form_fields',array($model,'add_form_fields'));
    		add_action($model->_key . '_edit_form_fields', array($model,'edit_form_fields'), 10, 2 );
    		
    		add_action('edited_' . $model->_key, array($model,'save'), 10, 2 );
    		add_action('create_' . $model->_key, array($model,'save'), 10, 2 );
    		
	    }
	}
	
}