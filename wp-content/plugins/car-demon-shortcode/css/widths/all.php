/* CSS Document */
body {
	background-color: #<?php echo $theme_color_background; ?>;
}
.footer_widget a:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
	color: #<?php echo $theme_color_highlight; ?>;
}
#menu-footer-menu a:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
	color: #<?php echo $theme_color_highlight; ?>;
}
#top_menu_bar ul .current_page_item a:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
}
.ad_widget_title {
	padding: 10px;
	padding-left: 15px;
	margin-left: 3px;
	margin-right: 3px;
	background-image:url(cds_images/widget_title_back.jpg);
	background-repeat: repeat-x;
	height: 59px;
	font-size: 24px;	
	color: #<?php echo $theme_color; ?>;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
}
#under_content {
	margin-left: 0px;
	margin-top: 0px;
	background-image: url(cds_images/under_content.jpg);
	overflow: hidden;
	display: inline;
	float: left;
	height: 60px;
	margin-bottom: 0;
	width: 100%;
}
#footer_widget_left {
	margin-left: 0px;
	margin-top: 0px;
	background-color:#<?php echo $theme_color_shadow; ?>;
	overflow: hidden;
	display: inline;
	float: left;
	height: 201px;
	margin-bottom: 0;
}
#footer_middle_widget {
	margin-left: 0px;
	margin-top: 0px;
	background-color:#<?php echo $theme_color_shadow; ?>;
	overflow: hidden;
	display: inline;
	float: left;
	height: 201px;
	margin-bottom: 0;
}
#footer_right_widget {
	margin-left: 0px;
	margin-top: 0px;
	background-color:#<?php echo $theme_color_shadow; ?>;
	overflow: hidden;
	display: inline;
	float: left;
	height: 201px;
	margin-bottom: 0;
}
#footer_menu li ul {
	margin-top: -10px;	
}
#footer_menu li ul li {
	list-style: none;
	float: left;
	padding: 3px;
    margin-top:4px;
}
#footer_menu li a {
	text-decoration:none;
	font-size: 10px;
	color: #<?php echo $theme_color_shadow; ?>;
	font-weight: bold;
	font-size: 10px;
	font-family: Verdana, Geneva, sans-serif;
}
/*
Search Widget
*/
.search_car_box_frame {
	width: 100%;
	height: 380px;
	font-size: 12px;
	background: #DCDCDC;
	clear: both;
}
.search_car_box {
	position: relative;
	width: 245px;
	height: 380px;
	margin: 10px;
	margin-top: 0px;
}
.search_car_box div {
	margin-top: 4px;
}
#car-demon-search-cars select {
	font-size: 14px;	
	height: auto;
	max-height: 34px;
}
.search_header_logo img {
    margin: 6px;
    margin-top: 0px;
    float: left;
}
.search_header_logo {
	color: #<?php echo $theme_color; ?>;
	font: 24px/24px 'TitilliumText22LRegular';
    font-size: 24px !important;
    font-weight: bold;
}
#car-demon-search-cars-pro input[type=submit] {
	/*background:url(cds_images/search_btn_bck.png);*/
	background-color: #<?php echo $theme_color; ?>;
	background-position: left;
	/*background-repeat: repeat-x;*/
    background-position: 10% 0%;
	color: #<?php echo $theme_color_highlight; ?>;
	width: 245px;
	/*height: 38px;*/
	margin-top: 18px;
	font-family:Verdana, Geneva, sans-serif;
	font-size: 14px;
	font-weight: bold;
	text-align: center;
	float: right;
	cursor: pointer;
}
.compare_btn {
	/*background:url(cds_images/search_btn_bck.png);*/
    border: #000 1px solid;
	background-color: #<?php echo $theme_color; ?>;
	background-position: left;
	background-repeat: repeat-x;
	color: #<?php echo $theme_color_highlight; ?>;
	width: 150px;
	height: 28px;
	margin-top: 18px;
	font-family:Verdana, Geneva, sans-serif;
	font-size: 14px;
	font-weight: bold;
	text-align: center;
	float: right;
	cursor: pointer;
}
.compare_btn:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
}
.car_demon_compare_box_list_cars_div .random {
    width: 185px;
    float: left;
}
.car_demon_compare_box {
	display:none;
	background:#FFFFFF;
	width:800px;
	height:600px;		
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
.car_demon_compare_widget :hover {
	right: 0;
}
.search_left {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: bold;
}
.search_right {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: bold;
}
.search_car_box_sm .search_body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: bold;
}
.search_btn {
	background: #<?php echo $theme_color_button; ?>;
    min-width: 100px;
    border: none;
    color: #fff;
    cursor: pointer;
    margin-left: 20px;
    float: left;
}
.search_btn:hover {
	background: #<?php echo $theme_color_button_hover; ?>;
}
.search_body .search_labels {
	width: 100%;
}
#car-demon-search-cars-pro input[type=submit]:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
}
ol.bjqs-markers li {
	background: #<?php echo $theme_color; ?>;
}
ol.bjqs-markers li:hover {
	background: #<?php echo $theme_color_button_hover; ?>;
}
ul.bjqs-controls.v-centered li a:hover {
	background:#000;
	color:#<?php echo $theme_color_highlight; ?>;
}
#key_0 a {
	-webkit-border-top-left-radius: 5px;
	-moz-border-radius-topleft: 5px;
	border-top-left-radius: 5px;
}
ol.bjqs-markers li a {
	padding:3px 10px;
//	background:#<?php echo $theme_color; ?>;
	background: #C4C4C4;
//	color:#<?php echo $theme_color_highlight; ?>;
	color: #555;
	text-decoration: none;
	border-bottom: solid;
	border-width: 1px;
	border-left-width: 0px;
//	border-color: #<?php echo $theme_color_shadow; ?>;
	border-color: #555;
    font-weight: bold;
	font-size: 12px;
}
ol.bjqs-markers li.active-marker a,
ol.bjqs-markers li a:hover{
//	background: #<?php echo $theme_color_button_hover; ?>;
	background: #EAEAEA;
    color: #c91344;
}
#cdsp_compare {
	color: #<?php echo $theme_color_highlight; ?>;
}
#cdsp_compare p {
	width: 195px;
}
.search_header {
	float: left;
	left: 0px;
	top: 0px;
	width: 239px;
	height: 10px;
	z-index: 1;
	visibility: visible;
}
.search_header_logo {
	float: left;
	left: 0px;
	top: 10px;
	width: 239px;
	height: 34px;
	z-index: 2;
	visibility: visible;
}
.search_icon {
	float: left;	
}
.search_title {
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
	color: #<?php echo $theme_color; ?>;
	margin: 4px;
	float: left;	
}
.advanced_search_btn {
	cursor: pointer;
	color: #00CC00;
	float: left;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
	margin-top: 4px;
	margin-left: 4px;
    display:none;
}
.advanced_search {
	display: none;
	width: 250px;
	float: left;
	min-height: 24px;
}
.search_labels {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;	
}
.search_left {
	float: left;
	width: 85px;
	height: 55px;
}
.search_right {
	float: left;
	width: 155px;
	height: 55px;
}
.search_min_price {
	float: left;
	left: 0px;
	top: 131px;
	width: 119px;
	height: 61px;
	z-index: 6;
	visibility: visible;
}
.search_max_price {
	float: left;
	left: 119px;
	top: 131px;
	width: 120px;
	height: 61px;
	z-index: 7;
	visibility: visible;
}
.search_trans {
	float: left;
	left: 0px;
	top: 182px;
	width: 119px;
	height: 61px;
	z-index: 8;
	visibility: visible;
}
.search_mileage {
	float: left;
	left: 119px;
	top: 182px;
	width: 120px;
	height: 61px;
	z-index: 9;
	visibility: visible;
}
.search_body {
	float: left;
	left: 0px;
	top: 233px;
	width: 119px;
	height: 60px;
	z-index: 10;
	visibility: visible;
}
.search_button_box {
	float: left;
	width: 100px;
	height: 60px;
	z-index: 11;
	visibility: visible;
}
.search_footer {
	float: left;
	left: 0px;
	top: 283px;
	width: 239px;
	z-index: 12;
	visibility: visible;
}
.search_labels {
	float: left;
	width: 85px;
}
.search_condition {
	width: 75px;
}
.search_year {
	width: 75px;
}
.search_make {
	width: 136px;
}
.search_model {
	width: 136px;
}
/*
Slide Show
*/
#slider_title_container {
	margin-top: 22px;
	padding-bottom: 8px;
	padding-left: 34px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 26px;
	margin-bottom: 0;
	width: 100%;
}
#slider_title {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;
	font-weight: bold;
	color: #<?php echo $theme_color; ?>;
    padding-top: 8px;
}
#slider_nav {
	font-weight: bold;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	color: #555;
}
#slider_1 {
	margin-left: 20px;
	margin-top: 2px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 17px;
	margin-bottom: 0;
	width: 24px;
	background-color:#ddd;
	text-align: center;
	padding-top:2px;
	color: #<?php echo $theme_color; ?>;
	cursor: pointer;
	-webkit-border-top-left-radius: 4px;
	-moz-border-radius-topleft: 4px;
	border-top-left-radius: 4px;
}
#slider_1:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
	color:#<?php echo $theme_color_highlight; ?>;
}
#slider_2 {
	margin-left: 0px;
	margin-top: 2px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 17px;
	margin-bottom: 0;
	width: 24px;
	background-color:#aaa;
	text-align: center;
	padding-top:2px;
	cursor: pointer;
}
#slider_2:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
	color:#<?php echo $theme_color_highlight; ?>;
}
#slider_3 {
	margin-left: 0px;
	margin-top: 2px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 17px;
	margin-bottom: 0;
	width: 24px;
	background-color:#aaa;
	text-align: center;
	padding-top:2px;
	cursor: pointer;
}
#slider_3:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
	color:#<?php echo $theme_color_highlight; ?>;
}
#slider_4 {
	margin-left: 0px;
	margin-top: 2px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 17px;
	margin-bottom: 0;
	width: 24px;
	background-color:#aaa;
	text-align: center;
	padding-top:2px;
	cursor: pointer;
	-webkit-border-top-right-radius: 4px;
	-moz-border-radius-topright: 4px;
	border-top-right-radius: 4px;
}
#slider_4:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
	color:#<?php echo $theme_color_highlight; ?>;
}
#slider_view_details {
	margin-left: 613px;
	margin-top: 131px;
	background-position: right;
	background-repeat: no-repeat;
	background-color:#000;
	overflow: hidden;
	float: left;
	height: 24px;
	width: 74px;
	margin-bottom: 0;
	color: #<?php echo $theme_color_highlight; ?>;
    -moz-box-shadow: 0 0 5px #fff;
    -webkit-box-shadow: 0 0 5px #fff;
    box-shadow: 0px 0px 5px #fff;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #fff;
	display:inline-block;
	color:#e6e6e6;
	font-family:Tahoma, Geneva, sans-serif;
	font-size:10px;
	font-weight:bold;
	padding-left: 8px;
	padding-top: 6px;
	padding-right: 4px;
	text-decoration:none;
	text-shadow:1px 1px 0px #0f000f;
	cursor: pointer;
}
#slider_view_details:hover {
	/*background-color:#<?php echo $theme_color_button_hover; ?>;*/
    background-color:#C4C4C4;
    color: #333;
    text-shadow: 1px 1px 0px #aaa;
}
#slider_view_details:active {
	position:relative;
	top:1px;
}
/*
Vehicle Widget Details
*/
.vehicle_title_lg {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 57px;
	margin-bottom: 0;
	width: 255px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: bold;
	color: #<?php echo $theme_color_shadow; ?>;
	margin-left: 6px;
}
.vehicle_widget .vehicle_title {
	width: 247px !important;
    font: 18px/18px 'TitilliumText22LRegular';
    font-weight: bold;
}
.vehicle_compare_lg {
	margin-left: 0px;
	margin-top: 3px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 37px;
	margin-bottom: 0;
	width: 75px;
	text-align: center;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #<?php echo $theme_color_shadow; ?>;
}
.vehicle_compare_lg input {
	border: solid;
	border-width: 1px;
	border-color: #<?php echo $theme_color; ?>;
}
.vehicle_compare {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 37px;
	margin-bottom: 0;
	width: 75px;
	text-align: center;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #<?php echo $theme_color_shadow; ?>;
}
.vehicle_compare input {
	border: solid;
	border-width: 1px;
	border-color: #<?php echo $theme_color; ?>;
}
.vehicle_stock_box {
	margin-left: 19px;
	margin-top: 13px;
	background-color: #<?php echo $theme_color_highlight; ?>;
	background-image: url(cds_images/vehicle_stock.png);
	background-repeat: no-repeat;
	overflow: hidden;
	display: inline;
	float: left;
	height: 54px;
	margin-bottom: 0;
	width: 130px;
}
.vehicle_model {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: bold;
	color: #<?php echo $theme_color; ?>;
	margin-left: 20px;
}
.vehicle_stock {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #<?php echo $theme_color_shadow; ?>;
	margin-left: 20px;
	background-color: #<?php echo $theme_color_highlight; ?>;
}
.vehicle_stock_box_lg {
	margin-left: 19px;
	margin-top: 13px;
	background-color: #<?php echo $theme_color_highlight; ?>;
	background-image: url(cds_images/vehicle_stock.png);
	background-repeat: no-repeat;
	overflow: hidden;
	display: inline;
	float: left;
	height: 54px;
	margin-bottom: 0;
	width: 130px;
    background-position-y: -10px;
}
.vehicle_model_lg {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: bold;
	color: #<?php echo $theme_color; ?>;
	margin-left: 20px;
}
.vehicle_stock_lg {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #<?php echo $theme_color_shadow; ?>;
	margin-left: 20px;
}
.vehicle_price_box_lg {
	margin-left: 0px;
	margin-top: 12px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 55px;
	margin-bottom: 0;
	width: 150px;
}
.vehicle_price_label_lg {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #<?php echo $theme_color_shadow; ?>;
	margin-left: 20px;
	text-align: center;
}
.vehicle_price_lg {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;
	font-weight: bold;
	color: #0a0;
	margin-left: 20px;
	text-align: center;
}
.vehicle_price_box {
	margin-left: 0px;
	margin-top: 12px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 55px;
	margin-bottom: 0;
	width: 168px;
}
.vehicle_price_label {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #<?php echo $theme_color_shadow; ?>;
    line-height: 18px;
	margin-left: 20px;
	text-align: center;
}
.vehicle_price {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 28px;
	font-weight: bold;
	color: #0a0;
	margin-left: 20px;
	text-align: center;
}
/*
Vehicle Content
*/
.cd_pro .main_photo_pro_img {
	cursor:pointer;
    max-width: 100%;
}
.view_vehicle_lg:hover {
	background-color:#<?php echo $theme_color_button_hover; ?>;
}
.view_vehicle_lg a {
	font-size: 10px;
	font-weight: bold;
	font-family: Tahoma, Geneva, sans-serif;
	color: #<?php echo $theme_color_highlight; ?>;
	text-decoration:none;
	padding-left: 10px;
	padding-top: 10px;
}
.view_vehicle_lg a:visited {
	color: #<?php echo $theme_color_highlight; ?>;    
}
.view_vehicle_lg a:hover {
	color: #<?php echo $theme_color_highlight; ?>;
}
.view_vehicle:hover {
	background-color:#<?php echo $theme_color_button_hover; ?>;
}
.view_vehicle a {
	font-size: 10px;
	font-weight: bold;
	font-family: Tahoma, Geneva, sans-serif;
	color: #<?php echo $theme_color_highlight; ?>;
	text-decoration:none;
	padding-left: 10px;
	padding-top: 10px;
}
.view_vehicle a:visited {
	color: #<?php echo $theme_color_highlight; ?>;    
}
.view_vehicle a:hover {
	color: #<?php echo $theme_color_highlight; ?>;
}
.vehicle_button_img {
	margin-right: 1px;
	cursor: pointer;
}
/*
Calculator Widget
*/
#calculator_icon {
	margin-left: 0px;
	margin-top: 0px;
	background-image: url(cds_images/calculator_icon.jpg);
	overflow: hidden;
	display: inline;
	float: left;
	height: 54px;
	margin-bottom: 0;
	width: 55px;
}
#calculator_title_div {
	margin-left: 0px;
	margin-top: 0px;
	background-color: #DDD;
	overflow: hidden;
	display: inline;
	float: left;
	height: 54px;
	margin-bottom: 0;
	width: 212px;
}
#calculator_title {
	font: 24px/24px 'TitilliumText22LRegular';
	font-weight: bold;
	color: #<?php echo $theme_color; ?>;
	margin-top: 16px;
}
.calculator_label {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #555;
	margin-left: 10px;
	margin-top: 20px;
}
#calculator_price_label {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 46px;
	margin-bottom: 0;
	width: 107px;
	background-color: #DDD;
}
#calculator_price_div {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 46px;
	margin-bottom: 0;
	width: 160px;
	background-color: #DDD;
}
.calculator_price_field {
	width:50px;
	border-radius:5px;
	margin-left: 20px;
	margin-top: 14px;
}
#calculator_rate_label {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 46px;
	margin-bottom: 0;
	width: 107px;
	background-color: #DDD;
}
#calculator_rate_div {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 46px;
	margin-bottom: 0;
	width: 160px;
	background-color: #DDD;
}
.calculator_rate_field {
	width:30px;
	border-radius:5px;	
	margin-left: 20px;
	margin-top: 14px;
}
#calculator_term_label {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 46px;
	margin-bottom: 0;
	width: 107px;
	background-color: #DDD;
}
#calculator_term_div {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 46px;
	margin-bottom: 0;
	width: 160px;
	background-color: #DDD;
}
.calculator_term_field {
	width:30px;
	border-radius:5px;
	margin-left: 20px;
	margin-top: 14px;
}
#calculator_submit_div {
	margin-left: 0px;
	margin-top: 0px;
	background-color: #DDD;
	overflow: hidden;
	display: inline;
	float: left;
	margin-bottom: 0;
	width: 105px;
    padding-left: 20px;
    padding-right: 10px;
	cursor: pointer;
}
#calculator_reset_div {
	margin-left: 0px;
	margin-top: 0px;
	background-color: #DDD;
	overflow: hidden;
	display: inline;
	float: left;
	margin-bottom: 0;
	width: 105px;
    padding-left: 10px;
    padding-right: 17px;
	cursor: pointer;
}
.calculator_form_button {
	/*background:url(cds_images/search_btn_bck.png);*/
	background-color: #<?php echo $theme_color; ?>;
	background-position: left;
	background-repeat: repeat-x;
    background-position: 10% 0%;
	color: #<?php echo $theme_color_highlight; ?>;
	width: 105px;
	height: 28px;
	margin-top: 18px;
	font-family:Verdana, Geneva, sans-serif;
	font-size: 14px;
	font-weight: bold;
	text-align: center;
	float: right;
	cursor: pointer;
}
.calculator_form_button:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
}
/*
Page Styles
*/
.random_img {
	cursor:pointer;
	width:143px;
}
.random{
	margin-bottom: 15px;
}
.random_widget_image {
	min-width: 180px !important;
	/*margin-left:15px;*/
	margin-right:0px;
	margin-bottom:0px;
	border:solid;
	border-width:1px;
	border-color:gray;
	padding: 3px;
	border-radius: 3px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	-moz-box-shadow: 0 0 5px rgba(0,0,0, .3);
	-webkit-box-shadow: 0 0 5px 
	rgba(0, 0, 0, .3);
	box-shadow: 0 0 5px 
	rgba(0, 0, 0, .3);
}
.compare_widget_image_bg {
	min-width: 120px !important;
	margin-left:15px;
	margin-right:0px;
	margin-bottom:0px;
	border:solid;
	border-width:1px;
	border-color:gray;
	padding: 3px;
	border-radius: 3px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	-moz-box-shadow: 0 0 5px rgba(0,0,0, .3);
	-webkit-box-shadow: 0 0 5px 
	rgba(0, 0, 0, .3);
	box-shadow: 0 0 5px 
	rgba(0, 0, 0, .3);
}
.compare_widget_image {
	margin-left:2px;
	margin-right:0px;
	margin-bottom:0px;
	border:solid;
	border-width:1px;
	border-color:gray;
	padding: 3px;
	border-radius: 3px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	-moz-box-shadow: 0 0 5px rgba(0,0,0, .3);
	-webkit-box-shadow: 0 0 5px 
	rgba(0, 0, 0, .3);
	box-shadow: 0 0 5px 
	rgba(0, 0, 0, .3);
	}
