<?php
function cdsp_template_1($post_id) {
	$template = get_car_template($post_id);
   	$x = '
        <div class="car_item_pro cd_1">
            <div class="section group">
                <div class="col cols_1_of_4">
                    <div class="archive_main_photo_pro_box">
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
                <div class="col cols_1_of_4">
                    <div class="description">';
						if ($template['show_hide']['body_style'] != true) {
							if (isset($template['car']['body_style'])) {
								if (!empty($template['car']['body_style'])) {
									$x .='<div class="description_label">'.$template['labels']['body_style'].':</div>';
									$x .='<div class="description_text">'.$template['car']['body_style'].'</div>';
								}
							}
						}
						if ($template['show_hide']['mileage'] != true) {
							if (isset($template['car']['mileage'])) {
								if (!empty($template['car']['mileage'])) {
									$x .='<div class="description_label">'.$template['labels']['mileage'].':</div>';
									$x .='<div class="description_text">'.$template['car']['mileage'].'</div>';
								}
							}
						}
						if ($template['show_hide']['stock_number'] != true) {
							if (isset($template['car']['stock_number'])) {
								if (!empty($template['car']['stock_number'])) {
									$x .='<div class="description_label">'.$template['labels']['stock_number'].':</div>';
									$x .='<div class="description_text">'.$template['car']['stock_number'].'</div>';
								}
							}
						}
                    $x .='</div>';
					if ($template['show_hide']['exterior_color'] != true) {
						$x .= '<div class="car_color"><span></span>'.$template['car']['exterior_color'].'</div>';
					}
				$x .= '
				</div>
                <div class="col cols_1_of_4 col_break">
                	<div class="car_social">
						<a href="'.$template['social_facebook_link'].'" target="_blank">
	                    	<img src="'.$template['cdsp_pluginpath'].'images/social_fb_1.png" alt=""/>
						</a>
						<a href="'.$template['social_twitter_link'].'" target="_blank">
	                        <img src="'.$template['cdsp_pluginpath'].'images/social_twitter_1.png" alt=""/>
						</a>
						<a href="'.$template['social_linkedin_link'].'" target="_blank">
	                        <img src="'.$template['cdsp_pluginpath'].'images/social_linkedin_1.png" alt=""/>
						</a>
						<a href="'.$template['social_gplus_link'].'" target="_blank">
	                        <img src="'.$template['cdsp_pluginpath'].'images/social_gplus_1.png" alt=""/>
						</a>
                    </div>';
					if ($template['show_hide']['price'] != true) {
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
							if (!empty($currency_symbol_after)) {
								$currency_symbol = "";
							}
						} else {
							$currency_symbol_after = "";
						}	
						if ($is_sold == "Yes") {
							$template['price'] = __("SOLD", 'car-demon-shortcode');
						}
						$x .= '
						<div class="car_price_details_style" id="car_price_details">
							<div id="your_price_text" class="car_your_price_style">'.$template['labels']['price'].'</div>
							<div id="your_price" class="car_final_price_style">'.$currency_symbol.$template['price'].$currency_symbol_after.'<span class="cd_change">.00</span></div>
						</div>';
					}
				$x .= '
                </div>
            </div>
		</div>
	';
	return $x;
}