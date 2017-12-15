<?php
function cdsp_template_3($post_id) {
	$template = get_car_template($post_id);
   	$x = '
    <div class="car_item_pro cd_3">
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
        </div>
        
        <div class="description">
            <div class="description_left">';
				if ($template['show_hide']['body_style'] != true) {
					$x .= '<div class="description_label">'.$template['labels']['body_style'].':</div>';
					$x .= '<div class="description_text">'.$template['car']['body_style'].'</div>';
				}
				if ($template['show_hide']['stock_number'] != true) {
					$x .= '<div class="description_label">'.$template['labels']['stock_number'].':</div>';
					$x .= '<div class="description_text">'.$template['car']['stock_number'].'</div>';
				}
            $x .= '</div>
            <div class="description_right">';
				if ($template['show_hide']['mileage'] != true) {
					$x .= '<div class="description_label">'.$template['labels']['mileage'].':</div>';
					$x .= '<div class="description_text">'.$template['car']['mileage'].'</div>';
				}
				if ($template['show_hide']['exterior_color'] != true) {
					$x .= '<div class="description_label">'.$template['labels']['exterior_color'].':</div>';
					$x .= '<div class="description_text">'.$template['car']['exterior_color'].'</div>';
				}
            $x .= '</div>
        
            <div class="description_wrap">';
				if ($template['show_hide']['vin'] != true) {
					$x .= '<div class="description_label_vin">'.$template['labels']['vin'].': '.$template['car']['vin'].'</div>';
				}
            $x .= '</div>
        </div>
		<div class="car_price_details_style" id="car_price_details">
			'.get_template_price($post_id, $template).'
		</div>
        <div class="car_contact">
            '.__('Call us @ ', 'car-demon-shortcode' ).$template['car_contact']['sales_phone'].'
        </div>
        <div class="compare">
			'.$template['compare'].'
        </div>
    </div>';
	return $x;
}