// JavaScript Document
function car_demon_fix_model() {
	var e = document.getElementById("search_model");
	var my_val = e.options[e.selectedIndex].value;
}
function car_demon_disable_form() {
	if (document.getElementById("search_year")) {
		document.getElementById("search_year").disabled = true;
	}
	if (document.getElementById("search_condition")) {
		document.getElementById("search_condition").disabled = true;
	}
	document.getElementById("search_make").disabled = true;
	document.getElementById("search_model").disabled = true;
	document.getElementById("submit_search").disabled = true;
	jQuery("#search_year").addClass('search_spinner');
	jQuery("#search_make").addClass('search_spinner');
	jQuery("#search_model").addClass('search_spinner');
}
function car_demon_enable_form() {
	if (document.getElementById("search_year")) {
		document.getElementById("search_year").disabled = false;
	}
	if (document.getElementById("search_condition")) {
		document.getElementById("search_condition").disabled = false;
	}
	document.getElementById("search_make").disabled = false;
	document.getElementById("search_model").disabled = false;
	document.getElementById("submit_search").disabled = false;
	jQuery("#search_year").removeClass('search_spinner');
	jQuery("#search_make").removeClass('search_spinner');
	jQuery("#search_model").removeClass('search_spinner');
}
jQuery( document ).ready(function($) {
	$('#search_condition').change (function(){
		car_demon_disable_form();
		var search_condition = document.getElementById("search_condition").options[document.getElementById("search_condition").selectedIndex].value;
		if (document.getElementById("search_year")) {
			var options = {
				 type: "POST",
				 url: cdSearchParams.ajaxurl,
				 data: {'action': 'car_demon_search_handler', '_name': 'search_condition', '_value': search_condition},
				 contentType: "application/x-www-form-urlencoded",
				 dataType: "json",
				 success: function(msg) {
					 $("#search_year").html("");
					 var returnedArray = msg;
					 for (i = 0; i < returnedArray.length; i++) {
						for ( key in returnedArray[i] ) {	
							$("#search_year").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
						}
					 }
				 }
			 };
			 $.ajax(options);
		}
		var options = {
			 type: "POST",
			 url: cdSearchParams.ajaxurl,
			 data: {'action': 'car_demon_search_handler', '_name': 'search_make_condition', '_value': search_condition},
			 contentType: "application/x-www-form-urlencoded",
			 dataType: "json",
			 success: function(msg) {
				 $("#search_make").html("");
				 var returnedArray = msg;
				 for (i = 0; i < returnedArray.length; i++) {
					for ( key in returnedArray[i] ) {	
						$("#search_make").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
					}
				 }
			 }
		 };
		 $.ajax(options);
		var options = {
			 type: "POST",
			 url: cdSearchParams.ajaxurl,
			 data: {'action': 'car_demon_search_handler', '_name': 'search_model_condition', '_value': search_condition},
			 contentType: "application/x-www-form-urlencoded",
			 dataType: "json",
			 success: function(msg) {
				 $("#search_model").html("");
				 var returnedArray = msg;
				 for (i = 0; i < returnedArray.length; i++) {
					for ( key in returnedArray[i] ) {	
						$("#search_model").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
					}
				 }
				car_demon_enable_form();
			 }
		 };
		 $.ajax(options);
	});
	$('#search_make').change (function(){
		car_demon_disable_form();
		var search_make = document.getElementById("search_make").options[document.getElementById("search_make").selectedIndex].value;
		var options = {
			 type: "POST",
			 url: cdSearchParams.ajaxurl,
			 data: {'action': 'car_demon_search_handler', '_name': 'search_make', '_value': search_make},
			 contentType: "application/x-www-form-urlencoded",
			 dataType: "json",
			 success: function(msg) {
				 $("#search_model").html("");
				 var returnedArray = msg;
				 for (i = 0; i < returnedArray.length; i++) {
					for ( key in returnedArray[i] ) {	
						$("#search_model").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
					}
				 }
				car_demon_enable_form();
			 }
		 };
		 $.ajax(options);
	});
	$('#search_year').change (function(){
		car_demon_disable_form();
		if (document.getElementById("search_condition")) {
			document.getElementById("search_condition").selectedIndex = 0;
		}
		var search_year = document.getElementById("search_year").options[document.getElementById("search_year").selectedIndex].value;
		var options = {
			 type: "POST",
			 url: cdSearchParams.ajaxurl,
			 data: {'action': 'car_demon_search_handler', '_name': 'search_year', '_value': search_year},
			 contentType: "application/x-www-form-urlencoded",
			 dataType: "json",
			 success: function(msg) {
				 $("#search_make").html("");
				 var returnedArray = msg;
				 for (i = 0; i < returnedArray.length; i++) {
					for ( key in returnedArray[i] ) {	
						$("#search_make").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
					}
				 }
				car_demon_enable_form();
			 }
		 };
		 $.ajax(options);
		var options = {
			 type: "POST",
			 url: cdSearchParams.ajaxurl,
			 data: {'action': 'car_demon_search_handler', '_name': 'search_year_model', '_value': search_year},
			 contentType: "application/x-www-form-urlencoded",
			 dataType: "json",
			 success: function(msg) {
				 $("#search_model").html("");
				 var returnedArray = msg;
				 for (i = 0; i < returnedArray.length; i++) {
					for ( key in returnedArray[i] ) {	
						$("#search_model").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
					}
				 }
				car_demon_enable_form();
			 }
		 };
		 $.ajax(options);
	});
	$('.advanced_search_btn').on('click', function() {
		if ($('.advanced_search').css('display') == 'none') {
			$('.advanced_search').slideDown();
		} else {
			$('.advanced_search').slideUp();
		}
	});
});
function remove_search(fld, val, query_string, result_page) {
	query_string = query_string.replace( '+', ' ' );
	query_string = decodeURI(query_string);
	remove_this = "&"+fld+"="+val;
	var reg = new RegExp(remove_this,"g");
	var query_string = query_string.replace(reg, "");
	if (result_page != '' && typeof(result_page) != 'undefined') {
		window.location = result_page+'\\?'+query_string;
	} else {
		window.location = cdSearchParams.search_url+'\\?s=cars&'+query_string;
	}
}