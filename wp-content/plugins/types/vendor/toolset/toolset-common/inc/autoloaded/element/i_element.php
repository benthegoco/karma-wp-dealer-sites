<?php

/**
 * Interface for an "element", which is a generic name for posts, users and terms.
 *
 * For instantiating elements, use Toolset_Element::get_instance().
 *
 * Note: All public methods dealing with fields need to call $this->initialize_fields() at the beginning.
 *
 * @since m2m
 */
interface IToolset_Element {


	/**
	 * @return string One of the Toolset_Field_Utils::get_domains() values.
	 */
	function get_domain();


	/**
	 * @return int ID of the underlying object.
	 */
	function get_id();


	/**
	 * @return string Element title.
	 */
	function get_title();


	/**
	 * Load custom fields of the element if they're not loaded yet.
	 *
	 * @return void
	 * @since m2m
	 */
	function initialize_fields();


	/**
	 * @return bool
	 */
	function are_fields_loaded();


	/**
	 * Get the object this model is wrapped around.
	 *
	 * @return mixed Depends on the implementation.
	 * @since m2m
	 */
	function get_underlying_object();


	/**
	 * Determine if the element has a particular field.
	 *
	 * It depends on the field definitions and field groups assigned to the element, not on the actual values in the
	 * database.
	 *
	 * @param string|Toolset_Field_Definition $field_source Field definition or a field slug.
	 * @return bool True if a field with given slug exists.
	 * @throws InvalidArgumentException When the field source has a wrong type.
	 * @since m2m
	 */
	function has_field( $field_source );


	/**
	 * Get a field instance.
	 *
	 * Check if has_field() before, otherwise may get an exception.
	 *
	 * @param string|Toolset_Field_Definition $field_source Field definition or a field slug.
	 * @return Toolset_Field_Instance
	 * @throws InvalidArgumentException When the field source has a wrong type.
	 */
	function get_field( $field_source );


	/**
	 * Get all field instances belonging to the element.
	 *
	 * @return Toolset_Field_Instance[]
	 * @since m2m
	 */
	function get_fields();


	function get_field_count();


	/**
	 * Determine whether the current element may have translations.
	 *
	 * @return bool
	 */
	function is_translatable();


	/**
	 * Get element language.
	 *
	 * @return string Language code or an empty string if not applicable.
	 * @since m2m
	 */
	function get_language();
}