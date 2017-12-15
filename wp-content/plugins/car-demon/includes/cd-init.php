<?php
if ( ! is_admin() ) {
	add_action( 'wp', 'start_car_demon' );
	add_filter( 'widget_text', 'car_demon_text_filter', 11 );
	add_filter( 'the_content', 'car_demon_text_filter', 11 );
}

/**
 * Set cookies, start session if not started and redirect if affiliate sales code is used
 *
 * @todo
 *     - add option to disable affiliate sales code by default
 */
function start_car_demon() {
	if ( ! defined( 'CD_NO_SESSION' ) ) {
		if ( ! session_id() ) {
			session_start();
		}
	} 

	if ( defined( 'CD_NO_AFFILIATES' ) ) {
		return;
	}
	
	if ( isset( $_GET['sales_code'] ) ) {
		$sales_code = $_GET['sales_code'];
	} else {
		$sales_code = '';
	}

	if ( $sales_code == '0' ) {
		setcookie( 'sales_code', $sales_code, time()-3600*24*90, '/' );
		$redirect = car_demon_self_url();
		$redirect = str_replace( '?sales_code=' . $sales_code, '', $redirect );
		$redirect = str_replace( '&sales_code=' . $sales_code, '', $redirect );
		if ( empty( $redirect ) ) {
			$redirect = site_url();
		}
		header( 'Location:' . $redirect );
	}

	if ( ! empty( $sales_code ) ) {
		setcookie( 'sales_code', $_GET['sales_code'], time()+3600*24*90, '/' );
		$redirect = car_demon_self_url();
		$redirect = str_replace( '?sales_code=' . $sales_code, '', $redirect );
		$redirect = str_replace( '&sales_code=' . $sales_code, '', $redirect );
		if ( empty( $redirect ) ) {
			$redirect = site_url();
		}
		header( 'Location:' . $redirect );
	}
	car_demon_subdomains();
}

/**
 * Add post-thumbnails theme support, call car_demon_session(), define global $car_demon_options, register menus and sidebars
 *
 */
function car_demon_init() {
	/**
	 * Add support for Featured Images
	 */
	if ( function_exists( 'add_theme_support' ) ) {
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'car-index', 150, 150, true );
		add_image_size( 'car-single', 350, 350, true );
	}

	car_demon_session();
	global $car_demon_options;
	register_car_demon_menus();

	if ( function_exists('register_sidebar') ){
		add_action( 'widgets_init', 'car_demon_register_sidebars', 2 );
	}

	// Register Mobile Widget Areas
	if ( $car_demon_options['mobile_theme'] == 'Yes' ) {
		car_demon_register_mobile_sidebars();
	}

	// = New Location Management Code - undefine to deactivate & use legacy
	if ( ! defined( 'CD_LOCATIONS' ) ) {
		define('CD_LOCATIONS', true);
	}

}
add_action( 'init', 'car_demon_init' );

/**
 * Set global $car_demon_options, start session if not started and check if legacy session options are being used
 *
 * @todo
 *     - remove legacy session options
 *     - remove session_start()
 */
function car_demon_session() {
	global $car_demon_options;
	$car_demon_options = car_demon_options();

	if (isset($car_demon_options['search_form_count'])) {
		if ($car_demon_options['search_form_count'] == 'Yes') {
			define( 'CD_NO_SEARCH_FORM_COUNT', true );
		}
	}
	
	if ( ! defined( 'CD_NO_SESSION' ) ) {
		if ( ! session_id() ) {
			session_start();
		}

		if ( isset( $car_demon_options['use_session'] ) ) {
			if ( isset( $car_demon_options['use_session'] ) == 'Yes' ) {
				$_SESSION['car_demon_options'] = $car_demon_options;
			}
		}
	}
}

/**
 * Register mobile menu areas if option selected in settings
 *
 */
function register_car_demon_menus(){
	global $car_demon_options;

	if ( $car_demon_options['mobile_theme'] == 'Yes' ) {
		register_nav_menus(
			array(
				'mobile-menu' => __( 'Mobile Menu', 'car-demon' )
			)	
		);
		register_nav_menus(
			array(
				'top-mobile-menu' => __( 'Top Mobile Menu', 'car-demon' )
			)	
		);
	}

}

/**
 * Filter post and widget content, replace contact tags with their respective information
 *
 * @todo
 *     - add documention on usage
 */
function car_demon_text_filter( $body ) {
	$text = replace_contact_info_tags( 0, $body );
	return $text;
}

/**
 * Get current page URL for sales affiliate redirect
 *
 */
function car_demon_self_url() {

    if( ! isset( $_SERVER['REQUEST_URI'] ) ){
        $serverrequri = $_SERVER['PHP_SELF'];
    } else {
        $serverrequri = $_SERVER['REQUEST_URI'];
    }

    $s = empty( $_SERVER["HTTPS"] ) ? '' : ( $_SERVER["HTTPS"] == "on" ) ? "s" : "";
    $protocol = car_demon_str_left( strtolower( $_SERVER["SERVER_PROTOCOL"] ), "/" ) . $s;
    $port = ( $_SERVER["SERVER_PORT"] == "80" ) ? "" : ( ":".$_SERVER["SERVER_PORT"] );

    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $serverrequri;   
}

