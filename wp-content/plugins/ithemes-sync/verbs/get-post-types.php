<?php

/*
Implementation of the get-post-types verb.
Written by Lew Ayotte for iThemes.com
Version 1.0.0

Version History
	1.0.0 - 2016-10-17 - Lew Ayotte
		Initial version
*/


class Ithemes_Sync_Verb_Get_Post_Types extends Ithemes_Sync_Verb {
	public static $name = 'get-post-types';
	public static $description = 'Retrieve post types from WordPress.';
	public static $status_element_name = 'post-types';

	private $default_arguments = array(
		'args' => array(),
		'output' => 'names',
		'operator' => 'and',
	);
	
	public function run( $arguments ) {	
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		return get_post_types( $arguments['args'], $arguments['output'], $arguments['operator'] );
	}
}
