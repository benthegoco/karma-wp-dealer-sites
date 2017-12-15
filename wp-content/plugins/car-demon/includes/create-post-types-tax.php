<?php
if ( ! function_exists( 'car_demon_recreate_post_type' ) && ! function_exists( 'cdcs_register_cpt_projects' ) ) {
	add_action( 'init', 'car_demon_create_post_type' );
}

if ( ! function_exists( 'cdcs_register_cpt_projects' ) ) {
	add_action( 'init', 'car_demon_tax_init' );
}

/**
 * Register cars_for_sale post type
 *
 */
function car_demon_create_post_type() {
  $slug = get_option( 'car-demon-slug' );
	if ( empty( $slug ) ) {
		$slug = 'cars-for-sale';
		update_option( 'car-demon-slug', $slug );
	}
	register_post_type( 'cars_for_sale',
		array(
			'labels' => array(
			'name' => __( 'Cars For Sale','car-demon' ),
			'singular_name' => __( 'Car New Car','car-demon' ),
			'add_new_item' => __( 'Add New Car', 'car-demon' ),
			'edit_item' =>  __( 'Edit Car', 'car-demon' ),
		  ),
		  'public' => true,
		  'rewrite' => array( 'slug' => $slug ),
		  'has_archive' => true,
		  'supports' => array( 'title', 'editor', 'thumbnail' ,'comments' ,'excerpt' ),
		  'menu_icon' => plugins_url( '../images/cd_icon.png', __FILE__ ),
		)
	);
}

/**
 * Register cars_for_sale taxonomies
 *
 */
function car_demon_tax_init() {

	$cd_show_ui_tax_items = cd_show_ui_tax_items();
	
	if ( defined( 'CAR_DEMON_ADMIN' ) ) {
		$admin_users = CAR_DEMON_ADMIN;
		$current_user = get_current_user_id();
		$show_admin_ui = false;
		if ( strpos( $admin_users, ',' ) ) {
			$admin_users_array = explode( ',', $admin_users );
			if ( in_array( $current_user, $admin_users_array ) ) {
				$show_admin_ui = true;
			}
		} else {
			if ( $admin_users == $current_user ) {
				$show_admin_ui = true;
			}
		}
	
	} else {
		$show_admin_ui = true;
	}
	
	register_taxonomy(
		'vehicle_year',
		'cars_for_sale',
		array(
			'label' => __( 'Year', 'car-demon' ),
			'labels' => array(
			'add_new_item' => __( 'Add New Year', 'car-demon' ),
			'edit_item' =>  __( 'Edit Year', 'car-demon' ),
		),
		'sort' => true,
		'args' => array( 'orderby' => 'term_order' ),
		'rewrite' => array( 'slug' => 'vehicle_year' ),
		'show_ui' => $cd_show_ui_tax_items['year'],
		)
	);

	register_taxonomy(
		'vehicle_make',
		'cars_for_sale',
		array(
			'label' => __( 'Make', 'car-demon' ),
			'labels' => array(
			'add_new_item' => __( 'Add New Make', 'car-demon' ),
			'edit_item' =>  __( 'Edit Make', 'car-demon' ),
		),
		'sort' => true,
		'args' => array( 'orderby' => 'term_order' ),
		'rewrite' => array( 'slug' => 'make' ),
		'show_ui' => $cd_show_ui_tax_items['make'],
		)
	);

	register_taxonomy(
		'vehicle_model',
		'cars_for_sale',
		array(
			'label' => __( 'Model', 'car-demon' ),
			'labels' => array(
			'add_new_item' => __( 'Add New Model', 'car-demon' ),
			'edit_item' =>  __( 'Edit Model', 'car-demon' )
		),
		'sort' => true,
		'args' => array( 'orderby' => 'term_order' ),
		'rewrite' => array( 'slug' => 'model' ),
		'show_ui' => $cd_show_ui_tax_items['model'],
		)
	);

	register_taxonomy(
		'vehicle_condition',
		'cars_for_sale',
		array(
			'label' => __( 'Condition', 'car-demon' ),
			'labels' => array(
			'add_new_item' => __( 'Add New Condition', 'car-demon' ),
			'edit_item' =>  __( 'Edit Condition', 'car-demon' ),
		),
		'sort' => true,
		'args' => array( 'orderby' => 'term_order' ),
		'rewrite' => array( 'slug' => 'condition' ),
		'show_ui' => $cd_show_ui_tax_items['condition'],
		)
	);

	register_taxonomy(
		'vehicle_body_style',
		'cars_for_sale',
		array(
			'label' => __( 'Body Style', 'car-demon' ),
			'labels' => array(
			'add_new_item' => __( 'Add New Body Style', 'car-demon' ),
			'edit_item' =>  __( 'Edit Body Style', 'car-demon' ),
		),
		'sort' => true,
		'args' => array( 'orderby' => 'term_order' ),
		'rewrite' => array( 'slug' => 'body_style' ),
		'show_ui' => $cd_show_ui_tax_items['body_style'],
		)
	);

	register_taxonomy(
		'vehicle_tag',
		'cars_for_sale',
		array(
			'label' => __( 'Vehicle Tags', 'car-demon' ),
			'labels' => array(
			'add_new_item' => __( 'Add New Vehicle Tag', 'car-demon' ),
			'edit_item' =>  __( 'Edit Vehicle Tags', 'car-demon' ),
		),
		'sort' => true,
		'args' => array( 'orderby' => 'term_order' ),
		'rewrite' => array( 'slug' => 'vehicle_tag' ),
		'show_ui' => $cd_show_ui_tax_items['vehicle_tag'],
		'hierarchical' => false,
		)
	);

	register_taxonomy(
		'vehicle_location',
		'cars_for_sale',
		array(
			'label' => __( 'Locations', 'car-demon' ),
			'labels' => array(
			'add_new_item' => __( 'Add New Location', 'car-demon' ),
			'edit_item' =>  __( 'Edit Location', 'car-demon' ),
		),
		'sort' => true,
		'args' => array( 'orderby' => 'term_order' ),
		'rewrite' => array( 'slug' => 'location' ),
		'show_ui' => $show_admin_ui,
		)
	);

	//= Add Default location to prevent it from being deleted
	$taxonomy = 'vehicle_location';
	$key = get_option( 'default_' . $taxonomy );
	$term = get_term_by( 'name', 'Default', $taxonomy );
	if ( empty( $key ) && $term ) {
		 update_option( $key, $term->term_id );
	}
}

