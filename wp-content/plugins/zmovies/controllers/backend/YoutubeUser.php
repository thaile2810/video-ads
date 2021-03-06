<?php
namespace Zmovies\Controller\Backend;

class YoutubeUser{
	
	public function __construct(){
		global $zController;
		
		$model = $zController->getModel('YoutubeUser');
		
		add_action('init',array($model,'create'));
		
		add_action($model->_key . '_add_form_fields',array($model,'add_form_fields'));		
		add_action($model->_key . '_edit_form_fields', array($model,'edit_form_fields'), 10, 2 );
		
		add_action('edited_' . $model->_key, array($model,'save'), 10, 2 );
		add_action('create_' . $model->_key, array($model,'save'), 10, 2 );
		
		$model->customBulkAction();
		
		add_action('admin_enqueue_scripts', array($this,'addJs'));
	}
		
	public function addJs(){
	    global $zController;
	    wp_register_script('admin-tax-validate', $zController->getJsUrl('admin-tax-validate.js'), array('jquery'),'1.1',true);
	    wp_enqueue_script('admin-tax-validate');
	}
    
}