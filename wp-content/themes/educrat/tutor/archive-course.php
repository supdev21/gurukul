<?php

/**
 * Template for displaying courses
 *
 * @since v.1.0.0
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.5.8
 */

get_header();

educrat_render_breadcrumbs();

?>
<section id="main-container" class="main-content <?php echo apply_filters('educrat_tutor_course_content_class', '');?> inner">
    
    <div id="main-content">
        <div id="main" class="site-main layout-course" role="main">

        <?php
        if ( isset( $_GET['course_filter'] ) ) {
            $filter = (new \Tutor\Course_Filter(false))->load_listing( $_GET, true );
            query_posts( $filter );
        }
        
        // Load the 
        tutor_load_template('archive-course-init', array_merge($_GET, array(
            'course_filter' => (bool) tutor_utils()->get_option('course_archive_filter', false),
            'supported_filters' => tutor_utils()->get_option('supported_course_filters', array()),
            'loop_content_only' => false
        )));
        ?>

        </div><!-- .site-main -->
    </div><!-- .content-area -->
        
        
</section>
<?php get_footer();