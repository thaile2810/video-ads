<?php
global $zController;
$topView = $zController->getHelper('TopView');
$model = $zController->getModel('Video');
?>

<div class="row zItems">
      <div class="item-group-title"><h2><i class="fa fa-film"></i><?php echo $this->_nameSc;?></h2></div>
<?php
while ($this->_wpQuery->have_posts()) {
    $this->_wpQuery->the_post();
    $post = $this->_wpQuery->post;
    
    $title = mb_substr(get_the_title(), 0,60);
    
    $date = date('Y-m-d', strtotime($post->post_date));
    
    $src = $model->getImageUrl($post->ID,'mqdefault',$date);
    //$src = $model->getImageUrl($post->ID, 'mqdefault');
    $view = $topView->getView($post);
    
?>

<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 item">
	<a href="<?php the_permalink();?>" title="<?php the_title();?>" class="thumbnail">
	   <img src="<?php echo $src;?>" alt="<?php the_title();?>">
   </a>
	<div class="caption">
		<h3 class="title">
			<a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php echo $title;?></a>
		</h3>
		<p><?php echo $view['total'];?> views - <?php the_modified_date();?></p>
	</div>
</div>
<?php 
    } 
    
?>
</div>
<div class="row">
    <div class="col-lg-12 img-loading-more">
        <img src="<?php echo ZVIDEO_THEME_IMG.'/loading.gif';?>" class="" alt="Loading more..."/>
    </div>
</div>
<div class="row zPaging" style="margin-right: 10px">
        <?php
            if($this->_data['load_type'] == 'paginator'){
                echo paging($this->_wpQuery);
            }elseif($this->_data['load_type'] == 'load-more'){
                echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn-loadmore">
                        <a data-href="load-more-shortcode" data-post-position="'.$this->_data['first_post_per_page'].'" data-short-id="'.$this->_data['short_id'].'" data-post-limit="'.$this->_data['items'].'" data-total-post= "'.$this->_data['total_item'].'" data-scroll-total="'.$this->_data['scroll_total_item'].'" class="btn-loadmore-button">Load more</a>
                    </div>';
            }
         ?>
</div>
<?php wp_reset_query();?>