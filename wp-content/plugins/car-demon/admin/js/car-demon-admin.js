// JavaScript Document
jQuery( document ).ready(function($) {
	$('.cd_admin_form h1').on('click', function() {
		if ($(this).next('.cd_location').css('display') == 'none') {
			$(this).next('.cd_location').slideDown();
		} else {
			$(this).next('.cd_location').slideUp();
		}
	});
	$('.cd_welcome_tab_title').on('click', function() {
		$('.cd_welcome_tab_title').removeClass('active');
		$('.cd_welcome_tab').removeClass('active');
		$(this).addClass('active');
		var tab = $(this).data('tab');
		$('#'+tab).addClass('active');
	});
	$('.create_inventory_btn').on('click', function() {
		var page_name = $('.create_inventory').val();
		var include_search = $('.create_inventory_search').prop('checked');
		if (include_search == true) {
			include_search = 'yes';
		}
		$('.cd_setup_inventory').css('background', '#0b0');
		$.ajax({
			type: 'POST',
			data: {'action': 'create_inventory_page', 'page_name': page_name, 'include_search': include_search},
			url: cdAdminParams.ajaxurl,
			timeout: 5000,
			error: function() {},
			dataType: "html",
			success: function(html){
				var new_body = html;
				$('.create_inventory_results').html(html);
				$('.cd_setup_inventory').css('background', '');
			}
		})
	});
	$('.cd_insert_samples_btn').on('click', function() {
		$('.cd_sample_inventory').css('background', '#bfb');
		$('.cd_sample_inventory').css('font-weight', 'bold');
		$('.cd_insert_samples_btn').prop('disabled', true);
		var spinner = '<img src="' + cdAdminParams.spinner + '" />';
		var msg = cdAdminParams.sample_msg;
		var qty = $('.sample_qty').val();
		$('.cd_sample_inventory').html(spinner + ' ' + msg);
		$.ajax({
			type: 'POST',
			data: {'action': 'cd_insert_sample_vehicles', 'qty': qty},
			url: cdAdminParams.ajaxurl,
			timeout: 135000,
			error: function(html) {
				$('.cd_sample_inventory').html( html );
				$('.cd_sample_inventory').css('background', '');
				$('.cd_sample_inventory').css('color', '');
				$('.cd_insert_samples_btn').prop('disabled', false);
			},
			dataType: "html",
			success: function(html){
				$('.cd_sample_inventory').html( html );
				$('.cd_sample_inventory').css('background', '');
				$('.cd_sample_inventory').css('font-weight', '');
				$('.cd_insert_samples_btn').prop('disabled', false);
			}
		})
	});
	$('.itemtitle').on('click', function() {
		if ($(this).nextAll('.itemcontent').css('display') == 'none') {
			$(this).nextAll('.itemcontent').slideDown();
		} else {
			$(this).nextAll('.itemcontent').slideUp();
		}
	});
	$( ".cd_admin_show_all").on('click', function() {
		var text = $(this).data('open-close-text');
		var status = $(this).data('status');
		var html = $(this).html();
		var option_groups = $('.cd_option_group');
		if (status == 0) {
			option_groups.slideDown();
			$(this).data('status', 1);
		} else {
			option_groups.slideUp();
			$(this).data('status', 0);		
		}
		$(this).html(text);
		$(this).data('open-close-text', html);
	});
	$( ".cd_admin_group legend").on('click', function() {
		var option_group = $(this).next('.cd_option_group');
		if (option_group.css('display') == 'none') {
			option_group.slideDown();
		} else {
			option_group.slideUp();		
		}
	});
	$( "#cd_open_description" ).click(function() {
		$("#description_tab").show( 500, function(){});
	});
	$( "#cd_close_description" ).click(function() {
		$("#description_tab").hide( 500, function(){});
	});
	$( "#cd_add_description" ).click(function() {
		$("#frm_add_description").show( 500, function(){});
	});
	$( "#cancel_description" ).click(function() {
		$("#frm_add_description").hide( 500, function(){});
	});
	
	$( ".open_tab" ).click(function() {
		if (typeof($(this).data('status')) != 'undefined') {
			if ($(this).data('status') == 'closed') {
				$(this).next('div').show( 500, function(){});
				$(this).data('status', 'open')
			} else {
				$(this).next('div').hide( 500, function(){});
				$(this).data('status', 'closed');
			}
		} else {
			$(this).next('div').show( 500, function(){});
			$(this).data('status', 'open')
		}
	});
	
	$( "#cd_close_specs" ).click(function() {
		$("#specs_tab").prev(".open_tab").data('status', 'closed');
		$("#specs_tab").hide( 500, function(){});
	});
	$( "#cd_add_specs" ).click(function() {
		$("#frm_add_specs").show( 500, function(){});
	});
	$( "#cancel_specs" ).click(function() {
		$("#frm_add_specs").hide( 500, function(){});
	});
	
	$( "#cd_close_safety" ).click(function() {
		$("#safety_tab").hide( 500, function(){});
		$("#safety_tab").prev(".open_tab").data('status', 'closed');
	});
	$( "#cd_add_safety" ).click(function() {
		$("#frm_add_safety").show( 500, function(){});
	});
	$( "#cancel_safety" ).click(function() {
		$("#frm_add_safety").hide( 500, function(){});
	});
	
	$( "#cd_close_convenience" ).click(function() {
		$("#convenience_tab").hide( 500, function(){});
		$("#convenience_tab").prev(".open_tab").data('status', 'closed');
	});
	$( "#cd_add_convenience" ).click(function() {
		$("#frm_add_convenience").show( 500, function(){});
	});
	$( "#cancel_convenience" ).click(function() {
		$("#frm_add_convenience").hide( 500, function(){});
	});
	
	$( "#cd_close_comfort" ).click(function() {
		$("#comfort_tab").hide( 500, function(){});
		$("#comfort_tab").prev(".open_tab").data('status', 'closed');
	});
	$( "#cd_add_comfort" ).click(function() {
		$("#frm_add_comfort").show( 500, function(){});
	});
	$( "#cancel_comfort" ).click(function() {
		$("#frm_add_comfort").hide( 500, function(){});
	});
	
	$( "#cd_close_entertainment" ).click(function() {
		$("#entertainment_tab").hide( 500, function(){});
		$("#entertainment_tab").prev(".open_tab").data('status', 'closed');
	});
	$( "#cd_add_entertainment" ).click(function() {
		$("#frm_add_entertainment").show( 500, function(){});
	});
	$( "#cancel_entertainment" ).click(function() {
		$("#frm_add_entertainment").hide( 500, function(){});
	});
	
	$( "#cd_close_about_us" ).click(function() {
		$("#about_us_tab").hide( 500, function(){});
		$("#about_us_tab").prev(".open_tab").data('status', 'closed');
	});
	$( "#cd_add_about_us" ).click(function() {
		$("#frm_add_about_us").show( 500, function(){});
	});
	$( "#cancel_about_us" ).click(function() {
		$("#frm_add_about_us").hide( 500, function(){});
	});
	$(".cd_dynamic_load").on('change', function() {
		if ($(this).val() == 'Yes') {
			$('.cd_auto_load').slideDown();
		} else {
			$('.cd_auto_load').slideUp();
		}
	});
	$('.cd_open_caps').on('click', function() {
		var fld_type = $(this).data('type');
		$('.cd_spec_cap_box.' + fld_type).slideToggle();
	});
	$('.reset_car_demon').on( 'click', function() {
		if ( confirm( cdAdminParams.reset_msg ) ) {
			return true;
		} else {
			return false;
		}
	});
});

