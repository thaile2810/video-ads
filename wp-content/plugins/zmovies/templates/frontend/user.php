<?php
    if(!is_user_logged_in ()){
        auth_redirect();
    } 
    get_header();
    /**
     * determine main column size from actived sidebar
     */
    $main_column_size = bootstrapBasicGetMainColumnSize();
    
    
   
    ?>
    <?php get_sidebar('left'); ?> 
    				<div class="col-md-<?php echo $main_column_size; ?> content-area" id="main-column">
    					<main id="main" class="site-main" role="main">
    						<?php 
    						
    							global $zController,$htmlObj;
    							$obj = $zController->getController('User','/frontend'); 
    
    							echo "\n\n";
    						?> 
    					</main>
    				</div>
    <?php get_sidebar('right'); ?> 
    <?php get_footer(); ?> 
    
	
	
	
	