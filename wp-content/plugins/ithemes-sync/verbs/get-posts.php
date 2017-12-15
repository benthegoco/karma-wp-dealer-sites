<?php

/*
Implementation of the get-posts verb.
Written by Aaron D. Campbell for iThemes.com
Version 1.0.0

Version History
	1.0.0 - 2015-02-17 - Aaron D. Campbell
		Initial version
*/


class Ithemes_Sync_Verb_Get_Posts extends Ithemes_Sync_Verb {
	public static $name = 'get-posts';
	public static $description = 'Retrieve posts from WordPress.';
	public static $status_element_name = 'posts';
	public static $show_in_status_by_default = false;

	private $default_arguments = array(
		'args' => array(
			'post_type'   => 'post',
			'numberposts' => 10,
		),
		'include-comment-details'  => true,
		'include-post-counts' => true,
	);

	public function run( $arguments ) {
		$arguments = Ithemes_Sync_Functions::merge_defaults( $arguments, $this->default_arguments );
		$results = array();
		$posts = get_posts( $arguments['args'] );

		// Add some additional data
		foreach ( $posts as &$post ) {
			$post->permalink = get_permalink( $post->ID );
			$post->author_display_name = get_the_author_meta( 'display_name', $post->post_author );
						
			if ( !empty( $arguments['include-comment-details'] ) ) {
				$post->comment_counts = $this->add_comment_details( $post->ID );
			}
			
			// Yoast SEO Verbs
			if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
				$post->yoast_seo = $this->add_yoast_seo_details( $post->ID );
			}

		}
	
		if ( !empty( $arguments['include-post-counts'] ) ) {
			$results['posts'] = $posts;
			$results['post_counts'] = wp_count_posts( $arguments['args']['post_type'] );
		} else {
			$results = $posts;
		}
		
		return $results;
	}
	
	private function add_comment_details( $post_id ) {
		return wp_count_comments( $post_id );
	}
	
	private function add_yoast_seo_details( $post_id ) {
		if ( '1' === WPSEO_Meta::get_value( 'meta-robots-noindex', $post_id ) ) {
			$score = new WPSEO_Rank( WPSEO_Rank::NO_INDEX );
		} elseif ( '' === WPSEO_Meta::get_value( 'focuskw', $post_id ) ) {
			$score = new WPSEO_Rank( WPSEO_Rank::NO_FOCUS );
		} else {
			$score = (int) WPSEO_Meta::get_value( 'linkdex', $post_id );
			$score = WPSEO_Rank::from_numeric_score( $score );
		}

		$score = $score->get_label();
		$content_score = (int) WPSEO_Meta::get_value( 'content_score', $post_id );
		$readability = WPSEO_Rank::from_numeric_score( $content_score );
		$readability = $readability->get_label();
		
        $keyword = WPSEO_Meta::get_value( 'focuskw', $post_id );

		return array( 'score' => $score, 'readability' => $readability, 'keyword' => $keyword );

	}
}
