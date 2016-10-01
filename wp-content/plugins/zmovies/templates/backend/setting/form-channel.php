<?php 
global $htmlObj;

$data       = @$this->_data['channel'];
$inputId           = ZMOVIES_SETTING_OPTION . '[channel][content]';
if (empty($data)){
    $value  = @file_get_contents(ZMOVIES_TEMPLATE_PATH.'/backend/setting/content.htm');
}else{
    $value = html_entity_decode(@$data['content']);
}

$sitechannelContent = $htmlObj->textarea($inputId,$value,['class'=>'regular-text','rows' => '22','cols' => '120']);

$inputId            = ZMOVIES_SETTING_OPTION . '[channel][status]';
$vStatus            = (!empty(@$data['status'])) ? @$data['status'] : 0;
$siteStatus         = $htmlObj->radio($inputId,$vStatus,array('class'=>'regular-text'),['data' => [1 => 'Yes',0 => 'No'],'separator' => str_repeat('&nbsp;', 6)]);

$lbl 	= __('Site channel',ZMOVIES_DOMAIN_LANGUAGE);
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
					<label><?php echo __('channel',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $siteStatus;?>
				   <p></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('Content channel',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $sitechannelContent;?>
				   <p></p>
				</td>
			</tr>
		  </tbody>
		</table>
	</div>
</div>