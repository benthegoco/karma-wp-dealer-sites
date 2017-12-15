<?php
function vehicle_search_box( $button, $message, $settings = array() ) {
	$url = get_bloginfo( 'wpurl' );
	$native_search = '<input type="hidden" name="s" value="cars" />';
	$search_class = '';
	global $car_demon_options;
	if ( isset( $car_demon_options['inventory_page'] ) ) {
		if ( $url != $car_demon_options['inventory_page'] ) {
			$native_search = '';
		}
	}
	if ( isset( $settings['result_page'] ) ) {
		if ( ! empty( $settings['result_page'] ) ) {
			$url = get_permalink( $settings['result_page'] );
		}
		$native_search = '';
	}
	$box = '<form action="' . $url . '" method="get" class="vehicle_search_box" id="vehicle_search_box" name="vehicle_search_box" />';
		$box .= $native_search;
		$box .= '<input type="hidden" name="car" value="1" />';
		$box .= '<span id="criteria_message">' . $message . '</span>';
		$box .= '<input type="text" name="criteria" class="search_criteria" value="" />';
		$box .= '<input type="submit" name="submit_search" id="submit_search" value="' . $button . '" class="search_btn advanced_btn criteria_btn">';
	$box .= '</form>';
	$box = apply_filters( 'cd_search_form_filter', $box, 'search_box' );
	$box = apply_filters( 'car_demon_search_shortcode_filter', $box ); //= deprecated
	return $box;
}
function car_demon_search_form( $settings = array() ) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace( 'includes', '', $car_demon_pluginpath );
	$url = get_bloginfo( 'wpurl' );
	$native_search = '<input type="hidden" name="s" value="cars" />';
	global $car_demon_options;
	if ( isset( $car_demon_options['inventory_page'] ) ) {
		if ( $url != $car_demon_options['inventory_page'] ) {
			$url = $car_demon_options['inventory_page'];
			$native_search = '';
		}
	}
	if ( isset($settings['form_action'] ) ) {
		if ( ! empty( $settings['form_action'] ) ) {
			$url = get_permalink( $settings['form_action'] );
			$native_search = '';
		}
	}
	if ( isset( $settings['result_page'] ) ) {
		if ( ! empty( $settings['result_page'] ) ) {
			$url = get_permalink( $settings['result_page'] );
			$native_search = '';
		}
	}
	$field_labels = get_default_field_labels();
	ob_start();
		?>
		<div class="search_car_box_frame">
			<div id="car-demon-search-cars" class="search_car_box">
		<form action="<?php echo $url ?>" method="get" />
		<?php
			echo $native_search;
		?>
		<input type="hidden" name="car" value="1" />
        <!--input type="hidden" name="post_type" value="cars_for_sale" /-->
				<div id="car-demon-searchr1c1" class="search_header"></div>
				<div id="car-demon-searchr2c1" class="search_header_logo"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/search_cars.gif" alt="Search Cars" title="Search Cars" width="20" height="23" />&nbsp;<?php _e('QUICK SEARCH', 'car-demon'); ?>
		<?php
			echo '<div class="advanced_search_btn" title="'. __('Advanced Search', 'car-demon') .'">+</div></div>';
			echo '<div class="advanced_search" id="advanced_search">'. __('Stock #', 'car-demon') .': <input class="search_dropdown_sm" type="text" name="stock" id="stock" size="6" />
			&nbsp;<span class="advanced_search_btn_hide" onclick="document.getElementById(\'advanced_search\').style.display=\'none\';" title="'. __('Hide Advanced Search', 'car-demon') .'">-</span>
			</div>';
		?>
					<!--start form fields-->
                    <div class="search_left cd_condition_box">
						<div id="car-demon-searchr3c1" class=""><?php echo $field_labels['condition']; ?>:</div>
						<div><?php echo car_demon_search_condition( $settings ); ?></div>
					</div><!--end condition-->
					<div class="search_right cd_make_box">
						<div id="car-demon-searchr3c1" class=""><?php echo $field_labels['make']; ?>:</div>
						<div id="car-demon-searchr4c1" class=""><?php echo car_demon_search_makes();?></div>
					</div><!--end make-->
					<div class="search_left cd_year_box">
						<div class="search_labels"><?php echo $field_labels['year']; ?>:</div>
						<div><?php echo car_demon_search_years(); ?></div>
					</div><!--end year-->
					<div class="search_right cd_model_box">
						<div class=""><?php echo $field_labels['model']; ?>:</div>
						<div><?php echo car_demon_search_models();?></div>
					</div><!--end model-->
					<div id="car-demon-searchr6c1" class="search_min_price">
						<div class="search_labels"><?php _e('Min Price', 'car-demon'); ?>:</div>
						<div><?php echo car_demon_search_price('Min'); ?></div>
					</div><!--end min price-->
					<div id="car-demon-searchr6c2" class="search_max_price">
						<div class="search_labels"><?php _e('Max Price', 'car-demon'); ?>:</div>
						<div><?php echo car_demon_search_price('Max'); ?></div>
					</div><!--end max price-->
					<div id="car-demon-searchr7c1" class="search_trans">
						<div class="search_labels"><?php _e('Trans', 'car-demon'); ?>:</div>
						<div><?php echo car_demon_search_tran(); ?></div>
					</div><!--end transmission-->
					<div id="car-demon-searchr7c2" class="search_mileage">
						<div class="search_labels"><?php _e('Mileage', 'car-demon'); ?>:</div>
						<div><?php echo car_demon_search_miles(); ?></div>
					</div><!--end mileage-->
					<div id="car-demon-searchr8c1" class="search_body">
						<div class="search_labels"><?php echo $field_labels['body_style']; ?>:</div>
						<div>
							<?php echo car_demon_search_body(); ?>
						</div>
					</div><!--end body-->
					<div id="car-demon-searchr8c2" class="search_button_box">
					  <input type="submit" name="submit_search" id="submit_search" value="<?php _e( 'Search', 'car-demon' ); ?>" class="search_btn advanced_btn">
					</div><!--end search button-->
				<div id="car-demon-searchr9c1" class="search_footer"></div><!--end search footer-->
		</form>
			</div>
		</div>
		<?php
	$box = ob_get_contents();
	ob_end_clean();
	$box = apply_filters( 'cd_search_form_filter', $box, 'full' );
	$box = apply_filters( 'car_demon_search_form_filter', $box ); //= deprecated
	return $box;
}
function car_demon_simple_search( $size='l', $settings = array() ) {
	$car_demon_auto_credit_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace( 'car-demon-auto-credit', 'car-demon', $car_demon_auto_credit_pluginpath );
	$field_labels = get_default_field_labels();
	$url = get_option( 'siteurl' );
	$native_search = '<input type="hidden" name="s" value="cars" />';
	global $car_demon_options;
	if ( isset( $car_demon_options['inventory_page'] ) ) {
		if ( $url != $car_demon_options['inventory_page'] ) {
			$url = $car_demon_options['inventory_page'];
			$native_search = '';
		}
	}
	if ( isset($settings['result_page'] ) ) {
		if ( ! empty( $settings['result_page'] ) ) {
			$url = get_permalink( $settings['result_page'] );
			$native_search = '';
		}
	}
	if ( $size == 's' ) {
		$form_size = "search_car_box_frame_narrow";
		$logo = "search_header_logo_simple";
	} else {
		$form_size = "search_car_box_frame_wide";
		$logo = "search_header_logo_simple_wide";
	}
	if ( isset( $_GET['search_condition'] ) ) {
		$search_condition = $_GET['search_condition'];
	} else {
		$search_condition = '';
	}
	ob_start();
?>
<div class="<?php echo $form_size; ?>">
	<div id="car-demon-search-cars_sm" class="search_car_box_sm">
<form action="<?php echo $url ?>" method="get" />
<?php
	echo $native_search;
?>
<input type="hidden" name="car" value="1" />
<!--input type="hidden" name="post_type" value="cars_for_sale" /-->
<input type="hidden" name="search_condition" value="<?php echo $search_condition;?>" />
		<?php
		if ( isset( $settings['title'] ) ) {
			if ( ! empty( $settings['title'] ) ) {
				?>
				<div id="car-demon-searchr2c1" class="<?php echo $logo; ?>"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/search_cars.gif" alt="<?php echo $settings['title']; ?>" width="20" height="23" title="<?php echo $settings['title']; ?>" />&nbsp;<?php echo $settings['title']; ?></div>
				<?php
			}
		}
		?>
        <!--start form fields-->
        <div class="search_left cd_year_box">
			<div id="car-demon-searchr3c1" class="search_manufacturer_title2"><?php echo $field_labels['year']; ?>:</div>
			<div class="search_year_dropdown"><?php echo car_demon_search_years(); ?></div>
		</div><!--end year-->
		<div class="search_right cd_make_box">
			<div id="car-demon-searchr3c1" class=""><?php echo $field_labels['make']; ?>:</div>
			<div id="car-demon-searchr4c1" class=""><?php echo car_demon_search_makes();?></div>
		</div><!--end make-->
		<div class="search_right cd_model_box">
			<div id="car-demon-searchr3c1" class=""><?php echo $field_labels['model']; ?>:</div>
			<div id="car-demon-searchr5c1" class=""><?php echo car_demon_search_models();?></div>
		</div><!--end model-->
		<div id="car-demon-searchr8c1" class="search_body"><?php echo $field_labels['body_style']; ?>:<br /><?php echo car_demon_search_body(); ?></div><!--end body-->
		<div id="car-demon-searchr8c2" class="search_button_box">
<?php
	echo '<div class="advanced_search_btn" title="Advanced Search">+</div>';
	echo '<div class="advanced_search" id="advanced_search">Stock #: <input class="search_dropdown_sm" type="text" name="stock" id="stock" size="6" />
	&nbsp;<span class="advanced_search_btn_hide" onclick="document.getElementById(\'advanced_search\').style.display=\'none\';" title="Hide Advanced Search">-</span>
	</div>';
?>
		  <input type="submit" name="submit_search" id="submit_search" value="<?php _e( 'Search', 'car-demon' ); ?>" class="search_btn simple_btn">
		</div><!--end search button-->
</form>
	</div>
</div>
<?php
	$box = ob_get_contents();
	ob_end_clean();
	$box = apply_filters( 'cd_search_form_filter', $box, 'small' );
	$box = apply_filters( 'car_demon_small_search_form_filter', $box ); //= deprecated
	return $box;
}
function car_demon_get_searched_by( $result_page='' ) {
	$searched = '';
	$query_string = $_SERVER['QUERY_STRING'];
	$query_string = str_replace( '%2C', ',', $query_string );
	if ( isset( $_GET['search_condition'] ) ) {
		if ( $_GET['search_condition'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_condition\', \'' . sanitize_text_field( $_GET['search_condition'] ) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">' . __( 'Condition', 'car-demon' ) . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_condition'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	if ( isset( $_GET['search_year'] ) ) {
		if ( $_GET['search_year'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_year\', \'' . sanitize_text_field( $_GET['search_year'] ) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">' . __( 'Year', 'car-demon' ) . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_year'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	if ( isset( $_GET['search_make'] ) ) {
		if ( $_GET['search_make'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_make\', \'' . sanitize_text_field( $_GET['search_make'] ) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">' . __( 'Make', 'car-demon' ) . ':</span> ';
				$search_make_array = $_GET['search_make'];
				$search_make_array = explode( ',', $search_make_array );
				if ( isset( $search_make_array[1] ) ) {
					$search_make = $search_make_array[1];
				} else {
					$search_make = str_replace( ',', '', $search_make_array[0] );
				}
				$searched .= stripslashes_deep( sanitize_text_field( $search_make ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	if ( isset( $_GET['search_model'] ) ) {
		if ( $_GET['search_model'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_model\', \'' . sanitize_text_field( $_GET['search_model']) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">'. __( 'Model', 'car-demon' ) . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_model'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	
	if ( isset( $_GET['search_dropdown_Min_years'] ) ) {
		if ( $_GET['search_dropdown_Min_years'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_Min_years\', \'' . sanitize_text_field( $_GET['search_dropdown_Min_years'] ) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">' . __( 'Min Year', 'car-demon' ) . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_dropdown_Min_years'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';	
		}
	}
	if ( isset( $_GET['search_dropdown_Max_years'] ) ) {
		if ( $_GET['search_dropdown_Max_years'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_Max_years\', \'' . sanitize_text_field( $_GET['search_dropdown_Max_years'] ) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">' . __( 'Max Year', 'car-demon' ) . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field($_GET['search_dropdown_Max_years']) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}

	if ( isset( $_GET['search_dropdown_Min_price'] ) ) {
		if ($_GET['search_dropdown_Min_price'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_Min_price\', \'' . sanitize_text_field( $_GET['search_dropdown_Min_price']) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">' . __( 'Min Price', 'car-demon') . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_dropdown_Min_price'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	if ( isset( $_GET['search_dropdown_Max_price'] ) ) {
		if ($_GET['search_dropdown_Max_price'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_Max_price\', \'' . sanitize_text_field( $_GET['search_dropdown_Max_price'] ) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">'. __( 'Max Price', 'car-demon' ) . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_dropdown_Max_price'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	if ( isset( $_GET['search_dropdown_miles_Min'] ) ) {
		if ($_GET['search_dropdown_miles_Min'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_miles_Min\', \'' . sanitize_text_field( $_GET['search_dropdown_miles_Min'] ) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">'. __( 'Min Mileage', 'car-demon' ) . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_dropdown_miles_Min'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	if ( isset( $_GET['search_dropdown_miles_Max'] ) ) {
		if ( $_GET['search_dropdown_miles_Max'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_miles_Max\', \'' . sanitize_text_field( $_GET['search_dropdown_miles_Max'] ) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">'. __( 'Max Mileage', 'car-demon' ) . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_dropdown_miles_Max'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	if ( isset( $_GET['search_dropdown_tran'] ) ) {
		if ( $_GET['search_dropdown_tran'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_tran\', \'' . sanitize_text_field( $_GET['search_dropdown_tran'] ) . '\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">'. __( 'Transmission', 'car-demon' ) . ':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_dropdown_tran'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	if ( isset( $_GET['search_dropdown_miles'] ) ) {
		if ($_GET['search_dropdown_miles'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_miles\', \'' . sanitize_text_field( $_GET['search_dropdown_miles'] ) .'\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">'. __( 'Miles', 'car-demon' ) .':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_dropdown_miles'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	if ( isset( $_GET['search_dropdown_body'] ) ) {
		if ($_GET['search_dropdown_body'] ) {
			$searched .= '<span class="search_by_item">';
				$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_body\', \'' . sanitize_text_field( $_GET['search_dropdown_body'] ) .'\', \'' . $query_string . '\', \'' . $result_page . '\');">x</span> <span class="remove_search_title">'. __( 'Body Style', 'car-demon' ) .':</span> ';
				$searched .= stripslashes_deep( sanitize_text_field( $_GET['search_dropdown_body'] ) ) . '<span class="search_by_comma">,</span>';
			$searched .= '</span>';
		}
	}
	$searched .= '@@';
	$searched = str_replace( ', @@', '', $searched );
	$searched = str_replace( '@@', '', $searched );
	if ( ! empty( $searched ) ) {
		$searched = '<div class="searched_by">' . $searched . '</div>';
	}
	$searched = apply_filters( 'cd_searched_by_filter', $searched );
	$searched = apply_filters( 'car_demon_searched_by_filter', $searched ); //= deprecated
	return $searched;
}
?>