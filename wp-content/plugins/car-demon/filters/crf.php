<?php
function car_crf() {
	// Array containing all style slugs, to add a new style add a slug for it then add a folder with that slug name and your template files
	$car_crf = 'default, content-replacement';
	$car_crf = explode( ',', $car_crf );
	return $car_crf;
}

//= Output Admin Area
function cd_cdrf_style_options_do_page() {
	$cd_cdrf_pluginpath = CAR_DEMON_PATH;
	$cd_cdrf_options = get_option( 'car_demon_options' );
	if ( isset( $_POST['submit'] ) ) {
		$cd_cdrf_options['cd_cdrf_style'] = sanitize_text_field( $_POST['cd_cdrf_style'] );
		$cd_cdrf_options['cd_cdrf_page_style'] = sanitize_text_field( $_POST['cd_cdrf_page_style'] );
		if ( $cd_cdrf_options['cd_cdrf_style'] == 'content-replacement' ) {
			$cd_cdrf_options['use_theme_files'] = 'No';
		}
		update_option( 'car_demon_options', $cd_cdrf_options );
	}
	wp_enqueue_style( 'car-demon-style-admin-css', plugins_url() . '/car-demon/filters/crf-admin.css' );
	echo '<h3>' . __( 'Car Display Options', 'car-demon' ) . '</h3>';
	echo '<h5>' . __( 'The default option will load the original, default template files.', 'car-demon' ) . '</h5>';
	/*
	These are the from the legacy page - included as reference for when this segment is revised
	echo '<h5>'.__('Content replacement will use your existing Theme\'s template files.', 'car-demon').'</h5>';
	echo '<h5>'.__('If you have not set this option then the default templates will load.', 'car-demon').'</h5>';
	echo '<h5>'.__('If Content Replacement is selected then auto load inventory on scroll and a few other special features may not work.', 'car-demon').'</h5>';
	echo '<h5>'.__('Content Replacement can not be used with the Car Demon Styles PlugIn.', 'car-demon').'</h5>';
	*/
	echo '<form action="" method="post" />';
		$car_crf = car_crf();
		$vehicle_list_styles = '';
		$vehicle_page_styles = '';
		foreach ( $car_crf as $cd_cdrf_style ) {
			$current_style = trim( $cd_cdrf_style );
			${$current_style.'_check'} = '';
			${$current_style.'_check_page'} = '';
			if ( isset( $cd_cdrf_options['cd_cdrf_style'] ) ) {
				if ( $cd_cdrf_options['cd_cdrf_style'] == $current_style ) { ${$current_style.'_check'} = ' checked'; }
			}
			if ( isset( $cd_cdrf_options['cd_cdrf_page_style'] ) ) {
				if ( $cd_cdrf_options['cd_cdrf_page_style'] == $current_style ) { ${$current_style.'_check_page'} = ' checked'; }
			}
			$style_name = str_replace( '_', ' ', $current_style );
			$style_name = ucwords( $style_name );
			$vehicle_list_styles .= '<div class="cd_style_selection" />';
				$vehicle_list_styles .= '<input type="radio" name="cd_cdrf_style" value="' . $current_style . '"' . ${$current_style.'_check'} . ' />&nbsp;' . $style_name . '<br />';
			$vehicle_list_styles .= '</div>';
			$vehicle_page_styles .= '<div class="cd_style_selection" />';
				$vehicle_page_styles .= '<input type="radio" name="cd_cdrf_page_style" value="' . $current_style . '"' . ${$current_style.'_check_page'} . ' />&nbsp;' . $style_name . '<br />';
			$vehicle_page_styles .= '</div>';
		}
		echo '<h5>Vehicle List Style</h5>';
			echo $vehicle_list_styles;
		echo '<h5>Vehicle Page Style</h5>';
			echo $vehicle_page_styles;
		echo '<div class="cd_style_submit_holder" />';
			echo '<div class="welcome_float_button"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"></div>';
			echo '</div>';
	echo '</form>';
}

function cdcr_load_replacement() {
	global $car_demon_options;
	$cd_cdrf_options = $car_demon_options;
	if ( ! empty( $cd_cdrf_options ) ) {
		$theme_style = $cd_cdrf_options['cd_cdrf_style'];
		$theme_page_style = $cd_cdrf_options['cd_cdrf_page_style'];
	}
	if ( $theme_style == 'content-replacement' ) {
		include( 'theme-files/content-replacement/cd-content-replacement.php' );
	}
}
if ( ! is_admin() ) {
	add_action( 'init', 'cdcr_load_replacement' );
}

