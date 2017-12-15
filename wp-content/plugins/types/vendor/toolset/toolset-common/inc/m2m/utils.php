<?php

/**
 * Various and constants for the Toolset relationships functionality.
 *
 * Note: Code related to native associations and database should go to Toolset_Relationship_Database_Operations.
 *
 * @since m2m
 */
class Toolset_Relationship_Utils {

	/**
	 * @param string|Toolset_Relationship_Definition $relationship_definition_source
	 *
	 * @return null|IToolset_Relationship_Definition
	 */
	public static function get_relationship_definition( $relationship_definition_source ) {
		$rd_factory = Toolset_Relationship_Definition_Repository::get_instance();

		if( $relationship_definition_source instanceof IToolset_Relationship_Definition ) {
			return $relationship_definition_source;
		} elseif( is_string( $relationship_definition_source ) ) {
			return $rd_factory->get_definition( $relationship_definition_source );
		} elseif( is_int( $relationship_definition_source ) ) {
			return $rd_factory->get_definition_by_row_id( $relationship_definition_source );
		}

		return null;
	}

}