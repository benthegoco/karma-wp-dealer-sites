// JavaScript Document
jQuery(document).ready(function($) {
	var gal = jQuery('#single-car-thumbnail-box-container');
	var slider = jQuery('.cdsp_thumbs_box');
	var img_cnt = jQuery("div.cdsp_thumbs_box img").length;
	var thumb_width = 100;
	var galW = img_cnt*thumb_width;
	jQuery('.cdsp_thumbs_box').css("width",galW + "px");
	var maxW = 50*100;
	var visible_width = jQuery(".thumb-slider").width();
	var how_many_fit = galW/visible_width;
	var max_clicks = how_many_fit;
	max_clicks = Math.ceil(max_clicks * 10) / 10;
	var sliderW = (galW);
	var pad = 0;
	var hidden_width = (galW-visible_width)*(-1);

	$('#bsw_go_back_thumb').click(function(e){
		var vehicle_image_count = parseInt(document.getElementById("vehicle_image_count").value);
		var position = jQuery(".cdsp_thumbs_box").position();
		var left = position.left;
		if (left < 0) {
			var new_left = left+visible_width-10;
			if (new_left > 0) {
				new_left = 0;
			}
			slider.animate({ left: new_left },800);
			document.getElementById("vehicle_image_count").value = parseInt(vehicle_image_count)-1;
		} else {
			document.getElementById("vehicle_image_count").value = 0;	
		}
		$("#bsw_go_back_thumb").data("position", new_left);
	});

	$('#btw_go_forward_thumb').click(function(e){
		var vehicle_image_count = parseInt(document.getElementById("vehicle_image_count").value);
		var position = jQuery(".cdsp_thumbs_box").position();
		$("#bsw_go_back_thumb").data("position", position.left);
		var left = position.left;

		if (vehicle_image_count < max_clicks) {
			var new_left = left-visible_width+10;
			if (left > (hidden_width)) {
				slider.animate({ left: new_left },800);
			}
			document.getElementById("vehicle_image_count").value = parseInt(vehicle_image_count)+1;
		} else {
			slider.animate({ left: 0 },800);
			document.getElementById("vehicle_image_count").value = 0;
		}
	});
	
	$('.cd_thumbnail_img').on('click', function() {
		var full_size = $(this).data('full-sized');
		$('.main_photo').attr('src', full_size);
	});
	
});