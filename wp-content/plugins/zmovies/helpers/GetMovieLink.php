<?php
namespace Zmovies\Helper;

class GetMovieLink{

	public $_options = array();

	public function __construct($options = array()){
		$this->_options = $options;
	}

	public function get($movies = array(), $movie_code = null){
	    
	    if(count($movies) == 0 || $movie_code == null ) return false;
	    	    
	    $movie_code = explode('-', $movie_code);
	   
	    $s = str_replace('s', '', $movie_code[0]);
	    $m = str_replace('m', '', $movie_code[1]);
	   
	    return trim($movies[$s][$m]);
	    
	}
	
	
}