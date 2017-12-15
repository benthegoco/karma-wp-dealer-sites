<?php
add_action( 'widgets_init', 'cdp_cdsf_widgets' );
function cdp_cdsf_widgets() {
	register_widget( 'cdp_cdsf_Widget' );
}
class cdp_cdsf_Widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'car_demon_cds', 'description' => __('Advanced Search widget for the Car Demon PlugIn.', 'car-demon-search') );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'car_demon_cds-widget' );
		/* Create the widget. */
		parent::__construct( 'car_demon_cds-widget', __('Car Demon Pro Search', 'car-demon-search'), $widget_ops, $control_ops );
	}
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget (defined by themes). */

		echo $before_widget;
		/* Display the widget title if one was input (before and after defined by themes). */
		if (!empty($title)) {
			$title = $before_title . $title . $after_title;
		}
		echo $title;
		//= Insert Search Form Here ==========================

		// setup the setting to send to cdsf_advanced_search_form()
		$settings = array();
		$settings['hide'] = array();

		if ($instance['hide_location'] == 'on') {
			array_push($settings['hide'], 'location');
		}
		if ($instance['hide_condition'] == 'on') {
			array_push($settings['hide'], 'condition');
		}
		if ($instance['hide_year'] == 'on') {
			array_push($settings['hide'], 'year');
		}
		if ($instance['hide_year_range'] == 'on') {
			array_push($settings['hide'], 'year_range');
		}
		if ($instance['hide_make'] == 'on') {
			array_push($settings['hide'], 'make');
		}
		if ($instance['hide_model'] == 'on') {
			array_push($settings['hide'], 'model');
		}
		if ($instance['hide_price'] == 'on') {
			array_push($settings['hide'], 'price');
		}
		if ($instance['hide_mileage'] == 'on') {
			array_push($settings['hide'], 'mileage');
		}
		if ($instance['hide_body_style'] == 'on') {
			array_push($settings['hide'], 'body_style');
		}
		if ($instance['hide_trim_level'] == 'on') {
			array_push($settings['hide'], 'trim_level');
		}
		if ($instance['hide_transmission'] == 'on') {
			array_push($settings['hide'], 'transmission');
		}

		$settings['value'] = array();
	
		$settings['value']['location'] = $instance['value_location'];
		$settings['value']['condition'] = $instance['value_condition'];
		$settings['value']['make'] = $instance['value_make'];
		$settings['value']['model'] = $instance['value_model'];
		$settings['value']['year'] = $instance['value_year'];
		$settings['value']['price'] = $instance['value_price'];
		$settings['value']['mileage'] = $instance['value_mileage'];
		$settings['value']['body_style'] = $instance['value_body_style'];
		$settings['value']['trim_level'] = $instance['value_trim_level'];
		$settings['value']['transmission'] = $instance['value_transmission'];

		$settings['custom_class'] = $instance['custom_class'];
		$settings['form_action'] = $instance['form_action'];

		$settings['label']['location'] = $instance['label_location'];
		$settings['label']['condition'] = $instance['label_condition'];
		$settings['label']['make'] = $instance['label_make'];
		$settings['label']['model'] = $instance['label_model'];
		$settings['label']['year'] = $instance['label_year'];
		$settings['label']['price'] = $instance['label_price'];
		$settings['label']['mileage'] = $instance['label_mileage'];
		$settings['label']['button'] = $instance['label_button'];
		$settings['label']['reset'] = $instance['label_reset'];
		$settings['label']['body_style'] = $instance['label_body_style'];
		$settings['label']['trim_level'] = $instance['label_trim_level'];
		$settings['label']['transmission'] = $instance['label_transmission'];
		
		$settings['style'] = $instance['style'];

		echo cdsf_advanced_search_form($settings);

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
		$instance['custom_class'] = strip_tags( $new_instance['custom_class'] );
		if (empty($new_instance['form_action'])) {
			$new_instance['form_action'] = get_option('siteurl');
		}
		$instance['form_action'] = strip_tags( $new_instance['form_action'] );
		$instance['hide_location'] = strip_tags( $new_instance['hide_location'] );
		$instance['hide_condition'] = strip_tags( $new_instance['hide_condition'] );
		$instance['hide_year'] = strip_tags( $new_instance['hide_year'] );
		$instance['hide_year_range'] = strip_tags( $new_instance['hide_year_range'] );
		$instance['hide_make'] = strip_tags( $new_instance['hide_make'] );
		$instance['hide_model'] = strip_tags( $new_instance['hide_model'] );
		$instance['hide_price'] = strip_tags( $new_instance['hide_price'] );
		$instance['hide_mileage'] = strip_tags( $new_instance['hide_mileage'] );
		$instance['hide_body_style'] = strip_tags( $new_instance['hide_body_style'] );
		$instance['hide_trim_level'] = strip_tags( $new_instance['hide_trim_level'] );
		$instance['hide_transmission'] = strip_tags( $new_instance['hide_transmission'] );

		$instance['value_location'] = strip_tags( $new_instance['value_location'] );
		$instance['value_condition'] = strip_tags( $new_instance['value_condition'] );
		$instance['value_make'] = strip_tags( $new_instance['value_make'] );
		$instance['value_model'] = strip_tags( $new_instance['value_model'] );
		$instance['value_year'] = strip_tags( $new_instance['value_year'] );
		$instance['value_price'] = strip_tags( $new_instance['value_price'] );
		$instance['value_mileage'] = strip_tags( $new_instance['value_mileage'] );
		$instance['value_body_style'] = strip_tags( $new_instance['value_body_style'] );
		$instance['value_trim_level'] = strip_tags( $new_instance['value_trim_level'] );
		$instance['value_transmission'] = strip_tags( $new_instance['value_transmission'] );
		$instance['value_button'] = strip_tags( $new_instance['value_button'] );
		$instance['value_reset'] = strip_tags( $new_instance['value_reset'] );
		
		$instance['label_location'] = strip_tags( $new_instance['label_location'] );
		$instance['label_condition'] = strip_tags( $new_instance['label_condition'] );
		$instance['label_make'] = strip_tags( $new_instance['label_make'] );
		$instance['label_model'] = strip_tags( $new_instance['label_model'] );
		$instance['label_year'] = strip_tags( $new_instance['label_year'] );
		$instance['label_price'] = strip_tags( $new_instance['label_price'] );
		$instance['label_mileage'] = strip_tags( $new_instance['label_mileage'] );
		$instance['label_body_style'] = strip_tags( $new_instance['label_body_style'] );
		$instance['label_trim_level'] = strip_tags( $new_instance['label_trim_level'] );
		$instance['label_transmission'] = strip_tags( $new_instance['label_transmission'] );
		$instance['label_button'] = strip_tags( $new_instance['label_button'] );
		$instance['label_reset'] = strip_tags( $new_instance['label_reset'] );
		
		$instance['style'] = strip_tags( $new_instance['style'] );

		return $instance;
	}
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => __('Search', 'car-demon-search'),
			'custom_class' => 'cd_closed',
			'hide_location' => false,
			'hide_condition' => false,
			'hide_year' => false,
			'hide_year_range' => false,
			'hide_make' => false,
			'hide_model' => false,
			'hide_price' => false,
			'hide_mileage' => false,
			'hide_body_style' => true,
			'hide_trim_level' => false,
			'hide_transmission' => false,
			'value_location' => '',
			'value_condition' => '',
			'value_make' => '',
			'value_model' => '',
			'value_year' => '',
			'value_price' => '',
			'value_mileage' => '',
			'value_body_style' => '',
			'value_trim_level' => '',
			'value_transmission' => '',
			'label_location' => __('Location', 'car-demon-search'),
			'label_condition' => __('Condition', 'car-demon-search'),
			'label_make' => __('Make', 'car-demon-search'),
			'label_model' => __('Model', 'car-demon-search'),
			'label_year' => __('Year Range', 'car-demon-search'),
			'label_price' => __('Price Range', 'car-demon-search'),
			'label_mileage' => __('Mileage', 'car-demon-search'),
			'label_body_style' => __('Body Style', 'car-demon-search'),
			'label_trim_level' => __('Trim Level', 'car-demon-search'),
			'label_transmission' => __('Transmission', 'car-demon-search'),
			'label_button' => __('Find Your Car', 'car-demon-search'),
			'label_reset' => __('Reset Filters', 'car-demon-search'),
			'form_action' => get_option('siteurl'),
			'style' => ''
		 );

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<div class="cdsf_widget_admin">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'car-demon-search'); ?></label>
                <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
            </p>
    
            <p>
                <label for="<?php echo $this->get_field_id( 'custom_class' ); ?>"><?php _e('Custom CSS Class:', 'car-demon-search'); ?></label>
                <input id="<?php echo $this->get_field_id( 'custom_class' ); ?>" name="<?php echo $this->get_field_name( 'custom_class' ); ?>" value="<?php echo $instance['custom_class']; ?>" />
            </p>

			<p>
            
                <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e('Style:', 'car-demon-search'); ?></label>            
            <?php
				// Determine if global settings are only loading CSS for a single style
				$cdsf_use_css_form_global = get_option('cdsf_use_css_form');
				$cdsf_use_css_form = $instance['style'];
				$x =  '<select id="'.$this->get_field_id( 'style' ).'" name="'.$this->get_field_name( 'style' ).'">';
					$x .=  '<option value="0"'.($cdsf_use_css_form == 0 ? ' selected': '').'>'.__('No CSS', 'car-demon-search').'</option>';
					if ($cdsf_use_css_form_global == 1 || empty($cdsf_use_css_form_global)) {
						$x .=  '<option value="1"'.($cdsf_use_css_form == 1 ? ' selected': '').'>'.__('Style One', 'car-demon-search').'</option>';
					}
					if ($cdsf_use_css_form_global == 2 || empty($cdsf_use_css_form_global)) {
						$x .=  '<option value="2"'.($cdsf_use_css_form == 2 ? ' selected': '').'>'.__('Style Two', 'car-demon-search').'</option>';
					}
					if ($cdsf_use_css_form_global == 3 || empty($cdsf_use_css_form_global)) {
						$x .=  '<option value="3"'.($cdsf_use_css_form == 3 ? ' selected': '').'>'.__('Style Three', 'car-demon-search').'</option>';
					}
					if ($cdsf_use_css_form_global == 4 || empty($cdsf_use_css_form_global)) {
						$x .=  '<option value="4"'.($cdsf_use_css_form == 4 ? ' selected': '').'>'.__('Style Four', 'car-demon-search').'</option>';
					}
					//$x .=  '<option value=""'.($cdsf_use_css_form == '' ? ' selected': '').'>'.__('Load All CSS Styles', 'car-demon-search').'</option>';
				$x .=  '</select>';
				echo $x;
			?>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'form_action' ); ?>"><?php _e('Form Results Page:', 'car-demon-search'); ?></label>
                <input id="<?php echo $this->get_field_id( 'form_action' ); ?>" name="<?php echo $this->get_field_name( 'form_action' ); ?>" value="<?php echo $instance['form_action']; ?>" />
            </p>
    
            <?php
            $labels = array('label_location', 'label_condition','label_make','label_model','label_year','label_price','label_mileage','label_body_style','label_button','label_reset');
            if ( defined( 'CDPRO_EXTRAS' ) ) {
				$labels[] = 'label_trim_level';
				$labels[] = 'label_transmission';
			}
            foreach ($labels as $label) {
                $title = str_replace('label_', '', $label);
                $title = str_replace('_', ' ', $label);
                $title = ucwords($title);
                ?>
                <p>
                    <label for="<?php echo $this->get_field_id( $label ); ?>"><?php echo $title; ?></label>
                    <input id="<?php echo $this->get_field_id( $label ); ?>" name="<?php echo $this->get_field_name( $label ); ?>" value="<?php echo $instance[$label]; ?>" />
                </p>
                <?php
            }
            
            ?>

           <?php
            $values = array('value_location', 'value_condition','value_make','value_model','value_year','value_price','value_mileage','value_body_style');
   			if ( defined( 'CDPRO_EXTRAS' ) ) {
				$values[] = 'value_trim_level';
				$values[] = 'value_transmission';
			}
            foreach ($values as $value) {
                $title = str_replace('value_', '', $value);
                $title = str_replace('_', ' ', $value);
                $title = ucwords($title);
                ?>
                <p>
                    <label for="<?php echo $this->get_field_id( $value ); ?>"><?php echo $title; ?></label>
                    <input id="<?php echo $this->get_field_id( $value ); ?>" name="<?php echo $this->get_field_name( $value ); ?>" value="<?php echo $instance[$value]; ?>" />
                </p>
                <?php
            }
            
            ?>
                
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['hide_location'], 'on'); ?> id="<?php echo $this->get_field_id('hide_location'); ?>" name="<?php echo $this->get_field_name('hide_location'); ?>" /> 
                <label for="<?php echo $this->get_field_id( 'hide_location' ); ?>"><?php _e('Hide Location', 'car-demon-search'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['hide_condition'], 'on'); ?> id="<?php echo $this->get_field_id('hide_condition'); ?>" name="<?php echo $this->get_field_name('hide_condition'); ?>" /> 
                <label for="<?php echo $this->get_field_id( 'hide_condition' ); ?>"><?php _e('Hide Condition', 'car-demon-search'); ?></label>
            </p>
    
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['hide_year'], 'on'); ?> id="<?php echo $this->get_field_id('hide_year'); ?>" name="<?php echo $this->get_field_name('hide_year'); ?>" /> 
                <label for="<?php echo $this->get_field_id( 'hide_year' ); ?>"><?php _e('Hide Year', 'car-demon-search'); ?></label>
            </p>

            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['hide_year_range'], 'on'); ?> id="<?php echo $this->get_field_id('hide_year_range'); ?>" name="<?php echo $this->get_field_name('hide_year_range'); ?>" /> 
                <label for="<?php echo $this->get_field_id( 'hide_year_range' ); ?>"><?php _e('Hide Year Range', 'car-demon-search'); ?></label>
            </p>
    
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['hide_make'], 'on'); ?> id="<?php echo $this->get_field_id('hide_make'); ?>" name="<?php echo $this->get_field_name('hide_make'); ?>" /> 
                <label for="<?php echo $this->get_field_id( 'hide_make' ); ?>"><?php _e('Hide Make', 'car-demon-search'); ?></label>
            </p>
    
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['hide_model'], 'on'); ?> id="<?php echo $this->get_field_id('hide_model'); ?>" name="<?php echo $this->get_field_name('hide_model'); ?>" /> 
                <label for="<?php echo $this->get_field_id( 'hide_model' ); ?>"><?php _e('Hide Model', 'car-demon-search'); ?></label>
            </p>
    
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['hide_price'], 'on'); ?> id="<?php echo $this->get_field_id('hide_price'); ?>" name="<?php echo $this->get_field_name('hide_price'); ?>" /> 
                <label for="<?php echo $this->get_field_id( 'hide_price' ); ?>"><?php _e('Hide Price', 'car-demon-search'); ?></label>
            </p>
    
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['hide_mileage'], 'on'); ?> id="<?php echo $this->get_field_id('hide_mileage'); ?>" name="<?php echo $this->get_field_name('hide_mileage'); ?>" /> 
                <label for="<?php echo $this->get_field_id( 'hide_mileage' ); ?>"><?php _e('Hide Mileage', 'car-demon-search'); ?></label>
            </p>

			<p>
                <input class="checkbox" type="checkbox" <?php checked($instance['hide_body_style'], 'on'); ?> id="<?php echo $this->get_field_id('hide_body_style'); ?>" name="<?php echo $this->get_field_name('hide_body_style'); ?>" /> 
                <label for="<?php echo $this->get_field_id( 'hide_body_style' ); ?>"><?php _e('Hide Body Style', 'car-demon-search'); ?></label>
            </p>
			<?php
			if ( defined( 'CDPRO_EXTRAS' ) ) {
				?>
				<p>
					<input class="checkbox" type="checkbox" <?php checked($instance['hide_trim_level'], 'on'); ?> id="<?php echo $this->get_field_id('hide_trim_level'); ?>" name="<?php echo $this->get_field_name('hide_trim_level'); ?>" /> 
					<label for="<?php echo $this->get_field_id( 'hide_trim_level' ); ?>"><?php _e('Hide Trim Level', 'car-demon-search'); ?></label>
				</p>
				
				<p>
					<input class="checkbox" type="checkbox" <?php checked($instance['hide_transmission'], 'on'); ?> id="<?php echo $this->get_field_id('hide_transmission'); ?>" name="<?php echo $this->get_field_name('hide_transmission'); ?>" /> 
					<label for="<?php echo $this->get_field_id( 'hide_transmission' ); ?>"><?php _e('Hide Transmission', 'car-demon-search'); ?></label>
				</p>
				<?php
			}
			?>
		</div>
	<?php
	}
}


?>
