<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $post, $authordata;

$profile_url        = tutor_utils()->profile_url( $authordata->ID, true );
$show_author        = tutor_utils()->get_option( 'enable_course_author' );
$disable_reviews    = ! get_tutor_option( 'enable_course_review' );
$layout_type = educrat_tutor_course_layout_type();
?>
<div class="course-header <?php echo esc_attr($layout_type); ?>">
    <?php educrat_render_breadcrumbs(); ?>
    <div class="header-inner-v5">
        <div class="container">
            <div class="inner-default">
                <div class="col-xl-8">
                    <div class="course-header-left">
                        <div class="course-category">
                            <?php
                            $course_categories  = get_tutor_course_categories();
                            if( !empty( $course_categories ) && is_array( $course_categories ) && count( $course_categories ) ) { ?>
                                <?php
                                    foreach ($course_categories as $term) {
                                        ?>
                                        <a class="course-category-item" href="<?php echo get_term_link($term); ?>"><?php echo esc_html($term->name); ?></a>
                                        <?php
                                    }
                                ?>
                            <?php } ?>
                        </div>
                        <h1 class="title"><?php the_title(); ?></h1>
                        <?php if(has_excerpt()){ ?>
                            <div class="excerpt">
                                <?php echo get_the_excerpt(); ?>
                            </div>
                        <?php } ?>
                        <div class="course-header-meta">
                            <?php if ( ! $disable_reviews ) { ?>
                                <div class="rating">
                                    <div class="wrapper_rating_avg d-flex align-items-center">
                                        <?php
                                            $course_rating = tutor_utils()->get_course_rating();
                                            $rating_avg = $course_rating->rating_avg;
                                        ?>
                                            <span class="rating_avg"><?php echo number_format($rating_avg, 1,".","."); ?></span>
                                            <?php Educrat_Tutor_Course_Review::print_review($course_rating->rating_avg, 'detail', $course_rating->rating_count); ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ( tutor_utils()->get_option( 'enable_course_total_enrolled' ) ) { ?>
                                <div class="course-student-number course-meta-field">
                                    <i class="flaticon-online-course-1"></i>
                                    <?php
                                        $count = tutor_utils()->count_enrolled_users_by_course();
                                        echo number_format($count);
                                    ?>
                                    <span><?php esc_html_e('Enrolled', 'educrat'); ?></span>
                                </div>
                            <?php } ?>
                            <!-- time -->
                            <?php
                            $duration = get_tutor_course_duration_context();
                            ?>
                            <div class="course-duration course-meta-field">
                                <i class="flaticon-clock"></i>
                                <?php echo trim( $duration ); ?>
                            </div>
                        </div>
                        
                        <div class="course-header-bottom">

                            <div class="lp-course-author d-flex align-items-center">
                                <div class="course-author__pull-left d-flex align-items-center justify-content-center">
                                    <a href="<?php echo esc_url($profile_url); ?>">
                                        <?php echo get_avatar(get_the_author_meta('ID'), '100'); ?>
                                    </a>
                                </div>
                                <div class="author-title"><a href="<?php echo esc_url($profile_url); ?>"><?php echo get_the_author_meta('display_name'); ?></a></div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>