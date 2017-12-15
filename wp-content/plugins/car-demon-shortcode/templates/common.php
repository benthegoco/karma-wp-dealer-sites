<?php

if ( ! function_exists( 'get_car_template' ) ) {

	function get_car_template($post_id) {

		global $car_demon_options;

		$template = array();

		$template['post_id'] = $post_id;

	

		$car = car_demon_get_car($post_id);

		$car_contact = get_car_contact($post_id);

		$template['car_contact'] = $car_contact;

	

		//= Find out which of the default fields are hidden

		$template['show_hide'] = get_show_hide_fields();

		//= Get the labels for the default fields

		$template['labels'] = get_default_field_labels();

		$term = get_term_by('name', $car['location'], 'vehicle_location');

		

		if (is_object($term)) {

			$location = $term->slug;

		} else {

			$location = 'default';

		}

	

		if (!isset($template['car_contact']['warranty_url'])) {

			$template['car_contact']['warranty_url'] = get_option($location.'_warranty_url');

		}

		if (!isset($template['car_contact']['make_offer_url'])) {

			$template['car_contact']['make_offer_url'] = get_option($location.'_make_offer_url');

		}

		if (!isset($template['car_contact']['vehicle_contact_url'])) {

			$template['car_contact']['vehicle_contact_url'] = get_option($location.'_vehicle_contact_url');

		}

		

		$template['car'] = $car;

	

		$cdsp_pluginpath = trailingslashit(plugins_url()).trailingslashit('car-demon-shortcode');

		$template['cdsp_pluginpath'] = $cdsp_pluginpath;

		$template['pluginpath'] = $cdsp_pluginpath;

		$template['condition'] = ucfirst($car['condition']);

		$title = get_car_title($post_id);

		$template['title'] = $title;

		$link = get_permalink($post_id);

		$template['link'] = $link;

		if (isset($car_demon_options['use_compare'])) {

			$use_compare = $car_demon_options['use_compare'];

		} else {

			$use_compare = '';

		}

		if ($use_compare == __('Yes', 'car-demon')) {

			$in_compare = '';

			if (isset($_SESSION['car_demon_compare'])) {

				$compare_list = $_SESSION['car_demon_compare'];

			} else {

				$compare_list = '';

			}

			$compare_these = explode(',',$compare_list);

			if (in_array($post_id,$compare_these)) {

				$in_compare = ' checked="checked"';

			}

			$template['compare'] = '<input'.$in_compare.' class="compare_input" id="compare_'.$post_id.'" type="checkbox" onclick="update_car('.$post_id.',this);" />';

			$template['compare'] .= '<div class="vehicle_compare_label">'.__('Compare', 'car-demon-shortcode').'</div>';

		} else {

			$template['compare'] = '';	

		}

		

		$template['price_box'] = get_vehicle_price_shortcode($post_id);

		$price = $car['price'];

		$template['price'] = number_format((int)$price); //RS: remove trailing zeros

		$template['price'] = apply_filters( 'cd_price_format', $template['price'] );

	

		$main_image_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'vehicle-srp' );

		$main_image = $main_image_arr[0];

	//	$main_image = cd_main_photo( $post_id );

	

		if ( ! $main_image|| empty( $main_image) ) {

			$main_image = trailingslashit( plugins_url() ) . trailingslashit( 'car-demon' ) . trailingslashit( 'images' ) . 'photo-coming-soon.png';

		}

	

		$template['main_image'] = $main_image;

	

		if ( defined( 'CD_CUSTOM_NO_PHOTO' ) ) {

			$img_output = "<img onclick='window.location=\"".$link."\";' title='Click for price on this ".$title."' onerror='cdsImgError(this, \"". CD_CUSTOM_NO_PHOTO . "\");' class='main_photo_pro_img' src='";

		} else {

			$img_output = "<img onclick='window.location=\"".$link."\";' title='Click for price on this ".$title."' onerror='ImgError(this, \"no_photo.gif\");' class='main_photo_pro_img' src='";

		}

	

			$img_output .= $main_image;

		$img_output .= "' />";

		$template['img_output'] = $img_output;

		$ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);

		if (empty($ribbon)) {

			$ribbon = 'no-ribbon';		

		}

		if ($ribbon != 'custom_ribbon') {

			$ribbon = str_replace('_', '-', $ribbon);

			$template['current_ribbon'] = '<img class="similar_car_ribbon" src="'. $cdsp_pluginpath .'images/ribbon-'.$ribbon.'.png" width="76" height="76" alt="New Ribbon" id="ribbon">';

		} else {

			$custom_ribbon_file = get_post_meta($post_id, '_custom_ribbon', true);

			$template['current_ribbon'] = '<img class="similar_car_ribbon" src="'.$custom_ribbon_file.'" width="76" height="76" alt="New Ribbon" id="ribbon">';

		}

		if (isset($car_demon_options['dynamic_ribbons'])) {

			if ($car_demon_options['dynamic_ribbons'] == 'Yes') {

				$template['current_ribbon'] = car_demon_dynamic_ribbon_filter($template['current_ribbon'], $post_id, '76');

			}

		}

		$template['social_facebook_link'] = 'https://www.facebook.com/sharer/sharer.php?u='.$link;

		$template['social_twitter_link'] = 'https://twitter.com/home?status='.$link;

		$template['social_gplus_link'] = 'https://plus.google.com/share?url='.$link;

		$template['social_linkedin_link'] = 'https://www.linkedin.com/shareArticle?mini=true&url='.$link.'&title='.$title.'&summary=&source=';

		$template['social_pintrest_link'] = 'https://pinterest.com/pin/create/button/?url='.$link.'&media='.$main_image[0].'&description='.$title;

	

		$attachments = get_children( array( 'post_parent' => $post_id ) );

		$count = count( $attachments );

		$image_list = get_post_meta($post_id, '_images_value', true);

		if (!empty($image_list)) {

			$thumbnails = explode(",",$image_list);

			$count = $count + count($thumbnails);

		}

		$template['car']['photos_count'] = $count;

	

		$page_data = get_page( $post_id );

		$excerpt = strip_tags($page_data->post_excerpt);

	

		$template['car']['excerpt'] = substr( $excerpt, 0, 40 );

	

		if ( function_exists( 'cd_color_map' ) ) {

			$template['car']['color_swatch'] = cd_color_map( $template['car']['exterior_color'] );

		} else {

			$template['car']['color_swatch'] = '';

		}

	

		return $template;

	}

}



