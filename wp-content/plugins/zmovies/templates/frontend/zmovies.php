<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package movies
 */
get_header();
?>

<?php 
   
        //require_once 'zmovies-view.php';
    
        //require_once 'zmovies-sumary.php';
        global $zController;
        $zController->getController('Product','/frontend');
  
?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>