<?php

/**
 * Relationship query class with a more OOP/functional approach.
 *
 * Replaces Toolset_Relationship_Query.
 *
 * Allows for chaining query conditions and avoiding passing query arguments as associative arrays.
 * It makes it also possible to build queries with nested AND & OR statements in an arbitrary way.
 * The object model may be complex but all the complexity is hidden from the user, they need to know
 * only the methods on this class.
 *
 * Example usage:
 *
 * $query = new Toolset_Relationship_Query_V2();
 *
 * $results = $query
 *     ->add(
 *         $query->has_domain( 'posts' )
 *     )
 *     ->add(
 *         $query->do_or(
 *             $query->has_type( 'attachment', new Toolset_Relationship_Role_Parent() ),
 *             $query->do_and(
 *                 $query->has_type( 'page', new Toolset_Relationship_Role_Parent() ),
 *                 $query->is_legacy( false )
 *             )
 *         )
 *     )
 *     ->add( $query->is_active( '*' ) )
 *     ->get_results();
 *
 * Note:
 * - If no is_active() condition is provided on the top level, is_active(true) is used. To get both
 *     active and non-active relationship definitions, you need to manually add is_active('*').
 * - If no has_active_post_types() condition is provided on the top level, has_active_post_types(true) is used
 *     for both parent and child role.
 *
 * @since m2m
 */
class Toolset_Relationship_Query_V2 implements IToolset_Query {


	/** @var IToolset_Relationship_Query_Condition[] */
	private $conditions = array();

	/** @var wpdb */
	private $wpdb;

	/** @var Toolset_Relationship_Definition_Translator */
	private $definition_translator;


	private $should_add_default_conditions = true;
	private $has_is_active_condition = false;
	private $has_is_post_type_active_condition = false;


	/** @var Toolset_Relationship_Database_Unique_Table_Alias */
	private $unique_table_alias;


	/** @var Toolset_Relationship_Query_Sql_Expression_Builder */
	private $expression_builder;


	/** @var Toolset_Relationship_Query_Condition_Factory */
	private $condition_factory;


	/**
	 * Toolset_Relationship_Query_V2 constructor.
	 *
	 * @param wpdb|null $wpdb_di
	 * @param Toolset_Relationship_Definition_Translator|null $definition_translator_di
	 * @param Toolset_Relationship_Database_Unique_Table_Alias|null $unique_table_alias_di
	 * @param Toolset_Relationship_Database_Operations|null $database_operations_di
	 * @param Toolset_Relationship_Query_Sql_Expression_Builder|null $expression_builder_di
	 * @param Toolset_Relationship_Query_Condition_Factory|null $condition_factory_di
	 */
	public function __construct(
		wpdb $wpdb_di = null,
		Toolset_Relationship_Definition_Translator $definition_translator_di = null,
		Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias_di = null,
		Toolset_Relationship_Database_Operations $database_operations_di = null,
		Toolset_Relationship_Query_Sql_Expression_Builder $expression_builder_di = null,
		Toolset_Relationship_Query_Condition_Factory $condition_factory_di = null
	) {

		if( null === $wpdb_di ) {
			global $wpdb;
			$this->wpdb = $wpdb;
		} else {
			$this->wpdb = $wpdb_di;
		}

		$this->definition_translator = (
			null === $definition_translator_di
				? new Toolset_Relationship_Definition_Translator()
				: $definition_translator_di
		);

		$this->unique_table_alias = (
			null === $unique_table_alias_di
				? new Toolset_Relationship_Database_Unique_Table_Alias()
				: $unique_table_alias_di
		);

		$this->expression_builder = (
			null === $expression_builder_di
				? new Toolset_Relationship_Query_Sql_Expression_Builder()
				: $expression_builder_di
		);

		$this->condition_factory = (
			null === $condition_factory_di
				? new Toolset_Relationship_Query_Condition_Factory()
				: $condition_factory_di
		);
	}


	/**
	 * Add another condition to the query.
	 *
	 * @param IToolset_Relationship_Query_Condition $condition
	 *
	 * @return $this
	 */
	public function add( IToolset_Relationship_Query_Condition $condition ) {
		$this->conditions[] = $condition;

		if( $condition instanceof Toolset_Relationship_Query_Condition_Is_Active ) {
			$this->has_is_active_condition = true;
		}

		if( $condition instanceof Toolset_Relationship_Query_Condition_Has_Active_Types ) {
			$this->has_is_post_type_active_condition = true;
		}

		return $this;
	}


	/**
	 * Basically, this sets default query parameters.
	 *
	 * The method needs to stay idempotent.
	 */
	private function add_default_conditions() {
		if( ! $this->should_add_default_conditions ) {
			return;
		}

		if( ! $this->has_is_active_condition ) {
			$this->add( $this->is_active() );
		}

		if( ! $this->has_is_post_type_active_condition ) {
			$this->add( $this->has_active_post_types() );
		}
	}


