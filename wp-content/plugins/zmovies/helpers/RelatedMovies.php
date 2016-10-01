<?php
namespace Zmovies\Helper;

class RelatedMovies{

	public $_options = array();
	
	public function __construct($options = array() ){
	    $this->_options = $options;
		
	}

	public function show($params = null){
	
	   global $zController;
	   
	   $newArray = array();
	   $ids = explode(',', $params);
	   if(count($ids) > 0){
	       
	       $termHelper = $zController->getHelper('GetTerms');
	       $args = array('post_type'   => 'zmovies',
        	             'post_status' => 'publish',
        	             'post__in'    => $ids,
        	           );
	       $wpQuery = new \WP_Query($args);
	      
	      if($wpQuery->have_posts()){
	          while ($wpQuery->have_posts()){
	              $wpQuery->the_post();
	              $post        = $wpQuery->post;
	              $years       = $termHelper->get($post->ID,'zmovies_year',array('type'=>'text'));
	              $metaOptiops = unserialize(get_post_meta($post->ID, 'zmovies_movies_options',true));
	              
	              $newArray[$post->ID]['title']         = $post->post_title;
	              $newArray[$post->ID]['other_name']    = $metaOptiops['other_name']; 
	              $newArray[$post->ID]['years']         = $years; 
	              $newArray[$post->ID]['link']          = get_the_permalink(); 
	              $newArray[$post->ID]['id']            = $post->ID;
	             
	          }
	      }
	      
	      ksort ($newArray);	     
	   }
	  return $newArray;
	  
	}
	
	
 
}