<?php
namespace Zmovies\Helper;

class GetTerms{
	
	public $_options = array();
	
	public function __construct($options = array()){
		$this->_options = $options;	
	}
	
	public function get($post, $taxonomy, $options = null){
	    
	    $postID = (is_object($post))? $postID = $post->ID: $post;	        
	    $strTerm = '';	    
	    $terms = get_the_terms($postID, $taxonomy );
	    if(!empty($terms)){	       
	        foreach ( $terms as $key => $term ){
	            if($options['type']=='text'){
	                $strTerm .= ' ' . @$term->name . ',';
	            }else{
	               $strTerm .= ' <a href="' . esc_url(get_term_link( $term->slug, $taxonomy )) . '">' . $term->name .'</a>,';
	            }
	        }
	    
	        $strTerm = rtrim($strTerm, ",");
	    }
	    
	    return $strTerm;
	}
	
}