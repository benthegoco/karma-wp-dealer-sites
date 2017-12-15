<?php

/**
 * Class Toolset_Relationship_Service
 *
 * Most provided services here require m2m and are useless if "toolset_is_m2m_enabled" is false.
 *
 * @since 2.5.2
 */
class Toolset_Relationship_Service {
	/**
	 * @var bool
	 */
	private $m2m_enabled;

	/**
	 * @return bool
	 */
	private function is_m2m_enabled() {
		if( $this->m2m_enabled === null ) {
			$this->m2m_enabled = apply_filters( 'toolset_is_m2m_enabled', false );

			if( $this->m2m_enabled ) {
				do_action( 'toolset_do_m2m_full_init' );
			}
		}

		return $this->m2m_enabled;
	}

	/**
	 * @param $string
	 *
	 * @return false|IToolset_Relationship_Definition
	 */
	public function find_by_string( $string ) {
		if( ! $this->is_m2m_enabled() ) {
			return false;
		}

		Toolset_Relationship_Controller::get_instance()->initialize_full();
		$factory = Toolset_Relationship_Definition_Repository::get_instance();

		if ( $relationship = $factory->get_definition( $string ) ) {
			return $relationship;
		}

		return false;
	}

	/**
	 * Function to find parend id by relationship and child id
	 *
	 * @param IToolset_Relationship_Definition $relationship
	 * @param $child_id
	 * @param null $parent_slug
	 *s
	 *
	 * @return bool|int[]|IToolset_Association[]|IToolset_Element[]
	 */
	public function find_parent_id_by_relationship_and_child_id(
		IToolset_Relationship_Definition $relationship,
		$child_id,
		$parent_slug = null
	) {
		$qry_args = array(
			Toolset_Association_Query::QUERY_RELATIONSHIP_SLUG => $relationship->get_slug(),
			Toolset_Association_Query::QUERY_CHILD_ID          => $child_id,
			Toolset_Association_Query::OPTION_RETURN           => Toolset_Association_Query::RETURN_PARENT_IDS
		);

		if ( $parent_slug ) {
			$qry_args['parent_query'] = array( 'post_type' => $parent_slug );
		}

		return $this->query_association( $qry_args );
	}

	/**
	 * Function to find parend id by relationship and child id
	 *
	 * @param IToolset_Relationship_Definition $relationship
	 * @param $parent_id
	 * @param null $child_slug
	 *
	 * @return bool|int[]|IToolset_Association[]|IToolset_Element[]
	 */
	public function find_child_id_by_relationship_and_parent_id(
		IToolset_Relationship_Definition $relationship,
		$parent_id,
		$child_slug = null
	) {
		$qry_args = array(
			Toolset_Association_Query::QUERY_RELATIONSHIP_SLUG => $relationship->get_slug(),
			Toolset_Association_Query::QUERY_PARENT_ID         => $parent_id,
			Toolset_Association_Query::OPTION_RETURN           => Toolset_Association_Query::RETURN_CHILD_IDS
		);

		if ( $child_slug ) {
			$qry_args[ Toolset_Association_Query::QUERY_CHILD_QUERY ] = array( 'post_type' => $child_slug );
		}

		return $this->query_association( $qry_args );
	}

	/**
	 * Function to find intermediary post id by relationship and child id
	 *
	 * @param IToolset_Relationship_Definition $relationship
	 * @param $post_id
	 *
	 * @return bool|int[]|IToolset_Association[]|IToolset_Element[]
	 *
	 */
	public function find_intermediary_by_relationship_and_child_id( IToolset_Relationship_Definition $relationship, $post_id ) {
		$qry_args = array(
			Toolset_Association_Query::QUERY_RELATIONSHIP_SLUG => $relationship->get_slug(),
			Toolset_Association_Query::QUERY_CHILD_ID          => $post_id,
			Toolset_Association_Query::OPTION_RETURN           => Toolset_Association_Query::RETURN_ASSOCIATIONS
		);

		return $this->query_association( $qry_args );
	}

	/**
	 * Function to find intermediary post id by relationship and parent id
	 *
	 * @param IToolset_Relationship_Definition $relationship
	 * @param $post_id
	 *
	 * @return bool|int[]|IToolset_Association[]|IToolset_Element[]
	 *
	 */
	public function find_intermediary_by_relationship_and_parent_id( IToolset_Relationship_Definition $relationship, $post_id ) {
		$qry_args = array(
			Toolset_Association_Query::QUERY_RELATIONSHIP_SLUG => $relationship->get_slug(),
			Toolset_Association_Query::QUERY_PARENT_ID         => $post_id,
			Toolset_Association_Query::OPTION_RETURN           => Toolset_Association_Query::RETURN_ASSOCIATIONS
		);

		return $this->query_association( $qry_args );
	}

	/**
	 * @param $qry_args
	 *
	 * @return bool|int[]|IToolset_Association[]|IToolset_Element[]
	 */
	private function query_association( $qry_args ) {
		if( ! $this->is_m2m_enabled() ) {
			return false;
		}

		Toolset_Relationship_Controller::get_instance()->initialize_full();
		$query   = new Toolset_Association_Query( $qry_args );
		$results = $query->get_results();

		if ( ! $results || empty( $results ) ) {
			return false;
		}

		return $results;
	}

	/**
	 * @param $parent_id
	 * @param array $children_args
	 *
	 * @return bool|int[]
	 * @internal param string $child_slug
	 */
	public function find_children_ids_by_parent_id( $parent_id, $children_args = array() ) {
		$children_args = wp_parse_args( $children_args, array(
			'post_type' => 'any',
			'post_status' => 'published',
			'numberposts' => -1,
			'suppress_filters' => 0,
		) );

		$qry_args = array(
			Toolset_Association_Query::QUERY_PARENT_ID   => $parent_id,
			Toolset_Association_Query::OPTION_RETURN     => Toolset_Association_Query::RETURN_CHILD_IDS,
			Toolset_Association_Query::QUERY_CHILD_QUERY => $children_args,
		);

		return $this->query_association( $qry_args );
	}

	/**
	 * @param $post_id
	 *
	 * @return IToolset_Association[]
	 */
	public function find_associations_by_id( $post_id ) {
		if( ! $this->is_m2m_enabled() ) {
			return false;
		}

		$qry_args = array(
			Toolset_Association_Query::QUERY_PARENT_ID   => $post_id,
			Toolset_Association_Query::OPTION_RETURN     => Toolset_Association_Query::RETURN_ASSOCIATIONS
		);

		$associations_parent = $this->query_association( $qry_args );
		$associations_parent = is_array( $associations_parent )
			? $associations_parent
			: array();

		$qry_args = array(
			Toolset_Association_Query::QUERY_CHILD_ID   => $post_id,
			Toolset_Association_Query::OPTION_RETURN     => Toolset_Association_Query::RETURN_ASSOCIATIONS
		);

		$associations_child = $this->query_association( $qry_args );
		$associations_child = is_array( $associations_child )
			? $associations_child
			: array();

		return array_merge( $associations_parent, $associations_child );
	}

	/**
	 * Function uses legacy structure to find parent id by child id and parent slug.
	 * NOTE: always check "m2m" relationship table before you try to find a legacy relationship
	 *
	 * @param $child_id
	 * @param $parent_slug
	 *
	 * @return bool|int
	 */
	public function legacy_find_parent_id_by_child_id_and_parent_slug( $child_id, $parent_slug ) {
		$parent_slug = sanitize_title( $parent_slug );

		$option_key = '_wpcf_belongs_' . $parent_slug . '_id';

		return get_post_meta( $child_id, $option_key, false );
	}
}