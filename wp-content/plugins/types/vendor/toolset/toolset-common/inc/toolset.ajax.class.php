<?php

/**
 * Main AJAX call controller for Toolset.
 *
 * 
 * When DOING_AJAX, you need to run initialize() to register the callbacks, only creating an instance will not be enough.
 *
 * 
 * When implementing AJAX actions here, please follow these rules:
 *
 * 1.  All AJAX action names are automatically prefixed with 'wp_ajax_{$plugin_name}_'. Only lowercase characters
 *     and underscores can be used.
 * 
 *     $plugin_name is in this case 'toolset' but it may be different in subclasses.
 * 
 * 2.  Action names (without a prefix) should be defined as constants, and be part of array returned
 *     by get_callback_names().
 * 
 * 3.  For each action, there should be a dedicated class implementing the Toolset_Ajax_Handler_Interface. 
 * 
 *     Name of the class must be {$capitalized_plugin_name}_Ajax_Handler_{$capitalized_action_name}. 
 * 
 *     So for example, for a hook to 'wp_ajax_types_field_control_action' you need to create a class 
 *     'Types_Ajax_Handler_Field_Control_Action'.
 * 
 * 4.  All callbacks must use the ajax_begin() and ajax_finish() methods.
 * 
 * 
 * When creating subclasses, you only need to do following:
 * 
 * - Override get_plugin_slug().
 * - Override get_callback_names().
 * - Override additional_ajax_init() if you need to.
 *
 * @since m2m
 */
class Toolset_Ajax {
	
	/** Prefix for the callback method name */
	const CALLBACK_PREFIX = 'callback_';
	
	const DELIMITER = '_';

	/**
	 * @return Toolset_Ajax|false
	 */
	final public static function get_instance() {
		static $instances = array();
		$called_class = get_called_class();

		if ( isset( $instances[ $called_class ] ) ) {
			return $instances[ $called_class ];
		} else {
			if ( class_exists( $called_class ) ) {
				$instances[ $called_class ] = new $called_class();

				return $instances[ $called_class ];
			} else {
				// This can unfortunately happen when the get_called_class() workaround for PHP 5.2 misbehaves.
				return false;
			}
		}
	}


	public static function initialize() {
		$instance = self::get_instance();

		$instance->register_callbacks();
		$instance->additional_ajax_init();
	}


	private function __clone() { }


	private function __construct() { }


	const CALLBACK_MIGRATE_TO_M2M = 'migrate_to_m2m';
		

	protected function get_callback_names() {
		return array(
			self::CALLBACK_MIGRATE_TO_M2M
		);
	}


	private $callbacks_registered = false;


	/**
	 * Register all callbacks.
	 *
	 * Each callback is registered as a "{$plugin_slug}_{$callback}" action and needs to have a "callback_{$callback_name}"
	 * method in this class.
	 *
	 * @since 2.0
	 */
	private function register_callbacks() {

		if( $this->callbacks_registered ) {
			return;
		}

		$callback_names = $this->get_callback_names();
		foreach( $callback_names as $callback_name ) {
			$action_name = 'wp_ajax_' . $this->get_plugin_slug() . self::DELIMITER . $callback_name;
			add_action(
				$action_name,
				array( $this, self::CALLBACK_PREFIX . $callback_name ) 
			);
		}

		$this->callbacks_registered = true;

	}
	
	
	protected function get_plugin_slug( $capitalized = false ) {
		return ( $capitalized ? 'Toolset' : 'toolset' );
	}
	
	
	protected function get_handler_class_prefix() {
		return $this->get_plugin_slug( true ) . '_Ajax_Handler_';    
	}

	
	public function get_action_js_name( $action ) {
		return $this->get_plugin_slug( false ) . self::DELIMITER . $action;
	}


