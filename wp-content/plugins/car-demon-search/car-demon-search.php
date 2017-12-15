<?php
/*
Plugin Name: Car Demon Pro Search
Plugin URI: http://www.CarDemons.com/
Description:  Advanced Search Form for Car Demon
Author: CarDemons
Version: 1.2.0
Author URI: http://www.CarDemons.com/
WPCD ID: 22
*/

/*
//= Change log
= 1.2.0 =
* Added function get_cds_field_labels() to pull all field labels from Car Demon Settings
* Added constant CDS_NO_S - if defined then drop down default options will not be plural (ie. All Year instead of All Years)
* Added constant CDS_TRIM_LEVEL_LABEL - if defined it will replace default trim level label
* Added constant CDS_LOCATION_LABEL - if defined it will replace default location label

= 1.1.27 =
* Removed old php4 class constructor calls

= 1.1.26 =
* Added js locatization parameter reset_on_back_page
* Added constant CDPS_RESET_ON_BACKPAGE to set reset_on_back_page to true if wanted

= 1.1.25 =
* Changed json path to use media root path

= 1.1.24 =
* Changed engine search to trim. Must define CDPRO_EXTRAS to use.

= 1.1.23 =
* Added constant CD_SEARCH_TRIGGER. If defined it adds car=2 to URL for legacy users.

= 1.1.22 =
* Fixed issue preventing year, price & mileage range sliders from retaining the selected values after the form has been submitted

= 1.1.21 =
* Fixed issue preventing search criteria with a space in their value from not removing when clicking on the remove filter items

= 1.1.2 =
* Patched single location issue

= 1.1.1 =
* Patched js to close drop downs when another drop down is clicked

= 1.1.0 =
* Patched js to reset form if user clicks on browser back button
* Changed server OS check to handle Mac for local development
* Props to Nick Carapanagos for reporting both issues and providing solution when reporting second issue

= 1.0.9 =
* Add constant CDPRO_EXTRAS
* If defined true then trim_level and transmission become valid fields

= 1.0.8 =
* Forced preset body style value to support search_dropdown_body & search_body_style query parameters

= 1.0.7 =
* Localized All, All Makes & All Models

= 1.0.6 =
* Minor adjustment to js to respect 'ALL' fields as blank values
* Adjusted js to make sure cnt is always a number

= 1.0.5 =
Added currency options to price slider
Currency symbol before and after now appear in slider box

= 1.0.4 =
* Changed all cds_ references to cdsf_
= 1.0.3 =
* Changed cdsf_activate() to cdsf_activate() so it doesn't collide with Pro Shortcode

= 1.0.2 =
* Added class to item count cdii_count
* Adjusted method used to count totals
* Added 'year' field
* Added ability to set default value for fields
* Added style #4

= 1.0.1 =
* Feature - Style can now be selected on a per form basis
* Option to not load unused styles

= 1.0.0 =
* Initial release
*/

//define( 'CDPRO_EXTRAS', true );

require_once( 'admin/cdsf_admin.php' );
require_once( 'includes/search_form.php' );
require_once( 'includes/widget.php' );
require_once( 'includes/shortcode.php' );
require_once( 'includes/cdsf_build_cache.php' );
require_once( 'includes/cdsf_cache.php' );
require_once( 'includes/cdsf_query.php' );

add_action( 'admin_enqueue_scripts', 'cdsf_admin_enqueue_style' );
function cdsf_admin_enqueue_style() {
	wp_enqueue_style('cds-admin-css', plugins_url().'/car-demon-search/css/cds-admin.css');	
}