#ribbon {
	position:absolute;
	z-index:500;
}
#fade_right {
	position:absolute;
	z-index:500;
	cursor:pointer;
	margin-left:265px;
}
#look_close {
	position:absolute;
	z-index:499;
	opacity: 0;
	transition: opacity .25s ease-in-out;
	-moz-transition: opacity .25s ease-in-out;
	-webkit-transition: opacity .25s ease-in-out;
	cursor:pointer;
}
#look_close:hover {
	display:block;
	opacity: 1;
}
.car_demon_compare_widget a {
	color: #<?php echo $theme_color_highlight; ?>;
	list-style: none;
	text-decoration: none;
	font-size: 12px;
}
.car_demon_compare_widget a:visited {
	color: #<?php echo $theme_color_highlight; ?>;
	list-style: none;
	text-decoration: none;
	font-size: 12px;
}
.car_demon_compare_box_list_cars {
	height: 530px !important;
    overflow: auto;
}
.car_your_price_compare {
	color: #0a0;
    font-weight: bold;
}
.car_final_price_compare {
	color: #0a0;
    font-weight: bold;
}
.close_cdsp_compare {
	float:right;
	color:#BBBBBB;
	font-family:Arial, Helvetica, sans-serif;
	font-weight:bold;
	cursor:pointer;
}
.compare_title {
	margin-top:10px;
	margin-left:10px;
	font-size:12px;
	font-weight:bold;
	color:gray;
}
.compare_text {
	margin-top:1px;
	margin-left:10px;
	font-size:10px;
	font-weight:bold;
	color:gray;
	line-height:1.1em;
}
.car_demon_compare_print {
	float:left;
	margin-left:10px;
	color:#BBBBBB;
	font-family:Arial, Helvetica, sans-serif;
	font-weight:bold;
	cursor:pointer;
}
/*
Cars For Sale Vehicle List    
*/
.car_item_pro.cd_pro {
	margin: 0 auto 0 0;
	width: 239px;
	margin-left: 2px;
	margin-top: 5px;
	float: left;
}
.vehicle_top_bar {
	margin-left: 0px;
	margin-top: 0px;
	background-color: #<?php echo $theme_color; ?>;
	overflow: hidden;
	display: inline;
	float: left;
	height: 4px;
	margin-bottom: 0;
	width: 239px;
}
.vehicle_title {
	box-sizing: content-box;
	overflow: hidden;
	display: inline;
	float: left;
	height: 45px;
	margin-bottom: 0px;
	margin-left: 0px;
	width: 177px;
	font: 14px/14px 'TitilliumText22LRegular';
	font-weight: bold;
	text-align: left;
	color: #555;
	padding-top: 7px;
	padding-left: 7px;
	background-color: #<?php echo $theme_color_highlight; ?>;
}
.vehicle_compare_box_lg {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 42px;
	margin-bottom: 0;
	width: 83px;
	text-align: center;
	background-color: #<?php echo $theme_color_highlight; ?>;
}
.vehicle_compare_box_lg input {
	outline: 1px solid #<?php echo $theme_color; ?>;
}
.vehicle_compare_label_lg {
	font-family: Verdana, Geneva, sans-serif;
	font-weight: bold;
	font-size: 10px;
	text-align: center;
	color: #555;
}
.vehicle_compare_box {
	margin-left: 0px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 52px;
	margin-bottom: 0;
	min-width: 53px;
	text-align: center;
	background-color: #<?php echo $theme_color_highlight; ?>;
}
.vehicle_compare_box input {
	outline: 1px solid #<?php echo $theme_color; ?>;
}
.cd_pro .vehicle_compare_label {
	font-family: Verdana, Geneva, sans-serif;
	font-weight: bold;
	font-size: 10px;
	text-align: center;
	color: #555;
}
.vehicle_make_box {
	margin-left: 11px;
	margin-top: 8px;
	background-color: #<?php echo $theme_color_highlight; ?>;
	overflow: hidden;
	display: inline;
	float: left;
	height: 35px;
	margin-bottom: 0;
	width: 105px;
}
.vehicle_make_arrow {
	background-image: url(cds_images/cars-for-sale-title-arrow.png);
	background-color: #<?php echo $theme_color; ?>;
	width: 6px;
	height: 40px;
	float: left;
    background-position: 0 -5px;
}
.vehicle_make {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
	text-align: center;
	color: #<?php echo $theme_color; ?>;
	text-align: left;
	padding-left: 12px;
}
.vehicle_stock_number {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 10px;
	text-align: left;
	color: #555;	
	padding-left: 12px;
}
.vehicle_price_box {
	margin-left: 0px;
	margin-top: 8px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 35px;
	margin-bottom: 0;
	width: 111px;
}
.vehicle_price_label {
	text-align: center;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #555;
}
.vehicle_price {
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
	color: #0a0;
	margin-top: -5px;
}
.vehicle_details_box{
	background-color: #<?php echo $theme_color_background; ?>;
    <?php
		if ( defined( 'CDSP_SRP_SIDEBAR' ) ) {
			?>
			height: 397px;
            <?php
		} else {
			?>
            height: 360px;
            overflow: hidden;            
            <?php	
		}
		?>
}
.vehicle_photo_box {
	margin-left: 11px;
	margin-top: 0px;
	padding-top: 6px;
	overflow: hidden;
	background-color: #<?php echo $theme_color_highlight; ?>;
	display: inline;
	float: left;
	height: 162px;
	margin-bottom: 0;
	width: 218px;
	text-align: center;
}
.vehicle_option_title {
	box-sizing: content-box;
	margin-left: 11px;
	margin-top: 0px;
	background-color: #<?php echo $theme_color_highlight; ?>;
	overflow: hidden;
	display: inline;
	float: left;
	height: 18px;
	margin-bottom: 0;
	width: 210px;
	color: #<?php echo $theme_color; ?>;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	font-weight: bold;
	padding-left: 8px;
    padding-top:8px;
}
.vehicle_options {
	box-sizing: content-box;
	margin-left: 11px;
	margin-top: 0px;
	background-color: #<?php echo $theme_color_highlight; ?>;
	overflow: hidden;
	display: inline;
	float: left;
	height: 85px;
	margin-bottom: 0;
	width: 216px;
	color: #555;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	font-weight: bold;
	padding-left: 2px;
}
.vehicle_options li {
	list-style: none;
    line-height: 20px;
    margin-left: 6px !important;
	padding-left: 15px;
}
.vehicle_options_style {
	background-color: #<?php echo $theme_color; ?>;
	background-image: url(cds_images/cars-for-sale-option-arrow.png);
	background-repeat: no-repeat;
	width: 9px;
	float: left;
	height: 20px;
	margin-right: 10px;
}
.vehicle_detail_button_box {
	margin-left: 11px;
	margin-top: 0px;
	overflow: hidden;
	display: inline;
	float: left;
	height: 44px;
	margin-bottom: 0;
	width: 218px;
}
.view_vehicle {
	margin-left: 0px;
	margin-top: 0px;
	padding-top: 2px;
	background-color: #<?php echo $theme_color; ?>;
	overflow: hidden;
	display: inline;
	float: left;
	height: 28px;
	margin-bottom: 0;
	width: 218px;
	cursor: pointer;
	text-align: center;	
}
.view_vehicle a {
	font-size: 10px;
	font-weight: bold;
	font-family: Tahoma, Geneva, sans-serif;
	color: #<?php echo $theme_color_highlight; ?>;
	text-decoration: none;
	padding-left: 10px;
	padding-top: 10px;
	text-align: center;
}
.view_vehicle a:visited {
	color: #<?php echo $theme_color_highlight; ?>;
}
/*
Sort
*/
#frm_cd_sort {
	float: right;
	color: #<?php echo $theme_color; ?>;
	font-family: Tahoma, Geneva, sans-serif;
	font-weight: bold;
	font-size: 12px;
    width: 230px;
}
#frm_cd_sort input {
	float: right;
	color: #<?php echo $theme_color; ?>;
	font-family: Tahoma, Geneva, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
