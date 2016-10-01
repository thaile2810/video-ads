<?php 
	global $zController,$htmlObj;
	
	$action = ($zController->getParams('action') != '')? $zController->getParams('action'):'add';
	//Lay gia trị tham so Page
	$page 	= $zController->getParams('page');
	$lbl 	= __('Add a new ShortCode',ZMOVIES_DOMAIN_LANGUAGE);
	
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
	<form method="post" action="" id="<?php echo $page;?>" enctype="multipart/form-data">
		<input name="action" value="<?php echo $action;?>" type="hidden">				
		<?php wp_nonce_field($action,'security_code',true);?>
		<div id="poststuff">
			<div class="metabox-holder" id="post-body">
				<div id="postbox-container-2" class="postbox-container">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<?php $zController->getView('/short-code/form-channel.php','/backend');?>
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
