<?php
/*
Plugin Name: iThemes Sync
Plugin URI: http://ithemes.com/sync
Description: Manage updates to your WordPress sites easily in one place.
Author: iThemes
Version: 2.0.3
Author URI: http://ithemes.com/
Domain Path: /lang/
iThemes Package: ithemes-sync
*/

if ( ! empty( $GLOBALS['ithemes_sync_path'] ) ) {
	$active_plugin_path = preg_replace( '|^' . preg_quote( ABSPATH, '|' ) . '|', '', $GLOBALS['ithemes_sync_path'] );
	$this_plugin_path = preg_replace( '|^' . preg_quote( ABSPATH, '|' ) . '|', '', dirname( __FILE__ ) );

	if ( $active_plugin_path != $this_plugin_path ) {
		$function = 'echo \'<div class="error"><p>';
		$function .= sprintf( __( 'Only one iThemes Sync plugin can be active at a time. The plugin at <code>%1$s</code> is running while the plugin at <code>%2$s</code> was skipped in order to prevent errors. Please deactivate the plugin that you do not wish to use.', 'it-l10n-ithemes-sync' ), $active_plugin_path, $this_plugin_path );
		$function .= '</p></div>\';';

		$function_ref = create_function( '', $function );

		add_action( 'all_admin_notices', $function_ref, 0 );
	}

	return;
}

$GLOBALS['ithemes_sync_path'] = dirname( __FILE__ );

require( $GLOBALS['ithemes_sync_path'] . '/load.php' );

/**
 * On activation, set a time, frequency and name of an action hook to be scheduled.
 *
 * @since 1.12.0
 */
function ithemes_sync_activation() {
    if ( ! wp_next_scheduled ( 'ithemes_sync_daily_schedule' ) ) {
	    wp_schedule_event( strtotime( 'Tomorrow 2AM' ), 'daily', 'ithemes_sync_daily_schedule' );
	}
}
register_activation_hook( __FILE__, 'ithemes_sync_activation' );

/**
 * On deactivation, remove all functions from the scheduled action hook.
 *
 * @since 1.12.0
 */
function ithemes_sync_deactivation() {
    wp_clear_scheduled_hook( 'ithemes_sync_daily_schedule' );
}
register_deactivation_hook( __FILE__, 'ithemes_sync_deactivation' );
