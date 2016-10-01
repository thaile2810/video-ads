<?php 
	global $zController,$htmlObj;
	$AdsHelper = $zController->getHelper('Area');
	$action = ($zController->getParams('action') != '')? $zController->getParams('action'):'add';  
	
	//Lay gia trị tham so Page
	$page 	= $zController->getParams('page');	
	$lbl 	= __('Add a new Ads',ZMOVIES_DOMAIN_LANGUAGE);	
	if($action == 'edit'){
	    $lbl 	= __('Edit a Ads',ZMOVIES_DOMAIN_LANGUAGE);
	}
	

	$vName           = @$zController->_data['name'];
	$name 	         = $htmlObj->textbox('name',$vName,array('class'=>'regular-text'));
	
	$vContent        = @$zController->_data['content'];
	$vContent        = str_replace('\"', '"', $vContent);
	$vContent        = str_replace("\'", "'", $vContent);
	$content 	     = $htmlObj->textarea('content',$vContent,array('class'=>'regular-text','cols' => 46, 'rows' => 5));
	

	$vArea           = @$zController->_data['area'];
	$options['data'] = $AdsHelper->getArea();
	$area            = $htmlObj->selectbox('area',$vArea,array('class'=>'regular-text'),$options);
	
	$vOrdering       = @$zController->_data['ordering'];
	$ordering 	     = $htmlObj->textbox('ordering',$vOrdering,array('class'=>'regular-text','id'=>'ordering'));
	
	$vStatus         = @$zController->_data['status'];
	$options['data'] = array(null => __('Select a Status',ZMOVIES_DOMAIN_LANGUAGE), 'inactive' => __('Inactive',ZMOVIES_DOMAIN_LANGUAGE),'active' => __('Active',ZMOVIES_DOMAIN_LANGUAGE));
	$status          = $htmlObj->selectbox('status',$vStatus,array('class'=>'regular-text'),$options);
	
	
	$msg = '';
	if($zController->getParams('msg') == 1){
	    $msg .='<div class="updated"><p>Update finish</p></div>';
	}
	
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
	<form method="post" action="" id="<?php echo $page;?>"
		enctype="multipart/form-data">
		<input name="action" value="<?php echo $action;?>" type="hidden">				
		<?php wp_nonce_field($action,'security_code',true);?>
		
		<h3>Filter:</h3>
		<p></p>
		<table class="form-table zmovies-form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php echo __('Name',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $name;?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Content',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $content;?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Area',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $area;?></td>
				</tr>
				
				<tr>
					<th scope="row">
						<label><?php echo __('Ordering',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $ordering;?></td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo __('Status',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $status;?></td>
				</tr>								
			</tbody>
		</table>
		
		<p class="submit">
			<input name="save" id="save" class="button button-primary" value="Save" type="submit">
			
			<input name="cancel" id="cancel" class="button button-primary" value="Back" type="submit">
		</p>
	</form>
</div>
