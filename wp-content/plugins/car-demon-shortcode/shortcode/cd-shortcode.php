<?php
add_action('init', 'cdsp_shortcode_init');
function cdsp_shortcode_init() {
	global $car_demon_options;
	if (!isset($car_demon_options['inventory_page'])) {
		// get homepage by default
		$car_demon_options['inventory_page'] = get_bloginfo('wpurl');
		// we need to know if page is https or http
		if (is_ssl()) {
			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}
		// get $post_id from URL
		$post_id = url_to_postid( $protocol.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] );
		$content_post = get_post($post_id);
		if ($content_post) {
			$content = $content_post->post_content;
			if (strpos($content, '[inventory') !== false) {
				$car_demon_options['inventory_page'] = get_permalink($post_id);	
			}
		}
	}
}

function cdsp_inventory_shortcode_func( $atts ) {
	global $car_demon_options;
	if (isset($car_demon_options['cars_per_page'])) {
		$cars_per_page = $car_demon_options['cars_per_page'];
	} else {
		$cars_per_page = 9;
	}

	$atts = shortcode_atts( array(
		'title' => 'View our inventory',
		'style' => '',
		'query'=> '',
		'stock' => '',
		'condition' => '',
		'year' => '',
		'make' => '',
		'model' => '',
		'location' => '',
		'body_style' => '',
		'mileage' => '',
		'min_price' => '',
		'max_price' => '',
		'transmission' => '',
		'vehicle_tag' => '',
		'criteria' => '',
		'hide_sort' => false,
		'hide_nav' => false,
		'hide_results_found' => false,
		'cars_per_page' => $cars_per_page
	), $atts, 'car-demon-shortcode' );
	
	$query = '';
	
	$query_terms = array('stock', 'condition', 'year', 'make', 'model', 'location', 'body_style', 'vehicle_tag', 'mileage', 'min_price', 'max_price', 'transmission', 'criteria', 'cars_per_page');
	foreach ($query_terms as $term) {
		 if (isset($atts[$term])) {
			  $query .= ', ' . $term .'=' . $atts[$term];
		 }
	}
	
	$atts['query'] .= $query;
	$atts['query'] = '@@@' . $atts['query'];
	$atts['query'] = str_replace('@@@,', '', $atts['query']);
	$atts['query'] = str_replace('@@@', '', $atts['query']);

	$widget = cdsp_show_inventory($atts);
	return $widget;
}
add_shortcode( 'pro_inventory', 'cdsp_inventory_shortcode_func' );

