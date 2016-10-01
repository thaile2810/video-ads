<?php 
global $htmlObj,$wpdb;

$data               = @$this->_data['pagination'];
$data['posts_per_page'] = @$this->_data['posts_per_page'];

$inputId            = ZMOVIES_SETTING_OPTION . '[pagination][page_mid_size]';
$value              = (!empty(@$data['page_mid_size'])) ? @$data['page_mid_size'] : 5;
$videoPageMidSize   = $htmlObj->textbox($inputId,$value,['class'=>'regular-text']);

$inputId            = ZMOVIES_SETTING_OPTION . '[pagination][page_end_size]';
$value              = (!empty(@$data['page_end_size'])) ? @$data['page_end_size'] : 1;
$videoPageEndSize   = $htmlObj->textbox($inputId,$value,['class'=>'regular-text']);


$option = [];

for($i=1; $i<=20; $i++){
	$x = $i*4;
	$options['data'][$x] = $x.' item';
}

$inputId            = ZMOVIES_SETTING_OPTION . '[pagination][first_post_per_page]';
$value              = (!empty(@$data['first_post_per_page'])) ? @$data['first_post_per_page'] : '4';
$first_post_per_page   = $htmlObj->selectbox($inputId,$value,['class'=>'regular-text'],$options);

$inputId            = ZMOVIES_SETTING_OPTION . '[pagination][page_limit_scroll]';
$value              = (!empty(@$data['page_limit_scroll'])) ? @$data['page_limit_scroll'] : '4';
$videoLimitScroll  = $htmlObj->selectbox($inputId,$value,['class'=>'regular-text'],$options);


$inputId            = 'posts_per_page';
$value              = (!empty(@$data['posts_per_page'])) ? @$data['posts_per_page'] : '4';
$videoPostOfPerpage  = $htmlObj->selectbox($inputId,$value,['class'=>'regular-text'],$options);

$options['data'] = [
                	    'paginator'        => 'Paginator',
                	    'load-more'        => 'Load more',
                	    'next-preview'     => 'Next & Preview',
            	       ];
$inputId            = ZMOVIES_SETTING_OPTION . '[pagination][page_load_type]';
$value              = (!empty(@$data['page_load_type'])) ? @$data['page_load_type'] : 'paginator';
$videoPageLoadType  = $htmlObj->selectbox($inputId,$value,['class'=>'regular-text'],$options);

$lbl 	= __('Default',ZMOVIES_DOMAIN_LANGUAGE);
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
					<label><?php echo __('First Post of perpage',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $first_post_per_page;?>
				   <p><?php echo __('First Post of perpage',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('Post of perpage',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $videoPostOfPerpage;?>
				   <p><?php echo __('Post of perpage',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('Limit Scroll',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $videoLimitScroll;?>
				   <p><?php echo __('Limit scroll',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('Pagination type',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $videoPageLoadType;?>
				   <p><?php echo __('Pagination type',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('Mid size',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $videoPageMidSize;?>
				   <p><?php echo __('How many numbers to either side of current page, but not including current page',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php echo __('End size',ZMOVIES_DOMAIN_LANGUAGE) . ':';?></label>
				</th>
				<td>
				   <?php echo $videoPageEndSize;?>
				   <p><?php echo __('How many numbers on either the start and the end list edges',ZMOVIES_DOMAIN_LANGUAGE);?></p>
				</td>
			</tr>
		  </tbody>
		</table>
	</div>
</div>