<?php

/**
 * Native Toolset relationship driver.
 *
 * @since m2m
 */
class Toolset_Relationship_Driver extends Toolset_Relationship_Driver_Base {


	const DA_INTERMEDIARY_POST_TYPE = 'intermediary_post_type';

	/** @var null|Toolset_Field_Definition[] */
	private $association_field_definitions = null;


	/**
	 * Create new native association in the database.
	 *
	 * @param int|Toolset_Element|WP_Post $parent_source
	 * @param int|Toolset_Element|WP_Post $child_source
	 * @param array $args Association arguments:
	 *     - 'intermediary_id': ID of the intermediary post; defaults to zero.
	 *     - 'instantiate': bool Whether to return an association on success, or just a result. Default is false
	 *
	 * todo check that the intermediary post really exists, has the correct type, is not taken
	 *
	 * @return IToolset_Association|Toolset_Result ID of the new association on success or a result information with an error.
	 */
	public function create_association( $parent_source, $child_source, $args = array() ) {

		$relationship_definition = $this->get_relationship_definition();

		// This will throw when the elements don't exist
		$parent = Toolset_Element::get_untranslated_instance( $relationship_definition->get_parent_domain(), $parent_source );
		$child = Toolset_Element::get_untranslated_instance( $relationship_definition->get_child_domain(), $child_source );

		// We need to make sure the association is allowed.
		if ( ! $relationship_definition->can_associate( $parent, $child ) ) {
			return new Toolset_Result( false, __( 'These two elements cannot be associated because they don\'t match the conditions for this relationship.', 'wpcf' ) );
		}

		$intermediary_id = (int) toolset_getarr( $args, 'intermediary_id', 0 );

		// Create intermediary post if the association should have some fields.
		$needs_fields = $relationship_definition->has_association_field_definitions();
		if ( 0 == $intermediary_id && $needs_fields ) {
			$intermediary_id = (int) $this->create_intermediary_post( $parent->get_id(), $child->get_id() );
		}

		// Insert the database records as needed, respecting the status of WPML and
		// existing translations of related items.
		$intermediary = ( 0 !== $intermediary_id ? Toolset_Post::get_instance( $intermediary_id ) : null );
		try {
			$insert_results = $this->translate_and_insert( $parent, $child, $intermediary );
		} catch( Exception $e ) {
			return new Toolset_Result(
				false,
				sprintf(
					__( 'Database error occurred when creating an association: %s', 'wpcf' ),
					$e->getMessage()
				)
			);
		}

		// Get the association instance (in the best language available)
		$instantiate_association = (bool) toolset_getarr( $args, 'instantiate', false );
		if ( $instantiate_association ) {
			$association = $this->instantiate_after_insert( $insert_results, $relationship_definition );

			return $association;
		} else {
			return new Toolset_Result( true, __( 'Association created', 'wpcf' ) );
		}

	}


	/**
	 * Return one instance of Toolset_Association, choosing the best language version.
	 *
	 * @param $insert_results
	 * @param $relationship_definition
	 *
	 * @return IToolset_Association
	 */
	private function instantiate_after_insert( $insert_results, $relationship_definition ) {

		// Prepare results into lang => ID arrays for each element.
		$element_sources = array(
			Toolset_Relationship_Role::PARENT => array(),
			Toolset_Relationship_Role::CHILD => array(),
			Toolset_Relationship_Role::INTERMEDIARY => array()
		);

		foreach ( $insert_results as $language_code => $insert_result ) {
			foreach ( Toolset_Relationship_Role::all_role_names() as $role_name ) {
				$element_sources[ $role_name ][ $language_code ] = $insert_result['elements'][ $role_name ];
			}
		}

		// Get a trid of the association (which is the same for all language versions).
		$single_language_version = array_slice( $insert_results, 0, 1 );
		$any_language_version = array_shift( $single_language_version );
		$trid = $any_language_version['trid'];

		$association = Toolset_Association_Repository::get_instance()->instantiate(
			$relationship_definition,
			$trid,
			$element_sources
		);

		return $association;
	}


