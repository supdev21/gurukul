<?php
	$thumbsize = isset($thumbsize) ? $thumbsize : '450x300';
	$event = apussimpleevent_event( get_the_ID() );
	$metas = $event->getMetaFullInfo();
	$startdate = isset($metas['startdate']) ? $metas['startdate']['value'] : '';
	$address = isset($metas['address']) ? $metas['address']['value'] : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('event-grid'); ?>>
	
		<?php if ( has_post_thumbnail() ) {
			$thumb = educrat_display_post_thumb($thumbsize);
		?>
			<?php echo wp_kses_post($thumb); ?>
		<?php } ?>
		<div class="inner">
			<?php if ( !empty($startdate) ) { ?>
				<div class="event-time">
					<i class="flaticon-calendar icon-space"></i> <?php echo date_i18n(get_option('date_format'), $startdate); ?>
				</div>
			<?php } ?>
			<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
			
			<?php if ( !empty($address) ) { ?>
				<div class="event-address">
					<i class="flaticon-location icon-space"></i> <?php echo esc_html($address); ?>
				</div>
			<?php } ?>
		</div>
</article>