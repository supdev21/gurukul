<?php
	$thumbsize = isset($thumbsize) ? $thumbsize : 'full';
	$event = apussimpleevent_event( get_the_ID() );
	$metas = $event->getMetaFullInfo();
	$startdate = isset($metas['startdate']) ? $metas['startdate']['value'] : '';
	$address = isset($metas['address']) ? $metas['address']['value'] : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('event-list-v2'); ?>>
	<div class="d-flex align-items-center">
		<?php if ( $startdate ) { ?>
			<div class="flex-shrink-0">
				<div class="startdate">
					<div class="day"><?php echo date('d', $startdate); ?></div>
					<div class="month"><?php echo date('M', $startdate); ?></div>
				</div>
			</div>
		<?php } ?>
		<div class="inner-right flex-grow-1">
			<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
		</div>	
	</div>
	<?php if ( !empty($address) ) { ?>
		<div class="event-address">
			<i class="flaticon-location"></i> <?php echo esc_html($address); ?>
		</div>
	<?php } ?>
</article>