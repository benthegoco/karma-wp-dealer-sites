<?php

/**
 * Stores upgrade command definitions.
 *
 * Had to be extracted from Toolset_Upgrade_Controller for testability reasons.
 *
 * @since 2.5.4
 */
class Toolset_Upgrade_Command_Definition_Repository {

	public function get_commands() {

		$upgrade_commands = array(
			new Toolset_Upgrade_Command_Definition(
				'Toolset_Upgrade_Command_M2M_V1_Database_Structure_Upgrade',
				0
			)
		);

		return $upgrade_commands;
	}

}