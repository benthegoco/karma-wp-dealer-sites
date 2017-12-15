<?php
/**
* Plugin Name: Car Demon
* Plugin URI: http://CarDemons.com/
* Description:  Car Demon is a PlugIn designed for car dealers and other vehicle sales.
* Author: CarDemons
* Version: 1.7.6
* Author URI: http://CarDemons.com/
* Text Domain: car-demon
* Domain Path: /languages
* License: GPL2
* WPCD ID: 101
*/

/**
 * Return 403 error if loaded directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * @const CAR_DEMON_VER
 */
define( 'CAR_DEMON_VER', '1.7.6' );

/**
 * Define the path to Car Demon PlugIn Folder
 */
$car_demon_pluginpath = plugins_url() . '/car-demon/';

/**
 * @const CAR_DEMON_PATH
 */
define( 'CAR_DEMON_PATH', $car_demon_pluginpath );

/**
 * Define default condition
 *
*/
if ( ! defined( 'CD_DEFAULT_CONDITION' ) ) {
	define( 'CD_DEFAULT_CONDITION', __( 'Preowned', 'car-demon' ) );
}

/**
 * Init Functions
 *
 * @todo
 *     - Provide option to load sales affiliate feature
 *     - Do not load affiliate feature by default
 */
require_once( 'includes/cd-init.php' );

/**
 * Load core styles and scripts
 *
 * @todo
 *     - Refine and minimize all CSS and JS
 */
require_once( 'includes/car-demon-header.php' );

/**
 * Load Car Demon Custom Query
 *
 * @todo
 *     - Consolidate search and archive queries into one function
 *     - Move sort function into its own file
 */
require_once( 'includes/car-demon-query.php' );

/**
 * Register custom post type and taxonomies
 *
 */
require_once( 'includes/create-post-types-tax.php' );

/**
 * Template redirect if included template files are used
 *
 * @todo
 *     - Depracate option to use included template files
 */
require_once( 'includes/cd-template-redirect.php' );

/**
 * Register custom sidebar areas
 *
 */
require_once( 'includes/register-sidebars.php');

/**
 * Function to return vehicle price
 *
 * @todo
 *     - Ability to return price parts as array for templating
 */
require_once( 'includes/vehicle-price.php' );

/**
 * Function to display archive vehicle item
 *
 * Function to load vehicle ribbon
 *
 * @todo
 *     - Document functions
 *     - Move ribbon functions to own file
 */
require_once( 'includes/list-cars.php' );

/**
 * Functions for compare vehicle feature
 *
 * @todo
 *     - Document functions
 */
require_once( 'includes/compare-vehicles.php' );

/**
 * Functions for car contact information
 *
 * @todo
 *     - Document functions
 */
require_once( 'includes/get-contact-info.php' );

/**
 * Functions for adding fields to user edit page for staff card management
 *
 * @todo
 *     - Document functions
 *     - Security check on feature to make sure only admins can modify information
 *     - Create admin option to disable affiliate sales feature
 *     - Review full feature set
 */
require_once( 'includes/user-control.php' );

/**
 * Functions for building staff page
 *
 * @todo
 *     - Document functions
 */
require_once( 'includes/staff-pages.php' );

/**
 * Functions for dynamic auto loading inventory
 *
 * @todo
 *     - Document functions
 */
require_once( 'includes/dynamic-load.php' );

/**
 * Functions that supply template parts for vehicle display
 *
 * Function car_demon_get_car( $post_id ) for returning vehicle data array
 *
 * @todo
 *     - Document functions
 */
require_once( 'includes/cd-template.php' );

/**
 * Functions to manage shortcodes
 *
 * @todo
 *     - Document functions
 */
require_once( 'includes/shortcodes.php' );

/**
 * TGM-Plugin-Activation configuration
 */
require_once( 'includes/suggested_required.php' );

/**
 * Functions for payement calculator widget
 *
 * @todo
 *     - Document functions
 *     - Revise calculator template
 */
require_once( 'includes/payment-calculator.php' );

/**
 * Functions to build vehicle search forms
 *
 * @todo
 *     - Document functions
 *     - Review feature set
 */
require_once( 'search/search-form.php' );

/**
 * Functions to build fields for vehicle search forms
 *
 * @todo
 *     - Document functions
 *     - Review feature set
 */
require_once( 'search/search-fields.php' );

