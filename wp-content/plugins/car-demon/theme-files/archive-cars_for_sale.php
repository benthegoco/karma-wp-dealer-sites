<?php
global $car_demon_options;
get_header();
$car_demon_query = car_demon_query_archive();
if (isset($_GET['car'])) {
	if ($_GET['car'] == 1) {
		$car_demon_query = car_demon_query_search();
	}
}
query_posts($car_demon_query);
$total_results = $wp_query->found_posts;
echo car_demon_dynamic_load();
do_action( 'cd_before_content_srp_action', array() );
do_action( 'car_demon_before_main_content' ); //= deprecated
do_action( 'cd_before_content_action' );
	echo $car_demon_options['before_listings'];
	echo car_demon_sorting('achive');
	?>
		<h4 class="results_found"><?php _e('Results Found','car-demon'); echo ': '.$total_results;?></h4>
	<?php 
	echo car_demon_nav('top', $wp_query);
	/*======= Car Demon Loop ======================================================= */
	while ( have_posts() ) : the_post();
		$post_id = $post->ID;
		$html = apply_filters('cd_srp_filter', car_demon_display_car_list($post_id), $post_id );
		$html = apply_filters('car_demon_display_car_list_filter', $html, $post_id ); //= deprecated
		echo $html;
	endwhile; // End the loop. Whew. ?>
	<?php
	echo car_demon_nav('bottom', $wp_query);
do_action( 'car_demon_after_main_content' ); //= deprecated
do_action( 'cd_after_content_action' );
do_action( 'cd_after_content_srp_action', array() );
do_action( 'car_demon_sidebar' ); //= deprecated
do_action( 'cd_sidebar_action' );
get_footer();
?>