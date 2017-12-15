<?php
function car_demon_get_sidebar() {
    $show_sidebar = cd_show_sidebar();
    if ( $show_sidebar == 1 ) {
        get_sidebar();
    }
}
add_action( 'cd_sidebar', 'car_demon_get_sidebar', 10 );

function car_demon_get_vehicle_header_sidebar() {
    $x = '<div id="vehicle_header_widget" class="vehicle_header_widget">';
        $x .= '<ul>';
            ob_start();
            if ( ! function_exists( 'dynamic_sidebar') || ! dynamic_sidebar( 'Vehicle Header Sidebar' ) ) :
            endif;
            $output = ob_get_contents();
            ob_end_clean();
            $x .= $output;
        $x .= '</ul>';
        $x .= '<br class="clear_car">';
    $x .= '</div>';
    if ( empty( $output ) ) {
        $x = '';
    }
    echo $x;
}
add_action( 'cd_header_sidebar_action', 'car_demon_get_vehicle_header_sidebar', 10 );

function car_demon_get_vehicle_sidebar() {
    $show_sidebar = cd_show_sidebar();
    if ( $show_sidebar == 1 ) {
        echo '<div id="sideBar1" class="car_side_bar">';
            echo '<ul>';
                if ( ! function_exists( 'dynamic_sidebar') || ! dynamic_sidebar( 'Vehicle Detail Sidebar' ) ) :
                endif;
            echo '</ul>';
            echo '<br class="clear_car">';
        echo '</div>';
    }
}
add_action( 'cd_vehicle_sidebar_action', 'car_demon_get_vehicle_sidebar', 10 );

function cd_show_sidebar() {
    global $car_demon_options;
    $show_sidebar = 1;
    if ( is_single() ) {
        if ( isset( $car_demon_options['right_vehicle_sidebar'] ) ) {
            if ( ! empty( $car_demon_options['right_vehicle_sidebar'] ) ) {
                $show_sidebar = 0;
            }
        }
        if ( isset( $car_demon_options['left_vehicle_sidebar'] ) ) {
            if ( ! empty( $car_demon_options['left_vehicle_sidebar'] ) ) {
                $show_sidebar = 0;
            }
        }
    } else {
        if ( isset( $car_demon_options['right_list_sidebar'] ) ) {
            if ( ! empty( $car_demon_options['right_list_sidebar'] ) ) {
                $show_sidebar = 0;
            }
        }
        if ( isset( $car_demon_options['left_list_sidebar'] ) ) {
            if ( ! empty( $car_demon_options['left_list_sidebar'] ) ) {
                $show_sidebar = 0;
            }
        }
    }
    return $show_sidebar;
}

function car_demon_output_content_wrapper() {
    global $car_demon_options;
    $cd_page_css = '';
    if ( isset( $car_demon_options['cd_page_id'] ) ) {
        if ( ! empty( $car_demon_options['cd_page_id'] ) ) {
            if ( isset( $car_demon_options['cd_page_css'] ) ) {
                if ( ! empty( $car_demon_options['cd_page_css'] ) ) {
                    $cd_page_css = ' class="' . $car_demon_options['cd_page_css'] . '"';
                }
            }
            global $cd_doing_shortcode;
            if ( $cd_doing_shortcode ) {
                if ( empty( $cd_page_css ) ) {
                    $cd_page_css = ' class="cd_shortcode"';
                } else {
                    $cd_page_css = ' class="' . $car_demon_options['cd_page_css'] . ' cd_shortcode"';
                }
            }

            echo '<div id="' . $car_demon_options['cd_page_id'] . '"' . $cd_page_css . '>';
        }
    }
    global $cd_doing_shortcode;
    if ( ! $cd_doing_shortcode ) {
        if ( isset( $car_demon_options['vehicle_sidebar_class'] ) ) {
            $vehicle_sidebar_class = ' class="' . $car_demon_options['vehicle_sidebar_class'] . '"';
        } else {
            $vehicle_sidebar_class = '';
        }
        if ( isset( $car_demon_options['sidebar_id'] ) ) {
            if ( empty( $car_demon_options['sidebar_id'] ) ) {
                $sidebar_id = 'cd_sidebar'; 
            } else {
                $sidebar_id = ' id="' . $car_demon_options['sidebar_id'] . '"';
            }
        } else {
            $sidebar_id = '';
        }
    
        if ( isset( $car_demon_options['use_theme_files'] ) ) {
            if ( $car_demon_options['use_theme_files'] == 'Yes' ) {
                if ( is_single() ) {
                    if ( isset( $car_demon_options['left_vehicle_sidebar'] ) ) {
                        if ( ! empty( $car_demon_options['left_vehicle_sidebar'] ) ) {
                            echo '<div' . $sidebar_id . $vehicle_sidebar_class . '>';
                                if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $car_demon_options['left_vehicle_sidebar'] ) ) :
                                endif;
                            echo '</div>';
                        }
                    }
                } else {
                    if ( isset($car_demon_options['left_list_sidebar'] ) ) {
                        if ( ! empty( $car_demon_options['left_list_sidebar'] ) ) {
                            echo '<div' . $sidebar_id . $vehicle_sidebar_class . '>';
                                if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $car_demon_options['left_list_sidebar'] ) ) :
                                endif;
                            echo '</div>';
                        }
                    }
                }
            }
        }
    }
    
    if ( isset( $car_demon_options['vehicle_container'] ) ) {
        $container = ' class="' . $car_demon_options['vehicle_container'] . '"';
    } else {
        $container = '';
    }
    if ( isset( $car_demon_options['cd_content_id'] ) ) {
        $cd_content_id = $car_demon_options['cd_content_id'];
    }
    if ( ! empty( $cd_content_id ) ) {
        $cd_content_id = $car_demon_options['cd_content_id'];
        echo '<div id="' . $cd_content_id . '"' . $container . '>';
            echo '<div id="demon-container">';
                echo '<div id="demon-content" class="listing" role="main">';
    } else {
        echo '<div id="demon-container"' . $container . '>';
            echo '<div id="demon-content" class="listing" role="main">';
    }
}
add_action( 'cd_before_content_action', 'car_demon_output_content_wrapper', 10 );

