<?php
/**
 * Template for displaying course instructors/ instructor
 *
 * @since v.1.0.0
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

use TUTOR\Instructors_List;

do_action( 'tutor_course/single/enrolled/before/instructors' );

$instructors = tutor_utils()->get_instructors_by_course();

if ( $instructors && count( $instructors ) ) : ?>
<div class="tutor-course-details-instructors course-detail-author box-info-white">
	<h3 class="title">
		<?php esc_html_e( 'Instructors', 'educrat' ); ?>
	</h3>
	<?php foreach ( $instructors as $key => $instructor ) : ?>	
		<div class="<?php echo esc_attr(( $key != count( $instructors ) - 1 ) ? ' tutor-mb-24' : ''); ?>">
			<div class="d-sm-flex tutor-course-detail-author tutor-align-center">
				<div class="flex-shrink-0">
					<div class="author-image d-flex align-items-center justify-content-center">
						<?php echo tutor_utils()->get_tutor_avatar( $instructor->ID, 'lg' ); ?>
					</div>
				</div>

				<div class="course-author-infomation flex-grow-1">
					<h4 class="course-author-title">
						<a href="<?php echo tutor_utils()->profile_url( $instructor->ID, true ); ?>">
							<?php echo trim($instructor->display_name); ?>
						</a>
					</h4>
					<?php if ( ! empty( $instructor->tutor_profile_job_title ) ) : ?>
						<div class="job-title">
							<?php echo trim($instructor->tutor_profile_job_title); ?>
						</div>
					<?php endif; ?>

					<?php
		            $ratings = tutor_utils()->get_instructor_ratings( $instructor->ID );

		            $courses_count  = absint( tutor_utils()->get_course_count_by_instructor( $instructor->ID ) );
		            $students_count = absint( tutor_utils()->get_total_students_by_instructor( $instructor->ID ) );
		            ?>
		            <div class="author-top-content">
		                <div class="d-inline-block">
		                    <?php Educrat_Tutor_Course_Review::print_review_star($ratings->rating_avg); ?>
		                </div>
		                <div class="d-inline-block">
		                    <i class="flaticon-online-learning-5"></i>
		                    <?php echo trim( $students_count ); ?>
		                    <?php $students_count > 1 ? esc_html_e( 'Students', 'educrat' ) : esc_html_e( 'Student', 'educrat' ); ?>
		                </div>
		                <div class="d-inline-block">
		                    <i class="flaticon-play-1"></i>
		                    <?php echo trim( $courses_count ); ?>
		                    <?php $courses_count > 1 ? esc_html_e( 'Courses', 'educrat' ) : esc_html_e( 'Course', 'educrat' ); ?>
		                </div>
		            </div>
				</div>
			</div>
			<?php
	        $userdata = get_user_meta( $instructor->ID );
	        ?>
	        <div class="author-description">
				<?php echo wpautop($userdata['description'][0]); ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
	<?php
endif;

do_action( 'tutor_course/single/enrolled/after/instructors' );