function get_template_price($post_id, $template) {

	$vehicle_location = get_cd_term( $post_id, 'vehicle_location' );

	if ($vehicle_location == '') {

		$vehicle_location = __('Default', 'car-demon');

		$vehicle_location_slug = __('default', 'car-demon');

	} else {

		$vehicle_location_term = get_term_by('name', $vehicle_location, 'vehicle_location');

		$vehicle_location_slug = $vehicle_location_term->slug;

		$vehicle_condition = get_cd_term( $post_id, 'vehicle_condition' );

	}



	$vehicle_condition = get_cd_term( $post_id, 'vehicle_condition' );

	if ($vehicle_condition == 'New') {

		$show_price = get_option($vehicle_location_slug.'_show_new_prices');

	} else {

		$show_price = get_option($vehicle_location_slug.'_show_used_prices');

	}



	global $car_demon_options;

	$is_sold = get_post_meta($post_id, 'sold', true);

	$spacer = '';

	if (isset($car_demon_options['currency_symbol'])) {

		$currency_symbol = $car_demon_options['currency_symbol'];

	} else {

		$currency_symbol = "$";

	}

	if (isset($car_demon_options['currency_symbol_after'])) {

		$currency_symbol_after = $car_demon_options['currency_symbol_after'];

		/*

		if (!empty($currency_symbol_after)) {

			$currency_symbol = "";

		}

		*/

	} else {

		$currency_symbol_after = "";

	}	

	if ($is_sold == __( 'Yes', 'car-demon' )) {

		$template['price'] = __('SOLD', 'car-demon-shortcode');

	}

	

	$x = '';



	//= Find out which of the default fields are hidden

	$show_hide = get_show_hide_fields();

	if ( $show_price == __( 'Yes', 'car-demon' ) ) {

		if (isset($template['car']['msrp'])) {

			if (!empty($template['car']['msrp'])) {

				if ($show_hide['retail'] != true) {

					$x .= '<div class="car_price_retail">'.$template['labels']['retail'].': '.$currency_symbol.$template['car']['msrp'].$currency_symbol_after.'</div>';

				}

			}

		}

		if (isset($template['car']['discount'])) {

			if (!empty($template['car']['discount'])) {

				if ($show_hide['discount'] != true) {

					$x .= '<div class="car_price_discount">'.$template['labels']['discount'].': '.$currency_symbol.$template['car']['discount'].$currency_symbol_after.'</div>';

				}

			}

		}

		if (isset($template['car']['rebates'])) {

			if (!empty($template['car']['rebates'])) {

				if ($show_hide['rebates'] != true) {

					$x .= '<div class="car_price_rebate">'.$template['labels']['rebates'].': '.$currency_symbol.$template['car']['rebate'].$currency_symbol_after.'</div>';

				}

			}

		}

		if ($show_hide['price'] == true) {

			if ($vehicle_condition == __('New', 'car-demon')) {

				$x = '<div class="car_final_price_style">'.get_option($vehicle_location_slug.'_no_new_price').'</div>';

			} else {

				$x = '<div class="car_final_price_style">'.get_option($vehicle_location_slug.'_no_used_price').'</div>';

			}

		} else {

			if ($template['car']['price'] == 0) {

				if ($vehicle_condition == __('New', 'car-demon')) {

					$x = '<div class="car_final_price_style">'.get_option($vehicle_location_slug.'_no_new_price').'</div>';

				} else {

					$x = '<div class="car_final_price_style">'.get_option($vehicle_location_slug.'_no_used_price').'</div>';

				}

			} else {

				$x .= '<div id="your_price_text" class="car_your_price_style">'.$template['labels']['price'].'</div>';

				$x .= '<div id="your_price" class="car_final_price_style">'.$currency_symbol.$template['car']['price'].$currency_symbol_after.'</div>';

			}

		}

	} else {

		if ($vehicle_condition == __('New', 'car-demon')) {

			$x = '<div class="car_final_price_style">'.get_option($vehicle_location_slug.'_no_new_price').'</div>';

		} else {

			$x = '<div class="car_final_price_style">'.get_option($vehicle_location_slug.'_no_used_price').'</div>';

		}

	}

	return $x;

}



