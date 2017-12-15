		<footer>
			<div class="karma_navigation">
				<div class="pageCenter">
					<?php wp_nav_menu(array('theme_location' => 'bottom_menu_slot')); ?>
					<hr />
					<!-- RS: added copyright //-->
                    <div style="float: left; width: 100%; clear: both;">
					<div class="copyright">
						Karma Automotive™ 2017
					</div>
					<div class="socialNetworkIcons">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("social_button_panel") ) : ?><?php endif;?>
					</div>
                    </div>
				</div>
			</div>
		</footer>

		<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.flexslider-min.js" type="text/javascript"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/custom_scripts.js" type="text/javascript"></script>
		<?php wp_footer(); ?>
	</body>
</html>

