<?php
namespace Zmovies\Ext\ShortCodes;

class Movies{
	
	private $_post;
	public $_nameSc;
	public $_wpQuery;
	public $_data;
	public $_args_post;
	public function __construct($options = array()){

		// echo '<br/>' . __METHOD__;
		add_shortcode('zvideo_sc', array($this,'show'));

	}
	public function show($atts){
		
		global $zController,$wpdb, $post;
		$html = '';
		$this->_post =  $post;
        $scID = (int)$atts['id'];
        if($scID > 0 ){
         
          $sql = 'SELECT * FROM ' . ZMOVIES_TABLE_SHORTCODE . ' WHERE id=' . $scID;
          $result = $wpdb->get_row($sql);
         
          $this->_nameSc = $result->name;
          
          $result    = unserialize($result->content);
          
		  $args = $this->createQuery($atts,$result);

		  $this->_args_post = $args;

		  // echo '<pre>';
		  // print_r($args);
		  // echo '</pre>';


		  // set total_item from sc admin
		  $total = $result['total_item'];
		  if($total == 0 || $total == null){
		      $total = 100;
		  }

		  $this->_data = $result;
		  
		  $this->_data['short_id'] = $scID;
		  
		  $this->_wpQuery = new \WP_Query($args);

		  $this->_wpQuery->max_num_pages = ceil($total/$result['items']);
		 
		  if($result['position'] == 'content') $this->pageHtml($this->_wpQuery);
		  
		  if($result['position'] == 'sidebar') $html = $this->sidebarHtml($this->_wpQuery);
		  
		  if($result['position'] == 'footer') $html = $this->footerHtml($this->_wpQuery);
		
        }
		
		
		return $html;
	}

	public function getArgs($short_id, $atts){

		global $zController,$wpdb, $post;
		$html = '';
		$args = '';
		if($short_id > 0){
			$sql = 'SELECT * FROM ' . ZMOVIES_TABLE_SHORTCODE . ' WHERE id=' . $short_id;
			$result = $wpdb->get_row($sql);

			$result    = unserialize($result->content);

			$args = $this->createQuery($atts,$result);
		}

		return $args;
	}
	
	public function createQuery($atts,$result){

	    
	    global $wpdb;
	   
	    $currentPage   = max(1,get_query_var('paged'));
	    
	    $post_category = $result['post_category'];
	    $orderby       = $result['orderby'];
	    $ordering      = $result['ordering'];
	    $filter_where  = $result['filter_where'];
	    $page_title    = $result['page_title'];
	    $post_category = $result['post_category'];
	    $tax_input	   = $result['tax_input'];
	    $load_type	   = $result['load_type'];
	    $video_16	   = $result['video_16'];
	    $items         = (int)$result['items'];
	    $fist_post_per_page = (int)$result['first_post_per_page'];	   
	    $total         = (int)$result['total_item'];

	   
	    $args = array();
	    
	    $args = [
	        'post_type' => array( 'post'),
	        'post_status' => 'publish,new,error',
	        'order' => $ordering,
	        'paged' => $currentPage,
	        'category__in' => $post_category
	    ];
	    if($load_type == 'paginator'){
	    	$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	     
		    $offset = ( $page - 1 ) * $items;
		     
		    $args['page'] = $page;
		    
		    if($items > 0){
		        $args['posts_per_page'] = $items;
		        
		    }
	    }elseif($load_type == 'load-more'){
	    	$args['posts_per_page'] = $fist_post_per_page;
	    	$args['offset']			= 0;
	    }

	    if($video_16 == 1){
	    	$args['post_status'] = 'publish,new,error,video_16';
	    }

	    if($page_title == 'member_video'){
	    	$args['author__not_in'] = array('2');
	    }

	    if($page_title == 'site_video'){
	    	if(count($post_category) > 0){
	    		$args['tax_query'] = array(
		    		'relation' => 'AND',
		    		array(
				        'taxonomy' => 'category',                //(string) - Taxonomy.
				        'field' => 'id',                    //(string) - Select taxonomy term by ('id' or 'slug')
				        'terms' => $post_category,    //(int/string/array) - Taxonomy term(s).
				        'include_children' => true,
				        'operator' => 'IN'                    //(string) - Operator to test. Possible values are 'IN', 'NOT IN', 'AND'.
				    )
		    	);
	    	}
	    	if(count($post_category) > 0 && count($tax_input['zvideos_channel']) > 0){
		    	$args['tax_query'] = array(
		    		'relation' => 'AND',
		    		array(
				        'taxonomy' => 'category',                //(string) - Taxonomy.
				        'field' => 'id',                    //(string) - Select taxonomy term by ('id' or 'slug')
				        'terms' => $post_category,    //(int/string/array) - Taxonomy term(s).
				        'include_children' => true,
				        'operator' => 'IN'                    //(string) - Operator to test. Possible values are 'IN', 'NOT IN', 'AND'.
				    ),
				    array(
				        'taxonomy' => 'zvideos_channel',                //(string) - Taxonomy.
				        'field' => 'id',                    //(string) - Select taxonomy term by ('id' or 'slug')
				        'terms' => $tax_input['zvideos_channel'],    //(int/string/array) - Taxonomy term(s).
				        'include_children' => true,
				        'operator' => 'IN'                    //(string) - Operator to test. Possible values are 'IN', 'NOT IN', 'AND'.
				    )
		    	);
		    }

		    if(count($post_category) > 0 && count($tax_input['zvideos_channel']) > 0 && count($tax_input['zvideos_playlist']) > 0){
		    	$args['tax_query'] = array(
		    		'relation' => 'AND',
		    		array(
				        'taxonomy' => 'category',                //(string) - Taxonomy.
				        'field' => 'id',                    //(string) - Select taxonomy term by ('id' or 'slug')
				        'terms' => $post_category,    //(int/string/array) - Taxonomy term(s).
				        'include_children' => true,
				        'operator' => 'IN'                    //(string) - Operator to test. Possible values are 'IN', 'NOT IN', 'AND'.
				    ),
				    array(
				        'taxonomy' => 'zvideos_channel',                //(string) - Taxonomy.
				        'field' => 'id',                    //(string) - Select taxonomy term by ('id' or 'slug')
				        'terms' => $tax_input['zvideos_channel'],    //(int/string/array) - Taxonomy term(s).
				        'include_children' => true,
				        'operator' => 'IN'                    //(string) - Operator to test. Possible values are 'IN', 'NOT IN', 'AND'.
				    ),
				    array(
				        'taxonomy' => 'zvideos_playlist',                //(string) - Taxonomy.
				        'field' => 'id',                    //(string) - Select taxonomy term by ('id' or 'slug')
				        'terms' => $tax_input['zvideos_playlist'],    //(int/string/array) - Taxonomy term(s).
				        'include_children' => true,
				        'operator' => 'IN'                    //(string) - Operator to test. Possible values are 'IN', 'NOT IN', 'AND'.
				    ),
		    	);
		    }

	    }


	    //random_post_7_day
	    if($orderby == 'random_post_7_day'){
	    	$args['date_query'] = array(
	    		array(
	    			'after'     => '1 week ago'
	    		),
	    	);
	    	$args['orderby'] = 'rand';
	    }

	    //random_post_all_day
	    if($orderby == 'random_post_all_day'){
	    	$args['orderby'] = 'rand';
	    }

	    //random_post_7_day
	    if($orderby == 'random_post_7_day'){
	    	$args['date_query'] = array(
	    		array(
	    			'after'     => '1 week ago'
	    		),
	    	);
	    	$args['orderby'] = 'rand';
	    }

		if($orderby == 'ID'){
	        $args['orderby'] = 'ID';	    
	    }	    

	    if($orderby == 'views'){
	        $args['orderby'] = 'meta_value_num';
	        $args['meta_key'] = 'zvideos_views';
	    
	    }

	    if($result['orderby'] == 'date_view'){
	        $args['orderby'] = 'meta_value_num';
	        $args['meta_key'] = 'zvideos_views_date_' . date("Ymd");
	    }
	    
        //date_view
	    if($result['orderby'] == 'date_view'){
	        $args['orderby'] = 'meta_value_num';
	        $args['meta_key'] = 'zvideos_views_date_' . date("Ymd");
	    }
	    
	    //week_view
	    if($result['orderby'] == 'week_view'){
	        $args['orderby'] = 'meta_value_num';
	        $currentWeek      = date("W");
	        $args['meta_key'] = 'zvideos_views_week_' . $currentWeek;
	    }
	    
	    //month_view
	    if($result['orderby'] == 'month_view'){
	        $args['orderby'] = 'meta_value_num';
	        $currentMonth     = date("m");
	        $args['meta_key'] = 'zvideos_views_month_' . $currentMonth;
	    }
	    
	    //year_view
	    if($result['orderby'] == 'year_view'){
	        $args['orderby'] = 'meta_value_num';
	        $currentYear      = date("Y");	        
	        $args['meta_key'] = 'zvideos_views_year_' . $currentYear;	        
	    }
       
        
	    return $args;
	}
	