function car_demon_output_content_wrapper_end() {
    global $car_demon_options;
        echo '</div><!-- #content -->';
    echo '</div><!-- #container -->';
    if ( isset( $car_demon_options['cd_content_id'] ) ) {
        $cd_content_id = $car_demon_options['cd_content_id'];
    }
    if ( ! empty( $cd_content_id ) ) {
        echo '</div><!-- #custom container -->';
    }

    global $cd_doing_shortcode;
    if ( ! $cd_doing_shortcode ) {
        if ( isset( $car_demon_options['vehicle_sidebar_class'] ) ) {
            $list_container = ' class="' . $car_demon_options['vehicle_sidebar_class'] . '"';
        } else {
            $list_container = '';
        }
        if ( isset( $car_demon_options['sidebar_id'] ) ) {
            if ( empty( $car_demon_options['sidebar_id'] ) ) {
                $sidebar_id = 'cd_sidebar'; 
            } else {
                $sidebar_id = ' id="' . $car_demon_options['sidebar_id'] . '"';
            }
        } else {
            $sidebar_id = '';
        }

        if ( isset( $car_demon_options['use_theme_files'] ) ) {
            if ( $car_demon_options['use_theme_files'] == 'Yes' ) {
                if ( is_single() ) {
                    if ( isset( $car_demon_options['right_vehicle_sidebar'] ) ) {
                        if ( ! empty( $car_demon_options['right_vehicle_sidebar'] ) ) {
                            echo '<div' . $sidebar_id . $list_container . '>';
                                if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $car_demon_options['right_vehicle_sidebar'] ) ) :
                                endif;
                            echo '</div>';
                        }
                    }
                } else {
                    if ( isset( $car_demon_options['right_list_sidebar'] ) ) {
                        if ( ! empty( $car_demon_options['right_list_sidebar'] ) ) {
                            echo '<div' . $sidebar_id . $list_container . '>';
                                if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $car_demon_options['right_list_sidebar'] ) ) :
                                endif;
                            echo '</div>';
                        }
                    }
                }
            }
        }
    }
    
    if ( isset( $car_demon_options['cd_page_id'] ) ) {
        if ( ! empty( $car_demon_options['cd_page_id'] ) ) {
            echo '</div>';
        }
    }

}
add_action( 'cd_after_content_action', 'car_demon_output_content_wrapper_end', 10 );

function get_car_title( $post_id ) {
    global $car_demon_options;
    if ( $car_demon_options['use_post_title'] == 'Yes' ) {
        $car_title = get_the_title( $post_id );
    } else {
        $car_title = '';
    }
    if ( empty( $car_title ) ) {
        $vehicle_year = get_cd_term( $post_id, 'vehicle_year' );
        $vehicle_make = get_cd_term( $post_id, 'vehicle_make' );
        $vehicle_model = get_cd_term( $post_id, 'vehicle_model' );
        //get_cd_term( $post_id, 'vehicle_model' );
        $car_title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
    }
    $car_title = trim( $car_title );
    if ( isset( $car_demon_options['title_trim'] ) ) {
        if ( $car_demon_options['title_trim'] > 1 ) {
            $car_title = substr( $car_title, 0, $car_demon_options['title_trim'] );
        }
    }
    $car_title = apply_filters( 'cd_title_filter', $car_title, $post_id );
    $car_title = apply_filters( 'car_title_filter', $car_title, $post_id ); //= deprecated
    return $car_title;
}

