<?php
/*
Plugin Name: Car Demon Keyword Search
Plugin URI: http://www.cardemons.com
Description: Add an autofill keyword search widget for your vehicles. Searches year, make, model, description field, interior color and stock number.
Author: CarDemons
Version: 0.0.2
Author URI: http://www.cardemonspro.com
WPCD ID: 11
*/

function car_demon_keyword_search_styles() {
	wp_enqueue_style('car-demon-keyword-search-css', WP_CONTENT_URL . '/plugins/car-demon-keyword-search/css/car-demon-keyword-search.css');
}
add_action( 'wp_enqueue_scripts', 'car_demon_keyword_search_styles' );

function car_demon_search_keyword_load_widgets() {
	register_widget( 'car_demon_keyword_search_Widget' );
}
add_action( 'widgets_init', 'car_demon_search_keyword_load_widgets' );

class car_demon_keyword_search_Widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	function car_demon_keyword_search_Widget() {
		$widget_ops = array( 'classname' => 'car_demon_keyword_search', 'description' => __('Search inventory by keyword.', 'car-demon') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'car_demon_keyword_search-widget' );

		/* Create the widget. */
		parent::__construct( 'car_demon_keyword_search-widget', __('Car Demon Keyword Search', 'car-demon-keyword-search'), $widget_ops, $control_ops );
	}
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$button = $instance['button'];
		$message = $instance['message'];
		$default_value = $instance['default_value'];
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}
		echo keyword_search_box($button, $message, $default_value);
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['button'] = strip_tags( $new_instance['button'] );
		$instance['message'] = strip_tags( $new_instance['message'] );
		$instance['default_value'] = strip_tags( $new_instance['default_value'] );
		return $instance;
	}
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => __('Search Inventory', 'car-demon-keyword-search'),
			'button' => __('Search', 'car-demon-keyword-search'),
			'message' => __('Keyword Search', 'car-demon-keyword-search'),
			'default_value' => __('Example: Blue, Altima, Nissan', 'car-demon-keyword-search')
			 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'car-demon-keyword-search'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="car_demon_wide" />
		</p>
		<!--p>
			<label for="<?php echo $this->get_field_id( 'button' ); ?>"><?php _e('Button:', 'car-demon-keyword-search'); ?></label>
			<input id="<?php echo $this->get_field_id( 'button' ); ?>" name="<?php echo $this->get_field_name( 'button' ); ?>" value="<?php echo $instance['button']; ?>" class="car_demon_wide" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'message' ); ?>"><?php _e('Message:', 'car-demon-keyword-search'); ?></label>
			<input id="<?php echo $this->get_field_id( 'message' ); ?>" name="<?php echo $this->get_field_name( 'message' ); ?>" value="<?php echo $instance['message']; ?>" class="car_demon_wide" />
		</p-->
		<p>
			<label for="<?php echo $this->get_field_id( 'default_value' ); ?>"><?php _e('Default Value:', 'car-demon-keyword-search'); ?></label>
			<input id="<?php echo $this->get_field_id( 'default_value' ); ?>" name="<?php echo $this->get_field_name( 'default_value' ); ?>" value="<?php echo $instance['default_value']; ?>" class="car_demon_wide" />
		</p>
	<?php
	}
}

function keyword_search_box($button, $message, $default_value) {
	wp_register_script('car-demon-keyword-search-js', WP_CONTENT_URL . '/plugins/car-demon-keyword-search/js/car-demon-keyword-search.js', array('jquery'));
	wp_localize_script( 'car-demon-keyword-search-js', 'cdKeywordSearchParams', array(
		'default_value' => $default_value
	));
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('car-demon-keyword-search-js');

	$url = get_option('siteurl');
	$box = '<div class="keyword_search_form"><form action="'.$url.'" method="get" class="vehicle_search_keyword_box" id="vehicle_search_box" name="vehicle_search_keyword_box" />';
		$box .= '<input type="hidden" name="s" value="cars" />';
		$box .= '<input type="hidden" name="car" value="1" />';
		$box .= '<span id="criteria_message">'.$message.'</span>';
		$box .= '<input type="text" name="criteria" id="keyword_search_criteria" class="keyword_search_criteria" value="'.$default_value.'" />';
		if (!empty($button)) {
			$box .= '<input type="submit" name="submit_search" id="submit_search" value="'.$button.'" class="search_btn advanced_btn criteria_btn">';
		} else {
			$box .= '<button type="submit" class="keyword_search_button"><img src="'.WP_CONTENT_URL .'/plugins/car-demon-keyword-search/css/images/search.png" alt="Search" class="keyword_search_glass" /></button>	';
		}
	$box .= '</form></div>';
	return $box;
}
?>