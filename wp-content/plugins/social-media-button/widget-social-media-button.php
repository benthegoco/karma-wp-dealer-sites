<?php
class SMButtonsWidget extends WP_Widget {

	public function __construct() {
        parent::__construct(
            'sm_buttons_widget',
            __('Social Media Buttons', 'smbuttons' ),
            array( 'description' => __( 'A widget that displays various social media button at Wordpress widgets area.', 'smbuttons' ), )
        );
    }// end constructor

	function widget( $args, $instance ) {
		// Widget output
	    extract( $args );
	 
	    /* Our variables from the widget settings. */
	    $widget_title = apply_filters('widget_title', $instance['title'] );

	    $fb_url 		= 	$instance['fb_url'];
	    $gp_url 		=	$instance['gp_url'];
	    $in_url 		=	$instance['in_url'];
	    $t_url 			=	$instance['t_url'];
	    $y_url 			=	$instance['y_url'];
	    $pin_url 		=	$instance['pin_url'];
	    $git_url 		=	$instance['git_url'];
	    $codepen 		=	$instance['codepen'];
	    $digg 			=	$instance['digg'];
	    $dribbble 		=	$instance['dribbble'];
	    $dropbox 		=	$instance['dropbox'];
	    $flickr 		=	$instance['flickr'];
	    $foursquare 	=	$instance['foursquare'];
	    $instagram 		=	$instance['instagram'];
	    $reddit 		=	$instance['reddit'];
	    $vimeo 			=	$instance['vimeo'];
	    $wordpress 		=	$instance['wordpress'];
	    $email 			=	$instance['email'];

	    $icon_color 			=	$instance['icon_color'];
	    $icon_hover_color 		=	$instance['icon_hover_color'];
	    $icon_bg_color 			=	$instance['icon_bg_color'];
	    $icon_hover_bg_color 	=	$instance['icon_hover_bg_color'];

	    $widget_id = uniqid();

	   	//Display Sidebar
	   	echo $before_widget;

		if ( ! empty( $widget_title ) )
			echo $before_title . $widget_title . $after_title;
	    ?>
		    <style>
				.smb-<?php echo $widget_id; ?> { margin: 0px 0; }
				.smb-<?php echo $widget_id; ?> a {
					background-color: <?php echo $icon_bg_color; ?>;
					border-radius: 0 none;
					display: inline-block;
					padding: 5px;
					margin-bottom: 3px;
				}
				.smb-<?php echo $widget_id; ?> a:hover {
					background-color: <?php echo $icon_hover_bg_color; ?>;
					-webkit-transition: all 1s;
					transition: all 1s;
				}
				.smb-<?php echo $widget_id; ?> a:hover svg {
					fill: <?php echo $icon_hover_color; ?>;
					-webkit-transition: all 1s;
					transition: all 1s;
				}
				.smb-<?php echo $widget_id; ?> svg {
					fill: <?php echo $icon_color; ?>;
					vertical-align: middle;
				}
			</style>
	        <div class="smb-<?php echo $widget_id; ?>">
	        	<?php if ( !empty($fb_url) ): ?>
	        		<a class="facebook" title="Facebook" target="_blank" href="<?php echo esc_url($fb_url) ?>">
						<img src="<?php print(get_template_directory_uri()); ?>/images/facebook.png"></img>
	        		</a>
	        	<?php endif; ?>

	        	<?php if ( !empty($gp_url) ): ?>
	        		<a title="Google+" target="_blank" href="<?php echo esc_url($gp_url); ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 36 28"><path d="M22.5 14.3c0 6.5-4.4 11.2-11 11.2-6.3 0-11.5-5.1-11.5-11.5s5.1-11.5 11.5-11.5c3.1 0 5.7 1.1 7.7 3l-3.1 3c-0.8-0.8-2.3-1.8-4.6-1.8-3.9 0-7.1 3.2-7.1 7.2s3.2 7.2 7.1 7.2c4.5 0 6.2-3.3 6.5-4.9h-6.5v-3.9h10.8c0.1 0.6 0.2 1.2 0.2 1.9zM36 12.4v3.3h-3.3v3.3h-3.3v-3.3h-3.3v-3.3h3.3v-3.3h3.3v3.3h3.3z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($in_url) ): ?>
	        		<a title="LinkedIn" target="_blank" href="<?php echo esc_url($in_url); ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 28"><path d="M5.5 9.8v15.5h-5.2v-15.5h5.2zM5.8 5c0 1.5-1.1 2.7-2.9 2.7v0h0c-1.7 0-2.8-1.2-2.8-2.7 0-1.5 1.2-2.7 2.9-2.7 1.8 0 2.9 1.2 2.9 2.7zM24 16.4v8.9h-5.1v-8.3c0-2.1-0.7-3.5-2.6-3.5-1.4 0-2.3 1-2.6 1.9-0.1 0.3-0.2 0.8-0.2 1.3v8.6h-5.1c0.1-14 0-15.5 0-15.5h5.1v2.3h0c0.7-1.1 1.9-2.6 4.7-2.6 3.4 0 5.9 2.2 5.9 7z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($t_url) ): ?>
	        		<a title="Twitter" target="_blank" href="<?php echo esc_url($t_url); ?>">
						<img src="<?php print(get_template_directory_uri()); ?>/images/twitter.png"></img>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($y_url) ): ?>
	        		<a title="Youtube" target="_blank" href="<?php echo esc_url($y_url); ?>">
						<img src="<?php print(get_template_directory_uri()); ?>/images/youtube.png"></img>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($pin_url) ): ?>
	        		<a title="Pinterest" target="_blank" href="<?php echo esc_url($pin_url); ?>">
						<img src="<?php print(get_template_directory_uri()); ?>/images/pinterest.png"></img>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($git_url) ): ?>
	        		<a title="GitHub" target="_blank" href="<?php echo esc_url($git_url); ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 28"><path d="M12 2c6.6 0 12 5.4 12 12 0 5.3-3.4 9.8-8.2 11.4-0.6 0.1-0.8-0.3-0.8-0.6 0-0.4 0-1.7 0-3.3 0-1.1-0.4-1.8-0.8-2.2 2.7-0.3 5.5-1.3 5.5-5.9 0-1.3-0.5-2.4-1.2-3.2 0.1-0.3 0.5-1.5-0.1-3.2-1-0.3-3.3 1.2-3.3 1.2-1-0.3-2-0.4-3-0.4s-2 0.1-3 0.4c0 0-2.3-1.5-3.3-1.2-0.7 1.7-0.2 2.9-0.1 3.2-0.8 0.8-1.2 1.9-1.2 3.2 0 4.6 2.8 5.6 5.5 5.9-0.3 0.3-0.7 0.8-0.8 1.6-0.7 0.3-2.4 0.8-3.5-1-0.7-1.1-1.8-1.2-1.8-1.2-1.2 0-0.1 0.7-0.1 0.7 0.8 0.4 1.3 1.8 1.3 1.8 0.7 2.1 4 1.4 4 1.4 0 1 0 1.9 0 2.2 0 0.3-0.2 0.7-0.8 0.6-4.8-1.6-8.2-6.1-8.2-11.4 0-6.6 5.4-12 12-12zM4.5 19.2c0-0.1 0-0.1-0.1-0.2-0.1 0-0.2 0-0.2 0 0 0.1 0 0.1 0.1 0.2 0.1 0 0.2 0 0.2 0zM5 19.8c0.1 0 0-0.2 0-0.2-0.1-0.1-0.2-0.1-0.2 0-0.1 0 0 0.2 0 0.3 0.1 0.1 0.2 0.1 0.3 0zM5.5 20.5c0.1-0.1 0.1-0.2 0-0.3-0.1-0.1-0.2-0.2-0.3-0.1-0.1 0-0.1 0.2 0 0.3s0.2 0.2 0.3 0.1zM6.2 21.1c0.1-0.1 0-0.2-0.1-0.3-0.1-0.1-0.2-0.1-0.3 0-0.1 0.1 0 0.2 0.1 0.3 0.1 0.1 0.3 0.1 0.3 0zM7 21.5c0-0.1-0.1-0.2-0.2-0.2-0.1 0-0.3 0-0.3 0.1s0.1 0.2 0.2 0.2c0.1 0 0.3 0 0.3-0.1zM8 21.6c0-0.1-0.1-0.2-0.3-0.2-0.1 0-0.2 0.1-0.2 0.2 0 0.1 0.1 0.2 0.3 0.2 0.1 0 0.3-0.1 0.3-0.2zM8.9 21.4c0-0.1-0.1-0.2-0.3-0.1-0.1 0-0.2 0.1-0.2 0.2 0 0.1 0.1 0.2 0.3 0.1s0.2-0.1 0.2-0.2z"/></svg>
	        		</a>
				<?php endif; ?>
				<!-- New Options-->
	        	<?php if ( !empty($codepen) ): ?>
	        		<a title="Codepen" target="_blank" href="<?php echo esc_url($codepen); ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 28 28"><path d="M3.4 18.3l9.4 6.3v-5.6l-5.2-3.5zM2.4 16l3-2-3-2v4zM15.2 24.5l9.4-6.3-4.2-2.8-5.2 3.5v5.6zM14 16.8l4.3-2.8-4.2-2.8-4.2 2.8zM7.6 12.5l5.2-3.5v-5.6l-9.4 6.3zM22.6 14l3 2v-4zM20.4 12.5l4.2-2.8-9.4-6.3v5.6zM28 9.7v8.5c0 0.4-0.2 0.8-0.5 1l-12.8 8.5c-0.2 0.1-0.4 0.2-0.7 0.2s-0.5-0.1-0.7-0.2l-12.8-8.5c-0.3-0.2-0.5-0.6-0.5-1v-8.5c0-0.4 0.2-0.8 0.5-1l12.8-8.5c0.2-0.1 0.4-0.2 0.7-0.2s0.5 0.1 0.7 0.2l12.8 8.5c0.3 0.2 0.5 0.6 0.5 1z"/></svg>
	        		</a>
				<?php endif; ?>
				
	        	<?php if ( !empty($digg) ): ?>
	        		<a title="Digg" target="_blank" href="<?php echo esc_url($digg); ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 28"><path d="M5.1 4.4h3.2v15.4h-8.3v-10.9h5.1v-4.5zM5.1 17.2v-5.8h-1.9v5.8h1.9zM9.6 8.9v10.9h3.2v-10.9h-3.2zM9.6 4.4v3.2h3.2v-3.2h-3.2zM14.1 8.9h8.3v14.7h-8.3v-2.5h5.1v-1.3h-5.1v-10.9zM19.2 17.2v-5.8h-1.9v5.8h1.9zM23.7 8.9h8.3v14.7h-8.3v-2.5h5.1v-1.3h-5.1v-10.9zM28.8 17.2v-5.8h-1.9v5.8h1.9z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($dribbble) ): ?>
	        		<a title="Dribbble" target="_blank" href="<?php echo esc_url($dribbble); ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 28"><path d="M16 23.4c-0.2-0.9-0.7-4-2.2-7.8 0 0 0 0-0.1 0 0 0-6.1 2.1-8 6.4-0.1-0.1-0.2-0.2-0.2-0.2 1.8 1.5 4 2.3 6.5 2.3 1.4 0 2.8-0.3 4-0.8zM13.1 14c-0.2-0.6-0.5-1.2-0.8-1.7-5.3 1.6-10.3 1.5-10.5 1.5 0 0.1 0 0.2 0 0.3 0 2.6 1 5 2.6 6.8v0c2.8-5 8.3-6.8 8.3-6.8 0.1 0 0.3-0.1 0.4-0.1zM11.4 10.6c-1.8-3.2-3.7-5.7-3.8-5.9-2.9 1.3-5 4-5.7 7.2 0.3 0 4.5 0 9.5-1.2zM22.1 15.6c-0.2-0.1-3.1-1-6.4-0.5 1.3 3.7 1.9 6.7 2 7.3 2.3-1.5 3.9-4 4.4-6.9zM9.5 4c0 0 0 0 0 0 0 0 0 0 0 0zM18.8 6.3c-1.8-1.6-4.2-2.6-6.8-2.6-0.8 0-1.6 0.1-2.4 0.3 0.2 0.2 2.1 2.8 3.8 6 3.9-1.4 5.3-3.7 5.3-3.7zM22.3 13.9c0-2.4-0.9-4.7-2.3-6.4 0 0-1.7 2.4-5.7 4.1 0.2 0.5 0.5 1 0.7 1.5 0.1 0.2 0.1 0.4 0.2 0.5 3.5-0.5 7 0.3 7.1 0.3zM24 14c0 6.6-5.4 12-12 12s-12-5.4-12-12 5.4-12 12-12 12 5.4 12 12z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($dropbox) ): ?>
	        		<a title="Dropbox" target="_blank" href="<?php echo esc_url($dropbox); ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 28 28"><path d="M6.3 11l7.7 4.8-5.3 4.5-7.7-5zM21.7 19.7v1.7l-7.7 4.6v0l0 0 0 0v0l-7.6-4.6v-1.7l2.3 1.5 5.3-4.4v0l0 0 0 0v0l5.4 4.4zM8.7 1.8l5.3 4.5-7.7 4.8-5.3-4.2zM21.7 11l5.3 4.2-7.6 5-5.4-4.5zM19.4 1.8l7.6 5-5.3 4.2-7.7-4.7z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($flickr) ): ?>
	        		<a title="Flickr" target="_blank" href="<?php echo esc_url($flickr); ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 28"><path d="M19.5 2c2.5 0 4.5 2 4.5 4.5v15c0 2.5-2 4.5-4.5 4.5h-15c-2.5 0-4.5-2-4.5-4.5v-15c0-2.5 2-4.5 4.5-4.5h15zM10.9 14c0-1.8-1.5-3.3-3.3-3.3s-3.3 1.5-3.3 3.3 1.5 3.3 3.3 3.3 3.3-1.5 3.3-3.3zM19.7 14c0-1.8-1.5-3.3-3.3-3.3s-3.3 1.5-3.3 3.3 1.5 3.3 3.3 3.3 3.3-1.5 3.3-3.3z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($foursquare) ): ?>
	        		<a title="Foursquare" target="_blank" href="<?php echo esc_url($foursquare) ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 20 28"><path d="M15.6 6.8l0.6-3c0.1-0.5-0.3-0.9-0.7-0.9h-11.1c-0.5 0-0.8 0.5-0.8 0.8v17.2c0 0 0 0.1 0.1 0 4.1-4.9 4.5-5.5 4.5-5.5 0.5-0.5 0.7-0.6 1.3-0.6h3.7c0.5 0 0.8-0.4 0.9-0.7s0.5-2.5 0.6-3-0.3-0.9-0.7-0.9h-4.6c-0.6 0-1-0.4-1-1v-0.7c0-0.6 0.4-1 1-1h5.4c0.4 0 0.8-0.3 0.9-0.7zM19.2 3.3c-0.6 2.8-2.3 11.7-2.5 12.3-0.2 0.7-0.5 2-2.2 2h-4.2c-0.2 0-0.2 0-0.3 0.2 0 0-0.1 0.1-6.7 7.7-0.5 0.6-1.4 0.5-1.7 0.4s-0.9-0.5-0.9-1.5v-22c0-0.9 0.6-2.3 2.5-2.3h13.9c2 0 2.6 1.2 2.1 3.3zM19.2 3.3l-2.5 12.3c0.2-0.6 1.9-9.5 2.5-12.3z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($instagram) ): ?>
	        		<a title="Instagram" target="_blank" href="<?php echo esc_url($instagram) ?>">
                        <img src="<?php print(get_template_directory_uri()); ?>/images/instagram.png"></img>	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($reddit) ): ?>
	        		<a title="Reddit" target="_blank" href="<?php echo esc_url($reddit) ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 28 28"><path d="M28 13.2c0 1.2-0.7 2.3-1.7 2.8 0.1 0.5 0.2 1 0.2 1.5 0 4.9-5.6 8.9-12.5 8.9-6.9 0-12.4-4-12.4-8.9 0-0.5 0.1-1 0.2-1.5-1-0.5-1.8-1.6-1.8-2.8 0-1.7 1.4-3.1 3.1-3.1 0.9 0 1.7 0.4 2.3 1 2.1-1.5 4.9-2.4 8-2.5l1.8-8.1c0.1-0.3 0.4-0.5 0.6-0.4l5.8 1.3c0.4-0.7 1.2-1.3 2.1-1.3 1.3 0 2.3 1 2.3 2.3 0 1.3-1 2.3-2.3 2.3-1.3 0-2.3-1-2.3-2.3l-5.2-1.2-1.6 7.4c3.1 0.1 6 1 8.1 2.5 0.6-0.6 1.4-1 2.2-1 1.7 0 3.1 1.4 3.1 3.1zM6.5 16.3c0 1.3 1 2.3 2.3 2.3 1.3 0 2.3-1 2.3-2.3 0-1.3-1-2.3-2.3-2.3-1.3 0-2.3 1-2.3 2.3zM19.2 21.9c0.2-0.2 0.2-0.6 0-0.8-0.2-0.2-0.6-0.2-0.8 0-0.9 1-3 1.3-4.4 1.3s-3.5-0.3-4.4-1.3c-0.2-0.2-0.6-0.2-0.8 0-0.2 0.2-0.2 0.6 0 0.8 1.5 1.5 4.3 1.6 5.2 1.6s3.7-0.1 5.2-1.6zM19.1 18.7c1.3 0 2.3-1 2.3-2.3 0-1.3-1-2.3-2.3-2.3-1.3 0-2.3 1-2.3 2.3 0 1.3 1 2.3 2.3 2.3z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($vimeo) ): ?>
	        		<a title="Vimeo" target="_blank" href="<?php echo esc_url($vimeo) ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 28 28"><path d="M26.7 8.1c-0.1 2.5-1.8 5.9-5.2 10.2-3.5 4.5-6.4 6.7-8.8 6.7-1.5 0-2.7-1.4-3.7-4.1-0.7-2.5-1.4-5-2.1-7.5-0.7-2.7-1.6-4.1-2.5-4.1-0.2 0-0.8 0.4-2 1.2l-1.2-1.5c1.3-1.1 2.5-2.2 3.7-3.3 1.7-1.5 2.9-2.2 3.8-2.3 2-0.2 3.2 1.2 3.7 4 0.5 3.1 0.8 5.1 1 5.8 0.6 2.6 1.2 3.9 1.9 3.9 0.5 0 1.3-0.8 2.4-2.5 1.1-1.7 1.6-3 1.7-3.8 0.1-1.5-0.4-2.2-1.7-2.2-0.6 0-1.2 0.1-1.9 0.4 1.3-4.1 3.6-6.1 7.2-6 2.6 0.1 3.8 1.8 3.7 5.1z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($wordpress) ): ?>
	        		<a title="WordPress" target="_blank" href="<?php echo esc_url($wordpress) ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 28 28"><path d="M2 14c0-1.7 0.4-3.4 1-4.9l5.7 15.7c-4-2-6.8-6.1-6.8-10.8zM22.1 13.4c0 1-0.4 2.2-0.9 3.9l-1.2 4-4.3-12.9s0.7 0 1.4-0.1c0.6-0.1 0.6-1-0.1-1-2 0.1-3.2 0.2-3.2 0.2s-1.2 0-3.2-0.2c-0.7 0-0.7 0.9-0.1 1 0.6 0.1 1.3 0.1 1.3 0.1l1.9 5.1-2.6 7.9-4.4-13s0.7 0 1.4-0.1c0.6-0.1 0.6-1-0.1-1-1.9 0.1-3.2 0.2-3.2 0.2-0.2 0-0.5 0-0.8 0 2.1-3.3 5.8-5.4 10-5.4 3.1 0 6 1.2 8.1 3.2h-0.2c-1.2 0-2 1-2 2.1 0 1 0.6 1.8 1.2 2.8 0.5 0.8 1 1.8 1 3.3zM14.2 15l3.7 10.1c0 0.1 0 0.1 0.1 0.2-1.2 0.4-2.6 0.7-4 0.7-1.2 0-2.3-0.2-3.4-0.5zM24.5 8.2c0.9 1.7 1.5 3.7 1.5 5.8 0 4.4-2.4 8.3-6 10.4l3.7-10.6c0.6-1.7 0.9-3.1 0.9-4.3 0-0.4 0-0.8-0.1-1.2zM14 0c7.7 0 14 6.3 14 14s-6.3 14-14 14-14-6.3-14-14 6.3-14 14-14zM14 27.4c7.4 0 13.4-6 13.4-13.4s-6-13.4-13.4-13.4-13.4 6-13.4 13.4 6 13.4 13.4 13.4z"/></svg>
	        		</a>
				<?php endif; ?>

	        	<?php if ( !empty($email) ): ?>
	        		<a href="mailto:<?php echo $email ?>">
	        			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 28 28"><path d="M26 23.5v-12c-0.3 0.4-0.7 0.7-1.1 1-2.2 1.7-4.5 3.5-6.7 5.3-1.2 1-2.6 2.2-4.2 2.2h0c-1.6 0-3.1-1.2-4.2-2.2-2.2-1.8-4.4-3.6-6.7-5.3-0.4-0.3-0.7-0.7-1.1-1v12c0 0.3 0.2 0.5 0.5 0.5h23c0.3 0 0.5-0.2 0.5-0.5zM26 7.1c0-0.4 0.1-1.1-0.5-1.1h-23c-0.3 0-0.5 0.2-0.5 0.5 0 1.8 0.9 3.3 2.3 4.4 2.1 1.6 4.2 3.3 6.3 5 0.8 0.7 2.3 2.1 3.4 2.1h0c1.1 0 2.6-1.4 3.4-2.1 2.1-1.7 4.2-3.3 6.3-5 1-0.8 2.3-2.5 2.3-3.9zM28 6.5v17c0 1.4-1.1 2.5-2.5 2.5h-23c-1.4 0-2.5-1.1-2.5-2.5v-17c0-1.4 1.1-2.5 2.5-2.5h23c1.4 0 2.5 1.1 2.5 2.5z"/></svg>
	        		</a>
				<?php endif; ?>
	        </div>
	    <?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
	    $instance = $old_instance;
	 
	    /* Strip tags for title and name to remove HTML (important for text inputs). */
	    $instance['title'] = strip_tags( $new_instance['title'] );
	    
	    $instance['fb_url'] = esc_url_raw( $new_instance['fb_url'] );
	    $instance['gp_url'] = esc_url_raw( $new_instance['gp_url'] );
	    $instance['in_url'] = esc_url_raw( $new_instance['in_url'] );
	    $instance['t_url'] = esc_url_raw( $new_instance['t_url'] );
	    $instance['y_url'] = esc_url_raw( $new_instance['y_url'] );
	    $instance['pin_url'] = esc_url_raw( $new_instance['pin_url'] );
	    $instance['git_url'] = esc_url_raw( $new_instance['git_url'] );

	    $instance['codepen'] = esc_url_raw( $new_instance['codepen'] );
	    $instance['digg'] = esc_url_raw( $new_instance['digg'] );
	    $instance['dribbble'] = esc_url_raw( $new_instance['dribbble'] );
	    $instance['dropbox'] = esc_url_raw( $new_instance['dropbox'] );
	    $instance['flickr'] = esc_url_raw( $new_instance['flickr'] );
	    $instance['foursquare'] = esc_url_raw( $new_instance['foursquare'] );
	    $instance['instagram'] = esc_url_raw( $new_instance['instagram'] );
	    $instance['reddit'] = esc_url_raw( $new_instance['reddit'] );
	    $instance['vimeo'] = esc_url_raw( $new_instance['vimeo'] );
	    $instance['wordpress'] = esc_url_raw( $new_instance['wordpress'] );

	    $instance['email'] = sanitize_email( $new_instance['email'] );

	    $instance['icon_color'] = sanitize_text_field( $new_instance['icon_color'] );
	    $instance['icon_hover_color'] = sanitize_text_field( $new_instance['icon_hover_color'] );
	    $instance['icon_bg_color'] = sanitize_text_field( $new_instance['icon_bg_color'] );
	    $instance['icon_hover_bg_color'] = sanitize_text_field( $new_instance['icon_hover_bg_color'] );

	    return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form	    
		/* Set up some default widget settings. */
	    $defaults = array(
	        'title' 				=> '',
	        'fb_url' 				=> '',
	        'gp_url' 				=> '',
	        'in_url' 				=> '',
	        't_url' 				=> '',
	        'y_url' 				=> '',
	        'pin_url' 				=> '',
	        'git_url' 				=> '',

	        'codepen' 				=> '',
	        'digg' 					=> '',
	        'dribbble' 				=> '',
	        'dropbox' 				=> '',
	        'flickr' 				=> '',
	        'foursquare' 			=> '',
	        'instagram' 			=> '',
	        'reddit' 				=> '',
	        'vimeo' 				=> '',
	        'wordpress' 			=> '',
	        'email' 				=> '',

	        'icon_color' 			=> '#000000',
	        'icon_hover_color' 		=> '#dddddd',
	        'icon_bg_color' 		=> '#00fd00',
	        'icon_hover_bg_color' 	=> '#ff0000',
	    );
	 
	    $instance = wp_parse_args( (array) $instance, $defaults );
		?>	    
		<!-- Widget Title: Text Input -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'smbuttons') ?></label>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	    </p>
		<!-- Show Facebook: Checkbox -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'fb_url' ); ?>"><?php _e('Facebook', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'fb_url' ); ?>" name="<?php echo $this->get_field_name( 'fb_url' ); ?>" value="<?php echo $instance['fb_url']; ?>" placeholder="http://facebook.com/name" />
	    </p>
		<!-- Show Google Plug: Checkbox -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'gp_url' ); ?>"><?php _e('Google Plus', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'gp_url' ); ?>" name="<?php echo $this->get_field_name( 'gp_url' ); ?>" value="<?php echo $instance['gp_url']; ?>" placeholder="https://plus.google.com/+userid" />
	    </p>
		<!-- Show LinkedIn: Checkbox -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'in_url' ); ?>"><?php _e('LinkedIn', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'in_url' ); ?>" name="<?php echo $this->get_field_name( 'in_url' ); ?>" value="<?php echo $instance['in_url']; ?>" placeholder="https://www.linkedin.com/in/user" />
	    </p>
		<!-- Show Twitter: Checkbox -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 't_url' ); ?>"><?php _e('Twitter', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 't_url' ); ?>" name="<?php echo $this->get_field_name( 't_url' ); ?>" value="<?php echo $instance['t_url']; ?>" placeholder="https://twitter.com/username" />
	    </p>
		<!-- Show Youtube: Checkbox -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'y_url' ); ?>"><?php _e('Youtube', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'y_url' ); ?>" name="<?php echo $this->get_field_name( 'y_url' ); ?>" value="<?php echo $instance['y_url']; ?>" placeholder="https://www.youtube.com/user/username" />
	    </p>
		<!-- Show Pinterest: Checkbox -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'pin_url' ); ?>"><?php _e('Pinterest', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'pin_url' ); ?>" name="<?php echo $this->get_field_name( 'pin_url' ); ?>" value="<?php echo $instance['pin_url']; ?>" placeholder="http://www.pinterest.com/username" />
	    </p>
		<!-- Show GitHub -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'git_url' ); ?>"><?php _e('GitHub', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'git_url' ); ?>" name="<?php echo $this->get_field_name( 'git_url' ); ?>" value="<?php echo $instance['git_url']; ?>" placeholder="https://github.com/username" />
	    </p>
		<!-- Show codepen -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'codepen' ); ?>"><?php _e('Codepen', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'codepen' ); ?>" name="<?php echo $this->get_field_name( 'codepen' ); ?>" value="<?php echo $instance['codepen']; ?>" placeholder="http://codepen.io/username" />
	    </p>
		<!-- Show digg -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'digg' ); ?>"><?php _e('digg', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'digg' ); ?>" name="<?php echo $this->get_field_name( 'digg' ); ?>" value="<?php echo $instance['digg']; ?>" placeholder="" />
	    </p>
		<!-- Show dribbble -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'dribbble' ); ?>"><?php _e('dribbble', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'dribbble' ); ?>" name="<?php echo $this->get_field_name( 'dribbble' ); ?>" value="<?php echo $instance['dribbble']; ?>" placeholder="http://dribbble.com/username" />
	    </p>
		<!-- Show dropbox -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'dropbox' ); ?>"><?php _e('dropbox', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'dropbox' ); ?>" name="<?php echo $this->get_field_name( 'dropbox' ); ?>" value="<?php echo $instance['dropbox']; ?>" placeholder="" />
	    </p>
		<!-- Show flickr -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'flickr' ); ?>"><?php _e('flickr', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'flickr' ); ?>" name="<?php echo $this->get_field_name( 'flickr' ); ?>" value="<?php echo $instance['flickr']; ?>" placeholder="http://www.flickr.com/photos/username" />
	    </p>
		<!-- Show foursquare -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'foursquare' ); ?>"><?php _e('foursquare', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'foursquare' ); ?>" name="<?php echo $this->get_field_name( 'foursquare' ); ?>" value="<?php echo $instance['foursquare']; ?>" placeholder="https://foursquare.com/username" />
	    </p>
		<!-- Show instagram -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php _e('instagram', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>" value="<?php echo $instance['instagram']; ?>" placeholder="http://instagram.com/username" />
	    </p>
		<!-- Show reddit -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'reddit' ); ?>"><?php _e('reddit', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'reddit' ); ?>" name="<?php echo $this->get_field_name( 'reddit' ); ?>" value="<?php echo $instance['reddit']; ?>" placeholder="" />
	    </p>
		<!-- Show vimeo -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'vimeo' ); ?>"><?php _e('vimeo', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'vimeo' ); ?>" name="<?php echo $this->get_field_name( 'vimeo' ); ?>" value="<?php echo $instance['vimeo']; ?>" placeholder="https://vimeo.com/username" />
	    </p>
		<!-- Show wordpress -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'wordpress' ); ?>"><?php _e('wordpress', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'wordpress' ); ?>" name="<?php echo $this->get_field_name( 'wordpress' ); ?>" value="<?php echo $instance['wordpress']; ?>" placeholder="https://profiles.wordpress.org/username" />
	    </p>
		<!-- Show email -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e('email', 'smbuttons') ?></label><br>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" placeholder="mail@example.com" />
	    </p>
		<!-- Show Icon Color: Checkbox -->
	    <p>
	    	<input type="text" class="icon_color" id="<?php echo $this->get_field_id( 'icon_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_color' ); ?>" value="<?php echo $instance['icon_color']; ?>" data-default-color="#000000">
	    	<label for="<?php echo $this->get_field_id( 'icon_color' ); ?>"><?php _e('Icon Color', 'smbuttons') ?></label>
	    </p>
		<!-- Show Icon Background Color: Checkbox -->
	    <p>	    
	    	<input type="text" class="icon_bg_color" id="<?php echo $this->get_field_id( 'icon_bg_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_bg_color' ); ?>" value="<?php echo $instance['icon_bg_color']; ?>" data-default-color="#00fd00">
	    	<label for="<?php echo $this->get_field_id( 'icon_bg_color' ); ?>"><?php _e('Icon Background Color', 'smbuttons') ?></label>
	    </p>
		<!-- Show Icon Color: Checkbox -->
	    <p>
	    	<input type="text" class="icon_hover_color" id="<?php echo $this->get_field_id( 'icon_hover_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_hover_color' ); ?>" value="<?php echo $instance['icon_hover_color']; ?>" data-default-color="#dddddd">
	    	<label for="<?php echo $this->get_field_id( 'icon_hover_color' ); ?>"><?php _e('Icon Color on Hover', 'smbuttons') ?></label>
	    </p>
		<!-- Show Icon Background Color: Checkbox -->
	    <p>	    
	    	<input type="text" class="icon_hover_bg_color" id="<?php echo $this->get_field_id( 'icon_hover_bg_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_hover_bg_color' ); ?>" value="<?php echo $instance['icon_hover_bg_color']; ?>" data-default-color="#ff0000">
	    	<label for="<?php echo $this->get_field_id( 'icon_hover_bg_color' ); ?>"><?php _e('Icon Background Color on Hover', 'smbuttons') ?></label>
	    </p>
	    <script type="text/javascript">
	    	jQuery(document).ready(function( $ ){
			    $('.icon_color').wpColorPicker();
			    $('.icon_bg_color').wpColorPicker();
			    $('.icon_hover_color').wpColorPicker();
			    $('.icon_hover_bg_color').wpColorPicker();
			});
	    </script>
	    <?php
	}
}

function social_media_button_widget() {
	register_widget( 'SMButtonsWidget' );
}

add_action( 'widgets_init', 'social_media_button_widget' );
