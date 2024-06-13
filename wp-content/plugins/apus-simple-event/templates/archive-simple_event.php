<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$bcols = 4;
?>
	<section id="primary" class="content-area">
		<main id="main" class="site-main content container" role="main">
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->
				
				
				<div class="apuseslate-archive-container">
					
					<div class="apussimpleevent-archive-bottom apussimpleevent-rows">
						<div class="row">
							<?php while ( have_posts() ) : the_post(); ?>
								<div class="col-sm-<?php echo esc_attr($bcols); ?>">
									<?php echo ApusSimpleEvent_Template_Loader::get_template_part( 'content-event' ); ?>
								</div>
							<?php endwhile; ?>
						</div>
					</div>	

				</div>
				<?php the_posts_pagination( array(
					'prev_text'          => __( 'Previous page', 'apus-simple-event' ),
					'next_text'          => __( 'Next page', 'apus-simple-event' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'apus-simple-event' ) . ' </span>',
				) ); ?>
				
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->


<?php get_footer(); ?>
