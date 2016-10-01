<?php 
global $zController;
$lbl       = __('Settings',ZMOVIES_DOMAIN_LANGUAGE);
$page      = $zController->getParams('page');
$action    = ($zController->getParams('action') != '')? $zController->getParams('action'):'add';

$msg = '';
if($zController->getParams('msg') == 1){
    $msg .='<div class="updated"><p>Update finish</p></div>';
}
?>
<div class="wrap">
	<h2><?php echo $lbl;?></h2>
	<?php echo $msg;?>
	<form method="post" action="" id="<?php echo $page;?>" enctype="multipart/form-data">
		<input name="action" value="<?php echo $action;?>" type="hidden">				
		<?php wp_nonce_field($action,'security_code',true);?>
		<div id="poststuff">
			<div class="metabox-holder" id="post-body">
				<div id="postbox-container-2" class="postbox-container">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<?php $zController->getView('/setting/form-offline.php','/backend');?>
						<?php $zController->getView('/setting/form-player.php','/backend');?>
						<?php $zController->getView('/setting/form-video.php','/backend');?>
						<?php $zController->getView('/setting/form-pagination.php','/backend');?>
						<?php $zController->getView('/setting/form-pagination-cate.php','/backend');?>
						<?php $zController->getView('/setting/form-pagination-channel.php','/backend');?>
						<?php $zController->getView('/setting/form-pagination-playlist.php','/backend');?>
						<?php $zController->getView('/setting/form-facebook.php','/backend');?>
						<?php $zController->getView('/setting/form-google.php','/backend');?>
						<?php $zController->getView('/setting/form-author.php','/backend');?>
						<?php $zController->getView('/setting/form-footer.php','/backend');?>
						<?php //$zController->getView('/setting/form-comment.php','/backend');?>
						<p class="submit">
                			<input name="save" id="save" class="button button-primary" value="Save Settings" type="submit">
                		</p>
					</div>
				</div>
			</div>
			<!-- /post-body -->
			<br class="clear">
		</div>
		<!-- /poststuff -->
	</form>
</div>