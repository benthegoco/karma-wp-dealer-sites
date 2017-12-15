<?php
function cds_color_styles() {
	$color_background = 'E0E0E0';
	$color = '3254C2';
	$color_highlight = 'ffffff';
	$color_shadow = '777777';
	$color_button = '0000aa';
	$color_button_hover = '0000bb';
	$color_button_shadow = '999999';
	$border_top_color = '0000bb';
	$border_bottom_color = 'D6D6D6';
	$border_left_color = 'D6D6D6';
	$border_right_color = 'D6D6D6';
	$border_top_width = '4';
	$border_bottom_width = '1';
	$border_left_width = '1';
	$border_right_width = '1';
	
	$cds_options = cdsp_template_options();

	if (!empty($cds_options['theme_color_background'])) { $color_background = $cds_options['theme_color_background']; }
	if (!empty($cds_options['theme_color'])) { $color = $cds_options['theme_color']; }
	if (!empty($cds_options['theme_color_highlight'])) { $color_highlight = $cds_options['theme_color_highlight']; }
	if (!empty($cds_options['theme_color_shadow'])) { $color_shadow = $cds_options['theme_color_shadow']; }
	if (!empty($cds_options['theme_color_button'])) { $color_button = $cds_options['theme_color_button']; }
	if (!empty($cds_options['theme_color_button_hover'])) { $color_button_hover = $cds_options['theme_color_button_hover']; }
	if (!empty($cds_options['theme_color_button_shadow'])) { $color_button_shadow = $cds_options['theme_color_button_shadow']; }
	if (!empty($cds_options['theme_border_top_color'])) { $border_top_color = $cds_options['theme_border_top_color']; }
	if (!empty($cds_options['theme_border_bottom_color'])) { $border_bottom_color = $cds_options['theme_border_bottom_color']; }
	if (!empty($cds_options['theme_border_left_color'])) { $border_left_color = $cds_options['theme_border_left_color']; }
	if (!empty($cds_options['theme_border_right_color'])) { $border_right_color = $cds_options['theme_border_right_color']; }
	if (!empty($cds_options['theme_border_top_width'])) { $border_top_width = $cds_options['theme_border_top_width']; }
	if (!empty($cds_options['theme_border_bottom_width'])) { $border_bottom_width = $cds_options['theme_border_bottom_width']; }
	if (!empty($cds_options['theme_border_left_width'])) { $border_left_width = $cds_options['theme_border_left_width']; }
	if (!empty($cds_options['theme_border_right_width'])) { $border_right_width = $cds_options['theme_border_right_width']; }
	
	$css = '
	.cd_single_car_2 .title_bar {
		background: ' . cds_hexToRgb( $color, .75 ) . '
	}
	
	.cd_single_car_2 .cd_single_item_title_box {
		background: ' . cds_hexToRgb( $color, .75 ) . '
	}
	
	.cd_single_car_2 .cd_tooltip {
		background: ' . cds_hexToRgb( $color, .75 ) . '
	}
	
	.cd_single_car_2 .cd_tooltip:after {
		border-color: rgba(251, 251, 187, 0);
		border-bottom-color: rgba(100, 100, 255, 0.75);
	}
	
	.cd_single_car_2 .cd_tooltip:before {
		border-color: rgba(119, 119, 119, 0);
	}
	
	.cd_single_car_2 .contact_us_widget_btn, .cd_single_car_2 .email_friend_btn {
		background-color: ' . cds_hexToRgb( $color_button, .75 ) . '
	}
	
	.cd_single_car_2 .contact_us_widget_btn:hover, .cd_single_car_2 .email_friend_btn:hover {
		background-color: ' . cds_hexToRgb( $color_button_hover, .75 ) . '
	}
	
	.cd_single_car_3 .cd_tooltip:after {
		border-color: rgba(251, 251, 187, 0);
		border-bottom-color: rgba(100, 100, 255, 0.75);
	}
	
	.cd_single_car_3 .cd_tooltip:before {
		border-color: rgba(119, 119, 119, 0);
		border-bottom-color: #777777;
	}
	
	.cd_single_car_3 .contact_us_widget_btn, .cd_single_car_3 .email_friend_btn {
		background-color: ' . cds_hexToRgb( $color_button, .75 ) . '
	}
	
	.cd_single_car_3 .contact_us_widget_btn:hover, .cd_single_car_3 .email_friend_btn:hover {
		background-color: ' . cds_hexToRgb( $color_button_hover, .75 ) . '
	}';

   return $css;
}

function cds_hexToRgb($hex, $alpha = false) {
   $hex      = str_replace('#', '', $hex);
   $length   = strlen($hex);
   $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
   $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
   $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
   if ( $alpha ) {
      $rgb['a'] = $alpha;
   }
	return implode(array_keys($rgb)) . '(' . implode(', ', $rgb) . ')';
}
?>