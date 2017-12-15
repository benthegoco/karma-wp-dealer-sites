<?php

/*
Implementation of the manage-posts verb.
Written by Lew Ayotte for iThemes.com
Version 1.0.0

Version History
	1.0.0 - 2016-10-27 - Lew Ayotte
		Initial version
*/

class Ithemes_Sync_Verb_Manage_Posts extends Ithemes_Sync_Verb {
	public static $name = 'manage-posts';
	public static $description = 'WordPress post verbs.';
	
	private $default_arguments = array();
	private $response = array();
	private $posts_cache = array();
	
	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		
		$actions = array(
			'add'                 => '',
			'edit'                => '',
			'trash'               => array( $this, 'trash_post' ),
			'restore'             => array( $this, 'restore_post' ),
			'untrash'             => array( $this, 'restore_post' ),
			'delete'              => array( $this, 'delete_post' ),
			'emptytrash'          => array( $this, 'empty_trash' ),
			'deletepostrevisions' => array( $this, 'delete_revisions' ),
		);
		
		foreach ( $arguments as $action => $data ) {
			if ( 'get-actions' == $action ) {
				$this->response[$action] = array_keys( $actions );
				continue;
			}
			
			if ( ! isset( $actions[$action] ) ) {
				$this->response[$action] = 'This action is not recognized.';
				continue;
			}
			if ( in_array( $action, array( 'deletepostrevisions', 'emptytrash' ) ) ) {
				$function = $actions[$action];
				if ( ! is_callable( $actions[$action] ) ) {
					return new WP_Error( "missing-function-$function", "Due to an unknown issue, the $function function is not available." );
				}
				$this->response[$action] = call_user_func( $function, $data );
			} else {
				$this->response[$action] = $this->run_function_on_ids( $actions[$action], $data );
			}		
		}
		
		return $this->response;
	}
	
	private function is_valid_post_id( $id ) {
		if ( isset( $this->posts_cache[$id] ) ) {
			return $this->posts_cache[$id];
		}
		
		$post = get_post( $id );
		
		if ( empty( $post ) || is_wp_error( $post ) ) {
			$post = false;
		} else {
			$post = true;
		}
		
		$this->posts_cache[$id] = $post;
		
		return $post;
	}
	
	private function run_function_on_ids( $function, $ids ) {
		if ( ! is_callable( $function ) ) {
			return new WP_Error( "missing-function-$function", "Due to an unknown issue, the $function function is not available." );
		}
		
		$response = array();
		
		foreach ( $ids as $id ) {
			if ( ! $this->is_valid_post_id( $id ) ) {
				$response[$id] = new WP_Error( 'invalid-post-id', 'The requested post could not be found.' );
			} else {
				$response[$id] = call_user_func( $function, $id );
			}
		}
		
		return $response;
	}
		
	private function trash_post( $id ) {
		return wp_trash_post( $id );
	}
	
	private function restore_post( $id ) {
		return wp_untrash_post( $id );
	}
	
	private function delete_post( $id ) {
		return wp_delete_post( $id, true );
	}
	
	private function empty_trash( $post_type ) {
		global $wpdb;
		
        $post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type=%s AND post_status = 'trash'", $post_type ) );

	    $deleted = 0;
	    foreach ( $post_ids as $post_id ) { // Check the permissions on each
            wp_delete_post( $post_id, true );
            $deleted++;
	    }
	    return $deleted;
	}

	/**
	 * Deletes ALL posts with post_type 'revision' and returns number deleted
	 */
	private function delete_revisions() {
		global $wpdb;

        $post_ids = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type='revision'" );

	    $deleted = 0;
	    foreach ( $post_ids as $post_id ) {
            wp_delete_post( $post_id, true );
            $deleted++;
	    }
	    return $deleted;
	}

}