	public function checkTerm($arrTerm){
	    $flag = false;    
	    if(count($arrTerm)> 0) $flag = true;
	    return $flag;
	}
	
	public function pageHtml($wpQuery){
	    global $zController;
	    //echo '<br/>' . __METHOD__;
	   
	    $nameSc = $this->_nameSc;
	    require('movies/display.php');   
    }
    
    public function sidebarHtml($wpQuery){
        
        global $zController;        
        $model = $zController->getModel('Video');
        
        $topView = $zController->getHelper('TopView');
        
        if ( $wpQuery->have_posts() ){
            
            $html = '';
            
            while ( $wpQuery->have_posts() ){
                $wpQuery->the_post();
                $post = $wpQuery->post;
            
                $title = mb_substr(get_the_title(), 0, 60);
                //$src = $model->getImageUrl($post->ID, 'mqdefault');
                $date = date('Y-m-d', strtotime($post->post_date));
                $src = $model->getImageUrl($post->ID,$date);
                
                $view = $topView->getView($post);
                
                $html .= '<div class="row item">
                    <a href="' . get_the_permalink() . '" class="thumbnail col-xs-4 col-sm-5">
                            <img src="' . $src . '" alt="' . $title . '"></a>
                    <div class="caption col-xs-8 col-sm-7">
                        <h3 class="title"><a href="' . get_the_permalink() . '">' . $title . '</a></h3>
                        <p> ' . $view['total'] .' views - ' . get_the_modified_date() . ' </p>
                        <p>Posted: ' . get_the_author_posts_link() . '</p>
                    </div>
                </div>';
                
          
            }
            wp_reset_query();
            //
            $html .= '</ul>';
        }
        return $html;
    }
    
    public function footerHtml($wpQuery){
        global $zController;
        
        if ( $wpQuery->have_posts() ){
            //
            $html = '<ul>';
    
            while ( $wpQuery->have_posts() ){
                $wpQuery->the_post();
                
                $html .= '<li>
                            <a href="' .  get_the_permalink() . '"  title="' . get_the_title() . '">' . get_the_title() . '</a>
                        </li>';
               
            }
    
            //
            $html .= '</ul>';
        }
        return $html;
    }
}
