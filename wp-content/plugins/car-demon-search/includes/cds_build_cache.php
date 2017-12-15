<?php
function cds_build_cache($return = false) {
	$item_value = '';

	$location = cds_build_cache_item('as_location', $item_value, 1);
		update_option( 'cds_cache_location', $location);
	$condition = cds_build_cache_item('as_condition', $item_value, 1);
		update_option( 'cds_cache_condition', $condition );
	$make = cds_build_cache_item('as_make', $item_value, 1);
		update_option( 'cds_cache_makes', $make );
	$model = cds_build_cache_item('as_model', $item_value, 1);
		update_option( 'cds_cache_models', $model );
	$year = cds_build_cache_item('as_year', $item_value, 1);
		update_option( 'cds_cache_years', $year );
	$body_style = cds_build_cache_item('as_body_style', $item_value, 1);
		update_option( 'cds_cache_body_style', $body_style );
	$current_inventory = cds_get_current_inventory();
		update_option( 'cds_cache_current_inventory', $current_inventory );
	if ($return == true) {
		return $current_inventory;
	}
}

function cds_delete_cache() {
	delete_option( 'cds_cache_location' );
	delete_option( 'cds_cache_condition' );
	delete_option( 'cds_cache_makes' );
	delete_option( 'cds_cache_models' );
	delete_option( 'cds_cache_years' );
	delete_option( 'cds_cache_body_style' );
	delete_option( 'cds_cache_current_inventory' );
}

function cds_get_min_max( $end = 'max', $meta ) {
    global $wpdb;
	$prefix = $wpdb->prefix;
    $query = $wpdb->prepare("
		SELECT ".$end."( cast( wpostmeta1.meta_value as UNSIGNED ) )
		FROM ". $prefix ."posts wposts
			LEFT JOIN ". $prefix ."postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
			LEFT JOIN ". $prefix ."postmeta wpostmeta1 ON wposts.ID = wpostmeta1.post_id 
		WHERE wposts.post_type='cars_for_sale'
			AND wpostmeta.meta_key = 'sold'
			AND wpostmeta.meta_value = 'no'
			AND wpostmeta1.meta_key='%s'
		",
        $meta
    );

	$results = $wpdb->get_var( $query );
    return $results;
}

function cds_remove_json_file() {
	$dir = plugin_dir_path( __FILE__ );
	if (!stristr(PHP_OS, 'WIN')) {
		$slash = '/';
	} else {
		$slash = '\\';
	}
	$dir = str_replace('includes/','json-cache',$dir);
	$dir = str_replace('includes\\','json-cache',$dir);
	$dir = trim($dir);
	$blog_id = get_current_blog_id();
	$filename = $dir.$slash.'inventory'.$blog_id.'.txt';
	if (file_exists($filename)) {
		unlink($filename);
	}
}