//= Beta Popup button for Style #5

if ( defined( 'CD_STYLE_5_POPUP' ) ) {

	

	add_filter( 'cds_contact_link', 'my_cds_contact_link', 10, 3 );

	function my_cds_contact_link( $contact_url, $post_id, $template ) {

		$x = '<div class="car_email cds_contact_form_btn" onclick="cds_contact_form_btn(this);" data-post-id="' . $post_id . '">' . CD_SHORTCODE_CONTACT_LABEL . '</div>';

		$x .= '<div class="cds_contact_form_5" id="cds_contact_form_5_' . $post_id . '">';

		$contact_form = car_demon_display_vehicle_contacts($post_id, '', '', '', '');

		$contact_form = str_replace( 'id="contact_form"', 'id="contact_form_' . $post_id . '"', $contact_form );

		$contact_form = str_replace( 'contact_msg', 'contact_msg_tmp', $contact_form );

		$contact_form = str_replace( 'cc', 'cc_tmp', $contact_form );

		$contact_form = str_replace( 'send_receipt', 'send_receipt_tmp', $contact_form );

		$contact_form = str_replace( 'send_to_name', 'send_to_name_tmp', $contact_form );

		$contact_form = str_replace( 'send_to', 'send_to_tmp', $contact_form );

		$contact_form = str_replace( 'car_id', 'car_id_tmp', $contact_form );

		$contact_form = str_replace( 'vehicle_vin', 'vehicle_vin_tmp', $contact_form );

		$contact_form = str_replace( 'vehicle_year', 'vehicle_year_tmp', $contact_form );

		$contact_form = str_replace( 'vehicle_make', 'vehicle_make_tmp', $contact_form );

		$contact_form = str_replace( 'vehicle_model', 'vehicle_model_tmp', $contact_form );

		$contact_form = str_replace( 'vehicle_condition', 'vehicle_condition_tmp', $contact_form );

		$contact_form = str_replace( 'vehicle_location', 'vehicle_location_tmp', $contact_form );

		$contact_form = str_replace( 'vehicle_stock_number', 'vehicle_stock_number_tmp', $contact_form );

		$contact_form = str_replace( 'vehicle_photo', 'vehicle_photo_tmp', $contact_form );

		$contact_form = str_replace( 'nonce', 'nonce_tmp', $contact_form );

		$contact_form = str_replace( 'cd_name', 'cd_name_tmp', $contact_form );

		$contact_form = str_replace( 'cd_phone', 'cd_phone_tmp', $contact_form );

		$contact_form = str_replace( 'cd_email', 'cd_email_tmp', $contact_form );

		$contact_form = str_replace( 'contact_needed', 'contact_needed_tmp', $contact_form );

	

		$x .= $contact_form;

		$x .= '</div>';

		return $x;

	}

	

	add_action ( 'car_demon_before_main_content', 'my_car_demon_before_main_content' );

	function my_car_demon_before_main_content() {

		global $car_demon_options;

		wp_register_script( 'car-demon-contact-widget-js', CAR_DEMON_PATH . '/widgets/js/car-demon-vehicle-contact-widget.js', array(), CAR_DEMON_VER );

		$validate_phone = 0;

		if ( isset( $car_demon_options['validate_phone'] ) ) {

			if ( $car_demon_options['validate_phone'] == __('Yes', 'car-demon') ) {

				$validate_phone = 1;

			}

		}

		wp_localize_script( 'car-demon-contact-widget-js', 'cdContactWidgetParams', array( 

			'ajaxurl' => admin_url( 'admin-ajax.php' ),

			'error1' => __( 'You must enter your name.', 'car-demon' ),

			'error2' => __( 'You must enter your name.', 'car-demon' ),

			'error3' => __( 'You must enter a valid Phone Number.', 'car-demon' ),

			'error4' => __( 'The phone number you entered is not valid.', 'car-demon' ),

			'error5' => __( 'You did not select who you want to send this message to.', 'car-demon' ),

			'error6' => __( 'You did not enter a message to send.', 'car-demon' ),

			'form_js' => apply_filters('car_demon_mail_hook_js', '', 'contact_us_vehicle', 'unk' ),

			'form_data' => apply_filters('car_demon_mail_hook_js_data', '', 'contact_us_vehicle', 'unk' ),

			'sending_msg' => __( 'Please wait while your message is sent.', 'car-demon' ),

			'spinner' => CAR_DEMON_PATH . 'images/wpspin_light.gif',

			'validate_phone' => $validate_phone,

			'path_url' => CAR_DEMON_PATH,

		));

		

		wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(

			'error1' => __( 'You didn\'t enter an email address.', 'car-demon' ),

			'error2' => __( 'Please enter a valid email address.', 'car-demon' ),

			'error2' => __( 'The email address contains illegal characters.', 'car-demon' )



		));

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'car-demon-common-js' );

		wp_enqueue_script( 'car-demon-contact-widget-js' );

	

		$post_id = '';

		$list_phone = '';

		$cc = '';

		$send_receipt = '';

		$send_receipt_msg = '';

		$x = '

			<div class="car_demon_light_box" id="car_demon_light_box_' . $post_id . '">

				<div class="car_demon_contact_box" id="car_demon_contact_box"">

					<a name="cds_lightbox"></a>

					<div class="close_contact_box" onclick="cds_close_contact_box();">(close) X</div>

					<div id="car_demon_light_box_main">



					</div>

				</div>

			</div>';

		echo $x;

	}

}



