<?php 
    global $htmlObj;
    $data               = @$this->_data['google'];
    
    $inputId           = ZMOVIES_SETTING_OPTION . '[google][app-id]';
    $ggAPI             = $htmlObj->textbox($inputId,@$data['app-id'],['class'=>'regular-text']);
    
    $inputId           = ZMOVIES_SETTING_OPTION . '[google][client-id]';
    $ggClientId	       = $htmlObj->textbox($inputId,@$data['client-id'],['class'=>'regular-text']);

    $inputId           = ZMOVIES_SETTING_OPTION . '[google][client-secret]';
    $ggClientSecret	   = $htmlObj->textbox($inputId,@$data['client-secret'],['class'=>'regular-text']);
    
    $lbl 	= __('Google',ZMOVIES_DOMAIN_LANGUAGE);
?>
<div id="zmovice-setting-google" class="postbox closed">
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
					   <?php echo $ggAPI;?>
					   <p>Goto https:https://console.developers.google.com/apis/ to get APP ID</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Client ID',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $ggClientId;?>
					   <p>Goto https://console.developers.google.com/apis/ to get Client ID</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Client Secret',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td>
					   <?php echo $ggClientSecret;?>
					   <p>Goto https://console.developers.google.com/apis/ to get Client Secret</p>
					</td>
				</tr>				
		  </tbody>
		</table>		
	</div>
</div>