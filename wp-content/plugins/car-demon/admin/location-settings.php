<?php

function car_demon_plugin_options_do_page() {
	screen_icon();
	echo '<div class="wrap">';
		echo "<h2>". __('Car Demon Contact Options', 'car-demon') . "</h2>";
		echo __('For support please visit', 'car-demon').' <a href="http://cardemons.com" target="demon_win">CarDemons.com</a><br />';
		echo __('Each location will have it\'s own location settings. Make sure you fill out all of them.','car-demon');
		echo '<br />';
		echo __('Click on each location title to expand and hide it\'s settings.','car-demon');
		admin_contact_forms();
	echo '</div>';
}

function admin_contact_forms() {
	if (isset($_POST['update_location_options'])) {
		update_car_location_options();
	}
	$args = array(
		'style'              => 'none',
		'show_count'         => 0,
		'use_desc_for_title' => 0,
		'hierarchical'       => true,
		'echo'               => 0,
		'hide_empty'		 => 0,
		'taxonomy'           => 'vehicle_location'
		);
	$locations = get_categories( $args );

	$location_list = '';
	$location_name_list = '';
	foreach ($locations as $location) {
		$location_list .= ','.$location->slug;
		$location_name_list .= ','.$location->cat_name;
	}

	if (empty($location_list)) {
		$location_list = 'default';
		$location_name_list = 'Default';
	} else {
		$location_list = '@'.$location_list;
		$location_name_list = '@'.$location_name_list;
	
		$location_list = str_replace('@,', '', $location_list);
		$location_name_list = str_replace('@,', '', $location_name_list);
	}

	$location_name_list_array = explode(',',$location_name_list);
	$location_list_array = explode(',',$location_list);
	$x = 0;

    $numOfItems = 10;
    $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
    $to = $page * $numOfItems;
    $start = $to - $numOfItems;
    $total = sizeof($location_list_array);

	echo '<hr />';
	echo '<br />';
	echo '<div class="cd_location_nav">';
		echo paginate_links( array(
			'base' => add_query_arg( 'cpage', '%#%' ),
			'format' => '',
			'prev_text' => __('&laquo;'),
			'next_text' => __('&raquo;'),
			'total' => ceil($total / $numOfItems),
			'current' => $page
		));
	echo '</div>';
	$i = 0;

	foreach ($location_list_array as $current_location) {
		if ($i >= $start &&  $i < $to) {
			++$i;
		} else {
			++$x;
			++$i;
			continue;
		}
		cd_location_fields($current_location, $location_name_list_array, $x);
		++$x;
	}
	echo '<br /><a href="http://cardemons.com" target="demon_win"><img title="Certified Support" src="'.CAR_DEMON_PATH.'images/cd-certified-support.png" /></a>';
	echo '<br />'.__('For support please visit', 'car-demon').' <a href="http://cardemons.com" target="demon_win">CarDemons.com</a><br />';
}

