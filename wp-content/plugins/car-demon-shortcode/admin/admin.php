<?php
if (is_admin()) {
	require_once('hooks.php');
	if (isset($_GET['page']) && $_GET['page'] == 'cdsp_template_options') {
		add_action('admin_print_scripts', 'cdsp_templates_admin_scripts');
		add_action('admin_print_styles', 'cdsp_template_admin_styles');
	}
}
function cdsp_templates_admin_scripts() {
	$plugin_path = trailingslashit( plugins_url() ) . trailingslashit( 'car-demon-shortcode' );

	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('cd-upload', plugins_url().'/car-demon-shortcode/js/uploader.js', array('jquery','media-upload','thickbox'));
	wp_register_script("cd-pro-admin-js", plugins_url().'/car-demon-shortcode/js/cd-pro-admin.js', array('jquery'), false, true );
	wp_localize_script( 'cd-pro-admin-js', 'cdProCommonParams', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'theme_url' => get_template_directory_uri(),
		'plugin_path' => $plugin_path
	));

	wp_register_script('cd-color', plugins_url().'/car-demon-shortcode/js/jscolor.js');
	wp_localize_script( 'cd-color', 'cdColorCommonParams', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'plugin_path' => $plugin_path
	));

	wp_enqueue_script('cd-color');
	wp_enqueue_style('cd-cds-admin', plugins_url().'/car-demon-shortcode/css/cds-admin.css');
	wp_enqueue_script('cd-upload');
	wp_enqueue_script('cd-pro-admin-js');
}
function cdsp_template_admin_styles() {
	wp_enqueue_style('thickbox');
}
function cdsp_template_settings_page() {
	add_submenu_page( 'edit.php?post_type=cars_for_sale', 'Shortcode Settings', 'Shortcode Settings', 'manage_options', 'cdsp_template_options', 'cdsp_template_settings_options_do_page' );
}
add_action('admin_menu', 'cdsp_template_settings_page');
function cdsp_template_options() {
	$cdsp_template_path = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$cdsp_template_path = str_replace('admin/','',$cdsp_template_path);
	$default = array();
	$default['favico_img'] = '';
	$default['favico_ico'] = '';
	$default['custom_header'] = '';
	$default['custom_header_bar'] = '';
	$default['custom_header_type'] = 'Image';
	$default['chat_code'] = '';
	$default['mobile_chat_code'] = '';
	$default['mobile_theme'] = 'No';
	$default['mobile_logo'] = '';
	$default['mobile_header'] = 'Yes';
	$default['theme_color_background'] = 'E0E0E0';
	$default['theme_color'] = '3254C2';
	$default['theme_color_highlight'] = 'FFFFFF';
	$default['theme_color_shadow'] = '777777';
	$default['theme_color_button'] = '0000aa';
	$default['theme_color_button_hover'] = '0000bb';
	$default['theme_color_button_shadow'] = '999999';
	$default['theme_border_top_color'] = '0000bb';
	$default['theme_border_bottom_color'] = 'D6D6D6';
	$default['theme_border_left_color'] = 'D6D6D6';
	$default['theme_border_right_color'] = 'D6D6D6';
	$default['theme_border_top_width'] = '4';
	$default['theme_border_bottom_width'] = '1';
	$default['theme_border_left_width'] = '1';
	$default['theme_border_right_width'] = '1';
	$default['warranty_button'] = $cdsp_template_path.'/images/btn_100k_warranty.png';
	$default['finance_button'] = $cdsp_template_path.'/images/btn_0_apr.png';
	$default['trade_button'] = $cdsp_template_path.'/images/btn_black_book.png';
	$default['cds_cdp_style'] = 1;
	$cdsp_template_options = array();
	$cdsp_template_options = get_option( 'cdsp_template_options', $default );
	if (empty($cdsp_template_options['favico_img'])) {$cdsp_template_options['favico_img'] = $default['favico_img'];}
	if (empty($cdsp_template_options['favico_ico'])) {$cdsp_template_options['favico_ico'] = $default['favico_ico'];}
	if (empty($cdsp_template_options['custom_header'])) {$cdsp_template_options['custom_header'] = $default['custom_header'];}
	if (empty($cdsp_template_options['custom_header_bar'])) {$cdsp_template_options['custom_header_bar'] = $default['custom_header_bar'];}
	if (empty($cdsp_template_options['custom_header_type'])) {$cdsp_template_options['custom_header_type'] = $default['custom_header_type'];}
	if (empty($cdsp_template_options['chat_code'])) {$cdsp_template_options['chat_code'] = $default['chat_code'];}
	if (empty($cdsp_template_options['mobile_chat_code'])) {$cdsp_template_options['mobile_chat_code'] = $default['mobile_chat_code'];}
	if (empty($cdsp_template_options['mobile_theme'])) {$cdsp_template_options['mobile_theme'] = $default['mobile_theme'];}
	if (empty($cdsp_template_options['mobile_logo'])) {$cdsp_template_options['mobile_logo'] = $default['mobile_logo'];}
	if (empty($cdsp_template_options['mobile_header'])) {$cdsp_template_options['mobile_header'] = $default['mobile_header'];}
	if (empty($cdsp_template_options['theme_color_background'])) {$cdsp_template_options['theme_color_background'] = $default['theme_color_background'];}
	if (empty($cdsp_template_options['theme_color'])) {$cdsp_template_options['theme_color'] = $default['theme_color'];}
	if (empty($cdsp_template_options['theme_color_highlight'])) {$cdsp_template_options['theme_color_highlight'] = $default['theme_color_highlight'];}
	if (empty($cdsp_template_options['theme_color_shadow'])) {$cdsp_template_options['theme_color_shadow'] = $default['theme_color_shadow'];}
	if (empty($cdsp_template_options['theme_color_button'])) {$cdsp_template_options['theme_color_button'] = $default['theme_color_button'];}
	if (empty($cdsp_template_options['theme_color_button_hover'])) {$cdsp_template_options['theme_color_button_hover'] = $default['theme_color_button_hover'];}
	if (empty($cdsp_template_options['theme_color_button_shadow'])) {$cdsp_template_options['theme_color_button_shadow'] = $default['theme_color_button_shadow'];}	
	if (empty($cdsp_template_options['theme_border_top_color'])) {$cdsp_template_options['theme_border_top_color'] = $default['theme_border_top_color'];}	
	if (empty($cdsp_template_options['theme_border_bottom_color'])) {$cdsp_template_options['theme_border_bottom_color'] = $default['theme_border_bottom_color'];}	
	if (empty($cdsp_template_options['theme_border_left_color'])) {$cdsp_template_options['theme_border_left_color'] = $default['theme_border_left_color'];}	
	if (empty($cdsp_template_options['theme_border_right_color'])) {$cdsp_template_options['theme_border_right_color'] = $default['theme_border_right_color'];}	
	
	if (empty($cdsp_template_options['theme_border_top_width'])) {$cdsp_template_options['theme_border_top_width'] = $default['theme_border_top_width'];}
	if (empty($cdsp_template_options['theme_border_bottom_width'])) {$cdsp_template_options['theme_border_bottom_width'] = $default['theme_border_bottom_width'];}
	if (empty($cdsp_template_options['theme_border_left_width'])) {$cdsp_template_options['theme_border_left_width'] = $default['theme_border_left_width'];}
	if (empty($cdsp_template_options['theme_border_right_width'])) {$cdsp_template_options['theme_border_right_width'] = $default['theme_border_right_width'];}
	if (empty($cdsp_template_options['warranty_button'])) {$cdsp_template_options['warranty_button'] = $default['warranty_button'];}	
	if (empty($cdsp_template_options['finance_button'])) {$cdsp_template_options['finance_button'] = $default['finance_button'];}	
	if (empty($cdsp_template_options['trade_button'])) {$cdsp_template_options['trade_button'] = $default['trade_button'];}
	return $cdsp_template_options;
}
function cdsp_template_settings_options_do_page() {
	echo '<h1>'.__('Car Demon Shortcode Settings','car-demon-shortcode').'</h1>';
	if (isset($_POST['reset_cdsp_template'])) {
		reset_cdsp_template();
	}
	else {
		if(isset($_POST['update_cdsp_template'])) {
			if($_POST['update_cdsp_template'] == 1) {
				update_cdsp_template_settings();
			}
		}
	}
	$cdsp_template_options = cdsp_template_options();
	echo '<form action="" method="post">';
		echo '<input type="hidden" name="update_cdsp_template" value="1" />';
		echo '<hr />';
		echo '<h3>'.__('Vehicle Colors','car-demon-shortcode').'</h3>';
		echo '<table class="cdsp_admin_group"><tr valign="top">
			<td>
			<h4>'.__('Select colors for your vehicles.','car-demon-shortcode').'</h4>
			<label for="upload_image">
			Base<br /><input class="color" name="theme_color" id="theme_color" type="text" value="'.$cdsp_template_options['theme_color'].'" />
			<br />Background<br /><input class="color" type="text" name="theme_color_background" id="theme_color_background" value="'.$cdsp_template_options['theme_color_background'].'" />
			<br />Button<br /><input class="color" type="text" name="theme_color_button" id="theme_color_button" value="'.$cdsp_template_options['theme_color_button'].'" />
			<br />Button Hover<br /><input class="color" type="text" name="theme_color_button_hover" id="theme_color_button_hover" value="'.$cdsp_template_options['theme_color_button_hover'].'" />
			<br />Top Border Color<br /><input class="color" type="text" name="theme_border_top_color" id="theme_border_top_color" value="'.$cdsp_template_options['theme_border_top_color'].'" />
			<br />Bottom Border Color<br /><input class="color" type="text" name="theme_border_bottom_color" id="theme_border_bottom_color" value="'.$cdsp_template_options['theme_border_bottom_color'].'" />
			<br />Left Border Color<br /><input class="color" type="text" name="theme_border_left_color" id="theme_border_left_color" value="'.$cdsp_template_options['theme_border_left_color'].'" />
			<br />Right Border Color<br /><input class="color" type="text" name="theme_border_right_color" id="theme_border_right_color" value="'.$cdsp_template_options['theme_border_right_color'].'" />
			</label></td>
			</tr></table>';
/*
		<br />Highlight<br /><input class="color" type="text" name="theme_color_highlight" id="theme_color_highlight" value="'.$cdsp_template_options['theme_color_highlight'].'" />
		<br />Shadow<br /><input class="color" type="text" name="theme_color_shadow" id="theme_color_shadow" value="'.$cdsp_template_options['theme_color_shadow'].'" />
		<br />Button Shadow<br /><input class="color" type="text" name="theme_color_button_shadow" id="theme_color_button_shadow" value="'.$cdsp_template_options['theme_color_button_shadow'].'" />
		echo '<br />'.__('Custom Header Bar','car-demon-shortcode').'<br />';
		echo '<textarea name="custom_header_bar" rows="5" cols="60">'.$cdsp_template_options['custom_header_bar'].'</textarea><br />';
		echo '<br />'.__('Add Custom Favicon .gif or .png','car-demon-shortcode').'<br />';
		echo '<table><tr valign="top">
			<td><label for="upload_image">
			<input name="favico_img" id="favico_img" type="text" size="36" value="'.$cdsp_template_options['favico_img'].'" />
			<input id="upload_image_button" type="button" value="'.__('Upload Icon','car-demon-shortcode').'" />
			<br /><img id="favico_img_ico" src="'.$cdsp_template_options['favico_img'].'" />
			<br />'.__('Enter a URL or upload an image for the icon.','car-demon-shortcode').'
			</label></td>
			</tr></table>';
		echo '<br />'.__('Add Custom Favicon .ico','car-demon-shortcode').'<br />';
		echo '<table><tr valign="top">
			<td><label for="upload_image">
			<input name="favico_ico" id="favico_ico" type="text" size="36" value="'.$cdsp_template_options['favico_ico'].'" />
			<input id="upload_ico_button" type="button" value="'.__('Upload Icon','car-demon-shortcode').'" />
			<br /><img id="favico_ico_ico" src="'.$cdsp_template_options['favico_ico'].'" />
			<br />'.__('Enter a URL or upload an image for the icon.','car-demon-shortcode').'
			</label></td>
			</tr></table>';
*/
		echo '<br />'.__('Warranty Button','car-demon-shortcode').'<br />';
		echo '<table><tr valign="top">
			<td><label for="warranty_button">
			<input name="warranty_button" id="warranty_button" type="text" size="36" value="'.$cdsp_template_options['warranty_button'].'" />
			<input id="upload_warranty_button" type="button" value="'.__('Upload Image','car-demon-shortcode').'" />
			<br />'.__('Enter a URL or upload an image for the icon.','car-demon-shortcode').'
			<br /><img id="warranty_button_ico" src="'.$cdsp_template_options['warranty_button'].'" />
			<hr />
			</label></td>
			</tr></table>';
		echo '<br />'.__('Finance Button','car-demon-shortcode').'<br />';
		echo '<table><tr valign="top">
			<td><label for="finance_button">
			<input name="finance_button" id="finance_button" type="text" size="36" value="'.$cdsp_template_options['finance_button'].'" />
			<input id="upload_finance_button" type="button" value="'.__('Upload Image','car-demon-shortcode').'" />
			<br />'.__('Enter a URL or upload an image for the icon.','car-demon-shortcode').'
			<br /><img id="finance_button_ico" src="'.$cdsp_template_options['finance_button'].'" />
			<hr />
			</label></td>
			</tr></table>';
		echo '<br />'.__('Trade Button','car-demon-shortcode').'<br />';
		echo '<table><tr valign="top">
			<td><label for="trade_button">
			<input name="trade_button" id="trade_button" type="text" size="36" value="'.$cdsp_template_options['trade_button'].'" />
			<input id="upload_trade_button" type="button" value="'.__('Upload Image','car-demon-shortcode').'" />
			<br />'.__('Enter a URL or upload an image for the icon.','car-demon-shortcode').'
			<br /><img id="trade_button_ico" src="'.$cdsp_template_options['trade_button'].'" />
			<hr />
			</label></td>
			</tr></table>';
		echo '<div class="cdpro_admin_clear"></div>';
		echo '<h3>'.__('Border widths','car-demon-shortcode').'</h3>';
		echo '<table class="cdsp_admin_group"><tr valign="top">
			<td><label for="theme_border_top_width">
			<h4>'.__('Enter the width for each border area.','car-demon-shortcode').'</h4>
			<div class="cdsp_border_size">
				<p>Top</p>
				<input name="theme_border_top_width" id="theme_border_top_width" type="text" size="3" value="'.$cdsp_template_options['theme_border_top_width'].'" />
			</div>
			<div class="cdsp_border_size">
				<p>Bottom</p>
				<input name="theme_border_bottom_width" id="theme_border_bottom_width" type="text" size="3" value="'.$cdsp_template_options['theme_border_bottom_width'].'" />
			</div>
			<div class="cdsp_border_size">
				<p>Left</p>
				<input name="theme_border_left_width" id="theme_border_left_width" type="text" size="3" value="'.$cdsp_template_options['theme_border_left_width'].'" />
			</div>
			<div class="cdsp_border_size">
				<p>Right</p>
				<input name="theme_border_right_width" id="theme_border_right_width" type="text" size="3" value="'.$cdsp_template_options['theme_border_right_width'].'" />
			</div>
			<hr />
			</label></td>
			</tr></table>';		
		if ( defined( 'CDS_EXTRA_STYLES' ) ) {
			echo '<br />'.__('Single Vehicle Style','car-demon-shortcode').'<br />';
			echo '<table><tr valign="top">
				<td><label for="cds_cdp_style">
					<select id="cds_cdp_style" name="cds_cdp_style">
						<option value="1"' . ( $cdsp_template_options['cds_cdp_style'] === '1' ? ' selected' : '' ) . '>One</option>
						<option value="2"' . ( $cdsp_template_options['cds_cdp_style'] === '2' ? ' selected' : '' ) . '>Two</option>
						<option value="3"' . ( $cdsp_template_options['cds_cdp_style'] === '3' ? ' selected' : '' ) . '>Three</option>
					</select>
				<br />
				<br />
				<img id="cds_cdp_style_img" src="' . trailingslashit( plugins_url() ) . 'car-demon-shortcode/images/style_' . $cdsp_template_options['cds_cdp_style'] . '.jpg" />
				<hr />
				</label></td>
				</tr></table>';
		}
		echo '<div class="cdpro_admin_clear"></div>';
		echo '<input type="submit" value="Update Car Demon" />';
		echo '<input type="submit" name="reset_cdsp_template" value="Reset to Default" />';
	echo '</form>';
}
function update_cdsp_template_settings() {
	$new = array();
	$custom_header_bar = '';
	if (isset($_POST['favico_img'])) {
		$new['favico_img'] = sanitize_text_field($_POST['favico_img']);
	}
	if (isset($_POST['favico_ico'])) {
		$new['favico_ico'] = sanitize_text_field($_POST['favico_ico']);
	}
	if (isset($_POST['custom_header'])) {
		$new['custom_header'] = sanitize_text_field($_POST['custom_header']);
	}
	if (isset($_POST['custom_header_bar'])) {
		$custom_header_bar = sanitize_text_field($_POST['custom_header_bar']);
	}
	$custom_header_bar = str_replace("\'", "'", $custom_header_bar);
	$custom_header_bar = str_replace('\"', '"', $custom_header_bar);
	$custom_header_bar = str_replace('\\', '', $custom_header_bar);
	$new['custom_header_bar'] = $custom_header_bar;
	if (isset($_POST['chat_code'])) {
		$chat_code = sanitize_text_field($_POST['chat_code']);
		$chat_code = str_replace("\'", "'", $chat_code);
		$chat_code = str_replace('\"', '"', $chat_code);
		$chat_code = str_replace('\\', '', $chat_code);
	} else {
		$chat_code = '';
	}
	$new['chat_code'] = $chat_code;
	if (isset($_POST['mobile_chat_code'])) {
		$mobile_chat_code = sanitize_text_field($_POST['mobile_chat_code']);
		$mobile_chat_code = str_replace("\'", "'", $mobile_chat_code);
		$mobile_chat_code = str_replace('\"', '"', $mobile_chat_code);
		$mobile_chat_code = str_replace('\\', '', $mobile_chat_code);	
		$new['mobile_chat_code'] = $mobile_chat_code;
	}
	if (isset($_POST['custom_header_type'])) {
		$new['custom_header_type'] = sanitize_text_field($_POST['custom_header_type']);
	}
	if (isset($_POST['mobile_theme'])) {
		$new['mobile_theme'] = sanitize_text_field($_POST['mobile_theme']);
	}
	if (isset($_POST['mobile_logo'])) {
		$new['mobile_logo'] = sanitize_text_field($_POST['mobile_logo']);
	}
	if (isset($_POST['mobile_header'])) {
		$new['mobile_header'] = sanitize_text_field($_POST['mobile_header']);
	}
	if (isset($_POST['theme_color'])) {
		$new['theme_color'] = str_replace("#", "", sanitize_text_field($_POST['theme_color']));
	}
	if (isset($_POST['theme_color_highlight'])) {
		$new['theme_color_highlight'] = str_replace("#", "", sanitize_text_field($_POST['theme_color_highlight']));
	}
	if (isset($_POST['theme_color_shadow'])) {
		$new['theme_color_shadow'] = str_replace("#", "", sanitize_text_field($_POST['theme_color_shadow']));
	}
	if (isset($_POST['theme_color_button'])) {
		$new['theme_color_button'] = str_replace("#", "", sanitize_text_field($_POST['theme_color_button']));
	}
	if (isset($_POST['theme_color_button_hover'])) {
		$new['theme_color_button_hover'] = str_replace("#", "", sanitize_text_field($_POST['theme_color_button_hover']));
	}
	if (isset($_POST['theme_color_button_shadow'])) {
		$new['theme_color_button_shadow'] = str_replace("#", "", sanitize_text_field($_POST['theme_color_button_shadow']));
	}
	if (isset($_POST['theme_color_background'])) {
		$new['theme_color_background'] = str_replace("#", "", sanitize_text_field($_POST['theme_color_background']));
	}
	if (isset($_POST['theme_border_top_color'])) {
		$new['theme_border_top_color'] = str_replace("#", "", sanitize_text_field($_POST['theme_border_top_color']));
	}
	if (isset($_POST['theme_border_bottom_color'])) {
		$new['theme_border_bottom_color'] = str_replace("#", "", sanitize_text_field($_POST['theme_border_bottom_color']));
	}
	if (isset($_POST['theme_border_left_color'])) {
		$new['theme_border_left_color'] = str_replace("#", "", sanitize_text_field($_POST['theme_border_left_color']));
	}
	if (isset($_POST['theme_border_right_color'])) {
		$new['theme_border_right_color'] = str_replace("#", "", sanitize_text_field($_POST['theme_border_right_color']));
	}
	if (isset($_POST['theme_border_top_width'])) {
		$new['theme_border_top_width'] = sanitize_text_field($_POST['theme_border_top_width']);
	}
	if (isset($_POST['theme_border_bottom_width'])) {
		$new['theme_border_bottom_width'] = sanitize_text_field($_POST['theme_border_bottom_width']);
	}
	if (isset($_POST['theme_border_left_width'])) {
		$new['theme_border_left_width'] = sanitize_text_field($_POST['theme_border_left_width']);
	}
	if (isset($_POST['theme_border_right_width'])) {
		$new['theme_border_right_width'] = sanitize_text_field($_POST['theme_border_right_width']);
	}
	if (isset($_POST['warranty_button'])) {
		$new['warranty_button'] = str_replace("#", "", sanitize_text_field($_POST['warranty_button']));
	}
	if (isset($_POST['finance_button'])) {
		$new['finance_button'] = str_replace("#", "", sanitize_text_field($_POST['finance_button']));
	}
	if (isset($_POST['trade_button'])) {
		$new['trade_button'] = str_replace("#", "", sanitize_text_field($_POST['trade_button']));
	}
	if (isset($_POST['cds_cdp_style'])) {
		$new['cds_cdp_style'] = sanitize_text_field($_POST['cds_cdp_style']);
	}

	update_option( 'cdsp_template_options', $new );

	cdsp_update_dynamic_css();

	echo '<h3 style="color:#FF0000;">'.__('SETTINGS HAVE BEEN UPDATED','car-demon-shortcode').'</h3>';
}
function reset_cdsp_template() {
	delete_option('cdsp_template_options');
	echo '<h3 style="color:#FF0000;">'.__('SETTINGS HAVE BEEN RESET','car-demon-shortcode').'</h3>';
}
function cdsp_theme_options() {
	$cdsp_theme_path = plugins_url() .'/car-demon-widgets';
	$default = array();
//	$default['widget_body_styles'] = car_demon_theme_body_style_defaults();
	$cdsp_theme_options = array();
	$cdsp_theme_options = get_option( 'cdsp_template_options', $default );
//	if (empty($cdsp_theme_options['widget_body_styles'])) {$cdsp_theme_options['widget_body_styles'] = $default['widget_body_styles'];}
	return $cdsp_theme_options;
}

