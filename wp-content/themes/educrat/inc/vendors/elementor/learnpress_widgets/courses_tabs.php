<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Educrat_Elementor_Learnpress_Courses_Tabs extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_learnpress_courses_tabs';
    }

	public function get_title() {
        return esc_html__( 'Apus Learnpress Courses Tabs', 'educrat' );
    }

    public function get_icon() {
        return 'fa fa-shopping-bag';
    }

	public function get_categories() {
        return [ 'educrat-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title', [
                'label' => esc_html__( 'Tab Title', 'educrat' ),
                'type' => Elementor\Controls_Manager::TEXT
            ]
        );

        $repeater->add_control(
            'get_course_by',
            [
                'label' => esc_html__( 'Get Courses By', 'educrat' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'recent_courses' => esc_html__('Recent Courses', 'educrat' ),
                    'featured_courses' => esc_html__('Featured Courses', 'educrat' ),
                    'popular_courses' => esc_html__('Popular Courses', 'educrat' ),
                ),
                'default' => 'recent_courses'
            ]
        );

        $repeater->add_control(
            'slugs',
            [
                'label' => esc_html__( 'Category Slug', 'educrat' ),
                'type' => Elementor\Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => '',
                'placeholder' => esc_html__( 'Enter id spearate by comma(,)', 'educrat' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'educrat' ),
                'type' => Elementor\Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'content',
            [
                'label' => esc_html__( 'Description', 'educrat' ),
                'type' => Elementor\Controls_Manager::TEXTAREA,
            ]
        );

        $this->add_control(
            'tabs_style',
            [
                'label' => esc_html__( 'Tabs Position', 'educrat' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'justify-content-center' => esc_html__('Center', 'educrat'),
                    'justify-content-start' => esc_html__('Left', 'educrat'),
                    'justify-content-end' => esc_html__('Right', 'educrat'),
                ),
                'default' => 'justify-content-center'
            ]
        );

        $this->add_control(
            'tabstyle',
            [
                'label' => esc_html__( 'Tabs Style', 'educrat' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'educrat'),
                    'st_gray' => esc_html__('Gray', 'educrat'),
                ),
                'default' => ''
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => esc_html__( 'Tabs', 'educrat' ),
                'type' => Elementor\Controls_Manager::REPEATER,
                'placeholder' => esc_html__( 'Enter your product tabs here', 'educrat' ),
                'fields' => $repeater->get_controls(),
            ]
        );
        
        $this->add_control(
            'limit',
            [
                'label' => esc_html__( 'Limit', 'educrat' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'placeholder' => esc_html__( 'Enter number products to display', 'educrat' ),
                'default' => 4
            ]
        );

        $this->add_control(
            'item_style',
            [
                'label' => esc_html__( 'Course Style', 'educrat' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
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
                'default' => ''
            ]
        );
        
        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'educrat' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid', 'educrat'),
                    'carousel' => esc_html__('Carousel', 'educrat'),
                ),
                'default' => 'grid'
            ]
        );
        
        $columns = range( 1, 12 );
        $columns = array_combine( $columns, $columns );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'educrat' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $columns,
                'frontend_available' => true,
                'default' => 3,
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__( 'Slides to Scroll', 'educrat' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'description' => esc_html__( 'Set how many slides are scrolled per swipe.', 'educrat' ),
                'options' => $columns,
                'condition' => [
                    'columns!' => '1',
                    'layout_type' => 'carousel',
                ],
                'frontend_available' => true,
                'default' => 1,
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'educrat' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'placeholder' => esc_html__( 'Enter your rows number here', 'educrat' ),
                'default' => 1,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label'         => esc_html__( 'Show Navigation', 'educrat' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'educrat' ),
                'label_off'     => esc_html__( 'Hide', 'educrat' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'         => esc_html__( 'Show Pagination', 'educrat' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'educrat' ),
                'label_off'     => esc_html__( 'Hide', 'educrat' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'educrat' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'educrat' ),
                'label_off'     => esc_html__( 'No', 'educrat' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label'         => esc_html__( 'Infinite Loop', 'educrat' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'educrat' ),
                'label_off'     => esc_html__( 'No', 'educrat' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
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
            'section_info_style',
            [
                'label' => esc_html__( 'Tabs Style', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_info_style' );

                $this->start_controls_tab(
                    'tab_info_normal',
                    [
                        'label' => esc_html__( 'Normal', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'info_color',
                    [
                        'label' => esc_html__( 'Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .tabs-course > li > a ' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'info_bg_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .tabs-course > li > a ' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

                // tab hover
                $this->start_controls_tab(
                    'tab_info_hover',
                    [
                        'label' => esc_html__( 'Hover', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'info_hv_color',
                    [
                        'label' => esc_html__( 'Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .tabs-course > li > a:hover ' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .tabs-course > li > a:focus ' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .tabs-course > li > a.active ' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'info_hv_bg_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .tabs-course > li > a:hover ' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .tabs-course > li > a:focus ' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .tabs-course > li > a.active ' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );


                $this->end_controls_tab();

            $this->end_controls_tabs();
            // end tab normal and hover

            $this->add_responsive_control(
            'border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'educrat' ),
                    'type' => Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .tabs-course > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'space_li',
                [
                    'label' => esc_html__( 'Space', 'educrat' ),
                    'type' => Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .tabs-course > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_information_style',
            [
                'label' => esc_html__( 'Information Style', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_color',
            [
                'label' => esc_html__( 'Item Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .course-layout-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_link_color',
            [
                'label' => esc_html__( 'Item Link Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_link_hv_color',
            [
                'label' => esc_html__( 'Item Link Hover Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} a:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_bg_color',
            [
                'label' => esc_html__( 'Item Background Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .course-layout-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Title Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .course-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'name_hv_color',
            [
                'label' => esc_html__( 'Title Hover Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .course-title a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .course-title a:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Price Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .course-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'line',
            [
                'label' => esc_html__( 'Line', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .course-meta-bottom' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_arrow_style',
            [
                'label' => esc_html__( 'Arrow Style', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'tabs_arrow_style' );

                $this->start_controls_tab(
                    'tab_arrow_normal',
                    [
                        'label' => esc_html__( 'Normal', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'arrow_color',
                    [
                        'label' => esc_html__( 'Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .slick-carousel .slick-arrow' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'arrow_bg_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .slick-carousel .slick-arrow' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Elementor\Group_Control_Border::get_type(),
                    [
                        'name' => 'border_arrow',
                        'label' => esc_html__( 'Border', 'educrat' ),
                        'selector' => '{{WRAPPER}} .slick-carousel .slick-arrow',
                    ]
                );

                $this->add_group_control(
                    Elementor\Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'arrow_shadow',
                        'label' => esc_html__( 'Box Shadow', 'educrat' ),
                        'selector' => '{{WRAPPER}} .slick-carousel .slick-arrow',
                    ]
                );

                $this->end_controls_tab();

                // tab hover
                $this->start_controls_tab(
                    'tab_arrow_hover',
                    [
                        'label' => esc_html__( 'Hover', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'arrow_hv_color',
                    [
                        'label' => esc_html__( 'Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .slick-carousel .slick-arrow:hover' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .slick-carousel .slick-arrow:focus' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'arrow_hv_bg_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .slick-carousel .slick-arrow:hover' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .slick-carousel .slick-arrow:focus' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Elementor\Group_Control_Border::get_type(),
                    [
                        'name' => 'border_hv_arrow',
                        'label' => esc_html__( 'Border', 'educrat' ),
                        'selector' => '{{WRAPPER}} .slick-carousel .slick-arrow:focus , {{WRAPPER}} .slick-carousel .slick-arrow:hover',
                    ]
                );

                $this->add_group_control(
                    Elementor\Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'arrow_hv_shadow',
                        'label' => esc_html__( 'Box Shadow', 'educrat' ),
                        'selector' => '{{WRAPPER}} .slick-carousel .slick-arrow:focus , {{WRAPPER}} .slick-carousel .slick-arrow:hover',
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

        if ( !empty($tabs) ) {
            $_id = educrat_random_key();

            $columns = !empty($columns) ? $columns : 3;
            $columns_tablet = !empty($columns_tablet) ? $columns_tablet : 2;
            $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 1;
            
            $slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
            $slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
            $slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;
            ?>
            <div class="widget-courses-tabs <?php echo esc_attr($el_class); ?>">
                <div class="widget-content <?php echo esc_attr($layout_type); ?>">
                    <?php if( !empty($title) || !empty($content) ){ ?>
                        <div class="top-info d-lg-flex align-items-end">
                            <div class="inner-left">
                                <?php if ( !empty($title) ) { ?>
                                    <h2 class="title">
                                        <?php echo esc_html($title); ?>
                                    </h2>
                                <?php } ?>
                                <?php if ( !empty($content) ) { ?>
                                    <div class="description"><?php echo trim($content); ?></div>
                                <?php } ?>
                            </div>
                            <div class="ms-auto">
                                <ul role="tablist" class="nav nav-tabs tabs-course mb-0 <?php echo trim($tabs_style.' '.$tabstyle); ?>">
                                    <?php $i = 0; foreach ($tabs as $tab) : ?>
                                        <li>
                                            <a href="#tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>" class="<?php echo esc_attr($i == 0 ? 'active' : '');?>" data-bs-toggle="tab">
                                                <?php if ( !empty($tab['title']) ) { ?>
                                                    <?php echo trim($tab['title']); ?>
                                                <?php } ?>
                                            </a>
                                        </li>
                                    <?php $i++; endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php } else { ?>
                        <ul role="tablist" class="nav nav-tabs tabs-course <?php echo trim($tabs_style.' '.$tabstyle); ?>">
                            <?php $i = 0; foreach ($tabs as $tab) : ?>
                                <li>
                                    <a href="#tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>" class="<?php echo esc_attr($i == 0 ? 'active' : '');?>" data-bs-toggle="tab">
                                        <?php if ( !empty($tab['title']) ) { ?>
                                            <?php echo trim($tab['title']); ?>
                                        <?php } ?>
                                    </a>
                                </li>
                            <?php $i++; endforeach; ?>
                        </ul>
                    <?php } ?>
                    <div class="tab-content">
                        <?php $i = 0; foreach ($tabs as $tab) : ?>
                            <div id="tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>" class="tab-pane fade <?php echo esc_attr($i == 0 ? 'show active' : ''); ?>">

                                <?php
                                    $slugs = !empty($tab['slugs']) ? array_map('trim', explode(',', $tab['slugs'])) : array();
                                    
                                    $courses = null;
                                    switch ($tab['get_course_by']) {
                                        case 'featured_courses':
                                            $courses = educrat_get_courses(array(
                                                'course_type' => 'featured_courses',
                                                'categories' => $slugs,
                                                'limit' => (int)$limit,
                                                'fields' => 'ids'
                                            ));
                                            break;
                                        case 'popular_courses':
                                            $filter        = new LP_Course_Filter();
                                            $filter->limit = $limit;
                                            
                                            $lp_course_db = LP_Course_DB::getInstance();
                                            $lp_course_db->get_courses_order_by_popular( $filter );
                                            $courses = $lp_course_db->get_courses( $filter );
                                            break;
                                        default:
                                            $courses = educrat_get_courses(array(
                                                'course_type' => 'recent_courses',
                                                'categories' => $slugs,
                                                'limit' => (int)$limit,
                                                'fields' => 'ids'
                                            ));
                                            break;
                                    }
                                    
                                    if ( !empty($courses) ) {
                                        if ( $layout_type == 'carousel' ): ?>
                                            <div class="slick-carousel <?php echo ( ( $columns >= count($courses) )? 'hidden-dot-lg' : '' ); ?>"
                                                data-items="<?php echo esc_attr($columns); ?>"
                                                data-large="<?php echo esc_attr( $columns_tablet ); ?>"
                                                data-medium="<?php echo esc_attr( $columns_tablet ); ?>"
                                                data-small="<?php echo esc_attr($columns_mobile); ?>"
                                                data-extrasmall="<?php echo esc_attr($columns_mobile); ?>"

                                                data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>"
                                                data-slidestoscroll_large="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
                                                data-slidestoscroll_medium="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
                                                data-slidestoscroll_small="<?php echo esc_attr($slides_to_scroll_mobile); ?>"
                                                data-slidestoscroll_extrasmall="<?php echo esc_attr($slides_to_scroll_mobile); ?>"

                                                data-pagination="<?php echo esc_attr( $show_pagination ? 'true' : 'false' ); ?>"
                                                data-nav="<?php echo esc_attr( $show_nav ? 'true' : 'false' ); ?>"
                                                data-rows="<?php echo esc_attr( $rows ); ?>"
                                                data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>"
                                                data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>">

                                                <?php
                                                foreach ( $courses as $course_id ) {
                                                    $course_post = get_post( $course_id );
                                                    get_template_part( 'learnpress/content-course', $item_style, array('post' => $course_post) );
                                                }
                                                ?>
                                            </div>
                                        <?php else: ?>
                                            <?php
                                                $mdcol = 12/$columns;
                                                $smcol = 12/$columns_tablet;
                                                $xscol = 12/$columns_mobile;
                                            ?>
                                            <div class="row">
                                                <?php
                                                foreach ( $courses as $course_id ) {
                                                    ?>
                                                    <div class="col-xl-<?php echo esc_attr($mdcol); ?> col-md-<?php echo esc_attr($smcol); ?> col-<?php echo esc_attr( $xscol ); ?> ">
                                                        <?php
                                                            $course_post = get_post( $course_id );
                                                            get_template_part( 'learnpress/content-course', $item_style, array('post' => $course_post) );
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php wp_reset_postdata();
                                    }
                                ?>

                            </div>
                        <?php $i++; endforeach; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Educrat_Elementor_Learnpress_Courses_Tabs );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Educrat_Elementor_Learnpress_Courses_Tabs );
}