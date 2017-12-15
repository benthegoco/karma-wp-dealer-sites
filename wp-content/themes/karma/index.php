<?php get_header(); ?>

	<div class="pageCenter">
<?php
	while(have_posts()) {
		the_post();
?>
		<h2><?php the_title(); ?></h2>
		<hr />
		<p><?php the_content(); ?></p>
<?php
	}
?>
	</div>

<?php get_footer(); ?>

