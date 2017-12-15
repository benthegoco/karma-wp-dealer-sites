<?php

/**
 * Model of a WordPress post.
 *
 * Simplifies the access to field instances and associations.
 *
 * @since m2m
 */
class Toolset_Post extends Toolset_Element implements IToolset_Post {

	const SORTORDER_META_KEY = 'toolset-post-sortorder';

	/** @var WP_Post */
	private $post;


	/** @var string Language code of the current post or an empty string if unknown or not applicable. */
	private $language_code = null;


	/**
	 * Toolset_Element constructor.
	 *
	 * @param mixed|int $object_source The underlying object or its ID.
	 * @param string|null $language_code Post's language. An empty string will be interpreted as
	 *     "this post has no language", while null can be passed if this unknown (and it will be
	 *     determined first time it's needed).
	 * @param null|Toolset_Field_Group_Post_Factory $group_post_factory DI for phpunit
	 *
	 * @since m2m
	 */
	protected function __construct( $object_source, $language_code = null, $group_post_factory = null ) {

		if( Toolset_Utils::is_natural_numeric( $object_source ) ) {
			$post = WP_Post::get_instance( $object_source );
		} else {
			$post = $object_source;
		}

		if( ! $post instanceof WP_Post ) {
			throw new InvalidArgumentException(
				sprintf( __( 'Unable to load post "%s".', 'wpcf' ), esc_html( print_r( $object_source, true ) ) )
			);
		}

		if( ! is_string( $language_code ) && null !== $language_code ) {
			throw new InvalidArgumentException( 'Invalid language code provided.' );
		}

		parent::__construct( $post, $group_post_factory );

		$this->post = $post;

		$this->language_code = $language_code;
	}


	/**
	 * Instantiate the post.
	 *
	 * To be used only within m2m API. For instantiating Toolset elements, you should
	 * always use Toolset_Element::get_instance().
	 *
	 * @param string|WP_Post $object_source
	 * @param string|null $language_code
	 *
	 * @deprecated Use Toolset_Element_Factory::get_post() instead.
	 *
	 * @return Toolset_Post
	 */
	public static function get_instance( $object_source, $language_code = null ) {
		return new self( $object_source, $language_code );
	}


	/**
	 * @return string One of the Toolset_Field_Utils::get_domains() values.
	 */
	public function get_domain() { return Toolset_Field_Utils::DOMAIN_POSTS; }


	/**
	 * @return int Post ID.
	 */
	public function get_id() { return $this->post->ID; }


	/**
	 * @return string Post title.
	 */
	public function get_title() { return $this->post->post_title; }


	/**
	 * @inheritdoc
	 * @return Toolset_Field_Group_Post[]
	 * @since m2m
	 */
	protected function get_relevant_field_groups() {

		$selected_groups = $this->group_post_factory->get_groups_by_post_type( $this->get_type() );

		return $selected_groups;
	}


	/**
	 * @return string Post type slug.
	 * @since m2m
	 */
	public function get_type() {
		return $this->post->post_type;
	}


	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function is_translatable() {
		return Toolset_Wpml_Utils::is_post_type_translatable( $this->get_type() );
	}


	/**
	 * @inheritdoc
	 * @return string
	 */
	function get_language() {
		if( null === $this->language_code ) {
			$post_language_details = apply_filters( 'wpml_post_language_details', null, $this->get_id() );
			$this->language_code = toolset_getarr( $post_language_details, 'language_code', '' );
		}

		return $this->language_code;
	}


	/**
	 * @param string $title New post title
	 *
	 * @return void
	 * @since m2m
	 */
	public function set_title( $title ) {
		$this->post->post_title = sanitize_text_field( $title );
	}


	/**
	 * @inheritdoc
	 * @return string
	 */
	public function get_slug() {
		return $this->post->post_name;
	}


}