<?php

/**
 * Abstract relationship driver.
 *
 * Relationship driver encapsulates all database operations specific to an individual relationship.
 * 
 * @since m2m
 */
abstract class Toolset_Relationship_Driver_Base {

	/** @var Toolset_Relationship_Definition */
	private $definition;

	/** @var array Driver setup array provided by the relationship definition. */
	private $setup;


	/**
	 * Toolset_Relationship_Driver_Base constructor.
	 *
	 * @param Toolset_Relationship_Definition $definition Relationship definition that is going to be using this driver.
	 * @param array $setup Driver setup array provided by the relationship definition.
	 *
	 * @since m2m
	 */
	public function __construct( $definition, $setup ) {

		if ( ! $definition instanceof Toolset_Relationship_Definition ) {
			throw new InvalidArgumentException();
		}

		$this->definition = $definition;

		$this->setup = toolset_ensarr( $setup );
	}


	/**
	 * @return Toolset_Relationship_Definition
	 */
	protected function get_relationship_definition() {
		return $this->definition;
	}


	/**
	 * @return string
	 */
	protected function get_relationship_slug() {
		$relationship_definition = $this->get_relationship_definition();

		return $relationship_definition->get_slug();
	}

	/**
	 * @param int|Toolset_Element|WP_Post $parent_source
	 * @param int|Toolset_Element|WP_Post $child_source
	 * @param array $args Optional arguments, implementation-specific
	 *
	 * @return Toolset_Association_Base|Toolset_Result ID of the new association on success or a result information with an error.
	 */
	public abstract function create_association( $parent_source, $child_source, $args = array() );


	/**
	 * Delete an association from the database.
	 *
	 * @param Toolset_Association_Base $association
	 *
	 * @return Toolset_Result
	 * @since m2m
	 */
	public abstract function delete_association( $association );


	/**
	 * Check if the driver can offer some field definitions for the relationship.
	 *
	 * @return bool
	 */
	public function has_field_definitions() {
		return count( $this->get_field_definitions() );
	}


	/**
	 * Check if fields for the managed relationship are translatable.
	 *
	 * @return bool
	 * @since m2m
	 */
	public function has_translatable_fields() {
		return false;
	}


	/**
	 * Get the field definitions for the relationship this driver is managing.
	 *
	 * @return Toolset_Field_Definition[]
	 */
	public function get_field_definitions() {
		return array();
	}


	/**
	 * Get information from the driver setup.
	 *
	 * @param null|string $argument Key from the setup array or null to return the whole array.
	 * @param mixed $default Value to return when the requested argument is not defined.
	 *
	 * @return mixed Whole setup array if no argument is provided, or argument value.
	 * @since m2m
	 */
	public function get_setup( $argument = null, $default = null ) {
		if ( null == $argument ) {
			return $this->setup;
		} else {
			return toolset_getarr( $this->setup, $argument, $default );
		}
	}


	protected function set_setup_argument( $argument, $value ) {
		$this->setup[ $argument ] = $value;
	}


	protected function is_association_match( $association ) {
		return ( $association instanceof Toolset_Association_Base && $association->get_driver() === $this );
	}

}