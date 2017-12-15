<?php
if(is_admin()) {
	add_filter('cdsp_settings_update_hook', 'cdsp_shortcode_admin_update_filter',10,2);
	add_filter('cdsp_settings_hook', 'cdsp_shortcode_admin_filter',10,2);
}
function cdsp_shortcode_admin_filter($holder, $current_location) {
	cdsp_shortcode_admin_update_filter();
	global $car_demon_options;
	$x = '<fieldset class="cdsp_admin_group">';
		$x .= '<legend>';
			$x .= __('+ Search Results Page','car-demon-shortcode');
		$x .= '</legend>';
		$x .= '<p class="cdsp_option_group">';
			$x .= __('Select the default page for your search results:','car-demon-shortcode').'<br />';
			if (!isset($car_demon_options['inventory_page'])) {
				$car_demon_options['inventory_page'] = get_bloginfo('wpurl');
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
			$x .= wp_dropdown_pages($args);
			$x .= '<br />';
			$x .= __('Point the search result page to the page with your inventory shortcode [cdsp_inventory].','car-demon-shortcode');
			$x .= '<br />';
			$x .= __('This will change the default search results page for all search forms.','car-demon-shortcode');
			$x .= '<br />';
			$x .= __('You can override this by setting it in the search form widget or shortcode.','car-demon-shortcode');
		$x .= '</p>';
	$x .= '</fieldset>';
	echo $x;
}
function cdsp_shortcode_admin_update_filter() {
	if (isset($_POST['inventory_page'])) {
		$car_demon_options = get_option( 'car_demon_options' );
		$inventory_page_id = sanitize_text_field($_POST['inventory_page']);
		if ( FALSE === get_post_status( $inventory_page_id ) ) {
		  // The post does not exist
	  		$car_demon_options['inventory_page'] = get_bloginfo('wpurl');
		} else {
		  // The post exists
			$inventory_page = get_permalink($inventory_page_id);
			$car_demon_options['inventory_page'] = $inventory_page;
		}		
		update_option( 'car_demon_options', $car_demon_options );
	}
}
?>