function get_car_title_slug( $post_id ) {
    $car_title = get_car_title( $post_id );
    $car_title = strtolower( $car_title );
    $car_title = trim( $car_title );
    $car_title = str_replace( chr(32), '_', $car_title );
    return $car_title;
}

function car_demon_nav( $position, $search_query ) {
    $x = '';
    global $wp_query;
    if ( $position == 'top' ) {
        $second_position = 'above';
        $third_position = '-top';
    }
    if ( $position == 'bottom' ) {
        $second_position = 'below';
        $third_position = '';
    }
    if ( $search_query->max_num_pages > 1 ) {
        $x .= '<div id="cd-nav-' . $second_position . '" class="navigation' . $third_position . ' inventory_nav_' . $position . '">';
        if( function_exists( 'wp_pagenavi' ) ) {
            $nav_list_str = wp_pagenavi( array( 'query' => $search_query, 'echo' => false ) );
            if ( $position == 'top' ) {
                $nav_list_str = str_replace( 'nextpostslink','nextpostslink-' . $second_position, $nav_list_str );
            } else {
                $nav_list_str = str_replace( 'wp-pagenavi', 'wp-pagenavi nav-bottom', $nav_list_str );
            }
            $x .= $nav_list_str;
        } else { 
            $x .= '<div class="nav-previous"> ' . next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'car-demon' ) ) . '</div>';
            $x .= '<div class="nav-next"> ' . previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'car-demon' ) ) . '</div>';
        } 
        $x .= '</div><!-- #nav-' . $second_position . ' -->';
    } else {
        $x .= '<div id="cd-nav-' . $second_position . '" class="navigation-' . $position . ' inventory_nav"><span class="wp-pagenavi"><span class="pages">'. $wp_query->post_count; __( 'Results Found', 'car-demon') . '</span></span>';
        $x .= '</div>';
    }
    $x = str_replace( 'none', '', $x );
    $x = apply_filters( 'cd_nav_filter', $x, $position, $search_query );
    return $x;
}

//=====Functions used exclusively in single-cars_for_sale.php
function car_demon_photo_lightbox() {
    $car_demon_pluginpath = CAR_DEMON_PATH;
    $car_demon_pluginpath = str_replace( 'includes/', '', $car_demon_pluginpath );
    $x = '<div class="car_demon_light_box" id="car_demon_light_box">
        <div class="car_demon_photo_box" id="car_demon_photo_box"">
            <div class="close_light_box" onclick="close_car_demon_lightbox();">(close) X</div>
            <div class="car_demon_light_box_main_email" id="car_demon_light_box_main_email"></div>
            <div class="car_demon_light_box_main" id="car_demon_light_box_main">
                <img id="car_demon_light_box_main_img" src="" />
                <div class="run_slideshow_div" onclick="car_slide_show();" id="run_slideshow_div">
                        <input type="checkbox" id="run_slideshow" /> '. __( 'Run Slideshow', 'car-demon').'
                </div>
                <div class="photo_next" id="photo_next">
                    <img src="'. $car_demon_pluginpath . 'theme-files/images/btn_next.png" onclick="get_next_img();" title="' . __( 'Next', 'car-demon') . '" />
                </div>
                <div class="photo_prev" id="photo_prev">
                    <img src="'. $car_demon_pluginpath . 'theme-files/images/btn_prev.png" onclick="get_prev_img();" title="' . __( 'Previous', 'car-demon') . '" />
                </div>
            </div>
            <div class="hor_lightbox" id="car_demon_thumb_box">
            </div>
        </div>
    </div>';
    $x = apply_filters( 'cd_lightbox_filter', $x );
    $x = apply_filters( 'cd_lightbox', $x ); // deprecated
    return $x;
}

