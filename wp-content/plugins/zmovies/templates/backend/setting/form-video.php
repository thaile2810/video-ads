<?php 
    global $htmlObj,$wpdb;
    
    $data               = @$this->_data['video'];
    /* Video setting */
    $vMin              = (!empty(@$data['video_rand_min'])) ? @$data['video_rand_min'] : 200;
    $inputId           = ZMOVIES_SETTING_OPTION . '[video][video_rand_min]';
    $videoRandMin      = $htmlObj->textbox($inputId,$vMin,['class'=>'regular-text','placeholder'=>'Min']);
    
    $vMax              = (!empty(@$data['video_rand_max'])) ? @$data['video_rand_max'] : 600;
    $inputId           = ZMOVIES_SETTING_OPTION . '[video][video_rand_max]';
    $videoRandMax      = $htmlObj->textbox($inputId,$vMax,['class'=>'regular-text','placeholder'=>'Max']);
    
    $inputId           = ZMOVIES_SETTING_OPTION . '[video][video_jump]';
    $videoJump         = $htmlObj->textbox($inputId,@$data['video_jump'],['class'=>'regular-text']);
    
    $inputId            = ZMOVIES_SETTING_OPTION . '[video][video_relate]';
    $videoRalate        = $htmlObj->textbox($inputId,@$data['video_relate'],['class'=>'regular-text']);
    
    $inputId            = ZMOVIES_SETTING_OPTION . '[video][max_video_id]';
    $videoMaxId         = $htmlObj->textbox($inputId,@$data['max_video_id'],['class'=>'regular-text']);
    
    $sql = "SELECT count(p.ID) FROM $wpdb->posts AS p WHERE p.post_type = 'post' AND p.post_status != 'auto-draft'";
    $post = $wpdb->get_row($sql, ARRAY_N );
    $wpdb->flush();

    $totalVideo     = current($post);

    $lbl 	= __('Video',ZMOVIES_DOMAIN_LANGUAGE);
?>
<div id="zmovice-setting-video" class="postbox closed">
	<button type="button" class="handlediv button-link" aria-expanded="false">
		<span class="screen-reader-text">Toggle panel: <?php echo $lbl?></span>
		<span class="toggle-indicator" aria-hidden="true"></span>
	</button>
	<h2 class="hndle ui-sortable-handle">
		<span><?php echo $lbl?></span>
	</h2>
	<div class="inside">
		<table class="form-table">
		  <tbody>
			<tr>
				<th scope="row">
					<label><?php echo __('Max video perpage',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $videoMaxId;?><span> Total videos: <?php echo $totalVideo;?></span>
				   <p><?php echo __('Max id will be show in frontend(empty field for show all videos)',ZMOVIES_DOMAIN_LANGUAGE);?></p>			  
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('Jump for max',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $videoJump;?>
				   <p><?php echo __('Number video will be publish every day.',ZMOVIES_DOMAIN_LANGUAGE);?></p>				  
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('Rand views',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $videoRandMin;?>
				   <?php echo $videoRandMax;?>
				   <p><?php echo __('Random on per view',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label><?php echo __('Related videos',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $videoRalate;?>
				   <p><?php echo __('Number related videos on detail page',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('Default systerm user',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php
				   		$inputId            = ZMOVIES_SETTING_OPTION . '[video][default_systerm_user]';
					    $valueDefaultSystermUser = (!empty(@$data['default_systerm_user'])) ? @$data['default_systerm_user'] : '';
					     
				   		$args = array(
				   			'orderby'   => 'display_name',
    						'order'  	=> 'ASC',
    						'name'      => $inputId,
    						'selected'  		=> $valueDefaultSystermUser
				   		);
				   		wp_dropdown_users($args);
				   ?>
				   <p><?php echo __('Enter user_id you want set default user',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
				
		  </tbody>
		</table>
	</div>
</div>