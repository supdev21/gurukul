<?php
/**
 * Template for displaying course benefits
 *
 * @since v.1.0.0
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */



do_action( 'tutor_course/single/before/benefits' );


$course_benefits = tutor_course_benefits();
if ( empty( $course_benefits ) ) {
	return;
}
?>

<?php if (is_array($course_benefits) && count($course_benefits)): ?>
	<div class="tutor-course-details-widget">
		<h3 class="tutor-course-details-widget-title">
			<?php echo esc_html( apply_filters( 'tutor_course_benefit_title', esc_html__( 'What Will You Learn?', 'educrat' ) ) ); ?>
		</h3>
		<ul class="tutor-course-details-widget-list">
			<?php foreach ($course_benefits as $benefit): ?>
				<li>
					<span><?php echo trim($benefit); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<?php do_action('tutor_course/single/after/benefits'); ?>