function car_demon_email_a_friend( $post_id, $vehicle_stock_number ) {
    $car_head_title = get_car_title( $post_id );
    $nonce = wp_create_nonce( 'cd_email_friend_nonce' );
    $x = '<div id="email_friend_div" class="email_friend_div">
        <div id="ef_contact_final_msg_tmp" class="ef_contact_final_msg_tmp"></div>
        <div id="main_email_friend_div_tmp" class="main_email_friend_div_tmp">
        <h2>'. __( 'Send this car to a friend', 'car-demon') .'</h2><hr />
            <form enctype="multicontact/form-data" data-nonce="' . $nonce . '" action="?send_contact=1" method="post" class="cdform email_friend_form" id="email_friend_form_tmp" name="email_friend_form_tmp">
            <input type="hidden" name="nonce" id="nonce" value="' . $nonce . '" />
            <input type="hidden" name="ef_stock_num_tmp" id="ef_stock_num_tmp" value="' . $vehicle_stock_number . '" />
                    <fieldset class="">
                    <legend>'. __( 'Your Information', 'car-demon') .'</legend>
                    <ol class="cd-ol">
                        <li class=""><label for="cd_field_2"><span>' . __( 'Your Name', 'car-demon' ) . '</span></label><input type="text" name="ef_cd_name_tmp" id="ef_cd_name_tmp" class="single fldrequired" value="' . __( 'Your Name', 'car-demon' ) . '" onfocus="ef_clearField(this)" onblur="ef_setField(this)"><span class="reqtxt">*</span></li>
                        <li class=""><label for="cd_field_4"><span>' . __( 'Your Email', 'car-demon' ) . '</span></label><input type="text" name="ef_cd_email_tmp" id="ef_cd_email_tmp" class="single fldemail fldrequired" value=""><span class="emailreqtxt">*</span></li>
                        <li class=""><label for="cd_field_2"><span>' . __( 'Friend\'s Name', 'car-demon' ) . '</span></label><input type="text" name="ef_cd_friend_name_tmp" id="ef_cd_friend_name_tmp" class="single fldrequired" value="' . __( 'Friend Name', 'car-demon' ) . '" onfocus="ef_clearField(this)" onblur="ef_setField(this)"><span class="reqtxt">*</span></li>
                        <li class=""><label for="cd_field_4"><span>' . __( 'Friend\'s Email', 'car-demon' ) . '</span></label><input type="text" name="ef_cd_friend_email_tmp" id="ef_cd_friend_email_tmp" class="single fldemail fldrequired" value=""><span class="emailreqtxt">*</span></li>
                    </ol>
                    </fieldset>
                    <fieldset class="">
                    <legend>'. __( 'Your Message', 'car-demon') .'</legend>
                    <ol class="cd-ol">
                        <li id="li-5" class=""><textarea name="ef_comment_tmp" id="ef_comment_tmp" class="">' . __( 'Check out this', 'car-demon') . ' ' . $car_head_title .', '. __( 'stock number', 'car-demon' ) . ' ' . $vehicle_stock_number .'!</textarea><br><span class="reqtxt ef_reqtxt"><br>* '. __( 'required', 'car-demon') .'</span></li>
                    </ol>
                    </fieldset>
                    <div id="ef_contact_msg_tmp"></div>
                    <p class="cd-sb"><input type="button" name="ef_search_btn_tmp" id="ef_sendbutton_tmp" class="search_btn ef_search_btn" value="' . __( 'Send Now!', 'car-demon' ) . '" onclick="return ef_car_demon_validate()"></p>
            </form>
        </div>
    </div>';
    return $x;
}

