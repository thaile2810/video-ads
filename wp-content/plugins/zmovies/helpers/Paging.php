<?php
namespace Zmovies\Helper;

class Paging{
	
	public $_options = array();
	
	public function __construct($options = array()){
		$this->_options = $options;	
	}
	
	public function getPaging($queryObj = null){
		
		global $wp_rewrite;
		
		if($queryObj != null){
			
			$big = 999999999;
			//#038;
			$pagenum_link = str_replace( $big, '%#%', get_pagenum_link( $big ));
			$pagenum_link = str_replace( '#038;','&',  $pagenum_link);
			$pagenum_link = str_replace( '&&','&',  $pagenum_link);
			
			$current = max(1,get_query_var('paged'));
			/* if(is_front_page()){
				//echo 'Day la trang chủ';
				$current = max(1,get_query_var('page'));
			} */
			$format = '?page=%#%';
			/* if(is_front_page() && !empty($wp_rewrite->permalink_structure)){
				//echo '<br/> Day la trang chu';
				$format = '?res_category=ban-nha&page=%#%';
				$pagenum_link = str_replace( 'page','bat-dong-san/ban-nha/page',  $pagenum_link);
			} */
			/* echo '<br/>' .  $format;
			echo '<br/>' .  $pagenum_link; */
			
			$args = array(
					'base'               => $pagenum_link,
					'format'             => $format,
					'total'              => $queryObj->max_num_pages,
					'current'            => $current,
					'show_all'           => false,
					'end_size'           => 1,
					'mid_size'           => 2,
					'prev_next'          => true,
					'prev_text'          => __('< Previous',ZMOVIES_DOMAIN_LANGUAGE),
					'next_text'          => __('Next >',ZMOVIES_DOMAIN_LANGUAGE),
					'type'               => 'plain',
			);
			
			return paginate_links($args);	
			
		}
	}
	
}