function cd_location_fields($current_location = '', $location_name_list_array = array(), $x = '') {
	$holder = '';
	?>
	<form action="" name="frm_admin" method="post" class="cd_admin_form">
		<input type="hidden" name="update_location_options" id="update_location_options" value="1" />
		<input type="hidden" name="location_name" id="location_name" value="<?php echo $location_name_list_array[$x]; ?>" />
		<h1>+ <?php echo $location_name_list_array[$x]; ?> <?php _e('Contact Information', 'car-demon'); ?></h1>
		<div class="cd_location">
			<span class="cd_admin_form_label facebook_page"><?php _e('Facebook Fan Page', 'car-demon'); ?></span>
			<input type="text" value="<?php echo get_option($current_location.'_facebook_page') ?>" name="<?php echo $current_location; ?>_facebook_page" class="facebook_page" id="<?php echo $current_location; ?>_facebook_page" />
			<br />
			<span class="cd_admin_form_label new_sales_name"><?php _e('New Sales Name', 'car-demon'); ?></span>
			<input type="text" value="<?php echo get_option($current_location.'_new_sales_name') ?>" name="<?php echo $current_location; ?>_new_sales_name" class="new_sales_name" id="<?php echo $current_location; ?>_new_sales_name" />
			<br />
			<span class="cd_admin_form_label new_sales_number"><?php _e('New Sales Number', 'car-demon'); ?></span>
			<input type="text" value="<?php echo get_option($current_location.'_new_sales_number') ?>" name="<?php echo $current_location; ?>_new_sales_number" class="new_sales_number" id="<?php echo $current_location; ?>_new_sales_number" />
			<span class="cd_admin_form_label new_mobile_number"><?php _e('New Mobile Number', 'car-demon'); ?></span>
			<input type="text" value="<?php echo get_option($current_location.'_new_mobile_number') ?>" name="<?php echo $current_location; ?>_new_mobile_number" class="new_mobile_number" id="<?php echo $current_location; ?>_new_mobile_number" />
			<br />
			<span class="cd_admin_form_label new_mobile_provider"><?php _e('New Mobile Provider', 'car-demon'); ?></span>
			<?php
				$current_val = get_option($current_location.'_new_mobile_provider');
				echo select_cell_provider($current_location.'_new_mobile_provider', $current_val);
			?> <span class="small_text new_mobile_provider"><?php _e('- blank disables SMS for new.', 'car-demon'); ?></span>
			<br />
			<span class="cd_admin_form_label new_sales_email"><?php _e('New Sales Email', 'car-demon'); ?></span>
			<input type="text" value="<?php echo get_option($current_location.'_new_sales_email') ?>" name="<?php echo $current_location; ?>_new_sales_email" class="new_sales_email" id="<?php echo $current_location; ?>_new_sales_email" />
			<br />
			<span class="cd_admin_form_label used_sales_name"><?php _e('Used Sales Name', 'car-demon'); ?></span>
			<input type="text" value="<?php echo get_option($current_location.'_used_sales_name') ?>" name="<?php echo $current_location; ?>_used_sales_name" class="used_sales_name" id="<?php echo $current_location; ?>_used_sales_name" />
			<br />
			<span class="cd_admin_form_label used_sales_number"><?php _e('Used Sales Number', 'car-demon'); ?></span>
			<input type="text" value="<?php echo get_option($current_location.'_used_sales_number') ?>" name="<?php echo $current_location; ?>_used_sales_number" class="used_sales_number" id="<?php echo $current_location; ?>_used_sales_number" />
			<br />		
			<span class="cd_admin_form_label used_mobile_number"><?php _e('Used Mobile Number', 'car-demon'); ?></span>
			<input type="text" value="<?php echo get_option($current_location.'_used_mobile_number') ?>" name="<?php echo $current_location; ?>_used_mobile_number" class="used_mobile_number" id="<?php echo $current_location; ?>_used_mobile_number" />
			<br />
			<span class="cd_admin_form_label used_mobile_provider"><?php _e('Used Mobile Provider', 'car-demon'); ?></span>
			<?php
				$current_val = get_option($current_location.'_used_mobile_provider');
				echo select_cell_provider($current_location.'_used_mobile_provider', $current_val);
			?> <span class="small_text used_mobile_provider"><?php _e('- blank disables SMS for used.', 'car-demon'); ?></span>
			<br />
			<span class="cd_admin_form_label used_sales_email"><?php _e('Used Sales Email', 'car-demon'); ?></span>
			<input type="text" value="<?php echo get_option($current_location.'_used_sales_email') ?>" name="<?php echo $current_location; ?>_used_sales_email" class="used_sales_email" id="<?php echo $current_location; ?>_used_sales_email" />
			<br />
			<?php
				$default_description = get_option($current_location.'_default_description');
				if (strlen($default_description) < 2) {
					$default_description = get_default_description();
				}
			?>
			<span class="cd_admin_form_label admin_default_description"><?php _e('Default Description', 'car-demon'); ?></span>
			<textarea name="<?php echo $current_location; ?>_default_description" id="<?php echo $current_location; ?>_default_description" class="admin_default_description"><?php echo $default_description; ?></textarea>
			<br />
			<span class="cd_admin_form_label service_name"><?php _e('Service Name', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_service_name')) ?>" name="<?php echo $current_location; ?>_service_name" class="service_name" id="<?php echo $current_location; ?>_service_name" />
			<br />
			<span class="cd_admin_form_label service_number"><?php _e('Service Number', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_service_number')) ?>" name="<?php echo $current_location; ?>_service_number" class="service_number" id="<?php echo $current_location; ?>_service_number" />
			<br />
			<span class="cd_admin_form_label service_email"><?php _e('Service Email', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_service_email')) ?>" name="<?php echo $current_location; ?>_service_email" class="service_email" id="<?php echo $current_location; ?>_service_email" />
			<br />
			<span class="cd_admin_form_label parts_name"><?php _e('Parts Name', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_parts_name')) ?>" name="<?php echo $current_location; ?>_parts_name" class="parts_name" id="<?php echo $current_location; ?>_parts_name" />
			<br />
			<span class="cd_admin_form_label parts_number"><?php _e('Parts Number', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_parts_number')) ?>" name="<?php echo $current_location; ?>_parts_number" class="parts_number" id="<?php echo $current_location; ?>_parts_number" />
			<br />
			<span class="cd_admin_form_label parts_email"><?php _e('Parts Email', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_parts_email')) ?>" name="<?php echo $current_location; ?>_parts_email" class="parts_email" id="<?php echo $current_location; ?>_parts_email" />
			<br />
			<span class="cd_admin_form_label finance_name"><?php _e('Finance Name', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_name')) ?>" name="<?php echo $current_location; ?>_finance_name" class="finance_name" id="<?php echo $current_location; ?>_finance_name" />
			<br />
			<span class="cd_admin_form_label finance_number"><?php _e('Finance Number', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_number')) ?>" name="<?php echo $current_location; ?>_finance_number" class="finance_number" id="<?php echo $current_location; ?>_finance_number" />
			<br />
			<span class="cd_admin_form_label finance_email"><?php _e('Finance Email', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_email')) ?>" name="<?php echo $current_location; ?>_finance_email" class="finance_email" id="<?php echo $current_location; ?>_finance_email" />
			<br />
			<span class="cd_admin_form_label finance_url"><?php _e('Link to Finance Form', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_url')) ?>" name="<?php echo $current_location; ?>_finance_url" class="finance_url" id="<?php echo $current_location; ?>_finance_url" />
			<br />
			<span class="cd_admin_form_label finance_popup"><?php _e('Pop Up Finance Form', 'car-demon'); ?></span>
			<select name="<?php echo $current_location; ?>_finance_popup" id="<?php echo $current_location; ?>_finance_popup" class="finance_popup">
				<option value="<?php echo strip_tags(get_option($current_location.'_finance_popup')) ?>"><?php echo get_option($current_location.'_finance_popup') ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select>
			<span class="cd_finance_size_desc">&nbsp;Width: <input name="<?php echo $current_location; ?>_finance_width" id="<?php echo $current_location; ?>_finance_width" type="text" class="admin_finance_size" value="<?php echo get_option($current_location.'_finance_width') ?>" />&nbsp;Height: <input name="<?php echo $current_location; ?>_finance_height" id="<?php echo $current_location; ?>_finance_height" type="text" class="admin_finance_size" value="<?php echo get_option($current_location.'_finance_height') ?>" /> (800px X 600px optimal)</span>
			<br />
			<?php
				$finance_disclaimer =  wp_kses_post(get_option($current_location.'_finance_disclaimer'));
				if (strlen($finance_disclaimer) < 2) {
					$finance_disclaimer = get_default_finance_disclaimer();
				}
				$finance_description =  wp_kses_post(get_option($current_location.'_finance_description'));
				if (strlen($finance_description) < 2) {
					$finance_description = get_default_finance_description();
				}
			?>
			<span class="cd_admin_form_label finance_disclaimer"><?php _e('*Finance Disclaimer', 'car-demon'); ?></span>
			<textarea name="<?php echo $current_location; ?>_finance_disclaimer" id="<?php echo $current_location; ?>_finance_disclaimer" class="admin_default_description finance_disclaimer"><?php echo $finance_disclaimer; ?></textarea>
			<br />
			<span class="cd_admin_form_label finance_description"><?php _e('*Finance Description', 'car-demon'); ?></span>
			<textarea name="<?php echo $current_location; ?>_finance_description" id="<?php echo $current_location; ?>_finance_description" class="admin_default_description finance_description"><?php echo $finance_description; ?></textarea>
			<br />
			<span class="cd_admin_form_label trade_name"><?php _e('Trade Name', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_name')) ?>" name="<?php echo $current_location; ?>_trade_name" class="trade_name" id="<?php echo $current_location; ?>_trade_name" />
			<br />
			<span class="cd_admin_form_label trade_number"><?php _e('Trade Number', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_number')) ?>" name="<?php echo $current_location; ?>_trade_number" class="trade_number" id="<?php echo $current_location; ?>_trade_number" />
			<br />
			<span class="cd_admin_form_label trade_email"><?php _e('Trade Email', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_email')) ?>" name="<?php echo $current_location; ?>_trade_email" class="trade_email" id="<?php echo $current_location; ?>_trade_email" />
			<br />
			<span class="cd_admin_form_label trade_url"><?php _e('Link to Trade Form', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_url')) ?>" name="<?php echo $current_location; ?>_trade_url" class="trade_url" id="<?php echo $current_location; ?>_trade_url" />
			<br />
			<span class="cd_admin_form_label show_new_prices"><?php _e('Show Prices on New', 'car-demon'); ?></span>
			<select name="<?php echo $current_location; ?>_show_new_prices" id="<?php echo $current_location; ?>_show_new_prices" class="show_new_prices">
				<option value="<?php echo strip_tags(get_option($current_location.'_show_new_prices')) ?>"><?php echo strip_tags(get_option($current_location.'_show_new_prices')) ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select>&nbsp;<span class="cd_no_use">If No use:</span>
				<input type="text" value="<?php echo strip_tags(get_option($current_location.'_no_new_price')) ?>" name="<?php echo $current_location; ?>_no_new_price" id="<?php echo $current_location; ?>_no_new_price" class="admin_no_price no_new_price" />
			<br />
			<span class="cd_admin_form_label show_used_prices"><?php _e('Show Prices on Used', 'car-demon'); ?></span>
			<select name="<?php echo $current_location; ?>_show_used_prices" id="<?php echo $current_location; ?>_show_used_prices" class="show_used_prices">
				<option value="<?php echo strip_tags(get_option($current_location.'_show_used_prices')) ?>"><?php echo strip_tags(get_option($current_location.'_show_used_prices')) ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select>&nbsp;<span class="cd_no_use">If No use:</span>
				<input type="text" value="<?php echo strip_tags(get_option($current_location.'_no_used_price')) ?>" name="<?php echo $current_location; ?>_no_used_price" id="<?php echo $current_location; ?>_no_used_price" class="admin_no_price no_used_price" />
			<br />
			<span class="cd_admin_form_label new_large_photo_url"><?php _e('New Large Photo Url', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_new_large_photo_url')) ?>" name="<?php echo $current_location; ?>_new_large_photo_url" class="new_large_photo_url" id="<?php echo $current_location; ?>_new_large_photo_url" />
			<br />
			<span class="cd_admin_form_label new_small_photo_url"><?php _e('New Small Photo Url', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_new_small_photo_url')) ?>" name="<?php echo $current_location; ?>_new_small_photo_url" class="new_small_photo_url" id="<?php echo $current_location; ?>_new_small_photo_url" />
			<br />
			<span class="cd_admin_form_label used_large_photo_url"><?php _e('Used Large Photo Url', 'car-demon'); ?></span>
			<input type="text" value="<?php echo htmlentities(get_option($current_location.'_used_large_photo_url')) ?>" name="<?php echo $current_location; ?>_used_large_photo_url" class="used_large_photo_url" id="<?php echo $current_location; ?>_used_large_photo_url" />
			<br />
			<span class="cd_admin_form_label used_small_photo_url"><?php _e('Used Small Photo Url', 'car-demon'); ?></span>
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_used_small_photo_url')) ?>" name="<?php echo $current_location; ?>_used_small_photo_url" class="used_small_photo_url" id="<?php echo $current_location; ?>_used_small_photo_url" />
			<span class="car_demon_admin_hook">
				<?php
                    $car_demon_settings_hook = apply_filters('car_demon_admin_hook', $holder, $current_location); //= deprecated
					do_action( 'cd_location_action', $current_location );
					do_action( 'cd_location_settings', $current_location ); //= deprecated
                ?>
             </span>
			<br /><span class="admin_disclaimer_notice"><?php _e('* The Default disclaimer and description are provided as examples ONLY and may or may not be legally suitable for your state. Please have a lawyer review your disclaimer and description before using.', 'car-demon'); ?></span>
			<br />
			<input type="submit" name="sbtSendIt" value="<?php _e('Update Options', 'car-demon'); ?>" class="admin_update_options_btn" />
		</form>
	</div>
	<?php
}

function update_car_location_options($term_id = '') {

	$location_slug = 'default';

	if (isset($_POST['location_name'])) {
		// Build slug from name
		$current_location = $_POST['location_name'];
		$current_location = trim($current_location);
		$current_location = strtolower($current_location);
		$current_location = str_replace(' ', '-', $current_location);
		$location_slug = $current_location;
	}

	if (!empty($term_id)) {
		//collect all term related data for this new taxonomy
		$term_item = get_term($term_id, 'vehicle_location');
		$current_location = $term_item->slug;
	}

	if (isset($_POST['tag-name'])) {
		$current_location = $_POST['tag-name'];
		$current_location = trim($current_location);
		$current_location = strtolower($current_location);
		$current_location = str_replace(' ', '-', $current_location);
		$location_slug = 'default';
	}

	if (isset($_POST['slug'])) {
		$current_location = $_POST['slug'];
		$location_slug = $_POST['slug'];
	}

	$data = $_POST;
	$location = array();
	foreach ($data as $key=>$value) {
		$key = str_replace($location_slug.'_', '', $key);
		$location[$current_location][$key] = $value;
	}

	// begin storing all location information in a single array
	update_option('location_'.$current_location, $location, 'no');

	if (empty($current_location)) {
		$current_location = 'default';
	}

	// store location information as individual options for legacy - these options should be removed in the future
	if (isset($_POST[$location_slug.'_new_mobile_number'])) { update_option($current_location.'_new_mobile_number', wp_filter_nohtml_kses($_POST[$location_slug.'_new_mobile_number']), 'no'); }
	if (isset($_POST[$location_slug.'_new_mobile_provider'])) { update_option($current_location.'_new_mobile_provider', wp_filter_nohtml_kses($_POST[$location_slug.'_new_mobile_provider']), 'no'); }
	if (isset($_POST[$location_slug.'_used_mobile_number'])) { update_option($current_location.'_used_mobile_number', wp_filter_nohtml_kses($_POST[$location_slug.'_used_mobile_number']), 'no'); }
	if (isset($_POST[$location_slug.'_used_mobile_provider'])) { update_option($current_location.'_used_mobile_provider', wp_filter_nohtml_kses($_POST[$location_slug.'_used_mobile_provider']), 'no'); }
	if (isset($_POST[$location_slug.'_facebook_page'])) { update_option($current_location.'_facebook_page', wp_filter_nohtml_kses($_POST[$location_slug.'_facebook_page']), 'no'); }
	if (isset($_POST[$location_slug.'_new_sales_name'])) { update_option($current_location.'_new_sales_name', wp_filter_nohtml_kses($_POST[$location_slug.'_new_sales_name']), 'no'); }
	if (isset($_POST[$location_slug.'_new_sales_number'])) { update_option($current_location.'_new_sales_number', wp_filter_nohtml_kses($_POST[$location_slug.'_new_sales_number']), 'no'); }
	if (isset($_POST[$location_slug.'_new_sales_email'])) { update_option($current_location.'_new_sales_email', wp_filter_nohtml_kses($_POST[$location_slug.'_new_sales_email']), 'no'); }
	if (isset($_POST[$location_slug.'_used_sales_name'])) { update_option($current_location.'_used_sales_name', wp_filter_nohtml_kses($_POST[$location_slug.'_used_sales_name']), 'no'); }
	if (isset($_POST[$location_slug.'_used_sales_number'])) { update_option($current_location.'_used_sales_number', wp_filter_nohtml_kses($_POST[$location_slug.'_used_sales_number']), 'no'); }
	if (isset($_POST[$location_slug.'_used_sales_email'])) { update_option($current_location.'_used_sales_email', wp_filter_nohtml_kses($_POST[$location_slug.'_used_sales_email']), 'no'); }
	if (isset($_POST[$location_slug.'_default_description'])) { update_option($current_location.'_default_description', wp_filter_nohtml_kses($_POST[$location_slug.'_default_description']), 'no'); }
	if (isset($_POST[$location_slug.'_service_name'])) { update_option($current_location.'_service_name', wp_filter_nohtml_kses($_POST[$location_slug.'_service_name']), 'no'); }
	if (isset($_POST[$location_slug.'_service_number'])) { update_option($current_location.'_service_number', wp_filter_nohtml_kses($_POST[$location_slug.'_service_number']), 'no'); }
	if (isset($_POST[$location_slug.'_service_email'])) { update_option($current_location.'_service_email', wp_filter_nohtml_kses($_POST[$location_slug.'_service_email']), 'no'); }
	if (isset($_POST[$location_slug.'_parts_name'])) { update_option($current_location.'_parts_name', wp_filter_nohtml_kses($_POST[$location_slug.'_parts_name']), 'no'); }
	if (isset($_POST[$location_slug.'_parts_number'])) { update_option($current_location.'_parts_number', wp_filter_nohtml_kses($_POST[$location_slug.'_parts_number']), 'no'); }
	if (isset($_POST[$location_slug.'_parts_email'])) { update_option($current_location.'_parts_email', wp_filter_nohtml_kses($_POST[$location_slug.'_parts_email']), 'no'); }
	if (isset($_POST[$location_slug.'_finance_name'])) { update_option($current_location.'_finance_name', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_name']), 'no'); }
	if (isset($_POST[$location_slug.'_finance_number'])) { update_option($current_location.'_finance_number', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_number']), 'no'); }
	if (isset($_POST[$location_slug.'_finance_email'])) { update_option($current_location.'_finance_email', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_email']), 'no'); }
	if (isset($_POST[$location_slug.'_finance_url'])) { update_option($current_location.'_finance_url', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_url']), 'no'); }
	if (isset($_POST[$location_slug.'_finance_popup'])) { update_option($current_location.'_finance_popup', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_popup']), 'no'); }
	if (isset($_POST[$location_slug.'_finance_width'])) { update_option($current_location.'_finance_width', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_width']), 'no'); }
	if (isset($_POST[$location_slug.'_finance_height'])) { update_option($current_location.'_finance_height', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_height']), 'no'); }
	if (isset($_POST[$location_slug.'_finance_disclaimer'])) { 
		$finance_disclaimer = $_POST[$location_slug.'_finance_disclaimer'];
		$finance_disclaimer = str_replace("\'", "'", $finance_disclaimer);
		$finance_disclaimer = str_replace('\"', '"', $finance_disclaimer);
		$finance_disclaimer = str_replace('\\', '', $finance_disclaimer);
		update_option($current_location.'_finance_disclaimer', $finance_disclaimer, 'no'); 
	}
	if (isset($_POST[$location_slug.'_finance_description'])) {
		$finance_description = $_POST[$location_slug.'_finance_description'];
		$finance_description = str_replace("\'", "'", $finance_description);
		$finance_description = str_replace('\"', '"', $finance_description);
		$finance_description = str_replace('\\', '', $finance_description);			
		update_option($current_location.'_finance_description', $finance_description, 'no');
	}
	if (isset($_POST[$location_slug.'_trade_name'])) { update_option($current_location.'_trade_name', wp_filter_nohtml_kses($_POST[$location_slug.'_trade_name']), 'no'); }
	if (isset($_POST[$location_slug.'_trade_number'])) { update_option($current_location.'_trade_number', wp_filter_nohtml_kses($_POST[$location_slug.'_trade_number']), 'no'); }
	if (isset($_POST[$location_slug.'_trade_email'])) { update_option($current_location.'_trade_email', wp_filter_nohtml_kses($_POST[$location_slug.'_trade_email']), 'no'); }
	if (isset($_POST[$location_slug.'_trade_url'])) { update_option($current_location.'_trade_url', wp_filter_nohtml_kses($_POST[$location_slug.'_trade_url']), 'no'); }
	if (isset($_POST[$location_slug.'_show_new_prices'])) { update_option($current_location.'_show_new_prices', wp_filter_nohtml_kses($_POST[$location_slug.'_show_new_prices']), 'no'); }
	if (isset($_POST[$location_slug.'_show_used_prices'])) { update_option($current_location.'_show_used_prices', wp_filter_nohtml_kses($_POST[$location_slug.'_show_used_prices']), 'no'); }
	if (isset($_POST[$location_slug.'_new_large_photo_url'])) { update_option($current_location.'_new_large_photo_url', wp_filter_nohtml_kses($_POST[$location_slug.'_new_large_photo_url']), 'no'); }
	if (isset($_POST[$location_slug.'_new_small_photo_url'])) { update_option($current_location.'_new_small_photo_url', wp_filter_nohtml_kses($_POST[$location_slug.'_new_small_photo_url']), 'no'); }
	// allow HTML in this field
	if (isset($_POST[$location_slug.'_used_large_photo_url'])) { 
		$used_large_photo_url = sanitize_text_field($_POST[$location_slug.'_used_large_photo_url']);
		$used_large_photo_url = str_replace('\\"', '"', $used_large_photo_url);
		update_option($current_location.'_used_large_photo_url', $used_large_photo_url, 'no'); 
	}
	if (isset($_POST[$location_slug.'_used_small_photo_url'])) { update_option($current_location.'_used_small_photo_url', wp_filter_nohtml_kses($_POST[$location_slug.'_used_small_photo_url']), 'no'); }
	if (isset($_POST[$location_slug.'_no_new_price'])) { update_option($current_location.'_no_new_price', wp_filter_nohtml_kses($_POST[$location_slug.'_no_new_price']), 'no'); }
	if (isset($_POST[$location_slug.'_no_used_price'])) { update_option($current_location.'_no_used_price', wp_filter_nohtml_kses($_POST[$location_slug.'_no_used_price']), 'no'); }
	$holder = '';
	$car_demon_settings_hook = apply_filters('car_demon_admin_update_hook', $holder, $location_slug); //= deprecated
	do_action( 'cd_location_update_action', $location_slug );
}

// = New Location Management Code
if ( ! defined( 'CD_LOCATIONS' ) ) {
	define('CD_LOCATIONS', true);
}
if (defined('CD_LOCATIONS')) {
	add_action('vehicle_location_add_form_fields','cd_add_location_form');
	function cd_add_location_form($term_id = '') {
		if (is_object($term_id)) {
			$location = $term_id->slug;
		} else {
			if ($term_id == 'vehicle_location') {
				$location = 'default';
			} else {
				if (!empty($term_id)) {
					//collect all term related data for this new taxonomy
					$term_item = get_term($term_id, 'vehicle_location');
					$location = $term_item->slug;
				} else {
					// Build slug from name
					$location = $_POST['location_name'];
					$location = trim($location);
					$location = strtolower($location);
					$location = str_replace(' ', '-', $location);
				}
			}
		}
	
		if (empty($location)) {
			$location = 'default';
		}
	
		$holder = '';
	
		$fields = cd_get_location_fields();
	
		$html = '';
		
		foreach ($fields as $field) {
			$html .= cd_add_location_text_field($location, $field);
		}
		
		echo $html;
		
		apply_filters('cd_add_location_hook', '');
		
		$html = '
			<div class="form-field">';
				echo $html;
				apply_filters('car_demon_admin_hook', $holder, $location); //= deprecated
		$html = '</div>';
		echo $html;

		do_action( 'cd_location_action', $location );
	
		$html = '<span class="admin_disclaimer_notice">'.__('* The Default disclaimer and description are provided as examples ONLY and may or may not be legally suitable for your state. Please have a lawyer review your disclaimer and description before using.', 'car-demon').'</span>';
		echo $html;
	}
	
	function cd_add_location_text_field($location, $field) {
		$value = strip_tags(get_option($location.$field['field']));
	
		if (empty($value)) {
			$value = $field['default'];
		}
	
		if (empty($field['type'])) {
			$field['type'] = 'text';
		}
	
		$html = '
			<div class="form-field">
				<label for="'.$location.$field['field'].'">'.$field['label'].'</label>';
	
				if ($field['type'] == 'text') {
					$html .= '<input type="text" value="'.$value.'" name="'.$location.$field['field'].'" id="'.$location.$field['field'].'" />';
				}
	
				if ($field['type'] == 'textarea') {
					$html .= '<textarea name="'.$location.$field['field'].'" id="'.$location.$field['field'].'" rows="5" cols="40">';
						$html .= $value;
					$html .= '</textarea>';
				}
	
				if ($field['type'] == 'select') {
					if (isset($field['options'])) {
						if (is_array($field['options'])) {
							$html .= '<select name="'.$location.$field['field'].'" id="'.$location.$field['field'].'">';
								foreach ($field['options'] as $option) {
									$html .= '<option value="'.$option.'"'.($option == $value ? ' selected' : '').'>'.$option.'</option>';
								}
							$html .= '</select>';
						}
					}
				}
	
				$html .= '<p>'.$field['msg'].'</p>
			</div>
		';
	
		return $html;
	}
	
	add_action('vehicle_location_edit_form_fields','cd_edit_location_form');
	function cd_edit_location_form($term_id = '') {
		if (is_object($term_id)) {
			$location = $term_id->slug;
		} else {
			if ($term_id == 'vehicle_location') {
				$location = 'default';
			} else {
				if (!empty($term_id)) {
					//collect all term related data for this new taxonomy
					$term_item = get_term($term_id, 'vehicle_location');
					$location = $term_item->slug;
				} else {
					// Build slug from name
					$location = $_POST['location_name'];
					$location = trim($location);
					$location = strtolower($location);
					$location = str_replace(' ', '-', $location);
				}
			}
		}

		if (empty($location)) {
			$location = 'default';
		}
	
		$holder = '';
	
		$fields = cd_get_location_fields();
	
		$html = '';
		
		foreach ($fields as $field) {
			$html .= cd_edit_location_text_field($location, $field);
		}
		
		echo $html;
		
		apply_filters('cd_edit_location_hook', '', $location); //= deprecated
		do_action( 'cd_location_update_action', $location );
		
		$html = '
			<tr class="form-field">
				<th valign="top" scope="row">
				</th>
				<td>';
					echo $html;
					apply_filters('car_demon_admin_hook', $holder, $location); //= deprecated
			$html = '</td>
			</tr>';
		echo $html;

		do_action( 'cd_location_action', $location );
		
		$html = '
			<tr class="form-field">
				<th valign="top" scope="row">
					<label class="admin_disclaimer_notice">
						'.__('NOTICE', 'car-demon').'
					</label>
				</th>
				<td>';
				$html .= '<span class="admin_disclaimer_notice">'.__('* The Default disclaimer and description are provided as examples ONLY and may or may not be legally suitable for your state. Please have a lawyer review your disclaimer and description before using.', 'car-demon').'</span>';
			$html .= '</td>
			</tr>';
		echo $html;
	}
	
	function cd_edit_location_text_field($location, $field) {
		$value = strip_tags(get_option($location.$field['field']));
		if (empty($value)) {
			$value = $field['default'];
		}
	
		if (empty($field['type'])) {
			$field['type'] = 'text';
		}

		if ( $field['type'] == 'hidden' ) {
			$html = '<input type="hidden" name="' . $field['field'] . '" id="' . $location . $field['field'] . '" value="' . $value . '" />';
			return $html;
		}

		$html = '
			<tr class="form-field">
				<th valign="top" scope="row">
					<label for="' . $location . $field['field'] . '">' . $field['label'] . '</label>
				</th>
				<td>';

				if ($field['type'] == 'text') {
					$html .= '<input type="text" value="' . $value . '" name="' . $location . $field['field'] . '" id="' . $location . $field['field'] . '" />';
				}
	
				if ($field['type'] == 'textarea') {
					$html .= '<textarea name="' . $location . $field['field'] . '" id="' . $location . $field['field'] . '" rows="5" cols="40">';
						$html .= $value;
					$html .= '</textarea>';
				}

				if ($field['type'] == 'select') {
					if (isset($field['options'])) {
						if (is_array($field['options'])) {
							$html .= '<select name="' . $location . $field['field'] . '" id="' . $location . $field['field'] . '">';
								foreach ($field['options'] as $option) {
									$html .= '<option value="' . $option . '"' . ($option == $value ? ' selected' : '') . '>' . $option . '</option>';
								}
							$html .= '</select>';
						}
					}
				}
	
					$html .= '<p class="description">' . $field['msg'] . '</p>
				</td>
			</tr>
		';
		return $html;
	}
	
	add_action( 'edit_vehicle_location', 'update_car_location_options' );
	add_action( 'create_vehicle_location', 'add_car_location_options' );
	function add_car_location_options( $term_id = '' ) {
		$location_slug = 'default';

		if (isset($_POST['tag-name'])) {
			$current_location = $_POST['tag-name'];
			$current_location = trim($current_location);
			$current_location = strtolower($current_location);
			$current_location = str_replace(' ', '-', $current_location);
		} else {
			$current_location = 'default';
		}

		if (isset($_POST['slug'])) {
			if (!empty($_POST['slug'])) {
				$current_location = $_POST['slug'];
			}
		}

		if ( ! isset( $_POST['manage_locations'] ) ) {
			if ( isset( $_POST['post_title'] ) ) {

				if ( isset($_POST['tax_input']['vehicle_location'][0]) ) {
					$current_location = $_POST['tax_input']['vehicle_location'][0];
					$term_settings_exists = get_option('location_'.$current_location, false );
					if ( ! $term_settings_exists ) {

						remove_action( 'edit_vehicle_location', 'update_car_location_options' );
						remove_action( 'create_vehicle_location', 'add_car_location_options' );
						$location = car_demon_default_location( $current_location );
						car_demon_import_location( $location, $current_location );
						return;
						
					} //= end term exists
				
				} //= end isset($_POST['tax_input']['vehicle_location'][0])

			} //= end isset( $_POST['post_title']
		} //= end isset( $_POST['manage_locations'] )

		$data = $_POST;
		$location = array();
		foreach ($data as $key=>$value) {
			$key = str_replace($location_slug.'_', '', $key);
			$location[$current_location][$key] = $value;
		}

		if (empty($current_location)) {
			$current_location = 'default';
		}

		// begin storing all location information in a single array
		update_option('location_'.$current_location, $location, 'no');
	
		// store location information as individual options for legacy - these options should be removed in the future
		if (isset($_POST[$location_slug.'_new_mobile_number'])) { update_option($current_location.'_new_mobile_number', wp_filter_nohtml_kses($_POST[$location_slug.'_new_mobile_number']), 'no'); }
		if (isset($_POST[$location_slug.'_new_mobile_provider'])) { update_option($current_location.'_new_mobile_provider', wp_filter_nohtml_kses($_POST[$location_slug.'_new_mobile_provider']), 'no'); }
		if (isset($_POST[$location_slug.'_used_mobile_number'])) { update_option($current_location.'_used_mobile_number', wp_filter_nohtml_kses($_POST[$location_slug.'_used_mobile_number']), 'no'); }
		if (isset($_POST[$location_slug.'_used_mobile_provider'])) { update_option($current_location.'_used_mobile_provider', wp_filter_nohtml_kses($_POST[$location_slug.'_used_mobile_provider']), 'no'); }
		if (isset($_POST[$location_slug.'_facebook_page'])) { update_option($current_location.'_facebook_page', wp_filter_nohtml_kses($_POST[$location_slug.'_facebook_page']), 'no'); }
		if (isset($_POST[$location_slug.'_new_sales_name'])) { update_option($current_location.'_new_sales_name', wp_filter_nohtml_kses($_POST[$location_slug.'_new_sales_name']), 'no'); }
		if (isset($_POST[$location_slug.'_new_sales_number'])) { update_option($current_location.'_new_sales_number', wp_filter_nohtml_kses($_POST[$location_slug.'_new_sales_number']), 'no'); }
		if (isset($_POST[$location_slug.'_new_sales_email'])) { update_option($current_location.'_new_sales_email', wp_filter_nohtml_kses($_POST[$location_slug.'_new_sales_email']), 'no'); }
		if (isset($_POST[$location_slug.'_used_sales_name'])) { update_option($current_location.'_used_sales_name', wp_filter_nohtml_kses($_POST[$location_slug.'_used_sales_name']), 'no'); }
		if (isset($_POST[$location_slug.'_used_sales_number'])) { update_option($current_location.'_used_sales_number', wp_filter_nohtml_kses($_POST[$location_slug.'_used_sales_number']), 'no'); }
		if (isset($_POST[$location_slug.'_used_sales_email'])) { update_option($current_location.'_used_sales_email', wp_filter_nohtml_kses($_POST[$location_slug.'_used_sales_email']), 'no'); }
		if (isset($_POST[$location_slug.'_default_description'])) { update_option($current_location.'_default_description', wp_filter_nohtml_kses($_POST[$location_slug.'_default_description']), 'no'); }
		if (isset($_POST[$location_slug.'_service_name'])) { update_option($current_location.'_service_name', wp_filter_nohtml_kses($_POST[$location_slug.'_service_name']), 'no'); }
		if (isset($_POST[$location_slug.'_service_number'])) { update_option($current_location.'_service_number', wp_filter_nohtml_kses($_POST[$location_slug.'_service_number']), 'no'); }
		if (isset($_POST[$location_slug.'_service_email'])) { update_option($current_location.'_service_email', wp_filter_nohtml_kses($_POST[$location_slug.'_service_email']), 'no'); }
		if (isset($_POST[$location_slug.'_parts_name'])) { update_option($current_location.'_parts_name', wp_filter_nohtml_kses($_POST[$location_slug.'_parts_name']), 'no'); }
		if (isset($_POST[$location_slug.'_parts_number'])) { update_option($current_location.'_parts_number', wp_filter_nohtml_kses($_POST[$location_slug.'_parts_number']), 'no'); }
		if (isset($_POST[$location_slug.'_parts_email'])) { update_option($current_location.'_parts_email', wp_filter_nohtml_kses($_POST[$location_slug.'_parts_email']), 'no'); }
		if (isset($_POST[$location_slug.'_finance_name'])) { update_option($current_location.'_finance_name', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_name']), 'no'); }
		if (isset($_POST[$location_slug.'_finance_number'])) { update_option($current_location.'_finance_number', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_number']), 'no'); }
		if (isset($_POST[$location_slug.'_finance_email'])) { update_option($current_location.'_finance_email', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_email']), 'no'); }
		if (isset($_POST[$location_slug.'_finance_url'])) { update_option($current_location.'_finance_url', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_url']), 'no'); }
		if (isset($_POST[$location_slug.'_finance_popup'])) { update_option($current_location.'_finance_popup', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_popup']), 'no'); }
		if (isset($_POST[$location_slug.'_finance_width'])) { update_option($current_location.'_finance_width', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_width']), 'no'); }
		if (isset($_POST[$location_slug.'_finance_height'])) { update_option($current_location.'_finance_height', wp_filter_nohtml_kses($_POST[$location_slug.'_finance_height']), 'no'); }
		if (isset($_POST[$location_slug.'_finance_disclaimer'])) { 
			$finance_disclaimer = $_POST[$location_slug.'_finance_disclaimer'];
			$finance_disclaimer = str_replace("\'", "'", $finance_disclaimer);
			$finance_disclaimer = str_replace('\"', '"', $finance_disclaimer);
			$finance_disclaimer = str_replace('\\', '', $finance_disclaimer);
			update_option($current_location.'_finance_disclaimer', $finance_disclaimer, 'no'); 
		}
		if (isset($_POST[$location_slug.'_finance_description'])) {
			$finance_description = $_POST[$location_slug.'_finance_description'];
			$finance_description = str_replace("\'", "'", $finance_description);
			$finance_description = str_replace('\"', '"', $finance_description);
			$finance_description = str_replace('\\', '', $finance_description);			
			update_option($current_location.'_finance_description', $finance_description, 'no');
		}
		if (isset($_POST[$location_slug.'_trade_name'])) { update_option($current_location.'_trade_name', wp_filter_nohtml_kses($_POST[$location_slug.'_trade_name']), 'no'); }
		if (isset($_POST[$location_slug.'_trade_number'])) { update_option($current_location.'_trade_number', wp_filter_nohtml_kses($_POST[$location_slug.'_trade_number']), 'no'); }
		if (isset($_POST[$location_slug.'_trade_email'])) { update_option($current_location.'_trade_email', wp_filter_nohtml_kses($_POST[$location_slug.'_trade_email']), 'no'); }
		if (isset($_POST[$location_slug.'_trade_url'])) { update_option($current_location.'_trade_url', wp_filter_nohtml_kses($_POST[$location_slug.'_trade_url']), 'no'); }
		if (isset($_POST[$location_slug.'_show_new_prices'])) { update_option($current_location.'_show_new_prices', wp_filter_nohtml_kses($_POST[$location_slug.'_show_new_prices']), 'no'); }
		if (isset($_POST[$location_slug.'_show_used_prices'])) { update_option($current_location.'_show_used_prices', wp_filter_nohtml_kses($_POST[$location_slug.'_show_used_prices']), 'no'); }
		if (isset($_POST[$location_slug.'_new_large_photo_url'])) { update_option($current_location.'_new_large_photo_url', wp_filter_nohtml_kses($_POST[$location_slug.'_new_large_photo_url']), 'no'); }
		if (isset($_POST[$location_slug.'_new_small_photo_url'])) { update_option($current_location.'_new_small_photo_url', wp_filter_nohtml_kses($_POST[$location_slug.'_new_small_photo_url']), 'no'); }
		// allow HTML in this field
		if (isset($_POST[$location_slug.'_used_large_photo_url'])) { 
			$used_large_photo_url = sanitize_text_field($_POST[$location_slug.'_used_large_photo_url']);
			$used_large_photo_url = str_replace('\\"', '"', $used_large_photo_url);
			update_option($current_location.'_used_large_photo_url', $used_large_photo_url, 'no'); 
		}
		if (isset($_POST[$location_slug.'_used_small_photo_url'])) { update_option($current_location.'_used_small_photo_url', wp_filter_nohtml_kses($_POST[$location_slug.'_used_small_photo_url']), 'no'); }
		if (isset($_POST[$location_slug.'_no_new_price'])) { update_option($current_location.'_no_new_price', wp_filter_nohtml_kses($_POST[$location_slug.'_no_new_price']), 'no'); }
		if (isset($_POST[$location_slug.'_no_used_price'])) { update_option($current_location.'_no_used_price', wp_filter_nohtml_kses($_POST[$location_slug.'_no_used_price']), 'no'); }
		$holder = '';
		$car_demon_settings_hook = apply_filters('car_demon_admin_update_hook', $holder, $location_slug);
		do_action( 'cd_location_update_action', $location_slug );
	}

	function cd_get_location_fields() {
		$default_name = get_bloginfo( 'name' );
		$default_email = get_bloginfo( 'admin_email' );
	
		$fields = array(
			array(
				'field' => '_new_sales_name',
				'label' => __('New Sales Name', 'car-demon'),
				'type' => 'text',
				'default' => $default_name,
				'msg' => __('Enter a name for your new sales department.', 'car-demon')
			),
			array(
				'field' => '_new_sales_number',
				'label' => __('New Sales Number', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter the phone number for your new sales department.', 'car-demon')
			),
/*
			array(
				'field' => '_new_mobile_number',
				'label' => __('New Mobile Sales Number', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter the mobile number for your new sales department.', 'car-demon')
			),
			array(
				'field' => '_new_mobile_provider',
				'label' => __('New Mobile Provider', 'car-demon'),
				'type' => 'select',
				'default' => '',
				'msg' => __('Enter the mobile provider for your new sales department.', 'car-demon'),
				'options' => cd_cell_providers()
			),
*/
			array(
				'field' => '_new_sales_email',
				'label' => __('New Sales Email', 'car-demon'),
				'type' => 'text',
				'default' => $default_email,
				'msg' => __('Enter the email address for your new sales department.', 'car-demon')
			),
			array(
				'field' => '_used_sales_name',
				'label' => __('Used Sale Name', 'car-demon'),
				'type' => 'text',
				'default' => $default_name,
				'msg' => __('Enter a name for your used sales department.', 'car-demon')
			),
			array(
				'field' => '_used_sales_number',
				'label' => __('Used Sales Number', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a phone number for your used sales department.', 'car-demon')
			),
/*
			array(
				'field' => '_used_mobile_number',
				'label' => __('Used Mobile Sales Number', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a mobile number for your used sales department.', 'car-demon')
			),
			array(
				'field' => '_used_mobile_provider',
				'label' => __('Used Mobile Provider', 'car-demon'),
				'type' => 'select',
				'default' => '',
				'msg' => __('Enter a mobile provider for your used sales department.', 'car-demon'),
				'options' => cd_cell_providers()
			),
*/
			array(
				'field' => '_used_sales_email',
				'label' => __('Used Sales Email', 'car-demon'),
				'type' => 'text',
				'default' => $default_email,
				'msg' => __('Enter an email address for your used sales department.', 'car-demon')
			),
			array(
				'field' => '_default_description',
				'label' => __('Default Vehicle Description', 'car-demon'),
				'type' => 'textarea',
				'default' => '',
				'msg' => __('Enter a default vehicle description for your vehicles.', 'car-demon')
			),
			array(
				'field' => '_service_name',
				'label' => __('Service Name', 'car-demon'),
				'type' => 'text',
				'default' => $default_name,
				'msg' => __('Enter a name for your service department.', 'car-demon')
			),
			array(
				'field' => '_service_number',
				'label' => __('Service Number', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a phone number for your service department.', 'car-demon')
			),
			array(
				'field' => '_service_email',
				'label' => __('Service Email', 'car-demon'),
				'type' => 'text',
				'default' => $default_email,
				'msg' => __('Enter an email address for your service department.', 'car-demon')
			),
			array(
				'field' => '_parts_name',
				'label' => __('Parts Name', 'car-demon'),
				'type' => 'text',
				'default' => $default_name,
				'msg' => __('Enter a name for your parts department.', 'car-demon')
			),
			array(
				'field' => '_parts_number',
				'label' => __('Parts Number', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a phone number for your parts department.', 'car-demon')
			),
			array(
				'field' => '_parts_email',
				'label' => __('Parts Email', 'car-demon'),
				'type' => 'text',
				'default' => $default_email,
				'msg' => __('Enter an email address for your parts department.', 'car-demon')
			),
			array(
				'field' => '_finance_name',
				'label' => __('Finance Name', 'car-demon'),
				'type' => 'text',
				'default' => $default_name,
				'msg' => __('Enter a name for your finance department.', 'car-demon')
			),
			array(
				'field' => '_finance_number',
				'label' => __('Finance Number', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a phone number for your finance department.', 'car-demon')
			),
			array(
				'field' => '_finance_email',
				'label' => __('Finance Email', 'car-demon'),
				'type' => 'text',
				'default' => $default_email,
				'msg' => __('Enter an email address for your finance department.', 'car-demon')
			),
			array(
				'field' => '_finance_url',
				'label' => __('Finance URL', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a URL to your finance department.', 'car-demon')
			),
			array(
				'field' => '_finance_popup',
				'label' => __('Finance Popup', 'car-demon'),
				'type' => 'select',
				'default' => __('No', 'car-demon'),
				'msg' => __('Do you want to open your finance form URL in a pop up?', 'car-demon'),
				'options' => array(__('Yes', 'car-demon'), __('No', 'car-demon'))
			),
			array(
				'field' => '_finance_width',
				'label' => __('Finance Popup Window Width', 'car-demon'),
				'type' => 'text',
				'default' => '600',
				'msg' => __('Enter a width for your pop-up finance form window.', 'car-demon'),
			),
			array(
				'field' => '_finance_height',
				'label' => __('Finance Popup Window Height', 'car-demon'),
				'type' => 'text',
				'default' => '800',
				'msg' => __('Enter a height for your pop-up finance form window.', 'car-demon'),
			),
			array(
				'field' => '_finance_disclaimer',
				'label' => __('Finance Disclaimer', 'car-demon'),
				'type' => 'textarea',
				'default' => '',
				'msg' => __('Enter a disclaimer for your finance form.', 'car-demon')
			),
			array(
				'field' => '_finance_description',
				'label' => __('Finance Description', 'car-demon'),
				'type' => 'textarea',
				'default' => '',
				'msg' => __('Enter a description for your finance form.', 'car-demon')
			),
			array(
				'field' => '_trade_name',
				'label' => __('Trade Name', 'car-demon'),
				'type' => 'text',
				'default' => $default_name,
				'msg' => __('Enter a name for your trade-in department.', 'car-demon')
			),
			array(
				'field' => '_trade_number',
				'label' => __('Trade Number', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a phone number for your finance department.', 'car-demon')
			),
			array(
				'field' => '_trade_email',
				'label' => __('Trade Email', 'car-demon'),
				'type' => 'text',
				'default' => $default_email,
				'msg' => __('Enter an email address for your finance department.', 'car-demon')
			),
			array(
				'field' => '_trade_url',
				'label' => __('Trade URL', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a URL to your trade-in department.', 'car-demon')
			),
			array(
				'field' => '_show_new_prices',
				'label' => __('Show New Prices', 'car-demon'),
				'type' => 'select',
				'default' => __('Yes', 'car-demon'),
				'msg' => __('Do you want to show prices for your new vehicles?', 'car-demon'),
				'options' => array(__('Yes', 'car-demon'), __('No', 'car-demon'))
			),
			array(
				'field' => '_no_new_price',
				'label' => __('New no price text', 'car-demon'),
				'type' => 'text',
				'default' => 'Call for price',
				'msg' => __('Enter the text to show if new prices are hidden or a new vehicle does not have a price.', 'car-demon')
			),
			array(
				'field' => '_show_used_prices',
				'label' => __('Show Used Prices', 'car-demon'),
				'type' => 'select',
				'default' => __('Yes', 'car-demon'),
				'msg' => __('Do you want to show prices for your used vehicles?', 'car-demon'),
				'options' => array(__('Yes', 'car-demon'), __('No', 'car-demon'))
			),
			array(
				'field' => '_no_used_price',
				'label' => __('Used no price text', 'car-demon'),
				'type' => 'text',
				'default' => 'Call for price',
				'msg' => __('Enter the text to show if used prices are hidden or a used vehicle does not have a price.', 'car-demon')
			),
			array(
				'field' => 'manage_location',
				'label' => '',
				'type' => 'hidden',
				'default' => '1',
				'msg' => ''
			),
/*
			array(
				'field' => '_facebook_page',
				'label' => __('Facebook Page', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter the URL to your Facebook page - this option is not used by default but may be used by add-ons', 'car-demon'),
			),
			array(
				'field' => '_new_large_photo_url',
				'label' => __('New Large Photo URL', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a URL to an image to display on new vehicle pages - this option is not used by default but may be used by add-ons', 'car-demon'),
			),
			array(
				'field' => '_new_small_photo_url',
				'label' => __('New Small Photo URL', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a URL to an image to display on new vehicle pages - this option is not used by default but may be used by add-ons', 'car-demon'),
			),
			array(
				'field' => '_used_large_photo_url',
				'label' => __('Used Large Photo URL', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a URL to an image to display on used vehicle pages - this option is not used by default but may be used by add-ons', 'car-demon'),
			),
			array(
				'field' => '_used_small_photo_url',
				'label' => __('Used Small Photo URL', 'car-demon'),
				'type' => 'text',
				'default' => '',
				'msg' => __('Enter a URL to an image to display on used vehicle pages - this option is not used by default but may be used by add-ons', 'car-demon'),
			),
*/
		);
		$fields = apply_filters( 'cd_location_fields_filter', $fields );
		return $fields;
	}
}

function car_demon_default_location( $site_name = '' ) {
	if ( empty( $site_name ) ) {
		$site_name = get_bloginfo( 'name' );
	}
	$site_email = get_bloginfo( 'admin_email' );
	$site_url = get_bloginfo( 'wpurl' );

	$location['dealer_name'] = $site_name;
	$location['facebook_page'] = '';
	$location['new_sales_name'] = $site_name;
	$location['new_sales_number'] = '';
	$location['new_mobile_number'] = '';
	$location['new_mobile_provider'] = '';
	$location['new_sales_email'] = $site_email;
	$location['used_sales_name'] = $site_name;
	$location['used_sales_number'] = '';
	$location['used_mobile_number'] = '';
	$location['used_mobile_provider'] = '';
	$location['used_sales_email'] = $site_email;
	$location['default_description'] = __('Ready to go.', 'car-demon');
	$location['service_name'] = $site_name;
	$location['service_number'] = '';
	$location['service_email'] = $site_email;
	$location['parts_name'] = $site_name;
	$location['parts_number'] = '';
	$location['parts_email'] = $site_email;
	$location['finance_name'] = $site_name;
	$location['finance_number'] = '';
	$location['finance_email'] = $site_email;
	$location['finance_url'] = '';
	$location['finance_popup'] = '';
	$location['finance_width'] = '600';
	$location['finance_height'] = '800';
	$location['finance_disclaimer'] = get_default_finance_disclaimer();
	$location['finance_description'] = get_default_finance_description();
	$location['trade_name'] = $site_name;
	$location['trade_number'] = '';
	$location['trade_email'] = $site_email;
	$location['trade_url'] = '';
	$location['show_new_prices'] = 'Yes';
	$location['no_new_prices'] = __('Call for price', 'car-demon');
	$location['show_used_prices'] = 'Yes';
	$location['no_used_price'] = __('Call for price', 'car-demon');
	$location['new_large_photo_url'] = '';
	$location['new_small_photo_url'] = '';
	$location['used_large_photo_url'] = '';
	$location['used_small_photo_url'] = '';
	$location['catch_phrase'] = '';
	$location['address'] = '';
	$location['city'] = '';
	$location['state'] = '';
	$location['zip'] = '';
	$location['hours'] = '';
	$location['website'] = '';
	$location['overview'] = '';
	return $location;
}

function car_demon_import_location( $location, $dealer = '' ) {
	if ( empty( $dealer ) ) {
		// since this is the default location we want to use 'default' as the slug
		$location['dealer_name'] = 'Default';
	}
	$slug = $location['dealer_name'];
	$slug = trim( $slug );
	$slug = strtolower( $slug );
	$slug = str_replace( ' ', '-', $slug );

	$description = $location['default_description'];

	$term_exists = term_exists( $location['dealer_name'], 'vehicle_location' );

	if ( ! $term_exists ) {
		$location_id = wp_insert_term(
			$location['dealer_name'], // the term 
			'vehicle_location', // the taxonomy
			array(
				'description'=> $description,
				'slug' => $slug,
			)
		);
	}

	$data = $_POST;
	$location_data = array();
	foreach ($data as $key=>$value) {
		$key = str_replace($slug.'-', '', $key);
		$location_data[$slug][$key] = $value;
	}
	update_option('location_'.$slug, $location_data);

	update_option($slug.'_facebook_page', $location['facebook_page']);
	update_option($slug.'_new_sales_name', $location['new_sales_name']);
	update_option($slug.'_new_sales_number', $location['new_sales_number']);
	update_option($slug.'_new_mobile_number', $location['new_mobile_number']);
	update_option($slug.'_new_mobile_provider', $location['new_mobile_provider']);
	update_option($slug.'_new_sales_email', $location['new_sales_email']);
	update_option($slug.'_used_sales_name', $location['used_sales_name']);
	update_option($slug.'_used_sales_number', $location['used_sales_number']);
	update_option($slug.'_used_mobile_number', $location['used_mobile_number']);
	update_option($slug.'_used_mobile_provider', $location['used_mobile_provider']);
	update_option($slug.'_used_sales_email', $location['used_sales_email']);
	update_option($slug.'_default_description', $location['default_description']);
	update_option($slug.'_service_name', $location['service_name']);
	update_option($slug.'_service_number', $location['service_number']);
	update_option($slug.'_service_email', $location['service_email']);
	update_option($slug.'_parts_name', $location['parts_name']);
	update_option($slug.'_parts_number', $location['parts_number']);
	update_option($slug.'_parts_email', $location['parts_email']);
	update_option($slug.'_finance_name', $location['finance_name']);
	update_option($slug.'_finance_number', $location['finance_number']);
	update_option($slug.'_finance_email', $location['finance_email']);
	update_option($slug.'_finance_url', $location['finance_url']);
	update_option($slug.'_finance_popup', $location['finance_popup']);
	update_option($slug.'_finance_width', $location['finance_width']);
	update_option($slug.'_finance_disclaimer', $location['finance_disclaimer']);
	update_option($slug.'_finance_description', $location['finance_description']);
	
	update_option($slug.'_trade_name', $location['trade_name']);
	update_option($slug.'_trade_number', $location['trade_number']);
	update_option($slug.'_trade_email', $location['trade_email']);
	update_option($slug.'_trade_url', $location['trade_url']);
	update_option($slug.'_show_new_prices', $location['show_new_prices']);
	update_option($slug.'_no_new_price', $location['no_new_prices']);
	update_option($slug.'_show_used_prices', $location['show_used_prices']);
	update_option($slug.'_no_used_price', $location['no_used_price']);
	update_option($slug.'_new_large_photo_url', $location['new_large_photo_url']);
	update_option($slug.'_new_small_photo_url', $location['new_small_photo_url']); 
	update_option($slug.'_used_large_photo_url', $location['used_large_photo_url']);
	update_option($slug.'_used_small_photo_url', $location['used_small_photo_url']);

	update_option($slug.'_catch_phrase', $location['catch_phrase']);
	update_option($slug.'_address', $location['address']);
	update_option($slug.'_city', $location['city']);
	update_option($slug.'_state', $location['state']);
	update_option($slug.'_zip', $location['zip']);
	update_option($slug.'_hours', $location['hours']);
	update_option($slug.'_website', $location['website']);
	update_option($slug.'_overview', $location['overview']);

	return $location;
}
?>