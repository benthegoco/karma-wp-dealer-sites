<?php

/**
 * Holds helper methods related to native Toolset associations.
 *
 * Throughout m2m API, only these classes should directly touch the database:
 *
 * - Toolset_Relationship_Database_Operations
 * - Toolset_Relationship_Migration
 * - Toolset_Relationship_Driver
 * - Toolset_Relationship_Translation_View_Management
 * - Toolset_Association_Query
 *
 * @since m2m
 */
class Toolset_Relationship_Database_Operations {

	/**
	 * Warning: Changing this value in any way may break existing sites.
	 *
	 * @since m2m
	 */
	const MAXIMUM_RELATIONSHIP_SLUG_LENGTH = 255;


	/**
	 * Delimiter used in GROUP_CONCAT MySQL function.
	 */
	const GROUP_CONCAT_DELIMITER = ',';


	private static $instance;


	/** @var wpdb */
	private $wpdb;


	private $table_name;


	public function __construct(
		wpdb $wpdb_di = null,
		Toolset_Relationship_Table_Name $table_name_di = null
	) {

		if( null === $wpdb_di ) {
			global $wpdb;
			$this->wpdb = $wpdb;
		} else {
			$this->wpdb = $wpdb_di;
		}
		$this->table_name = ( null === $table_name_di ? new Toolset_Relationship_Table_Name() : $table_name_di );

	}


	/**
	 * Careful. This class is NOT meant to be a singleton. This is a temporary solution for easier transition
	 * from using static methods.
	 *
	 * @return Toolset_Relationship_Database_Operations
	 */
	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Create new association and persist it.
	 *
	 * From outside of the m2m API, use Toolset_Relationship_Definition::create_association().
	 *
	 * @param Toolset_Relationship_Definition|string $relationship_definition_source Can also contain slug of
	 *     existing relationship definition.
	 * @param int|Toolset_Element|WP_Post $parent_source
	 * @param int|Toolset_Element|WP_Post $child_source
	 * @param int $intermediary_id
	 * @param bool $instantiate Whether to create an instance of the newly created association
	 *     or only return a result on success
	 *
	 * @return IToolset_Association|Toolset_Result
	 * @since m2m
	 */
	public static function create_association( $relationship_definition_source, $parent_source, $child_source, $intermediary_id, $instantiate = true ) {

		$relationship_definition = Toolset_Relationship_Utils::get_relationship_definition( $relationship_definition_source );

		if ( ! $relationship_definition instanceof Toolset_Relationship_Definition ) {
			throw new InvalidArgumentException(
				sprintf(
					__( 'Relationship definition "%s" doesn\'t exist.', 'wpcf' ),
					is_string( $relationship_definition_source ) ? $relationship_definition_source : print_r( $relationship_definition_source, true )
				)
			);
		}

		$driver = $relationship_definition->get_driver();

		$result = $driver->create_association(
			$parent_source,
			$child_source,
			array(
				'intermediary_id' => $intermediary_id,
				'instantiate' => (bool) $instantiate
			)
		);

		return $result;
	}


	// The _id columns in the associations table
	const COLUMN_ID = '_id';

	// Columns in the relationships table
	const COLUMN_DOMAIN = '_domain';
	const COLUMN_TYPES = '_types';


	/**
	 * For a given role name, return the corresponding column in the associations table.
	 *
	 * @param string|IToolset_Relationship_Role $role
	 * @param string $column
	 *
	 * @return string
	 * @since m2m
	 */
	public function role_to_column( $role, $column = self::COLUMN_ID ) {

		if( $role instanceof IToolset_Relationship_Role ) {
			$role_name = $role->get_name();
		} else {
			$role_name = $role;
		}

		return $role_name . $column;
	}


	/**
	 * Update the database to support the native m2m implementation.
	 *
	 * Practically that means creating the wp_toolset_associations table.
	 *
	 * @since m2m
	 *
	 * TODO is it possible to reliably detect dbDelta failure?
	 */
	public function do_native_dbdelta() {
		$this->create_associations_table();
		$this->create_relationship_table();
		$this->create_type_set_table();
		return true;
	}


