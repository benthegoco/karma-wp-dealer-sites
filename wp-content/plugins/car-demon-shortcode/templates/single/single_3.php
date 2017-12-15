<?php
function cd_single_3( $post_id ) {
	wp_enqueue_script('cd-pro-single-3', plugins_url() . '/car-demon-shortcode/js/cd-pro-single-3.js', 'jquery');

	global $car_demon_options;
	$template = get_car_template( $post_id );

	$post = get_post( $post_id );
	$content = $post->post_content;
	$content = wpautop( $content, true );

	$template['car']['content'] = $content;

	/*
	echo '<pre>';
		print_r($template);
	echo '</pre>';
	*/

	$details = cd_3_details( $template );
	$vin_query_decode = get_post_meta($post_id, "decode_string", true);
	$options = cds_3_options( $post_id );
	$about = cd_3_about( $post_id );

	$is_sold = get_post_meta($post_id, 'sold', true);
	$spacer = '';
	if (isset($car_demon_options['currency_symbol'])) {
		$currency_symbol = $car_demon_options['currency_symbol'];
	} else {
		$currency_symbol = "$";
	}
	if (isset($car_demon_options['currency_symbol_after'])) {
		$currency_symbol_after = $car_demon_options['currency_symbol_after'];
		if (!empty($currency_symbol_after)) {
			$currency_symbol = "";
		}
	} else {
		$currency_symbol_after = "";
	}
	if ($is_sold == "Yes") {
		$template['price'] = __("SOLD", 'car-demon-shortcode');
	}

	if ($template['car']['price'] == 0) {
		$template['car']['price'] = '
			<div id="your_price" class="car_final_price_style">'.$template['price_box'].'</div>
		';
	} else {
		$template['car']['price'] = '
			<div id="your_price" class="car_final_price_style">'.$currency_symbol.$template['price'].'<span class="cd_change">.00</span>'.$currency_symbol_after.'</div>
		';
	}
	
	$top_sidebar = cdsp_pro_get_vst_sidebar( $post_id );
	$top_sidebar = str_replace( '{finance_link}', $template['car_contact']['finance_url'], $top_sidebar );
	$top_sidebar = str_replace( '{trade_link}', $template['car_contact']['trade_url'], $top_sidebar );

	$bottom_sidebar = cdsp_pro_get_vsb_sidebar( $post_id );
	$bottom_sidebar = str_replace( '{finance_link}', $template['car_contact']['finance_url'], $bottom_sidebar );
	$bottom_sidebar = str_replace( '{trade_link}', $template['car_contact']['trade_url'], $bottom_sidebar );

	$cd_3_width = '';
	
	if (empty($bottom_sidebar)) {
		$cd_3_width = ' cd_fullwidth';
	}
	
	$show_similar_cars = 'Yes';
	$similar_cars = '';
	if (isset($car_demon_options['show_similar_cars'])) {
		$show_similar_cars = $car_demon_options['show_similar_cars'];
	}		
	if (isset($template['car']['body_style'])) {
		if ($show_similar_cars == 'Yes') {
			$similar_cars = car_demon_display_similar_cars($template['car']['body_style'], $post_id); 
		}
	}
	
	$html = '
		<div class="cd_single_car_3">
			<div class="header">
				<div class="title_bar">
					<div class="title">
						'. $template['car']['title'] .'
					</div>

					<div class="car_excerpt">
						 '.$template['car']['excerpt'].'
					</div>';

					if ($template['show_hide']['vin'] != true) {
						$html .= '<div class="description_label_vin">'.$template['labels']['vin'].': </div><div class="vin_value">'.$template['car']['vin'].'</div>';
					}

					if ($template['show_hide']['stock_number'] != true) {
						$html .= '<div class="description_label stock">'.$template['labels']['stock_number'].':</div>';
						$html .= '<div class="description_text stock">'.$template['car']['stock_number'].'</div>';
					}

					$html .= '<div class="social">';

					$social = '
						<div class="cd2_social fb" data-link="'. $template['social_facebook_link'] .'"></div>
						<div class="cd2_social twitter" data-link="'. $template['social_twitter_link'] .'"></div>
						<div class="cd2_social gplus" data-link="'. $template['social_gplus_link'] .'"></div>
						<div class="cd2_social linkedin" data-link="'. $template['social_linkedin_link'] .'"></div>
						';
					
					$social = apply_filters( 'cd_single_3_social', $social );
					
				$html .= $social . '</div>
				</div><!--end title bar-->
				
				<div class="photo_box">
					<div class="main_photo_box">
						<img class="main_photo" src="'. $template['car']['main_photo'] .'" alt="'. $template['car']['title'] .'" title="'. $template['car']['title'] .'" />
					</div>
				</div><!--end photo box-->
				<div class="thumbnails">
					'. cd_3_thumbnails( $post_id ) .'
				</div>
			</div><!--end header-->
			
			<div class="break_line"></div>';
			
            $html .= '<div class="description_left">';
				//$html .= get_template_price($post_id, $template);
				$html .= $template['car']['price'];
				if ( $template['car']['price'] != '0' ) {
					$html .= '<div class="price_details">';
						$html .= '+ taxes & licensing';
					$html .= '</div>';
				}
			$html .= '</div>';

            $html .= '<div class="description_right">';
				if ($template['show_hide']['mileage'] != true) {
					$html .= '<div class="odometer">Odometer:</div>';
					$html .= '<div class="description_text mileage">'.$template['car']['mileage'].'</div>';
					$html .= '<div class="description_label mileage">'.$template['labels']['mileage'].'</div>';
				}
				
				//= is certified
				$certified = has_term( 'certified', 'vehicle_condition', $post );
				if ( $certified == true ) {
					$html .= '<div class="certified"><img src="' . plugins_url() . '/car-demon-shortcode/images/thumbs_up.jpg" /> Certified + E-Tested</div>';
				}
				
            $html .= '</div>';

			$html .= '<div class="break_line"></div>';
			
			$html .= '<div class="cd_content">
				<div class="cd_single_sidebar_top">
					'. $top_sidebar .'
				</div>';

				if ( $certified == true ) {
					$html .= '<div class="certified_alert">
								<div class="certified">
									<img src="' . plugins_url() . '/car-demon-shortcode/images/thumbs_up_alert.jpg" /> Certified + E-Tested
								</div>
								<div class="certified_note">
									This vehicle is Safety Certified and E-Tested.
								</div>
							</div>';
				}

				$html .= $details;

				$html .= '<div class="cd_content_left' .$cd_3_width. '">
					
					'. $about .'
					<div class="cd_single_item">
						<div class="cd_single_item_content">
							'. $options .'
						</div>
					</div><!--end single item-->
		
					
				</div><!--end cd_content_left-->
				<div class="cd_content_right' .$cd_3_width. '">
					'. $bottom_sidebar .'
				</div><!--end cd_content_right-->
			</div><!--end cd_content-->
			<div class="similar_cars">
				'. $similar_cars .'
			</div>
		</div>
		<div class="cd_clear"></div>
	';

	return $html;
}

