<?php

//= Template part for vehicle archive and search loops

function cdcr_loop($content, $post_id) {

	$cd_cdrf_pluginpath = CAR_DEMON_PATH;

	$vehicle_year = get_cd_term( $post_id, 'vehicle_year' );

	$vehicle_make = get_cd_term( $post_id, 'vehicle_make' );

	$vehicle_model = get_cd_term( $post_id, 'vehicle_model' );

	$vehicle_condition = get_cd_term( $post_id, 'vehicle_condition' );

	$vehicle_body_style = get_cd_term( $post_id, 'vehicle_body_style' );

	$vehicle_location = get_cd_term( $post_id, 'vehicle_location' );

	$vehicle_stock_number = get_post_meta($post_id, "_stock_value", true);

	$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);

	$vehicle_exterior_color = get_post_meta($post_id, "_exterior_color_value", true);

	$vehicle_interior_color = get_post_meta($post_id, "_interior_color_value", true);

	$vehicle_mileage = get_post_meta($post_id, "_mileage_value", true);

	$vehicle_transmission = get_post_meta($post_id, "_transmission_value", true);

	$title = get_car_title($post_id);

	$stock_value = get_post_meta($post_id, "_stock_value", true);

	$mileage_value = get_post_meta($post_id, "_mileage_value", true);

	global $car_demon_options;

	$compare = '';

	if (isset($car_demon_options['use_compare'])) {

		if ($car_demon_options['use_compare'] == 'Yes') {

			$in_compare = '';

			if (isset($_SESSION['car_demon_compare'])) {

				$compare_these = explode(',',$_SESSION['car_demon_compare']);

			} else {

				$compare_these = array();

			}

			if (in_array($post_id,$compare_these)) {

				$in_compare = ' checked="checked"';

			}

			$compare = '<div class="compare">';

				$compare .= '<div class="compare_input">';

					$compare .= '<input'.$in_compare.' id="compare_'.$post_id.'" type="checkbox" onclick="update_car('.$post_id.',this);" />';

				$compare .= '</div>';

				$compare .= '<div class="compare_label">';

					$compare .= 'Compare';

				$compare .= '</div>';

			$compare .= '</div>';

		}

	}

	$link = get_permalink($post_id);

	if (isset($_COOKIE["sales_code"])) {

		$link = $link .'?sales_code='.$_COOKIE["sales_code"];

	}

	$main_photo = cd_main_photo( $post_id );

	if (empty($main_photo)) {

		$main_photo = CAR_DEMON_PATH.'images/no_photo.gif';

	}

	//= Build the HTML for each vehicle

	//= Get Ribbon

		$ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);

	if (empty($ribbon)) {

		$ribbon = 'no-ribbon';

	}

	if ($ribbon != 'custom_ribbon') {

		$ribbon = str_replace('_', '-', $ribbon);

		$current_ribbon = '<img class="inventory_ribbon" src="'. CAR_DEMON_PATH .'theme-files/images/ribbon-'.$ribbon.'.png" width="76" height="76" id="ribbon">';

	} else {

		$custom_ribbon_file = get_post_meta($post_id, '_custom_ribbon', true);

		$current_ribbon = '<img class="inventory_ribbon" src="'.$custom_ribbon_file.'" width="76" height="76" id="ribbon">';

	}

	$x = '';

	if (isset($car_demon_options['dynamic_ribbons'])) {

		if ($car_demon_options['dynamic_ribbons'] == 'Yes') {

			$current_ribbon = car_demon_dynamic_ribbon_filter($current_ribbon, $post_id, '76');

		}

	}



	$x .= '<div class="car_title">';

	$x .= '<a href="'.$link.'">';

		$x .= $title;

	$x .= '</a>';

	$x .= '</div>';



	$x .= '<div class="car_details-together">';



	//= Get Main Photo

	$x .= '<div class="main_photo">';

		$x .= '<a href="'.$link.'">'.$current_ribbon.'</a>';

		$x .= '<a href="'.$link.'">';

			$x .= '<img class="photo_thumb" onerror="ImgError(this, \'no_photo.gif\');" src="'.$main_photo.'" alt="" title="'.$title.'">';

		$x .= '</a>';

	$x .= '</div>';

	

	$x .= $compare;

	//= Find out which of the default fields are hidden

	$show_hide = get_show_hide_fields();

	//= Get the labels for the default fields

	$field_labels = get_default_field_labels();



	$x .= '<div class="description">';

		$x .= '<div class="description_left">';

			if ($show_hide['stock_number'] != true) {



				$x .= '<div class="stock_number-together">';



				$x .= '<div class="description_label stock_number">'.$field_labels['stock_number'].':</div>';

				$x .= '<div class="description_text stock_number">'.$vehicle_stock_number.'</div>';



				$x .= '</div>';

			}



			if ($show_hide['year'] != true) {



				$x .= '<div class="year-together">';



				$x .= '<div class="description_label year">'.$field_labels['year'].':</div>';

				$x .= '<div class="description_text year">'. $vehicle_year.'</div>';



				$x .= '</div>';

			}

			if ($show_hide['make'] != true) {

				$x .= '<div class="make-together">';



				$x .= '<div class="description_label make">'.$field_labels['make'].':</div>';

				$x .= '<div class="description_text make">'. $vehicle_make.'</div>';



				$x .= '</div>';

			}

			if ($show_hide['model'] != true) {

				$x .= '<div class="model-together">';



				$x .= '<div class="description_label model">'.$field_labels['model'].':</div>';

				$x .= '<div class="description_text model">'. $vehicle_model.'</div>';



				$x .= '</div>';

			}

			if ($show_hide['body_style'] != true) {

				$x .= '<div class="body_style-together">';



				$x .= '<div class="description_label body_style">'.$field_labels['body_style'].':</div>';

				$x .= '<div class="description_text body_style">'. $vehicle_body_style.'</div>';



				$x .= '</div>';

			}

		$x .= '</div>';

		$x .= '<div class="description_right">';

			if (!empty($vehicle_transmission)) {

				$x .= '<div class="transmission-together">';



				$x .= '<div class="description_label transmission">'.$field_labels['transmission'].':</div>';

				$x .= '<div class="description_text transmission">'. $vehicle_transmission.'</div>';



				$x .= '</div>';

			}



			if ($show_hide['condition'] != true) {

				$x .= '<div class="condition-together">';



				$x .= '<div class="description_label condition">'.$field_labels['condition'].':</div>';

				$x .= '<div class="description_text condition">'. $vehicle_condition.'</div>';



				$x .= '</div>';

			}

			if ($show_hide['mileage'] != true) {

				$x .= '<div class="mileage-together">';



				$x .= '<div class="description_label mileage">'.$field_labels['mileage'].':</div>';

				$x .= '<div class="description_text mileage">' . apply_filters( 'cd_mileage_filter', $vehicle_mileage ) . '</div>';



				$x .= '</div>';

			}

			if (!empty($vehicle_exterior_color)) {

				$x .= '<div class="exterior_color-together">';



				$x .= '<div class="description_label exterior_color">'.$field_labels['exterior_color'].':</div>';

				$x .= '<div class="description_text exterior_color">'. $vehicle_exterior_color.'</div>';



				$x .= '</div>';

			}
			if (!empty($vehicle_exterior_color)) {

				$x .= '<div class="exterior_color-together">';



				$x .= '<div class="description_label exterior_color">'.$field_labels['interior_color'].':</div>';

				$x .= '<div class="description_text exterior_color">'. $vehicle_interior_color.'</div>';



				$x .= '</div>';

			}

			if ($show_hide['vin'] != true) {

				$x .= '<div class="vin-together">';



				$x .= '<div class="description_label vin">'.$field_labels['vin'].':</div>';

				$x .= '<div class="description_text description_text_vin vin">'. $vehicle_vin.'</div>';



				$x .= '</div>';

			}



			$car = car_demon_get_car($post_id);

			/* RS check for location from site url */
    		if(strpos(get_site_url(),"toronto") || strpos(get_site_url(),"montreal") || strpos(get_site_url(),"vancouver")){
			setlocale(LC_MONETARY, 'en_CA.utf8');

    $vehicle_price = money_format('$%.0i', $car['price']); //RS: remove trailing zeros
    $vehicle_price = str_replace('CAD','CAD ',$vehicle_price);
			} else { 
			setlocale(LC_MONETARY, 'en_US.utf8');

			$vehicle_price = money_format('$%.0i', $car['price']); //RS: remove trailing zeros
			$vehicle_price = str_replace('USD','',$vehicle_price);	//RS: removed USD from currency
			}


			$x .= '<div class="price-together">';



			$x .= '<div class="description_label car_price">Price:</div>';

			$x .= '<div class="description_text car_price">'. $vehicle_price.'</div>';



			$x .= '</div>';

		$x .= '</div>';

	$x .= '</div>';



	$contact_link = 'contact';

	$call_link = 'tel:' . of_get_option('phone_number');;



	$x .= '<div class="car_item_button_group">';

		$x .= '<div class="car_item_button" onclick="location.href=\'' . $link . '\'">More Info</div>';

		$x .= '<div class="car_item_button" onclick="location.href=\'' . $contact_link . '?stocknum=' . $vehicle_stock_number . '\'">Contact Dealer</div>';

		$x .= '<div class="car_item_button" onclick="location.href=\'' . $call_link . '\'">Call</div>';

	$x .= '</div>';



	$x .= '</div>';





//	$x .= get_vehicle_price( $post_id );

	$x = apply_filters( 'car_demon_display_car_list_filter', $x, $post_id ); //= deprecated

	$x = apply_filters( 'cd_srp_filter', $x, $post_id );

	return $x;

}



add_filter( 'car_demon_price_filter', 'cdcr_price_filter', 10, 1 ); //= deprecate

function cdcr_price_filter( $price ) {

	$price = str_replace( 'class="car_price_details"', 'class="car_price_details_style"', $price );

	$price = str_replace( 'class="car_your_price"', 'class="car_your_price_style"', $price );

	$price = str_replace( 'class="car_final_price"', 'class="car_final_price_style"', $price );

	$price = str_replace( 'class="car_rebate"', 'class="car_rebate_style"', $price );

	$price = str_replace( 'class="car_price_text"', 'class="car_price_text_style"', $price );

	$price = str_replace( 'class="car_selling_price"', 'class="car_selling_price_style"', $price );

	$price = str_replace( 'class="car_dealer_discounts"', 'class="car_dealer_discounts_style"', $price );

	return $price;

}

?>

