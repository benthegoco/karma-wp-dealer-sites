<?php
add_filter('car_demon_query_filter', 'cdsf_car_query',10,1);
function cdsf_car_query($car_demon_query) {

	// $car_demon_query contains the current query
	if ( defined( 'CDPRO_EXTRAS' ) ) {
		
		if ( isset( $_GET['search_trim_level'] ) ) {
			if ( ! empty( $_GET['search_trim_level'] ) ) {
				$trim_level = sanitize_text_field( $_GET['search_trim_level'] );
				$trim_level = urldecode( $trim_level );
				$meta_query = $car_demon_query['meta_query'];
				$meta_query = array_merge( $meta_query, array( array( 'key' => '_trim_value','value' => $trim_level, 'compare' => '=' ) ) );
				$car_demon_query['meta_query'] = $meta_query;
			}
		}

		if ( isset( $_GET['search_transmission'] ) ) {
			if ( ! empty( $_GET['search_transmission'] ) ) {
				$transmission = sanitize_text_field( $_GET['search_transmission'] );
				$meta_query = $car_demon_query['meta_query'];
				$meta_query = array_merge( $meta_query, array( array( 'key' => '_transmission_value','value' => $transmission, 'compare' => '=' ) ) );
				$car_demon_query['meta_query'] = $meta_query;
			}
		}
	}

	//= check max mileage
	if ( isset( $_GET['search_dropdown_miles_Max'] ) ) {
		if ( $_GET['search_dropdown_miles_Max'] == 0 ) {
			$meta_query = $car_demon_query['meta_query'];
			foreach ( $meta_query as $key=>$value ) {
				if ($value['key'] == '_mileage_value' ) {
					// since max miles is 0 go ahead and unset it from the query
					unset( $meta_query[$key] );
				}
			}
			$car_demon_query['meta_query'] = $meta_query;
		}
	}

	if ( 1 == 2 ) {
		echo '<pre>';
			print_r($car_demon_query);
		echo '</pre>';
	}

	return $car_demon_query;
}
?>