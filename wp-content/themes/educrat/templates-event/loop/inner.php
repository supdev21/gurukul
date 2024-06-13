<?php
	$thumbsize = isset($thumbsize) ? $thumbsize : '410x300';
	$event = apussimpleevent_event( get_the_ID() );
	$metas = $event->getMetaFullInfo();
	$startdate = isset($metas['startdate']) ? $metas['startdate']['value'] : '';
	$address = isset($metas['address']) ? $metas['address']['value'] : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('event-item'); ?>>
		<?php if ( has_post_thumbnail() ) {
			$thumb = educrat_display_post_thumb($thumbsize);
		?>
			<?php echo wp_kses_post($thumb); ?>
		<?php } ?>

		<div class="event-metas d-flex align-items-center">
			<div class="inner-left">
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
			<div class="inner-right ms-auto">
				<a href="<?php the_permalink(); ?>" class="btn btm-permalink btn-theme btn-outline"><?php esc_html_e('More', 'educrat'); ?></a>
			</div>
		</div>
</article>