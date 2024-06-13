<?php
/**
 * Template for displaying instructor of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/instructor.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.3.0
 */

defined( 'ABSPATH' ) || exit();

$course     = LP_Global::course();
/**
 * @var LP_User
 */
$instructor = $course->get_instructor();
$profile = LP_Profile::instance($instructor->ID);
$user = $profile->get_user();
?>

<div class="course-detail-author box-info-white">
	<h3 class="title"><?php echo esc_html__('Instructor','educrat') ?></h3>
	<?php do_action( 'learn-press/before-single-course-instructor' ); ?>

	<div class="lp-course-detail-author m-0 d-sm-flex align-items-sm-center">
		<div class="flex-shrink-0">
			<div class="author-image d-flex align-items-center justify-content-center">
				<?php echo trim($instructor->get_profile_picture()); ?>
			</div>
		</div>
		<div class="course-author-infomation flex-grow-1">
			<h4 class="course-author-title"><?php echo trim($course->get_instructor_html()); ?></h4>
			<?php
			$user_id = $instructor->get_id();
			$args = array(
			    'author' => $user_id,
			    'fields' => 'ids',
			);
			$courses = educrat_get_courses($args);
			$course_count = !empty($courses) ? count($courses) : 0;
			$students = $nb_reviews = 0;
			if ( !empty($courses) ) {
			    foreach ($courses as $course_id) {
			    	$course = learn_press_get_course( $course_id );
			        $students += intval($course->count_students());
			        $nb_reviews += Educrat_Course_Review::get_total_reviews( $course_id );
			    }
			}
			?>

			<?php
            $job_title = get_user_meta($user_id, '_user_job_title', true);
            if ( $job_title ) {
            ?>
                <div class="job-title"><?php echo esc_html($job_title); ?></div>
            <?php } ?>

			<div class="author-top-content">
	    		<?php
                $rating_avg = Educrat_Course_Review::get_total_rating_by_user($user->ID);
                ?>
                <div class="d-inline-block">
                    <?php Educrat_Course_Review::print_review_star($rating_avg); ?>
                </div>
                <div class="nb-reviews">
	    			<i class="flaticon-comment"></i>
	    			<?php echo sprintf(_n('%d Review', '%d Reviews', $nb_reviews, 'educrat'), number_format($nb_reviews, 0)); ?>
	    		</div>
	    		<div class="nb-students">
	    			<i class="flaticon-online-learning-5"></i>
	    			<?php echo sprintf(_n('%d Student', '%d Students', $students, 'educrat'), number_format($students, 0)); ?>
	    		</div>
	    		<div class="nb-course">		    			
	    			<i class="flaticon-play-1"></i>		
	    			<?php echo sprintf(_n('%d Course', '%d Courses', $course_count, 'educrat'), number_format($course_count, 0)); ?>
	    		</div>
    		</div>

			<?php
			/**
			 * LP Hook
			 *
			 * @since 4.0.0
			 */
			do_action( 'learn-press/after-course-instructor-description', $instructor );
			?>

			<?php

			/**
			 * LP Hook
			 *
			 * @since 4.0.0
			 */
			do_action( 'learn-press/after-course-instructor-socials', $instructor );

			?>
		</div>
	</div>
	<div class="author-description">
		<?php
		/**
		 * LP Hook
		 *
		 * @since 4.0.0
		 */
		do_action( 'learn-press/begin-course-instructor-description', $instructor );

		echo wpautop($instructor->get_description());

		/**
		 * LP Hook
		 *
		 * @since 4.0.0
		 */
		do_action( 'learn-press/end-course-instructor-description', $instructor );

		?>
	</div>
	<?php do_action( 'learn-press/after-single-course-instructor' ); ?>

</div>