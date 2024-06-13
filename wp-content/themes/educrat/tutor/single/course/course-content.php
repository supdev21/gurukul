<?php

/**
 * Template for displaying course content
 *
 * @since v.1.0.0
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

global $post;

do_action('tutor_course/single/before/content');

if (tutor_utils()->get_option('enable_course_about', true, true)) {
    $string             = apply_filters( 'tutor_course_about_content', get_the_content() );
    $content_summary 	= (bool) get_tutor_option( 'course_content_summary', true );
    $post_size_in_words = sizeof( explode(" ", $string) );
		$word_limit         = 200;
		$has_show_more       = false;

	if ( $content_summary && ( $post_size_in_words > $word_limit ) ) {
		$has_show_more = true;
	}
?>
<?php if ( !empty($string) ) : ?>
	<div class="box-info-white">
		<div class="tutor-course-details-content<?php echo esc_attr($has_show_more ? ' tutor-toggle-more-content tutor-toggle-more-collapsed' : ''); ?>"<?php echo trim($has_show_more ? ' data-tutor-toggle-more-content data-toggle-height="200" style="height: 200px;"' : ''); ?>>
			<h2 class="title">
				<?php echo apply_filters( 'tutor_course_about_title', esc_html__( 'About Course', 'educrat' ) ); ?>
			</h2>
			
			<?php echo apply_filters( 'the_content', $string ); ?>
		</div>

		<?php if ( $has_show_more ) : ?>
			<a href="#" class="tutor-btn-show-more tutor-btn tutor-btn-ghost tutor-mt-16 text-theme" data-tutor-toggle-more=".tutor-toggle-more-content">
				<span class="tutor-toggle-btn-icon tutor-icon tutor-icon-plus tutor-mr-8" area-hidden="true"></span>
				<span class="tutor-toggle-btn-text"><?php esc_html_e( 'Show More', 'educrat' ); ?></span>
			</a>
		<?php endif; ?>
	</div>
<?php endif; ?>
<?php
}

do_action('tutor_course/single/after/content'); ?>