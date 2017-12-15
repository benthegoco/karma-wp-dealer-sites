<?php
function cdsf_pro_search_shortcode_func( $atts ) {
	$atts = shortcode_atts( array(
		'title' => __('Search', 'car-demon-search'),
		'custom_class' => 'cd_closed',
		'hide_body_style' => false,
		'hide_condition' => false,
		'hide_year' => false,
		'hide_year_range' => false,
		'hide_make' => false,
		'hide_model' => false,
		'hide_price' => false,
		'hide_mileage' => false,
		'hide_location' => false,
		'value_body_style' => '',
		'value_condition' => '',
		'value_year' => '',
		'value_make' => '',
		'value_model' => '',
		'value_price' => '',
		'value_mileage' => '',
		'value_location' => '',
		'label_location' => __('Location', 'car-demon-search'),
		'label_condition' => __('Condition', 'car-demon-search'),
		'label_make' => __('Make', 'car-demon-search'),
		'label_model' => __('Model', 'car-demon-search'),
		'label_year' => __('Year Range', 'car-demon-search'),
		'label_price' => __('Price Range', 'car-demon-search'),
		'label_mileage' => __('Mileage', 'car-demon-search'),
		'label_body_style' => __('Body Style', 'car-demon-search'),
		'label_button' => __('Find Your Car', 'car-demon-search'),
		'label_reset' => __('Reset Filters', 'car-demon-search'),
		'form_action' => get_option('siteurl'),
		'style' => '1'
	), $atts, 'pro_search' );

	// setup the setting to send to cdsf_advanced_search_form()
	$settings = array();
	$settings['hide'] = array();

	if ($atts['hide_location'] == 'on') {
		array_push($settings['hide'], 'location');
	}
	if ($atts['hide_condition'] == 'on') {
		array_push($settings['hide'], 'condition');
	}
	if ($atts['hide_year'] == 'on') {
		array_push($settings['hide'], 'year');
	}
	if ($atts['hide_year_range'] == 'on') {
		array_push($settings['hide'], 'year_range');
	}
	if ($atts['hide_make'] == 'on') {
		array_push($settings['hide'], 'make');
	}
	if ($atts['hide_model'] == 'on') {
		array_push($settings['hide'], 'model');
	}
	if ($atts['hide_price'] == 'on') {
		array_push($settings['hide'], 'price');
	}
	if ($atts['hide_mileage'] == 'on') {
		array_push($settings['hide'], 'mileage');
	}
	if ($atts['hide_body_style'] == 'on') {
		array_push($settings['hide'], 'body_style');
	}

	$settings['value'] = array();

	$settings['value']['location'] = $atts['value_location'];
	$settings['value']['condition'] = $atts['value_condition'];
	$settings['value']['make'] = $atts['value_make'];
	$settings['value']['model'] = $atts['value_model'];
	$settings['value']['year'] = $atts['value_year'];
	$settings['value']['price'] = $atts['value_price'];
	$settings['value']['mileage'] = $atts['value_mileage'];
	$settings['value']['body_style'] = $atts['value_body_style'];

	$settings['custom_class'] = $atts['custom_class'];
	$settings['form_action'] = $atts['form_action'];

	$settings['label']['location'] = $atts['label_location'];
	$settings['label']['condition'] = $atts['label_condition'];
	$settings['label']['make'] = $atts['label_make'];
	$settings['label']['model'] = $atts['label_model'];
	$settings['label']['year'] = $atts['label_year'];
	$settings['label']['price'] = $atts['label_price'];
	$settings['label']['mileage'] = $atts['label_mileage'];
	$settings['label']['body_style'] = $atts['label_body_style'];
	$settings['label']['button'] = $atts['label_button'];
	$settings['label']['reset'] = $atts['label_reset'];

	$settings['style'] = $atts['style'];

	$x = cdsf_advanced_search_form($settings);
	
	return $x;
}
add_shortcode( 'pro_search', 'cdsf_pro_search_shortcode_func' );
?>