function car_demon_display_similar_cars( $body_style, $current_id ) {
    global $car_demon_options;
    global $wpdb;
    $show_it = '';
    $car_demon_pluginpath = CAR_DEMON_PATH;
    $car_demon_pluginpath = str_replace( 'includes', 'theme-files', $car_demon_pluginpath );
    $my_tag_id = get_term_by( 'slug', $body_style, 'vehicle_body_style' );
    if ( ! empty( $body_style ) ) {
        if ( ! empty( $my_tag_id ) ) {
            $my_search = " AND $wpdb->term_taxonomy.taxonomy = 'vehicle_body_style' AND $wpdb->term_taxonomy.term_id IN(" . $my_tag_id->term_id . ")";
            $str_sql = "SELECT wposts.ID
                FROM $wpdb->posts wposts
                    LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
                    LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
                    LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
                WHERE wposts.post_type='cars_for_sale'
                    AND wpostmeta.meta_key = 'sold'
                    AND wpostmeta.meta_value = 'no'" . $my_search . '
                    ORDER BY ID LIMIT 4';
            $the_lists = $wpdb->get_results( $str_sql );
        } else {
            $the_lists = '';        
        }
    } else {
        $the_lists = '';
    }
    $car = '';
    $cnt = 0;
    if ( ! empty( $the_lists ) ) {
        $car .= '<span class="similar_cars_area"><h3 class="similar_cars_box_title">' . __( 'Other Great Deals', 'car-demon' ) . '</h3>';
        $car .= '<div class="similar_cars_box">';
        //= Get the labels for the default fields
        $field_labels = get_default_field_labels();
        foreach ( $the_lists as $the_list ) {
            $post_id = $the_list->ID;
            if ( $post_id != $current_id ) {
                $cnt = $cnt + 1;
                if ( $cnt < 4 ) {
                    $show_it = 1;
                    $stock_value = get_post_meta( $post_id, "_stock_value", true );
                    $vehicle_year = trim(get_cd_term( $post_id, 'vehicle_year' ));
                    $vehicle_make = trim(get_cd_term( $post_id, 'vehicle_make' ));
                    $vehicle_model = trim(get_cd_term( $post_id, 'vehicle_model' ));
                    $vehicle_condition = trim(get_cd_term( $post_id, 'vehicle_condition' ));
                    $title = get_car_title( $post_id );
                    $mileage_value = get_post_meta( $post_id, "_mileage_value", true );
                    $detail_output = '<span class="random_title">' . $title . '</span><br />';
                    $detail_output .= '<span class="random_text">';
                        $detail_output .= $field_labels['condition'] . ': ' . $vehicle_condition . '<br />';
                    $detail_output .= '</span>';
                    $detail_output .= '<span class="random_text">';
                        $detail_output .= $field_labels['mileage'] . ': ' . $mileage_value . '<br />';
                    $detail_output .= '</span>';
                    $detail_output .= '<span class="random_text">';
                        $detail_output .= $field_labels['stock_number'] . ': ' . $stock_value;
                    $detail_output .= '</span>';
                    $link = get_permalink( $post_id );
                    $img_output = "<img onclick='window.location=\"" . $link . "\";' title='" . __( "Click for price on this", "car-demon" ) . " " . $title . "' onerror='ImgError(this, \"no_photo.gif\");' class='random_widget_image' width='180px' height='135px' src='";
                    $img_output .= cd_main_photo( $post_id );
                    $img_output .= "' />";
                    $ribbon = get_post_meta( $post_id, '_vehicle_ribbon', true );
                    if ( empty( $ribbon ) ) {
                        $ribbon = 'no-ribbon';      
                    }                   
                    if ( $ribbon != 'custom_ribbon' ) {
                        $ribbon = str_replace( '_', '-', $ribbon );
                        $current_ribbon = '<img class="similar_car_ribbon" src="' . $car_demon_pluginpath . 'images/ribbon-' . $ribbon . '.png" width="76" height="76" id="ribbon">';
                    } else {
                        $custom_ribbon_file = get_post_meta( $post_id, '_custom_ribbon', true );
                        $current_ribbon = '<img class="similar_car_ribbon" src="' . $custom_ribbon_file . '" width="76" height="76" id="ribbon">';
                    }
                    if ( isset( $car_demon_options['dynamic_ribbons'] ) ) {
                        if ( $car_demon_options['dynamic_ribbons'] == 'Yes' ) {
                            $current_ribbon = car_demon_dynamic_ribbon_filter( $current_ribbon, $post_id, '76' );
                        }
                    }
                    $car .= '
                        <div class="random similar_car">
                            <div class="random_img">
                                ' . $current_ribbon . '
                                <img class="look_close similar_car_look_close" onclick="window.location=\'' . $link . '\';" src="' . $car_demon_pluginpath . 'theme-files/images/look_close.png" width="188" height="143" id="look_close">
                                ' . $img_output . '
                            </div>
                            <div class="random_description">
                                ' . $detail_output . '
                            </div>
                        </div>';
                }
            }
        }
        $car .= '</div></span>';
    }
    if ( $show_it != 1 ) {
        $car = '';
    }
    return $car;
}