/**
 * Function that returns an array of taxonomies to hide or show the UIs
 *
 * @return $cd_show_ui_tax_items - an array of taxonomies to hide or show the UIs
 *
*/
function cd_show_ui_tax_items() {
	$show_ui = get_option( 'cd_show_tax_ui', false );
	
	if( isset( $_GET['show_ui'] ) ) {
		if ( $_GET['show_ui'] == 1 ) {
			update_option( 'cd_show_tax_ui', true );
			$show_ui = true;
		} else if ( $_GET['show_ui'] == 0 ) {
			update_option( 'cd_show_tax_ui', false );
			$show_ui = false;
		}
	}

	$taxes = array( 'year', 'make', 'model', 'condition', 'body_style', 'location' );

	$cd_show_ui_tax_items_default = array();

	if ( $show_ui == false ) {
		$cd_show_ui_tax_items_default['year'] = false;
		$cd_show_ui_tax_items_default['make'] = false;
		$cd_show_ui_tax_items_default['model'] = false;
		$cd_show_ui_tax_items_default['condition'] = true;
		$cd_show_ui_tax_items_default['body_style'] = false;
		$cd_show_ui_tax_items_default['vehicle_tag'] = true;
		$cd_show_ui_tax_items_default['location'] = true;
	} else {
		$cd_show_ui_tax_items_default['year'] = true;
		$cd_show_ui_tax_items_default['make'] = true;
		$cd_show_ui_tax_items_default['model'] = true;
		$cd_show_ui_tax_items_default['condition'] = true;
		$cd_show_ui_tax_items_default['body_style'] = true;
		$cd_show_ui_tax_items_default['vehicle_tag'] = true;
		$cd_show_ui_tax_items_default['location'] = true;
	}
	$cd_show_ui_tax_items = get_option( 'cd_show_ui_tax_items', $cd_show_ui_tax_items_default );

	foreach ( $cd_show_ui_tax_items_default as $item ) {
		if ( ! isset( $cd_show_ui_tax_items[ $item ] ) ) {
			if ( isset( $cd_show_ui_tax_items_default[ $item ] ) ) {
				$cd_show_ui_tax_items[ $item ] = $cd_show_ui_tax_items_default[ $item ];
			}
		}
	}

	return $cd_show_ui_tax_items;
}
?>