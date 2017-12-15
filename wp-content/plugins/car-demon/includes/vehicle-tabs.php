<?php
function car_demon_vehicle_detail_tabs( $post_id, $no_content = false ) {
    global $car_demon_options;
    $tab_cnt = 1;
    $vin_query = '';
    $about_cnt = 2;
    $vehicle_vin = rwh( strip_tags( get_post_meta( $post_id, "_vin_value", true ) ), 0 );
    if ( $no_content == false ) {
        $content = car_demon_get_the_content_with_formatting();
    } else {
        $content = '';
    }
    $content = trim( $content );
    if ( empty( $content ) ) {
        $location_lists = get_the_terms( $post_id, 'vehicle_location' );
        if ( $location_lists ) {
            foreach ( $location_lists as $location_list ) {
                $location_slug = $location_list->slug;
            }
        } else {
            $location_slug = cd_get_default_location_slug();
        }
        $content = get_option( $location_slug . '_default_description' );
        if ( empty( $content ) ) {
            $content = get_default_description();
        }
    }

    $vehicle_options_list = get_post_meta( $post_id, '_vehicle_options', true );
    $vehicle_options_array = explode( ',', $vehicle_options_list );
    $options_image = '<img class="custom_option_img" src="' . plugins_url() . '/car-demon/theme-files/images/opt_standard.gif" />';
    $include_options = 0;
    $included_options = '';
    $flag = false;
    if ( isset( $car_demon_options['hide_tabs'] ) ) {
        if ( $car_demon_options['hide_tabs'] == 'Yes' ) {
            $include_options = 1;
        }
    }
    //= If vehicle has hide tab setting true then hide tabs
    //= admin option to enable / disable tabs on a per vehicle basis has been disabled
    $vin_query_decode = cd_get_car( $post_id );
    if ( isset( $vin_query_decode['hide_tabs'] ) ) {
        if ( $vin_query_decode['hide_tabs'] == 'Yes' ) {
            $include_options = 1;
        }
    }
    if ( $include_options == 1 ) {
        if ( count( $vehicle_options_array ) > 0 && ! empty ( $vehicle_options_array[0] ) ) {
            $vehicle_options = '<table class="decode_table">';
            $vehicle_options .= '<tr class="decode_table_header">
                                    <td><strong>' . __( 'Vehicle Options', 'car-demon' ) . '</strong></td>
                                    <td></td>
                                  </tr>';
            foreach ( $vehicle_options_array as $vehicle_option ) {
                if ( ! empty( $vehicle_option ) ) {
                    if ( $flag == true ) {
                        $class = 'decode_table_even';
                        $flag = false;
                    } else {
                        $class = 'decode_table_odd';
                        $flag = true;
                    }
                    $vehicle_options .= '<tr class="' . $class . '">
                        <td class="decode_table_label">' . $vehicle_option . '</td>
                        <td>' . $options_image . '</td>
                        </tr>';
                }
            }
            $vehicle_options .= '</table>';
            $included_options = $vehicle_options;
        }
    }
    if ( $car_demon_options['use_about'] == 'Yes' ) {
        $about = 1;
        $tab_cnt = $tab_cnt + 1;
    } else {
        $about = '';
    }
    $car = car_demon_get_car($post_id);
    if ( ! empty( $car_demon_options['vinquery_id'] ) ) {
        if ( $car['year'] > 1984 ) {
            car_demon_get_vin_query( $post_id, $car['vin'] );
        }
    }

    $vin_query_decode = cd_get_car( $post_id );

    $content = apply_filters( 'cd_vdp_content_filter', $content, $post_id );
    $content = apply_filters( 'cd_single_car_content_filter', $content, $post_id ); //= deprecated

    //= If we aren't showing the tabs then grab the specs and add it to the content
    //= then add the included options we created above
    if ( $include_options == 1 ) {
        $specs = get_tab_specs( $vin_query_decode, $vehicle_vin, $post_id );
        $content .= $specs . $included_options;
    }

    if ( isset( $car_demon_options['hide_tabs'] ) ) {
        if ( $car_demon_options['hide_tabs'] == 'Yes' ) {
            $vin_query = 0;
        } else {
            $tab_cnt = $tab_cnt + 5;
            $vin_query = 1;
            $about_cnt = 7;
        }
    } else {
        $tab_cnt = $tab_cnt + 5;
        $vin_query = 1;
        $about_cnt = 7;
    }

    if ( isset( $vin_query_decode['hide_tabs'] ) ) {
        if ( $vin_query_decode['hide_tabs'] == 'Yes' ) {
            $vin_query = 0;
        }
    }
    $x = '<div id="car_features_box" class="car_features_box">';
    $x .= '<div class="car_features">';
    $x .= '<ul class="tabs">';
    $x .= '<li class="tab_inactive tab_active"><a href="javascript:car_demon_switch_tabs(1, ' .  $tab_cnt . ', \'tab_\', \'content_\');" id="tab_1" class="active">' . __( 'Description', 'car-demon' ) . '</a></li>';
    if ($vin_query == 1) {
        $x .= '<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(2, ' . $tab_cnt . ', \'tab_\', \'content_\');" id="tab_2">' . __( 'Specs', 'car-demon' ) . '</a></li>';
        $x .= '<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(3, ' . $tab_cnt . ', \'tab_\', \'content_\');" id="tab_3">' . __( 'Safety', 'car-demon' ) . '</a></li>';
        $x .= '<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(4, ' . $tab_cnt . ', \'tab_\', \'content_\');" id="tab_4">' . __( 'Convenience', 'car-demon' ) . '</a></li>';
        $x .= '<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(5, ' . $tab_cnt . ', \'tab_\', \'content_\');" id="tab_5">' . __( 'Comfort', 'car-demon' ) . '</a></li>';
        $x .= '<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs(6, ' . $tab_cnt . ', \'tab_\', \'content_\');" id="tab_6">' . __( 'Entertainment', 'car-demon' ) . '</a></li>';
    }
    if ($about == 1) {
        $x .= '<li class="tab_inactive"><a href="javascript:car_demon_switch_tabs( '.  $about_cnt .', '.  $tab_cnt .', \'tab_\', \'content_\');" id="tab_'.  $about_cnt . '">' . __( 'About', 'car-demon' ) . '</a></li>';
    }
    $x .= '</ul>';
    $x .= '<div id="$" class="car_features_content">' .  $content . '</div>';
    if ( $vin_query == 1 ) {
        $specs = get_tab_specs( $vin_query_decode, $vehicle_vin, $post_id );
        $safety = get_option_tab( 'safety', $post_id );
        $convienience = get_option_tab( 'convenience', $post_id );
        $comfort = get_option_tab( 'comfort', $post_id );
        $entertainment = get_option_tab( 'entertainment', $post_id );

        $x .= '<div class="single-car-description-label">FEATURES</div>';
        $x .= '<div class="single-car-description-label-line"></div>';
        $x .= '<div id="content_2" class="car_features_content">' . $specs . '</div>';

        $x .= '<div id="content_3" class="car_features_content">' . $safety . '</div>';
        $x .= '<div id="content_4" class="car_features_content">' . $convienience . '</div>';
        $x .= '<div id="content_5" class="car_features_content">' . $comfort . '</div>';
        $x .= '<div id="content_6" class="car_features_content">' . $entertainment . '</div>';
    }
    if ( $about == 1 ) {
        $x .= '<div id="content_' . $about_cnt . '" class="car_features_content car_features_content_about">';
        if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries
            $x .= '<div id="entry-author-info">';
            if ( isset( $_COOKIE['sales_code'] ) ) {
                if ( $_COOKIE['sales_code'] ) {
                    $user_id = $_COOKIE['sales_code'];
                    $user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
                    $location_approved = 0;
                    if ( $vehicle_location == $user_location ) {
                        $location_approved = 1;
                    } else {
                        $location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
                    }
                }
            }
            if ( empty( $location_approved ) ) {
                $location_approved = 0;
                $user_sales_type = 0;
            }
            if ( $location_approved == 1 ) {
                $user_sales_type = 0;
                if ( $vehicle_condition == 'New' ) {
                    $user_sales_type = get_the_author_meta( 'lead_new_cars', $user_id );
                }
                else {
                    $user_sales_type = get_the_author_meta( 'lead_used_cars', $user_id );
                }
            }
            if ( $user_sales_type == 1 ) {
                $x .= build_user_hcard( $_COOKIE['sales_code'], 1 );
            }
            else {
                $x .= build_location_hcard( $vehicle_location, $vehicle_condition );
            }
            $x .= html_entity_decode( get_about_us_tab( $post_id ) . 'test' );
        else:
            $x .= get_about_us_tab( $post_id );
        endif;
        $x .= '</div>';
    }
    $x .= '</div>';
    $x .= '</div>';
    return $x;
}

