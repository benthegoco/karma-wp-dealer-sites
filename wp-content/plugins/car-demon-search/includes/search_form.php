<?php
function cdsf_advanced_search_form($settings = array()) {
	//= Uncomment to store inventory in field on page, this bypasses all cache features if needed
	//=	<input type="hidden" id="cdsf_inventory" name="cdsf_inventory" value="'.get_option('cdsf_cache_current_inventory').'" />
	$min_max_values = get_option('cdsf_min_max_values', true);

	if ( isset( $_GET['search_dropdown_Min_years'] ) ) {
		if ( ! empty( $_GET['search_dropdown_Min_years'] ) ) {
			$min_max_values['min_year'] = sanitize_text_field( $_GET['search_dropdown_Min_years'] );
		}
	}
	if ( isset( $_GET['search_dropdown_Max_years'] ) ) {
		if ( ! empty( $_GET['search_dropdown_Max_years'] ) ) {
			$min_max_values['max_year'] = sanitize_text_field( $_GET['search_dropdown_Max_years'] );
		}
	}

	if ( isset( $_GET['search_dropdown_miles_Min'] ) ) {
		if ( ! empty( $_GET['search_dropdown_miles_Min'] ) ) {
			$min_max_values['min_miles'] = sanitize_text_field( $_GET['search_dropdown_miles_Min'] );
		}
	}
	if ( isset( $_GET['search_dropdown_miles_Max'] ) ) {
		if ( ! empty( $_GET['search_dropdown_miles_Max'] ) ) {
			$min_max_values['max_miles'] = sanitize_text_field( $_GET['search_dropdown_miles_Max'] );
		}
	}

	if ( isset( $_GET['search_dropdown_Min_price'] ) ) {
		if ( ! empty( $_GET['search_dropdown_Min_price'] ) ) {
			$min_max_values['min_price'] = sanitize_text_field( $_GET['search_dropdown_Min_price'] );
		}
	}
	if ( isset( $_GET['search_dropdown_Max_price'] ) ) {
		if ( ! empty( $_GET['search_dropdown_Max_price'] ) ) {
			$min_max_values['max_price'] = sanitize_text_field( $_GET['search_dropdown_Max_price'] );
		}
	}

	$site_url = get_option('siteurl');
	global $car_demon_options;
	if (isset($car_demon_options['inventory_page'])) {
		$url = $car_demon_options['inventory_page'];
	}

	if (isset($settings['form_action'])) {
		if (!empty($settings['form_action'])) {
			if ($settings['form_action'] != $site_url) {
				$url = $settings['form_action'];
			}
		}
	}

	if (empty($url)) {
		$url = $site_url;
	}

	$filter_active = 0;
	$filter_class = '';

	$location = '';
	if (isset($_GET['search_location'])) {
		if ($_GET['search_location'] != '') {
			$location = $_GET['search_location'];
			$location = ucfirst($location);
			$filter_active = 1;
		}
	}
	if (isset($settings['value']['location'])) {
		if ($settings['value']['location'] != '') {
			$location = $settings['value']['location'];
			$location = ucfirst($location);
			$filter_active = 1;
		}
	}

	$condition = '';
	if (isset($_GET['search_condition'])) {
		if ($_GET['search_condition'] != '') {
			$condition = $_GET['search_condition'];
			$condition = ucfirst($condition);
			$filter_active = 1;
		}
	}
	if (isset($settings['value']['condition'])) {
		if ($settings['value']['condition'] != '') {
			$condition = $settings['value']['condition'];
			$condition = ucfirst($condition);
			$filter_active = 1;
		}
	}


	$year = '';
	if (isset($_GET['search_year'])) {
		if ($_GET['search_year'] != '') {
			$year = $_GET['search_year'];
			$year = ucfirst($year);
			$filter_active = 1;
		}
	}
	if (isset($settings['value']['year'])) {
		if ($settings['value']['year'] != '') {
			$year = $settings['value']['year'];
			$year = ucfirst($year);
			$filter_active = 1;
		}
	}

	$make = '';
	if (isset($_GET['search_make'])) {
		if ($_GET['search_make'] != '') {
			$make = $_GET['search_make'];
			$make = str_replace('%2C','',$make);
			$make = str_replace(',','',$make);
			$make = ucfirst($make);
			$filter_active = 1;
		}
	}
	if (isset($settings['value']['make'])) {
		if ($settings['value']['make'] != '') {
			$make = $settings['value']['make'];
			$make = str_replace('%2C','',$make);
			$make = str_replace(',','',$make);
			$make = ucfirst($make);
			$filter_active = 1;
		}
	}

	$model = '';
	if (isset($_GET['search_model'])) {
		if ($_GET['search_model'] != '') {
			$model = $_GET['search_model'];
			$model = ucfirst($model);
			$filter_active = 1;
		}
	}
	if (isset($settings['value']['model'])) {
		if ($settings['value']['model'] != '') {
			$model = $settings['value']['model'];
			$model = ucfirst($model);
			$filter_active = 1;
		}
	}

	$trim_level = '';
	if (isset($settings['value']['trim_level'])) {
		if ($settings['value']['trim_level'] != '') {
			$trim_level = $settings['value']['trim_level'];
			$trim_level = ucfirst($trim_level);
			$filter_active = 1;
		}
	}

	if (isset($_GET['search_trim_level'])) {
		if ($_GET['search_trim_level'] != '') {
			$trim_level = $_GET['search_trim_level'];
			$trim_level = ucfirst($trim_level);
			$filter_active = 1;
		}
	}
	
	$transmission = '';
	if (isset($settings['value']['transmission'])) {
		if ($settings['value']['transmission'] != '') {
			$transmission = $settings['value']['transmission'];
			$transmission = ucfirst($transmission);
			$filter_active = 1;
		}
	}

	if (isset($_GET['search_transmission'])) {
		if ($_GET['search_transmission'] != '') {
			$transmission = $_GET['search_transmission'];
			$transmission = ucfirst($transmission);
			$filter_active = 1;
		}
	}
	
	$body_style = '';
	if (isset($_GET['search_dropdown_body'])) {
		if ($_GET['search_dropdown_body'] != '') {
			$body_style = $_GET['search_dropdown_body'];
			$body_style = ucfirst($body_style);
			$filter_active = 1;
		}
	}
	if (isset($_GET['search_body_style'])) {
		if ($_GET['search_body_style'] != '') {
			$body_style = $_GET['search_body_style'];
			$body_style = ucfirst($body_style);
			$filter_active = 1;
		}
	}
	if (isset($settings['value']['body_style'])) {
		if ($settings['value']['body_style'] != '') {
			$body_style = $settings['value']['body_style'];
			$body_style = ucfirst($body_style);
			$filter_active = 1;
		}
	}

	if ($filter_active == 1) {
		$filter_class = ' cdsf_filter_active';
	}
	if (!isset($settings['hide'])) {
		$settings['hide'] = array();
	}
	
	$custom_class = '';
	if (isset($settings['custom_class'])) {
		if (!empty($settings['custom_class'])) {
			$custom_class = ' ' . $settings['custom_class'];
		}
	}

	if (!isset($settings['label'])) {
		$label = array();
	} else {
		$label = $settings['label'];
	}
	
	if (!isset($settings['label']['location'])) {
		$label['location'] = __('Location', 'car-demon-search');
	}
	if (!isset($settings['label']['condition'])) {
		$label['condition'] = __('Condition', 'car-demon-search');
	}
	if (!isset($settings['label']['year'])) {
		$label['year'] = __('Year', 'car-demon-search');
	}
	if (!isset($settings['label']['make'])) {
		$label['make'] = __('Make', 'car-demon-search');
	}
	if (!isset($settings['label']['model'])) {
		$label['model'] = __('Model', 'car-demon-search');
	}
	if (!isset($settings['label']['year'])) {
		$label['year'] = __('Year Range', 'car-demon-search');
	}
	if (!isset($settings['label']['price'])) {
		$label['price'] = __('Price Range', 'car-demon-search');
	}
	if (!isset($settings['label']['mileage'])) {
		$label['mileage'] = __('Mileage Range', 'car-demon-search');
	}
	if (!isset($settings['label']['body_style'])) {
		$label['body_style'] = __('Body Style', 'car-demon-search');
	}
	if (!isset($settings['label']['button'])) {
		$label['button'] = __('Find Your Car', 'car-demon-search');
	}
	if (!isset($settings['label']['reset'])) {
		$label['reset'] = __('Reset Filters', 'car-demon-search');
	}
	if (!isset($settings['label']['trim_level'])) {
		$label['trim_level'] = __('Trim Level', 'car-demon-search');
	}
	if (!isset($settings['label']['transmission'])) {
		$label['transmission'] = __('Transmission', 'car-demon-search');
	}
	
	if ($url == get_option('siteurl')) {
		$search_fld = '<input type="hidden" name="s" value="cars" />';
	} else {
		$search_fld = '';
	}
	
	$cdsf_use_css_form = '';
	if (isset($settings['style'])) {
		if (!empty($settings['style'])) {
			$cdsf_use_css_form = $settings['style'];			
		}
	}

	if (empty($cdsf_use_css_form)) {
		$cdsf_use_css_form = get_option('cdsf_use_css_form', '1');
	}

	$style_class = '';
	if ($cdsf_use_css_form == '1') {
		$style_class = ' cdsf_one';
	} else if ($cdsf_use_css_form == '2') {
		$style_class = ' cdsf_two';
	} else if ($cdsf_use_css_form == '3') {
		$style_class = ' cdsf_three';
	} else if ($cdsf_use_css_form == '4') {
		$style_class = ' cdsf_four';
	}

	if ( ! defined('CD_SEARCH_TRIGGER') ) {
		$search_trigger = '<input type="hidden" name="car" value="2" />';
	} else {
		$search_trigger = '';
	}

	$x = '
	<form action="'.$url.'" method="get" class="cdsf_tb_forms'.$style_class.$custom_class.'" id="cdsf_tb_forms" />
		'.$search_fld.'
		'.$search_trigger.'
		<span id="car-demon-as">';

			if (in_array('location', $settings['hide'])) {
				$hide_location = ' cd_hide';
			} else {
				$hide_location = '';
			}
			$x .= '<div class="cdsf_item'.$hide_location.'">
						<div id="label_location">'.$label['location'].'</div>
						<input type="text" id="search_location" name="search_location" value="'.$location.'" />
						<div class="selectBox" id="search-location">
							<span class="selected" id="search_location_selected">Location</span>
							<span class="selectArrow">&#9660</span>
							<div class="selectOptions" id="search_location_options">
								'.get_cdsf_item('location').'
							</div>
							<div id="apply_location" class="cdsf_apply">Apply</div>
							<div id="apply_location_cancel" class="cdsf_apply_cancel">Hide</div>
						</div>
					</div>';

			if (in_array('condition', $settings['hide'])) {
				$hide_condition = ' cd_hide';
			} else {
				$hide_condition = '';
			}
			$x .= '<div class="cdsf_item'.$hide_condition.'">
						<div id="label_condition">'.$label['condition'].'</div>
						<input type="text" id="search_condition" name="search_condition" value="' . $condition . '" />
						<div class="selectBox" id="search-condition">
							<span class="selected" id="search_condition_selected">Condition</span>
							<span class="selectArrow">&#9660</span>
							<div class="selectOptions" id="search_condition_options">
								'.get_cdsf_item('condition').'
							</div>
							<div id="apply_condition" class="cdsf_apply">Apply</div>
							<div id="apply_condition_cancel" class="cdsf_apply_cancel">Hide</div>
						</div>
					</div>';

			if (in_array('year', $settings['hide'])) {
				$hide_year = ' cd_hide';
			} else {
				$hide_year = '';
			}
			$x .= '<div class="cdsf_item'.$hide_year.'">
					<div id="label_year">'.$label['year'].'</div>
					<input type="text" id="search_year" name="search_year" value="'.$year.'" />
					<div class="selectBox" id="search-year">
						<span class="selected" id="search_year_selected">Year</span>
						<span class="selectArrow">&#9660</span>
						<div class="selectOptions" id="search_year_options">
							'.get_cdsf_item('year').'
						</div>
						<div id="apply_year" class="cdsf_apply">Apply</div>
						<div id="apply_year_cancel" class="cdsf_apply_cancel">Hide</div>
					</div>
				</div>';

			if (in_array('make', $settings['hide'])) {
				$hide_make = ' cd_hide';
			} else {
				$hide_make = '';
			}
			$x .= '<div class="cdsf_item'.$hide_make.'">
					<div id="label_make">'.$label['make'].'</div>
					<input type="text" id="search_make" name="search_make" value="'.$make.'" />
					<div class="selectBox" id="search-make">
						<span class="selected" id="search_make_selected">Make</span>
						<span class="selectArrow">&#9660</span>
						<div class="selectOptions" id="search_make_options">
							'.get_cdsf_item('make').'
						</div>
						<div id="apply_make" class="cdsf_apply">Apply</div>
						<div id="apply_make_cancel" class="cdsf_apply_cancel">Hide</div>
					</div>
				</div>';

			if (in_array('model', $settings['hide'])) {
				$hide_model = ' cd_hide';
			} else {
				$hide_model = '';
			}
			$x .= '<div class="cdsf_item'.$hide_model.'">
					<div id="label_model">'.$label['model'].'</div>
					<input type="text" id="search_model" name="search_model" value="'.$model.'" />
					<div class="selectBox" id="search-model">
						<span class="selected" id="search_model_selected">Model</span>
						<span class="selectArrow">&#9660</span>
						<div class="selectOptions" id="search_model_options">
							'.get_cdsf_item('model').'
						</div>
						<div id="apply_model" class="cdsf_apply">Apply</div>
						<div id="apply_model_cancel" class="cdsf_apply_cancel">Hide</div>
					</div>
				</div>
				';

			if (in_array('body_style', $settings['hide'])) {
				$hide_body = ' cd_hide';
			} else {
				$hide_body = '';
			}

			$x .= '<div class="cdsf_item'.$hide_body.'">
						<div id="label_body_style">'.$label['body_style'].'</div>
						<input type="text" id="search_dropdown_body" name="search_dropdown_body" value="'.$body_style.'" />
						<div class="selectBox" id="search-body-style">
							<span class="selected" id="search_body_style_selected">Body Style</span>
							<span class="selectArrow">&#9660</span>
							<div class="selectOptions" id="search-body_style">
								'.get_cdsf_item('body_style').'
							</div>
						</div>
						<div id="apply_body_style" class="cdsf_apply">Apply</div>
						<div id="apply_body_style_cancel" class="cdsf_apply_cancel">Hide</div>
					</div>';

			if ( defined( 'CDPRO_EXTRAS' ) ) {
				if (in_array('trim_level', $settings['hide'])) {
					$hide_trim_level = ' cd_hide';
				} else {
					$hide_trim_level = '';
				}
	
				$x .= '<div class="cdsf_item'.$hide_trim_level.'">
							<div id="label_trim_level">'.$label['trim_level'].'</div>
							<input type="text" id="search_trim_level" name="search_trim_level" value="'.$trim_level.'" />
							<div class="selectBox" id="search-trim_level">
								<span class="selected" id="search_trim_level_selected">Trim Level</span>
								<span class="selectArrow">&#9660</span>
								<div class="selectOptions" id="search_trim_level_options">
									'.get_cdsf_item('trim_level').'
								</div>
							</div>
							<div id="apply_trim_level" class="cdsf_apply">Apply</div>
							<div id="apply_trim_level_cancel" class="cdsf_apply_cancel">Hide</div>
						</div>';
	
				if (in_array('transmission', $settings['hide'])) {
					$hide_transmission = ' cd_hide';
				} else {
					$hide_transmission = '';
				}

				$x .= '<div class="cdsf_item'.$hide_transmission.'">
							<div id="label_transmission">'.$label['transmission'].'</div>
							<input type="text" id="search_transmission" name="search_transmission" value="'.$transmission.'" />
							<div class="selectBox" id="search-transmission">
								<span class="selected" id="search_transmission_selected">Transmission</span>
								<span class="selectArrow">&#9660</span>
								<div class="selectOptions" id="search_transmission_options">
									'.get_cdsf_item('transmission').'
								</div>
							</div>
							<div id="apply_transmission" class="cdsf_apply">Apply</div>
							<div id="apply_transmission_cancel" class="cdsf_apply_cancel">Hide</div>
						</div>';
	
	
			}


			if (in_array('year_range', $settings['hide'])) {
				$hide_year_range = ' cd_hide';
			} else {
				$hide_year_range = '';
			}
			$x .='<div class="cdsf_item'.$hide_year_range.'">
						<div id="label_year_range">'.$label['year'].'</div>
						<input type="text" name="search_dropdown_Min_years" id="search_dropdown_Min_years" value="'.$min_max_values['min_year'].'" />
						<input type="text" name="search_dropdown_Max_years" id="search_dropdown_Max_years" value="'.$min_max_values['max_year'].'" />
						<input type="text" id="years_range" />
							<div id="year_range_box">
								<div id="years-slider"></div>
								<div id="apply_years_range" class="cdsf_apply">Apply</div>
								<div id="apply_year_cancel" class="cdsf_apply_cancel">Hide</div>
							</div>
					</div>';

			if (in_array('price', $settings['hide'])) {
				$hide_price = ' cd_hide';
			} else {
				$hide_price = '';
			}
			$x .= '<div class="cdsf_item'.$hide_price.'">
						<div id="label_price_range">'.$label['price'].'</div>
						<input type="text" name="search_dropdown_Min_price" id="search_dropdown_Min_price" value="'.$min_max_values['min_price'].'" />
						<input type="text" name="search_dropdown_Max_price" id="search_dropdown_Max_price" value="'.$min_max_values['max_price'].'" />
						<input type="text" id="price_range" />
							<div id="price_range_box">
								<div id="price-slider"></div>
								<div id="apply_price_range" class="cdsf_apply">Apply</div>
								<div id="apply_price_cancel" class="cdsf_apply_cancel">Hide</div>
							</div>
					</div>';
			
			if (in_array('mileage', $settings['hide'])) {
				$hide_mileage = ' cd_hide';
			} else {
				$hide_mileage = '';
			}
			$x .= '<div class="cdsf_item'.$hide_mileage.'">
						<div id="label_miles_range">'.$label['mileage'].'</div>
						<input type="text" name="search_dropdown_miles_Min" id="search_dropdown_miles_Min" value="'.$min_max_values['min_miles'].'" />
						<input type="text" name="search_dropdown_miles_Max" id="search_dropdown_miles_Max" value="'.$min_max_values['max_miles'].'" />
						<input type="text" id="miles_range" />
							<div id="mileage_range_box">
								<div id="mileage-slider"></div>
								<div id="apply_mileage_range" class="cdsf_apply">Apply</div>
								<div id="apply_mileage_cancel" class="cdsf_apply_cancel">Hide</div>
							</div>
					</div>';
				
	$x .= '	<div class="cdsf_item">';
		$x .= '	<div id="cdsf_button_box" class="cdsf_button_box">
					<input type="submit" name="cdsf_button_apply" id="cdsf_button_apply" value="'.$label['button'].'" class="cdsf_button">
				</div>';
	$x .= '</div>
		   <div class="clear"></div>';

	$x .= ' <div id="reset_cdsf_filters" class="reset_cdsf_filters'.$filter_class.'">'.$label['reset'].'</div>
		</span>
	</form>
	<div class="clear"></div>
	<div id="cdsf_results_box">';

		$x .= '<div id="cdsf_results_found"></div>';	

	$x .= '
		<input type="hidden" id="current_results_found" value="0" />
		<div id="cdsf_results_found_title">
		</div>
		<div id="cdsf_results">
		</div>
	</div>
	<div class="clear"></div>
	';
	return $x;
}

