<?php
/**
 * Plugin Name: Easy Admin Menu
 * Plugin URI: http://wordpress.org/plugins/easy-admin-menu
 * Description: Reorder and/or Hide items in the Admin Menu
 * Version: 1.3
 * Author: Joaquín Ruiz
 * Author URI: http://jokiruiz.com
 * License: GPLv2
 *
 */

add_action('admin_menu', 'create_theme_easy_admin_menu');
function create_theme_easy_admin_menu() {
    add_options_page('Easy Admin Menu', 'Easy Admin Menu', 'administrator', 'easy_admin_menu_plugin', 'build_easy_admin_menu','dashicons-wordpress');
}

// Add settings link on plugin page
function your_plugin_easy_admin_menu_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=easy_admin_menu_plugin.php">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'your_plugin_easy_admin_menu_settings_link' );


function build_easy_admin_menu() {
    ?>
    <style>

        .easy-wysiwyg-style-head {
            color: #cdbfe3;
            text-shadow: 0 1px 0 rgba(0,0,0,.1);
            background-color: #6f5499;
        }
        .easy-wysiwyg-style-head h1 {
            color: #ffffff !important;
            font-family: HelveticaNeue, 'Helvetica Neue', Helvetica, Arial, Verdana, sans-serif;
        }
        .about-wrap .wp-badge {
            right: 15px;
            background-color: transparent;
            box-shadow: none;
        }
        .about-text {
            color: #cdbfe3 !important;
        }

        .guidelines {
            margin-top: 15px;
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            position: relative;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            padding: 5px 15px;
        }


        .easy-more {
            margin-top: 15px;
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            position: relative;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            padding: 5px 15px;
        }
        .easy-plugins-box {
            background-color: #EEEFFF;
            border: 1px solid #E5E5E5;
            border-top: 0 none;
            position: relative;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            padding: 15px;
        }
        .easy-bottom {
            background-color: #52ACCC;
            color: #FFFFFF;
            border: 1px solid #FFFFFF;
            border-top: 0 none;
            position: relative;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            padding: 5px 15px;
        }
        .easy-bottom a {
            color: #FFFFFF;
        }
        .border {
            border: 1px solid #E5E5E5;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            padding: 20px;
        }
        .nopadding {
            padding-right: 0px !important;
        }
    </style>

    <div class="wrap about-wrap">
        <div class="row easy-wysiwyg-style-head">
            <div class="col-md-12 ">
                <h1>Easy Admin Menu</h1>
                <div class="about-text">Thank you for installing Easy Admin Menu! This WordPress plugin makes
                    it even easier to customize the admin area of your site.</div>
                <div class="wp-badge">EAM v1.3</div>
            </div>
        </div>
        <div class="row">
            <form method="post" action="options.php">
                <div class="col-md-8">
                    <div class="row column" draggable="false" style="background-color: #8CB0BB !important; cursor: auto !important;">
                        <div class="col-sm-4">
                            Menu Item Name
                        </div>
                        <div class="col-sm-4">
                            New Name
                        </div>
                        <div class="col-sm-4">
                            Hide item?
                        </div>
                    </div>

                    <?php settings_fields( 'easy_admin_menu' ); ?>
                    <?php do_settings_sections( 'easy_admin_menu' ); ?>
                    <?php
                    $opt = get_option( 'easy_admin_menu_hidd' );
                    $opt2 = get_option( 'easy_admin_menu_ren' );
                    $opt3 = get_option( 'easy_admin_menu_orig_names' );
                    global $menu;
                    $i=0;
                    foreach ($menu as $item) {
                        $i++;
                        if ($item[0]!='') : //var_dump($item)?>
                            <div class="row column" draggable="true">
                                <div class="col-sm-4">
                                    <input type="text" hidden name="easy_admin_menu[<?= $i ?>][0]" value="<?= $item[2] ?>"/>
                                    <?= $item[0] ?>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" hidden name="easy_admin_menu_orig_names[<?= $item[2] ?>]"
                                           value="<?php if (isset($opt3[$item[2]])) echo $opt3[$item[2]]; else echo $item[0]; ?>" />
                                    <input type="text" name="easy_admin_menu_ren[<?= $item[2] ?>]"
                                           value="<?= strip_tags($item[0]) ?>" />
                                </div>
                                <div class="col-sm-4">
                                    <input type="checkbox" name="easy_admin_menu_hidd[<?= $item[2].'easy_admin_page_separator'.$item[0] ?>]"
                                        <?php if (isset($opt[$item[2].'easy_admin_page_separator'.$item[0]]) && ($opt[$item[2].'easy_admin_page_separator'.$item[0]]=="on"))
                                            echo "checked"; ?>/> hide
                                </div>
                            </div>
                        <?php endif;
                    }
                    ?>
                    <div class="row column" draggable="false" style="background-color: #8CB0BB !important; cursor: auto !important;">
                        <div class="col-sm-6">
                            Hidden Menu Item
                        </div>
                        <div class="col-sm-6">
                            Hide item?
                        </div>
                    </div>
                    <?php
                    if ($opt){
                        foreach ($opt as $k => $op) {
                            $i++; ?>
                            <div class="row column" draggable="true">
                                <?php $exploded = explode('easy_admin_page_separator',$k); ?>
                                <div class="col-sm-6">
                                    <input type="text" hidden name="easy_admin_menu[<?= $i ?>][0]" value="<?= $exploded[0] ?>"/>
                                    <?= $exploded[1] ?>
                                </div>
                                <div class="col-sm-6">
                                    <input type="checkbox" name="easy_admin_menu_hidd[<?= $k ?>]" <?php if ($op=="on") echo "checked"; ?>/>
                                    hidden
                                </div>
                            </div>
                            <?php
                        }}
                    ?>
                </div>
                <div class="col-md-4 nopadding">
                    <div class="guidelines">
                        <h2>Guidelines</h2>
                        <ul>
                            <li>1. Move the menu items in the desired order<br/>(drag & drop)</li>
                            <li>2. Tick 'Hide', to disable menu items</li>
                            <li>3. Change the title of the menu items if needed</li>
                            <li>4. Press "Save Changes"</li>
                        </ul>
                        <?php submit_button(); ?>
                    </div>

                    <div class="easy-box">
                        <div class="easy-more">
                            <h4>Related plugins:</h4>
                            <ul>
                                <li>
                                    <a href="https://wordpress.org/plugins/easy-admin-menu/" target="_blank">· Easy Admin Menu</a>
                                </li>
                                <li>
                                    <a href="https://wordpress.org/plugins/easy-login-form/" target="_blank">· Easy Login Form</a>
                                </li>
                                <li>
                                    <a href="https://wordpress.org/plugins/easy-options-page/" target="_blank">· Easy Options Page</a>
                                </li>
                                <li>
                                    <a href="https://wordpress.org/plugins/easy-timeout-session/" target="_blank">· Easy Timeout Session</a>
                                </li>
                                <li>
                                    <a href="https://wordpress.org/plugins/easy-wysiwyg-style/" target="_blank">· Easy Wysiwyg Style</a>
                                </li>
                            </ul>
                        </div>
            </form>
                        <div class="easy-plugins-box">
                            <!--                <h2>Easy Wysiwyg Style</h2>-->
                            <div class="text-center">
                                <p>This plugin is Free Software and is made available free of charge.</p>
                                <p>If you like the software, please consider a donation.</p>
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="">
                                    <input type="hidden" name="cmd" value="_s-xclick">
                                    <input type="hidden" name="hosted_button_id" value="CHXF6Q9T3YLQU">
                                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                    <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
                                </form>
                            </div>
                        </div>
                        <div class="easy-bottom">
                            Created by <a href="http://jokiruiz.com" target="_blank">Joaquín Ruiz</a>
                        </div>
                    </div>
                </div>


        </div>
    </div>

    <?php
}

