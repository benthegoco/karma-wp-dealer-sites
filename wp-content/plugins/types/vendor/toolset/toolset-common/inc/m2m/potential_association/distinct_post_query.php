<?php

/**
 * Augments WP_Query to check whether posts are associated with a particular other element ID,
 * and dismisses those posts.
 *
 * This is used in Toolset_Potential_Association_Query_Posts to handle distinct relationships.
 *
 * Both before_query() and after_query() methods need to be called as close to the actual
 * querying as possible, otherwise things will get broken.
 *
 * @since m2m
 */
class Toolset_Relationship_Distinct_Post_Query {

	/** @var Toolset_Relationship_Definition */
	private $relationship;

	/** @var int */
	private $for_element_id;

	/** @var IToolset_Relationship_Role_Parent_Child */
	private $target_role;

	/** @var null|Toolset_Relationship_Table_Name */
	private $_table_names;

	/** @var null|wpdb */
	private $_wpdb;


	/**
	 * Toolset_Relationship_Distinct_Post_Query constructor.
	 *
	 * @param Toolset_Relationship_Definition $relationship
	 * @param IToolset_Relationship_Role_Parent_Child $target_role Target role of the relationships (future role of
	 *     the posts that are being queried)
	 * @param int $for_element_id ID of the element to check against.
	 * @param Toolset_Relationship_Table_Name|null $table_names_di
	 * @param wpdb|null $wpdb_di
	 */
	public function __construct(
		Toolset_Relationship_Definition $relationship,
		IToolset_Relationship_Role_Parent_Child $target_role,
		$for_element_id,
		Toolset_Relationship_Table_Name $table_names_di = null,
		wpdb $wpdb_di = null
	) {
		$this->relationship = $relationship;
		$this->for_element_id = $for_element_id;
		$this->target_role = $target_role;

		$this->_table_names = $table_names_di;
		$this->_wpdb = $wpdb_di;
	}


	private function is_actionable() {
		return $this->relationship->is_distinct();
	}


	/**
	 * Hooks to filters in order to add extra clauses to the MySQL query.
	 */
	public function before_query() {
		if( ! $this->is_actionable() ) {
			return;
		}

		add_filter( 'posts_join', array( $this, 'add_join_clauses' ) );

		add_filter( 'posts_where', array( $this, 'add_where_clauses' ) );
	}


	/**
	 * Cleanup - unhooks the filters added in before_query().
	 */
	public function after_query() {
		if( ! $this->is_actionable() ) {
			return;
		}

		remove_filter( 'posts_join', array( $this, 'add_join_clauses' ) );

		remove_filter( 'posts_where', array( $this, 'add_where_clauses' ) );
	}


	private function get_table_names() {
		if( null === $this->_table_names ) {
			$this->_table_names = new Toolset_Relationship_Table_Name();
		}

		return $this->_table_names;
	}


	private function get_wpdb() {
		if( null === $this->_wpdb ) {
			global $wpdb;
			$this->_wpdb = $wpdb;
		}

		return $this->_wpdb;
	}


	/**
	 * Add a JOIN clause to the WP_Query's MySQL query string.
	 *
	 * That will connect the row from the associations table, if there is an association
	 * with the correct relationship and the $for_element.
	 *
	 * Otherwise, those columns will be NULL, because we're doing a LEFT JOIN here.
	 *
	 * @param string $join
	 *
	 * @return string
	 */
	public function add_join_clauses( $join ) {
		$association_table = $this->get_table_names()->association_table();
		$posts_table_name = $this->get_wpdb()->posts;
		$target_element_column = $this->target_role->get_name() . '_id';
		$for_element_column = $this->target_role->other() . '_id';

		$join .= $this->get_wpdb()->prepare(
			" LEFT JOIN {$association_table} AS toolset_associations ON ( 
				toolset_associations.relationship_id = %d
				AND toolset_associations.{$target_element_column} = {$posts_table_name}.ID
				AND toolset_associations.{$for_element_column} = %d    
			) ",
			$this->relationship->get_row_id(),
			$this->for_element_id
		);

		return $join;
	}


	/**
	 * Add a WHERE clause to the WP_Query's MySQL query string.
	 *
	 * After adding the JOIN, we only need to check that there's not an ID of the
	 * column with $for_element: That means there's no association between the queried
	 * post and $for_element, and we can offer the post as a result.
	 *
	 * @param string $where
	 *
	 * @return string
	 */
	public function add_where_clauses( $where ) {
		$for_element_column = $this->target_role->other() . '_id';
		$where .= " AND ( toolset_associations.{$for_element_column} IS NULL ) ";
		return $where;
	}

}