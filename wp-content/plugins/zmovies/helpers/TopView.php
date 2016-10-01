<?php
namespace Zmovies\Helper;

class TopView{

	public $_options = array();
	
	public $_settings = array();
		
	public function __construct($options = array() ){
	    $this->_options = $options;
		
	}
	
	public function getView($postObj = null){
	    
	    $total = get_post_meta($postObj->ID, 'zvideos_views',true);
	    if($total == null) $total = 0; 
	    
	    $today     = date("Ymd");
	    $day       = @get_post_meta($postObj->ID,'zvideos_views_date_' . $today,true);
	    
	    $currentWeek   = date("W", strtotime($today));
	    $week          = @get_post_meta($post->ID,'zvideos_views_week_' . $currentWeek,true);
	    
	    $currentMonth   = date("m");
	    $month          = @get_post_meta($post->ID,'zvideos_views_month_' . $currentMonth,true);
	    
	    $currentYear   = date("Y");
	    $year          = @get_post_meta($post->ID,'zvideos_views_year_' . $currentYear,true);
	    
	    $views = ['total'=>$total, 'day'=>$day, 'week'=>$week,'month'=>$month, 'year'=>$year];
	    
	    return $views;
	}
	
	public function setViews(){
	    $this->setViewDate();
	    $this->setViewWeek();
	    $this->setViewMonth();
	    $this->setViewYear();
	}
	
	public function setViewTotal($postObj = null){
	
	    global $post;
	
	    $views = get_post_meta($post->ID, 'zvideos_views',true);
				    
		if($views == null || $views == 0){
		      $views = 1;				        
		}else{
		     $views = $views + 1;
		}
		update_post_meta(get_the_ID(), 'zvideos_views', $views);
	     
	    return $views;
	     
	}
	
	public function setViewDate($postObj = null){
	   
	    global $post;
	     
	    //zvideos_views_of_date	    
	    $day = get_post_meta($post->ID, 'zvideos_views_of_date',true);
	    $today = date("Ymd");
	    if(empty($day)){	 
	        $view = 1;
	        update_post_meta($post->ID, 'zvideos_views_of_date', $today);
	        update_post_meta($post->ID, 'zvideos_views_date_' . $today, $view);
	    }else{
	        if($day == $today){	            
	            $view = get_post_meta($post->ID,'zvideos_views_date_' . $today,true);
	            $view = $view + 1;
	            update_post_meta($post->ID, 'zvideos_views_date_' . $today, $view);
	        }else{
	           $view = 1;
	           update_post_meta($post->ID, 'zvideos_views_of_date', $today);
	           update_post_meta($post->ID, 'zvideos_views_date_' . $today,$view);
	        }
	    }
	    
	    return $view;
	    
	}
	
	public function setViewWeek(){
	    
	    global $post;
	   
	    $today         = date("Y-m-d");
	    $currentWeek   = date("W", strtotime($today));
	    
	    $week = get_post_meta($post->ID, 'zvideos_views_of_week',true);
	   
	   if(empty($week)){	 
	        $view = 1;
	        update_post_meta($post->ID, 'zvideos_views_of_week', $currentWeek);
	        update_post_meta($post->ID, 'zvideos_views_week_' . $currentWeek, $view);
	    }else{
	        if($week == $currentWeek){
	            $view = get_post_meta($post->ID,'zvideos_views_week_' . $currentWeek,true);
	            $view = $view + 1;
	            update_post_meta($post->ID, 'zvideos_views_week_' . $currentWeek, $view);
	        }else{
	           $view = 1;
	           update_post_meta($post->ID, 'zvideos_views_of_week', $currentWeek);
	           update_post_meta($post->ID, 'zvideos_views_week_' . $currentWeek,$view);
	        }
	    }
	     
	    return $view;
	}
	
    public function setViewMonth(){
	    
	    global $post;
	    
	    //$today         = date("Y-m-d");
	    $currentMonth   = date("m");
	     
	    $month = get_post_meta($post->ID, 'zvideos_views_of_month',true);
	    
	    if(empty($month)){
	        $view = 1;
	        update_post_meta($post->ID, 'zvideos_views_of_month', $currentMonth);
	        update_post_meta($post->ID, 'zvideos_views_month_' . $currentMonth, $view);
	    }else{
	        if($month == $currentMonth){
	            $view = get_post_meta($post->ID,'zvideos_views_month_' . $currentMonth,true);
	            $view = $view + 1;
	            update_post_meta($post->ID, 'zvideos_views_month_' . $currentMonth, $view);
	        }else{
	            $view = 1;
	            update_post_meta($post->ID, 'zvideos_views_of_month', $currentMonth);
	            update_post_meta($post->ID, 'zvideos_views_month_' . $currentMonth,$view);
	        }
	    }
	    
	    return $view;
	}
	
	public function setViewYear(){
	    
	    global $post;
	     
	    
	    $currentYear   = date("Y");
	    
	    $year = get_post_meta($post->ID, 'zvideos_views_of_year',true);
	     
	    if(empty($year)){
	        $view = 1;
	        update_post_meta($post->ID, 'zvideos_views_of_year', $currentYear);
	        update_post_meta($post->ID, 'zvideos_views_year_' . $currentYear, $view);
	    }else{
	        if($year == $currentYear){
	            $view = get_post_meta($post->ID,'zvideos_views_year_' . $currentYear,true);
	            $view = $view + 1;
	            update_post_meta($post->ID, 'zvideos_views_year_' . $currentYear, $view);
	        }else{
	            $view = 1;
	            update_post_meta($post->ID, 'zvideos_views_of_year', $currentYear);
	            update_post_meta($post->ID, 'zvideos_views_year_' . $currentYear,$view);
	        }
	    }
	     
	    return $view;
	}
	
	public function removeViews(){
	    
	}
}