	/**
	 * Execute a dbDelta() query, ensuring that the function is available.
	 *
	 * @param string $query MySQL query.
	 *
	 * @return array dbDelta return value.
	 */
	private static function dbdelta( $query ) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		return dbDelta( $query );
	}


	/**
	 * Determine if a table exists in the database.
	 *
	 * @param string $table_name
	 *
	 * @return bool
	 * @since m2m
	 */
	public function table_exists( $table_name ) {
		global $wpdb;
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name );
		return ( $wpdb->get_var( $query ) == $table_name );
	}


	private function get_charset_collate() {
		global $wpdb;
		return $wpdb->get_charset_collate();
	}


	/**
	 * Create the table for storing associations.
	 *
	 * Note: It is assumed that the table doesn't exist.
	 *
	 * @since m2m
	 */
	private function create_associations_table() {

		$association_table_name = Toolset_Relationship_Table_Name::associations();

		if ( $this->table_exists( $association_table_name ) ) {
			return;
		}

		// Note that dbDelta is very sensitive about details, almost nothing here is arbitrary.
		$query = "CREATE TABLE {$association_table_name} (
				id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    			relationship_id bigint(20) UNSIGNED NOT NULL, 
			    parent_id bigint(20) UNSIGNED NOT NULL,
			    child_id bigint(20) UNSIGNED NOT NULL,
			    intermediary_id bigint(20) UNSIGNED NOT NULL,
			    trid bigint(20) UNSIGNED NOT NULL,
			    lang varchar(7) NOT NULL DEFAULT '',
			    translation_type enum('original','translation','none') NOT NULL DEFAULT 'none',
			    PRIMARY KEY  id (id),
			    KEY relationship_id (relationship_id),
				KEY parent_id (parent_id, relationship_id),
				KEY child_id (child_id, relationship_id)			    
			) " . $this->get_charset_collate() . ";";

		self::dbdelta( $query );
	}


	/**
	 * Create the table for the relationship definitions.
	 *
	 * Note: It is assumed that the table doesn't exist.
	 *
	 * @since m2m
	 */
	private function create_relationship_table() {

		$table_name = $this->table_name->relationship_table();

		if ( $this->table_exists( $table_name ) ) {
			return;
		}

		// Note that dbDelta is very sensitive about details, almost nothing here is arbitrary.
		$query = "CREATE TABLE {$table_name} (
				id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    			slug varchar(" . self::MAXIMUM_RELATIONSHIP_SLUG_LENGTH . ") NOT NULL DEFAULT '',
    			display_name_plural varchar(255) NOT NULL DEFAULT '',
    			display_name_singular varchar(255) NOT NULL DEFAULT '',
    			driver varchar(50) NOT NULL DEFAULT '',
    			parent_domain varchar(20) NOT NULL DEFAULT '', 
    			parent_types bigint(20) UNSIGNED NOT NULL DEFAULT 0,
    			child_domain varchar(20) NOT NULL DEFAULT '',
    			child_types bigint(20) UNSIGNED NOT NULL DEFAULT 0,
    			intermediary_type varchar(20) NOT NULL DEFAULT '',
    			ownership enum('parent', 'child', 'none') NOT NULL DEFAULT 'none',
    			cardinality_parent_max int(10) NOT NULL DEFAULT -1,
    			cardinality_parent_min int(10) NOT NULL DEFAULT 0,
    			cardinality_child_max int(10) NOT NULL DEFAULT -1,
    			cardinality_child_min int(10) NOT NULL DEFAULT 0,
    			is_distinct tinyint(1) NOT NULL DEFAULT 0,
    			scope longtext NOT NULL DEFAULT '',
    			origin varchar(50) NOT NULL DEFAULT '',
    			role_name_parent varchar(255) NOT NULL DEFAULT '',
    			role_name_child varchar(255) NOT NULL DEFAULT '',
    			role_name_intermediary varchar(255) NOT NULL DEFAULT '',
    			needs_legacy_support tinyint(1) NOT NULL DEFAULT 0,
    			is_active tinyint(1) NOT NULL DEFAULT 0,
			    PRIMARY KEY  id (id),
			    KEY slug (slug),
				KEY is_active (is_active),
			    KEY needs_legacy_support (needs_legacy_support),
			    KEY parent_type (parent_domain, parent_types),
			    KEY child_type (child_domain, child_types)		    
			) " . $this->get_charset_collate() . ";";

		self::dbdelta( $query );

	}


	private function create_type_set_table() {
		$table_name = $this->table_name->type_set_table();
		if ( $this->table_exists( $table_name ) ) {
			return;
		}

		// Note that dbDelta is very sensitive about details, almost nothing here is arbitrary.
		$query = "CREATE TABLE {$table_name} (
				id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    			set_id bigint(20) UNSIGNED NOT NULL DEFAULT 0,
    			type varchar(20) NOT NULL DEFAULT '',
    			PRIMARY KEY  id (id),
			    KEY set_id (set_id),
			    KEY type (type)
			) " . $this->get_charset_collate() . ";";

		self::dbdelta( $query );
	}


	/**
	 * Get the next unused value for trid (translation ID, grouping different translations of
	 * one association together).
	 *
	 * Assumes that this method will be always called before inserting a new trid, and that
	 * the returned trid is always used.
	 *
	 * @return int
	 */
	public static function get_next_trid() {
		static $next_trid = 0;

		if ( 0 === $next_trid ) {
			global $wpdb;
			$associations_table = Toolset_Relationship_Table_Name::associations();
			$last_trid = $wpdb->get_var( "SELECT MAX(trid) FROM {$associations_table}" );

			// It will be incremented and becomes unique in the next step
			$next_trid = $last_trid;
		}

		$next_trid++;

		return $next_trid;
	}


	/**
	 * When a relationship definition slug is renamed, update the association table (where the slug is used as a foreign key).
	 *
	 * The usage of this method is strictly limited to the m2m API, always change the slug via
	 * Toolset_Relationship_Definition_Repository::change_definition_slug().
	 *
	 * @param string $old_slug
	 * @param string $new_slug
	 *
	 * @return Toolset_Result
	 *
	 * @since m2m
	 */
	public static function update_associations_on_definition_renaming( $old_slug, $new_slug ) {
		global $wpdb;

		$associations_table = Toolset_Relationship_Table_Name::associations();

		$rows_updated = $wpdb->update(
			$associations_table,
			array( 'relationship' => $new_slug ),
			array( 'relationship' => $old_slug ),
			'%s',
			'%s'
		);

		$is_success = ( false !== $rows_updated );

		$message = (
		$is_success
			? sprintf(
			__( 'The association table has been updated with the new relationship slug "%s". %d rows have been updated.', 'wpcf' ),
			$new_slug,
			$rows_updated
		)
			: sprintf(
			__( 'There has been an error when updating the assocation table with the new relationship slug: %s', 'wpcf' ),
			$wpdb->last_error
		)
		);

		return new Toolset_Result( $is_success, $message );
	}


	/**
	 * Delete all associations from a given relationship.
	 *
	 * @param string $relationship_slug
	 *
	 * @return Toolset_Result_Updated
	 */
	public function delete_associations_by_relationship( $relationship_slug ) {

		$associations_table = $this->table_name->association_table();

		$result = $this->wpdb->delete(
			$associations_table,
			array( 'relationship' => $relationship_slug ),
			array( '%s' )
		);

		if( false === $result ) {
			return new Toolset_Result_Updated(
				false, 0,
				sprintf( __( 'Database error when deleting associations: "%s"', 'wpcf' ), $this->wpdb->last_error )
			);
		} else {
			return new Toolset_Result_Updated(
				true, $result,
				sprintf( __( 'Deleted all associations for the relationship %s', 'wpcf'), $relationship_slug )
			);
		}
	}


	/**
	 * Build the part of the SELECT clause that is required for proper loading of a relationship definition.
	 *
	 * @param string $relationships_table_alias
	 * @param string $parent_types_table_alias
	 * @param string $child_types_table_alias
	 *
	 * @return string
	 * @since 2.5.4
	 */
	public function get_standard_relationships_select_clause(
		$relationships_table_alias = 'relationships',
		$parent_types_table_alias = 'parent_types_table',
		$child_types_table_alias = 'child_types_table'
	) {
		return "
		  	$relationships_table_alias.id AS id, 
		  	$relationships_table_alias.slug AS slug, 
		  	$relationships_table_alias.display_name_plural AS display_name_plural,
		  	$relationships_table_alias.display_name_singular AS display_name_singular,
		  	$relationships_table_alias.driver AS driver, 
		  	$relationships_table_alias.parent_domain AS parent_domain, 
		  	$relationships_table_alias.child_domain AS child_domain, 
		  	$relationships_table_alias.intermediary_type AS intermediary_type,
		  	$relationships_table_alias.ownership AS ownership,  
		  	$relationships_table_alias.cardinality_parent_max AS cardinality_parent_max,
		  	$relationships_table_alias.cardinality_parent_min AS cardinality_parent_min, 
		  	$relationships_table_alias.cardinality_child_max AS cardinality_child_max,
		  	$relationships_table_alias.cardinality_child_min AS cardinality_child_min,
		  	$relationships_table_alias.is_distinct AS is_distinct,
		  	$relationships_table_alias.scope AS scope,
		  	$relationships_table_alias.origin AS origin,
		  	$relationships_table_alias.role_name_parent AS role_name_parent,
		  	$relationships_table_alias.role_name_child AS role_name_child,
		  	$relationships_table_alias.role_name_intermediary AS role_name_intermediary,
		  	$relationships_table_alias.needs_legacy_support AS needs_legacy_support,
		  	$relationships_table_alias.is_active AS is_active,
		  	$relationships_table_alias.parent_types AS parent_types_set_id,
		  	$relationships_table_alias.child_types AS child_types_set_id,
		  	GROUP_CONCAT(DISTINCT $parent_types_table_alias.type) AS parent_types, 
		  	GROUP_CONCAT(DISTINCT $child_types_table_alias.type) AS child_types";
	}


	/**
	 * Build the part of the JOIN clause that is required for proper loading of a relationship definition.
	 *
	 * @param $type_set_table_name
	 * @param string $relationships_table_alias
	 * @param string $parent_types_table_alias
	 * @param string $child_types_table_alias
	 *
	 * @return string
	 * @since 2.5.4
	 */
	public function get_standard_relationships_join_clause(
		$type_set_table_name,
		$relationships_table_alias = 'relationships',
		$parent_types_table_alias = 'parent_types_table',
		$child_types_table_alias = 'child_types_table'
	) {
		return "
			JOIN {$type_set_table_name} AS {$parent_types_table_alias} 
				ON ({$relationships_table_alias}.parent_types = {$parent_types_table_alias}.set_id )
			JOIN {$type_set_table_name} AS {$child_types_table_alias} 
				ON ({$relationships_table_alias}.child_types = {$child_types_table_alias}.set_id )";
	}


	/**
	 * Build the part of the GROUP BY clause that is required for proper loading of a relationship definition.
	 *
	 * @param string $relationships_table_alias
	 *
	 * @return string
	 * @since 2.5.4
	 */
	public function get_standards_relationship_group_by_clause( $relationships_table_alias = 'relationships' ) {
		return "{$relationships_table_alias}.id";
	}


	public function load_all_relationships() {
		$relationship_table = $this->table_name->relationship_table();
		$type_set_table = $this->table_name->type_set_table();

		// The query is so complex because it needs to bring in data from the type set tables. But
		// those two joins are very cheap because we don't expect many records here.
		$query = "
		  SELECT {$this->get_standard_relationships_select_clause()}
		  FROM {$relationship_table} AS relationships 
    	  	{$this->get_standard_relationships_join_clause( $type_set_table )}
		  GROUP BY {$this->get_standards_relationship_group_by_clause()}";

		$rows = toolset_ensarr( $this->wpdb->get_results( $query ) );
		return $rows;
	}

}