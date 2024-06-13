<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Educrat
 * @since Educrat 1.0
 */
/*
*Template Name: 404 Page
*/
get_header();

$top_url = educrat_get_config('404_top_img');
$bg_img = educrat_get_config('404_bg_img');

$style = '';
if ( !empty($bg_img) ) {
	$style = 'style="background-image: url('.$bg_img.');"';
}

?>
<section class="page-404 justify-content-center flex-middle" <?php echo trim($style); ?>>
	<div id="main-container" class="inner">
		<div id="main-content" class="main-page">
			<section class="error-404 not-found clearfix">
				<div class="container">
					<div class="row d-md-flex align-items-center">
						<div class="left-image col-12 col-md-6">
							<?php if( !empty($bg_img) ) { ?>
								<img src="<?php echo esc_url( $bg_img); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php }else{ ?>
								<img src="<?php echo esc_url( get_template_directory_uri().'/images/404.png'); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php } ?>
						</div>

						<div class="col-12 col-md-6">
							<div class="content-inner">
								<div class="top-image">
									<?php if( !empty($top_url) ) { ?>
										<img src="<?php echo esc_url( $top_url); ?>" alt="<?php bloginfo( 'name' ); ?>">
									<?php }else{ ?>
										<img src="<?php echo esc_url( get_template_directory_uri().'/images/404-title.png'); ?>" alt="<?php bloginfo( 'name' ); ?>">
									<?php } ?>
								</div>
								<h3 class="title-404">
									<?php
									$title = educrat_get_config('404_title');
									if ( !empty($title) ) {
										echo esc_html($title);
									} else {
										esc_html_e('Page Not Found!', 'educrat');
									}
									?>
								</h3>
								<div class="description">
									<?php
									$description = educrat_get_config('404_description');
									if ( !empty($description) ) {
										echo esc_html($description);
									} else {
										esc_html_e(' The page you \'re looking for isn\'t available. Try to search again or use the go to.', 'educrat');
									}
									?>
								</div>
								<div class="page-content">
									<div class="return">
										<a class="btn-theme btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e('Go Back To Homepage','educrat') ?></a>
									</div>
								</div><!-- .page-content -->
							</div>
						</div>
					</div>
				</div>
			</section><!-- .error-404 -->
		</div><!-- .content-area -->
	</div>
</section>
<?php get_footer(); ?>