	/**
	 * Get all translations of given element.
	 *
	 * For non-posts, non-translatable posts or when WPML is not active, it will not attempt any translation.
	 *
	 * @param IToolset_Element|null $element
	 *
	 * @return int[] Associative array of post IDs, keys are language codes.
	 */
	private function get_post_translations_or_default( $element ) {
		if ( ! $element instanceof Toolset_Element ) {
			return array( '' => 0 );
		} elseif (
			! $element instanceof Toolset_Post
			|| ! Toolset_Relationship_Multilingual_Mode::is_on()
			|| ! $element->is_translatable()
		) {
			return array( '' => $element->get_id() );
		}

		return Toolset_Wpml_Utils::get_post_translations_directly( $element->get_id() );
	}


	/**
	 * Get the complete translation information for given elements, organized into triplets by language.
	 *
	 * If WPML is not active or the element is not translatable, it will have language '' (empty string).
	 * If a particular element translation is missing, there will be a zero instead the translation ID.
	 *
	 * @param IToolset_Element $parent
	 * @param IToolset_Element $child
	 * @param Toolset_Post|null $intermediary
	 *
	 * @return array[string] Associative array of triplets, where keys are language codes and each
	 *     triplet always contains items for the parent, child and intermediary post IDs (or zero if
	 *     no translation is available).
	 */
	private function get_translation_triplets( $parent, $child, $intermediary ) {

		$parent_translations = $this->get_post_translations_or_default( $parent );
		$child_translations = $this->get_post_translations_or_default( $child );
		$intermediary_translations = $this->get_post_translations_or_default( $intermediary );

		$present_languages = array_unique(
			array_merge(
				array_keys( $parent_translations ),
				array_keys( $child_translations ),
				array_keys( $intermediary_translations )
			)
		);


		$translations = array();
		foreach ( $present_languages as $language_code ) {
			$parent_id = toolset_getarr( $parent_translations, $language_code, 0 );
			$child_id = toolset_getarr( $child_translations, $language_code, 0 );
			$intermediary_id = toolset_getarr( $intermediary_translations, $language_code, 0 );

			if ( 0 === $parent_id && 0 === $child_id ) {
				// There are no actual elements to associate, skip this language
				continue;
			}

			$translations[ $language_code ] = array(
				Toolset_Relationship_Role::PARENT => $parent_id,
				Toolset_Relationship_Role::CHILD => $child_id,
				Toolset_Relationship_Role::INTERMEDIARY => $intermediary_id
			);
		}

		return $translations;
	}


	/**
	 * Get all available translations of given elements and create the appropriate
	 * records in the associations table.
	 *
	 * @param IToolset_Element $parent
	 * @param IToolset_Element $child
	 * @param Toolset_Post|null $intermediary
	 *
	 * @return array Translation results. Each key is a language code (can be an empty string if
	 *     the language information is not available) and the item is an associative array with
	 *     following elements:
	 *     - id: The association ID for this language.
	 *     - trid: Association trid (same for all languages).
	 *     - elements: A "translation triplet" of element IDs in this language (or 0 where no translation exists)
	 *     - translation_type: 'none'|'original'|'translation'
	 *
	 * @since m2m
	 */
	private function translate_and_insert( $parent, $child, $intermediary ) {

		global $wpdb;

		$translations = $this->get_translation_triplets( $parent, $child, $intermediary );

		// We're going to need a unique trid to bind translations together.
		// Even if we don't use WPML, trid is still needed for grouping records in the associations table.
		$trid = Toolset_Relationship_Database_Operations::get_next_trid();

		$translation_type = 'none';
		$has_translations = ! ( 1 === count( $translations ) && array_key_exists( '', $translations ) );

		$insert_results = array();
		foreach ( $translations as $language_code => $elements ) {

			if ( $has_translations ) {
				$translation_type = $this->determine_translation_type( $elements, $parent, $child, $intermediary, $language_code );
			}

			$affected_rows = $wpdb->insert(
				Toolset_Relationship_Table_Name::associations(),
				array(
					'relationship_id' => $this->get_relationship_definition()->get_row_id(),
					'parent_id' => $elements[ Toolset_Relationship_Role::PARENT ],
					'child_id' => $elements[ Toolset_Relationship_Role::CHILD ],
					'intermediary_id' => $elements[ Toolset_Relationship_Role::INTERMEDIARY ],
					'trid' => $trid,
					'lang' => $language_code,
					'translation_type' => $translation_type
				),
				array(
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s'
				)
			);

			if ( false == $affected_rows ) {
				throw new RuntimeException( __( 'Error when inserting a row in the associations table.', 'wpcf' ) );
			}

			$association_id = $wpdb->insert_id;

			if ( ! Toolset_Utils::is_natural_numeric( $association_id ) ) {
				// Not an ID, fail
				throw new RuntimeException( __( 'Unable to obtain an ID of the newly created association.', 'wpcf' ) );
			}

			$insert_results[ $language_code ] = array(
				'id' => $association_id,
				'trid' => $trid,
				'elements' => $elements,
				'translation_type' => $translation_type
			);

		}

		return $insert_results;
	}


