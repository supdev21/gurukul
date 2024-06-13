<?php

function educrat_event_customize_register( $wp_customize ) {
    global $wp_registered_sidebars;
    $sidebars = array();

    if ( is_admin() && !empty($wp_registered_sidebars) ) {
        foreach ($wp_registered_sidebars as $sidebar) {
            $sidebars[$sidebar['id']] = $sidebar['name'];
        }
    }
    $columns = array( '1' => esc_html__('1 Column', 'educrat'),
        '2' => esc_html__('2 Columns', 'educrat'),
        '3' => esc_html__('3 Columns', 'educrat'),
        '4' => esc_html__('4 Columns', 'educrat'),
        '5' => esc_html__('5 Columns', 'educrat'),
        '6' => esc_html__('6 Columns', 'educrat'),
        '7' => esc_html__('7 Columns', 'educrat'),
        '8' => esc_html__('8 Columns', 'educrat'),
    );
    
    // Event Panel
    $wp_customize->add_panel( 'educrat_settings_event', array(
        'title' => esc_html__( 'Events Settings', 'educrat' ),
        'priority' => 4,
    ) );

    // General Section
    $wp_customize->add_section('educrat_settings_events_general', array(
        'title'    => esc_html__('General', 'educrat'),
        'priority' => 1,
        'panel' => 'educrat_settings_event',
    ));

    // Breadcrumbs
    $wp_customize->add_setting('educrat_theme_options[events_breadcrumbs]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => 1,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('educrat_theme_options_events_breadcrumbs', array(
        'settings' => 'educrat_theme_options[events_breadcrumbs]',
        'label'    => esc_html__('Breadcrumbs', 'educrat'),
        'section'  => 'educrat_settings_events_general',
        'type'     => 'checkbox',
    ));

    // Breadcrumbs Background Color
    $wp_customize->add_setting('educrat_theme_options[events_breadcrumb_color]', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'events_breadcrumb_color', array(
        'label'    => esc_html__('Breadcrumbs Background Color', 'educrat'),
        'section'  => 'educrat_settings_events_general',
        'settings' => 'educrat_theme_options[events_breadcrumb_color]',
    )));

    // Breadcrumbs Background
    $wp_customize->add_setting('educrat_theme_options[events_breadcrumb_image]', array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',

    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'events_breadcrumb_image', array(
        'label'    => esc_html__('Breadcrumbs Background', 'educrat'),
        'section'  => 'educrat_settings_events_general',
        'settings' => 'educrat_theme_options[events_breadcrumb_image]',
    )));

    // Google Maps API
    $wp_customize->add_setting( 'educrat_theme_options[google_map_api_key]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_events_archive_google_map_api_key', array(
        'label'   => esc_html__('Google Map API Key', 'educrat'),
        'section' => 'educrat_settings_events_general',
        'type'    => 'text',
        'settings' => 'educrat_theme_options[google_map_api_key]'
    ) );

    // Events Archives
    $wp_customize->add_section('educrat_settings_events_archive', array(
        'title'    => esc_html__('Events Archives', 'educrat'),
        'priority' => 2,
        'panel' => 'educrat_settings_event',
    ));

    // General Setting ?
    $wp_customize->add_setting('educrat_theme_options[events_general_setting]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Educrat_WP_Customize_Heading_Control($wp_customize, 'events_general_setting', array(
        'label'    => esc_html__('General Settings', 'educrat'),
        'section'  => 'educrat_settings_events_archive',
        'settings' => 'educrat_theme_options[events_general_setting]',
    )));

    // Is Full Width
    $wp_customize->add_setting('educrat_theme_options[events_fullwidth]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('educrat_theme_options_events_fullwidth', array(
        'settings' => 'educrat_theme_options[events_fullwidth]',
        'label'    => esc_html__('Is Full Width', 'educrat'),
        'section'  => 'educrat_settings_events_archive',
        'type'     => 'checkbox',
    ));

    // layout
    $wp_customize->add_setting( 'educrat_theme_options[events_layout]', array(
        'default'        => 'main',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( new Educrat_WP_Customize_Radio_Image_Control( 
        $wp_customize, 
        'educrat_settings_events_archive_layout', 
        array(
            'label'   => esc_html__('Layout Type', 'educrat'),
            'section' => 'educrat_settings_events_archive',
            'type'    => 'select',
            'choices' => array(
                'main' => array(
                    'title' => esc_html__('Main Only', 'educrat'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                ),
                'left-main' => array(
                    'title' => esc_html__('Left - Main Sidebar', 'educrat'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                ),
                'main-right' => array(
                    'title' => esc_html__('Main - Right Sidebar', 'educrat'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                ),
            ),
            'settings' => 'educrat_theme_options[events_layout]',
            'description' => esc_html__('Select the variation you want to apply on your event/archive page.', 'educrat'),
        ) 
    ));

    // Left Sidebar
    $wp_customize->add_setting( 'educrat_theme_options[events_left_sidebar]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_events_archive_events_left_sidebar', array(
        'label'   => esc_html__('Archive Left Sidebar', 'educrat'),
        'section' => 'educrat_settings_events_archive',
        'type'    => 'select',
        'choices' => $sidebars,
        'settings' => 'educrat_theme_options[events_left_sidebar]',
        'description' => esc_html__('Choose a sidebar for left sidebar', 'educrat'),
    ) );

    // Right Sidebar
    $wp_customize->add_setting( 'educrat_theme_options[events_right_sidebar]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_events_archive_events_right_sidebar', array(
        'label'   => esc_html__('Archive Right Sidebar', 'educrat'),
        'section' => 'educrat_settings_events_archive',
        'type'    => 'select',
        'choices' => $sidebars,
        'settings' => 'educrat_theme_options[events_right_sidebar]',
        'description' => esc_html__('Choose a sidebar for right sidebar', 'educrat'),
    ) );

    // Events Display Mode
    $wp_customize->add_setting( 'educrat_theme_options[events_display_mode]', array(
        'default'        => 'grid',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_events_archive_events_display_mode', array(
        'label'   => esc_html__('Events Layout', 'educrat'),
        'section' => 'educrat_settings_events_archive',
        'type'    => 'select',
        'choices' => array(
            'grid' => esc_html__('Grid', 'educrat'),
            'list' => esc_html__('List', 'educrat'),
        ),
        'settings' => 'educrat_theme_options[events_display_mode]',
    ) );

    // Item Style
    $wp_customize->add_setting( 'educrat_theme_options[events_item_style]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_events_archive_events_item_style', array(
        'label'   => esc_html__('Event Item Style', 'educrat'),
        'section' => 'educrat_settings_events_archive',
        'type'    => 'select',
        'choices' => array(
            '' => esc_html__('Default', 'educrat'),
            'v1' => esc_html__('V1', 'educrat'),
            'v2' => esc_html__('V2', 'educrat'),
            'list' => esc_html__('List', 'educrat'),
        ),
        'settings' => 'educrat_theme_options[events_item_style]',
    ) );

    // events Columns
    $wp_customize->add_setting( 'educrat_theme_options[events_columns]', array(
        'default'        => '4',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_events_archive_events_columns', array(
        'label'   => esc_html__('Events Columns', 'educrat'),
        'section' => 'educrat_settings_events_archive',
        'type'    => 'select',
        'choices' => $columns,
        'settings' => 'educrat_theme_options[events_columns]',
    ) );

    // Number of Events Per Page
    $wp_customize->add_setting( 'educrat_theme_options[number_events_per_page]', array(
        'default'        => '12',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_events_archive_number_events_per_page', array(
        'label'   => esc_html__('Number of Events Per Page', 'educrat'),
        'section' => 'educrat_settings_events_archive',
        'type'    => 'number',
        'settings' => 'educrat_theme_options[number_events_per_page]',
    ) );


    



    // Single Event
    $wp_customize->add_section('educrat_settings_event_single', array(
        'title'    => esc_html__('Single Event', 'educrat'),
        'priority' => 3,
        'panel' => 'educrat_settings_event',
    ));

    // General Setting ?
    $wp_customize->add_setting('educrat_theme_options[event_general_setting]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Educrat_WP_Customize_Heading_Control($wp_customize, 'event_general_setting', array(
        'label'    => esc_html__('General Settings', 'educrat'),
        'section'  => 'educrat_settings_event_single',
        'settings' => 'educrat_theme_options[event_general_setting]',
    )));


    // Is Full Width
    $wp_customize->add_setting('educrat_theme_options[event_fullwidth]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('educrat_theme_options_event_fullwidth', array(
        'settings' => 'educrat_theme_options[event_fullwidth]',
        'label'    => esc_html__('Is Full Width', 'educrat'),
        'section'  => 'educrat_settings_event_single',
        'type'     => 'checkbox',
    ));

    // layout
    $wp_customize->add_setting( 'educrat_theme_options[event_layout]', array(
        'default'        => 'main',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( new Educrat_WP_Customize_Radio_Image_Control( 
        $wp_customize, 
        'educrat_settings_event_single_layout', 
        array(
            'label'   => esc_html__('Layout Type', 'educrat'),
            'section' => 'educrat_settings_event_single',
            'type'    => 'select',
            'choices' => array(
                'main' => array(
                    'title' => esc_html__('Main Only', 'educrat'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                ),
                'left-main' => array(
                    'title' => esc_html__('Left - Main Sidebar', 'educrat'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                ),
                'main-right' => array(
                    'title' => esc_html__('Main - Right Sidebar', 'educrat'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                ),
            ),
            'settings' => 'educrat_theme_options[event_layout]',
            'description' => esc_html__('Select the variation you want to apply on your event/archive page.', 'educrat'),
        ) 
    ));

    // Left Sidebar
    $wp_customize->add_setting( 'educrat_theme_options[event_left_sidebar]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_event_single_event_left_sidebar', array(
        'label'   => esc_html__('Archive Left Sidebar', 'educrat'),
        'section' => 'educrat_settings_event_single',
        'type'    => 'select',
        'choices' => $sidebars,
        'settings' => 'educrat_theme_options[event_left_sidebar]',
        'description' => esc_html__('Choose a sidebar for left sidebar', 'educrat'),
    ) );

    // Right Sidebar
    $wp_customize->add_setting( 'educrat_theme_options[event_right_sidebar]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_event_single_event_right_sidebar', array(
        'label'   => esc_html__('Archive Right Sidebar', 'educrat'),
        'section' => 'educrat_settings_event_single',
        'type'    => 'select',
        'choices' => $sidebars,
        'settings' => 'educrat_theme_options[event_right_sidebar]',
        'description' => esc_html__('Choose a sidebar for right sidebar', 'educrat'),
    ) );



    // Show Social Share
    $wp_customize->add_setting('educrat_theme_options[show_event_social_share]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => 1,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('educrat_theme_options_show_event_social_share', array(
        'settings' => 'educrat_theme_options[show_event_social_share]',
        'label'    => esc_html__('Show Social Share', 'educrat'),
        'section'  => 'educrat_settings_event_single',
        'type'     => 'checkbox',
    ));
    
}
add_action( 'customize_register', 'educrat_event_customize_register', 15 );