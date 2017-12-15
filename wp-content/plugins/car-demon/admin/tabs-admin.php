<?php
function get_tab_specs_admin( $vin_query_decode, $vehicle_vin, $post_id ) {
	if ( isset( $vin_query_decode['year'] ) ) {$decoded_model_year = $vin_query_decode['year']; } else {$decoded_model_year = ''; }
	if ( isset( $vin_query_decode["make"] ) ) {$decoded_make = $vin_query_decode["make"]; } else {$decoded_make = ''; }
	if ( isset( $vin_query_decode["model"] ) ) {$decoded_model = $vin_query_decode["model"]; } else {$decoded_model = ''; }
	if ( isset( $vin_query_decode["trim_level"] ) ) {$decoded_trim_level = $vin_query_decode["trim_level"]; } else {$decoded_trim_level = ''; }
	if ( isset( $vin_query_decode["manufactured_in"] ) ) {$decoded_manufactured_in = $vin_query_decode["manufactured_in"]; } else {$decoded_manufactured_in = ''; }
	if ( isset( $vin_query_decode["production_seq_number"] ) ) {$decoded_production_seq_number = $vin_query_decode["production_seq_number"]; } else {$decoded_production_seq_number = ''; }
	if ( isset( $vin_query_decode["body_style"] ) ) {$decoded_body_style = $vin_query_decode["body_style"]; } else {$decoded_body_style = ''; }
	if ( isset( $vin_query_decode["engine_type"] ) ) {$decoded_engine_type = $vin_query_decode["engine_type"]; } else {$decoded_engine_type = ''; }
	if ( isset( $vin_query_decode["transmission_long"] ) ) {$decoded_transmission_long = $vin_query_decode["transmission_long"]; } else {$decoded_transmission_long = ''; }
	if ( isset( $vin_query_decode["driveline"] ) ) {$decoded_driveline = $vin_query_decode["driveline"]; } else {$decoded_driveline = ''; }
	if ( isset( $vin_query_decode["tank"] ) ) {$decoded_tank = $vin_query_decode["tank"]; } else {$decoded_tank = ''; }
	if ( isset( $vin_query_decode["fuel_economy_city"] ) ) {$decoded_fuel_economy_city = $vin_query_decode["fuel_economy_city"]; } else {$decoded_fuel_economy_city = ''; }
	if ( isset( $vin_query_decode["fuel_economy_highway"] ) ) {$decoded_fuel_economy_highway = $vin_query_decode["fuel_economy_highway"]; } else {$decoded_fuel_economy_highway = ''; }
	if ( isset( $vin_query_decode["anti_brake_system"] ) ) {$decoded_anti_brake_system = $vin_query_decode["anti_brake_system"]; } else {$decoded_anti_brake_system = ''; }
	if ( isset( $vin_query_decode["steering_type"] ) ) {$decoded_steering_type = $vin_query_decode["steering_type"]; } else {$decoded_steering_type = ''; }
	if ( isset( $vin_query_decode["overall_length"] ) ) {$decoded_overall_length = $vin_query_decode["overall_length"]; } else {$decoded_overall_length = ''; }
	if ( isset( $vin_query_decode["overall_width"] ) ) {$decoded_overall_width = $vin_query_decode["overall_width"]; } else {$decoded_overall_width = ''; }
	if ( isset( $vin_query_decode["overall_height"] ) ) {$decoded_overall_height = $vin_query_decode["overall_height"]; } else {$decoded_overall_height = ''; }
	$show_custom_specs = false;
	// Meta Fields
	$stock_num = wp_kses_data( get_post_meta( $post_id, '_stock_value', true ) );
	$retail_price = wp_kses_data( get_post_meta( $post_id, '_msrp_value', true ) );
	$rebates = wp_kses_data( get_post_meta( $post_id, '_rebates_value', true ) );
	$discount = wp_kses_data( get_post_meta( $post_id, '_discount_value', true ) );
	$price = wp_kses_data( get_post_meta( $post_id, '_price_value',true));
	$exterior_color = wp_kses_data( get_post_meta( $post_id, '_exterior_color_value', true ) );
	$interior_color = wp_kses_data( get_post_meta( $post_id, '_interior_color_value', true ) );
	$mileage = wp_kses_data( get_post_meta( $post_id, '_mileage_value', true ) );
	$condition = get_cd_term( $post_id, 'vehicle_condition' );
	$remove_decode_btn = '<input onclick="remove_decode(' . $post_id . ')" type="button" name="remove_decode_vin_' . $post_id . '" id="remove_decode_vin_' . $post_id . '" value="Reset Options" class="btn btn_reset_decode" />';
	$car_demon_options = car_demon_options();
	$decode_btn = '';
	$decode_results = '';
	if ( empty( $vin_query_decode ) ) {
		$decode_results = '<div id="decode_results"></div>';
		if ( isset( $car_demon_options['vinquery_id'] ) ) {
			if ( ! empty( $car_demon_options['vinquery_id'] ) ) {
				$decode_btn = '<input onclick="decode_vin(' . $post_id . ')" type="button" name="decode_vin_' . $post_id . '" id="decode_vin_' . $post_id . '" value="Decode Vin" class="btn" />';
			}
		}
	} else {
		if ( isset( $car_demon_options['vinquery_id'] ) ) {
			if ( ! empty( $car_demon_options['vinquery_id'] ) ) {
				//= Removed message stating Vin has been decoded
				//	$decode_results = '<div id="decode_results">VIN HAS BEEN DECODED.</div>';
				$decode_btn = '<input onclick="decode_vin(' . $post_id . ')" type="button" name="decode_vin_' . $post_id . '" id="decode_vin_' . $post_id . '" value="Decode Vin" class="btn" />';
			}
		}
	}
	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();
	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();

	//= Get capability for editing all spec fields
	$spec_caps = get_cd_spec_caps();

	//= Start displaying the specs
	$x = '<table class="decode_table">';

	if ( current_user_can( $spec_caps['vin'] ) ) {
		if ( $show_hide['vin'] != true ) {
			$x .= '<tr class="decode_table_header">';
				$x .= '<td><strong>' . $field_labels['vin'] . '</strong></td>';
				$x .= '<td><input type="text" id="vin" name="vin" onchange="update_vehicle_data(this, ' . $post_id . ')" value="' . $vehicle_vin . '" />' . $remove_decode_btn . $decode_btn . $decode_results . '</td>';
			$x .= '</tr>';
		}
	}

	if ( current_user_can( $spec_caps['stock_number'] ) ) {
		if ( $show_hide['stock_number'] != true ) {
			$x .= '<tr class="decode_table_even">';
				$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['stock_number'] . '</td>';
				$x .= '<td><input type="text" id="stock_num" name="stock_num" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $stock_num . '" /></td>';
			$x .= '</tr>';
		}
	}

	if ( current_user_can( $spec_caps['mileage'] ) ) {
		if ( $show_hide['mileage'] != true ) {
			$x .= '<tr class="decode_table_odd">';
				$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['mileage'] . '</td>';
				$x .= '<td><input type="text" id="mileage" name="mileage" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $mileage . '" /></td>';
			$x .= '</tr>';
		}
	}
	
	//= Hide price header if all price fields are hidden
	if ( $show_hide['retail'] != true || $show_hide['rebates'] != true || $show_hide['discount'] != true || $show_hide['price'] != true ) {
		$x .= '<tr class="decode_table_header">';
			$x .= '<td colspan="2"><strong>' . __( 'Pricing', 'car-demon' ) . '</strong></td>';
		$x .= '</tr>';
	}

	if ( current_user_can( $spec_caps['retail'] ) ) {
		if ( $show_hide['retail'] != true ) {
		  $x .= '<tr class="decode_table_odd">';
			$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['retail'] . '</td>';
			$x .= '<td><input type="text" id="msrp" name="msrp" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $retail_price . '" /></td>';
		  $x .= '</tr>';
		}
	}

	if ( current_user_can( $spec_caps['rebates'] ) ) {
		if ( $show_hide['rebates'] != true ) {
		  $x .= '<tr class="decode_table_even">';
			$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['rebates'] . '</td>';
			$x .= '<td><input type="text" id="rebates" name="rebates" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $rebates . '" /></td>';
		  $x .= '</tr>';
		}
	}

	if ( current_user_can( $spec_caps['discount'] ) ) {
		if ( $show_hide['discount'] != true ) {
		  $x .= '<tr class="decode_table_odd">';
			$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['discount'] . '</td>';
			$x .= '<td><input type="text" id="discount" name="discount" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $discount . '" /></td>';
		  $x .= '</tr>';
		}
	}

	if ( current_user_can( $spec_caps['price'] ) ) {
		if ( $show_hide['price'] != true ) {
		  $x .= '<tr class="decode_table_even">';
			$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['price'] . '</td>';
			$x .= '<td><input type="text" id="price" name="price" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $price . '" /></td>';
		  $x .= '</tr>';
		}
	}

	$x .= '<tr class="decode_table_header">';
		$x .= '<td colspan="2"><strong>'.__('Details', 'car-demon').'</strong></td>';
	$x .= '</tr>';
	if ( $show_hide['body_style'] != true ) {
	  $x .= '<tr class="decode_table_odd">';
		$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['body_style'] . '</td>';
		$x .= '<td><input type="text" id="decoded_body_style" name="decoded_body_style" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $decoded_body_style . '" /></td>';
	  $x .= '</tr>';
	}
	if ( $show_hide['year'] != true ) {
	  $x .= '<tr class="decode_table_even">';
		$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['year'] . '</td>';
		$x .= '<td><input type="text" id="decoded_model_year" name="decoded_model_year" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $decoded_model_year . '" /></td>';
	  $x .= '</tr>';
	}
	if ( $show_hide['make'] != true ) {
	  $x .= '<tr class="decode_table_odd">';
		$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['make'] . '</td>';
		$x .= '<td><input type="text" id="decoded_make" name="decoded_make" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $decoded_make . '" /></td>';
	  $x .= '</tr>';
	}
	if ( $show_hide['model'] != true ) {
	  $x .= '<tr class="decode_table_even">';
		$x .= '<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field_labels['model'] . '</td>';
		$x .= '<td><input type="text" id="decoded_model" name="decoded_model" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $decoded_model . '" /></td>';
	  $x .= '</tr>';
	}

	//= BEGIN CUSTOM SPEC CODE
	if ( isset( $car_demon_options['show_custom_specs'] ) ) {
		$show_custom_specs = $car_demon_options['show_custom_specs'];
	} else {
		$show_custom_specs = 'No';
	}
	if ( $show_custom_specs == 'Yes' ) {
		$map = cd_get_vehicle_map();
		$specs_map = $map['specs'];
		foreach ( $specs_map as $key=>$spec_group ) {
			$x .= '<tr class="decode_table_header">
					<td colspan="2"><strong>' . $key . '</strong></td>
				</tr>';
				echo $key . '<br />';
			$spec_group_array = explode( ',', $spec_group );
			$odd_even = 'even';
			foreach( $spec_group_array as $spec_item ) {
				if($odd_even == 'odd') { $odd_even = 'even'; } else {$odd_even = 'odd';}
				$spec_item_slug = trim( $spec_item );
				$spec_item_slug = strtolower( $spec_item_slug );
				$spec_item_slug = str_replace( ' ', '_', $spec_item_slug );
				
				$group_slug = cd_clean_cap_slug( $key );
				if ( current_user_can( $spec_caps[ $group_slug ] ) ) {
					$disable = '';
				} else {
					$disable = ' disabled';
				}

				$x .= custom_spec_field_admin( $post_id, $spec_item, 'decoded_'.$spec_item_slug, $odd_even, $vin_query_decode, $disable );
			}
		}
	} else {
		$disable = '';
		$x .= custom_spec_field_admin( $post_id, __( 'Trim', 'car-demon' ), 'decoded_trim_level', 'odd', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Production Seq. Number', 'car-demon' ), 'decoded_production_seq_number', 'even', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Exterior Color', 'car-demon' ), 'exterior_color', 'odd', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Interior Color', 'car-demon' ), 'interior_color', 'even', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Manufactured in', 'car-demon' ), 'decoded_manufactured_in', 'odd', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Engine Type', 'car-demon' ), 'decoded_engine_type', 'even', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Transmission', 'car-demon' ), 'decoded_transmission_long', 'odd', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Driveline', 'car-demon' ), 'decoded_driveline', 'even', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Tank(gallon)', 'car-demon' ), 'decoded_driveline', 'odd', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Fuel Economy(City, miles/gallon)', 'car-demon' ), 'decoded_fuel_economy_city', 'even', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Fuel Economy(Highway, miles/gallon)', 'car-demon' ), 'decoded_fuel_economy_highway', 'odd', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Anti-Brake System', 'car-demon' ), 'decoded_anti_brake_system', 'even', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Steering Type', 'car-demon' ), 'decoded_steering_type', 'odd', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Length(in.)', 'car-demon' ), 'decoded_overall_length', 'even', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Width(in.)', 'car-demon' ), 'decoded_overall_width', 'odd', $vin_query_decode, $disable );
		$x .= custom_spec_field_admin( $post_id, __( 'Height(in.)', 'car-demon' ), 'decoded_overall_height', 'even', $vin_query_decode, $disable );
	}
	$x .= '</table>';
	return $x;
}