function car_crf_theme_redirect() {
	global $car_demon_options;
	if ( $car_demon_options['cd_cdrf_style'] != 'default' ) {
		if ( isset( $car_demon_options['is_mobile'] ) ) {
			$is_mobile = $car_demon_options['is_mobile'];
		} else {
			$is_mobile = 'No';
		}
		//= If we're using a Car Demon Mobile Theme then don't use this for mobile
		if ( $is_mobile != 'Yes' ) {
			global $wp;
			$plugindir = dirname( __FILE__ );
			$cd_cdrf_options = $car_demon_options;
			if ( ! empty( $cd_cdrf_options ) ) {
				$theme_style = $cd_cdrf_options['cd_cdrf_style'];
				$theme_page_style = $cd_cdrf_options['cd_cdrf_page_style'];
			} else {
				$theme_style = 'default';
				$theme_page_style = 'default';
			}
			$template_directory = get_template_directory();

			//= Use this css and js for everything right now
			if ( isset( $car_demon_options['use_vehicle_css'] ) ) {
				if ( $car_demon_options['use_vehicle_css'] != 'No' ) {
					wp_enqueue_style( 'cr-style-css', plugins_url() . '/car-demon/filters/theme-files/content-replacement/cr-style.css', array(), CAR_DEMON_VER );
					wp_enqueue_style( 'cr-style-single-car', plugins_url() . '/car-demon/filters/theme-files/content-replacement/cr-single-car.css', array(), CAR_DEMON_VER );
				}
			}

			wp_register_script( 'cr-style-js', plugins_url() . '/car-demon/filters/theme-files/content-replacement/cr.js', array('jquery'), CAR_DEMON_VER );
			wp_localize_script( 'cr-style-js', 'cdrParams', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'car_demon_path' => CAR_DEMON_PATH,
			));
			
			wp_enqueue_script( 'cr-style-js', plugins_url() . '/car-demon/filters/theme-files/content-replacement/cr-js.php', array(), CAR_DEMON_VER );
			//= Start Content Replacement
			if ( $theme_style == 'content-replacement' && ! is_home() ) {
				if ( isset( $wp->query_vars["cars_for_sale"] ) ) {
					$templatefilename = 'single.php';
				} else {
					$templatefilename = 'archive.php';
					$theme_page_style = $theme_style;
					global $post, $wp_query;
				}
				if ( file_exists( $template_directory . '/' . $templatefilename ) ) {
					$return_template = $template_directory . '/' . $templatefilename;
				} else {
					//= load index if no single or archive is found
					$templatefilename = 'index.php';
					if ( file_exists( $template_directory . '/' . $templatefilename ) ) {
						$return_template = $template_directory . '/' . $templatefilename;
					} else {
						$return_template = $plugindir . '/theme-files/' . $theme_page_style . '/' . $templatefilename;
					}
				}
				//= If a Car Demon search is performed then redirect it so we can get the archive template
				if ( is_search() ) {
					if ( $_GET['s']=='cars' ) {
						global $wp;
						$current_url = cdcr_getCurrentURL();
						$new_url = str_replace( 's=cars', 's=&post_type=cars_for_sale', $current_url );
						header( "Location: ".$new_url );
						exit();
					}
				}
				//= If the Car Search is set in the QueryString then load the archive template
/*
				if (isset($_GET['car'])) {
					if ($_GET['car']==1) {
						$templatefilename = 'archive.php';
						$return_template = $template_directory . '/' . $templatefilename;
						if (file_exists($template_directory . '/' . $templatefilename)) {
							$return_template = $template_directory . '/' . $templatefilename;
						} else {
							//= load index if no single or archive is found
							$templatefilename = 'index.php';
							$return_template = $template_directory . '/' . $templatefilename;
						}
						header('HTTP/1.1 200 OK');
						$wp_query->is_404 = false;
						include($return_template);
						die();
						do_car_crf_theme_redirect($return_template);
					}
				}
*/
			}
			//= End Content Replacement
		}
	}
}
add_action( 'template_redirect', 'car_crf_theme_redirect' );

function do_car_crf_theme_redirect( $url ) {
    global $post, $wp_query;
    if ( have_posts() ) {
        include( $url );
        die();
    } else {
        $wp_query->is_404 = true;
    }
}

function cd_cdrf_switch_theme_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'opt1' => 0,
		'opt2' => 1,
	), $atts ) );
	if ( function_exists( 'wp_theme_switcher' ) ) {
		wp_theme_switcher( 'dropdown' );
	}
	return $x;
}
add_shortcode( 'cd_cdrf_switch_theme', 'cd_cdrf_switch_theme_shortcode_func' );

//= Add Widget
include( 'widgets/cdcr-widget-archive.php' );

?>