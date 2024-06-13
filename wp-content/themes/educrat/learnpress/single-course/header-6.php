<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$course = learn_press_get_course();
$layout_type = educrat_course_layout_type();
?>
<div class="course-header <?php echo esc_attr($layout_type); ?>">
    <div class="container">
        <?php educrat_render_breadcrumbs_simple(); ?>
        <div class="inner-v6">
            <div class="row d-lg-flex align-items-center">
                <div class="col-lg-6 col-12">
                    <div class="course-header-left">
                        <div class="course-category">
                            <?php
                            $categories = get_the_terms( $post->ID, 'course_category' );
                            if ( $categories ) {
                                foreach ($categories as $term) {
                                    ?>
                                    <a class="course-category-item" href="<?php echo get_term_link($term); ?>"><?php echo esc_html($term->name); ?></a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <h2 class="title"><?php the_title(); ?></h2>

                        <?php if(has_excerpt()){ ?>
                            <div class="excerpt">
                                <?php echo get_the_excerpt(); ?>
                            </div>
                        <?php } ?>

                        <div class="course-header-meta">
                            <!-- rating -->
                            <div class="rating">
                                <div class="wrapper_rating_avg d-flex align-items-center">
                                    <?php
                                        $rating_avg = Educrat_Course_Review::get_ratings_average($post->ID);
                                        $total = Educrat_Course_Review::get_total_reviews( $post->ID );
                                        if($total > 0) {
                                    ?>
                                        <span class="rating_avg"><?php echo number_format($rating_avg, 1,".","."); ?></span>
                                        <?php Educrat_Course_Review::print_review($rating_avg, 'detail', $total); ?>

                                    <?php } ?>
                                </div>
                            </div>
                            <div class="course-student-number course-meta-field">
                                <i class="flaticon-online-course-1"></i>
                                <?php
                                    $count = $course->count_students();
                                    echo number_format($count);
                                ?>
                                <span><?php esc_html_e('Enrolled', 'educrat'); ?></span>
                            </div>
                            <!-- time -->
                            <?php
                            $duration = $course->get_data( 'duration' );
                            ?>
                            <div class="course-duration course-meta-field">
                                <i class="flaticon-clock"></i>
                                <?php echo trim( $duration ); ?>
                            </div>
                        </div>
                        <div class="course-header-bottom">
                            <div class="lp-course-author d-flex align-items-center">
                                <div class="course-author__pull-left d-flex align-items-center justify-content-center">
                                    <?php echo trim($course->get_instructor()->get_profile_picture()); ?>
                                </div>
                                <div class="author-title"><?php echo trim($course->get_instructor_html()); ?></div>
                            </div>
                        </div>
                        <!-- info -->
                        <?php
                        $course = learn_press_get_course( $post->ID );
                        $duration = $course->get_data( 'duration' );
                        $language = get_post_meta($post->ID, '_lp_language', true);
                        $skill_level = get_post_meta($post->ID, '_lp_level', true);
                        $certificate = get_post_meta($post->ID, '_lp_certificate', true);
                        $max_students = get_post_meta($post->ID, '_lp_max_students', true);
                        $more_info = get_post_meta($post->ID, '_lp_more_info', true);
                        ?>

                        <div class="inner">
                            <ul class="lp-course-info-fields st_white">
                                <li class="lp-course-info duration">
                                    <label><i class="flaticon-clock"></i><?php esc_html_e( 'Duration', 'educrat' ); ?></label>
                                    <?php learn_press_label_html( $duration ); ?>
                                </li>

                                <li class="lp-course-info lessons">
                                    <label><i class="flaticon-video-file"></i><?php esc_html_e( 'Lessons', 'educrat' ); ?></label>
                                    <?php learn_press_label_html( $course->count_items( LP_LESSON_CPT ) ); ?>
                                </li>

                                <li class="lp-course-info quizzes">
                                    <label><i class="flaticon-puzzle"></i><?php esc_html_e( 'Quizzes', 'educrat' ); ?></label>
                                    <?php learn_press_label_html( $course->count_items( LP_QUIZ_CPT ) ); ?>
                                </li>

                                <?php if ( $max_students ) { ?>
                                    <li class="lp-course-info max_students">
                                        <label><i class="flaticon-user"></i><?php esc_html_e( 'Maximum Students', 'educrat' ); ?></label>
                                        <?php learn_press_label_html( $max_students ); ?>
                                    </li>
                                <?php } ?>
                                <li class="lp-course-info language">
                                    <label><i class="flaticon-translate"></i><?php esc_html_e( 'Language', 'educrat' ); ?></label>
                                    <?php learn_press_label_html( $language ); ?>
                                </li>
                                <li class="lp-course-info skill_level">
                                    <label><i class="flaticon-bar-chart"></i><?php esc_html_e( 'Skill level', 'educrat' ); ?></label>
                                    <?php learn_press_label_html( $skill_level ); ?>
                                </li>
                                <li class="lp-course-info certificate">
                                    <label><i class="flaticon-badge-1"></i><?php esc_html_e( 'Certificate', 'educrat' ); ?></label>
                                    <?php learn_press_label_html( $certificate ); ?>
                                </li>
                            </ul>
                            <?php
                            if ( !empty($more_info) ) {
                            ?>
                                <ul class="lp-course-info-fields style2 st_white">
                                    <?php foreach ($more_info as $value) {
                                        if ( $value ) {
                                    ?>
                                        <li>
                                            <?php echo trim($value); ?>
                                        </li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                            
                            <!-- socials -->

                            <?php get_template_part('template-parts/sharebox-course'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="course-header-right">
                        <?php learn_press_get_template( 'single-course/video.php' ); ?>

                        <?php if ( !learn_press_is_learning_course() ) { ?>
                            <div class="course-price d-flex align-items-center">
                                <div class="sale-price"><?php echo trim($course->get_course_price_html()); ?></div>
                                <?php if(!empty($course->get_origin_price_html()) && !empty($course->get_sale_price()) ){ ?>
                                    <div class="origin-price ms-auto"><?php echo trim($course->get_origin_price_html()); ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php learn_press_get_template( 'single-course/buttons.php' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>