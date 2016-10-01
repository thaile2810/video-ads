<?php 
	global $zController,$htmlObj;
	
	$action = ($zController->getParams('action') != '')? $zController->getParams('action'):'add';
	
	/* echo '<pre>';
	print_r($zController->_data);
	echo '</pre>'; */
	if($action == 'edit'){
    	$vName 		= sanitize_text_field($zController->_data['name']);
    	$vCode		= $zController->_data['code'];
    	$content    = unserialize($zController->_data['content']);
    	echo '<pre>';
    	print_r($content);
    	echo '</pre>';
	}
	
	//Lay gia trị tham so Page
	$page 	= $zController->getParams('page');
		
	
	
	$lbl 	= __('Add new a Type',ZMOVIES_DOMAIN_LANGUAGE);	
	
	$name 	= $htmlObj->textbox('name',@$vName,array('class'=>'regular-text'));
	
	$code 	= @$vCode;
	
	$options['data'] = array('id' => 'ID','views'=>'Views','date'=>'Date','name'=>'Name');
	$orderBy = $htmlObj->selectbox('orderby',@$vOrderby,array('class'=>'regular-text'),$options);
	
	$options['data'] = array('asc' => 'ASC','desc'=>'DESC');
	$ordering 	= $htmlObj->selectbox('ordering',@$vOrdering,array('class'=>'regular-text','id'=>'ordering'),$options);
	
	$msg = '';
	if(count($zController->_error)>0){
	    $msg .= '<div class="error"><ul>';
	    foreach ($zController->_error as $key => $val){
	        $msg .= '<li>' . ucfirst($key) . ': ' . $val . '</li>';
	    }
	    $msg .= '</ul></div>';
	}else{
	
	}
	
	if($zController->getParams('msg') == 1){
	    $msg .='<div class="updated"><p>Update finish</p></div>';
	}
	
	/* $args  = array('hide_empty'=>false);
	$terms = get_terms( 'zmovies_category',$args );
	echo '<pre>';
	print_r($terms);
	echo '</pre>'; */
	
	$args = array(
	    'show_option_all'    => 'Select All',
	    'show_option_none'   => 0,
	    'option_none_value'  => '-1',
	    'orderby'            => 'NAME',
	    'order'              => 'ASC',
	    'show_count'         => 1,
	    'hide_empty'         => 0,
	    'child_of'           => 0,
	    'exclude'            => '',
	    'echo'               => 0,
	    'selected'           => '52,60',
	    'hierarchical'       => 1,
	    'name'               => 'category[]',
	    'id'                 => 'category_id',
	    'class'              => 'category-id chosen-select ',
	    'depth'              => 0,
	    'tab_index'          => 0,
	    'taxonomy'           => 'zmovies_category',
	    'hide_if_empty'      => false,
	    'value_field'	     => 'term_id',
	);
	
	$category	=  wp_dropdown_categories( $args );
	
	$args = array(
	    'show_option_all'    => 'Select All',
	    'show_option_none'   => 0,
	    'option_none_value'  => '-1',
	    'orderby'            => 'NAME',
	    'order'              => 'ASC',
	    'show_count'         => 1,
	    'hide_empty'         => 0,
	    'child_of'           => 0,
	    'exclude'            => '',
	    'echo'               => 0,
	    'selected'           => 0,
	    'hierarchical'       => 0,
	    'name'               => 'types[]',
	    'id'                 => 'types_id',
	    'class'              => 'types-id chosen-select ',
	    'depth'              => 0,
	    'tab_index'          => 0,
	    'taxonomy'           => 'zmovies_types',
	    'hide_if_empty'      => false,
	    'value_field'	     => 'term_id',
	);
	
	$types	=  wp_dropdown_categories( $args );
	
	$args = array(
	    'show_option_all'    => 'Select All',
	    'show_option_none'   => 0,
	    'option_none_value'  => '-1',
	    'orderby'            => 'NAME',
	    'order'              => 'ASC',
	    'show_count'         => 1,
	    'hide_empty'         => 0,
	    'child_of'           => 0,
	    'exclude'            => '',
	    'echo'               => 0,
	    'selected'           => 0,
	    'hierarchical'       => 0,
	    'name'               => 'status[]',
	    'id'                 => 'status_id',
	    'class'              => 'status-id chosen-select ',
	    'depth'              => 0,
	    'tab_index'          => 0,
	    'taxonomy'           => 'zmovies_status',
	    'hide_if_empty'      => false,
	    'value_field'	     => 'term_id',
	);
	
	$status	=  wp_dropdown_categories( $args );
	
	$args = array(
	    'show_option_all'    => 'Select All',
	    'show_option_none'   => 0,
	    'option_none_value'  => '-1',
	    'orderby'            => 'NAME',
	    'order'              => 'ASC',
	    'show_count'         => 1,
	    'hide_empty'         => 0,
	    'child_of'           => 0,
	    'exclude'            => '',
	    'echo'               => 0,
	    'selected'           => 0,
	    'hierarchical'       => 0,
	    'name'               => 'seasons[]',
	    'id'                 => 'seasons_id',
	    'class'              => 'seasons-id chosen-select ',
	    'depth'              => 0,
	    'tab_index'          => 0,
	    'taxonomy'           => 'zmovies_seasons',
	    'hide_if_empty'      => false,
	    'value_field'	     => 'term_id',
	);
	
	$seasons	=  wp_dropdown_categories( $args );
	
	$args = array(
	    'show_option_all'    => 'Select All',
	    'show_option_none'   => 0,
	    'option_none_value'  => '-1',
	    'orderby'            => 'NAME',
	    'order'              => 'ASC',
	    'show_count'         => 1,
	    'hide_empty'         => 0,
	    'child_of'           => 0,
	    'exclude'            => '',
	    'echo'               => 0,
	    'selected'           => 0,
	    'hierarchical'       => 0,
	    'name'               => 'year[]',
	    'id'                 => 'year_id',
	    'class'              => 'year-id chosen-select ',
	    'depth'              => 0,
	    'tab_index'          => 0,
	    'taxonomy'           => 'zmovies_year',
	    'hide_if_empty'      => false,
	    'value_field'	     => 'term_id',
	);
	
	$year	=  wp_dropdown_categories( $args );
	
	
	
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
						<label><?php echo __('Short code',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $code;?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Categories',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $category;?></td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo __('Status',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $status;?></td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo __('Seasons',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $seasons;?></td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo __('Year',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $year;?></td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo __('Types',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label></th>
					<td><?php echo $types;?></td>
				</tr>
			</tbody>
		</table>
		<h3>Ordering:</h3>
		<table class="form-table zmovies-form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php echo __('Order By',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $orderBy;?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Ordering',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $ordering;?></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input name="save" id="save" class="button button-primary" value="Save" type="submit">
			<input name="save-close" id="save-close" class="button button-primary" value="Save and close" type="submit">
			<input name="cancel" id="cancel" class="button button-primary" value="Back" type="submit">
		</p>
	</form>
</div>