function get_tab_specs( $vin_query_decode, $vehicle_vin, $post_id ) {
    //= Find out which of the default fields are hidden
    $show_hide = get_show_hide_fields();
    //= Get the labels for the default fields
    $field_labels = get_default_field_labels();
    $x = '<table class="decode_table">';
    $vehicle_condition = get_cd_term( $post_id, 'vehicle_condition' );
    $x .= '<tr class="decode_table_odd">
            <td class="decode_table_label">' . 'Condition' . '</td>
            <td>' . $vehicle_condition . '</td>
            </tr>';

    if ( isset( $vin_query_decode['decoded_model'] ) ) {
        if ( $show_hide['model'] != true ) {
            $x .= '<tr class="decode_table_odd">
                    <td class="decode_table_label">' . $field_labels['model'] . '</td>
                    <td>' . $vin_query_decode["decoded_model"] . '</td>
                    </tr>';
        }
    }
    /* RS: begin add vehicle year to feature list	*/
    $vehicle_year = get_cd_term( $post_id, 'vehicle_year' );
    $x .= '<tr class="decode_table_odd">
            <td class="decode_table_label">' . 'Model Year' . '</td>
            <td>' . $vehicle_year . '</td>
            </tr>';
    /* RS: end add vehicle year to feature list */
    if ( isset( $vin_query_decode['decoded_body_style'] ) ) {
        if ( $show_hide['body_style'] != true ) {
            $x .= '<tr class="decode_table_odd">
                    <td class="decode_table_label">' . $field_labels['body_style'] . '</td>
                    <td>' . $vin_query_decode["decoded_body_style"] . '</td>
                    </tr>';
        }
    }

    $x .= custom_spec_field( $post_id, __( 'Exterior', 'car-demon' ), 'exterior_color', 'odd', $vin_query_decode, $restrict );
    $x .= custom_spec_field( $post_id, __( 'Interior', 'car-demon' ), 'interior_color', 'odd', $vin_query_decode, $restrict );
    // TODO: doors

    $vehicle_details = car_demon_get_car($post_id);

    if (!empty($vehicle_details['stock_number'])) {
        $stock_number = $vehicle_details['stock_number'];
        $x .= '<tr class="decode_table_header">';
        $x .= '<td>' . 'Stock' . '</td>';
        $x .= '<td>' . $stock_number . '</td>';
        $x .= '</tr>';
    }

    // TODO: model number ?


    if ( $show_hide['vin'] != true ) {
        $x .= '<tr class="decode_table_header">';
        $x .= '<td>' . $field_labels['vin'] . '</td>';
        $x .= '<td>' . $vehicle_vin . '</td>';
        $x .= '</tr>';
    }

    $x .= custom_spec_field( $post_id, 'Wheels', 'wheels', 'even', $vin_query_decode, $restrict );
    $x .= custom_spec_field( $post_id, 'Calipers', 'brakes', 'even', $vin_query_decode, $restrict );

    // NEW CAR FIELDS START HERE...

    // NEW CAR FIELDS END HERE...



    /*
    if ( isset( $vin_query_decode['decoded_model_year'] ) ) {
        if ( $show_hide['year'] != true ) {
            $x .= '<tr class="decode_table_odd">
                <td class="decode_table_label">' . $field_labels['year'] . '</td>
                <td>' . $vin_query_decode['decoded_model_year'] . '</td>
                </tr>';
        }
    }
    if ( isset( $vin_query_decode['decoded_make'] ) ) {
        if ( $show_hide['make'] != true ) {
            $x .= '<tr class="decode_table_even">
                <td class="decode_table_label">' . $field_labels['make'] . '</td>
                <td>' . $vin_query_decode["decoded_make"] . '</td>
                </tr>';
        }
    }
    */
    $x = apply_filters( 'cd_pre_specs_filter', $x, $post_id );

    $car_demon_options = car_demon_options();
    if ( isset( $car_demon_options['show_custom_specs'] ) ) {
        $show_custom_specs = $car_demon_options['show_custom_specs'];
    } else {
        $show_custom_specs = 'No';
    }

    // Get CD Capabilities
    $cd_spec_caps = get_cd_spec_caps();

    /*
            //= BEGIN CUSTOM SPEC CODE
            if ( $show_custom_specs == 'Yes' ) {
                $map = cd_get_vehicle_map();
                $specs_map = $map['specs'];
                foreach ( $specs_map as $key=>$spec_group ) {
                    $x .= '<tr class="decode_table_header">
                            <td colspan="2"><strong>' . $key . '</strong></td>
                        </tr>';

                    //= get restrictions
                    $group_slug = cd_clean_cap_slug( $key );
                    $restrict = false;

                    if ( $cd_spec_caps[ $group_slug ] != 'read' ) {
                        if ( ! current_user_can( $cd_spec_caps[ $group_slug ] ) ) {
                            if ( defined( 'CD_RESTRICT_SPECS_MSG' ) ) {
                                if ( ! defined( 'CD_RESTRICT_SPECS_ALL_MSS' ) ) {
                                    $x .= '
                                        <tr class="decode_table_even cd_restrict_spec_row">
                                            <td class="cd_restrict_spec" colspan="2">' . CD_RESTRICT_SPECS_MSG . '</td>
                                        </tr>
                                    ';
                                }
                                $restrict = true;
                                if ( ! defined( 'CD_RESTRICT_SPECS_ALL_MSS' ) ) {
                                    break;
                                }
                            }
                        }
                    }

                    $spec_group_array = explode( ',', $spec_group );
                    $odd_even = 'even';
                    foreach( $spec_group_array as $spec_item ) {
                        if($odd_even == 'odd') { $odd_even = 'even'; } else {$odd_even = 'odd';}
                        $spec_item_slug = trim( $spec_item );
                        $spec_item_slug = strtolower( $spec_item_slug );
                        $spec_item_slug = str_replace( ' ', '_', $spec_item_slug );
                        $x .= custom_spec_field( $post_id, $spec_item, 'decoded_'.$spec_item_slug, $odd_even, $vin_query_decode, $restrict );
                    }
                }
            } else {
                $restrict = false;
                $x .= '<div class="label-specification">MTV</div>';

                $x .= '<tr class="decode_table_header">
                        <td colspan="2"><strong>' . __( 'Specifications', 'car-demon' ) . '</strong></td>
                    </tr>';
                $x .= custom_spec_field( $post_id, __( 'Trim', 'car-demon' ), 'decoded_trim_level', 'odd', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Production Seq. Number', 'car-demon' ), 'decoded_production_seq_number', 'even', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Exterior Color', 'car-demon' ), 'exterior_color', 'odd', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Interior Color', 'car-demon' ), 'interior_color', 'even', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Manufactured in', 'car-demon' ), 'decoded_manufactured_in', 'odd', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Engine Type', 'car-demon' ), 'decoded_engine_type', 'even', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Transmission', 'car-demon' ), 'decoded_transmission_long', 'odd', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Driveline', 'car-demon' ), 'decoded_driveline', 'even', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Tank(gallon)', 'car-demon' ), 'decoded_driveline', 'odd', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Fuel Economy (City, miles/gallon)', 'car-demon' ), 'decoded_fuel_economy_city', 'even', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Fuel Economy (Highway, miles/gallon)', 'car-demon' ), 'decoded_fuel_economy_highway', 'odd', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Anti-Brake System', 'car-demon' ), 'decoded_anti_brake_system', 'even', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Steering Type', 'car-demon' ), 'decoded_steering_type', 'odd', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Length(in.)', 'car-demon' ), 'decoded_overall_length', 'even', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Width(in.)', 'car-demon' ), 'decoded_overall_width', 'odd', $vin_query_decode, $restrict );
                $x .= custom_spec_field( $post_id, __( 'Height(in.)', 'car-demon' ), 'decoded_overall_height', 'even', $vin_query_decode, $restrict );
            }
    */

    $x = apply_filters( 'cd_specs_filter', $x, $post_id );
    //= END CUSTOM SPECS
    $disclaimer = '<tr class="decode_table_header">
            <td class="disclaimerrow" colspan="2"><strong>' . __( 'Disclaimer', 'car-demon' ) . '</strong></td>
            </tr>';
    $disclaimer .= '<tr>
            <td colspan="2"><div class="decode_disclaimer">';

    $disclaimer_text = __( 'ALTHOUGH THIS SITE CHECKS REGULARLY WITH ITS DATA SOURCES TO CONFIRM THE ACCURACY AND COMPLETENESS OF THE DATA,', 'car-demon' ).'<br />
                '.__( 'IT MAKES NO GUARANTY OR WARRANTY, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTY OR MERCHANTABILITY', 'car-demon' ).'<br />
                '.__( 'OR FITNESS FOR PARTICULAR PURPOSE, WITH RESPECT TO THE DATA PRESENTED IN THIS REPORT. USER ASSUMES ALL RISKS IN USING ANY', 'car-demon' ).'<br />
                '.__( 'DATA IN THIS REPORT FOR HIS OR HER OWN APPLICATIONS. ALL DATA IN THIS REPORT ARE SUBJECT TO CHANGE WITHOUT NOTICE.', 'car-demon' );
    $disclaimer_text = apply_filters( 'cd_disclaimer_text_filter', $disclaimer_text );
    $disclaimer_text = apply_filters( 'car_demon_disclaimer_text_filter', $disclaimer_text ); //= deprecated
    $disclaimer .= $disclaimer_text;

    $disclaimer .= '</div></td>
          </tr>';

    $disclaimer = apply_filters( 'cd_disclaimer_filter', $disclaimer );
    $disclaimer = apply_filters( 'car_demon_disclaimer_filter', $disclaimer ); //= deprecated
    $x .= $disclaimer;

    $x .= '</table>';
    $car_demon_pluginpath = CAR_DEMON_PATH;
    $standard_img = '<img src="' . $car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="' . __( 'Standard Option', 'car-demon' ) . '" alt="' . __( 'Standard Option', 'car-demon' ) . '" />';
    $x = str_replace( "Std.", $standard_img, $x );
    $opt_img = '<img src="' . $car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="' . __( 'Optional', 'car-demon' ) . '" alt="' . __( 'Optional', 'car-demon' ) . '" />';
    $x = str_replace("Opt.", $opt_img, $x);
    $na_img = '<img src="' . $car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="' . __( 'NA', 'car-demon' ) . '" alt="' . __( 'NA', 'car-demon' ) . '" />';
    $x = str_replace( "N/A", $na_img, $x );
    return $x;
}

