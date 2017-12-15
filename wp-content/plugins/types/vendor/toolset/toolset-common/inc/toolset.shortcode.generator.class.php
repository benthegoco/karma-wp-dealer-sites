<?php

/**
* Toolset_Shortcode_Generator
*
* Generic class to manage the Toolset shortcodes admin bar entry.
*
* Use the filter toolset_shortcode_generator_register_item before admin_init:99
* Register your items as follows:
* 		add_filter( 'toolset_shortcode_generator_register_item', 'register_my_shortcodes_in_the_shortcode_generator' );
* 		function register_my_shortcodes_in_the_shortcode_generator( $registered_sections ) {
* 			// Do your logic here to determine whether you need to add your section or not, and check if you need specific assets
* 			// In case you do, register as follows:
* 			$registered_sections['section-id'] = array(
* 				'id'		=> 'section-id',						// The ID of the item
*				'title'		=> __( 'My fields', 'my-textdomain' ),	// The title for the item
*				'href'		=> '#my_anchor',						// The href attribute for the link of the item
*				'parent'	=> 'toolset-shortcodes',				// Set the parent item as the known 'toolset-shortcodes'
*				'meta'		=> 'js-my-classname'					// Cloassname for the li container of the item
* 			);
* 			return $registered_sections;
* 		}
*
* Note that you will have to take care of displaying the dialog after clicking on the item, and deal with what is should do.
*
* @since 1.9
*/
    
abstract class Toolset_Shortcode_Generator {

	private static $registered_admin_bar_items	= array();
	private static $can_show_admin_bar_item		= false;
	private static $target_dialog_added			= false;
	
	function __construct() {
		
		add_action( 'admin_init',		array( $this, 'register_shortcodes_admin_bar_items' ), 99 );
	    add_action( 'admin_bar_menu',	array( $this, 'display_shortcodes_admin_bar_items' ), 99 );
		add_action( 'admin_footer',		array( $this, 'display_shortcodes_target_dialog' ) );
		
		add_action( 'toolset_action_require_shortcodes_templates',		array( $this, 'print_shortcodes_templates' ) );
		
		add_action( 'wp_ajax_toolset_select2_suggest_posts_by_title',			array( $this, 'toolset_select2_suggest_posts_by_title' ) );
		add_action( 'wp_ajax_nopriv_toolset_select2_suggest_posts_by_title',	array( $this, 'toolset_select2_suggest_posts_by_title' ) );
		add_action( 'wp_ajax_toolset_select2_suggest_users',		array( $this, 'toolset_select2_suggest_users' ) );
		add_action( 'wp_ajax_nopriv_toolset_select2_suggest_users',	array( $this, 'toolset_select2_suggest_users' ) );
	}
	
	public function register_shortcodes_admin_bar_items() {
		
		// Only register sections if the Admin Bar item is to be shown.
		$toolset_settings = Toolset_Settings::get_instance();
		$toolset_shortcodes_generator = ( isset( $toolset_settings['shortcodes_generator'] ) && in_array( $toolset_settings['shortcodes_generator'], array( 'unset', 'disable', 'editor', 'always' ) ) ) ? $toolset_settings['shortcodes_generator'] : 'unset';
		if ( $toolset_shortcodes_generator == 'unset' ) {
			$toolset_shortcodes_generator = apply_filters( 'toolset_filter_force_unset_shortcode_generator_option', $toolset_shortcodes_generator );
		}
		$register_section = false;
		switch ( $toolset_shortcodes_generator ) {
			case 'always':
				$register_section = true;
				break;
			case 'editor':
				$register_section = $this->is_admin_editor_page();
				break;
		}

		/**
		 * Filters whether to forcibly show or hide the Toolset Shortcodes menu on the admin bar.
		 *
		 * Returning true to this hook will forcibly show the Toolset Shortcodes menu on the admin bar, ignoring
		 * the value of the relevant Toolset setting. Returning false to this hook will forcibly hide the Toolset
		 * Shortcodes menu on the admin bar, ignoring the value of the relevant Toolset setting.
		 *
		 * @since 2.5.0
		 *
		 * @param bool $register_section Whether the Toolset Shortcodes menu on the admin bar should be forcibly shown or hidden.
		 */
		$register_section = apply_filters( 'toolset_filter_force_shortcode_generator_display', $register_section );
		
		if ( ! $register_section ) {
			return;
		}
		
		// Now that we know that it will be shown, collect the registered items.
		$registered_items = self::$registered_admin_bar_items;
		$registered_items = apply_filters( 'toolset_shortcode_generator_register_item', $registered_items );
		self::$registered_admin_bar_items = $registered_items;
		
	}
	
