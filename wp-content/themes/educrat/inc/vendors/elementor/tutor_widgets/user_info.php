<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Educrat_Elementor_Tutor_User_Info extends Elementor\Widget_Base {

    public function get_name() {
        return 'apus_element_tutor_user_info';
    }

    public function get_title() {
        return esc_html__( 'Apus Tutor Header User Info', 'educrat' );
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
            'login_text',
            [
                'label' => esc_html__( 'Login Text', 'educrat' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'default' => 'Login/Sign Up'
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

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'educrat' ),
                'type' => Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'educrat' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'educrat' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'educrat' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button Style', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

            $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => esc_html__( 'Normal', 'educrat' ),
                ]
            );

            $this->add_control(
                'button_color',
                [
                    'label' => esc_html__( 'Color', 'educrat' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .btn-login ' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_bgcolor',
                [
                    'label' => esc_html__( 'Background Color', 'educrat' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .btn-login ' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border_button',
                    'label' => esc_html__( 'Border', 'educrat' ),
                    'selector' => '{{WRAPPER}} .btn-login',
                ]
            );

            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => esc_html__( 'Hover', 'educrat' ),
                ]
            );

            $this->add_control(
                'button_hv_color',
                [
                    'label' => esc_html__( 'Color', 'educrat' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .btn-login:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_hv_bgcolor',
                [
                    'label' => esc_html__( 'Background Color', 'educrat' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .btn-login:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border_hv_button',
                    'label' => esc_html__( 'Border', 'educrat' ),
                    'selector' => '{{WRAPPER}} .btn-login:hover',
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        // end tab normal and hover

        $this->add_responsive_control(
            'padding-button',
            [
                'label' => esc_html__( 'Padding', 'educrat' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .btn-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius_button',
            [
                'label' => esc_html__( 'Border Radius', 'educrat' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .btn-login' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'educrat' ),
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .btn-login',
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        $profile_page_url = tutor_utils()->get_tutor_dashboard_page_permalink();
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $userdata = get_userdata($user_id);
            $user_name = $userdata->display_name;
            
            $menu_nav = 'user-menu';
            ?>
            <div class="top-wrapper-menu author-verify <?php echo esc_attr($el_class); ?>">
                <a class="drop-dow" href="<?php echo esc_url( $profile_page_url ); ?>">
                    <div class="infor-account d-flex align-items-center">
                        <div class="avatar-wrapper">
                            <?php echo get_avatar($user_id, 50); ?>
                        </div>
                    </div>
                </a>
                <?php
                    if ( !empty($menu_nav) && has_nav_menu( $menu_nav ) ) {
                        $args = array(
                            'theme_location' => $menu_nav,
                            'container_class' => 'inner-top-menu',
                            'menu_class' => 'nav navbar-nav topmenu-menu',
                            'fallback_cb' => '',
                            'menu_id' => '',
                            'walker' => new Educrat_Nav_Menu()
                        );
                        wp_nav_menu($args);
                    }
                ?>
            </div>
        <?php } else { ?>

            <div class="top-wrapper-menu <?php echo esc_attr($el_class); ?>">
                
                <a class="btn-login btn btn-sm btn-theme" href="<?php echo esc_url( $profile_page_url ); ?>" title="<?php echo esc_attr($login_text); ?>">
                    <?php echo esc_html($login_text); ?>
                </a>
                
            </div>
        <?php }
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Educrat_Elementor_Tutor_User_Info );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Educrat_Elementor_Tutor_User_Info );
}