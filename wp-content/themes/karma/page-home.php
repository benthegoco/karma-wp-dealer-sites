<?php
	/*
		Template Name: Home template
	*/
get_header(); ?>

	<div class="pageCenter">
<?php
	while(have_posts()) {
		the_post();
?>
		<p><?php the_content(); ?></p>
<?php
	}
?>
	</div>

<?php
	include(locate_template('template_part/map.php'));
?>

<?php get_footer(); ?>

