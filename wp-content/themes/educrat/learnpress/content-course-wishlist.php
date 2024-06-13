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

<div <?php post_class('course-layout-item position-relative course-list'); ?>>
    <div class="clearfix">
        <div class="course-entry d-flex align-items-center">

            <!-- course thumbnail -->
            <?php if ( $image = educrat_display_post_thumb('educrat-course-list') ) { ?>
                <div class="course-cover flex-shrink-0">
                    <div class="course-cover-thumb"> 
                        <?php echo wp_kses_post($image); ?>
                        <?php
                        if ( $course->has_sale_price() ) {
                            echo '<span class="sale-label">' . esc_html__('Sale', 'educrat') . '</span>';
                        }
                        ?>
                    </div>                
                </div>
            <?php } ?>

            <div class="course-layout-content flex-grow-1">
                <div class="course-info-top d-none d-md-block">
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
                <h3 class="course-title"><a href="<?php echo get_the_permalink( $course->get_id() ) ?>"><?php echo wp_kses_post($course->get_title()); ?></a></h3>

                <div class="course-meta-middle d-none d-md-block">

                    <!-- number lessons -->
                    <div class="course-lesson-number course-meta-field">
                        <i class="flaticon-document"></i>
                        <?php
                            $lesson_count = $course->count_items( LP_LESSON_CPT );
                            echo number_format($lesson_count);
                        ?>
                        <?php echo esc_html__('Lessons','educrat'); ?>
                    </div>

                    <!-- time -->
                    <?php
                    $duration = $course->get_data( 'duration' );
                    ?>
                    <div class="course-duration course-meta-field">
                        <i class="flaticon-wall-clock"></i>
                        <?php echo trim( $duration ); ?>
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
                    <?php } ?>
                   
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
                    <div class="course-meta-field course-meta-price ms-auto">
                        <?php LP()->template( 'course' )->courses_loop_item_price(); ?>
                    </div>
                </div>

                <a href="javascript:void(0);" class="apus-wishlist-remove" data-id="<?php echo esc_attr($course->get_id()); ?>">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
    </div>
</div>