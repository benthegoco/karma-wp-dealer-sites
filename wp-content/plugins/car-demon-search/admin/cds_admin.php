<?php
function cds_settings_page() {
	add_submenu_page( 'edit.php?post_type=cars_for_sale', 'Pro Search', 'Pro Search', 'edit_pages', 'cds_options', 'cds_options_do_page' );
}
add_action( 'admin_menu', 'cds_settings_page' );

function cds_options_do_page() {
	$x = '<div class="wrap cd_welcome">';
		$x .= '<h2 class="cd_welcome_small">&nbsp;</h2>';
		$x .= '<h2>'.__('Car Demon Pro Search Settings','car-demon-search').'</h2>';
		if (isset($_POST['build_cache_now'])) {
			cds_delete_cache();
			cds_build_cache();
			$x .= '<h3>'.__('Your inventory cache has been updated.','car-demon-as').'</h3>';
			$x .= '<h3>'.__('If you are using a third party CDN or cache PlugIn then please make sure it is set to update this file daily.','car-demon-as').'</h3>';
			//=Uncomment to view json inventory
			//print_r(cds_get_current_inventory());
		}
		cds_set_schedule();

        $x .='<div class="cd_welcome_logo">
            	<a href="http://cardemons.com/" target="_blank">
	            	<img src="'.trailingslashit(plugins_url()).'car-demon/images/cd-certified-support.png'.'" />
				</a>
            </div>';

		$x .= '<div class="cd_welcome_tab_title" data-tab="cd_welcome">
			'.__('Welcome', 'car-demon-search').'
			</div>';
		$x .= '<div class="cd_welcome_tab_title" data-tab="cd_settings">
			'.__('Settings', 'car-demon-search').'
			</div>';

		$x .= '<div class="cd_welcome_clear"></div>';
		$x .= '<div class="cd_welcome_tab active" id="cd_welcome">
				<h3>
					'.__('Welcome!', 'car-demon-search').'
				</h3>';

				$x .= __('', 'car-demon-search');
				$x .= __('Thank you for installing the Car Demon Pro Search PlugIn!', 'car-demon-search');
				$x .= '<br />';
				$x .= '<br />';
				
				$x .= __('This PlugIn uses a json file to populate the search form(s).', 'car-demon-search');
				$x .= '<br />';
				$x .= '<br />';

				$x .= __("Think of it as a text file that gets downloaded to your visitor's browser and contains the vehicle information displayed in the form.", "car-demon-search");
				$x .= '<br />';
				$x .= '<br />';

				$x .= __('You can click on the "Build json file" button on the settings page to recreate your json file at anytime.', 'car-demon-search');
				$x .= '<br />';
				$x .= '<br />';

				$x .= __('There is also a setting to schedule a daily update of the json file.', 'car-demon-search');
				$x .= '<br />';
				$x .= '<br />';

				$x .= "<b style='color:#f00;'>".__("DON'T forget to setup your schedule!", "car-demon-search")."</b>";
				$x .= '<br />';
				$x .= '<br />';
				
				$x .= "<b>".__ ("Your search form needs to update daily to correctly reflect your inventory.", "car-demon-search")."</b>";
				$x .= '<br />';
				$x .= '<br />';

				$x .= __('It is suggested that you setup the schedule for early in the morning.', 'car-demon-search');
				$x .= '<br />';
				$x .= '<br />';
				
				$x .= __('Anytime you make significant changes to your inventory you can manually "Build json file" to keep your search form up to date.', 'car-demon-search');
				$x .= __('', 'car-demon-search');
				$x .= '<br />';
				$x .= '<br />';

				$x .= '<h3>'.__('Displaying your form', 'car-demon-search').'</h3>';
				
				$x .= __('Your form can be displayed with either a widget or with the shortcode [pro_search].', 'car-demon-search');
				$x .= '<br />';
				
				$x .= __('You can use the following parameters with the shortcode;', 'car-demon-search');
				$x .= '<br />';
				$x .= '<ul>';
					$x .= '<li>';
						$atts = array(
							'title' => __('Search', 'car-demon-search'),
							'custom_class' => 'cd_closed //= add a custom CSS class to your form',
							'hide_body_style' => 'on //= if you set "hide" fields to "on" will hide them on the form',
							'hide_condition' => 'on',
							'hide_year' => 'on',
							'hide_make' => 'on',
							'hide_model' => 'on',
							'hide_price' => 'on',
							'hide_mileage' => 'on',
							'hide_location' => 'on',
							'label_location' => __('Location', 'car-demon-search') . ' //= you can modify labels as needed',
							'label_condition' => __('Condition', 'car-demon-search'),
							'label_make' => __('Make', 'car-demon-search'),
							'label_model' => __('Model', 'car-demon-search'),
							'label_year' => __('Year Range', 'car-demon-search'),
							'label_price' => __('Price Range', 'car-demon-search'),
							'label_mileage' => __('Mileage', 'car-demon-search'),
							'label_body_style' => __('Body Style', 'car-demon-search'),
							'label_button' => __('Find Your Car', 'car-demon-search'),
							'label_reset' => __('Reset Filters', 'car-demon-search'),
							'form_action' => get_option('siteurl') .' //= change the form "action" to a specific url.',
							'style' => 1 .'//= style to load for this form (exp. 1, 2, or 3) blank for none'
						);
					ob_start();
						echo '<pre>';
							print_r($atts);
						echo '</pre>';
					$params = ob_get_contents();
					ob_end_clean();
					$params = str_replace('Array', '', $params);
					$params = str_replace('(', '', $params);
					$params = str_replace(')', '', $params);
					$x .= $params;
					$x .= '</li>';
				$x .= '</ul>';

				$x .= '<b>'.__('Example:', 'car-demon-search').'</b>';
				$x .= '<br />';
				$x .= '[pro_search title="" hide_body_style="on" hide_year="on" hide_location="on" label_button="Search"]';
				$x .= '<br />';
				$x .= '<br />';
				$x .= __('This would create a search form with a blank title, no body style, year or location fields and a submit button that says "Search".', 'car-demon-search');
				$x .= '<br />';
				$x .= '<br />';

				$x .='<div align="center">
					<a href="http://cardemons.com/" target="_blank">
						<img src="'.trailingslashit(plugins_url()).'car-demon/images/wp-cd-logo.png" />
					</a>
				</div>';

				$x .= '<hr />';
				$x .= '<br />';
				$x .= '<h3>'.__('Tips', 'car-demon-search').'</h3>';
					$x .= __('If you use Style Two and enter a custom class of "cd_closed" in the widget settings then the form will start off with all of the fields closed by default.', 'car-demon-search');
					$x .= '<br />';
					$x .= '<br />';
					$x .= __("If you add ' + ' to the beginning of a field label then it will switch to ' - ' when the field opens and flip back to a plus when it's closed.", "car-demon-search");
					$x .= '<br />';
					$x .= '<br />';
					$x .= __("That's a space, plus sign, & another space.", "car-demon-search");
					$x .= '<br />';
					$x .= '<br />';
					$x .= __("You can also call the search form function directly in your template:", "car-demon-search");
					$x .= '<br />';
					$x .= 'car_demon_search_form($settings);';
					$x .= '<br />';
					$x .= '<br />';
					$x .= __('$settings needs to be an associative array using the fields listed above under "Displaying your form".', 'car-demon-search');
					$x .= '<br />';
					$x .= '$settings = array ("title" = > "My Title", "hide_location" => "on");';
					
					//car_demon_search_form($settings);
					
					$x .= __('exp: " + Condition"', 'car-demon-search');
		$x .= '</div>';

		$x .= '<div class="cd_welcome_tab" id="cd_settings">
				<h3>
					'.__('I. Update your json file', 'car-demon-search').'
				</h3>';

				$x .='<form method="post" action="">';
					$x .= '<input type="submit" name="build_cache_now" value="'.__('Build json file now', 'car-demon-search').'">';
				$x .= '</form>';
				
				$x .= '<br />';
				$x .= '<small>';
					$x .= __("You can manually update your search form's json file by clicking the button above.", "car-demon-search");
				$x .= '</small>';
				$x .= '<hr />';
				
				//= Schedule Options
				$x .=  '<h3>'.__('II. You can set a schedule here to automatically update your json file daily.', 'car-demon-search').'</h3>';
				$x .=  '<form action="" method="post">';
					$x .=  __('Time:', 'car-demon-search').' <select name="hour">';
					$i = 1;
					do {
						$x .=  '<option value="'.$i.'">'.$i.'</option>';
						++$i;
					} while ($i < 25);		
					$x .=  '</select>';
					$x .=  '&nbsp;<select name="minute">';
					$x .=  '<option value="00">00</option>';
					$i = 10;
					do {
						$x .=  '<option value="'.$i.'">'.$i.'</option>';
						$i = $i + 1;
					} while ($i < 60);		
					$x .=  '</select>';
					$x .=  '<input type="hidden" name="set_cds_schedule" value="1" />';
					$x .=  '<input type="submit" name="submit" value="'.__('Set json build time', 'car-demon-search').'" />';
				$x .=  '</form>';
				$timestamp = wp_next_scheduled('build_cds_cache');
				if (!empty($timestamp)) {
					$x .=  '<br />'.__('Next Scheduled:', 'car-demon-search').' '. date('Y-m-d H:i', $timestamp).'<br />';
					$x .=  __('Right Now:', 'car-demon-search').' ';
					$blogtime = current_time( 'mysql' );
					//list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = split( '([^0-9])', $blogtime );
					$x .=  $blogtime.'<br />';
					$x .=  '<form action="" method="post">';
						$x .=  '<input type="hidden" name="remove_schedule" value="1" />';
						$x .=  '<input type="submit" name="submit" value="'.__('Remove Schedule', 'car-demon-search').'" />';
					$x .=  '</form>';
				} else {
					$x .=  '<br /><b>'.__('Schedule has not been set to auto update your json file.', 'car-demon-search').'</b>';
				}
				$x .=  '<hr />';
				//= Update json
				if (isset($_POST['update_save_json'])) {
					update_option('save_json', $_POST['save_json']);
				}
				$save_json = get_option('save_json', '0');
				if ($save_json == '1') {
					$select_yes = ' selected';
					$select_no = '';
				} else {
					$select_no = ' selected';
					$select_yes = '';
				}
				$x .=  '<h3>'.__('III. Load json each time page is loaded:', 'car-demon-search').'</h3>';
				$x .=  '<blockquote>';
					$x .=  '<form action="" method="post" enctype="multipart/form-data">';
						$x .=  '<input type="hidden" name="update_save_json" value="1" /> ';
						$x .=  '<select id="save_json" name="save_json">';
							$x .=  '<option value="1"'.$select_yes.'>Yes</option>';
							$x .=  '<option value="0"'.$select_no.'>No</option>';
						$x .=  '</select>';
						$x .=  '<input type="submit" name="submit" value="'.__('Save', 'car-demon-search').'" />';
					$x .=  '</form>';
				$x .=  '</blockquote>';
				$x .= '<small>';
					$x .= __("Your json file will be cached for 24 hours unless you set this value to 'no'.", "car-demon-search");
					$x .= '<br />';
					$x .= __("When set to 'no' this will force your visitors to download a fresh copy of the json file each time they reload the page.", "car-demon-search");
					$x .= '<br />';
					$x .= __("Only set this value to 'no' if you need to force visitors to update due to a recent manual change.", "car-demon-search");
				$x .= '</small>';
				$x .= '<hr />';
				
				//= Load CSS for Search Form
				if (isset($_POST['cds_use_css_form'])) {
					update_option('cds_use_css_form', $_POST['cds_use_css_form_val']);
				}
				$cds_use_css_form = get_option('cds_use_css_form', '1');
		
				$x .=  '<h3>'.__('IV. Load css for search form:', 'car-demon-search').'</h3>';
				$x .=  '<blockquote>';
					$x .=  '<form action="" method="post" enctype="multipart/form-data">';
						$x .=  '<input type="hidden" name="cds_use_css_form" value="1" /> ';
						$x .=  '<select id="cds_use_css_form_val" name="cds_use_css_form_val">';
							$x .=  '<option value="0"'.($cds_use_css_form == 0 ? ' selected': '').'>'.__('No CSS', 'car-demon-search').'</option>';
							$x .=  '<option value="1"'.($cds_use_css_form == 1 ? ' selected': '').'>'.__('Style One', 'car-demon-search').'</option>';
							$x .=  '<option value="2"'.($cds_use_css_form == 2 ? ' selected': '').'>'.__('Style Two', 'car-demon-search').'</option>';
							$x .=  '<option value="3"'.($cds_use_css_form == 3 ? ' selected': '').'>'.__('Style Three', 'car-demon-search').'</option>';
							$x .=  '<option value=""'.($cds_use_css_form == '' ? ' selected': '').'>'.__('Load All CSS Styles', 'car-demon-search').'</option>';
						$x .=  '</select>';
						$x .=  '<input type="submit" name="submit" value="'.__('Save', 'car-demon-search').'" />';
					$x .=  '</form>';
				$x .=  '</blockquote>';
				$x .= '<small>';
					$x .= __("Select the styles to load for your search form.", "car-demon-search");
					$x .= '<br />';
					$x .= __("If you're only going to use one style then select it here so you don't load unneeded CSS files.", "car-demon-search");
					$x .= '<br />';
					$x .= __("You can also choose to use no CSS if you opt to style the form yourself.", "car-demon-search");
				$x .= '</small>';
				$x .= '<hr />';
				
				//= Load CSS for jqueryui
				if (isset($_POST['cds_use_css_jqueryui'])) {
					update_option('cds_use_css_jqueryui', $_POST['cds_use_css_jqueryui_val']);
				}
				$cds_use_css_jqueryui = get_option('cds_use_css_jqueryui', '1');
				if ($cds_use_css_jqueryui == '1') {
					$select_yes = ' selected';
					$select_no = '';
				} else {
					$select_no = ' selected';
					$select_yes = '';
				}
				$x .=  '<h3>'.__('V. Load css for jQueri UI:', 'car-demon-search').'</h3>';
				$x .=  '<blockquote>';
					$x .=  '<form action="" method="post" enctype="multipart/form-data">';
						$x .=  '<input type="hidden" name="cds_use_css_jqueryui" value="1" /> ';
						$x .=  '<select id="cds_use_css_jqueryui_val" name="cds_use_css_jqueryui_val">';
							$x .=  '<option value="1"'.$select_yes.'>'.__('Yes', 'car-demon-search').'</option>';
							$x .=  '<option value="0"'.$select_no.'>'.__('No', 'car-demon-search').'</option>';
						$x .=  '</select>';
						$x .=  '<input type="submit" name="submit" value="'.__('Save', 'car-demon-search').'" />';
					$x .=  '</form>';
				$x .=  '</blockquote>';
				$x .= '<small>';
					$x .= __("If you will not be using any field with a slider then set this option to 'No'.", "car-demon-search");
				$x .= '</small>';
				$x .= '<hr />';

			$x .='<div class="cd_welcome_demon_logo">
            	<a href="http://cardemons.com/" target="_blank">
	            	<img src="'.trailingslashit(plugins_url()).'car-demon/images/wp-cd-logo.png" />
				</a>
            </div>';

		$x .= '</div>';
//		$x .= cds_color_control();
	$x .= '</div>';
	echo $x;
}

function cds_set_schedule() {
	if (isset($_POST['set_cds_schedule'])) {
		$month = date("m");
		$day = date("d");
		$year = date("Y");
		$sec = "0";
		$hour = $_POST['hour'];
		$min = $_POST['minute'];
		$new_time = mktime($hour,$min,$sec,$month,$day,$year);
		wp_clear_scheduled_hook('build_cds_cache');	
		wp_schedule_event($new_time, 'daily', 'build_cds_cache');
	}
	if (isset($_POST['remove_schedule'])) {
		wp_clear_scheduled_hook('build_cds_cache');	
	}
}
function cds_schedule() {
	cds_delete_cache();
	cds_build_cache();
}
add_action( 'build_cds_cache', 'cds_schedule' );
?>