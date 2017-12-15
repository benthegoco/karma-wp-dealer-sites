
	var IMG_WIDTH = 125;
	var currentImg=0;
	var maxImages=50;
	var speed=500;
	var imgs;
	var swipeOptions=
		{
			triggerOnTouchEnd : true,	
			swipeStatus : swipeStatus,
			allowPageScroll:"vertical",
			threshold:75			
		}
	jQuery( document ).ready(function($) {
		$(function() {
//			imgs = $(".cdsp_thumbs");
//			imgs.swipe( swipeOptions );

			console.log("yo mtv");
			
			jQuery('.colwrap1').slick({
				dots: true,
				infinite: true,
				speed: 500,
				fade: true,
				cssEase: 'linear'
			  });

		});
	});
	
	/**
	* Catch each phase of the swipe.
	* move : we drag the div.
	* cancel : we animate back to where we were
	* end : we animate to the next image
	*/			
	function swipeStatus(event, phase, direction, distance)
	{
		//If we are moving before swipe, and we are going Lor R in X mode, or U or D in Y mode then drag.
		if( phase=="move" && (direction=="left" || direction=="right") )
		{
			var duration=0;
			
			if (direction == "left")
				scrollImages((IMG_WIDTH * currentImg) + distance, duration);
			
			else if (direction == "right")
				scrollImages((IMG_WIDTH * currentImg) - distance, duration);
			
		}
		
		else if ( phase == "cancel")
		{
			scrollImages(IMG_WIDTH * currentImg, speed);
		}
		
		else if ( phase =="end" )
		{
			if (direction == "right")
				previousImage()
			else if (direction == "left")			
				nextImage()
		}
	}
	
	function previousImage()
	{
		currentImg = Math.max(currentImg-1, 0);
		scrollImages( IMG_WIDTH * currentImg, speed);
	}
	
	function nextImage()
	{
		currentImg = Math.min(currentImg+1, maxImages-1);
		scrollImages( IMG_WIDTH * currentImg, speed);
	}
		
	/**
	* Manuallt update the position of the imgs on drag
	*/
	function scrollImages(distance, duration)
	{
		imgs.css("-webkit-transition-duration", (duration/1000).toFixed(1) + "s");
		
		//inverse the number we set in the css
		var value = (distance<0 ? "" : "-") + Math.abs(distance).toString();
		
		imgs.css("-webkit-transform", "translate3d("+value +"px,0px,0px)");
	}
	jQuery(function(){
		var gal = jQuery('#single-car-thumbnail-box-container');
		var slider = jQuery('.cdsp_thumbs_box');
		var img_cnt = jQuery("div.cdsp_thumbs_box img").length;
		var thumb_width = 100;
		var galW = img_cnt*thumb_width;
		jQuery('.cdsp_thumbs_box').css("width",galW + "px");
		var maxW = 50*100;
		var visible_width = jQuery("#single-car-thumbnail-box").width();
		var how_many_fit = galW/visible_width;
		var max_clicks = how_many_fit;
		max_clicks = Math.ceil(max_clicks * 10) / 10;
		var sliderW = (galW);
		var pad = 0;
		var hidden_width = (galW-visible_width)*(-1);

		jQuery("#bsw_go_back_thumbs, #bsw_go_back_thumb").click(function(e){
			var vehicle_image_count = parseInt(document.getElementById("vehicle_image_count").value);
			var position = slider.css("margin-left");
			var left = parseFloat(position.replace("px", ""));
			if (left < visible_width) {
				var new_left = left+visible_width+10;
				if (new_left > 0) {
					new_left = 0;
				}
				slider.animate({ marginLeft: new_left },800);
				document.getElementById("vehicle_image_count").value = parseInt(vehicle_image_count)-1;
			} else {
				document.getElementById("vehicle_image_count").value = 0;	
			}
			jQuery("#bsw_go_back_thumbs, #bsw_go_back_thumb").data("position", new_left);
		});

		jQuery("#btw_go_forward_thumbs, #btw_go_forward_thumb").click(function(e){
			var vehicle_image_count = parseInt(document.getElementById("vehicle_image_count").value);
			var position = slider.css("margin-left");

			jQuery("#bsw_go_back_thumbs, #bsw_go_back_thumb").data("position", position);
			var left = parseFloat(position.replace("px", ""));

			if (vehicle_image_count < max_clicks) {
				var new_left = left-visible_width+10;
				if (left > (hidden_width)) {
					slider.animate({ marginLeft: new_left },800);
				}
				document.getElementById("vehicle_image_count").value = parseInt(vehicle_image_count)+1;
			} else {
				slider.animate({ marginLeft: 0 },800);
				document.getElementById("vehicle_image_count").value = 0;
			}
		});

	});
	function share_twitter() {
		var width  = 575,
			height = 400,
			left   = (jQuery(window).width()  - width)  / 2,
			top    = (jQuery(window).height() - height) / 2,
			url    = 'http://twitter.com/share?text=Check%20out%20this%20car%20',
			opts   = 'status=1' +
					 ',width='  + width  +
					 ',height=' + height +
					 ',top='    + top    +
					 ',left='   + left;
		
		window.open(url, 'twitter', opts);
		
		return false;
	}
	function print_car() {
		var elem = "#demon-container";
		popup_car(jQuery(elem).html());
	}
	
	function popup_car(data) {
		var body = '<html><head><title>Print Car</title>';
		body = body + '<link rel="stylesheet" href="http://cddev.cardemons.dev/wp-content/plugins/car-demon-shortcode/css/print.css" type="text/css" media="print">';
		body = body + '<link rel="stylesheet" href="http://cddev.cardemons.dev/wp-content/plugins/car-demon-shortcode/css/cdf.css" type="text/css" media="all">';
		body = body + '<link rel="stylesheet" href="http://cddev.cardemons.dev/wp-content/plugins/car-demon-shortcode/css/car-demon-theme1.1.9.css" type="text/css" media="all">';
		body = body + '<link rel="stylesheet" href="http://cddev.cardemons.dev/wp-content/plugins/car-demon-shortcode/css/single-cars-for-sale.css" type="text/css" media="all">';
		body = body + '<style>';
			body = body + 'body { width: 100%; }';
		body = body + '</style>';
		body = body + '</head><body>';
		body = body + data;
		body = body + '</body></html>';
		var mywindow = window.open('', 'print_car', 'height=400,width=800');
		mywindow.document.write(body);
		window.setTimeout(function(){finish_print_car()},1000);
		return true;
	}
	function finish_print_car() {
		var w = window.open("", "print_car");
		w.print();
		w.close();
	}
	function MM_swapImage(img_id, tag, new_src) {
		var find = " ";
		var re = new RegExp(find, "g");
		img_id = img_id.replace(re, "_");
		document.getElementById(img_id).src = new_src;
		document.getElementById("car_demon_light_box_main_img").src = new_src;
	}

	function cdsp_swap_image(img) {
		jQuery(".main_photo_pro_img").attr("src", img);
		jQuery("#car_demon_light_box_main_img").attr("src", img);
		console.log(img);
	}


	
