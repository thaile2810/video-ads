<?php 
	global $zController;
	$tblManufacturer = $zController->getModel('ShortCode');	
	$tblManufacturer->prepare_items();
	
	$lbl = __('Short Codes',ZMOVIES_DOMAIN_LANGUAGE);
	
	$page = $zController->getParams('page');
	
	$linkAdd = admin_url('admin.php?page=' . $page . '&action=add') ;
	$lblAdd = __('Add a ShortCode',ZMOVIES_DOMAIN_LANGUAGE);
	
	$msg = '';
	if($zController->getParams('msg') == 1){
		$msg .='<div class="updated"><p>Update finish</p></div>';
	}
	
?>
<div class="wrap">
	<h2><?php echo esc_html__($lbl);?>
		<a href="<?php echo esc_url($linkAdd);?>" class="add-new-h2"><?php echo esc_html__($lblAdd);?></a>
	</h2>
	<?php echo $msg;?>
	<form action="" method="post" name="<?php echo $page;?>" id="<?php echo $page;?>">
	<?php $tblManufacturer->search_box('search', 'search_id');?>
	<?php $tblManufacturer->display();?>
	</form>
</div>