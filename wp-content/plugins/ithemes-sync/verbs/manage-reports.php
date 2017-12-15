<?php

/*
Implementation of the manage-reports verb.
Written by Lew Ayotte for iThemes.com
Version 1.0.0

Version History
	1.0.0 - 2016-07-20 - Lew Ayotte
		Initial version
*/


class Ithemes_Sync_Verb_Manage_Reports extends Ithemes_Sync_Verb {
	public static $name = 'manage-reports';
	public static $description = 'Import Site Reports.';
	
	private $default_arguments = array();
	private $handled_activation = false;
	private $response = array();
	private $current_action = '';
	
	
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		$actions = array(
			'download-report' => 'download_report',
			'delete-report'   => 'delete_report',
		);
		
		if ( isset( $actions['get-actions'] ) ) {
			$this->response['get-actions'] = array_keys( $actions );
		}
		
		foreach ( $arguments as $action => $data ) {
			$this->current_action = $action;
			
			if ( isset( $actions[$action] ) ) {
				$this->response[$action] = call_user_func( array( $this, $actions[$action] ), $data );
			} else {
				$this->response[$action] = 'This action is not recognized';
			}
		}
		
		return $this->response;
	}
	
	private function set_fatal_error_handler() {
		if ( function_exists( 'error_get_last' ) ) {
			register_shutdown_function( array( $this, 'handle_fatal_error' ) );
		}
	}
	
	private function handle_fatal_error() {
		$error = error_get_last();
		
		if ( is_array( $error ) ) {
			$this->response['error'] = array(
				'error_trigger_action' => $this->current_action,
				'error_details'        => $error,
			);
			
			$GLOBALS['ithemes_sync_request_handler']->send_response( $this->response );
		}
	}
	
	private function download_report( $dest_filename ) {
		if ( ! is_string( $dest_filename ) ) {
			return new WP_Error( 'invalid-argument', 'The download-report function takes a string representing an individual report.' );
		}
		
        $report_url = 'https://s3.amazonaws.com/sync-reports.ithemes.com/' . $dest_filename;
        $upload_path = Ithemes_Sync_Functions::get_upload_reports_dir();
		$result = false;
        
        if ( wp_is_writable( $upload_path ) ) {
	        if ( ! file_exists( $upload_path . '/index.php' ) ) {
                @file_put_contents( $upload_path . '/index.php', '<?php' . PHP_EOL . '// Silence is golden.' );
	        }
			$response = wp_remote_get( $report_url );
			if ( ! is_wp_error( $response ) && 200 == wp_remote_retrieve_response_code( $response ) ) {
				$contents = wp_remote_retrieve_body( $response );
				@file_put_contents( $upload_path . '/' . $dest_filename, $contents );
				$result = Ithemes_Sync_Functions::get_upload_reports_url()  . '/' . $dest_filename;
			}
		}
		
		return $result;
	}
	
	private function delete_report( $filename ) {
		if ( !empty( $filename ) && !is_string( $filename ) ) {
			return new WP_Error( 'invalid-argument', 'The delete-report function takes a string representing an individual report.' );
		}
		
        $upload_path = Ithemes_Sync_Functions::get_upload_reports_dir();
        
        if ( !empty( $upload_path ) && wp_is_writable( $upload_path ) ) {
            @unlink( $upload_path . '/' . $filename );
            return true;
		}
		
		return false;
	}
	
}
