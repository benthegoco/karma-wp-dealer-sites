<?php
function cdsp_template_6($post_id) {
	$template = get_car_template($post_id);
   	$x = '
        <div class="car_item_pro cd_6">
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
					<div class="compare">
						'.$template['compare'].'
					</div>
                </div>
                <div class="col cols_1_of_4 col_break">
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
							$x .= '<div class="description_label">'.$template['labels']['exterior_color'].':</div>';
							$x .= '<div class="description_text">'.$template['car']['exterior_color'].'</div>';
						}
						if ($template['show_hide']['stock_number'] != true) {
							$x .= '<div class="description_label">'.$template['labels']['stock_number'].':</div>';
							$x .= '<div class="description_text">'.$template['car']['stock_number'].'</div>';
						}
                    $x .= '</div>
				</div>
                <div class="col cols_1_of_4">
                	<div class="car_social">
						<a href="'.$template['social_facebook_link'].'" target="_blank">
	                    	<img src="'.$template['cdsp_pluginpath'].'images/social_fb_2.png" alt=""/>
						</a>
						<a href="'.$template['social_twitter_link'].'" target="_blank">
	                        <img src="'.$template['cdsp_pluginpath'].'images/social_twitter_2.png" alt=""/>
						</a>
						<a href="'.$template['social_linkedin_link'].'" target="_blank">
	                        <img src="'.$template['cdsp_pluginpath'].'images/social_linkedin_2.png" alt=""/>
						</a>
						<a href="'.$template['social_gplus_link'].'" target="_blank">
	                        <img src="'.$template['cdsp_pluginpath'].'images/social_gplus_2.png" alt=""/>
						</a>
                    </div>
                    <div class="car_price_details_style" id="car_price_details">'.get_template_price($post_id, $template).'
					</div>
                </div>
            </div>
			<div class="car_butons_row">';
				if (!empty($template['car_contact']['sales_phone'])) {
					$x .= '<span>'.__('Questions? Call ', 'car-demon-shortcode' ).$template['car_contact']['sales_phone'].'</span>';
				}
				if (!empty($template['car_contact']['vehicle_contact_url'])) {
					$x .= '<span><a href="'.$template['car_contact']['vehicle_contact_url'].'?stock_num='.$template['car']['stock_number'].'">'.__('Email Us', 'car-demon-shortcode' ).'</a></span>';
				}
				if (!empty($template['car_contact']['warranty_url'])) {
					$x .= '<span><a href="'.$template['car_contact']['warranty_url'].'?stock_num='.$template['car']['stock_number'].'">'.__('Warranty', 'car-demon-shortcode' ).'</a></span>';
				}
				if (!empty($template['car_contact']['trade_url'])) {
					$x .= '<span><a href="'.$template['car_contact']['trade_url'].'?stock_num='.$template['car']['stock_number'].'">'.__('Trade', 'car-demon-shortcode' ).'</a></span>';
				}
				if (!empty($template['car_contact']['finance_url'])) {
					$x .= '<span><a href="'.$template['car_contact']['finance_url'].'?stock_num='.$template['car']['stock_number'].'">'.__('Finance', 'car-demon-shortcode' ).'</a></span>';
				}
				if (!empty($template['car_contact']['make_offer_url'])) {
					$x .= '<span><a href="'.$template['car_contact']['make_offer_url'].'?stock_num='.$template['car']['stock_number'].'">'.__('Make Offer', 'car-demon-shortcode' ).'</a></span>';
				}
			$x .= '</div>
		</div>';
	return $x;
}