/**
 * Functions for Car Demon admin settings
 *
 * @todo
 *     - Document functions
 *     - Follow WordPress coding standards in admin required files - add spaces to parameters and functions
 *     - Reorganize settings in more logical fashion
 *     - Make initial setup as quick and easy as possible
 *     - Add Edmund's decode option
 */
require_once( 'admin/cd-admin.php' );

/**
 * Calculator widget
 *
 * @todo
 *     - Document functions
 */
require_once( 'widgets/calculator-widget.php' );

/**
 * Tag cloud widget
 *
 * @todo
 *     - Document functions
 *     - Review feature to make more efficient
 *     - With large number of vehicles this features causes system slow down
 */
require_once( 'widgets/tag-cloud.php' );

/**
 * Random vehicle widget
 *
 * @todo
 *     - Document functions
 *     - Revise template
 */
require_once( 'widgets/random-cars.php' );

/**
 * Vehicle search widget
 *
 * @todo
 *     - Document functions
 */
require_once( 'widgets/car-search-widget.php' );

/**
 * Compare vehicle widget
 *
 * @todo
 *     - Document functions
 */
require_once( 'widgets/compare-widget.php' );

/**
 * Vehicle contact widget
 *
 * @todo
 *     - Document functions
 */
require_once( 'widgets/vehicle-contact-widget.php' );

/**
 * VinQuery vin decode functions
 *
 * @todo
 *     - Document functions
 *     - Revise feature set
 */
require_once( 'vin-query/vin-query.php' );

/**
 * Vehicle tab management code
 *
 * @todo
 *     - Document functions
 */
require_once( 'includes/vehicle-tabs.php' );

/**
 * Default vehicle options
 *
 * @todo
 *     - Document functions
 */
require_once( 'includes/vehicle-options.php' );

/**
 * Functions to load Car Demon forms
 *
 * @todo
 *     - Document functions
 *     - Follow WordPress coding standards in required files - add spaces to parameters and functions
 *     - Revise templates
 *     - Review hooks and filters
 */
require_once( 'car-demon-forms/car-demon-forms.php' );

/**
 * Functions for vehicle display when not using included template files
 *
 * @todo
 *     - Document functions
 *     - Follow WordPress coding standards in required files - add spaces to parameters and functions
 *     - Reorganize files and functions
 *     - Full feature review
 */
require_once( 'filters/crf.php' );

/**
 * Functions for inventory shortcode [cd_inventory]
 *
 * @todo
 *     - Document functions
 */
require_once( 'shortcode/cd-shortcode.php' );

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 *      - WP_LANG_DIR/car-demon/car-demon-LOCALE.mo
 *      - WP_LANG_DIR/plugins/car-demon-LOCALE.mo
 */
function cardemon_language(){
    $domain = 'car-demon';
    // The "plugin_locale" filter is also used in load_plugin_textdomain()
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

    load_plugin_textdomain( $domain, FALSE, dirname(plugin_basename(__FILE__)).'/languages/' );
}
add_action( 'plugins_loaded', 'cardemon_language' );

/**
 * Run activation
 *
 * Set default options
 *
 * Set welcome screen transient
 */
function car_demon_activate() {
	cd_default_options();
	set_transient( '_cd_welcome_screen_activation_redirect', true, 30 );
	//= register post type and flush rewrite rules
	car_demon_create_post_type();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'car_demon_activate' );

/**
 * Run deactivation
 *
 * Do not delete settings
 *
 * @todo
 *     - add option to delete settings and post data
 */
function car_demon_deactivate() {
	//delete_option('car_demon_options');
	$cd_options = array();
	$cd_options = get_option( 'car_demon_options' );
	update_option( 'car_demon_options', $cd_options );
}
register_deactivation_hook( __FILE__, 'car_demon_deactivate' );

/**
 * Save default settings
 */
function cd_default_options() {
	$cd_options = car_demon_options();
	$cd_options['use_theme_files'] = 'No';
	$cd_options['use_about'] = 'No';
	$cd_options['hide_tabs'] = 'No';
	$cd_options['cd_cdrf_style'] = 'content-replacement';
	$cd_options['cd_cdrf_page_style'] = 'content-replacement';
	$cd_options['use_session'] = 'No';
	$cd_options['drop_down_sort'] = 'Yes';
	$cd_options['dynamic_load'] = 'No';
	$cd_options['use_vehicle_css'] = 'Yes';
	update_option( 'car_demon_options', $cd_options );
}
?>