<?php
require_once( 'vin-query-handler.php' );

function car_demon_get_vin_query( $post_id, $vin ) {
	global $car_demon_options;
	if ( ! empty( $vin ) ) {
		$report_type = '1';
		if ( isset( $car_demon_options['vinquery_type'] ) ) {
			$report_type = $car_demon_options['vinquery_type'];
		}
		$vinquery_id = $car_demon_options['vinquery_id'];
		$decode_saved = get_post_meta( $post_id, 'decode_saved' );
		if ( empty( $decode_saved ) ) {
			$url = "http://vinquery.com/ws_POQCXTYNO1D/xml_v100_QA7RTS8Y.aspx?accessCode=" . $vinquery_id . "&vin=" . $vin . "&reportType=" . $report_type;
			// test URL for sample import

			if ( $vinquery_id == '1234' ) {
				$url = plugins_url() . '/car-demon/vin-query/extended_sample.xml';
			}

			$xml = simplexml_load_file( $url );
			if ( $xml ) {
				$car_details = $xml->VIN->Vehicle->Item;
				if ( ! empty( $car_details ) ) {
					$decode_string = array();
					foreach ( $car_details as $car_detail ) {
						$key = strtolower( $car_detail->attributes()->Key );
						$key = str_replace( chr(32), '_', $key );
						$key = str_replace( '-', '_', $key );
						$key = str_replace( '/', '_', $key );
						$key = str_replace( '\\', '_', $key );
						$key = str_replace( '.', '', $key );
						$key = 'decoded_' . $key;
						$value = $car_detail->attributes()->Value;
						$value = $value . ' ' . $car_detail->attributes()->Unit;
						$value = str_replace( '\'', '', $value );
						$decode_string[$key] = $value;
					}
				}
				if ( ! empty( $decode_string ) ) {
					update_post_meta( $post_id, 'decode_string', $decode_string );
					update_post_meta( $post_id, 'decode_saved', '1' );
				}
				if ( isset( $decode_string['decoded_model_year'] ) ) {
					wp_set_post_terms( $post_id, $decode_string['decoded_model_year'], 'vehicle_year', false );
				}
				if ( isset( $decode_string['decoded_make'] ) ) {
					wp_set_post_terms( $post_id, $decode_string['decoded_make'], 'vehicle_make', false );
				}
				if ( isset( $decode_string['decoded_model'] ) ) {
					wp_set_post_terms( $post_id, $decode_string['decoded_model'], 'vehicle_model', false );
				}
				if ( isset( $decode_string['decoded_transmission_long'] ) ) {
					update_post_meta( $post_id, '_transmission_value', $decode_string['decoded_transmission_long'] );
				}
				if ( isset( $decode_string['decoded_engine_type'] ) ) {
					update_post_meta( $post_id, '_engine_value', $decode_string['decoded_engine_type'] );
				}
				if ( isset( $decode_string['decoded_trim_level'] ) ) {
					update_post_meta( $post_id, '_trim_value', $decode_string['decoded_trim_level'] );
				}
			}
		}
	}
}

?>