#demon-content h1 {
	float: left;
	color: #<?php echo $theme_color; ?>;
	font-family: Tahoma, Geneva, sans-serif;
	font-weight: bold;
	font-size: 12px;
    width: 100%;
}
#demon-content h4 {
	float: left;
	color: #<?php echo $theme_color; ?>;
	font-weight: bold;
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 12px;
	margin-left: 10px;
}
/*
Body Widget
*/
#bsw_go_back_thumbs {
    margin-left: 2px;
    margin-top: 0px;
    background-color: #<?php echo $theme_color; ?>;
    background-image: url(cds_images/btn_go_back.png);
    overflow: hidden;
    display: inline;
    float: left;
    height: 71px;
    margin-bottom: 0;
    width: 14px;
    cursor: pointer;
    padding-top: 10px;
    padding-bottom: 10px;
}
#bsw_go_back_thumbs:hover {
    background-color: #<?php echo $theme_color_button_hover; ?>;
}
#bsw_go_back:hover {
    background-color: #<?php echo $theme_color_button_hover; ?>;
}
#btw_go_forward_thumbs {
    margin-left: 0px;
    margin-top: 10px;
    background-image: url(cds_images/btn_go_forward.png);
    background-color: #<?php echo $theme_color; ?>;
    overflow: hidden;
    height: 71px;
    margin-bottom: 0;
    width: 12px;
    cursor: pointer;
    padding-top: 10px;
    padding-bottom: 10px;
}
#btw_go_forward_thumbs:hover {
    background-color: #<?php echo $theme_color_button_hover; ?>;
}
#btw_go_forward:hover {
    background-color: #<?php echo $theme_color_button_hover; ?>;
}
.body_style_thumb {
    margin-right: 10px;
    cursor: pointer;
}
#cdp_body_style_slider{
    position:relative;
    left:0;
    margin-top: -12px;
}
#cdp_body_style_slider > div {
    position:relative;
    max-width:123px;
    height:300px;
    float:left;
}
/*
Car Demon Widget
*/
.car-demon-widget {
    list-style:none;
}
#widgets {
    margin-top:44px;
}
#widgets a {
    display:inline-block;
    margin:0;
    padding:3px;
    text-decoration:none;
}
#widgets ul, #widgets p {
    border:1px solid #f5f5f5;
    border-top:none;
    margin:0 0 20px;
    padding:10px;
}
#widgets ul li {
    list-style-type:none;
    margin:0;
}
#widgets ul li a {
    color:#444;
    text-decoration:none;
}
#widgets ul li a:hover {
    color:#000;
}
#widgets ul ul a {
    padding:3px 0 3px 18px;
}
#widgets ul ul ul a {
    padding:3px 0 3px 18px;
}
#widgets ul ul ul ul a {
    border:none;
    padding:3px 0 3px 18px;
}
#widgets .widget-title img {
    float:right;
    height:11px;
    position:relative;
    top:4px;
    width:11px;
}
#widgets .rss-date {
    line-height:18px;
    padding:6px 12px;
}
#widgets .rssSummary {
    padding:10px;
}
#widgets cite {
    font-style:normal;
    line-height:18px;
    padding:6px 12px;
}
#widgets .textwidget, #widgets .tagcloud {
    border:1px solid #f5f5f5;
    border-top:none;
    display:block;
    line-height:1.5em;
    margin:0 0 20px;
    padding:10px;
}
#widgets .textwidget a {
    display:inline;
}
#widgets ul .children {
    border:none;
    margin:0;
    padding:0;
}
#widgets .author {
    font-weight:700;
    padding-top:4px;
}
/*
Staff Pages
*/
.staff_card .photo {
	width: 100px;
	margin: 2px;
}
.staff_card {
	border: solid;
	border-width: 3px;
	border-left-width: 1px;
	border-top-width: 1px;
	width: 285px;
	padding: 2px;
	margin: 3px;
	margin-bottom: 5px;
	margin-left: 10px;
	min-height: 110px;
	font-size: 12px;
	float:left;
}
.staff_card img {
	float:left;
	margin-right:4px;
	width: 100px;
}
.staff_mobile_description {
	display:none;
}
.staff_box {
	width:100%;
	float:left;
}
.staff_details {
	float:left;
	min-width:300px;
}
.staff_desktop_img {
	float:left;
	margin-right:4px;
	width:200px;
}
.staff_mobile_img {
	float:left;
	margin-right:4px;
	width:100px;
}
.staff_more {
	cursor:pointer;
}
.staff_card .tel {
	width: 86%;
    float: left;
    font-size: 1.5em;
    margin: 15px;
    margin-left: 7%;
}
.contact_form_container {
	display: none;
    background-color: #fff;
    padding: 15px;
}
/*
Ribbon Mod
*/
.similar_car_ribbon {
	padding: 0px !important;
    background: none !important;
    border: 0px !important;
}
/*
Forms
*/
.service_quote_container, .service_form_container, .parts_form_container, .qualify_form_container {
	display: none;
    background-color: #ffffff;
    padding: 10px;
}
#contact_form input[type="text"] {
	width: 90%;
}
#contact_form textarea {
	width: 85% !important;
    height: 75px;
    margin-right: 10px;
}
#contact_form br {
	display: none;
}
#contact_form .contact_us_btn {
	float: none;
}
form.cdform legend {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: bold;
	color: #C91344;
}
#trade_form fieldset, #qualify_form fieldset, #service_form fieldset, #service_quote fieldset, #part_form fieldset, #contact_form fieldset {
	width: 100%;
}
#trade_form ol, #qualify_form ol, #service_form ol, #service_quote ol, #part_form ol, #contact_form ol {
    width: 96%;
    margin-left: 2% !important;
    padding-left: 5px !important;
    padding-bottom: 12px !important;
}
#trade_form input[type="text"], #qualify_form input[type="text"], #service_form input[type="text"], #service_quote input[type="text"], #part_form input[type="text"], #contact_form input[type="text"] {
	width: 90%;
}
#trade_form textarea, #qualify_form textarea, #service_form textarea, #service_quote textarea, #part_form textarea, #contact_form textarea {
	width: 90%;
}
label.cd-group-after span {
	width: 132px !important;
}
form.cdform .finance_segment_wide ol {
    max-width: 390px;
    padding: 10px !important;
    margin-left: 10px !important;
}
form.cdform ol {
	background-color: #DDD;
    padding-left: 0;
    margin-left: 0;
    float: left;
    width: 100%;
    padding-bottom: 6px;
    padding-top: 6px;
}
form.cdform li {
	text-decoration: none;
	list-style: none;
    /*padding: 10px;*/
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #555;
    margin-left: 10px;
}
#trade_form {
	
}
.cd-box-group br {
	/*clear: both;*/
    display: none;
}
form.cdform li label {
	display: block;
    float: none;
    margin: 4px 10px 0 0;
    display: -moz-inline-box;
    text-align: left;
    vertical-align: top;
    clear: both;
}
.cd-box-group input.cd-box {
    margin: 2px 8px;
    width: 14px !important;
    height: 22px;
    border: none!important;
    background: none!important;
    display: inline-block !important;
    float: left;
}

