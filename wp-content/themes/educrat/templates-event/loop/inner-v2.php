<?php
	$thumbsize = isset($thumbsize) ? $thumbsize : 'full';
	$event = apussimpleevent_event( get_the_ID() );
	$metas = $event->getMetaFullInfo();
	$startdate = isset($metas['startdate']) ? $metas['startdate']['value'] : '';
	$address = isset($metas['address']) ? $metas['address']['value'] : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('event-grid-v2 position-relative'); ?>>
	
	<?php if ( $startdate ) { ?>
		<div class="startdate d-flex align-items-center">
			<span class="day"><?php echo date('d', $startdate); ?></span>
			<span class="month"><?php echo date('M', $startdate); ?></span>
		</div>
	<?php } ?>		
	<div class="event-metas">
		<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
		
		<!-- buy package -->
		<a href="<?php the_permalink(); ?>" class="btn btn-theme"><?php esc_html_e('Read More', 'educrat'); ?> <i class="flaticon-up-right-arrow"></i></a>
	</div>

</article>