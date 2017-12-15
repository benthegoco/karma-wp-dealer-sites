<?php
function car_demon_trade_form($post_id=0, $location) {
	global $car_demon_options;
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/car-demon-forms/forms', '', $car_demon_pluginpath);
	if (isset($car_demon_options['use_form_css'])) {
		if ($car_demon_options['use_form_css'] != 'No') {
			wp_enqueue_style('car-demon-trade-css', plugins_url() . '/car-demon/car-demon-forms/forms/css/car-demon-trade.css');
		}
	} else {
		wp_enqueue_style('car-demon-trade-css', plugins_url() . '/car-demon/car-demon-forms/forms/css/car-demon-trade.css');
	}
	if (isset($car_demon_options['validate_phone'])) {
		if ($car_demon_options['validate_phone'] == 'Yes') {
			$validate_phone = ' onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);"';
		} else {
			$validate_phone = '';
		}
	} else {
		$validate_phone = '';
	}
	$nonce = wp_create_nonce("cd_contact_us_nonce");
	$x = '
	<div id="trade_msg" class="trade_msg"></div>
	<form enctype="multipart/form-data" action="?send_trade=1" method="post" class="cdform trade-appointment " id="trade_form">
			<input type="hidden" name="nonce" id="nonce" value="'.$nonce.'" />
			<fieldset class="cd-fs1">
			<legend>'.__('Your Information', 'car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-name" class=""><label for="cd_field_2"><span>'.__('Your Name', 'car-demon').'</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="'.__('Your Name', 'car-demon').'" onfocus="clearField(this)" onblur="setField(this)"><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li" class=""><label for="cd_field_"><span>'.__('Phone #', 'car-demon').'</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" '.$validate_phone.'><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-4" class=""><label for="cd_field_4"><span>'.__('Email', 'car-demon').'</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">('.__('valid email required', 'car-demon').')</span></li>
			</ol>
			</fieldset>';
	$x .='
			<fieldset class="cd-fs4">
			<legend>'.__('Vehicle Information', 'car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-15" class=""><label for="cd_field_15"><span>'.__('Year', 'car-demon').'</span></label><input type="text" name="year" id="year" class="single" value=""><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-14" class=""><label for="cd_field_14"><span>'.__('Manufacturer', 'car-demon').'</span></label><input type="text" name="make" id="make" class="single" value=""><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-16" class=""><label for="cd_field_16"><span>'.__('Model', 'car-demon').'</span></label><input type="text" name="model" id="model" class="single" value=""><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-17" class=""><label for="cd_field_17"><span>'.__('Miles', 'car-demon').'</span></label><input type="text" name="miles" id="miles" class="single" value=""><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-18" class=""><label for="cd_field_18"><span>'.__('Vin', 'car-demon').'</span></label><input type="text" name="vin" id="vin" class="single" value=""></li>
				<li id="li-5" class=""><label for="cd_field_5"><span>'.__('Comments', 'car-demon').'</span></label><textarea cols="30" rows="4" name="comment" id="comment" class="area fldrequired"></textarea></li>
			</ol>
			</fieldset>';
	$x .= car_demon_trade_options();
	$x .= '
		<fieldset class="cd-fs2">
		<legend>'.__('Purchase Information', 'car-demon').'</legend>
		';
		if (isset($_GET['stock_num'])) {
			$x .= select_trade_for_vehicle(1);
			$x .= get_trade_for_vehicle($_GET['stock_num']);
		}
		else {
			$x .= select_trade_for_vehicle(0);
			$x .= '<ol class="cd-ol" id="show_voi"></o>';
		}
	$x .= '</fieldset>';
	if ($location == 'normal') {
		$x .= trade_locations_radio();
	} else {
		$x .= '<span id="select_location"><input type="radio" style="display:none;" name="trade_location" id="trade_location_1" value="'.$location.'" checked /></span>';
	}
	$x = apply_filters('cd_form_filter', $x, 'trade_form', '');
	$x = apply_filters('car_demon_mail_hook_form', $x, 'trade_form', 'unk');
	$x .= '
		<p class="cd-sb"><input type="button" name="search_btn" id="sendbutton" class="search_btn trade_btn" value="'.__("Get Quote!").'" onclick="return car_demon_validate_trade()"></p></form>
	';
	return $x;
}
function get_trade_for_vehicle($stock_num) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value='".$stock_num."'";
	$posts = $wpdb->get_results($sql);
	$vehicle_vin = '';
	$vehicle_year = '';
	$vehicle_make = '';
	$vehicle_model = '';
	$vehicle_condition = '';
	$vehicle_body_style = '';
	$vehicle_photo = '';
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);
			$vehicle_year = get_cd_term( $post_id, 'vehicle_year' );
			$vehicle_make = get_cd_term( $post_id, 'vehicle_make' );
			$vehicle_model = get_cd_term( $post_id, 'vehicle_model' );
			$vehicle_condition = get_cd_term( $post_id, 'vehicle_condition' );
			$vehicle_body_style = get_cd_term( $post_id, 'vehicle_condition' );
			$vehicle_photo = cd_main_photo( $post_id );
		}
	}
	$x = '
		<input type="hidden" id="purchase_stock" value="'.$stock_num.'" />
		<ol class="cd-ol" id="show_voi">
			<li id="" class="cd-box-title">'.__('Vehicle of Interest', 'car-demon').'</li>
			<li id="not_voi" class="cd-box-title"><input type="checkbox" class="not_my_car" onclick="show_voi()" />&nbsp;'.__('This is', 'car-demon').' <b>'.__('NOT', 'car-demon').'</b> '.__('the vehicle I\'m interested in.', 'car-demon').'</li>';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>'.__('Stock #', 'car-demon').'</span></label><label class="trade_label"><span class="trade_label">'.$stock_num.'</span></label></li>';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>'.__('VIN', 'car-demon').'</span></label><label class="trade_label"><span class="trade_label">'.$vehicle_vin.'</span></label></li>';
			$vehicle = $vehicle_condition .' '. $vehicle_year .' '. $vehicle_make .' '. $vehicle_model .' '. $vehicle_body_style;
			$x .= '<li id="" class=""><label for="cd_field_2"><span>'.__('Vehicle', 'car-demon').'</span></label><label class="trade_label"><span class="trade_label">'.$vehicle.'</span></label></li>';
			$x .= '<li id="" class=""><img src="'.$vehicle_photo.'" width="300" class="random_widget_image trade_img" title="'.$vehicle.'" alt="'.$vehicle.'" /></li>';
			$x .= '
			</li>
		</ol>
	';
	return $x;
}
function select_trade_for_vehicle($hide=0) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('forms/','',$car_demon_pluginpath);
	if ($hide == 1) {
		$hidden = " trade_hide";
	} else {
		$hidden = '';
	}
	$x = '
		<ol class="cd-ol'.$hidden.'" id="find_voi">
			<li id="voi_title" class="cd-box-title">'.__('What Vehicle are you Interested In Purchasing?', 'car-demon').'</li>
			<li id="" class="cd-box-title"><input onclick="select_voi(\'stock\');" name="pick_voi" id="pick_voi_1" type="radio" value="1" />'.__('I know the stock#', 'car-demon').'</li>
			<li id="select_stock" class="trade_select_stock"><span>'.__('Stock #', 'car-demon').'</span>&nbsp;<input class="ac_input" type="text" id="select_stock_txt" /></li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_2" onclick="select_voi(\'search\');" type="radio" value="2" />'.__('I would like to search for it', 'car-demon').'</li>
			<li id="select_description" class="trade_select_description"><span>'.__('Description', 'car-demon').'</span>&nbsp;<input type="text"  id="select_car_txt" /><span>&nbsp;('.__('enter year, make or model', 'car-demon').')</span></li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_3" onclick="select_voi(\'na\');" type="radio" checked="checked" value="3" />'.__('I haven\'t made up my mind.', 'car-demon').'</li>
			<li id="li-7items" class="cd-box-group">';
	$x .= '
			</li>
		</ol>
	';
	return $x;
}
function trade_locations_radio() {
	$args = array(
		'style'              => 'none',
		'show_count'         => 0,
		'use_desc_for_title' => 0,
		'hierarchical'       => true,
		'echo'               => 0,
		'hide_empty'		 => 0,
		'taxonomy'           => 'vehicle_location'
		);
	$locations = get_categories( $args );
	$cnt = 0;
	$location_list = '';
	$location_name_list = '';
	foreach ($locations as $location) {
		$cnt = $cnt + 1;
		$location_list .= ','.$location->slug;
		$location_name_list .= ','.$location->cat_name;
	}
	if (empty($locations)) {
		$location_list = 'default'.$location_list;
		$location_name_list = 'Default'.$location_name_list;
		$cnt = 1;
	} else {
		$location_list = '@'.$location_list;
		$location_list = str_replace("@,","", $location_list);
		$location_list = str_replace("@","", $location_list);
		$location_name_list = '@'.$location_name_list;
		$location_name_list = str_replace("@,","", $location_name_list);
		$location_name_list = str_replace("@","", $location_name_list);
	}
	$location_name_list_array = explode(',',$location_name_list);
	$location_list_array = explode(',',$location_list);
	$x = 0;
	if (empty($_GET['stock_num'])) {
		$hidden = "";	
	} else {
		$hidden = " trade_hide";
	}
	$html = '
		<fieldset class="cd-fs2'.$hidden.'" id="trade_locations">
		<legend id="trade_locations_label">'.__('Trade Location', 'car-demon').'</legend>
		<ol class="cd-ol">
			<li id="select_location" class="cd-box-title">'.__('Select your preferred Trade Location', 'car-demon').'</li>
			<li id="li-7items" class="cd-box-group">
	';
	if ($cnt == 1) {
		$select_trade = " checked='checked'";
	} else {
		$select_trade = '';
	}
	foreach ($location_list_array as $current_location) {
		$x = $x + 1;
		$html .= '
			<input type="radio"'.$select_trade.' id="trade_location_'.$x.'" name="trade_location" value="'.get_option($current_location.'_trade_name').'" class="cd-radio"><span for="trade_location_'.$x.'" class="cdlabel_right"><span>'.get_option($current_location.'_trade_name').'</span></span>
			<br>
		';
	}
	$html .= '
			</li>
		</ol>
		</fieldset>
	';
	return $html;
}
function car_demon_trade_options() {
	$option_array = array(
		__('4 Wheel Drive', 'car-demon'),
		__('ABS Brakes', 'car-demon'),
		__('Air Bag', 'car-demon'),
		__('Air Conditioning', 'car-demon'),
		__('Alloy Wheels', 'car-demon'),
		__('AM/FM Stereo', 'car-demon'),
		__('Anti-Theft', 'car-demon'),
		__('Bed Liner', 'car-demon'),
		__('Bra', 'car-demon'),
		__('Cassette', 'car-demon'),
		__('Cruise Control', 'car-demon'),
		__('Dual Air Bags', 'car-demon'),
		__('Dual Rear Wheels', 'car-demon'),
		__('DVD System', 'car-demon'),
		__('Integrated Cellular', 'car-demon'),
		__('Leather', 'car-demon'),
		__('Long Bed', 'car-demon'),
		__('Luggage Rack', 'car-demon'),
		__('Moon Roof', 'car-demon'),
		__('Multi CD', 'car-demon'),
		__('Nav System', 'car-demon'),
		__('Power Locks', 'car-demon'),
		__('Power Seats', 'car-demon'),
		__('Power Windows', 'car-demon'),
		__('Premium Wheels', 'car-demon'),
		__('Privacy Glass', 'car-demon'),
		__('Rear Air/Heat', 'car-demon'),
		__('Running Boards', 'car-demon'),
		__('Short Bed', 'car-demon'),
		__('Single CD', 'car-demon'),
		__('Sliding Rear Window', 'car-demon'),
		__('Sun Roof', 'car-demon'),
		__('Third Seat', 'car-demon'),
		__('Tilt Wheel', 'car-demon'),
		__('Towing Package', 'car-demon'),
		__('Video System', 'car-demon'),
		__('Wheel Covers', 'car-demon')
	);

	$option_array = apply_filters('car_demon_trade_options_filter', $option_array ); //= deprecated
	$option_array = apply_filters('cd_trade_options_filter', $option_array );

	$x = '<fieldset class="cd-fs3">';
		$x .= '<legend>'.__('Your Trade-In Vehicle Options', 'car-demon').'</legend>';
		$x .= '<ol class="cd-ol">';
			$x .= '<li id="li-7-25items" class="cd-box-group">';
			
			$cnt = 1;
			$break = 1;
			foreach ($option_array as $option) {
				$x .= '<span class="cd_trade_option">';
					$x .= '<input type="checkbox" id="Options-'. $cnt .'" name="Options[]" value="'. $option .'" class="cd-box">';
					$x .= '<label for="Options-'. $cnt .'" class="cd-group-after">';
						$x .= '<span>'. $option .'</span>';
					$x .= '</label>'.chr(13);
				$x .= '</span>';
				if ($break == 4) {
					$x .= '<br />';
					$break = 0;
				}
				++$cnt;
				++$break;
			}
			
			$x .= '</li>';
		$x .= '</ol>';
		$x .= '<span class="reqtxt trade_reqtxt">('.__('options not required, but help provide an accurate quote', 'car-demon').')</span>';
	$x .= '</fieldset>';

	return $x;
}
?>