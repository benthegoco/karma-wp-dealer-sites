// JavaScript Document

jQuery( document ).ready(function($) {
	var adjust_selects = function() {
		setTimeout(function() {
			if ($('.search_car_box select').attr('size') == 2) {
				$('.search_car_box select').attr('size', '1');			
			} else {
				adjust_selects();
			}
		}, 1000);
	};
	adjust_selects();
	$('.cd_thumbnail_img').on('click', function() {
		var full_size = $(this).data('full-sized');
		$('.car_photo_img').prop('src', full_size);
	});
	
	var make_offer = function() {
		if ($('.make_offer_form').css('display') == 'none') {
			$('.make_offer').scrollView();
			$('.offer_results').html('');
			$('.make_offer_form').slideDown();
		} else {
			$('.make_offer_form').slideUp();
		}
	};
	
	$('.make_offer_btn').on('click', function() {
		make_offer();
	});
	$('.single_make_offer').on('click', function() {
		make_offer();
	});
	$('.view_all_features_btn').on('click', function() {
		$('.end_options_html').scrollView();
	});
	
	$('.view_more_photos').on('click', function() {
		$('.car_item_pro_thumbs').scrollView();
	});
	$('.mo_btn').on('click', function() {
		var name = $('.mo_name').val();
		var email = $('.mo_email').val();
		var offer = $('.mo_offer').val();
		var comments = $('.mo_comments').val();
		var post_id = $('.make_offer_form').data('post-id');
		if (name == '' || email == '' || offer == '') {
			alert('Please fill out all fields');
		} else {
			$('.make_offer_form').slideUp();
			$('.offer_results').html('Sending Offer...');
			jQuery.ajax({
				type: 'POST',
				data: {action: 'cdf_make_offer', 'post_id': post_id, 'name': name, 'email': email, 'offer': offer, 'comments': comments},
				url: cdfParams.ajaxurl,
				timeout: 5000,
				error: function() {
					$('.offer_results').html('An error occured - your import may have failed');
				},
				dataType: "html",
				success: function(result){
					$('.offer_results').html(result);
				}
			});
		}
	});
	$.fn.scrollView = function () {
	  return this.each(function () {
		$('html, body').animate({
		  scrollTop: $(this).offset().top
		}, 1000);
	  });
	};
	
	$("ul li:last-child").addClass("last-item");
	if (document.getElementById("car_demon_compare")) {
		var html = document.getElementById("car_demon_compare").innerHTML;
		if (html == '<p></p>') {
			$('#car_demon_compare_widget').fadeOut('fast', function() { });
		} else {
			document.getElementById("car_demon_compare").innerHTML = html;
			$('#car_demon_compare_widget').fadeIn('slow', function() { });
		}
	}
	$('#cd_ddcb_dropdown_bottom').css('bottom', '-25px');
	$('#cd_ddcb_dropdown_bottom').mouseover(function() {
		var class_name = $('#cd_ddcb_open').attr('class');
		if (class_name == 'cd_ddcb_open') {
			$('#cd_ddcb_dropdown_bottom').css('bottom', '0px');
		}
	});
	$('#cd_ddcb_dropdown_bottom').mouseout(function() {
		var class_name = $('#cd_ddcb_open').attr('class');
		if (class_name == 'cd_ddcb_open') {
			$('#cd_ddcb_dropdown_bottom').css('bottom', '-25px');
		}
	});
	$('#car_demon_compare_widget').mouseover(function() {
		$('#car_demon_compare_widget').css('right', '-45px');
	});
	$('#car_demon_compare_widget').mouseout(function() {
		$('#car_demon_compare_widget').css('right', '-195px');
	});

	// Template 8
	$('.cd_8').on('mouseenter', function() {
		$(this).find('.cd_show').fadeIn(500);
	});

	$('.cd_8').on('mouseleave', function() {
		$(this).find('.cd_show').fadeOut(500);
	});
	
	$('.cd_show').on('click', function() {
		var url = $(this).data('permalink');
		window.location.href = url;
	});
	
	// Single Template 2
	$('.cd_single_car_2 .cd_single_item_title_box').on('click', function() {
		var content = $(this).next('.cd_single_item_content');
		var arrow = $(this).find('.cd_single_item_title_arrow');
		if ($(content).css('display') == 'none') {
			$(content).slideDown();
			$(arrow).removeClass('cd_arrow_down');
		} else {
			$(content).slideUp();
			$(arrow).addClass('cd_arrow_down');
		}
	});
	
	$('.cd_single_car_2 .cd2_social').on('click', function() {
		var url = $(this).data('link');
		window.open(url);
	});
	
	$('.cd_single_car_2 .photo_bar').on('mouseenter', function() {
		$('.photo_bar').slideUp();
		$('.thumbnails').slideDown();
	});
	
	$('.cd_single_car_2 .thumbnails').on('mouseleave', function() {
		$('.photo_bar').slideDown();
		$('.thumbnails').slideUp();
	});
	
	$('.cd_single_car_2 .main_photo').on('mouseenter', function() {
		$('.title_bar').slideUp();
	});
	
	$('.cd_single_car_2 .main_photo').on('mouseleave', function() {
		$('.title_bar').slideDown();
	});
	
	
	$('.cd_single_car_2 .cd_thumbnail_div img').on('click', function() {
		var url = $(this).prop('src');
		$('.main_photo').prop('src', url);
	});

	$('.cd_single_car_2 .car_thumbnails_left').on('click', function() {
		var left = $('.car_thumbnails_hor').data('left');
		var right = $('.car_thumbnails_hor').data('right');
		var total = $('.car_thumbnails_hor').data('total');
		if (left > 0) {
			$('.car_thumbnails_hor').data('right', right + 1);
			$('.car_thumbnails_hor').data('left', left - 1);
			var margin = $('.car_thumbnails_hor').css('margin-left');
			margin = margin.replace('px', '');
			margin = parseInt(margin) + 129;
			$('.car_thumbnails_hor').css('margin-left', margin + 'px');
		}
	});

	$('.cd_single_car_2 .car_thumbnails_right').on('click', function() {
		var left = $('.car_thumbnails_hor').data('left');
		var right = $('.car_thumbnails_hor').data('right');
		var total = $('.car_thumbnails_hor').data('total');
		if (right > 1) {
			$('.car_thumbnails_hor').data('right', right - 1);
			$('.car_thumbnails_hor').data('left', left + 1);
			var margin = $('.car_thumbnails_hor').css('margin-left');
			margin = margin.replace('px', '');
			margin = parseInt(margin) - 129;
			$('.car_thumbnails_hor').css('margin-left', margin + 'px');
		}
	});
	
	$('.cd_single_car_2 .contact_us_widget_btn').on('click', function() {
		var contact_form = $('.contact_us_widget');
		var friend_form = $('.email_friend_div');
		var contact_form_text = $('.contact_us_widget_btn').data('hide-text');
		var friend_form_text = $('.email_friend_btn').data('hide-text');
		
		if ($(contact_form).css('display') == 'none') {
			var current_text = $('.contact_us_widget_btn').html();
			$('.contact_us_widget_btn').data('hide-text', current_text);
			$('.contact_us_widget_btn').html(contact_form_text);
			$(contact_form).slideDown();
			if ($(friend_form).css('display') != 'none') {
				var current_text = $('.email_friend_btn').html();
				$('.email_friend_btn').data('hide-text', current_text);
				$('.email_friend_btn').html(friend_form_text);
				$(friend_form).slideUp();				
			}
		} else {
			var current_text = $('.contact_us_widget_btn').html();
			$('.contact_us_widget_btn').data('hide-text', current_text);
			$('.contact_us_widget_btn').html(contact_form_text);
			$(contact_form).slideUp();
		}
	});

	$('.cd_single_car_2 .email_friend_btn').on('click', function() {
		var contact_form = $('.contact_us_widget');
		var friend_form = $('.email_friend_div');
		var contact_form_text = $('.contact_us_widget_btn').data('hide-text');
		var friend_form_text = $('.email_friend_btn').data('hide-text');
		
		if ($(friend_form).css('display') == 'none') {
			var current_text = $('.email_friend_btn').html();
			$('.email_friend_btn').data('hide-text', current_text);
			$('.email_friend_btn').html(friend_form_text);
			$(friend_form).slideDown();
			if ($(contact_form).css('display') != 'none') {
				var current_text = $('.contact_us_widget_btn').html();
				$('.contact_us_widget_btn').data('hide-text', current_text);
				$('.contact_us_widget_btn').html(contact_form_text);
				$(contact_form).slideUp();				
			}
		} else {
			var current_text = $('.email_friend_btn').html();
			$('.email_friend_btn').data('hide-text', current_text);
			$('.email_friend_btn').html(friend_form_text);
			$(friend_form).slideUp();
		}
	});

});

