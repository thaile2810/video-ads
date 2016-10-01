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
       $zController->_data['load_type']         = @$content['load_type'];
       $zController->_data['filter_where']      = @$content['filter_where'];
       $zController->_data['video_16']      	= @$content['video_16'];
       $zController->_data['scroll_total_item'] = @$content['scroll_total_item'];
       $zController->_data['first_post_per_page'] = @$content['first_post_per_page'];
    }
    
    $tax_input  = @$zController->_data['tax_input'];
    $vOrderby   = @$zController->_data['orderby'];
    $vOrdering  = @$zController->_data['ordering'];
    $vItems     = @$zController->_data['items'];
    $vPosition  = @$zController->_data['position'];
    $vTotal     = @$zController->_data['total_item'];
    $category   = @$zController->_data['post_category'];
    $channels   = @$zController->_data['tax_input']['zvideos_channel'];
    $playlist   = @$zController->_data['tax_input']['zvideos_playlist'];
    $authors    = @$zController->_data['authors'];
    $vPageTitle = @$zController->_data['page_title'];
    $vLoadType  = @$zController->_data['load_type'];
    $vReportvideo_16  = @$zController->_data['video_16'];
    $vScroll_Total_item  = @$zController->_data['scroll_total_item'];
    $vFirst_post_per_page = @$zController->_data['first_post_per_page'];
    
	
	//Lay gia trị tham so Page
	$page 	= $zController->getParams('page');
	
	$lbl 	= __('Add a new ShortCode',ZMOVIES_DOMAIN_LANGUAGE);
	
	if($action == 'edit'){
	    $lbl 	= __('Edit a ShortCode',ZMOVIES_DOMAIN_LANGUAGE);
	}
	
	$name 	= $htmlObj->textbox('name',@$vName,array('class'=>'regular-text'));
	
	$options['data'] = [
                	   'member_video'      => 'Show Video Member',
                	   'site_video'        => 'Show Video On Site',
                	   ];
	$pageTitle = $htmlObj->selectbox('page_title',@$vPageTitle,array('class'=>'regular-text', 'id' => 'zvideo-page_title'),$options);
	
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

	$options['data'] = [
                	    '0'        => 'No',
                	    '1'        => 'Yes'
            	       ];
	$video_16 = $htmlObj->selectbox('video_16',@$vReportvideo_16,array('class'=>'regular-text'),$options);

	$filter_where = $htmlObj->selectbox('filter_where',@$vFilter_where,array('class'=>'regular-text'),$options);
	
	$options['data'] = ['ID'           			=> 'ID',
	                   'views'         			=> 'Views',
	                   'date'          			=> 'Date',
	                   'title'         			=> 'Title',
	                   'date_view'     			=> 'Top views in Day',
	                   'week_view'     			=> 'Top views in Week',
	                   'month_view'    			=> 'Top views in Month',
	                   'year_view'     			=> 'Top views in Year',
	                   'random_post_7_day'     	=> 'Random 7 day ago',
	                   'random_post_30_day'     => 'Random 30 day ago',
	                   'random_post_all_day'    => 'All day'
	    
	               ];
	$orderBy = $htmlObj->selectbox('orderby',@$vOrderby,array('class'=>'regular-text'),$options);
	
	$options['data'] = array('asc' => 'ASC','desc'=>'DESC');
	$ordering 	= $htmlObj->selectbox('ordering',@$vOrdering,array('class'=>'regular-text','id'=>'ordering'),$options);
	
	$items 	   = $htmlObj->textbox('items',@$vItems,array('class'=>'regular-text'));
	$total 	   = $htmlObj->textbox('total_item',@$vTotal,array('class'=>'regular-text'));

	$scroll_total_item 	   = $htmlObj->textbox('scroll_total_item',@$vScroll_Total_item,array('class'=>'regular-text'));

	$first_post_per_page 	   = $htmlObj->textbox('first_post_per_page',@$vFirst_post_per_page,array('class'=>'regular-text'));
	
	
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
	$playlistArgs = array(
	    'descendants_and_self'  => 0,
	    'selected_cats'         => $playlist,
	    'popular_cats'          => false,
	    'walker'                => null,
	    'taxonomy'              => 'zvideos_playlist',
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
		
		<h3><?php echo __('Setting',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></h3>
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
						<label><?php echo __('Follow',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
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
						<label><?php echo __('Load type',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $loadType;?></td>
				</tr>
				<tr class="first_post_per_page" style="<?php if($vLoadType == 'paginator') echo 'display: none;';?>">
					<th scope="row">
						<label><?php echo __('First Post of perpage',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $first_post_per_page;?></td>
				</tr>
				<tr">
					<th scope="row">
						<label><?php echo __('Item in page',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $items;?></td>
				</tr>
				<tr class="scroll_total" style="<?php if($vLoadType == 'paginator') echo 'display: none;';?>">
					<th scope="row">
						<label><?php echo __('Scroll total items',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $scroll_total_item;?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Total items',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $total;?></td>
				</tr>
				
			</tbody>
		</table>
		<h3><?php echo __('Filter',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></h3>
		<table class="form-table zmovies-form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php echo __('Videos 16+',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><?php echo $video_16;?></td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php echo __('Categories',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><ul class="zmovies-checklist"><?php wp_terms_checklist( 0, $categoryArgs );?></ul></td>
				</tr>
				<tr class="hidden">
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
						<label><?php echo __('Playlist',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
					</th>
					<td><ul class="zmovies-checklist"><?php wp_terms_checklist( 0, $playlistArgs );?></ul></td>
				</tr>
			</tbody>
		</table>
		<h3><?php echo __('Order By',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></h3>
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
			
			<input name="cancel" id="cancel" class="button button-primary" value="Back" type="submit">
		</p>
	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('select[name="load_type"]').change(function(event) {
			console.log($(this).val());
			if($(this).val() == 'paginator'){
				$('.first_post_per_page').hide();
				$('.scroll_total').hide();
			}else{
				$('.first_post_per_page').show();
				$('.scroll_total').show();
			}
		});

		if($('select[name="load_type"]').val() == 'paginator'){
			$('.first_post_per_page').hide();
			$('.scroll_total').hide();
		}else{
			$('.first_post_per_page').show();
			$('.scroll_total').show();
		}
	});
</script>