	/**
	 * Compare elements from a particular language version with the original ones and decide the
	 * translation type.
	 *
	 * The match of elements is required per each role, unless the original element is nontranslatable (
	 * or missing). When no language code is provided, the translation type is automatically 'none'.
	 *
	 * @param int[] $elements Element IDs, indexed by role names.
	 * @param IToolset_Element $original_parent
	 * @param IToolset_Element $original_child
	 * @param Toolset_Post|null $original_intermediary
	 * @param string $language_code
	 *
	 * @return string 'none'|'original'|'translation'
	 */
	private function determine_translation_type( $elements, $original_parent, $original_child, $original_intermediary, $language_code ) {
		if ( '' === $language_code ) {
			return 'none';
		}

		$is_original = (
			$this->is_element_original_or_untranslatable( $elements, Toolset_Relationship_Role::PARENT, $original_parent )
			&& $this->is_element_original_or_untranslatable( $elements, Toolset_Relationship_Role::CHILD, $original_child )
			&& $this->is_element_original_or_untranslatable( $elements, Toolset_Relationship_Role::INTERMEDIARY, $original_intermediary )
		);

		return ( $is_original ? 'original' : 'translation' );
	}


	/**
	 * Check if a particular elements match IDs or are untranslatable.
	 *
	 * @param int[] $elements_to_check
	 * @param string $role
	 * @param IToolset_Element $original_element
	 *
	 * @return bool
	 */
	private function is_element_original_or_untranslatable( $elements_to_check, $role, $original_element ) {

		$original_element_id = ( null === $original_element ? 0 : $original_element->get_id() );

		$result = (
			$elements_to_check[ $role ] == $original_element_id
			|| ! $original_element->is_translatable()
		);

		return $result;
	}


	/**
	 * Get the slug of the indermediary post type that holds association fields.
	 *
	 * @return string|null Post type slug or null if undefined/invalid.
	 * @since m2m
	 */
	public function get_intermediary_post_type() {
		$post_type_slug = $this->get_setup( self::DA_INTERMEDIARY_POST_TYPE );
		if ( ! is_string( $post_type_slug ) || empty( $post_type_slug ) ) {
			return null;
		}

		// todo check that it actually exists

		return $post_type_slug;
	}


	/**
	 * @return IToolset_Post_Type_From_Types|null
	 */
	public function get_intermediary_post_type_object() {
		$post_type_slug = $this->get_intermediary_post_type();

		if( null === $post_type_slug ) {
			return null;
		}

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return Toolset_Post_Type_Repository::get_instance()->get( $post_type_slug );
	}


