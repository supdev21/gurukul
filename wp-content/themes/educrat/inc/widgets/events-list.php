<?php

class Educrat_Widget_Events_List extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apus_events_list',
            esc_html__('Events List', 'educrat'),
            array( 'description' => esc_html__( 'Show list of events list', 'educrat' ), )
        );
        $this->widgetName = 'event_list';
    }

    public function widget( $args, $instance ) {
        get_template_part('widgets/events-list', '', array('args' => $args, 'instance' => $instance));
    }

    public function form( $instance ) {
        $defaults = array(
            'title' => 'Events',
            'get_event_by' => 'Events',
            'limit' => 4,
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        // Widget admin form
        $options = array(
            'recent' => esc_html__('Recent Events', 'educrat' ),
            'upcoming' => esc_html__('Upcoming Events', 'educrat' ),
        );
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'educrat' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('get_event_by')); ?>">
                <?php echo esc_html__('Get Events By:', 'educrat' ); ?>
            </label>
            <br>
            <select id="<?php echo esc_attr($this->get_field_id('get_event_by')); ?>" name="<?php echo esc_attr($this->get_field_name('get_event_by')); ?>">
                <?php foreach ($options as $key => $value) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected($instance['get_event_by'],$key); ?> ><?php echo esc_html( $value ); ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'limit' )); ?>"><?php esc_html_e( 'Limit:', 'educrat' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'limit' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'limit' )); ?>" type="text" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
        </p>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['get_event_by'] = ( ! empty( $new_instance['get_event_by'] ) ) ? strip_tags( $new_instance['get_event_by'] ) : '';
        $instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : '';
        return $instance;

    }
}

call_user_func( implode('_', array('register', 'widget') ), 'Educrat_Widget_Events_List' );