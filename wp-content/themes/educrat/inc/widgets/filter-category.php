<?php

class Educrat_Widget_Course_Filter_Category extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apus_course_filter_category',
            esc_html__('LearnPress Courses:: Filter Categories', 'educrat'),
            array( 'description' => esc_html__( 'Show list of course filter category', 'educrat' ), )
        );
        $this->widgetName = 'course_filter_category';
    }

    public function widget( $args, $instance ) {
        get_template_part('widgets/filter-category', '', array('args' => $args, 'instance' => $instance));
    }
    
    public function form( $instance ) {
        $defaults = array(
            'title' => 'Categories',
            'layout' => 'list',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        // Widget admin form

        $options = ['select' => esc_html__('Select', 'educrat'), 'list' => esc_html__('List', 'educrat')];
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'educrat' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout')); ?>">
                <?php echo esc_html__('Layout:', 'educrat' ); ?>
            </label>
            <br>
            <select id="<?php echo esc_attr($this->get_field_id('layout')); ?>" name="<?php echo esc_attr($this->get_field_name('layout')); ?>">
                <?php foreach ($options as $key => $value) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected($instance['layout'],$key); ?> ><?php echo esc_html( $value ); ?></option>
                <?php } ?>
            </select>
        </p>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['layout'] = ( ! empty( $new_instance['layout'] ) ) ? strip_tags( $new_instance['layout'] ) : '';
        return $instance;

    }
}

call_user_func( implode('_', array('register', 'widget') ), 'Educrat_Widget_Course_Filter_Category' );