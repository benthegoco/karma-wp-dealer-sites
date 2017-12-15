<?php

add_action( 'wp_ajax_cd_insert_sample_vehicles', 'cd_insert_sample_vehicles');

function cd_insert_sample_vehicles() {
	$qty = 3;

	if ( isset( $_POST['qty'] ) ) {
		$qty = sanitize_text_field( $_POST['qty'] );
	}
	
	if ( $qty > 30 ) {
		$qty = 30;
	}
	
	$report = cd_start_sample_import( $qty );
	echo $report;
	exit();
}

function cd_start_sample_import( $stop = 3 ) {
	wp_defer_term_counting( true );
	define( 'WP_IMPORTING', true );
	$cars = cd_sample_cars();
	$report = __( 'The following sample vehicles have been added:', 'car-demon' ) . '<br />';

	$cnt = 0;
	foreach ( $cars as $car ) {
		if ( $cnt == $stop ) {
			break;
		}

		$report .= cd_insert_sample_car( $car );
		++$cnt;
	}

	wp_defer_term_counting( false );
	
	return $report;
}

function cd_insert_sample_car( $car ) {
	$car['title'] = $car['year'] . ' ' . $car['make'] . ' ' . $car['model'];
	
	$user_id = get_current_user_id();
	
	$post = array(
		'post_status' => 'publish', 
		'post_type' => 'cars_for_sale',
		'post_author' => $user_id,
		'post_parent' => 0,
		'menu_order' => 0,
		'post_excerpt' => $car['vin'],
		'post_content' => $car['description'],
		'post_title' => $car['title'],
	 );
	$post_id = wp_insert_post( $post );
	
	$vehicle_details = array();

	update_post_meta( $post_id, '_stock_value', $car['stock_number'], false );
	$vehicle_details['stock_number'] = $car['stock_number'];

	update_post_meta( $post_id, '_vin_value', $car['vin'], false );
	$vehicle_details['vin'] = $car['vin'];

	wp_set_post_terms( $post_id, $car['year'], 'vehicle_year', false );
	$vehicle_details['decoded_model_year'] = $car['year'];

	wp_set_post_terms( $post_id, $car['make'], 'vehicle_make', false );
	$vehicle_details['decoded_make'] = $car['make'];

	wp_set_post_terms( $post_id, $car['model'], 'vehicle_model', false );
	$vehicle_details['decoded_model'] = $car['model'];

	wp_set_post_terms( $post_id, $car['new_used'], 'vehicle_condition', false );
	$vehicle_details['condition'] = $car['new_used'];

	wp_set_post_terms( $post_id, $car['body_type'], 'vehicle_body_style', false );
	$vehicle_details['decoded_body_style'] = $car['body_type'];

	update_post_meta( $post_id, '_exterior_color_value', $car['exterior_color'] );
	$vehicle_details['exterior_color'] = $car['exterior_color'];

	update_post_meta( $post_id, '_interior_color_value', $car['interior_color'] );
	$vehicle_details['interior_color'] = $car['interior_color'];

	update_post_meta( $post_id, '_transmission_value', $car['transmission'] );
	$vehicle_details['decoded_transmission_long'] = $car['transmission'];

	update_post_meta( $post_id, '_engine_value', $car['engine'] );

	update_post_meta( $post_id, '_trim_value', $car['trim_level'] );
	$vehicle_details['decoded_trim_level'] = $car['trim_level'];

	// Need to add vehicle options to the vehicle_details array
	$option_delimiter = ',';
	if ( isset( $car['options'] ) ) {
		$options = str_replace( '|', ',', $car['options'] );
		$option_array = explode( $option_delimiter, $options );
		if( is_array( $option_array ) ) {
			foreach ( $option_array as $option_item ) {
				$option_item = trim( $option_item );
				$slug = strtolower( $option_item );
				$slug = str_replace( ' ', '_',$slug );
				$slug = str_replace( '/', '_',$slug );
				$slug = str_replace( '(', '_',$slug );
				$slug = str_replace( ')', '_',$slug );
				$slug = str_replace( '-', '_',$slug );
				$vehicle_details['decoded_'.$slug] = 'Std.';
			}
		}
		update_post_meta( $post_id, '_vehicle_options', $options );
	}

	update_post_meta( $post_id, 'decode_string', $vehicle_details );

	update_post_meta( $post_id, '_price_value', $car['price'] );

	update_post_meta( $post_id, '_mileage_value', $car['mileage'] );

	//===== Mark Unsold
	update_post_meta( $post_id, 'sold', __( 'No', 'car-demon' ) );

	//= Import Main Photo & thumbnails
	cd_import_sample_images( $post_id, $car );

	//=== Get link to new vehicle and return it
	$link = get_permalink( $post_id );
	$car_report = '<div class=""><a href="' . $link . '" target="_blank">' . $car['title'] . ' added</a></div>';

	return $car_report;
}

function cd_import_sample_images( $post_id, $car ) {
	$photo_array = explode( ',', $car['image_links'] );
	$first_image = 0;
	$links = '';

	if ( defined( 'CD_IMPORT_SAMPLE_PHOTOS' ) ) {
		foreach ( $photo_array as $url ) {
			$image = media_sideload_image( $url, $post_id, $car['title'], 'src' );
			if ( $first_image == 0 ) {
				$first_image = 1;
				$image_id = cd_get_image_id( $image );
				add_post_meta( $post_id, '_thumbnail_id', $image_id, true );
			}
		}
	} else {
		foreach ( $photo_array as $url ) {
			if ( $first_image == 0 ) {
				$first_image = 1;
				$image = media_sideload_image( $url, $post_id, $car['title'], 'src' );
				$image_id = cd_get_image_id( $image );
				add_post_meta( $post_id, '_thumbnail_id', $image_id, true );
			} else {
				$links .= $url . ',';
			}
		}
	}

	if ( ! defined( 'CD_IMPORT_SAMPLE_PHOTOS' ) ) {
		//= remove trailing comma
		$links .= '###';
		$links = str_replace( ',###', '', $links );
	
		//= insert gallery as links
		update_post_meta( $post_id, '_images_value', $links, false );
	}
}

function cd_get_image_id( $image_url ) {
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) ); 
        return $attachment[0];
}

