<?php

if ( !function_exists( 'educrat_page_metaboxes' ) ) {
	function educrat_page_metaboxes(array $metaboxes) {
		global $wp_registered_sidebars;
        $sidebars = array();

        if ( !empty($wp_registered_sidebars) ) {
            foreach ($wp_registered_sidebars as $sidebar) {
                $sidebars[$sidebar['id']] = $sidebar['name'];
            }
        }
        $headers = array_merge( array('global' => esc_html__( 'Global Setting', 'educrat' )), educrat_get_header_layouts() );
        $footers = array_merge( array('global' => esc_html__( 'Global Setting', 'educrat' )), educrat_get_footer_layouts() );

		$prefix = 'apus_page_';
        
        // General
	    $fields = array(
			array(
				'name' => esc_html__( 'Select Layout', 'educrat' ),
				'id'   => $prefix.'layout',
				'type' => 'select',
				'options' => array(
					'main' => esc_html__('Main Content Only', 'educrat'),
					'left-main' => esc_html__('Left Sidebar - Main Content', 'educrat'),
					'main-right' => esc_html__('Main Content - Right Sidebar', 'educrat')
				)
			),
			array(
                'id' => $prefix.'fullwidth',
                'type' => 'select',
                'name' => esc_html__('Is Full Width?', 'educrat'),
                'default' => 'no',
                'options' => array(
                    'no' => esc_html__('No', 'educrat'),
                    'yes' => esc_html__('Yes', 'educrat')
                )
            ),
            array(
                'id' => $prefix.'left_sidebar',
                'type' => 'select',
                'name' => esc_html__('Left Sidebar', 'educrat'),
                'options' => $sidebars
            ),
            array(
                'id' => $prefix.'right_sidebar',
                'type' => 'select',
                'name' => esc_html__('Right Sidebar', 'educrat'),
                'options' => $sidebars
            ),
            array(
                'id' => $prefix.'show_breadcrumb',
                'type' => 'select',
                'name' => esc_html__('Show Breadcrumb?', 'educrat'),
                'options' => array(
                    'no' => esc_html__('No', 'educrat'),
                    'yes' => esc_html__('Yes', 'educrat')
                ),
                'default' => 'yes',
            ),
            array(
                'id' => $prefix.'breadcrumb_color',
                'type' => 'colorpicker',
                'name' => esc_html__('Breadcrumb Background Color', 'educrat')
            ),
            array(
                'id' => $prefix.'breadcrumb_image',
                'type' => 'file',
                'name' => esc_html__('Breadcrumb Background Image', 'educrat')
            ),

            array(
                'id' => $prefix.'header_type',
                'type' => 'select',
                'name' => esc_html__('Header Layout Type', 'educrat'),
                'description' => esc_html__('Choose a header for your website.', 'educrat'),
                'options' => $headers,
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'header_transparent',
                'type' => 'select',
                'name' => esc_html__('Header Transparent', 'educrat'),
                'description' => esc_html__('Choose a header for your website.', 'educrat'),
                'options' => array(
                    'no' => esc_html__('No', 'educrat'),
                    'yes' => esc_html__('Yes', 'educrat')
                ),
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'header_fixed',
                'type' => 'select',
                'name' => esc_html__('Header Fixed Top', 'educrat'),
                'description' => esc_html__('Choose a header position', 'educrat'),
                'options' => array(
                    'no' => esc_html__('No', 'educrat'),
                    'yes' => esc_html__('Yes', 'educrat')
                ),
                'default' => 'no'
            ),
            array(
                'id' => $prefix.'footer_type',
                'type' => 'select',
                'name' => esc_html__('Footer Layout Type', 'educrat'),
                'description' => esc_html__('Choose a footer for your website.', 'educrat'),
                'options' => $footers,
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'extra_class',
                'type' => 'text',
                'name' => esc_html__('Extra Class', 'educrat'),
                'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'educrat')
            )
    	);
		
	    $metaboxes[$prefix . 'display_setting'] = array(
			'id'                        => $prefix . 'display_setting',
			'title'                     => esc_html__( 'Display Settings', 'educrat' ),
			'object_types'              => array( 'page' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => $fields
		);

	    return $metaboxes;
	}
}
add_filter( 'cmb2_meta_boxes', 'educrat_page_metaboxes' );

if ( !function_exists( 'educrat_cmb2_style' ) ) {
	function educrat_cmb2_style() {
        wp_enqueue_style( 'educrat-cmb2-style', get_template_directory_uri() . '/inc/vendors/cmb2/assets/style.css', array(), '1.0' );
	}
}
add_action( 'admin_enqueue_scripts', 'educrat_cmb2_style' );


