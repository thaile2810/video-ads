<?php 
	global $zController,$htmlObj,$wpdb;
	
	//Lay gia trị tham so Page
	$page      = $zController->getParams('page');
		
	$action    = ($zController->getParams('action') != '')? $zController->getParams('action'):'add';
	
	$data      = get_option(ZMOVIES_SETTING_OPTION,[]);
	
	$lbl       = __('Settings',ZMOVIES_DOMAIN_LANGUAGE);	
	
	$inputId           = ZMOVIES_SETTING_OPTION . '[fb_api]';
	$fbAPI             = $htmlObj->textbox($inputId,@$data['fb_api'],['class'=>'regular-text']);
	
	$inputId           = ZMOVIES_SETTING_OPTION . '[fb_link]';
	$fbLink	           = $htmlObj->textbox($inputId,@$data['fb_link'],['class'=>'regular-text']);
	
/* Site offline */
	$inputId           = ZMOVIES_SETTING_OPTION . '[site_offline]';
	if (@$data['site_offline'] === NULL || empty(@$data['site_offline'])){
	   $value  = @file_get_contents(ZMOVIES_TEMPLATE_PATH.'/backend/setting/content.htm');
	}
	else{
	    $value = html_entity_decode(@$data['site_offline']);
	}

	
	$siteOfflineContent= $htmlObj->textarea($inputId,$value,['class'=>'regular-text','rows' => '22','cols' => '120']);
	

	$inputId           = ZMOVIES_SETTING_OPTION . '[site_status]';
	$vStatus            = (!empty(@$data['site_status'])) ? @$data['site_status'] : 0;

	$siteStatus	       = $htmlObj->radio($inputId,$vStatus,array('class'=>'regular-text'),['data' => [1 => 'Yes',0 => 'No'],'separator' => str_repeat('&nbsp;', 6)]);

	
/* Video setting */
	$vMin              = (!empty(@$data['video_rand_min'])) ? @$data['video_rand_min'] : 200;
	$inputId           = ZMOVIES_SETTING_OPTION . '[video_rand_min]';
	$videoRandMin      = $htmlObj->textbox($inputId,$vMin,['class'=>'regular-text','placeholder'=>'Min']);
	
	$vMax              = (!empty(@$data['video_rand_max'])) ? @$data['video_rand_max'] : 600;
	$inputId           = ZMOVIES_SETTING_OPTION . '[video_rand_max]';
	$videoRandMax      = $htmlObj->textbox($inputId,$vMax,['class'=>'regular-text','placeholder'=>'Max']);

// 	$inputId           = ZMOVIES_SETTING_OPTION . '[video_number]';
// 	$videoNumber      =  $htmlObj->textbox($inputId,@$data['video_number'],['class'=>'regular-text']);
	
	$inputId           = ZMOVIES_SETTING_OPTION . '[video_jump]';
	$videoJump         = $htmlObj->textbox($inputId,@$data['video_jump'],['class'=>'regular-text']);
	
	$inputId            = ZMOVIES_SETTING_OPTION . '[video_relate]';
	$videoRalate        = $htmlObj->textbox($inputId,@$data['video_relate'],['class'=>'regular-text']);
	
	$inputId            = ZMOVIES_SETTING_OPTION . '[max_video_id]';
	$videoMaxId         = $htmlObj->textbox($inputId,@$data['max_video_id'],['class'=>'regular-text']);
	
	$inputId            = ZMOVIES_SETTING_OPTION . '[page_mid_size]';
	$value              = (!empty(@$data['page_mid_size'])) ? @$data['page_mid_size'] : 5;
	$videoPageMidSize   = $htmlObj->textbox($inputId,$value,['class'=>'regular-text']);
	
	$inputId            = ZMOVIES_SETTING_OPTION . '[page_end_size]';
	$value              = (!empty(@$data['page_end_size'])) ? @$data['page_end_size'] : 1;
	$videoPageEndSize   = $htmlObj->textbox($inputId,$value,['class'=>'regular-text']);
	

	//$the_query     = new \WP_Query(array('post_type' => 'post'));
	//     $totalVideo     = $the_query->found_posts;
	$sql = $wpdb->prepare("SELECT count(p.ID) FROM $wpdb->posts AS p
                            WHERE p.post_type = '%s'"
                            ,'post');
                            $post = $wpdb->get_row($sql, ARRAY_N );
    $wpdb->flush();

    $totalVideo     = current($post);
    
    $inputId            = ZMOVIES_SETTING_OPTION . '[footer_content]';
    $value              = (!empty(@$data['footer_content'])) ? @$data['footer_content'] : '';
    $footerContent		= $htmlObj->textarea($inputId,$value,['class'=>'regular-text','rows' => '10','cols' => '120']);

    $msg = '';
	if($zController->getParams('msg') == 1){
	    $msg .='<div class="updated"><p>Update finish</p></div>';
	}
?>
<div class="wrap">
	<h2><?php echo $lbl;?></h2>
	<?php echo $msg;?>
	<form method="post" action="" id="<?php echo $page;?>"
		enctype="multipart/form-data">
		<input name="action" value="<?php echo $action;?>" type="hidden">				
		<?php wp_nonce_field($action,'security_code',true);?>
		
<!-- Setting Site Open-->
		<h3>Setting Site:</h3>
		<p></p>
		<table class="form-table zmovies-form-table">
		<tbody>
				<tr>
					<th scope="row">
						<label><?php echo __('Offline',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $siteStatus;?>
					   <p></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Content offline',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $siteOfflineContent;?>
					   <p></p>
					</td>
				</tr>

				
		  </tbody>
		</table>
<!-- Setting Site End-->		

<!-- Site Facebook Open-->
		<h3>Facebook API:</h3>
		<p></p>
		<table class="form-table zmovies-form-table">
		<tbody>
				<tr>
					<th scope="row">
						<label><?php echo __('APP ID',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $fbAPI;?>
					   <p>Goto https://developers.facebook.com/apps/ to get APP ID</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Fanpage link',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $fbLink;?>
					   <p>Copy shortcode to Widget [zvideos_fanpage]</p>
					</td>
				</tr>
				
		  </tbody>
		</table>		
<!-- Site Facebook End-->
		
		
<!-- Site offline Open-->
		<h3>Video Setting:</h3>
		<p></p>
		<table class="form-table zmovies-form-table">
		<tbody>
				<tr>
					<th scope="row">
						<label><?php echo __('Max video ID',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
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
						<label><?php echo __('Mid size',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $videoPageMidSize;?>
					   <p><?php echo __('How many numbers to either side of current page, but not including current page',ZMOVIES_DOMAIN_LANGUAGE);?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('End size',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $videoPageEndSize;?>
					   <p><?php echo __('How many numbers on either the start and the end list edges',ZMOVIES_DOMAIN_LANGUAGE);?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Footer content',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $footerContent;?>
					   <p><?php echo __('Enter your content into this fox',ZMOVIES_DOMAIN_LANGUAGE);?></p>
					</td>
				</tr>
				
		  </tbody>
		</table>
<!-- Site offline End-->
		<p class="submit">
			<input name="save" id="save" class="button button-primary" value="Save Settings" type="submit">
			
		</p>
	</form>
</div>
