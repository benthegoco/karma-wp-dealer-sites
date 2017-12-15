<?php
function cdsp_template_9($post_id) {
	$template = get_car_template($post_id);
   	$x = '
    <div class="car_item_pro cd_9">
        <div class="main_photo_pro">
            <a href="'.$template['link'].'">
                '.$template['current_ribbon'].'
            </a>
            <a href="'.$template['link'].'">
                '.$template['img_output'].'
            </a>
        </div>
        <div class="car_title_pro">
			<a href="'.$template['link'].'">
				 <span class="cds_condition">'.$template['condition'].'</span> '.$template['title'].'
			</a>
			<div class="car_excerpt">
				 '.$template['car']['excerpt'].'
			</div>
        </div>
        <div class="description">';
			$x .= '<div class="car_full_width">';
				$x .= '<div class="photos_count">';
					$x .= '<img src="' . plugins_url() . '/car-demon-shortcode/images/photos.png" />';
					$x .= $template['car']['photos_count'] . __( ' Photos', 'car-demon-shortcode' );
				$x .= '</div>';
				
				if ($template['show_hide']['exterior_color'] != true) {
					$x .= '<div class="vehicle_color_box" title="' . $template['labels']['exterior_color'] . '">';
						if ( ! empty( $template['car']['color_swatch'] ) ) {
							$x .= '<div class="color_swatch" style="background-color:' . $template['car']['color_swatch'] . '">';
							$x .= '</div>';
						}
						$x .= '<div class="vehicle_color">';
							$x .= $template['car']['exterior_color'];
						$x .= '</div>';
					$x .= '</div>';
				}
			$x .= '</div>';
            $x .= '<div class="description_left">';
				$x .= get_template_price($post_id, $template);
				if ( $template['car']['price'] != '0' ) {
					$x .= '<div class="price_details">';
						$x .= '+ tax & lic';
					$x .= '</div>';
				}
			$x .= '</div>';

            $x .= '<div class="description_right">';
				if ($template['show_hide']['mileage'] != true) {
					$x .= '<div class="description_text mileage">'.$template['car']['mileage'].'</div>';
					$x .= '<div class="description_label mileage">'.$template['labels']['mileage'].'</div>';
				}
				if ($template['show_hide']['vin'] != true) {
					$x .= '<div class="description_label_vin">'.$template['labels']['vin'].': </div><div class="vin_value">'.$template['car']['vin'].'</div>';
				}
            $x .= '</div>';
            $x .= '<div class="description_wrap">';
				$x .= apply_filters( 'cd_9_srp_wrap', '' );
            $x .= '</div>
        </div>
        <div class="compare">
			'.$template['compare'].'
        </div>
    </div>';
	$x = apply_filters( 'cd_9_srp', $x );

	return $x;
}

if ( defined( 'CD_9_CAR_PROOF' ) ) {
	add_filter( 'cd_9_srp_wrap', 'cd_9_srp_tmp' );
	function cd_9_srp_tmp() {
		$x = '<img src="' . plugins_url() . '/car-demon-shortcode/images/logo-carproof-color.png" style="width:100px;height:30px;margin-left: 10px;" />';
		return $x;
	}
}