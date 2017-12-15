<?php
/*
Plugin Name: Car Demon Pro Shortcode
Plugin URI: http://CarDemons.com/
Description: A shortcode to display the Pro Car Demon inventory pages
Version: 1.2.9
Author: Car Demon
Author URI: http://CarDemons.com
WPCD ID: 20


== Changelog ==
= 1.2.9 =
*
* Added constant CDSP_SRP_SIDEBAR - when defined it adds space on the SRP for sidebar or trade/finance/warranty buttons
*
= 1.2.8 =
*
* Replaced custom taxonomy calls with CD function: get_cd_term( $post_id, $term );
*
= 1.2.7 =
*
* Added constant CDS_EXTRA_STYLES to turn on beta option for styles #2 & #3 for the VDP
* Made changes to cdsp_tag_filter() function so it strips 'decoded_' from all keys
*
= 1.2.6 =
*
* CSS Image files are now copied to wp_upload_dir/cds_images/ on activation
* CSS Files are now generated on activation unless CDSP_DYNAMIC_CSS is defined
* Single vehicle page's print now requires print add-on to show
* Added constant CDS_PRINT_PDF, if defined and print add-on is installed it will trigger a PDF for printing rather than HTML
*
= 1.2.5 =
*
* Added constant CDSP_DYNAMIC_CSS to use css.php files rather than generate CSS from them
*
= 1.2.4 =
*
* Add vehicle_tag to query
*
= 1.2.3 =
*
* Updated string translations for boolean settings
* Updated price filters
*
= 1.2.2 =
*
* Updated js for default VDP to use margin-left for slider instead of left
*
= 1.2.1 =
*
* Localized jscolor.js and deprecated jscolor.js.php
*
= 1.2.0 =
*
* Added SRP Style #9
* Added VDP Style #3 - use define('CD_STYLE_3', true) to activate
*
= 1.1.91 =
*
* Added isset check for $cdsp_theme_options['finance_button'] and $cdsp_theme_options['trade_button']
*
= 1.1.9 =
* Add post_id to vehicle listing filters
= 1.1.8 =
* Added single page background to pull color from settings
* Added ability to use constant CD_CUSTOM_NO_PHOTO so users can add their own custom "no photo found" image.
= 1.1.7 =
* Added the following constants to change labels
* CD_SHORTCODE_FINANCE_LABEL CD_SHORTCODE_TRADE_IN_LABEL CD_SHORTCODE_WARRANTY_LABEL
* CD_SHORTCODE_VIEW_DETAILS_LABEL CD_SHORTCODE_CONTACT_LABEL
* Added the following constant, if set true then View Details button will show on style 5
* CD_SHORTCODE_5_VIEW_DETAILS
* Added following filter for style 5 apply_filters( 'cds_contact_link', $contact_url, $post_id, $template );
* Use to filter the contact button
* Added constant CD_STYLE_5_POPUP, if set true contact button on style 5 opens a popup vehicle contact form
* Added constant CDS_CSS_VER to add unique string behind CSS filename
= 1.1.6 =
* Minor updates to Single Vehicle Style #2
= 1.1.5 =
* Added Mammon theme support
= 1.1.4 =
* Changed css & js file path variable
= 1.1.3 =
* Changed main image calls to cd_main_photo( $post_id )
= 1.1.2 =
* Now allows currency before and currency after to both display at same time
= 1.1.1 =
* Added single style 2 - define constant to activate
* define('CD_STYLE_2', true);
* Added constants to attempt force full width for style 2
* define('CD_FULL_WIDTH', true);
* define('CD_FULL_WIDTH_ALL', true);
* Constants will be removed and option area added after testing
= 1.1.0 =
* Added list style #8
= 1.0.9 =
* Adjusted #demon-container css rule to max-width: 100%;
= 1.0.8 =
* Dynamic css & js now create static files when settings are saved
* Minor style adjustments
= 1.0.7 =
* Refining responsive CSS for styles 4, 5 & 6
= 1.0.6 =
* Refining responsive CSS for style 3
= 1.0.5 =
* Fixed translation issue on single vehicle page
= 1.0.4 =
* Improved localization - added initial .po file
= 1.0.3 =
* Added currency settings to templates
= 1.0.2 =
* Adding responsive CSS for style #2
= 1.0.1 =
* Added additional styles
= 1.0.0 =
* Initial release

Use these tags in the Single Vehicle Widget areas to get the links to each:
	{finance_link} - will be replaced in a text widget with the finance_link
	{trade_link} - will be replaced in a text widget with the trade_link

*/

