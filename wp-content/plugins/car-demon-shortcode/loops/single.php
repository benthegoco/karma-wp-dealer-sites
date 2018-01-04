<?php



function cds_display_single_car($post_id) {

    global $car_demon_options;

    wp_enqueue_script('car-demon-pro-single-car-js', plugins_url() . '/car-demon-shortcode/js/car-demon-single-cars.js', 'jquery');

    wp_enqueue_script('car-demon-swipe-js', plugins_url() . '/car-demon-shortcode/js/jQuery.TouchSwipe.js');



    $cdsp_pro_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';

    $cdsp_pro_pluginpath = str_replace('includes/','',$cdsp_pro_pluginpath);

    $vehicle_vin = rwh(strip_tags(get_post_meta($post_id, "_vin_value", true)),0);

    $car_title_pro = get_car_title_slug($post_id);

    $car_head_title = get_car_title($post_id);

    $car_url = get_permalink($post_id);



    $vehicle_location = get_cd_term( $post_id, 'vehicle_location' );

    $vehicle_details = car_demon_get_car($post_id);



    $video_url = get_post_meta($post_id, 'video_value', true);

    //=========================Contact Info===========================

    $car_contact = get_car_contact($post_id);

    if (empty($vehicle_location)) {

        $vehicle_location = cd_get_default_location_slug();

    }

    if (empty($vehicle_details['stock_number'])) {

        $vehicle_details['stock_number'] = $post_id;

    }

    $contact_trade_url = $car_contact['trade_url'].'?stock_num='.$vehicle_details['stock_number'];

    $contact_finance_url = $car_contact['finance_url'].'?stock_num='.$vehicle_details['stock_number'];

    $contact_facebook_url = "http://www.facebook.com/sharer/sharer.php?u=".$car_url;

    $terms = get_the_terms($post_id, 'vehicle_location');

    $cdsp_theme_options = cdsp_theme_options();

    if (!empty($terms))

        foreach($terms as $term) {

            if (!empty($term)) {

                $vehicle_location = $term->slug;

            }

        }

    if (empty($vehicle_location)) {

        $vehicle_location = 'default';

    }

    $contact_warranty_url = get_option($vehicle_location.'_warranty_url');

    $contact_gplus_url = $car_url;

    $contact_twitter_url = $car_url;

    $contact_pintrest_url = $car_url;

    $html = car_demon_email_a_friend($post_id, $vehicle_details['stock_number']);

    $mileage_value = get_post_meta($post_id, "_mileage_value", true);

    $cdsp_theme_options = cdsp_theme_options();

    $slug = str_replace(' ','_', $car_head_title);



    $car = car_demon_get_car($post_id);

    /* RS check for location from site url */
                        if(strpos(get_site_url(),"montreal") || strpos(get_site_url(),"vancouver")){
                        setlocale(LC_MONETARY, 'en_CA.utf8');

    $vehicle_price = money_format('$%.0i', $car['price']); //RS: remove trailing zeros
    $vehicle_price = str_replace('CAD','CAD ',$vehicle_price);
                        } else {
                        setlocale(LC_MONETARY, 'en_US.utf8');

                        $vehicle_price = money_format('$%.0i', $car['price']); //RS: remove trailing zeros
                        }


    $html .= '

		<div class="single-car-for-sale">

			<div class="colwrap1">

				'.cdsp_pro_single_thumbs($post_id);

    $html .='

			</div>

			<div class="colwrap2">

				<div class="single-car-details">

					<ul>';

    $template = get_car_template($post_id);

    $html .='<li><label>'.$template['labels']['condition'].'</label> '.$vehicle_details['condition'].'</li>';

    if ($template['show_hide']['mileage'] != true) {

        $html .='<li><label>'.$template['labels']['mileage'].'</label> '.$mileage_value.'</li>';

    }

    if ($template['show_hide']['stock_number'] != true) {

        $html .='<li><label>'.$template['labels']['stock_number'].'</label> '.$vehicle_details['stock_number'].'</li>';

    }

    if ($template['show_hide']['vin'] != true) {

        $html .='<li><label>'.$template['labels']['vin'].'</label> '.$vehicle_vin.'</li>';

    }





    if (isset($vehicle_details['exterior_color'])) {

        if (!empty($vehicle_details['exterior_color'])) {

            $html .='<li><label>'.__('Exterior', 'car-demon-shortcode').'</label> '.$vehicle_details['exterior_color'].'</li>';

        }

    }

    if (isset($vehicle_details['interior_color'])) {

        if (!empty($vehicle_details['interior_color'])) {

            $html .= '<li><label>'.__('Interior', 'car-demon-shortcode').'</label> '.$vehicle_details['interior_color'].'</li>';

        }

    }



    if ($template['show_hide']['price'] != true) {

        $html .='<li><label>'.$template['labels']['price'].'</label> '.$vehicle_price.'</li>';

    }



    $html .='<!--li><label>'.__('Engine:', 'car-demon-shortcode').'</label> '.$vehicle_details['engine'].'</li-->';



    $html .='</ul>';

    $html .='<div id="vehicle_buttons" class="vehicle_buttons">';

    $sidebar = cdsp_pro_get_vsb_sidebar($post_id);

    $sidebar = str_replace('{finance_link}', $contact_finance_url, $sidebar);

    $sidebar = str_replace('{trade_link}', $contact_trade_url, $sidebar);



    if (empty($sidebar)) {

        if (!empty($contact_warranty_url)) {

            $html .= '<a href="'.$contact_warranty_url .'?stock_num='.$vehicle_details['stock_number'].'&sales_code='.$car_contact['sales_code'].'"><img src="'.$cdsp_theme_options['warranty_button'].'" id="vehicle_warranty_img_'.$post_id.'" class="vehicle_button_img" /></a>';

        }

        if ( ! empty( $contact_finance_url ) && isset( $cdsp_theme_options['finance_button'] ) ) {

            if ($car_contact['finance_popup'] == 'Yes') {

                $html .= '<a onclick="window.open(\''.$contact_finance_url .'&sales_code='. $car_contact['sales_code'].'\',\'finwin\',\'width='.$car_contact['finance_width'].', height='.$car_contact['finance_height'].', menubar=0, resizable=0\')">

								<img src="'.$cdsp_theme_options['finance_button'].'" id="vehicle_finance_img_'.$post_id.'" class="vehicle_button_img" />

								</a>';

            } else {

                $html .= '<a href="'.$contact_finance_url.'&sales_code='. $car_contact['sales_code'].'"><img src="'.$cdsp_theme_options['finance_button'].'" id="vehicle_button_img_'.$post_id.'" class="vehicle_button_img" /></a>';

            }

        }

        if ( ! empty( $contact_trade_url ) && isset( $cdsp_theme_options['trade_button'] ) ) {

            $html .= '<a href="'.$contact_trade_url .'&sales_code='.$car_contact['sales_code'].'"><img src="'.$cdsp_theme_options['trade_button'].'" id="vehicle_trade_img_'.$post_id.'" class="vehicle_button_img" /></a>';

        }

    }



    $html .= $sidebar;

    $html .='</div>';

    $html .='</div>';



    $social_row = '';

    $social_row .='<div id="social_row">

					<div id="social_icon_set">

						<div id="social_r1_c1">

						</div>

						<div class="clearFloat"></div>

						<div id="social_r2_c1">

						</div>';

    if (!empty($contact_facebook_url)) {

        $social_row .='<div id="social_facebook" onclick="window.open(\''.$contact_facebook_url.'\');">

								</div>

								<div id="social_r2_c3">

								</div>';

    }

    if (!empty($contact_gplus_url)) {

        $social_row .='<div id="social_g_plus" onclick="window.open(\''.$contact_gplus_url.'\');">

								<a href="https://plus.google.com/share?url='.$contact_gplus_url.'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;">

									<img src="https://www.gstatic.com/images/icons/gplus-32.png" alt="Share on Google+"/>

								</a>

								</div>

								<div id="social_r2_c5">

								</div>';

    }

    if (!empty($contact_twitter_url)) {

        $social_row .='<div id="social_twitter" class="twitter_popup" onclick="share_twitter();">

									<input type="hidden" id="twitter_link" value="'.$contact_twitter_url.'" />

								</div>

								<div id="social_r2_c7">

								</div>';

    }

    if (!empty($contact_pintrest_url)) {

        $social_row .='<div id="social_pintrest" onclick="window.open(\'http://pinterest.com/pin/create/button/?url='.$contact_pintrest_url.'\&media=' . cd_main_photo( $post_id ) . '&description='.$car_head_title.'\');">

								</div>';

    }

    $social_row .='

						<div class="clearFloat"></div>

						<div class="contact-us-btn" onclick="location.href=\'contact?stocknum=' . $vehicle_details['stock_number'] . '\'">Contact Us</div>

						<div id="social_r3_c1"></div>

						<div class="clearFloat"></div>



						<div class="single-page-request-more-info-container">

							<div class="single-page-request-more-info-content">

							<div class="single-page-request-more-info-title">REQUEST MORE INFO</div>

								<form class="single-page-request-more-info-form">

									<div class="single-page-request-more-info-each-entry" id="firstnameField"><input class="more-info-fieldStyle" id="firstName" placeholder="FIRST NAME" required="" type="text"></div>

									<div class="single-page-request-more-info-each-entry" id="lastnameField"><input class="more-info-fieldStyle" id="lastName" placeholder="LAST NAME" required="" type="text"></div>

									<div class="single-page-request-more-info-each-entry" id="emailField"><input class="more-info-fieldStyle" id="firstName" placeholder="FIRST NAME" required="" type="text"></div>

									<div class="single-page-request-more-info-each-entry" id="phoneField"><input class="more-info-fieldStyle" id="firstName" placeholder="EMAIL" required="" type="text"></div>

									<div class="single-page-request-more-info-each-entry-comment" id="commentField"><input class="more-info-fieldStyle" id="firstName" placeholder="COMMENT" required="" type="text"></div>

									<div><button class="more-info-submit-fieldStyle" type="submit"><div class="submitStyle">SUBMIT</div></button></div>

								</form>

							</div>

						</div>





					</div>

					<div id="single-car-social-email-box">

						<div id="social_email_icon" onclick="email_friend();"></div>

						<div id="social_email_label" onclick="email_friend();"></div>

						<div class="clearFloat"></div>';



    if ( function_exists( 'cdp_enqueue_scripts' ) ) {

        $print_type = 'cds_print_html';

        if ( defined( 'CDS_PRINT_PDF' ) ) {

            $print_type = 'cds_print_pdf';

        }

        $social_row .='<div id="social_print_icon" data-post-id="' . $post_id . '" class="' . $print_type . '">

							</div>

							<div id="social_print_label" data-post-id="' . $post_id . '" class="' . $print_type . '">

							</div>

							<div class="clearFloat"></div>';

    }



    $social_row .='

					</div>

				</div>';



    $social_row = apply_filters( 'cdpro_cdp_social_filter', $social_row, $post_id );



    $html .= $social_row;



    $car = car_demon_get_car($post_id);

    $price = $car['price'];

    $price = number_format((int)$price); //RS: changed float to int



    if ( isset( $car_demon_options['currency_symbol'] ) ) {

        $currency_symbol = $car_demon_options['currency_symbol'];

    } else {

        $currency_symbol = "$";

    }

    if ( isset( $car_demon_options['currency_symbol_after'] ) ) {

        $currency_symbol_after = $car_demon_options['currency_symbol_after'];

    } else {

        $currency_symbol_after = "";

    }



    $price = $currency_symbol.$price.$currency_symbol_after;



    $vehicle_price = apply_filters( 'cd_price_format', $price );



    $is_sold = get_post_meta($post_id, 'sold', true);



    if ($is_sold == "Yes") {

        $vehicle_price = "<div class='car_sold'>".__("SOLD", 'car-demon-shortcode')."</div>";

    }



    $single_price_label = __('Price', 'car-demon-shortcode');

    $single_price_label = apply_filters( 'single_price_label_filter', $single_price_label, $post_id );



    $price_box = '<div class="single-car-price-box">

					<div class="single-car-price-label">

						' . $single_price_label . ':

					</div>

					<div class="single-car-price">

						'.$vehicle_price.'

					</div>

				</div>';



    $price_box = apply_filters( 'cdsp_price_filter', $price_box, $post_id, $vehicle_price );



    $html .= $price_box . '</div>

			<div class="single-car-under-price">

			</div>

			<div class="single-car-above-option-box">

			</div>

			

			<div class="single-car-option-box">

			<div class="single-car-description-label">DESCRIPTION</div>

			<div class="single-car-description-label-line"></div>

				'.car_demon_vehicle_detail_tabs($post_id).'

			</div>

		</div>

		<div class="car_clear" style="clear:both"></div>

		



	';

    return $html;

}



