<?php

function cd_readme( $part = 'Changelog' ) {
	// Get the Car Demon Readme.txt file - return changelog
	$path = plugin_dir_path( __FILE__ );
	$path = str_replace( 'admin/', 'readme.txt', $path );
	$path = str_replace( 'admin\\', 'readme.txt', $path );

	$readme = file_get_contents( $path );

	$readme = make_clickable( nl2br( $readme ) );

	$readme = preg_replace( '/`(.*?)`/', '<code>\\1</code>', $readme );

	$readme = preg_replace( '/[\040]\*\*(.*?)\*\*/', ' <strong>\\1</strong>', $readme );
	$readme = preg_replace( '/[\040]\*(.*?)\*/', ' <em>\\1</em>', $readme );

	$readme = preg_replace( '/=== (.*?) ===/', '<h2>\\1</h2>', $readme );
	$readme = preg_replace( '/== (.*?) ==/', '<h3>\\1</h3>', $readme );
	$readme = preg_replace( '/= (.*?) =/', '<h4>\\1</h4>', $readme );

	$readme = str_replace( 'Changelog', '|', $readme );
	$readme = str_replace( 'Upgrade Notice', '|', $readme );
	$readme_arr = explode( '|', $readme );

	if ( $part == 'Changelog' ) {
		return $readme_arr[1];
	} else {
		return $readme;
	}
}

?>