	/*
	 * Add admin bar main item for shortcodes
	 */
	public function display_shortcodes_admin_bar_items( $wp_admin_bar ) {
		if ( ! is_admin() ) {
			return;
		}
		$registered_items = self::$registered_admin_bar_items;
		if ( empty( $registered_items ) ) {
			return;
		}
		self::$can_show_admin_bar_item = true;
	    $this->create_admin_bar_item( $wp_admin_bar, 'toolset-shortcodes', __( 'Toolset shortcodes', 'wpv-views' ), '#', false );
		foreach ( $registered_items as $item_key => $item_args ) {
			$this->create_admin_bar_item( $wp_admin_bar, $item_args['id'], $item_args['title'], $item_args['href'], $item_args['parent'], $item_args['meta'] );
		}
	}
	
	/*
	 * General function for creating admin bar menu items
	 * 
	 */
	public static function create_admin_bar_item( $wp_admin_bar, $id, $name, $href, $parent, $classes = null ) {
	    $args = array(
			'id'		=> $id,
			'title'		=> $name,
			'href'		=> $href,
			'parent'	=> $parent,
			'meta' 		=> array( 'class' => $id . '-shortcode-menu ' . $classes )
	    );
	    $wp_admin_bar->add_node( $args );
	}
	
	/**
	 * is_admin_editor_page
	 *
	 * Helper method to check whether we are on an admin editor page. 
	 * This covers edit pages for posts, terms and users, 
	 * as well as Toolset object edit pages.
	 *
	 * @since 2.3.0
	 */
	
	public function is_admin_editor_page() {
		if ( ! is_admin() ) {
			return false;
		}
		global $pagenow, $wp_version;
		$allowed_pagenow_array = array( 'post.php', 'post-new.php', 'term.php', 'user-new.php', 'user-edit.php', 'profile.php' );				
		$allowed_page_array = array( 'views-editor', 'ct-editor', 'view-archives-editor', 'dd_layouts_edit' );
		// @todo maybe add a filter here for future Toolset admin pages...
		if (
			in_array( $pagenow, $allowed_pagenow_array ) 
			|| (
				$pagenow == 'admin.php' 
				&& isset( $_GET['page'] ) 
				&& in_array( $_GET['page'], $allowed_page_array )
			)
			|| (
				// In WordPress < 4.5, the edit tag admin page is edit-tags.php?action=edit&taxonomy=category&tag_ID=X
				version_compare( $wp_version, '4.5', '<' ) 
				&& $pagenow == 'edit-tags.php' 
				&& isset( $_GET['action'] ) 
				&& $_GET['action'] == 'edit'
			)
		) {
			return true;
		}
		return false;
	}
	
	/**
	 * is_frontend_editor_page
	 *
	 * Helper method to check whether we are on an frontend editor page. 
	 * This should cover as many frontend editors as possible.
	 *
	 * @since 2.3.0
	 */
	
	public function is_frontend_editor_page() {
		if ( is_admin() ) {
			return false;
		}
		if (
			// Layouts frontend editor
			isset( $_GET['toolset_editor'] )
			// Beaver Builder frontend editor
			|| isset( $_GET['fl_builder'] ) 
			// CRED frontend editor pages, when discoverable
		) {
			return true;
		}
		return false;
	}
	
	/*
	 * Dialog Template HTML code
	 */
	public function display_shortcodes_target_dialog() {
		if ( 
			self::$can_show_admin_bar_item
			&& self::$target_dialog_added === false 
		) {
			?>
			<div class="toolset-dialog-container" style="display:none">
				<div id="js-toolset-shortcode-generator-target-dialog" class="toolset-shortcode-gui-dialog-container js-toolset-shortcode-generator-target-dialog">
					<p>
						<?php echo __( 'This is the generated shortcode, based on the settings that you have selected:', 'wpv-views' ); ?>
					</p>
					<textarea id="js-toolset-shortcode-generator-target" readonly="readonly" style="width:100%;resize:none;box-sizing:border-box;font-family:monospace;display:block;padding:5px;background-color:#ededed;border: 1px solid #ccc !important;box-shadow: none !important;"></textarea>
					<p>
						<?php echo __( 'You can now copy and paste this shortcode anywhere you want.', 'wpv-views' ); ?>
					</p>
				</div>
			</div>
			<?php
			self::$target_dialog_added = true; 
		}

	}
	