function cd_3_thumbnails( $post_id ) {
	// Get array of thumbnails
	$thumbnails = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' =>'image', 'orderby' => 'menu_order ID') );
	// Create variable to store our thumbnail HTML
	$thumbnail_html = '';
	// Reverse the order of the thumbnails
	$thumbnails = array_reverse($thumbnails);
	$thumb_cnt = count($thumbnails);
	// Build HTML for each thumbnail
	foreach($thumbnails as $thumbnail) {
		$guid_full = wp_get_attachment_url( $thumbnail->ID );
		$guid = wp_get_attachment_image_src( $thumbnail->ID, 'car-thumbs' );
		if (is_array($guid)) {
			if (isset($guid[0])) {
				$thumbnail_html .= '<div class="cd_thumbnail_div"><img data-full-sized="'.$guid_full.'" class="cd_thumbnail_img" src="'.trim($guid[0]).'" /></div>';
			}
		}
	}
	// Check if vehicle has a list of photo urls that arent part of the normal gallery
	$image_list = get_post_meta($post_id, '_images_value', true);
	$cnt = 0;
	if (!empty($image_list)) {
		$thumbnails = explode(",",$image_list);
		$thumb_cnt = $thumb_cnt + count($thumbnails);
		foreach($thumbnails as $thumbnail) {
			$thumbnail = trim($thumbnail);
			if (empty($thumbnail)) {
				continue;	
			}
			if ($cnt > 0) {
				$thumbnail_html .= '<div class="cd_thumbnail_div"><img data-full-sized="'.$thumbnail.'" class="cd_thumbnail_img" src="'.trim($thumbnail).'" /></div>';
			}
			$cnt = $cnt + 1;
		}
	}
	
	$html = '
		<input type="hidden" value="0" name="vehicle_image_count" id="vehicle_image_count" />
		<div id="single-car-thumbnail-box-container">
			<div id="bsw_go_back_thumb" class="car_thumbnails_left" data-position="0">
				<div class="arrow-left">
					<div class="arrow-left-little"></div>
				</div>
			</div>
			
			<div class="thumb-slider">

				<div class="nohor car_thumbnails_hor slides_wrap cdsp_thumbs_box" id="car_demon_thumbs" data-total="'. $thumb_cnt .'" data-left="0" data-right="'. $thumb_cnt .'">
					'. $thumbnail_html .'
				</div>
	
			</div>

			<div id="btw_go_forward_thumb" class="car_thumbnails_right" data-position="0">
				<div class="arrow-right">
					<div class="arrow-right-little"></div>
				</div>
			</div>

		</div>
	';
	return $html;
}

