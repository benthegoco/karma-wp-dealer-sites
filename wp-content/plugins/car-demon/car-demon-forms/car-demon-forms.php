<?php
include( 'forms/contact-us.php' );
include( 'forms/trade-form.php' );
include( 'forms/service-form.php' );
include( 'forms/service-quote.php' );
include( 'forms/part-request.php' );
include( 'forms/finance-form.php' );
include( 'forms/qualify-form.php' );
include( 'handlers/contact-us-handler.php' );
include( 'handlers/trade-form-handler.php' );
include( 'handlers/part-handler.php' );
include( 'handlers/service-handler.php' );
include( 'handlers/service-quote-handler.php' );
include( 'handlers/qualify-handler.php' );
include( 'handlers/email-friend-handler.php' );

add_action( "wp_ajax_cd_contact_us_handler", "cd_contact_us_handler" );
add_action( "wp_ajax_nopriv_cd_contact_us_handler", "cd_contact_us_handler" );
//======
add_action( "wp_ajax_cd_trade_handler", "cd_trade_handler" );
add_action( "wp_ajax_nopriv_cd_trade_handler", "cd_trade_handler" );
//======
add_action( "wp_ajax_cd_trade_show_stock", "cd_trade_show_stock" );
add_action( "wp_ajax_nopriv_cd_trade_show_stock", "cd_trade_show_stock" );
//======
add_action( "wp_ajax_cd_trade_find_stock", "cd_trade_find_stock" );
add_action( "wp_ajax_nopriv_cd_trade_find_stock", "cd_trade_find_stock" );
add_action( "wp_ajax_cd_trade_find_vehicle", "cd_trade_find_vehicle" );
add_action( "wp_ajax_nopriv_cd_trade_find_vehicle", "cd_trade_find_vehicle" );
//======
add_action( "wp_ajax_cd_parts_handler", "cd_parts_handler" );
add_action( "wp_ajax_nopriv_cd_parts_handler", "cd_parts_handler" );
//======
add_action( "wp_ajax_cd_service_handler", "cd_service_handler" );
add_action( "wp_ajax_nopriv_cd_service_handler", "cd_service_handler" );
//======
add_action( "wp_ajax_cd_service_quote_handler", "cd_service_quote_handler" );
add_action( "wp_ajax_nopriv_cd_service_quote_handler", "cd_service_quote_handler" );
//======
add_action( "wp_ajax_cd_qualify_handler", "cd_qualify_handler" );
add_action( "wp_ajax_nopriv_cd_qualify_handler", "cd_qualify_handler" );
//======
add_action( "wp_ajax_email_friend_handler", "email_friend_handler" );
add_action( "wp_ajax_nopriv_email_friend_handler", "email_friend_handler" );
//======

function cd_forms_enqueue_style() {
	//= Load jquery-ui.css so autocomplete will work
	wp_enqueue_style( 'jquery-ui-css', plugins_url().'/car-demon/theme-files/css/jquery-ui.css' );
	wp_enqueue_script( 'jquery-ui-js', plugins_url().'/car-demon/theme-files/js/jquery-ui.js', array('jquery'), CAR_DEMON_VER );
}
add_action( 'wp_enqueue_scripts', 'cd_forms_enqueue_style' );

if ( ! is_admin() ) {
	add_filter( 'the_posts', 'cd_conditionally_add_scripts_and_styles' ); // the_posts gets triggered before wp_head
}

