<?php
add_filter('car_demon_admin_hook', 'cdsp_admin_filter',10,2);
function cdsp_admin_filter($holder, $current_location) {
	$x = '
		<tr class="form-field">
			<th valign="top" scope="row">
				<label for="'. $current_location .'_warranty_url">' . __ ( 'Warranty URL', 'car-demon-shortcode' ) . '</label>
			</th>
			<td>
				<input type="text" value="'. get_option($current_location.'_warranty_url') .'" name="'. $current_location .'_warranty_url" id="'. $current_location .'_warranty_url" />
				<p class="description">Enter a URL to your Warrany page.</p>
			</td>
		</tr>
		';
	$x .= '
		<tr class="form-field">
			<th valign="top" scope="row">
				<label for="'. $current_location .'_make_offer_url">' . __ ( 'Make Offer URL', 'car-demon-shortcode' ) . '</label>
			</th>
			<td>
				<input type="text" value="'. get_option($current_location.'_make_offer_url') .'" name="'. $current_location .'_make_offer_url" id="'. $current_location .'_make_offer_url" />
				<p class="description">Enter a URL to your Make Offer page</p>
			</td>
		</tr>
		';
	$x .= '
		<tr class="form-field">
			<th valign="top" scope="row">
				<label for="'. $current_location .'_vehicle_contact_url">' . __ ( 'Vehicle Contact URL', 'car-demon-shortcode' ) . '</label>
			</th>
			<td>
				<input type="text" value="'. get_option($current_location.'_vehicle_contact_url') .'" name="'. $current_location .'_vehicle_contact_url" id="'. $current_location .'_vehicle_contact_url" />
				<p class="description">Enter a URL to your contact us page</p>
			</td>
		</tr>
		';

	echo $x;
}
add_filter('car_demon_admin_update_hook', 'cdsp_admin_update_filter',10,2);
function cdsp_admin_update_filter($holder, $current_location) {
	// Save Your information
	if (isset($_POST[$current_location.'_warranty_url'])) { update_option($current_location.'_warranty_url', sanitize_text_field($_POST[$current_location.'_warranty_url'])); }
	if (isset($_POST[$current_location.'_make_offer_url'])) { update_option($current_location.'_make_offer_url', sanitize_text_field($_POST[$current_location.'_make_offer_url'])); }
	if (isset($_POST[$current_location.'_vehicle_contact_url'])) { update_option($current_location.'_vehicle_contact_url', sanitize_text_field($_POST[$current_location.'_vehicle_contact_url'])); }
}
?>