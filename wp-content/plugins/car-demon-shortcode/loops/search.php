<?php
/*================================================================*/
// Handle vehicle search
/*================================================================*/
// Handle Vehicle Searches
//add_action("template_redirect", 'cdt_theme_redirect');
function cdt_theme_redirect() {
	global $wp_query, $cd_template;
	//= If a Car Demon search is performed then redirect it so we can get the archive template
	if (is_search()) {
		if ($_GET['s']=='cars') {
			global $wp;
			$current_url = cdt_getCurrentURL();
			$new_url = str_replace('s=cars', 's=&post_type=cars_for_sale', $current_url);
			header("Location: ".$new_url);
			exit();
		}
	}
	//= If the Car Search is set in the QueryString then load the archive template
	if (isset($_GET['car'])) {
		if ($_GET['car']==1) {
			$dir_path = plugin_dir_path( __FILE__ );
			$dir_path = str_replace('car-demon-shortcode/', '', $dir_path);
			$dir_path = str_replace('car-demon-shortcode\\', '', $dir_path);
			
			$dir_path = str_replace('loops/', '', $dir_path);
			$dir_path = str_replace('loops\\', '', $dir_path);
			
			$template_directory = $dir_path.'/car-demon-shortcode/theme-files/'.$cd_template;
			$templatefilename = 'archive-cars_for_sale.php';
			$return_template = $template_directory . '/' . $templatefilename;
			if (file_exists($template_directory . '/' . $templatefilename)) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				//= load index if no single or archive is found
				$templatefilename = 'index.php';
				$return_template = get_template_directory() . '/' . $templatefilename;
			}
			header('HTTP/1.1 200 OK');
			$wp_query->is_404 = false;
			$cdsp_query = car_demon_query_search();
			query_posts($cdsp_query);
			include($return_template);
			die();
		}
	}
}
function cdt_getCurrentURL() {
	$protocol = "http";
	if($_SERVER["SERVER_PORT"]==443 || (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]==”on”)) {
		$protocol .= "s";
		$protocol_port = $_SERVER["SERVER_PORT"];
	} else {
		$protocol_port = 80;
	}
	$host = $_SERVER["HTTP_HOST"];
	$port = $_SERVER["SERVER_PORT"];
	$request_path = $_SERVER["PHP_SELF"];
	$querystr = $_SERVER["QUERY_STRING"];
	$url = $protocol."://".$host.(($port!=$protcol_port && strpos($host,":")==-1)?":".$port:"").$request_path.(empty($querystr)?'':'?'.$querystr);
	return $url;
}
?>