function custom_spec_field( $post_id, $field, $slug, $odd_even, $vin_query_decode, $restrict ) {
    $x = '';
    if ( isset( $vin_query_decode[$slug] ) ) {$value = $vin_query_decode[$slug]; } else {$value = ''; }

    if ( empty( $value ) ) {
        $slug = str_replace( 'decoded_', '', $slug );
        if ( isset( $vin_query_decode[$slug] ) ) {$value = $vin_query_decode[$slug]; } else {$value = ''; }
    }

    if ( ! empty( $value ) ) {
        if ( $restrict  ) {
            if ( defined( 'CD_RESTRICT_SPECS_MSG' ) ) {
                $value = CD_RESTRICT_SPECS_MSG;
            }
        }
        $x = '
          <tr class="decode_table_' . $odd_even . '">
            <td class="decode_table_label">' . $field . '</td>
            <td class="' . $slug . '">' . $value . '</td>
          </tr>
        ';
    }
    return $x;
}

function get_option_tab( $tab, $post_id, $type='' ) {
    $car_demon_pluginpath = CAR_DEMON_PATH;
    $car_demon_pluginpath = str_replace( 'vin-query', '', $car_demon_pluginpath );
    $vin_query_decode_array = get_post_meta( $post_id, 'decode_string' );
    if ( $vin_query_decode_array ) {
        $vin_query_decode = $vin_query_decode_array[0];
    } else {
        $vin_query_decode = '';
    }
    $vehicle_option_array = get_post_meta( $post_id, '_vehicle_options', true );
    $vehicle_option_array = explode( ',', $vehicle_option_array );
    $map = cd_get_vehicle_map();
    $cd_spec_caps = get_cd_spec_caps();

    $flag = '';
    $x = '
    <table class="decode_table">';
    $x .= '<tr class="decode_table_header">
        <td colspan="2"><strong>' . __( 'Legend', 'car-demon' ) . '</strong></td>
      </tr>';

    if ( ! defined( 'CD_LEGEND_ON_BOTTOM' ) ) {
        $x .= '<tr>
            <td colspan="2">' . __( 'Std. - Standard: indicates a manufacturer-installed feature that comes standard.', 'car-demon' ) . '<br/>
              '.__( 'Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.', 'car-demon' ) . '<br/>
              '.__( 'N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.', 'car-demon' ) . '</td>
          </tr>';
    }
    if ( isset( $map[$tab] ) ) {
        foreach( $map[$tab] as $tab_group => $value ) {
            //= Loop through all of the tab option groups, get their items
            $div_flag = false;

            $group_slug = cd_clean_cap_slug( $tab_group );
            $restrict = false;

            if ( ! current_user_can( $cd_spec_caps[ $group_slug ] ) ) {
                if ( defined( 'CD_RESTRICT_OPTIONS_MSG' ) ) {
                    $restrict = true;
                }
            }

            $x .= '<tr class="decode_table_header">
                <td colspan="2"><strong>' . $tab_group . '</strong></td>
              </tr>';

            if ( ! empty( $value ) ) {
                $option_array = explode( ',', $value );
                if ( $option_array ) {
                    foreach( $option_array as $option ) {
                        if ( $flag == true ) {
                            $class = 'decode_table_even';
                            $flag = false;
                        } else {
                            $class = 'decode_table_odd';
                            $flag = true;
                        }
                        //= Loop through list of items in each group
                        $option = trim( $option );
                        $slug = strtolower( $option );
                        $slug = str_replace( ' ', '_', $slug );
                        $slug = str_replace( '/', '_', $slug );
                        $slug = str_replace( '(', '_', $slug );
                        $slug = str_replace( ')', '_', $slug );
                        $slug = str_replace( '-', '_', $slug );
                        $slug = str_replace( '2_wheel_4_wheel_', 'brakes', $slug );
                        if ( $type == 'admin' ) {
                            if ( isset( $vin_query_decode['decoded_'.$slug] ) ) {
                                $content = decode_select( 'decoded_' . $slug, $vin_query_decode['decoded_' . $slug], $post_id, $restrict );
                            } else {
                                $content = decode_select( 'decoded_' . $slug, '', $post_id, $restrict );
                            }
                            $x .= '<tr class="' . $class . '">
                                <td class="decode_table_label">' . $option . '</td>
                                <td>' . $content . '</td>
                                </tr>';
                        } else {
                            if ( isset( $vin_query_decode['decoded_' . $slug] ) ) {
                                $content = $vin_query_decode['decoded_' . $slug];
                                if (isset($vin_query_decode['decoded_' . $slug]) || isset( $vin_query_decode[$slug] ) || isset( $vin_query_decode['decoded_' . $option] ) || isset( $vin_query_decode[$option] ) ) {
                                    if ( trim( $content ) === 'Std.' ) {
                                        $opt_class = "opt_standard";
                                    } else if ( trim( $content ) === 'Opt.' ) {
                                        $opt_class = "opt_optional";
                                    } else if ( trim( $content ) === 'N/A' ) {
                                        $opt_class = "opt_na";
                                    } else {
                                        $opt_class = "opt_standard";
                                    }
                                    if ( ! empty( $content ) ) {
                                        if ( $restrict ) {
                                            if ( defined( 'CD_RESTRICT_OPTIONS_MSG' ) ) {
                                                $x .= '<tr class="' . $class . ' ' . $opt_class . '">
                                                    <td class="decode_table_label cd_unregistered">' . CD_RESTRICT_OPTIONS_MSG . '</td>
                                                    </tr>';
                                                break;
                                            }
                                        } else {
                                            $x .= '<tr class="' . $class . ' ' . $opt_class . '">
                                                <td class="decode_table_label">' . $option . '</td>
                                                <td>' . $content . '</td>
                                                </tr>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                //= Loop through option meta and get those too
                if ( ! empty( $vehicle_option_array ) ) {
                    foreach( $vehicle_option_array as $option_item ) {
                        $option_item = trim( $option_item );
                        $option_item = ucwords( $option_item );
                        if ( in_array( $option_item, $option_array ) ) {
                            $label = str_replace( '_',' ',$option_item );
                            $label = ucwords( $label );
                            if ( $type == 'admin' ) {
                                $content = decode_select( 'decoded_' . $slug, 'Std.', $post_id, $restrict );
                                $x .= '<tr class="' . $class . '">
                                    <td class="decode_table_label">' . $label . '</td>
                                    <td>' . $content . '</td>
                                    </tr>';
                            } else {
                                $content = 'Std.';
                                if ( is_array( $vin_query_decode ) && ! in_array( 'decoded_' . $slug, $vin_query_decode ) ) {
                                    if ( ! empty( $content ) ) {
                                        $x .= '<tr class="' . $class . '">
                                            <td class="decode_table_label">' . $label . '</td>
                                            <td>' . $content . '</td>
                                            </tr>';
                                    }
                                }

                            }
                        }
                    }
                }
                //= End Option Loop
            }
        }
    }
    $x .= '<tr>
        <td class="lastrowinpage" colspan="2">&nbsp;</td>
        </tr>';

    if ( defined( 'CD_LEGEND_ON_BOTTOM' ) ) {
        $x .= '<tr>
            <td colspan="2">' . __( 'Std. - Standard: indicates a manufacturer-installed feature that comes standard.', 'car-demon' ) . '<br/>
              '.__( 'Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.', 'car-demon' ) . '<br/>
              '.__( 'N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.', 'car-demon' ) . '</td>
          </tr>';
    }

    $disclaimer = '<tr class="decode_table_header">
        <td class="disclaimerrow" colspan="2"><strong>' . __( 'Disclaimer', 'car-demon' ) . '</strong></td>
        </tr>';
    $disclaimer .= '<tr>
        <td colspan="2"><div class="decode_disclaimer">';

    $disclaimer_text = __( 'ALTHOUGH THIS SITE CHECKS REGULARLY WITH ITS DATA SOURCES TO CONFIRM THE ACCURACY AND COMPLETENESS OF THE DATA,', 'car-demon' ) . '<br />
            '.__( 'IT MAKES NO GUARANTY OR WARRANTY, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTY OR MERCHANTABILITY', 'car-demon' ) . '<br />
            '.__( 'OR FITNESS FOR PARTICULAR PURPOSE, WITH RESPECT TO THE DATA PRESENTED IN THIS REPORT. USER ASSUMES ALL RISKS IN USING ANY', 'car-demon' ) . '<br />
            '.__( 'DATA IN THIS REPORT FOR HIS OR HER OWN APPLICATIONS. ALL DATA IN THIS REPORT ARE SUBJECT TO CHANGE WITHOUT NOTICE.', 'car-demon' );
    $disclaimer_text = apply_filters( 'cd_disclaimer_text_filter', $disclaimer_text );
    $disclaimer_text = apply_filters( 'car_demon_disclaimer_text_filter', $disclaimer_text ); //= deprecated
    $disclaimer .= $disclaimer_text;

    $disclaimer .= '</div></td>
      </tr>';

    $disclaimer = apply_filters( 'cd_disclaimer_filter', $disclaimer );
    $disclaimer = apply_filters( 'car_demon_disclaimer_filter', $disclaimer ); //= deprecated
    $x .= $disclaimer;

    $x .= '</table>';
    if ( $type != 'admin' ) {
        $standard_img = '<img src="' . $car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="' . __( 'Standard Option', 'car-demon' ) . '" alt="' . __( 'Standard Option', 'car-demon' ) . '" />';
        $x = str_replace( "Std.", $standard_img, $x );
        $opt_img = '<img src="' . $car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="' . __( 'Optional', 'car-demon' ) . '" alt="' . __( 'Optional', 'car-demon' ) . '" />';
        $x = str_replace( "Opt.", $opt_img, $x );
        $na_img = '<img src="' . $car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="' . __( 'NA', 'car-demon' ) . '" alt="' . __( 'NA', 'car-demon' ) . '" />';
        $x = str_replace( "N/A", $na_img, $x );
    }
    return $x;
}

?>