	/**
	 * Create a new intermediary post type if it doesn't exist yet.
	 *
	 * @param null|string $new_slug_candidate Use this post slug if possible.
	 * @param boolean     $is_public If the intermediary post type is public.
	 * @return string Post type slug.
	 */
	public function create_intermediary_post_type( $new_slug_candidate = null, $is_public = false ) {
		$post_type_repository = Toolset_Post_Type_Repository::get_instance();

		$post_type_slug = $this->get_intermediary_post_type();
		if( null !== $post_type_slug && $post_type_repository->has( $post_type_slug ) ) {
			/** @noinspection PhpParamsInspection */
			$this->set_intermediary_post_type( $post_type_repository->get( $post_type_slug ), $is_public );
			return $post_type_slug;
		}

		$naming_helper = Toolset_Naming_Helper::get_instance();

		$names = array(
			'slug' => ( null === $new_slug_candidate ? $this->get_relationship_slug() : $new_slug_candidate ),
			'label_name' => sprintf(
				__( '%s Intermediary Posts', 'wpcf' ),
				$this->get_relationship_definition()->get_display_name_plural()
			),
			'label_singular_name' => sprintf(
				__( '%s Intermediary Post', 'wpcf' ),
				$this->get_relationship_definition()->get_display_name_singular()
			)
		);

		$filtered_names = apply_filters( 'toolset_new_intermediary_post_type_names', $names );

		$names = wp_parse_args( $filtered_names, $names );
		$post_type_slug = $naming_helper->generate_unique_post_type_slug( $names['slug'] );

		$post_type = $post_type_repository->create(
			$post_type_slug, $names['label_name'], $names['label_singular_name']
		);

		$this->set_intermediary_post_type( $post_type, $is_public );

		$post_type->set_is_public( $is_public );
		$post_type_repository->save( $post_type );

		return $post_type_slug;
	}


	/**
	 * Set the intermediary post type for a relationship.
	 *
	 * Also update the "is_intermediary" flag in the new and previous type (if they exist).
	 *
	 * @param IToolset_Post_Type_From_Types|null $post_type Post type or null to unlink an intermediary post type.
	 * @param boolean                            $is_public If the intermediary post type is public.
	 */
	public function set_intermediary_post_type( IToolset_Post_Type_From_Types $post_type = null, $is_public = false ) {

		$post_type_repository = Toolset_Post_Type_Repository::get_instance();

		if ( null === $post_type ) {
			$new_post_type_slug = '';
		} else {
			$new_post_type_slug = $post_type->get_slug();
		}

		$previous_post_type = $this->get_intermediary_post_type_object();
		if( null !== $previous_post_type && $previous_post_type->get_slug() !== $new_post_type_slug ) {
			$previous_post_type->unset_as_intermediary();
			$post_type_repository->save( $previous_post_type );
		}

		if( null !== $post_type ) {
			$post_type->set_as_intermediary( $is_public );
			$post_type_repository->save( $post_type );
		}

		$this->set_setup_argument( self::DA_INTERMEDIARY_POST_TYPE, $new_post_type_slug );
	}


	/**
	 * @inheritdoc
	 *
	 * @return Toolset_Field_Definition[]
	 * @since m2m
	 */
	public function get_field_definitions() {

		if ( null === $this->association_field_definitions ) {

			$intermediary_post_type = $this->get_intermediary_post_type();

			if ( null == $intermediary_post_type ) {
				$this->association_field_definitions = array();
			} else {
				$this->association_field_definitions = Toolset_Field_Utils::get_field_definitions_for_post_type( $intermediary_post_type );
			}
		}

		return $this->association_field_definitions;
	}


	/**
	 * @inheritdoc
	 *
	 * In the context of native Toolset relationships, the association fields are translatable when the intermediary
	 * post type is translatable.
	 *
	 * @return bool
	 */
	public function has_translatable_fields() {
		return $this->has_field_definitions() && Toolset_Wpml_Utils::is_post_type_translatable( $this->get_intermediary_post_type() );
	}


