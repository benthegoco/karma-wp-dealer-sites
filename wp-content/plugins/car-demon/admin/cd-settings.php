<?php

function car_demon_settings_page() {
	if ( isset( $_GET['page'] ) ) {
		if ( $_GET['page'] == 'car_demon_settings_options' ) {
			add_action( 'admin_enqueue_scripts', 'car_demon_admin_car_scripts' );
		}
	}
	if ( isset( $_GET['post_type'] ) ) {
		if ( $_GET['post_type'] == 'cars_for_sale' ) {
			add_action( 'admin_enqueue_scripts', 'car_demon_admin_car_scripts' );
		}
	}
	global $post;
	if ( isset( $_GET['action'] ) ) {
		if ( $_GET['action'] == 'edit' ) {
			if ( isset( $_GET['post'] ) ) {
				$post_type = get_post_type( $_GET['post'] );
				if ( $post_type == 'cars_for_sale' ) {
					add_action( 'admin_enqueue_scripts', 'car_demon_admin_car_scripts' );
				}
			}
		}
	}
	
	if ( defined( 'CAR_DEMON_ADMIN' ) ) {
		$admin_users = CAR_DEMON_ADMIN;
		$current_user = get_current_user_id();
		
		if ( strpos( $admin_users, ',' ) ) {
			$admin_users_array = explode( ',', $admin_users );
			if ( ! in_array( $current_user, $admin_users_array ) ) {
				return;
			}
		} else {
			if ( $admin_users != $current_user ) {
				return;
			}
		}
	
	}

	if ( ! defined( 'CD_LOCATIONS' ) ) {
		add_submenu_page( 'edit.php?post_type=cars_for_sale', __('Location Settings', 'car-demon'), __('Location Settings', 'car-demon'), 'edit_pages', 'car_demon_plugin_options', 'car_demon_plugin_options_do_page' );
	}
	add_submenu_page( 'edit.php?post_type=cars_for_sale', 'Car Demon' . __(' Settings', 'car-demon'), __('Settings', 'car-demon'), 'edit_pages', 'car_demon_settings_options', 'car_demon_settings_options_do_page' );

}
add_action( 'admin_menu', 'car_demon_settings_page' );

function get_my_post_thumbnail_id_detail_eil( $post_id = NULL ) {
	global $id;
	$post_id = ( NULL === $post_id ) ? $id : $post_id;
	$my_pic = get_post_meta( $post_id, '_thumbnail_id', true );
	return $my_pic;
}

function car_demon_admin_car_scripts() {
	if ( defined( 'CD_NON_NUMERIC_PRICE' ) ) {
		$non_numeric_price = 'Yes';
	} else {
		$non_numeric_price = 'No';
	}
	wp_register_script( 'car-demon-admin-js', plugins_url() . '/car-demon/admin/js/car-demon-admin.js', array(), CAR_DEMON_VER, true );
	wp_localize_script( 'car-demon-admin-js', 'cdAdminParams', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'error1' => __( 'You must fill out both fields before adding a new option group.', 'car-demon' ),
		'spinner' => CAR_DEMON_PATH . 'images/wpspin_light.gif',
		'sample_msg' => __( 'Please be patient while the vehicles and their images import.', 'car-demon' ),
		'msg_update' => __( 'Option Group Updated', 'car-demon' ),
		'bad_price_msg' => __( 'Please use numeric values only.', 'car-demon' ),
		'reset_msg' => __( 'Are you sure? This will reset all of your Car Demon Settings!', 'car-demon' ),
		'non_numeric_price' => $non_numeric_price,
	));
	wp_enqueue_script( 'car-demon-admin-js' );
	wp_enqueue_style( 'car-demon-admin-css', plugins_url() . '/car-demon/admin/css/cd-admin.css', array(), CAR_DEMON_VER );
}
add_action( "wp_ajax_car_demon_admin_update", "car_demon_admin_update" );
add_action( "wp_ajax_nopriv_car_demon_admin_update", "car_demon_admin_update" );
add_action( "wp_ajax_car_demon_add_option_group", "car_demon_add_option_group" );
add_action( "wp_ajax_nopriv_car_demon_add_option_group", "car_demon_add_option_group" );
add_action( "wp_ajax_car_demon_remove_option_group", "car_demon_remove_option_group" );
add_action( "wp_ajax_nopriv_car_demon_remove_option_group", "car_demon_remove_option_group" );
add_action( "wp_ajax_car_demon_update_option_group", "car_demon_update_option_group" );
add_action( "wp_ajax_nopriv_car_demon_update_option_group", "car_demon_update_option_group" );
add_action( "wp_ajax_car_demon_update_default_fields", "car_demon_update_default_fields" );
add_action( "wp_ajax_nopriv_car_demon_update_default_fields", "car_demon_update_default_fields" );
add_action( "wp_ajax_car_demon_update_default_labels", "car_demon_update_default_labels" );
add_action( "wp_ajax_nopriv_car_demon_update_default_labels", "car_demon_update_default_labels" );

function car_demon_admin_update() {
	$post_id = sanitize_text_field( $_POST['post_id'] );
	$fld = sanitize_text_field( $_POST['fld'] );
	$val = sanitize_text_field( $_POST['val'] );
	update_post_meta( $post_id, $fld, $val );
}