/*
* Adds the defined content to end of CSS filename
*/
if ( ! defined( 'CDS_CSS_VER' ) ) {
	define( 'CDS_CSS_VER', '1.2.9' );
}

//= Set to true forces dynamic css.php files to be used
//define( 'CDSP_DYNAMIC_CSS', true );
//define('CD_STYLE_2', true);
//define('CD_STYLE_3', true);
//define( 'CDS_EXTRA_STYLES', true );
//define( 'CDSP_SRP_SIDEBAR', true );

require_once('shortcode/cd-shortcode.php');
require_once('admin/admin.php');
require_once('loops/common.php');
require_once('loops/archive.php');
require_once('loops/single.php');
require_once('loops/search.php');
require_once('templates/common.php');
require_once('templates/template_1.php');
require_once('templates/template_2.php');
require_once('templates/template_3.php');
require_once('templates/template_4.php');
require_once('templates/template_5.php');
require_once('templates/template_6.php');
// template 7 is the original default archive template
require_once('templates/template_8.php');
require_once('templates/template_9.php');
// single 1 is the original default single vehicle page
require_once('templates/single/css/color_styles.php');
require_once('templates/single/single_2.php');
require_once('templates/single/single_3.php');

if ( ! defined( 'CD_SHORTCODE_5_VIEW_DETAILS' ) ) {
	define( 'CD_SHORTCODE_5_VIEW_DETAILS', true );
}

if ( ! defined( 'CD_SHORTCODE_FINANCE_LABEL' ) ) {
	define( 'CD_SHORTCODE_FINANCE_LABEL', __( 'Get Financed', 'car-demon-shortcode' ) );
}

if ( ! defined( 'CD_SHORTCODE_TRADE_IN_LABEL' ) ) {
	define( 'CD_SHORTCODE_TRADE_IN_LABEL', __( 'Trade In', 'car-demon-shortcode' ) );
}

if ( ! defined( 'CD_SHORTCODE_WARRANTY_LABEL' ) ) {
	define( 'CD_SHORTCODE_WARRANTY_LABEL', __( 'Warranty', 'car-demon-shortcode' ) );
}

if ( ! defined( 'CD_SHORTCODE_VIEW_DETAILS_LABEL' ) ) {
	define( 'CD_SHORTCODE_VIEW_DETAILS_LABEL', __( 'View More Details', 'car-demon-shortcode' ) );
}

if ( ! defined( 'CD_SHORTCODE_CONTACT_LABEL' ) ) {
	define( 'CD_SHORTCODE_CONTACT_LABEL', __( 'Contact Us', 'car-demon-shortcode' ) );
}

add_action( 'after_setup_theme', 'cd_shortcode_language' );
function cd_shortcode_language(){
    load_plugin_textdomain('car-demon-shortcode', false, plugin_basename( dirname( __FILE__ ) ) . '/languages');
}

