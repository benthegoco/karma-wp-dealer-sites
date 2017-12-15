<?php

function optionsframework_option_name()
{
    $themename = get_option('stylesheet');
    $themename = preg_replace("/\W/", "_", strtolower($themename));

    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);
}

function optionsframework_options()
{
    // Test data
    $test_array = array(
        'one' => __('One', 'options_check'),
        'two' => __('Two', 'options_check'),
        'three' => __('Three', 'options_check'),
        'four' => __('Four', 'options_check'),
        'five' => __('Five', 'options_check')
    );

    // Multicheck Array
    $multicheck_array = array(
        'one' => __('French Toast', 'options_check'),
        'two' => __('Pancake', 'options_check'),
        'three' => __('Omelette', 'options_check'),
        'four' => __('Crepe', 'options_check'),
        'five' => __('Waffle', 'options_check')
    );

    // Multicheck Defaults
    $multicheck_defaults = array(
        'one' => '1',
        'five' => '1'
    );

    // Background Defaults
    $background_defaults = array(
        'color' => '',
        'image' => '',
        'repeat' => 'repeat',
        'position' => 'top center',
        'attachment' => 'scroll');

    // Typography Defaults
    $typography_defaults = array(
        'size' => '15px',
        'face' => 'georgia',
        'style' => 'bold',
        'color' => '#bada55');

    // Typography Options
    $typography_options = array(
        'sizes' => array('6', '12', '14', '16', '20'),
        'faces' => array('Helvetica Neue' => 'Helvetica Neue', 'Arial' => 'Arial'),
        'styles' => array('normal' => 'Normal', 'bold' => 'Bold'),
        'color' => false
    );

    // Pull all the categories into an array
    $options_categories = array();
    $options_categories_obj = get_categories();
    foreach ($options_categories_obj as $category) {
        $options_categories[$category->cat_ID] = $category->cat_name;
    }

    // Pull all tags into an array
    $options_tags = array();
    $options_tags_obj = get_tags();
    foreach ($options_tags_obj as $tag) {
        $options_tags[$tag->term_id] = $tag->name;
    }

    // Pull all the pages into an array
    $options_pages = array();
    $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
    $options_pages[''] = 'Select a page:';
    foreach ($options_pages_obj as $page) {
        $options_pages[$page->ID] = $page->post_title;
    }

    // If using image radio buttons, define a directory path
    $imagepath = get_template_directory_uri() . '/images/';

    $options = array();

    $options[] = array(
        'name' => __('Header Details', 'options_check'),
        'type' => 'heading');

    {
        $options[] = array(
            'name' => __('Main Logo', 'options_check'),
            'desc' => __('Upload desktop logo', 'options_check'),
            'id' => 'mainlogo',
            'type' => 'upload');

        $options[] = array(
            'name' => __('Main Logo Mobile', 'options_check'),
            'desc' => __('Upload mobile logo', 'options_check'),
            'id' => 'mainlogo_mobile',
            'type' => 'upload');

        $options[] = array(
            'name' => __('Address', 'options_check'),
            'desc' => __('Enter header address', 'options_check'),
            'id' => 'header_address',
            'type' => 'textarea');

        $options[] = array(
            'name' => __('Phone number', 'options_check'),
            'desc' => __('Enter phone number', 'options_check'),
            'id' => 'phone_number',
            'type' => 'text');
    }

    $options[] = array(
        'name' => __('Footer Details', 'options_check'),
        'type' => 'heading');

    {
        $options[] = array(
            'name' => __('Facebook Link', 'options_check'),
            'desc' => __('Enter facebook Link', 'options_check'),
            'id' => 'fbl',
            'type' => 'text');

        $options[] = array(
            'name' => __('Twitter Link', 'options_check'),
            'desc' => __('Enter twitter Link', 'options_check'),
            'id' => 'twtl',
            'type' => 'text');

        $options[] = array(
            'name' => __('Youtube Link', 'options_check'),
            'desc' => __('Enter youtube Link', 'options_check'),
            'id' => 'youtube_link',
            'type' => 'text');

        $options[] = array(
            'name' => __('Pinterest', 'options_check'),
            'desc' => __('Enter pinterest link', 'options_check'),
            'id' => 'pinterest_link',
            'type' => 'text');
    }

    $options[] = array(
        'name' => __('General Map', 'options_check'),
        'type' => 'heading');
    {
        $options[] = array(
            'name' => __('Get Directions', 'options_check'),
            'desc' => __('Enter Get Directions URL', 'options_check'),
            'id' => 'map_direction_0',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Zoom', 'options_check'),
            'desc' => __('Enter zoom level', 'options_check'),
            'id' => 'map_zoom_0',
            'type' => 'text');
    }

    $options[] = array(
        'name' => __('Map Details 1', 'options_check'),
        'type' => 'heading');

    {
        $options[] = array(
            'name' => __('Map Address', 'options_check'),
            'desc' => __('Enter address for getting map location', 'options_check'),
            'id' => 'map_address_0',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Title', 'options_check'),
            'desc' => __('Enter map title', 'options_check'),
            'id' => 'map_title_0',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Sub Title', 'options_check'),
            'desc' => __('Enter map sub title', 'options_check'),
            'id' => 'map_sub_title_0',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Content', 'options_check'),
            'desc' => __('Enter map address', 'options_check'),
            'id' => 'map_con_0',
            'type' => 'textarea');

        $options[] = array(
            'name' => __('Map Phone', 'options_check'),
            'desc' => __('Enter map phone number', 'options_check'),
            'id' => 'map_phone_0',
            'type' => 'textarea');
    }

    $options[] = array(
        'name' => __('Map Details 2', 'options_check'),
        'type' => 'heading');

    {
        $options[] = array(
            'name' => __('Map Address', 'options_check'),
            'desc' => __('Enter address for getting map location', 'options_check'),
            'id' => 'map_address_1',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Title', 'options_check'),
            'desc' => __('Enter map title', 'options_check'),
            'id' => 'map_title_1',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Sub Title', 'options_check'),
            'desc' => __('Enter map sub title', 'options_check'),
            'id' => 'map_sub_title_1',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Content', 'options_check'),
            'desc' => __('Enter map address', 'options_check'),
            'id' => 'map_con_1',
            'type' => 'textarea');

        $options[] = array(
            'name' => __('Map Phone', 'options_check'),
            'desc' => __('Enter map phone number', 'options_check'),
            'id' => 'map_phone_1',
            'type' => 'textarea');
    }

    $options[] = array(
        'name' => __('Map Details 3', 'options_check'),
        'type' => 'heading');

    {
        $options[] = array(
            'name' => __('Map Address', 'options_check'),
            'desc' => __('Enter address for getting map location', 'options_check'),
            'id' => 'map_address_2',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Title', 'options_check'),
            'desc' => __('Enter map title', 'options_check'),
            'id' => 'map_title_2',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Sub Title', 'options_check'),
            'desc' => __('Enter map sub title', 'options_check'),
            'id' => 'map_sub_title_2',
            'type' => 'text');

        $options[] = array(
            'name' => __('Map Content', 'options_check'),
            'desc' => __('Enter map address', 'options_check'),
            'id' => 'map_con_2',
            'type' => 'textarea');

        $options[] = array(
            'name' => __('Map Phone', 'options_check'),
            'desc' => __('Enter map phone number', 'options_check'),
            'id' => 'map_phone_2',
            'type' => 'textarea');
    }

    $options[] = array(
        'name' => __('Google Analytics', 'options_check'),
        'type' => 'heading');

    {
        $options[] = array(
            'name' => __('Tracking ID', 'options_check'),
            'desc' => __('Copy and paste Tracking ID (format: UA-XXXXXXXXX-X) for each property here. Do NOT paste the entire code with script tags.', 'options_check'),
            'id' => 'googletrackingcode',
            'type' => 'text');
    }


    return $options;
}