add_action( 'wp_enqueue_scripts', 'cdsf_enqueue_style' );
function cdsf_enqueue_style() {
	$min_max_values = get_option('cdsf_min_max_values', true);

	$min_max_values_array = array(
		'search_dropdown_Min_years' => 'min_year',
		'search_dropdown_Max_years' => 'max_year',
		'search_dropdown_miles_Min' => 'min_miles',
		'search_dropdown_miles_Max' => 'max_miles',
		'search_dropdown_Min_price' => 'min_price',
		'search_dropdown_Max_price' => 'max_price',
	);

	foreach( $min_max_values_array as $key=>$value ) {
		if ( isset( $_GET[ $key ] ) ) {
			if ( ! empty( $_GET[ $key ] ) ) {
				$min_max_values[ $value . '_start' ] = sanitize_text_field( $_GET[ $key ] );
			} else {
				$min_max_values[ $value . '_start' ] = $min_max_values[ $value ];
			}
		} else {
			$min_max_values[ $value . '_start' ] = $min_max_values[ $value ];
		}
	}

	$save_json = get_option('save_json', '0');
	$cdsf_hide_count = get_option('cdsf_hide_count', 'off');
	//= Determine what css to load or not to load
	$cdsf_use_css_jqueryui = get_option('cdsf_use_css_jqueryui', '1');
	if ($cdsf_use_css_jqueryui == 1) {
		wp_enqueue_style('jquery-ui-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');
	}
	$cdsf_use_css_form = get_option('cdsf_use_css_form', '1');

	if ($cdsf_use_css_form == '1') {
		wp_enqueue_style('cds-form-css', plugins_url().'/car-demon-search/css/cds-form.css');
		$use_drop_downs = true;
	} else if ($cdsf_use_css_form == '2') {
		wp_enqueue_style('cds-form-css-2', plugins_url() .'/car-demon-search/css/cds-form_2.css');
		$use_drop_downs = false;
	} else if ($cdsf_use_css_form == '3') {		
		wp_enqueue_style('cds-form-css-3', plugins_url().'/car-demon-search/css/cds-form_3.css');
		$use_drop_downs = true;
	} else if ($cdsf_use_css_form == '4') {		
		wp_enqueue_style('cds-form-css-4', plugins_url().'/car-demon-search/css/cds-form_4.css');
		$use_drop_downs = true;
	} else {
		// load CSS for all 3
		wp_enqueue_style('cds-form-css', plugins_url().'/car-demon-search/css/cds-form.css');
		wp_enqueue_style('cds-form-css-2', plugins_url() .'/car-demon-search/css/cds-form_2.css');
		wp_enqueue_style('cds-form-css-3', plugins_url().'/car-demon-search/css/cds-form_3.css');
		wp_enqueue_style('cds-form-css-4', plugins_url().'/car-demon-search/css/cds-form_4.css');
		$use_drop_downs = false;
	}

	//= Load and localize js files as needed
	$blog_id = get_current_blog_id();
	global $car_demon_options;
	if (isset($car_demon_options['currency_symbol'])) {
		$currency_before = $car_demon_options['currency_symbol'];
	} else {
		$currency_before = "$";
	}
	if (isset($car_demon_options['currency_symbol_after'])) {
		$currency_after = $car_demon_options['currency_symbol_after'];
	} else {
		$currency_after = "";
	}	

	$upload_dir = wp_upload_dir();
	$json_url = $upload_dir['baseurl'];

	$reset_on_back_page = false;

	if ( defined( 'CDPS_RESET_ON_BACKPAGE' ) ) {
		$reset_on_back_page = CDPS_RESET_ON_BACKPAGE;
	}

	$field_labels = get_cds_field_labels( true );

	wp_register_script( 'car-demon-search-js', plugins_url() . '/car-demon-search/js/cds.js', array( 'jquery', 'jquery-ui-core' ) );
	wp_localize_script( 'car-demon-search-js', 'cdProSearchParams', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'jsonurl' => trailingslashit( $json_url ) . 'inventory'.$blog_id.'.txt',
		'car_demon_path' => CAR_DEMON_PATH,
		'site_url' => get_bloginfo('wpurl'),
		'min_price' => $min_max_values['min_price'],
		'min_price_start' => $min_max_values['min_price_start'],
		'max_price' => $min_max_values['max_price'],
		'max_price_start' => $min_max_values['max_price_start'],
		'min_miles' => $min_max_values['min_miles'],
		'min_miles_start' => $min_max_values['min_miles_start'],
		'max_miles' => $min_max_values['max_miles'],
		'max_miles_start' => $min_max_values['max_miles_start'],
		'min_year' => $min_max_values['min_year'],
		'min_year_start' => $min_max_values['min_year_start'],
		'max_year' => $min_max_values['max_year'],
		'max_year_start' => $min_max_values['max_year_start'],
		'save_cache' => $save_json,
		'items_per_page' => 12,
		'num_display_entries' => 3,
		'num_edge_entries' => 2,
		'prev_text' => '&lt;',
		'next_text' => '&gt;',
		'is_home' => is_front_page(),
		'all_prices' => 1,
		'cdsf_all' => '',
		'blog_id' => $blog_id,
		'use_drop_downs' => $use_drop_downs,
		'hide_count' => $cdsf_hide_count,
		'currency_before' => $currency_before,
		'currency_after' => $currency_after,
		'all_str' => __('ALL', 'car-demon-search'),
		'all_makes' => $field_labels['make'],
		'all_models' => $field_labels['model'],
		'all_trim_levels' => $field_labels['trim_level'],
		'all_transmissions' => $field_labels['transmission'],
		'reset_on_back_page' => $reset_on_back_page,
		'labels' => $field_labels,
	));
	wp_enqueue_script( 'car-demon-search-js' );
	
}

