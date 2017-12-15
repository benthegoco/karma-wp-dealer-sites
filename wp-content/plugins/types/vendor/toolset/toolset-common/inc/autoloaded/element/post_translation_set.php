<?php

/**
 * Represents a set of post translations.
 *
 * This class can act as a single post, working as a proxy to one language version of it.
 *
 * Each of the interface methods has an additional parameter where a language code may be specified. If it isn't,
 * the best available translation will be chosen: current language > default language > original post language.
 *
 * In all other aspects, these methods act exactly the same way as in Toolset_Post.
 *
 * Note: It is not possible to instantiate this class when WPML is not active. You should always
 * use Toolset_Element::get_instance() instead of reinventing its logic elsewhere.
 *
 * @since m2m
 */
class Toolset_Post_Translation_Set implements IToolset_Post {


	/** @var Toolset_Post[] */
	private $translations = array();


	/** @var int Any post ID from the translation group, which will be used to quickly obtain a translation from WPML. */
	private $starting_post_id;


	/** @var Toolset_Post[] Cache for get_best_translation().  */
	private $best_translation_for = array();


	/**
	 * Toolset_Post_Translation_Set constructor.
	 *
	 * @param Toolset_Post[] $translations Array of this post's translations indexed by language codes.
	 *     It doesn't need to be complete, but having these values ready can improve performance.
	 *
	 * @since m2m
	 */
	public function __construct( $translations ) {

		if ( ! Toolset_WPML_Compatibility::get_instance()->is_wpml_active_and_configured() ) {
			throw new RuntimeException( 'Attempted to use a post translation set while WPML was inactive' );
		}

		if ( ! is_array( $translations ) || empty( $translations ) ) {
			throw new InvalidArgumentException( 'Invalid argument when creating a post translation set');
		}

		foreach ( $translations as $translation ) {
			if ( ! $translation instanceof Toolset_Post ) {
				$translation = Toolset_Element::get_instance( $this->get_domain(), $translation );
			}

			$this->translations[ $translation->get_language() ] = $translation;
		}

		/** @var Toolset_Post $some_translation */
		$some_translation = array_shift( array_slice( $this->translations, 0, 1 ) );
		$this->starting_post_id = $some_translation->get_id();
	}


	/**
	 * For a given language code, fetch a translation ID from WPML.
	 *
	 * @param string $language_code Target language.
	 * @param bool $return_original_if_missing If true, something will always be returned.
	 *
	 * @return int ID of the post translation or zero if it doesn't exist.
	 */
	private function fetch_translation( $language_code, $return_original_if_missing = false ) {

		// See https://wpml.org/wpml-hook/wpml_object_id/
		//
		// Notice that $return_original_if_missing is set to false by default, so we'll not get a result that's not
		// truly translated.
		$id = (int) apply_filters( 'wpml_object_id', $this->starting_post_id, $this->get_type(), $return_original_if_missing, $language_code );

		// fixme handle when there's no translation and the same ID is returned as the starting one
		return $id;
	}


	/**
	 * Get an ID of post translation.
	 *
	 * The result is cached for performance optimization, and may be based on the data provided in the constructor.
	 *
	 * @param string $language_code
	 *
	 * @return Toolset_Post|null The translation or null if none exists.
	 * @since m2m
	 */
	private function get_translation( $language_code ) {

		if ( ! array_key_exists( $language_code, $this->translations ) ) {
			$translated_post_id = $this->fetch_translation( $language_code );
			if ( $translated_post_id !== 0 ) {
				$translation = Toolset_Post::get_instance( $translated_post_id, $language_code );
			} else {
				$translation = null;
			}

			$this->translations[ $language_code ] = $translation;
		}

		return $this->translations[ $language_code ];

	}


	private function is_translated_to( $language_code ) {
		$translation = $this->get_translation( $language_code );

		return ( null !== $translation );
	}


	/**
	 * Get the translation in default site language or, if that is not available, the original translation.
	 *
	 * @return Toolset_Post
	 */
	private function get_original_translation() {
		$translated_post_id = $this->fetch_translation( Toolset_Wpml_Utils::get_default_language(), true );

		$post_language_details = apply_filters( 'wpml_post_language_details', null, $translated_post_id );
		$language_code = toolset_getarr( $post_language_details, 'language_code' );

		$translation = Toolset_Post::get_instance( $translated_post_id, $language_code );

		// Store it in cache only if we got a language code. At this point I don't trust anything.
		if ( is_string( $language_code ) && ! empty( $language_code ) ) {
			$this->translations[ $language_code ] = $translation;
		}

		return $translation;
	}


