<?php
function cd_shortcode_init() {
	global $car_demon_options;
	if ( ! isset( $car_demon_options['inventory_page'] ) ) {
		// get homepage by default
		$car_demon_options['inventory_page'] = get_bloginfo( 'wpurl' );
		// we need to know if page is https or http
		if ( is_ssl() ) {
			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}
		// get $post_id from URL
		$post_id = url_to_postid( $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] );
		$content_post = get_post( $post_id );
		if ( $content_post ) {
			$content = $content_post->post_content;
			if ( strpos( $content, '[inventory' ) !== false) {
				$car_demon_options['inventory_page'] = get_permalink( $post_id );
			}
		}
	}
}
add_action( 'init', 'cd_shortcode_init' );

function cd_inventory_shortcode_func( $atts ) {
	global $car_demon_options;
	if ( isset( $car_demon_options['cars_per_page'] ) ) {
		$cars_per_page = $car_demon_options['cars_per_page'];
	} else {
		$cars_per_page = 9;
	}

	$atts = shortcode_atts( array(
		__( 'title', 'car-demon' ) => __( 'View our inventory', 'car-demon' ), /* Optional inventory title */
		__( 'query', 'car-demon' ) => '', /* Legacy querystring style filter, recommend do not use */
		__( 'stock', 'car-demon' ) => '', /* Filter by vehicle stock number */
		__( 'condition', 'car-demon' ) => '', /* Filter by vehicle condition */
		__( 'year', 'car-demon' ) => '', /* Filter by vehicle year */
		__( 'make', 'car-demon' ) => '', /* Filter by vehicle make */
		__( 'model', 'car-demon' ) => '', /* Filter by vehicle model */
		__( 'location', 'car-demon' ) => '', /* Filter by vehicle location */
		__( 'body_style', 'car-demon' ) => '', /* Filter by vehicle body style */
		__( 'mileage', 'car-demon' ) => '', /* Filter by maximum mileage */
		__( 'min_price', 'car-demon' ) => '', /* Filter vehicles with a price higher than this value */
		__( 'max_price', 'car-demon' ) => '', /* Filter vehicles with a price lower than this value */
		__( 'transmission', 'car-demon' ) => '', /* Filter vehicles by transmission */
		__( 'vehicle_tag', 'car-demon' ) => '', /* Filter by vehicle tag */
		__( 'show_sold', 'car-demon' ) => '', /* Show sold vehicles in list */
		__( 'criteria', 'car-demon' ) => '', /* Filter by this keyword */
		__( 'hide_sort', 'car-demon' ) => false, /* Hide the inventory sort option */
		__( 'hide_nav', 'car-demon' ) => false, /* Hide the inventory navigation */
		__( 'hide_results_found', 'car-demon' ) => false, /* Hide vehicle results found if search has been run */
		__( 'cars_per_page', 'car-demon' ) => $cars_per_page, /* List a maximum of this many vehicles */
	), $atts, 'cd_inventory' );

	$query = '';
	$query_terms = array( 
		__( 'stock', 'car-demon' ),
		__( 'condition', 'car-demon' ),
		__( 'year', 'car-demon' ),
		__( 'make', 'car-demon' ),
		__( 'model', 'car-demon' ),
		__( 'location', 'car-demon' ),
		__( 'body_style', 'car-demon' ),
		__( 'mileage', 'car-demon' ),
		__( 'min_price', 'car-demon' ),
		__( 'max_price', 'car-demon' ),
		__( 'transmission', 'car-demon' ),
		__( 'show_sold', 'car-demon' ),
		__( 'criteria', 'car-demon' ),
		__( 'cars_per_page', 'car-demon' ),
		__( 'vehicle_tag', 'car-demon' ),
		);
	foreach ( $query_terms as $term ) {
		 if ( isset( $atts[$term] ) ) {
			  $query .= ', ' . $term .'=' . $atts[$term];
		 }
	}

	$atts['query'] .= $query;
	$atts['query'] = '@@@' . $atts['query'];
	$atts['query'] = str_replace( '@@@,', '', $atts['query'] );
	$atts['query'] = str_replace( '@@@', '', $atts['query'] );
	
	global $cd_doing_shortcode;
	$cd_doing_shortcode = true;
	
	$widget = cd_show_inventory( $atts );
	return $widget;
}
add_shortcode( __( 'cd_inventory', 'car-demon' ), 'cd_inventory_shortcode_func' );