function cdsImgError(source, pic){
	source.src = pic;
	source.onerror = '';
	return true;
}

/*
 * Beta pop up contact form
 */
 function cds_contact_form_btn(fld) {
	//= reset the form
	jQuery('.car_demon_contact_box').css('top', '');
	jQuery('.car_demon_contact_box').css('display', '');
	jQuery('.car_demon_contact_box').css('left', '');
	jQuery('.car_demon_contact_box').css('margin-left', '');
	jQuery('.car_demon_contact_box').css('z-index', '');
	jQuery('.car_demon_contact_box').css('position', '');
	jQuery('.car_demon_contact_box').css('margin-top', '');

	var post_id = jQuery(fld).data('post-id');
	jQuery('.car_demon_contact_box').lightbox_me({
		overlayCSS: {background: 'black', opacity: 0.6}
	});

	jQuery('.car_demon_contact_box .close_contact_box').css('display', 'block');

	var box = document.getElementById('cds_contact_form_5_' + post_id).innerHTML;
	box = box.replace('id="contact_form_' + post_id + '"', 'id="contact_form"');
	box = box.replace("contact_msg_tmp", "contact_msg");
	box = box.replace(/_tmp/gi, "");
	document.getElementById('car_demon_light_box_main').innerHTML = box;
	
	var url = location.href;               //Save down the URL without hash.
	location.href = "#cds_lightbox";                 //Go to the target element.
	history.replaceState(null,null,url); 
}

function cds_close_contact_box() {
	jQuery(".car_demon_contact_box").trigger('close');
	jQuery(document).remove("#lb_overlay");
}