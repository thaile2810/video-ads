<?php 
    global $zController,$htmlObj;
    
    $data       = @$this->_data['player'];
   
	$vWidth  = (!empty($data['width'])) ? @$data['width'] : '100%';
	$vHeight = (!empty($data['height'])) ? @$data['height'] : '410px';
	$vAuto   = (!empty($data['auto_play'])) ? $data['auto_play'] : 0;
	$vApp   = (!empty($data['app'])) ? $data['app'] : 'youtube';
	
	$lbl 	= __('Player',ZMOVIES_DOMAIN_LANGUAGE);

	
	$inputName = ZMOVIES_SETTING_OPTION . '[player][app]';
	$app	= $htmlObj->radio($inputName,$vApp,array('class'=>'regular-text'),['data' => ['youtube' => 'Youtube player', 'jwplayer' => 'Jwplayer'],'separator' => str_repeat('&nbsp;', 6)]);
	
		
	$inputName = ZMOVIES_SETTING_OPTION . '[player][width]';
	$width	 = $htmlObj->textbox($inputName,$vWidth,array('class'=>'regular-text'));
	
	$inputName = ZMOVIES_SETTING_OPTION . '[player][height]';
	$height	 = $htmlObj->textbox($inputName,$vHeight,array('class'=>'regular-text'));
	
	$inputName = ZMOVIES_SETTING_OPTION . '[player][auto_play]';
	$auto	= $htmlObj->radio($inputName,$vAuto,array('class'=>'regular-text'),['data' => [0 => 'No', 1 => 'Yes'],'separator' => str_repeat('&nbsp;', 6)]);
?>
<div id="zmovice-setting-player" class="postbox closed">
	<button type="button" class="handlediv button-link" aria-expanded="false">
		<span class="screen-reader-text">Toggle panel: <?php echo $lbl;?></span>
		<span class="toggle-indicator" aria-hidden="true"></span>
	</button>
	<h2 class="hndle ui-sortable-handle">
		<span><?php echo $lbl;?></span>
	</h2>
	<div class="inside">
		<table class="form-table">
		  <tbody>
			<tr>
				<th scope="row">
					<label><?php echo __('Player will play',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $app;?>
				</td>
			</tr>
			<tr>
					<th scope="row">
						<label><?php echo __('Width',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $width;?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Height',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $height;?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Auto play',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $auto;?>
					</td>
				</tr>
		  </tbody>
		</table>	
	</div>
</div>