function update_default_labels(fld) {
	var field = fld.id;
	var label = fld.value;
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_update_default_labels', 'field': field, 'label': label},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			var new_body = html;
			document.getElementById(field).style.background = "#99CC99";
			var delay = function() { document.getElementById(field).style.background = "" };
			setTimeout(delay, 1000);
		}
	})
	return false;
}

function show_hide_default_fields(fld) {
	var field = fld.value;
	var checked = fld.checked;
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_update_default_fields', 'field': field, 'checked': checked},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			var new_body = html;
			document.getElementById('sh_'+field).style.background = "#99CC99";
			var delay = function() { document.getElementById('sh_'+field).style.background = "" };
			setTimeout(delay, 1000);
		}
	})
	return false;
}

function add_option_group(group) {
	var group_options = document.getElementById('group_options_'+group).value;
	var title = document.getElementById('group_option_title_'+group).value;
	var fail = 0;
	if (group_options=='') {
		var fail = 1;	
	}
	if (title=='') {
		var fail = 1;	
	}
	if (fail == 0) {
		jQuery.ajax({
			type: 'POST',
			data: {'action': 'car_demon_add_option_group', 'group': group, 'title': title, 'group_options': group_options},
			url: cdAdminParams.ajaxurl,
			timeout: 5000,
			error: function() {},
			dataType: "html",
			success: function(html){
				var new_body = html;
				location.reload();
			}
		})
		return false;
	} else {
		alert(cdAdminParams.error1);
	}
}

function remove_option_group(group, group_title) {
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_remove_option_group', 'group': group, 'group_title': group_title},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			var new_body = html;
			document.getElementById('group_'+group_title).style.display = 'none';
		}
	})
	return false;
}

function update_option_group(group, group_title) {
	var group_options = document.getElementById('vehicle_option_group_items_'+group_title).value;
	var group_title = document.getElementById('vehicle_option_group_'+group_title).value;
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_update_option_group', 'group': group, 'group_title': group_title, 'group_options': group_options},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			var new_body = html;
			alert(cdAdminParams.msg_update);
		}
	})
	return false;
}

