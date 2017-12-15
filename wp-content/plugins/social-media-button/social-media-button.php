<?php
/**!
 * Plugin Name: 	Social Media Buttons
 * Plugin URI: 		https://wordpress.org/plugins/social-media-button/
 * Description: 	A WordPress plugin that displays various social media button at Wordpress widgets area.
 * Version: 		1.2.0
 * Author: 			Sayful Islam
 * Author URI: 		http://sayfulit.com
 * Text Domain: 	social-media-button
 * Domain Path: 	/languages/
 * License: 		GPLv2 or later
 */

if (!class_exists('Social_Media_Button')):

class Social_Media_Button {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	public function __construct(){
		add_action('admin_enqueue_scripts', array( $this, 'color_picker' ) );

		$this->includes();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function color_picker( $hook )
	{
		if ('widgets.php' == $hook) {
		    wp_enqueue_style( 'wp-color-picker' );
		    wp_enqueue_script( 'wp-color-picker' );
		}
	}

	/**
	 * include widget file
	 */
	public function includes(){
		include_once( 'widget-social-media-button.php' );
	}
}

endif;

Social_Media_Button::get_instance();
