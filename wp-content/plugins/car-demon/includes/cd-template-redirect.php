<?php
/**
 * Template redirect if included template files are used
 *
 * @param $original_template the template being used when this function is called
 *
 * @todo
 *     - Depracate option to use included template files
 */
function car_demon_theme_redirect( $original_template ) {
	global $car_demon_options;
	if ( $car_demon_options['use_theme_files'] == 'Yes' ) {
		global $wp;
		$plugindir = dirname( __FILE__ );
		$plugindir = str_replace( 'includes/', '', $plugindir );
		$plugindir = str_replace( 'includes\\', '', $plugindir );
		$plugindir = str_replace( 'includes', '', $plugindir );
		// Custom Post Type cars_for_sale
		$template_directory = get_template_directory();
		if ( isset( $wp->query_vars["post_type"] ) ) {
			if ( $wp->query_vars["post_type"] == 'cars_for_sale' ) {
				if ( isset( $wp->query_vars["cars_for_sale"] ) ) {
					$templatefilename = 'single-cars_for_sale.php';
					add_action( 'wp_head', 'car_demon_facebook_meta' );
				} else {
					$templatefilename = 'archive-cars_for_sale.php';	
				}
				if ( file_exists( $template_directory . '/' . $templatefilename ) ) {
					$return_template = $template_directory . '/' . $templatefilename;
				} else {
					$return_template = $plugindir . '/theme-files/' . $templatefilename;
				}
				do_car_demon_theme_redirect( $return_template );
			} else {
				return $original_template;
			}
			// Custom Taxonomy
		} elseif ( isset( $wp->query_vars["vehicle_condition"] ) ) {
			$templatefilename = 'archive-cars_for_sale.php';
			if ( file_exists( $template_directory . '/' . $templatefilename ) ) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect( $return_template );
		} elseif ( isset( $wp->query_vars["vehicle_year"] ) ) {
			$templatefilename = 'archive-cars_for_sale.php';
			if ( file_exists( $template_directory . '/' . $templatefilename ) ) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect( $return_template );
		} elseif ( isset($wp->query_vars["vehicle_make"] ) ) {
			$templatefilename = 'archive-cars_for_sale.php';
			if ( file_exists( $template_directory . '/' . $templatefilename ) ) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect( $return_template );
		} elseif ( isset( $wp->query_vars["vehicle_model"] ) ) {
			$templatefilename = 'archive-cars_for_sale.php';
			if ( file_exists( $template_directory . '/' . $templatefilename ) ) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect( $return_template );
		} elseif ( isset( $wp->query_vars["vehicle_location"] ) ) {
			$templatefilename = 'archive-cars_for_sale.php';
			if ( file_exists( $template_directory . '/' . $templatefilename ) ) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect( $return_template );
		} elseif ( isset( $wp->query_vars["vehicle_body_style"] ) ) {
			$templatefilename = 'archive-cars_for_sale.php';
			if ( file_exists( $template_directory . '/' . $templatefilename ) ) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect( $return_template );
		// Search Cars
		} elseif ( isset( $wp->query_vars["s"] ) ) {
			if ( $wp->query_vars['s'] == 'cars' ) {
				if ( $_GET['car'] == 1 ) {
					$templatefilename = 'search.php';
					$return_template = $plugindir . '/theme-files/' . $templatefilename;
					global $post, $wp_query;
					$wp_query->is_404 = false;
					require_once( $return_template );
					die();
				}
			}
		} else {
			return $original_template;
		}
		return $return_template;
	} else {
		return $original_template;
	}
}
add_action( 'template_include', 'car_demon_theme_redirect', 1 );

/**
 * Force WP to use the template file we've selected
 *
 * @param $url the template file being used
 *
 * @todo
 *     - Depracate option to use included template files
 */
function do_car_demon_theme_redirect( $url ) {
    global $post, $wp_query;
	$url = str_replace( 'includes/', '', $url );
	$url = str_replace( 'includes\\', '', $url );
    if (have_posts()) {
        require_once( $url );
        die();
    } else {
        $wp_query->is_404 = true;
    }
}	
?>