function cd_sample_cars() {
	$cars = array(
		array(
		  'price' => '29585',
		  'new_used' => 'new',
		  'stock_number' => '112299',
		  'vin' => '1J4GW48NXYC114398',
		  'year' => '2016',
		  'make' => 'Jeep',
		  'model' => 'Grand Cherokee',
		  'trim_level' => 'Laredo',
		  'mileage' => '120708',
		  'exterior_color' => 'Red',
		  'interior_color' => 'Agate',
		  'body_type' => 'SUV 4X4',
		  'engine' => '4.7',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1J4GW48NXYC114399/1J4GW48NXYC114399---.jpg,https://cardemons.com/vehicles/1J4GW48NXYC114399/1J4GW48NXYC114399---2.jpg,https://cardemons.com/vehicles/1J4GW48NXYC114399/1J4GW48NXYC114399---3.jpg,https://cardemons.com/vehicles/1J4GW48NXYC114399/1J4GW48NXYC114399---4.jpg,https://cardemons.com/vehicles/1J4GW48NXYC114399/1J4GW48NXYC114399---5.jpg,https://cardemons.com/vehicles/1J4GW48NXYC114399/1J4GW48NXYC114399---6.jpg,https://cardemons.com/vehicles/1J4GW48NXYC114399/1J4GW48NXYC114399---7.jpg,https://cardemons.com/vehicles/1J4GW48NXYC114399/1J4GW48NXYC114399---8.jpg,https://cardemons.com/vehicles/1J4GW48NXYC114399/1J4GW48NXYC114399---9.jpg',
		  'certified' => '',
		  'new_title' => '2000 Jeep Grand Cherokee',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '10453',
		  'new_used' => 'preowned',
		  'stock_number' => '426581',
		  'vin' => '5TDZA23C26S424082',
		  'year' => '2006',
		  'make' => 'Toyota',
		  'model' => 'Sienna',
		  'trim_level' => 'LE 8 Passenger',
		  'mileage' => '134654',
		  'exterior_color' => 'Silver',
		  'interior_color' => 'Stone',
		  'body_type' => 'Mini-van',
		  'engine' => '3.3',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/5TDZA23C26S424081/5TDZA23C26S424081---.jpg,https://cardemons.com/vehicles/5TDZA23C26S424081/5TDZA23C26S424081---2.jpg,https://cardemons.com/vehicles/5TDZA23C26S424081/5TDZA23C26S424081---3.jpg,https://cardemons.com/vehicles/5TDZA23C26S424081/5TDZA23C26S424081---4.jpg,https://cardemons.com/vehicles/5TDZA23C26S424081/5TDZA23C26S424081---5.jpg,https://cardemons.com/vehicles/5TDZA23C26S424081/5TDZA23C26S424081---6.jpg,https://cardemons.com/vehicles/5TDZA23C26S424081/5TDZA23C26S424081---7.jpg,https://cardemons.com/vehicles/5TDZA23C26S424081/5TDZA23C26S424081---8.jpg,https://cardemons.com/vehicles/5TDZA23C26S424081/5TDZA23C26S424081---9.jpg',
		  'certified' => '',
		  'new_title' => '2006 Toyota Sienna',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '0',
		  'new_used' => 'preowned',
		  'stock_number' => '105682',
		  'vin' => '5J8TB185X8A007883',
		  'year' => '2008',
		  'make' => 'Acura',
		  'model' => 'RDX',
		  'trim_level' => 'SH-AWD w/Tech',
		  'mileage' => '128792',
		  'exterior_color' => 'Silver',
		  'interior_color' => 'Taupe',
		  'body_type' => 'SUV AWD',
		  'engine' => '2.3',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/5J8TB185X8A007882/5J8TB185X8A007882---.jpg,https://cardemons.com/vehicles/5J8TB185X8A007882/5J8TB185X8A007882---2.jpg,https://cardemons.com/vehicles/5J8TB185X8A007882/5J8TB185X8A007882---3.jpg,https://cardemons.com/vehicles/5J8TB185X8A007882/5J8TB185X8A007882---4.jpg,https://cardemons.com/vehicles/5J8TB185X8A007882/5J8TB185X8A007882---5.jpg,https://cardemons.com/vehicles/5J8TB185X8A007882/5J8TB185X8A007882---6.jpg,https://cardemons.com/vehicles/5J8TB185X8A007882/5J8TB185X8A007882---7.jpg,https://cardemons.com/vehicles/5J8TB185X8A007882/5J8TB185X8A007882---8.jpg,https://cardemons.com/vehicles/5J8TB185X8A007882/5J8TB185X8A007882---9.jpg',
		  'certified' => '',
		  'new_title' => '2008 Acura RDX',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '14589',
		  'new_used' => 'preowned',
		  'stock_number' => '1825A',
		  'vin' => '1GCRKTE30BZ185249',
		  'year' => '2011',
		  'make' => 'Chevrolet',
		  'model' => 'Silverado 1500',
		  'trim_level' => 'LTZ',
		  'mileage' => '75519',
		  'exterior_color' => 'White',
		  'interior_color' => 'Ebony',
		  'body_type' => 'Extended Cab Pickup 4X4',
		  'engine' => '5.3',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1GCRKTE30BZ185249/1GCRKTE30BZ185249---.jpg,https://cardemons.com/vehicles/1GCRKTE30BZ185249/1GCRKTE30BZ185249---2.jpg,https://cardemons.com/vehicles/1GCRKTE30BZ185249/1GCRKTE30BZ185249---3.jpg,https://cardemons.com/vehicles/1GCRKTE30BZ185249/1GCRKTE30BZ185249---4.jpg,https://cardemons.com/vehicles/1GCRKTE30BZ185249/1GCRKTE30BZ185249---5.jpg,https://cardemons.com/vehicles/1GCRKTE30BZ185249/1GCRKTE30BZ185249---6.jpg,https://cardemons.com/vehicles/1GCRKTE30BZ185249/1GCRKTE30BZ185249---7.jpg,https://cardemons.com/vehicles/1GCRKTE30BZ185249/1GCRKTE30BZ185249---8.jpg,https://cardemons.com/vehicles/1GCRKTE30BZ185249/1GCRKTE30BZ185249---9.jpg',
		  'certified' => '',
		  'new_title' => '2011 Chevrolet Silverado 1500',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '8450',
		  'new_used' => 'preowned',
		  'stock_number' => '146623',
		  'vin' => '1GCCS139378146623',
		  'year' => '2007',
		  'make' => 'Chevrolet',
		  'model' => 'Colorado',
		  'trim_level' => 'LT',
		  'mileage' => '144115',
		  'exterior_color' => 'Blue',
		  'interior_color' => 'Very Dark Pewter',
		  'body_type' => 'LT 4dr Crew Cab SB',
		  'engine' => '2.9',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1GCCS139378146623/1GCCS139378146623---.jpg,https://cardemons.com/vehicles/1GCCS139378146623/1GCCS139378146623---2.jpg,https://cardemons.com/vehicles/1GCCS139378146623/1GCCS139378146623---3.jpg,https://cardemons.com/vehicles/1GCCS139378146623/1GCCS139378146623---4.jpg,https://cardemons.com/vehicles/1GCCS139378146623/1GCCS139378146623---5.jpg,https://cardemons.com/vehicles/1GCCS139378146623/1GCCS139378146623---6.jpg,https://cardemons.com/vehicles/1GCCS139378146623/1GCCS139378146623---7.jpg,https://cardemons.com/vehicles/1GCCS139378146623/1GCCS139378146623---8.jpg,https://cardemons.com/vehicles/1GCCS139378146623/1GCCS139378146623---9.jpg',
		  'certified' => '',
		  'new_title' => '2007 Chevrolet Colorado',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '11542',
		  'new_used' => 'preowned',
		  'stock_number' => '1835',
		  'vin' => '2MRDA23285BJ03421',
		  'year' => '2005',
		  'make' => 'Mercury',
		  'model' => 'Monterey',
		  'trim_level' => 'Premier',
		  'mileage' => '72829',
		  'exterior_color' => 'Black',
		  'interior_color' => 'Charcoal',
		  'body_type' => 'Premier 4dr Mini-Van',
		  'engine' => '4.2',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/2MRDA23285BJ03421/2MRDA23285BJ03421---.jpg,https://cardemons.com/vehicles/2MRDA23285BJ03421/2MRDA23285BJ03421---2.jpg,https://cardemons.com/vehicles/2MRDA23285BJ03421/2MRDA23285BJ03421---3.jpg,https://cardemons.com/vehicles/2MRDA23285BJ03421/2MRDA23285BJ03421---4.jpg,https://cardemons.com/vehicles/2MRDA23285BJ03421/2MRDA23285BJ03421---5.jpg,https://cardemons.com/vehicles/2MRDA23285BJ03421/2MRDA23285BJ03421---6.jpg,https://cardemons.com/vehicles/2MRDA23285BJ03421/2MRDA23285BJ03421---7.jpg,https://cardemons.com/vehicles/2MRDA23285BJ03421/2MRDA23285BJ03421---8.jpg,https://cardemons.com/vehicles/2MRDA23285BJ03421/2MRDA23285BJ03421---9.jpg',
		  'certified' => '',
		  'new_title' => '2005 Mercury Monterey',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '6950',
		  'new_used' => 'preowned',
		  'stock_number' => '1833',
		  'vin' => '1FMCU0D70AKC97264',
		  'year' => '2010',
		  'make' => 'Ford',
		  'model' => 'Escape',
		  'trim_level' => 'XLT',
		  'mileage' => '112949',
		  'exterior_color' => 'Dk. Gray',
		  'interior_color' => 'Stone',
		  'body_type' => 'XLT 4dr SUV',
		  'engine' => '2.5',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1FMCU0D70AKC97264/1FMCU0D70AKC97264---.jpg,https://cardemons.com/vehicles/1FMCU0D70AKC97264/1FMCU0D70AKC97264---2.jpg,https://cardemons.com/vehicles/1FMCU0D70AKC97264/1FMCU0D70AKC97264---3.jpg,https://cardemons.com/vehicles/1FMCU0D70AKC97264/1FMCU0D70AKC97264---4.jpg,https://cardemons.com/vehicles/1FMCU0D70AKC97264/1FMCU0D70AKC97264---5.jpg,https://cardemons.com/vehicles/1FMCU0D70AKC97264/1FMCU0D70AKC97264---6.jpg,https://cardemons.com/vehicles/1FMCU0D70AKC97264/1FMCU0D70AKC97264---7.jpg,https://cardemons.com/vehicles/1FMCU0D70AKC97264/1FMCU0D70AKC97264---8.jpg,https://cardemons.com/vehicles/1FMCU0D70AKC97264/1FMCU0D70AKC97264---9.jpg',
		  'certified' => '',
		  'new_title' => '2010 Ford Escape',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '6540',
		  'new_used' => 'preowned',
		  'stock_number' => '1822',
		  'vin' => '2FMDK48C27BA90239',
		  'year' => '2007',
		  'make' => 'Ford',
		  'model' => 'Edge',
		  'trim_level' => 'SEL',
		  'mileage' => '125921',
		  'exterior_color' => 'Off White',
		  'interior_color' => 'Camel',
		  'body_type' => 'AWD SEL 4dr SUV',
		  'engine' => '3.5',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/2FMDK48C27BA90239/2FMDK48C27BA90239---.jpg,https://cardemons.com/vehicles/2FMDK48C27BA90239/2FMDK48C27BA90239---2.jpg,https://cardemons.com/vehicles/2FMDK48C27BA90239/2FMDK48C27BA90239---3.jpg,https://cardemons.com/vehicles/2FMDK48C27BA90239/2FMDK48C27BA90239---4.jpg,https://cardemons.com/vehicles/2FMDK48C27BA90239/2FMDK48C27BA90239---5.jpg,https://cardemons.com/vehicles/2FMDK48C27BA90239/2FMDK48C27BA90239---6.jpg,https://cardemons.com/vehicles/2FMDK48C27BA90239/2FMDK48C27BA90239---7.jpg,https://cardemons.com/vehicles/2FMDK48C27BA90239/2FMDK48C27BA90239---8.jpg,https://cardemons.com/vehicles/2FMDK48C27BA90239/2FMDK48C27BA90239---9.jpg',
		  'certified' => '',
		  'new_title' => '2007 Ford Edge',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '7580',
		  'new_used' => 'preowned',
		  'stock_number' => '1788A',
		  'vin' => 'NM0KS9BN4AT030647',
		  'year' => '2010',
		  'make' => 'Ford',
		  'model' => 'Transit Connect',
		  'trim_level' => 'Wagon XLT',
		  'mileage' => '103483',
		  'exterior_color' => 'Red',
		  'interior_color' => 'Dark Grey',
		  'body_type' => 'Wagon XLT 4dr Mini-Van',
		  'engine' => '2.0',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/NM0KS9BN4AT030647/NM0KS9BN4AT030647---.jpg,https://cardemons.com/vehicles/NM0KS9BN4AT030647/NM0KS9BN4AT030647---2.jpg,https://cardemons.com/vehicles/NM0KS9BN4AT030647/NM0KS9BN4AT030647---3.jpg,https://cardemons.com/vehicles/NM0KS9BN4AT030647/NM0KS9BN4AT030647---4.jpg,https://cardemons.com/vehicles/NM0KS9BN4AT030647/NM0KS9BN4AT030647---5.jpg,https://cardemons.com/vehicles/NM0KS9BN4AT030647/NM0KS9BN4AT030647---6.jpg,https://cardemons.com/vehicles/NM0KS9BN4AT030647/NM0KS9BN4AT030647---7.jpg,https://cardemons.com/vehicles/NM0KS9BN4AT030647/NM0KS9BN4AT030647---8.jpg,https://cardemons.com/vehicles/NM0KS9BN4AT030647/NM0KS9BN4AT030647---9.jpg',
		  'certified' => '',
		  'new_title' => '2010 Ford Transit Connect',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '9780',
		  'new_used' => 'preowned',
		  'stock_number' => '1824',
		  'vin' => '1J4GW58N63C538158',
		  'year' => '2003',
		  'make' => 'Jeep',
		  'model' => 'Grand Cherokee',
		  'trim_level' => 'Limited',
		  'mileage' => '139324',
		  'exterior_color' => 'Dk. Gray',
		  'interior_color' => 'Dark Slate Gray',
		  'body_type' => 'Limited 4WD 4dr SUV',
		  'engine' => '4.7',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1J4GW58N63C538158/1J4GW58N63C538158---.jpg,https://cardemons.com/vehicles/1J4GW58N63C538158/1J4GW58N63C538158---2.jpg,https://cardemons.com/vehicles/1J4GW58N63C538158/1J4GW58N63C538158---3.jpg,https://cardemons.com/vehicles/1J4GW58N63C538158/1J4GW58N63C538158---4.jpg,https://cardemons.com/vehicles/1J4GW58N63C538158/1J4GW58N63C538158---5.jpg,https://cardemons.com/vehicles/1J4GW58N63C538158/1J4GW58N63C538158---6.jpg,https://cardemons.com/vehicles/1J4GW58N63C538158/1J4GW58N63C538158---7.jpg,https://cardemons.com/vehicles/1J4GW58N63C538158/1J4GW58N63C538158---8.jpg,https://cardemons.com/vehicles/1J4GW58N63C538158/1J4GW58N63C538158---9.jpg',
		  'certified' => '',
		  'new_title' => '2003 Jeep Grand Cherokee',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '9850',
		  'new_used' => 'preowned',
		  'stock_number' => '1807A',
		  'vin' => '1D4HB58D84F227556',
		  'year' => '2004',
		  'make' => 'Dodge',
		  'model' => 'Durango',
		  'trim_level' => 'Limited',
		  'mileage' => '124733',
		  'exterior_color' => 'Silver',
		  'interior_color' => 'Medium Slate Gray',
		  'body_type' => 'Limited 4WD 4dr SUV',
		  'engine' => '5.7',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1D4HB58D84F227556/1D4HB58D84F227556---.jpg,https://cardemons.com/vehicles/1D4HB58D84F227556/1D4HB58D84F227556---2.jpg,https://cardemons.com/vehicles/1D4HB58D84F227556/1D4HB58D84F227556---3.jpg,https://cardemons.com/vehicles/1D4HB58D84F227556/1D4HB58D84F227556---4.jpg,https://cardemons.com/vehicles/1D4HB58D84F227556/1D4HB58D84F227556---5.jpg,https://cardemons.com/vehicles/1D4HB58D84F227556/1D4HB58D84F227556---6.jpg,https://cardemons.com/vehicles/1D4HB58D84F227556/1D4HB58D84F227556---7.jpg,https://cardemons.com/vehicles/1D4HB58D84F227556/1D4HB58D84F227556---8.jpg,https://cardemons.com/vehicles/1D4HB58D84F227556/1D4HB58D84F227556---9.jpg',
		  'certified' => '',
		  'new_title' => '2004 Dodge Durango',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '9345',
		  'new_used' => 'preowned',
		  'stock_number' => '233063',
		  'vin' => '1J4GR48KX6C233063',
		  'year' => '2006',
		  'make' => 'Jeep',
		  'model' => 'Grand Cherokee',
		  'trim_level' => 'Laredo',
		  'mileage' => '136250',
		  'exterior_color' => 'White',
		  'interior_color' => 'Khaki',
		  'body_type' => 'Laredo 4dr SUV 4WD',
		  'engine' => '3.7',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1J4GR48KX6C233063/1J4GR48KX6C233063---.jpg,https://cardemons.com/vehicles/1J4GR48KX6C233063/1J4GR48KX6C233063---2.jpg,https://cardemons.com/vehicles/1J4GR48KX6C233063/1J4GR48KX6C233063---3.jpg,https://cardemons.com/vehicles/1J4GR48KX6C233063/1J4GR48KX6C233063---4.jpg,https://cardemons.com/vehicles/1J4GR48KX6C233063/1J4GR48KX6C233063---5.jpg,https://cardemons.com/vehicles/1J4GR48KX6C233063/1J4GR48KX6C233063---6.jpg,https://cardemons.com/vehicles/1J4GR48KX6C233063/1J4GR48KX6C233063---7.jpg,https://cardemons.com/vehicles/1J4GR48KX6C233063/1J4GR48KX6C233063---8.jpg,https://cardemons.com/vehicles/1J4GR48KX6C233063/1J4GR48KX6C233063---9.jpg',
		  'certified' => '',
		  'new_title' => '2006 Jeep Grand Cherokee',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '12560',
		  'new_used' => 'preowned',
		  'stock_number' => '554845',
		  'vin' => '2GCEK13M171554845',
		  'year' => '2007',
		  'make' => 'Chevrolet',
		  'model' => 'Silverado 1500',
		  'trim_level' => 'LTZ',
		  'mileage' => '101998',
		  'exterior_color' => 'Silver',
		  'interior_color' => 'Ebony',
		  'body_type' => 'LTZ 4dr Crew Cab 4WD 5.8 ft. SB',
		  'engine' => '5.3',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/2GCEK13M171554845/2GCEK13M171554845---.jpg,https://cardemons.com/vehicles/2GCEK13M171554845/2GCEK13M171554845---2.jpg,https://cardemons.com/vehicles/2GCEK13M171554845/2GCEK13M171554845---3.jpg,https://cardemons.com/vehicles/2GCEK13M171554845/2GCEK13M171554845---4.jpg,https://cardemons.com/vehicles/2GCEK13M171554845/2GCEK13M171554845---5.jpg,https://cardemons.com/vehicles/2GCEK13M171554845/2GCEK13M171554845---6.jpg,https://cardemons.com/vehicles/2GCEK13M171554845/2GCEK13M171554845---7.jpg,https://cardemons.com/vehicles/2GCEK13M171554845/2GCEK13M171554845---8.jpg,https://cardemons.com/vehicles/2GCEK13M171554845/2GCEK13M171554845---9.jpg',
		  'certified' => '',
		  'new_title' => '2007 Chevrolet Silverado 1500',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '9850',
		  'new_used' => 'preowned',
		  'stock_number' => '187508',
		  'vin' => '1D7HW22K37S187508',
		  'year' => '2007',
		  'make' => 'Dodge',
		  'model' => 'Dakota',
		  'trim_level' => 'ST',
		  'mileage' => '133892',
		  'exterior_color' => 'Black',
		  'interior_color' => 'Medium Slate Gray',
		  'body_type' => 'ST 4dr Club Cab 4x4 SB',
		  'engine' => '3.7',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1D7HW22K37S187508/1D7HW22K37S187508---.jpg,https://cardemons.com/vehicles/1D7HW22K37S187508/1D7HW22K37S187508---2.jpg,https://cardemons.com/vehicles/1D7HW22K37S187508/1D7HW22K37S187508---3.jpg,https://cardemons.com/vehicles/1D7HW22K37S187508/1D7HW22K37S187508---4.jpg,https://cardemons.com/vehicles/1D7HW22K37S187508/1D7HW22K37S187508---5.jpg,https://cardemons.com/vehicles/1D7HW22K37S187508/1D7HW22K37S187508---6.jpg,https://cardemons.com/vehicles/1D7HW22K37S187508/1D7HW22K37S187508---7.jpg,https://cardemons.com/vehicles/1D7HW22K37S187508/1D7HW22K37S187508---8.jpg,https://cardemons.com/vehicles/1D7HW22K37S187508/1D7HW22K37S187508---9.jpg',
		  'certified' => '',
		  'new_title' => '2007 Dodge Dakota',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '6870',
		  'new_used' => 'preowned',
		  'stock_number' => '1747',
		  'vin' => '2G1WF52E649137589',
		  'year' => '2004',
		  'make' => 'Chevrolet',
		  'model' => 'Impala',
		  'trim_level' => '',
		  'mileage' => '118803',
		  'exterior_color' => 'Blue',
		  'interior_color' => 'Medium Gray',
		  'body_type' => '4dr Sedan',
		  'engine' => '3.4',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/2G1WF52E649137589/2G1WF52E649137589---.jpg,https://cardemons.com/vehicles/2G1WF52E649137589/2G1WF52E649137589---2.jpg,https://cardemons.com/vehicles/2G1WF52E649137589/2G1WF52E649137589---3.jpg,https://cardemons.com/vehicles/2G1WF52E649137589/2G1WF52E649137589---4.jpg,https://cardemons.com/vehicles/2G1WF52E649137589/2G1WF52E649137589---5.jpg,https://cardemons.com/vehicles/2G1WF52E649137589/2G1WF52E649137589---6.jpg,https://cardemons.com/vehicles/2G1WF52E649137589/2G1WF52E649137589---7.jpg,https://cardemons.com/vehicles/2G1WF52E649137589/2G1WF52E649137589---8.jpg,https://cardemons.com/vehicles/2G1WF52E649137589/2G1WF52E649137589---9.jpg',
		  'certified' => '',
		  'new_title' => '2004 Chevrolet Impala',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '9950',
		  'new_used' => 'preowned',
		  'stock_number' => '1805',
		  'vin' => '1GKDT33S292110478',
		  'year' => '2009',
		  'make' => 'GMC',
		  'model' => 'Envoy',
		  'trim_level' => 'SLE',
		  'mileage' => '117932',
		  'exterior_color' => 'Black',
		  'interior_color' => 'Light Gray',
		  'body_type' => '4x4 SLE 4dr SUV',
		  'engine' => '4.2',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1GKDT33S292110478/1GKDT33S292110478---.jpg,https://cardemons.com/vehicles/1GKDT33S292110478/1GKDT33S292110478---2.jpg,https://cardemons.com/vehicles/1GKDT33S292110478/1GKDT33S292110478---3.jpg,https://cardemons.com/vehicles/1GKDT33S292110478/1GKDT33S292110478---4.jpg,https://cardemons.com/vehicles/1GKDT33S292110478/1GKDT33S292110478---5.jpg,https://cardemons.com/vehicles/1GKDT33S292110478/1GKDT33S292110478---6.jpg,https://cardemons.com/vehicles/1GKDT33S292110478/1GKDT33S292110478---7.jpg,https://cardemons.com/vehicles/1GKDT33S292110478/1GKDT33S292110478---8.jpg,https://cardemons.com/vehicles/1GKDT33S292110478/1GKDT33S292110478---9.jpg',
		  'certified' => '',
		  'new_title' => '2009 GMC Envoy',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '12450',
		  'new_used' => 'preowned',
		  'stock_number' => '1809',
		  'vin' => '1FTNX21F53EA82315',
		  'year' => '2003',
		  'make' => 'Ford',
		  'model' => 'F-250 Super Duty',
		  'trim_level' => 'XLT',
		  'mileage' => '186200',
		  'exterior_color' => 'Blue',
		  'interior_color' => 'Dark Flint',
		  'body_type' => '4dr SuperCab XLT 4WD SB',
		  'engine' => '7.3',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1FTNX21F53EA82315/1FTNX21F53EA82315---.jpg,https://cardemons.com/vehicles/1FTNX21F53EA82315/1FTNX21F53EA82315---2.jpg,https://cardemons.com/vehicles/1FTNX21F53EA82315/1FTNX21F53EA82315---3.jpg,https://cardemons.com/vehicles/1FTNX21F53EA82315/1FTNX21F53EA82315---4.jpg,https://cardemons.com/vehicles/1FTNX21F53EA82315/1FTNX21F53EA82315---5.jpg,https://cardemons.com/vehicles/1FTNX21F53EA82315/1FTNX21F53EA82315---6.jpg,https://cardemons.com/vehicles/1FTNX21F53EA82315/1FTNX21F53EA82315---7.jpg,https://cardemons.com/vehicles/1FTNX21F53EA82315/1FTNX21F53EA82315---8.jpg,https://cardemons.com/vehicles/1FTNX21F53EA82315/1FTNX21F53EA82315---9.jpg',
		  'certified' => '',
		  'new_title' => '2003 Ford F-250 Super Duty',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '6500',
		  'new_used' => 'preowned',
		  'stock_number' => '121587',
		  'vin' => '1FAFP53245A121587',
		  'year' => '2005',
		  'make' => 'Ford',
		  'model' => 'Taurus',
		  'trim_level' => 'SE',
		  'mileage' => '143871',
		  'exterior_color' => 'Beige',
		  'interior_color' => 'Medium/Dark Pebble',
		  'body_type' => 'SE 4dr Sedan',
		  'engine' => '3.0',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1FAFP53245A121587/1FAFP53245A121587---.jpg,https://cardemons.com/vehicles/1FAFP53245A121587/1FAFP53245A121587---2.jpg,https://cardemons.com/vehicles/1FAFP53245A121587/1FAFP53245A121587---3.jpg,https://cardemons.com/vehicles/1FAFP53245A121587/1FAFP53245A121587---4.jpg,https://cardemons.com/vehicles/1FAFP53245A121587/1FAFP53245A121587---5.jpg,https://cardemons.com/vehicles/1FAFP53245A121587/1FAFP53245A121587---6.jpg,https://cardemons.com/vehicles/1FAFP53245A121587/1FAFP53245A121587---7.jpg,https://cardemons.com/vehicles/1FAFP53245A121587/1FAFP53245A121587---8.jpg,https://cardemons.com/vehicles/1FAFP53245A121587/1FAFP53245A121587---9.jpg',
		  'certified' => '',
		  'new_title' => '2005 Ford Taurus',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '7500',
		  'new_used' => 'preowned',
		  'stock_number' => '1517RR',
		  'vin' => '2FMZA53473BA56421',
		  'year' => '2003',
		  'make' => 'Ford',
		  'model' => 'Windstar',
		  'trim_level' => 'SEL',
		  'mileage' => '200117',
		  'exterior_color' => 'Red',
		  'interior_color' => 'Medium Parchment',
		  'body_type' => 'SEL 4dr Mini-Van',
		  'engine' => '3.8',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/2FMZA53473BA56421/2FMZA53473BA56421---.jpg,https://cardemons.com/vehicles/2FMZA53473BA56421/2FMZA53473BA56421---2.jpg,https://cardemons.com/vehicles/2FMZA53473BA56421/2FMZA53473BA56421---3.jpg,https://cardemons.com/vehicles/2FMZA53473BA56421/2FMZA53473BA56421---4.jpg,https://cardemons.com/vehicles/2FMZA53473BA56421/2FMZA53473BA56421---5.jpg,https://cardemons.com/vehicles/2FMZA53473BA56421/2FMZA53473BA56421---6.jpg,https://cardemons.com/vehicles/2FMZA53473BA56421/2FMZA53473BA56421---7.jpg,https://cardemons.com/vehicles/2FMZA53473BA56421/2FMZA53473BA56421---8.jpg,https://cardemons.com/vehicles/2FMZA53473BA56421/2FMZA53473BA56421---9.jpg',
		  'certified' => '',
		  'new_title' => '2003 Ford Windstar',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '8400',
		  'new_used' => 'preowned',
		  'stock_number' => '1798',
		  'vin' => '1NXBU4EE4AZ174393',
		  'year' => '2010',
		  'make' => 'Toyota',
		  'model' => 'Corolla',
		  'trim_level' => 'LE',
		  'mileage' => '131903',
		  'exterior_color' => 'Blue',
		  'interior_color' => 'Ash',
		  'body_type' => 'LE 4dr Sedan 4A',
		  'engine' => '1.8',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1NXBU4EE4AZ174393/1NXBU4EE4AZ174393---.jpg,https://cardemons.com/vehicles/1NXBU4EE4AZ174393/1NXBU4EE4AZ174393---2.jpg,https://cardemons.com/vehicles/1NXBU4EE4AZ174393/1NXBU4EE4AZ174393---3.jpg,https://cardemons.com/vehicles/1NXBU4EE4AZ174393/1NXBU4EE4AZ174393---4.jpg,https://cardemons.com/vehicles/1NXBU4EE4AZ174393/1NXBU4EE4AZ174393---5.jpg,https://cardemons.com/vehicles/1NXBU4EE4AZ174393/1NXBU4EE4AZ174393---6.jpg,https://cardemons.com/vehicles/1NXBU4EE4AZ174393/1NXBU4EE4AZ174393---7.jpg,https://cardemons.com/vehicles/1NXBU4EE4AZ174393/1NXBU4EE4AZ174393---8.jpg,https://cardemons.com/vehicles/1NXBU4EE4AZ174393/1NXBU4EE4AZ174393---9.jpg',
		  'certified' => '',
		  'new_title' => '2010 Toyota Corolla',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '9970',
		  'new_used' => 'preowned',
		  'stock_number' => '1796',
		  'vin' => '2D4GP44L93R279161',
		  'year' => '2003',
		  'make' => 'Dodge',
		  'model' => 'Grand Caravan',
		  'trim_level' => 'Sport',
		  'mileage' => '143365',
		  'exterior_color' => 'Dk. Blue',
		  'interior_color' => 'Taupe',
		  'body_type' => 'Sport 4dr Extended Mini-Van',
		  'engine' => '3.8',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/2D4GP44L93R279161/2D4GP44L93R279161---.jpg,https://cardemons.com/vehicles/2D4GP44L93R279161/2D4GP44L93R279161---2.jpg,https://cardemons.com/vehicles/2D4GP44L93R279161/2D4GP44L93R279161---3.jpg,https://cardemons.com/vehicles/2D4GP44L93R279161/2D4GP44L93R279161---4.jpg,https://cardemons.com/vehicles/2D4GP44L93R279161/2D4GP44L93R279161---5.jpg,https://cardemons.com/vehicles/2D4GP44L93R279161/2D4GP44L93R279161---6.jpg,https://cardemons.com/vehicles/2D4GP44L93R279161/2D4GP44L93R279161---7.jpg,https://cardemons.com/vehicles/2D4GP44L93R279161/2D4GP44L93R279161---8.jpg,https://cardemons.com/vehicles/2D4GP44L93R279161/2D4GP44L93R279161---9.jpg',
		  'certified' => '',
		  'new_title' => '2003 Dodge Grand Caravan',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '9995',
		  'new_used' => 'preowned',
		  'stock_number' => '1780',
		  'vin' => '1GNEK13T84J320962',
		  'year' => '2004',
		  'make' => 'Chevrolet',
		  'model' => 'Tahoe',
		  'trim_level' => '',
		  'mileage' => '144819',
		  'exterior_color' => 'Black',
		  'interior_color' => 'Gray/Dark Charcoal',
		  'body_type' => '4dr STD 4WD SUV',
		  'engine' => '5.3',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1GNEK13T84J320962/1GNEK13T84J320962---.jpg,https://cardemons.com/vehicles/1GNEK13T84J320962/1GNEK13T84J320962---2.jpg,https://cardemons.com/vehicles/1GNEK13T84J320962/1GNEK13T84J320962---3.jpg,https://cardemons.com/vehicles/1GNEK13T84J320962/1GNEK13T84J320962---4.jpg,https://cardemons.com/vehicles/1GNEK13T84J320962/1GNEK13T84J320962---5.jpg,https://cardemons.com/vehicles/1GNEK13T84J320962/1GNEK13T84J320962---6.jpg,https://cardemons.com/vehicles/1GNEK13T84J320962/1GNEK13T84J320962---7.jpg,https://cardemons.com/vehicles/1GNEK13T84J320962/1GNEK13T84J320962---8.jpg,https://cardemons.com/vehicles/1GNEK13T84J320962/1GNEK13T84J320962---9.jpg',
		  'certified' => '',
		  'new_title' => '2004 Chevrolet Tahoe',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '9595',
		  'new_used' => 'preowned',
		  'stock_number' => '1781',
		  'vin' => '1FTRW08L11KD77236',
		  'year' => '2001',
		  'make' => 'Ford',
		  'model' => 'F-150',
		  'trim_level' => 'XLT',
		  'mileage' => '92805',
		  'exterior_color' => 'Black',
		  'interior_color' => 'Dark Graphite',
		  'body_type' => '4dr SuperCrew XLT 4WD Styleside SB',
		  'engine' => '5.4',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1FTRW08L11KD77236/1FTRW08L11KD77236---.jpg,https://cardemons.com/vehicles/1FTRW08L11KD77236/1FTRW08L11KD77236---2.jpg,https://cardemons.com/vehicles/1FTRW08L11KD77236/1FTRW08L11KD77236---3.jpg,https://cardemons.com/vehicles/1FTRW08L11KD77236/1FTRW08L11KD77236---4.jpg,https://cardemons.com/vehicles/1FTRW08L11KD77236/1FTRW08L11KD77236---5.jpg,https://cardemons.com/vehicles/1FTRW08L11KD77236/1FTRW08L11KD77236---6.jpg,https://cardemons.com/vehicles/1FTRW08L11KD77236/1FTRW08L11KD77236---7.jpg,https://cardemons.com/vehicles/1FTRW08L11KD77236/1FTRW08L11KD77236---8.jpg,https://cardemons.com/vehicles/1FTRW08L11KD77236/1FTRW08L11KD77236---9.jpg',
		  'certified' => '',
		  'new_title' => '2001 Ford F-150',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '4995',
		  'new_used' => 'preowned',
		  'stock_number' => 'A77988',
		  'vin' => '2FMDA57695BA77988',
		  'year' => '2005',
		  'make' => 'Ford',
		  'model' => 'Freestar',
		  'trim_level' => 'SES',
		  'mileage' => '137163',
		  'exterior_color' => 'Dk. Gray',
		  'interior_color' => 'Flint',
		  'body_type' => 'SES 4dr Minivan',
		  'engine' => '3.9',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/2FMDA57695BA77988/2FMDA57695BA77988---.jpg,https://cardemons.com/vehicles/2FMDA57695BA77988/2FMDA57695BA77988---2.jpg,https://cardemons.com/vehicles/2FMDA57695BA77988/2FMDA57695BA77988---3.jpg,https://cardemons.com/vehicles/2FMDA57695BA77988/2FMDA57695BA77988---4.jpg,https://cardemons.com/vehicles/2FMDA57695BA77988/2FMDA57695BA77988---5.jpg,https://cardemons.com/vehicles/2FMDA57695BA77988/2FMDA57695BA77988---6.jpg,https://cardemons.com/vehicles/2FMDA57695BA77988/2FMDA57695BA77988---7.jpg,https://cardemons.com/vehicles/2FMDA57695BA77988/2FMDA57695BA77988---8.jpg,https://cardemons.com/vehicles/2FMDA57695BA77988/2FMDA57695BA77988---9.jpg',
		  'certified' => '',
		  'new_title' => '2005 Ford Freestar',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '6550',
		  'new_used' => 'preowned',
		  'stock_number' => '598304',
		  'vin' => '1J4GW58N91C598304',
		  'year' => '2001',
		  'make' => 'Jeep',
		  'model' => 'Grand Cherokee',
		  'trim_level' => 'Limited',
		  'mileage' => '156085',
		  'exterior_color' => 'Black',
		  'interior_color' => 'Agate',
		  'body_type' => 'Limited 4WD 4dr SUV',
		  'engine' => '4.7',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1J4GW58N91C598304/1J4GW58N91C598304---.jpg,https://cardemons.com/vehicles/1J4GW58N91C598304/1J4GW58N91C598304---2.jpg,https://cardemons.com/vehicles/1J4GW58N91C598304/1J4GW58N91C598304---3.jpg,https://cardemons.com/vehicles/1J4GW58N91C598304/1J4GW58N91C598304---4.jpg,https://cardemons.com/vehicles/1J4GW58N91C598304/1J4GW58N91C598304---5.jpg,https://cardemons.com/vehicles/1J4GW58N91C598304/1J4GW58N91C598304---6.jpg,https://cardemons.com/vehicles/1J4GW58N91C598304/1J4GW58N91C598304---7.jpg,https://cardemons.com/vehicles/1J4GW58N91C598304/1J4GW58N91C598304---8.jpg,https://cardemons.com/vehicles/1J4GW58N91C598304/1J4GW58N91C598304---9.jpg',
		  'certified' => '',
		  'new_title' => '2001 Jeep Grand Cherokee',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '7500',
		  'new_used' => 'preowned',
		  'stock_number' => '208410',
		  'vin' => '1J4GZ88Z6WC208410',
		  'year' => '1998',
		  'make' => 'Jeep',
		  'model' => 'Grand Cherokee',
		  'trim_level' => '5.9 Limited',
		  'mileage' => '173925',
		  'exterior_color' => 'White',
		  'interior_color' => 'Black',
		  'body_type' => '4dr 5.9 Limited 4WD SUV',
		  'engine' => '5.9',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1J4GZ88Z6WC208410/1J4GZ88Z6WC208410---.jpg,https://cardemons.com/vehicles/1J4GZ88Z6WC208410/1J4GZ88Z6WC208410---2.jpg,https://cardemons.com/vehicles/1J4GZ88Z6WC208410/1J4GZ88Z6WC208410---3.jpg,https://cardemons.com/vehicles/1J4GZ88Z6WC208410/1J4GZ88Z6WC208410---4.jpg,https://cardemons.com/vehicles/1J4GZ88Z6WC208410/1J4GZ88Z6WC208410---5.jpg,https://cardemons.com/vehicles/1J4GZ88Z6WC208410/1J4GZ88Z6WC208410---6.jpg,https://cardemons.com/vehicles/1J4GZ88Z6WC208410/1J4GZ88Z6WC208410---7.jpg,https://cardemons.com/vehicles/1J4GZ88Z6WC208410/1J4GZ88Z6WC208410---8.jpg,https://cardemons.com/vehicles/1J4GZ88Z6WC208410/1J4GZ88Z6WC208410---9.jpg',
		  'certified' => '',
		  'new_title' => '1998 Jeep Grand Cherokee',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '8500',
		  'new_used' => 'preowned',
		  'stock_number' => '577764',
		  'vin' => '1J4NF2GB5AD577764',
		  'year' => '2010',
		  'make' => 'Jeep',
		  'model' => 'Patriot',
		  'trim_level' => 'Sport',
		  'mileage' => '96130',
		  'exterior_color' => 'Orange',
		  'interior_color' => 'Dark Slate Gray',
		  'body_type' => '4x4 Sport 4dr SUV',
		  'engine' => '2.4',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1J4NF2GB5AD577764/1J4NF2GB5AD577764---.jpg,https://cardemons.com/vehicles/1J4NF2GB5AD577764/1J4NF2GB5AD577764---2.jpg,https://cardemons.com/vehicles/1J4NF2GB5AD577764/1J4NF2GB5AD577764---3.jpg,https://cardemons.com/vehicles/1J4NF2GB5AD577764/1J4NF2GB5AD577764---4.jpg,https://cardemons.com/vehicles/1J4NF2GB5AD577764/1J4NF2GB5AD577764---5.jpg,https://cardemons.com/vehicles/1J4NF2GB5AD577764/1J4NF2GB5AD577764---6.jpg,https://cardemons.com/vehicles/1J4NF2GB5AD577764/1J4NF2GB5AD577764---7.jpg,https://cardemons.com/vehicles/1J4NF2GB5AD577764/1J4NF2GB5AD577764---8.jpg,https://cardemons.com/vehicles/1J4NF2GB5AD577764/1J4NF2GB5AD577764---9.jpg',
		  'certified' => '',
		  'new_title' => '2010 Jeep Patriot',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '8400',
		  'new_used' => 'preowned',
		  'stock_number' => '1813',
		  'vin' => '1D7HU18297S101321',
		  'year' => '2007',
		  'make' => 'Dodge',
		  'model' => 'Ram 1500',
		  'trim_level' => 'SLT',
		  'mileage' => '102962',
		  'exterior_color' => 'Red',
		  'interior_color' => 'Medium Slate Gray',
		  'body_type' => 'SLT 4dr Quad Cab 4WD LB',
		  'engine' => '5.7',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1D7HU18297S101321/1D7HU18297S101321---.jpg,https://cardemons.com/vehicles/1D7HU18297S101321/1D7HU18297S101321---2.jpg,https://cardemons.com/vehicles/1D7HU18297S101321/1D7HU18297S101321---3.jpg,https://cardemons.com/vehicles/1D7HU18297S101321/1D7HU18297S101321---4.jpg,https://cardemons.com/vehicles/1D7HU18297S101321/1D7HU18297S101321---5.jpg,https://cardemons.com/vehicles/1D7HU18297S101321/1D7HU18297S101321---6.jpg,https://cardemons.com/vehicles/1D7HU18297S101321/1D7HU18297S101321---7.jpg,https://cardemons.com/vehicles/1D7HU18297S101321/1D7HU18297S101321---8.jpg,https://cardemons.com/vehicles/1D7HU18297S101321/1D7HU18297S101321---9.jpg',
		  'certified' => '',
		  'new_title' => '2007 Dodge Ram 1500',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '7550',
		  'new_used' => 'preowned',
		  'stock_number' => '1806',
		  'vin' => '1J8GN28K28W283973',
		  'year' => '2008',
		  'make' => 'Jeep',
		  'model' => 'Liberty',
		  'trim_level' => 'Sport',
		  'mileage' => '136037',
		  'exterior_color' => 'Green',
		  'interior_color' => 'Pastel Slate Gray',
		  'body_type' => '4x4 Sport 4dr SUV',
		  'engine' => '3.7',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1J8GN28K28W283973/1J8GN28K28W283973---.jpg,https://cardemons.com/vehicles/1J8GN28K28W283973/1J8GN28K28W283973---2.jpg,https://cardemons.com/vehicles/1J8GN28K28W283973/1J8GN28K28W283973---3.jpg,https://cardemons.com/vehicles/1J8GN28K28W283973/1J8GN28K28W283973---4.jpg,https://cardemons.com/vehicles/1J8GN28K28W283973/1J8GN28K28W283973---5.jpg,https://cardemons.com/vehicles/1J8GN28K28W283973/1J8GN28K28W283973---6.jpg,https://cardemons.com/vehicles/1J8GN28K28W283973/1J8GN28K28W283973---7.jpg,https://cardemons.com/vehicles/1J8GN28K28W283973/1J8GN28K28W283973---8.jpg,https://cardemons.com/vehicles/1J8GN28K28W283973/1J8GN28K28W283973---9.jpg',
		  'certified' => '',
		  'new_title' => '2008 Jeep Liberty',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		),
		array(
		  'price' => '7998',
		  'new_used' => 'preowned',
		  'stock_number' => '1821A',
		  'vin' => '1FMYU02Z86KA78554',
		  'year' => '2006',
		  'make' => 'Ford',
		  'model' => 'Escape',
		  'trim_level' => 'XLS',
		  'mileage' => '120940',
		  'exterior_color' => 'Dk. Blue',
		  'interior_color' => 'Medium/Dark Flint',
		  'body_type' => 'XLS 4dr SUV w/Automatic',
		  'engine' => '2.3',
		  'transmission' => 'Automatic',
		  'image_links' => 'https://cardemons.com/vehicles/1FMYU02Z86KA78554/1FMYU02Z86KA78554---.jpg,https://cardemons.com/vehicles/1FMYU02Z86KA78554/1FMYU02Z86KA78554---2.jpg,https://cardemons.com/vehicles/1FMYU02Z86KA78554/1FMYU02Z86KA78554---3.jpg,https://cardemons.com/vehicles/1FMYU02Z86KA78554/1FMYU02Z86KA78554---4.jpg,https://cardemons.com/vehicles/1FMYU02Z86KA78554/1FMYU02Z86KA78554---5.jpg,https://cardemons.com/vehicles/1FMYU02Z86KA78554/1FMYU02Z86KA78554---6.jpg,https://cardemons.com/vehicles/1FMYU02Z86KA78554/1FMYU02Z86KA78554---7.jpg,https://cardemons.com/vehicles/1FMYU02Z86KA78554/1FMYU02Z86KA78554---8.jpg,https://cardemons.com/vehicles/1FMYU02Z86KA78554/1FMYU02Z86KA78554---9.jpg',
		  'certified' => '',
		  'new_title' => '2006 Ford Escape',
		  'description' => __('This is a sample vehicle for the FREE ', 'car-demon'). '<a href="https://cardemons.com" target="_blank">'. __('Car Demon PlugIn', 'car-demon') . '</a>.',
		)
	);
	return $cars;
}


?>