<?php
add_action("wp_ajax_create_inventory_page", "create_inventory_page");

add_action( 'admin_init', 'cd_welcome_screen_do_activation_redirect' );
function cd_welcome_screen_do_activation_redirect() {
	// Check for constant that bypasses redirect - fix for users on older versions of WordPress
	if ( defined( 'CAR_DEMON_NO_WELCOME' ) ) {
		return;
	}

	// Bail if no activation redirect
	if ( ! get_transient( '_cd_welcome_screen_activation_redirect' ) ) {
		return;
	}
	
	// Delete the redirect transient
	delete_transient( '_cd_welcome_screen_activation_redirect' );
	
	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}
	
	// Redirect to about page
	wp_safe_redirect( add_query_arg( array( 'page' => 'car_demon_settings_options', 'post_type' => 'cars_for_sale' ), admin_url( 'edit.php' ) ) );

}

function cd_welcome_screen_content() {
	$dir = plugin_dir_path( __FILE__ );
	$plugin_file = str_replace('admin/', 'car-demon.php', $dir);
	$plugin_file = str_replace('admin\\', 'car-demon.php', $plugin_file);
	$plugin_data = get_plugin_data( $plugin_file, true, true );
	$cd_version = $plugin_data['Version'];

	$html = '
        <div class="wrap cd_welcome">
			<!--
				We need to add an empty h2 tag at the top so notices can hook onto it.
			-->
			<h2 class="cd_welcome_small">&nbsp;</h2>
        	<div class="cd_welcome_logo">
            	<a href="http://cardemons.com/" target="_blank">
	            	<img src="'.trailingslashit(plugins_url()).'car-demon/images/cd-certified-support.png'.'" />
				</a>
            </div>
        	<div class="cd_welcome_demon_logo">
            	<a href="http://cardemons.com/" target="_blank">
	            	<img src="'.trailingslashit(plugins_url()).'car-demon/images/wp-cd-logo.png" />
				</a>
            </div>
            <h2>'.__('Welcome to Car Demon', 'car-demon').' '.$cd_version.'</h2>
            <div class="welcome_excerpt">
	            '.__('Thank you for installing Car Demon', 'car-demon').' '.$cd_version.'
                <br /><br />
                '.__('You\'re now ready to release all your Wicked Powers for Car Dealers!', 'car-demon').'
                <br /><br />
                '.__('For professional support and assistance please visit:', 'car-demon').' <a href="http://cardemons.com" target="_blank">CarDemons.com</a>
            </div>
            <div class="cd_welcome_clear"></div>
            <h3>
            	'.__('For custom development, add-ons and themes designed to expand and enhance Car Demon please visit:', 'car-demon').' <a href="http://cardemons.com" target="_blank">CarDemons.com</a>
            </h3>
            <div class="cd_welcome_tab_title active" data-tab="cd_welcome">
	            '.__('What\'s New?', 'car-demon').'
            </div>
            <div class="cd_welcome_tab_title" data-tab="cd_startup_guide">
            	'.__('Startup Guide', 'car-demon').'
            </div>
            <div class="cd_welcome_tab_title" data-tab="cd_settings">
            	'.__('Settings', 'car-demon').'
            </div>';

			$single_location = false;
			if ( $single_location ) {
				$html .= '<div class="cd_welcome_tab_title" data-tab="cd_single_location">
								' . __('Locations', 'car-demon') . '
							</div>';
			} else {
				$html .= '<div class="cd_welcome_tab_title" data-tab="cd_change_log">
					'.__('Change Log', 'car-demon').'
				</div>';
			}

			$html .= '
            <div class="cd_welcome_tab_title" data-tab="cd_trouble_shooting">
            	'.__('Go Pro!', 'car-demon').'
            </div>
            <div class="cd_welcome_clear"></div>
           	<div class="cd_welcome_tab active" id="cd_welcome">
                <h3>
                    <span>
                        <img class="alignleft size-thumbnail wp-image-3122 cd_welcome_left" src="'.trailingslashit(plugins_url()).'car-demon/images/car-demon-1.5.0-150x150.png" alt="car-demon-1.5.0" width="150" height="150" />
                        '.__("We're excited to announce the release of Car Demon ", "car-demons").$cd_version.__(' and want to thank everyone who beta tested and provided feedback.', 'car-demon').'
                    </span>
                </h3>
                <h4>'.__('The most exciting new feature is an inventory shortcode you can drop in a page,', 'car-demon').' [cd_inventory].</h4>
				<p class="cd_setup_text">
	                ';
					
					$html .= __('This new shortcode accepts year, make, model, condition, body_style, transmission, location, stock, criteria ie. keyword search, min_price, max_price, mileage, searches vehicles with less than the mileage entered, and show_sold', 'car-demon');
					
                $html .= '</p>
                <p class="cd_setup_text">
	                '.__('You can also use the title parameter and add a title to the top of the listings.', 'car-demon').'
                </p>
	                '.__('You now have the ability to create a page that lists, for example, ford trucks;', 'car-demon').'
                <pre>[cd_inventory title="Ford Trucks" make="ford" body_style="truck"]</pre>
	                '.__('To give you even more power you can assign search forms to the page your shortcode is on and search just those items.', 'car-demon').'
                <p class="cd_setup_text">
	                '.__('To make sure your search forms point to the correct result page you can set the result_page parameter for the search form widget or use it in the search form shortcode;', 'car-demon').'
                <pre>[search_form size=1 result_page="'.__('the url to your inventory shortcode page', 'car-demon').'"]</pre>
	                '.__('We\'ve added a short form on the Startup Guide tab that can create a page and drop both the search form and inventory shortcodes into it.', 'car-demon').'
                </p>
                <p class="cd_setup_text">
	                '.__('The default style is fairly plain, but if you\'ve created a filter for your inventory items it will be applied to the shortcode.', 'car-demon').'
                </p>
                <img class="aligncenter size-medium wp-image-3123 cd_welcome_center_shortcode" src="'.trailingslashit(plugins_url()).'car-demon/images/car-demon-shortcode-inventory.png" alt="car-demon-shortcode-inventory" />
                <p class="cd_setup_text">
					<h4>
					'.__('If you\'d like a professional style we also offer the', 'car-demon').
					' "<a href="https://cardemons.com/product/car-demon-pro-shortcode" target="_blank">'.
						__('Car Demon Pro Shortcode', 'car-demon').
					'</a>" '.
					__('plugin.', 'car-demon').
					'</h4>'.
					__('Now you can quickly and easily add a professional responsive inventory layout that you can color match to your theme.', 'car-demon').'
                </p>
				<a href="https://cardemons.com/product/car-demon-pro-shortcode" target="_blank">
	                <img class="aligncenter size-medium wp-image-3124 cd_welcome_center_pro" src="'.trailingslashit(plugins_url()).'car-demon/images/car-demon-pro-shortcode.png" alt="car-demon-pro-shortcode" />
                </a>
				<p class="cd_setup_text">
                '.__('Other changes include minor style changes, admin UI updates, performance tweaks and misc clean up and structuring.', 'car-demon').'
                </p>
			</div>';

		$html .= '
			<div class="cd_welcome_tab" id="cd_startup_guide">
				<h3>'.__('Start up guide', 'car-demon').'</h3>
                    <p class="cd_setup_step">
                        '.__('1. First you need to setup your location(s).', 'car-demon').
						' <a href="edit-tags.php?taxonomy=vehicle_location&post_type=cars_for_sale" target="_blank">'.
						__('Click here', 'car-demon').
						'</a> '.
						__('to add your locations.', 'car-demon').'
					</p>
                    <p class="cd_setup_step">
                        '.__('2. Make sure you fill out your', 'car-demon').' '.
							__('Location Settings as completely as possible.', 'car-demon').' '.
						__('We suggest adding a location called "Default" which will cover any vehicle not assigned to a location.', 'car-demon').'
                    </p>
                    <p class="cd_setup_step">
                        '.__('3. Now add the inventory shortcode to a page.', 'car-demon').'
                    </p>
                    <pre>
                        <b>[cd_inventory]</b>
                    </pre>
                    <p class="cd_setup_text">
                        <b>'.__('Parameters to filter inventory - accepts year, make, model, condition, body_style, transmission, location, stock, vehicle_tag, criteria (keyword search), min_price, max_price, mileage (searches vehicles with less than the mileage entered) & show_sold', 'car-demon').'</b>
                    </p>
					<p class="cd_setup_text">
						'.__('Example', 'car-demon').': [cd_inventory title="'.__('View our Inventory', 'car-demon').'" make="dodge" condition="new"]
					</p>
                    <p class="cd_setup_inventory">
                        '.__('Enter a page name here and hit submit and we\'ll create an inventory page and insert the shortcode.', 'car-demon').'
                        <br /><br />
                        <input type="text" name="create_inventory" class="create_inventory" id="create_inventory" value="'.__('Inventory', 'car-demon').'" />
                        <br /><br />
                        <input type="checkbox" name="create_inventory_search" class="create_inventory_search" value="yes" checked="checked" /> '.__('Add a search form to inventory page', 'car-demon').'
                        <br /><br />
                        <input type="button" value="'.__('Add Inventory Page', 'car-demon').'" class="create_inventory_btn" />
						<span class="create_inventory_results"></span>
                    </p>
                    <p class="cd_setup_text">
                        '.__('You can also create template pages for your layout, filter layouts or use one of our professional themes or add-ons to change the visual appearance.', 'car-demon').'
                    </p>
                    <h4 class="cd_setup">
                        '.__('Our Website has', 'car-demon').' <a href="http://cardemons.com" target="_blank">'.__('lots of information', 'car-demon').'</a> '.__('on creating your own styles and leveraging Car Demon in your theme.', 'car-demon').'
                    </h4>
                    <p class="cd_setup_step">
                        '.__('4. After you\'ve setup your Inventory Page you might want to import a sample inventory.', 'car-demon').'
                    </p>
                    <p class="cd_setup_text">
						<blockquote>
							<p class="cd_setup_text">
								'. __('Click the button below to import sample vehicles.', 'car-demon') .'
							</p>
							<p class="cd_setup_text">
								'. __('Number of sample vehicles to import:', 'car-demon') .' '. cd_select_sample_qty() .'
								<br />
								<input type="button" class="cd_insert_samples_btn" value="'. __('Insert Sample Vehicles Now', 'car-demon') .'" />
							</p>
							<p class="cd_setup_text cd_sample_inventory">
								'. __('After you click the button please be patient while the vehicles and their images are inserted.', 'car-demon') .'
							</p>
							<p class="cd_setup_text">
								'. __('The more vehicles you select the longer your sample import will take.', 'car-demon') .'
							</p>
							<p class="cd_setup_text">
								'. __('Sample gallery photos will be linked to rather than imported.', 'car-demon') .'
							</p>
							<p class="cd_setup_text">
								'. __('If you would like to import the gallery photos please add the following to your wp-config.php file:', 'car-demon') .'
								<br />
								define("CD_IMPORT_SAMPLE_PHOTOS", true);
							</p>
							<p class="cd_setup_text">
								'. __('Add this right before the line that says: That\'s all, stop editing! Happy blogging.', 'car-demon') .'
							</p>
							<p class="cd_setup_text">
								<b>'. __('NOTE: Importing all photos may cause your server to timeout. Use with caution!', 'car-demon') .'</b>
							</p>

						</blockquote>
                    </p>
                    <p class="cd_setup_step">
                        '.__('5. Next click the Settings tab on this page and customize the features you want to use.', 'car-demon').'
                    </p>
						<p class="cd_setup_text">
							'.__('After you adjust your settings you should', 'car-demon').'
							 <a href="options-permalink.php" target="_blank">
								'.__('click here', 'car-demon').'
							</a> 
							'.__('to update your permalinks.', 'car-demon').'
						</p>
						<p class="cd_setup_text">
							'.__('We suggest you use a permalink structure like this:', 'car-demon').'
							<br /><br />/%postname%/%post_id%/</b>
						</p>
                    <p class="cd_setup_text">
                        '.__('If you run into trouble please visit the', 'car-demon').
						' <a href="http://wordpress.org/support/plugin/car-demon" target="_blank">'.
							__('WordPress support forum', 'car-demon').
						'</a> '.
						__('for Car Demon.', 'car-demon').'
                    </p>
                    <h4>
                        '.__('For professional one on one assistance please contact us at our website', 'car-demon').' <a href="http://CarDemons.com" target="_blank">CarDemons.com</a>
                    </h4>
					<div class="cd_welcome_logo">
						<a href="http://cardemons.com/" target="_blank">
							<img src="'.trailingslashit(plugins_url()).'car-demon/images/cd-certified-support.png" />
						</a>
					</div>
				</div>
				';
				echo $html;
			?>
			<div class="cd_welcome_tab" id="cd_settings">
                <?php
				car_demon_settings_form();
				?>
            </div>
			<?php
			if ( $single_location ) {
				?>
				<div class="cd_welcome_tab" id="cd_single_location">
					<?php
					ob_start();
					car_demon_plugin_options_do_page();
					$location_settings = ob_get_contents();
					ob_end_clean();
					$location_settings = str_replace( 'class="cd_location"', 'class="cd_single_location"', $location_settings );
					echo $location_settings;
					?>
				</div>
            <?php
			} //= end if single location
			$readme = cd_readme('Changelog');
			$html = '
            <div class="cd_welcome_tab" id="cd_change_log">
                <p class="cd_setup_text">
	                <h3>Car Demon Change log</h3>
				'.$readme.'
                </p>
                <p>
                '.__('If you\'ve recently updated and are having an issue please accept our apology and post a comment on our website so we can investigate the issue and issue patches as needed.', 'car-demon').'
                </p>
                <p>
                '.__('In closing we want to thank everyone for their continued support and look forward to many more updates!', 'car-demon').'
                </p>
                <div class="cd_welcome_logo">
                    <a href="http://cardemons.com/" target="_blank">
                        <img src="'.trailingslashit(plugins_url()).'car-demon/images/cd-certified-support.png" />
                    </a>
                </div>
            </div>
			';
			echo $html;
			?>
			<div class="cd_welcome_tab" id="cd_trouble_shooting">
            	<?php
					require_once('go-pro.php');
				?>
                <div class="cd_welcome_logo">
                    <a href="http://cardemons.com/" target="_blank">
                        <img src="<?php echo trailingslashit(plugins_url()).'car-demon/images/cd-certified-support.png' ?>" />
                    </a>
                </div>
            </div>
        </div>
	<?php
}