function car_photos( $post_id, $details, $vehicle_condition ) {
    global $car_demon_options;
    $car_demon_pluginpath = CAR_DEMON_PATH;
    $car_demon_pluginpath = str_replace( 'includes', '', $car_demon_pluginpath );
    $mileage_value = '';
    $car_title = '';
    $car_js = '';
    $ribbon = get_post_meta( $post_id, '_vehicle_ribbon', true );
    if ( empty( $ribbon ) ) {
        $ribbon = 'no-ribbon';      
    }
    if ( $ribbon != 'custom_ribbon' ) {
        $ribbon = str_replace( '_', '-', $ribbon );
        $current_ribbon = '<img src="' . $car_demon_pluginpath . 'theme-files/images/ribbon-' . $ribbon . '.png" id="ribbon" class="ribbon">';
    } else {
        $custom_ribbon_file = get_post_meta( $post_id, '_custom_ribbon', true );
        $current_ribbon = '<img src="'.$custom_ribbon_file.'" id="ribbon" class="ribbon">';
    }
    if ( isset( $car_demon_options['dynamic_ribbons'] ) ) {
        if ( $car_demon_options['dynamic_ribbons'] == 'Yes' ) {
            $current_ribbon = car_demon_dynamic_ribbon_filter( $current_ribbon, $post_id, '112' );
        }
    }
    if ( isset( $car_demon_options['popup_images'] ) ) {
        if ( $car_demon_options['popup_images'] == 'Yes' ) {
            $popup_imgs = ' onmouseover="cd_make_large(this)" onmouseout="cd_go_out();"';
            $lightbox_js = '';
        } else {
            $popup_imgs = '';
            $lightbox_js = ' onclick="open_car_demon_lightbox(\'' . $car_title . '_pic\');"';
        }
    } else {
        $popup_imgs = '';
        $lightbox_js = ' onclick="open_car_demon_lightbox(\'' . $car_title . '_pic\');"';
    }
    $this_car = '<div>';
        $this_car .= '<div class="car_detail_div">';
            $this_car .= '<div class="car_main_photo_box">';
                $this_car .= $current_ribbon;
                $this_car .= '<img' . $lightbox_js . ' src="' . $car_demon_pluginpath . 'theme-files/images/look_close.png" alt="New Ribbon" id="look_close" class="look_close">';
                $this_car .= '<div id="main_thumb"><img onerror="ImgError(this, \'no_photo.gif\');" id="' . $car_title . '_pic" name="' . $car_title . '_pic" class="car_demon_main_photo" src="';
                $main_guid = cd_main_photo( $post_id );
                $main_guid = trim( $main_guid );
                $this_car .= $main_guid;
                $this_car .= '" /></div>';
            $this_car .= '</div>';
            $this_car .= '<div class="car_details_box">';
                $this_car .= $details;
            $this_car .= '</div>';
        $this_car .= '</div>';
        // Thumbnails
        $thumbnails = get_children( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' =>'image', 'orderby' => 'menu_order ID') );
        $this_car .= '<div class="nohor" id="car_demon_thumbs">';
        $cnt = 0;
        $car_js .= 'carImg[' . $cnt . ']="'. trim( $main_guid ) . '";' . chr(13);
        $photo_array = '<img class="car_demon_thumbs"' . $popup_imgs . ' onClick=\'MM_swapImage("' . $car_title . '_pic","","' . trim( $main_guid ) . '",1);active_img(' . $cnt . ')\' src="' . trim( $main_guid ) . '" width="62" />';
        $this_car .= $photo_array;
        foreach( $thumbnails as $thumbnail ) {
            $guid = wp_get_attachment_url( $thumbnail->ID );
            if ( ! empty( $guid ) ) {
                if ( $main_guid != $guid ) {
                    $cnt = $cnt + 1;
                    $car_js .= 'carImg[' . $cnt . ']="' . trim( $guid ) . '";' . chr(13);
                    $photo_array = '<a href="#mainpic"><img class="car_demon_thumbs"' . $popup_imgs . ' onClick=\'MM_swapImage("' . $car_title . '_pic","","' . trim( $guid ) . '",1);active_img( ' . $cnt . ')\' src="' . trim( $guid ) . '" width="62" /></a>';
                    $this_car .= $photo_array;
                }
            }
        }
        // Check if vehicle has a list of photo urls that arent part of the normal gallery
        $image_list = get_post_meta( $post_id, '_images_value', true );
        if ( ! empty( $image_list ) ) {
            $thumbnails = explode( ",", $image_list );
            foreach( $thumbnails as $thumbnail ) {
                //= $pos = strpos($thumbnail,'.jpg');
                //= We need a different way to check if it really is an image file
                //= maybe check file ext array?
                $thumbnail = trim( $thumbnail );
                //= prevent empty images from being added
                if ( empty( $thumbnail ) ) {
                    continue;   
                }
                //= prevent duplicates from showing
                if ( strpos( $this_car, $thumbnail ) !== false) {
                    continue;
                }
                $pos = true;
                if( $pos == true ) {
                    if ( $cnt > 0 ) {
                        $car_js .= 'carImg[' . $cnt . ']="' . trim( $thumbnail ) . '";' . chr(13);
                        $photo_array = '<a href="#mainpic"><img class="car_demon_thumbs" style="cursor:pointer"' . $popup_imgs . ' onClick=\'MM_swapImage("' . $car_title . '_pic","","' . trim( $thumbnail ) . '",1);\' src="' . trim( $thumbnail ) . '" width="62" /></a>';
                        $this_car .= $photo_array;
                    }
                    $cnt = $cnt + 1;
                }
            }
        }
        $this_car .= '</div>';
        // End Thumbnails
    $this_car .= '</div>';
    $total_pics = $cnt;
    $car_js = '
        <input type="hidden" id="current_img_num" value="0" />
        <input type="hidden" id="current_img_name" />
        <input type="hidden" id="image_count" value="' . $total_pics . '" />
        <script>
            function photo_img_array() {
                var carImg = new Array;
                ' . $car_js . '
                return carImg;
            }
            setInterval(function(){car_slide_show()},3000);
        </script>';
    $html = $this_car.$car_js;
    $html = apply_filters( 'car_demon_photo_hook', $html, $post_id, $details, $vehicle_condition, 'desktop' ); //= deprecated
    $html = apply_filters( 'cd_photo_filter', $html, $post_id, $details, $vehicle_condition, 'desktop' );
    return $html;
}

function car_demon_no_search_results( $searched ) {
    $x = '<div id="post-0" class="post no-results not-found">';
        $x .= '<h2 class="entry-title">' . __( 'Nothing Found', 'car-demon' ) . '</h2>';
        $x .= '<div class="entry-content">';
            $x .= '<p class="sorry">' . __( 'Sorry, but nothing matched your search criteria. Please try using a broader search selection.', 'car-demon' ) . '</p>';
            $x .= $searched;
            $i = 0;
            do {
                $x .= '<div class="no_result">';
                    $x .= car_demon_display_random_cars(1);
                $x .= '</div>';
                ++$i;
            } while ( $i < 9 );
        $x .= '</div><!-- .entry-content -->';
    $x .= '</div><!-- #post-0 -->';
    return $x;
}

function car_demon_get_the_content_with_formatting( $more_link_text = '(more...)', $stripteaser = 0, $more_file = '' ) {
    global $car_demon_options;
    $content = get_the_content( $more_link_text, $stripteaser, $more_file );
    $cd_cdrf_options = $car_demon_options;
    $theme_style = '';
    if ( ! empty( $cd_cdrf_options ) ) {
        $theme_style = $cd_cdrf_options['cd_cdrf_style'];
    }
    // Do not apply filters if using content-replacement
    if ( $theme_style != 'content-replacement' ) {
        $content = apply_filters( 'the_content', $content );
    } else {
        remove_filter( 'the_content', 'cd_filter_vehicle_content', 20 );
        remove_filter( 'the_excerpt', 'cd_filter_vehicle_content', 20 );
        $content = apply_filters( 'the_content', $content );
    }
    $content = str_replace( ']]>', ']]&gt;', $content );
    return $content;
}

function car_demon_facebook_meta() {
    $post_id = get_the_ID();
    $title = get_car_title( $post_id );
    $url = get_permalink( $post_id );
    $image = cd_main_photo( $post_id );
    $x = '
        <meta property="og:title" content="' . $title . '"/>
        <meta property="og:url" content="' . $url . '"/>
        <meta property="og:image" content="' . $image . '"/>';
    echo $x;
}

function cd_get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {
    global $wpdb;
    if( empty( $key ) )
        return;

   $r = $wpdb->get_col( $wpdb->prepare( "
        SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '%s' 
        AND p.post_status = '%s' 
        AND p.post_type = '%s'
    ", $key, $status, $type ) );
    asort( $r );
    return $r;
}

function cd_main_photo( $post_id, $type = '' ) {
    global $car_demon_options;

    if ( defined( 'CD_LINK_MAIN_IMAGE' ) ) {
        $car_demon_options['link_main_image'] = 'Yes';
    }

    if ( isset( $car_demon_options['link_main_image'] ) ) {
        if ( $car_demon_options['link_main_image'] == 'Yes' ) {
            $image_links = get_post_meta( $post_id, '_images_value', true );
            $image_links_array = explode( ',', $image_links );
            if ( is_array( $image_links_array ) ) {
                $main_photo = trim( $image_links_array[0] );
            } else {
                $main_photo = trim( $image_links );
            }
            if ( ! empty( $main_photo ) ) {
                return $main_photo;
            }
        }
    }

    if ( $type == 'thumbnail' ) {
        $main_photo = wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );
    } else {
        $main_photo = wp_get_attachment_url( get_post_thumbnail_id( $post_id ), 'full' );
    }

    return $main_photo;
}

/**
 * This function is being deprecated in favor of cd_get_car()
*/
function car_demon_get_car( $post_id ) {
    $car = cd_get_car( $post_id );
    return $car;
}

function cd_get_car( $post_id ) {
    $x = get_post_meta( $post_id, 'decode_string', true );
    $x['title'] = get_car_title( $post_id );
    $x['title_slug'] = get_car_title_slug( $post_id );
    $x['car_link'] = get_permalink( $post_id );
    $x['vin'] = rwh( strip_tags( get_post_meta( $post_id, "_vin_value", true ) ), 0 );
    $x['year'] = get_cd_term( $post_id, 'vehicle_year' );
    $x['make'] = get_cd_term( $post_id, 'vehicle_make' );
    $x['model'] = get_cd_term( $post_id, 'vehicle_model' );
    $x['condition'] = get_cd_term( $post_id, 'vehicle_condition' );
    $x['body_style'] = get_cd_term( $post_id, 'vehicle_body_style' );
    if ( ! isset( $x['location'] ) ){
        $x['location'] = get_cd_term( $post_id, 'vehicle_location' );
    }
    $x['mileage'] = strip_tags( get_post_meta( $post_id, "_mileage_value", true ) );
    //= Begin potential custom spec items
    if( isset( $x['decoded_exterior_color'] ) ) {
        $x['exterior_color'] = $x['decoded_exterior_color'];
    } else {
        $x['exterior_color'] = strip_tags( get_post_meta( $post_id, "_exterior_color_value", true ) );
    }
    if( isset( $x['decoded_interior_color'] ) ) {
        $x['interior_color'] = $x['decoded_interior_color'];
    } else {
        $x['interior_color'] = strip_tags( get_post_meta( $post_id, "_interior_color_value", true ) );
    }
    $x['fuel'] = strip_tags( get_post_meta( $post_id, "_fuel_type_value", true ) );
    if( isset( $x['decoded_transmission_long'] ) ) {
        $x['transmission'] = $x['decoded_transmission_long'];
    } else {
        $x['transmission'] = strip_tags( get_post_meta( $post_id, "_transmission_value", true ) );
    }
    $x['cylinders'] = strip_tags( get_post_meta( $post_id, "_cylinders_value", true ) );
    if ( ! isset( $x['engine'] ) ) {
        $x['engine'] = strip_tags( get_post_meta( $post_id, "_engine_value", true ) );
    }
    $x['doors'] = strip_tags( get_post_meta( $post_id, "_doors_value", true ) );
    $x['trim'] = strip_tags( get_post_meta($post_id, "_trim_value", true ) );
    $x['warranty'] = strip_tags( get_post_meta($post_id, "_warranty_value", true ) );
    $x['main_photo'] = cd_main_photo( $post_id );

    // NEW CAR FIELDS START HERE...

    $x['wheels'] = strip_tags( get_post_meta( $post_id, "wheels", true ) );
    $x['brakes'] = strip_tags( get_post_meta( $post_id, "brakes", true ) );

    // NEW CAR FIELDS END HERE...

    //= Get Prices
    $x['price'] = get_post_meta( $post_id, "_price_value", true );
    $x['price'] = apply_filters( 'cd_price_format_filter', $x['price'] ); //= deprecated
    $x['price'] = apply_filters( 'cd_price_filter', $x['price'] );

    $x['msrp'] = get_post_meta( $post_id, "_msrp_value", true );
    $x['msrp'] = apply_filters( 'cd_price_format_filter', $x['msrp'] );
    $x['msrp'] = apply_filters( 'cd_price_filter', $x['msrp'] ); //= deprecated

    $x['rebate'] = get_post_meta( $post_id, "_rebates_value", true );
    $x['rebate'] = apply_filters( 'cd_price_format_filter', $x['rebate'] ); //= deprecated
    $x['rebate'] = apply_filters( 'cd_price_filter', $x['rebate'] );

    $x['discount'] = get_post_meta( $post_id, "_discount_value", true );
    $x['discount'] = apply_filters( 'cd_price_format_filter', $x['discount'] ); //= deprecated
    $x['discount'] = apply_filters( 'cd_price_filter', $x['discount'] );

    if ( isset( $x['stock_num'] ) ) {
        $x['stock_number'] = $x['stock_num'];
    }
    if ( ! isset( $x['stock_number'] ) ) {
        $x['stock_number'] = wp_kses_data( get_post_meta( $post_id, '_stock_value', true ) );
        if ( empty( $x['stock_number'] ) ) {
            $x['stock_number'] = $post_id;
        }
    }

    //= get full post
    $post = get_post( $post_id );
    if ( is_object( $post ) ) {
        $post = (array)$post;
        $x = array_merge( $post, $x );
    }

    return $x;
}

function get_cd_term( $post_id, $term ) {
    $name = '';
    $terms = wp_get_object_terms( $post_id, $term );
    $cnt = 1;
    foreach( $terms as $term_item ) {
        if ( $cnt === 1 ) {
            $name = $term_item->name;
        } else {
            $name .= ', ' . $term_item->name;
        }
        ++$cnt;
    }   
    return $name;
}

function cd_tag_filter( $post_id, $content ) {
    $car_options = cd_get_car( $post_id );
    $car_contact = get_car_contact( $post_id );
    $car_options = array_merge( $car_options, $car_contact );
    /*
    echo '<pre>';
        print_r($car_options);
    echo '</pre>';
    */
    if ( is_array( $car_options ) ) {
        foreach( $car_options as $car_option=>$value ) {
            $content = str_replace( '{' . $car_option . '}', $value, $content );
            $content = str_replace( '[' . $car_option . ']', $value, $content );
            if ( strpos( $car_option, 'decoded_' ) !== false ) {
                unset( $car_options[ $car_option ] );
            }
            $car_option = str_replace( 'decoded_', '', $car_option );
            $content = str_replace( '{' . $car_option . '}', $value, $content );
            $content = str_replace( '[' . $car_option . ']', $value, $content );
        }
    }

    $tags = '<pre>' . print_r( $car_options, true ) . '</pre>';

    $content = str_replace( '{tags}', $tags, $content );

    return $content;    
}
?>
