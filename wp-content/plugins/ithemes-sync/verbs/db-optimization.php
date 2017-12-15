<?php

/*
Implementation of the db-optimization verb.
Written by Glenn Ansley for iThemes.com
Version 1.0.0

Version History
	1.0.0 - 2016-11-17 - Glenn Ansley
*/


class Ithemes_Sync_Verb_DB_Optimization extends Ithemes_Sync_Verb {
	public static $name                      = 'db-optimization';
	public static $description               = 'Optimizes the database.';
	public static $status_element_name       = 'optimize';
	public static $show_in_status_by_default = false;

	private $date_format      = 'Y-m-d';
	private $optimize_options = array();
	private $default_arguments = array(
		'args' => array(
			'action'   => 'get-available',
			'selected' => array(),
		),
	);
	private $registered_optimizations = array(
		'delete-trackback-urls'         => 'delete_trackback_urls',
		'delete-all-revisions'          => 'delete_all_revisions',
		'delete-all-trackbacks'         => 'delete_all_trackbacks',
		'delete-all-pingbacks'          => 'delete_all_pingbacks',
		'delete-all-autodrafts'         => 'delete_all_autodrafts',
		'delete-all-trashed-items'      => 'delete_all_trashed_items',
		'delete-unapproved-comments'    => 'delete_unapproved_comments',
		'delete-trashed-comments'       => 'delete_trashed_comments',
		'delete-spam-comments'          => 'delete_spam_comments',
		'delete-orphaned-commentmeta'   => 'delete_orphaned_commentmeta',
		'delete-akismet-metadata'       => 'delete_akismet_metadata',
		'delete-expired-transients'     => 'delete_expired_transients',
	);

	public function run( $arguments ) {
		$this->arguments        = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		$this->date_format      = get_option( 'date_format' );
		$this->optimize_options = get_option( 'ithemes_sync_optimization' );

		$actions = array(
			'get-available' => 'get_available',
			'run'           => 'run_optimization',
		);
		
		if ( ! is_callable( array( $GLOBALS['ithemes-sync-api'], 'run' ) ) ) {
			return new WP_Error( 'missing-method-api-run', 'The Ithemes_Sync_API::run function is not callable. Unable to generate status details.' );
		}

		if ( empty( $arguments['args']['action'] ) || empty( $actions[$arguments['args']['action']] ) || ! is_callable( array( $this, $actions[$arguments['args']['action']] ) ) ) {
			return new WP_Error( "missing-function-" . $actions[$arguments['args']['action']], "Due to an unknown issue, the " . $actions[$arguments['args']['action']] . " function is not available." );
		} else {
			return call_user_func( array( $this, $actions[$arguments['args']['action']] ) );
		}
        return array();
	}

	private function get_available() {
		$optimizations = array();
		foreach ( $this->registered_optimizations as $key => $function ) {
			if ( is_callable( array( $this, $function ) ) && false !== ( $result = $this->$function( 'is_available' ) ) ) {
				$optimizations[$key] = $result;
			}
		}
		return array('success' => 1, 'optimizations' => $optimizations);
	}

	private function run_optimization() {
		$optimized = array();
		if ( empty( $this->arguments['args']['selected'] ) ) {
			return array('success' => 0);
		}
		foreach( (array) $this->arguments['args']['selected'] as $selected ) {
			$function = empty( $this->registered_optimizations[$selected] ) ? false : $this->registered_optimizations[$selected];
			if ( ! empty( $function ) && is_callable( array( $this, $function ) ) && false !== ( $result = $this->$function( 'run' ) ) ) {
				$optimized[$key] = $result;
			}
		}
		update_option( 'ithemes_sync_optimization', $this->optimize_options );
		return $this->get_available();
	}

