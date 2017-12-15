<?php
/**
 * Query for Car Demon Search
 *
 * @param $query optional query terms
 *
 * @todo
 *     - consolidate with car_demon_archive_query()
 */
function car_demon_query_search( $query = array() ) {
	global $car_demon_options;
	$get = array_merge( $_GET, $query );

	if ( is_array( $get ) ) {
		foreach ( $get as $key=>$value ) {
			if ( $value == 'ALL' ) {
				$get[$key] = '';
			}
		}
	}

	if ( isset( $car_demon_options['cars_per_page'] ) ) {
		$cars_per_page = $car_demon_options['cars_per_page'];
	} else {
		$cars_per_page = 9;
	}
	if ( isset( $get['cars_per_page'] ) ) {
		$cars_per_page = $get['cars_per_page'];
	}

	if ( isset( $get['car'] ) ) {
		add_filter( 'wp_title', 'car_demon_filter_search_title', 10, 3 );
		if ( $car_demon_options['sort_price'] == __( 'yes', 'car-demon' ) || $car_demon_options['sort_price'] == __( 'Yes', 'car-demon' ) ) {
			$order_by = '_price_value';
		} else {
			if ( $car_demon_options['sort_miles'] == __( 'yes', 'car-demon' ) || $car_demon_options['sort_miles'] == __( 'Yes', 'car-demon' ) ) {
				$order_by = '_mileage_value';
			} else {
				$order_by = '';
			}
		}
		$order_by_dir = 'ASC';
		if ( isset( $get['order_by'] ) ) {
			$order_by = sanitize_text_field( $get['order_by'] );
		}
		if ( isset( $get['order_by_dir'] ) ) {
			$order_by_dir = sanitize_text_field( $get['order_by_dir'] );
		}	

		if ( is_home() || is_front_page() ) {
			$paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
		} else {
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		}

		if ( $car_demon_options['show_sold'] != __( 'Yes', 'car-demon' ) ) {
			$meta_query = array(
				array(
					'key' => 'sold',
					'value' => __( 'No', 'car-demon' ),
					'compare' => '=',
				)
			);
		} else {
			$meta_query = array();
		}
		if ( isset( $get['show_sold'] ) ) {
			if ( $get['show_sold'] == __( 'Yes', 'car-demon' ) ) {
				$meta_query = array();
			}
		}
		if ( isset( $get['stock'] ) ) {
			if ( $get['stock'] ) {
				$stock = sanitize_text_field( $get['stock'] );
				$meta_query = array_merge( $meta_query, array( array( 'key' => '_stock_value','value' => $stock, 'compare' => '=', 'type' => 'text' ) ) );
			}
		}
		if ( isset( $get['search_dropdown_miles'] ) ) {
			if ( $get['search_dropdown_miles'] ) {
				$miles = sanitize_text_field( $get['search_dropdown_miles'] );
				$meta_query = array_merge( $meta_query, array( array( 'key' => '_mileage_value','value' => $miles, 'compare' => '<', 'type' => 'numeric' ) ) );
			}
		}
		if ( isset( $get['search_transmission'] ) ) {
			$get['search_dropdown_tran'] = $get['search_transmission'];
		}
		if ( isset( $get['search_dropdown_tran'] ) ) {
			if ( $get['search_dropdown_tran'] ) {
				$trans = sanitize_text_field( $get['search_dropdown_tran'] );
				$meta_query = array_merge( $meta_query, array( array( 'key' => '_transmission_value','value' => $trans, 'compare' => '=', 'type' => 'text' ) ) );
			}
		}
		
		if ( isset( $get['search_dropdown_Min_price'] ) ) {
			$min_price = sanitize_text_field( $get['search_dropdown_Min_price'] );
		} else {
			$min_price = '';
		}
		if ( isset( $get['search_dropdown_Max_price'] ) ) {
			$max_price = sanitize_text_field( $get['search_dropdown_Max_price'] );
		} else {
			$max_price = '';
		}
		
		if ( $max_price > 0 ) {
			if ( $min_price == 0 ) { $min_price = -1; }
			$meta_query = array_merge( $meta_query, array( array( 'key' => '_price_value','value' => array( $min_price, $max_price ), 'compare' => 'BETWEEN', 'type' => 'numeric' ) ) );
		} else {
			if ( $min_price > 0 ) {
				$meta_query = array_merge( $meta_query, array( array( 'key' => '_price_value','value' => $min_price, 'compare' => '>', 'type' => 'numeric' ) ) );
			}
		}

		if ( isset( $get['search_dropdown_miles_Min'] ) ) {
			$min_mileage = sanitize_text_field( $get['search_dropdown_miles_Min'] ) - 1;
		} else {
			$min_mileage = '';
		}
		if ( isset( $get['search_dropdown_miles_Max'] ) ) {
			$max_mileage = sanitize_text_field( $get['search_dropdown_miles_Max'] ) + 1;
		} else {
			$max_mileage = '';
		}
		
		if ( $max_mileage > 0 ) {
			if ( $min_mileage == 0 ) { $min_mileage = 1; }
			$meta_query = array_merge( $meta_query, array( array( 'key' => '_mileage_value','value' => array( $min_mileage, $max_mileage ), 'compare' => 'BETWEEN', 'type' => 'numeric' ) ) );
		} else {
			if ( $min_mileage > 0 ) {
				$meta_query = array_merge( $meta_query, array( array( 'key' => '_mileage_value','value' => $min_mileage, 'compare' => '>', 'type' => 'numeric' ) ) );
			}
		}

		// Search decode field
		if ( isset( $get['criteria'] ) ) {
			$criteria = sanitize_text_field( $get['criteria'] );
			if ( $criteria ) {
				if ( strpos( $criteria, ',' ) ) {
					$criteria_array = explode( ',', $criteria );
					foreach( $criteria_array as $search_criteria ) {
						$meta_query = array_merge( $meta_query, array( array( 'key' => 'decode_string','value' => $search_criteria, 'compare' => 'LIKE', 'type' => 'text' ) ) );
					}
				} else {
					$meta_query = array_merge( $meta_query, array( array( 'key' => 'decode_string','value' => $criteria, 'compare' => 'LIKE', 'type' => 'text' ) ) );
				}
			}
		}
		if ( isset( $get['search_location'] ) ) {
			$search_location = sanitize_text_field( $get['search_location'] );
			$car_query = array(
					'post_type' => 'cars_for_sale',
					'is_paged' => true,
					'paged' => $paged,
					'posts_per_page' => $cars_per_page,
					'meta_query' => $meta_query,
					'orderby' => 'meta_value_num',
					'meta_key' => $order_by,
					'order' => $order_by_dir,
					'taxonomy' =>'vehicle_location',
					'term' => $search_location,
				);
		} else {
			$car_query = array(
					'post_type' => 'cars_for_sale',
					'is_paged' => true,
					'paged' => $paged,
					'posts_per_page' => $cars_per_page,
					'meta_query' => $meta_query,
					'orderby' => 'meta_value_num',
					'meta_key' => $order_by,
					'order' => $order_by_dir,
				);
			$search_location = '';
		}
		$car_query = array(
				'post_type' => 'cars_for_sale',
				'is_paged' => true,
				'paged' => $paged,
				'posts_per_page' => $cars_per_page,
				'meta_query' => $meta_query,
				'orderby' => 'meta_value_num',
				'meta_key' => $order_by,
				'order' => $order_by_dir,
				'taxonomy' =>'vehicle_location',
				'term' => $search_location
			);
			
			if ( isset( $get['search_dropdown_Min_years'] ) ) {
				$min_year = sanitize_text_field( $get['search_dropdown_Min_years'] );
			} else {
				$min_year = '';
			}
			if ( isset( $get['search_dropdown_Max_years'] ) ) {
				$max_year = sanitize_text_field( $get['search_dropdown_Max_years'] );
			} else {
				$max_year = '';
			}
			
			if ( ! empty( $min_year ) ) {
				if ( $min_year == $max_year ) {
					$get['search_year'] = $max_year;
					unset( $get['search_dropdown_Min_years'] );
					unset( $get['search_dropdown_Max_years'] );
					$min_year = '';
					$max_year = '';
				}
			}
			
			if ( $max_year > 0 ) {
				if ( $min_year == 0 ) { $min_year = 1984; }
				if ( empty( $min_year ) ) { $min_year = 1984; }
				$all_years = array();
				$number_of_years = $max_year - $min_year;
				$start_year = $min_year;
				while ( $start_year < $max_year ) {
					$all_years[] = $start_year;
					++$start_year;
				}
				$tax_query = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'vehicle_year',
							'terms' => $all_years,
							'field' => 'slug',
							'compare' => 'BETWEEN',
							'type' => 'numeric',
						)
					));
				$car_query = array_merge( $car_query, $tax_query );
			}
			
			if ( isset( $get['search_year'] ) ) {
				if ( $get['search_year'] ) {
					$search_year = sanitize_text_field( $get['search_year'] );
					$car_query = array_merge( $car_query, array( 'vehicle_year' => $search_year ) );
				}
			}
			if ( isset( $get['search_condition'] ) ) {
				if ( $get['search_condition'] ) {
					if ( strpos( $get['search_condition'], '|' ) !== false ) {
						$condition_array = explode( '|', $get['search_condition'] );
						$condition_tax = array();
						$condition_tax['taxonomy'] = 'vehicle_condition';
						$condition_tax['terms'] = $condition_array;
						$condition_tax['field'] = 'slug';
						$condition_tax['compare'] = '=';
						$condition_tax['type'] = 'string';
						unset($car_query['vehicle_condition']);
						$car_query['tax_query'][] = $condition_tax;
					} else {
						$search_condition = sanitize_text_field( $get['search_condition'] );
						$car_query = array_merge($car_query, array( 'vehicle_condition' => $search_condition ) );
					}
				}
			}
			if ( isset( $get['search_make'] ) ) {
				if ( $get['search_make'] ) {
					if ( strpos( $get['search_make'], '|' ) !== false ) {
						$make_array = explode( '|', $get['search_make'] );
						$make_tax = array();
						$make_tax['taxonomy'] = 'vehicle_make';
						$make_tax['terms'] = $make_array;
						$make_tax['field'] = 'slug';
						$make_tax['compare'] = '=';
						$make_tax['type'] = 'string';
						unset($car_query['vehicle_make']);
						$car_query['tax_query'][] = $make_tax;
					} else {
						$search_make = sanitize_text_field( $get['search_make'] );
						$car_query = array_merge( $car_query, array( 'vehicle_make' => $search_make ) );
					}
				}
			}
			if ( isset( $get['search_model'] ) ) {
				if ( $get['search_model'] ) {
					if ( strpos( $get['search_model'], '|' ) !== false ) {
						$model_array = explode( '|', $get['search_model'] );
						$model_tax = array();
						$model_tax['taxonomy'] = 'vehicle_model';
						$model_tax['terms'] = $model_array;
						$model_tax['field'] = 'slug';
						$model_tax['compare'] = '=';
						$model_tax['type'] = 'string';
						unset($car_query['vehicle_model']);
						$car_query['tax_query'][] = $model_tax;
					} else {
						$search_model = sanitize_text_field( $get['search_model'] );
						$car_query = array_merge( $car_query, array( 'vehicle_model' => $search_model ) );
					}
				}
			}
			if ( isset( $get['search_vehicle_tag'] ) ) {
				if ( $get['search_vehicle_tag'] ) {
					if ( strpos( $get['search_vehicle_tag'], '|' ) !== false ) {
						$tag_array = explode( '|', $get['search_vehicle_tag'] );
						$tag_tax = array();
						$tag_tax['taxonomy'] = 'vehicle_tag';
						$tag_tax['terms'] = $tag_array;
						$tag_tax['field'] = 'slug';
						$tag_tax['compare'] = '=';
						$tag_tax['type'] = 'string';
						unset($car_query['vehicle_tag']);
						$car_query['tax_query'][] = $tag_tax;
					} else {
						$search_vehicle_tag = sanitize_text_field( $get['search_vehicle_tag'] );
						$car_query = array_merge( $car_query, array( 'vehicle_tag' => $search_vehicle_tag ) );
					}
				}
			}
			if ( isset( $get['search_body_style'] ) ) {
				$get['search_dropdown_body'] = $get['search_body_style'];				
			}
			if ( isset( $get['search_dropdown_body'] ) ) {
				if ( $get['search_dropdown_body'] ) {
					if ( strpos( $get['search_dropdown_body'], '|' ) !== false ) {
						$body_style_array = explode( '|', $get['search_dropdown_body'] );
						$body_tax = array();
						$body_tax['taxonomy'] = 'vehicle_body_style';
						$body_tax['terms'] = $body_style_array;
						$body_tax['field'] = 'slug';
						$body_tax['compare'] = '=';
						$body_tax['type'] = 'string';
						unset($car_query['vehicle_body_style']);
						$car_query['tax_query'][] = $body_tax;
					} else {
						$search_dropdown_body = sanitize_text_field( $get['search_dropdown_body'] );
						$car_query = array_merge( $car_query, array( 'vehicle_body_style' => $search_dropdown_body ) );
					}
				}
			}
		$car_query = apply_filters( 'cd_query_filter', $car_query );
		$car_query = apply_filters( 'car_demon_query_filter', $car_query ); //= deprecated
		return $car_query;
	}
}

