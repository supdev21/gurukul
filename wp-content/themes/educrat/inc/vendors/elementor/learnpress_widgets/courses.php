<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Educrat_Elementor_Learnpress_Courses extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_learnpress_courses';
    }

	public function get_title() {
        return esc_html__( 'Apus Learnpress Courses', 'educrat' );
    }
    
	public function get_categories() {
        return [ 'educrat-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Courses', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
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

        $this->add_control(
            'slugs',
            [
                'label' => esc_html__( 'Categories Slug', 'educrat' ),
                'type' => Elementor\Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => '',
                'placeholder' => esc_html__( 'Enter id spearate by comma(,)', 'educrat' ),
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => esc_html__( 'Limit', 'educrat' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
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
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
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
            'stretch_pagination',
            [
                'label'         => esc_html__( 'Stretch Pagination', 'educrat' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'educrat' ),
                'label_off'     => esc_html__( 'Hide', 'educrat' ),
                'return_value'  => true,
                'default'       => false,
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
            'fullscreen',
            [
                'label'         => esc_html__( 'Full Screen', 'educrat' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'educrat' ),
                'label_off'     => esc_html__( 'No', 'educrat' ),
                'return_value'  => true,
                'default'       => false,
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
        
        $courses = null;
        $slugs = !empty($slugs) ? array_map('trim', explode(',', $slugs)) : array();
        switch ($get_course_by) {
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
        ?>
        <div class="widget-courses <?php echo esc_attr($el_class); ?> <?php echo esc_attr( $fullscreen ? 'fullscreen' : 'nofullscreen' ); ?>">
            
            <?php
                if ( !empty($courses) ) {
                    $columns = !empty($columns) ? $columns : 3;
                    $columns_tablet = !empty($columns_tablet) ? $columns_tablet : 2;
                    $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 1;
                    
                    $slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
                    $slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
                    $slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;

                    if ( $layout_type == 'carousel' ): ?>
                        <div class="slick-carousel <?php echo ( ( $columns >= count($courses) )? 'hidden-dot-lg' : '' ); ?> <?php echo esc_attr( $stretch_pagination ? 'stretch_pagination' : '' ); ?>"
                            data-items="<?php echo esc_attr($columns); ?>"
                            data-large="<?php echo esc_attr( $columns_tablet ); ?>"
                            data-medium="<?php echo esc_attr( $columns_tablet ); ?>"
                            data-small="<?php echo esc_attr( $columns_mobile ); ?>"
                            data-smallest="<?php echo esc_attr( $columns_mobile ); ?>"

                            data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>"
                            data-slidestoscroll_large="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
                            data-slidestoscroll_small="<?php echo esc_attr($slides_to_scroll_mobile); ?>"

                            data-pagination="<?php echo esc_attr( $show_pagination ? 'true' : 'false' ); ?>"
                            data-nav="<?php echo esc_attr( $show_nav ? 'true' : 'false' ); ?>"
                            data-rows="<?php echo esc_attr( $rows ); ?>"
                            data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>"
                            data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>">

                            <?php
                            foreach ( $courses as $course_id ) {
                                $course_post = get_post( $course_id ); ?>
                                <div class="item">
                                    <?php get_template_part( 'learnpress/content-course', $item_style, array('post' => $course_post) ); ?>
                                </div>
                            <?php
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
                                <div class="col-xl-<?php echo esc_attr($mdcol); ?> col-md-<?php echo esc_attr($smcol); ?> col-<?php echo esc_attr( $xscol ); ?>">
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
        <?php

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Educrat_Elementor_Learnpress_Courses );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Educrat_Elementor_Learnpress_Courses );
}