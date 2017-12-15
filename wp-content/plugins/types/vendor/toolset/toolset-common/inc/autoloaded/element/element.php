<?php

/**
 * Model representing an "element", which is a generic name for posts, users and terms.
 *
 * It is supposed to simplify working with fields (field instances) and associations.
 * Its subclasses can be used in a similar way to WP_Post.
 *
 * Fields are loaded on-demand, when a field-related method is used.
 *
 * Note: All public methods dealing with fields need to call $this->initialize_fields() at the beginning.
 *
 * @since m2m
 */
abstract class Toolset_Element implements IToolset_Element {


	/** @var mixed The underlying object. */
	protected $object;


	/** @var Toolset_Field_Instance[] */
	protected $fields;


	/** @var Toolset_Field_Definition[] Definitions of fields that belong to this element (regardless their field group). */
	protected $aggregated_field_definitions;


	private $are_fields_initialized = false;


	/** @var Toolset_Field_Group_Post_Factory */
	protected $group_post_factory;


	/**
	 * Toolset_Element constructor.
	 *
	 * @param mixed $object_source The underlying object. The subclass is responsible for providing / validating
	 *    a correct value.
	 * @param null|Toolset_Field_Group_Post_Factory $group_post_factory DI for phpunit
	 */
	protected function __construct( $object_source, Toolset_Field_Group_Post_Factory $group_post_factory = null ) {

		$this->group_post_factory = ( null === $group_post_factory ) ? Toolset_Field_Group_Post_Factory::get_instance() : $group_post_factory;

		$this->object = $object_source;
	}


	/**
	 * Get an element instance based on it's domain.
	 *
	 * @param string $domain Valid element domain as defined in Toolset_Field_Utils.
	 * @param mixed $object_source Source of the underlying object that will be recognized by the specific element class.
	 *     It also recognizes translation sets (array of sources, indexed by language code) for posts.
	 *
	 * @return IToolset_Element
	 * @since m2m
	 * @deprecated Use Toolset_Element_Factory::get_element() instead.
	 */
	public static function get_instance( $domain, $object_source ) {

		switch( $domain ) {

			case Toolset_Field_Utils::DOMAIN_POSTS:

				if( $object_source instanceof IToolset_Post ) {
					// todo handle Toolset_Post where we should be returning Toolset_Post_Translation_Set
					return $object_source;

				}

				if( Toolset_WPML_Compatibility::get_instance()->is_wpml_active_and_configured() ) {

					// If we got a post object and we know it's not translatable, we don't need to bother.
					//
					// Without the post object (when we get only an ID, for example), we won't bother for performance reasons.
					if( $object_source instanceof WP_Post && ! Toolset_Wpml_Utils::is_post_type_translatable( $object_source->post_type ) ) {
						return self::get_untranslated_instance( $domain, $object_source );
					}

					if( ! is_array( $object_source ) ) {
						$object_source = array( $object_source );
					}

					$translated_posts = array();

					// Get a Toolset_Post for each translation
					foreach( $object_source as $language_code => $post_id ) {

						if( ! is_string( $language_code ) || empty( $language_code ) ) {
							// no (known) language here
							$language_code = null;
						}

						$post = Toolset_Post::get_instance( $post_id, $language_code );

						$translated_posts[ $post->get_language() ] = $post;
					}

					return new Toolset_Post_Translation_Set( $translated_posts );

				}

				// No WPML, simply return the post object.
				return self::get_untranslated_instance( $domain, $object_source );


			default:
				return self::get_untranslated_instance( $domain, $object_source );
		}
	}


	/**
	 * Get an element instance without attempting translation.
	 *
	 * Use with care. Normally you should never need this one and stick with get_instance().
	 *
	 * @param string $domain Valid element domain as defined in Toolset_Field_Utils.
	 * @param mixed $object_source Source of the underlying object that will be recognized by the specific element class.
	 *
	 * @return IToolset_Element
	 */
	public static function get_untranslated_instance( $domain, $object_source ) {
		switch( $domain ) {
			case Toolset_Field_Utils::DOMAIN_POSTS:
				return Toolset_Post::get_instance( $object_source );
			case Toolset_Field_Utils::DOMAIN_TERMS:
			case Toolset_Field_Utils::DOMAIN_USERS:
				throw new RuntimeException( 'Not implemented.' );
			default:
				throw new InvalidArgumentException( 'Invalid domain name.' );
		}
	}