/**
 * Query for Car Demon archive pages
 *
 * @todo
 *     - consolidate with car_demon_search_query()
 */
function car_demon_query_archive() {
	global $car_demon_options;
	global $query_string;
	if ( isset( $car_demon_options['cars_per_page'] ) ) {
		$cars_per_page = $car_demon_options['cars_per_page'];
	} else {
		$cars_per_page = 9;
	}
	$order_by = '_price_value';
	$order_by_dir = 'ASC';
	if ( isset( $_GET['order_by'] ) ) {
		$order_by = sanitize_text_field( $_GET['order_by'] );
	} else {
		$order_by = '';
	}
	if ( isset( $_GET['order_by_dir'] ) ) {
		$order_by_dir = sanitize_text_field( $_GET['order_by_dir'] );
	} else {
		$order_by_dir = '';
	}
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	if ( isset( $_GET['search_dropdown_Min_price'] ) ) {
		$min_price = sanitize_text_field( $_GET['search_dropdown_Min_price'] );
	} else {
		$min_price = '';
	}
	if ( isset( $_GET['search_dropdown_Max_price'] ) ) {
		$max_price = sanitize_text_field( $_GET['search_dropdown_Max_price'] );
	} else {
		$max_price = '';
	}
	if ( $car_demon_options['show_sold'] != __( 'Yes', 'car-demon' ) ) {
		$meta_query = array(
				array(
					'key' => 'sold',
					'value' => __( 'No', 'car-demon' ),
					'compare' => '=',
				)
			);
	}
	$car_query = array(
			'post_type' => 'cars_for_sale',
			'is_paged' => true,
			'paged' => $paged,
			'posts_per_page' => $cars_per_page,
			'meta_query' => $meta_query,
			'orderby' => 'meta_value_num',
			'meta_key' => $order_by,
			'order' => $order_by_dir,
		);
	$car_query = wp_parse_args( $query_string, $car_query );
	$car_query = apply_filters( 'cd_query_filter', $car_query );
	$car_query = apply_filters( 'car_demon_query_filter', $car_query ); //= deprecated
	return $car_query;
}