function cds_the_content_max_charlength($charlength) {

    $content = get_the_content();

    $excerpt = $content;

    $charlength++;

    $len = strlen($excerpt);

    $diff = $len - $charlength;

    if ( mb_strlen( $excerpt ) > $charlength ) {

        $subex = mb_substr( $excerpt, 0, $charlength - 5 );

        $exwords = explode( ' ', $subex );

        $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );

        if ( $excut < 0 ) {

            $x = mb_substr( $subex, 0, $excut );

        } else {

            $x .= $subex;

        }

        $extra = str_replace($x, '', $content);

        $x .= ' <span title="'.$extra.'" class="more_hover">[more...]</span>';

    } else {

        $x .= $excerpt;

    }

    return $x;

}

function cdsp_pro_main_photo_pro($post_id) {

    global $car_demon_options;

    $car_title_pro = get_car_title($post_id);

    $car_title_pro = str_replace(' ','_', $car_title_pro);

    $car_title_pro = str_replace('.','_', $car_title_pro);

    $cdsp_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';

    $cdsp_pluginpath = str_replace('includes/','',$cdsp_pluginpath);

    $ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);

    if (empty($ribbon)) {

        $ribbon = 'no-ribbon';

    }

    if ($ribbon != 'custom_ribbon') {

        $ribbon = str_replace('_', '-', $ribbon);

        $current_ribbon = '<img src="'. $cdsp_pluginpath .'images/ribbon-'.$ribbon.'.png" width="112" height="112" id="ribbon">';

    } else {

        $custom_ribbon_file = get_post_meta($post_id, '_custom_ribbon', true);

        $current_ribbon = '<img src="'.$custom_ribbon_file.'" width="112" height="112" id="ribbon">';

    }

    if (isset($car_demon_options['dynamic_ribbons'])) {

        if ($car_demon_options['dynamic_ribbons'] == 'Yes') {

            $current_ribbon = car_demon_dynamic_ribbon_filter($current_ribbon, $post_id, '112');

        }

    }

    if (isset($car_demon_options['popup_images'])) {

        if ($car_demon_options['popup_images'] == 'Yes') {

            $popup_imgs = ' onmouseover="cd_make_large(this)" onmouseout="cd_go_out();"';

            $lightbox_js = '';

        } else {

            $popup_imgs = '';

            $lightbox_js = ' onclick="open_car_demon_lightbox(\''.$car_title_pro.'_pic\');"';

        }

    } else {

        $popup_imgs = '';

        $lightbox_js = ' onclick="open_car_demon_lightbox(\''.$car_title_pro.'_pic\');"';

    }

    $look_close = '<img'.$lightbox_js.' src="'. $cdsp_pluginpath .'images/look_close.png" alt="New Ribbon" id="single_look_close_pro">';



    if ( defined( 'CD_CUSTOM_NO_PHOTO' ) ) {

        $img_output = '<img onerror="cdsImgError(this, \''. CD_CUSTOM_NO_PHOTO .'\');" id="'.$car_title_pro.'_pic" name="'.$car_title_pro.'_pic" class="main_photo_pro_img" src="';

    } else {

        $img_output = '<img onerror="ImgError(this, \'no_photo.gif\');" id="'.$car_title_pro.'_pic" name="'.$car_title_pro.'_pic" class="main_photo_pro_img" src="';

    }



    $main_guid = cd_main_photo( $post_id );



    if ( ! $main_guid || empty( $main_guid ) ) {

        $main_guid = trailingslashit( plugins_url() ) . trailingslashit( 'car-demon' ) . trailingslashit( 'images' ) . 'photo-coming-soon.png';

    }



    $main_guid = str_replace('-150x150','',$main_guid);

    $img_output .= $main_guid;

    $img_output .= '" />';



    $car_photo = '

		<div class="vehicle_main_photo_pro">

			'.$current_ribbon.$look_close.$img_output.'

		</div>';



    return $car_photo;

}

