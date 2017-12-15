<?php
	/*
		Template Name: Inventory template
	*/
get_header(); ?>

<div class="pageCenter">
	<h2><?php the_title(); ?></h2>
	<hr />

	<div class="main_container">
		<div class="sidebar">
			<?php
				if (function_exists('dynamic_sidebar')) {
					dynamic_sidebar('side_panel');
				}
			?>
		</div>
		<div class="carlist">
			<?php
				while(have_posts()) {
					the_post();
			?>
			<p><?php the_content(); ?></p>
			<?php
				}

				if (function_exists('dynamic_sidebar')) {
					dynamic_sidebar("main_panel");
				}
			?>
		</div>
	</div>
</div>


<?php get_footer(); ?>

