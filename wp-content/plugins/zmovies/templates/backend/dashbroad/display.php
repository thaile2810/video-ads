<?php 
global $wpdb;
?>
<div class="wrap">
	<h1 class="page-header">Dashboard</h1>
	<div class="zInform">
	   <div class="panel-item">
	       <div class="panel-heading">
	           <div class="number">
					<?php 
					$sql = $wpdb->prepare("SELECT count(p.ID) FROM $wpdb->posts AS p
					    WHERE p.post_type = '%s'"
					    ,'post');
				    $post = $wpdb->get_row($sql, ARRAY_N );
					$wpdb->flush();
					
					//$the_query = new \WP_Query(array('post_type' => 'post'));
					
					?>
	               <span><?php echo current($post)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo  __('Total videos')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit.php')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	    </div><!-- End panel item -->
	   
	   	<div class="panel-item green">
	       <div class="panel-heading">
	           <div class="number">
					<?php 
					$sql = $wpdb->prepare("SELECT count(p.ID) FROM $wpdb->posts AS p
                    					    WHERE (p.post_status = '%s') 
                    					    AND p.post_type = 'post'"
                    					    ,'publish');
					
					//$data      = get_option(ZMOVIES_SETTING_OPTION,[]);
					// if(isset($data['max_video_id']) && intval($data['max_video_id'], 0) > 0){
					//     $sql .= sprintf(" AND ID <= '%d'", intval($data['max_video_id']));
					// }
					
				    $post = $wpdb->get_row($sql, ARRAY_N );
					$wpdb->flush();
					
                    ?>
                    <span><?php echo current($post)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo  __('Publish videos')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit.php?post_status=publish&post_type=post');?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div><!-- End panel item -->
	   	   

	   
	   <div class="panel-item yellow">
	       <div class="panel-heading">
	           <div class="number">
	               <?php 
					$sql = $wpdb->prepare("SELECT count(p.ID) FROM $wpdb->posts AS p
					    WHERE p.post_status IN ('%s')
					    AND p.post_type = 'post'"
					    ,'trash');
					    $post = $wpdb->get_row($sql, ARRAY_N );
					    $wpdb->flush();
					    
					?>
	               <span><?php echo current($post)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo  __('Trash videos')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit.php')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div><!-- End panel item -->
	   
	   <div class="panel-item red">
	       <div class="panel-heading">
	           <div class="number">
					<?php 
					
					$sql = $wpdb->prepare("SELECT count(p.ID) FROM $wpdb->posts AS p
					    WHERE p.post_status IN ('%s')
					    AND p.post_type = 'post'"
					    ,'pending');
					    $post = $wpdb->get_row($sql, ARRAY_N );
					    $wpdb->flush();
					    
					?>
	               <span><?php echo current($post)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo  __('Pending videos')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit.php?post_status=pending&post_type=post');?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div><!-- End panel item -->
	   
	   
	   <div class="panel-item green">
	       <div class="panel-heading">
	           <div class="number">
	               <?php 
	               
	               $sql = $wpdb->prepare("SELECT count(t.term_id) FROM $wpdb->terms AS t
	                   INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
	                   WHERE tt.taxonomy IN ('%s')"
	                   ,'category');
	                   $terms = $wpdb->get_row($sql, ARRAY_N );
	               
	               $wpdb->flush();
	               //$terms = get_terms('category',array('hide_empty'=>false));
	               
	               ?>
	               <span><?php echo current($terms)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo __('Categories')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit-tags.php?taxonomy=category')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div>
	   <div class="panel-item yellow">
	       <div class="panel-heading">
	           <div class="number">
	               <?php 
	               
	               
	               $sql = $wpdb->prepare("SELECT count(p.ID) FROM $wpdb->posts AS p
					    WHERE p.post_status IN ('%s')
					    AND p.post_type = 'post'"
					    ,'video_16');
					    $post = $wpdb->get_row($sql, ARRAY_N );
					    $wpdb->flush();
	               
	               $wpdb->flush();
	               
	               //$terms = get_terms('post_tag',array('hide_empty'=>false));
	               
	               ?>
	               <span><?php echo current($post)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo __('Video 16+')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit.php?post_status=publish&post_type=post')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div>
	   
	   <div class="panel-item red">
	       <div class="panel-heading">
	           <div class="number">
	               <?php 
	               
	               $sql = $wpdb->prepare("SELECT count(t.term_id) FROM $wpdb->terms AS t
	                   INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
	                   WHERE tt.taxonomy IN ('%s')"
	                   ,'zvideos_youtube_user');
	                   $terms = $wpdb->get_row($sql, ARRAY_N );
          
	                   $wpdb->flush();
	               //$terms = get_terms('zvideos_youtube_user',array('hide_empty'=>false));
	               
	               
	               ?>
	               <span><?php echo current($terms)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo __('Youtube users')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit-tags.php?taxonomy=zvideos_youtube_user&post_type=post')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div>
		<div class="panel-item">
	       <div class="panel-heading">
	           <div class="number">
	               <?php 
	               
	               
	               $sql = $wpdb->prepare("SELECT count(t.term_id) FROM $wpdb->terms AS t
	                   INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
	                   WHERE tt.taxonomy IN ('%s')"
	                   ,'zvideos_channel');
	                   $terms = $wpdb->get_row($sql, ARRAY_N );
          
	                   $wpdb->flush();
	               //$terms = get_terms('zvideos_channel',array('hide_empty'=>false));
	               
	               ?>
	               <span><?php echo current($terms)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo __('Chanels')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit-tags.php?taxonomy=zvideos_channel&post_type=post')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div>
	   <div class="panel-item yellow">
	       <div class="panel-heading">
	           <div class="number">
	               <?php 
	               
	               
	               $sql = $wpdb->prepare("SELECT count(t.term_id) FROM $wpdb->terms AS t
	                   INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
	                   WHERE tt.taxonomy IN ('%s')"
	                   ,'zvideos_playlist');
	                   $terms = $wpdb->get_row($sql, ARRAY_N );
          
	                   $wpdb->flush();
	               //$terms = get_terms('zvideos_playlist',array('hide_empty'=>false));
	               
	               ?>
	               <span><?php echo current($terms)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo __('Playlist')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit-tags.php?taxonomy=zvideos_playlist&post_type=post')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div>
	   
	   <div class="panel-item red">
	       <div class="panel-heading">
	           <div class="number">
	               <?php 
	               
	               $sql = $wpdb->prepare("SELECT count(t.term_id) FROM $wpdb->terms AS t
                    	                   INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                    	                   WHERE tt.taxonomy IN ('%s')"
	                           ,'zvideos_youtube_keyword');
                   $terms = $wpdb->get_row($sql, ARRAY_N );
                   
                   $wpdb->flush();
	               //$terms = get_terms('zvideos_youtube_keyword',array('hide_empty'=>false));
	               
	               ?>
	               <span><?php echo current($terms)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo __('Keywords')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit-tags.php?taxonomy=zvideos_youtube_keyword&post_type=post')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div>
	   <div class="panel-item">
	       <div class="panel-heading">
	           <div class="number">
					<?php global $zController;
					       $model = $zController->getModel('Ads');
					?>
	               <span><?php echo $model->count_items();?></span>
	           </div>
	           <div class="text">
	               <span><?php echo  __('Advertisements')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('admin.php?page=zvideos-manager-ads')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div>
	   <div class="panel-item green">
	       <div class="panel-heading">
	           <div class="number">
					<?php global $zController;
					       $modelShortCode = $zController->getModel('ShortCode');
					?>
	               <span><?php echo $modelShortCode->count_items();?></span>
	           </div>
	           <div class="text">
	               <span><?php echo  __('ShortCode')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('admin.php?page=zvideos-manager-short-code')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div>
	   <div class="panel-item red">
	       <div class="panel-heading">
	           <div class="number">
	               <?php 
	               
	               $sql = $wpdb->prepare("SELECT count(p.ID) FROM $wpdb->posts AS p
					    WHERE p.post_status IN ('%s')
					    AND p.post_type = 'post'"
					    ,'report_16');
					    $post = $wpdb->get_row($sql, ARRAY_N );
					    $wpdb->flush();
                   
                   $wpdb->flush();
	               //$terms = get_terms('zvideos_youtube_keyword',array('hide_empty'=>false));
	               
	               ?>
	               <span><?php echo current($post)?></span>
	           </div>
	           <div class="text">
	               <span><?php echo __('Report 16+')?></span>
	           </div>
	       </div>
	       <div class="panel-bottom">
	           <a href="<?php echo admin_url('edit.php?post_status=publish&post_type=post')?>">
	               <span >View Details</span>
	               <span class="right"><span class="dashicons dashicons-arrow-right-alt"></span></span>
	           </a>
	       </div>
	   </div>
	</div>
</div>