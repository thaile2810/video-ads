<?php 
    global $zController;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ('post' == get_post_type()) { ?> 
		<div class="entry-meta">
			<?php bootstrapBasicPostOn(); ?> 
		</div><!-- .entry-meta -->
		<?php } //endif; ?> 
	</header><!-- .entry-header -->

	
	<?php if (is_search()) { // Only display Excerpts for Search ?> 
	<div class="entry-summary">
		<?php the_excerpt(); ?> 
		<div class="clearfix"></div>
	</div><!-- .entry-summary -->
	<?php } else { ?> 
	<div class="entry-content">
		<?php the_content(bootstrapBasicMoreLinkText()); ?> 
		<div class="clearfix"></div>
		<?php 
		/**
		 * This wp_link_pages option adapt to use bootstrap pagination style.
		 * The other part of this pager is in inc/template-tags.php function name bootstrapBasicLinkPagesLink() which is called by wp_link_pages_link filter.
		 */
		wp_link_pages(array(
			'before' => '<div class="page-links">' . __('Pages:', 'bootstrap-basic') . ' <ul class="pagination">',
			'after'  => '</ul></div>',
			'separator' => ''
		));
		?> 
	</div><!-- .entry-content -->
	<?php } //endif; ?> 
	<footer class="entry-meta">
		<?php if ('post' == get_post_type()) { // Hide category and tag text for pages on Search ?> 
		<div class="entry-meta-category-tag">
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list(__(', ', 'bootstrap-basic'));
				if (!empty($categories_list)) {
			?> 
			<span class="cat-links">
				<?php echo bootstrapBasicCategoriesList($categories_list); ?> 
			</span>
			<?php } // End if categories ?> 

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list('', __(', ', 'bootstrap-basic'));
				if ($tags_list) {
			?> 
			<span class="tags-links">
				<?php echo bootstrapBasicTagsList($tags_list); ?> 
			</span>
			<?php } // End if $tags_list ?> 
		</div><!--.entry-meta-category-tag-->
		<?php } // End if 'post' == get_post_type() ?> 

		<div class="entry-meta-comment-tools">			
			<?php bootstrapBasicEditPostLink(); ?>
		    <?php             		  
    		  $Tags = get_the_terms(get_the_ID(), 'post_tag' );
    		  $strTags = '';
    		  if(!empty($Tags)){
    		      foreach ( $Tags as $key => $tag ){
    		          $strTags .= ' <a href="' . get_term_link( $tag->slug, 'post_tag' ) . '">' . $tag->name .'</a>,';
    		      }               		  
    		      $strTags = rtrim($strTags, ",");
    		  }
    		
    		  $jwPlayer = $zController->getHelper('JwPlayer');
            ?>
			 <div class="row">
	             <div class="col-sm-12 col-md-12 player">
    	             <div id="player"></div>
    	             <?php $jwPlayer->play(get_the_ID());?>
    			 </div>
			
        			<div class="infomation">
                    <?php 
                        $termHelper = $zController->getHelper('GetTerms');                   
                        $cats       = $termHelper->get(get_the_ID(),'category');
                        $channel    = $termHelper->get(get_the_ID(),'zvideos_channel');
                        $user       = $termHelper->get(get_the_ID(),'zvideos_youtube_user');
                        $playlist   = $termHelper->get(get_the_ID(),'zvideos_playlist');
                        
                    ?>
                    <ul class="info">	
                        <?php if(!empty($cats)):?>			    
        			    <li><span>Categories:</span> <?php echo $cats;?></li>
        			    <?php endif;?>
        			    <?php if(!empty($channel)):?>
        			    <li><span>Channel:</span> <?php echo $channel;?></li>
        			    <?php endif;?>
        			     <?php if(!empty($user)):?>
        			    <li><span>Youtube User:</span> <?php echo $user;?></li>
        			    <?php endif;?>
        			     <?php  if(!empty($playlist)):?>
        			    <li><span>Playlist:</span> <?php echo $playlist;?></li> 
        			    <?php endif;?>  
        			</ul>
        			<div class="content">
        	              <?php the_excerpt();?>
        	            </div>
        			<div class='description'>
        	            <!-- excerpt -->
        	            <!-- tag -->
        	            <?php if(!empty($strTags)):?>
        	            <div class="row tags" > 
        	              <span><strong>Video Tags:</strong></span> <?php echo $strTags;?>		             
        	            </div>
        	            <?php endif;?>
        	            <!-- tag -->
        	        </div>
        		  </div>
        		  <div class="row zmovies-facebook-comment"></div>
        		  <?php echo do_shortcode('[zvideo_fb_share data-href="' . get_the_permalink() . '" data-layout="button_count"]'); ?>
        	       <?php echo do_shortcode('[zvideo_fb_comment data-href="' . get_the_permalink() . '" data-numposts="5"]'); ?>
        	</div>
		</div><!--.entry-meta-comment-tools-->
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->