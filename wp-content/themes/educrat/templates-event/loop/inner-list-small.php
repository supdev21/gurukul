<?php
	$thumbsize = isset($thumbsize) ? $thumbsize : 'thumbnail';
	$event = apussimpleevent_event( get_the_ID() );
	$metas = $event->getMetaFullInfo();
	$startdate = isset($metas['startdate']) ? $metas['startdate']['value'] : '';
	$time = isset($metas['time']) ? $metas['time']['value'] : '';
	$address = isset($metas['address']) ? $metas['address']['value'] : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('event-list-small'); ?>>
	<div class="d-flex align-items-center"> 
		<?php if ( has_post_thumbnail() ) {
			$thumb = educrat_display_post_thumb($thumbsize);
		?>
			<div class="flex-shrink-0">
				<?php echo wp_kses_post($thumb); ?>
			</div>
		<?php } ?>
		<div class="inner-right flex-grow-1">
			<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
			<?php if ( $startdate ) { ?>
				<div class="startdate">
					<?php echo date_i18n('j M, Y', $startdate); ?>
				</div>
			<?php } ?>				
		</div>
	</div>
</article>