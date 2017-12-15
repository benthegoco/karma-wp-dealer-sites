<?php

/**
 * Factory for IToolset_Element.
 *
 * Note: Currently used only for the purpose of unit test mocking. Eventually the relevant code should be moved here.
 *
 * @since m2m
 */
class Toolset_Element_Factory {


	/**
	 * Get an element instance based on it's domain.
	 *
	 * @param string $domain Valid element domain as defined in Toolset_Field_Utils.
	 * @param mixed $object_source Source of the underlying object that will be recognized by the specific element class.
	 *     It also recognizes translation sets (array of sources, indexed by language code) for posts.
	 *
	 * @return IToolset_Element
	 * @since m2m
	 */
	public function get_element( $domain, $object_source ) {
		return Toolset_Element::get_instance( $domain, $object_source );
	}


	/**
	 * Instantiate the post.
	 *
	 * To be used only within m2m API. For instantiating Toolset elements, you should
	 * always use Toolset_Element::get_instance().
	 *
	 * @param string|WP_Post $object_source
	 * @param null|string $language_code
	 *
	 * @return Toolset_Post
	 */
	public function get_post( $object_source, $language_code = null ) {
		return Toolset_Post::get_instance( $object_source, $language_code );
	}

}