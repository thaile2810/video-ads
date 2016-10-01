<?php 

	global $zController,$htmlObj;
	
	$page          = $zController->getParams('page');
	$options_key   = ZMOVIES_SETTING_OPTION . '-auto-update';
	
	$action        = $options_key;
	$data          = get_option($options_key,[]);
	$lbl           = __('Auto Update',ZMOVIES_DOMAIN_LANGUAGE);				
			
	$lbl_user 	    = __('Youtube user setting',ZMOVIES_DOMAIN_LANGUAGE);
	$lbl_channel 	= __('Youtube channel setting',ZMOVIES_DOMAIN_LANGUAGE);
	$lbl_playlist 	= __('Youtube playlist setting',ZMOVIES_DOMAIN_LANGUAGE);
	$lbl_keyword 	= __('Youtube keyword setting',ZMOVIES_DOMAIN_LANGUAGE);
	
	$msg       = '';
	if($zController->getParams('msg') == 1){
	    $msg .='<div class="updated"><p>Update finish</p></div>';
	}

	$week  = [
	    0 => 'Every day',
	    1 => 'Sunday',
	    2 => 'Monday',
	    3 => 'Tuesday',
	    4 => 'Wednesday',
	    5 => 'Thursday',
	    6 => 'Friday',
	    7 => 'Saturday',
	];
	$order =   [
	    'date'          => 'Date',
	    'rating'        => 'Rating',
	    'relevance'     => 'Relevance',
	    'title'         => 'Title',
	    'videoCount'    => 'Video Count',
	    'viewCount'     => 'View Count',
	];
    // ================================== USER ========================================
    $id                 = $options_key . '[user][updated]';	
    $userUpdated        = $htmlObj->selectbox($id,@$data['user']['updated'],null,['data' => $week]);
	
	$id                 = $options_key . '[user][status]';
	$vStatus               = (!empty($data['user']['status'])) ? $data['user']['status'] : 0;
	$userStatus	        = $htmlObj->radio($id,@$vStatus,array('class'=>'regular-text'),['data' => [0 => 'No', 1 => 'Yes'],'separator' => str_repeat('&nbsp;', 6)]);
	
// 	$id                = $options_key . '[user][total]';
// 	$userTotal         = $htmlObj->textbox($id,@$data['user']['total'],['class'=>'regular-text']);
	
// 	$id                 = $options_key . '[user][order]';
// 	$userOrder          = $htmlObj->selectbox($id,@$data['user']['order'],null,['data' => $order]);
	// ================================== CHANNEL ========================================
    $id                     = $options_key . '[channel][updated]';
    $channelUpdated         = $htmlObj->selectbox($id,@$data['channel']['updated'],null,['data' => $week]);

	$id                    = $options_key . '[channel][status]';
	$vStatus               = (!empty($data['channel']['status'])) ? $data['channel']['status'] : 0;
	$channelStatus	       = $htmlObj->radio($id,$vStatus,array('class'=>'regular-text'),['data' => [0 => 'No', 1 => 'Yes'],'separator' => str_repeat('&nbsp;', 6)]);
	
// 	$id                    = $options_key . '[channel][total]';
// 	$channelTotal          = $htmlObj->textbox($id,@$data['channel']['total'],['class'=>'regular-text']);
	
// 	$id                    = $options_key . '[channel][order]';
// 	$channelOrder          = $htmlObj->selectbox($id,@$data['channel']['order'],null,['data' => $order]);
	// ================================== CHANNEL ========================================
	$id                    = $options_key . '[playlist][updated]';
	$playlistUpdated       = $htmlObj->selectbox($id,@$data['playlist']['updated'],null,['data' => $week]);

	$id                    = $options_key . '[playlist][status]';
	$vStatus               = (!empty($data['playlist']['status'])) ? $data['playlist']['status'] : 0;
	$playlistStatus        = $htmlObj->radio($id,$vStatus,array('class'=>'regular-text'),['data' => [0 => 'No', 1 => 'Yes'],'separator' => str_repeat('&nbsp;', 6)]);

