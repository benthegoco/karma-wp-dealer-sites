<?php
function get_vehicle_price_shortcode($post_id) {
	global $car_demon_options;
	$is_sold = get_post_meta($post_id, 'sold', true);
	$spacer = '';
	$vehicle_condition = '';
	$vehicle_price_pack = 0;
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
	if ($is_sold == "Yes") {
		$sold = "<div class='car_sold'>".__("SOLD", 'car-demon-shortcode')."</div>";
		return $sold;
	}

	$vehicle_location = get_cd_term( $post_id, 'vehicle_location' );
	if ($vehicle_location == '') {
		$vehicle_location = cd_get_default_location_name();
		$vehicle_location_slug = cd_get_default_location_slug();
	} else {
		$vehicle_location_term = get_term_by('name', $vehicle_location, 'vehicle_location');
		$vehicle_location_slug = $vehicle_location_term->slug;
		$vehicle_condition = get_cd_term( $post_id, 'vehicle_condition' );
	}
	if ($vehicle_condition == 'New') {
		$show_price = get_option($vehicle_location_slug.'_show_new_prices');
	} else {
		$show_price = get_option($vehicle_location_slug.'_show_used_prices');
	}
	if ( $show_price == __( 'Yes', 'car-demon' ) ) {
		$vehicle_price = get_post_meta($post_id, "_price_value", true);
		$vehicle_price_pack = (int)$vehicle_price;
		if ($vehicle_price == 0) {
			$vehicle_price = get_option($vehicle_location_slug.'_no_new_price');
		}
	} else {
		$vehicle_price = get_option($vehicle_location_slug.'_no_new_price');
	}
	if (!empty($vehicle_price)) {
		$vehicle_price = str_replace('.00','',$vehicle_price);
		$vehicle_price = str_replace('$','',$vehicle_price);
		$vehicle_price = str_replace(',','',$vehicle_price);
		if (is_numeric($vehicle_price)) {
			$vehicle_price = number_format($vehicle_price, 2);
			$vehicle_price = apply_filters( 'cd_price_format', $vehicle_price );
		}
	} else {
		$vehicle_price = get_option($vehicle_location_slug.'_no_new_price');
	}
	if ($vehicle_price_pack == 0) {
		$final_price = $vehicle_price;
	} else {
		$final_price = $currency_symbol.$vehicle_price.$currency_symbol_after;
	}

	$final_price = apply_filters( 'car_demon_price_filter', $final_price );
	return $final_price;
}
function remove_cdsp_vehicle_widget(){
	unregister_sidebar( 'single_vehicle_header' );
	unregister_sidebar( 'car_page' );
}
add_action( 'widgets_init', 'remove_cdsp_vehicle_widget', 11 );
function cdsp_pro_get_vehicle_sidebar() {
	if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Vehicle Detail Pages')) :
	endif;
}
add_action('cdsp_pro_vehicle_sidebar', 'cdsp_pro_get_vehicle_sidebar', 14);
function register_cdsp_pro_vst_sidebar() {
	register_sidebar(array(
	  'name' => __( 'Vehicle Summary Top' ),
	  'id' => 'car_page_pro_vst',
	  'description' => __( 'Widgets in this area will be shown in the top of the vehicle summary.' ),
	  'before_title' => '<h1>',
	  'after_title' => '</h1>',
	  'before_widget' => '<div id="%1$s" class="widget-vst %2$s">',
	  'after_widget' => '</div>'
	));
}
add_action( 'widgets_init', 'register_cdsp_pro_vst_sidebar', 1 );
function cdsp_pro_get_vst_sidebar($post_id) {
	$sidebar_contents = "";
	ob_start();
	dynamic_sidebar('Vehicle Summary Top');
	$sidebar_contents = ob_get_clean();
	return $sidebar_contents;
}
function register_cdsp_pro_vsb_sidebar() {
	register_sidebar(array(
	  'name' => __( 'Vehicle Summary Bottom' ),
	  'id' => 'car_page_pro_vsb',
	  'description' => __( 'Widgets in this area will be shown in the bottom of the vehicle summary.' ),
	  'before_title' => '<h1>',
	  'after_title' => '</h1>',
	  'before_widget' => '<div id="%1$s" class="widget-vsb %2$s">',
	  'after_widget' => '</div>'
	));
}
add_action( 'widgets_init', 'register_cdsp_pro_vsb_sidebar', 1 );
function cdsp_pro_get_vsb_sidebar($post_id) {
	$sidebar_contents = "";
	ob_start();
	dynamic_sidebar('Vehicle Summary Bottom');
	$sidebar_contents = ob_get_clean();
	return $sidebar_contents;
}
function register_cdsp_pro_srp_sidebar() {
	if ( defined( 'CDSP_SRP_SIDEBAR' ) ) {
		register_sidebar(array(
		  'name' => __( 'SRP Sidebar' ),
		  'id' => 'car_page_pro_srp',
		  'description' => __( 'Widgets in this area will be shown in the bottom of the Style #1 SRP.' ),
		  'before_title' => '<h1>',
		  'after_title' => '</h1>',
		  'before_widget' => '<div id="%1$s" class="widget-vsb %2$s">',
		  'after_widget' => '</div>'
		));
	}
}
add_action( 'widgets_init', 'register_cdsp_pro_srp_sidebar', 1 );
function cdsp_pro_get_srp_sidebar($post_id) {
	$sidebar_contents = "";
	ob_start();
	dynamic_sidebar('SRP Sidebar');
	$sidebar_contents = ob_get_clean();
	$sidebar_contents = cdsp_tag_filter( $post_id, $sidebar_contents );
	return $sidebar_contents;
}
function cdsp_pro_display_similar_cars($body_style, $current_id) {
	global $wpdb;
	$show_it = '';
	$cdsp_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$cdsp_pluginpath = str_replace('includes','theme-files',$cdsp_pluginpath);
	$my_tag_id = get_term_by('slug', $body_style, 'vehicle_body_style');
	if (!empty($body_style)) {
		if (!empty($my_tag_id)) {
			$my_search = " AND $wpdb->term_taxonomy.taxonomy = 'vehicle_body_style' AND $wpdb->term_taxonomy.term_id IN(".$my_tag_id->term_id.")";
			$str_sql = "SELECT wposts.ID
				FROM $wpdb->posts wposts
					LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
					LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
					LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
				WHERE wposts.post_type='cars_for_sale'
					AND wposts.post_status = 'publish'
					AND wpostmeta.meta_key = 'sold'
					AND wpostmeta.meta_value = 'no'".$my_search.'
					ORDER BY ID LIMIT 4';
			$the_lists = $wpdb->get_results($str_sql);
		} else {
			$the_lists = '';		
		}
	} else {
		$the_lists = '';
	}
	$car = '';
	$cnt = 0;
	if (!empty($the_lists)) {
		$car .= '<h3 class="other_great_deals_title">'.__('Other Great Deals','car-demon-shortcode').'</h3>';
		$car .= '<div class="similar_cars_box">';
		foreach ($the_lists as $the_list) {
			$post_id = $the_list->ID;
			if ($post_id != $current_id) {
				$cnt = $cnt + 1;
				if ($cnt < 4) {
					$show_it = 1;
					$car .= cdt_loop('',$post_id);
				} else {
					break;
				}
			}
		}
		$car .= '</div>';
	}
	if ($show_it != 1) {
		$car = '';
	}
	return $car;
}

?>