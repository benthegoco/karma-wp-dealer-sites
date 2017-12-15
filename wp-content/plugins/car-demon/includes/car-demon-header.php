<?php
add_action( 'wp_ajax_cd_compare_handler', 'cd_compare_handler' );
add_action( 'wp_ajax_nopriv_cd_compare_handler', 'cd_compare_handler' );

add_action( 'wp_ajax_cd_get_compare_list', 'cd_get_compare_list' );
add_action( 'wp_ajax_nopriv_cd_get_compare_list', 'cd_get_compare_list' );

/**
 * Load front end styles
 *
 * @todo
 *     - replace car-demon.css.php with static file
 *     - use theme_mods() for dynamic style options
 */
function car_demon_header() {
	global $car_demon_options;
	if ( isset( $car_demon_options['use_vehicle_css'] ) ) {
		if ( $car_demon_options['use_vehicle_css'] != 'No' ) {
			wp_enqueue_style( 'car-demon-css', CAR_DEMON_PATH . 'theme-files/css/car-demon.css.php', array(), CAR_DEMON_VER );
			wp_enqueue_style( 'car-demon-style-css', CAR_DEMON_PATH . 'theme-files/css/car-demon-style.css', array(), CAR_DEMON_VER );
		}
	} else {
		wp_enqueue_style( 'car-demon-css', CAR_DEMON_PATH . 'theme-files/css/car-demon.css.php', array(), CAR_DEMON_VER );
		wp_enqueue_style( 'car-demon-style-css', CAR_DEMON_PATH . 'theme-files/css/car-demon-style.css', array(), CAR_DEMON_VER );
	}
	?>
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo CAR_DEMON_PATH; ?>theme-files/css/car-demon-ie.css" />
	<![endif]-->
	<!--[if IE 8]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo CAR_DEMON_PATH; ?>theme-files/css/car-demon-ie.css" />
	<![endif]-->
	<?php
	if (isset($car_demon_options['use_form_css'])) {
		if ($car_demon_options['use_form_css'] != 'No') {
			wp_enqueue_style( 'car-demon-search-css', plugins_url() . '/car-demon/search/css/car-demon-search.css', array(), CAR_DEMON_VER );
		}
	} else {
		wp_enqueue_style( 'car-demon-search-css', plugins_url() . '/car-demon/search/css/car-demon-search.css', array(), CAR_DEMON_VER );
	}
}
add_filter( 'wp_print_styles', 'car_demon_header' );

/**
 * Load front end scripts
 *
 * @todo
 *     - replace vanilla js with jQuery functions
 */
function cd_enqueue_scripts() {
	global $car_demon_options;
	wp_enqueue_script( 'cd-jquery-lightbox-js', CAR_DEMON_PATH . 'theme-files/js/jquery.lightbox_me.js', array('jquery'), CAR_DEMON_VER );
	wp_register_script( 'cd-js', CAR_DEMON_PATH . 'theme-files/js/car-demon.js', array('jquery') );
	wp_localize_script( 'cd-js', 'cdParams', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'css_url' => get_bloginfo( 'stylesheet_url' ),
		'cd_path' => CAR_DEMON_PATH
	));
	wp_enqueue_script( 'cd-js' );

	if ( $car_demon_options['use_compare'] == 'Yes' ) {
		wp_register_script( 'cd-compare-js', CAR_DEMON_PATH . 'theme-files/js/car-demon-compare.js', array(), CAR_DEMON_VER );
		wp_localize_script( 'cd-compare-js', 'cdCompareParams', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'msg1' => __( 'Compare Vehicles', 'car-demon' ),
			'css_url' => get_bloginfo( 'stylesheet_url' )
		));
		wp_enqueue_script( 'cd-compare-js' );
	}
	car_demon_search_cars_scripts();
}
add_action( 'wp_enqueue_scripts', 'cd_enqueue_scripts');

?>