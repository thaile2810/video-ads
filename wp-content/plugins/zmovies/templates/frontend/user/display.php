
<?php 

	global $zController,$htmlObj;
	$action = ($zController->getParams('action') != '')? $zController->getParams('action'):'add';  
	
	
	//Lay gia trị tham so Page
	$page 	= $zController->getParams('page');	
	$lbl 	= __('Add a new Video',ZMOVIES_DOMAIN_LANGUAGE);	
	
	

	$vTitle          = @$zController->_data['post_title'];
	$title 	         = $htmlObj->textbox('post_title',$vTitle,array('class'=>'form-control'));
	
	$vUrl            = @$zController->_data['post_url'];
	$url             = $htmlObj->textbox('post_url',$vUrl,array('class'=>'form-control'));
	
	$vContent        = @$zController->_data['post_content'];
	$content 	     = $htmlObj->textarea('post_content',$vContent,array('class'=>'form-control','cols' => 46, 'rows' => 5));	
	
	
	
	
	$msg = '';
	if($zController->getParams('msg') == 1){
	    $msg .='<div class="updated"><p>Update finish</p></div>';
	}
	
	$msg = '';
	if(count($zController->_error)>0){
	    $msg .= '<div class="alert alert-danger"><ul>';
	    foreach ($zController->_error as $key => $val){
	        $msg .= '<li>' . __(ucfirst($key),ZMOVIES_DOMAIN_LANGUAGE) . ': ' . __($val,ZMOVIES_DOMAIN_LANGUAGE) . '</li>';
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
	<form method="post" action="#" id="add-video-form" enctype="multipart/form-data">
		<input name="action" value="<?php echo $action;?>" type="hidden">				
		<?php wp_nonce_field($action,'security_code',true);?>		
		<p></p>
		
		<div class="form-group">
					<label for="first-name"><?php echo __('Title', ZMOVIES_DOMAIN_LANGUAGE) . ':'; ?></label>
					<?php echo $title;?>
		</div>
		<div class="form-group">
					<label for="first-name"><?php echo __('Url', ZMOVIES_DOMAIN_LANGUAGE) . ':'; ?></label>
					<?php echo $url;?>
		</div>
		
		<div class="form-group">
					<label for="first-name"><?php echo __('Content', ZMOVIES_DOMAIN_LANGUAGE) . ':'; ?></label>
					<?php echo $content;?>
		</div>		
		
		<p class="submit">
			<center><input name="save" id="save" class="btn btn-primary" value="Save" type="submit"></center>			
		</p>
	</form>
</div>
