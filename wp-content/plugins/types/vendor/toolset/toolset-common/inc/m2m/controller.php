<?php

/**
 * Main controller class for object relationships in Toolset.
 * 
 * initialize_core() needs to be called during init on every request so it can handle relevant core actions.
 * Before using any relationship functionality, initialize_full() must be called.
 *
 * Always use this as a singleton in the production code.
 *
 * @since m2m
 */
class Toolset_Relationship_Controller {


	/** @var Toolset_Post_Type_Query_Factory|null */
	private $_post_type_query_factory;


	/** @var Toolset_Relationship_Controller|null */
	private static $instance;


	public static function get_instance() {
		if( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Toolset_Relationship_Controller constructor.
	 *
	 * @param Toolset_Post_Type_Query_Factory|null $post_type_query_factory_di
	 */
	public function __construct( Toolset_Post_Type_Query_Factory $post_type_query_factory_di = null ) {
		$this->_post_type_query_factory = $post_type_query_factory_di;
	}


	const IS_M2M_ENABLED_OPTION = 'toolset_is_m2m_enabled';


	/**
	 * We need WPML to fire certain actions when it updates its icl_translations table.
	 */
	const MINIMAL_WPML_VERSION = '3.5.0';


    private $is_autoloader_initialized = false;


    /** @var null|bool Cache for is_m2m_enabled() */
    private $is_m2m_enabled_cache = null;  


	/**
	 * Returns the value of the m2m feature toggle.
	 *
	 * Default value depends on the presence of legacy post relationships on the site.
	 *
	 * The result is cached.
	 *
	 * @return bool
	 */
	public function is_m2m_enabled() {

		if( null !== $this->is_m2m_enabled_cache ) {
			return $this->is_m2m_enabled_cache;
		}

		$is_enabled = get_option( self::IS_M2M_ENABLED_OPTION, null );

		if( null === $is_enabled ) {
			$is_enabled = $this->set_initial_m2m_state();
		} else {
			$is_enabled = ( 'no' == $is_enabled ? false : true );
		}

		/**
		 * Allows for overriding the m2m feature toggle (both ways).
		 *
		 * This filter is dangerous and should never be used in production. Also, it may disappear at any given
		 * moment. For only determining whether m2m is enabled or not, use the toolset_is_m2m_enabled filter.
		 *
		 * @since m2m
		 */
		$is_enabled = (bool) apply_filters( 'toolset_enable_m2m_manually', (bool) $is_enabled );

		$this->is_m2m_enabled_cache = $is_enabled;

		return $is_enabled;
	}


	private $is_core_initialized = false;


	/**
	 * Initialize only the very core of the controller.
	 *
	 * That means mainly hooks to various WordPress events.
	 *
	 * @since m2m
	 */
	public function initialize_core() {

		if( $this->is_core_initialized ) {
			return;
		}

		$this->is_core_initialized = true;

		$this->add_hooks();
	}


	private $is_everything_initialized = false;


	/**
	 * Full initialization that is needed before any relationships-related action takes place.
	 *
	 * @since m2m
	 */
	public function initialize_full() {

		if( $this->is_everything_initialized ) {
			return;
		}

		if( ! $this->is_m2m_enabled() ) {
			return;
		}

        $this->initialize_autoloader();
		$this->initialize_core();

		// fixme: This is for the purpose of alpha and beta versions: If there's a database problem,
		// at least make it fail on every request. Otherwise, we'll just waste a little performance
		// on checking that the tables already exist.
		$migration = new Toolset_Relationship_Migration();
		$migration->do_native_dbdelta();

        $this->is_everything_initialized = true;
	}


	public function is_fully_initialized() {
		return $this->is_everything_initialized;
	}


	/**
	 * Add hooks to relevant actions and filters.
	 *
	 * All callback functions need to do initialize_full() before anything else.
	 *
	 * @since m2m
	 */
	private function add_hooks() {

		/**
		 * toolset_is_m2m_enabled
		 *
		 * @param false $default_value
		 * @return bool Is the m2m functionality enabled? If true, all legacy post relationship functionality should be
		 *     replaced by the m2m one.
		 * @since m2m
		 */
		add_filter( 'toolset_is_m2m_enabled', array( $this, 'is_m2m_enabled' ) );

		/**
		 * toolset_do_m2m_full_init
		 *
		 * Shortcut action to easily fully initialize the m2m API.
		 *
		 * @since m2m
		 */
		add_action( 'toolset_do_m2m_full_init', array( $this, 'initialize_full' ) );

		// If the m2m feature is not enabled, nothing else should happen now.
		if( ! $this->is_m2m_enabled() ) {
			return;
		}

		add_filter( 'before_delete_post', array( $this, 'on_before_delete_post' ) );

		// Intercept icl_translations table changes
		//
		//

		/**
		 * toolset_use_default_m2m_wpml_interoperability_manager
		 *
		 * Allow for disabling the standard WPML interoperability manager if it's about to be overridden by
		 * something else.
		 *
		 * @since m2m
		 */
		if( true == apply_filters( 'toolset_use_default_m2m_wpml_interoperability_manager', true ) ) {
			add_action( 'wpml_translation_update', array( $this, 'on_wpml_translation_update' ), 10 );
		}

		/**
		 * toolset_relationship_query
		 *
		 * Query Toolset relationships without dependencies and the need for initializing the relationship controller
		 * manually.
		 *
		 * For possible query argument values, refer to the Toolset_Relationship_Query description.
		 *
		 * @since m2m
		 */
		add_filter( 'toolset_relationship_query', array( $this, 'on_toolset_relationship_query' ), 10, 2 );
	}


	/**
	 * Intercept icl_translations table changes.
	 *
	 * @param $args
	 * @since m2m
	 */
	public function on_wpml_translation_update( $args ) {
		$this->initialize_full();
		$view_manager = Toolset_Relationship_WPML_Interoperability::get_instance();
		$view_manager->on_wpml_translation_update( $args );
	}


	/**
	 * Hooked into the toolset_relationship_query filter.
	 *
	 * @param $ignored
	 * @param $query_args
	 *
	 * @return int[]|Toolset_Association_Base[]|Toolset_Element[]
	 */
	public function on_toolset_relationship_query( /** @noinspection PhpUnusedParameterInspection */ $ignored, $query_args ) {
		$this->initialize_full();
		$query = new Toolset_Association_Query( $query_args );
		return $query->get_results();
	}


	/**
	 * Register all Toolset_Relationship_* classes in the Toolset autoloader.
	 *
	 * @since m2m
	 */
	private function initialize_autoloader() {

	    if( $this->is_autoloader_initialized ) {
	        return;
        }

		$autoloader = Toolset_Common_Autoloader::get_instance();

		$autoload_classmap_file = TOOLSET_COMMON_PATH . '/inc/m2m/autoload_classmap.php';

		if( ! is_file( $autoload_classmap_file ) ) {
			// abort if file does not exist
			return;
		}

		$classmap = include( $autoload_classmap_file );

		$autoloader->register_classmap( $classmap );

        $this->is_autoloader_initialized = true;
	}


	/**
	 * Handle events on post deletion (triggered by wp_delete_post()).
	 *
	 * Basically, that means checking if there are any associations with this post and delete them.
	 * Note that that will also trigger deleting the intermediary post and possibly some owned elements.
	 *
	 * WIP
	 *
	 * @param int $post_id
	 * @since m2m
	 */
	public function on_before_delete_post( $post_id ) {

		$this->initialize_full();

		try {
			$post = Toolset_Post::get_instance( $post_id );

			$assocation_repository = Toolset_Association_Repository::get_instance();
			$assocation_repository->delete_associations_involving_element( $post );

			// todo Query all post's associations and delete them. That should trigger deleting the intermediary posts and owned elements.

		} catch( Exception $e ) {

		}
	}


	/**
	 * Determine whether m2m should be enabled by default.
	 *
	 * We do that only if there are no legacy post relationships defined. Otherwise, the user needs to
	 * manually trigger the migration.
	 *
	 * When activating m2m on a fresh site, this will also create the relationship tables.
	 *
	 * Finally, this method updates the toggle option so we don't need to run this check on each request.
	 *
	 * @return bool
	 * @since m2m
	 */
	private function set_initial_m2m_state() {
		$legacy_relationships = toolset_ensarr( get_option( 'wpcf_post_relationship', array() ) );
		$has_legacy_relationships = ! empty( $legacy_relationships );

		$is_ready_for_m2m = new Toolset_Condition_Plugin_Types_Ready_For_M2M();

		$enable_m2m = ( $is_ready_for_m2m->is_met() && ! $has_legacy_relationships );

		if( $enable_m2m ) {
			$this->force_autoloader_initialization();
			$migration = new Toolset_Relationship_Migration();
			$migration->do_native_dbdelta();
		}

		update_option( self::IS_M2M_ENABLED_OPTION, ( $enable_m2m ? 'yes' : 'no' ), true );
		return $enable_m2m;
	}


    /**
     * Force the autoloader classmap registration when usage of m2m API classes is necessary even
     * with m2m not enabled.
     *
     * @since m2m
     */
    public function force_autoloader_initialization() {
        $this->initialize_autoloader();
	}
}