	/**
	 * @return string One of the Toolset_Field_Utils::get_domains() values.
	 */
	public abstract function get_domain();


	/**
	 * @return int ID of the underlying object.
	 */
	public abstract function get_id();


	/**
	 * Load custom fields of the element if they're not loaded yet.
	 *
	 * @return void
	 * @since m2m
	 */
	public function initialize_fields() {

		if( $this->are_fields_initialized ) {
			return;
		}

		$this->load_fields();

		$this->are_fields_initialized = true;
	}


	/**
	 * Load custom fields of the element.
	 *
	 * @since m2m
	 */
	protected function load_fields() {

		$selected_groups = $this->get_relevant_field_groups();

		$this->aggregated_field_definitions = $this->get_aggregated_field_definitions( $selected_groups );

		$this->fields = $this->get_field_instances( $this->aggregated_field_definitions );

	}


	/**
	 * @return Toolset_Field_Group[] Field groups that are assigned to this element.
	 * @since m2m
	 */
	protected abstract function get_relevant_field_groups();


	/**
	 * For given field definitions, create their instances.
	 *
	 * @param Toolset_Field_Definition[] $field_definitions
	 * @return Toolset_Field_Instance[] Field instances indexed by field slugs.
	 * @throws InvalidArgumentException
	 * @since m2m
	 *
	 * TODO: Do we need to throw an exception? Isn't it better to catch and replace the field instance
	 * TODO     by "Instance_Unsaved" one? Or should it be optional?
	 */
	protected function get_field_instances( $field_definitions ) {

		$instances = array();

		foreach( $field_definitions as $field_definition ) {
			$field_instance = $field_definition->instantiate( $this->get_id() );
			$instances[ $field_definition->get_slug() ] = $field_instance;
		}

		return $instances;
	}


	/**
	 * For given set of field groups, return an array of (unique) field definitions.
	 *
	 * @param Toolset_Field_Group[] $field_groups
	 * @return Toolset_Field_Definition[] Field definitions indexed by field slugs.
	 */
	protected function get_aggregated_field_definitions( $field_groups ) {

		$results = array();
		foreach( $field_groups as $group ) {
			$field_definitions = $group->get_field_definitions();

			/** @var Toolset_Field_Definition $field_definition */
			foreach( $field_definitions as $field_definition ) {
				if( ! array_key_exists( $field_definition->get_slug(), $results ) ) {
					$results[ $field_definition->get_slug() ] = $field_definition;
				}
			}
		}

		return $results;
	}


	/**
	 * @return bool
	 */
	public function are_fields_loaded() { return $this->are_fields_initialized; }


	/**
	 * Get the object this model is wrapped around.
	 *
	 * @return mixed Depends on the subclass.
	 * @since m2m
	 */
	public function get_underlying_object() { return $this->object; }


	/**
	 * Determine if the element has a particular field.
	 *
	 * It depends on the field definitions and field groups assigned to the element, not on the actual values in the
	 * database.
	 *
	 * @param string|Toolset_Field_Definition $field_source Field definition or a field slug.
	 * @return bool True if a field with given slug exists.
	 * @throws InvalidArgumentException
	 * @since m2m
	 */
	public function has_field( $field_source ) {
		$field_slug = Toolset_Field_Utils::get_field_slug( $field_source );
		$this->initialize_fields();
		return array_key_exists( $field_slug, $this->fields );
	}


	/**
	 * Get a field instance.
	 *
	 * Check if has_field() before, otherwise you'll get an exception.
	 *
	 * @param string|Toolset_Field_Definition $field_source Field definition or a field slug.
	 * @return Toolset_Field_Instance
	 * @throws InvalidArgumentException
	 */
	public function get_field( $field_source ) {

		$this->initialize_fields();

		$field_slug = Toolset_Field_Utils::get_field_slug( $field_source );

		if( ! $this->has_field( $field_slug ) ) {
			throw new InvalidArgumentException( 'The element has no such field.' );
		}

		return $this->fields[ $field_slug ];
	}


	/**
	 * Get all field instances belonging to the element.
	 *
	 * @return Toolset_Field_Instance[]
	 * @since m2m
	 */
	public function get_fields() {

		$this->initialize_fields();
		return $this->fields;

	}


	public function get_field_count() {
		return count( $this->get_fields() );
	}


	/**
	 * Determine whether the current element may have translations.
	 *
	 * @return bool
	 */
	public function is_translatable() {
		return false;
	}

}