<?php
/* Display a notice that can be dismissed */
add_action( 'admin_notices', 'cd_admin_notice' );
add_action( 'network_admin_notices', 'cd_admin_notice' );
function cd_admin_notice() {
	if (isset($_SERVER['SERVER_ADDR'])) {
		$server_hash = base64_encode($_SERVER['SERVER_ADDR']);
	} else {
		$server_hash = 'unk';	
	}
	$home_hash = 'MTAuMTc2LjE2MS4zO2Q==';

	if ($server_hash == $home_hash) {
		//= If server is authenticated as home then do not display notice
		return;	
	}
	if ( !class_exists( 'CARDEMONS_Update_Notifications' ) && current_user_can( 'install_plugins' ) ) {
		global $current_user ;
			$user_id = $current_user->ID;
			/* Check that the user hasn't already clicked to ignore the message */
		if ( ! get_user_meta( $user_id, 'cd_ignore_notice' ) ) {
			//= Don't show on settings page
			if ( isset($_GET['page'] ) ) {
				if ( $_GET['page'] == 'car_demon_settings_options' ) {
					return;
				}
			}
			echo '<div class="updated"><p>';
			printf( __( 'Expand Car Demon with add-ons and themes at <a href="http://cardemons.com/" target="cduwin" title="Download Now &raquo;">CarDemons.com</a>. Stay up-to-date with design tips, development information and new releases.<br /><a href="http://cardemons.com/" target="cduwin">CarDemons.com</a>| <a href="%1$s">Hide Notice</a>', 'car-demon' ), '?cd_nag_ignore=0' );
			echo "</p></div>";
			
			//= Have any locations been created?
			$args = array(
				'style'              => 'none',
				'show_count'         => 0,
				'use_desc_for_title' => 0,
				'hierarchical'       => true,
				'echo'               => 0,
				'hide_empty'		 => 0,
				'taxonomy'           => 'vehicle_location',
				);
			$locations = get_categories( $args );

			if ( count($locations) < 1 ) {
				echo '<div class="error"><p>';
					echo __( '<b>YOU HAVE NOT SETUP YOUR LOCATION(S) YET!</b><br /><a href="edit-tags.php?taxonomy=vehicle_location&post_type=cars_for_sale">Click here</a> to setup your locations.', 'car-demon' );
				echo "</p></div>";
				// Create a default location
				$location = car_demon_default_location();
				car_demon_import_location( $location );
			}
			
		}
	}
}

add_action( 'admin_init', 'cd_nag_ignore' );
function cd_nag_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset( $_GET['cd_nag_ignore'] ) && '0' == $_GET['cd_nag_ignore'] ) {
		 add_user_meta( $user_id, 'cd_ignore_notice', 'true', true );
	}
}
?>