// 	$id                    = $options_key . '[playlist][total]';
// 	$playlistTotal         = $htmlObj->textbox($id,@$data['playlist']['total'],['class'=>'regular-text']);
	
// 	$id                    = $options_key . '[playlist][order]';
// 	$playlistOrder          = $htmlObj->selectbox($id,@$data['playlist']['order'],null,['data' => $order]);
	// ================================== KEYWORD ========================================
	$id                    = $options_key . '[keyword][updated]';
	$keywordUpdated        = $htmlObj->selectbox($id,@$data['keyword']['updated'],null,['data' => $week]);
	
	$id                    = $options_key . '[keyword][status]';
	$vStatus               = (!empty($data['keyword']['status'])) ? $data['keyword']['status'] : 0;
	$keywordStatus         = $htmlObj->radio($id,$vStatus,array('class'=>'regular-text'),['data' => [0 => 'No', 1 => 'Yes'],'separator' => str_repeat('&nbsp;', 6)]);

	$dataTotal  = [
	    5 => '5 videos',
	    10 => '10 videos',
	    15 => '15 videos',
	    20 => '20 videos',
	    25 => '25 videos',
	    30 => '30 videos',
	    35 => '35 videos',
	    40 => '40 videos',
	    45 => '45 videos',
	    50 => '50 videos',
	];
	
	$id                    = $options_key . '[keyword][total]';    
// 	$keywordTotal          = $htmlObj->textbox($id,@$data['keyword']['total'],['class'=>'regular-text']);
	$keywordTotal          = $htmlObj->selectbox($id,@$data['keyword']['total'],null,['data' => $dataTotal]);;


	$id                    = $options_key . '[keyword][order]';
	$keywordOrder          = $htmlObj->selectbox($id,@$data['keyword']['order'],null,['data' => $order]);

?>
<div class="wrap">
	<h2><?php echo $lbl;?></h2>
	<?php echo $msg;?>
	<form method="post" action="" id="<?php echo $page;?>"
		enctype="multipart/form-data">
		<input name="action" value="<?php echo $action;?>" type="hidden">				
		<?php wp_nonce_field($action,'security_code',true);?>
		<!-- Keyword -->	
		<hr/>
		<h3><?php echo $lbl_keyword;?></h3>
		<table class="form-table zmovies-form-table">
			<tbody>
				
				<tr>
					<th scope="row"><label><?php echo __('Status',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $keywordStatus; ?></td>
				</tr>	
				
				<tr>
					<th scope="row"><label><?php echo __('Update date',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $keywordUpdated?></td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo __('Videos/Keyword',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $keywordTotal;?></td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo __('Order',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $keywordOrder;?></td>
				</tr>	
									
			</tbody>
		</table>	
<!-- User -->	
		<hr/>
		<h3><?php echo $lbl_user;?></h3>
		<table class="form-table zmovies-form-table">
			<tbody>
				
				<tr>
					<th scope="row"><label><?php echo __('Status',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $userStatus; ?></td>
				</tr>	
				
				<tr>
					<th scope="row"><label><?php echo __('Update day',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $userUpdated?></td>
				</tr>
							
			</tbody>
		</table>
<!-- Chanel -->	
		<hr/>
		<h3><?php echo $lbl_channel;?></h3>
		<table class="form-table zmovies-form-table">
			<tbody>
				
				<tr>
					<th scope="row"><label><?php echo __('Status',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $channelStatus; ?></td>
				</tr>	
				
				<tr>
					<th scope="row"><label><?php echo __('Update date',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $channelUpdated?></td>
				</tr>
				
			</tbody>
		</table>
<!-- Playlist -->	
		<hr/>
		<h3><?php echo $lbl_playlist;?></h3>
		<table class="form-table zmovies-form-table">
			<tbody>
				
				<tr>
					<th scope="row"><label><?php echo __('Status',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $playlistStatus; ?></td>
				</tr>	
				
				<tr>
					<th scope="row"><label><?php echo __('Update date',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $playlistUpdated?></td>
				</tr>
				
			</tbody>
		</table>
        <p></p>
        				
		<p class="submit">
			<input name="save" id="save" class="button button-primary" value="Save Settings" type="submit">
			
		</p>
	</form>
</div>
