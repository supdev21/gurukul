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
    <div class="container">
        <?php educrat_render_breadcrumbs(); ?>
        <div class="inner-v6">
            <div class="row d-lg-flex align-items-center">
                <div class="col-lg-6 col-12">
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


                        <div class="inner">
                            <!-- info -->
                            <?php
                            $default_meta = array(
                                array(
                                    'icon_class' => 'flaticon-user',
                                    'label'      => esc_html__( 'Total Enrolled', 'educrat' ),
                                    'value'      => tutor_utils()->get_option( 'enable_course_total_enrolled' ) ? tutor_utils()->count_enrolled_users_by_course() : null,
                                ),
                                array(
                                    'icon_class' => 'flaticon-clock',
                                    'label'      => esc_html__( 'Duration', 'educrat' ),
                                    'value'      => get_tutor_option( 'enable_course_duration' ) ? ( get_tutor_course_duration_context() ? get_tutor_course_duration_context() : false ) : null,
                                ),
                                array(
                                    'icon_class' => 'tutor-icon-refresh-o',
                                    'label'      => esc_html__( 'Last Updated', 'educrat' ),
                                    'value'      => get_tutor_option( 'enable_course_update_date' ) ? get_the_modified_date( get_option( 'date_format' ) ) : null,
                                ),
                            );

                            // Add level if enabled
                            if(tutor_utils()->get_option('enable_course_level', true, true)) {
                                array_unshift($default_meta, array(
                                    'icon_class' => 'flaticon-bar-chart',
                                    'label'      => esc_html__( 'Level', 'educrat' ),
                                    'value'      => get_tutor_course_level( get_the_ID() ),
                                ));
                            }

                            // Right sidebar meta data
                            $sidebar_meta = apply_filters('tutor/course/single/sidebar/metadata', $default_meta, get_the_ID() );
                            ?>

                            <?php
                            do_action('tutor_course/single/entry/after', get_the_ID());
                            ?>
                            <!-- Course Info -->
                            <ul class="tutor-course-info-fields st_white">
                                <?php foreach ( $sidebar_meta as $key => $meta ) : ?>
                                    <?php
                                    if ( ! $meta['value'] ) {
                                        continue;
                                    }
                                    ?>
                                    <li>
                                        <label>
                                            <i class="<?php echo esc_attr( $meta['icon_class'] ); ?>"></i>
                                            <?php echo esc_html($meta['label']); ?>
                                        </label>
                                        <span class="tutor-label">
                                            <?php echo wp_kses_post( $meta['value'] ); ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php get_template_part('template-parts/sharebox-course'); ?>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="course-header-right">
                        <?php tutor_utils()->has_video_in_single() ? tutor_course_video() : get_tutor_course_thumbnail(); ?>
                        <?php tutor_load_template( 'single.course.course-entry-box-2' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>