<?php

/*
Implementation of the get-authentication-token verb.
Written by Lew Ayotte for iThemes.com
Version 1.0.0

Version History
	1.0.1 - 2016-08-36 - Lew Ayotte
		Added `login as user` support
	1.0.0 - 2016-08-22 - Lew Ayotte
		Initial version
*/

class Ithemes_Sync_Verb_Get_Authentication_Token extends Ithemes_Sync_Verb {
	public static $name = 'get-authentication-token';
	public static $description = 'Get authentication token from the WordPress authentication system.';

	private $default_arguments = array();
	private $response = array();
	
	private $auth_cookie;
	private $logged_in_cookie;
	

	public function run( $arguments ) {
		$response = array( 
			'site_url' => admin_url(), //Default to the wp-admin URL if something goes wrong.
		);
		$path = !empty( $arguments['path'] ) ? $arguments['path'] : '';
		$path_data = !empty( $arguments['path_data'] ) ? $arguments['path_data'] : '';
		
		$auth_details = $GLOBALS['ithemes-sync-settings']->get_authentication_details( $arguments['user_id'] );
		if ( !empty( $auth_details ) ) { //Make sure the synced user is still authenticated to this site
			if ( !empty( $arguments['wp_user_id'] ) ) { //If wp_user_id is passed, log in as that user.
				$user = get_user_by( 'id', $arguments['wp_user_id'] );
			} else { //Otherwise log in as the sync'ed user
				$user = get_user_by( 'login', $auth_details['local_user'] );
			}
			
			if ( !empty( $user ) ) {
				$scheme = is_ssl() || force_ssl_admin() ? 'https' : 'http';
				$key = bin2hex( random_bytes( 16 ) );
				$response = array( 
					'site_url' => add_query_arg( 'sync-login', $key, site_url( null, $scheme ) ),
				);
				update_option( 'sync_login_' . $key, array( 'user_id' => $user->ID, 'path' => $path, 'path_data' => $path_data, 'expires' => current_time( 'timestamp', true ) + 90 ) );
			}
		}

		return $response;
	}
}