function cd_conditionally_add_scripts_and_styles( $posts ) {
	global $car_demon_options;
	if ( empty( $posts ) ) return $posts;
	$use_css = 1;
	$x = '';
	if ( isset( $car_demon_options['use_form_css'] ) ) {
		if ( $car_demon_options['use_form_css'] != 'No' ) {
			$use_css = 1;
		}
	} else {
		$use_css = 1;
	}
	if ( $use_css == 1 ) {
		$shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
		foreach ( $posts as $post ) {
			if ( stripos( $post->post_content, '[contact_us' ) !== false || stripos( $post->post_content, '[staff') !== false ) {
				wp_register_script( 'car-demon-common-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-common.js', array('jquery'), CAR_DEMON_VER );
				wp_register_script( 'car-demon-contact-us-form-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-contact-us.js', array('jquery'), CAR_DEMON_VER );
				$validate_phone = 0;
				if ( isset( $car_demon_options['validate_phone'] ) ) {
					if ( $car_demon_options['validate_phone'] == 'Yes' ) {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-contact-us-form-js', 'cdContactParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __( 'You must enter your name.', 'car-demon' ),
					'error2' => __( 'You must enter your name.', 'car-demon' ),
					'error3' => __( 'You must enter a valid Phone Number.', 'car-demon' ),
					'error4' => __( 'The phone number you entered is not valid.', 'car-demon' ),
					'error5' => __( 'You did not select who you want to send this message to.', 'car-demon' ),
					'error6' => __( 'You did not enter a message to send.', 'car-demon' ),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'contact_us', 'unk' ),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'contact_us', 'unk' ),
					'sending_msg' => __( 'Please wait while your message is sent.', 'car-demon' ),
					'spinner' => CAR_DEMON_PATH . 'images/wpspin_light.gif',
					'validate_phone' => $validate_phone,
				) );
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __( 'You didn\'t enter an email address.', 'car-demon' ),
					'error2' => __( 'Please enter a valid email address.', 'car-demon' ),
					'error2' => __( 'The email address contains illegal characters.', 'car-demon' ),
				) );
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-contact-us-form-js' );
			}
			if ( stripos( $post->post_content, '[finance_form' ) !== false ) {
				$validate_phone = 0;
				if ( isset( $car_demon_options['validate_phone'] ) ) {
					if ($car_demon_options['validate_phone'] == 'Yes') {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-trade-form-js', 'cdTradeParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __( 'You must enter your name.', 'car-demon' ),
					'error2' => __( 'You must enter your name.', 'car-demon' ),
					'error3' => __( 'You must enter a valid Phone Number.', 'car-demon' ),
					'error4' => __( 'The phone number you entered is not valid.', 'car-demon' ),
					'error5' => __( 'You did not select who you want to send this message to.', 'car-demon' ),
					'error6' => __( 'You did not enter a message to send.', 'car-demon' ),
					'error7' => __( 'You must enter the year of the vehicle you wish to trade.', 'car-demon' ),
					'error8' => __( 'You must enter the manufacturer of the vehicle you wish to trade.', 'car-demon' ),
					'error9' => __( 'You must enter the model of the vehicle you wish to trade.', 'car-demon' ),
					'error10' => __( 'You must enter the miles of the vehicle you wish to trade.', 'car-demon' ),
					'error11' => __( 'You indicated you were interested in purchasing a vehicle but did not select one.', 'car-demon' ),
					'error12' => __( 'You did not select a trade location.', 'car-demon' ),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'trade', 'unk' ),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'trade', 'unk' ),
					'sending_msg' => __( 'Please wait while your message is sent.', 'car-demon' ),
					'spinner' => CAR_DEMON_PATH . 'images/wpspin_light.gif',
					'validate_phone' => $validate_phone,
				));
			}
			if ( stripos( $post->post_content, '[trade' ) !== false ) {
				wp_enqueue_style('cd-jquery-autocomplete-css', plugins_url() . '/car-demon/theme-files/css/jquery.autocomplete.css', array(), CAR_DEMON_VER);
				wp_register_script("cd-jquery-autocomplete-js", plugins_url() . '/car-demon/theme-files/js/jquery.autocomplete.js', array('jquery'), CAR_DEMON_VER );
				wp_register_script('car-demon-common-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-common.js', array('jquery'), CAR_DEMON_VER );
				wp_register_script('car-demon-trade-form-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-trade.js', array('jquery'), CAR_DEMON_VER, true );
				$validate_phone = 0;
				if ( isset( $car_demon_options['validate_phone'] ) ) {
					if ( $car_demon_options['validate_phone'] == 'Yes' ) {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-trade-form-js', 'cdTradeParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __( 'You must enter your name.', 'car-demon' ),
					'error2' => __( 'You must enter your name.', 'car-demon' ),
					'error3' => __( 'You must enter a valid Phone Number.', 'car-demon' ),
					'error4' => __( 'The phone number you entered is not valid.', 'car-demon' ),
					'error5' => __( 'You did not select who you want to send this message to.', 'car-demon' ),
					'error6' => __( 'You did not enter a message to send.', 'car-demon' ),
					'error7' => __( 'You must enter the year of the vehicle you wish to trade.', 'car-demon' ),
					'error8' => __( 'You must enter the manufacturer of the vehicle you wish to trade.', 'car-demon' ),
					'error9' => __( 'You must enter the model of the vehicle you wish to trade.', 'car-demon' ),
					'error10' => __( 'You must enter the miles of the vehicle you wish to trade.', 'car-demon' ),
					'error11' => __( 'You indicated you were interested in purchasing a vehicle but did not select one.', 'car-demon' ),
					'error12' => __( 'You did not select a trade location.', 'car-demon' ),
					'form_js' => apply_filters( 'car_demon_mail_hook_js', $x, 'trade', 'unk' ),
					'form_data' => apply_filters( 'car_demon_mail_hook_js_data', $x, 'trade', 'unk' ),
					'sending_msg' => __( 'Please wait while your message is sent.', 'car-demon' ),
					'spinner' => CAR_DEMON_PATH . 'images/wpspin_light.gif',
					'validate_phone' => $validate_phone,
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __( 'You didn\'t enter an email address.', 'car-demon' ),
					'error2' => __( 'Please enter a valid email address.', 'car-demon' ),
					'error2' => __( 'The email address contains illegal characters.', 'car-demon' ),
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'cd-jquery-autocomplete-js' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-trade-form-js' );
			}
			if ( stripos( $post->post_content, '[part_request' ) !== false ) {
				wp_register_script( 'car-demon-part-request-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-part-request.js', array(), CAR_DEMON_VER );
				wp_register_script( 'car-demon-common-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-common.js', array(), CAR_DEMON_VER );
				$validate_phone = 0;
				if ( isset( $car_demon_options['validate_phone'] ) ) {
					if ( $car_demon_options['validate_phone'] == 'Yes' ) {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-part-request-js', 'cdPartsParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __(' You may only add 10 parts to your request', 'car-demon' ),
					'error2' => __(' If you need additional parts please add them in the comment area.', 'car-demon' ),
					'error3' => __(' You must enter your name.', 'car-demon' ),
					'error4' => __(' You must enter your name.', 'car-demon' ),
					'error5' => __(' You must enter a valid Phone Number.', 'car-demon' ),
					'error6' => __(' The phone number you entered is not valid.', 'car-demon' ),
					'error7' => __(' You did not select a part location.', 'car-demon' ),
					'error8' => __(' You need to add at least the name of one part you are looking for.', 'car-demon' ),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'trade', 'unk' ),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'trade', 'unk' ),
					'sending_msg' => __( 'Please wait while your message is sent.', 'car-demon' ),
					'spinner' => CAR_DEMON_PATH . 'images/wpspin_light.gif',
					'validate_phone' => $validate_phone,
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __( 'You didn\'t enter an email address.', 'car-demon' ),
					'error2' => __( 'Please enter a valid email address.', 'car-demon' ),
					'error2' => __( 'The email address contains illegal characters.', 'car-demon' ),
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-part-request-js' );
			}
			if ( stripos( $post->post_content, '[service_form' ) !== false ) {
				wp_register_script( 'car-demon-service-form-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-service-form.js', array(), CAR_DEMON_VER );
				wp_register_script( 'car-demon-common-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-common.js', array(), CAR_DEMON_VER );
				wp_register_script( 'car-demon-service-calendar-js', plugins_url() . '/car-demon/theme-files/js/CalendarPopup.js', array(), CAR_DEMON_VER );
				$validate_phone = 0;
				if ( isset( $car_demon_options['validate_phone'] ) ) {
					if ( $car_demon_options['validate_phone'] == 'Yes' ) {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-service-form-js', 'cdServiceParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __( 'You must enter your name.', 'car-demon' ),
					'error2' => __( 'You must enter your name.', 'car-demon' ),
					'error3' => __( 'You must enter a valid Phone Number.', 'car-demon' ),
					'error4' => __( 'The phone number you entered is not valid.', 'car-demon' ),
					'error5' => __( 'You did not select a service location.', 'car-demon' ),
					'error6' => __( 'You did not select a preferred appointment date.', 'car-demon' ),
					'error7' => __( 'You did not select an alternate appointment date.', 'car-demon' ),
					'error8' => __( 'You did not tell us what kind of service you need.', 'car-demon' ),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'service', 'unk' ),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'service', 'unk' ),
					'sending_msg' => __( 'Please wait while your message is sent.', 'car-demon' ),
					'spinner' => CAR_DEMON_PATH . 'images/wpspin_light.gif',
					'validate_phone' => $validate_phone,
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __( 'You didn\'t enter an email address.', 'car-demon' ),
					'error2' => __( 'Please enter a valid email address.', 'car-demon' ),
					'error2' => __( 'The email address contains illegal characters.', 'car-demon' )
				));
				wp_localize_script( 'car-demon-service-calendar-js', 'cdCalendarParams', array(
					'jan' => __( 'January', 'car-demon' ),
					'feb' => __( 'February', 'car-demon' ),
					'mar' => __( 'March', 'car-demon' ),
					'apr' => __( 'April', 'car-demon' ),
					'may' => __( 'May', 'car-demon' ),
					'jun' => __( 'June', 'car-demon' ),
					'jul' => __( 'July', 'car-demon' ),
					'aug' => __( 'August', 'car-demon' ),
					'sep' => __( 'September', 'car-demon' ),
					'oct' => __( 'October', 'car-demon' ),
					'nov' => __( 'November', 'car-demon' ),
					'dec' => __( 'December', 'car-demon' ),
					'picktime' => __( 'Pick Time', 'car-demon' ),
					'early_morning' => __( 'Early Morning', 'car-demon' ),
					'mid_morning' => __( 'Mid Morning', 'car-demon' ),
					'late_morning' => __( 'Late Morning', 'car-demon' ),
					'early_afternoon' => __( 'Early Afternoon', 'car-demon' ),
					'mid_afternoon' => __( 'Mid Afternoon', 'car-demon' ),
					'late_afternoon' => __( 'Late Afternoon', 'car-demon' ),				
					'clear' => __( 'Clear', 'car-demon' ), 
					'close_it' => __( 'Close', 'car-demon' )
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-service-form-js' );
				wp_enqueue_script( 'car-demon-service-calendar-js' );
			}
			if ( stripos( $post->post_content, '[service_quote' ) !== false ) {
				wp_register_script( 'car-demon-service-quote-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-service-quote.js', array(), CAR_DEMON_VER );
				wp_register_script( 'car-demon-common-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-common.js', array(), CAR_DEMON_VER );
				$validate_phone = 0;
				if ( isset( $car_demon_options['validate_phone'] ) ) {
					if ( $car_demon_options['validate_phone'] == 'Yes' ) {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-service-quote-js', 'cdServiceQuoteParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __( 'You must enter your name.', 'car-demon' ),
					'error2' => __( 'You must enter your name.', 'car-demon' ),
					'error3' => __( 'You must enter a valid Phone Number.', 'car-demon' ),
					'error4' => __( 'The phone number you entered is not valid.', 'car-demon' ),
					'error5' => __( 'You did not select a service location.', 'car-demon' ),
					'error6' => __( 'You did not tell us what kind of service you need.', 'car-demon' ),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'service_quote', 'unk' ),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'service_quote', 'unk' ),
					'sending_msg' => __( 'Please wait while your message is sent.', 'car-demon' ),
					'spinner' => CAR_DEMON_PATH . 'images/wpspin_light.gif',
					'validate_phone' => $validate_phone,
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __( 'You didn\'t enter an email address.', 'car-demon' ),
					'error2' => __( 'Please enter a valid email address.', 'car-demon' ),
					'error2' => __( 'The email address contains illegal characters.', 'car-demon' )
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-service-quote-js' );
			}
			if ( stripos( $post->post_content, '[qualify' ) !== false ) {
				wp_register_script( 'car-demon-qualify-us-form-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-qualify.js', array(), CAR_DEMON_VER );
				wp_register_script( 'car-demon-common-js', plugins_url() . '/car-demon/car-demon-forms/forms/js/car-demon-common.js', array(), CAR_DEMON_VER );
				$validate_phone = 0;
				if ( isset( $car_demon_options['validate_phone'] ) ) {
					if ( $car_demon_options['validate_phone'] == 'Yes' ) {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-qualify-us-form-js', 'cdQualifyParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __( 'You must enter your name.', 'car-demon' ),
					'error2' => __( 'You must enter your name.', 'car-demon' ),
					'error3' => __( 'You must enter a valid Phone Number.', 'car-demon' ),
					'error4' => __( 'The phone number you entered is not valid.', 'car-demon' ),
					'error5' => __( 'You must enter your city.', 'car-demon' ),
					'error6' => __( 'You must enter your employer.', 'car-demon' ),
					'error7' => __( 'You must enter your income.', 'car-demon' ),
					'error8' => __( 'You did not select who you want to send this message to.', 'car-demon' ),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'qualify', 'unk' ),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'qualify', 'unk' ),
					'spinner' => CAR_DEMON_PATH . 'images/wpspin_light.gif',
					'sending_msg' => __( 'Please wait while your message is sent.', 'car-demon' ),
					'validate_phone' => $validate_phone
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __( 'You didn\'t enter an email address.', 'car-demon' ),
					'error2' => __( 'Please enter a valid email address.', 'car-demon' ),
					'error2' => __( 'The email address contains illegal characters.', 'car-demon' )
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-qualify-us-form-js' );
			}
		}
	}
	return $posts;
}

