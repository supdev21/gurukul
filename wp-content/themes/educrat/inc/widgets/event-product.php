<?php

class Educrat_Widget_Event_Product extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apus_event_product',
            esc_html__('Event Single:: WooCoomerce Product', 'educrat'),
            array( 'description' => esc_html__( 'Show WooCoomerce product', 'educrat' ), )
        );
        $this->widgetName = 'event_product';
    }

    public function widget( $args, $instance ) {
        get_template_part('widgets/event-product', '', array('args' => $args, 'instance' => $instance));
    }

    public function form( $instance ) {
        $defaults = array(
            'title' => 'Event Product',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'educrat' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;

    }
}

call_user_func( implode('_', array('register', 'widget') ), 'Educrat_Widget_Event_Product' );