/**
 * Vehicle sorting form
 *
 * @param $page_type either search or archive
 *
 * @todo
 *     - consolidate with car_demon_archive_query()
 */
function car_demon_sorting( $page_type = 'search' ) {
	global $car_demon_options;
	$wpurl = site_url();
	$query_string = $_SERVER['QUERY_STRING'];
	$query_string = str_replace( '&order_by_dir=desc', '', $query_string );
	$query_string = str_replace( '&order_by_dir=asc', '', $query_string );
	$query_string = str_replace( '&order_by=_price_value', '', $query_string );
	$query_string = str_replace( '&order_by=_mileage_value', '', $query_string );
	$wpurl_img = $wpurl.'/wp-content/plugins/car-demon/theme-files/images/';
	if ( $page_type == 'search' ) {
		$wpurl = $wpurl .'?'. $query_string;
	} else {
		$wpurl = '?'. $query_string;
	}
	$car_demon_sorting = '';
	$do_sort = 1;
	$sort_price = 1;
	$sort_miles = 1;
	if ( isset( $car_demon_options['do_sort'] ) ) {
		if ( $car_demon_options['do_sort'] == 'No' ) {
			$do_sort = 0;
		}
	}
	if ( $do_sort == 1 ) {
		$car_demon_sorting = '<span class="cd_sort_label">' .__( 'Sort By:', 'car-demon' ) .'</span>';
		if ( isset( $car_demon_options['sort_price'] ) ) {
			if ( $car_demon_options['sort_price'] == 'No' ) {
				$sort_price = 0;
			}
		}
		if ( $sort_price == 1 ) {
			$sort_asc_img = '<a href="' . $wpurl . '&order_by=_price_value&order_by_dir=asc"><img src="' . $wpurl_img . 'sort_asc.png" title="' . __( 'Sort Low to High', 'car-demon' ) . '" /></a>&nbsp;';
			$sort_desc_img = '<a href="' . $wpurl . '&order_by=_price_value&order_by_dir=desc"><img src="' . $wpurl_img . 'sort_desc.png" title="' . __( 'Sort High to Low', 'car-demon' ) . '" /></a>';
			$car_demon_sorting .= '&nbsp;&nbsp;&nbsp;' . __( 'Price', 'car-demon' ) . ' ' . $sort_asc_img . $sort_desc_img;
		}
		if ( isset( $car_demon_options['sort_miles'] ) ) {
			if ( $car_demon_options['sort_miles'] == 'No' ) {
				$sort_miles = 0;
			}
		}
		if ( $sort_miles == 1 ) {
			$sort_asc_img = '<a href="' . $wpurl . '&order_by=_mileage_value&order_by_dir=asc"><img src="' . $wpurl_img . 'sort_asc.png" title="' . __( 'Sort Low to High', 'car-demon' ) . '" /></a>&nbsp;';
			$sort_desc_img = '<a href="' . $wpurl . '&order_by=_mileage_value&order_by_dir=desc"><img src="' . $wpurl_img . 'sort_desc.png" title="' . __( 'Sort High to Low', 'car-demon' ) . '" /></a>';
				$car_demon_sorting .= '&nbsp;&nbsp;&nbsp;' . __( 'Mileage', 'car-demon' ) . ' ' . $sort_asc_img . $sort_desc_img;
		}
	}
	if ( isset( $car_demon_options['drop_down_sort'] ) ) {
		if ( $car_demon_options['drop_down_sort'] == 'Yes' ) {
			if ( isset( $_GET['order_by'] ) ) {
				if ( $_GET['order_by'] == '_mileage_value' ) {
					$select_price = '';
					$select_mileage = ' selected';
				} else {
					$select_price = ' selected';
					$select_mileage = '';
				}
			} else {
				$select_price = ' selected';
				$select_mileage = '';
			}
			if ( isset( $_GET['order_by_dir'] ) ) {
				if ( $_GET['order_by_dir'] == 'asc' ) {
					$select_desc = '';
					$select_asc = ' selected';
				} else {
					$select_desc = ' selected';
					$select_asc = '';	
				}
			} else {
				$select_desc = ' selected';
				$select_asc = '';	
			}
			parse_str( $query_string, $output );
			$hidden_items = '';
			foreach ( $output as $key => $value ) {
				if ( ! empty( $value ) ) {
					$hidden_items .= '<input type="hidden" value="' . $value . '" name="' . $key . '" />';
				}
			}

			$car_demon_sorting = '<form id="frm_cd_sort" name="frm_cd_sort" action="" method="get">
						' . $hidden_items . '
						<span id="cd_sort_by_label" class="cd_sort_by_label">'.__( 'Sort By: ','car-demon' ).'</span>
						<select id="order_by" name="order_by" class="cd_order_by" onchange="document.forms[\'frm_cd_sort\'].submit();">
							';
							if ( isset( $car_demon_options['sort_price'] ) ) {
								if ( $car_demon_options['sort_price'] != 'No' ) {
									$car_demon_sorting .= '<option value="_price_value"' . $select_price . '>'.__( 'Price', 'car-demon' ) . '</option>';
								}
							}

							if ( isset( $car_demon_options['sort_miles'] ) ) {
								if ( $car_demon_options['sort_miles'] != 'No' ) {
									$car_demon_sorting .= '<option value="_mileage_value"' . $select_mileage . '>' . __( 'Mileage', 'car-demon' ) . '</option>';
								}
							}
						$car_demon_sorting .= '</select>';

			$car_demon_sorting .= '&nbsp;<select id="order_by_dir" name="order_by_dir" class="cd_order_by_dir" onchange="document.forms[\'frm_cd_sort\'].submit();">
										<option value="desc"' . $select_desc . '>'.__( 'Desc', 'car-demon' ).'</option>
										<option value="asc"'. $select_asc . '>'.__( 'Asc', 'car-demon' ).'</option>
									</select></form>';
		}
	}
	$car_demon_sorting = apply_filters( 'cd_sort_filter', $car_demon_sorting );
	$car_demon_sorting = apply_filters( 'car_demon_sort_filter', $car_demon_sorting ); //= deprecated
	return $car_demon_sorting;
}

/**
 * Filter search page title
 *
 * @param $title the current page title of the search result page
 *
 */
function car_demon_filter_search_title( $title ) {
	$title = str_replace( __( 'Page not found', 'car-demon' ), __( 'Search Results', 'car-demon' ), $title );
	return $title;
}
?>