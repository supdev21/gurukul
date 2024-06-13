<?php $event = apussimpleevent_event( get_the_ID() );
	$metas = $event->getMetaFullInfo();
	$time = isset($metas['startdate']) ? $metas['startdate']['value'] : '';
?>
<article id="post-<?php the_ID(); ?>" itemscope itemtype="" <?php post_class(); ?>>
	<?php do_action( 'apussimpleevent_before_event_loop_item' ); ?>
	<div class="row">
		<div class="col-md-6">
			<div class="event-thumb">
				<?php if ( has_post_thumbnail() ) { ?>
					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
						<?php
							the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) );
						?>
					</a>
				<?php } ?>
				<?php if ($time) { ?>
					<div class="apus-countdown" data-time="timmer"
					     data-date="<?php echo date('m',$time).'-'.date('d',$time).'-'.date('Y',$time).'-'. date('H',$time) . '-' . date('i',$time) . '-' .  date('s',$time) ; ?>">
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="col-md-6">
			<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
			<?php the_excerpt(); ?>
		</div>
	</div>
	<?php do_action( 'apussimpleevent_after_event_loop_item' ); ?>
	<meta itemprop="url" content="<?php the_permalink(); ?>" />
</article>