<html>
	<head>
		<title>Karma</title>
        <script>
            var siteURL = '<?php echo get_site_url()."/"; ?>';
        </script>

		<?php wp_head(); ?>
        <?php
        if(!empty(of_get_option('googletrackingcode'))){
        ?>
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo of_get_option('googletrackingcode'); ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?php echo of_get_option('googletrackingcode'); ?>');
            </script>

            <?php
        }
        ?>
        <base href="<?php echo get_site_url()."/"; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	</head>
	<body <?php body_class(); ?>>
		<header>
			<div class="pageCenter siteHeader">
				<div class="logoStyle" onclick="location.href='<?php echo get_site_url(); ?>'">
					<img id="logo-desktop" src="<?php print(of_get_option("mainlogo")); ?>"/>
					<img id="logo-mobile" src="<?php print(of_get_option("mainlogo_mobile")); ?>"/>
				</div>
				<div class="header-address">
					<div class="addressStyle"><?php print(of_get_option('header_address')); ?></div>
					<div class="pNumberStyle"><a href="tel:<?php print(of_get_option('phone_number')); ?>"><?php print(of_get_option('phone_number')); ?></a></div>
				</div>
			</div>
			<div class="karma_navigation">
				<?php wp_nav_menu(array('theme_location' => 'top_menu_slot')); ?>
			</div>

		</header>