function cdsp_pro_single_thumbs($post_id) {

    $car_title_pro = get_car_title($post_id);

    $car_title_pro = str_replace(' ', '_', $car_title_pro);

    // Thumbnails

    $thumbnails = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' =>'image', 'orderby' => 'menu_order ID') );

    $thumbnails = array_reverse($thumbnails);

    $cnt = 0;

    $car_js = '';

    $main_guid = cd_main_photo( $post_id );

    $car_js .= 'carImg['.$cnt.']="'.trim($main_guid).'";'.chr(13);

    $photo_array = '';

    $this_car = $photo_array;

    foreach($thumbnails as $thumbnail) {

        //$guid = $thumbnail->guid;

        $guid = wp_get_attachment_url($thumbnail->ID);

        if (!empty($guid)) {

//				if ($main_guid != $guid) {

            $cnt = $cnt + 1;

            $car_js .= 'carImg['.$cnt.']="'.trim($guid).'";'.chr(13);

            $photo_array = '<div><img id="slide'.$cnt.'" class="cdsp_thumbs img_slide" src="'.trim($guid).'" /></div>';

            $this_car .= $photo_array;

//				}

        }

    }

    // Check if vehicle has a list of photo urls that arent part of the normal gallery

    $image_list = get_post_meta($post_id, '_images_value', true);



    $popup_imgs = '';

    if (!empty($image_list)) {

        $thumbnails = explode(",",$image_list);



        foreach($thumbnails as $thumbnail) {

            if (strpos($thumbnail, '150x150') > 0) {

                continue;

            }

//= too many vendors give image links that aren't really images - checking for file extension isn't viable

//				$pos = strpos($thumbnail,'.jpg');

            $pos = true;

            if($pos == true) {

                $cnt = $cnt + 1;

                $car_js .= 'carImg['.$cnt.']="'.trim($thumbnail).'";'.chr(13);

//					$photo_array = '<a href="#mainpic"></a><img id="slide'.$cnt.'" class="cdsp_thumbs img_slide" style="cursor:pointer"'.$popup_imgs.' onClick=\'MM_swapImage("'.$car_title_pro.'_pic","","'.trim($thumbnail).'",1);\' src="'.trim($thumbnail).'" />';



                $photo_array = '<div class=""><img id="slide'.$cnt.'" class="cdsp_thumbs img_slide" src="'.trim($thumbnail).'" /></div>';



                $this_car .= $photo_array;

            }

        }

    }

    // End Thumbnails

    $total_pics = $cnt;

    $html = $this_car;

    $details = '';

    $vehicle_condition = '';

    $html = apply_filters( 'cdsp_photo_hook', $html, $post_id, $details, $vehicle_condition, 'desktop' );

    return $html;

}

?>

