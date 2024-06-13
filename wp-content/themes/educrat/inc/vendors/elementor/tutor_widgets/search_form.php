<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Educrat_Elementor_Tutor_Search_Form extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_tutor_course_search_form';
    }

	public function get_title() {
        return esc_html__( 'Apus Tutor Course Search Form', 'educrat' );
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
            'placeholder',
            [
                'label'         => esc_html__( 'Input placeholder', 'educrat' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'default'   => 'What do you want to learn today?'
            ]
        );

        $this->add_control(
            'show_category',
            [
                'label' => esc_html__( 'Show Categories Field', 'educrat' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'educrat' ),
                'label_off' => esc_html__( 'Show', 'educrat' ),
            ]
        );

        $this->add_control(
            'show_level',
            [
                'label' => esc_html__( 'Show Level Field', 'educrat' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'educrat' ),
                'label_off' => esc_html__( 'Show', 'educrat' ),
            ]
        );

        $this->add_control(
            'btn_icon',
            [
                'label' => esc_html__( 'Button Icon', 'educrat' ),
                'type' => Elementor\Controls_Manager::ICON,

            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'         => esc_html__( 'Button Text', 'educrat' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'default'   => 'Search'
            ]
        );

        $this->add_responsive_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout Type', 'educrat' ),                
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'educrat'),
                    'button' => esc_html__('Button', 'educrat'),
                ],
                'default' => '',
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
            'section_box_style',
            [
                'label' => esc_html__( 'Box', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'search_button_color',
            [
                'label' => esc_html__( 'Icon Search Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .search-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding-box',
            [
                'label' => esc_html__( 'Padding', 'educrat' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .search-form-course' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border-box',
                'label' => esc_html__( 'Border', 'educrat' ),
                'selector' => '{{WRAPPER}} .search-form-course',
            ]
        );

        $this->add_responsive_control(
            'border_box_radius',
            [
                'label' => esc_html__( 'Border Radius', 'educrat' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .search-form-course' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'educrat' ),
                'selector' => '{{WRAPPER}} .search-form-course',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        // tab normal and hover
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
                            '{{WRAPPER}} .btn' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'button_bg_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .btn' => 'background-color: {{VALUE}};',
                        ],
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
                            '{{WRAPPER}} .btn:hover' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .btn:focus' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'button_hv_bg_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .btn:hover' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .btn:focus' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'button_hv_br_color',
                    [
                        'label' => esc_html__( 'Border Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .btn:hover' => 'border-color: {{VALUE}};',
                            '{{WRAPPER}} .btn:focus' => 'border-color: {{VALUE}};',
                        ],
                        'condition' => [
                            'border-button_border!' => '',
                        ],
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
                    '{{WRAPPER}} .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border-button',
                'label' => esc_html__( 'Border', 'educrat' ),
                'selector' => '{{WRAPPER}} .btn',
            ]
        );

        $this->add_responsive_control(
            'border_button_radius',
            [
                'label' => esc_html__( 'Border Radius', 'educrat' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'educrat' ),
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .btn',
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        ?>
        <div class="apus-search-form-course <?php echo esc_attr($el_class.' '.$layout_type); ?>">
            <?php if ( $layout_type == 'button' ) { ?>
                <span class="search-button">
                    <i class="flaticon-search"></i>
                </span>
                <div class="over-dark"></div>
            <?php } ?>
            <form action="<?php echo esc_url( tutor_utils()->course_archive_page_url() ); ?>" method="get" class="search-form-popup">
                <div class="form-inner">
                    <div class="search-form-course <?php echo esc_attr($layout_type); ?>">
                        <div class="d-sm-flex align-items-center">
                            <input type="text" placeholder="<?php echo esc_attr( $placeholder ); ?>" name="keyword" class="form-control" autocomplete="off"/>
                            <?php if ( $show_category || $show_level) { ?>
                                <div class="d-sm-flex align-items-center addon">
                                    <?php if ( $show_category ) {
                                        $t_args = array(
                                            'taxonomy' => 'course-category',
                                            'hide_empty' => false,
                                            'orderby' => 'name',
                                            'order' => 'ASC',
                                        );
                                        $terms = get_terms($t_args);
                                        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                                        ?>
                                        <div class="item">
                                            <select name="tutor-course-filter-category" class="filter-category">
                                                <?php foreach ($terms as $term) { ?>
                                                    <option value="<?php echo esc_attr($term->term_id); ?>"><?php echo esc_html($term->name); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if ( $show_level ) {
                                        $levels = tutor_utils()->course_levels();
                                        if ( ! empty( $levels ) ) {
                                    ?>
                                        <div class="item">
                                            <select name="tutor-course-filter-level" class="filter-level">
                                                <?php foreach ($levels as $key => $title) { ?>
                                                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($title); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <input type="hidden" name="course_filter" value="true">
                            
                            <button type="submit" class="btn btn-theme btn-search ms-auto">
                                <?php
                                if ( ! empty( $btn_icon ) ) {
                                    ?><i class="<?php echo esc_attr($btn_icon); ?>"></i><?php
                                }
                                if ( $btn_text ) {
                                    echo '<span class="text-search">';
                                        echo esc_html($btn_text);
                                    echo '</span>';
                                }
                                ?>
                            </button>
                            <?php if($layout_type == 'button') { ?>
                                <span class="close-search"><i class="ti-close"></i></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Educrat_Elementor_Tutor_Search_Form );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Educrat_Elementor_Tutor_Search_Form );
}