function get_car_from_stock( $selected_car ) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id, meta_value from " . $prefix . "postmeta WHERE meta_key='_stock_value' and meta_value = '" . $selected_car . "'";
	$posts = $wpdb->get_results( $sql );
	if ( $posts ) {
		foreach ( $posts as $post ) {
			$post_id = $post->post_id;
			$vehicle_year = get_cd_term( $post_id, 'vehicle_year' );
			$vehicle_make = get_cd_term( $post_id, 'vehicle_make' );
			$vehicle_model = get_cd_term( $post_id, 'vehicle_model' );
			$vehicle_condition = get_cd_term( $post_id, 'vehicle_condition' );
			$car = $vehicle_condition .' '. $vehicle_year .' '. $vehicle_make .' '. $vehicle_model;
			$car_link = get_permalink( $post_id );
			if ( isset( $_COOKIE["sales_code"] ) ) {
				$car_link = $car_link . '?sales_code=' . $_COOKIE["sales_code"];
			}
			$car_img = cd_main_photo( $post_id );
			$car_img = str_replace( chr(32), "%20", $car_img );
			$car_pic = '<p align="center"><a href="' . $car_link . '" /><img width="400" src="' . $car_img . '" title="' . $car . '" /></a></p>';
			$x = $post->meta_value . ' ' . $car . '<br />' . $car_pic;
		}
	}
	return $x;
}

function get_car_id_from_stock( $selected_car ) {
	global $wpdb;
	$post_id = '';
	$prefix = $wpdb->prefix;
	$sql = "Select post_id, meta_value from " . $prefix . "postmeta WHERE meta_key='_stock_value' and meta_value = '" . $selected_car . "'";
	$posts = $wpdb->get_results( $sql );
	if ( $posts ) {
		foreach ( $posts as $post ) {
			$post_id = $post->post_id;
		}
	}
	return $post_id;
}

if ( defined( 'CD_USE_WPMAIL' ) ) {
	add_filter( 'wp_mail_content_type', 'cd_set_content_type' );
	function cd_set_content_type( $content_type ) {
		return 'text/html; charset=ISO-8859-1';
	}
}
?>