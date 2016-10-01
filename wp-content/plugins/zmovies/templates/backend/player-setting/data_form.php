<?php 
	global $zController,$htmlObj;
	$key = ZMOVIES_SETTING_OPTION . '-player';
	//Lay gia trị tham so Page
	$page 	= $zController->getParams('page');
		
	$action = ($zController->getParams('action') != '')? $zController->getParams('action'):'add';
	
	
	if(!$zController->isPost()){
	    $zController->_data = get_option($key,[]);    
	}

	$vWidth  = (!empty($zController->_data['width'])) ? @$zController->_data['width'] : '100%';
	$vHeight = (!empty($zController->_data['height'])) ? @$zController->_data['height'] : 480;
	$vLogo   = (!empty($zController->_data['show_logo'])) ? $zController->_data['show_logo'] : 0;
	$vImage  = (!empty($zController->_data['show_image'])) ? $zController->_data['show_image'] : 0;
	$vAuto   = (!empty($zController->_data['auto_play'])) ? $zController->_data['auto_play'] : 0;
	$vApp   = (!empty($zController->_data['app'])) ? $zController->_data['app'] : 'jwplayer';
	
	$lbl 	= __('Player Settings',ZMOVIES_DOMAIN_LANGUAGE);

	
	$inputName = $key . '[app]';
	$app	= $htmlObj->radio($inputName,$vApp,array('class'=>'regular-text'),['data' => ['jwplayer' => 'Jwplayer', 'youtube' => 'Youtube player'],'separator' => str_repeat('&nbsp;', 6)]);
	
		
	$inputName = $key . '[width]';
	$width	 = $htmlObj->textbox($inputName,$vWidth,array('class'=>'regular-text'));
	
	$inputName = $key . '[height]';
	$height	 = $htmlObj->textbox($inputName,$vHeight,array('class'=>'regular-text'));
	
	$inputName = $key . '[auto_play]';
	$auto	= $htmlObj->radio($inputName,$vAuto,array('class'=>'regular-text'),['data' => [0 => 'No', 1 => 'Yes'],'separator' => str_repeat('&nbsp;', 6)]);
	
	$msg = '';
	if(count($zController->_error)>0){
	    $msg .= '<div class="error"><ul>';
	    foreach ($zController->_error as $key => $val){
	        $msg .= '<li>' . ucfirst($key) . ': ' . $val . '</li>';
	    }
	    $msg .= '</ul></div>';
	}else{
	    if($zController->getParams('msg') == 1){
	        $msg .='<div class="updated"><p>Update finish</p></div>';
	    }
	}
?>
<div class="wrap">
	<h2><?php echo $lbl;?></h2>
	<?php echo $msg;?>
	<form method="post" action="" id="<?php echo $page;?>" name="<?php echo $page;?>"
		enctype="multipart/form-data">
		<input name="action" value="<?php echo $action;?>" type="hidden">				
		<?php wp_nonce_field($action,'security_code',true);?>
		<h3>Default player:</h3>
		<p></p>
		<table class="form-table zmovies-form-table">
		<tbody>
				<tr>
					<th scope="row">
						<label><?php echo __('Player will play',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $app;?>
					</td>
				</tr>
		  </tbody>
		</table>	
		<h3>Jwplayer:</h3>
		<p></p>
		<table class="form-table zmovies-form-table">
		<tbody>
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
		
		<p class="submit">
			<input name="save" id="save" class="button button-primary" value="<?php echo __('Save',ZMOVIES_DOMAIN_LANGUAGE);?>" type="submit">
		</p>
	</form>
</div>