function cd_show_inventory( $atts ) {
	global $car_demon_options;
	$searched = '';
	$sorting = '';
	if ( isset( $atts['hide_sort'] ) ) {
		if ( $atts['hide_sort'] == false ) {
			$sorting = car_demon_sorting( 'archive' );
		}
	} else {
		$sorting = car_demon_sorting( 'archive' );
	}

	$query = array();
	$query['car'] = 1;
	if ( isset( $atts['query'] ) ) {
		if ( ! empty( $atts['query'] ) ) {
			if ( strpos( $atts['query'], ',' ) !== false ) {
				$query_parts = explode( ',', $atts['query'] );
			} else {
				$query_parts = array( $atts['query'] );
			}
			foreach ( $query_parts as $query_part ) {
				$part = explode( '=', $query_part );
				if ( ! empty( $part[1] ) ) {
					if ( trim( $part[0] ) != 'stock' && trim( $part[0] ) != 'mileage' && trim( $part[0] ) != 'min_price'  && trim( $part[0] ) != 'max_price'  && trim( $part[0] ) != 'show_sold' && trim( $part[0] ) != 'criteria' && trim( $part[0] ) != 'cars_per_page' ) {
						$query['search_'.trim( $part[0] )] = $part[1];
					} else {
						if ( trim( $part[0] ) == 'mileage' ) {
							$query['search_dropdown_miles'] = $part[1];
						} else if ( trim( $part[0] ) == 'criteria' ) {
							$query['criteria'] = $part[1];
						} else if ( trim( $part[0] ) == 'min_price' ) {
							$query['search_dropdown_Min_price'] = $part[1];
						} else if ( trim( $part[0] ) == 'max_price' ) {
							$query['search_dropdown_Max_price'] = $part[1];
						} else if ( trim( $part[0] ) == 'cars_per_page' ) {
							$query['cars_per_page'] = $part[1];
						} else {
							$query[trim( $part[0] )] = $part[1];	
						}
					}
				}
			}
		}
	}

	$car_demon_query = car_demon_query_search( $query );
	unset($car_demon_query['pagename']);

	global $post;
	$post_id = $post->ID;
	$result_page = get_permalink( $post_id );

	$car_query = new WP_Query();
	$car_query->query( $car_demon_query );
	$total_results = $car_query->found_posts;
	$searched = car_demon_get_searched_by( $result_page );

	$total_results = $car_query->found_posts;
	
	if ( isset( $atts['hide_nav'] ) ) {
		if ( $atts['hide_nav'] == false ) {
			$html = car_demon_dynamic_load();
		}
	} else {
		$html = car_demon_dynamic_load();
	}

	ob_start();
	do_action( 'cd_before_content_srp_action', $atts );
	do_action( 'car_demon_before_main_content' ); //= deprecated
	do_action( 'cd_before_content_action' );
	$html .= ob_get_contents();
	ob_end_clean();

		$html .= $car_demon_options['before_listings'];
		$html .= $sorting;
		if ( isset( $atts['hide_results_found'] ) ) {
			if ( $atts['hide_results_found'] == false ) {
				$results_found = '<h4 class="results_found car_list">' . __( 'Results Found','car-demon' ) . ': ' . $total_results . '</h4>';
				$html .= apply_filters( 'cd_results_found_filter', $results_found, $total_results );
				$html .= $searched;
			}
		} else {
			$results_found = '<h4 class="results_found car_list">' . __( 'Results Found','car-demon' ) . ': ' . $total_results . '</h4>';
			$html .= apply_filters( 'cd_results_found_filter', $results_found, $total_results );
			$html .= $searched;
		}

		if ( isset( $atts['hide_nav'] ) ) {
			if ( $atts['hide_nav'] == false ) {
				$html .= car_demon_nav( 'top', $car_query );
			}
		} else {
			$html .= car_demon_nav( 'top', $car_query );
		}
	
		if ( $car_query->have_posts() ) : while ( $car_query->have_posts() ) : $car_query->the_post();
			$post_id = $car_query->post->ID;
			if ( function_exists( 'cdcr_loop' ) ) {
				$loop = '<div class="car_item">' . cdcr_loop( '', $post_id ) . '</div>';
			} else {
				if ( isset( $car_demon_options['use_theme_files'] ) ) {
					if ( $car_demon_options['use_theme_files'] == 'Yes' ) {
						$loop = car_demon_display_car_list( $post_id );
					} else {
						$loop = '<div class="car_item">' . car_demon_display_car_list( $post_id ) . '</div>';
					}
				} else {
					$loop = '<div class="car_item">' . car_demon_display_car_list( $post_id ) . '</div>';					
				}
			}
			$loop = apply_filters( 'car_demon_display_car_list_filter', $loop, $post_id ); //= deprecated
			$html .= apply_filters( 'cd_srp_filter', $loop, $post_id );
			
		endwhile; endif;

		if ( isset( $atts['hide_nav'] ) ) {
			if ( $atts['hide_nav'] == false ) {
				$nav_bottom = car_demon_nav( 'bottom', $car_query );
				$nav_bottom = str_replace( 'wp-pagenavi', 'wp-pagenavi inventory_nav_bottom', $nav_bottom );
				$html .= $nav_bottom;
			}
		} else {
			$nav_bottom = car_demon_nav( 'bottom', $car_query );
			$nav_bottom = str_replace( 'wp-pagenavi', 'wp-pagenavi inventory_nav_bottom', $nav_bottom );
			$html .= $nav_bottom;
		}

	ob_start();	
	do_action( 'car_demon_after_main_content' ); //= deprecated
	do_action( 'cd_after_content_action' );
	do_action( 'cd_after_content_srp_action', $atts );
	$html .= ob_get_contents();
	ob_end_clean();
	wp_reset_query();
	return $html;
}

?>