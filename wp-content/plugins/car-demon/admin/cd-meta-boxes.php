<?php
if (is_admin()) {
	$post_type = car_demon_get_current_post_type();
	//add_action( 'wp_dashboard_setup', 'cd_add_dashboard_widgets' );
	if ( $post_type == 'cars_for_sale' ) {
		add_action( 'admin_enqueue_scripts', 'car_demon_admin_header' );
		add_action( 'add_meta_boxes', 'cd_start_meta_boxes' );
	}
}

add_action( 'wp_ajax_car_demon_vinquery', 'car_demon_vinquery' );
add_action( 'wp_ajax_nopriv_car_demon_vinquery', 'car_demon_vinquery' );

function car_demon_admin_header() {
	wp_register_script( 'car-demon-vin-query-admin-js', plugins_url() . '/car-demon/vin-query/js/car-demon-vin-query.js', array(), CAR_DEMON_VER );
	wp_localize_script( 'car-demon-vin-query-admin-js', 'cdVinQueryParams', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'car_demon_path' => CAR_DEMON_PATH,
	));
	wp_enqueue_script( 'car-demon-vin-query-admin-js' );
	wp_enqueue_script( 'car-demon-jquery-lightbox', plugins_url() . '/car-demon/theme-files/js/jquery.lightbox_me.js', array( 'jquery' ), CAR_DEMON_VER );
	wp_enqueue_style( 'car-demon-vin-query-css', plugins_url() . '/car-demon/vin-query/css/car-demon-vin-query.css', array(), CAR_DEMON_VER );
}

function cd_start_meta_boxes() {
	global $car_demon_options;
	global $theme_name;
	add_meta_box( 'decode-div', __( 'Vehicle Options', 'car-demon' ), 'decode_metabox', 'cars_for_sale', 'normal', 'high' );
	//= Only use the custom option box if they're hiding tabs
	if ( isset( $car_demon_options['hide_tabs'] ) ) {
		if ( $car_demon_options['hide_tabs'] == 'Yes' ) {
			add_meta_box( 'cd_custom_metabox', __( 'Custom Options', 'car-demon' ), 'cd_custom_metabox', 'cars_for_sale', 'normal', 'high' );
		}
	}
	add_meta_box( 'cd_sold_metabox', __( 'Sales Status', 'car-demon' ), 'cd_sold_metabox', 'cars_for_sale', 'side', 'high' );
	add_meta_box( 'cd_ribbons_meta_box', __( 'Photo Ribbon', 'car-demon' ), 'cd_ribbons_meta_box', 'cars_for_sale', 'side', 'default' );
	add_meta_box( 'cd_images_metabox', __( 'Vehicle Photos', 'car-demon' ), 'cd_images_metabox', 'cars_for_sale', 'side', 'default' );
}

