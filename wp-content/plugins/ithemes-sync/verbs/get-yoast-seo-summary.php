<?php

/*
Implementation of the get-yoast-seo-summary verb.
Written by Lew Ayotte for iThemes.com
Version 1.0.0

Version History
	1.0.0 - 2016-10-05 - Lew Ayotte
		Initial version
*/


class Ithemes_Sync_Verb_Get_Yoast_SEO_Summary extends Ithemes_Sync_Verb {
	public static $name = 'get-yoast-seo-summary';
	public static $description = 'Retrieve details from Yoast SEO.';
	
	public function run( $arguments ) {
		$statistics = array();
		$statistics_obj = new WPSEO_Statistics();
		$ranks = WPSEO_Rank::get_all_ranks();
		foreach( $ranks as $rank ) {
			$statistics[$rank->get_rank()] = $statistics_obj->get_post_count( $rank );
		}
		return $statistics;
	}
}
