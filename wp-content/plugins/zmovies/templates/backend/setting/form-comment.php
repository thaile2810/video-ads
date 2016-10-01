<?php 
global $htmlObj;

$data       = @$this->_data['comment'];

	$inputId            = ZMOVIES_SETTING_OPTION . '[comment][page_mid_size]';
    $value              = (!empty(@$data['page_mid_size'])) ? @$data['page_mid_size'] : 5;
    $commentPageMidSize   = $htmlObj->textbox($inputId,$value,['class'=>'regular-text']);
    
    $inputId            = ZMOVIES_SETTING_OPTION . '[comment][page_end_size]';
    $value              = (!empty(@$data['page_end_size'])) ? @$data['page_end_size'] : 3;
    $commentPageEndSize   = $htmlObj->textbox($inputId,$value,['class'=>'regular-text']);

$lbl 	= __('Comment setting',ZMOVIES_DOMAIN_LANGUAGE);
?>
<div id="zmovice-setting-channel" class="postbox closed">
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
					<label><?php echo __('Comment post perpage',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $commentPageMidSize;?>
				   <p></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('Comment child perpage',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $commentPageEndSize;?>
				   <p></p>
				</td>
			</tr>
		  </tbody>
		</table>
	</div>
</div>