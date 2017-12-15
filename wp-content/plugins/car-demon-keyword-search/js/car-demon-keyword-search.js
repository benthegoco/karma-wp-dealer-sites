// JavaScript Document
jQuery(document).ready(function() {
	jQuery('#keyword_search_criteria').click(function() {
		if (document.getElementById('keyword_search_criteria').value == cdKeywordSearchParams.default_value) {
			document.getElementById('keyword_search_criteria').value = '';
		}
	}); // end click
	jQuery('#keyword_search_criteria').blur(function() {
		if (document.getElementById('keyword_search_criteria').value == '') {
			document.getElementById('keyword_search_criteria').value = cdKeywordSearchParams.default_value;
		}
	}); // end blur
});