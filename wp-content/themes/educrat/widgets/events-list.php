<?php

global $post;
extract( $args );
extract( $args );
extract( $instance );
$events = educrat_get_events(array(
    'event_type' => $get_event_by,
    'limit' => (int)$limit
));


if ( $events->have_posts() ) {
	echo trim($before_widget);
	$title = apply_filters('widget_title', $instance['title']);

	if ( $title ) {
	    echo trim($before_title)  . trim( $title ) . $after_title;
	}

	?>
	<div class="widget-content">
		<?php while ( $events->have_posts() ) { $events->the_post(); ?>
            
            <?php get_template_part('templates-event/loop/inner', 'list-small'); ?>
            
        <?php } ?>
	</div>
<?php } echo trim($after_widget);