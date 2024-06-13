<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Educrat_Elementor_Learnpress_My_Wishlist extends Elementor\Widget_Base {

    public function get_name() {
        return 'apus_element_learnpress_my_favorite';
    }

    public function get_title() {
        return esc_html__( 'Apus Learnpress My Wishlist', 'educrat' );
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

        <div class="widget m-0 widget-my-wishlist <?php echo esc_attr($el_class); ?>">
            <?php if ( $title ) { ?>
                <h2 class="widget-title"><?php echo trim($title); ?></h2>
            <?php } ?>
            <?php

                $post_ids = Educrat_Wishlist::get_wishlist();
                $post_ids = (!empty($post_ids) && is_array($post_ids)) ? array_merge(array(0), $post_ids) : array(0);
                $args = array(
                    'includes' => $post_ids,
                    'fields' => 'ids'
                );
                $courses = educrat_get_courses($args);
                if ( $courses ) {
                ?>
                    <div class="row">
                        <?php
                        foreach ( $courses as $course_id ) {
                            ?>
                            <div class="col-xl-6 col-12 my-course-item-wrapper" id="wishlist-course-<?php echo esc_attr($course_id); ?>">
                                <?php
                                    $course_post = get_post( $course_id );
                                    
                                    get_template_part( 'learnpress/content-course', 'wishlist', array('post' => $course_post) );
                                ?>
                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
            <?php } else { ?>
                <div class="d-flex flex-column">
                    <div class="wishlist-not-found alert alert-warning">
                        <?php esc_html_e('No courses found', 'educrat'); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Educrat_Elementor_Learnpress_My_Wishlist );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Educrat_Elementor_Learnpress_My_Wishlist );
}