	/**
	 * Create an intermediary post for a new association.
	 *
	 * @param int $parent_id
	 * @param int $child_id
	 *
	 * @return int|null ID of the new post or null if the post creation failed.
	 * @since m2m
	 */
	private function create_intermediary_post( $parent_id, $child_id ) {
		$post_type = $this->get_intermediary_post_type();

		if ( null == $post_type ) {
			return null;
		}

		/**
		 * toolset_build_intermediary_post_title
		 *
		 * Allow for overriding the post title of an intermediary post.
		 *
		 * @param string $post_title Post title default value.
		 * @param string $relationship_slug
		 * @param int $parent_id
		 * @param int $child_id
		 *
		 * @since m2m
		 */
		$post_title = wp_strip_all_tags(
			apply_filters(
				'toolset_build_intermediary_post_title',
				$this->get_default_intermediary_post_title( $parent_id, $child_id ),
				$this->get_relationship_slug(),
				$parent_id,
				$child_id
			)
		);

		/**
		 * toolset_build_intermediary_post_name
		 *
		 * Allow for overriding the post name (slug) of an intermediary post.
		 *
		 * @param string $post_slug Post slug default value.
		 * @param string $relationship_slug
		 * @param int $parent_id
		 * @param int $child_id
		 *
		 * @since m2m
		 */
		$post_name = apply_filters(
			'toolset_build_intermediary_post_name',
			$post_title,
			$this->get_relationship_slug(),
			$parent_id,
			$child_id
		);

		$result = wp_insert_post(
			array(
				'post_type' => $post_type,
				'post_title' => $post_title,
				'post_name' => $post_name,
				'post_content' => '',
				'post_status' => 'publish'
			),
			true
		);

		if ( $result instanceof WP_Error ) {
			return null;
		} else {
			return $result;
		}
	}


	private function get_default_intermediary_post_title( $parent_id, $child_id ) {
		// Todo improve this - allow for specifying a default template in the relationship definition
		$relationship_definition = $this->get_relationship_definition();

		return sprintf(
			'%s: %d - %d',
			$relationship_definition->get_display_name(),
			$parent_id,
			$child_id
		);
	}


	/**
	 * Delete an association from the database.
	 *
	 * Also delete an intermediary post if it exists.
	 *
	 * @param Toolset_Association $association
	 *
	 * @return Toolset_Result
	 * @since m2m
	 */
	public function delete_association( $association ) {

		if ( ! $this->is_association_match( $association ) ) {
			throw new InvalidArgumentException();
		}

		// Trigger the association translation view refresh.
		// fixme probably no longer needed
		$view_management = Toolset_Relationship_WPML_Interoperability::get_instance();
		$view_management->before_association_delete( $association );

		$this->maybe_delete_intermediary_post( $association );

		global $wpdb;

		$rows_updated = $wpdb->delete(
			Toolset_Relationship_Table_Name::associations(),
			// todo make this clearer - add a method to specifically query for trid
			array( 'trid' => $association->get_uid() ),
			'%d'
		);

		$is_success = ( false !== $rows_updated || 1 === $rows_updated );

		return new Toolset_Result( $is_success );
	}


	/**
	 * Delete the intermediary post if it exists and it's not disabled by a filter.
	 *
	 * fixme probably need to delete its translations too (in that case, rename)
	 *
	 * @param Toolset_Association $association
	 */
	private function maybe_delete_intermediary_post( $association ) {

		if ( $association->has_intermediary_post() ) {
			$intermediary_id = $association->get_intermediary_id();

			/**
			 * toolset_deleting_association_intermediary_post
			 *
			 * Notify about deleting the intermediary post and allow avoiding it.
			 *
			 * @param bool $delete_post Whether the post should be deleted.
			 * @param int $intermediary_id ID of the intermediary post.
			 * @param int $association_uid Unique identifier of the (native) association that is removing it
			 * @param Toolset_Association $association The association object.
			 */
			$delete_post = apply_filters(
				'toolset_deleting_association_intermediary_post',
				true,
				$intermediary_id,
				$association->get_uid(),
				$association
			);

			if ( $delete_post ) {
				wp_delete_post( $intermediary_id );
			}
		}
	}


	protected function is_association_match( $association ) {
		return ( parent::is_association_match( $association ) && $association instanceof Toolset_Association );
	}

}