function car_demon_options() {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$default = array();
	$default['currency_symbol'] = '$';
	$default['currency_symbol_after'] = '';
	$default['vinquery_id'] = '';
	$default['vinquery_type'] = '0';
	$default['use_about'] = 'Yes';
	$default['adfxml'] = 'No';
	$default['use_compare'] = 'Yes';
	$default['dynamic_load'] = 'No';
	$default['secure_finance'] = 'Yes';
	$default['use_theme_files'] = 'No';
	$default['mobile_chat_code'] = '';
	$default['mobile_theme'] = 'No';
	$default['mobile_logo'] = '';
	$default['mobile_header'] = 'Yes';
	$default['validate_phone'] = 'Yes';
	$default['dynamic_ribbons'] = 'No';
	$default['before_listings'] = '';
	$default['use_post_title'] = 'No';
	$default['show_sold'] = 'No';
	$default['cc_admin'] = 'Yes';
	$default['do_sort'] = 'Yes';
	$default['drop_down_sort'] = 'Yes';
	$default['sort_price'] = 'Yes';
	$default['sort_miles'] = 'Yes';
	$default['hide_tabs'] = 'No';
	$default['popup_images'] = 'No';
	$default['custom_options'] = '';
	$default['use_form_css'] = 'Yes';
	$default['use_vehicle_css'] = 'Yes';
	$default['title_trim'] = '49';
	$default['cars_per_page'] = '9';
	$default['cd_cdrf_style'] = '';
	$default['cd_cdrf_page_style'] = '';
	$default['show_custom_specs'] = 'Yes';
	//= Sidebars
	$default['cd_page_id'] = '';
	$default['cd_page_css'] = '';
	$default['cd_content_id'] = '';
	$default['sidebar_id'] = '';
	$default['vehicle_sidebar_class'] = '';
	$default['left_list_sidebar'] = '';
	$default['right_list_sidebar'] = '';
	$default['vehicle_container'] = '';
	$default['left_vehicle_sidebar'] = '';
	$default['right_vehicle_sidebar'] = '';
	//= Auto Load Inventory Options
	$default['dl_container'] = '#demon-content';
	$default['dl_items'] = '.car_item';
	$default['dl_pagination'] = '.inventory_nav_bottom .wp-pagenavi';
	$default['dl_next'] = '.nextpostslink';
	//= Content Replacement
	$default['cd_cdrf_style'] = 'content-replacement';
	$default['cd_cdrf_page_style'] = 'content-replacement';
	$default['cd_slug'] = get_option('car-demon-slug','cars-for-sale');
	//= Similar cars on single car page
	$default['show_similar_cars'] = 'Yes';
	//= Do not use legacy session options
	$default['use_session'] = false;
	$default['search_form_count'] = 'No';

	$car_demon_options = array();
	$car_demon_options = get_option( 'car_demon_options', $default );
	if ( empty( $car_demon_options['currency_symbol'] ) ) {$car_demon_options['currency_symbol'] = $default['currency_symbol'];}
	if ( empty( $car_demon_options['currency_symbol_after'] ) ) {$car_demon_options['currency_symbol_after'] = $default['currency_symbol_after'];}
	if ( empty( $car_demon_options['vinquery_id'] ) ) {$car_demon_options['vinquery_id'] = $default['vinquery_id'];}
	if ( empty( $car_demon_options['vinquery_type'] ) ) {$car_demon_options['vinquery_type'] = $default['vinquery_type'];}
	if ( empty( $car_demon_options['use_about'] ) ) {$car_demon_options['use_about'] = $default['use_about'];}
	if ( empty( $car_demon_options['adfxml'] ) ) {$car_demon_options['adfxml'] = $default['adfxml'];}
	if ( empty( $car_demon_options['use_compare'] ) ) {$car_demon_options['use_compare'] = $default['use_compare'];}
	if ( empty( $car_demon_options['dynamic_load'] ) ) {$car_demon_options['dynamic_load'] = $default['dynamic_load'];}
	if ( empty( $car_demon_options['secure_finance'] ) ) {$car_demon_options['secure_finance'] = $default['secure_finance'];}
	if ( empty( $car_demon_options['use_theme_files'] ) ) {$car_demon_options['use_theme_files'] = $default['use_theme_files'];}
	if ( empty( $car_demon_options['mobile_chat_code'] ) ) {$car_demon_options['mobile_chat_code'] = $default['mobile_chat_code'];}
	if ( empty( $car_demon_options['mobile_theme'] ) ) {$car_demon_options['mobile_theme'] = $default['mobile_theme'];}
	if ( empty( $car_demon_options['mobile_logo'] ) ) {$car_demon_options['mobile_logo'] = $default['mobile_logo'];}
	if ( empty( $car_demon_options['mobile_header'] ) ) {$car_demon_options['mobile_header'] = $default['mobile_header'];}
	if ( empty( $car_demon_options['validate_phone'] ) ) {$car_demon_options['validate_phone'] = $default['validate_phone'];}
	if ( empty( $car_demon_options['dynamic_ribbons'] ) ) {$car_demon_options['dynamic_ribbons'] = $default['dynamic_ribbons'];}
	if ( empty( $car_demon_options['before_listings'] ) ) {$car_demon_options['before_listings'] = $default['before_listings'];}
	if ( empty( $car_demon_options['use_post_title'] ) ) {$car_demon_options['use_post_title'] = $default['use_post_title'];}
	if ( empty( $car_demon_options['show_sold'] ) ) {$car_demon_options['show_sold'] = $default['show_sold'];}
	if ( empty( $car_demon_options['cc_admin'] ) ) {$car_demon_options['cc_admin'] = $default['cc_admin'];}
	if ( empty( $car_demon_options['drop_down_sort'] ) ) {$car_demon_options['drop_down_sort'] = $default['drop_down_sort'];}
	if ( empty( $car_demon_options['do_sort'] ) ) {$car_demon_options['do_sort'] = $default['do_sort'];}
	if ( empty( $car_demon_options['sort_price'] ) ) {$car_demon_options['sort_price'] = $default['sort_price'];}
	if ( empty( $car_demon_options['sort_miles'] ) ) {$car_demon_options['sort_miles'] = $default['sort_miles'];}
	if ( empty( $car_demon_options['hide_tabs'] ) ) {$car_demon_options['hide_tabs'] = $default['hide_tabs'];}
	if ( empty( $car_demon_options['popup_images'] ) ) {$car_demon_options['popup_images'] = $default['popup_images'];}
	if ( empty( $car_demon_options['custom_options'] ) ) {$car_demon_options['custom_options'] = $default['custom_options'];}
	if ( empty( $car_demon_options['use_form_css'] ) ) {$car_demon_options['use_form_css'] = $default['use_form_css'];}
	if ( empty( $car_demon_options['use_vehicle_css'] ) ) {$car_demon_options['use_vehicle_css'] = $default['use_vehicle_css'];}
	if ( empty( $car_demon_options['title_trim'] ) ) {$car_demon_options['title_trim'] = $default['title_trim'];}
	if ( empty( $car_demon_options['cars_per_page'] ) ) {$car_demon_options['cars_per_page'] = $default['cars_per_page'];}
	if ( empty( $car_demon_options['show_custom_specs'] ) ) {$car_demon_options['show_custom_specs'] = $default['show_custom_specs'];}
	if ( empty( $car_demon_options['cd_slug'] ) ) {$car_demon_options['cd_slug'] = $default['cd_slug'];}
	//= Sidebars
	if ( empty( $car_demon_options['cd_page_id'] ) ) {$car_demon_options['cd_page_id'] = $default['cd_page_id'];}
	if ( empty( $car_demon_options['cd_page_css'] ) ) {$car_demon_options['cd_page_css'] = $default['cd_page_css'];}
	if ( empty( $car_demon_options['cd_content_id'] ) ) {$car_demon_options['cd_content_id'] = $default['cd_content_id'];}
	if ( empty( $car_demon_options['vehicle_sidebar_class'] ) ) {$car_demon_options['vehicle_sidebar_class'] = $default['vehicle_sidebar_class'];}
	if ( empty( $car_demon_options['left_list_sidebar'] ) ) {$car_demon_options['left_list_sidebar'] = $default['left_list_sidebar'];}
	if ( empty( $car_demon_options['right_list_sidebar'] ) ) {$car_demon_options['right_list_sidebar'] = $default['right_list_sidebar'];}
	if ( empty( $car_demon_options['vehicle_container'] ) ) {$car_demon_options['vehicle_container'] = $default['vehicle_container'];}
	if ( empty( $car_demon_options['left_vehicle_sidebar'] ) ) {$car_demon_options['left_vehicle_sidebar'] = $default['left_vehicle_sidebar'];}
	if ( empty( $car_demon_options['right_vehicle_sidebar'] ) ) {$car_demon_options['right_vehicle_sidebar'] = $default['right_vehicle_sidebar'];}
	if ( empty( $car_demon_options['sidebar_id'] ) ) {$car_demon_options['sidebar_id'] = $default['sidebar_id'];}
	//= Content Replacement
	if ( empty( $car_demon_options['cd_cdrf_style'] ) ) {$car_demon_options['cd_cdrf_style'] = $default['cd_cdrf_style'];}
	if ( empty( $car_demon_options['cd_cdrf_page_style'] ) ) {$car_demon_options['cd_cdrf_page_style'] = $default['cd_cdrf_page_style'];}
	//= Auto Load Inventory Options
	if ( empty( $car_demon_options['dl_container'] ) ) {$car_demon_options['dl_container'] = $default['dl_container'];}
	if ( empty( $car_demon_options['dl_items'] ) ) {$car_demon_options['dl_items'] = $default['dl_items'];}
	if ( empty( $car_demon_options['dl_pagination'] ) ) {$car_demon_options['dl_pagination'] = $default['dl_pagination'];}
	if ( empty( $car_demon_options['dl_next'] ) ) {$car_demon_options['dl_next'] = $default['dl_next'];}
	//= Similar cars
	if ( empty( $car_demon_options['show_similar_cars'] ) ) {$car_demon_options['show_similar_cars'] = $default['show_similar_cars'];}
	//= Legacy session options
	if ( empty( $car_demon_options['use_session'] ) ) {$car_demon_options['use_session'] = $default['use_session'];}
	//= Search form options
	if ( empty( $car_demon_options['search_form_count'] ) ) {$car_demon_options['search_form_count'] = $default['search_form_count'];}

	return $car_demon_options;
}
function car_demon_settings_options_do_page() {
	// in Welcome.php
	cd_welcome_screen_content();
}
function car_demon_settings_form() {
	echo '<div class="settings_wrap"><div id="icon-tools" class="icon32"></div><h1>' . __( 'Car Demon Settings', 'car-demon' ) . '</h1>';
	if ( isset( $_POST['reset_car_demon'] ) ) {
		reset_car_demon();
	} else {
		if( isset( $_POST['update_car_demon']) == 1) {
			update_car_demon_settings();
		}
	}

	$car_demon_options = car_demon_options();
	echo __( 'For support please visit', 'car-demon' ) . ' <a href="http://cardemons.com" target="demon_win">CarDemons.com</a><br /><br />';
	echo __( 'Click on an option title to show or hide options in each section.', 'car-demon' ) . '<br />';
	echo '<hr />';
	echo '<div data-status="0" data-open-close-text="' . __( 'Close all sections', 'car-demon' ) . '" class="cd_admin_show_all">';
		echo __( 'Open all sections', 'car-demon' );
	echo '</div>';
	echo '<hr />';
	echo '<form action="edit.php" id="cd_settings" name="cd_settings" method="get">';
		echo '<input type="hidden" name="post_type" value="cars_for_sale" />';
		echo '<input type="hidden" name="page" value="car_demon_settings_options" />';
		echo '<input type="hidden" name="edit_vehicle_options" value="1" />';
	echo '</form>';
	echo '<form action="" method="post">';
		//= Save Start
		echo '<fieldset class="cd_admin_group cd_save_settings">';
			echo '<legend>';
				echo __( 'Save settings','car-demon' );
			echo '</legend>';
			echo '<blockquote>';
				echo __( 'After you make your changes click the "Update Car Demon" button.', 'car-demon' ) . '<br />';
			echo '</blockquote>';			
			echo '<p><input type="submit" value="' . __( 'Update Car Demon', 'car-demon' ) . '" />';
			echo '<input type="submit" name="reset_car_demon" class="reset_car_demon" value="' . __( 'Reset to Default', 'car-demon' ) . '" /></p>';
		echo '</fieldset>';
		//= Save Stop

		echo '<input type="hidden" name="update_car_demon" value="1" />';
		//= Currency Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>+ ';
				echo __( 'Currency Options', 'car-demon' );
			echo '</legend>';
			echo '<span class="cd_option_group">';
				echo '<br />*' . __( 'Currency Symbol', 'car-demon' ) . ':<br />';
				echo '<input type="text" name="currency_symbol" value="' . $car_demon_options['currency_symbol'] . '" /><br />';
				echo '<br />*' . __( 'Currency Symbol After Price', 'car-demon' ) . ':<br />';
				echo '<input type="text" name="currency_symbol_after" value="' . $car_demon_options['currency_symbol_after'] . '" /><br />';
			echo '</span>';
		echo '</fieldset>';
		//= Currency Stop

		//= VinQuery Start		
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>+ ';
				echo __( 'VIN Decode Options', 'car-demon' );
			echo '</legend>';
			echo '<span class="cd_option_group">';
				echo '<br />';
				echo __( 'Sign up for a decode account and fill out your vehicles options by just entering a VIN number.', 'car-demon' );
				echo '<br />*' . __( 'VinQuery.com Access Code', 'car-demon' ) . ':<br />';
				echo '<input type="text" name="vinquery_id" value="' . $car_demon_options['vinquery_id'] . '" />';
				echo '*(optional)<br />';
				$vinquery_type_num = $car_demon_options['vinquery_type'];
				if ( empty( $vinquery_type_num ) ) {
					$vinquery_type_num = 0;
				}
				$select_basic = '';
				$select_standard = '';
				$select_extended = '';
				$select_lite = '';
				if ( $vinquery_type_num == 0 ) {
					$select_basic = ' selected';
				} elseif ( $vinquery_type_num == 1 ) {
					$select_standard = ' selected';
				} elseif ( $vinquery_type_num == 2 ) {
					$select_extended = ' selected';
				} elseif ( $vinquery_type_num == 3 ) {
					$select_lite = ' selected';
				}
				echo '<br />' . __( 'VinQuery.com Report Type', 'car-demon' ) . ':<br />';
				echo '<select name="vinquery_type">
						<option value="2"' . $select_extended . '>' . __( 'Extended', 'car-demon' ) . '</option>
						<option value="1"' . $select_standard . '>'. __( 'Standard', 'car-demon' ) . '</option>
						<option value="0"' . $select_basic . '>'. __( 'Basic', 'car-demon' ) . '</option>
						<option value="3"' . $select_lite . '>'. __( 'Lite', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br /><strong>';
				echo __( 'Did you know you can get trial account from VinQuery?', 'car-demon' );
				echo '<br />';
				echo '<a href="http://www.cardemons.com/vinquery-com/" target="vin_win">' . __( 'Learn more here!', 'car-demon' ) . '</a></strong>';
			echo '</span>';
		echo '</fieldset>';
		//= VinQuery Stop

		//= Search Result Start
		$x = '<fieldset class="cd_admin_group">';
			$x .= '<legend>';
				$x .= __( '+ Search Results Page', 'car-demon');
			$x .= '</legend>';
			$x .= '<p class="cd_option_group">';
				$x .= __( 'Select the default page for your search results:', 'car-demon' ) . '<br />';
				if ( ! isset( $car_demon_options['inventory_page'] ) ) {
					$car_demon_options['inventory_page'] = get_bloginfo( 'wpurl' );
				}
				$selected = url_to_postid( $car_demon_options['inventory_page'] );
				$args = array(
					'depth'                 => 0,
					'child_of'              => 0,
					'selected'              => $selected,
					'echo'                  => 0,
					'name'                  => 'inventory_page',
					'id'                    => 'inventory_page', // string
					'class'                 => 'select_inventory_page', // string
					'show_option_none'      => 'Default', // string
					'show_option_no_change' => null, // string
					'option_none_value'     => null, // string
				);
				$x .= wp_dropdown_pages( $args );
				$x .= '<br />';
				$x .= __( 'Point the search result page to the page with your inventory shortcode [cd_inventory] . ', 'car-demon' );
				$x .= '<br />';
				$x .= __( 'This will change the default search results page for all search forms.', 'car-demon' );
				$x .= '<br />';
				$x .= __( 'You can override this by setting it in the search form widget or shortcode.', 'car-demon' );
			$x .= '</p>';
		$x .= '</fieldset>';
		echo $x;
		//= Search Result Stop

		//= Style Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>+ ';
				echo __( 'Style Options', 'car-demon' );
			echo '</legend>';
			//==============
			echo '<span class="cd_option_group">';
				echo '<br />' . __( 'Use Form CSS?', 'car-demon' ) . ':<br />';
				echo '<select name="use_form_css">
						<option value="Yes"' . ($car_demon_options['use_form_css'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['use_form_css'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Use Vehicle CSS?', 'car-demon' ) . ':<br />';
				echo '<select name="use_vehicle_css">
						<option value="Yes"' . ($car_demon_options['use_vehicle_css'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['use_vehicle_css'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Show similar cars on single vehiclepage?', 'car-demon' ) . ':<br />';
				echo '<select name="show_similar_cars">
						<option value="Yes"' . ($car_demon_options['show_similar_cars'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['show_similar_cars'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Use Title field for Vehicle Titles? - If No then title will be "Year Make Model"', 'car-demon' ) . ':<br />';
				echo '<select name="use_post_title">
						<option value="Yes"' . ($car_demon_options['use_post_title'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['use_post_title'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Trim title', 'car-demon' ) . ':<br />';
				echo '<input type="text" name="title_trim" value="' . $car_demon_options['title_trim'] . '" /><br />';
				echo '<br />' . __( 'Use Dynamic Ribbons?', 'car-demon' ) . ':<br />';
				echo '<select name="dynamic_ribbons">
						<option value="Yes"' . ($car_demon_options['dynamic_ribbons'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['dynamic_ribbons'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
			echo '</span>';
		echo '</fieldset>';
		//= Style Option Stop

		//= Vehicle Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>+ ';
				echo __( 'Vehicle Options', 'car-demon' );
			echo '</legend>';
			echo '<span class="cd_option_group">';
				echo '<br />' . __( 'Hide all Vehicle Option Tabs?', 'car-demon' ) . ':<br />';
				echo '<select name="hide_tabs">
						<option value="Yes"' . ($car_demon_options['hide_tabs'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['hide_tabs'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Use About Tab on Vehicle Pages', 'car-demon' ) . ':<br />';
				echo '<select name="use_about">
						<option value="Yes"' . ($car_demon_options['use_about'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['use_about'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				//= Change next value to Yes to enable this feature
				if ($car_demon_options['hide_tabs'] == 'Yes') {
					echo '<br />' . __( 'Custom Vehicle Options', 'car-demon' ) . ':<br />';
					if ( empty( $car_demon_options['custom_options'] ) ) {
						$car_demon_options['custom_options'] = cd_get_default_options();
					}
					echo '<textarea name="custom_options" cols="60" rows="6">' . $car_demon_options['custom_options'] . ' </textarea><br />';
					echo __( 'Separate options with a comma. The options you enter here will appear on the vehicle edit page under "Custom Options"', 'car-demon' );
				}
				car_demon_settings_edit_vehicle_options();
			echo '</span>';
		echo '</fieldset>';
		//= Vehicle Option Stop

		//= Legacy Option Start
		//= Allow users to return to legacy show_custom_specs option
		if (isset($_GET['advanced'] ) ) {
			echo '<fieldset class="cd_admin_group">';
				echo '<legend>+ ';
					echo __( 'Legacy Options', 'car-demon' );
				echo '</legend>';
				echo '<span class="cd_option_group">';
					echo '<br />' . __( 'Use Custom Specs?', 'car-demon' ) . ':<br />';
					echo '<br />' . __( 'This option was added to allow support for users that can not migrate to the new custom specs code. If you are having problems viewing your vehicle specs or if information does not appear to be updating then try setting this to No.', 'car-demon' ) . '<br />';
					echo '<select name="show_custom_specs">
							<option value="Yes"' . ($car_demon_options['show_custom_specs'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
							<option value="No"' . ($car_demon_options['show_custom_specs'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
						</select><br />';
					echo '</span>';
			echo '</fieldset>';
		}

		//= List Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>+ ';
				echo __( 'List Options', 'car-demon' );
			echo '</legend>';
			//==============
			echo '<span class="cd_option_group">';
				echo '<br />' . __( 'Max number of vehicles in search results and archive pages:', 'car-demon' ) . '<br />';
				echo '<input type="text" name="cars_per_page" id="cars_per_page" value=' . $car_demon_options['cars_per_page'] . ' /><br />';
				if ($car_demon_options['cd_cdrf_style'] != 'content-replacement') {
					echo '<br />' . __( 'Display before listings:', 'car-demon' ) . '<br />';
					echo '<textarea name="before_listings" rows="5" cols="60">' . $car_demon_options['before_listings'] . '</textarea><br />';
				}
				echo '<br />' . __( 'Load Next Inventory Page on Scroll', 'car-demon' ) . ':<br />';
				echo '<select name="dynamic_load" class="cd_dynamic_load">
						<option value="Yes"' . ($car_demon_options['dynamic_load'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['dynamic_load'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
					if ( $car_demon_options['dynamic_load'] == 'No' ) {
						$hidden = ' cd_hidden';
					} else {
						$hidden = '';
					}
					echo '<fieldset class="cd_admin_group cd_auto_load' . $hidden . '">';
						echo '<legend>';
							echo __( 'Auto Load Inventory Options', 'car-demon' );
						echo '</legend>';
						echo '<br />' . __( 'You must match the object settings of your theme for dynamic load to work.', 'car-demon' ) . '<br />';
						echo '<br />' . __( 'You can use an ID or a Class to identify an object.', 'car-demon' ) . '<br /><br />';
						echo '<br />' . __( 'Class or ID of content container - default .grid-box.width100', 'car-demon' ) . '<br />';
						echo '<input type="text" name="dl_container" id="dl_container" value="' . $car_demon_options['dl_container'] . '" /><br />';
						echo '<br />' . __( 'Class or ID of item containers - default .item', 'car-demon' ) . '<br />';
						echo '<input type="text" name="dl_items" id="dl_items" value="' . $car_demon_options['dl_items'] . '" /><br />';
						echo '<br />' . __( 'Class or ID of pagination container - default .pagination', 'car-demon' ) . '<br />';
						echo '<input type="text" name="dl_pagination" id="dl_pagination" value="' . $car_demon_options['dl_pagination'] . '" /><br />';
						echo '<br />' . __( 'Class or ID of pagination next container container - default .next-post a', 'car-demon' ) . '<br />';
						echo '<input type="text" name="dl_next" id="dl_next" value="' . $car_demon_options['dl_next'] . '" /><br />';
					echo '</fieldset>';
				echo '<br />' . __( 'Show sold vehicles in search results?', 'car-demon' ) . ':<br />';
				echo '<select name="show_sold">
						<option value="' . $car_demon_options['show_sold'] . '">' . $car_demon_options['show_sold'] . '</option>
						<option value="Yes"' . ($car_demon_options['show_sold'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['show_sold'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Use Compare Vehicle Option', 'car-demon' ) . ':<br />';
				echo '<select name="use_compare">
						<option value="' . $car_demon_options['use_compare'] . '">' . $car_demon_options['use_compare'] . '</option>
						<option value="Yes"' . ($car_demon_options['use_compare'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['use_compare'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select> ' . __( 'You must use the vehicle compare widget to display the list to your visitors .','car-demon' ) . '<br />';
				echo '<hr />' . __( 'Show sorting options on vehicle listing pages?', 'car-demon' ) . ':<br />';
				echo '<select name="do_sort">
						<option value="' . $car_demon_options['do_sort'] . '">' . $car_demon_options['do_sort'] . '</option>
						<option value="Yes"' . ($car_demon_options['do_sort'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['do_sort'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<blockquote>';
					echo '<hr />' . __( 'Use Drop down sorting?', 'car-demon' ) . ':<br />';
					echo '<select name="drop_down_sort">
							<option value="' . $car_demon_options['drop_down_sort'] . '">' . $car_demon_options['drop_down_sort'] . '</option>
							<option value="Yes"' . ($car_demon_options['drop_down_sort'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
							<option value="No"' . ($car_demon_options['drop_down_sort'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
						</select><br />';
					echo '<br />' . __( 'Sort By Price? - Sorting options must be set to yes to use this feature.', 'car-demon' ) . ':<br />';
					echo '<select name="sort_price">
							<option value="' . $car_demon_options['sort_price'] . '">' . $car_demon_options['sort_price'] . '</option>
							<option value="Yes"' . ($car_demon_options['sort_price'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
							<option value="No"' . ($car_demon_options['sort_price'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
						</select><br />';
					echo '<br />' . __( 'Sort By Mileage? - Sorting options must be set to yes to use this feature.', 'car-demon' ) . ':<br />';
					echo '<select name="sort_miles">
							<option value="' . $car_demon_options['sort_miles'] . '">' . $car_demon_options['sort_miles'] . '</option>
							<option value="Yes"' . ($car_demon_options['sort_miles'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
							<option value="No"' . ($car_demon_options['sort_miles'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
						</select><br />';
				echo '</blockquote>';
			echo '</span>';
		echo '</fieldset>';
		//= List Option Stop

		//= Form Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>+ ';
				echo __( 'Form Options', 'car-demon' );
			echo '</legend>';
			//==============
			echo '<span class="cd_option_group">';
				echo '<br />';
					echo '<a href="edit-tags.php?taxonomy=vehicle_location&post_type=cars_for_sale">';
						echo __( 'You also need to setup the information on the Locations page.', 'car-demon' );
					echo '</a>';
				echo '<hr />';
				echo '<br />' . __( 'Blind Carbon Copy Forms to Admin?', 'car-demon' ) . ':<br />';
				echo '<select name="cc_admin">
						<option value="Yes"' . ($car_demon_options['cc_admin'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['cc_admin'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Include ADFxml with Leads?', 'car-demon' ) . ':<br />';
				echo '<select name="adfxml">
						<option value="Yes"' . ($car_demon_options['adfxml'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['adfxml'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Validate Phone Numbers?', 'car-demon' ) . ':<br />';
				echo '<select name="validate_phone">
						<option value="Yes"' . ($car_demon_options['validate_phone'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['validate_phone'] == 'NO' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Disable Finance Form if it isn\'t loaded with SSL', 'car-demon' ) . ':<br />';
				echo '<select name="secure_finance">
					<option value="Yes"' . ($car_demon_options['secure_finance'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
					<option value="No"' . ($car_demon_options['secure_finance'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<br />' . __( 'Disable count on search forms', 'car-demon' ) . ':<br />';
				echo '<select name="search_form_count">
					<option value="Yes"' . ($car_demon_options['search_form_count'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
					<option value="No"' . ($car_demon_options['search_form_count'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
			echo '</span>';
		echo '</fieldset>';
		//= Form Option Stop

		//= Slug Start
		echo '<fieldset class="cd_admin_group cd_slug_option">';
			echo '<legend>+ ';
				echo __( 'URL Slug Options', 'car-demon' );
			echo '</legend>';
			echo '<span class="cd_option_group">';
				echo '<br />*' . __( 'URL path for inventory (ie cars-for-sale creates http://yoursite.com/cars-for-sale)', 'car-demon' ) . '<br />';
				echo '<br />*' . __( 'If you enter "inventory" it creates http://yoursite.com/inventory', 'car-demon' ) . '<br />';
				echo '<input type="text" name="cd_slug" value="' . $car_demon_options['cd_slug'] . '" /><br />';
				/*echo '<br />*' . __( 'You will need to refresh your permalinks after changing this setting.', 'car-demon' ) . '<br />';*/
				echo '<br />*' . __( 'Only use lower case letters, no spaces but you may use - to seperate words.', 'car-demon' ) . '<br />';
			echo '</span>';
		echo '</fieldset>';
		//= Slug Stop

		//= Hook for additional settings
		$holder = '';
		$location = '';
		$car_demon_settings_hook = apply_filters( 'car_demon_settings_hook', $holder, $location ); //= deprecated
		do_action( 'cd_settings_hook' ); //= deprecated
		do_action( 'cd_settings_action' );
		
		//= Mobile Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>+ ';
				echo __( 'Mobile Options', 'car-demon' );
			echo '</legend>';
			echo '<span class="cd_option_group">';
				echo '<br />' . __( 'Is Mobile Theme Installed?', 'car-demon' ) . ':<br />';
				echo '<select name="mobile_theme">
						<option value="Yes"' . ($car_demon_options['mobile_theme'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['mobile_theme'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				if ( $car_demon_options['mobile_theme'] == 'Yes' ) {
					echo '<br />' . __( 'Use Default Mobile Header with Mobile Logo?', 'car-demon' ) . ':<br />';
					echo '<select name="mobile_header">
						<option value="Yes"' . ($car_demon_options['mobile_header'] == 'Yes' ? ' selected' : '' ) . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['mobile_header'] == 'No' ? ' selected' : '' ) . '>' . __( 'No', 'car-demon' ) . '</option>
						</select><br />';
					echo '<br />' . __( 'Mobile Header Logo', 'car-demon' ) . '<br />';
					echo '<table><tr valign="top">
						<td><label for="upload_mobile_logo">
						<input name="mobile_logo" id="mobile_logo" type="text" size="36" value="' . $car_demon_options['mobile_logo'] . '" />
						<input id="upload_mobile_logo_button" type="button" value="' . __( 'Upload Logo', 'car-demon' ) . '" />
						<br />' . __( 'Enter a URL or upload an image for the Mobile Logo. 169x58 px', 'car-demon' ) . '
						</label></td>
						</tr></table>';
					echo '<br />' . __( 'Mobile Chat Code', 'car-demon' ) . '<br />';
					echo '<textarea name="mobile_chat_code" rows="5" cols="60">' . $car_demon_options['mobile_chat_code'] . '</textarea><br />';
				}
			echo '</span>';
		echo '</fieldset>';
		//= Mobile Option Stop

		//= Start Legacy
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>+ ';
				echo __( 'Legacy Options', 'car-demon' );
			echo '</legend>';
			echo '<span class="cd_option_group">';
				echo __( 'These options are provided for backwards support purposes.', 'car-demon');
				echo '<br />';
				echo __( 'It is suggested you not change these settings unless directed.', 'car-demon');
				echo '<br />';
				echo '<h4>';
					echo __( 'Use Legacy Settings', 'car-demon' );
				echo '</h4>';
				if (!isset($car_demon_options['use_session'] ) ) {
					$car_demon_options['use_session'] = 'No';
				} else {
					$car_demon_options['use_session'] = 'No';
				}

				echo '<select name="use_session">
						<option value="Yes"' . ( $car_demon_options['use_session'] == 'Yes' ? ' selected' : '') . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ($car_demon_options['use_session'] == 'No' ? ' selected' : '') . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
				echo '<hr />';

				echo '<br />' . __( 'Use included theme files?', 'car-demon' ) . ':<br />';
				echo '<select name="use_theme_files">
						<option value="Yes"' . ( $car_demon_options['use_theme_files'] == 'Yes' ? ' selected' : '') . '>' . __( 'Yes', 'car-demon' ) . '</option>
						<option value="No"' . ( $car_demon_options['use_theme_files'] == 'No' ? ' selected' : '') . '>' . __( 'No', 'car-demon' ) . '</option>
					</select><br />';
					//= Sidebar and Page ID code
					if ( $car_demon_options['use_theme_files'] == 'No' ) {
						$show_template_options = ' style="display:none;"';
					} else {
						$show_template_options = '';	
					}
					echo '<fieldset class="cd_admin_group"' . $show_template_options . '>';
						echo '<legend>';
							echo __( 'Advanced template options', 'car-demon' );
						echo '</legend>';
						echo '<blockquote>';
							echo '<br />' . __( 'These options give you greater control over the included template files.', 'car-demon' ) . '<br />';
							echo '<br />' . __( 'If you\'re not sure how to use these then please leave them set at their defaults.', 'car-demon' ) . '<hr />';
							echo '<br />' . __( 'Custom Page ID', 'car-demon' ) . ':<br />';
								$cd_page_id = $car_demon_options['cd_page_id'];
								echo '<input type="text" id="cd_page_id" name="cd_page_id" value="' . $cd_page_id . '" />';
								echo '<br />' . __( 'If you enter an ID then the vehicle content and sidebar will be enclosed in a div with that ID.', 'car-demon' ) . '<br />';
							echo '<br />' . __( 'Custom Page CSS class', 'car-demon' ) . ':<br />';
								$cd_page_css = $car_demon_options['cd_page_css'];
								echo '<input type="text" id="cd_page_css" name="cd_page_css" value="' . $cd_page_css . '" />';
								echo '<br />' . __( 'If you enter a class name then the vehicle content and sidebar will be enclosed in a div with that class. You must set an ID above for this to work.', 'car-demon' ) . '<br />';
							echo '<br /><br />' . __( 'Custom Sidebar ID', 'car-demon' ) . ':<br />';
								$sidebar_id = $car_demon_options['sidebar_id'];
								echo '<input type="text" id="sidebar_id" name="sidebar_id" value="' . $sidebar_id . '" />';
							echo '<br />' . __( 'Custom Sidebar CSS class', 'car-demon' ) . ':<br />';
								$vehicle_sidebar_class = $car_demon_options['vehicle_sidebar_class'];
								echo '<input type="text" id="vehicle_sidebar_class" name="vehicle_sidebar_class" value="' . $vehicle_sidebar_class . '" />';
							echo '<br /><br />' . __( 'Left Sidebar on List Pages', 'car-demon' ) . ':<br />';
								$left_list_sidebar = $car_demon_options['left_list_sidebar'];
								echo cd_sidebar_selectbox( 'left_list_sidebar', $left_list_sidebar );
							echo '<br />' . __( 'Right Sidebar on List Pages', 'car-demon' ) . ':<br />';
								$right_list_sidebar = $car_demon_options['right_list_sidebar'];
								echo cd_sidebar_selectbox( 'right_list_sidebar', $right_list_sidebar );
							echo '<br /><br />' . __( 'Left Sidebar on Vehicle Pages', 'car-demon' ) . ':<br />';
								$left_vehicle_sidebar = $car_demon_options['left_vehicle_sidebar'];
								echo cd_sidebar_selectbox( 'left_vehicle_sidebar', $left_vehicle_sidebar );
							echo '<br />' . __( 'Right Sidebar on Vehicle Pages', 'car-demon' ) . ':<br />';
								$right_vehicle_sidebar = $car_demon_options['right_vehicle_sidebar'];
								echo cd_sidebar_selectbox( 'right_vehicle_sidebar', $right_vehicle_sidebar );
							echo '<br /><br />' . __( 'Custom Vehicle Content ID', 'car-demon' ) . ':<br />';
								$cd_content_id = $car_demon_options['cd_content_id'];
								echo '<input type="text" id="cd_content_id" name="cd_content_id" value="' . $cd_content_id . '" />';
							echo '<br />' . __( 'Vehicle Content CSS container class', 'car-demon' ) . ':<br />';
								$vehicle_container = $car_demon_options['vehicle_container'];
								echo '<input type="text" id="vehicle_container" name="vehicle_container" value="' . $vehicle_container . '" />';
						echo '</blockquote>';
					echo '</fieldset>';
					//= End Sidebar Code
				echo '<hr />';
				echo '<fieldset>';
					cd_cdrf_style_options_do_page();
				echo '</fieldset>';

				echo '<div class="cd_welcome_clear"></div>';

				echo '<hr />';
				echo '<h4>';
					echo __( 'Taxonomy UI', 'car-demon' );
				echo '</h4>';
				$show_ui = get_option( 'cd_show_tax_ui', false );
				if ( $show_ui == true ) {
					echo '<a href="?post_type=cars_for_sale&page=car_demon_settings_options&show_ui=0">';
						_e('Hide Taxonomy UI', 'car-demon');
					echo '</a>';
				} else if ( $show_ui == false ) {
					echo '<a href="?post_type=cars_for_sale&page=car_demon_settings_options&show_ui=1">';
						_e( 'Show Taxonomy UI', 'car-demon' );
					echo '</a>';
				}
				echo '<p>';
					_e( 'In most cases you will only want to show the Taxonomy information for debugging purposes.', 'car-demon' );
				echo '</p>';
				echo '<p>';
					_e( 'Be aware that directly editing vehicle taxonomies on the vehicle edit page may not have the intended effect you are looking for.', 'car-demon' );
				echo '</p>';
			echo '</span>';
		echo '</fieldset>';
		//= Stop Legacy
	echo '</form>';

		//= Shortcodes Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>+ ';
				echo __( 'Shortcodes', 'car-demon' );
			echo '</legend>';
			echo '<span class="cd_option_group">';
				echo '<h2>' . __( 'Car Demon Pages', 'car-demon' ) . '</h2>';
				echo '<div class="admin_pages_div">';
					echo '<p>' . __( 'Car Demon comes with support for adding several custom forms and pages. You can quickly and easily add these pages by clicking the buttons below or you can create your own pages and simply paste the shortcode you want to use into the content of the page.', 'car-demon' ) . '</p>';
				echo '</div>';
				if ( isset( $_POST['add_page'] ) ) {
					$title = $_POST['title'];
					$type = $_POST['type'];
					$new_page_id = car_demon_add_page( $title, $type );
					$link = get_permalink( $new_page_id );
					echo '<a href="' . $link . '" target="_blank">New ' . $title . ' Page</a>';
				}
				echo '<div class="admin_add_pages_div">';
					echo '<form method="POST" action="">';
						echo '<input type="hidden" name="type" value="cd_inventory" />';
						echo '<input type="hidden" name="title" value="Inventory" />';
						echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="' . __( 'Add Inventory Page', 'car-demon' ) . '" />';
					echo '</form>';
					echo '<form method="POST" action="">';
						echo '<input type="hidden" name="type" value="contact" />';
						echo '<input type="hidden" name="title" value="Contact Us" />';
						echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="' . __( 'Add Contact Us Page', 'car-demon' ) . '" />';
					echo '</form>';
					echo '<form method="POST" action="">';
						echo '<input type="hidden" name="type" value="service_appointment" />';
						echo '<input type="hidden" name="title" value="Service Appointment" />';
						echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="' . __( 'Add Service Appointment Page', 'car-demon' ) . '" />';
					echo '</form>';
					echo '<form method="POST" action="">';
						echo '<input type="hidden" name="type" value="service_quote" />';
						echo '<input type="hidden" name="title" value="Service Quote" />';
						echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="' . __( 'Add Service Quote Page', 'car-demon' ) . '" />';
					echo '</form>';
					echo '<form method="POST" action="">';
						echo '<input type="hidden" name="type" value="parts" />';
						echo '<input type="hidden" name="title" value="Parts Quote" />';
						echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="' . __( 'Add Parts Quote Page', 'car-demon' ) . '" />';
					echo '</form>';
					echo '<form method="POST" action="">';
						echo '<input type="hidden" name="type" value="trade" />';
						echo '<input type="hidden" name="title" value="Trade In" />';
						echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="' . __( 'Add Trade In Page', 'car-demon' ) . '" />';
					echo '</form>';
					echo '<form method="POST" action="">';
						echo '<input type="hidden" name="type" value="finance" />';
						echo '<input type="hidden" name="title" value="Finance Application" />';
						echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="' . __( 'Add Finanace Application Page', 'car-demon' ) . '" />';
					echo '</form>';
					echo '<form method="POST" action="">';
						echo '<input type="hidden" name="type" value="qualify" />';
						echo '<input type="hidden" name="title" value="Pre-Qualify" />';
						echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="' . __( 'Add Pre-Qualify Page', 'car-demon' ) . '" />';
					echo '</form>';
					echo '<form method="POST" action="">';
						echo '<input type="hidden" name="type" value="staff" />';
						echo '<input type="hidden" name="title" value="Staff Page" />';
						echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="' . __( 'Add Staff Page', 'car-demon' ) . '" />';
					echo '</form>';
				echo '</div>';
				echo '<div class="admin_add_pages_shortcodes">';
					echo '<h3 class="admin_add_pages_shortcodes">' . __( 'Shortcodes', 'car-demon' ) . '</h3>';
					echo '<blockquote>';
						echo '[cd_inventory]<br />';
						echo '[contact_us]<br />';
						echo '[service_form]<br />';
						echo '[service_quote]<br />';
						echo '[part_request]<br />';
						echo '[trade]<br />';
						echo '[finance_form]<br />';
						echo '[qualify]<br />';
						echo '[staff_page]<br />';
					echo '</blockquote>';
				echo '</div>';
			echo '</span>';
		echo '</fieldset>';
		//= Shortcodes Stop

	echo '<br /><a href="http://www.cardemons.com" target="demon_win"><img title="Certified Support" src="' . CAR_DEMON_PATH . 'images/cd-certified-support.png" /></a>';
	// added for welcome screen
	echo '</div>';
}

