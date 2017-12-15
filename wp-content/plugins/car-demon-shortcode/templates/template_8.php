<?php
function cdsp_template_8($post_id) {
	$template = get_car_template($post_id);
	/*
	echo '<pre>';
		print_r($template);
	echo '</pre>';
	*/
   	$x = '
        <div class="car_item_pro cd_8" data-bgcolor="">
            <div class="section group">
			
				<div class="main_photo_pro">
					<div class="car_price_details_style" id="car_price_details">';
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
							if ($template['price'] == 0) {
								$x .= '
									<div id="your_price" class="car_final_price_style">'.$template['price_box'].'</div>
								';
							} else {
								$x .= '
									<div id="your_price" class="car_final_price_style">'.$currency_symbol.$template['price'].'<span class="cd_change">.00</span>'.$currency_symbol_after.'</div>
								';
							}
						}
					$x .= '</div>
					
					<div class="cd_show" data-permalink="'. $template['link'] .'">'. __('Show', 'car-demon-shortcode') .'</div>
					
					<a href="'.$template['link'].'" class="cd_ribbon">
						'.$template['current_ribbon'].'
					</a>
					<a href="'.$template['link'].'">
						'.$template['img_output'].'
					</a>
				</div>
				
                <div class="col">
					<div class="car_title_pro">
						<a href="'.$template['link'].'">
							'.$template['title'].'
						</a>
					</div>
                </div>
				
				
                <div class="col">
                    <div class="description">';
						if ($template['show_hide']['stock_number'] != true) {
							$x .= '<div class="description_text">'.$template['car']['stock_number'].'</div>';
						}
						if ($template['show_hide']['mileage'] != true) {
							$x .= '<div class="description_text"><span class="cd_circle"></span>'.$template['car']['mileage'].' '. __('miles', 'car-demon-shortcode') .'</div>';
						}

						if (!empty($template['car']['fuel'])) {
							$x .= '<div class="description_text"><span class="cd_circle"></span>'.$template['car']['fuel'].'</div>';
						}

						if ($template['show_hide']['transmission'] != true) {
							$x .= '<div class="description_text"><span class="cd_circle"></span>'.$template['car']['transmission'].'</div>';
						}

                    $x .= '</div>
				</div>
                <div class="col">
					<div class="compare">
						'.$template['compare'].'
					</div>

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

                </div>
            </div>
			<div class="car_butons_row">';
				if (!empty($template['car_contact']['sales_phone'])) {
					$x .= '<span class="call_us">'.__('Questions? Call ', 'car-demon-shortcode' ).$template['car_contact']['sales_phone'].'</span>';
				}
			$x .= '</div>
		</div>';
	return $x;
}