add_action( 'wp_enqueue_scripts', 'cdsp_templates_enqueue_scripts' );
function cdsp_templates_enqueue_scripts() {
	global $car_demon_options;
	$cds_options = cdsp_template_options();

	if ( defined( 'CDSP_DYNAMIC_CSS' ) ) {
		wp_enqueue_style('cdsp-css', plugins_url().'/car-demon-shortcode/css/car-demon-theme-css.php', CDS_CSS_VER);
	} else {
		$upload_dir = wp_upload_dir();
		$path = trailingslashit( $upload_dir['baseurl'] );
	
		wp_enqueue_style('cdsp-css', $path .'car-demon-theme-' . CDS_CSS_VER . '.css', CDS_CSS_VER);
	}

	wp_enqueue_style("cd-shortcode-css", plugins_url().'/car-demon-shortcode/css/cdf.css');

	wp_enqueue_style('cdsp-1', plugins_url().'/car-demon-shortcode/templates/css/cd_1.css');

	wp_enqueue_style('cdsp-2', plugins_url().'/car-demon-shortcode/templates/css/cd_2.css');

	wp_enqueue_style('cdsp-3', plugins_url().'/car-demon-shortcode/templates/css/cd_3.css');

	wp_enqueue_style('cdsp-4', plugins_url().'/car-demon-shortcode/templates/css/cd_4.css');

	wp_enqueue_style('cdsp-5', plugins_url().'/car-demon-shortcode/templates/css/cd_5.css');

	wp_enqueue_style('cdsp-6', plugins_url().'/car-demon-shortcode/templates/css/cd_6.css');
	
	wp_enqueue_style('cdsp-8', plugins_url().'/car-demon-shortcode/templates/css/cd_8.css');
	
	wp_enqueue_style('cdsp-9', plugins_url().'/car-demon-shortcode/templates/css/cd_9.css');
	
	// Single Page Styles
	if ( isset( $cds_options['cds_cdp_style'] ) ) {
		if ( $cds_options['cds_cdp_style'] === '2' ) {
			wp_enqueue_style('cdsps-2', plugins_url().'/car-demon-shortcode/templates/single/css/cd_single_2.css');
			define( 'CD_STYLE_2', true );
		}
		if ( $cds_options['cds_cdp_style'] === '3' ) {
			wp_enqueue_style('cdsps-3', plugins_url().'/car-demon-shortcode/templates/single/css/cd_single_3.css');
			define( 'CD_STYLE_3', true );
		}
	}

	wp_enqueue_script('car-demon-shortcode-js', plugins_url().'/car-demon-shortcode/js/cdf.js');
	if ( ! function_exists('cd_pro_activate') && ! function_exists('cd_mammon_activate') ) {
		wp_enqueue_script('car-demon-shortcode-move-js', plugins_url().'/car-demon-shortcode/js/jquery.event.move.js');
		wp_enqueue_script('car-demon-shortcode-swipe-js', plugins_url().'/car-demon-shortcode/js/jquery.event.swipe.js');
		wp_enqueue_script('car-demon-shortcode-swipe-config-js', plugins_url().'/car-demon-shortcode/js/car-demon-pro-swipe.js');
		wp_enqueue_script('car-demon-shortcode-navigation', plugins_url().'/car-demon-shortcode/js/navigation.js', array(), '20120206', true);
		
		if ( defined( 'CDSP_DYNAMIC_CSS' ) ) {
			wp_enqueue_style('car-demon-single-car-css-php', plugins_url() . '/car-demon-shortcode/css/single-cars-for-sale.css.php');
		} else {
			wp_enqueue_style('car-demon-single-car-css-php', $path . 'single-cars-for-sale.css');
		}
	}
	
	if ( isset( $car_demon_options['use_vehicle_css'] ) ) {
		if ( $car_demon_options['use_vehicle_css'] != 'No' ) {
			if ( ! function_exists( 'cd_pro_activate' ) ) {
				wp_enqueue_style('car-demon-vin-query-css', plugins_url() . '/car-demon/vin-query/css/car-demon-vin-query.css');
				wp_enqueue_style('car-demon-single-car-css', plugins_url() . '/car-demon/theme-files/css/car-demon-single-car.css');
			}
		}
	} else {
		if ( ! function_exists( 'cd_pro_activate' ) ) {
			wp_enqueue_style('car-demon-vin-query-css', plugins_url() . '/car-demon/vin-query/css/car-demon-vin-query.css');
			wp_enqueue_style('car-demon-single-car-css', plugins_url() . '/car-demon/theme-files/css/car-demon-single-car.css');
		}
	}
	if ( ! function_exists('cd_pro_activate')) {
		wp_enqueue_script( 'cd-jquery-lightbox-js', CAR_DEMON_PATH . 'theme-files/js/jquery.lightbox_me.js', array('jquery'), CAR_DEMON_VER );
		wp_register_script('car-demon-single-car-js', plugins_url() . '/car-demon/theme-files/js/car-demon-single-cars.js');
		wp_localize_script('car-demon-single-car-js', 'cdSingleCarParams', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'car_demon_path' => CAR_DEMON_PATH,
			'site_url' => get_bloginfo('wpurl')
		));
		wp_enqueue_script('car-demon-single-car-js');
	}
}
/* Display a notice that can be dismissed */
add_action( 'admin_notices', 'cdsp_admin_notice' );
add_action( 'network_admin_notices', 'cdsp_admin_notice' );
function cdsp_admin_notice() {
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
		if ( ! get_user_meta($user_id, 'cdsp_ignore_notice') ) {
			//= Don't show on settings page
			if (isset($_GET['page'])) {
				if ($_GET['page'] == 'car_demon_settings_options') {
					return;
				}
			}
			echo '<div class="updated"><p>';
				_e('Thanks for installing the Car Demon Pro Shortcode PlugIn!', 'car-demon-shortcode');
				echo '<br />';
				_e('Use [pro_inventory style=0] with all the normal parameters (like year, make, model, etc.)', 'car-demon-shortcode');
				echo '<br />';
				_e('Set style to any number between 0 and 6 to switch layouts.', 'car-demon-shortcode');
				echo '<br />';
				echo '<a href="http://cardemons.com/" target="cduwin">CarDemons.com</a>| <a href="?post_type=cars_for_sale&cdsp_nag_ignore=0">Hide Notice</a>';
			echo "</p></div>";
		}
	}
}
add_action( 'admin_init', 'cdsp_nag_ignore' );
function cdsp_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['cdsp_nag_ignore']) && '0' == $_GET['cdsp_nag_ignore'] ) {
             add_user_meta($user_id, 'cdsp_ignore_notice', 'true', true);
	}
}