	private function delete_trackback_urls( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE pinged !='' OR to_ping != ''" );

				return array(
					'title'       => __( 'Delete Trackback URLs' ),
					'description' => esc_attr__( 'Deletes URLs added to the "pinged" and "to_ping" columns in your posts table.', 'it-l10n-ithemes-sync' ),
					'action'      => 'delete-trackback-urls',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-trackback-urls' ),
				);
				break;
			case 'run' :
				$this->update_last_run_var( 'delete-trackback-urls' );
				if ( $result = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE pinged !='' OR to_ping != ''" ) ) {
					if ( $wpdb->query( "UPDATE $wpdb->posts SET pinged = '', to_ping = '' WHERE ID IN (" . implode( $result, ',' ) . ")" ) ) {
						return true;
					}
				}
				break;
		}
		return false;
	}

	private function delete_all_revisions( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'revision';" );
				return array(
					'title'       => 'Delete All Revisions',
					'action'      => 'delete-all-revisions',
					'description' => 'Deletes all revisions from posts, pages, and all other post-types.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-all-revisions' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-all-revisions' );
				if ( $result = $wpdb->query( "DELETE FROM $wpdb->posts WHERE post_type = 'revision'" ) ) {
					return true;
				}
				break;
		}
		return false;
	}

	private function delete_all_trackbacks( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_type = 'trackback';" );
				return array(
					'title'       => 'Delete All Trackbacks',
					'action'      => 'delete-all-trackbacks',
					'description' => 'Deletes all trackbacks from other sites stored in your comments table.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-all-trackbacks' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-all-trackbacks' );
				if ( $result = $wpdb->query( "DELETE FROM $wpdb->comments WHERE comment_type = 'trackback'" ) ) {
					return true;
				}
				break;
		}
		return false;
	}

	private function delete_all_pingbacks( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_type = 'pingback';" );
				return array(
					'title'       => 'Delete All Pingbacks',
					'action'      => 'delete-all-pingbacks',
					'description' => 'Deletes all pingbacks from other sites stored in your comments table.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-all-pingbacks' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-all-pingbacks' );
				if ( $result = $wpdb->query( "DELETE FROM $wpdb->comments WHERE comment_type = 'pingback'" ) ) {
					return true;
				}
				break;
		}
		return false;

	}

	private function delete_all_autodrafts( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = 'auto-draft';" );
				return array(
					'title'       => 'Delete All Auto-Drafts',
					'action'      => 'delete-all-autodrafts',
					'description' => 'Deletes all auto-drats from posts, pages, and all other post-types.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-all-autodrafts' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-all-autodrafts' );
				if ( $result = $wpdb->query( "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'" ) ) {
					return true;
				}
				break;
		}
		return false;

	}

	private function delete_all_trashed_items( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = 'trash';" );
				return array(
					'title'       => 'Delete All Trashed Items',
					'action'      => 'delete-all-trashed-items',
					'description' => 'Deletes all posts, pages, menus, and all other post-types that have been trashed but not yet deleted.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-all-trashed-items' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-all-trashed-items' );
				if ( $result = $wpdb->query( "DELETE FROM $wpdb->posts WHERE post_status = 'trash'" ) ) {
					return true;
				}
				break;
		}
		return false;

	}

	private function delete_unapproved_comments( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0';" );
				return array(
					'title'       => 'Delete Unapproved Comments',
					'action'      => 'delete-unapproved-comments',
					'description' => 'Deletes all comments waiting to be approved.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-unapproved-comments' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-unapproved-comments' );
				if ( $result = $wpdb->query( "DELETE FROM $wpdb->comments WHERE comment_approved = '0'" ) ) {
					return true;
				}
				break;
		}
		return false;

	}

	private function delete_trashed_comments( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = 'trash';" );
				return array(
					'title'       => 'Delete Trashed Comments',
					'action'      => 'delete-trashed-comments',
					'description' => 'Deletes all trashed comments that have yet to be deleted.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-trashed-comments' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-trashed-comments' );
				if ( $result = $wpdb->query( "DELETE FROM $wpdb->comments WHERE comment_approved = 'trash'" ) ) {
					return true;
				}
				break;
		}
		return false;
	}

	private function delete_spam_comments( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(comment_id) FROM $wpdb->comments WHERE comment_approved = 'spam';" );
				return array(
					'title'       => 'Delete Spam Comments',
					'action'      => 'delete-spam-comments',
					'description' => 'Deletes all comments that have been marked as spam.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-spam-comments' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-spam-comments' );
				if ( $result = $wpdb->query( "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'" ) ) {
					return true;
				}
				break;
		}
		return false;
	}

	private function delete_orphaned_commentmeta( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(comment_id) FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_ID FROM $wpdb->comments);" );
				return array(
					'title'       => 'Delete Orphaned Commentmeta',
					'action'      => 'delete-orphaned-commentmeta',
					'description' => 'Deletes all commentmeta that references a deleted comment.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-orphaned-commentmeta' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-orphaned-commentmeta' );
				if ( $result = $wpdb->get_col( "SELECT meta_id FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_ID FROM $wpdb->comments);" ) ) {
					if ( $wpdb->query( "DELETE FROM $wpdb->commentmeta WHERE meta_id IN (" . implode( $result, ',' ) . ")" ) ) {
						return true;
					}
				}
				break;
		}
		return false;
	}

	private function delete_akismet_metadata( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->commentmeta WHERE meta_key LIKE 'akismet%';" );
				return array(
					'title'       => 'Delete Akismet Metadata',
					'action'      => 'delete-akismet-metadata',
					'description' => 'Deletes all metadata attached to comments by the Akismet plugin.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-akismet-metadata' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-akismet-metadata' );
				if ( $result = $wpdb->query( "DELETE FROM $wpdb->commentmeta WHERE meta_key LIKE 'akismet%'" ) ) {
					return true;
				}
				break;
		}
		return false;
	}

	private function delete_expired_transients( $action ) {
		global $wpdb;
		switch( $action ) {
			case 'is_available' :
				$applies = $wpdb->get_var( "SELECT COUNT(option_id) FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_%' AND option_value < " . ( current_time( 'timestamp' ) - WEEK_IN_SECONDS ). ";" );
				return array(
					'title'       => 'Delete Stale Transient Options',
					'action'      => 'delete-expired-transients',
					'description' => 'Deletes all transient options that expired more than a week ago.',
					'applies_to'  => empty( $applies ) ? '0 items' : $applies . ' items',
					'has_items'   => ! empty( $applies ),
					'last_run'    => $this->get_last_run( 'delete-expired-transients' ),
				);
			case 'run' :
				$this->update_last_run_var( 'delete-expired-transients' );
				if ( $result = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_%' AND option_value < " . ( current_time( 'timestamp' ) - WEEK_IN_SECONDS ) . ";" ) ) {
					foreach ( (array) $result as $name ) {
						delete_transient( substr( $name, 19 ) );
					}
					if ( 0 === $wpdb->get_var( "SELECT COUNT(option_id) FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_%' AND option_value < " . current_time( 'timestamp' ) . ";" ) ) {
						return true;
					}
				}
				break;
		}
		return false;
	}


	private function get_last_run( $optimization_slug ) {
		return empty( $this->optimize_options[$optimization_slug]['last_run'] ) ? __( 'Never', 'it-l10n-ithemes-sync' ) : $this->optimize_options[$optimization_slug]['last_run'];
	}

	private function update_last_run_var( $optimization_slug ) {
		$this->optimize_options[$optimization_slug]['last_run'] = date( $this->date_format );
	}
}
