<?php
namespace Zmovies\Controller\Backend;

class Playlist{
	
	public function __construct(){
		global $zController;
		
		$model = $zController->getModel('Playlist');
		
		add_action('init',[$model,'create']);
		
		add_action($model->_key . '_add_form_fields',[$model,'add_form_fields']);		
		add_action($model->_key . '_edit_form_fields', [$model,'edit_form_fields'], 10, 2 );
		
		add_action('edited_' . $model->_key, [$model,'save'], 10, 2 );
		add_action('create_' . $model->_key, [$model,'save'], 10, 2 );
		
		$model->customBulkAction();
	}

}