register_activation_hook( __FILE__, 'cdsf_activate' );
function cdsf_activate() {
	set_transient( '_cdsf_welcome_screen_activation_redirect', true, 30 );
//	cdsf_build_cache();
}
add_action( 'admin_init', 'cdsf_welcome_screen_do_activation_redirect' );
function cdsf_welcome_screen_do_activation_redirect() {
	// Bail if no activation redirect
	if ( ! get_transient( '_cdsf_welcome_screen_activation_redirect' ) ) {
		return;
	}
	// Delete the redirect transient
	delete_transient( '_cdsf_welcome_screen_activation_redirect' );
	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}
	// Redirect to bbPress about page
	wp_safe_redirect( add_query_arg( array( 'page' => 'cdsf_options', 'post_type' => 'cars_for_sale' ), admin_url( 'edit.php' ) ) );
}

/* Display a notice that can be dismissed */
add_action( 'admin_notices', 'cdsf_admin_notice' );
add_action( 'network_admin_notices', 'cdsf_admin_notice' );
function cdsf_admin_notice() {
	if (isset($_SERVER['SERVER_ADDR'])) {
		$server_hash = base64_encode($_SERVER['SERVER_ADDR']);
	} else {
		$server_hash = 'unk';	
	}
	$home_hash = 'MTAuMTc2LjE2MS4zO2Q==';
	if ($server_hash == $home_hash) {
		//= If server is authenticated as home then do not display notice
		return;	
	}
	if ( !class_exists( 'CARDEMONS_Update_Notifications' ) && current_user_can( 'install_plugins' ) ) {
		global $current_user ;
			$user_id = $current_user->ID;
			/* Check that the user hasn't already clicked to ignore the message */
		if ( ! get_user_meta($user_id, 'cdsf_ignore_notice') ) {
			//= Don't show on settings page
			if (isset($_GET['page'])) {
				if ($_GET['page'] == 'car_demon_settings_options') {
					return;
				}
			}
			echo '<div class="updated"><p>';
				_e('Thank\'s for installing the Car Demon Pro Search PlugIn!', 'car-demon-search');
				echo '<br />';
				echo '<b>';
					_e('Don\'t forget to visit the ', 'car-demon-search');
					echo '<a href="edit.php?post_type=cars_for_sale&page=cdsf_options">';
						_e('settings page ', 'car-demon-search');
					echo '</a>';
					_e('to configure your PlugIn ', 'car-demon-search');
				echo '</b>';
				_e('and get usage information on the [pro_search] shortcode and the included widget.', 'car-demon-search');
				echo '<br />';
				echo '<a href="http://cardemons.com/" target="cduwin">CarDemons.com</a>| <a href="?cdsf_nag_ignore=0">Hide Notice</a>';
			echo "</p></div>";
		}
	}
}
add_action( 'admin_init', 'cdsf_nag_ignore' );
function cdsf_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['cdsf_nag_ignore']) && '0' == $_GET['cdsf_nag_ignore'] ) {
             add_user_meta($user_id, 'cdsf_ignore_notice', 'true', true);
	}
}

//cd_fix_price();
function cd_fix_price() {
	global $wpdb;

	$sql = "UPDATE $wpdb->postmeta SET meta_value='0' where meta_key='_price_value' and meta_value='Contact for Price'";
	$x = $wpdb->query( $sql );
	echo '<pre>';
		print_r($sql);
	echo '</pre>';
}

function get_cds_field_labels( $add_all = false ) {
	$field_labels = get_default_field_labels();
	$field_labels['trim_level'] = __( 'Trim Level', 'car-demon-search' );
	$field_labels['location'] = __( 'Location', 'car-demon-search' );

	if ( defined( 'CDS_TRIM_LEVEL_LABEL' ) ) {
		$field_labels['trim_level'] = CDS_TRIM_LEVEL_LABEL;
	}

	if ( defined( 'CDS_LOCATION_LABEL' ) ) {
		$field_labels['trim_level'] = CDS_LOCATION_LABEL;
	}

	foreach( $field_labels as $label=>$key ) {
		$field_labels[ $label ] = ( $add_all ? __( 'ALL ', 'car-demon-search') : '') . strtoupper( $key ) . ( ! defined( 'CDS_NO_S' ) ? 'S' : '');
	}

	return $field_labels;
}
?>