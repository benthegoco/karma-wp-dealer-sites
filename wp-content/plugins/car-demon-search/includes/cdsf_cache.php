<?php
// cdsf_temp() is used for diagnostics only
function cdsf_temp($this_key) {
	$items = '[{"id":11,"year":"2009","make":"Chevrolet","model":"Cobalt", "body_style": "Sedan"},{"id":12,"year":"2007","make":"Ford","model":"Focus", "body_style":"Sedan"}]';
	if (!empty($items)) {
		$items_array = json_decode($items, true);
		foreach ($items_array as $item) {
			foreach ($item as $key => $current_item) {
				if ($key == $this_key) {
					echo $key.' - ';
					echo $current_item .'<br />';
				}
			}
			echo '<hr />';
		}
	}
}

function cdsf_build_cache_item($item_type, $item_value, $show=0) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$cdsf_hide_count = get_option('cdsf_hide_count', 'off');
	$field_labels = get_cds_field_labels();
	$my_year = '';
	$array = array();
	if ($item_type == 'as_year') {
		$my_condition = esc_sql( $item_value );
		if (!empty($my_condition)) {
			$condition_srch = '(('.$prefix.'terms.name)="'. $my_condition .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_condition") AND';
		} else {
			$condition_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $condition_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_year") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$label = __('ALL ', 'car-demon-search' ) . $field_labels['year'];
		$array[] = array('' => $label);
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$cnt_results = cdsf_count_these_tax_items($my_condition, $my_result->new_name, 1);
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_year' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					if ($cdsf_hide_count != 'on') {
						$array[] = array($my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
					} else {
						$array[] = array($my_slug => $my_result->new_name);
					}
				} else {
					if (!empty($year_srch)) {
						$array[] = array($my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	} elseif ($item_type == 'as_location') {
		$my_location = esc_sql( $item_value );
		if (!empty($my_location)) {
			$location_srch = '(('.$prefix.'terms.name)="'. $my_location .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_location") AND';
		} else {
			$location_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $location_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_location") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$array[] = array('' => 'ALL LOCATIONS');
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$cnt_results = cdsf_count_these_tax_items($my_year, $my_result->new_name, 6);
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_location' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					if ($cdsf_hide_count != 'on') {
						$array[] = array($my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
					} else {
						$array[] = array($my_slug => $my_result->new_name);
					}
				} else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	} elseif ($item_type == 'as_condition') {
		$my_condition = esc_sql( $item_value );
		if (!empty($my_condition)) {
			$condition_srch = '(('.$prefix.'terms.name)="'. $my_condition .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_condition") AND';
		} else {
			$condition_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $condition_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_condition") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$label = __('ALL ', 'car-demon-search' ) . $field_labels['condition'];
		$array[] = array('' => $label);
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$cnt_results = cdsf_count_these_tax_items($my_year, $my_result->new_name, 5);
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_condition' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					if ($cdsf_hide_count != 'on') {
						$array[] = array($my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
					} else {
						$array[] = array($my_slug => $my_result->new_name);
					}
				} else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	} elseif ($item_type == 'as_make') {
		$my_condition = esc_sql( $item_value );
		if (!empty($my_condition)) {
			$condition_srch = '(('.$prefix.'terms.name)="'. $my_condition .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_condition") AND';
		} else {
			$condition_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $condition_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_make") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$label = __('ALL ', 'car-demon-search' ) . $field_labels['make'];
		$array[] = array('' => $label);
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$cnt_results = cdsf_count_these_tax_items($my_year, $my_result->new_name, 2);
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_make' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					if ($cdsf_hide_count != 'on') {
						$array[] = array($my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
					} else {
						$array[] = array($my_slug => $my_result->new_name);
					}
				} else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	} elseif ($item_type == 'as_model') {
		$my_string = esc_sql( $item_value );
		$my_array = explode(',', $my_string);
		if (isset($my_array[0])) {
			$my_year = $my_array[0];
		} else {
			$my_year = '';	
		}
		if (isset($my_array[1])) {
			$my_make = $my_array[1];
		} else {
			$my_make = '';
		}
		if (!empty($my_make)) {
			$make_str = '(('.$prefix.'terms.slug)="'. $my_make .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_make") AND';
		} else {
			$make_str = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('.$make_str.' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_model") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		if (!empty($my_results)) {
			$label = __('ALL ', 'car-demon-search' ) . $field_labels['model'];
			$array[] = array('' => $label);
			foreach ($my_results as $my_result) {
				$cnt_results = cdsf_count_these_tax_items($my_year, $my_result->new_name, 3);
				if ($cnt_results > 0 ) {
					$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_model' );
					$my_slug = $my_sel_val->slug;
					if ($cdsf_hide_count != 'on') {
						$array[] = array($my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
					} else {
						$array[] = array($my_slug => $my_result->new_name);
					}
				}
			}
		} else {
			$array[] = array('1' => 'ALL MODELS');
		}
	} elseif ($item_type == 'as_body_style') {
		$my_string = esc_sql( $item_value );
		$my_array = explode(',', $my_string);
		if (isset($my_array[0])) {
			$my_year = $my_array[0];
		} else {
			$my_year = '';
		}
		if (isset($my_array[1])) {
			$my_make = $my_array[1];
		} else {
			$my_make = '';
		}
		if (!empty($my_make)) {
			$make_str = '(('.$prefix.'terms.slug)="'. $my_make .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_make") AND';
		} else {
			$make_str = '';	
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('.$make_str.' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_body_style") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';

		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$label = __('ALL ', 'car-demon-search' ) . $field_labels['body_style'];
		if (!empty($my_results)) {
			$array[] = array('' => $label);
			foreach ($my_results as $my_result) {
				$cnt_results = cdsf_count_these_tax_items($my_year, $my_result->new_name, 4);
				if ($cnt_results > 0 ) {
					$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_body_style' );
					$my_slug = $my_sel_val->slug;
					if ($cdsf_hide_count != 'on') {
						$array[] = array($my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
					} else {
						$array[] = array($my_slug => $my_result->new_name);
					}
				}
			}
		} else {
			$array[] = array('1' => $label);
		}
//==============================================================================
	} elseif ($item_type == 'search_year') {
		$my_year = esc_sql( $item_value );
		if (!empty($my_year)) {
			$year_srch = '(('.$prefix.'terms.name)="'. $my_year .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_year") AND';
		} else {
			$year_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $year_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_make") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$label = __('ALL ', 'car-demon-search' ) . $field_labels['make'];
		$array[] = array('' => $label);
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$cnt_results = cdsf_count_these_tax_items($my_year, $my_result->new_name, 1);
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_make' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					if ($cdsf_hide_count != 'on') {
						$array[] = array($my_year.','.$my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
					} else {
						$array[] = array($my_year.','.$my_slug => $my_result->new_name);
					}
				} else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	} elseif ($item_type == 'search_year_model') {
		$my_year = $item_value;
		if (!empty($my_year)) {
			$year_srch = '(('.$prefix.'terms.name)="'. esc_sql( $my_year ) .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_year") AND';
		} else {
			$year_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $year_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_model") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$label = __('ALL ', 'car-demon-search' ) . $field_labels['model'];
		$array[] = array('' => $label);
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_model' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					$array[] = array($my_year.','.$my_slug => $my_result->new_name);
				} else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	} elseif ($item_type == 'search_model_condition') {
		$my_condition = esc_sql( $item_value );
		if (!empty($my_condition)) {
			$condition_srch = '(('.$prefix.'terms.name)="'. $my_condition .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_condition") AND';
		} else {
			$condition_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $condition_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_model") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$label = __('ALL ', 'car-demon-search' ) . $field_labels['model'];
		$array[] = array('' => $label);
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_model' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					$array[] = array($my_year.','.$my_slug => $my_result->new_name);
				}
				else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	} elseif ($item_type == 'cdsf_cache_trim_levels') {
		$my_trim_level = esc_sql( $item_value );
		if ( ! empty( $my_trim_level ) ) {
			$trim_level_srch = '(('.$prefix.'postmeta_1.meta_value)="'. $my_trim_level .'")) AND ';
		} else {
			$trim_level_srch = '';
		}
		$str_sql = 'SELECT DISTINCT '.$prefix.'postmeta_1.meta_value AS new_name
			FROM '.$prefix.'postmeta AS '.$prefix.'postmeta_1 RIGHT JOIN '.$prefix.'postmeta ON '.$prefix.'postmeta_1.post_id = '.$prefix.'postmeta.post_id
			WHERE ('. $trim_level_srch .'(('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no") AND (('.$prefix.'postmeta_1.meta_key)="_trim_level_value"))
			';

		$my_results = $wpdb->get_results($str_sql);
		$cnt_results = 0;
		$label = __('ALL ', 'car-demon-search' ) . $field_labels['trim_level'];
		$array[] = array('' => $label);
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				if ($my_result->new_name != '-') {
					$array[] = array($my_trim_level => $my_result->new_name);
				} else {
					if (!empty($year_srch)) {
						$array[] = array($my_trim_level => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}

	} elseif ($item_type == 'cdsf_cache_transmissions') {
		$my_transmission = esc_sql( $item_value );
		if ( ! empty( $my_transmission ) ) {
			$transmission_srch = '(('.$prefix.'postmeta_1.meta_value)="'. $my_transmission .'")) AND ';
		} else {
			$transmission_srch = '';
		}
		$str_sql = 'SELECT DISTINCT '.$prefix.'postmeta_1.meta_value AS new_name
			FROM '.$prefix.'postmeta AS '.$prefix.'postmeta_1 RIGHT JOIN '.$prefix.'postmeta ON '.$prefix.'postmeta_1.post_id = '.$prefix.'postmeta.post_id
			WHERE ('. $transmission_srch .'(('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no") AND (('.$prefix.'postmeta_1.meta_key)="_transmission_value"))
			';

		$my_results = $wpdb->get_results($str_sql);
		$cnt_results = 0;
		$label = __('ALL ', 'car-demon-search' ) . $field_labels['transmission'];
		$array[] = array('' => $label);
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				if ($my_result->new_name != '-') {
					$array[] = array($my_transmission => $my_result->new_name);
				}
				else {
					if (!empty($year_srch)) {
						$array[] = array($my_transmission => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
		
	}

	if (empty($array)) {
		$array[] = array('1' => 'No Match');
		$array[] = array('2' => 'No Match');
	}
	if ($show == 0) {
		echo json_encode( $array );
	} else {
		return json_encode( $array );
	}
}

function cdsf_count_these_tax_items($old_val, $new_val, $type) {
	global $wpdb;
	$new_val = esc_sql( $new_val );
	$prefix = $wpdb->prefix;
	if ($type == 1 ) {
		$my_term = get_term_by( 'name', $new_val, 'vehicle_year' );
		$my_slug = esc_sql( $my_term->slug );
		$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
			FROM ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships.object_id = '.$prefix.'postmeta.post_id
			WHERE ((('.$prefix.'terms.name)="'. $new_val .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_year") AND (('.$prefix.'postmeta.meta_key)="sold ") AND (('.$prefix.'postmeta.meta_value)="no "))';
	}
	if ($type == 2 ) {
		$my_term = get_term_by( 'name', $new_val, 'vehicle_make' );
		$my_slug = esc_sql( $my_term->slug );
		$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
			FROM ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships.object_id = '.$prefix.'postmeta.post_id
			WHERE ((('.$prefix.'terms.name)="'. $new_val .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_make") AND (('.$prefix.'postmeta.meta_key)="sold ") AND (('.$prefix.'postmeta.meta_value)="no "))';
	}
	if ($type == 3 ) {
		$my_term = get_term_by( 'name', $new_val, 'vehicle_model' );
		$my_slug = esc_sql( $my_term->slug );
		$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
			FROM ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships.object_id = '.$prefix.'postmeta.post_id
			WHERE ((('.$prefix.'terms.name)="'. $new_val .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_model") AND (('.$prefix.'postmeta.meta_key)="sold ") AND (('.$prefix.'postmeta.meta_value)="no "))';
	}
	if ($type == 4 ) {
		$my_term = get_term_by( 'name', $new_val, 'vehicle_body_style' );
		$my_slug = esc_sql( $my_term->slug );
		$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
			FROM ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships.object_id = '.$prefix.'postmeta.post_id
			WHERE ((('.$prefix.'terms.name)="'. $new_val .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_body_style") AND (('.$prefix.'postmeta.meta_key)="sold ") AND (('.$prefix.'postmeta.meta_value)="no "))';
	}
	if ($type == 5 ) {
		$my_term = get_term_by( 'name', $new_val, 'vehicle_condition' );
		$my_slug = esc_sql( $my_term->slug );
		$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
			FROM ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships.object_id = '.$prefix.'postmeta.post_id
			WHERE ((('.$prefix.'terms.name)="'. $new_val .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_condition") AND (('.$prefix.'postmeta.meta_key)="sold ") AND (('.$prefix.'postmeta.meta_value)="no "))';
	}
	if ($type == 6 ) {
		$my_term = get_term_by( 'name', $new_val, 'vehicle_location' );
		$my_slug = esc_sql( $my_term->slug );
		$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
			FROM ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships.object_id = '.$prefix.'postmeta.post_id
			WHERE ((('.$prefix.'terms.name)="'. $new_val .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_location") AND (('.$prefix.'postmeta.meta_key)="sold ") AND (('.$prefix.'postmeta.meta_value)="no "))';
	}
	$total_cars = $wpdb->get_var($str_sql);
	$my_total = $total_cars;
	return $my_total;
}

function cdsf_count_my_active_tax_items($my_tag_name, $post_type, $taxonomy) {
	global $wpdb;
	$my_tag_id = get_term_by( 'slug', ''.$my_tag_name.'', ''.$taxonomy.'');
	$my_tag_id = $my_tag_id->term_id;
	if (!empty($my_tag_id)) {
		$my_search .= " AND $wpdb->term_taxonomy.taxonomy = '". esc_sql( $taxonomy ) ."'	AND $wpdb->term_taxonomy.term_id IN(".$my_tag_id.")";
		$query = "SELECT COUNT(*) as num
			FROM $wpdb->posts wposts
				LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
				LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
				LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			WHERE wposts.post_type='".$post_type."'
				AND wpostmeta.meta_key = 'sold'
				AND wpostmeta.meta_value = 'no'".$my_search;
		$total_cars = $wpdb->get_var($str_sql);
	}
	return $total_cars;
}
?>