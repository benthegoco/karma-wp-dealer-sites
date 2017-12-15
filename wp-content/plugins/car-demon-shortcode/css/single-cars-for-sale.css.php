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

$theme_color_background = 'FFF';//b00
$theme_color = 'b00';//b00
$theme_color_highlight = 'fff';//00b
$theme_color_shadow = '777';//777
$theme_color_button = '0000aa';
$theme_color_button_hover = '00b';
$theme_color_button_shadow = '999';
$cdsp_template_options = array();
$cdsp_template_options = get_option( 'cdsp_template_options' );
if (!empty($cdsp_template_options['theme_color_background'])) { $theme_color_background = $cdsp_template_options['theme_color_background']; }
if (!empty($cdsp_template_options['theme_color'])) { $theme_color = $cdsp_template_options['theme_color']; }
if (!empty($cdsp_template_options['theme_color_highlight'])) { $theme_color_highlight = $cdsp_template_options['theme_color_highlight']; }
if (!empty($cdsp_template_options['theme_color_shadow'])) { $theme_color_shadow = $cdsp_template_options['theme_color_shadow']; }
if (!empty($cdsp_template_options['theme_color_button'])) { $theme_color_button = $cdsp_template_options['theme_color_button']; }
if (!empty($cdsp_template_options['theme_color_button_hover'])) { $theme_color_button_hover = $cdsp_template_options['theme_color_button_hover']; }
if (!empty($cdsp_template_options['theme_color_button_shadow'])) { $theme_color_button_shadow = $cdsp_template_options['theme_color_button_shadow']; }
$cdsp_template_path = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
$cdsp_template_path = str_replace('css','',$cdsp_template_path);
?>
/* CSS Document */
/* #### Mobile Phones Portrait or Landscape #### */
@media screen and (max-width: 479px){
  /* some CSS here */
	<?php include('widths/single-cars-for-sale/small.php'); ?>
}
/* #### Tablet Portrait or Landscape #### */
@media screen and (min-width: 480px) and (max-width: 639px){
  /* some CSS here */
	<?php include('widths/single-cars-for-sale/480.php'); ?>
}
/* #### Tablet Landscape #### */
@media screen and (min-width: 640px) and (max-width: 759px){
  /* some CSS here */
	<?php include('widths/single-cars-for-sale/640.php'); ?>
}
/* #### Tablets Portrait or Landscape #### */
@media screen and (min-width: 760px) and (max-width: 900px){
  /* some CSS here */
	<?php include('widths/single-cars-for-sale/760.php'); ?>
}
/* #### Desktops #### */
@media screen and (min-width: 900px) and (max-width: 1100px){
  /* some CSS here */
	<?php include('widths/single-cars-for-sale/1000.php'); ?>
}
@media screen and (min-width: 1100px){
  /* some CSS here */
	<?php include('widths/single-cars-for-sale/1200.php'); ?>
}
/*
Styles for All Widths
*/
<?php // include('single-cars-for-sale.css'); ?>
<?php include('widths/single-cars-for-sale/all.php'); ?>
.email_friend_div {
	display: none;
}
ul.tabs li a.active {
    border: none !important;
    color: #464c54 !important;
	background-color: #d5d5d5 !important;
}
ul.tabs li a.active:hover {
	background-color: #<?php echo $theme_color_highlight; ?> !important;
}
ul.tabs li a {
    border: none !important;
    color: #fff !important;
    font-size: 12px !important;
	background-color: #<?php echo $theme_color; ?> !important;
}
ul.tabs li a:hover {
	background-color: #<?php echo $theme_color_button_hover; ?> !important;
}
.single-car-details ul {
	margin-left: 0px;
}
.cdsp_thumbs_box {
	position: relative;
}
.other_great_deals_title {
    line-height:40px;
    font: 20px/26px 'NobileRegular', Verdana, Geneva, sans-serif;
    font-family: NobileRegular, Verdana, Geneva, sans-serif;
    font-size: 26px;
    padding-top: 15px;
    padding-bottom: 15px;
}
.single-car-details label {
	display: inline;
}
#demon-container {
	background: #fff;
}
.single-car-title {
	display: none;
}
#content_7 {
	display: none;
}
/*
Lightbox
*/
.car_demon_photo_box {
	display: none;
	background: #DDD;
	width: 800px;
	height: 600px;
	padding: 9px;
	border: 3px solid gray;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	-moz-box-shadow: 0 0 5px rgba(0,0,0, .3);
	-webkit-box-shadow: 0 0 5px 
	 rgba(0, 0, 0, .3);
	box-shadow: 0 0 5px 
	 rgba(0, 0, 0, .3);
}
.close_light_box {
	position: absolute;
	margin-left: 720px;
	color: #aa0000;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	cursor: pointer;
}
.car_demon_light_box_main_email {
	margin-left: 80px;
	margin-top: 25px;
}
.car_demon_light_box_main {
	margin-left: 80px;
	margin-top: 25px;
}
.run_slideshow_div {
	position: absolute;
	color: #CCCCCC;
	font-weight: bold;
	top: 12px;
	left: 615px;
}
.photo_next {
	cursor: pointer;
	position: absolute;
	top: 465px;
	left: 715px;
	cursor: pointer;
}
.photo_prev {
	cursor: pointer;
	position: absolute;
	top: 465px;
	left: 55px;
}
.hor_lightbox {
	width: 600px;
	height: 102px;
	overflow: scroll;
	white-space: nowrap;
	margin-left: 100px;
}
.car_demon_light_box_main {
	margin-left: 80px;
	margin-top: 25px;
}
.run_slideshow_div {
	position: absolute;
	color: #CCCCCC;
	font-weight: bold;
	top: 12px;
	left: 615px;
}
.single-car-for-sale, .single-car-for-sale div, .single-car-for-sale ul, .single-car-for-sale li {
	box-sizing: content-box;
}
.single-car-for-sale li:before {
	content: none;
	text-decoration: none;
    padding: 0px;
    margin: 0px;
}
.single-car-for-sale ul {
	padding: 0px;
}
.single-car-for-sale ul li {
	text-decoration: none;
    padding: 0px;
    margin: 0px;
}