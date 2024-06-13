<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Educrat_Elementor_Tutor_My_Wishlist extends Elementor\Widget_Base {

    public function get_name() {
        return 'apus_tutor_my_favorite';
    }

    public function get_title() {
        return esc_html__( 'Apus Tutor My Wishlist', 'educrat' );
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

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'educrat' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'educrat' ),
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

    }

    protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        ?>

        <div class="widget no-margin widget-my-wishlist <?php echo esc_attr($el_class); ?>">
            <?php if ( $title ) { ?>
                <h2 class="widget-title"><?php echo trim($title); ?></h2>
            <?php } ?>
            <?php
            if ( !is_user_logged_in() ) { ?>
                <div class="text-danger"><?php esc_html_e('Please login to view your wishlist', 'educrat'); ?></div>
            <?php
            } else {
                $wishlists    = tutor_utils()->get_wishlist(null, 0, '-1'); 
                if (is_array($wishlists) && count($wishlists)) {
                    foreach ($wishlists as $post) {
                        setup_postdata($post);
                        ?>
                        <div class="my-course-item-wrapper">
                            <?php get_template_part( 'tutor/loop/course/course' ); ?>
                        </div>
                        <?php
                    }
                } else {
                    tutor_utils()->tutor_empty_state( tutor_utils()->not_found_text() );
                }
            }
            ?>

        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Educrat_Elementor_Tutor_My_Wishlist );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Educrat_Elementor_Tutor_My_Wishlist );
}