	/**
	 * Handle a call to undefined method on this class, hopefully an AJAX call.
	 *
	 * @param string $name Method name.
	 * @param array $parameters Method parameters.
	 * @since 2.1
	 */
	public function __call( $name, $parameters ) {
		// Check for the callback prefix in the method name
		$name_parts = explode( self::DELIMITER, $name );
		if( 0 !== strcmp( $name_parts[0] . self::DELIMITER, self::CALLBACK_PREFIX ) ) {
			// Not a callback, resign.
			return;
		}

		// Deduct the handler class name from the callback name
		unset( $name_parts[0] );
		$class_name = implode( self::DELIMITER, $name_parts );
		$class_name = strtolower( $class_name );
		$class_name = mb_convert_case( $class_name, MB_CASE_TITLE );
		$class_name = $this->get_handler_class_prefix() . $class_name;

		// Obtain an instance of the handler class.
		try {
			/** @var Toolset_Ajax_Handler_Interface $handler */
			$handler = new $class_name( $this );
		} catch( Exception $e ) {
			// The handler class could not have been instantiated, resign.
			return;
		}

		// Success
		$handler->process_call( $parameters );
	}



	/**
	 * Perform basic authentication check.
	 *
	 * Check user capability and nonce. Dies with an error message (wp_json_error() by default) if the authentization
	 * is not successful.
	 *
	 * @param array $args Arguments (
	 *     @type string $nonce Name of the nonce that should be verified. Mandatory
	 *     @type string $nonce_parameter Name of the parameter containing nonce value.
	 *         Optional, defaults to "wpnonce".
	 *     @type string $parameter_source Determines where the function should look for the nonce parameter.
	 *         Allowed values are 'get' and 'post'. Optional, defaults to 'post'.
	 *     @type string $capability_needed Capability that user has to have in order to pass the check.
	 *         Optional, default is "manage_options".
	 *     @type string $type_of_death How to indicate failure:
	 *         - 'die': Call wp_json_error with array( 'type' => 'capability'|'nonce', 'message' => $error_message )
	 *         - 'return': Do not die, just return the error array as above.
	 *         Optional, default is 'die'.
	 *     )
	 *
	 * @return mixed
	 *
	 * @since 2.0
	 */
	private function ajax_authenticate( $args = array() ) {
		// Read arguments
		$type_of_death = toolset_getarr( $args, 'type_of_death', 'die', array( 'die', 'return' ) );
		$nonce_name = toolset_getarr( $args, 'nonce' );
		$nonce_parameter = toolset_getarr( $args, 'nonce_parameter', 'wpnonce' );
		$capability_needed = toolset_getarr( $args, 'capability_needed', 'manage_options' );
		$parameter_source_name = toolset_getarr( $args, 'parameter_source', 'post', array( 'get', 'post' ) );
		$parameter_source = ( $parameter_source_name == 'get' ) ? $_GET : $_POST;

		$is_error = false;
		$error_message = null;
		$error_type = null;

		// Check permissions
		if ( ! current_user_can( $capability_needed ) ) {
			$error_message = __( 'You do not have permissions for that.', 'wpv-views' );
			$error_type = 'capability';
			$is_error = true;
		}

		// Check nonce
		if ( !$is_error && !wp_verify_nonce( toolset_getarr( $parameter_source, $nonce_parameter, '' ), $nonce_name ) ) {
			$error_message = __( 'Your security credentials have expired. Please reload the page to get new ones.', 'wpv-views' );
			$error_type = 'nonce';
			$is_error = true;
		}

		if( $is_error ) {
			$error_description = array( 'type' => $error_type, 'message' => $error_message );
			switch( $type_of_death ) {

				case 'die':
					wp_send_json_error( $error_description );
					break;

				case 'return':
				default:
					return $error_description;
			}
		}

		return true;
	}


	/**
	 * Begin an AJAX call handling.
	 *
	 * To be extended in the future.
	 *
	 * @param array $args See ajax_authenticate for details
	 * @return mixed
	 * @since 2.0
	 */
	public function ajax_begin( $args ) {
		return $this->ajax_authenticate( $args );
	}


	/**
	 * Complete an AJAX call handling.
	 *
	 * Sends a success/error response in a standard way.
	 *
	 * To be extended in the future.
	 *
	 * @param array $response Custom response data
	 * @param bool $is_success
	 * @since 2.0
	 */
	public function ajax_finish( $response, $is_success = true ) {
		if( $is_success ) {
			wp_send_json_success( $response );
		} else {
			wp_send_json_error( $response );
		}
	}


	/**
	 * Handles all initialization of except AJAX callbacks itself that is needed when
	 * we're DOING_AJAX.
	 *
	 * Since this is executed on every AJAX call, make sure it's as lightweight as possible.
	 *
	 * @since m2m
	 */
	protected function additional_ajax_init() { }
	
}