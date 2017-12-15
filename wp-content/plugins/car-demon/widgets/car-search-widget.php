<?php
function car_demon_search_car_load_widgets() {
	register_widget( 'car_demon_search_car_Widget' );
}
add_action( 'widgets_init', 'car_demon_search_car_load_widgets' );

class car_demon_search_car_Widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'car_demon_search_car', 'description' => __( 'Display Search Cars.', 'car-demon' ) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'car_demon_search_car-widget' );
		/* Create the widget. */
		parent::__construct( 'car_demon_search_car-widget', __( 'Car Demon search Cars', 'car-demon' ), $widget_ops, $control_ops );
	}
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( isset( $instance['form_type'] ) ) {
			$form_type = $instance['form_type'];
		} else {
			$form_type = '';
		}
		/* Before widget (defined by themes). */
		echo $before_widget;
		/* Display the widget title if one was input (before and after defined by themes). */
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		if ( $form_type == 'Simple Narrow' ) {
			echo car_demon_simple_search( 's', $instance );
		} elseif ( $form_type == 'Simple Wide' ) {
			echo car_demon_simple_search( 'l', $instance );
		} else {
			echo car_demon_search_form( $instance );
		}
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
		$instance['form_type'] = strip_tags( $new_instance['form_type'] );
		$instance['result_page'] = strip_tags( $new_instance['result_page'] );
		return $instance;
	}
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => __( 'Search Inventory', 'car-demon' ),
			'form_type' => __( 'Full', 'car-demon' ),
			'result_page' => 0
			 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'car-demon'); ?></label>
            <br />
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="car_demon_wide" />
			<br />
            <label for="<?php echo $this->get_field_id( 'form_type' ); ?>"><?php _e('Form Type:', 'car-demon'); ?></label>
			<br />
            <select name="<?php echo $this->get_field_name( 'form_type' ); ?>" id="<?php echo $this->get_field_id( 'form_type' ); ?>">
				<option value="<?php echo $instance['form_type']; ?>"><?php echo $instance['form_type']; ?></option>
				<option value="Simple Narrow"><?php _e( 'Simple Narrow', 'car-demon' ); ?></option>
				<option value="Simple Wide"><?php _e( 'Simple Wide', 'car-demon' ); ?></option>
				<option value="Full"><?php _e( 'Full', 'car-demon' ); ?></option>
			</select>
			<?php
				if ( function_exists( 'cd_shortcode_init' ) ) {
					$x = '<br />';
					$x .= '<label for="' . $this->get_field_id( 'result_page' ) . '">' . __( 'Search result page:', 'car-demon' ) . '</label>';
					$x .= '<br />';
					$args = array(
						'depth'                 => 0,
						'child_of'              => 0,
						'selected'              => $instance['result_page'],
						'echo'                  => 0,
						'name'                  => $this->get_field_name( 'result_page' ),
						'id'                    => $this->get_field_id( 'result_page' ), // string
						'class'                 => $this->get_field_name( 'result_page' ), // string
						'show_option_none'      => 'Default', // string
						'show_option_no_change' => null, // string
						'option_none_value'     => null, // string
					);
					$x .= wp_dropdown_pages( $args );
					$x .= '<p>' . __( 'Point the search result page to the page with your inventory shortcode [cd_inventory].', 'car-demon' ) . '</p>';
					echo $x;
				}
			?>
		</p>
	<?php
	}
}
?>