	/**
	 * @return IToolset_Relationship_Query_Condition MySQL WHERE clause for the query.
	 */
	private function build_root_condition() {
		$this->add_default_conditions();
		return $this->condition_factory->do_and( $this->conditions );
	}


	/**
	 * @return $this
	 */
	public function do_not_add_default_conditions() {
		$this->should_add_default_conditions = false;
		return $this;
	}


	/**
	 * Apply stored conditions and perform the query.
	 *
	 * todo: Add the results to the relationship repository.
	 *
	 * @return IToolset_Relationship_Definition[]
	 */
	public function get_results() {

		$query = $this->build_sql_query();
		$rows = toolset_ensarr( $this->wpdb->get_results( $query ) );

		$results = array();
		foreach( $rows as $row ) {
			$definition = $this->definition_translator->from_database_row( $row );
			$results[] = $definition;
			/*if( null != $definition ) {
				$this->insert_definition( $definition );
			}*/
		}

		return $results;
	}


	/**
	 * Build a complete MySQL query from the conditions.
	 *
	 * @return string
	 */
	private function build_sql_query() {
		$root_condition = $this->build_root_condition();
		return $this->expression_builder->build( $root_condition );
	}


	/**
	 * Chain multiple conditions with OR.
	 *
	 * The whole statement will evaluate to true if at least one of provided conditions is true.
	 *
	 * @param IToolset_Relationship_Query_Condition[] [$condition1, $condition2, ...]
	 * @return IToolset_Relationship_Query_Condition
	 */
	public function do_or() {
		return $this->condition_factory->do_or( func_get_args() );
	}


	/**
	 * Chain multiple conditions with AN.
	 *
	 * The whole statement will evaluate to true if all provided conditions are true.
	 *
	 * @param IToolset_Relationship_Query_Condition[] [$condition1, $condition2, ...]
	 * @return IToolset_Relationship_Query_Condition
	 */
	public function do_and() {
		return $this->condition_factory->do_and( func_get_args() );
	}


	/**
	 * Condition that the relationship involves a certain domain.
	 *
	 * @param string $domain_name 'posts'|'users'|'terms'
	 * @param IToolset_Relationship_Role_Parent_Child|null $in_role If null is provided, the type
	 *    can be in both parent or child role for the condition to be true.
	 *
	 * @return IToolset_Relationship_Query_Condition
	 */
	public function has_domain( $domain_name, $in_role = null ) {
		if( null === $in_role ) {
			return $this->do_or(
				$this->has_domain( $domain_name, new Toolset_Relationship_Role_Parent() ),
				$this->has_domain( $domain_name, new Toolset_Relationship_Role_Child() )
			);
		}

		return $this->condition_factory->has_domain( $domain_name, $in_role );
	}


	/**
	 * Condition that the relationship comes from a certain source
	 *
	 * @param string $origin
	 *
	 * @return Toolset_Relationship_Query_Condition_Origin
	 */
	public function origin( $origin ) {
		return $this->condition_factory->origin( $origin );
	}


	/**
	 * Condition that the relationship has a certain type in a given role.
	 *
	 * @param string $type
	 * @param IToolset_Relationship_Role_Parent_Child|null $in_role If null is provided, the type
	 *    can be in both parent or child role for the condition to be true.
	 *
	 * @return IToolset_Relationship_Query_Condition
	 */
	public function has_type( $type, $in_role = null ) {
		if( null === $in_role ) {
			return $this->do_or(
				$this->has_type( $type, new Toolset_Relationship_Role_Parent() ),
				$this->has_type( $type, new Toolset_Relationship_Role_Child() )
			);
		}

		return $this->condition_factory->has_type( $type, $in_role );
	}


	/**
	 * Condition that the relationship was migrated from the legacy implementation.
	 *
	 * @param bool $should_be_legacy
	 *
	 * @return IToolset_Relationship_Query_Condition
	 */
	public function is_legacy( $should_be_legacy = true ) {
		return $this->condition_factory->is_legacy( $should_be_legacy );
	}


	/**
	 * Condition that the relationship is active.
	 *
	 * @param bool $should_be_active
	 *
	 * @return IToolset_Relationship_Query_Condition
	 */
	public function is_active( $should_be_active = true ) {
		return $this->condition_factory->is_active( $should_be_active );
	}


	/**
	 * Condition that the relationship has at least one active post type in a given role (or another domain than posts).
	 *
	 * @param bool $has_active_post_types
	 * @param IToolset_Relationship_Role_Parent_Child|null $in_role
	 *
	 * @return IToolset_Relationship_Query_Condition|Toolset_Relationship_Query_Condition_Has_Active_Types
	 */
	public function has_active_post_types( $has_active_post_types = true, IToolset_Relationship_Role_Parent_Child $in_role = null ) {
		if( null === $in_role ) {
			return $this->do_or(
				$this->has_active_post_types( $has_active_post_types, new Toolset_Relationship_Role_Parent() ),
				$this->has_active_post_types( $has_active_post_types, new Toolset_Relationship_Role_Child() )
			);
		}

		return $this->condition_factory->has_active_post_types( $has_active_post_types, $in_role );
	}

}