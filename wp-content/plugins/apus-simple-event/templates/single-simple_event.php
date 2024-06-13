<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main content container" role="main">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); $event = apussimpleevent_event( get_the_ID() ); ?>
					<?php
						$info = $event->getMetaFullInfo();
						
						$map = $info['map']['value'];
						$lat_lng = $map['latitude'] && $map['longitude'] ? $map['latitude'] . ','.$map['longitude'] : '';
						$startdate = $info['startdate']['value'] ? $info['startdate']['value'] : '';
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="post-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
						<?php if ($startdate): ?>
							<div class="apus-countdown" data-time="timmer"
						         data-date="<?php echo date('m',$startdate).'-'.date('d',$startdate).'-'.date('Y',$startdate).'-'. date('H',$startdate) . '-' . date('i',$startdate) . '-' .  date('s',$startdate) ; ?>">
						    </div>
						<?php endif; ?>
						<h3 class="entry-title">
							<?php the_title(); ?>
						</h3>
						<div class="entry-content"><?php the_content(); ?></div>
						<div class="google-map">
							<div id="single_event_gmap_canvas" class="map_canvas"></div>
						</div>
						<?php if ( isset($lat_lng) && $lat_lng ): ?>
							<script type="text/javascript">
								jQuery(document).ready(function($){
								  	$('#single_event_gmap_canvas').gmap3({
								        map:{
								          	options:{
								              	"draggable": true
												,"mapTypeControl": true
												,"mapTypeId": google.maps.MapTypeId.ROADMAP
												,"scrollwheel": false
												,"panControl": true
												,"rotateControl": false
												,"scaleControl": true
												,"streetViewControl": true
												,"zoomControl": true
												,"center":[<?php echo esc_js( $lat_lng ); ?>]
								              	,"zoom": <?php echo trim($zoom); ?>
								              	,'styles': [
												    {
													    featureType: "all",
													    elementType: "all",
													    "stylers": [ { "visibility": "on" }, { "invert_lightness": true }, { "lightness": 80 }, { "gamma": 0.3 }, { "saturation": -100 } ]
												  	}
											  	]
								          	}
								        },
								        marker:{
						                  latLng: [<?php echo esc_js( $lat_lng ); ?>]
						                }
								  	});
								});
							</script>
						<?php endif; ?>
					</article>
				<?php endwhile; ?>

				<?php the_posts_pagination( array(
					'prev_text'          => __( 'Previous page', 'apus-simple-event' ),
					'next_text'          => __( 'Next page', 'apus-simple-event' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'apus-simple-event' ) . ' </span>',
				) ); ?>
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>

		</main>
	</section>

<?php get_footer(); ?>
