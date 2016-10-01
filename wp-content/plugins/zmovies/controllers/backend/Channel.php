<?php
namespace Zmovies\Controller\Backend;

class Channel{
	
	public function __construct(){
		global $zController;
		
		$model = $zController->getModel('Channel');
		
		add_action('init',array($model,'create'));
		
		add_action($model->_key . '_add_form_fields',array($model,'add_form_fields'));		
		add_action($model->_key . '_edit_form_fields', array($model,'edit_form_fields'), 10, 2 );
		
		add_action('edited_' . $model->_key, array($model,'save'), 10, 2 );
		add_action('create_' . $model->_key, array($model,'save'), 10, 2 );
		
		$model->customBulkAction();
	}

}