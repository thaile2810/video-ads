<?php 
	global $zController;
	$tblAds = $zController->getModel('Ads');	
	$tblAds->prepare_items();
	
	$lbl = __('Ads manager',ZMOVIES_DOMAIN_LANGUAGE);
	
	$page = $zController->getParams('page');
	
	$linkAdd = admin_url('admin.php?page=' . $page . '&action=add') ;
	$lblAdd = __('Add a Ads',ZMOVIES_DOMAIN_LANGUAGE);
	
	$msg = '';
	if($zController->getParams('msg') == 1){
		$msg .='<div class="updated"><p> ' . __('Update finish',ZMOVIES_DOMAIN_LANGUAGE) . '</p></div>';
	}
	
?>
<div class="wrap">
	<h2><?php echo esc_html__($lbl);?>
		<a href="<?php echo esc_url($linkAdd);?>" class="add-new-h2"><?php echo esc_html__($lblAdd);?></a>
	</h2>
	<?php echo $msg;?>
	<form action="" method="post" name="<?php echo $page;?>" id="<?php echo $page;?>">
	<?php $tblAds->search_box('search', 'search_id');?>
	<?php $tblAds->display();?>
	</form>
</div>