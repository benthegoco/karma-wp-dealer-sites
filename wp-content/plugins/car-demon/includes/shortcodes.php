<?php
function contact_us_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'send_to', 'car-demon' ) => __( 'normal', 'car-demon' ),
		__( 'popup_id', 'car-demon' ) => '',
		__( 'popup_button') => __( 'Contact Us', 'car-demon' ),
	), $atts, __( 'contact_us', 'car-demon' ) );
	$contact_us = car_demon_contact_request( $atts['send_to'], $atts['popup_id'], $atts['popup_button'] );
	return $contact_us;
}
add_shortcode( __( 'contact_us', 'car-demon' ), 'contact_us_shortcode_func' );

function search_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'size', 'car-demon' ) => '0',
		__( 'popup_id', 'car-demon' ) => '',
		__( 'title', 'car-demon' ) => __( 'Quick Search', 'car-demon' ),
		__( 'popup_button', 'car-demon' ) => __( 'Search Vehicles', 'car-demon' ),
		__( 'result_page', 'car-demon' ) => '',
		__( 'filter_condition', 'car-demon' ) => '',
	), $atts, __( 'search_form', 'car-demon' ) );
	
	if ( $atts['size'] == 0 ) {
		$atts['size'] = 's';
		$search_form = car_demon_simple_search( $atts['size'], $atts );
	} elseif ( $atts['size'] == 1 ) {
		$atts['size'] = '1';
		$search_form = car_demon_simple_search( $atts['size'], $atts );
	} else {
		$search_form = car_demon_search_form( $atts );
	}
	return $search_form;
}
add_shortcode( __( 'search_form', 'car-demon' ), 'search_shortcode_func' );

function search_box_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'button', 'car-demon' ) => __( 'Search', 'car-demon' ),
		__( 'message', 'car-demon' ) => __( 'Search Vehicles', 'car-demon' ),
		__( 'popup_id', 'car-demon' ) => '',
		__( 'popup_button', 'car-demon' ) => __( 'Search Vehicles', 'car-demon' ),
	), $atts, __( 'search_box', 'car-demon' ) );
	$search_box = vehicle_search_box( $atts['button'], $atts['message'] );
	return $search_box;
}
add_shortcode( __( 'search_box', 'car-demon' ), 'search_box_shortcode_func' );

function parts_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'location', 'car-demon' ) => __( 'normal', 'car-demon' ),
		__( 'popup_id', 'car-demon' ) => '',
		__( 'popup_button', 'car-demon' ) => __( 'Request Parts Quote', 'car-demon' ),
	), $atts, __( 'part_request', 'car-demon' ) );
	$part_quote = car_demon_part_request( $atts['location'], $atts['popup_id'], $atts['popup_button'] );
	return $part_quote;
}
add_shortcode( __( 'part_request', 'car-demon' ), 'parts_shortcode_func' );

function service_form_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'location', 'car-demon' ) => __( 'normal', 'car-demon' ),
		__( 'popup_id', 'car-demon' ) => '',
		__( 'popup_button', 'car-demon' ) => __( 'Service Appointment', 'car-demon' ),
	), $atts, __( 'service_form', 'car-demon' ) );
	$service_form = car_demon_service_form( $atts['location'], $atts['popup_id'], $atts['popup_button'] );
	return $service_form;
}
add_shortcode( __('service_form', 'car-demon' ), 'service_form_shortcode_func' );

function service_quote_form_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'location', 'car-demon' ) => __( 'normal', 'car-demon' ),
		__( 'popup_id', 'car-demon' ) => '',
		__( 'popup_button', 'car-demon' ) => __( 'Service Quote', 'car-demon' ),
	), $atts, __( 'service_quote', 'car-demon' ) );
	$service_quote = car_demon_service_quote( $atts['location'], $atts['popup_id'], $atts['popup_button'] );
	return $service_quote;
}
add_shortcode( __( 'service_quote', 'car-demon' ), 'service_quote_form_shortcode_func' );

