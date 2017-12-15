/**
 * API and helper functions for the GUI on Toolset shortcodes.
 *
 * @since 2.5.4
 * @package Toolset
 */

var Toolset = Toolset || {};

/**
 * -------------------------------------
 * Shortcode GUI
 * -------------------------------------
 */

Toolset.shortcodeManager = function( $ ) {
	
	var self = this;
	
	/**
	 * Shortcodes GUI API version.
	 *
	 * Access to it using the API methods, from inside this object:
	 * - self.getShortcodeGuiApiVersion
	 * 
	 * Access to it using the API hooks, from the outside world:
	 * - toolset-filter-get-shortcode-gui-api-version
	 *
	 * @since 2.5.4
	 */
	self.apiVersion = 254000;
	
	/**
	 * Get the current shortcodes GUI API version.
	 *
	 * @see toolset-filter-get-shortcode-gui-api-version
	 *
	 * @since 2.5.4
	 */
	self.getShortcodeGuiApiVersion = function( version ) {
		return self.apiVersion;
	};
	
	/**
	 * Dialog rendering helpers, mainly size calculators.
	 *
	 * @since 2.5.4
	 */
	self.dialogMinWidth = 870;
	self.calculateDialogMaxWidth = function() {
		return ( $( window ).width() - 200 );
	};
	self.calculateDialogMaxHeight = function() {
		return ( $( window ).height() - 100 );
	};
	
	/**
	 * The current GUI API action to be performed. Can be 'insert', 'create', 'save', 'append', 'edit', 'skip'.
	 *
	 * Access to it using the API methods, from inside this object:
	 * - self.getShortcodeGuiAction
	 * - self.setShortcodeGuiAction
	 * 
	 * Access to it using the API hooks, from the outside world:
	 * - toolset-filter-get-shortcode-gui-action
	 * - toolset-action-set-shortcode-gui-action
	 *
	 * @since 2.5.4
	 */
	self.action			= 'insert';
	self.validActions	= [ 'insert', 'create', 'save', 'append', 'edit', 'skip' ];
	
	/**
	 * Get the current shortcodes GUI action.
	 *
	 * @see wpv-filter-wpv-shortcodes-gui-get-gui-action
	 *
	 * @since 2.5.4
	 */
	self.getShortcodeGuiAction = function( action ) {
		return self.action;
	};
	
	/**
	 * Set the current shortcodes GUI action.
	 *
	 * @see wpv-action-wpv-shortcodes-gui-set-gui-action
	 *
	 * @since 2.5.4
	 */
	self.setShortcodeGuiAction = function( action ) {
		if ( $.inArray( action, self.validActions ) !== -1 ) {
			self.action = action;
		}
	};
	
	/**
	 * Register the canonical Toolset hooks, both API filters and actions.
	 *
	 * @since 2.5.4
	 */
	self.initHooks = function() {
		
		/**
		 * ###############################
		 * API filters
		 * ###############################
		 */
		
		/**
		 * Return the current shortcodes GUI API version.
		 *
		 * @since 2.5.4
		 */
		Toolset.hooks.addFilter( 'toolset-filter-get-shortcode-gui-api-version', self.getShortcodeGuiApiVersion );
		
		/**
		 * Return the current shortcode GUI action: 'insert', 'create', 'save', 'append', 'edit', 'skip'.
		 *
		 * @since 2.5.4
		 */
		Toolset.hooks.addFilter( 'toolset-filter-get-shortcode-gui-action', self.getShortcodeGuiAction );
		
		/**
		 * Validate a shortcode attributes container.
		 *
		 * @since 2.5.4
		 */
		Toolset.hooks.addFilter( 'toolset-filter-is-shortcode-attributes-container-valid', self.isShortcodeAttributesContainerValid, 10, 2 );
		
		/**
		 * Return the shortcode GUI templates.
		 *
		 * @since 2.5.4
		 */
		Toolset.hooks.addFilter( 'toolset-filter-get-shortcode-gui-templates', self.getShortcodeTemplates );
		
		/**
		 * Return the current crafted shortcode with the current dialog GUI attrbutes.
		 *
		 * @since 2.5.4
		 */
		Toolset.hooks.addFilter( 'toolset-filter-get-crafted-shortcode', self.getCraftedShortcode );
		
		/**
		 * Return the current crafted shortcode with the current dialog GUI attrbutes.
		 *
		 * @since 2.5.4
		 */
		Toolset.hooks.addFilter( 'toolset-filter-shortcode-gui-computed-attribute-values', self.resolveToolsetComboValues, 1, 2 );

        /**
         * Filter the generated shortcode to support shortcodes with different format.
         *
         * @since 2.5.4
         */
        Toolset.hooks.addFilter( 'wpv-filter-wpv-shortcodes-gui-before-do-action', self.secureShortcodeFromSanitizationIfNeeded );

        /**
         * Filter the generated shortcode to support shortcodes with different format.
         *
         * @since 2.5.4
         */
        Toolset.hooks.addFilter( 'wpv-filter-wpv-shortcodes-transform-format', self.secureShortcodeFromSanitizationIfNeeded );
		
		/**
		 * ###############################
		 * API actions
		 * ###############################
		 */
		
		/**
		 * Set the current shortcodes GUI action: 'insert', 'create', 'save', 'append', 'edit', 'skip'.
		 *
		 * @since 2.5.4
		 */
		Toolset.hooks.addAction( 'toolset-action-set-shortcode-gui-action', self.setShortcodeGuiAction );
		
		/**
		 * Act upon the generated shortcode according to the current shortcodes GUI action: 'insert', 'create', 'save', 'append', 'edit', 'skip'.
		 *
		 * @since 2.5.4
		 */
		Toolset.hooks.addAction( 'toolset-action-do-shortcode-gui-action', self.doAction );
		Toolset.hooks.addAction( 'toolset-action-do-shortcode-gui-action-create', self.doActionCreate, 1, 1 );
		Toolset.hooks.addAction( 'toolset-action-do-shortcode-gui-action-insert', self.doActionInsert, 1, 1 );
		
		Toolset.hooks.addAction( 'toolset-action-shortcode-dialog-loaded', self.initSelect2 );
		
		return self;
		
	};
	
	/**
	 * Init GUI templates.
	 *
	 * @uses wp.template
	 * @since 2.5.4
	 */
	self.templates = {};
	self.initTemplates = function() {
		self.templates.dialog = wp.template( 'toolset-shortcode-gui' );
		self.templates.attributeWrapper = wp.template( 'toolset-shortcode-attribute-wrapper' );
		self.templates.attributes = {
			text: wp.template( 'toolset-shortcode-attribute-text' ),
			radio: wp.template( 'toolset-shortcode-attribute-radio' ),
			select: wp.template( 'toolset-shortcode-attribute-select' ),
			select2: wp.template( 'toolset-shortcode-attribute-select2' ),
			ajaxSelect2: wp.template( 'toolset-shortcode-attribute-ajaxSelect2' ),
			post: wp.template( 'toolset-shortcode-attribute-post-selector' ),
			user: wp.template( 'toolset-shortcode-attribute-user-selector' )
		};
		return self;
	}
	
	self.getShortcodeTemplates = function( templates ) {
		return self.templates;
	};
	
	/**
	 * Init GUI dialogs.
	 *
	 * @uses jQuery.dialog
	 * @since 2.5.4
	 */
	self.dialogs = {};
	self.dialogs.target = null;
	
	self.shortcodeDialogSpinnerContent = $(
		'<div style="min-height: 150px;">' +
		'<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; ">' +
		'<div class="ajax-loader"></div>' +
		'<p>' + toolset_shortcode_i18n.action.loading + '</p>' +
		'</div>' +
		'</div>'
	);
	
	self.initDialogs = function() {
		
		/**
		 * Canonical dialog to generate Toolset shortcodes.
		 *
		 * @since 2.5.4
		 */
		if ( ! $( '#js-toolset-shortcode-generator-target-dialog' ).length ) {
			$( 'body' ).append( '<div id="js-toolset-shortcode-generator-target-dialog" class="toolset-shortcode-gui-dialog-container js-toolset-shortcode-gui-dialog-container"></div>' );
		}
		self.dialogs.target = $( '#js-toolset-shortcode-generator-target-dialog' ).dialog({
			autoOpen:	false,
			modal:		true,
			width:		self.dialogMinWidth,
			title:		toolset_shortcode_i18n.title.generated,
			resizable:	false,
			draggable:	false,
			show: {
				effect:		"blind",
				duration:	800
			},
			create: function( event, ui ) {
				$( event.target ).parent().css( 'position', 'fixed' );
			},
			open: function( event, ui ) {
				$( '#js-toolset-shortcode-generator-target' )
					.html( $( this ).data( 'shortcode' ) )
					.focus();
				$('body').addClass('modal-open');
			},
			close: function( event, ui ) {
				$( 'body' ).removeClass( 'modal-open' );
				self.setShortcodeGuiAction( 'insert' );
				$( this ).dialog( 'close' );
			}
		});
		
		return self;
	};
	
	$( document ).on( 'change', 'input.js-toolset-shortcode-gui-item-selector', function() {
		var checkedSelector = $( this ).val();
		$( '.js-toolset-shortcode-gui-item-selector-has-related' ).each( function() {
			var hasRelatedContainer = $( this );
			if ( $( 'input.js-toolset-shortcode-gui-item-selector:checked', hasRelatedContainer ).val() == checkedSelector ) {
				$( '.js-toolset-shortcode-gui-item-selector-is-related', hasRelatedContainer ).slideDown( 'fast' );
			} else {
				$( '.js-toolset-shortcode-gui-item-selector-is-related', hasRelatedContainer ).slideUp( 'fast' );
			}
		});
	});
	
	self.initSelect2 = function() {
		$( '.js-toolset-shortcode-gui-dialog-container .js-toolset-shortcode-gui-field-select2' ).each( function() {
			var selector = $( this ),
				selectorParent = selector.closest( '.js-toolset-shortcode-gui-dialog-container' );
			
			selector
				.addClass( 'js-toolset-shortcode-gui-field-select2-inited' )
				.css( { width: '100%' } )
				.toolset_select2(
					{ 
						width:				'resolve',
						dropdownAutoWidth:	true, 
						dropdownParent:		selectorParent,
						placeholder:		selector.data( 'placeholder' )
					}
				)
				.data( 'toolset_select2' )
					.$dropdown
						.addClass( 'toolset_select2-dropdown-in-dialog' );
		});
		
		$( '.js-toolset-shortcode-gui-dialog-container .js-toolset-shortcode-gui-field-ajax-select2' ).each( function() {
			var selector = $( this ),
				selectorParent = selector.closest( '.js-toolset-shortcode-gui-dialog-container' );
			
			selector
				.addClass( 'js-toolset-shortcode-gui-field-select2-inited' )
				.css( { width: '100%' } )
				.toolset_select2(
					{ 
						width:				'resolve',
						dropdownAutoWidth:	true, 
						dropdownParent:		selectorParent,
						placeholder:		selector.data( 'placeholder' ),
						ajax: {
							url: toolset_shortcode_i18n.ajaxurl + '?action=' + selector.data( 'action' ) + '&nonce=' + selector.data( 'nonce' ),
							dataType: 'json',
							delay: 250,
							type: 'post',
							data: function( params ) {
								return {
									s:          params.term,
									page:       params.page,
								};
							},
							processResults: function( response, params ) {
								params.page = params.page || 1;
								if ( response.success ) {
									return {
										results: response.data,
									};
								}
								return {
									results: [],
								};
							},
							cache: false
						}
					}
				)
				.data( 'toolset_select2' )
					.$dropdown
						.addClass( 'toolset_select2-dropdown-in-dialog' );
		});
	};
	
	/**
	 * Clean validation errors on input change.
	 *
	 * @since 2.5.4
	 */
	$( document ).on( 'change keyup input cut paste', '.js-toolset-shortcode-gui-dialog-container input, .js-toolset-shortcode-gui-dialog-container select', function() {
		$( this ).removeClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
	});
	
	$( document ).on( 'change', 'input.js-shortcode-gui-field:radio', function() {
		var checkedValue = $( this ).val(),
			attribute = $( this ).closest( '.js-toolset-shortcode-gui-attribute-wrapper' ).data( 'attribute' ),
			comboAttributeWrapper = $( '.js-toolset-shortcode-gui-attribute-wrapper-for-toolsetCombo\\:' + attribute );
		
		if ( comboAttributeWrapper.length == 0 ) {
			return;
		}
		
		if ( 'toolsetCombo' == checkedValue ) {
			comboAttributeWrapper.slideDown( 'fast' );
		} else {
			comboAttributeWrapper.slideUp( 'fast' );
		}
	});
	
	/**
	 * Validation patterns.
	 *
	 * @since 2.5.4
	 */
	self.validationPatterns = {
		number: /^[0-9]+$/,
		numberList: /^\d+(?:,\d+)*$/,
		numberExtended: /^(-1|[0-9]+)$/,
		url: /^(https?):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
		
	};
	
	self.isShortcodeAttributesContainerValid = function( status, container ) {
		return self.validateShortcodeAttributes( container );
	}
	
	/**
	 * Check required shortcode attributes while crafting the shortcode.
	 *
	 * @since 2.5.4
	 */
	self.requireShortcodeAttributes = function( evaluatedContainer ) {
		var valid = true;
		
		evaluatedContainer.find( '.js-shortcode-gui-field.js-toolset-shortcode-gui-required' ).each( function() {
			var requiredAttribute = $( this ),
				requiredAttributeIsValid = true;
	
			// Here we are checking for empty text inputs and selects with the default empty option selected.
			if ( 
				null === requiredAttribute.val() 
				|| '' == requiredAttribute.val() 
			) {
				requiredAttribute.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
				requiredAttributeIsValid = false;
				
				if ( requiredAttribute.hasClass( 'toolset_select2-hidden-accessible' ) ) {
					requiredAttribute
						.toolset_select2()
							.data( 'toolset_select2' )
								.$selection
									.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
				}
				
			}
			if ( ! requiredAttributeIsValid ) {
				valid = false;
				/*
				error_container
					.wpvToolsetMessage({
						text: wpv_shortcodes_gui_texts.attr_empty,
						type: 'error',
						inline: false,
						stay: true
					});
				// Hack to allow more than one error message per filter
				error_container
					.data( 'message-box', null )
					.data( 'has_message', false );
				*/
			}
		});
		
		evaluatedContainer.find( 'input.js-shortcode-gui-field:radio:checked' ).each( function() {
			var checkedValue = $( this ).val(),
				attribute = $( this ).closest( '.js-toolset-shortcode-gui-attribute-wrapper' ).data( 'attribute' ),
				comboAttributeWrapper = $( '.js-toolset-shortcode-gui-attribute-wrapper-for-toolsetCombo\\:' + attribute );
				
			if (
				'toolsetCombo' == checkedValue 
				&& comboAttributeWrapper.length > 0 
			) {
				var comboAttributeActualSelector = comboAttributeWrapper.find( '.js-shortcode-gui-field' );
				if (
					null == comboAttributeActualSelector.val() 
					|| '' == comboAttributeActualSelector.val()  
				) {
					comboAttributeActualSelector.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
					if ( comboAttributeActualSelector.hasClass( 'toolset_select2-hidden-accessible' ) ) {
						comboAttributeActualSelector
							.toolset_select2()
								.data( 'toolset_select2' )
									.$selection
										.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
					}
					valid = false;
				}
			}
		});
		
		return valid;
	};
	
	/**
	 * Validate shortcode attributes before crafting the final shortcode.
	 *
	 * @since 2.5.4
	 */
	self.validateShortcodeAttributes = function( evaluatedContainer ) {
		var valid = true;
		
		valid = self.requireShortcodeAttributes( evaluatedContainer );
		if ( ! valid ) {
			return false;
		}
		/*
		$evaluatedContainer.find( 'input:text' ).each( function() {
			var thiz = $( this ),
				thiz_val = thiz.val(),
				thiz_type = thiz.data( 'type' ),
				thiz_message = '',
				thiz_valid = true;
			if ( ! thiz.hasClass( 'js-toolset-shortcode-gui-invalid-attr' ) ) {
				switch ( thiz_type ) {
					case 'number':
						if (
							self.numeric_natural_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_number_invalid;
						}
						break;
					case 'numberextended':
						if (
							self.numeric_natural_extended_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_number_invalid;
						}
						break;
					case 'numberlist':
						if (
							self.numeric_natural_list_pattern.test( thiz_val.replace(/\s+/g, '') ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_numberlist_invalid;
						}
						break;
					case 'year':
						if (
							self.year_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_year_invalid;
						}
						break;
					case 'month':
						if (
							self.month_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_month_invalid;
						}
						break;
					case 'week':
						if (
							self.week_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_week_invalid;
						}
						break;
					case 'day':
						if (
							self.day_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_day_invalid;
						}
						break;
					case 'hour':
						if (
							self.hour_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_hour_invalid;
						}
						break;
					case 'minute':
						if (
							self.minute_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_minute_invalid;
						}
						break;
					case 'second':
						if (
							self.second_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_second_invalid;
						}
						break;
					case 'dayofyear':
						if (
							self.dayofyear_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_dayofyear_invalid;
						}
						break;
					case 'dayofweek':
						if (
							self.dayofweek_pattern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_dayofweek_invalid;
						}
						break;
					case 'url':
						if (
							self.url_patern.test( thiz_val ) == false
							&& thiz_val != ''
						) {
							thiz_valid = false;
							thiz.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
							thiz_message = wpv_shortcodes_gui_texts.attr_url_invalid;
						}
						break;
				}
				if ( ! thiz_valid ) {
					valid = false;
					error_container
						.wpvToolsetMessage({
							text: thiz_message,
							type: 'error',
							inline: false,
							stay: true
						});
					// Hack to allow more than one error message per filter
					error_container
						.data( 'message-box', null )
						.data( 'has_message', false );
				}
			}
		});
		*/
		// Special case: item selector tab
		if (
			$( '.js-toolset-shortcode-gui-item-selector:checked', evaluatedContainer ).length > 0
			&& 'object_id' == $( '.js-toolset-shortcode-gui-item-selector:checked', evaluatedContainer ).val()
		) {
			var itemSelection = $( '.js-toolset-shortcode-gui-item-selector_object_id', evaluatedContainer ),
				itemSelectionId = itemSelection.val(),
				itemSelectionValid = true;
				//$itemSelectionMessage = '';
			if ( '' == itemSelectionId ) {
				itemSelectionValid = false;
				itemSelection.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
				if ( itemSelection.hasClass( 'toolset_select2-hidden-accessible' ) ) {
					itemSelection
						.toolset_select2()
							.data( 'toolset_select2' )
								.$selection
									.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
				}
				//$itemSelectionMessage = cred_shortcode_i18n.validation.mandatory;
			} else if ( self.validationPatterns.number.test( itemSelectionId ) == false ) {
				itemSelectionValid = false;
				itemSelection.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
				if ( itemSelection.hasClass( 'toolset_select2-hidden-accessible' ) ) {
					itemSelection
						.toolset_select2()
							.data( 'toolset_select2' )
								.$selection
									.addClass( 'toolset-shortcode-gui-invalid-attr js-toolset-shortcode-gui-invalid-attr' );
				}
				//$itemSelectionMessage = cred_shortcode_i18n.validation.number;
			}
			if ( ! itemSelectionValid ) {
				valid = false;
			}
		}
		return valid;
	};
	
	self.getCraftedShortcode = function( defaultValue ) {
		return self.craftShortcode();
	}
	
	self.craftShortcode = function() {
		var shortcodeName = $('.js-toolset-shortcode-gui-shortcode-handle').val(),
			shortcodeAttributeString = '',
			shortcodeAttributeValues = {},
			shortcodeRawAttributeValues = {},
			shortcodeContent = '',
			shortcodeToInsert = '',
			shortcodeIsValid = self.validateShortcodeAttributes( $( '.js-toolset-shortcode-gui-dialog-container' ) );
		
		if ( ! shortcodeIsValid ) {
			return;
		}
		
		$( '.js-toolset-shortcode-gui-attribute-wrapper', '.js-toolset-shortcode-gui-dialog-container' ).each( function() {
			var attributeWrapper = $( this ),
				shortcodeAttributeKey = attributeWrapper.data( 'attribute' ),
				shortcodeAttributeValue = '',
				shortcodeAttributeDefaultValue = attributeWrapper.data( 'default' );
			switch ( attributeWrapper.data('type') ) {
				case 'post':
				case 'user':
					shortcodeAttributeValue = $( '.js-toolset-shortcode-gui-item-selector:checked', attributeWrapper ).val();
					switch( shortcodeAttributeValue ) {
						case 'current':
							shortcodeAttributeValue = false;
							break;
						case 'parent':
							if ( shortcodeAttributeValue ) {
								shortcodeAttributeValue = '$' + shortcodeAttributeValue;
							}
							break;
						case 'related':
							shortcodeAttributeValue = $( '[name="related_object"]:checked', attributeWrapper ).val();
							if ( shortcodeAttributeValue ) {
								shortcodeAttributeValue = '$' + shortcodeAttributeValue;
							}
							break;
						case 'object_id':
							shortcodeAttributeValue = $( '.js-toolset-shortcode-gui-item-selector_object_id', attributeWrapper ).val();
						default:
					}
					break;
				case 'select':
					shortcodeAttributeValue = $('option:checked', attributeWrapper ).val();
					break;
				case 'radio':
				case 'radiohtml':
					shortcodeAttributeValue = $('input:checked', attributeWrapper ).val();
					break;
				case 'checkbox':
					shortcodeAttributeValue = $('input:checked', attributeWrapper ).val();
					break;
				default:
					shortcodeAttributeValue = $('input', attributeWrapper ).val();
			}

			
			/**
			 * Fix true/false from data attribute for shortcodeAttributeDefaultValue
			 */
			if ( 'boolean' == typeof shortcodeAttributeDefaultValue ) {
				shortcodeAttributeDefaultValue = shortcodeAttributeDefaultValue ? 'true' :'false';
			}
			
			shortcodeRawAttributeValues[ shortcodeAttributeKey ] = shortcodeAttributeValue;
			/**
			 * Filter value
			 */
			shortcodeAttributeValue = Toolset.hooks.applyFilters( 'toolset-filter-shortcode-gui-attribute-value', shortcodeAttributeValue, { shortcode: shortcodeName, attribute: shortcodeAttributeKey } );
			shortcodeAttributeValue = Toolset.hooks.applyFilters( 'toolset-filter-shortcode-gui-' + shortcodeName + '-attribute-' + shortcodeAttributeKey + '-value', shortcodeAttributeValue, { shortcode: shortcodeName, attribute: shortcodeAttributeKey } );
			
			/**
			 * Add to the shortcodeAttributeValues object
			 */
			if (
				shortcodeAttributeValue
				&& shortcodeAttributeValue != shortcodeAttributeDefaultValue
			) {
				shortcodeAttributeValues[ shortcodeAttributeKey ] = shortcodeAttributeValue;
			}
		});
		// Filter pairs key => value
		shortcodeAttributeValues = Toolset.hooks.applyFilters( 'toolset-filter-shortcode-gui-computed-attribute-values', shortcodeAttributeValues, { shortcode: shortcodeName, rawAttributes: shortcodeRawAttributeValues } );
		shortcodeAttributeValues = Toolset.hooks.applyFilters( 'toolset-filter-shortcode-gui-' + shortcodeName + '-computed-attribute-values', shortcodeAttributeValues, { shortcode: shortcodeName, rawAttributes: shortcodeRawAttributeValues } );
		
		// Compose the shortcodeAttributeString string
		_.each( shortcodeAttributeValues, function( value, key ) {
			if ( value ) {
				shortcodeAttributeString += " " + key + '="' + value + '"';
			}
		});
		shortcodeToInsert = '[' + shortcodeName + shortcodeAttributeString + ']';
		
		/**
		 * Shortcodes with content
		 */
		if ( $( '.js-toolset-shortcode-gui-content' ).length > 0 ) {
			shortcodeContent = $( '.js-toolset-shortcode-gui-content' ).val();
			shortcodeToInsert += shortcodeContent;
			shortcodeToInsert += '[/' + shortcodeName + ']';
		}
		
		return shortcodeToInsert;
		
	};
	
	self.resolveToolsetComboValues = function( shortcodeAttributeValues, data ) {
		var resolvedAttributes = {};
		_.each( shortcodeAttributeValues, function( value, key ) {
			if ( 'toolsetCombo' == value ) {
				resolvedAttributes[ key ] = data.rawAttributes[ 'toolsetCombo:' + key ];
			} else if ( /^toolsetCombo/.test( key ) ) {
				resolvedAttributes[ key ] = false;
			} else {
				resolvedAttributes[ key ] = value;
			}
		});
		return resolvedAttributes;
	};
	
	self.doAction = function( shortcode ) {
		
		var action = self.action;
		
		/**
		 * Custom action executed before performing the shortcodes GUI action.
		 *
		 * @param string shortcode The shortcode to action upon
		 * @param string self.action The action to execute
		 *
		 * @since 2.5.4
		 */
		Toolset.hooks.doAction( 'toolset-action-before-do-shortcode-gui-action', shortcode, action );
		shortcode = Toolset.hooks.applyFilters( 'toolset-filter-before-do-shortcode-gui-action', shortcode, action );
		
		switch ( action ) {
			case 'skip':
			case 'create':
			case 'append':
			case 'edit':
			case 'save':
				Toolset.hooks.doAction( 'toolset-action-do-shortcode-gui-action-' + action, shortcode );
				break;
			case 'insert':
			default:
				Toolset.hooks.doAction( 'toolset-action-do-shortcode-gui-action-insert', shortcode );
				break;
		}
		
		Toolset.hooks.doAction( 'toolset-action-after-do-shortcode-gui-action', shortcode, action );
		
		// Set the shortcodes GUI action to its default 'insert'
		self.setShortcodeGuiAction( 'insert' );
	};
	
	self.doActionCreate = function( shortcode ) {
		self.dialogs.target
			.data( 'shortcode', shortcode )
			.dialog( 'open' ).dialog({
				maxHeight:	self.calculateDialogMaxHeight(),
				maxWidth:	self.calculateDialogMaxWidth(),
				position:	{
					my:			"center top+50",
					at:			"center top",
					of:			window,
					collision:	"none"
				}
		});
	};
	
	self.doActionInsert = function( shortcode ) {
		window.icl_editor.insert( shortcode );
	};

    self.secureShortcodeFromSanitizationIfNeeded = function( shortcode_data ) {
        var shortcode_string;
        if ( typeof( shortcode_data ) === 'object' ) {
            shortcode_string = shortcode_data.shortcode;
        } else {
            shortcode_string = shortcode_data;
        }

        /**
         * In Views 2.5.0, we introduced support for shortcodes using placeholders instead of bracket.
         * The selected placeholder for the left bracket "[" was chosen to be the "{!{" and the selected
		 * placeholder for the right bracket "]" was chosen to be the "}!}". This was done to allow the use
         * of Toolset shortcodes inside the various page builder modules fields.
         * Here, we are offering the shortcodes created by the Toolset Shortcodes admin bar menu, in their
         * new format, with the brackets replaced with placeholders but only on the Content Template edit page.
         * where the Visual Composer builder is used.
         * For all the other needed pages (native post editor with each page builder enabled), this is handled
         * elsewhere.
         **/
        if (
            (
                // In the Content Template edit page with WPBakery Page Builder (former Visual Composer) enabled.
                'toolset_page_ct-editor' === window.pagenow
                && 'undefined' !== typeof window.vc
            )
            || (
                $.inArray( window.adminpage, [ 'post-php', 'post-new-php' ] ) !== -1
                && (
                    (
                        // Divi builder is enabled.
                        'undefined' !== typeof window.et_builder
                        && $( '#et_pb_toggle_builder.et_pb_builder_is_used' ).length > 0
                    )
                    || (
                        // WPBakery Page Builder (former Visual Composer) is enabled.
                        'undefined' !== typeof window.vc
                        && $( '.composer-switch.vc_backend-status' ).length > 0
                    )
                    || (
                        // Frontend WPBakery Page Builder (former Visual Composer) is enabled.
                        'undefined' !== typeof window.vc
                        && (
                            $( '#vc_navbar.vc_navgar-frontend' ).length > 0
                            // Adding a second condition to catch the case that they will fix the typo in the class name.
                            || $( '#vc_navbar.vc_navbar-frontend' ).length > 0
                        )
                    )
                    || (
                        // Fusion Builder is enabled.
                        'undefined' !== typeof window.FusionPageBuilder
                        && $( '#fusion_toggle_builder.fusion_builder_is_active' ).length > 0
                    )
                )
            )
        ) {
            shortcode_string = shortcode_string.replace( /\[/g, '{!{' ).replace( /]/g, '}!}' ).replace( /"/g, '\'' );
        }

        if ( typeof( shortcode_data ) === 'object' ) {
            shortcode_data.shortcode = shortcode_string;
        } else {
            shortcode_data = shortcode_string;
        }

        return shortcode_data;
    }
	
	/**
	 * Init main method:
	 * - Init templates
	 * - Init dialogs.
	 * - Init API hooks.
	 *
	 * @since 2.5.4
	 */
	self.init = function() {
		
		self.initTemplates()
			.initDialogs()
			.initHooks()
		
	};

	self.init();
	
}

jQuery( document ).ready( function( $ ) {
	Toolset.shortcodeGUI = new Toolset.shortcodeManager( $ );
});