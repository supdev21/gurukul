<?php

global $post;
extract( $args );
extract( $args );
extract( $instance );
$tag_cloud = wp_tag_cloud(
	array(
		'taxonomy'   => 'simple_event_tags',
		'echo'       => false,
	)
);


if ( !empty($tag_cloud) ) {
	echo trim($before_widget);
	$title = apply_filters('widget_title', $instance['title']);

	if ( $title ) {
	    echo trim($before_title)  . trim( $title ) . $after_title;
	}
	?>
	<div class="tagcloud">

		<?php echo trim($tag_cloud); ?>

	</div>
<?php } echo trim($after_widget);