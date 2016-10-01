<?php
namespace Zmovies\Helper;

class DataSelect{

	public $_options = array();
	
	/*
	 * Options Array Structure
	 * $options = array( 'first_elemt'	=> array('your_key'=>'your_value'),
	 * 					 'table_name' 	=> 'table_name',
	 * 					 'key_col'		=> 'field_name',
	 * 					 'value_col'	=> 'field_name',	 * 					 
	 * 					 'condition'	=> ''
	 * 					);  
	 */
	public function __construct($options = array() ){
		$this->_options = $options;
	}

	public function setOptions($options = array()){
		$this->_options = $options;
	}
	
	public function getData() {
		
		global $wpdb;
		
		extract($this->_options,EXTR_OVERWRITE);
		
		$data = array();
		if(count($first_elemt)>0){
			$data = $first_elemt;
		}
		
		
		$sql  = "SELECT {$key_col},{$value_col} FROM {$table_name}";
		
		if(!empty($condition)){
			$sql  .= " WHERE {$condition}";
		}
		//echo '<br/>' . $sql;
		$result = $wpdb->get_results($sql, ARRAY_A);
		
		if(count($result) >0 ){
			foreach ($result as $obj){
				$data[$obj[$key_col]] = $obj[$value_col];
			}
		}
		
		return $data;
	}
	
	/* private function example(){
		$options = array( 'first_elemt'	=> array('-- Select a city--'),
				'table_name' 	=> 'zendvn_res_cities',
				'key_col'		=> 'id',
				'value_col' 	=> 'name',
				'condition'	=> 'status = 1'
		);
		
		$dataSelect = $zController->getHelper('DataSelect','',$options)->getData();
	} */


}