add_action('admin_init', 'easy_admin_menuregister_and_build_fields');

function easy_admin_menuregister_and_build_fields() {
    register_setting( 'easy_admin_menu', 'easy_admin_menu', 'validate_easy_admin_menu' );
    register_setting( 'easy_admin_menu', 'easy_admin_menu_hidd', 'validate_easy_admin_menu' );
    register_setting( 'easy_admin_menu', 'easy_admin_menu_ren', 'validate_easy_admin_menu' );
    register_setting( 'easy_admin_menu', 'easy_admin_menu_orig_names', 'validate_easy_admin_menu' );
}

function validate_easy_admin_menu($easy_page_options) {


    return $easy_page_options;
}

function easy_admin_menu_scripts() {
    $currentScreen = get_current_screen();
    if( $currentScreen->base === "settings_page_easy_admin_menu_plugin" ) {
        wp_enqueue_script( 'jquery');
        wp_enqueue_script( 'easy_admin_menu-script-name', plugins_url( '/js/scripts.js', __FILE__ ));
        wp_enqueue_style( 'easy_admin_menu-style-name', plugins_url( '/css/styles.css', __FILE__ ));
        wp_enqueue_style( 'bootstrap', plugins_url( '/css/bootstrap.min.css', __FILE__ ));
    }
}

add_action( 'admin_enqueue_scripts', 'easy_admin_menu_scripts' );

// Reorder Menu
function easy_admin_menu_change_menu_order( $menu_order ) {
    $opt = get_option( 'easy_admin_menu' );
    if (!$opt)
        $arr_to_return = $menu_order;
    else {
        $arr_to_return = array();
        foreach ($opt as $op){
            $arr_to_return[] = $op[0];
        }
    }
    return $arr_to_return;
}
add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'easy_admin_menu_change_menu_order' );

//Change Names
function rename_plugin_menu(  ) {
    global $menu;
    $opt2 = get_option( 'easy_admin_menu_ren' ); if ($opt2 == null) $opt2 = array();
    $opt3 = get_option( 'easy_admin_menu_orig_names' );
    foreach ($menu as $n => $item){
        foreach ($opt2 as $k =>$v) {
            if ($v !='') {
                if (strstr($item[0],$opt3[$k])){
                    $menu[$n][0] = $v;
                }
            }
        }

    }
}

add_action( 'admin_menu', 'rename_plugin_menu' );


// Hide Menu
function easy_admin_menu_remove_menus() {
    $opt = get_option( 'easy_admin_menu_hidd' );
    if (!$opt)
        return;
    else {
        foreach ($opt as $k =>$op){
            $exploded = $exploded = explode('easy_admin_page_separator',$k);
            if ($op == "on")
                remove_menu_page($exploded[0]);
        }
    }
}

add_action( 'admin_menu', 'easy_admin_menu_remove_menus', 999 );