function cd_add_dashboard_widgets() {
	wp_add_dashboard_widget( 'vinquery_dashboard_widget', __( 'Add a Vehicle', 'car-demon' ), 'vinquery_add_vehicle_dashboard_widget_function' );
	global $wp_meta_boxes;
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	$vinquery_dashboard_widget = array( 'vinquery_dashboard_widget' => $normal_dashboard['vinquery_dashboard_widget'] );
	unset( $normal_dashboard['vinquery_dashboard_widget'] );
	$sorted_dashboard = array_merge( $vinquery_dashboard_widget, $normal_dashboard );
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

function vinquery_add_vehicle_dashboard_widget_function() {
	$vin = '';
	$post_id = '';
	$html = '<div id="add_vehicle_div">';
		$html .= __( 'TITLE:', 'car-demon' ) . '<br /><input type="text" size="35" id="cd_title" name="cd_title" value="' . $vin . '"><br />';
		$html .= __( 'STOCK #:', 'car-demon' ) . '<br /><input type="text" size="35" id="cd_stock" name="cd_stock" value="' . $vin . '"><br />';
		$html .= __( 'VIN:', 'car-demon' ) . '<br /><input type="text" size="35" id="cd_vin" name="cd_vin" value="' . $vin . '" onchange="validate_vin(this.value)">';
		$html .= '<br /><input onclick="dashboard_decode_vin(' . $post_id . ')" type="button" name="decode_vin_' . $post_id . '" id="decode_vin_' . $post_id . '" value="' . __( 'Add Vehicle', 'car-demon' ) . '" class="btn" />';
		$html .= '<div id="alert_msg"></div>';
		$html .= '<div id="decode_results"></div>';
	$html .= '</div>';
	echo $html;
}

function decode_metabox( $post ) {
	$post_id = $post->ID;
	echo car_demon_admin_tabs( $post_id );
	return;
}

function car_demon_admin_tabs( $post_id ) {
	global $car_demon_options;
	$vin_query_decode = cd_get_car( $post_id );
	$vin = get_post_meta( $post_id, "_vin_value", true );
	$html = '';
	$show_tabs = 1;
	if ( isset( $car_demon_options['hide_tabs'] ) ) {
		if ( $car_demon_options['hide_tabs'] == 'Yes' ) {
			$show_tabs = 0;
		}
	}
	if ( ! isset( $vin_query_decode['hide_tabs'] ) ) {
		$vin_query_decode['hide_tabs'] = __( 'No', 'car-demon' );
	} else {
		if ( $vin_query_decode['hide_tabs'] == 'Yes' ) {
			$show_tabs = 0;
		}
	}

	/* disabled ability to show or hide tabs on per vehicle basis
	if ( $show_tabs == 1 ) {
		$html .= '<span class="cd_vehicle_hide_tabs">';
			$html .= __( 'Hide Tabs on vehicle display page?', 'car-demon' ) . '
				<select name="hide_tabs" id="hide_tabs" onchange="update_admin_decode(this, ' . $post_id . ')">
					<option value="' . $vin_query_decode['hide_tabs'] . '">' . $vin_query_decode['hide_tabs'] . '</option>
					<option value="Yes">' . __( 'Yes', 'car-demon' ) . '</option>
					<option value="No">' . __( 'No', 'car-demon' ) . '</option>
				</select><br />
			' . __( 'This option does not hide the description tab or the about us tab.','car-demon' ) . '
			<hr />';
		$html .= '</span>';
	}
	*/
	
	$html .= '
	<div id="vin_decode_options_' . $post_id . '">';
		$specs = get_tab_specs_admin( $vin_query_decode, $vin, $post_id );
		global $pagenow;
		if ( $pagenow == 'post-new.php' ) {
			$show_tabs = 0;
		}
		if ( $show_tabs == 1 ) {
			$safety = get_option_tab( 'safety', $post_id, 'admin' );
			$convienience = get_option_tab( 'convenience', $post_id, 'admin' );
			$comfort = get_option_tab( 'comfort', $post_id, 'admin' );
			$entertainment = get_option_tab('entertainment',$post_id,'admin');
		//= Enable this to use custom options and tabs at the same time.
		//	echo get_option_tab('about_us',$post_id,'admin');
		} else {
			$url = $_SERVER["REQUEST_URI"];
			if ( strpos( $url, 'post-new.php' ) ) {
				$html .= __( 'Vehicle Option Tabs will appear after the vehicle has been saved.', 'car_demon_options' );
			} else {
				$html .= __( 'Vehicle Option Tabs have been set to hidden under Car Demon settings and will not appear on the front end.', 'car_demon_options' );
			}
			$safety = '';
			$convienience = '';
			$comfort = '';
			$entertainment = '';
		}
		echo '<hr />';
		$html .= '<ul class="tabs">';
			$html .= '<li><a href="javascript:car_demon_switch_tabs(1, 5, \'tab_\', \'content_\');" id="tab_1">' . __( 'Specs', 'car-demon' ) . '</a></li> ';
			if ( $show_tabs == 1 ) {
				$html .= '<li><a href="javascript:car_demon_switch_tabs(2, 5, \'tab_\', \'content_\');" id="tab_2">' . __( 'Safety', 'car-demon' ) . '</a></li>';
				$html .= '<li><a href="javascript:car_demon_switch_tabs(3, 5, \'tab_\', \'content_\');" id="tab_3">' . __( 'Convenience', 'car-demon' ) . '</a></li>';
				$html .= '<li><a href="javascript:car_demon_switch_tabs(4, 5, \'tab_\', \'content_\');" id="tab_4">'. __('Comfort', 'car-demon' ) . '</a></li>';
				$html .= '<li><a href="javascript:car_demon_switch_tabs(5, 5, \'tab_\', \'content_\');" id="tab_5">' . __( 'Entertainment', 'car-demon' ) . '</a></li>';
			}
		$html .= '</ul>';
		$html .= '<div id="content_1" class="car_features_content">' . $specs . '</div> ';
		$html .= '<div id="content_2" class="car_features_content">' . $safety . '</div>  ';
		$html .= '<div id="content_3" class="car_features_content">' . $convienience . '</div>';
		$html .= '<div id="content_4" class="car_features_content">' . $comfort . '</div>';
		$html .= '<div id="content_5" class="car_features_content">' . $entertainment . '</div>';
	$html .= '</div>';
	return $html;
}

function cd_custom_metabox( $post ) {
	global $car_demon_options;
	$content = '';
	$vehicle_options = '<div style="overflow:hidden;">';
	$post_id = $post->ID;
	$vehicle_options_list = get_post_meta( $post_id, '_vehicle_options', true );
	$custom_option_list = $car_demon_options['custom_options'];
	if ( empty( $custom_option_list ) ) {
		$custom_option_list = cd_get_default_options();
	}
	$custom_option_list_array = explode( ',', $custom_option_list );
	$select_custom_options = '';
	foreach ( $custom_option_list_array as $custom_item ) {
		$select_custom_options .= '<option value="' . $custom_item . '">' . $custom_item . '</option>';
	}
	$vehicle_options .= '<div class="custom_option_container">
			<h3>
			' . __( 'Add custom vehicle options here', 'car-demon' ) . '
			</h3>
			<div class="cd_select_custom_options_container" id="cd_select_custom_options_container">
				'.__('Available', 'car-demon').'<br />
				<select size="5" id="ListBox1" class="cd_select_custom_options" id="cd_select_custom_options" name="cd_select_custom_options">' . $select_custom_options . '</select>
				<br /><input type = "button" id = "btnMoveRight" class="btn_move_right" value="' . __( 'Add To Vehicle', 'car-demon' ) . ' ->" onclick = "fnMoveItems(\'ListBox1\',\'vehicle_options\');update_admin_decode(document.getElementById(\'vehicle_options\'), ' . $post_id . ')">
			</div>
			<div class="cd_custom_options_box_container" id="cd_custom_options_box_container">
				' . __( 'Current Options', 'car-demon' ) . '<br />
				<textarea cols="60" class="cd_custom_options_box" id="vehicle_options" name="vehicle_options" onchange="update_admin_decode(this, ' . $post_id . ')">' . $vehicle_options_list . '</textarea>
			</div>
			<div class="custom_option_directions" id="custom_option_directions">
			' . __( 'You can select from the list or you can manually add and remove options in the box on the right. Make sure you separate each option with a comma.', 'car-demon' ) . '
			</div>
		</div>';
	$vehicle_options_array = explode( ',', $vehicle_options_list );
	$options_image = '<img src="' . plugins_url() . '/car-demon/theme-files/images/opt_standard.gif" />';
	$include_options = 0;
	foreach ( $vehicle_options_array as $vehicle_option ) {
		if ( ! empty( $vehicle_option ) ) {
			$include_options = 1;
			$vehicle_options .= '<div style="float:left;width:260px;">';
				$vehicle_options .= $options_image . '&nbsp;' . $vehicle_option . '<br />';
			$vehicle_options .= '</div>';
		}
	}
	$vehicle_options .= '</div>';
	$content .= $vehicle_options;
	echo $content;
	return;
}

function cd_images_metabox( $post ) {
	// Show currently attached photos
	$post_id = $post->ID;
	$popup_imgs = '';
	echo '
	<div align="center">
		<a href="#" class="custom_media_upload" id="manage_vehicle_photos"><input type="button" value="Manage Photos" class="wp-core-ui button-primary" /></a>
	</div>
	<img class="custom_media_image" src="" />
		<input class="custom_media_url" type="hidden" name="attachment_url" value="">
		<input class="custom_media_id" type="hidden" name="attachment_id" id="attachment_id" value="">
		<input type="hidden" name="attachment_post_id" id="attachment_post_id" value="' . $post_id . '">
	';
	$image_list = get_post_meta( $post_id, '_images_value', true );
	$this_car = '';
	$cnt = 1;
	if ( ! empty( $image_list ) ) {
		echo '<h3>' . __( 'Linked Photos', 'car-demon' ) . '</h3><br />';
		$thumbnails = explode( ",", $image_list );
		foreach( $thumbnails as $thumbnail ) {
			$pos = true;
			if( $pos == true ) {
				$photo_array = '<div id="car_photo_' . $cnt . '" name="car_photo_' . $cnt . '" class="car_photo_admin_box">';
					$photo_array .= '<div class="car_photo_remove" onclick="remove_linked_car_image(' . $post_id . ', \'' . trim( $thumbnail ) . '\', ' . $cnt . ')">';
						$photo_array .= 'X';
					$photo_array .= '</div>';
					$photo_array .= '<div align="center">';
						$photo_array .= '<img class="car_demon_thumbs" style="cursor:pointer"' . $popup_imgs . ' src="' . trim( $thumbnail ) . '" width="162" />';
					$photo_array .= '</div>';
				$photo_array .= '</div>';
				$this_car .= $photo_array;
				$cnt = $cnt + 1;
			}
		}
	}
	echo $this_car;
	$this_car = '';
	$thumbnails = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' =>'image', 'orderby' => 'menu_order ID') );
	echo '<h3>'. __( 'Attached Photos', 'car-demon' ) .'</h3><br />';
		$cnt = 0;
		foreach( $thumbnails as $thumbnail ) {
			$guid = wp_get_attachment_url( $thumbnail->ID );
			if ( ! empty( $guid ) ) {
				$photo_array = '<div id="car_photo_' . $cnt . '" name="car_photo_' . $cnt . '" class="car_photo_admin_box">';
					$photo_array .= '<div class="car_photo_remove" onclick="remove_attached_car_image(' . $post_id . ', \'' . $thumbnail->ID . '\', ' . $cnt . ')">';
						$photo_array .= 'X';
					$photo_array .= '</div>';
					$photo_array .= '<div align="center">';
						$photo_array .= '<img class="car_demon_thumbs" style="cursor:pointer"' . $popup_imgs . ' src="' . trim( $guid ) . '" width="162" />';
					$photo_array .= '</div>';
				$photo_array .= '</div>';
				$this_car .= $photo_array;
				++$cnt;
			}
		}
	$this_car = '<div id="car_photo_attachments">' . $this_car . '</div>';	
	echo $this_car;
	return;
}

function cd_ribbons_meta_box( $post ) {
	$post_id = $post->ID;
	$ribbon = get_post_meta( $post_id, '_vehicle_ribbon', true );
	$custom_ribbon_file = get_post_meta( $post_id, '_custom_ribbon', true );
	$no_ribbon = '';
	$custom_ribbon = '';
	$low_price = '';
	$great_deal = '';
	$just_added = '';
	$low_miles = '';
	$brand_new = '';
	if ($ribbon == 'no_ribbon') {
		$no_ribbon = ' selected';
	} elseif ($ribbon == 'custom_ribbon') {
		$custom_ribbon = ' selected';
	} elseif ($ribbon == 'low_price') {
		$low_price = ' selected';
	} elseif ($ribbon == 'great_deal') {
		$great_deal = ' selected';
	} elseif ($ribbon == 'just_added') {
		$just_added = ' selected';
	} elseif ($ribbon == 'low_miles') {
		$low_miles = ' selected';
	} elseif ($ribbon == 'brand_new') {
		$brand_new = ' selected';
	} else {
		update_post_meta( $post_id, '_vehicle_ribbon', 'no_ribbon' );
		$ribbon = 'no_ribbon';
		$no_ribbon = ' selected';
	}
	echo '<input type="hidden" id="this_car_id" name="this_car_id" value="' . $post_id . '" />';
	echo __('Select Ribbon Banner', 'car-demon').' <select name="_vehicle_ribbon" id="_vehicle_ribbon" onchange="update_vehicle_data(this, ' . $post_id . ');update_ribbon(this.value);">
			<option value="no_ribbon"' . $no_ribbon . '>' . __( 'No Ribbon', 'car-demon' ) . '</option>
			<option value="custom_ribbon"' . $custom_ribbon. '>' . __( 'Custom Ribbon', 'car-demon' ) . '</option>
			<option value="low_price"' . $low_price . '>' . __( 'Low Price', 'car-demon' ) . '</option>
			<option value="great_deal"' . $great_deal . '>' . __( 'Great Deal', 'car-demon' ) . '</option>
			<option value="just_added"' . $just_added . '>' . __( 'Just Added', 'car-demon' ) . '</option>
			<option value="low_miles"' . $low_miles . '>' . __( 'Low Miles', 'car-demon' ) . '</option>
			<option value="brand_new"' . $brand_new . '>' . __( 'Brand New', 'car-demon' ) . '</option>
		</select><br />';
	if ( $ribbon != 'custom_ribbon' ) {
		$ribbon = str_replace( '_', '-', $ribbon );
		$ribbon_url = plugins_url() . '/car-demon/theme-files/images/ribbon-' . $ribbon . '.png';
		echo '<img src="' . $ribbon_url . '" id="vehicle_ribbon" name="vehicle_ribbon" /><br />';
		$custom_ribbon_div_class = 'custom_ribbon_div_hide';
	} else {
		echo '<img src="'.$custom_ribbon_file.'" id="vehicle_ribbon" name="vehicle_ribbon" /><br />';	
		$custom_ribbon_div_class = 'custom_ribbon_div';
	}
	echo '<div id="custom_ribbon_div" class="' . $custom_ribbon_div_class . '">';
		echo __( 'Custom Ribbon', 'car-demon' ) . '<br />';
		echo '<input type="text" id="_custom_ribbon" name="_custom_ribbon" value="' . $custom_ribbon_file . '" onchange="update_vehicle_data(this, ' . $post_id . ');" />';
		echo '&nbsp;&nbsp;&nbsp;<input type="button" value="'.__('Upload', 'car-demon').'" id="custom_ribbon_btn" name="custom_ribbon_btn" class="button" />';
	echo '</div>';
	return;
}

function cd_sold_metabox( $post ) {
	$post_id = $post->ID;
	$status = get_post_meta( $post_id, 'sold', true );
	if ( $status == __( 'Yes', 'car-demon') ) {
		$yes = ' selected';
		$no = '';
	} else {
		$no = ' selected';
		$yes = '';
	}
	echo 'Sold <select name="sold" id="sold" onchange=" update_vehicle_data(this, ' . $post_id . ')">
			<option value="' . __( 'No', 'car-demon') . '"' . $no . '>' . __( 'No', 'car-demon') . '</option>
			<option value="' . __( 'Yes', 'car-demon') . '"' . $yes . '>' . __( 'Yes', 'car-demon') . '</option>
		</select>';
	return;
}

function does_vin_exist( $vin ) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$query = "SELECT post_id FROM " . $prefix . "postmeta
		WHERE " . $prefix . "postmeta.meta_key = '_vin_value'
		AND " . $prefix . "postmeta.meta_value = '" . $vin . "'";
	$cars = $wpdb->get_results( sprintf( $query ) );
	if ( ! empty( $cars ) ) {
		foreach ( $cars as $car ) {
			$car_id = $car->post_id;
		}
	} else {
		$car_id = 0;
	}
	return $car_id;
}
?>