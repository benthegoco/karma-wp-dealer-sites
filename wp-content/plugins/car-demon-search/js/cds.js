// JavaScript Document
var optInit = getOptionsFromForm();
var tmp_data = [];
jQuery(document).ready(function($) {

	//= check querystring to see if a search has been run
	var cdsf_query_string = cdsf_getQueryStrings();
	if ( typeof(cdsf_query_string.car) == 'undefined' ) {
		//= if no search has been run then make sure all fields are reset
		//= this should reset the form if user has used browser back button
		if ( cdProSearchParams.reset_on_back_page == true ) {
			$('.cdsf_tb_forms input[type=text]').val('');
		}
	}

	enableSelectBoxes();
	if (cdProSearchParams.is_home == true) {
		cdsf_apply_search();
	} else {
		cdsf_apply_search();
	}

	//= Sort inventory if selected
	//= Load inventory if button is clicked
	$( "#cdsf_button_apply" ).on( "click", function() {
		cdsf_apply_search();
	});

	//= Show hidden search form
	$( "#cdsf_show_search" ).on( "click", function() {
		$('.cdsf_show_search').css({'display':'none'});
		$('.cdsf_tb_forms').css({'display':'block'});
	});

	//= Load years slider on click/mouseover hide on mouseout
	$( "#years_range" ).on( "click", function() {
		$( "#years_range" ).blur();
		if (cdProSearchParams.use_drop_downs == true || $('.cdsf_one, .cds_four').length > 0 ||  $('.cdsf_three').length > 0) {
			$( "#mileage_range_box" ).fadeOut();
			$( "#price_range_box" ).fadeOut();
		}
		$( "#year_range_box" ).fadeIn();
	});
	$( "#apply_year_cancel" ).on( "click", function() {
		$( "#year_range_box" ).fadeOut();
	});

	//= Load miles slider on mouseover hide on mouseout
	$( "#miles_range" ).on( "click", function() {
		$( "#miles_range" ).blur();
		if (cdProSearchParams.use_drop_downs == true || $('.cdsf_one, .cds_four').length > 0 ||  $('.cdsf_three').length > 0) {
			$( "#price_range_box" ).fadeOut();
			$( "#year_range_box" ).fadeOut();
		}
		$( "#mileage_range_box" ).fadeIn();
	});
	$( "#apply_mileage_cancel" ).on( "click", function() {
		$( "#mileage_range_box" ).fadeOut();
	});

	//= Load price slider on mouseover hide on mouseout
	$( "#price_range" ).on( "click", function() {
		$( "#price_range" ).blur();
		if (cdProSearchParams.use_drop_downs == true || $('.cdsf_one, .cds_four').length > 0 ||  $('.cdsf_three').length > 0) {
			$( "#year_range_box" ).fadeOut();
			$( "#mileage_range_box" ).fadeOut();
		}
		$( "#price_range_box" ).fadeIn();
	});
	$( "#apply_price_cancel" ).on( "click", function() {
		$( "#price_range_box" ).fadeOut();
	});

	//= Set the years slider
	$(function() {
		$( "#years-slider" ).slider({
		  range: true,
		  animate: true,
		  min: parseFloat(cdProSearchParams.min_year),
		  max: parseFloat(cdProSearchParams.max_year),
		  values: [ parseFloat(cdProSearchParams.min_year_start), parseFloat(cdProSearchParams.max_year_start) ],
			  slide: function( event, ui ) {
				$( "#years_range" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
				$( "#search_dropdown_Min_years" ).val(ui.values[ 0 ]);
				$( "#search_dropdown_Max_years" ).val(ui.values[ 1 ]);
			  }
	});
	$( "#years_range" ).val( $( "#years-slider" ).slider( "values", 0 ) +
		  " - " + $( "#years-slider" ).slider( "values", 1 ) );
	});
	
	//= Set the price slider
	$(function() {
		$( "#price-slider" ).slider({
		  range: true,
		  animate: true,
		  min: parseFloat(cdProSearchParams.min_price),
		  max: parseFloat(cdProSearchParams.max_price),
		  step: 500,
		  values: [ parseFloat(cdProSearchParams.min_price_start), parseFloat(cdProSearchParams.max_price_start) ],
		  slide: function( event, ui ) {
			$( "#price_range" ).val( cdProSearchParams.currency_before + ui.values[ 0 ] + cdProSearchParams.currency_after + " - " + cdProSearchParams.currency_before + ui.values[ 1 ] + cdProSearchParams.currency_after);
			$( "#search_dropdown_Min_price" ).val(ui.values[ 0 ]);
			$( "#search_dropdown_Max_price" ).val(ui.values[ 1 ]);
		  }
	});
	
	$( "#price_range" ).val( cdProSearchParams.currency_before + $( "#price-slider" ).slider( "values", 0 ) +
		  cdProSearchParams.currency_after + " - " + cdProSearchParams.currency_before + $( "#price-slider" ).slider( "values", 1 ) + cdProSearchParams.currency_after );
	});

	//= Set the mileage slider
	$(function() {
		$( "#mileage-slider" ).slider({
		  range: true,
		  min: parseFloat(cdProSearchParams.min_miles),
		  max: parseFloat(cdProSearchParams.max_miles),
       	  animate: true,
		  step: 25000,
		  values: [ parseFloat(cdProSearchParams.min_miles_start), parseFloat(cdProSearchParams.max_miles_start) ],
		  slide: function( event, ui ) {
			$( "#miles_range" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			$( "#search_dropdown_miles_Min" ).val(ui.values[ 0 ]);
			$( "#search_dropdown_miles_Max" ).val(ui.values[ 1 ]);
		  }
	});

	$( "#miles_range" ).val( $( "#mileage-slider" ).slider( "values", 0 ) +
		  " - " + $( "#mileage-slider" ).slider( "values", 1 ) );
	});
	
	$( "#reset_cdsf_filters" ).on( "click", function() {
		cdsf_reset_all_filters(this);
	});
	
	$( ".cdsf_apply" ).on( "click", function() {
		cdsf_apply_search();
		$("#reset_cdsf_filters").css("background", "#b00");
		$("#reset_cdsf_filters").css("color", "#fff");
	});
	
	if (cdProSearchParams.use_drop_downs == true || $('.cdsf_one, .cds_four').length > 0 ||  $('.cdsf_three').length > 0) {
		
		$('.selectOptions').on('mouseleave', function() {
			$(this).css('display', 'none');
		});
		$('#year_range_box').on('mouseleave', function() {
			$(this).css('display', 'none');
		});
		$('#price_range_box').on('mouseleave', function() {
			$(this).css('display', 'none');
		});
		$('#mileage_range_box').on('mouseleave', function() {
			$(this).css('display', 'none');
		});
	}

});

function cdsf_reset_all_filters(fld) {
	//= Reset the text boxes that store default data
	//= jQuery("#cdsf_tb_forms").trigger('reset');

	fld_id = jQuery(fld).parent().attr('id');
	
	if (fld_id != 'search_model_options') {
		jQuery('#search_make').val(cdProSearchParams.all_str);
		jQuery( "#search_make_selected" ).html(cdProSearchParams.all_str);
	}

//	jQuery('#search_location').val('ALL');
	jQuery('#search_condition').val(cdProSearchParams.all_str);
	jQuery('#search_model').val(cdProSearchParams.all_str);
	jQuery('#search_dropdown_body').val(cdProSearchParams.all_str);

	//= Reset div values
//	jQuery( "#search_location_selected" ).html('ALL');
	jQuery( "#search_condition_selected" ).html(cdProSearchParams.all_str);
	jQuery( "#search_model_selected" ).html(cdProSearchParams.all_str);
	jQuery( "#search_year_selected" ).html(cdProSearchParams.all_str);
	jQuery( "#search_body_style_selected" ).html(cdProSearchParams.all_str);

	//= Reset the sliders back to defaults
	var $years_slider_reset = jQuery("#years-slider");
	$years_slider_reset.slider("values", 0, cdProSearchParams.min_year_start);
	$years_slider_reset.slider("values", 1, cdProSearchParams.max_year_start);
	jQuery('#search_dropdown_Min_years').val(cdProSearchParams.min_year_start);
	jQuery('#search_dropdown_Max_years').val(cdProSearchParams.max_year_start);
	jQuery( "#years_range" ).val( cdProSearchParams.min_year_start + " - " + cdProSearchParams.max_year_start );
	
	var $price_slider_reset = jQuery("#price-slider");
	$price_slider_reset.slider("values", 0, cdProSearchParams.min_price);
	$price_slider_reset.slider("values", 1, cdProSearchParams.max_price);
	jQuery('#search_dropdown_Min_price').val(cdProSearchParams.min_price);
	jQuery('#search_dropdown_Max_price').val(cdProSearchParams.max_price);
	jQuery( "#prices_range" ).val( cdProSearchParams.min_prices + " - " + cdProSearchParams.max_prices );
	
	var $mileage_slider_reset = jQuery("#mileage-slider");
	$mileage_slider_reset.slider("values", 0, cdProSearchParams.min_miles);
	$mileage_slider_reset.slider("values", 1, cdProSearchParams.max_miles);
	jQuery('#search_dropdown_miles_Min').val(cdProSearchParams.min_miles);
	jQuery('#search_dropdown_miles_Max').val(cdProSearchParams.max_miles);
	jQuery( "#miles_range" ).val( cdProSearchParams.min_miles + " - " + cdProSearchParams.max_miles );
	
	cdsf_apply_search();
	jQuery("#reset_cdsf_filters").css("display", "none");
//	jQuery("#reset_cdsf_filters").css("background", "transparent");
//	jQuery("#reset_cdsf_filters").css("color", "transparent");
}

function nothing(page_index, jq) {
	var old_page_index = document.getElementById('page_index').innerHTML;
	document.getElementById('page_index').innerHTML = page_index;
	jQuery( "#cdsf_results" ).html( '' );
}

function getOptionsFromForm(){
	var opt = {callback: nothing};
	opt['items_per_page'] = cdProSearchParams.items_per_page;
	opt['num_display_entries'] = cdProSearchParams.num_display_entries;
	opt['num_edge_entries'] = cdProSearchParams.num_edge_entries;
	opt['prev_text'] = cdProSearchParams.prev_text;
	opt['next_text'] = cdProSearchParams.next_text;
	// Avoid html injections in this demo
	var htmlspecialchars ={ "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;"}
	jQuery.each(htmlspecialchars, function(k,v){
		opt.prev_text = opt.prev_text.replace(k,v);
		opt.next_text = opt.next_text.replace(k,v);
	})
	return opt;
}

function cdsf_apply_search() {
	//= We have to add this chunk of code so IE will play nice
	if (!Array.prototype.filter)
	{
	  Array.prototype.filter = function(fun /*, thisp*/)
	  {
		var len = this.length;
		if (typeof fun != "function")
		  throw new TypeError();
	
		var res = new Array();
		var thisp = arguments[1];
		for (var i = 0; i < len; i++)
		{
		  if (i in this)
		  {
			var val = this[i]; // in case fun mutates this
			if (fun.call(thisp, val, i, this))
			  res.push(val);
		  }
		}
	
		return res;
	  };
	}
	//= End of IE patch	
	jQuery( "#cdsf_results" ).html( '' );
	//= Make sure the file is unique each time so it never goes in the cahce
	if (cdProSearchParams.save_cache == 1) {
		var json_url = cdProSearchParams.jsonurl+'?nocache=' + (new Date()).getTime();
	} else {
		var json_url = cdProSearchParams.jsonurl;
	}
	jQuery.getJSON( json_url, function( data ) {
	  var cnt = [];
	  var location = [];
	  var condition = [];
	  var makes = [];
	  var models = [];
	  var years = [];
	  var body_styles = [];
	  var trim_levels = [];
	  var transmissions = [];
	  var sort_by = jQuery( "#order_by" ).val();
	  if (sort_by == '_price_value') {sort_by = 'price';}
	  if (sort_by == '_mileage_value') {sort_by = 'mileage';}
	  var order_by_dir = jQuery( "#order_by_dir" ).val();
	  if (order_by_dir == 'false') order_by_dir = false;
	  if (order_by_dir == 'true') order_by_dir = true;
	  //= Sort by selected options
	  cdsf_sortResults(data, sort_by, order_by_dir);
/*
		//= Filter for price
		  var price_min = jQuery('#search_dropdown_Min_price').val();
		  var price_min = parseInt(price_min) - 1;
		  var price_max = jQuery('#search_dropdown_Max_price').val();
		  var price_max = parseInt(price_max) + 1;
		  data = data.filter(function(elem) {
			  return parseInt(elem.price) < parseInt(price_max) && parseInt(elem.price) > parseInt(price_min);
		  });
		//= Filter for miles
		  var miles_min = jQuery('#search_dropdown_miles_Min').val();
		  var miles_min = parseInt(miles_min) - 1;
		  var miles_max = jQuery('#search_dropdown_miles_Max').val();
		  var miles_max = parseInt(miles_max) + 1;
		  data = data.filter(function(elem) {
			  return parseInt(elem.mileage) < parseInt(miles_max) && parseInt(elem.mileage) > parseInt(miles_min);
		  });
		//= Filter for year
		  var year_min = jQuery('#search_dropdown_Min_years').val();
		  var year_min = parseInt(year_min) - 1;
		  var year_max = jQuery('#search_dropdown_Max_years').val();
// Review year_max value
		  var year_max = parseInt(year_max) + 2;
		  data = data.filter(function(elem) {
			  return parseInt(elem.year) < parseInt(year_max) && parseInt(elem.year) > parseInt(year_min);
		  });
*/
		//= Filter for location
		  var search_location = jQuery('#search_location').val();
		  if (search_location != '' && search_location != cdProSearchParams.all_str && search_location != cdProSearchParams.labels.location) {
			  var filter_location = true;
			  data = data.filter(function(elem) {
				  return elem.a == search_location;
			  });
		  } else {
			  jQuery('#search_location').val('');
			  var filter_location = false;			  	
		  }
		//= Filter for condition
		  var search_condition = jQuery('#search_condition').val();
		  if (search_condition != '' && search_condition != cdProSearchParams.all_str && search_condition != cdProSearchParams.labels.condition) {
			  var filter_condition = true;
			  data = data.filter(function(elem) {
				  return elem.e == search_condition;
			  });
		  } else {
			  jQuery('#search_condition').val('');
			  var filter_condition = false;			  	
		  }

		//= Filter for year
		  var search_year = jQuery('#search_year').val();
		  if (search_year != '' && search_year != cdProSearchParams.all_str && search_year != cdProSearchParams.labels.year) {
			  var filter_year = true;
			  data = data.filter(function(elem) {
				  return elem.f == search_year;
			  });
		  } else {
			  jQuery('#search_year').val('');
			  var filter_year = false;			  	
		  }
		//= Filter for make
		  var search_make = jQuery('#search_make').val();
		  if (search_make != '' && search_make != cdProSearchParams.all_str && search_make != cdProSearchParams.all_makes) {
			  var filter_make = true;
			  data = data.filter(function(elem) {
				  return elem.b == search_make;
			  });
		  } else {
			  jQuery('#search_make').val('');
			  var filter_make = false;			  	
		  }
		//= Filter for model
		  var search_model = jQuery('#search_model').val();
		  if (search_model != '' && search_model != cdProSearchParams.all_str && search_model != cdProSearchParams.all_models) {
				var filter_model = true;
				data = data.filter(function(elem) {
					if (elem.c == search_model) {
						jQuery('#search_make').val(elem.b);
					}
					return elem.c == search_model;
				});
		  } else {
			  jQuery('#search_model').val('');
			  var filter_model = false;
		  }
		//= Filter for trim_level
		  var search_trim_level = jQuery('#search_trim_level').val();

		  if (search_trim_level != '' && search_trim_level != cdProSearchParams.all_str && search_trim_level != cdProSearchParams.all_trim_levels) {
				var filter_trim_level = true;
				data = data.filter(function(elem) {
					if (elem.h == search_trim_level) {
						jQuery('#search_trim_level').val(elem.h);
					}
					return elem.h == search_trim_level;
				});
		  } else {
			  jQuery('#search_trim_level').val('');
			  var filter_trim_level = false;
		  }
		//= Filter for transmission
		  var search_transmission = jQuery('#search_transmission').val();
		  if (search_transmission != '' && search_transmission != cdProSearchParams.all_str && search_transmission != cdProSearchParams.all_transmissions) {
				var filter_transmission = true;
				data = data.filter(function(elem) {
					if (elem.i == search_transmission) {
						jQuery('#search_transmission').val(elem.i);
					}
					return elem.i == search_transmission;
				});
		  } else {
			  jQuery('#search_transmission').val('');
			  var filter_transmission = false;
		  }
		//= Filter for body_style
		  var search_body_style = jQuery('#search_dropdown_body').val();
		  if (search_body_style != '' && search_body_style != cdProSearchParams.all_str && search_body_style != cdProSearchParams.labels.body_style) {
			  var filter_body_style = true;
			  data = data.filter(function(elem) {
				  return elem.d == search_body_style;
			  });
		  } else {
			  jQuery('#search_dropdown_body').val('');
			  var filter_body_style = false;
		  }
		  
	jQuery.each( data, function( key, val ) {
		jQuery.each( val, function( item_key, item ) {
			  //= Update the search fields, get an array of the current existing items
			  //= Start with cnt
			  if (item_key == 'g') {
				  cnt.push( item );
			  }
			  if (item_key == 'a') {
				  location.push( item );
			  }
			  if (item_key == 'e') {
				  condition.push( item );
			  }
			  if (item_key == 'b') {
				  makes.push( item );
			  }
			  if (item_key == 'c') {
				  models.push( item );
			  }
			  if (item_key == 'f') {
				  years.push( item );
			  }
			  if (item_key == 'd') {
				  body_styles.push( item );
			  }
			  if (item_key == 'h') {
				  trim_levels.push( item );
			  }
			  if (item_key == 'i') {
				  transmissions.push( item );
			  }
		  });
	  });
	  //= Get current vehicle list
	  tmp_data = data;
	  //= Update the location field
	  var new_location = cdsf_remove_dups_and_count(location, cnt, data, cdProSearchParams.labels.location);
	  new_location = '<span class="selectOption cdsf_hide_options apply_hide" value="hide">Hide</span>' + new_location;
	  jQuery( "#search_location_options" ).html( new_location );

	  //= Update the condition field
	  var new_condition = cdsf_remove_dups_and_count(condition, cnt, data, cdProSearchParams.labels.condition);
	  new_condition = '<span class="selectOption cdsf_hide_options apply_hide" value="hide">Hide</span>' + new_condition;
	  jQuery( "#search_condition_options" ).html( new_condition );

	  //= Update the year field
	  var new_year = cdsf_remove_dups_and_count(years, cnt, data, cdProSearchParams.labels.year);
	  new_year = '<span class="selectOption cdsf_hide_options apply_hide" value="hide">Hide</span>' + new_year;
	  jQuery( "#search_year_options" ).html( new_year );

	  //= Update the make field
	  var new_makes = cdsf_remove_dups_and_count(makes, cnt, data, cdProSearchParams.all_makes);
	  new_makes = '<span class="selectOption cdsf_hide_options apply_hide" value="hide">Hide</span>' + new_makes;
	  jQuery( "#search_make_options" ).html( new_makes );

	  //= Update the model field
	  var new_models = cdsf_remove_dups_and_count(models, cnt, data, cdProSearchParams.all_models);
	  new_models = '<span class="selectOption cdsf_hide_options apply_hide" value="hide">Hide</span>' + new_models;
	  jQuery( "#search_model_options" ).html( new_models );

	  //= Update the trim_level field
	  var new_trim_levels = cdsf_remove_dups_and_count(trim_levels, cnt, data, cdProSearchParams.all_trim_levels);
	  new_trim_levels = '<span class="selectOption cdsf_hide_options apply_hide" value="hide">Hide</span>' + new_trim_levels;
	  jQuery( "#search_trim_level_options" ).html( new_trim_levels );

	  //= Update the transmission field
	  var new_transmissions = cdsf_remove_dups_and_count(transmissions, cnt, data, cdProSearchParams.all_transmissions);
	  new_transmissions = '<span class="selectOption cdsf_hide_options apply_hide" value="hide">Hide</span>' + new_transmissions;
	  jQuery( "#search_transmission_options" ).html( new_transmissions );

	  //= Update the body style field
	  var new_body_style = cdsf_remove_dups_and_count(body_styles, cnt, data, cdProSearchParams.labels.body_style);
	  new_body_style = '<span class="selectOption cdsf_hide_options apply_hide" value="hide">Hide</span>' + new_body_style;
	  jQuery( "#search-body_style" ).html( new_body_style );

	  var current_model = jQuery('#search_model_selected').html();
	  if (current_model != '' && current_model != 'ALL' && current_model != cdProSearchParams.all_models) {
		  var set_make_default = jQuery('#search-make').children('div.selectOptions').children('span.selectOption:nth-child(3)').html();
		  jQuery('#search_make_selected').html(set_make_default);			
	  }
	});

}

function cdsf_sortResults(items, prop, asc) {
    list = items.sort(function(a, b) {
        if (asc) return (parseFloat(a[prop]) > parseFloat(b[prop])) ? 1 : ((parseFloat(a[prop]) < parseFloat(b[prop])) ? -1 : 0);
        else return (parseFloat(b[prop]) > parseFloat(a[prop])) ? 1 : ((parseFloat(b[prop]) < parseFloat(a[prop])) ? -1 : 0);
    });
	return list;
}

function cdsf_sortResults_alpha(items, prop, asc) {
	list = items.sort(function(a, b) {
		if (a < b) return -1;
		if (a > b) return 1;
		return 0;
	});
	return list;
}

function cdsf_make_row( elem ) {
	var row = "<span class='selectOption' value='" + elem + "()'>" + elem + "</span>";
	return row;
}

function cdsf_make_array_row( elem ) {
	var row = [];
	row.push( "<input class='array' value='"+ elem +"'>");
	return row;
}

/*
function cdsf_remove_dups_and_count(a,i_cnt,y) {
	var x = new Object; //OBJECT
	var s = '';
    for(i=0; i<a.length; i++) {
		var is_nan = isNaN(x[a[i]]);
		if (is_nan) {
			x[a[i]] = parseFloat(i_cnt[i]);
		} else {
			x[a[i]] = parseFloat(x[a[i]]) + parseFloat(i_cnt[i]);
		}
	}

	s += "<span class='selectOption' value='ALL'>ALL</span>";
     //EACH VALUE AND ITS NUMBERS OF OCCURANCE IN THE FULL ARRAY 
    for(var i in x) {
	  s += "<span class='selectOption' value='" + i+" ("+x[i]+")" + "'>" + i+" ("+x[i]+")</span>";
    }

	return s;
}
*/
function cdsf_remove_dups_and_count(a,i_cnt,y,label) {
	var x = new Object; //OBJECT
	var s = '';
	var y = [];
	var w = '';
    for(i=0; i<a.length; i++) {
		var is_nan = isNaN(x[a[i]]);
		if (is_nan) {
			x[a[i]] = parseFloat(i_cnt[i]);
		} else {
			x[a[i]] = parseFloat(x[a[i]]) + parseFloat(i_cnt[i]);
		}
	}

	w += "<span class='selectOption' value='"+ label +"'>"+ label +"</span>";

     //EACH VALUE AND ITS NUMBERS OF OCCURANCE IN THE FULL ARRAY 
	var c = 0;
    for(var i in x) {
	  ++c;
	  //s += "<span class='selectOption' value='" + i+" ("+x[i]+")" + "'>" + i+" <span class='cdii_count'>("+x[i]+")</span></span>";

	  if (cdProSearchParams.hide_count == 'on') {
		  y[c] = i;
	  } else {
		  y[c] = i+" ("+x[i]+")";
	  }
    }

	var t = cdsf_sortResults_alpha(y);

	var ar_len = t.length;

    for(var i in t) {
		if (isNaN(i)) {
		} else {
	    	w += "<span class='selectOption' value='" + t[i] + "'>" + t[i] + "</span>";
		}
    }

	return w;
}

function cdsf_urldecode(str) {
   return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}

function enableSelectBoxes() {
	//=This is where the magic happens when you click on the options
	jQuery('div.selectBox').each(function(){

		jQuery(this).children('span.selected').html(jQuery(this).children('div.selectOptions').children('span.selectOption:first').html());
		jQuery(this).attr('value',jQuery(this).children('div.selectOptions').children('span.selectOption:first').attr('value'));

		var cdsf_dropdown = function(this_fld) {
			var current_options = jQuery(this_fld).parent().children('div.selectOptions').html();
			current_options = jQuery.trim(current_options);
			// If no options have been loaded then go get options - remove the xxx to activate
			if (current_options == 'xxx') {
				var spin = cdProSearchParams.car_demon_path+"images/wpspin_light.gif";			
				var loading = '<img src="'+spin+'" style="margin-left:10px;margin-right:10px;margin-top:10px;" />Fetching';
				jQuery(this_fld).parent().children('div.selectOptions').html(loading);
				var fld_id = jQuery(this_fld).parent().get(0).id;
				var fld = fld_id.replace("search-","");
				get_search_data(fld, jQuery(this_fld).parent());
			}

			if(jQuery(this_fld).parent().children('div.selectOptions').css('display') == 'none'){
				if (cdProSearchParams.use_drop_downs == true || jQuery('.cdsf_one, .cds_four').length > 0 ||  jQuery('.cdsf_three').length > 0) {
					jQuery('div.selectOptions').css( 'display', 'none' );
				}
				jQuery(this_fld).parent().children('div.selectOptions').css('display','block');
			} else {
				jQuery(this_fld).parent().children('div.selectOptions').css('display','none');
			}	
		}

		if (cdProSearchParams.use_drop_downs != true || jQuery('.cdsf_one, .cds_four').length == 0 ||  jQuery('.cdsf_three').length == 0) {
			//#label_year_range,#label_price_range,#label_miles_range
			jQuery('.cdsf_two #label_condition,.cdsf_two #label_year,.cdsf_two #label_make,.cdsf_two #label_model,.cdsf_two #label_body_style,.cdsf_two #label_location').off().on('click', function(){

			// if the label has a + or - then switch them to denote open and closed
			var label_val = jQuery(this).html();
			var n = label_val.indexOf("+");
			
			if (n > 0) {
				var label_val = label_val.replace("+", "-");
			} else {
				n = label_val.indexOf("-");
				if (n > 0) {
					var label_val = label_val.replace("-", "+");
				}
			}
			jQuery(this).html(label_val);

				if (jQuery(this).parent().children('div.selectBox').css('display') == 'none') {
					jQuery(this).parent().children('div.selectBox').slideDown();
				} else {
					jQuery(this).parent().children('div.selectBox').slideUp();
				}

			});
		}

		jQuery(this).children('span.selected,span.selectArrow').click(function(){
			cdsf_dropdown(this);
		});

		jQuery(this).on("click", "span.selectOption", function(event){		
			var str = jQuery(this).attr('value');
			var val_array = str.split('(');
			var search_val = val_array[0];
			search_val = jQuery.trim(search_val);
			if (cdProSearchParams.use_drop_downs == true || jQuery('.cdsf_one, .cds_four').length > 0 ||  jQuery('.cdsf_three').length > 0) {
				if (search_val != cdProSearchParams.all_str) {
					jQuery(this).parent().css('display','none');
				}
				if (search_val == 'hide') {
					return true;
				}
			}
			jQuery(this).closest('div.selectBox').attr('value',jQuery(this).attr('value'));
			jQuery(this).parent().siblings('span.selected').html(jQuery(this).html());
			//= Alert the selected value for debug
			//= alert(jQuery(this).attr('value'));
			//= Find out what field it is and update the correct form field
			var this_field = jQuery(this).closest('div.selectBox').attr('id');
				if (this_field == 'search-location') {
					jQuery('#search_location').val(search_val);
				}
				if (this_field == 'search-condition') {
					jQuery('#search_condition').val(search_val);
				}
				if (this_field == 'search-year') {
					jQuery('#search_year').val(search_val);
				}
				if (this_field == 'search-make') {
					jQuery('#search_make').val(search_val);
				}
				if (this_field == 'search-model') {
					jQuery('#search_model').val(search_val);
				}
				if (this_field == 'search-trim_level') {
					jQuery('#search_trim_level').val(search_val);
				}
				if (this_field == 'search-transmission') {
					jQuery('#search_transmission').val(search_val);
				}
				if (this_field == 'search-year') {
					jQuery('#search_year').val(search_val);
				}
				if (this_field == 'search-body-style') {
					jQuery('#search_dropdown_body').val(search_val);
				}
			if (search_val == cdProSearchParams.all_str) {
				cdsf_reset_all_filters(this);	
			} else {
				cdsf_apply_search();
				jQuery("#reset_cdsf_filters").css("background", "#b00");
				jQuery("#reset_cdsf_filters").css("color", "#fff");
				jQuery("#reset_cdsf_filters").css("display", "block");
			}

		});
	});
}//-->

function get_search_data(fld, fld_object) {
	//=Disable form if needed, then re-enable after load
	var options = {
		 type: "POST",
		 url: cdProSearchParams.ajaxurl,
		 data: {'action': 'car_demon_as_handler', '_name': 'as_'+fld, '_value': ''},
		 contentType: "application/x-www-form-urlencoded",
		 dataType: "json",
		 success: function(msg) {
			 jQuery("#search_make").html("");
			 var returnedArray = msg;
			 var new_body = '';
			 fld_object.children('div.selectOptions').empty();
			 for (i = 0; i < returnedArray.length; i++) {
				for ( key in returnedArray[i] ) {	
					new_body = '<span class="selectOption" value="'+returnedArray[i][key]+'">'+returnedArray[i][key]+'</span>';
					fld_object.children('div.selectOptions').append(new_body);
				}
				fld_object.children('div.selectOptions').find('span.selectOption').on('click', function () {
					jQuery(this).parent().css('display','none');
					jQuery(this).closest('div.selectBox').attr('value',jQuery(this).attr('value'));
					jQuery(this).parent().siblings('span.selected').html(jQuery(this).html());
				});
			 }
		 }
	 };
	 jQuery.ajax(options);
}
function cdsf_createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function cdsf_readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function cdsf_eraseCookie(name) {
	cdsf_createCookie(name,"",-1);
}

function cdsf_getQueryStrings() { 
	var assoc  = {};
	var decode = function (s) {
		var str = decodeURIComponent(s.replace(/\+/g, " "));
		str = decodeURIComponent(str.replace(/\-/g, " "));
		return str;
	};

	var queryString = location.search.substring(1); 
	var keyValues = queryString.split('&'); 
	for(var i in keyValues) { 
		var key = keyValues[i].split('=');
		if (key.length > 1) {
		  assoc[decode(key[0])] = decode(key[1]);
		}
	} 
	return assoc; 
}

function remove_search(fld, val, query_string, form_action) {
	query_string = query_string.replace( '+', ' ' );
	query_string = decodeURI(query_string);
	remove_this = "&"+fld+"="+val;
	var reg = new RegExp(remove_this,"g");
	var query_string = query_string.replace(reg, "");
	if (form_action != '' && typeof(form_action) != 'undefined') {
		window.location = form_action+'\\?'+query_string;
	} else {
		window.location = cdSearchParams.search_url+'\\?s=cars&'+query_string;
	}
}