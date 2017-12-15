<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: Async JavaScript
Plugin URI: https://cloughit.com.au/product/async-javascript/
Description: Async JavaScript adds a 'async' or 'defer' attribute to scripts loaded via wp_enqueue_script
Version: 2.17.11.15
Author: Clough I.T. Solutions
Author URI: http://www.cloughit.com.au/
Text Domain: async-javascript
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
/**
 *  aj_admin_init()
 *
 *  Register admin stylesheets and javascripts
 *
 */
add_action( 'init', 'aj_admin_init' );
function aj_admin_init() {
	define( 'AJ_TITLE', 'Async JavaScript' );
    define( 'AJ_ADMIN_MENU_SLUG', 'async-javascript' );
    define( 'AJ_ADMIN_ICON_URL', 'dashicons-performance' );
    define( 'AJ_ADMIN_POSITION', 3 );
	define( 'AJ_ADMIN_URL', trailingslashit( admin_url() ) );
    define( 'AJ_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
    define( 'AJ_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    define( 'AJ_VERSION', '2.17.11.15' );
	define( 'AJ_UA', 'Async JavaScript/' . AJ_VERSION . ' (+https://cloughit.com.au/product/async-javascript/)' );
	if ( !class_exists( 'Services_WTF_Test' ) ) {
		require_once( AJ_PLUGIN_DIR . 'lib/gtmetrix/class.Services_WTF_Test.php' );
	}
}
/**
 *  aj_enqueue_scripts()
 *
 *  Register admin stylesheets and javascripts
 *
 */
add_action( 'admin_enqueue_scripts', 'aj_enqueue_scripts' );
function aj_enqueue_scripts() {
	wp_register_style(
        'aj_admin_styles',
        plugins_url( '/css/admin.min.css', __FILE__ )
    );
    wp_enqueue_style( 'aj_admin_styles' );
    wp_enqueue_script(
        'aj_admin_scripts',
        plugins_url( '/js/admin.min.js', __FILE__ ),
        array( 'jquery' ),
        time()
    );
	$aj_localize = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'siteurl' => get_site_url(),
		'pluginurl' => AJ_PLUGIN_URL,
		'ajadminurl' => admin_url( 'options-general.php?page=async-javascript' )
	);
    wp_localize_script( 'aj_admin_scripts', 'aj_localize_admin', $aj_localize );
}
/**
 *  register_aj_dashboard_widget()
 *
 *  Register dashboard widget
 *
 */
add_action( 'wp_dashboard_setup', 'register_aj_dashboard_widget' );
function register_aj_dashboard_widget() {
	if ( current_user_can( 'manage_options' ) ) {
		global $wp_meta_boxes;
		wp_add_dashboard_widget(
			'aj_dashboard_widget',
			AJ_TITLE,
			'aj_dashboard_widget'
		);
		$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$my_widget = array( 'aj_dashboard_widget' => $dashboard['aj_dashboard_widget'] );
	 	unset( $dashboard['aj_dashboard_widget'] );
	 	$sorted_dashboard = array_merge( $my_widget, $dashboard );
	 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}
}
/**
 *  aj_dashboard_widget()
 *
 *  Dashboard widget
 *
 */
function aj_dashboard_widget() {
    $site_url = trailingslashit( get_site_url() );
	$aj_gtmetrix_username = get_option( 'aj_gtmetrix_username', '' );
	$aj_gtmetrix_api_key = get_option( 'aj_gtmetrix_api_key', '' );
	$aj_gtmetrix_server = get_option( 'aj_gtmetrix_server', '' );
	$aj_enabled = ( get_option( 'aj_enabled', 0 ) == 1 ) ? 'Enabled' : 'Disabled';
	$aj_method = ( get_option( 'aj_method', 'async' ) == 'async' ) ? 'Async' : 'Defer';
	$aj_jquery = get_option( 'aj_jquery', 'async' );
	$aj_jquery = ( $aj_jquery == 'same ' ) ? get_option( 'aj_method', 'async' ) : $aj_jquery;
	$aj_jquery = ( $aj_jquery == 'async' ) ? 'Async' : ( $aj_jquery == 'defer' ) ? 'Defer' : 'Excluded';
	$aj_exclusions = get_option( 'aj_exclusions', '' );
	$aj_autoptimize_enabled = ( get_option( 'aj_autoptimize_enabled', 0 ) == 1 ) ? 'Enabled' : 'Disabled';
	$aj_autoptimize_method = ( get_option( 'aj_autoptimize_method', 'async' ) == 'async' ) ? 'Async' : 'Defer';
	?>
	<div class="wrap aj">
		<h3><?php echo AJ_TITLE; ?> Status</h3>
		<ul>
			<li><strong>Status:</strong> <?php echo $aj_enabled; ?></li>
			<?php
			if ( $aj_enabled == 'Enabled' ) {
				?>
				<li><strong>Method:</strong> <?php echo $aj_method; ?></li>
				<li><strong>jQuery:</strong> <?php echo $aj_jquery; ?></li>
				<li><strong>Exclusions:</strong> <?php echo $aj_exclusions; ?></li>
				<?php
				if ( is_plugin_active( 'autoptimize/autoptimize.php' ) ) {
					?>
					<li><strong>Autoptimize Status:</strong> <?php echo $aj_autoptimize_enabled; ?></li>
					<?php
					if ( $aj_autoptimize_enabled == 'Enabled' ) {
						?>
						<li><strong>Autoptimize Method:</strong> <?php echo $aj_autoptimize_method; ?></li>
						<?php
					}
				} else {
					?>
					<li>Autoptimize not installed or activated.</li>
					<?php
				}
			}
			?>
		</ul>
		<hr />
		<h3>Latest GTmetrix Results</h3>
		<?php
		$aj_gtmetrix_results = get_option( 'aj_gtmetrix_results', array() );
		if ( isset( $aj_gtmetrix_results['latest'] ) ) {
			$latest = $aj_gtmetrix_results['latest'];
			//$screenshot = $latest['results']['report_url'] . '/screenshot.jpg';
			$screenshot = $latest['screenshot'];
			$pagespeed = $latest['results']['pagespeed_score'];
			$yslow = $latest['results']['yslow_score'];
			$pr = round( 255 * ( 1 - ( $pagespeed / 100 ) ), 0 );
			$yr = round( 255 * ( 1 - ( $yslow / 100 ) ), 0 );
			$pg = round( 255 * ( $pagespeed / 100 ), 0 );
			$yg = round( 255 * ( $yslow / 100 ), 0 );
			$pagespeed_style = ' style="color: rgb(' . $pr . ',' . $pg . ',0 )"';
			$yslow_style = ' style="color: rgb(' . $yr . ',' . $yg . ',0 )"';
			$flt = number_format( ( float )$latest['results']['fully_loaded_time'] / 1000, 2, '.', '' );
			$tps = number_format( ( float )$latest['results']['page_bytes'] / 1024, 0, '.', '' );
			$requests = $latest['results']['page_elements'];
			$report = $latest['results']['report_url'];
			$report_url = '<a href="' . $report . '" target="_blank">' . $report . '</a>';
			?>
			<table id="aj_latest_gtmetrix_results" class="form-table aj-steps-table" width="100%" cellpadding="10">
				<tr>
					<td scope="row" align="center"><img src="data:image/jpeg;base64,<?php echo $screenshot; ?>" class="aj_latest_screenshot aj_gtmetrix_screenshot_dashboard">
					<td scope="row" align="center">
						<h3>PageSpeed Score</h3>
						<span class="aj_latest_pagespeed aj_gtmetrix_result"<?php echo $pagespeed_style; ?>><?php echo $pagespeed; ?>%</span>
					</td>
					<td scope="row" align="center">
						<h3>YSlow Score</h3>
						<span class="aj_latest_yslow aj_gtmetrix_result"<?php echo $yslow_style; ?>><?php echo $yslow; ?>%</span>
					</td>
				</tr>
				<tr>
					<td scope="row" align="center">
						<h3>Fully Loaded Time</h3>
						<span class="aj_latest_flt aj_gtmetrix_result"><?php echo $flt; ?>s</span>
					</td>
					<td scope="row" align="center">
						<h3>Total Page Size</h3>
						<span class="aj_latest_tps aj_gtmetrix_result"><?php echo $tps; ?>KB</span>
					</td>
					<td scope="row" align="center">
						<h3>Requests</h3>
						<span class="aj_latest_requests aj_gtmetrix_result"><?php echo $requests; ?></span>
					</td>
				</tr>
				<tr><td scope="row" align="left" colspan="6">See full report: <span class="aj_latest_report"><?php echo $report_url; ?></span></td></tr>
			</table>
			<?php
		}
		?>
		<p>Please click on the Settings button below to generate a new GTmetrix Report.</p>
		<p><button data-id="aj_goto_settings" class="aj_steps_button">Settings</button></p>
	</div>
	<?php
}
/**
 *  async_javascript_menu()
 *
 *  Register admin menu
 *
 */
add_action( 'admin_menu', 'async_javascript_menu' );
function async_javascript_menu() {
	add_submenu_page(
		'options-general.php',
		AJ_TITLE . ' Admin',
		AJ_TITLE,
		'manage_options',
		'async-javascript',
		'async_javascript_admin'
	);

}
/**
 *  async_javascript_admin()
 *
 *  Admin page
 *
 */
function async_javascript_admin() {
    // load settings from database
    $tabs = array( 'wizard', 'status', 'settings', 'help' );
	$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) : 'wizard';
    ?>
    <div class="wrap aj">
    	<input type="hidden" id="aj_nonce" value="<?php echo wp_create_nonce( "aj_nonce" ); ?>" />
    	<div id="aj_notification"></div>
        <h2>Welcome to <?php echo AJ_TITLE; ?></h2>
        <h2 class="nav-tab-wrapper">
        	<?php
        	foreach ($tabs as $tab ) {
        		$active = $active_tab == $tab ? 'nav-tab-active' : '';
        		echo '<a href="?page=async-javascript&tab=' . $tab . '" class="nav-tab ' . $active . '">' . ucfirst( $tab ) . '</a>';
        	}
			?>
	    </h2>
	    <?php
	    if ( $active_tab == 'wizard' ) {
			$site_url = trailingslashit( get_site_url() );
			$aj_gtmetrix_username = get_option( 'aj_gtmetrix_username', '' );
			$aj_gtmetrix_api_key = get_option( 'aj_gtmetrix_api_key', '' );
			$aj_gtmetrix_server = get_option( 'aj_gtmetrix_server', '' );
			if ( $aj_gtmetrix_username != '' && $aj_gtmetrix_api_key != '' ) {
				$test = new Services_WTF_Test();
				$test->api_username( $aj_gtmetrix_username );
        		$test->api_password( $aj_gtmetrix_api_key );
        		$test->user_agent( AJ_UA );
				$status = $test->status();
				$credits = $status['api_credits'];
			} else {
				$credits = 'N/A';
			}
			?>
			<table class="form-table" width="100%" cellpadding="10">
				<tr id="aj_intro">
					<td scope="row" align="center" style="vertical-align: top !important;"><img src="<?php echo AJ_PLUGIN_URL; ?>images/finger_point_out_punch_hole_400_clr_17860.png" title="<?php echo AJ_TITLE; ?>" alt="<?php echo AJ_TITLE; ?>"  class="aj_step_img"></td>
					<td scope="row" align="left" style="vertical-align: top !important;">
						<h3>Async JavaScript</h3>
						<?php echo about_aj(); ?>
					</td>
				</tr>
				<tr id="aj_step1">
					<td scope="row" align="center" style="vertical-align: top !important;"><img src="<?php echo AJ_PLUGIN_URL; ?>images/number_one_break_hole_150_clr_18741.gif" title="Step 1" alt="GTmetrix API Key" class="aj_step_img"></td>
					<td scope="row" align="left" style="vertical-align: top !important;">
						<h3>Step 1: GTmetrix API Key</h3>
						<p><strong><em>Please Note:</em></strong>You do not have to use this Wizard.  All settings can be changed under the <a href="<?php echo menu_page_url( AJ_ADMIN_MENU_SLUG, false ) . '&tab=settings'; ?>">Settings</a> tab.</p>
						<hr />
						<p>If you haven't already done so, grab an API Key from GTmetrix so that Async JavaScript can obtain your PageSpeed / YSlow results.  Here's how:</p>
						<ol>
							<li>Navigate to <a href="https://gtmetrix.com/api/" target="_blank">https://gtmetrix.com/api/</a> (link opens in a new tab)</li>
							<li>If you do not already have an account with GTmetrix, go ahead and sign up (it's FREE!).</li>
							<li>Log in to your GTmetrix account.</li>
							<li>If you haven't yet generated your API Key, click on <strong>Generate API Key</strong></li>
							<li>Copy your Username and API Key into the fields below:<br /><input type="text" id="aj_gtmetrix_username" value="<?php echo $aj_gtmetrix_username; ?>" placeholder="GTmetrix Username"><input type="text" id="aj_gtmetrix_api_key" value="<?php echo $aj_gtmetrix_api_key; ?>" placeholder="GTmetrix API Key"></li>
							<li>Select the desired server.<br />
								<select id="aj_gtmetrix_server">
									<?php
									$gtmetrix_locations = array(
										'Vancouver, Canada' => 1,
										'London, United Kingdom' => 2,
										'Sydney, Australia' => 3,
										'Dallas, United States' => 4,
										'Mumbai, India' => 5
									);
									foreach ( $gtmetrix_locations as $location => $value ) {
										$selected = ( $aj_gtmetrix_server == $value ) ? ' selected="selected"' : '';
										echo '<option value="' . $value . '"' . $selected . '>' . $location . '</option>';
									}
									?>
								</select>
							</li>
							<li>GTmetrix Credits Available: <span class="aj_gtmetrix_credits"><?php echo $credits; ?></span></li>
						</ol>
						<p><strong>Please Note:</strong> By clicking the button below you acknowledge that you understand that five (5) GTmetrix API credits will be used.</p>
						<p><button data-id="aj_step2" class="aj_steps_button">Proceed to Step 2</button></p>
					</td>
				</tr>
				<tr id="aj_step2" class="aj_steps_hidden">
					<td scope="row" align="center"><img src="<?php echo AJ_PLUGIN_URL; ?>images/number_two_break_hole_150_clr_18753.gif" title="Step 2" alt="Initial Test Results" class="aj_step_img"></td>
					<td scope="row" align="left">
						<h3>Step 2: Initial Test Results</h3>
						<p><?php echo AJ_TITLE; ?> will now query GTmetrix and retrieve your sites PageSpeed and YSlow scores.</p>
						<p id="aj_step2_please_wait"><img src="<?php echo AJ_PLUGIN_URL; ?>images/loading.gif" title="Please Wait" alt="Please Wait" class="aj_step_img"></p>
						<table id="aj_step2_gtmetrix_results" class="form-table aj-steps-table" width="100%" cellpadding="10">
							<tr>
								<td scope="row" align="center"><img src="" class="aj_step2_screenshot aj_gtmetrix_screenshot">
								<td scope="row" align="center">
									<h3>PageSpeed Score</h3>
									<span class="aj_step2_pagespeed aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>YSlow Score</h3>
									<span class="aj_step2_yslow aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Fully Loaded Time</h3>
									<span class="aj_step2_flt aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Total Page Size</h3>
									<span class="aj_step2_tps aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Requests</h3>
									<span class="aj_step2_requests aj_gtmetrix_result"></span>
								</td>
							</tr>
							<tr><td scope="row" align="left" colspan="6">See full report: <span class="aj_step2_report"></span></td></tr>
							<tr><td scope="row" align="left" colspan="6">Simulate <span class="aj_step2_gtmetrix"></span>: <a href="" class="aj_step2_url" target="_blank"></a></tr>
							<p></p>
						</table>
					</td>
				</tr>
				<tr id="aj_step2b" class="aj_steps_hidden">
					<td scope="row" align="center"></td>
					<td scope="row" align="left">
						<h3>Testing: Async</h3>
						<p><?php echo AJ_TITLE; ?> will now query GTmetrix and retrieve your sites PageSpeed and YSlow scores whilst simulating the JavaScript 'async' method.</p>
						<p id="aj_step2b_please_wait"><img src="<?php echo AJ_PLUGIN_URL; ?>images/loading.gif" title="Please Wait" alt="Please Wait" class="aj_step_img"></p>
						<table id="aj_step2b_gtmetrix_results" class="form-table aj-steps-table" width="100%" cellpadding="10">
							<tr>
								<td scope="row" align="center"><img src="" class="aj_step2b_screenshot aj_gtmetrix_screenshot">
								<td scope="row" align="center">
									<h3>PageSpeed Score</h3>
									<span class="aj_step2b_pagespeed aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>YSlow Score</h3>
									<span class="aj_step2b_yslow aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Fully Loaded Time</h3>
									<span class="aj_step2b_flt aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Total Page Size</h3>
									<span class="aj_step2b_tps aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Requests</h3>
									<span class="aj_step2b_requests aj_gtmetrix_result"></span>
								</td>
							</tr>
							<tr><td scope="row" align="left" colspan="6">See full report: <span class="aj_step2b_report"></span></td></tr>
							<tr><td scope="row" align="left" colspan="6">Simulate <span class="aj_step2b_gtmetrix"></span>: <a href="" class="aj_step2b_url" target="_blank"></a></tr>
						</table>
					</td>
				</tr>
				<tr id="aj_step2c" class="aj_steps_hidden">
					<td scope="row" align="center"></td>
					<td scope="row" align="left">
						<h3>Testing: Defer</h3>
						<p><?php echo AJ_TITLE; ?> will now query GTmetrix and retrieve your sites PageSpeed and YSlow scores whilst simulating the JavaScript 'defer' method.</p>
						<p id="aj_step2c_please_wait"><img src="<?php echo AJ_PLUGIN_URL; ?>images/loading.gif" title="Please Wait" alt="Please Wait" class="aj_step_img"></p>
						<table id="aj_step2c_gtmetrix_results" class="form-table aj-steps-table" width="100%" cellpadding="10">
							<tr>
								<td scope="row" align="center"><img src="" class="aj_step2c_screenshot aj_gtmetrix_screenshot">
								<td scope="row" align="center">
									<h3>PageSpeed Score</h3>
									<span class="aj_step2c_pagespeed aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>YSlow Score</h3>
									<span class="aj_step2c_yslow aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Fully Loaded Time</h3>
									<span class="aj_step2c_flt aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Total Page Size</h3>
									<span class="aj_step2c_tps aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Requests</h3>
									<span class="aj_step2c_requests aj_gtmetrix_result"></span>
								</td>
							</tr>
							<tr><td scope="row" align="left" colspan="6">See full report: <span class="aj_step2c_report"></span></td></tr>
							<tr><td scope="row" align="left" colspan="6">Simulate <span class="aj_step2c_gtmetrix"></span>: <a href="" class="aj_step2c_url" target="_blank"></a></tr>
						</table>
					</td>
				</tr>
				<tr id="aj_step2d" class="aj_steps_hidden">
					<td scope="row" align="center"></td>
					<td scope="row" align="left">
						<h3>Testing: Async (jQuery excluded)</h3>
						<p><?php echo AJ_TITLE; ?> will now query GTmetrix and retrieve your sites PageSpeed and YSlow scores whilst simulating the JavaScript 'async' method but excluding jQuery.</p>
						<p id="aj_step2d_please_wait"><img src="<?php echo AJ_PLUGIN_URL; ?>images/loading.gif" title="Please Wait" alt="Please Wait" class="aj_step_img"></p>
						<table id="aj_step2d_gtmetrix_results" class="form-table aj-steps-table" width="100%" cellpadding="10">
							<tr>
								<td scope="row" align="center"><img src="" class="aj_step2d_screenshot aj_gtmetrix_screenshot">
								<td scope="row" align="center">
									<h3>PageSpeed Score</h3>
									<span class="aj_step2d_pagespeed aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>YSlow Score</h3>
									<span class="aj_step2d_yslow aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Fully Loaded Time</h3>
									<span class="aj_step2d_flt aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Total Page Size</h3>
									<span class="aj_step2d_tps aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Requests</h3>
									<span class="aj_step2d_requests aj_gtmetrix_result"></span>
								</td>
							</tr>
							<tr><td scope="row" align="left" colspan="6">See full report: <span class="aj_step2d_report"></span></td></tr>
							<tr><td scope="row" align="left" colspan="6">Simulate <span class="aj_step2d_gtmetrix"></span>: <a href="" class="aj_step2d_url" target="_blank"></a></tr>
						</table>
					</td>
				</tr>
				<tr id="aj_step2e" class="aj_steps_hidden">
					<td scope="row" align="center"></td>
					<td scope="row" align="left">
						<h3>Testing: Defer (jQuery excluded)</h3>
						<p><?php echo AJ_TITLE; ?> will now query GTmetrix and retrieve your sites PageSpeed and YSlow scores whilst simulating the JavaScript 'defer' method but excluding jQuery.</p>
						<p id="aj_step2e_please_wait"><img src="<?php echo AJ_PLUGIN_URL; ?>images/loading.gif" title="Please Wait" alt="Please Wait" class="aj_step_img"></p>
						<table id="aj_step2e_gtmetrix_results" class="form-table aj-steps-table" width="100%" cellpadding="10">
							<tr>
								<td scope="row" align="center"><img src="" class="aj_step2e_screenshot aj_gtmetrix_screenshot">
								<td scope="row" align="center">
									<h3>PageSpeed Score</h3>
									<span class="aj_step2e_pagespeed aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>YSlow Score</h3>
									<span class="aj_step2e_yslow aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Fully Loaded Time</h3>
									<span class="aj_step2e_flt aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Total Page Size</h3>
									<span class="aj_step2e_tps aj_gtmetrix_result"></span>
								</td>
								<td scope="row" align="center">
									<h3>Requests</h3>
									<span class="aj_step2e_requests aj_gtmetrix_result"></span>
								</td>
							</tr>
							<tr><td scope="row" align="left" colspan="6">See full report: <span class="aj_step2e_report"></span></td></tr>
							<tr><td scope="row" align="left" colspan="6">Simulate <span class="aj_step2e_gtmetrix"></span>: <a href="" class="aj_step2e_url" target="_blank"></a></tr>
						</table>
					</td>
				</tr>
				<tr id="aj_step_results" class="aj_steps_hidden">
					<td scope="row" align="center" style="vertical-align: top !important;"><img src="<?php echo AJ_PLUGIN_URL; ?>images/number_three_break_hole_150_clr_18837.gif" title="Results &amp; Recommendations" alt="Results &amp; Recommendations"  class="aj_step_img"></td>
					<td scope="row" align="left">
						<h3>Step 3: Results &amp; Recommendations</h3>
						<p><?php echo AJ_TITLE; ?> has finished testing your site with the most common configuration options.</p>
						<p>Based on the tests <?php echo AJ_TITLE; ?> has determined that <span class="aj_gtmetrix_config"></span> has resulted in <span id="aj_gtmetrix_inde_pagespeed"></span> in PageSpeed from <span id="aj_gtmetrix_baseline_pagespeed"></span> to <span id="aj_gtmetrix_best_pagespeed"></span> and <span id="aj_gtmetrix_inde_yslow"></span> in YSlow from <span id="aj_gtmetrix_baseline_yslow"></span> to <span id="aj_gtmetrix_best_yslow"></span>, with a Fully Loaded time of <span id="aj_gtmetrix_best_fullyloaded"></span>.</p>
						<p>Before applying these settings it is important to check your site is still functioning correctly.  Click the link below to open your site in a new tab / window to simulate the <?php echo AJ_TITLE; ?> settings and check that everything is working, and also be sure to check the console for any JavaScript errors (see <a href="https://codex.wordpress.org/Using_Your_Browser_to_Diagnose_JavaScript_Errors" target="_blank">Using Your Browser to Diagnose JavaScript Errors</a>)</p>
						<ul>
							<li>Simulate <span class="aj_gtmetrix_config"></span>: <a href="" id="aj_gtmetrix_best_url" target="_blank"></a></li>
						</ul>
						<p>Once you have simulated <span class="aj_gtmetrix_config"></span> click on the button below to continue.</p>
						<p><button data-id="aj_step4" class="aj_steps_button">Proceed to Step 4</button></p>
					</td>
				</tr>
				<tr id="aj_step4" class="aj_steps_hidden">
					<td scope="row" align="center" style="vertical-align: top !important;"><img src="<?php echo AJ_PLUGIN_URL; ?>images/number_four_break_hole_150_clr_18840.gif" title="Apply Settings" alt="Apply Settings"  class="aj_step_img"></td>
					<td scope="row" align="left">
						<h3>Step 4: Apply Settings</h3>
						<p>Is your site still functioning properly and are there no JavaScript errors in the console?</p>
						<p><input type="radio" name="aj_step4_check" value="y"> Yes <input type="radio" name="aj_step4_check" value="n"> No</p>
						<div id="aj_step4_y">
							<p>Great to hear! To apply these settings click the button below.</p>
							<p><button data-id="aj_apply_settings" class="aj_steps_button">Apply Settings</button></p>
						</div>
						<div id="aj_step4_n">
							<p>Ok, so you have run the simulation on <span class="aj_gtmetrix_config"></span> and although there has been an improvement in reported performance, the simulation shows that something is not right with your site.</p>
							<div id="aj_step4_jquery_excluded">
								<p>In most cases the issue being experienced relates to jQuery (usually due to inline JavaScript which relies on jQuery) and the solution is to exclude jQuery.  However, in this simulation jQuery has already been exculded.  As a result a different configuration may work better with a marginal sacrifice in site speed improvement.</p>
								<p>Below are links that can be used to run simulations on each of the basic configurations.  Click on each of the links and check the functionality of your site as well as the console for errors.</p>
								<ul>
									<li>Simulate <span class="aj_step2b_gtmetrix"></span>: <a href="" class="aj_step2b_url" target="_blank"></a></li>
									<li>Simulate <span class="aj_step2c_gtmetrix"></span>: <a href="" class="aj_step2c_url" target="_blank"></a></li>
									<li>Simulate <span class="aj_step2d_gtmetrix"></span>: <a href="" class="aj_step2d_url" target="_blank"></a></li>
									<li>Simulate <span class="aj_step2e_gtmetrix"></span>: <a href="" class="aj_step2e_url" target="_blank"></a></li>
								</ul>
								<p>Click one of the buttons below to apply these settings or click the Settings button to go to the settings page for manual configuration.</p>
								<p>
									<button data-id="aj_step2b_apply" class="aj_steps_button">Apply <span class="aj_step2b_gtmetrix"></button>
									<button data-id="aj_step2c_apply" class="aj_steps_button">Apply <span class="aj_step2c_gtmetrix"></button>
									<button data-id="aj_step2d_apply" class="aj_steps_button">Apply <span class="aj_step2d_gtmetrix"></button>
									<button data-id="aj_step2e_apply" class="aj_steps_button">Apply <span class="aj_step2e_gtmetrix"></button>
								</p>
								<p>
									<button data-id="aj_goto_settings" class="aj_steps_button">Settings</button>
								</p>
							</div>
							<div id="aj_step4_jquery_not_excluded">
								<p>In most cases the issue being experienced relates to jQuery (usually due to inline JavaScript which relies on jQuery) and the solution is to exclude jQuery.</p>
								<p>Below are links that can be used to run simulations on each of the configurations with jQuery excluded.  Click on each of the links and check the functionality of your site as well as the console for errors.</p>
								<ul>
									<li>Simulate <span class="aj_step2d_gtmetrix"></span>: <a href="" class="aj_step2d_url" target="_blank"></a></li>
									<li>Simulate <span class="aj_step2e_gtmetrix"></span>: <a href="" class="aj_step2e_url" target="_blank"></a></li>
								</ul>
								<p>Click one of the buttons below to apply these settings or click the Settings button to go to the settings page for manual configuration.</p>
								<p>
									<button data-id="aj_step2d_apply" class="aj_steps_button">Apply <span class="aj_step2d_gtmetrix"></button>
									<button data-id="aj_step2e_apply" class="aj_steps_button">Apply <span class="aj_step2e_gtmetrix"></button>
								</p>
								<p>
									<button data-id="aj_goto_settings" class="aj_steps_button">Settings</button>
								</p>
							</div>
						</div>
					</td>
				</tr>
				<tr id="aj_step5" class="aj_steps_hidden">
					<td scope="row" align="center" style="vertical-align: top !important;"><img src="<?php echo AJ_PLUGIN_URL; ?>images/number_five_break_hole_150_clr_18842.gif" title="Further Hints &amp; Tips" alt="Further Hints &amp; Tips"  class="aj_step_img"></td>
					<td scope="row" align="left">
						<?php echo hints_tips(); ?>
						<p><button data-id="aj_goto_settings" class="aj_steps_button">Settings</button></p>
					</td>
				</tr>
			</table>
			<?php
        } else if ( $active_tab == 'status' ) {
			$site_url = trailingslashit( get_site_url() );
			$aj_gtmetrix_username = get_option( 'aj_gtmetrix_username', '' );
			$aj_gtmetrix_api_key = get_option( 'aj_gtmetrix_api_key', '' );
			$aj_gtmetrix_server = get_option( 'aj_gtmetrix_server', '' );
			if ( $aj_gtmetrix_username != '' && $aj_gtmetrix_api_key != '' ) {
				$test = new Services_WTF_Test();
				$test->api_username( $aj_gtmetrix_username );
        		$test->api_password( $aj_gtmetrix_api_key );
        		$test->user_agent( AJ_UA );
				$status = $test->status();
				$credits = $status['api_credits'];
			} else {
				$credits = 'N/A';
			}
			$aj_enabled = ( get_option( 'aj_enabled', 0 ) == 1 ) ? 'Enabled' : 'Disabled';
			$aj_method = ( get_option( 'aj_method', 'async' ) == 'async' ) ? 'Async' : 'Defer';
			$aj_jquery = get_option( 'aj_jquery', 'async' );
			$aj_jquery = ( $aj_jquery == 'same ' ) ? get_option( 'aj_method', 'async' ) : $aj_jquery;
			$aj_jquery = ( $aj_jquery == 'async' ) ? 'Async' : ( $aj_jquery == 'defer' ) ? 'Defer' : 'Excluded';
			$aj_exclusions = get_option( 'aj_exclusions', '' );
			$aj_autoptimize_enabled = ( get_option( 'aj_autoptimize_enabled', 0 ) == 1 ) ? 'Enabled' : 'Disabled';
			$aj_autoptimize_method = ( get_option( 'aj_autoptimize_method', 'async' ) == 'async' ) ? 'Async' : 'Defer';
			?>
			<table class="form-table" width="100%" cellpadding="10">
				<tr>
					<td scope="row" align="center" style="vertical-align: top !important;"><img src="<?php echo AJ_PLUGIN_URL; ?>images/finger_point_out_punch_hole_400_clr_17860.png" title="Most Recent GTmetrix Results" alt="Most Recent GTmetrix Results"  class="aj_step_img"></td>
					<td scope="row" align="left">
						<h3><?php echo AJ_TITLE; ?></h3>
						<ul>
							<li><strong>Status:</strong> <?php echo $aj_enabled; ?></li>
							<?php
							if ( $aj_enabled == 'Enabled' ) {
								?>
								<li><strong>Method:</strong> <?php echo $aj_method; ?></li>
								<li><strong>jQuery:</strong> <?php echo $aj_jquery; ?></li>
								<li><strong>Exclusions:</strong> <?php echo $aj_exclusions; ?></li>
								<?php
								if ( is_plugin_active( 'autoptimize/autoptimize.php' ) ) {
									?>
									<li><strong>Autoptimize Status:</strong> <?php echo $aj_autoptimize_enabled; ?></li>
									<?php
									if ( $aj_autoptimize_enabled == 'Enabled' ) {
										?>
										<li><strong>Autoptimize Method:</strong> <?php echo $aj_autoptimize_method; ?></li>
										<?php
									}
								} else {
									?>
									<li>Autoptimize not installed or activated.</li>
									<?php
								}
							}
							?>
						</ul>
						<hr />
						<h3>Latest GTmetrix Results</h3>
						<?php
						$aj_gtmetrix_results = get_option( 'aj_gtmetrix_results', array() );
						if ( isset( $aj_gtmetrix_results['latest'] ) ) {
							$latest = $aj_gtmetrix_results['latest'];
							$screenshot = $latest['screenshot'];
							$pagespeed = $latest['results']['pagespeed_score'];
							$yslow = $latest['results']['yslow_score'];
							$pr = round( 255 * ( 1 - ( $pagespeed / 100 ) ), 0 );
							$yr = round( 255 * ( 1 - ( $yslow / 100 ) ), 0 );
							$pg = round( 255 * ( $pagespeed / 100 ), 0 );
							$yg = round( 255 * ( $yslow / 100 ), 0 );
							$pagespeed_style = ' style="color: rgb(' . $pr . ',' . $pg . ',0 )"';
							$yslow_style = ' style="color: rgb(' . $yr . ',' . $yg . ',0 )"';
							$flt = number_format( ( float )$latest['results']['fully_loaded_time'] / 1000, 2, '.', '' );
							$tps = number_format( ( float )$latest['results']['page_bytes'] / 1024, 0, '.', '' );
							$requests = $latest['results']['page_elements'];
							$report = $latest['results']['report_url'];
							$report_url = '<a href="' . $report . '" target="_blank">' . $report . '</a>';
							?>
							<p id="aj_latest_please_wait"><img src="<?php echo AJ_PLUGIN_URL; ?>images/loading.gif" title="Please Wait" alt="Please Wait" class="aj_step_img"></p>
							<table id="aj_latest_gtmetrix_results" class="form-table aj-steps-table" width="100%" cellpadding="10">
								<tr>
									<td scope="row" align="center"><img src="data:image/jpeg;base64,<?php echo $screenshot; ?>" class="aj_latest_screenshot aj_gtmetrix_screenshot">
									<td scope="row" align="center">
										<h3>PageSpeed Score</h3>
										<span class="aj_latest_pagespeed aj_gtmetrix_result"<?php echo $pagespeed_style; ?>><?php echo $pagespeed; ?>%</span>
									</td>
									<td scope="row" align="center">
										<h3>YSlow Score</h3>
										<span class="aj_latest_yslow aj_gtmetrix_result"<?php echo $yslow_style; ?>><?php echo $yslow; ?>%</span>
									</td>
									<td scope="row" align="center">
										<h3>Fully Loaded Time</h3>
										<span class="aj_latest_flt aj_gtmetrix_result"><?php echo $flt; ?>s</span>
									</td>
									<td scope="row" align="center">
										<h3>Total Page Size</h3>
										<span class="aj_latest_tps aj_gtmetrix_result"><?php echo $tps; ?>KB</span>
									</td>
									<td scope="row" align="center">
										<h3>Requests</h3>
										<span class="aj_latest_requests aj_gtmetrix_result"><?php echo $requests; ?></span>
									</td>
								</tr>
								<tr><td scope="row" align="left" colspan="6">See full report: <span class="aj_latest_report"><?php echo $report_url; ?></span></td></tr>
							</table>
							<hr />
							<?php
						} else {
							?>
							<table id="aj_latest_gtmetrix_results" class="form-table aj-steps-table" width="100%" cellpadding="10" style="display: none;">
								<tr>
									<td scope="row" align="center"><img src="" class="aj_latest_screenshot aj_gtmetrix_screenshot">
									<td scope="row" align="center">
										<h3>PageSpeed Score</h3>
										<span class="aj_latest_pagespeed aj_gtmetrix_result"></span>
									</td>
									<td scope="row" align="center">
										<h3>YSlow Score</h3>
										<span class="aj_latest_yslow aj_gtmetrix_result"></span>
									</td>
									<td scope="row" align="center">
										<h3>Fully Loaded Time</h3>
										<span class="aj_latest_flt aj_gtmetrix_result"></span>
									</td>
									<td scope="row" align="center">
										<h3>Total Page Size</h3>
										<span class="aj_latest_tps aj_gtmetrix_result"></span>
									</td>
									<td scope="row" align="center">
										<h3>Requests</h3>
										<span class="aj_latest_requests aj_gtmetrix_result"></span>
									</td>
								</tr>
								<tr><td scope="row" align="center" colspan="6">See full report: <span class="aj_latest_report"></span></td></tr>
							</table>
							<hr />
							<?php
						}
						?>
						<p>Please click on the button below to generate a new GTmetrix Report.</p>
						<p><strong>Please Note:</strong> By clicking the button below you acknowledge that you understand that one (1) GTmetrix API credit will be used.</p>
						<p><button data-id="aj_gtmetrix_test" class="aj_steps_button">Run GTmetrix Test</button></p>
						<h3>GTmetrix API Key</h3>
						<p>If you haven't already done so, grab an API Key from GTmetrix so that <?php echo AJ_TITLE; ?> can obtain your PageSpeed / YSlow results.  Here's how:</p>
						<ol>
							<li>Navigate to <a href="https://gtmetrix.com/api/" target="_blank">https://gtmetrix.com/api/</a> (link opens in a new tab)</li>
							<li>If you do not already have an account with GTmetrix, go ahead and sign up (it's FREE!).</li>
							<li>Log in to your GTmetrix account.</li>
							<li>If you haven't yet generated your API Key, click on <strong>Generate API Key</strong></li>
							<li>Copy your Username and API Key into the fields below:<br /><input type="text" id="aj_gtmetrix_username" value="<?php echo $aj_gtmetrix_username; ?>" placeholder="GTmetrix Username"><input type="text" id="aj_gtmetrix_api_key" value="<?php echo $aj_gtmetrix_api_key; ?>" placeholder="GTmetrix API Key"></li>
							<li>Select the desired server.<br />
								<select id="aj_gtmetrix_server">
									<?php
									$gtmetrix_locations = array(
										'Vancouver, Canada' => 1,
										'London, United Kingdom' => 2,
										'Sydney, Australia' => 3,
										'Dallas, United States' => 4,
										'Mumbai, India' => 5
									);
									foreach ( $gtmetrix_locations as $location => $value ) {
										$selected = ( $aj_gtmetrix_server == $value ) ? ' selected="selected"' : '';
										echo '<option value="' . $value . '"' . $selected . '>' . $location . '</option>';
									}
									?>
								</select>
							</li>
							<li>GTmetrix Credits Available: <span class="aj_gtmetrix_credits"><?php echo $credits; ?></span></li>
						</ol>
						<hr />
						<?php echo hints_tips(); ?>
					</td>
				</tr>
			</table>
			<?php
		} else if ( $active_tab == 'settings' ) {
			$site_url = trailingslashit( get_site_url() );
			$aj_gtmetrix_username = get_option( 'aj_gtmetrix_username', '' );
			$aj_gtmetrix_api_key = get_option( 'aj_gtmetrix_api_key', '' );
			$aj_gtmetrix_server = get_option( 'aj_gtmetrix_server', '' );
			$aj_enabled = get_option( 'aj_enabled', 0 );
			$aj_enabled_checked = ( $aj_enabled == 1 ) ? ' checked="checked"' : '';
			$aj_method = get_option( 'aj_method', 'async' );
			$aj_method_async = ( $aj_method == 'async' ) ? ' checked="checked"' : '';
			$aj_method_defer = ( $aj_method == 'defer' ) ? ' checked="checked"' : '';
			$aj_jquery = get_option( 'aj_jquery', 'async' );
			$aj_jquery = ( $aj_jquery == 'same ' ) ? $aj_method : $aj_jquery;
			$aj_jquery_async = ( $aj_jquery == 'async' ) ? ' checked="checked"' : '';
			$aj_jquery_defer = ( $aj_jquery == 'defer' ) ? ' checked="checked"' : '';
			$aj_jquery_exclude = ( $aj_jquery == 'exclude' ) ? ' checked="checked"' : '';
			$aj_exclusions = get_option( 'aj_exclusions', '' );
			$aj_autoptimize_enabled = get_option( 'aj_autoptimize_enabled', 0 );
			$aj_autoptimize_enabled_checked = ( $aj_autoptimize_enabled == 1 ) ? ' checked="checked"' : '';
			$aj_autoptimize_method = get_option( 'aj_autoptimize_method', 'async' );
			$aj_autoptimize_async = ( $aj_autoptimize_method == 'async' ) ? ' checked="checked"' : '';
			$aj_autoptimize_defer = ( $aj_autoptimize_method == 'defer' ) ? ' checked="checked"' : '';
			?>
			<table class="form-table" width="100%" cellpadding="10">
				<tr id="aj_intro">
					<td scope="row" align="center" style="vertical-align: top !important;"><img src="<?php echo AJ_PLUGIN_URL; ?>images/finger_point_out_punch_hole_400_clr_17860.png" title="<?php echo AJ_TITLE; ?>" alt="<?php echo AJ_TITLE; ?>"  class="aj_step_img"></td>
					<td scope="row" align="left" style="vertical-align: top !important;">
						<h3><?php echo AJ_TITLE; ?></h3>
						<?php echo about_aj(); ?>
					</td>
				</tr>
				<tr id="aj_settings_enable">
					<td scope="row" align="center" style="vertical-align: top !important;"><img src="<?php echo AJ_PLUGIN_URL; ?>images/finger_point_out_punch_hole_400_clr_17860.png" title="Enable <?php echo AJ_TITLE; ?>" alt="Enable <?php echo AJ_TITLE; ?>"  class="aj_step_img"></td>
					<td scope="row" align="left" style="vertical-align: top !important;">
						<h3>Enable <?php echo AJ_TITLE; ?></h3>
						<p><label>Enable <?php echo AJ_TITLE; ?>? </label><input type="checkbox" id="aj_enabled" id="aj_enabled" value="1" <?php echo $aj_enabled_checked; ?> /></p>
						<hr />
						<h3><?php echo AJ_TITLE; ?> Method</h3>
						<p>Please select the method (<strong>async</strong> or <strong>defer</strong>) that you wish to enable:</p>
						<p><label>Method: </label><input type="radio" name="aj_method" value="async" <?php echo $aj_method_async; ?> /> Async <input type="radio" name="aj_method" value="defer" <?php echo $aj_method_defer; ?> /> Defer</p>
						<hr />
						<h3>jQuery</h3>
						<p>Often if jQuery is loaded with <strong>async</strong> or <strong>defer</strong> it can break some jQuery functions, specifically inline scripts which require jQuery to be loaded before the scripts are run.  <strong><em>Sometimes</em></strong> choosing a different method (<strong>async</strong> or <strong>defer</strong>) will work, otherwise it may be necessary to exclude jQuery from having <strong>async</strong> or <strong>defer</strong> applied.</p>
						<p><label>jQuery Method: </label><input type="radio" name="aj_jquery" value="async" <?php echo $aj_jquery_async; ?> /> Async <input type="radio" name="aj_jquery" value="defer" <?php echo $aj_jquery_defer; ?> /> Defer <input type="radio" name="aj_jquery" value="exclude" <?php echo $aj_jquery_exclude; ?> /> Exclude</p>
						<hr />
						<h3>Script Exclusion</h3>
						<p>Please list any scripts which you would like excluded from having <strong>async</strong> or <strong>defer</strong> applied during page load. (comma seperated list eg: jquery.js,jquery-ui.js)</p>
						<p><label>Exclusions: </label><textarea id="aj_exclusions" style="width:95%;"><?php echo $aj_exclusions; ?></textarea></p>
						<hr />
						<h3><?php echo AJ_TITLE; ?> For Plugins</h3>
						<p>Although not recommended, some themes / plugins can load JavaScript files without using the <strong><a href="https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts" target="_blank">wp_enqueue_script</a></strong> function.  In some cases this is necessary for the functionality of the theme / plugin.</p>
						<p>If these themes / plugins provide a hook that can be used to manipulate how the JavaScript file is loaded then <?php echo AJ_TITLE; ?> may be able to provide support for these themes / plugins.</p>
						<p>If you have any active themes / plugins that <?php echo AJ_TITLE; ?> supports then these will be listed below.</p>
						<p>If you think you have found a plugin that <?php echo AJ_TITLE; ?> may be able to provide support for please lodge a ticket at <a href="https://cloughit.com.au/support/?wpsc_category=8" target="_blank">https://cloughit.com.au/support/</a></p>
						<?php
						if ( is_plugin_active( 'autoptimize/autoptimize.php' ) ) {
							?>
							<div class="aj_plugin">
								<h4>Plugin: Autoptimize</h4>
								<p><a href="https://wordpress.org/plugins/autoptimize/" target="_blank">https://wordpress.org/plugins/autoptimize/</a></p>
								<p><label>Enable Autoptimize Support: </label><input type="checkbox" id="aj_autoptimize_enabled" value="1" <?php echo $aj_autoptimize_enabled_checked; ?> /></p>
								<p><label>jQuery Method: </label><input type="radio" name="aj_autoptimize_method" value="async" <?php echo $aj_autoptimize_async; ?> /> Async <input type="radio" name="aj_autoptimize_method" value="defer" <?php echo $aj_autoptimize_defer; ?> /> Defer</p>
							</div>
							<?php
						}
						?>
						<p><button data-id="aj_save_settings" class="aj_steps_button">Save Settings</button></p>
					</td>
				</tr>
			</table>
	        <?php
	    } else if ( $active_tab == 'help' ) {
	    	?>
			<table class="form-table" width="100%" cellpadding="10">
				<tr>
					<td scope="row" align="center" style="vertical-align: top !important;"><img src="<?php echo AJ_PLUGIN_URL; ?>images/stick_figure_panicking_150_clr_13267.gif" title="Help &amp; Support" alt="Help &amp; Support"  class="aj_step_img"></td>
					<td scope="row" align="left" style="vertical-align: top !important;">
						<h3>Help &amp; Support</h3>
						<p>Below are some answers to some frequently asked questions about <?php echo AJ_TITLE; ?></p>
						<hr />
						<h3>Which browsers support the 'async' and 'defer' attributes?</h3>
						<p>The 'async' attribute is new in HTML5. It is supported by the following browsers:</p>
						<ul>
							<li>Chrome</li>
							<li>IE 10 and higher</li>
							<li>Firefox 3.6 and higher</li>
							<li>Safari</li>
							<li>Opera</li>
						</ul>
						<hr />
						<h3>Where can I get help?</h3>
						<p><?php echo AJ_TITLE; ?> is supported exclusively via our Support Ticketing System at <a href="https://cloughit.com.au/support/?wpsc_category=8" target="_blank">https://cloughit.com.au/support/</a></p>
						<hr />
						<h3>What support do you provide?</h3>
						<p>We will provide support for any functionality of the <?php echo AJ_TITLE; ?> plugin itself, suggestions for theme / plugin support and suggestions on how <?php echo AJ_TITLE; ?> could be improved.</p>
						<hr />
						<h3>What support don't you provide?</h3>
						<p>We will not provide support for questions such as 'Why is <?php echo AJ_TITLE; ?> not making any improvement on my site?'.  If you need this level of support we offer a bundled <?php echo AJ_TITLE; ?> Pro plus Installation &amp; Configuration (homepage only) AUD $155.00 (<a href="https://cloughit.com.au/product/async-javascript-pro-plus-install/" target="_blank">buy now!</a>)</p>
						<hr />
						<h3>Can I use the WordPress Forums to get support for <?php echo AJ_TITLE; ?>?</h3>
						<p>No. Support is provided via our Support Ticketing System at <a href="https://cloughit.com.au/support/?wpsc_category=8" target="_blank">https://cloughit.com.au/support/</a></p>
						<hr />
						<h3>Can I email the author directly to get support for <?php echo AJ_TITLE; ?>?</h3>
						<p>No. Support is provided via our Support Ticketing System at <a href="https://cloughit.com.au/support/?wpsc_category=8" target="_blank">https://cloughit.com.au/support/</a></p>
						<hr />
						<h3>What about CSS?</h3>
						<p>As the name implies, <?php echo AJ_TITLE; ?> is built to enhance JavaScript loading only. <?php echo AJ_TITLE; ?> does not have any impact on CSS.</p>
						<p>We recommend using the awesome <a href="https://wordpress.org/plugins/autoptimize/" target="_blank">Autoptimize</a> plugin alongside <?php echo AJ_TITLE; ?> for CSS optimization.</p>
						<hr />
						<h3>Do you have a pro version?</h3>
						<p>Yes we do! Here are some of the benefits of <?php echo AJ_TITLE; ?> Pro:</p>
						<ul>
							<li>Selective ‘async’ – choose which JavaScripts to apply ‘async’ to</li>
							<li>Selective ‘defer’ – choose which JavaScripts to apply ‘defer’ to</li>
							<li>Exclude individual scripts – choose which JavaScripts to ignore</li>
							<li>Exclude plugins – choose local plugin JavaScripts to ignore</li>
							<li>Exclude themes – choose local theme JavaScripts to ignore</li>
						</ul>
						<p><a href="https://cloughit.com.au/product/async-javascript-pro/" target="_blank">Buy Now!</a></p>
						<hr />
						<h3>I want out, how should I remove <?php echo AJ_TITLE; ?>?</h3>
						<ul>
							<li>Disable the plugin</li>
							<li>Delete the plugin</li>
						</ul>
					</td>
				</tr>
			</table>
			<?php
		}
		?>
    </div>
    <?php
}
/**
 *  about_aj()
 *
 *  Return common text for about Async JavaScript
 *
 */
function about_aj() {
	$return = '';
	$return .= '<p>When a JavaScript file is loaded via the <strong><a href="https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts" target="_blank">wp_enqueue_script</a></strong> function, ' . AJ_TITLE . ' will add an <strong>async</strong> or <strong>defer</strong> attribute.</p>';
	$return .= '<p>There are several ways an external JavaScript file can be executed:</p>';
	$return .= '<ul style="list-style:disc inside;">';
		$return .= '<li>If <strong>async</strong> is present: The script is executed asynchronously with the rest of the page (the script will be executed while the page continues the parsing)</li>';
		$return .= '<li>If <strong>defer</strong> is present and <strong>async</strong> is not present: The script is executed when the page has finished parsing</li>';
		$return .= '<li>If neither <strong>async</strong> or <strong>defer</strong> is present: The script is fetched and executed immediately, before the browser continues parsing the page</li>';
	$return .= '</ul>';
	$return .= '<p>Using <strong>async</strong> or <strong>defer</strong> helps to eliminate render-blocking JavaScript in above-the-fold content.  This can also help to increase your pagespeed which in turn can assist in improving your page ranking.</p>';
	return $return;
}
/**
 *  hints_tips()
 *
 *  Return common text for Hints & Tips
 *
 */
function hints_tips() {
	$return = '';
	$return .= '<h3>Further Hints &amp; Tips</h3>';
	if ( is_plugin_active( 'autoptimize/autoptimize.php' ) ) {
		$return .= '<p>' . AJ_TITLE . ' has detected that you have Autoptimize installed and active.  ' . AJ_TITLE . ' can further enhance Autoptimize results by applying Async or Defer to the cache files used by Autoptimize.</p>';
	} else {
		$return .= '<p>' . AJ_TITLE . ' has detected that you do not have Autoptimize installed and active.  Autoptimize can provide further optimization of JavaScript which can benefit the results of ' . AJ_TITLE . ' (and ' . AJ_TITLE . ' can also enhance Autoptimize results!)</p>';
		$return .= '<p>You can install Autoptimize from the plugin repository, or download it from here: <a href="https://wordpress.org/plugins/autoptimize/" target="_blank">https://wordpress.org/plugins/autoptimize/</a></p>';
	}
	$return .= '<p>Through our testing the following common Autoptimize settings work well to achieve the best results.  Of course each website is different so you may need to fine tune these settings to suit.</p>';
	$return .= '<ol>';
		$return .= '<li>Navigate to <strong>Settings &gt; Autoptimize</strong></li>';
		$return .= '<li>Click on the <strong>Show advanced settings</strong> button</li>';
		$return .= '<li>Under <strong>JavaScript Options</strong> set the following:</li>';
		$return .= '<ul>';
			$return .= '<li><strong>Optimize JavaScript Code?</strong>: Checked</li>';
			$return .= '<li><strong>Force JavaScript in &lt;head&gt;?</strong>: Unchecked</li>';
			$return .= '<li><strong>Also aggregate inline JS?</strong>: Checked<br />(did you need to exclude jQuery in ' . AJ_TITLE . '? Enabling this option <strong><em>MAY</em></strong> help resolve jQuery errors caused by inline JavaScript / jQuery code)</li>';
			$return .= '<li><strong>Exclude scripts from Autoptimize:</strong>: Leave as default (or add any other scripts that you may need to exclude)</li>';
			$return .= '<li><strong>Add try-catch wrapping?</strong>: Unchecked</li>';
		$return .= '</ul>';
		$return .= '<li>Click on the <strong>Save Changes and Empty Cache</strong> button</li>';
		$return .= '<li>Navigate to <strong>Settings &gt; ' . AJ_TITLE . '</strong></li>';
		$return .= '<li>Click on the <strong>Settings</strong> tab</li>';
		$return .= '<li>Scroll down to <strong>' . AJ_TITLE . ' For Plugins</strong></li>';
		$return .= '<li>Under <strong>Autoptimize</strong> set the following:</li>';
		$return .= '<ul>';
			$return .= '<li><strong>Enable Autoptimize Support</strong>: Checked</li>';
			$return .= '<li><strong>Method</strong>: Select either <strong>Async</strong> or <strong>Defer</strong> (testing has found that <strong>Defer</strong> usually works best here!)</li>';
		$return .= '</ul>';
		$return .= '<li>Click on <strong>Save Changes</strong></li>';
	$return .= '</ol>';
	return $return;
}
/**
 *  async_js()
 *
 *  Add 'async' or 'defer' attribute to '<script>' tasks called via wp_enqueue_script using the 'script_loader_tag' filter
 *
 */
add_filter( 'script_loader_tag', 'async_js', 10, 3 );
function async_js( $tag, $handle, $src ) {
	if ( isset( $_GET['aj_simulate'] ) ) {
		$aj_enabled = true;
		$aj_method = sanitize_text_field( $_GET['aj_simulate'] );
		if ( isset( $_GET['aj_simulate_jquery'] ) ) {
			$aj_jquery = sanitize_text_field( $_GET['aj_simulate_jquery'] );
		} else {
			$aj_jquery = $aj_method;
		}
		$array_exclusions = array();
	} else {
		$aj_enabled = ( get_option( 'aj_enabled', 0 ) == 1 ) ? true : false;
	    $aj_method = ( get_option( 'aj_method', 'async' ) == 'async' ) ? 'async' : 'defer';
		$aj_jquery = get_option( 'aj_jquery', 'async' );
		$aj_jquery = ( $aj_jquery == 'same ' ) ? $aj_method : $aj_jquery;
	    $aj_exclusions = get_option( 'aj_exclusions', '' );
	    $array_exclusions = ( $aj_exclusions != '' ) ? explode( ',', $aj_exclusions ) : array();
	}
    if ( false !== $aj_enabled && false === is_admin() ) {
        if ( is_array( $array_exclusions ) && !empty( $array_exclusions ) ) {
            foreach ( $array_exclusions as $exclusion ) {
            	$exclusion = trim( $exclusion );
                if ( $exclusion != '' ) {
                    if ( false !== strpos( strtolower( $src ), strtolower( $exclusion ) ) ) {
                        return $tag;
                    }
                }
            }
        }
		if ( false !== strpos( strtolower( $src ), 'js/jquery/jquery.js' ) ) {
			if ( $aj_jquery == 'async' || $aj_jquery == 'defer' ) {
				$tag = str_replace( 'src=', $aj_jquery . "='" . $aj_jquery . "' src=", $tag );
        		return $tag;
			} else if ( $aj_jquery == 'exclude' ) {
				return $tag;
			}
		}
		$tag = str_replace( 'src=', $aj_method . "='" . $aj_method . "' src=", $tag );
        return $tag;
    }
    return $tag;
}
/**
 *  my_autoptimize_defer()
 *
 *  Adds support for Autoptimize plugin.  Adds 'async' attribute to '<script>' tasks called via autoptimize_filter_js_defer filter
 *  Autoptimize: https://wordpress.org/plugins/autoptimize/
 *
 */
add_filter( 'autoptimize_filter_js_defer', 'my_autoptimize_defer', 11 );
function my_autoptimize_defer( $defer ) {
	$aj_enabled = ( get_option( 'aj_enabled', 0 ) == 1 ) ? true : false;
    $aj_method = ( get_option( 'aj_method', 'async' ) == 'async' ) ? 'async' : 'defer';
    $aj_autoptimize_enabled = ( get_option( 'aj_autoptimize_enabled', 0 ) == 1 ) ? true : false;
	$aj_autoptimize_method = ( get_option( 'aj_autoptimize_method', 'async' ) == 'async' ) ? 'async' : 'defer';
    if ( false !== $aj_enabled && false === is_admin() ) {
        if ( false !== $aj_autoptimize_enabled ) {
            return " " . $aj_autoptimize_method . "='" . $aj_autoptimize_method . "' ";
        }
    }
}
function aj_steps() {
	check_ajax_referer( 'aj_nonce', 'security' );
	$aj_gtmetrix_results = get_option( 'aj_gtmetrix_results', array() );
	if ( !isset( $_POST['sub_action'] ) ) {
		$return = array(
			'status' => false,
			'error' => 'No sub action defined.'
		);
	} else {
		$sub_action = sanitize_text_field( $_POST['sub_action'] );
		switch ( $sub_action ) {
			case 'aj_step2':
				$aj_gtmetrix_username = sanitize_text_field( $_POST['aj_gtmetrix_username'] );
				$aj_gtmetrix_api_key = sanitize_text_field( $_POST['aj_gtmetrix_api_key'] );
				$aj_gtmetrix_server = sanitize_text_field( $_POST['aj_gtmetrix_server'] );
				$site_url = trailingslashit( esc_url( $_POST['site_url'] ) );
				update_option( 'aj_gtmetrix_username', $aj_gtmetrix_username );
				update_option( 'aj_gtmetrix_api_key', $aj_gtmetrix_api_key );
				update_option( 'aj_gtmetrix_server', $aj_gtmetrix_server );
				$test = new Services_WTF_Test();
				$test->api_username( $aj_gtmetrix_username );
        		$test->api_password( $aj_gtmetrix_api_key );
        		$test->user_agent( AJ_UA );
				$args = array(
					'url' => $site_url,
					'location' => $aj_gtmetrix_server
				);
				$testid = $test->test( $args );
				if ( $testid ) {
					$test->get_results();
					if ( $test->error() ) {
					    $return = array(
							'status' => false,
							'error' => $test->error()
						);
					} else {
						$testid = $test->get_test_id();
						$results = $test->results();
						$resources = $test->resources();
						$status = $test->status();
						$credits = $status['api_credits'];
						$return = array(
							'status' => true,
							'testid' => $testid,
							'results' => $results,
							'resources' => $resources,
							'id' => $sub_action,
							'name' => 'Baseline',
							'url' => $args['url'],
							'credits' => $credits
						);
						$aj_gtmetrix_results[$sub_action] = $return;
						update_option( 'aj_gtmetrix_results', $aj_gtmetrix_results );
		    		}
				} else {
					$return = array(
						'status' => false,
						'error' => $test->error()
					);
				}
				break;
			case 'aj_step2b':
				$aj_gtmetrix_username = sanitize_text_field( $_POST['aj_gtmetrix_username'] );
				$aj_gtmetrix_api_key = sanitize_text_field( $_POST['aj_gtmetrix_api_key'] );
				$aj_gtmetrix_server = sanitize_text_field( $_POST['aj_gtmetrix_server'] );
				$site_url = trailingslashit( esc_url( $_POST['site_url'] ) );
				update_option( 'aj_gtmetrix_username', $aj_gtmetrix_username );
				update_option( 'aj_gtmetrix_api_key', $aj_gtmetrix_api_key );
				update_option( 'aj_gtmetrix_server', $aj_gtmetrix_server );
				$test = new Services_WTF_Test();
				$test->api_username( $aj_gtmetrix_username );
        		$test->api_password( $aj_gtmetrix_api_key );
        		$test->user_agent( AJ_UA );
				$args = array(
					'url' => $site_url . '?aj_simulate=async',
					'location' => $aj_gtmetrix_server
				);
				$testid = $test->test( $args );
				if ( $testid ) {
					$test->get_results();
					if ( $test->error() ) {
					    $return = array(
							'status' => false,
							'error' => $test->error()
						);
					} else {
						$testid = $test->get_test_id();
						$results = $test->results();
						$resources = $test->resources();
						$status = $test->status();
						$credits = $status['api_credits'];
						$return = array(
							'status' => true,
							'testid' => $testid,
							'results' => $results,
							'resources' => $resources,
							'id' => $sub_action,
							'name' => 'Async',
							'url' => $args['url'],
							'credits' => $credits
						);
						$aj_gtmetrix_results[$sub_action] = $return;
						update_option( 'aj_gtmetrix_results', $aj_gtmetrix_results );
		    		}
				} else {
					$return = array(
						'status' => false,
						'error' => $test->error()
					);
				}
				break;
			case 'aj_step2c':
				$aj_gtmetrix_username = sanitize_text_field( $_POST['aj_gtmetrix_username'] );
				$aj_gtmetrix_api_key = sanitize_text_field( $_POST['aj_gtmetrix_api_key'] );
				$aj_gtmetrix_server = sanitize_text_field( $_POST['aj_gtmetrix_server'] );
				$site_url = trailingslashit( esc_url( $_POST['site_url'] ) );
				update_option( 'aj_gtmetrix_username', $aj_gtmetrix_username );
				update_option( 'aj_gtmetrix_api_key', $aj_gtmetrix_api_key );
				update_option( 'aj_gtmetrix_server', $aj_gtmetrix_server );
				$test = new Services_WTF_Test();
				$test->api_username( $aj_gtmetrix_username );
        		$test->api_password( $aj_gtmetrix_api_key );
        		$test->user_agent( AJ_UA );
				$args = array(
					'url' => $site_url . '?aj_simulate=defer',
					'location' => $aj_gtmetrix_server
				);
				$testid = $test->test( $args );
				if ( $testid ) {
					$test->get_results();
					if ( $test->error() ) {
					    $return = array(
							'status' => false,
							'error' => $test->error()
						);
					} else {
						$testid = $test->get_test_id();
						$results = $test->results();
						$resources = $test->resources();
						$status = $test->status();
						$credits = $status['api_credits'];
						$return = array(
							'status' => true,
							'testid' => $testid,
							'results' => $results,
							'resources' => $resources,
							'id' => $sub_action,
							'name' => 'Defer',
							'url' => $args['url'],
							'credits' => $credits
						);
						$aj_gtmetrix_results[$sub_action] = $return;
						update_option( 'aj_gtmetrix_results', $aj_gtmetrix_results );
		    		}
				} else {
					$return = array(
						'status' => false,
						'error' => $test->error()
					);
				}
				break;
			case 'aj_step2d':
				$aj_gtmetrix_username = sanitize_text_field( $_POST['aj_gtmetrix_username'] );
				$aj_gtmetrix_api_key = sanitize_text_field( $_POST['aj_gtmetrix_api_key'] );
				$aj_gtmetrix_server = sanitize_text_field( $_POST['aj_gtmetrix_server'] );
				$site_url = trailingslashit( esc_url( $_POST['site_url'] ) );
				update_option( 'aj_gtmetrix_username', $aj_gtmetrix_username );
				update_option( 'aj_gtmetrix_api_key', $aj_gtmetrix_api_key );
				update_option( 'aj_gtmetrix_server', $aj_gtmetrix_server );
				$test = new Services_WTF_Test();
				$test->api_username( $aj_gtmetrix_username );
        		$test->api_password( $aj_gtmetrix_api_key );
        		$test->user_agent( AJ_UA );
				$args = array(
					'url' => $site_url . '?aj_simulate=async&aj_simulate_jquery=exclude',
					'location' => $aj_gtmetrix_server
				);
				$testid = $test->test( $args );
				if ( $testid ) {
					$test->get_results();
					if ( $test->error() ) {
					    $return = array(
							'status' => false,
							'error' => $test->error()
						);
					} else {
						$testid = $test->get_test_id();
						$results = $test->results();
						$resources = $test->resources();
						$status = $test->status();
						$credits = $status['api_credits'];
						$return = array(
							'status' => true,
							'testid' => $testid,
							'results' => $results,
							'resources' => $resources,
							'id' => $sub_action,
							'name' => 'Async (jQuery Excluded)',
							'url' => $args['url'],
							'credits' => $credits
						);
						$aj_gtmetrix_results[$sub_action] = $return;
						update_option( 'aj_gtmetrix_results', $aj_gtmetrix_results );
		    		}
				} else {
					$return = array(
						'status' => false,
						'error' => $test->error()
					);
				}
				break;
			case 'aj_step2e':
				$aj_gtmetrix_username = sanitize_text_field( $_POST['aj_gtmetrix_username'] );
				$aj_gtmetrix_api_key = sanitize_text_field( $_POST['aj_gtmetrix_api_key'] );
				$aj_gtmetrix_server = sanitize_text_field( $_POST['aj_gtmetrix_server'] );
				$site_url = trailingslashit( esc_url( $_POST['site_url'] ) );
				update_option( 'aj_gtmetrix_username', $aj_gtmetrix_username );
				update_option( 'aj_gtmetrix_api_key', $aj_gtmetrix_api_key );
				update_option( 'aj_gtmetrix_server', $aj_gtmetrix_server );
				$test = new Services_WTF_Test();
				$test->api_username( $aj_gtmetrix_username );
        		$test->api_password( $aj_gtmetrix_api_key );
        		$test->user_agent( AJ_UA );
				$args = array(
					'url' => $site_url . '?aj_simulate=defer&aj_simulate_jquery=exclude',
					'location' => $aj_gtmetrix_server
				);
				$testid = $test->test( $args );
				if ( $testid ) {
					$test->get_results();
					if ( $test->error() ) {
					    $return = array(
							'status' => false,
							'error' => $test->error()
						);
					} else {
						$testid = $test->get_test_id();
						$results = $test->results();
						$resources = $test->resources();
						$status = $test->status();
						$credits = $status['api_credits'];
						$return = array(
							'status' => true,
							'testid' => $testid,
							'results' => $results,
							'resources' => $resources,
							'id' => $sub_action,
							'name' => 'Defer (jQuery Excluded)',
							'url' => $args['url'],
							'credits' => $credits
						);
						$aj_gtmetrix_results[$sub_action] = $return;
						update_option( 'aj_gtmetrix_results', $aj_gtmetrix_results );
		    		}
				} else {
					$return = array(
						'status' => false,
						'error' => $test->error()
					);
				}
				break;
			case 'aj_step_results':
				$best_pagespeed = 0;
				$best_yslow = 0;
				$best_overall = 0;
				$best_result = array();
				$baseline = $aj_gtmetrix_results['aj_step2'];
				foreach ( $aj_gtmetrix_results as $aj_step => $aj_gtmetrix_result ) {
					if ( $aj_step != 'aj_step2' ) {
						$pagespeed = $aj_gtmetrix_result['results']['pagespeed_score'];
						$yslow = $aj_gtmetrix_result['results']['yslow_score'];
						$combined = $pagespeed + $yslow;
						if ( $combined > $best_overall ) {
							$best_overall = $combined;
							$best_result = $aj_gtmetrix_result;
						}
					}
				}
				if ( !empty( $best_result ) ) {
					$return = $best_result;
					$return['status'] = true;
					$return['baseline_pagespeed'] = $baseline['results']['pagespeed_score'];
					$return['baseline_yslow'] = $baseline['results']['yslow_score'];
					$aj_gtmetrix_results['best_result'] = $return;
					update_option( 'aj_gtmetrix_results', $aj_gtmetrix_results );
				} else {
					$return = array(
						'status' => false,
						'error' => 'No detected increase'
					);
				}
				break;
			case 'aj_apply_settings':
				$settings = sanitize_text_field( $_POST['settings'] );
				if ( $settings != '' ) {
					$best_id = $settings;
				} else {
					$best_result = $aj_gtmetrix_results['best_result'];
					$best_id = $best_result['id'];
				}
				update_option( 'aj_enabled', 1 );
				if ( $best_id == 'aj_step2b' || $best_id == 'aj_step2d' ) {
					update_option( 'aj_method', 'async' );
				} else if ( $best_id == 'aj_step2c' || $best_id == 'aj_step2e' ) {
					update_option( 'aj_method', 'defer' );
				}
				if ( $best_id == 'aj_step2b' ) {
					update_option( 'aj_jquery', 'async' );
				} else if ( $best_id == 'aj_step2d' ) {
					update_option( 'aj_jquery', 'defer' );
				} else if ( $best_id == 'aj_step2c' || $best_id == 'aj_step2e' ) {
					update_option( 'aj_jquery', 'exclude' );
				}
				update_option( 'aj_exclusions', '' );
				$return['status'] = true;
				break;
			case 'aj_gtmetrix_test':
				$aj_gtmetrix_username = sanitize_text_field( $_POST['aj_gtmetrix_username'] );
				$aj_gtmetrix_api_key = sanitize_text_field( $_POST['aj_gtmetrix_api_key'] );
				$aj_gtmetrix_server = sanitize_text_field( $_POST['aj_gtmetrix_server'] );
				$site_url = trailingslashit( esc_url( $_POST['site_url'] ) );
				update_option( 'aj_gtmetrix_username', $aj_gtmetrix_username );
				update_option( 'aj_gtmetrix_api_key', $aj_gtmetrix_api_key );
				update_option( 'aj_gtmetrix_server', $aj_gtmetrix_server );
				$test = new Services_WTF_Test();
				$test->api_username( $aj_gtmetrix_username );
        		$test->api_password( $aj_gtmetrix_api_key );
        		$test->user_agent( AJ_UA );
				$args = array(
					'url' => $site_url,
					'location' => $aj_gtmetrix_server
				);
				$testid = $test->test( $args );
				if ( $testid ) {
					$test->get_results();
					if ( $test->error() ) {
					    $return = array(
							'status' => false,
							'error' => $test->error()
						);
					} else {
						$testid = $test->get_test_id();
						$results = $test->results();
						$resources = $test->resources();
						$screenshot = base64_encode( file_get_contents( $results['report_url'] . '/screenshot.jpg' ) );
						$status = $test->status();
						$credits = $status['api_credits'];
						$return = array(
							'status' => true,
							'testid' => $testid,
							'results' => $results,
							'resources' => $resources,
							'id' => $sub_action,
							'name' => 'Latest',
							'url' => $args['url'],
							'credits' => $credits,
							'screenshot' => $screenshot
						);
						$aj_gtmetrix_results['latest'] = $return;
						update_option( 'aj_gtmetrix_results', $aj_gtmetrix_results );
		    		}
				} else {
					$return = array(
						'status' => false,
						'error' => $test->error()
					);
				}
				break;
			case 'aj_save_settings':
				$aj_enabled = sanitize_text_field( $_POST['aj_enabled'] );
		        $aj_method = sanitize_text_field( $_POST['aj_method'] );
				$aj_jquery = sanitize_text_field( $_POST['aj_jquery'] );
				$aj_exclusions = sanitize_text_field( $_POST['aj_exclusions'] );
				$aj_autoptimize_enabled = sanitize_text_field( $_POST['aj_autoptimize_enabled'] );
				$aj_autoptimize_method = sanitize_text_field( $_POST['aj_autoptimize_method'] );
				update_option( 'aj_enabled', $aj_enabled );
				update_option( 'aj_method', $aj_method );
				update_option( 'aj_jquery', $aj_jquery );
				update_option( 'aj_exclusions', $aj_exclusions );
				update_option( 'aj_autoptimize_enabled', $aj_autoptimize_enabled );
				update_option( 'aj_autoptimize_method', $aj_autoptimize_method );
				$return['status'] = true;
				break;
		}
	}
	if( is_null( $return ) ) {
		$return = array(
			'status' => false
		);
	}
	echo json_encode( $return );
	wp_die();
}
add_action( 'wp_ajax_aj_steps', 'aj_steps' );