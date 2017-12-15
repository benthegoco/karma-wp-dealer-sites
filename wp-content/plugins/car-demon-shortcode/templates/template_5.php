<?php
function cdsp_template_5($post_id) {
	$template = get_car_template($post_id);
   	$x = '
        <div class="car_item_pro cd_5">
            <div class="section group">
                <div class="col cols_1_of_4">
					<div class="main_photo_pro">
						<a href="'.$template['link'].'">
							'.$template['current_ribbon'].'
						</a>
						<a href="'.$template['link'].'">
							'.$template['img_output'].'
						</a>
					</div>
                </div> <!--End column 1-->
                <div class="col cols_1_of_4">
					<div class="car_title_pro">
						<a href="'.$template['link'].'">
							 <span class="cds_condition">'.$template['condition'].'</span> '.$template['title'].'
						</a>
					</div>
                    <div class="car_phone">'.__('Call us @ ', 'car-demon-shortcode' ).$template['car_contact']['sales_phone'].'</div>';

					if ( defined( 'CD_SHORTCODE_5_VIEW_DETAILS' ) ) {
						if ( CD_SHORTCODE_5_VIEW_DETAILS == true ) {
							$x .= '<div class="car_email"><a href="'.$template['link'].'">' . CD_SHORTCODE_VIEW_DETAILS_LABEL . '</a></div>';
						}
					}

					if (isset($template['car_contact']['finance_url'])) {
						if (!empty($template['car_contact']['finance_url'])) {
							$x .= '<div class="car_email"><a href="'.$template['car_contact']['finance_url'].'?stock_num='.$template['car']['stock_number'].'">' . CD_SHORTCODE_FINANCE_LABEL . '</a></div>';
						}
					}

					if (isset($template['car_contact']['vehicle_contact_url'])) {
						if (!empty($template['car_contact']['vehicle_contact_url'])) {
							$contact_url = '<div class="car_email"><a href="'.$template['car_contact']['vehicle_contact_url'].'?stock_num='.$template['car']['stock_number'].'">' . CD_SHORTCODE_CONTACT_LABEL . '</a></div>';
							$contact_url = apply_filters( 'cds_contact_link', $contact_url, $post_id, $template );
							$x .= $contact_url;
						}
					}

					if (isset($template['car_contact']['trade_url'])) {
						if (!empty($template['car_contact']['trade_url'])) {
							$x .= '<div class="car_email"><a href="'.$template['car_contact']['trade_url'].'?stock_num='.$template['car']['stock_number'].'">' . CD_SHORTCODE_TRADE_IN_LABEL . '</a></div>';
						}
					}
           $x .= '</div>
                <div class="col cols_1_of_4 col_break">
					<div class="compare">
						'.$template['compare'].'
					</div>
                    <div class="description">';
						if ($template['show_hide']['body_style'] != true) {
							$x .= '<div class="description_label">'.$template['labels']['body_style'].':</div>';
							$x .= '<div class="description_text">'.$template['car']['body_style'].'</div>';
						}
						if ($template['show_hide']['mileage'] != true) {
							$x .= '<div class="description_label">'.$template['labels']['mileage'].':</div>';
							$x .= '<div class="description_text">'.$template['car']['mileage'].'</div>';
						}
						if ($template['show_hide']['exterior_color'] != true) {
							if (!empty($template['show_hide']['exterior_color'])) {
								$x .= '<div class="description_label">'.$template['labels']['exterior_color'].':</div>';
								$x .= '<div class="description_text">'.$template['car']['exterior_color'].'</div>';
							}
						}
						if ($template['show_hide']['stock_number'] != true) {
							$x .= '<div class="description_label">'.$template['labels']['stock_number'].':</div>';
							$x .= '<div class="description_text">'.$template['car']['stock_number'].'</div>';
						}
                    $x .= '</div>
				</div>
                <div class="col cols_1_of_4">
					<div class="car_price_details_style" id="car_price_details">
						'.get_template_price($post_id, $template).'
					</div>
                </div>
            </div>
		</div>';
	return $x;
}