function custom_spec_field_admin( $post_id, $field, $slug, $odd_even, $vin_query_decode, $disable ) {
	if ( isset( $vin_query_decode[$slug] ) ) {$value = $vin_query_decode[$slug]; } else {$value = ''; }
	if ( empty( $value ) ) {
		$slug = str_replace( 'decoded_', '', $slug );
		if ( isset( $vin_query_decode[$slug] ) ) {$value = $vin_query_decode[$slug]; } else {$value = ''; }
	}

	$x = '
	  <tr class="decode_table_' . $odd_even . '">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;' . $field . '</td>
		<td><input type="text" id="' . $slug . '" name="spec_' . $slug . '" onchange="update_admin_decode(this, ' . $post_id . ')" value="' . $value . '"' . $disable . ' /></td>
	  </tr>
	';
	return $x;	
}

function get_about_us_tab( $post_id ) {
	$map = cd_get_vehicle_map();
	$x = '';
	if ( isset( $map['about_us'] ) ) {
		foreach( $map['about_us'] as $tab_group => $value ) {
			$x .= '<h2>' . stripslashes_deep( $tab_group ) . '</h2>';
			$x .= '<p>' . stripslashes_deep( $value ) . '</p>';
		}
	}
	return $x;	
}

function decode_select( $fld, $val, $post_id, $restrict = false ) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace( 'vin-query', '', $car_demon_pluginpath );
	$val = trim( $val );
	$no_check = '';
	$standard_checked = '';
	$option_checked = '';
	$na_checked = '';
	$img = '';
	if ( $val == '' ) {
		$no_check = ' selected';
		$img = '<img id="img_' . $fld . '" src="' . $car_demon_pluginpath . 'theme-files/images/spacer.gif" width="22" height="24" title="Standard Option" alt="Standard Option" />';	
	}
	if ( $val == 'Std.' ) {
		$standard_checked = ' selected';
		$img = '<img id="img_' . $fld . '" src="' . $car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="Standard Option" alt="Standard Option" />';
	}
	if ( $val == 'Opt.' ) {
		$option_checked = ' selected';
		$img = '<img id="img_' . $fld . '" src="' . $car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="Optional" alt="Optional" />';	
	}
	if ( $val == 'N/A' ) {
		$na_checked = ' selected';
		$img = '<img id="img_' . $fld . '" src="' . $car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="NA" alt="NA" />';
	}
	if ( $restrict ) {
		$disable = ' disabled';
	} else {
		$disable = '';
	}
	$x = $img . '&nbsp;<select onchange="update_decode_option(this, ' . $post_id . ')" id="' . $fld . '" name="option_' . $fld . '"'. $disable.'>';
		$x .= '<option value=""' . $no_check . '>None</option>';
		$x .= '<option value="Std."' . $standard_checked . '>Standard</option>';
		$x .= '<option value="Opt."' . $option_checked . '>Optional</option>';
		$x .= '<option value="N/A"' . $na_checked . '>Not Available</option>';
	$x .= '</select>';
	return $x;
}
?>