function update_car_demon_settings() {
	$new = array();
	$new = get_option( 'car_demon_options' );
	if ( isset( $_POST['cd_slug'] ) ) {
		$new['cd_slug'] = sanitize_text_field( $_POST['cd_slug'] );
		update_option( 'car-demon-slug', $new['cd_slug'] );
		car_demon_create_post_type();
		flush_rewrite_rules();
	}
	if ( isset( $_POST['currency_symbol'] ) ) $new['currency_symbol'] = $_POST['currency_symbol'];
	if ( isset( $_POST['currency_symbol_after'] ) ) $new['currency_symbol_after'] = $_POST['currency_symbol_after'];
	if ( isset( $_POST['vinquery_id'] ) ) $new['vinquery_id'] = $_POST['vinquery_id'];
	if ( isset( $_POST['vinquery_type'] ) ) $new['vinquery_type'] = $_POST['vinquery_type'];
	if ( isset( $_POST['use_about'] ) ) $new['use_about'] = $_POST['use_about'];
	if ( isset( $_POST['adfxml'] ) ) $new['adfxml'] = $_POST['adfxml'];
	if ( isset( $_POST['use_compare'] ) ) $new['use_compare'] = $_POST['use_compare'];
	if ( isset( $_POST['secure_finance'] ) ) $new['secure_finance'] = $_POST['secure_finance'];
	if ( isset( $_POST['use_theme_files'] ) ) $new['use_theme_files'] = $_POST['use_theme_files'];
	if ( isset( $_POST['dynamic_load'] ) ) $new['dynamic_load'] = $_POST['dynamic_load'];
	if ( isset( $_POST['dynamic_ribbons'] ) ) $new['dynamic_ribbons'] = $_POST['dynamic_ribbons'];
	if ( isset( $_POST['mobile_chat_code'] ) ) $mobile_chat_code = $_POST['mobile_chat_code'];
	$mobile_chat_code = '';
	$mobile_chat_code = str_replace( "\'", "'", $mobile_chat_code );
	$mobile_chat_code = str_replace( '\"', '"', $mobile_chat_code );
	$mobile_chat_code = str_replace( '\\', '', $mobile_chat_code );	
	$new['mobile_chat_code'] = $mobile_chat_code;
	if ( isset( $_POST['custom_header_type'] ) ) $new['custom_header_type'] = $_POST['custom_header_type'];
	if ( isset( $_POST['mobile_theme'] ) ) {
		$new['mobile_theme'] = $_POST['mobile_theme'];
		if ( isset( $_POST['mobile_logo'] ) ) {
			$new['mobile_logo'] = $_POST['mobile_logo'];
		}
		if ( empty( $new['mobile_logo'] ) ) {
			$new['mobile_logo'] = CAR_DEMON_PATH . 'theme-files/images/mobile_header.png';
		}
	}
	if ( isset( $_POST['mobile_header'] ) ) $new['mobile_header'] = $_POST['mobile_header'];
	if ( isset( $_POST['validate_phone'] ) ) $new['validate_phone'] = $_POST['validate_phone'];
	if ( isset( $_POST['before_listings'] ) ) $new['before_listings'] = $_POST['before_listings'];
	if ( isset( $_POST['use_post_title'] ) ) $new['use_post_title'] = $_POST['use_post_title'];
	if ( isset( $_POST['show_sold'] ) ) $new['show_sold'] = $_POST['show_sold'];
	if ( isset( $_POST['cc_admin'] ) ) $new['cc_admin'] = $_POST['cc_admin'];
	if ( isset( $_POST['drop_down_sort'] ) ) $new['drop_down_sort'] = $_POST['drop_down_sort'];
	if ( isset( $_POST['do_sort'] ) ) $new['do_sort'] = $_POST['do_sort'];
	if ( isset( $_POST['sort_price'] ) ) $new['sort_price'] = $_POST['sort_price'];
	if ( isset( $_POST['sort_miles'] ) ) $new['sort_miles'] = $_POST['sort_miles'];
	if ( isset( $_POST['hide_tabs'] ) ) $new['hide_tabs'] = $_POST['hide_tabs'];
	if ( isset( $_POST['popup_images'] ) ) $new['popup_images'] = $_POST['popup_images'];
	if ( isset( $_POST['custom_options'] ) ) $new['custom_options'] = $_POST['custom_options'];
	if ( isset( $_POST['use_form_css'] ) ) $new['use_form_css'] = $_POST['use_form_css'];
	if ( isset( $_POST['use_vehicle_css'] ) ) $new['use_vehicle_css'] = $_POST['use_vehicle_css'];
	if ( isset( $_POST['title_trim'] ) ) $new['title_trim'] = $_POST['title_trim'];
	if ( isset( $_POST['cars_per_page'] ) ) $new['cars_per_page'] = $_POST['cars_per_page'];
	//= Legacy Specs option
	
	if ( isset( $_POST['show_custom_specs'] ) ) $new['show_custom_specs'] = $_POST['show_custom_specs'];
	//=Sidebars
	if ( isset( $_POST['vehicle_sidebar_class'] ) ) $new['vehicle_sidebar_class'] = $_POST['vehicle_sidebar_class'];
	if ( isset( $_POST['left_list_sidebar'] ) ) $new['left_list_sidebar'] = $_POST['left_list_sidebar'];
	if ( isset( $_POST['right_list_sidebar'] ) ) $new['right_list_sidebar'] = $_POST['right_list_sidebar'];
	if ( isset( $_POST['vehicle_container'] ) ) $new['vehicle_container'] = $_POST['vehicle_container'];
	if ( isset( $_POST['left_vehicle_sidebar'] ) ) $new['left_vehicle_sidebar'] = $_POST['left_vehicle_sidebar'];
	if ( isset( $_POST['right_vehicle_sidebar'] ) ) $new['right_vehicle_sidebar'] = $_POST['right_vehicle_sidebar'];
	if ( isset( $_POST['sidebar_id'] ) ) $new['sidebar_id'] = $_POST['sidebar_id'];
	if ( isset( $_POST['cd_content_id'] ) ) $new['cd_content_id'] = $_POST['cd_content_id'];
	if ( isset( $_POST['cd_page_id'] ) ) $new['cd_page_id'] = $_POST['cd_page_id'];
	if ( isset( $_POST['cd_page_css'] ) ) $new['cd_page_css'] = $_POST['cd_page_css'];
	if ( isset( $_POST['dl_container'] ) ) $new['dl_container'] = $_POST['dl_container'];
	if ( isset( $_POST['dl_items'] ) ) $new['dl_items'] = $_POST['dl_items'];
	if ( isset( $_POST['dl_pagination'] ) ) $new['dl_pagination'] = $_POST['dl_pagination'];
	if ( isset( $_POST['dl_next'] ) ) $new['dl_next'] = $_POST['dl_next'];
	if ( isset( $_POST['show_similar_cars'] ) ) $new['show_similar_cars'] = $_POST['show_similar_cars'];
	if ( isset( $_POST['search_form_count'] ) ) $new['search_form_count'] = $_POST['search_form_count'];
	if ( isset( $_POST['use_session'] ) ) $new['use_session'] = $_POST['use_session'];
	if ( isset( $_POST['inventory_page'] ) ) {
		$inventory_page_id = sanitize_text_field( $_POST['inventory_page'] );
		if ( FALSE === get_post_status( $inventory_page_id ) ) {
		  // The post does not exist
	  		$new['inventory_page'] = get_bloginfo( 'wpurl' );
		} else {
		  // The post exists
			$inventory_page = get_permalink( $inventory_page_id );
			$new['inventory_page'] = $inventory_page;
		}
	}
	cd_save_spec_caps();
	$new = apply_filters( 'cd_settings_filter', $new );
	update_option( 'car_demon_options', $new );
	$holder = '';
	$car_demon_settings_hook = apply_filters( 'car_demon_settings_update_hook', $holder ); //= deprecated
	do_action( 'cd_settings_update_action' );
	do_action( 'cd_settings_hook_update' ); //= deprecated
	global $car_demon_options;
	$car_demon_options = $new;
	echo '<h3 class="admin_settings_updated_title">' . __( 'SETTINGS HAVE BEEN UPDATED', 'car-demon' ) . '</h3>';
}
function reset_car_demon() {
	cd_default_options();
	delete_option( 'cd_vehicle_option_map' );
	delete_option( 'cd_default_field_labels' );
	delete_option( 'cd_show_hide_labels' );
	car_demon_create_post_type();
	flush_rewrite_rules();
	echo '<h3 class="admin_settings_updated_title">' . __( 'SETTINGS HAVE BEEN RESET', 'car-demon' ) . '</h3>';
}
function get_default_finance_description() {
	$x = '<span>' . __( 'This is not a contract to purchase. It is an application for financing a possible automotive purchase.', 'car-demon' ) . ' <br />
		  <strong> ' . __( 'You are not obligated to purchase', 'car-demon' ) . '</strong> ' . __( 'a vehicle if you submit this form.', 'car-demon' ) . ' </span>
		  <br />
		  <span>' . __( 'Your information is kept confidential and is used only to assist in obtaining financing for a potential vehicle purchase.', 'car-demon' ) . '<br />
		  ' . __( '*By clicking this button you agree to the terms  posted above.', 'car-demon' ) . '
		  </span>
	';
	return $x;
}
function get_default_finance_disclaimer() {
	$x = __( '
	*TERMS AND DISCLOSURE 
	
	The following terms of agreement apply to all of our online applications;
	
	we, us, our and ours as used below refer to the dealer and to the Financial 
	
	Institutions selected to receive your application.
	
	You authorize the dealer, as part of the credit underwriting process, to submit this 
	
	application and any other application submitted in connection with the proposed transaction
	
	to the Financial Institutions disclosed to you by the dealer, for review. In addition, in 
	
	accordance with the Fair Credit Reporting Act, you authorize that such Financial Institutions 
	
	may submit your applications for review to other Financial Institutions that may want to 
	
	purchase your contract.
	
	
	You agree that we and any Financial Institutions to which your application is submitted may 
	
	obtain a consumer credit report periodically from one or more consumer reporting agencies 
	
	(credit bureaus) in connection with the proposed transaction and any update, renewal, 
	
	refinancing, modification or extension of that transaction.
	
	You agree that we may verify your employment, pay, assets and debts, and that anyone 
	
	receiving a copy of this is authorized to provide us with such information.
	
	You further authorize us to gather whatever credit and employment history we consider
	
	necessary and appropriate in reviewing this application and any other applications 
	
	submitted in connection with the proposed transaction.
	
	We may keep this application and any other application submitted to us, and information 
	
	about you whether or not the application is approved. You certify that the information on 
	
	this application and in any other application submitted to us, is true and complete. You 
	
	understand that false statements may subject you to criminal penalties.
	
	FEDERAL NOTICES
	
	IMPORTANT INFORMATION ABOUT PROCEDURES FOR OPENING A NEW ACCOUNT
	
	To help the government fight the funding of terrorism and money laundering activities, 
	
	Federal law requires all financial institutions to obtain, verify, and record information that 
	
	identifies each person who opens an account. What this means for you: When you open an 
	
	account, we will ask for your name,address, date of birth, and other information that will  
	
	allow us to identify you. We may also ask to see your driver\'s license or other identifying 
	
	documents.
	
	STATE NOTICES
	
	Arizona, California, Idaho, Louisiana, Nevada, New Mexico, Texas, Washington or  
	
	Wisconsin Residents: If you, the applicant, are married and live in a community property 
	
	state, you should also provide the personal credit information on your spouse in the 
	
	co-applicant section. Your spouse is not required to be a co-applicant for the credit 
	
	requested unless he/she wishes to be a co-applicant.
	
	California Residents: An applicant, if married, may apply for a separate account.
	
	Ohio Residents: Ohio laws against discrimination require that all creditors make credit 
	
	equally available to all creditworthy customers and that credit reporting agencies maintain  
	
	separate credit histories on each individual upon request. The Ohio Civil Rights  
	
	Commission administers compliance with this law.
	
	New Hampshire Residents:If this is an application for balloon financing, you are entitled to 
	
	receive, upon request, a written estimate of the monthly payment amount that would be  
	
	required to refinance the balloon payment at the time such payment is due based on the
	
	creditor\'s current refinancing programs.
	
	New Hampshire Residents: In connection with your application for credit, we may request a 
	
	consumer report that contains information on your credit worthiness, credit standing,  
	
	personal characteristics and general reputation. If we grant you credit, we or our  
	
	servicer may order additional consumer reports in connection with any update, renewal  
	
	or extension of the credit. If you ask us, we will tell you whether we obtained a consumer  
	
	report and if we did, we will tell you the name and address of the consumer reporting 
	
	agency that gave us the report.
	
	Vermont Residents: By clicking on Submit, you authorize us and our employees or agents  
	
	to obtain and verify information about you (including one or more credit reports, information 
	
	about your employment and banking and credit relationships) that we may deem necessary  
	
	or appropriate in reviewing your application. If your application is approved and credit is 
	
	extended, you also authorize us, and our employees and agents, to obtain additional credit 
	
	reports and other information about you in connection with reviewing the account,  
	
	increasing the available credit on the account (if applicable), taking collection on  
	
	the account, or for any other legitimate purpose.
	
	Married Wisconsin Residents: Wisconsin law provides that no provision of any marital  
	
	property agreement, or unilateral statement, or court order applied to marital property  
	
	will adversely affect a creditor\'s interests unless, prior to the time that the credit 
	
	is granted, the creditor is furnished with a copy of the agreement, statement or decree,
	
	or has actual  knowledge of the adverse provision. If you are making this application  
	
	individually, and not jointly with your spouse, the full name and current address of 
	
	your spouse must be properly disclosed in the co-applicant section of this application.
	', 'car-demon' );
	return $x;
}
function get_default_description() {
	$x = __( 'This vehicle is ready to go right now.', 'car-demon' );
	return $x;
}

function car_demon_settings_edit_vehicle_options() {
	global $car_demon_options;
	$options = cd_get_vehicle_map();
	$description_options = $options['description'];
	$specs_options = $options['specs'];
	$safety_options = $options['safety'];
	$convenience_options = $options['convenience'];
	$comfort_options = $options['comfort'];
	$entertainment_options = $options['entertainment'];
	$about_us_options = $options['about_us'];
	if ( isset( $car_demon_options['hide_tabs'] ) ) {
		if ( $car_demon_options['hide_tabs'] == 'Yes' ) {
			$show_tabs = 0;
		} else {
			$show_tabs = 1;	
		}
	}
	
	echo '<div class="wrap_options"><h1>' . __( 'Edit Vehicle Labels', 'car-demon' ) . '</h1>';
	echo '<blockquote>';
		_e( 'Add and remove the groups and options that appear under the different vehicle information tabs.', 'car-demon' );
	echo '</blockquote>';
		echo '<h3 class="open_tab" id="cd_open_specs" data-status="closed">+ ' . __( 'Specs Tab', 'car-demon' ) . '</h3>';
		echo '<div class="tab" id="specs_tab">';
			echo '<h5 class="close_tab" id="cd_close_specs">- ' . __( 'Close Tab', 'car-demon' ) . '</h5>';
			echo '<fieldset class="cd_admin_group">';
				echo '<legend>' . __( 'Manage default fields','car-demon' ) . '</legend>';
				echo '<h4>' . __( 'Check the box next to each field to hide it.','car-demon' ) . '</h4>';
				echo '<h4>' . __( 'You may also relabel the field by changing the value in the box.','car-demon' ) . '</h4>';
				echo '<h5>' . __( 'These label changes are only reflected in the page content and do not change URL structures.','car-demon' ) . '</h5>';
				echo '<blockquote>';
					$show_hide = get_show_hide_fields();
					$field_labels = get_default_field_labels();
					$cd_spec_caps = get_cd_spec_caps();

					echo '<div id="sh_condition"><input type="checkbox"' . ( $show_hide['condition'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="condition" /><input type="text" id="label_condition" value="' . $field_labels['condition'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="condition" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Condition','car-demon' ) . '</div>';
						echo cd_specs_cap( 'condition', $cd_spec_caps );
					echo '<div id="sh_vin"><input type="checkbox"' . ( $show_hide['vin'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="vin" /><input type="text" id="label_vin" value="' . $field_labels['vin'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="vin" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Vin','car-demon' ) . '</div>';
						echo cd_specs_cap( 'vin', $cd_spec_caps );
					echo '<div id="sh_stock_number"><input type="checkbox"' . ( $show_hide['stock_number'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="stock_number" /><input type="text" id="label_stock_number" value="' . $field_labels['stock_number'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="stock_number" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Stock Number','car-demon' ) . '</div>';
						echo cd_specs_cap( 'stock_number', $cd_spec_caps );
					echo '<div id="sh_mileage"><input type="checkbox"' . ( $show_hide['mileage'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="mileage" /><input type="text" id="label_mileage" value="' . $field_labels['mileage'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="mileage" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Mileage','car-demon' ) . '</div>';
						echo cd_specs_cap( 'mileage', $cd_spec_caps );
					echo '<div id="sh_body_style"><input type="checkbox"' . ( $show_hide['body_style'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="body_style" /><input type="text" id="label_body_style" value="' . $field_labels['body_style'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="body_style" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Body Style','car-demon' ) . '</div>';
						echo cd_specs_cap( 'body_style', $cd_spec_caps );
					echo '<div id="sh_year"><input type="checkbox"' . ( $show_hide['year'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="year" /><input type="text" id="label_year" value="' . $field_labels['year'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="year" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Year','car-demon' ) . '</div>';
						echo cd_specs_cap( 'year', $cd_spec_caps );
					echo '<div id="sh_make"><input type="checkbox"' . ( $show_hide['make'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="make" /><input type="text" id="label_make" value="' . $field_labels['make'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="make" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Make','car-demon' ) . '</div>';
						echo cd_specs_cap( 'make', $cd_spec_caps );
					echo '<div id="sh_model"><input type="checkbox"' . ( $show_hide['model'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="model" /><input type="text" id="label_model" value="' . $field_labels['model'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="model" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Model','car-demon' ) . '</div>';
						echo cd_specs_cap( 'model', $cd_spec_caps );
					echo '<div id="sh_retail"><input type="checkbox"' . ( $show_hide['retail'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="retail" /><input type="text" id="label_retail" value="' . $field_labels['retail'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="retail" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Retail Price','car-demon' ) . '</div>';
						echo cd_specs_cap( 'retail', $cd_spec_caps );
					echo '<div id="sh_rebates"><input type="checkbox"' . ( $show_hide['rebates'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="rebates" /><input type="text" id="label_rebates" value="' . $field_labels['rebates'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="rebates" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Rebates','car-demon' ) . '</div>';
						echo cd_specs_cap( 'rebates', $cd_spec_caps );
					echo '<div id="sh_discount"><input type="checkbox"' . ( $show_hide['discount'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="discount" /><input type="text" id="label_discount" value="' . $field_labels['discount'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="discount" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Discount','car-demon' ) . '</div>';
						echo cd_specs_cap( 'discount', $cd_spec_caps );
					echo '<div id="sh_price"><input type="checkbox"' . ( $show_hide['price'] == true ? ' checked' : '' ) . ' onclick="show_hide_default_fields(this);" value="price" /><input type="text" id="label_price" value="' . $field_labels['price'] . '" onchange="update_default_labels(this);" /> <span class="cd_open_caps" data-type="price" title="' . __( 'Manage Capabilities', 'car-demon' ) . '">+</span> ' . __( 'Price','car-demon' ) . '</div>';
						echo cd_specs_cap( 'price', $cd_spec_caps );
					echo '<h5>' . __( 'These values should update as soon as you make changes.', 'car-demon' ) . '<h5>';
					echo '<h5>' . __( 'The vehicle search widget requires you to use a Stock # and may not behave properly if this field is hidden.', 'car-demon' ) . '<h5>';
				echo '</blockquote>';
			echo '</fieldset>';
			echo '<h4 class="add_vehicle_option_group" id="cd_add_specs"> + ' . __( 'Add Group', 'car-demon' ) . '</h4>';
			echo add_vehicle_option_group_form( 'specs' );
			if ( ! empty( $specs_options ) ) {
				echo '<blockquote>';
					foreach ( $specs_options as $group => $value ) {
						$group_slug = cd_clean_cap_slug( $group );
						echo '<div id="group_' . $group_slug . '">';
							echo '<input type="text" value="' . $group . '" class="vehicle_option_group" id="vehicle_option_group_' . $group . '" />';
							echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'specs\',\'' . $group . '\')">X - ' . __( 'Delete this group', 'car-demon' ) .'</div>';
							echo '<div class="clear"></div>';
							echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_' . $group . '">';
								echo $value;
							echo '</textarea>';
							echo '<div class="clear"></div>';
							echo '<input type="button" value="Update Group" class="btn_update_group" onclick="update_option_group(\'specs\',\'' . $group . '\')" />';
							echo ' <span class="cd_open_caps" data-type="' . $group_slug . '" title="' . __('Manage Capabilities', 'car-demon' ) . '"> + </span>';
							echo cd_specs_cap( $group_slug, $cd_spec_caps );
						echo '</div>';
					}
				echo '</blockquote>';
				echo '<div class="clear"></div>';
			}
		echo '</div>';
		if ( $show_tabs == 1 ) {
			echo '<h3 class="open_tab" id="cd_open_safety">+ ' . __( 'Safety Tab', 'car-demon' ) . '</h3>';
			echo '<div class="tab" id="safety_tab">';
				echo '<h5 class="close_tab" id="cd_close_safety">- ' . __( 'Close Tab', 'car-demon' ) . '</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_safety"> + ' . __( 'Add Group', 'car-demon' ) . '</h4>';
				echo add_vehicle_option_group_form( 'safety' );
				if ( ! empty( $safety_options ) ) {
					echo '<blockquote>';
						foreach ( $safety_options as $group => $value ) {
							$group_slug = cd_clean_cap_slug( $group );
							echo '<div id="group_' . $group . '">';
								echo '<input type="text" value="' . $group . '" class="vehicle_option_group" id="vehicle_option_group_' . $group . '" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'safety\',\'' . $group . '\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_' . $group . '">';
									echo $value;
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="button" value="Update Group" class="btn_update_group" onclick="update_option_group(\'safety\',\'' . $group . '\')" />';
								echo ' <span class="cd_open_caps" data-type="' . $group_slug . '" title="' . __('Manage Capabilities', 'car-demon' ) . '"> + </span>';
								echo cd_specs_cap( $group_slug, $cd_spec_caps );
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
				}
			echo '</div>';
	
			echo '<h3 class="open_tab" id="cd_open_convenience">+ ' . __( 'Convenience Tab', 'car-demon' ) . '</h3>';
			echo '<div class="tab" id="convenience_tab">';
				echo '<h5 class="close_tab" id="cd_close_convenience">- ' . __( 'Close Tab', 'car-demon' ) . '</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_convenience"> + ' . __( 'Add Group', 'car-demon' ) . '</h4>';
				echo add_vehicle_option_group_form( 'convenience' );
				if ( ! empty( $convenience_options ) ) {
					echo '<blockquote>';
						foreach ( $convenience_options as $group => $value ) {
							$group_slug = cd_clean_cap_slug( $group );
							echo '<div id="group_' . $group . '">';
								echo '<input type="text" value="' . $group . '" class="vehicle_option_group" id="vehicle_option_group_' . $group . '" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'convenience\',\'' . $group . '\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_' . $group . '">';
									echo $value;
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="button" value="Update Group" class="btn_update_group" onclick="update_option_group(\'convenience\',\'' . $group . '\')" />';
								echo ' <span class="cd_open_caps" data-type="' . $group_slug . '" title="' . __('Manage Capabilities', 'car-demon' ) . '"> + </span>';
								echo cd_specs_cap( $group_slug, $cd_spec_caps );
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
				}
			echo '</div>';
	
			echo '<h3 class="open_tab" id="cd_open_comfort">+ ' . __( 'Comfort Tab', 'car-demon' ) . '</h3>';
			echo '<div class="tab" id="comfort_tab">';
				echo '<h5 class="close_tab" id="cd_close_comfort">- ' . __( 'Close Tab', 'car-demon' ) . '</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_comfort"> + ' . __( 'Add Group', 'car-demon' ) . '</h4>';
				echo add_vehicle_option_group_form( 'comfort' );
				if ( ! empty( $comfort_options ) ) {
					echo '<blockquote>';
						foreach ( $comfort_options as $group => $value ) {
							$group_slug = cd_clean_cap_slug( $group );
							echo '<div id="group_' . $group . '">';
								echo '<input type="text" value="' . $group . '" class="vehicle_option_group" id="vehicle_option_group_' . $group . '" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'comfort\',\'' . $group . '\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_' . $group . '">';
									echo $value;
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="button" value="Update Group" class="btn_update_group" onclick="update_option_group(\'comfort\',\'' . $group . '\')" />';
								echo ' <span class="cd_open_caps" data-type="' . $group_slug . '" title="' . __('Manage Capabilities', 'car-demon' ) . '"> + </span>';
								echo cd_specs_cap( $group_slug, $cd_spec_caps );
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
				}
			echo '</div>';
	
			echo '<h3 class="open_tab" id="cd_open_entertainment">+ ' . __( 'Entertainment Tab', 'car-demon' ) . '</h3>';
			echo '<div class="tab" id="entertainment_tab">';
				echo '<h5 class="close_tab" id="cd_close_entertainment">- ' . __( 'Close Tab', 'car-demon' ) . '</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_entertainment"> + ' . __( 'Add Group', 'car-demon' ) . '</h4>';
				echo add_vehicle_option_group_form( 'entertainment' );
				if ( ! empty( $entertainment_options ) ) {
					echo '<blockquote>';
						foreach ( $entertainment_options as $group => $value ) {
							$group_slug = cd_clean_cap_slug( $group );
							echo '<div id="group_' . $group . '">';
								echo '<input type="text" value="' . $group . '" class="vehicle_option_group" id="vehicle_option_group_' . $group . '" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'entertainment\',\'' . $group . '\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_' . $group . '">';
									echo $value;
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="button" value="Update Group" class="btn_update_group" onclick="update_option_group(\'entertainment\',\'' . $group . '\')" />';
								echo ' <span class="cd_open_caps" data-type="' . $group_slug . '" title="' . __('Manage Capabilities', 'car-demon' ) . '"> + </span>';
								echo cd_specs_cap( $group_slug, $cd_spec_caps );
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
				}
			echo '</div>';
		}
		if ( $car_demon_options['use_about'] == 'Yes' ) {
			$about = 1;
			echo '<h3 class="open_tab" id="cd_open_about_us">+ ' . __( 'About Us Tab', 'car-demon' ) . '</h3>';
			echo '<div class="tab" id="about_us_tab">';
				echo '<h5 class="close_tab" id="cd_close_about_us">- ' . __( 'Close Tab', 'car-demon' ) . '</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_about_us"> + ' . __( 'Add Group', 'car-demon' ) . '</h4>';
				echo add_vehicle_option_group_form( 'about_us' );
				if ( ! empty( $about_us_options ) ) {
					echo '<blockquote>';
						foreach ( $about_us_options as $group => $value ) {
							$group_slug = cd_clean_cap_slug( $group );
							echo '<div id="group_' . stripslashes_deep( $group ) . '">';
								echo '<input type="text" value="' . stripslashes_deep( $group ) . '" class="vehicle_option_group" id="vehicle_option_group_' . stripslashes_deep( $group ) . '" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'about_us\',\'' . $group . '\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_' . stripslashes_deep( $group ) . '">';
									echo stripslashes_deep( $value );
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="button" value="Update Group" class="btn_update_group" onclick="update_option_group(\'about_us\',\'' . $group . '\')" />';
								echo ' <span class="cd_open_caps" data-type="' . $group_slug . '" title="' . __('Manage Capabilities', 'car-demon' ) . '"> + </span>';
								echo cd_specs_cap( $group_slug, $cd_spec_caps );
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
					_e('The About Us tab works slightly differently than the other tabs.', 'car-demon' );
					echo '<br />';
					_e('It displays each title and description area as a block of text with a title, it does not display items delimited by a comma.', 'car-demon' );
					echo '<div class="clear"></div>';
				}
			echo '</div>';
		}
		echo '<br />';
		
		_e( 'If you delete a group or option here then it will no longer show on the vehicles.', 'car-demon' );
		echo '<br />';
		_e( 'However, the information is not removed from the vehicles, it is retained but hidden.', 'car-demon' );
		echo '<br />';
		_e( 'To permanently delete the information from the vehicles you will need to remove it from each one, then remove the option here to prevent it from being readded to a vehicle.', 'car-demon' );
	echo '</div>';
}

function add_vehicle_option_group_form( $group ) {
	$x = '<div id="frm_add_' . $group . '" class="add_vehicle_option_group_form">';
		$x .= '<blockquote>';
			$x .= 'Group Title:<br /><input type="text" id="group_option_title_' . $group . '" class="vehicle_option_group" />';
			$x .= '<span id="cancel_' . $group . '" class="cancel_add_group">- Cancel Add</span>';
			$x .= '<div class="clear"></div>';
			$x .= 'Group Items:<br /><textarea class="vehicle_option_group_items" id="group_options_' . $group . '">';
			$x .= '</textarea>';
			$x .= '<div class="clear"></div>';
			if ( $group != 'about_us' ) {
				$x .= 'Put a comma between each item, do not use a space ie. item1,item2,item3';
			}
		$x .= '</blockquote>';
		$x .= '<div class="clear"></div>';
		$x .= '<input type="button" value="Add New Group" onclick="add_option_group(\'' . $group . '\');" />';
	$x .= '</div>';
	return $x;
}

function select_group_type( $slug, $value ) {
	$x = '
		<select id="select_type_' . $slug . '">
			<option value="' . $value . '">' . $value . '</option>
			<option value="Text"></option>
			<option value="Option"></option>
		</select>
	';
	return $x;	
}

function car_demon_remove_option_group() {
	$group = sanitize_text_field( $_POST['group'] );
	$group_title = sanitize_text_field( $_POST['group_title'] );
	$map = cd_get_vehicle_map();
	if ( isset( $map[$group][$group_title] ) ) {
		unset( $map[$group][$group_title] );
		update_option( 'cd_vehicle_option_map', $map );
	}
}

function car_demon_add_option_group() {
	$group = sanitize_text_field( $_POST['group'] );
	$group_options = sanitize_text_field( $_POST['group_options'] );
	$group_title = sanitize_text_field( $_POST['title'] );
	$map = cd_get_vehicle_map();
	$map[$group][$group_title] = $group_options;
	update_option( 'cd_vehicle_option_map', $map );
	exit();
}

function car_demon_update_option_group() {
	$group = sanitize_text_field( $_POST['group'] );
	$group_options = sanitize_text_field( $_POST['group_options'] );
	$group_title = sanitize_text_field( $_POST['group_title'] );
	$map = cd_get_vehicle_map();
	$map[$group][$group_title] = stripslashes_deep( $group_options );
	update_option( 'cd_vehicle_option_map', $map );
	exit();
}

function cd_sidebar_selectbox( $name = '', $current_value = false ) {
    global $wp_registered_sidebars;
	$sidebar_list = '';
    if ( empty( $wp_registered_sidebars ) )
        return;
	$sidebar_list .= '<select name="' . $name . '" id="' . $name . '">';
		if ( empty( $current_value ) ) {
			$sidebar_list .= '<option value="" selected>None</option>';
		} else {
			$sidebar_list .= '<option value="">None</option>';
		}
		foreach ( $wp_registered_sidebars as $sidebar ) :
			if ( $sidebar['name'] == $current_value ) {
				$sidebar_list .= '<option value="' . $sidebar['name'] . '" selected>' . $sidebar['name'] . '</option>';
			} else {
				$sidebar_list .= '<option value="' . $sidebar['name'] . '">' . $sidebar['name'] . '</option>';
			}
		endforeach; 
	$sidebar_list .= '</select>';	
	return $sidebar_list;
}

function get_default_field_labels() {
	$labels = array();
	$labels['vin'] = __( 'Vin #', 'car-demon' );
	$labels['stock_number'] = __( 'Stock #', 'car-demon' );
	$labels['mileage'] = __( 'Mileage', 'car-demon' );
	$labels['body_style'] = __( 'Body Style', 'car-demon' );
	$labels['year'] = __( 'Year', 'car-demon' );
	$labels['make'] = __( 'Make', 'car-demon' );
	$labels['model'] = __( 'Model', 'car-demon' );
	$labels['retail'] = __( 'Retail Price', 'car-demon' );
	$labels['rebates'] = __( 'Rebates', 'car-demon' );
	$labels['discount'] = __( 'Discount', 'car-demon' );
	$labels['price'] = __( 'Price', 'car-demon' );
	//= These fields are not changable at this point
	$labels['condition'] = __( 'Condition', 'car-demon' );
	$labels['transmission'] = __( 'Transmission', 'car-demon' );
	$labels['exterior_color'] = __( 'Exterior', 'car-demon' );
	$labels['interior_color'] = __( 'Interior', 'car-demon' );
	$labels['engine'] = __( 'Engine', 'car-demon' );
	$label_options = get_option( 'cd_default_field_labels', $labels );
	if ( ! isset( $label_options['vin'] ) ) $label_options['vin'] = $labels['vin'];
	if ( ! isset( $label_options['stock_number'] ) ) $label_options['stock_number'] = $labels['stock_number'];
	if ( ! isset( $label_options['mileage'] ) ) $label_options['mileage'] = $labels['mileage'];
	if ( ! isset( $label_options['body_style'] ) ) $label_options['body_style'] = $labels['body_style'];
	if ( ! isset( $label_options['year'] ) ) $label_options['year'] = $labels['year'];
	if ( ! isset( $label_options['make'] ) ) $label_options['make'] = $labels['make'];
	if ( ! isset( $label_options['model'] ) ) $label_options['model'] = $labels['model'];
	if ( ! isset( $label_options['retail'] ) ) $label_options['retail'] = $labels['retail'];
	if ( ! isset( $label_options['rebates'] ) ) $label_options['rebates'] = $labels['rebates'];
	if ( ! isset( $label_options['discount'] ) ) $label_options['discount'] = $labels['discount'];
	if ( ! isset( $label_options['price'] ) ) $label_options['price'] = $labels['price'];
	if ( ! isset( $label_options['condition'] ) ) $label_options['condition'] = $labels['condition'];
	if ( ! isset( $label_options['transmission'] ) ) $label_options['transmission'] = $labels['transmission'];
	if ( ! isset( $label_options['exterior_color'] ) ) $label_options['exterior_color'] = $labels['exterior_color'];
	if ( ! isset( $label_options['interior_color'] ) ) $label_options['interior_color'] = $labels['interior_color'];
	if ( ! isset( $label_options['engine'] ) ) $label_options['engine'] = $labels['engine'];
	return $label_options;	
}

function get_show_hide_fields() {
	$fields = array();
	$fields['vin'] = 0;
	$fields['stock_number'] = 0;
	$fields['mileage'] = 0;
	$fields['body_style'] = 0;
	$fields['year'] = 0;
	$fields['make'] = 0;
	$fields['model'] = 0;
	$fields['retail'] = 0;
	$fields['rebates'] = 0;
	$fields['discount'] = 0;
	$fields['price'] = 0;
	$fields['condition'] = 0;
	$fields['transmission'] = 0;
	$fields['exterior_color'] = 0;
	$fields['interior_color'] = 0;
	$fields['engine'] = 0;
	$fields['location'] = 0;
	$field_options = get_option( 'cd_show_hide_labels', $fields );
	if ( ! isset( $field_options['vin'] ) ) $field_options['vin'] = $fields['vin'];
	if ( ! isset( $field_options['stock_number'] ) ) $field_options['stock_number'] = $fields['stock_number'];
	if ( ! isset( $field_options['mileage'] ) ) $field_options['mileage'] = $fields['mileage'];
	if ( ! isset( $field_options['body_style'] ) ) $field_options['body_style'] = $fields['body_style'];
	if ( ! isset( $field_options['year'] ) ) $field_options['year'] = $fields['year'];
	if ( ! isset( $field_options['make'] ) ) $field_options['make'] = $fields['make'];
	if ( ! isset( $field_options['model'] ) ) $field_options['model'] = $fields['model'];
	if ( ! isset( $field_options['retail'] ) ) $field_options['retail'] = $fields['retail'];
	if ( ! isset( $field_options['rebates'] ) ) $field_options['rebates'] = $fields['rebates'];
	if ( ! isset( $field_options['discount'] ) ) $field_options['discount'] = $fields['discount'];
	if ( ! isset( $field_options['price'] ) ) $field_options['price'] = $fields['price'];
	if ( ! isset( $field_options['condition'] ) ) $field_options['condition'] = $fields['condition'];
	if ( ! isset( $field_options['transmission'] ) ) $field_options['transmission'] = $fields['transmission'];
	if ( ! isset( $field_options['exterior_color'] ) ) $field_options['exterior_color'] = $fields['exterior_color'];
	if ( ! isset( $field_options['interior_color'] ) ) $field_options['interior_color'] = $fields['interior_color'];
	if ( ! isset( $field_options['engine'] ) ) $field_options['engine'] = $fields['engine'];
	if ( ! isset( $field_options['location'] ) ) $field_options['location'] = $fields['location'];
	return $field_options;	
}

function car_demon_update_default_labels() {
	$field = $_POST['field'];
	$field = str_replace( 'label_', '', $field );
	$label = $_POST['label'];
	$labels = get_default_field_labels();
	$labels[$field] = $label;
	update_option( 'cd_default_field_labels', $labels );
	exit();
}

function car_demon_update_default_fields() {
	$field = $_POST['field'];
	$checked = $_POST['checked'];
	if ( $checked == 'false' ) $checked = '';
	$fields = get_show_hide_fields();
	$fields[$field] = $checked;
	update_option( 'cd_show_hide_labels', $fields );
	exit();
}

function cd_get_default_options() {
	$options_array = cd_get_vehicle_map();
	$options = '';
	if ( isset( $options_array['safety']['Equipment - Anti-Theft & Locks'] ) ) {
		$options .= $options_array['safety']['Equipment - Anti-Theft & Locks'];
	}
	if ( isset( $options_array['safety']['Equipment - Braking & Traction'] ) ) {
		$options .= ',' . $options_array['safety']['Equipment - Braking & Traction'];
	}
	if ( isset( $options_array['safety']['Equipment - Safety'] ) ) {
		$options .= ',' . $options_array['safety']['Equipment - Safety'];
	}
	if ( isset( $options_array['convenience']['Equipment - Remote Controls & Release'] ) ) {
		$options .= ',' . $options_array['convenience']['Equipment - Remote Controls & Release'];
	}
	if (isset( $options_array['convenience']['Equipment - Interior Features'] )) {
		$options .= ',' . $options_array['convenience']['Equipment - Interior Features'];
	}
	if ( isset( $options_array['convenience']['Equipment - Storage'] ) ) {
		$options .= ',' . $options_array['convenience']['Equipment - Storage'];
	}
	if ( isset( $options_array['convenience']['Equipment - Roof'] ) ) {
		$options .= ',' . $options_array['convenience']['Equipment - Roof'];
	}
	if ( isset ( $options_array['convenience']['Equipment - Climate Control'] ) ) {
		$options .= ',' . $options_array['convenience']['Equipment - Climate Control'];
	}
	// if ( isset( $options_array['comfort']['Equipment - Seat'] ) ) {
	// 	$options .= ',' . $options_array['comfort']['Equipment - Seat'];
	// }
	// if ( isset( $options_array['comfort']['Equipment - Exterior Lighting'] ) ) {
	// 	$options .= ',' . $options_array['comfort']['Equipment - Exterior Lighting'];
	// }
	// if ( isset( $options_array['comfort']['Equipment - Exterior Features'] ) ) {
	// 	$options .= ',' . $options_array['comfort']['Equipment - Exterior Features'];
	// }
	// if ( isset( $options_array['comfort']['Equipment - Wheels'] ) ) {
	// 	$options .= ',' . $options_array['comfort']['Equipment - Wheels'];
	// }
	// if ( isset( $options_array['comfort']['Equipment - Tires'] ) ) {
	// 	$options .= ',' . $options_array['comfort']['Equipment - Tires'];
	// }
	// if ( isset( $options_array['comfort']['Equipment - Windows'] ) ) {
	// 	$options .= ',' . $options_array['comfort']['Equipment - Windows'];
	// }
	// if ( isset( $options_array['comfort']['Equipment - Mirrors'] ) ) {
	// 	$options .= ',' . $options_array['comfort']['Equipment - Mirrors'];
	// }
	// if ( isset( $options_array['comfort']['Equipment - Wipers'] ) ) {
	// 	$options .= ',' . $options_array['comfort']['Equipment - Wipers'];
	// }
	// if ( isset( $options_array['comfort']['Equipment - Towings'] ) ) {
	// 	$options .= ',' . $options_array['comfort']['Equipment - Towings'];
	// }
	if ( isset( $options_array['entertainment']['Equipment - Entertainment, Communication & Navigation'] ) ) {
		$options .= ',' . $options_array['entertainment']['Equipment - Entertainment, Communication & Navigation'];
	}
	return $options;
}

function cd_specs_cap( $item, $cap_settings ) {
	$x = '';
	if ( isset( $cap_settings[$item] ) ) {
		if ( ! empty( $cap_settings[$item] ) ) {
			$x = '<div class="cd_spec_cap_box ' . $item . '">';
				$x .= '<span class="cd_tooltip">' . __( '?', 'car-demon' ) . '<span class="cd_tip">' . __( 'Capability required to change value on vehicle edit page.', 'car-demon' ) . '</span></span><input type="text" name="cd_spec_cap_' . $item . '" class="cd_spec_cap" value="' . $cap_settings[$item] . '" />';
			$x .= '</div>';
		}
	}
	
	return $x;
}

function get_cd_spec_caps() {
	$default_cap_settings = get_cd_spec_caps_defaults();
	$cap_settings = get_option( 'cd_spec_caps', $default_cap_settings );

	foreach( $default_cap_settings as $key=>$value ) {
		if ( ! isset( $cap_settings[$key] ) ) {
			$cap_settings[$key] = $value;
		} else {
			if ( empty( $cap_settings[$key] ) ) {
				$cap_settings[$key] = $value;
			}
		}
	}

	$cap_settings = apply_filters( 'cd_cap_settings_filter', $cap_settings );

	return $cap_settings;
}

function get_cd_spec_caps_defaults() {
	$cap_settings = array();
	$cap_settings['condition'] = 'edit_posts';
	$cap_settings['vin'] = 'edit_posts';
	$cap_settings['stock_number'] = 'edit_posts';
	$cap_settings['mileage'] = 'edit_posts';
	$cap_settings['body_style'] = 'edit_posts';
	$cap_settings['year'] = 'edit_posts';
	$cap_settings['make'] = 'edit_posts';
	$cap_settings['model'] = 'edit_posts';
	$cap_settings['retail'] = 'edit_posts';
	$cap_settings['rebates'] = 'edit_posts';
	$cap_settings['discount'] = 'edit_posts';
	$cap_settings['price'] = 'edit_posts';
	
	$options = cd_get_vehicle_map();

	foreach( $options as $specs_options ) {
		foreach ( $specs_options as $group => $value ) {
			$group_slug = cd_clean_cap_slug( $group );
			$cap_settings[ $group_slug ] = 'edit_posts';
		}
	}
	
	$cap_settings = apply_filters( 'cd_cap_default_settings_filter', $cap_settings );
	
	return $cap_settings;
}

function cd_save_spec_caps() {
	$caps = get_cd_spec_caps();
	$specs = array( 'condition', 'vin', 'stock_number', 'mileage', 'body_style', 'year', 'make', 'model', 'retail', 'rebates', 'discount', 'price' );

	$options = cd_get_vehicle_map();

	foreach( $options as $specs_options ) {
		foreach ( $specs_options as $group => $value ) {
			$group_slug = cd_clean_cap_slug( $group );
			$specs[] = $group_slug;
		}
	}
	
	foreach ( $specs as $spec ) {
		if ( isset( $_POST['cd_spec_cap_' . $spec] ) ) {
			$caps[$spec] = sanitize_text_field( $_POST['cd_spec_cap_' . $spec] );
		}
	}
	
	update_option( 'cd_spec_caps', $caps );
}

function cd_clean_cap_slug( $slug ) {
	$slug = str_replace( ' ', '_', $slug );
	$slug = strtolower( $slug );
	$slug = preg_replace( "/[^A-Za-z ]/", "_", $slug );

	return $slug;	
}
?>