add_filter( 'car_demon_disclaimer_text_filter', 'cdsp_disclaimer', 10, 1 );
function cdsp_disclaimer($content) {
	$content = str_replace( '/', '', $content );
	return $content;
}

function cdsp_single( $content, $post_id ) {
	$theme = wp_get_theme();
	$theme_name = $theme->name;
	if ( ( $theme_name == 'Car Demon Pro' || $theme_name == 'Car Demon Azrael' || $theme_name == 'Car Demon Mammon' ) && ! defined('CD_STYLE_2') ) {
		return $content;
	}
	if ( defined( 'CD_STYLE_3' ) ) {
		$atts = array(
			'single' => 3
		);
	} else if ( defined( 'CD_STYLE_2' ) ) {
		$atts = array(
			'single' => 2
		);
	} else {
		$atts = array(
			'single' => 1
		);
	}

	ob_start();
		echo car_demon_photo_lightbox();
		do_action( 'car_demon_before_main_content' );
		do_action( 'car_demon_vehicle_header_sidebar' );
			$post_id = get_the_ID();
			if (isset($atts['single'])) {
				if ($atts['single'] == 3) {
					$html = cd_single_3($post_id);
				} else if ($atts['single'] == 2) {
					$html = cd_single_2($post_id);
				} else {
					// load default template
					$html = cds_display_single_car($post_id);
				}
			} else {
				// load default template
				$html = cds_display_single_car($post_id);
			}
			echo $html;
		do_action( 'car_demon_after_main_content' );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;	
}
add_filter( 'car_demon_single_car_filter', 'cdsp_single', 10, 2 );

