<?php 
    global $htmlObj;
    $data               = @$this->_data['facebook'];
    
    $inputId           = ZMOVIES_SETTING_OPTION . '[facebook][app-id]';
    $fbAPI             = $htmlObj->textbox($inputId,@$data['app-id'],['class'=>'regular-text']);
    
    $inputId           = ZMOVIES_SETTING_OPTION . '[facebook][fanpage]';
    $fbLink	           = $htmlObj->textbox($inputId,@$data['fanpage'],['class'=>'regular-text']);

    $inputId           = ZMOVIES_SETTING_OPTION . '[facebook][app-secret]';
    $fbAppSecret	   = $htmlObj->textbox($inputId,@$data['app-secret'],['class'=>'regular-text']);
    
    $lbl 	= __('Facebook',ZMOVIES_DOMAIN_LANGUAGE);
?>
<div id="zmovice-setting-facebook" class="postbox closed">
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
						<label><?php echo __('APP ID',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $fbAPI;?>
					   <p>Goto https://developers.facebook.com/apps/ to get APP ID</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('APP Secret',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $fbAppSecret;?>
					   <p>Goto https://developers.facebook.com/apps/ to get APP Secret</p>
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
	</div>
</div>