function cd_3_details( $template ) {
	$odd_even = 'odd';
	$html = '
		<div class="cd2_single_details">
			<ul>';
				if ($template['show_hide']['year'] != true) {
					if ( ! empty( $template['car']['year'] ) ) {
						$html .='<li class="' . $odd_even .'"><label>'.$template['labels']['year'].'</label> '.$template['car']['year'].'</li>';
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
				if ($template['show_hide']['make'] != true) {
					if ( ! empty( $template['car']['make'] ) ) {
						$html .='<li class="' . $odd_even .'"><label>'.$template['labels']['make'].'</label> '.$template['car']['make'].'</li>';
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
				if ($template['show_hide']['model'] != true) {
					if ( ! empty( $template['car']['model'] ) ) {
						$html .='<li class="' . $odd_even .'"><label>'.$template['labels']['model'].'</label> '.$template['car']['model'].'</li>';
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
				if ($template['show_hide']['body_style'] != true) {
					if ( ! empty( $template['car']['body_style'] ) ) {
						$html .='<li class="' . $odd_even .'"><label>'.$template['labels']['body_style'].'</label> '.$template['car']['body_style'].'</li>';
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
				if ($template['show_hide']['condition'] != true) {
					if ( ! empty( $template['car']['condition'] ) ) {
						$html .='<li class="' . $odd_even .'"><label>'.$template['labels']['condition'].'</label> '.$template['car']['condition'].'</li>';
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
				if ($template['show_hide']['mileage'] != true) {
					if ( ! empty( $template['car']['mileage'] ) ) {
						$html .='<li class="' . $odd_even .'"><label>'.$template['labels']['mileage'].'</label> '.$template['car']['mileage'].'</li>';
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
				if ($template['show_hide']['stock_number'] != true) {
					if ( ! empty( $template['car']['stock_number'] ) ) {
						$html .='<li class="' . $odd_even .'"><label>'.$template['labels']['stock_number'].'</label> '.$template['car']['stock_number'].'</li>';
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
				if ($template['show_hide']['vin'] != true) {
					if ( ! empty( $template['car']['vin'] ) ) {
						$html .='<li class="' . $odd_even .'"><label>'.$template['labels']['vin'].'</label> '.$template['car']['vin'].'</li>';
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
				if (isset($template['car']['exterior_color'])) {
					if (!empty($template['car']['exterior_color'])) {
						$html .='<li class="' . $odd_even .'"><label>'.__('Color:', 'car-demon-shortcode').'</label> '.$template['car']['exterior_color'];
						if (isset($template['car']['interior_color'])) {
							if (!empty($template['car']['interior_color'])) {
								$html .= '/'.$template['car']['interior_color'].'</li>';
							} else {
								$html .= '</li>';
							}
						} else {
							$html .= '</li>';
						}
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
				if (isset($template['car']['engine'])) {
					if (!empty($template['car']['engine'])) {
						$html .='<li class="' . $odd_even .'"><label>'.__('Engine:', 'car-demon-shortcode').'</label> '.$template['car']['engine'].'</li>';
						if ( $odd_even == 'odd' ) { $odd_even = 'even'; } else { $odd_even = 'odd'; }
					}
				}
			$html .='</ul>';
			$html .= '<div class="car_content">';
			
				$html .= '<div class="content_description_label">';
					$html .= __( 'Description', 'car-demon-shortcode' );
				$html .= '</div>';
			
				$html .= $template['car']['content'];
			$html .= '</div>';
		$html .= '</div>';
	return $html;
}

function cd_3_vehicle_options( $post_id ) {
	$vehicle_options_list = get_post_meta($post_id, '_vehicle_options', true);
	$vehicle_options_array = explode(',',$vehicle_options_list);
	$options_image = '<img class="custom_option_img" src="'.plugins_url() . '/car-demon/theme-files/images/opt_standard.gif" />';

	$vehicle_options = '<table class="decode_table">';
	$vehicle_options .= '<tr class="decode_table_header">
							<td><strong>'.__('Vehicle Options','car-demon').'</strong></td>
							<td></td>
						  </tr>';
	foreach ($vehicle_options_array as $vehicle_option) {
		if (!empty($vehicle_option)) {
			if ($flag == true) {
				$class = 'decode_table_even';
				$flag = false;
			} else {
				$class = 'decode_table_odd';
				$flag = true;
			}
			$vehicle_options .= '<tr class="'.$class.'">
				<td class="decode_table_label">'.$vehicle_option.'</td>
				<td>'.$options_image.'</td>
				</tr>';
		}
	}
	$vehicle_options .= '</table>';	
	return $vehicle_options;
}

function cd_3_about( $post_id ) {
	$x = '';
	$html = '';
	if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  
		$x .= '<div id="entry-author-info">';
		if (isset($_COOKIE['sales_code'])) {
			if ($_COOKIE['sales_code']) {
				$user_id = $_COOKIE['sales_code'];
				$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
				$location_approved = 0;
				if ($vehicle_location == $user_location) {
					$location_approved = 1;
				} else {
					$location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
				}
			}
		}
		if (empty($location_approved)) {
			$location_approved = 0;
			$user_sales_type = 0;
		}
		if ($location_approved == 1) {
			$user_sales_type = 0;
			if ($vehicle_condition == 'New') {
				$user_sales_type = get_the_author_meta('lead_new_cars', $user_id);	
			}
			else {
				$user_sales_type = get_the_author_meta('lead_used_cars', $user_id);		
			}
		}
		if ($user_sales_type == 1) {
			$x .= build_user_hcard($_COOKIE['sales_code'], 1);
		}
		else {
			$x .= build_location_hcard($vehicle_location, $vehicle_condition);
		} 
		$x .= html_entity_decode(get_about_us_tab($post_id).'test');
	else:
		$x .= get_about_us_tab($post_id);
	endif;

	if (!empty($x)) {
		$html = '
			<div class="cd_single_item">
				<div class="cd_single_item_title_box">
					<div class="cd_single_item_title_icon_questions">
					</div>
					<div class="cd_single_item_title">
						'. __('Frequently Asked Questions', 'car-demon-shortcode') .'
					</div>
					<div class="cd_single_item_title_arrow cd_arrow_down">
					</div>
				</div>
				<div class="cd_single_item_content cd_closed">
					'. $x .'
				</div>
			</div><!--end single item-->
		';
	}
	
	return $html;
}

function cd_3_buttons( $template, $post_id ) {
	$cdsp_theme_options = cdsp_theme_options();
	$cdsp_pro_pluginpath = plugins_url() . '/car-demon-shortcode/';
	$html = '';
	
	if (!empty($template['car_contact']['warranty_url'])) {
		$html .= '<a href="'.$template['car_contact']['warranty_url'] .'?stock_num='.$template['car']['stock_number'].'&sales_code='.$template['car_contact']['sales_code'].'"><img src="'.$cdsp_theme_options['warranty_button'].'" id="vehicle_warranty_img_'.$post_id.'" class="vehicle_button_img" /></a>';
	}
	if (!empty($template['car_contact']['finance_url'])) { 
		if ($template['car_contact']['finance_popup'] == 'Yes') {
			$html .= '<a onclick="window.open(\''.$template['car_contact']['finance_url'] .'?stock_num='.$template['car']['stock_number'].'&sales_code='. $template['car_contact']['sales_code'].'\',\'finwin\',\'width='.$template['car_contact']['finance_width'].', height='.$template['car_contact']['finance_height'].', menubar=0, resizable=0\')">
				<img src="'.$cdsp_pro_pluginpath.$cdsp_theme_options['finance_button'].'" id="vehicle_finance_img_'.$post_id.'" class="vehicle_button_img" />
				</a>';
		} else {
			$html .= '<a href="'.$template['car_contact']['finance_url'].'?stock_num='.$template['car']['stock_number'].'&sales_code='. $template['car_contact']['sales_code'].'"><img src="'.$cdsp_theme_options['finance_button'].'" id="vehicle_button_img_'.$post_id.'" class="vehicle_button_img" /></a>';
		}
	}
	if (!empty($template['car_contact']['trade_url'])) {
		$html .= '<a href="'.$template['car_contact']['trade_url'] .'?stock_num='.$template['car']['stock_number'].'&sales_code='.$template['car_contact']['sales_code'].'"><img src="'.$cdsp_theme_options['trade_button'].'" id="vehicle_trade_img_'.$post_id.'" class="vehicle_button_img" /></a>';
	}
	
	if (!empty($html)) {
		$html = '<div class="cd_contact_buttons">'. $html .'</div>';
	}
	
	wp_register_script('car-demon-contact-widget-js', plugins_url() . '/car-demon/widgets/js/car-demon-vehicle-contact-widget.js');
	$validate_phone = 0;
	if (isset($car_demon_options['validate_phone'])) {
		if ($car_demon_options['validate_phone'] == 'Yes') {
			$validate_phone = 1;
		}
	}
	wp_localize_script( 'car-demon-contact-widget-js', 'cdContactWidgetParams', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'error1' => __('You must enter your name.', 'car-demon'),
		'error2' => __('You must enter your name.', 'car-demon'),
		'error3' => __('You must enter a valid Phone Number.', 'car-demon'),
		'error4' => __('The phone number you entered is not valid.', 'car-demon'),
		'error5' => __('You did not select who you want to send this message to.', 'car-demon'),
		'error6' => __('You did not enter a message to send.', 'car-demon'),
		'form_js' => apply_filters('car_demon_mail_hook_js', '', 'contact_us_vehicle', 'unk'),
		'form_data' => apply_filters('car_demon_mail_hook_js_data', '', 'contact_us_vehicle', 'unk'),
		'validate_phone' => $validate_phone,
		'path_url' => CAR_DEMON_PATH
	));
	wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
		'error1' => __('You didn\'t enter an email address.', 'car-demon'),
		'error2' => __('Please enter a valid email address.', 'car-demon'),
		'error2' => __('The email address contains illegal characters.', 'car-demon')
	));
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'car-demon-common-js' );
	wp_enqueue_script( 'car-demon-contact-widget-js' );

	$defaults = array(
		'list_phone' => __('No', 'car-demon'),
		'cc' => '',
		'send_receipt' => __('No', 'car-demon'),
		'send_receipt_msg' => '',
	 );

	$phone = $template['car_contact']['sales_phone'];

	if (!empty($phone)) {
		$html .= '<div class="call_us">';
			$html .= __('Call Us at ', 'car-demon-shortcode');
			$html .= $phone;
		$html .= '</div>';
	}

	$html .= '<div class="cd_email_btn_box">';
		$html .= '<div class="contact_us_widget_btn" data-hide-text="'. __('Hide Email Form', 'car-demon-shortcode') .'">';
			$html .= __('Email Us Here', 'car-demon-shortcode');
		$html .= '</div>';
	
		$html .= '<div class="email_friend_btn" data-hide-text="'. __('Hide Friend Form', 'car-demon-shortcode') .'">';
			$html .= __('Email to Friend', 'car-demon-shortcode');
		$html .= '</div>';
	$html .= '</div>';
		
	$html .= '<div class="cd_clear"></div>';
	
	$html .= '<div class="contact_us_widget">';
		$html .= car_demon_display_vehicle_contacts($post_id, $defaults['list_phone'], $defaults['cc'], $defaults['send_receipt'], $defaults['send_receipt_msg']);
	$html .= '</div>';
	
	$email_friend = car_demon_email_a_friend($post_id, $template['car']['stock_number']);
	$email_friend = str_replace('_tmp', '', $email_friend);
	$email_friend = str_replace('search_btn ef_search_btn', 'cdg_email_friend_btn', $email_friend);
	
	$html .= $email_friend;
	
	return $html;
}

function cds_3_options( $post_id ) {

	$safety = cds_3_get_option_tab( 'safety', $post_id );
	$convienience = cds_3_get_option_tab( 'convenience', $post_id );
	$comfort = cds_3_get_option_tab( 'comfort', $post_id );
	$entertainment = cds_3_get_option_tab( 'entertainment', $post_id );

	$x = '<table>';
		$x .= $safety;
		$x .= $convienience;
		$x .= $comfort;
		$x .= $entertainment;
	$x .= '</table>';

	return $x;
}

function cds_3_get_option_tab( $tab, $post_id ) {
	$vin_query_decode_array = get_post_meta( $post_id, 'decode_string' );
	if ( $vin_query_decode_array ) {
		$vin_query_decode = $vin_query_decode_array[0];
	} else {
		$vin_query_decode = '';
	}
	$vehicle_option_array = get_post_meta( $post_id, '_vehicle_options', true );

	$vehicle_option_array = explode( ',', $vehicle_option_array );
	$map = cd_get_vehicle_map();

	$arrow = '<img src="' . plugins_url() . '/car-demon-shortcode/images/arrow_r.png" class="option_arrow" />';

	$x = '<tr>';
		$x .= '<td class="option_label">';
			$x .= $tab;
		$x .= '</td>';
	$x .= '</tr>';
	
	$cnt = 0;

	if ( isset( $map[$tab] ) ) {
		foreach( $map[$tab] as $tab_group => $value ) {
			//= Loop through all of the tab option groups, get their items
			$div_flag = false;

			$group_slug = cd_clean_cap_slug( $tab_group );

			if ( ! empty( $value ) ) {
				$option_array = explode( ',', $value );
				
				
				if ( $option_array ) {
					foreach( $option_array as $option ) {
						//= Loop through list of items in each group
						$option = trim( $option );
						$slug = strtolower( $option );
						$slug = str_replace( ' ', '_', $slug );
						$slug = str_replace( '/', '_', $slug );
						$slug = str_replace( '(', '_', $slug );
						$slug = str_replace( ')', '_', $slug );
						$slug = str_replace( '-', '_', $slug );
						if ( isset( $vin_query_decode['decoded_' . $slug] ) ) {
							$content = $vin_query_decode['decoded_' . $slug];
							if (isset($vin_query_decode['decoded_' . $slug]) || isset( $vin_query_decode[$slug] ) || isset( $vin_query_decode['decoded_' . $option] ) || isset( $vin_query_decode[$option] ) ) {
								if ( ! empty( $content ) ) {
										++$cnt;
										$content = apply_filters( 'cdp_option_filter', $content );
										$x .= '<tr class="' . $class . '">
											<td class="decode_table_label">' . $arrow . $option . '</td>
											</tr>';
								} // end if ! empty $content
							} // end if isset different possibilities
						} // end if isset $vin_query_decode ['decoded_' . $slug]
					} // end foreach $option_array

					//= Loop through option meta and get those too
					if ( ! empty( $vehicle_option_array ) ) {
						foreach( $vehicle_option_array as $option_item ) {
							$option_item = trim( $option_item );
							$option_item = ucwords( $option_item );
							if ( in_array( $option_item, $option_array ) ) {
	
								$label = str_replace( '_',' ',$option_item );
								$label = ucwords( $label );
								$content = 'Std.';
								if ( ! in_array( 'decoded_' . $slug, $vin_query_decode ) ) {
									if ( ! empty( $content ) ) {
										++$cnt;
										$content = apply_filters( 'cdp_option_filter', $content );
										$x .= '<tr class="' . $class . '">
											<td class="decode_table_label">' . $arrow . $label . '</td>
											</tr>';
									}
								}
							} // end if in_array
						} // end foreach $vehicle_option_array
					} //= end Option Loop

				} // end if $option_array
			} // end if ! empty $value
		} // end foreach $map[$tab]
	} // end if isset $map[$tab]
	
	if ( $cnt < 1 ) {
		$x = '';
	}
	
	return $x;
}

if (defined('CD_FULL_WIDTH')) {
	add_filter( 'body_class', 'cd3_custom_add_post_classes', 10 );
}
function cd3_custom_add_post_classes( $classes ) {
	if (defined('CD_FULL_WIDTH_ALL')) {
		$classes[] = 'car-style-3';
		return $classes;
	}
	global $post;
	$post_id = $post->ID;
	// Get Post Type
	$post_type = get_post_type( $post_id );
	// Make sure this is a vehicle
	if ('cars_for_sale' == $post_type) {
		// Make sure this is the single vehicle page
		if ( is_single() ) {
			$classes[] = 'car-style-3';
		}
	}
	return $classes;
}
?>