	/**
	 * Generate the shared Toolset shortcode GUI templates.
	 *
	 * @since 2.5.4
	 * @note Move this to a separated dedicated set of template files.
	 */
	public function print_shortcodes_templates() {
		
		if ( did_action( 'toolset_action_require_shortcodes_templates_done' ) ) {
			return;
		}
		
		?>
		<script type="text/html" id="tmpl-toolset-shortcode-gui">
			<input value="{{{data.shortcode}}}" class="toolset-shortcode-gui-shortcode-handle js-toolset-shortcode-gui-shortcode-handle" type="hidden" />
			<# if ( _.has( data, 'parameters' ) ) {
				_.each( data.parameters, function( parameterValue, parameterKey ) {
					#>
					<span class="toolset-shortcode-gui-attribute-wrapper js-toolset-shortcode-gui-attribute-wrapper js-toolset-shortcode-gui-attribute-wrapper-for-form" data-attribute="{{{parameterKey}}}" data-type="parameter">
						<input type="hidden" name="{{{parameterKey}}}" value="{{{parameterValue}}}" disabled="disabled" />
					</span>
					<#
				});
			} #>
			<div id="js-toolset-shortcode-gui-dialog-tabs" class="toolset-shortcode-gui-tabs js-toolset-shortcode-gui-tabs">
			<# if ( _.size( data.attributes ) > 1 ) { #>
				<ul class="js-toolset-shortcode-gui-tabs-list">
					<# _.each( data.attributes, function( attributesGroup, groupKey ) { #>
						<li>
							<a href="#{{{data.shortcode}}}-{{{groupKey}}}">{{{attributesGroup.header}}}</a>
						</li>
					<# }); #>
				</ul>
			<# } #>
				<# _.each( data.attributes, function( attributesGroup, groupKey ) { #>
					<div id="{{{data.shortcode}}}-{{{groupKey}}}">
						<h2>{{{attributesGroup.header}}}</h2>
						<# _.each( attributesGroup.fields, function( attributeData, attributeKey ) { 
							if ( _.has( data.templates, 'attributeWrapper' ) ) {
								attributeData = _.extend( { shortcode: data.shortcode, attribute: attributeKey, templates: data.templates }, attributeData );
								print( data.templates.attributeWrapper( attributeData ) );
							}
						}); #>
					</div>
				<# }); #>
			</div>
			<div class="toolset-shortcode-gui-messages js-toolset-shortcode-gui-messages"></div>
		</script>
		<script type="text/html" id="tmpl-toolset-shortcode-attribute-wrapper">
			<# 
				data = _.defaults( data, { defaultValue: '', required: false, hidden: false, placeholder: '' } ); 
				data = _.defaults( data, { defaultForceValue: data.defaultValue } ); 
			#>
			<div class="toolset-shortcode-gui-attribute-wrapper js-toolset-shortcode-gui-attribute-wrapper js-toolset-shortcode-gui-attribute-wrapper-for-{{{data.attribute}}}" data-attribute="{{{data.attribute}}}" data-type="{{{data.type}}}" data-default="{{{data.defaultValue}}}"<# if ( data.hidden ) { #> style="display:none"<# } #>>
				<# if ( _.has( data, 'label' ) ) { #>
					<h3>{{{data.label}}}</h3>
				<# } #>
				<# if ( 
					_.has( data.templates, 'attributes' ) 
					&& _.has( data.templates.attributes, data.type )
				) {
					print( data.templates.attributes[ data.type ]( data ) );
				} #>
				<# if ( _.has( data, 'description' ) ) { #>
					<p class="description">{{{data.description}}}</p>
				<# } #>
			</div>
		</script>
		<script type="text/html" id="tmpl-toolset-shortcode-attribute-text"><# //alert(data.toSource()); #>
			<input id="{{data.shortcode}}}-{{data.attribute}}}" data-type="text" class="js-shortcode-gui-field large-text<# if ( data.required ) { #> js-toolset-shortcode-gui-required<# } #>" value="{{{data.defaultForceValue}}}" type="text">
		</script>
		<script type="text/html" id="tmpl-toolset-shortcode-attribute-radio">
			<ul id="{{{data.shortcode}}}-{{{data.attribute}}}">
				<# _.each( data.options, function( optionLabel, optionKey ) { #>
					<li>
						<label>
							<input name="{{{data.shortcode}}}-{{{data.attribute}}}" value="{{{optionKey}}}" class="js-shortcode-gui-field" type="radio"<# if ( optionKey == data.defaultForceValue ) { #> checked="checked"<# } #>>
							{{{optionLabel}}}
						</lanel>
					</li>
				<# }); #>
			</ul>
		</script>
		<script type="text/html" id="tmpl-toolset-shortcode-attribute-select">
			<select id="{{{data.shortcode}}}-{{{data.attribute}}}" class="js-shortcode-gui-field">
				<# _.each( data.options, function( optionLabel, optionKey ) { #>
					<option value="{{{optionKey}}}"<# if ( optionKey == data.defaultForceValue ) { #> selected="selected"<# } #>>
						{{{optionLabel}}}
					</option>
				<# }); #>
			</select>
		</script>
		<script type="text/html" id="tmpl-toolset-shortcode-attribute-select2">
			<select id="{{{data.shortcode}}}-{{{data.attribute}}}" class="js-shortcode-gui-field js-toolset-shortcode-gui-field-select2<# if ( data.required ) { #> js-toolset-shortcode-gui-required<# } #>">
				<#
				if ( _.has( data, 'options' ) ) {
					_.each( data.options, function( optionLabel, optionKey ) { 
					#>
					<option value="{{{optionKey}}}"<# if ( optionKey == data.defaultForceValue ) { #> selected="selected"<# } #>>
						{{{optionLabel}}}
					</option>
					<# 
					});
				} 
				#>
			</select>
		</script>
		<script type="text/html" id="tmpl-toolset-shortcode-attribute-ajaxSelect2">
			<select 
				id="{{{data.shortcode}}}-{{{data.attribute}}}" 
				class="js-shortcode-gui-field js-toolset-shortcode-gui-field-ajax-select2<# if ( data.required ) { #> js-toolset-shortcode-gui-required<# } #>" 
				data-action="{{{data.action}}}" 
				data-nonce="{{{data.nonce}}}"
				data-placeholder="{{{data.placeholder}}}"
				>
			</select>
		</script>
		<script type="text/html" id="tmpl-toolset-shortcode-attribute-post-selector">
			<ul id="{{data.shortcode}}}-{{data.attribute}}}">
				<li>
					<label>
						<input type="radio" name="{{{data.shortcode}}}-select-target-post" class="toolset-shortcode-gui-item-selector js-toolset-shortcode-gui-item-selector" value="current" checked="checked" />
						<?php
						if ( 
							isset( $_GET['page'] )
							&& in_array( $_GET['page'], array( 'views-editor', 'view-archives-editor' ) )
						) {
							_e( 'Edit current post in the loop', 'wp-cred' );
						} else {
							_e( 'Edit current post', 'wp-cred' );
						}
						?>
					</label>
				</li>
				<li class="js-toolset-shortcode-gui-item-selector-has-related">
					<label>
						<input type="radio" name="{{{data.shortcode}}}-select-target-post" class="toolset-shortcode-gui-item-selector js-toolset-shortcode-gui-item-selector" value="object_id" />
						<?php _e( 'Edit another post', 'wp-cred' ); ?>
						<div class="toolset-shortcode-gui-item-selector-is-related js-toolset-shortcode-gui-item-selector-is-related" style="display:none">
							<select id="toolset-shortcode-gui-item-selector-object_id" class="js-toolset-shortcode-gui-item-selector_object_id js-toolset-shortcode-gui-field-ajax-select2" data-action="toolset_select2_suggest_posts_by_title" data-nonce="" data-placeholder="<?php echo esc_attr( 'Search for posts by title', 'wpv-views' ); ?>">
							</select>
						</div>
					</label>
				</li>
			</ul>
		</script>
		<script type="text/html" id="tmpl-toolset-shortcode-attribute-user-selector">
			<ul id="{{data.shortcode}}}-{{data.attribute}}}">
				<li>
					<label>
						<input type="radio" name="{{{data.shortcode}}}-select-target-user" class="toolset-shortcode-gui-item-selector js-toolset-shortcode-gui-item-selector" value="current" checked="checked" />
						<?php _e( 'Edit current logged in user', 'wp-cred' ); ?>
					</label>
				</li>
				<li class="js-toolset-shortcode-gui-item-selector-has-related">
					<label>
						<input type="radio" name="{{{data.shortcode}}}-select-target-user" class="toolset-shortcode-gui-item-selector js-toolset-shortcode-gui-item-selector" value="object_id" />
						<?php _e( 'Edit another user', 'wp-cred' ); ?>
						<div class="toolset-shortcode-gui-item-selector-is-related js-toolset-shortcode-gui-item-selector-is-related" style="display:none">
							<select id="toolset-shortcode-gui-item-selector-object_id" class="js-toolset-shortcode-gui-item-selector_object_id js-toolset-shortcode-gui-field-ajax-select2" data-action="toolset_select2_suggest_users" data-nonce="" data-placeholder="<?php echo esc_attr( 'Search for users', 'wpv-views' ); ?>">
							</select>
						</div>
					</label>
				</li>
			</ul>
		</script>
		<?php
		
		do_action( 'toolset_action_require_shortcodes_templates_done' );
		
	}
	
	public function toolset_select2_suggest_posts_by_title() {
		if ( ! isset( $_POST['s'] ) ) {
			$output = array(
				'message' => __( 'Wrong or missing query.', 'wpv-views' ),
			);
			wp_send_json_error( $output );
		}
		
		global $wpdb;
		
		if ( method_exists( $wpdb, 'esc_like' ) ) { 
			$s = '%' . $wpdb->esc_like( $_POST['s'] ) . '%'; 
		} else { 
			$s = '%' . like_escape( esc_sql( $_POST['s'] ) ) . '%'; 
		}
		
		$toolset_post_type_exclude = new Toolset_Post_Type_Exclude_List();
		$toolset_post_type_exclude_list = $toolset_post_type_exclude->get();
		$toolset_post_type_exclude_list_string = "'" . implode( "', '", $toolset_post_type_exclude_list ) . "'";
		
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID, post_type, post_title 
				FROM {$wpdb->posts} 
				WHERE post_title LIKE %s 
				AND post_status = %s 
				AND post_type NOT IN ( {$toolset_post_type_exclude_list_string} ) 
				LIMIT 0, 15",
				$s,
				'publish'
			)
		);
		
		if ( 
			isset( $results ) 
			&& ! empty( $results ) 
		) {
			$output = array();
			if ( is_array( $results ) ) {
				foreach ( $results as $result ) {
					$output[] = array(
						'text' => $result->post_title . ' (' . $result->post_type . ')',
						'id' => $result->ID,
					);
				}
				wp_send_json_success( $output );
			}
		} else {
			$output = array(
				'message' => __( 'Error while retrieving result.', 'wpv-views' ),
			);
			wp_send_json_error( $output );
		}
	}
	
	public function toolset_select2_suggest_users() {
		if ( ! isset( $_POST['s'] ) ) {
			$output = array(
				'message' => __( 'Wrong or missing query.', 'wpv-views' ),
			);
			wp_send_json_error( $output );
		}
		
		global $wpdb;
		
		
		if ( method_exists( $wpdb, 'esc_like' ) ) { 
			$s = '%' . $wpdb->esc_like( $_POST['s'] ) . '%'; 
		} else { 
			$s = '%' . like_escape( esc_sql( $_POST['s'] ) ) . '%'; 
		} 
		
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID, display_name 
				FROM {$wpdb->users} 
				WHERE display_name LIKE %s 
				OR user_login LIKE %s 
				OR user_nicename LIKE %s
				LIMIT 0, 15",
				$s,
				$s,
				$s
			)
		);
		
		if ( 
			isset( $results ) 
			&& ! empty( $results ) 
		) {
			$output = array();
			if ( is_array( $results ) ) {
				foreach ( $results as $result ) {
					$output[] = array(
						'text' => $result->display_name,
						'id' => $result->ID,
					);
				}
				wp_send_json_success( $output );
			}
		} else {
			$output = array(
				'message' => __( 'Error while retrieving result.', 'wpv-views' ),
			);
			wp_send_json_error( $output );
		}
	}
    
}