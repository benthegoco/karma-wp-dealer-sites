// JavaScript Document
jQuery( document ).ready(function($) {
	$('#body_style_widget_new_body_style_btn').on('click', function() {
		var new_body_style = $('#body_style_widget_new_body_style').val();
		var new_body_style_slug = new_body_style.toLowerCase();
		var find = ' ';
		var re = new RegExp(find, 'g');
		new_body_style_slug = new_body_style_slug.replace(re, '_');
		$('.body_style_widget_new_body_style').css('background-color', '#0f0');
		$.ajax({
			type: 'POST',
			data: {action: 'cdpro_add_body_style_handler', 'new_body_style': new_body_style, 'new_body_style_slug': new_body_style_slug},
			url: cdProCommonParams.ajaxurl,
			timeout: 7000,
			error: function() {},
			dataType: "html",
			success: function(html) {
				$('.widget_body_styles').append(
					$('<option></option>').val(new_body_style_slug).html(new_body_style)
				);
				$('.body_style_widget_new_body_style').css('background-color', '#fff');
			},
			error: function(html) {
				console.log(html);
				alert('An error occured. Please check console log.');
				$('.body_style_widget_new_body_style').css('background-color', '#f00');
			}
		});
	});
	// end add body style button click
	$('#body_style_widget_new_body_style_icons_btn').on('click', function() {
		var body_style = $('#body_style_widget_new_body_style_selection').val();
		var available = $('#body_style_widget_new_body_style_available').val();
		var unavailable = $('#body_style_widget_new_body_style_unavailable').val();
		if (body_style == '' || available == '' || unavailable == '') {
			alert('You must select a body style, an available icon and an unavailable icon.');
		} else {
			$('#body_style_widget_new_body_style_selection').css('background-color', '#0f0');
			$('#body_style_widget_new_body_style_available').css('background-color', '#0f0');
			$('#body_style_widget_new_body_style_unavailable').css('background-color', '#0f0');
			$.ajax({
				type: 'POST',
				data: {action: 'cdpro_add_body_style_ico_handler', 'body_style': body_style, 'available': available, 'unavailable': unavailable},
				url: cdProCommonParams.ajaxurl,
				timeout: 7000,
				error: function() {},
				dataType: "html",
				success: function(html) {
					$('.widget_body_styles_box').append(html);
					$('#body_style_widget_new_body_style_selection').css('background-color', '#fff');
					$('#body_style_widget_new_body_style_selection').val('');
					$('#body_style_widget_new_body_style_available').css('background-color', '#fff');
					$('#body_style_widget_new_body_style_available').val('');
					$('#body_style_widget_new_body_style_unavailable').css('background-color', '#fff');
					$('#body_style_widget_new_body_style_unavailable').val('');
					$('#select_available_body_styles').val('');
					$('#select_unavailable_body_styles').val('');
				},
				error: function(html) {
					console.log(html);
					alert('An error occured. Please check console log.');
					$('#body_style_widget_new_body_style_selection').css('background-color', '#f00');
					$('#body_style_widget_new_body_style_available').css('background-color', '#f00');
					$('#body_style_widget_new_body_style_unavailable').css('background-color', '#f00');
				}
			});
		}
	});
	// end add body style icon button click
	
	$('.widget_body_style_remove').on('click', function() {
		var remove_id = $(this).data('remove');
		$('#'+remove_id).remove();
	});
	// end remove body style option button click
	$('#select_available_body_styles').on('change', function() {
		$('#body_style_widget_new_body_style_available').val($(this).val());
	});
	$('#select_unavailable_body_styles').on('change', function() {
		$('#body_style_widget_new_body_style_unavailable').val($(this).val());
	});
	
	$('#cds_cdp_style').on( 'change', function() {
		var style = $(this).val();
		var src = cdProCommonParams.plugin_path + 'images/style_' + style + '.jpg';
		$('#cds_cdp_style_img').attr( 'src', src );
		
	});
	
});