function trade_form_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'location', 'car-demon' ) => 'normal',
	), $atts, __( 'trade_form', 'car-demon' ) );
	$trade_form = car_demon_trade_form( 0, $atts['location'] );
	return $trade_form;
}
add_shortcode( __( 'trade', 'car-demon' ), 'trade_form_shortcode_func' );

function finance_form_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'location', 'car-demon' ) => __( 'normal', 'car-demon' ),
		__( 'send_to', 'car-demon' ) => '',
	), $atts, __( 'finance_form', 'car-demon' ) );
	$finance_form = car_demon_finance_form( $atts['location'], $atts['send_to'] );
	return $finance_form;
}
add_shortcode( __( 'finance_form', 'car-demon' ), 'finance_form_shortcode_func' );

function qualify_form_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'location', 'car-demon' ) => __( 'normal', 'car-demon' ),
		__( 'popup_id', 'car-demon' ) => '',
		__( 'popup_button', 'car-demon' ) => __( 'Qualify Me', 'car-demon' ),
	), $atts, __( 'qualify_form', 'car-demon' ) );
	$qualify_form = car_demon_qualify_form( $atts['location'], $atts['popup_id'], $atts['popup_button'] );
	return $qualify_form;
}
add_shortcode( __( 'qualify', 'car-demon' ), 'qualify_form_shortcode_func' );

function highlight_staff_shortcode_func( $atts ) {
	if ( isset( $_COOKIE["sales_code"] ) ) {
		$staff_id = $_COOKIE["sales_code"];
	} else {
		$staff_id = '';
	}

	$atts = shortcode_atts( array(
		__( 'staff_id', 'car-demon' ) => $staff_id,
		__( 'contact_id', 'car-demon' ) => '',
		__( 'contact_button', 'car-demon' ) => __('Contact Me', 'car-demon' ),
	), $atts, __( 'highlight_staff', 'car-demon' ) );

	if ( ! empty( $atts['staff_id'] ) ) {
		$highlight_staff = build_user_hcard( $atts['staff_id'], 1, 1 );
		$highlight_staff = '<div class="highlight_staff">' . $highlight_staff . '</div>';
	} else {
		$highlight_staff = '';
	}
	return $highlight_staff;
}
add_shortcode( __( 'highlight_staff', 'car-demon' ), 'highlight_staff_shortcode_func' );

function vehicle_cloud_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'taxonomy', 'car-demon' ) => 'vehicle_body_style',
		__( 'max_num', 'car-demon' ) => '',
		__( 'max_font', 'car-demon' ) => '14',
		__( 'min_font', 'car-demon' ) => '14',
	), $atts, __( 'vehicle_cloud', 'car-demon' ) );
	$vehicle_cloud = vehicle_cloud( $atts['taxonomy'], $atts['max_num'], $atts['max_font'], $atts['min_font'] );
	return $vehicle_cloud;
}
add_shortcode( __( 'vehicle_cloud', 'car-demon' ), 'vehicle_cloud_shortcode_func' );

function vehicle_search_box_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'button', 'car-demon' ) => 'Search Inventory',
		__( 'message', 'car-demon' ) => '',
	), $atts, __( 'vehicle_search_box', 'car-demon' ) );
	$vehicle_cloud = vehicle_search_box( $atts['button'], $atts['message'] );
	return $vehicle_cloud;
}
add_shortcode( __( 'vehicle_search_box', 'car-demon' ), 'vehicle_search_box_shortcode_func' );

function staff_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
	), $atts, __( 'staff_page', 'car-demon' ) );
	$staff_page = car_demon_staff_page();
	return $staff_page;
}
add_shortcode( __( 'staff_page', 'car-demon' ), 'staff_shortcode_func' );

function random_cars_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		__( 'amount', 'car-demon' ) => '1',
	), $atts, 'random_car' );
	$x = car_demon_display_random_cars( $amount );
	return $x;
}
add_shortcode( __( 'random_cars', 'car-demon' ), 'random_cars_shortcode_func' );
?>