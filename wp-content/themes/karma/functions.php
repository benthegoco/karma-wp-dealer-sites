<?php

function karma_script_enqueue()
{
	wp_enqueue_style(
		'karmastyle',
		get_template_directory_uri() . '/css/karma.css',
		array(),
		'1.0.0',
		'all');

	wp_enqueue_style(
		'reverostyle',
		get_template_directory_uri() . '/css/revero.css',
		array(),
		'1.0.0',
		'all');

	wp_enqueue_style(
		'service',
		get_template_directory_uri() . '/css/service.css',
		array(),
		'1.0.0',
		'all');

	wp_enqueue_style(
		'slick',
		get_template_directory_uri() . '/css/slick.css',
		array(),
		'1.0.0',
		'all');

	wp_enqueue_style(
		'slick-theme',
		get_template_directory_uri() . '/css/slick-theme.css',
		array(),
		'1.0.0',
		'all');

	wp_enqueue_style(
		'reverostyle-2',
		get_template_directory_uri() . '/css/revero-2.css',
		array(),
		'1.0.0',
		'all');

	wp_enqueue_script(
		'jquery',
		'https://code.jquery.com/jquery-3.2.1.min.js',
		array(),
		'1.0.0',
		true);
	
	wp_enqueue_script(
		'slickJS',
		get_template_directory_uri() . '/js/slick.js',
		array(),
		'1.0.0',
		true);

	wp_enqueue_script(
		'karmajs',
		get_template_directory_uri() . '/js/karma.js',
		array(),
		'1.0.0',
		true);

	wp_enqueue_script(
		'reverojs',
		get_template_directory_uri() . '/js/revero.js',
		array(),
		'1.0.0',
		true);
}

add_action('wp_enqueue_scripts', 'karma_script_enqueue');

function karma_widgets_init()
{
	register_sidebar(array(
		'name'          => 'Side panel',
		'id'            => 'side_panel',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	));

	register_sidebar(array(
		'name'          => 'Main panel',
		'id'            => 'main_panel',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	));

	register_sidebar(array(
		'name'          => 'Social network panel',
		'id'            => 'social_button_panel',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	));

}

add_action('widgets_init', 'karma_widgets_init');

function karma_theme_setup()
{
	add_theme_support('menus');
	register_nav_menu('top_menu_slot', 'Top menu');
	register_nav_menu('bottom_menu_slot', 'Bottom menu');
}

add_action('init', 'karma_theme_setup');



if (!function_exists( 'of_get_option' )) {
	function of_get_option($name, $default = false)
	{
		$optionsframework_settings = get_option('optionsframework');
		$option_name = $optionsframework_settings['id'];
		$options = get_option($option_name);

		if ($options && isset($options[$name])) {
			return $options[$name];
		}
		return $default;
	}
}



?>

