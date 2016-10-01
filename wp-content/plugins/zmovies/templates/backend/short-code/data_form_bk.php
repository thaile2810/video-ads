<?php 
	global $zController,$htmlObj;
	
	$action = ($zController->getParams('action') != '')? $zController->getParams('action'):'add';
	
	
	$vName 		= sanitize_text_field(@$zController->_data['name']);
    $vCode		= @$zController->_data['code'];
    if($action == 'edit' && $zController->isPost() == false){              
       $content                                 = unserialize(@$zController->_data['content']);
       $zController->_data['tax_input']         = @$content['tax_input'];
       $zController->_data['orderby']           = @$content['orderby'];
       $zController->_data['ordering']          = @$content['ordering'];
       $zController->_data['items']             = @$content['items'];
       $zController->_data['position']          = @$content['position'];
       $zController->_data['post_category']     = @$content['post_category'];
       $zController->_data['total_item']        = @$content['total_item'];
       $zController->_data['page_title']        = @$content['page_title'];
       $zController->_data['load_type']        = @$content['load_type'];
    }
    
    $tax_input  = @$zController->_data['tax_input'];
    $vOrderby   = @$zController->_data['orderby'];
    $vOrdering  = @$zController->_data['ordering'];
    $vItems     = @$zController->_data['items'];
    $vPosition  = @$zController->_data['position'];
    $vTotal     = @$zController->_data['total_item'];
    $category   = @$zController->_data['post_category'];
    $channels   = @$zController->_data['channels'];
    $authors       = @$zController->_data['authors'];
    $vPageTitle   = @$zController->_data['page_title'];
    $vLoadType   = @$zController->_data['load_type'];
    
	
	//Lay gia trị tham so Page
	$page 	= $zController->getParams('page');
	
	$lbl 	= __('Add a new ShortCode',ZMOVIES_DOMAIN_LANGUAGE);
	
	if($action == 'edit'){
	    $lbl 	= __('Edit a ShortCode',ZMOVIES_DOMAIN_LANGUAGE);
	}
	
	$name 	= $htmlObj->textbox('name',@$vName,array('class'=>'regular-text'));
	
	$options['data'] = [
	                   'name'          => 'Follow Name',
                	   'tag'           => 'Tag',
                	   'category'      => 'Category',
                	   'member'        => 'All member',
                	   'system'        => 'System',
                	   ];
	$pageTitle = $htmlObj->selectbox('page_title',@$vPageTitle,array('class'=>'regular-text'),$options);
	
	$code 	= @$vCode;
	
	//$options['data'] = array('content' => 'In Content','sidebar'=>'In Sidebar','footer'=>'In Footer');
	$options['data'] = array('content' => 'In Content','sidebar'=>'In Sidebar');
	$position = $htmlObj->selectbox('position',@$vPosition,array('class'=>'regular-text'),$options);
	
	$options['data'] = [
                	    'paginator'        => 'Paginator',
                	    'load-more'        => 'Load more',
                	    'next-preview'     => 'Next & Preview',
            	       ];
	$loadType = $htmlObj->selectbox('load_type',@$vLoadType,array('class'=>'regular-text'),$options);
	
	$options['data'] = ['ID'           => 'ID',
	                   'views'         => 'Views',
	                   'date'          => 'Date',
	                   'title'         => 'Title',
	                   'date_view'     => 'Top views in Day',
	                   'week_view'     => 'Top views in Week',
	                   'month_view'    => 'Top views in Month',
	                   'year_view'     => 'Top views in Year',
	    
	               ];
	$orderBy = $htmlObj->selectbox('orderby',@$vOrderby,array('class'=>'regular-text'),$options);
	
	$options['data'] = array('asc' => 'ASC','desc'=>'DESC');
	$ordering 	= $htmlObj->selectbox('ordering',@$vOrdering,array('class'=>'regular-text','id'=>'ordering'),$options);
	
	$items 	   = $htmlObj->textbox('items',@$vItems,array('class'=>'regular-text'));
	$total 	   = $htmlObj->textbox('total_item',@$vTotal,array('class'=>'regular-text'));
	
	
	$msg = '';
	if($zController->getParams('msg') == 1){
	    $msg .='<div class="updated"><p>Update finish</p></div>';
	}
	
	
	$categoryArgs = array(
            	'descendants_and_self'  => 0,
            	'selected_cats'         => $category,
            	'popular_cats'          => false,
            	'walker'                => null,
            	'taxonomy'              => 'category',
            	'checked_ontop'         => true
            );
	$channelArgs = array(
	    'descendants_and_self'  => 0,
	    'selected_cats'         => $channels,
	    'popular_cats'          => false,
	    'walker'                => null,
	    'taxonomy'              => 'zvideos_channel',
	    'checked_ontop'         => true
	);
	$authorArgs = array(
            	    /* 'show_option_none'     => 'Select authors',
            	    'option_none_value'    => 0, */
            	    'selected'             => $authors,
            	    'multi'                => 1,
            	    'name'                => 'authors[]',
            	    'class'                => 'regular-text',
            	    'id'                => 'display_name',
            	   );
	add_filter('wp_dropdown_users', 'z_user_multiple');
	
	function z_user_multiple($output){
	    return str_replace('<select', '<select multiple', $output);
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
	<form method="post" action="" id="<?php echo $page;?>" enctype="multipart/form-data">
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
						<label><?php echo __('Page Title',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $pageTitle;?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Position',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $position;?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Categories',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><ul class="zmovies-checklist"><?php wp_terms_checklist( 0, $categoryArgs );?></ul></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Authors',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php wp_dropdown_users($authorArgs);?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Channels',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><ul class="zmovies-checklist"><?php wp_terms_checklist( 0, $channelArgs );?></ul></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Load type',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $loadType;?></td>
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
				<tr>
					<th scope="row">
						<label><?php echo __('Item in page',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $items;?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Total items',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $total;?></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input name="save" id="save" class="button button-primary" value="Save" type="submit">
			
			<input name="cancel" id="cancel" class="button button-primary" value="Back" type="submit">
		</p>
	</form>
</div>