function cdsp_update_dynamic_css() {
	// Build file one
	$path = plugin_dir_path( __FILE__ );
	$path = str_replace ( 'admin', 'css', $path );

	$upload_dir = wp_upload_dir();
	$new_path = trailingslashit( $upload_dir['basedir'] );

	$css_file = $new_path . 'car-demon-theme-'. CDS_CSS_VER . '.css';

	ob_start();
		require_once( $path . 'car-demon-theme-css.php');
		$css_content = ob_get_contents();
	ob_end_clean();
	$new_css_file = fopen($css_file, "w") or die("Unable to open file!");
	fwrite($new_css_file, $css_content);
	fclose($new_css_file);

	// Build file two
	$css_file = $new_path . 'single-cars-for-sale.css';
	ob_start();
		require_once( $path . 'single-cars-for-sale.css.php');
		$css_content = ob_get_contents();
	ob_end_clean();
	$new_css_file = fopen($css_file, "w") or die("Unable to open file!");
	fwrite($new_css_file, $css_content);
	fclose($new_css_file);

	// Build file three - would be better to just localize this and not rewrite it
	$path = str_replace( 'css', 'js', $path );
	$js_file = $new_path . 'car-demon-single-cars.js';
	ob_start();
		require_once( $path . 'car-demon-single-cars.js.php');
		$js_content = ob_get_contents();
	ob_end_clean();
	$new_js_file = fopen($js_file, "w") or die("Unable to open file!");
	fwrite($new_js_file, $js_content);
	fclose($new_js_file);
}
?>