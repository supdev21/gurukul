<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$sidebar_configs = educrat_get_event_layout_configs();
educrat_render_breadcrumbs();
global $post;
$event = apussimpleevent_event( get_the_ID() );
?>
<?php
	$info = $event->getMetaFullInfo();	
	$startdate = $info['startdate']['value'] ? $info['startdate']['value'] : '';
	$address = isset($info['address']['value']) ? $info['address']['value'] : '';
	$speakers = !empty($info['speakers']['value']) ? $info['speakers']['value'] : '';
	$style = '';
	if ( !empty(has_post_thumbnail()) ) {
	    $style = 'style="background-image:url('.get_the_post_thumbnail_url($post).');"';
	}
?>
<div class="header-single-envent" <?php echo trim($style); ?>>
	<div class="container">
		<?php if ( !empty($startdate) || !empty($address) ) { ?>
			<div class="detail-time-location">
				<?php if ( !empty($startdate) ) { ?>
					<div class="event-time d-inline-block">
						<i class="flaticon-calendar"></i> <?php echo date_i18n(get_option('date_format'), $startdate); ?>
					</div>
				<?php } ?>
				<?php if ( !empty($address) ) { ?>
					<div class="event-address d-inline-block">
						<i class="flaticon-location"></i> <?php echo esc_html($address); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<h3 class="entry-title">
			<span><?php the_title(); ?></span>
		</h3>
		
		<?php if ($startdate): ?>
			<div class="apus-countdown-dark" data-time="timmer"
		        data-date="<?php echo date('m',$startdate).'-'.date('d',$startdate).'-'.date('Y',$startdate).'-'. date('H',$startdate) . '-' . date('i',$startdate) . '-' .  date('s',$startdate) ; ?>">
		    </div>
		<?php endif; ?>
	</div>
</div>
<section id="main-container" class="main-content single-envent-content <?php echo apply_filters( 'educrat_event_content_class', 'container' ); ?> inner">
	<?php educrat_before_content( $sidebar_configs ); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<div class="row">
				<?php educrat_display_sidebar_left( $sidebar_configs ); ?>
				<div id="main-content" class="<?php echo esc_attr($sidebar_configs['main']['class']); ?>">
					<div id="primary" class="content-area box-event-detail">
						<div id="content" class="site-content detail-post" role="main">
							
							<div class="entry-content"><?php the_content(); ?></div>
							
		        			<div class="envent-participant">
		        				<h4 class="heading"><?php esc_html_e('Our Speakers', 'educrat'); ?></h4>
								<?php if ( $speakers ) { ?>
									<div class="slick-carousel" data-carousel="slick" data-items="4" data-smallmedium="2" data-extrasmall="1" data-pagination="false" data-nav="true">
										<?php foreach ($speakers as $value) { ?>
											<div class="item">
												<div class="participant-item">
													<?php if ( !empty($value['image_id']) ) { ?>
														<div class="image d-flex align-items-center justify-content-center">
															<?php 
																$thumb = educrat_get_attachment_thumbnail($value['image_id'], 'thumbnail');
																echo wp_kses_post($thumb);
															?>
														</div>
													<?php } ?>
													<?php if ( !empty($value['name']) ) { ?>
														<h3 class="name"><?php echo esc_html($value['name']); ?></h3>
													<?php } ?>
													<?php if ( !empty($value['job']) ) { ?>
														<div class="job"><?php echo esc_html($value['job']); ?></div>
													<?php } ?>
												</div>	
											</div>
										<?php } ?>
									</div>
								<?php } ?>
		        			</div> 																

							<?php
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
							
							
						</div><!-- #content -->
					</div><!-- #primary -->
				</div>	
				
				<?php educrat_display_sidebar_right( $sidebar_configs ); ?>
				
			</div>
		</article>
	<?php endwhile; ?>

</section>
<?php get_footer();