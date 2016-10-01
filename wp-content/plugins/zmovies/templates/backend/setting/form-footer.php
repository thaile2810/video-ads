<?php
global $htmlObj,$wpdb;
    
$data               = @$this->_data['footer'];

$inputId            = ZMOVIES_SETTING_OPTION . '[footer][footer_content]';
$value              = (!empty(@$data['footer_content'])) ? @$data['footer_content'] : '';
$footerContent		= $htmlObj->textarea($inputId,$value,['class'=>'regular-text','rows' => '10','cols' => '120']);
$lbl 				= __('Footer',ZMOVIES_DOMAIN_LANGUAGE);
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
			<tr>
				<th scope="row">
					<label><?php echo __('Footer content',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $footerContent;?>
				   <p><?php echo __('Enter your content into this fox',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
		</table>
	</div>
</div>