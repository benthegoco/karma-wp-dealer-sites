<?php
function cdt_loop($content, $post_id='') {
	global $car_demon_options;
	$cdsp_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$cdsp_pluginpath = str_replace('includes', '', $cdsp_pluginpath);
	global $post;
	if (empty($post_id)) {
		$post_id = $post->ID;
	}
	
	$car = cd_get_car ( $post_id );
	
	$vehicle_year = $car['year'];
	$vehicle_make = $car['make'];
	$vehicle_model = $car['model'];
	$vehicle_condition = $car['condition'];
	$vehicle_exterior_color = $car['exterior_color'];
	$title = $car['title'];
	$stock_value = $car['stock_number'];
	$mileage_value = $car['mileage'];
	$vehicle_transmission = $car['transmission'];
	$link = get_permalink($post_id);
	if (isset($car_demon_options['use_compare'])) {
		$use_compare = $car_demon_options['use_compare'];
	} else {
		$use_compare = '';
	}
	if ($use_compare == 'Yes') {
		$in_compare = '';
		if ( isset( $_SESSION['car_demon_compare'] ) ) {
			$compare_list = $_SESSION['car_demon_compare'];
		} else {
			$compare_list = '';
		}
		$compare_these = explode(',',$compare_list);
		if (in_array($post_id,$compare_these)) {
			$in_compare = ' checked="checked"';
		}
		$compare = '<input'.$in_compare.' id="compare_'.$post_id.'" type="checkbox" onclick="update_car('.$post_id.',this);" />';
		$compare .= '<div class="vehicle_compare_label">'.__('Compare', 'car-demon-shortcode').'</div>';
	} else {
		$compare = '';	
	}
	$price = get_vehicle_price_shortcode($post_id);

	if ( defined( 'CD_CUSTOM_NO_PHOTO' ) ) {
		$img_output = "<img onclick='window.location=\"".$link."\";' title='Click for price on this ".$title."' onerror='cdsImgError(this, \"". CD_CUSTOM_NO_PHOTO . "\");' class='main_photo_pro_img' src='";
	} else {
		$img_output = "<img onclick='window.location=\"".$link."\";' title='Click for price on this ".$title."' onerror='ImgError(this, \"no_photo.gif\");' class='main_photo_pro_img' src='";
	}

	$main_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'vehicle-srp' );
	$img_output .= $main_image[0];
	//$img_output .= cd_main_photo( $post_id );

	$img_output .= "' />";
	$ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);
	if (empty($ribbon)) {
		$ribbon = 'no-ribbon';		
	}
	if ($ribbon != 'custom_ribbon') {
		$ribbon = str_replace('_', '-', $ribbon);
		$current_ribbon = '<img class="similar_car_ribbon" src="'. $cdsp_pluginpath .'images/ribbon-'.$ribbon.'.png" width="76" height="76" alt="New Ribbon" id="ribbon">';
	} else {
		$custom_ribbon_file = get_post_meta($post_id, '_custom_ribbon', true);
		$current_ribbon = '<img class="similar_car_ribbon" src="'.$custom_ribbon_file.'" width="76" height="76" alt="New Ribbon" id="ribbon">';
	}
	if (isset($car_demon_options['dynamic_ribbons'])) {
		if ($car_demon_options['dynamic_ribbons'] == 'Yes') {
			$current_ribbon = car_demon_dynamic_ribbon_filter($current_ribbon, $post_id, '76');
		}
	}
	$car_photo = '
		<div class="randoms">
			<div class="random_imgs">
				'.$current_ribbon.'
				<img onclick="window.location=\''.$link.'\';" class="random_car_lookup" src="'. $cdsp_pluginpath .'images/look_close.png" width="218" height="164" style="width:218px;height:164px;margin-left:0px;" alt="New Ribbon" id="look_close" class="look_close">
				'.$img_output.'
			</div>
		</div>';
	$labels = get_default_field_labels();
	
	$options = '<li><div class="vehicle_options_style"></div>'.__('Mileage:', 'car-demonpro').' '.$mileage_value.'</li>
		<li><div class="vehicle_options_style"></div>'.__('Exterior:', 'car-demonpro').' '.$vehicle_exterior_color.'</li>
		<li><div class="vehicle_options_style"></div>'.__('Transmission:', 'car-demonpro').' '.$vehicle_transmission.'</li>
		';
	$options = apply_filters( 'cdpro_option_filter', $options, $post_id );
	
	$html = '
		<div class="car_item_pro cd_pro">
			<div class="clearFloat"></div>
			<div class="vehicle_title">
				'.$title.'
			</div>
			<div class="vehicle_compare_box">
				'.$compare.'
			</div>
			<div class="clearFloat"></div>
			<div class="vehicle_details_box">
				<div class="vehicle_make_box">
					<div class="vehicle_make_arrow"></div>
					<div class="vehicle_make">'.$vehicle_model.'</div>
					<div class="vehicle_stock_number">'.__('Stock #', 'car-demon-shortcode').' '.$stock_value.'</div>
				</div>
				<div class="vehicle_price_box">
					<div class="vehicle_price_label">
						'.$labels['price'].'
					</div>
					<div class="vehicle_price">
						'.$price.'
					</div>
				</div>
				<div class="clearFloat"></div>
				<div class="vehicle_photo_box">
					'.$car_photo.'
				</div>
				<div class="clearFloat"></div>
				<div class="vehicle_option_title">'.__('Vehicle Highlights', 'car-demon-shortcode').'
				</div>
				<div class="clearFloat"></div>
				<div class="vehicle_options">
					'. $options . '
				</div>
				<div class="clearFloat"></div>
				<div class="vehicle_detail_button_box">
					<div class="view_vehicle">
						<a href="'.$link.'">'.__('CLICK HERE FOR DETAILS', 'car-demon-shortcode').'</a>
					</div>
				</div>';

				$extra = '';
				if ( defined( 'CDSP_SRP_SIDEBAR' ) ) {
					$extra = cdsp_pro_get_srp_sidebar( $post_id );
				}
				$extra = apply_filters( 'cd_pro_list_extras', $extra, $post_id );
				$html .= $extra;

				if ( defined( 'CDSP_SRP_SIDEBAR' ) ) {
					if ( empty( $extra ) ) {
	
						$car_contact = get_car_contact( $post_id );
						$contact_trade_url = $car_contact['trade_url'].'?stock_num='.$car['stock_number'];
						$contact_finance_url = $car_contact['finance_url'].'?stock_num='.$car['stock_number'];
						$contact_facebook_url = "http://www.facebook.com/sharer/sharer.php?u=".$car['car_link'];
						$terms = get_the_terms( $post_id, 'vehicle_location' );
						$cdsp_theme_options = cdsp_theme_options();
	
						if ( ! empty( $terms ) ) {
							foreach( $terms as $term ) {
								if ( ! empty( $term ) ) {
									$vehicle_location = $term->slug;
								}
							}
						}
						if ( empty( $vehicle_location ) ) {
							$vehicle_location = 'default';	
						}
						$contact_warranty_url = get_option( $vehicle_location . '_warranty_url' );
	
						$html .= '<div class="cdsp_srp_btns">';
								if (!empty($contact_warranty_url)) {
									$html .= '<a href="'.$contact_warranty_url .'?stock_num='.$car['stock_number'] . '&sales_code=' . $car_contact['sales_code'] . '"><img src="' . $cdsp_theme_options['warranty_button'] . '" id="vehicle_warranty_img_' . $post_id . '" class="vehicle_button_img" /></a>';
								}
								if ( ! empty( $contact_finance_url ) && isset( $cdsp_theme_options['finance_button'] ) ) { 
									if ( $car_contact['finance_popup'] == 'Yes' ) {
										$html .= '<a onclick="window.open(\'' . $contact_finance_url . '&sales_code=' . $car_contact['sales_code'] . '\',\'finwin\',\'width=' . $car_contact['finance_width'] . ', height=' . $car_contact['finance_height'] . ', menubar=0, resizable=0\')">
											<img src="'.$cdsp_theme_options['finance_button'].'" id="vehicle_finance_img_'.$post_id.'" class="vehicle_button_img" />
											</a>';
									} else {
										$html .= '<a href="' . $contact_finance_url . '&sales_code=' . $car_contact['sales_code'] . '"><img src="' . $cdsp_theme_options['finance_button'] . '" id="vehicle_button_img_' . $post_id . '" class="vehicle_button_img" /></a>';
									}
								}
								if ( ! empty( $contact_trade_url ) && isset( $cdsp_theme_options['trade_button'] ) ) {
									$html .= '<a href="' . $contact_trade_url . '&sales_code=' . $car_contact['sales_code'] . '"><img src="' . $cdsp_theme_options['trade_button'] . '" id="vehicle_trade_img_' . $post_id . '" class="vehicle_button_img" /></a>';
								}
	
						$html .= '</div>';
						$html .= '<div class="cdsp_float_left"></div>';
					}
				}
				$html .= '<div class="clearFloat"></div>
			</div>
		</div>
	';
	return $html;
}
?>