function get_cdsf_item($type) {
	$x = '';
	if ($type == 'make') {
		$items = get_option('cdsf_cache_makes');
		if (isset($_GET['search_make'])) {
			if ($_GET['search_make'] != '') {
				$make = $_GET['search_make'];
				$make = str_replace('%2C','',$make);
				$make = str_replace(',','',$make);
				$make = ucfirst($make);
				if (stripos($make, '-')) {
					$make_array = explode('-',$make);
					$make = $make_array[0];
				}
				$x = '<span class="selectOption" value="'.$make.'">'.$make.'</span>';
			}
		}
	} elseif ($type == 'location') {
		$items = get_option('cdsf_cache_location');
		if (isset($_GET['search_location'])) {
			if ($_GET['search_location'] != '') {
				$search_location = $_GET['search_location'];
				$search_location = ucfirst($search_location);
				if (stripos($search_location, '-')) {
					$search_location_array = explode('-',$search_location);
					$search_location = $search_location_array[0];
				}
				$x = '<span class="selectOption" value="'.ucfirst($search_location).'">'.ucfirst($search_location).'</span>';
			}
		}
	} elseif ($type == 'condition') {
		$items = get_option('cdsf_cache_condition');
		if (isset($_GET['search_condition'])) {
			if ($_GET['search_condition'] != '') {
				$search_condition = $_GET['search_condition'];
				$search_condition = ucfirst($search_condition);
				if (stripos($search_condition, '-')) {
					$search_condition_array = explode('-',$search_condition);
					$search_condition = $search_condition_array[0];
				}
				$x = '<span class="selectOption" value="'.ucfirst($search_condition).'">'.ucfirst($search_condition).'</span>';
			}
		}
	} elseif ($type == 'model') {
		$items = get_option('cdsf_cache_models');
		if (isset($_GET['search_model'])) {
			if ($_GET['search_model'] != '') {
				$search_model = $_GET['search_model'];
				if (stripos($search_model, '-')) {
					$search_model_array = explode('-',$search_model);
					$search_model = $search_model_array[0];
				}
				$x = '<span class="selectOption" value="'.ucfirst($search_model).'">'.ucfirst($search_model).'</span>';
			}
		}
	} elseif ($type == 'body_style') {
		$items = get_option('cdsf_cache_body_style');
		if (isset($_GET['search_dropdown_body'])) {
			if ($_GET['search_dropdown_body'] != '') {
				$search_dropdown_body = $_GET['search_dropdown_body'];
				if (stripos($search_dropdown_body, '-')) {
					$search_dropdown_body_array = explode('-',$search_dropdown_body);
					$search_dropdown_body = $search_dropdown_body_array[0];
				}
				$x = '<span class="selectOption" value="'.ucfirst($search_dropdown_body).'">'.ucfirst($search_dropdown_body).'</span>';
			}
		}

	} elseif ($type == 'trim_level') {
		$items = get_option('cdsf_cache_trim_levels');
		if (isset($_GET['search_trim_level'])) {
			if ($_GET['search_trim_level'] != '') {
				$search_trim_level = $_GET['search_trim_level'];
				if (stripos($search_trim_level, '-')) {
					$search_trim_level_array = explode('-',$search_trim_level);
					$search_trim_level = $search_trim_level_array[0];
				}
				$x = '<span class="selectOption" value="'.ucfirst($search_trim_level).'">'.ucfirst($search_trim_level).'</span>';
			}
		}
	} elseif ($type == 'transmission') {
		$items = get_option('cdsf_cache_transmissions');
		if (isset($_GET['search_transmission'])) {
			if ($_GET['search_transmission'] != '') {
				$search_transmission = $_GET['search_transmission'];
				if (stripos($search_transmission, '-')) {
					$search_transmission_array = explode('-',$search_transmission);
					$search_transmission = $search_transmission_array[0];
				}
				$x = '<span class="selectOption" value="'.ucfirst($search_transmission).'">'.ucfirst($search_transmission).'</span>';
			}
		}

	} elseif ($type == 'year') {
		$items = get_option('cdsf_cache_years');
	} elseif ($type == 'body_style') {
		$items = get_option('cdsf_cache_body_style');
	} else {
		$items = '';
	}

	if (!empty($items)) {
		$items_array = json_decode($items, true);
		foreach ($items_array as $item) {
			foreach ($item as $current_item) {
				$x .= '<span class="selectOption" value="'.$current_item.'">'.$current_item.'</span>';
			}
		}
	}
	return $x;
}

?>
