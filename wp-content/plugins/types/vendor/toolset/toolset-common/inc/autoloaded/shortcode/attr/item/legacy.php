<?php

/**
 * Class Toolset_Shortcode_Attr_Item_Legacy
 *
 * Adds support for the Types legacy format, like "$parent" and "$post-type".
 *
 * @since m2m
 */
class Toolset_Shortcode_Attr_Item_Legacy extends Toolset_Shortcode_Attr_Item_Id {
	/**
	 * @var Toolset_Shortcode_Attr_Interface
	 */
	private $chain_link;

	/**
	 * @var Toolset_Relationship_Service
	 */
	private $service_relationship;

	/**
	 * Toolset_Shortcode_Attr_Item_Legacy constructor.
	 *
	 * @param Toolset_Shortcode_Attr_Interface $chain_link
	 * @param Toolset_Relationship_Service $service
	 *
	 * @internal param Types_Wordpress_Post $wp_post_api
	 */
	public function __construct( Toolset_Shortcode_Attr_Interface $chain_link, Toolset_Relationship_Service $service ) {
		$this->chain_link           = $chain_link;
		$this->service_relationship = $service;

	}

	/**
	 * @param array $data
	 *
	 * @return $this|int ->chain_link->get();
	 */
	public function get( array $data ) {
		if ( ! $role_slug = $this->handle_attr_synonyms( $data ) ) {
			return $this->chain_link->get( $data );
		}

		if ( substr( $role_slug, 0, 1 ) != '$' ) {
			// legacy format must start with $
			return $this->chain_link->get( $data );
		}

		global $post;

		if ( ! is_object( $post ) || ! property_exists( $post, 'ID' ) || ! property_exists( $post, 'post_type' ) ) {
			// no data without $post
			return $this->chain_link->get( $data );
		}

		$role_slug = substr( $role_slug, 1 );

		if( $role_slug == 'parent' ) {
			if ( property_exists( $post, 'post_parent' ) && ! empty( $post->post_parent ) ) {
				// this targets the wp build-in relationship between posts of same post type (hierarchical cpt)
				return $this->return_single_id( $post->post_parent );
			}

			return $this->chain_link->get( $data );
		}

		if( ! apply_filters( 'toolset_is_m2m_enabled', false ) ) {
			// m2m disabled
			if( $requested_id = $this->service_relationship->legacy_find_parent_id_by_child_id_and_parent_slug( $post->ID, $role_slug ) ) {
				return $this->return_single_id( $requested_id );
			}

			return $this->chain_link->get( $data );
		}

		if( $requested_id = $this->service_relationship->legacy_find_parent_id_by_child_id_and_parent_slug( $post->ID, $role_slug ) ) {
			return $this->return_single_id( $requested_id );
		}

		return $this->chain_link->get( $data );
	}
}