function cdsp_show_inventory($atts) {
	global $car_demon_options;
	$searched = '';
	$sorting = '';
	if (isset($atts['hide_sort'])) {
		if ($atts['hide_sort'] == false) {
			$sorting = car_demon_sorting('search');
		}
	} else {
		$sorting = car_demon_sorting('search');	
	}
	$query = array();
	$query['car'] = 1;
	if (isset($atts['query'])) {
		if (!empty($atts['query'])) {
			if (strpos($atts['query'], ',') !== false) {
				$query_parts = explode(',', $atts['query']);
			} else {
				$query_parts = array($atts['query']);
			}
			foreach ($query_parts as $query_part) {
				$part = explode('=', $query_part);
				if (!empty($part[1])) {
					if (trim($part[0]) != 'stock' && trim($part[0]) != 'mileage' && trim($part[0]) != 'min_price'  && trim($part[0]) != 'max_price'  && trim($part[0]) != 'criteria' && trim($part[0]) != 'cars_per_page') {
						$query['search_'.trim($part[0])] = $part[1];
					} else {
						if (trim($part[0]) == 'mileage') {
							$query['search_dropdown_miles'] = $part[1];
						} else if (trim($part[0]) == 'criteria') {
							$query['criteria'] = $part[1];
						} else if (trim($part[0]) == 'min_price') {
							$query['search_dropdown_Min_price'] = $part[1];
						} else if (trim($part[0]) == 'max_price') {
							$query['search_dropdown_Max_price'] = $part[1];
						} else if (trim($part[0]) == 'cars_per_page') {
							$query['cars_per_page'] = $part[1];
						} else {
							$query[trim($part[0])] = $part[1];	
						}
					}
				}
			}
		}
	}
	$cdsp_query = car_demon_query_search( $query );
	unset($cdsp_query['pagename']);
	global $post;
	$post_id = $post->ID;
	$result_page = get_permalink($post_id);
	$car_query = new WP_Query();
	$car_query->query($cdsp_query);
	$total_results = $car_query->found_posts;
	$searched = car_demon_get_searched_by($result_page);
	$total_results = $car_query->found_posts;
	if (isset($atts['hide_nav'])) {
		if ($atts['hide_nav'] == false) {
			$html = car_demon_dynamic_load();
		}
	} else {
		$html = car_demon_dynamic_load();
	}
	ob_start();
		do_action( 'car_demon_before_main_content_srp', $atts );
		do_action( 'car_demon_before_main_content' );
		$html .= ob_get_contents();
	ob_end_clean();
		$html .= $car_demon_options['before_listings'];
		$html .= $sorting;
		if (isset($atts['hide_results_found'])) {
			if ($atts['hide_results_found'] == false) {
				$html .= '<h4 class="results_found car_list">'. __('Results Found','car-demon').': '.$total_results.'</h4>';
				$html .= $searched;
			}
		} else {
			$html .= '<h4 class="results_found car_list">'. __('Results Found','car-demon').': '.$total_results.'</h4>';
			$html .= $searched;
		}

		if (isset($atts['hide_nav'])) {
			if ($atts['hide_nav'] == false) {
				$html .= car_demon_nav('top', $car_query);
			}
		} else {
			$html .= car_demon_nav('top', $car_query);
		}
		
		$html .= '<div class="clearFloat"></div>';
		if ($car_query->have_posts()) : while ($car_query->have_posts()) : $car_query->the_post();
			$post_id = $car_query->post->ID;
			if (isset($atts['style'])) {
				if ($atts['style'] == 1) {
					$loop = cdsp_template_1($post_id);
				} else if ($atts['style'] == 2) {
					$loop = cdsp_template_2($post_id);
				} else if ($atts['style'] == 3) {
					$loop = cdsp_template_3($post_id);
				} else if ($atts['style'] == 4) {
					$loop = cdsp_template_4($post_id);
				} else if ($atts['style'] == 5) {
					$loop = cdsp_template_5($post_id);
				} else if ($atts['style'] == 6) {
					$loop = cdsp_template_6($post_id);
				// template 7 is the original default template
				} else if ($atts['style'] == 8) {
					$loop = cdsp_template_8($post_id);
				} else if ($atts['style'] == 9) {
					$loop = cdsp_template_9($post_id);
				} else {
					$loop = cdt_loop($post_id);
				}
			} else {
				$loop = cdt_loop($post_id);
			}
			$html .= apply_filters('car_demon_display_car_list_filter', $loop, $post_id );
		endwhile; endif;
		$html .= '<div class="cdsc_clear"></div>';
		if (isset($atts['hide_nav'])) {
			if ($atts['hide_nav'] == false) {
				$nav_bottom = car_demon_nav('bottom', $car_query);
				$nav_bottom = str_replace('wp-pagenavi', 'wp-pagenavi inventory_nav_bottom', $nav_bottom);
				$html .= $nav_bottom;
			}
		} else {
			$nav_bottom = car_demon_nav('bottom', $car_query);
			$nav_bottom = str_replace('wp-pagenavi', 'wp-pagenavi inventory_nav_bottom', $nav_bottom);
			$html .= $nav_bottom;
		}

	ob_start();	
		do_action( 'car_demon_after_main_content' );
		do_action( 'car_demon_after_main_content_srp', $atts );
		$html .= ob_get_contents();
	ob_end_clean();
	return $html;
}
?>