function create_inventory_page() {
	if (isset($_POST['page_name']) && isset($_POST['include_search'])) {
		$page_name = sanitize_text_field($_POST['page_name']);
		$include_search = sanitize_text_field($_POST['include_search']);
		$html = '';
		if ($include_search == 'yes') {
			$html .= '[search_form size=1 title=""]';
		}
		$html .= '[cd_inventory]';
		
		// create the inventory shortcode page if it doesn't exist.
		if (!get_page_by_title($page_name)) {
			// Create post object
			$page = array(
			  'post_title'    => $page_name,
			  'post_type'	  => 'page',
			  'post_content'  => $html,
			  'post_status'   => 'publish',
			  'post_author'   => 1,
			);
			// Insert the post into the database
			$p_id = wp_insert_post( $page );
			$cd_cdrf_options = array();
			$cd_cdrf_options = get_option( 'car_demon_options' );
			$cd_cdrf_options['inventory_page'] = get_permalink($p_id);
			$cd_cdrf_options['result_page_created'] = true;
			update_option( 'car_demon_options', $cd_cdrf_options );
			_e('Your page has been created and the following has been added to it:', 'car-demon');
			echo '<br />';
			echo $html;
			echo '<br />';
			echo '<a href="'.$cd_cdrf_options['inventory_page'].'" target="_blank">';
				_e('View Your Page', 'car-demon');
			echo '</a>';
		} else {
			$page = get_page_by_title($page_name);
			$link = $page->guid;
			_e('That page name has already been used.', 'car-demon');
			echo '<br />';
			_e('A new page could not be created, please use a new name and try again.', 'car-demon');
			echo '<br />';
			echo '<a href="'.$link.'" target="_blank">';
				_e('View existing page', 'car-demon');
			echo '</a>';
		}
	} else {
		echo 'Your page could not be created. Please make sure you entered a title.';
	}
	exit();	
}

function cd_select_sample_qty() {
	$qty = 1;
	$x = '<select class="sample_qty">';
	while($qty <= 30) {
		if ($qty == 3) {
			$select = ' selected';
		} else {
			$select = '';
		}
		$x .= '<option value="'. $qty .'"'. $select .'>'. $qty .'</option>';
		++$qty;
	} 
	$x .= '</select>';
	return $x;
}
?>