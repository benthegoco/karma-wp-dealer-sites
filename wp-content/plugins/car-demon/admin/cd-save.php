<?php
if (is_admin()) {
	$post_type = car_demon_get_current_post_type();
	if ( $post_type == 'cars_for_sale' ) {
		add_action( 'save_post','cd_save_car' );
	}
}

function cd_save_car( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	if ( isset($_POST['post_type'] ) ) {
		if ( 'cars_for_sale' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		} else {
			return;
		}
	} else {
		return;
	}

	if ( empty( $stock_value ) ) {
		$x = get_post_meta( $post_id, 'decode_string', true );
		if ( isset( $x['stock_num'] ) ) {
			$stock_value = $x['stock_num'];
			update_post_meta( $post_id, 'decode_string', $x );
			update_post_meta( $post_id, '_stock_value', $stock_value );
		}
	}

	$price = get_post_meta( $post_id, '_price_value', true );
	if ( empty( $price ) ) {
		update_post_meta( $post_id, '_price_value', '0.0' );
	}

	$mileage = get_post_meta( $post_id, '_mileage_value', true );
	if ( empty( $mileage ) ) {
		update_post_meta( $post_id, '_mileage_value', '0.0' );
	}

	$sold_status = get_post_meta( $post_id, 'sold', true );
	if ( empty( $sold_status ) ) {
		update_post_meta( $post_id, 'sold', 'no' );
	}
	if ( isset($_POST['_vehicle_ribbon'] ) ) {
		update_post_meta( $post_id, '_vehicle_ribbon', $_POST['_vehicle_ribbon'] );
	}
	if ( isset($_POST['_custom_ribbon'] ) ) {
		update_post_meta( $post_id, '_custom_ribbon', $_POST['_custom_ribbon'] );
	}

	/*
	 * Save specs and options from form post
	 * 
	 * These items should already have been saved via ajax
	 *
	 * This is a fallback method for users having ajax issues
	 */
	$vin_query_decode = get_post_meta( $post_id, "decode_string", true );
	$vin_query_decode = cd_save_static_specs( $post_id, $vin_query_decode );

	foreach ( $_POST as $name => $val ) {
		if ( strpos( $name, 'spec_' ) !== false ) {
			$fld = str_replace( 'spec_', '', $name );
			$vin_query_decode[$fld] = cd_validate_option( $val, $name );
		}
		if ( strpos( $name, 'option_' ) !== false ) {
			$fld = str_replace( 'option_', '', $name );
			$vin_query_decode[$fld] = cd_validate_option( $val, $name );
		}
	}
	
	/*
	* Save default condition from constant
	*/
	if ( defined( 'CD_DEFAULT_CONDITION' ) ) {
		$condition = get_cd_term( $post_id, 'vehicle_condition' );
		if ( empty( $condition ) ) {
			$condition = CD_DEFAULT_CONDITION;
			wp_set_object_terms( $post_id, $condition, 'vehicle_condition', false );
			$vin_query_decode['condition'] = $condition;
		}
	}

	update_post_meta( $post_id, 'decode_string', $vin_query_decode );

	return;
}

