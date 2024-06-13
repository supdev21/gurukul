<?php

function educrat_tutor_customize_register( $wp_customize ) {
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
    
    // Course Panel
    $wp_customize->add_panel( 'educrat_settings_tutor_course', array(
        'title' => esc_html__( 'Tutor Courses Settings', 'educrat' ),
        'priority' => 4,
    ) );

    // General Section
    $wp_customize->add_section('educrat_settings_tutor_course_general', array(
        'title'    => esc_html__('General', 'educrat'),
        'priority' => 1,
        'panel' => 'educrat_settings_tutor_course',
    ));

    // Breadcrumbs
    $wp_customize->add_setting('educrat_theme_options[tutor_courses_breadcrumbs]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => 1,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('educrat_theme_options_tutor_courses_breadcrumbs', array(
        'settings' => 'educrat_theme_options[tutor_courses_breadcrumbs]',
        'label'    => esc_html__('Breadcrumbs', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_general',
        'type'     => 'checkbox',
    ));

    // Breadcrumbs Background Color
    $wp_customize->add_setting('educrat_theme_options[tutor_courses_breadcrumb_color]', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'tutor_courses_breadcrumb_color', array(
        'label'    => esc_html__('Breadcrumbs Background Color', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_general',
        'settings' => 'educrat_theme_options[tutor_courses_breadcrumb_color]',
    )));

    // Breadcrumbs Background
    $wp_customize->add_setting('educrat_theme_options[tutor_courses_breadcrumb_image]', array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',

    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'tutor_courses_breadcrumb_image', array(
        'label'    => esc_html__('Breadcrumbs Background', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_general',
        'settings' => 'educrat_theme_options[tutor_courses_breadcrumb_image]',
    )));


    // Courses Archives
    $wp_customize->add_section('educrat_settings_tutor_course_archive', array(
        'title'    => esc_html__('Courses Archives', 'educrat'),
        'priority' => 2,
        'panel' => 'educrat_settings_tutor_course',
    ));

    // General Setting ?
    $wp_customize->add_setting('educrat_theme_options[tutor_courses_general_setting]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Educrat_WP_Customize_Heading_Control($wp_customize, 'tutor_courses_general_setting', array(
        'label'    => esc_html__('General Settings', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_archive',
        'settings' => 'educrat_theme_options[tutor_courses_general_setting]',
    )));

    // Is Full Width
    $wp_customize->add_setting('educrat_theme_options[tutor_courses_fullwidth]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('educrat_theme_options_tutor_courses_fullwidth', array(
        'settings' => 'educrat_theme_options[tutor_courses_fullwidth]',
        'label'    => esc_html__('Is Full Width', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_archive',
        'type'     => 'checkbox',
    ));

    // layout
    $wp_customize->add_setting( 'educrat_theme_options[tutor_courses_layout]', array(
        'default'        => 'main',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( new Educrat_WP_Customize_Radio_Image_Control( 
        $wp_customize, 
        'educrat_settings_tutor_course_archive_layout', 
        array(
            'label'   => esc_html__('Layout Type', 'educrat'),
            'section' => 'educrat_settings_tutor_course_archive',
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
            'settings' => 'educrat_theme_options[tutor_courses_layout]',
            'description' => esc_html__('Select the variation you want to apply on your course/archive page.', 'educrat'),
        ) 
    ));

    // Courses Filter
    $wp_customize->add_setting( 'educrat_theme_options[tutor_courses_filter_layout]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_tutor_course_archive_tutor_courses_filter_layout', array(
        'label'   => esc_html__('Courses Filter Layout', 'educrat'),
        'section' => 'educrat_settings_tutor_course_archive',
        'type'    => 'select',
        'choices' => array(
            '' => esc_html__('None', 'educrat'),
            'top' => esc_html__('Filter Top', 'educrat'),
            'offcanvas' => esc_html__('Filter Offcanvas', 'educrat'),
        ),
        'settings' => 'educrat_theme_options[tutor_courses_filter_layout]',
    ) );

    // Courses Layout
    $wp_customize->add_setting( 'educrat_theme_options[tutor_courses_display_mode]', array(
        'default'        => 'grid',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_tutor_course_archive_tutor_courses_display_mode', array(
        'label'   => esc_html__('Courses Layout', 'educrat'),
        'section' => 'educrat_settings_tutor_course_archive',
        'type'    => 'select',
        'choices' => array(
            'grid' => esc_html__('Grid', 'educrat'),
            'list' => esc_html__('List', 'educrat'),
        ),
        'settings' => 'educrat_theme_options[tutor_courses_display_mode]',
    ) );

    // Item Style
    $wp_customize->add_setting( 'educrat_theme_options[tutor_courses_item_style]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_tutor_course_single_tutor_courses_item_style', array(
        'label'   => esc_html__('Courses Grid Item', 'educrat'),
        'section' => 'educrat_settings_tutor_course_archive',
        'type'    => 'select',
        'choices' => array(
            '' => esc_html__('Grid 1', 'educrat'),
            '2' => esc_html__('Grid 2', 'educrat'),
            '3' => esc_html__('Grid 3', 'educrat'),
            '4' => esc_html__('Grid 4', 'educrat'),
            'list' => esc_html__('List 1', 'educrat'),
            'list-v2' => esc_html__('List 2', 'educrat'),
            'list-v3' => esc_html__('List 3', 'educrat'),
            'list-v4' => esc_html__('List 4', 'educrat'),
            'list-v5' => esc_html__('List 5', 'educrat'),
        ),
        'settings' => 'educrat_theme_options[tutor_courses_item_style]',
    ) );

    // courses Columns
    $wp_customize->add_setting( 'educrat_theme_options[tutor_courses_columns]', array(
        'default'        => '4',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_tutor_course_archive_tutor_courses_columns', array(
        'label'   => esc_html__('Courses Columns', 'educrat'),
        'section' => 'educrat_settings_tutor_course_archive',
        'type'    => 'select',
        'choices' => $columns,
        'settings' => 'educrat_theme_options[tutor_courses_columns]',
    ) );

    



    // Single Course
    $wp_customize->add_section('educrat_settings_tutor_course_single', array(
        'title'    => esc_html__('Single Course', 'educrat'),
        'priority' => 3,
        'panel' => 'educrat_settings_tutor_course',
    ));

    // General Setting ?
    $wp_customize->add_setting('educrat_theme_options[tutor_course_general_setting]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Educrat_WP_Customize_Heading_Control($wp_customize, 'tutor_course_general_setting', array(
        'label'    => esc_html__('General Settings', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_single',
        'settings' => 'educrat_theme_options[tutor_course_general_setting]',
    )));


    // Course Layout
    $wp_customize->add_setting( 'educrat_theme_options[tutor_course_layout_type]', array(
        'default'        => 'grid',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_tutor_course_single_tutor_course_layout_type', array(
        'label'   => esc_html__('Course Layout', 'educrat'),
        'section' => 'educrat_settings_tutor_course_single',
        'type'    => 'select',
        'choices' => array(
            'v1' => esc_html__('Layout 1', 'educrat'),
            'v2' => esc_html__('Layout 2', 'educrat'),
            'v3' => esc_html__('Layout 3', 'educrat'),
            'v4' => esc_html__('Layout 4', 'educrat'),
            'v5' => esc_html__('Layout 5', 'educrat'),
            'v6' => esc_html__('Layout 6', 'educrat'),
        ),
        'settings' => 'educrat_theme_options[tutor_course_layout_type]',
    ) );

    // Show Social Share
    $wp_customize->add_setting('educrat_theme_options[tutor_show_course_social_share]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => 1,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('educrat_theme_options_tutor_show_course_social_share', array(
        'settings' => 'educrat_theme_options[tutor_show_course_social_share]',
        'label'    => esc_html__('Show Social Share', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_single',
        'type'     => 'checkbox',
    ));

    // Show Course Review Tab
    $wp_customize->add_setting('educrat_theme_options[tutor_show_course_review_tab]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => 1,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('educrat_theme_options_tutor_show_course_review_tab', array(
        'settings' => 'educrat_theme_options[tutor_show_course_review_tab]',
        'label'    => esc_html__('Show Course Review Tab', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_single',
        'type'     => 'checkbox',
    ));

    // Course Block Setting ?
    $wp_customize->add_setting('educrat_theme_options[tutor_course_block_setting]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Educrat_WP_Customize_Heading_Control($wp_customize, 'tutor_course_block_setting', array(
        'label'    => esc_html__('Course Block Settings', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_single',
        'settings' => 'educrat_theme_options[tutor_course_block_setting]',
    )));

    // Show Courses Related
    $wp_customize->add_setting('educrat_theme_options[tutor_show_course_related]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => 1,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('educrat_theme_options_tutor_show_course_related', array(
        'settings' => 'educrat_theme_options[tutor_show_course_related]',
        'label'    => esc_html__('Show Courses Related', 'educrat'),
        'section'  => 'educrat_settings_tutor_course_single',
        'type'     => 'checkbox',
    ));

    // Number related courses
    $wp_customize->add_setting( 'educrat_theme_options[tutor_number_course_related]', array(
        'default'        => '4',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_tutor_course_single_tutor_number_course_related', array(
        'label'   => esc_html__('Number related courses', 'educrat'),
        'section' => 'educrat_settings_tutor_course_single',
        'type'    => 'number',
        'settings' => 'educrat_theme_options[tutor_number_course_related]',
    ) );

    // Related Courses Columns
    $wp_customize->add_setting( 'educrat_theme_options[tutor_related_course_columns]', array(
        'default'        => '4',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educrat_settings_tutor_course_single_tutor_related_course_columns', array(
        'label'   => esc_html__('Related Courses Columns', 'educrat'),
        'section' => 'educrat_settings_tutor_course_single',
        'type'    => 'select',
        'choices' => $columns,
        'settings' => 'educrat_theme_options[tutor_related_course_columns]',
    ) );
    
}
add_action( 'customize_register', 'educrat_tutor_customize_register', 15 );