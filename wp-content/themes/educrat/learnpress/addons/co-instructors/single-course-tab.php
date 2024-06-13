<?php
/**
 * Template for displaying instructor tab in single course page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/co-instructor/single-course-tab.php.
 *
 * @author ThimPress
 * @package LearnPress/Co-Instructor/Templates
 * @version 3.0.2
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

/**
 * @var $instructors
 */
if ( $instructors ) {
	foreach ( $instructors as $instructor_id ) {
		$user = get_userdata( $instructor_id );
		if ( $user ) {
            $user_id = $instructor_id;
            $rating = get_user_meta($user_id, '_average_rating', true );

            $args = array(
                'author' => $user_id,
                'fields' => 'ids',
            );
            $courses = educrat_get_courses($args);
            $course_count = !empty($courses) ? count($courses) : 0;
            $students = $nb_reviews = 0;
            if ( !empty($courses) ) {
                foreach ($courses as $course_id) {
                    $students += intval(get_post_meta($course_id, '_lp_students', true));
                    $nb_reviews += Educrat_Course_Review::get_total_reviews( $course_id );
                }
            }

            ?>

                <div class="author-wrapper">
                    <div class="author-image">
                        <?php echo get_avatar( $instructor_id, 96 ); ?>
                    </div>
                    <div class="content">
                        <div class="top-content flex-middle">
                            <div class="top-content-left">
                                <div class="ratings">
                                    <?php Educrat_Course_Review::print_review($rating, 'list'); ?>
                                </div>
                            </div>
                            <div class="top-content-right">
                                <div class="nb-reviews">
                                    <i class="flaticon-comment"></i>
                                    <?php echo sprintf(_n('%d Review', '%d Reviews', $nb_reviews, 'educrat'), number_format($nb_reviews, 0)); ?>
                                </div>
                                <div class="nb-students">
                                    <i class="flaticon-profile"></i>
                                    <?php echo sprintf(_n('%d Student', '%d Students', $students, 'educrat'), number_format($students, 0)); ?>
                                </div>
                                <div class="nb-course">                     
                                    <i class="flaticon-play-button-1"></i>      
                                    <?php echo sprintf(_n('%d Course', '%d Courses', $course_count, 'educrat'), number_format($course_count, 0)); ?>
                                </div>
                            </div>
                        </div>

                        <h3 class="course-link-title">
                            <span itemprop="name"><?php echo get_the_author_meta( 'display_name', $instructor_id ); ?></span>
                        </h3>

                        <div class="author-bio">
                            <?php echo get_the_author_meta( 'description', $instructor_id ); ?>
                        </div>
                    </div>
                </div>


			<?php
		}
	}
}