function update_car(post_id, this_fld, fld) {
	var new_value = this_fld.value;

	if ( cdAdminParams.non_numeric_price == 'No' ) {
		// clean up price fields
		if ( fld == '_msrp_value' || fld == '_rebates_value' || fld == '_discount_value' || fld == '_price_value' ) {
			// remove commas & US currency symbol
			new_value = new_value.replace(/\$|,/g, "");
			// remove non numeric characters
			new_value = new_value.replace(/\D/g,'');
			// set clean value to the form field
			this_fld.value = new_value;
			// if it still isn't numeric then set it to 0 and alert users
			if ( cd_isNumeric( new_value ) !== true ) {
				this_fld.value = '0';
				new_value = '0';
				setTimeout( function() {
					alert( cdAdminParams.bad_price_msg );
				}, 300 );
			}
		}
	}

	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_admin_update', 'post_id': post_id, 'val': new_value, 'fld': fld},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
		var new_body = html;
			this_fld.style.background = "#99CC99";
			var delay = function() { this_fld.style.background = "#FFFFFF" };
			setTimeout(delay, 1000);
			if (document.getElementById("msrp_"+post_id)) {
				var msrp = document.getElementById("msrp_"+post_id).value;
			} else {
				var msrp = 0;	
			}
			if (document.getElementById("rebate_"+post_id)) {
				var rebate = document.getElementById("rebate_"+post_id).value;
			} else {
				var rebate = 0;	
			}
			if (document.getElementById("discount_"+post_id)) {
				var discount = document.getElementById("discount_"+post_id).value;				
			} else {
				var discount = 0;	
			}
			if (document.getElementById("price_"+post_id)) {
				var price = document.getElementById("price_"+post_id).value;
			} else {
				var price = 0;	
			}
			if (msrp == "") { msrp = 0; }
			if (rebate == "") { rebate = 0; }
			if (discount == "") { discount = 0; }
			if (price == "") { price = 0; }
			msrp = parseInt(msrp);
			rebate = parseInt(rebate);
			discount = parseInt(discount);
			price = parseInt(price);
			var calc_price = msrp - rebate - discount;
			document.getElementById("calc_price_"+post_id).innerHTML = calc_price
			document.getElementById("calc_discounts_"+post_id).innerHTML = rebate + discount;
			if (price != calc_price) {
				if (msrp != 0) {
					document.getElementById("price_"+post_id).style.background = "#FFB3B3";
					document.getElementById("calc_error_"+post_id).innerHTML = "Calc Error: " + (calc_price - price) + "<br />";
				}
				else {
					document.getElementById("price_"+post_id).style.background = "#FFFFFF";
					document.getElementById("calc_error_"+post_id).innerHTML = "";
				}
			}
			else {
				document.getElementById("calc_error_"+post_id).innerHTML = "";
				document.getElementById("price_"+post_id).style.background = "#FFFFFF";
			}
		}
	})
	return false;
}
function update_car_sold(post_id, this_fld, fld) {
	var new_value = this_fld.options[this_fld.selectedIndex].value;
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_admin_update', 'post_id': post_id, 'val': new_value, 'fld': fld},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
		var new_body = html;
			this_fld.style.background = "#99CC99";
			var delay = function() { this_fld.style.background = "#FFFFFF" };
			setTimeout(delay, 1000);
		}
	})
	return false;
}
function show_custom_slide(slide_num) {
	document.getElementById("custom_slide_"+slide_num).style.display = "inline";
	document.getElementById("show_slide_"+slide_num).style.display = "none";
	document.getElementById("hide_slide_"+slide_num).style.display = "inline";
}
function hide_custom_slide(slide_num) {
	document.getElementById("custom_slide_"+slide_num).style.display = "none";
	document.getElementById("show_slide_"+slide_num).style.display = "inline";
	document.getElementById("hide_slide_"+slide_num).style.display = "none";
}
function clear_custom_slide(slide_num) {
	document.getElementById("custom_slide"+slide_num+"_title").value = "";
	document.getElementById("custom_slide"+slide_num+"_img").value = "";
	document.getElementById("custom_slide"+slide_num+"_link").value = "";
	document.getElementById("custom_slide"+slide_num+"_text").value = "";
}
function fnMoveItems(lstbxFrom,lstbxTo) {
	var varFromBox = document.all(lstbxFrom);
	var varToBox = document.all(lstbxTo); 
	if ((varFromBox != null) && (varToBox != null)) { 
		if (varFromBox.length < 1) {
			alert('There are no items in the source ListBox');
			return false;
		}
		if (varFromBox.options.selectedIndex == -1) { // when no Item is selected the index will be -1
			alert('Please select an Item to move');
			return false;
		}
		while ( varFromBox.options.selectedIndex >= 0 ) { 
			var newOption = new Option(); // Create a new instance of ListItem 
			newOption.text = varFromBox.options[varFromBox.options.selectedIndex].text; 
			newOption.value = varFromBox.options[varFromBox.options.selectedIndex].value; 
			var OldToDoBox = varToBox.value + ',';
			OldToDoBox = OldToDoBox.trim();
			if (OldToDoBox==',') {
				OldToDoBox = '';
			}
			varToBox.value = OldToDoBox + varFromBox.options[varFromBox.selectedIndex].text;
			varFromBox.remove(varFromBox.options.selectedIndex); //Remove the item from Source Listbox 
		} 
	}
	return false; 
}
function ImgError(source, pic){
	source.src = pic;
	source.onerror = '';
	return true;
}
function cd_isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}