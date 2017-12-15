<?php
	
/**
 * Generates and returns the Duplicate Product URL
 *
 * @since 0.0.0
 *
 * @params object $post Current post object
 * @return string
*/
function ithemes_sync_duplicate_post_addon_get_duplicating_url( $post ) {
	$args = array(
		'post_type'                      => $post->post_type,
		'ithemes-sync-duplicate-post-id' => $post->ID,
	);

	return add_query_arg( $args, admin_url( 'post-new.php' ) );
}

/**
 * Add Duplicate action from Any Post Types
 *
 * @since 0.0.0
 *
 * @params array $actions Current post row actions
 * @params object $post Current post object
 * @return array
*/
function ithemes_sync_duplicate_post_addon_add_duplicate_post_function( $actions, $post ) {
	$url = ithemes_sync_duplicate_post_addon_get_duplicating_url( $post );
	$actions['ithemes_sync_duplicate'] =  '<a class="sync_duplicate_post" id="sync-post-' . $post->ID . '" title="' . __( 'Duplicate Post', 'it-l10n-ithemes-sync' ) . '" href="' . $url . '">Duplicate</a>';

	return $actions;
}
add_filter( 'post_row_actions', 'ithemes_sync_duplicate_post_addon_add_duplicate_post_function', 10, 2 );
add_filter( 'page_row_actions', 'ithemes_sync_duplicate_post_addon_add_duplicate_post_function', 10, 2 );

/**
 * Copies previous post content to new post
 *
 * @since 0.0.0
 *
 * @params string $post_content Current WordPress default post content
 * @params object $post Current post object
 * @return array
*/
function ithemes_sync_duplicate_post_addon_default_post_content( $post_content, $post ) {
	if ( !empty( $_REQUEST['ithemes-sync-duplicate-post-id'] )
		&& $duplicate_post_post_id = $_REQUEST['ithemes-sync-duplicate-post-id'] ) {
		$duplicate_post_post = get_post( $duplicate_post_post_id );
		$post_content = $duplicate_post_post->post_content;
	}

	return $post_content;
}
add_filter( 'default_content', 'ithemes_sync_duplicate_post_addon_default_post_content', 10, 2 );

/**
 * Copies previous post title to new post
 *
 * @since 1.1.2
 *
 * @params string $post_content Current WordPress default post title
 * @params object $post Current post object
 * @return array
*/
function ithemes_sync_duplicate_post_addon_default_post_title( $post_title, $post ) {
	if ( !empty( $_REQUEST['ithemes-sync-duplicate-post-id'] )
		&& $duplicate_post_post_id = $_REQUEST['ithemes-sync-duplicate-post-id'] ) {
		$duplicate_post_post = get_post( $duplicate_post_post_id );
		$post_title = $duplicate_post_post->post_title . ' - ' . __( 'copy', 'it-l10n-ithemes-exchange' );
	}

	return $post_title;
}
add_filter( 'default_title', 'ithemes_sync_duplicate_post_addon_default_post_title', 10, 2 );

/**
 * Copies previous post content to new excerpt (not really used in Exchange)
 *
 * @since 1.1.2
 *
 * @params string $post_content Current WordPress default post excerpt
 * @params object $post Current post object
 * @return array
*/
function ithemes_sync_duplicate_post_addon_default_post_excerpt( $post_excerpt, $post ) {
	if ( !empty( $_REQUEST['ithemes-sync-duplicate-post-id'] )
		&& $duplicate_post_post_id = $_REQUEST['ithemes-sync-duplicate-post-id'] ) {
		$duplicate_post_post = get_post( $duplicate_post_post_id );
		$post_excerpt = $duplicate_post_post->post_excerpt;
	}

	return $post_excerpt;
}
add_filter( 'default_excerpt', 'ithemes_sync_duplicate_post_addon_default_post_excerpt', 10, 2 );

/**
 * Copies previous post meta to new post
 *
 * @since 1.1.2
 *
 * @params string $post_type Current WordPress default post type (ignored)
 * @params object $post Current post object
 * @return array
*/
function ithemes_sync_duplicate_post_addon_default_post_meta( $post_type, $post ) {
	if ( !empty( $_REQUEST['ithemes-sync-duplicate-post-id'] )
		&& $duplicate_post_post_id = $_REQUEST['ithemes-sync-duplicate-post-id'] ) {
		$duplicate_post_post_meta = get_post_meta( $duplicate_post_post_id );
		foreach ( $duplicate_post_post_meta as $key => $values ) {
			foreach ( $values as $value ) {
				//We do not want to copy ALL of the post meta, some of it is specific to transaction history, etc.
				if ( in_array( $key, apply_filters( 'ithemes_sync_duplicate_post_addon_default_post_meta_invalid_keys', array( '_edit_lock', '_edit_last', '_ithemes_sync_transaction_id' ) ) ) ) {
					continue;
				}
				$value = maybe_unserialize( $value );
				add_post_meta( $post->ID, $key, $value );

				//Other add-ons might need to perform some extra actions with this new post meta (e.g. Membership)
				do_action( 'ithemes_sync_duplicate_post_addon_add_post_meta', $post, $key, $value );
			}
		}
		do_action( 'ithemes_sync_duplicate_post_addon_default_post_meta', $post, $duplicate_post_post_id );
	}
}
add_action( 'add_meta_boxes', 'ithemes_sync_duplicate_post_addon_default_post_meta', 10, 2 );