<?php get_header(); ?>
 <?php 
 	while(have_posts()): the_post();
 	the_title();
 	endwhile;
 ?>

<?php get_footer(); ?>