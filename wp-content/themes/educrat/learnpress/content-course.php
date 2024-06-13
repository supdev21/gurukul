<?php
/**
 * Template for displaying course content within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if (!empty($args)) {
    extract($args);
}
$course = learn_press_get_course( $post->ID );
if ( empty($course) ) {
    return;
}
?>

<div <?php post_class('course-grid'); ?>>
    <div class="course-layout-item">
        <div class="course-entry">

            <!-- course thumbnail -->
    		<?php if ( $image = educrat_display_post_thumb('educrat-course-grid') ) { ?>
                <div class="course-cover">
                    <div class="course-cover-thumb"> 
                        <?php echo trim($image); ?>
                        <?php
                        if ( $course->has_sale_price() ) {
                            echo '<span class="sale-label">' . esc_html__('Sale', 'educrat') . '</span>';
                        }
                        ?>
                    </div>
                </div>
    		<?php } ?>

            <div class="course-layout-content">
                <div class="course-info-top">
                    <!-- rating -->
                    <div class="wrapper_rating_avg d-flex align-items-center">
                        <?php
                            $rating_avg = Educrat_Course_Review::get_ratings_average($post->ID);
                            $total = Educrat_Course_Review::get_total_reviews( $post->ID );
                            if($total > 0) {
                        ?>
                            <span class="rating_avg"><?php echo number_format($rating_avg, 1,".","."); ?></span>
                            <?php Educrat_Course_Review::print_review($rating_avg, 'list', $total); ?>

                        <?php } ?>
                    </div>
                </div>

                <!-- course title -->       	
                <h3 class="course-title"><a href="<?php echo get_the_permalink( $course->get_id() ) ?>"><?php echo trim($course->get_title()); ?></a></h3>

                <div class="course-meta-middle">


                    <!-- time -->

                    <!-- <?php
                    $duration = $course->get_data( 'duration' );
                    ?>
                    <div class="course-duration course-meta-field">
                        <i class="flaticon-wall-clock"></i>
                        <?php echo trim( $duration ); ?>
                    </div> -->

                    <!-- number lessons -->

                    <!-- <div class="course-lesson-number course-meta-field">
                        <i class="flaticon-document"></i>
                        <?php
                            $lesson_count = $course->count_items( LP_LESSON_CPT );
                            echo number_format($lesson_count);
                        ?>
                        <?php echo esc_html__('Lessons','educrat'); ?>
                    </div>
                    
                    <?php $skill_level = get_post_meta($post->ID, '_lp_level', true);
                        if(empty($skill_level)){
                            $skill_level = esc_html__('All Levels', 'educrat');
                        }
                    if ( $skill_level ) {
                    ?>
                        <div class="course-level course-meta-field">
                            <i class="flaticon-bar-chart"></i>
                            <?php echo trim( $skill_level ); ?>
                        </div>
                    <?php } ?> -->




                        <!-- time  -->
                        <?php if(!empty($total_course_hours) && !empty($total_chapters) && !empty($course_language)) : ?>
                        
                        <div class="course-duration course-meta-field">
                            <i class="fas fa-clock"></i>
                            <?php echo $total_course_hours = get_post_meta($post->ID, 'total_course_hours', true); ?>
                        </div>
                        <!-- number lessons -->
                        <div class="course-lesson-number course-meta-field">
                            <i class="fas fa-file"></i>
                            <?php echo $total_chapters = get_post_meta($post->ID, 'total_chapters', true); ?>
                        </div>
                        <div class="course-level course-meta-field">
                            <i class="fa fa-language"></i>
                            <?php echo $course_language = get_post_meta($post->ID, 'course_language', true); ?>
                        </div>

                   
                </div>

                <div class="course-meta-bottom d-flex align-items-center">
                    <!-- course teacher -->
                    <div class="lp-course-author d-flex align-items-center">
                        <div class="course-author__pull-left d-flex align-items-center justify-content-center">
                            <?php echo trim($course->get_instructor()->get_profile_picture()); ?>
                        </div>
                        <div class="author-title"><?php echo trim($course->get_instructor_html()); ?></div>
                    </div>

                    <!-- price -->
                    <!-- <div class="course-meta-field course-meta-price ms-auto">
                        <?php //LP()->template( 'course' )->courses_loop_item_price(); ?>
                    </div> -->



                    <div class="courseFeeDetail">
                        <?php 
                            $regular_pricefee  = get_post_meta( $post->ID, '_lp_regular_price', true );
                            $course_salefee = get_post_meta( $post->ID, '_lp_sale_price', true );
                        ?>
                        <?php if (!empty($course_salefee)) : ?>
                            <p class="courseFee"><s>₹<?php echo esc_html($regular_pricefee); ?></s><span>&nbsp;&nbsp; ₹<?php echo esc_html($course_salefee); ?></span> (Introductory Offer)</p>
                        <?php else : ?>
                            <p class="courseFee"><?php if (!empty($regular_pricefee) && !empty($course_salefee) ): ?>₹<?php endif; ?><?php echo esc_html($regular_pricefee); ?></p>
                        <?php  endif; ?>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>