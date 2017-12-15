<?php

/*
Implementation of the update-show-sync verb.
Written by Lew Ayotte for iThemes.com
Version 1.0.0

Version History
	1.0.0 - 2016-12-15 - Lew Ayotte
		Initial version
*/


class Ithemes_Sync_Verb_Update_Google_Site_Verification_Token extends Ithemes_Sync_Verb {
	public static $name = 'update-google-site-verification-token';
	public static $description = 'Temporarily sets a meta token to verify sites w/ Google';
	
	public function run( $arguments ) {
		update_option( 'ithemes-sync-googst', array( 'code' => $arguments, 'expiry' => time() + ( 60 * 60 * 24 ) ) );
				
		return array( 'success' => 1 );
	}
}