/**
 * Return portion of string based on parameters
 *
 * @param $s1 string to be trimmed
 * @param $s2 segment of string to begin trim
 * @todo
 *     - add documention on usage
 */
function car_demon_str_left( $s1, $s2 ) {
	return substr( $s1, 0, strpos( $s1, $s2 ) );
}

/**
 * Cross check current URL to see if it matches sales affiliate redirect URL
 *
 * @todo
 *     - add documention on functionality
 */
function car_demon_subdomains() {

	if ( empty( $_COOKIE['domain_check'] ) ) {
		$site_url = car_demon_get_site_url();
		setcookie( "domain_check", "1", time()+3600*24*90, '/', $site_url );

		if ( empty( $_COOKIE['sales_code'] ) ) {
			$this_url = $_SERVER['SERVER_NAME'];
			$this_url = str_replace( 'www.', '', $this_url );
			$this_url = str_replace( 'http://', '', $this_url );
			$this_url = str_replace( 'https://', '', $this_url );
			$site_url =  car_demon_get_site_url();
			$site_url = str_replace( 'www.', '', $site_url );
			$site_url = str_replace( 'http://', '', $site_url );
			$site_url = str_replace( 'https://', '', $site_url );
			$site_url = 'check-'.$site_url;

			if ( strpos( $site_url, $this_url ) == 0 ) {
				global $wpdb;
				$prefix = $wpdb->prefix;
				$sql = 'SELECT user_id from ' . $prefix . 'usermeta where meta_key="custom_url" AND meta_value like "%' . $this_url . '%"';
				$posts = $wpdb->get_results( $sql );
				if ( $posts ) {

					foreach ( $posts as $post ) {
						$user_id = $post->user_id;
					}

					$site_url = str_replace( 'check-', '', $site_url );

					if ( empty( $redirect ) ) {
						$redirect = site_url() . '?sales_code=' . $user_id;
					}

					header( 'Location:' . $redirect );
				}

			}

		}

	}

}

/**
 * Get current site URL
 *
 * @todo
 *     - remove and replace with calls with standard WP function get_site_url()
 */
function car_demon_get_site_url() {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = 'SELECT option_value from ' . $prefix . 'options WHERE option_name="siteurl"';
	$posts = $wpdb->get_results( $sql );

	if ( $posts ) {
		foreach ( $posts as $post ) {
			$site_url = $post->option_value;
		}
	}

	return $site_url;
}

/**
 * Set query post_type if cars_for_sale or associated taxonomies are loaded
 *
 * @todo
 *     - remove and replace with calls with standard WP function get_site_url()
 */
function get_car_demon_posts( $query ) {

	if ( is_post_type_archive( 'cars_for_sale' ) || is_tax( 'vehicle_condition' ) || is_tax( 'vehicle_year' ) || is_tax( 'vehicle_make' ) || is_tax( 'vehicle_model' ) || is_tax( 'vehicle_body_style' ) ){
		if ( $query->is_main_query() ) {
			$query->set( 'post_type', array( 'cars_for_sale' ) );
		}
	}

	return $query;
}
if ( !is_admin() ) {
	add_filter( 'pre_get_posts', 'get_car_demon_posts' );
}

/**
 * Return string encapsulated in header tag of associated size
 *
 * @param $x string to be encapsulated
 * @param $y header tag size 1-6
 *
 * @todo
 *     - remove and replace with calls with standard WP function get_site_url()
 */
function rwh( $x, $y ) {
	if ( $y == 0 ) {
		$new_string = $x;	
	} else {
		$new_string = '<h' . $y . '>' . $x . '</h' . $y . '>';
	}
	return $new_string;
}

if (!function_exists('write_log')) {
    function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}

/**
 * Patch for issue with Yoast SEO
 *
 * @param $excerpt - the current excerpt
 *
 * Issue: Yoast SEO returns blank vehicle pages if no excerpt is entered
 * Only occurs if twitter or facebook integration is enabled
 * These are on by default so easiest solution is to return an excerpt with a blank space in it
 * Code traced to wp-seo-main.php line 311 in Yoast ver 4.5
*/
function cd_get_the_excerpt( $excerpt ) {
	//= Is the excerpt empty?
	if ( empty( $excerpt ) ) {
		//= Get the current $post_id
		$post_id = get_the_ID();
		//= Did we get a current $post_id
		if ( ! empty( $post_id ) ) {
			//= Get current post type
			$post_type = get_post_type( $post_id );
			//= Is current post type 'cars_for_sale'
			if ( 'cars_for_sale' === $post_type ) {
				//= Send a blank space if vehicle excerpt is empty
			    return " ";
			}
		}
	}
	//= Return normal excerpt
	return $excerpt;
}
add_filter('get_the_excerpt', 'cd_get_the_excerpt', 1);

?>