<?php
/**
 * Template for displaying single course
 *
 * @since v.1.0.0
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

// Prepare the nav items
$course_id = get_the_ID();
$course_nav_item = apply_filters( 'tutor_course/single/nav_items', tutor_utils()->course_nav_items(), $course_id );

$student_must_login_to_view_course = tutor_utils()->get_option('student_must_login_to_view_course');
$is_public = \TUTOR\Course_List::is_public($course_id);

get_header();

if (!is_user_logged_in() && !$is_public && $student_must_login_to_view_course){
    tutor_load_template('login');
    get_footer();
    return;
}


$layout_type = educrat_tutor_course_layout_type();

?>

<?php do_action('tutor_course/single/before/wrap'); ?>
<div <?php tutor_post_class('tutor-full-width-course-top tutor-course-top-info tutor-page-wrap tutor-wrap-parent'); ?>>
        <?php if ( in_array($layout_type, array('v1', 'v2', 'v3', 'v4', 'v5')) ) { ?>
            <?php educrat_tutor_single_course_heading(); ?>
            <div id="main-container" class="inner">
                <div class="tutor-course-details-page tutor-container single-content-course <?php echo esc_attr($layout_type); ?>">
                    <div class="detail-course">
                        <div class="row">
                            <div class="col-lg-8 col-12">
                                <div class="course-summary">
                                    <?php
                                    if ( $layout_type == 'v5' ) {
                                        tutor_utils()->has_video_in_single() ? tutor_course_video() : get_tutor_course_thumbnail();
                                    }
                                    ?>
                    	            <?php do_action('tutor_course/single/before/inner-wrap'); ?>

                                    <div class="tutor-course-details-tab apus-lp-content-area">

                                        <div class="tutor-is-sticky">
                                            <?php tutor_load_template( 'single.course.enrolled.nav', array('course_nav_item' => $course_nav_item ) ); ?>
                                        </div>
                                        <div class="tutor-tab course-tab-panels">
                                            <?php
                                            $add_class = '';
                                            if ( $layout_type == 'v1' || $layout_type == 'v2' || $layout_type == 'v3' || $layout_type == 'v6' ) {
                                                $add_class = '-tab';
                                            }
                                            ?>
                                            <?php foreach( $course_nav_item as $key => $subpage ) : ?>
                                                <div id="tutor-course-details-tab-<?php echo esc_attr($key); ?>" class="tutor-tab-item<?php echo esc_attr($add_class);?> <?php echo esc_attr($key == 'info' ? ' is-active' : ''); ?>">
                                                    <?php
                                                        do_action( 'tutor_course/single/tab/'.$key.'/before' );
                                                        
                                                        $method = $subpage['method'];
                                                        if ( is_string($method) ) {
                                                            $method();
                                                        } else {
                                                            $_object = $method[0];
                                                            $_method = $method[1];
                                                            $_object->$_method(get_the_ID());
                                                        }
                                                        do_action( 'tutor_course/single/tab/'.$key.'/after' );
                                                    ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                    	            <?php do_action('tutor_course/single/after/inner-wrap'); ?>
                                </div>
                            </div>

                            <div class="sidebar-wrapper col-lg-4 col-12 sidebar-course-single">
                                <div class="sidebar sidebar-right sticky-top">
                                    <div class="tutor-single-course-sidebar">
                                        
                                        <?php do_action('tutor_course/single/before/sidebar'); ?>

                                        <?php tutor_load_template('single.course.course-entry-box'); ?>

                                        <?php do_action('tutor_course/single/after/sidebar'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            if ( educrat_get_config('tutor_show_course_related', false) ){
                                get_template_part( 'tutor/single/course/courses-related' );
                            }
                        ?>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <?php educrat_tutor_single_course_heading(); ?>
            <div id="main-container" class="inner">
                <div class="single-content-course v6 tutor-course-details-page tutor-container">
                    <div class="detail-course">
                        <?php do_action('tutor_course/single/before/inner-wrap'); ?>
                        <div class="tutor-course-details-tab apus-lp-content-area">

                            <div class="tutor-is-sticky">
                                <?php tutor_load_template( 'single.course.enrolled.nav', array('course_nav_item' => $course_nav_item ) ); ?>
                            </div>

                            <div class="tutor-tab">
                                <?php
                                $add_class = '';
                                if ( $layout_type == 'v1' || $layout_type == 'v2' || $layout_type == 'v3' || $layout_type == 'v6' ) {
                                    $add_class = '-tab';
                                }
                                ?>
                                <?php foreach( $course_nav_item as $key => $subpage ) : ?>
                                    <div id="tutor-course-details-tab-<?php echo esc_attr($key); ?>" class="tutor-tab-item<?php echo esc_attr($add_class); ?> <?php echo esc_attr($key == 'info' ? ' is-active' : ''); ?>">
                                        <?php
                                            do_action( 'tutor_course/single/tab/'.$key.'/before' );
                                            
                                            $method = $subpage['method'];
                                            if ( is_string($method) ) {
                                                $method();
                                            } else {
                                                $_object = $method[0];
                                                $_method = $method[1];
                                                $_object->$_method(get_the_ID());
                                            }

                                            do_action( 'tutor_course/single/tab/'.$key.'/after' );
                                        ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        if ( educrat_get_config('tutor_show_course_related', false) ){
                            get_template_part( 'tutor/single/course/courses-related' );
                        }
                    ?>
                    
                    <?php do_action('tutor_course/single/after/inner-wrap'); ?>

                </div>
            </div>
        <?php } ?>
</div>

<?php do_action('tutor_course/single/after/wrap'); ?>

<?php
get_footer();