function cdsp_tag_filter( $post_id, $content ) {

	$car_options = cd_get_car( $post_id );

	$car_contact = get_car_contact( $post_id );

	$car_options = array_merge( $car_options, $car_contact );

	/*

	echo '<pre>';

		print_r($car_options);

	echo '</pre>';

	*/

	if ( is_array( $car_options ) ) {

		foreach( $car_options as $car_option=>$value ) {

			$content = str_replace( '{' . $car_option . '}', $value, $content );

			$content = str_replace( '[' . $car_option . ']', $value, $content );

			if ( strpos( $car_option, 'decoded_' ) !== false ) {

				unset( $car_options[ $car_option ] );

			}

			$car_option = str_replace( 'decoded_', '', $car_option );

			$content = str_replace( '{' . $car_option . '}', $value, $content );

			$content = str_replace( '[' . $car_option . ']', $value, $content );

		}

	}



	$tags = '<pre>' . print_r( $car_options, true ) . '</pre>';



	$content = str_replace( '{tags}', $tags, $content );



	return $content;	

}



function cdsp_filter_widgets( $content ) {

	global $post;

	

	if ( ! is_single() ) {

		return $content;

	}

	

	if ( is_object( $post ) ) {

		$post_type = $post->post_type;

		

		if ( $post_type == 'cars_for_sale' ) {

			$content = cdsp_tag_filter( $post->ID, $content );

		}

	}



	return $content;

}



add_filter( 'widget_text', 'cdsp_filter_widgets', 10, 1 );

?>