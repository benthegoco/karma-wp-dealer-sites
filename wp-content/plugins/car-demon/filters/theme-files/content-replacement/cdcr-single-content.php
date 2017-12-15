<?php
function cdcr_single_content($content) {
	global $car_demon_options;
	$cd_cdrf_pluginpath = CAR_DEMON_PATH;
	$post_id = get_the_ID();
	$vehicle_vin = rwh(strip_tags(get_post_meta($post_id, "_vin_value", true)),0);
	$car_title = get_car_title_slug($post_id);
	$car_head_title = get_car_title($post_id);
	$car_url = get_permalink($post_id);
	$vehicle_location = get_cd_term( $post_id, 'vehicle_location' );
	//$vehicle_details = get_post_meta($post_id, 'decode_string', true);
	$vehicle_details = car_demon_get_car($post_id);
	$mileage = strip_tags(get_post_meta($post_id, "_mileage_value", true));
	//=========================Contact Info===========================
	$car_contact = get_car_contact($post_id);
	$contact_trade_url = $car_contact['trade_url'];
	$contact_finance_url = $car_contact['finance_url'];
	//===============================================================
	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();
	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();
	ob_start();
		echo car_demon_photo_lightbox();
		do_action( 'car_demon_before_main_content' ); //= deprecated
		do_action( 'cd_before_content_action' );
		do_action( 'car_demon_vehicle_header_sidebar' ); //= deprecated
		do_action( 'cd_header_sidebar_action' );
	$x = ob_get_contents();
	ob_end_clean();
	$detail_output = '<div class="car_title_div">';
		$detail_output .= '<ul>';
			if (!empty($vehicle_details['condition'])) {
				if ($show_hide['condition'] != true) {
					$detail_output .= '<li class="condition"><strong>'.$field_labels['condition'].':</strong> '.$vehicle_details['condition'].'</li>';
				}
			}
			if ($show_hide['mileage'] != true) {
				$detail_output .= '<li class="mileage"><strong>'.$field_labels['mileage'].':</strong> ' . apply_filters( 'cd_mileage_filter', $mileage ) . '</li>';
			}
			if ($show_hide['stock_number'] != true) {
				$detail_output .= '<li class="stock_number"><strong>'.$field_labels['stock_number'].':</strong> '.$vehicle_details['stock_number'].'</li>';
			}
			if ($show_hide['vin'] != true) {
				$detail_output .= '<li class="vin"><strong>'.$field_labels['vin'].':</strong> '.$vehicle_vin.'</li>';
			}
			if (isset($vehicle_details['exterior_color'])) {
				if (!empty($vehicle_details['exterior_color'])) {
					$detail_output .= '<li class="exterior_color"><strong>'.$field_labels['exterior_color'].':</strong> '.$vehicle_details['exterior_color'].'/'.$vehicle_details['interior_color'].'</li>';
				}
			}
			if (isset($vehicle_details['transmission'])) {
				if (!empty($vehicle_details['transmission'])) {
					$detail_output .= '<li class="transmission"><strong>'.$field_labels['transmission'].':</strong> '.$vehicle_details['transmission'].'</li>';
				}
			}
			if (isset($vehicle_details['decoded_engine_type'])) {
				if (!empty($vehicle_details['decoded_engine_type'])) {
					$detail_output .= '<li class="decoded_engine_type"><strong>'.$field_labels['engine'].':</strong> '.$vehicle_details['decoded_engine_type'].'</li>';
				}
			}
			$detail_output = apply_filters( 'cd_vdp_detail_output_filter', $detail_output, $post_id );
			$detail_output = apply_filters( 'cd_single_car_detail_output_filter', $detail_output, $post_id ); //= deprecated
			$detail_output .= get_vehicle_price( $post_id );
		$detail_output .= '</ul>';
	$detail_output .= '</div>';
	$x .= car_photos($post_id, $detail_output, $vehicle_details['condition']);
	$x .= '<div class="car_buttons_div">';
		if (!empty($contact_finance_url)) { 
			if ($car_contact['finance_popup'] == 'Yes') {
				$x .= '<div class="featured-button">
					<p><a onclick="window.open(\''.$contact_finance_url .'?stock_num='.$vehicle_details['stock_number'].'&sales_code='.$car_contact['sales_code'].'\',\'finwin\',width='.$car_contact['finance_width'].', height='.$car_contact['finance_height'].', menubar=0, resizable=0\')">'.__('GET FINANCED', 'car-demon').'</a></p>
				</div>';
			} else {
				$x .= '<div class="featured-button">
					<p><a href="'. $contact_finance_url .'?stock_num='.$vehicle_details['stock_number'].'&sales_code='. $car_contact['sales_code'] .'">'. __('GET FINANCED', 'car-demon').'</a></p>
				</div>';
			}
		} 
		if (!empty($contact_trade_url)) {
			$x .= '<div class="featured-button">
				<p><a href="'.$contact_trade_url .'?stock_num='.$vehicle_details['stock_number'].'&sales_code='. $car_contact['sales_code'] .'">'. __('TRADE-IN QUOTE', 'car-demon').'</a></p>
			</div>';
		}
	$x .= '</div>';	
	$x .= car_demon_vehicle_detail_tabs($post_id, false);
	$show_similar_cars = 'Yes';
	if (isset($car_demon_options['show_similar_cars'])) {
		$show_similar_cars = $car_demon_options['show_similar_cars'];
	}		
	if ($show_similar_cars == 'Yes') {
		if (isset($vehicle_details['decoded_body_style'])) {
			$x .= '<div class="similar_cars_container">
					'. car_demon_display_similar_cars($vehicle_details['decoded_body_style'], $post_id) .'
				  </div>';
		}
	}
	ob_start();
		do_action( 'car_demon_after_main_content' ); //= deprecated
		do_action( 'cd_after_content_action' );
		do_action( 'car_demon_vehicle_sidebar' ); //= deprecated
		do_action( 'cd_vehicle_sidebar_action' );
	$x .= ob_get_contents();
	ob_end_clean();
	$x .= '<div style="clear:both;width:100%"></div>';
	return $x;
}

add_filter( 'cd_mileage_filter', 'cd_mileage_format_func', 10, 1 );
function cd_mileage_format_func( $miles ) {
	$miles = apply_filters( 'cd_mileage_format', $miles ); //= deprecated
	return $miles;	
}
?>