form.cdform .cd-box-group label {
    width: 125px;
    display: block;
    float: left;
    margin: 4px 10px 0 0;
    display: -moz-inline-box;
    text-align: left;
    vertical-align: top;
    clear: none;
}
.single.fldrequired {
	padding: 2px;
    margin: 0px;
    line-height: 1;
	display: block;
    float: left;
}
.reqtxt {
	font-size: 10px;
	color: #C91344;
    margin-left: 4px;
	/*float: left;*/
}
.emailreqtxt {
	font-size: 10px;
	color: #C91344;
    margin-left: 4px;
    float: left;
}
/*
Parts Form
*/
.remove_part {
	cursor: pointer;
}
.hide_parts {
	display:none;
}
.remove_part_btn {
	cursor: pointer;
	display:none;
	margin-left:10px;
	margin-top:4px;
}
.add_part_btn {
	cursor: pointer;
	margin-left:10px;
	margin-top:4px;
}
.part_msg {
	display:none;
	background: #f1cadf;
	margin:10px;
	padding:5px;
	font-weight:bold;
}
/*
Forms
*/
.cdform .search_btn {
    /*background: url(cds_images/search_btn_bck.png);*/
	background-color: #<?php echo $theme_color_button; ?>;
	background-position: 10% 0%;
    background-repeat: repeat-x;
    color: #FFFFFF !important;
    width: 190px;
    height: 38px;
    margin-top: 18px;
    font-family: Verdana, Geneva, sans-serif;
    font-size: 14px;
    font-weight: bold;
    text-align: center;
    float: right;
    cursor: pointer;
}
.cdform .search_btn:hover {
	background-color: #<?php echo $theme_color_button_hover; ?>;
}
#social_facebook, #social_g_plus, #social_twitter, #social_pintrest, #social_email_icon, #social_print_icon, #social_email_label, #social_print_label {
	cursor: pointer;
}
.email_friend_div {
	display: none;
}
#ef_contact_msg {
	max-width: 250px;
}
#ef_contact_msg br {
	display: block !important;
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
.clearFloat {
	clear: both;
}
.wp-pagenavi {
    clear: both;
    margin-top: 8px;
    margin-bottom: 4px;
    height: 22px;
    float: left;
    width: 96%;
    font: 14px/14px 'TitilliumText22LRegular';
}
.cdsp_thumbs_box {
	position: relative;
}
.search_dropdown_sm {
	width: 100px;
}
.cd_drop_down_title_bottom, #qc_icon_btm {
	float: left;
}
#qc_icon_btm {
	margin-top: -60px !important;
}
.cd_drop_down_title_bottom {
	margin-top: -45px !important;
}
#cd_ddcb_title {
	margin-top: 10px;
}
.results_found {
//	display: none;
}
.no_result {
	float: left;
}
.sorry {
	font-weight: bold;
}
.car_demon_compare_box_main h2 {
	padding: 10px;
}
.car_demon_compare_print {
	display: none;
}
.cd-box-group input[type="checkbox"] {
	float:left;
}
.content_section input[type="radio"] {
	width: 35px !important;
}
.content_section .cdlabel_right {
	width: 90px !important;
}
.content_section input[type=text], select, textarea {
	margin-left: 2% !important;
}
.cd-box-group input.cd-box {
	margin: 2px 8px;
	width: 14px !important;
	height: 22px;
	border: none!important;
	background: none!important;
	display: inline-block !important;
	/*float:none !important;*/
}
.content_section .cdform .cd-group-after {
	width: 130px !important;
	float:none !important;
}
.cd-box-group {
	width: 96% !important;
	max-width: 700px !important;
}
.trade_label {
	float: none;
}
.trade_hide {
	display: none;
}
.search_car_box select {
	height: 33px;
}
.vehicle_widget .vehicle_title a:hover {
	color: #<?php echo $theme_color; ?>;
}
.cdform textarea {
	width: 94%;
}
.random_imgs .cd_pro .main_photo_pro_img {
	height: auto !important;
    width: 94%;
}
.search_btn advanced_btn {
    background: url(cds_images/search_btn_bck.png);
    background-color: #<?php echo $theme_color; ?>;
    background-position: left;
    background-repeat: repeat-x;
    background-position: 10% 0%;
    color: #FFFFFF;
    width: 105px;
    height: 28px;
    margin-top: 18px;
    font-family: Verdana, Geneva, sans-serif;
    font-size: 14px;
    font-weight: bold;
    text-align: center;
    float: right;
    cursor: pointer;
}
.search_footer {
	height: auto !important;
}
.search_car_box_frame {
	min-width: 245px;
}
a.cd_btn_single {
    margin: 5px;
    border: 1px solid #000;
    padding: 10px;
    float: left;
    background-color: #b00;
    color: #fff !important;
    cursor: pointer;
	width: 120px;
    text-align: center;
}
a.cd_btn_single:hover {
	background: #fff;
    color: #b00 !important;
}
.cd_trade_option {
	float: left;
}