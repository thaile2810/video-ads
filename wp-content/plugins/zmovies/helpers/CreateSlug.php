<?php
namespace Zmovies\Helper;

class CreateSlug{
	/*
	 * array[
	 * 		'table' => 'wp_zendvn_sp_manufacturer',
	 * 		'field' => 'slug',
	 * 		'exception' => array('field' => 'id', 'value'=> 2)  
	 *  ]
	 */
	
	
	public function getSlug($val = '', $options = array()){
		
		global $wpdb, $zController;
		
		$newVal = $val;
		$table 	= $options['table'];
		$field 	= $options['field'];
		
		for($i=0; $i<999; $i++){
			if($i>0){
				$newVal = $val . '-' . $i;
			}
			if(!isset($options['exception'])){
				
				$sql = "SELECT COUNT(id) 
						FROM $table 
						WHERE $field = '$newVal'";
				$sql = $wpdb->prepare($sql, '%s','%s','%s');
				$result = $wpdb->get_col($sql);
			}else{
				$excep_field = $options['exception']['field'];
				$excep_value = $options['exception']['value'];
				$sql = "SELECT COUNT(id)
						FROM $table
						WHERE $field = '$newVal'
						AND $excep_field != $excep_value";
				$sql = $wpdb->prepare($sql, '%s','%s','%s','%s','%s');
				$result = $wpdb->get_col($sql);
				
			}
			if($result[0] == 0) return $newVal;
		}
	}
}