function cd_save_static_specs( $post_id, $vin_query_decode ) {
	if ( isset( $_POST['decoded_body_style'] ) ) {
		$val = cd_validate_option( $_POST['decoded_body_style'], 'body_style' );
		if ( ! empty( $val ) ) {
			wp_set_post_terms( $post_id, $val, 'vehicle_body_style', false );
			$vin_query_decode['decoded_body_style'] = $val;
		}
	} 
	if ( isset( $_POST['decoded_model_year'] ) ) {
		$val = cd_validate_option( $_POST['decoded_model_year'], 'year' );
		if ( ! empty( $val ) ) {
			wp_set_post_terms( $post_id, $val, 'vehicle_year', false );
			$vin_query_decode['decoded_model_year'] = $val;
		}
	} 
	if ( isset( $_POST['vehicle_make'] ) ) {
		$val = cd_validate_option( $_POST['vehicle_make'], 'make' );
		if ( ! empty( $val ) ) {
			wp_set_post_terms( $post_id, $val, 'vehicle_make', false );
			$vin_query_decode['vehicle_make'] = $val;
		}
	} 
	if ( isset( $_POST['vehicle_model'] ) ) {
		$val = cd_validate_option( $_POST['vehicle_model'], 'model' );
		if ( ! empty( $val ) ) {
			wp_set_post_terms( $post_id, $val, 'vehicle_model', false );
			$vin_query_decode['vehicle_model'] = $val;
		}
	} 
	if ( isset( $_POST['transmission'] ) ) {
		$val = cd_validate_option( $_POST['transmission'], 'transmission' );
		if ( ! empty( $val ) ) {
			$vin_query_decode['decoded_transmission_long'] = $val;
			update_post_meta( $post_id, '_transmission_value', $val );
			$vin_query_decode['transmission'] = $val;
		}
	} 
	if ( isset( $_POST['engine'] ) ) {
		$val = cd_validate_option( $_POST['engine'], 'engine' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_engine_value', $val );
			$vin_query_decode['engine'] = $val;
		}
	} 
	if ( isset( $_POST['trim'] ) ) {
		$val = cd_validate_option( $_POST['trim'], 'trim' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_trim_value', $val );
			$vin_query_decode['trim'] = $val;
		}
	} 
	if ( isset( $_POST['stock_num'] ) ) {
		$val = cd_validate_option( $_POST['stock_num'], 'stock_number' );
		if ( empty( $val ) ) {
			$val = $post_id;
		}
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_stock_value', $val );
			$vin_query_decode['stock_number'] = $val;
		}
	} 
	if ( isset( $_POST['msrp'] ) ) {
		$val = cd_validate_option( $_POST['msrp'], 'msrp' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_msrp_value', $val );
			$vin_query_decode['msrp'] = $val;
		}
	} 
	if ( isset( $_POST['rebates'] ) ) {
		$val = cd_validate_option( $_POST['rebates'], 'rebates' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_rebates_value', $val );
			$vin_query_decode['rebates'] = $val;
		}
	} 
	if ( isset( $_POST['discount'] ) ) {
		$val = cd_validate_option( $_POST['discount'], 'discount' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_discount_value', $val );
			$vin_query_decode['discount'] = $val;
		}
	} 
	if ( isset( $_POST['price'] ) ) {
		$val = cd_validate_option( $_POST['price'], 'price' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_price_value', $val );
			$vin_query_decode['price'] = $val;
		}
	} 
	if ( isset( $_POST['exterior_color'] ) ) {
		$val = cd_validate_option( $_POST['exterior_color'], 'exterior_color' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_exterior_color_value', $val );
			$vin_query_decode['exterior_color'] = $val;
		}
	} 
	if ( isset( $_POST['interior_color'] ) ) {
		$val = cd_validate_option( $_POST['interior_color'], 'interior_color' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_interior_color_value', $val );
			$vin_query_decode['interior_color'] = $val;
		}
	} 
	if ( isset( $_POST['mileage'] ) ) {
		$val = cd_validate_option( $_POST['mileage'], 'mileage' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_mileage_value', $val );
			$vin_query_decode['mileage'] = $val;
		}
	} 
	if ( isset( $_POST['vehicle_options'] ) ) {
		$val = cd_validate_option( $_POST['vehicle_options'], 'options' );
		if ( ! empty( $val ) ) {
			update_post_meta( $post_id, '_vehicle_options', $val );
			$vin_query_decode['vehicle_options'] = $val;
		}
	}
	
	return $vin_query_decode;
}

function cd_validate_option( $val, $name ) {
	$val = sanitize_text_field( $val );
	$val = apply_filters( 'cd_validate_option_filter', $val, $name );
	return $val;
}
?>