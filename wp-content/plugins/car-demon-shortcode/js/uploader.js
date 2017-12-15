jQuery(document).ready(function() {
var sendto = '';
jQuery('#upload_image_button').click(function() {
	formfield = jQuery('#favico_img').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'favico_img';
	return false;
});
jQuery('#upload_ico_button').click(function() {
	formfield = jQuery('#favico_ico').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'favico_ico';
	return false;
});
jQuery('#upload_warranty_button').click(function() {
	formfield = jQuery('#warranty_button').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'warranty_button';
	// jQuery('#upload_warranty_button').src = formfield;
	return false;
});
jQuery('#upload_finance_button').click(function() {
	formfield = jQuery('#finance_button').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'finance_button';
	return false;
});
jQuery('#upload_trade_button').click(function() {
	formfield = jQuery('#trade_button').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'trade_button';
	return false;
});
jQuery('#upload_header_button').click(function() {
	formfield = jQuery('#custom_header').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'custom_header';
	return false;
});
jQuery('#upload_mobile_logo_button').click(function() {
	formfield = jQuery('#mobile_logo').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'mobile_logo';
	return false;
});
jQuery('#upload_slide1_image_button').click(function() {
	formfield = jQuery('#custom_slide1_img').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'custom_slide1_img';
	return false;
});
jQuery('#upload_slide2_image_button').click(function() {
	formfield = jQuery('#custom_slid2_img').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'custom_slide2_img';
	return false;
});
jQuery('#upload_slide3_image_button').click(function() {
	formfield = jQuery('#custom_slide3_img').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'custom_slide3_img';
	return false;
});
jQuery('#upload_slide4_image_button').click(function() {
	formfield = jQuery('#custom_slide4_img').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'custom_slide4_img';
	return false;
});
jQuery('#upload_slide5_image_button').click(function() {
	formfield = jQuery('#custom_slide5_img').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'custom_slide5_img';
	return false;
});
jQuery('#upload_slide6_image_button').click(function() {
	formfield = jQuery('#custom_slide6_img').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'custom_slide6_img';
	return false;
});
jQuery('#upload_slide7_image_button').click(function() {
	formfield = jQuery('#custom_slide7_img').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'custom_slide7_img';
	return false;
});
jQuery('#body_style_widget_new_body_style_available').click(function() {
	formfield = jQuery('#body_style_widget_new_body_style_available').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'body_style_widget_new_body_style_available';
	return false;
});
jQuery('#body_style_widget_new_body_style_unavailable').click(function() {
	formfield = jQuery('#body_style_widget_new_body_style_unavailable').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	sendto = 'body_style_widget_new_body_style_unavailable';
	return false;
});
window.send_to_editor = function(html) {
	imgurl = jQuery('img',html).attr('src');
	jQuery('#'+sendto).val(imgurl);
	if(sendto != 'body_style_widget_new_body_style_available' || sendto != 'body_style_widget_new_body_style_unavailable') {
		jQuery('#'+sendto+'_ico').attr("src", imgurl);
	}
	tb_remove();
}
});