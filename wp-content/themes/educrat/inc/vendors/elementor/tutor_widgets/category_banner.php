<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Educrat_Elementor_Tutor_Category_Banner extends Widget_Base {

	public function get_name() {
        return 'apus_tutor_category_banner';
    }

	public function get_title() {
        return esc_html__( 'Apus Tutor Category Banner', 'educrat' );
    }
    
	public function get_categories() {
        return [ 'educrat-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Category Banner', 'educrat' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slug',
            [
                'label' => esc_html__( 'Category Slug', 'educrat' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your Category Slug here', 'educrat' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Category Title', 'educrat' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your category title', 'educrat' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__( 'Description', 'educrat' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your description here', 'educrat' ),
            ]
        );

        $this->add_control(
            'show_nb_courses',
            [
                'label' => esc_html__( 'Show Number Courses', 'educrat' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'educrat' ),
                'label_off' => esc_html__( 'Show', 'educrat' ),
            ]
        );

        $this->add_control(
            'image_icon',
            [
                'label' => esc_html__( 'Image or Icon', 'educrat' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'icon' => esc_html__('Icon', 'educrat'),
                    'image' => esc_html__('Image', 'educrat'),
                ),
                'default' => 'image'
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'educrat' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-star',
                'condition' => [
                    'image_icon' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__( 'Choose Image', 'educrat' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'image_icon' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'default' => 'full',
                'separator' => 'none',
                'condition' => [
                    'image_icon' => 'image',
                ],
            ]
        );

        $this->add_control(
            'custom_url',
            [
                'label' => esc_html__( 'Custom URL', 'educrat' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your custom category link here', 'educrat' ),
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'educrat' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'educrat'),
                    'style2' => esc_html__('Style 2', 'educrat'),
                    'style3' => esc_html__('Style 3', 'educrat'),
                    'style4' => esc_html__('Style 4', 'educrat'),
                    'style5' => esc_html__('Style 5', 'educrat'),
                ),
                'default' => 'style1'
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__( 'Height', 'educrat' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-content-wrapper' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'style' => ['style2'],
                ]
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'educrat' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'educrat' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_box_style',
            [
                'label' => esc_html__( 'Box Style', 'educrat' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'tabs_box_style' );

                $this->start_controls_tab(
                    'tab_box_normal',
                    [
                        'label' => esc_html__( 'Normal', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'box_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .banner-content-wrapper ' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'border_box',
                        'label' => esc_html__( 'Border', 'educrat' ),
                        'selector' => '{{WRAPPER}} .banner-content-wrapper',
                    ]
                );

                $this->end_controls_tab();

                // tab hover
                $this->start_controls_tab(
                    'tab_box_hover',
                    [
                        'label' => esc_html__( 'Hover', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'box_hv_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .banner-content-wrapper:hover ' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'border_hv_box',
                        'label' => esc_html__( 'Border', 'educrat' ),
                        'selector' => '{{WRAPPER}} .banner-content-wrapper:hover',
                    ]
                );

                $this->end_controls_tab();

            $this->end_controls_tabs();
            // end tab normal and hover


        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__( 'Icon Style', 'educrat' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );



            $this->start_controls_tabs( 'tabs_icon_style' );

                $this->start_controls_tab(
                    'tab_icon_normal',
                    [
                        'label' => esc_html__( 'Normal', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'icon_color',
                    [
                        'label' => esc_html__( 'Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .features-box-image ' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'icon_bg_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .features-box-image ' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

                // tab hover
                $this->start_controls_tab(
                    'tab_icon_hover',
                    [
                        'label' => esc_html__( 'Hover', 'educrat' ),
                    ]
                );

                $this->add_control(
                    'icon_hv_color',
                    [
                        'label' => esc_html__( 'Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .banner-content-wrapper:hover .features-box-image ' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'icon_hv_bg_color',
                    [
                        'label' => esc_html__( 'Background Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .banner-content-wrapper:hover .features-box-image ' => 'background-color: {{VALUE}};',
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
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .features-box-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'width',
                [
                    'label' => esc_html__( 'Width', 'educrat' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .features-box-image' => 'width: {{SIZE}}{{UNIT}}',
                    ]
                ]
            );

            $this->add_responsive_control(
                'height',
                [
                    'label' => esc_html__( 'Height', 'educrat' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .features-box-image' => 'height: {{SIZE}}{{UNIT}}',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'educrat' ),
                    'name' => 'icon_typography',
                    'selector' => '{{WRAPPER}} .features-box-image',
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_info_style',
            [
                'label' => esc_html__( 'Information Style', 'educrat' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
                    'heading_color',
                    [
                        'label' => esc_html__( 'Title Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .banner-title' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'number_color',
                    [
                        'label' => esc_html__( 'Number Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .number' => 'color: {{VALUE}};',
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
                    'heading_hv_color',
                    [
                        'label' => esc_html__( 'Title Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .banner-content-wrapper:hover .banner-title' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'number_hv_color',
                    [
                        'label' => esc_html__( 'Number Color', 'educrat' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .banner-content-wrapper:hover .number' => 'color: {{VALUE}};',
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

        if ( $thumbnail_size == 'custom' ) {
            
            if ( $thumbnail_custom_dimension['width'] && $thumbnail_custom_dimension['height'] ) {
                $thumbsize = $thumbnail_custom_dimension['width'].'x'.$thumbnail_custom_dimension['height'];
            } else {
                $thumbsize = 'full';
            }
        } else {
            $thumbsize = $thumbnail_size;
        }
        ?>

        <?php
        $term = get_term_by( 'slug', $slug, 'course-category' );
        $link = $custom_url;
        $count = 0;
        if ($term) {
            if ( empty($link) ) {
                $link = get_term_link( $term, 'course-category' );
            }
            if ( empty($title) ) {
                $title = $term->name;
            }
            $count = $term->count;
        }
        ?>

        <?php 
            if($style == 'style2') {
                $image = ( isset( $image['id'] ) && $image['id'] != 0 ) ? wp_get_attachment_url( $image['id'] ) : '';
                $style_bg = '';
                if ( !empty($image) ) {
                    $style_bg = 'style="background-image:url('.esc_url($image).')"';
                }
            }
        ?>
        <div class="widget-category-banner">
            <?php if ( !empty($link) ) { ?>
                <a href="<?php echo esc_url($link); ?>">
            <?php } ?>
                <div class="inner">
                        <div class="banner-content-wrapper d-flex flex-column <?php echo esc_attr($el_class.' '.$style); ?>" <?php echo trim($style_bg); ?>>
                            
                            <?php if($style !== 'style2') { ?>

                                <?php
                                if ( $image_icon == 'image' && !empty( $image['id'] ) ) {
                                    ?>
                                    <div class="features-box-image d-flex align-items-center justify-content-center img">
                                        <?php echo educrat_get_attachment_thumbnail($image['id'], $thumbsize); ?>
                                    </div>
                                    <?php
                                } elseif ( $image_icon == 'icon' && !empty($icon)) {
                                    ?>
                                    <div class="features-box-image d-flex align-items-center justify-content-center icon">
                                        <i class="<?php echo esc_attr($icon); ?>"></i>
                                    </div>
                                    <?php
                                }
                                ?>
                            <?php } ?>
                            <div class="right-inner">
                                <?php if ( !empty($title) ) { ?>
                                    <h3 class="banner-title"><?php echo trim($title); ?></h3>
                                <?php } ?>

                                <?php if ( $show_nb_courses ) {
                                    $number_courses = number_format($count);
                                ?>
                                <div class="number"><?php echo sprintf(_n('<span>%d</span> Course', '<span>%d</span> Courses', $count, 'educrat'), $number_courses); ?></div>
                                <?php } ?>
                            </div>
                        </div>
                </div>
            <?php if ( !empty($link) ) { ?>
                </a>
            <?php } ?>
        </div>
        <?php

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Educrat_Elementor_Tutor_Category_Banner );
} else {
    Plugin::instance()->widgets_manager->register( new Educrat_Elementor_Tutor_Category_Banner );
}