	/**
	 * Choose the best available translation.
	 *
	 * Priorities: given language > current language > default language > original post language.
	 *
	 * @param null|string $language_code
	 *
	 * @return Toolset_Post
	 */
	private function get_best_translation( $language_code = null ) {

		if ( null === $language_code ) {
			$language_code = Toolset_Wpml_Utils::get_current_language();
		}

		if( array_key_exists( $language_code, $this->best_translation_for ) ) {
			return $this->best_translation_for[ $language_code ];
		}

		if ( $this->is_translated_to( $language_code ) ) {
			$post = $this->get_translation( $language_code );
		} else {
			$default_language = Toolset_Wpml_Utils::get_default_language();
			if ( $this->is_translated_to( $default_language ) ) {
				$post = $this->get_translation( $default_language );
			} else {
				$post = $this->get_original_translation();
			}
		}

		$this->best_translation_for[ $language_code ] = $post;

		return $post;
	}


	/**
	 * @return string One of the Toolset_Field_Utils::get_domains() values.
	 */
	public function get_domain() {
		return Toolset_Field_Utils::DOMAIN_POSTS;
	}

	/**
	 * @param null|string $language_code If null, the best translation will be selected automatically.
	 *
	 * @return int ID of the underlying object.
	 */
	public function get_id( $language_code = null ) {
		return $this->get_best_translation( $language_code )->get_id();
	}


	/**
	 * @param null|string $language_code If null, the best translation will be selected automatically.
	 *
	 * @return string Post title.
	 */
	public function get_title( $language_code = null ) {
		return $this->get_best_translation( $language_code )->get_title();
	}


		/**
	 * @param null|string $language_code If null, the best translation will be selected automatically.
	 *
	 * @return string Post type slug.
	 * @since m2m
	 */
	function get_type( $language_code = null ) {
		return $this->get_best_translation( $language_code )->get_type();
	}


	/**
	 * Load custom fields of the element if they're not loaded yet.
	 *
	 * @param null|string $language_code If null, the best translation will be selected automatically.
	 *
	 * @return void
	 * @since m2m
	 */
	function initialize_fields( $language_code = null ) {
		$this->get_best_translation( $language_code )->initialize_fields();
	}

	/**
	 * @param null|string $language_code If null, the best translation will be selected automatically.
	 *
	 * @return bool
	 */
	function are_fields_loaded( $language_code = null ) {
		return $this->get_best_translation( $language_code )->are_fields_loaded();
	}

	/**
	 * Get the object this model is wrapped around.
	 *
	 * @param null $language_code
	 *
	 * @return mixed Depends on the subclass.
	 * @since m2m
	 */
	function get_underlying_object( $language_code = null ) {
		return $this->get_best_translation( $language_code )->get_underlying_object();
	}


	/**
	 * Determine if the element has a particular field.
	 *
	 * It depends on the field definitions and field groups assigned to the element, not on the actual values in the
	 * database.
	 *
	 * @param string|Toolset_Field_Definition $field_source Field definition or a field slug.
	 *
	 * @param null $language_code
	 *
	 * @return bool True if a field with given slug exists.
	 * @since m2m
	 */
	function has_field( $field_source, $language_code = null ) {
		return $this->get_best_translation( $language_code )->has_field( $field_source );
	}


	/**
	 * Get a field instance.
	 *
	 * Check if has_field() before, otherwise you'll get an exception.
	 *
	 * @param string|Toolset_Field_Definition $field_source Field definition or a field slug.
	 *
	 * @param null|string $language_code If null, the best translation will be selected automatically.
	 *
	 * @return Toolset_Field_Instance
	 */
	function get_field( $field_source, $language_code = null ) {
		return $this->get_best_translation( $language_code )->get_field( $field_source );
	}


	/**
	 * Get all field instances belonging to the element.
	 *
	 * @param null|string $language_code If null, the best translation will be selected automatically.
	 *
	 * @return Toolset_Field_Instance[]
	 * @since m2m
	 */
	function get_fields( $language_code = null ) {
		return $this->get_best_translation( $language_code )->get_fields();
	}

	/**
	 * @param null|string $language_code If null, the best translation will be selected automatically.
	 *
	 * @return int
	 */
	function get_field_count( $language_code = null ) {
		return $this->get_best_translation( $language_code )->get_field_count();
	}


	/**
	 * @return bool
	 */
	function is_translatable() {
		// fixme actually check this
		return true;
	}


	/**
	 * Get the actual language of the post when selecting a specific language.
	 *
	 * It will differ in case of missing translations.
	 *
	 * @param null $language_code
	 *
	 * @return string
	 */
	function get_language( $language_code = null ) {
		return $this->get_best_translation( $language_code )->get_language();
	}


	/**
	 * @param null|string $language_code
	 *
	 * @return string Post slug
	 * @since m2M
	 */
	public function get_slug( $language_code = null ) {
		return $this->get_best_translation( $language_code )->get_slug();
	}

	/**
	 * @param string $title New post title
	 *
	 * @param null|string $language_code
	 *
	 * @return void
	 * @since m2m
	 */
	public function set_title( $title, $language_code = null ) {
		$this->get_best_translation( $language_code )->set_title( $title );
	}
}