function cdsp_activate() {
	$cd_cdrf_options = array();
	$cd_cdrf_options = get_option( 'car_demon_options' );
	$cd_cdrf_options['use_theme_files'] = 'No';
	$cd_cdrf_options['use_vehicle_css'] = 'No';
	$cd_cdrf_options['use_form_css'] = 'No';
	$cd_cdrf_options['cd_cdrf_style'] = 'content-replacement';
	$cd_cdrf_options['cd_cdrf_page_style'] = 'content-replacement';
	$cd_cdrf_options['use_session'] = 'No';
	cdsp_copy_images();
	if ( ! defined( 'CDSP_DYNAMIC_CSS' ) ) {
		cdsp_update_dynamic_css();
	}
	update_option( 'car_demon_options', $cd_cdrf_options );
	set_transient( '_cdsp_welcome_screen_activation_redirect', true, 30 );
}
register_activation_hook( __FILE__, 'cdsp_activate' );

function cdsp_welcome_screen_do_activation_redirect() {
  // Bail if no activation redirect
    if ( ! get_transient( '_cdsp_welcome_screen_activation_redirect' ) ) {
    return;
  }
  // Delete the redirect transient
  delete_transient( '_cdsp_welcome_screen_activation_redirect' );
  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }
  // Redirect to about page
  wp_safe_redirect( add_query_arg( array( 'page' => 'cdsp_template_options', 'post_type' => 'cars_for_sale' ), admin_url( 'edit.php' ) ) );
}
add_action( 'admin_init', 'cdsp_welcome_screen_do_activation_redirect' );

function cdps_setup() {
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'vehicle-widget', 286, 214, true );
	add_image_size( 'vehicle-srp', 218, 164, true );
	add_image_size( 'vehicle-main', 340, 256, true );
	add_image_size( 'vehicle-thumbnail', 97, 72, true );
}
add_action( 'after_setup_theme', 'cdps_setup' );

function cdsp_copy_images() {
	$upload_dir = wp_upload_dir();
	$path = trailingslashit( $upload_dir['basedir'] );
	$path .= trailingslashit( 'cds_images' );
	$images = array(
		'under_menu.jpg',
		'body_style_content.jpg',
		'btn_go_back.png',
		'btn_go_forward.png',
		'lite-green-check.png',
		'body_style_content.jpg',
		'widget_title_back.png',
		'vehicle_stock.png',
		'under_content.jpg',
		'search_btn_bck.png',
		'calculator_icon.jpg',
		'cars-for-sale-title-arrow.png',
		'cars-for-sale-option-arrow.png',
		'social_email_icon.jpg',
		'social_email_label.jpg',
		'social_print_icon.jpg',
		'social_print_label.jpg',
		'social_facebook.jpg',
		'social_g_plus.jpg',
		'social_twitter.jpg',
		'social_pintrest.jpg',
		'single-car-enlarge.jpg',
	);

	$plugin_path = plugin_dir_path( __FILE__ );
	$plugin_path .= trailingslashit( 'images' );

	//= does images folder exist?
	if ( ! file_exists( $path ) ) {
		mkdir( $path, 0755 );
	}
	
	//= loop images and copy each one to images directory
	foreach( $images as $image ) {
		copy( $plugin_path . $image, $path . $image );
	}
	
}

// Text Swapping
function cdso_translate_text( $translated ) {
//	$translated = str_ireplace('Exterior', 'Buitenkant', $translated);
//	$translated = str_ireplace('Color', 'Kleur', $translated);
	return $translated;
}

//= Text replacement
add_filter('gettext', 'cdso_translate_text'); 
add_filter('ngettext', 'cdso_translate_text');

?>