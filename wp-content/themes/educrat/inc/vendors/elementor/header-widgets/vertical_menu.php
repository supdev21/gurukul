<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Educrat_Elementor_Vertical_Menu extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_vertical_menu';
    }

	public function get_title() {
        return esc_html__( 'Apus Header Vertical Menu', 'educrat' );
    }
    
	public function get_categories() {
        return [ 'educrat-header-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'educrat' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'educrat' ),
            ]
        );

        $custom_menus = array();
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
        if ( is_array( $menus ) && ! empty( $menus ) ) {
            foreach ( $menus as $menu ) {
                if ( is_object( $menu ) && isset( $menu->name, $menu->slug ) ) {
                    $custom_menus[ $menu->slug ] = $menu->name;
                }
            }
        }

        $this->add_control(
            'nav_menu',
            [
                'label' => esc_html__( 'Menu', 'educrat' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $custom_menus,
                'default' => ''
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Layout', 'educrat' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'educrat' ),
                    'layout1' => esc_html__( 'Position Sidebar', 'educrat' ),
                ],
                'default' => ''
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'educrat' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'educrat' ),
            ]
        );

        $this->end_controls_section();
                
                
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Style Menu', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .action-vertical' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .icon-vertical' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .icon-vertical:before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .icon-vertical:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_menu_style' );

            $this->start_controls_tab(
                'tab_menu_normal',
                [
                    'label' => esc_html__( 'Normal', 'educrat' ),
                ]
            );

            $this->add_control(
                'link_color',
                [
                    'label' => esc_html__( 'Color', 'educrat' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nav > li > a' => 'color: {{VALUE}};',
                        '{{WRAPPER}} li .icon-toggle' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'link_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'educrat' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nav > li > a' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_menu_hover',
                [
                    'label' => esc_html__( 'Hover', 'educrat' ),
                ]
            );

            $this->add_control(
                'link_hover_color',
                [
                    'label' => esc_html__( 'Color', 'educrat' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nav > li:hover > a,{{WRAPPER}} .nav > li.active > a' => 'color: {{VALUE}};',
                        '{{WRAPPER}} li:hover > .icon-toggle' => 'color: {{VALUE}};',
                        '{{WRAPPER}} li.active > .icon-toggle' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'link_hover_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'educrat' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nav > li:hover > a,{{WRAPPER}} .nav > li.active > a' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        // end tab normal and hover

        $this->end_controls_section();


        $this->start_controls_section(
            'section_menuinner_style',
            [
                'label' => esc_html__( 'Style Menu Inner', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'tabs_menuinner_style' );

                $this->start_controls_tab(
                    'tab_menuinner_normal',
                    [
                        'label' => esc_html__( 'Normal', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'link_inner_color',
                    [
                        'label' => esc_html__( 'Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} li a' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .sub-menu a::before' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

                // tab hover
                $this->start_controls_tab(
                    'tab_menuinner_hover',
                    [
                        'label' => esc_html__( 'Hover', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'link_inner_hover_color',
                    [
                        'label' => esc_html__( 'Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} li:hover > a' => 'color: {{VALUE}};',
                            '{{WRAPPER}} li.active > a' => 'color: {{VALUE}};',
                            '{{WRAPPER}} li.active > a:before' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} li:hover > a:before' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

            $this->end_controls_tabs();
            // end tab normal and hover

        $this->end_controls_section();
    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        $menu_id = 0;
        if ($nav_menu) {
            $term = get_term_by( 'slug', $nav_menu, 'nav_menu' );
            if ( !empty($term) ) {
                $menu_id = $term->term_id;
            }
        }

        if ( !empty($menu_id) ) {
            if ( empty($layout) ) {
        ?>
            <div class="vertical-wrapper <?php echo esc_attr($el_class.' show-hover'); ?>">
                <span class="action-vertical d-flex align-items-center"><i class="icon-vertical"></i>
                    <?php if( !empty($title) ) { ?>
                        <span class="title">
                           <?php echo esc_html( $title ); ?>
                        </span>
                    <?php } ?>
                </span>
                <?php
                    $args = array(
                        'container_class' => 'content-vertical',
                        'menu_class' => 'apus-vertical-menu nav',
                        'fallback_cb' => '',
                        'menu'        => $menu_id,
                        'menu_id' => 'vertical-menu',
                        'walker' => new Educrat_Nav_Menu()
                    );
                    wp_nav_menu($args);
                ?>
            </div>
        <?php
            } else {
                ?>
                <div class="vertical-wrapper <?php echo esc_attr($el_class.' '.$layout); ?>">
                    
                    <?php if( !empty($title) ) { ?>
                        <span class="title">
                           <?php echo esc_html( $title ); ?>
                        </span>
                    <?php } ?>
                    
                    <?php
                        $args = array(
                            'container_class' => 'content-vertical-layout1',
                            'menu_class' => 'apus-vertical-menu-layout1 nav',
                            'fallback_cb' => '',
                            'menu'        => $menu_id,
                            'menu_id' => 'vertical-menu-layout1',
                            'walker' => new Educrat_Mobile_Vertical_Menu()
                        );
                        wp_nav_menu($args);
                    ?>
                </div>
                <?php
            }
        }
    }
}


if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Educrat_Elementor_Vertical_Menu );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Educrat_Elementor_Vertical_Menu );
}