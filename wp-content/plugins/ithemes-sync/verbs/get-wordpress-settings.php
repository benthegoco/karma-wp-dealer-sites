<?php

/*
Implementation of the get-wordpress-settngs verb.
Written by Glenn Ansley iThemes.com
Version 1.0.0

Version History
	1.0.0 - 2017-04-01 - Chris Jean
		Initial version
*/


class Ithemes_Sync_Verb_Get_Wordpress_Settings extends Ithemes_Sync_Verb {
	public static $name = 'get-wordpress-settings';
	public static $description = 'Retrieve Settings from the WordPress options system along with supporting data from various WP functions.';
	public static $status_element_name = 'wp-settings';
	public static $show_in_status_by_default = false;
	
	private $default_arguments = array();
	
	private $response = array();
	
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		$this->set_versions();
		$this->set_options();
		$this->set_user_roles();
		$this->set_timezone_format();
		$this->set_timezone_format_string();
		$this->set_timezone_options();
		$this->set_date_format_options();
		$this->set_time_format_options();
		$this->set_day_of_week_options();
		$this->set_default_category_options();
		$this->set_post_format_strings();
		$this->set_page_on_front_select_options();
		$this->set_page_for_posts_select_options();
		
		return $this->response;
	}
	
	/**
	 * Populates version data for various techs
	 */
	private function set_versions() {
		$this->response['versions'] = array(
			'wp'         => $GLOBALS['wp_version'],
			'db'         => get_option( 'db_version' ),
			'db-initial' => get_option( 'initial_db_version' ),
			'php'        => phpversion(),
		);
	}

	/**
	 * Options we need
	 */
	private function set_options() {
		$this->response['options'] = array(
			'is_multisite'                  => defined('MULTISITE' ) && MULTISITE === true,
			// General
			'blogname'                      => get_option( 'blogname' ),
			'blogdescription'               => get_option( 'blogdescription' ),
			'admin_email'                   => get_option( 'admin_email' ),
			'users_can_register'            => get_option( 'users_can_register' ),
			'default_role'                  => get_option( 'default_role' ),
			'date_format'                   => get_option( 'date_format' ),
			'time_format'                   => get_option( 'time_format' ),
			'start_of_week'                 => get_option( 'start_of_week' ),
			// Writing
			'use_smilies'                   => get_option( 'use_smilies' ),
			'use_balanceTags'               => get_option( 'use_balanceTags' ),
			'default_category'              => get_option( 'default_category' ),
			'default_post_format'           => get_option( 'default_post_format'),
			'link_manager_enabled'          => get_option( 'link_manager_enabled' ),
			'default_link_category'         => get_option( 'default_link_category' ),
			'blog_public'                   => get_option( 'blog_public' ),
			'ping_sites'                    => get_option( 'ping_sites' ),
			// Reading
			'show_on_front'                 => get_option( 'show_on_front' ),
			'posts_per_page'                => get_option( 'posts_per_page' ),
			'posts_per_rss'                 => get_option( 'posts_per_rss' ),
			'rss_use_excerpt'               => get_option( 'rss_use_excerpt' ),
			'blog_public'                   => get_option( 'blog_public' ),
			// Reading
			'default_pingback_flag'         => get_option( 'default_pingback_flag' ),
			'default_ping_status'           => get_option( 'default_ping_status' ),
			'default_comment_status'        => get_option( 'default_comment_status' ),
			'require_name_email'            => get_option( 'require_name_email' ),
			'comment_registration'          => get_option( 'comment_registration' ),
			'close_comments_for_old_posts'  => get_option( 'close_comments_for_old_posts' ),
			'close_comments_days_old'       => get_option( 'close_comments_days_old' ),
			'thread_comments'               => get_option( 'thread_comments' ),
			'thread_comments_depth'         => get_option( 'thread_comments_depth' ),
			'page_comments'                 => get_option( 'page_comments' ),
			'comments_per_page'             => get_option( 'comments_per_page' ),
			'default_comments_page'         => get_option( 'default_comments_page' ),
			'comment_order'                 => get_option( 'comment_order' ),
			'comment_max_links'             => get_option( 'comment_max_links' ),
			'comments_notify'               => get_option( 'comments_notify' ),
			'moderation_notify'             => get_option( 'moderation_notify' ),
			'comment_moderation'            => get_option( 'comment_moderation' ),
			'comment_whitelist'             => get_option( 'comment_whitelist' ),
			'show_avatars'                  => get_option( 'show_avatars' ),
			'avatar_rating'                 => get_option( 'avatar_rating' ),
			'avatar_default'                => get_option( 'avatar_default' ),
			// Media
			'thumbnail_size_w'              => get_option( 'thumbnail_size_w' ),
			'thumbnail_size_h'              => get_option( 'thumbnail_size_h' ),
			'thumbnail_crop'                => get_option( 'thumbnail_crop' ),
			'medium_size_w'                 => get_option( 'medium_size_w' ),
			'medium_size_h'                 => get_option( 'medium_size_h' ),
			'large_size_w'                  => get_option( 'large_size_w' ),
			'large_size_h'                  => get_option( 'large_size_h' ),
			'uploads_use_yearmonth_folders' => get_option( 'uploads_use_yearmonth_folders' ),
		);
	}

	/**
	 * Return a list of user roles.
	 *
	 * slug => Display Name
	 *
	 * @since 1.0.0
	 * @return array
	 */
	private function set_user_roles() {
		require_once( ABSPATH . 'wp-admin/includes/user.php' );
		$roles = get_editable_roles();
		foreach( $roles as $i => $d ) {
			$user_roles[$i] = $d['name'];
		}
		$this->response['user_roles'] = empty( $user_roles ) ? array() : $user_roles;
	}

	/**
	 * Set the timezone format
	 */
	private function set_timezone_format() {
		$this->response['timezone']['format'] = _x( 'Y-m-d H:i:s', 'timezone date format' );
	}

	/**
	 * Set the timezone format string
	 */
	private function set_timezone_format_string() {
		$this->response['timezone']['format_string'] = date_i18n( $this->response['timezone']['format'] );
	}

	/**
	 * Generate timezone select options
	 */
	private function set_timezone_options() {

		$current_offset  = get_option('gmt_offset');
		$tzstring        = get_option('timezone_string');
		$check_zone_info = true;

		// Remove old Etc mappings. Fallback to gmt_offset.
		if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
			$tzstring = ''; 
		}

		if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists
			$check_zone_info = false;
			if ( 0 == $current_offset ) {
				$tzstring = 'UTC+0';
			} else if ( $current_offset < 0 ) {
				$tzstring = 'UTC' . $current_offset;
			} else {
				$tzstring = 'UTC+' . $current_offset;
			}
		}

		$this->response['timezone']['options'] = explode("\n", wp_timezone_choice( $tzstring, get_user_locale() ));
	}

	/**
	 * Generate date format select options
	 */
	private function set_date_format_options() {
		$custom = true;
		$formats = array_unique( apply_filters( 'date_formats', array( __( 'F j, Y' ), 'Y-m-d', 'm/d/Y', 'd/m/Y' ) ) );
		foreach ( $formats as $format ) {
			$date_formats[$format] = date_i18n( $format );
			$custom = ( $format === $this->response['options']['date_format'] ) ? false : $custom;
		}
		$this->response['date_format_options'] = $date_formats;
		$this->response['date_format_custom_selected']  = $custom;
	}

	/**
	 * Generate time format select options
	 */
	private function set_time_format_options() {
		$custom  = true;
		$formats = array_unique( apply_filters( 'time_formats', array( __( 'g:i a' ), 'g:i A', 'H:i' ) ) );
		foreach ( $formats as $format ) {
			$time_formats[$format] = date_i18n( $format );
			$custom = ( $format === $this->response['options']['time_format'] ) ? false : $custom;
		}
		$this->response['time_format_options'] = $time_formats;
		$this->response['time_format_custom_selected']  = $custom;
	}

	/**
	 * Generate day of week select options
	 */
	private function set_day_of_week_options() {
		global $wp_locale;
		$options = array();

		for ($day_index = 0; $day_index <= 6; $day_index++) :
			$selected = ( get_option( 'start_of_week' ) == $day_index ) ? 'selected="selected"' : '';
            $options[] = "\n\t<option value='" . esc_attr( $day_index ) . "' $selected>" . $wp_locale->get_weekday( $day_index ) . '</option>';
		endfor;
		$this->response['day_of_week_options'] = $options;
	}
	
	/* Generate Default Category select options
	 *
	 */
	private function set_default_category_options() {
		$this->response['default_category_options'] = wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'default_category', 'orderby' => 'name', 'selected' => get_option('default_category'), 'hierarchical' => true));
	}

	/**
	 * All the Post Format strings in an array
	 */
	private function set_post_format_strings() {
		$this->response['post_format_strings'] = get_post_format_strings();
	}

	/**
	 * Generates page list options
	 */
	private function set_page_on_front_select_options() {
		$this->response['page_on_front_options'] = wp_dropdown_pages( array( 'name' => 'page_on_front', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;'     ), 'option_none_value' => '0', 'selected' => get_option( 'page_on_front' ) ) );
	}

	/**
	 * Generates page list options
	 */
	private function set_page_for_posts_select_options() {
		$this->response['page_for_posts_options'] = wp_dropdown_pages( array( 'name' => 'page_for_posts', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;'     ), 'option_none_value' => '0', 'selected' => get_option( 'page_for_posts' ) ) );
	}
}
