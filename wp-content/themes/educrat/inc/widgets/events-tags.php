<?php

class Educrat_Widget_Events_Tags extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apus_events_tags',
            esc_html__('Events Tags', 'educrat'),
            array( 'description' => esc_html__( 'Show list of events tags', 'educrat' ), )
        );
        $this->widgetName = 'event_tags';
    }

    public function widget( $args, $instance ) {
        get_template_part('widgets/events-tags', '', array('args' => $args, 'instance' => $instance));
    }

    public function form( $instance ) {
        $defaults = array(
            'title' => 'Event Tags',
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

call_user_func( implode('_', array('register', 'widget') ), 'Educrat_Widget_Events_Tags' );