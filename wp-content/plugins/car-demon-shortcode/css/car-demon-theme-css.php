<?php
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
} else {
	$is_it_iis = 'Win';
}
if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon-shortcode/css', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
} else {
	$newPath = str_replace('wp-content\plugins\car-demon-shortcode\css', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
if ( defined( 'CDSP_DYNAMIC_CSS' ) ) {
	ob_start ("ob_gzhandler");
	header("Content-type: text/css; charset: UTF-8");
	header("Cache-Control: must-revalidate");
	$offset = 60 * 60 ;
	$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
	header($ExpStr);
}
$theme_color_background = 'E0E0E0';
$theme_color = '3254C2';
$theme_color_highlight = 'ffffff';
$theme_color_shadow = '777777';
$theme_color_button = '0000aa';
$theme_color_button_hover = '0000bb';
$theme_color_button_shadow = '999999';
$theme_border_top_color = '0000bb';
$theme_border_bottom_color = 'D6D6D6';
$theme_border_left_color = 'D6D6D6';
$theme_border_right_color = 'D6D6D6';
$theme_border_top_width = '4';
$theme_border_bottom_width = '1';
$theme_border_left_width = '1';
$theme_border_right_width = '1';
$cdsp_template_options = array();
$cdsp_template_options = get_option( 'cdsp_template_options', $cdsp_template_options );
if (!empty($cdsp_template_options['theme_color_background'])) { $theme_color_background = $cdsp_template_options['theme_color_background']; }
if (!empty($cdsp_template_options['theme_color'])) { $theme_color = $cdsp_template_options['theme_color']; }
if (!empty($cdsp_template_options['theme_color_highlight'])) { $theme_color_highlight = $cdsp_template_options['theme_color_highlight']; }
if (!empty($cdsp_template_options['theme_color_shadow'])) { $theme_color_shadow = $cdsp_template_options['theme_color_shadow']; }
if (!empty($cdsp_template_options['theme_color_button'])) { $theme_color_button = $cdsp_template_options['theme_color_button']; }
if (!empty($cdsp_template_options['theme_color_button_hover'])) { $theme_color_button_hover = $cdsp_template_options['theme_color_button_hover']; }
if (!empty($cdsp_template_options['theme_color_button_shadow'])) { $theme_color_button_shadow = $cdsp_template_options['theme_color_button_shadow']; }
if (!empty($cdsp_template_options['theme_border_top_color'])) { $theme_border_top_color = $cdsp_template_options['theme_border_top_color']; }
if (!empty($cdsp_template_options['theme_border_bottom_color'])) { $theme_border_bottom_color = $cdsp_template_options['theme_border_bottom_color']; }
if (!empty($cdsp_template_options['theme_border_left_color'])) { $theme_border_left_color = $cdsp_template_options['theme_border_left_color']; }
if (!empty($cdsp_template_options['theme_border_right_color'])) { $theme_border_right_color = $cdsp_template_options['theme_border_right_color']; }
if (!empty($cdsp_template_options['theme_border_top_width'])) { $theme_border_top_width = $cdsp_template_options['theme_border_top_width']; }
if (!empty($cdsp_template_options['theme_border_bottom_width'])) { $theme_border_bottom_width = $cdsp_template_options['theme_border_bottom_width']; }
if (!empty($cdsp_template_options['theme_border_left_width'])) { $theme_border_left_width = $cdsp_template_options['theme_border_left_width']; }
if (!empty($cdsp_template_options['theme_border_right_width'])) { $theme_border_right_width = $cdsp_template_options['theme_border_right_width']; }
$cdsp_theme_path = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
$cdsp_theme_path = str_replace('css','',$cdsp_theme_path);
/*
	background: url(<?php echo $cdsp_theme_path; ?>images/bck.png) repeat-x 0 0;
	background-color:#<?php echo $theme_color; ?>;
	240x320
*/
?>
/* default style */
.selectnav { display: none; }
/* Pro Car Item Colors */
.car_item_pro {
    background-color: #<?php echo $theme_color_background; ?>;
	/*border: 1px solid #D6D6D6;*/
	border-top: <?php echo $theme_border_top_width; ?>px solid #<?php echo $theme_border_top_color; ?>;
    border-bottom: <?php echo $theme_border_bottom_width; ?>px solid #<?php echo $theme_border_bottom_color; ?>;
    border-left: <?php echo $theme_border_left_width; ?>px solid #<?php echo $theme_border_left_color; ?>;
    border-right: <?php echo $theme_border_right_width; ?>px solid #<?php echo $theme_border_right_color; ?>;
}
.car_title_pro {
	color: #<?php echo $theme_color; ?>;
}
.car_title_pro a {
	color: #<?php echo $theme_color; ?> !important;
    text-decoration: none;
}
.cd_5 .car_email {
	text-align: center;
	background: #<?php echo $theme_color_button; ?>;
}
.cd_5 .car_email:hover {
	background: #<?php echo $theme_color_button_hover; ?>;
}
.cd_6 .car_butons_row span:hover {
	background: #<?php echo $theme_color_button_hover; ?>;
}
.cd_6 .car_butons_row {
	background-color: #<?php echo $theme_color; ?>;
}
#demon-container {
    margin-left: auto;
    margin-right: auto;
    display: block;
    clear: both;
    float: left;
}
.search_car_box_frame_wide {
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    max-width: 800px;
}

.search_btn {
    height: 46px;
    margin-left: auto;
    margin-right: auto;
    display: block;
    font-size: 18px;
}

/* small screen */
/*
Car Demon Pro Styles
*/
/* #### Mobile Phones Portrait or Landscape #### */
@media screen and (max-width: 479px){
  /* some CSS here */
	<?php include('widths/small.php'); ?>
}
/* #### Tablet Portrait or Landscape #### */
@media screen and (min-width: 480px) and (max-width: 639px){
  /* some CSS here */
	<?php include('widths/480.php'); ?>
}
/* #### Tablet Landscape #### */
@media screen and (min-width: 640px) and (max-width: 759px){
  /* some CSS here */
	<?php include('widths/640.php'); ?>
}
/* #### Tablets Portrait or Landscape #### */
@media screen and (min-width: 760px) and (max-width: 1000px){
  /* some CSS here */
	<?php include('widths/760.php'); ?>
}
/* #### Mobile Phones Portrait or Landscape #### */
@media screen and (max-width: 701px){
	<?php include('widths/700max.php'); ?>
}
/* #### Mobile Phones Portrait or Landscape #### */
@media screen and (min-width: 701px) and (max-width: 790px){
	/* some CSS here */
	<?php include('widths/700_800.php'); ?>
}
/* #### Desktops #### */
@media screen and (min-width: 1000px){
  /* some CSS here */
	<?php include('widths/1000.php'); ?>
}
//==Styles for all Width Devices
/* some CSS here */
	<?php include('widths/all.php'); ?>
	
.ias_loader {
	float: left;
}
.search_car_box .hidden_select_container {
	display: inline;
}
.simpleselect {
	display: none;
}
.search_car_box select {
	height: 26px !important;
    padding: 0px;
}
.cd_order_by, .cd_order_by_dir {
    float: left;
    height: 28px;
}
.cd_sort_by_label {
	display: block;
}
.vehicle_price {
	font-size: 14px !important;
}
#cdsp_compare p a {
	color: #fff;
}
.searched_by {
	width:600px;
    min-height:20px;
    margin-left:15px;
    float:left;
}
.remove_search {
 	color:#FF0000;
	font-weight:bold;
	cursor:pointer;
	margin-left:4px;
}
.remove_search_title {
	font-weight:bold;
}
.custom_btn {
    width: 90%;
    padding: 10px;
    text-align: center;
	background-color: #<?php echo $theme_color; ?>;
    color: #fff;
    font-size: 1.5em;
}
.custom_btn a {
	list-style: none;
    color: #fff;
}
.custom_btn:hover {
	background-color: #<?php echo $theme_color_highlight; ?>;
    color: #000;
}
/*
Generic Car Item
*/
@media only screen and (min-width : 768px) {
	.car_item {
		clear: both;
		float: left;
		font: 13px/1.231 arial,helvetica,clean,sans-serif;
		padding:1%;
		background-color: white;
		margin: 15px;
		position: relative;
		max-width: 1100px;
	}
	.car_item .main_photo {
		float: left;
		height: auto;
		width: 20%;
		text-align:center;
		margin: 2px;
	}
	.car_item .photo_thumb {
		width:110px;
		height:83px;
	}
	.car_item .description {
		float: left;
		width: 55%;
		font-size:85%;
	}
	.car_item .car_title {
		font-size:125%;
		font-weight:bold;
		color:#006699;
		float:left;
		width:50%;
	}
	.car_item .description_left{
		float: left;
		width: 50%;
	}
	.car_item .description_right{
		float: left;
		width: 50%;
	}
	.car_item .description_label {
		float: left;
		width: 50%;
	}
	.car_item .description_text {
		float: left;
		width: 50%;
	}
	.car_item .price {
		float: left;
		width: 23%;
		padding-left:2%;
	}
	.car_item .price_label {
		width: 50%;
		font-size:85%;
		color:#555;
		float:left;
	}
	.car_item .price_value {
		width: 50%;
		font-size:95%;
		color:#222;
		float:left;
	}
	.car_item .price_line {
		float:left;
		width:100%;
		border-bottom:solid;
		border-bottom-color:#CCCCCC;
		height:1px;
		line-height:1px;
	}
	.car_item .final_price_label {
		width: 70%;
		font-size:100%;
		font-weight:bold;
		color:#050;
		float:left;
	}
	.car_item .final_price_value {
		width: 70%;
		font-size:120%;
		font-weight:bold;
		color:#96262B;
		float:right;
		text-align:right;
	}
}
@media only screen and (max-width : 767px) {
	.car_item .compare {
		display:none;
	}
	.car_item .cd_cdrf_compare {
		display:none !important;
	}
	.car_item .car_item {
		float: left;
		font: 13px/1.231 arial,helvetica,clean,sans-serif;
		padding:1%;
		background-color: white;
		margin: 15px;
		position: relative;
		width: 210px;
	}
	.car_item .main_photo {
		height: auto;
		text-align:center;
		margin: 2px;
	}
	.car_item .photo_thumb {
		width:100%;
		height:auto;
	}
	.car_item .description {
		float: left;
		width: 100%;
		font-size:85%;
	}
	.car_item .car_title {
		font-size:125%;
		font-weight:bold;
		color:#006699;
		float:left;
		width:100%;
	}
	.car_item .description_left{
		float: left;
		width: 100%;
	}
	.car_item .description_right{
		float: left;
		width: 100%;
	}
	.car_item .description_label {
		float: left;
		width: 48%;
	}
	.car_item .description_text {
		float: left;
		width: 48%;
	}
	.car_item .price {
		float: left;
		width: 100%;
		padding-left:2%;
	}
	.car_item .price_label {
		width: 50%;
		font-size:85%;
		color:#555;
		float:left;
	}
	.car_item .price_value {
		width: 100%;
		font-size:95%;
		color:#222;
		float:left;
	}
	.car_item .price_line {
		float:left;
		width:100%;
		border-bottom:solid;
		border-bottom-color:#CCCCCC;
		height:1px;
		line-height:1px;
	}
	.car_item .final_price_label {
		width: 100%;
		font-size:100%;
		font-weight:bold;
		color:#050;
		float:left;
	}
	.car_item .final_price_value {
		width: 100%;
		font-size:120%;
		font-weight:bold;
		color:#96262B;
		float:right;
		text-align:right;
	}
}
.car_item {
	background: #F9F9F9;
	border: 1px solid #D6D6D6;
	display: block;
	padding: 5px;
}
.car_item .car_title {
	display: none;
}
.car_item .inventory_ribbon {
    margin-left: 0px;
}
.car_item .car_title {
	display: block;
	margin-top: 2px;
}
.car_item .main_photo {
	margin: 5px;
}
#cd-nav-above {
	width: 100%;
}
.cdsc_clear {
    clear: both;
}
.cdsp_srp_btns {
    text-align: center;
}
.cdsp_srp_btns a img {
	max-width: 75px;
}
.cdsp_float_left {
	clear: both;
	float: left;
}