function cds_get_current_inventory() {
	$x = '';
	if (1==1) {
		$options = array();
		$options['car'] = '1';
		$options['order_by'] = '';
		$options['order_by_dir'] = '';
		$options['search_dropdown_Min_price'] = '';
		$options['search_dropdown_Max_price'] = '';
		$options['stock'] = '';
		$options['search_dropdown_miles'] = '';
		$options['search_dropdown_tran'] = '';
		$options['criteria'] = '';
		$options['search_location'] = '';
		$options['search_year'] = '';
		$options['search_condition'] = '';
		$options['search_make'] = '';
		$options['search_model'] = '';
		$options['search_dropdown_body'] = '';
	}

	cds_remove_json_file();

	global $wpdb;
	$prefix = $wpdb->prefix;

	$sql = 'SELECT DISTINCT Count(*) AS cnt, 
	'.$prefix.'terms_4.name AS location, 
	'.$prefix.'terms_3.name AS cond, 
	'.$prefix.'terms.name AS v_year, 
	'.$prefix.'terms_1.name AS make, 
	'.$prefix.'terms_2.name AS model, 
	'.$prefix.'terms_5.name AS body_style
	FROM '.$prefix.'terms AS '.$prefix.'terms_5 
	RIGHT JOIN ('.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_5 
	RIGHT JOIN ('.$prefix.'term_relationships AS '.$prefix.'term_relationships_5 
	RIGHT JOIN ('.$prefix.'terms AS '.$prefix.'terms_4 
	RIGHT JOIN ('.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_4 
	RIGHT JOIN ('.$prefix.'term_relationships AS '.$prefix.'term_relationships_4 
	RIGHT JOIN ('.$prefix.'terms AS '.$prefix.'terms_3 
	RIGHT JOIN ('.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_3 
	RIGHT JOIN ('.$prefix.'term_relationships AS '.$prefix.'term_relationships_3 
	RIGHT JOIN (('.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_2 
	RIGHT JOIN ('.$prefix.'term_relationships AS '.$prefix.'term_relationships_2 
	RIGHT JOIN ('.$prefix.'terms AS '.$prefix.'terms_1 
	RIGHT JOIN ('.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 
	RIGHT JOIN ('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
	RIGHT JOIN (('.$prefix.'terms RIGHT JOIN ('.$prefix.'term_taxonomy 
	RIGHT JOIN '.$prefix.'term_relationships 
		ON '.$prefix.'term_taxonomy.term_taxonomy_id = '.$prefix.'term_relationships.term_taxonomy_id) 
		ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) 
	RIGHT JOIN ('.$prefix.'postmeta 
	RIGHT JOIN '.$prefix.'posts 
		ON '.$prefix.'postmeta.post_id = '.$prefix.'posts.ID) 
		ON '.$prefix.'term_relationships.object_id = '.$prefix.'posts.ID) 
		ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'posts.ID) 
		ON '.$prefix.'term_taxonomy_1.term_taxonomy_id = '.$prefix.'term_relationships_1.term_taxonomy_id) 
		ON '.$prefix.'terms_1.term_id = '.$prefix.'term_taxonomy_1.term_id) 
		ON '.$prefix.'term_relationships_2.object_id = '.$prefix.'posts.ID) 
		ON '.$prefix.'term_taxonomy_2.term_taxonomy_id = '.$prefix.'term_relationships_2.term_taxonomy_id) 
	LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_2 
		ON '.$prefix.'term_taxonomy_2.term_id = '.$prefix.'terms_2.term_id) 
		ON '.$prefix.'term_relationships_3.object_id = '.$prefix.'posts.ID) 
		ON '.$prefix.'term_taxonomy_3.term_taxonomy_id = '.$prefix.'term_relationships_3.term_taxonomy_id) 
		ON '.$prefix.'terms_3.term_id = '.$prefix.'term_taxonomy_3.term_id) 
		ON '.$prefix.'term_relationships_4.object_id = '.$prefix.'posts.ID) 
		ON '.$prefix.'term_taxonomy_4.term_taxonomy_id = '.$prefix.'term_relationships_4.term_taxonomy_id) 
		ON '.$prefix.'terms_4.term_id = '.$prefix.'term_taxonomy_4.term_id) 
		ON '.$prefix.'term_relationships_5.object_id = '.$prefix.'posts.ID) 
		ON '.$prefix.'term_taxonomy_5.term_taxonomy_id = '.$prefix.'term_relationships_5.term_taxonomy_id) 
		ON '.$prefix.'terms_5.term_id = '.$prefix.'term_taxonomy_5.term_id
	WHERE ((('.$prefix.'postmeta.meta_key)="sold") 
		AND (('.$prefix.'postmeta.meta_value)="no") 
		AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_year") 
		AND (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_make") 
		AND (('.$prefix.'term_taxonomy_2.taxonomy)="vehicle_model") 
		AND (('.$prefix.'term_taxonomy_3.taxonomy)="vehicle_condition") 
		AND (('.$prefix.'term_taxonomy_4.taxonomy)="vehicle_location") 
		AND (('.$prefix.'term_taxonomy_5.taxonomy)="vehicle_body_style"))
	GROUP BY '.$prefix.'terms_4.name, '.$prefix.'terms_3.name, '.$prefix.'terms.name, '.$prefix.'terms_1.name, '.$prefix.'terms_2.name, '.$prefix.'terms_5.name
	ORDER BY '.$prefix.'terms_4.name DESC , '.$prefix.'terms_3.name DESC , '.$prefix.'terms.name DESC , '.$prefix.'terms_1.name, '.$prefix.'terms_2.name';
		
	// No locations - then use this
	$locations = get_terms('vehicle_location');
	if (!is_array($locations)) {
		$sql = 'SELECT DISTINCT Count(*) AS cnt, 
			'.$prefix.'terms_3.name AS cond, 
			'.$prefix.'terms.name AS v_year, 
			'.$prefix.'terms_1.name AS make, 
			'.$prefix.'terms_2.name AS model, 
			'.$prefix.'terms_5.name AS body_style
			FROM '.$prefix.'terms AS '.$prefix.'terms_5 
			RIGHT JOIN ('.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_5 
			RIGHT JOIN ('.$prefix.'term_relationships AS '.$prefix.'term_relationships_5 
			RIGHT JOIN ('.$prefix.'terms AS '.$prefix.'terms_3 
			RIGHT JOIN ('.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_3 
			RIGHT JOIN ('.$prefix.'term_relationships AS '.$prefix.'term_relationships_3 
			RIGHT JOIN (('.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_2 
			RIGHT JOIN ('.$prefix.'term_relationships AS '.$prefix.'term_relationships_2 
			RIGHT JOIN ('.$prefix.'terms AS '.$prefix.'terms_1 
			RIGHT JOIN ('.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 
			RIGHT JOIN ('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN (('.$prefix.'terms RIGHT JOIN ('.$prefix.'term_taxonomy 
			RIGHT JOIN '.$prefix.'term_relationships 
				ON '.$prefix.'term_taxonomy.term_taxonomy_id = '.$prefix.'term_relationships.term_taxonomy_id) 
				ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) 
			RIGHT JOIN ('.$prefix.'postmeta 
			RIGHT JOIN '.$prefix.'posts 
				ON '.$prefix.'postmeta.post_id = '.$prefix.'posts.ID) 
				ON '.$prefix.'term_relationships.object_id = '.$prefix.'posts.ID) 
				ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'posts.ID) 
				ON '.$prefix.'term_taxonomy_1.term_taxonomy_id = '.$prefix.'term_relationships_1.term_taxonomy_id) 
				ON '.$prefix.'terms_1.term_id = '.$prefix.'term_taxonomy_1.term_id) 
				ON '.$prefix.'term_relationships_2.object_id = '.$prefix.'posts.ID) 
				ON '.$prefix.'term_taxonomy_2.term_taxonomy_id = '.$prefix.'term_relationships_2.term_taxonomy_id) 
			LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_2 
				ON '.$prefix.'term_taxonomy_2.term_id = '.$prefix.'terms_2.term_id) 
				ON '.$prefix.'term_relationships_3.object_id = '.$prefix.'posts.ID) 
				ON '.$prefix.'term_taxonomy_3.term_taxonomy_id = '.$prefix.'term_relationships_3.term_taxonomy_id) 
				ON '.$prefix.'terms_3.term_id = '.$prefix.'term_taxonomy_3.term_id) 
				ON '.$prefix.'term_relationships_5.object_id = '.$prefix.'posts.ID) 
				ON '.$prefix.'term_taxonomy_5.term_taxonomy_id = '.$prefix.'term_relationships_5.term_taxonomy_id) 
				ON '.$prefix.'terms_5.term_id = '.$prefix.'term_taxonomy_5.term_id
			WHERE ((('.$prefix.'postmeta.meta_key)="sold") 
				AND (('.$prefix.'postmeta.meta_value)="no") 
				AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_year") 
				AND (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_make") 
				AND (('.$prefix.'term_taxonomy_2.taxonomy)="vehicle_model") 
				AND (('.$prefix.'term_taxonomy_3.taxonomy)="vehicle_condition") 
				AND (('.$prefix.'term_taxonomy_5.taxonomy)="vehicle_body_style"))
			GROUP BY '.$prefix.'terms_3.name, '.$prefix.'terms.name, '.$prefix.'terms_1.name, '.$prefix.'terms_2.name, '.$prefix.'terms_5.name
			ORDER BY '.$prefix.'terms_3.name DESC , '.$prefix.'terms.name DESC , '.$prefix.'terms_1.name, '.$prefix.'terms_2.name';
	}
	$search_query = $wpdb->get_results($sql);
	$array = array();
	$array1 = array();
	$min_max_values = array();
	
	$price_max = cds_get_min_max( $end = 'max', '_price_value' );
	$price_min = cds_get_min_max( $end = 'min', '_price_value' );
	$mileage_max = cds_get_min_max( $end = 'max', '_mileage_value' );
	$mileage_min = cds_get_min_max( $end = 'min', '_mileage_value' );

	$min_max_values['max_price'] = $price_max;
	$min_max_values['min_price'] = $price_min;
	$min_max_values['max_miles'] = $mileage_max;
	$min_max_values['min_miles'] = $mileage_min;
	$min_max_values['max_year'] = date("Y")+1;
	$min_max_values['min_year'] = 1984;

	$cnt = 0;
	$count = count($search_query);

	foreach ($search_query as $result) {
		++$cnt;
		if (is_array($locations)) { //= if they're using locations then include it in json
			$array1['a'] = $result->location;
		} else {
			$array1['a'] = ''; //= locations are not being used so populate with blank info
		}
		$array1['b'] = $result->make;
		$array1['c'] = $result->model;
		$array1['d'] = $result->body_style;
		$array1['e'] = $result->cond;
		$array1['f'] = $result->v_year;
		$array1['g'] = $result->cnt;

		$new_line = $array1;
		cds_add_to_json($new_line);
	}
	//= Save the current min and max values for price and miles
	update_option( 'cds_min_max_values', $min_max_values );

//	$new_array = json_encode($array);
//	return $new_array;
}

function cds_add_to_json($new_line) {
	$dir = plugin_dir_path( __FILE__ );
	if (!stristr(PHP_OS, 'WIN')) {
		$slash = '/';
	} else {
		$slash = '\\';
	}
	$dir = str_replace('includes/','json-cache',$dir);
	$dir = str_replace('includes\\','json-cache',$dir);
	$dir = trim($dir);
	$blog_id = get_current_blog_id();
	$filename = $dir.$slash.'inventory'.$blog_id.'.txt';

	// read the file if present
	$handle = @fopen($filename, 'r+');

	// create the file if needed
	if (!file_exists($filename)) {
		$handle = fopen($filename, 'w+');
	}
	
	if ($handle) {
		// seek to the end
		fseek($handle, 0, SEEK_END);
	
		// are we at the end of is the file empty
		if (ftell($handle) > 0) {
			// move back a byte
			fseek($handle, -1, SEEK_END);
	
			// add the trailing comma
			fwrite($handle, ',', 1);
	
			// add the new json string
			fwrite($handle, json_encode($new_line) . ']');
		} else {
			// write the first event inside an array
			fwrite($handle, json_encode(array($new_line)));
		}

		// close the handle on the file
		fclose($handle);
	}
	else {
		echo 'fail';
	}
}
?>