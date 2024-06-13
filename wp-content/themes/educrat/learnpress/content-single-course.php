<?php
/**
 * Template for displaying content of course without header and footer
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit();

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}
global $post;
$layout_type = educrat_course_layout_type();

if ( in_array($layout_type, array('v1', 'v2', 'v3', 'v4', 'v5')) ) {
	if ( $layout_type == 'v5' ) {
	    add_action( 'learn-press/course-content-summary', 'educrat_single_course_video', 17 );
	}

?>
<?php do_action( 'educrat_single_course_header' ); ?>






<section id="main-container" class="inner">

	<div class="claerfix single-content-course <?php echo esc_attr($layout_type); ?> <?php echo apply_filters( 'educrat_course_content_class', 'container' ); ?>">
		


		<div class="row">
			<div id="main-content" class="col-12">
				<div id="primary" class="content-area">
					<div id="content" class="site-content detail-course" role="main">
						
						<div class="row">
							
							<?php
							//$main_class = 'col-lg-8 col-12';
							$main_class = 'col-12';
							if ( !is_active_sidebar( 'course-single-sidebar' ) ) {
								$main_class = 'col-12';
							}
							?>
							<div class="<?php echo esc_attr($main_class); ?>">
								<div class="lp-archive-courses">

									<?php
									/**
									 * LP Hook
									 */
									do_action( 'learn-press/before-single-course' );

									?>
									<div id="learn-press-course" class="course-summary">
										<?php
										/**
										 * @since 3.0.0
										 *
										 * @called single-course/content.php
										 * @called single-course/sidebar.php
										 */
										do_action( 'learn-press/single-course-summary' );
										?>
									</div>
									<?php

									/**
									 * LP Hook
									 */
									do_action( 'learn-press/after-single-course' );

									?>
								</div>
							</div>

							<?php if ( is_active_sidebar( 'course-single-sidebar' ) ): ?>
								<div class="sidebar-wrapper col-lg-4 col-12 sidebar-course-single">
								  	<aside class="sidebar sidebar-right sticky-top" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
								   		<?php dynamic_sidebar( 'course-single-sidebar' ); ?>
								  	</aside>
								</div>
							<?php endif; ?>
						</div>

						<?php 
							if ( educrat_get_config('show_course_related', false) ){
								get_template_part( 'learnpress/single-course/courses-related' );
							}
						?>

					</div><!-- #content -->
				</div><!-- #primary -->
			</div>	

		</div>	
	</div>
</section>
<?php } else { ?>
<?php do_action( 'educrat_single_course_header' ); ?>


<?php

	$course_post =  $post->ID;
	
	$what_you_will_learn = get_field('what_you_will_learn', $course_post);

	if ($what_you_will_learn) {

		$section_background_image = $what_you_will_learn['section_background_image'];
		$section_title_wywl = $what_you_will_learn['section_title_wywl'];
		$section_subtitle_wywl = $what_you_will_learn['section_subtitle_wywl'];
		$section_short_description = $what_you_will_learn['section_short_description'];
		$section_cta_wywl = $what_you_will_learn['section_cta_wywl'];
	}

?>


<section class="inner inner-whatwelearn" >
	<div class="learn-section" style="background-image: url(<?php echo $section_background_image ?>);">
		<div  class="container">
			<div class="row">
				<div class="col-md-6">						
					<div class="learn-info">
						<p class="p-title"><?php echo $section_subtitle_wywl ?></p>
						<h2><?php echo $section_title_wywl ?></h2>
						<div class="learn-desc"><?php echo $section_short_description ?></div>
						<p class="learn-cta cta-<?php echo $course_post ?>"><a href="<?php echo $section_cta_wywl ?>">Explore More</a></p>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</section>



<section id="main-container" class="inner pt-5">
	<div class="single-content-course v4 <?php echo apply_filters( 'educrat_course_content_class', 'container' ); ?>">
		<div class="row">
			<div id="main-content" class="col-12">
				<div id="primary" class="content-area">
					<div id="content" class="site-content detail-course" role="main">
						<div class="lp-archive-courses">
							<?php
							/**
							 * LP Hook
							 */
							do_action( 'learn-press/before-single-course' );

							?>
							<div id="learn-press-course" class="course-summary">
								<?php
								/**
								 * @since 3.0.0
								 *
								 * @called single-course/content.php
								 * @called single-course/sidebar.php
								 */
								do_action( 'learn-press/single-course-summary' );
								?>
							</div>
							<?php

							/**
							 * LP Hook
							 */
							do_action( 'learn-press/after-single-course' );
							?>
						</div>
						<?php 
							if ( educrat_get_config('show_course_related', false) ){
								get_template_part( 'learnpress/single-course/courses-related' );
							}
						?>
					</div><!-- #content -->
				</div><!-- #primary -->
			</div>	
			
		</div>	
	</div>
</section>
<?php }