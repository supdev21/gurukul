<?php
	$thumbsize = isset($thumbsize) ? $thumbsize : 'thumbnail';
	$event = apussimpleevent_event( get_the_ID() );
	$metas = $event->getMetaFullInfo();
	$startdate = isset($metas['startdate']) ? $metas['startdate']['value'] : '';
	$time = isset($metas['time']) ? $metas['time']['value'] : '';
	$address = isset($metas['address']) ? $metas['address']['value'] : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('event-list'); ?>>
	<div class="d-flex align-items-center"> 
		<?php if ( has_post_thumbnail() ) {
			$thumb = educrat_display_post_thumb($thumbsize);
		?>
			<div class="flex-shrink-0">
				<?php echo wp_kses_post($thumb); ?>
			</div>
		<?php } ?>
		<div class="inner-right flex-grow-1">
			<div class="d-flex align-items-center">
				<div class="clearfix">
					<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
					<?php if ( !empty($startdate) || !empty($address) ) { ?>
						<div class="time-location">
							<?php if ( !empty($startdate) ) { ?>
								<div class="event-time d-inline-block">
									<i class="flaticon-calendar"></i> <?php echo date_i18n('j M, Y', $startdate); ?>
								</div>
							<?php } ?>
							<?php if ( !empty($address) ) { ?>
								<div class="event-address d-inline-block">
									<i class="flaticon-location"></i> <?php echo esc_html($address); ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<div class="more ms-auto d-none d-xl-block">
					<a href="<?php the_permalink(); ?>" class="btn btn-orange"><?php esc_html_e('More', 'educrat'); ?> <i class="flaticon-up-right-arrow"></i></a>
				</div>
			</div>
		</div>
	</div>
</article>