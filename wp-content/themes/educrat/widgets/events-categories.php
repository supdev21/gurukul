<?php

global $post;
extract( $args );
extract( $args );
extract( $instance );
$terms = get_terms(array(
	'taxonomy' => 'simple_event_category',
	'hide_empty' => false,
));
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
	echo trim($before_widget);
	$title = apply_filters('widget_title', $instance['title']);

	if ( $title ) {
	    echo trim($before_title)  . trim( $title ) . $after_title;
	}

	$selected = '';
	if ( is_tax('simple_event_category') ) {
		global $wp_query;
		$term =	$wp_query->queried_object;
		if ( isset( $term->term_id) ) {
			$selected = $term->term_id;
		}
	}
	?>
	<div class="widget_categories">
	    <ul>
		    <?php foreach ( $terms as $term ) { ?>
		        <li><a href="<?php echo get_term_link($term); ?>" class="<?php echo esc_attr($term->term_id == $selected ? 'active' : ''); ?>"><?php echo esc_html($term->name); ?></a></li>
		    <?php } ?>
	    </ul>
    </div>
<?php
	echo trim($after_widget);
}