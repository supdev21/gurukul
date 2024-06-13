<?php
    /**
     * @package TutorLMS/Templates
     * @version 1.4.3
     */

	$sort_by = '';
	if(isset( $_POST['tutor_course_filter'] )) {
        $sort_by = tutor_sanitize_data( $_POST['tutor_course_filter'] );
    }

    $filter_offcanvas = $filter_top = '';
    if ( defined('EDUCRAT_DEMO_MODE') && EDUCRAT_DEMO_MODE && !empty($_GET['course']) ) {
        if( ($_GET['course'] =='style4') || ($_GET['course'] =='list1') || ($_GET['course'] =='list2') ){
            $filter_offcanvas = true;
        }
        if( $_GET['course'] =='filtertop' ){
            $filter_top = true;
        }
    }
?>

<div class="course-top-wrapper d-md-flex align-items-center">
	<div class="course-found">
		<?php
            global $wp_query;
            $courseCount = $wp_query->found_posts;
            $count_text = $courseCount>1 ? esc_html__("%s Courses", "educrat") : esc_html__("%s Course", "educrat");
            echo sprintf($count_text, "<strong>{$courseCount}</strong>");
		?>
	</div>

    <div class="d-sm-flex align-items-center ms-auto">
        <form class="tutor-course-filter-form" method="get">
            <select class="tutor-form-select" name="tutor_course_filter">
                <option value="newest_first" <?php selected("newest_first", $sort_by); ?> ><?php esc_html_e("Release Date (newest first)", "educrat");
					?></option>
                <option value="oldest_first" <?php selected("oldest_first", $sort_by); ?>><?php esc_html_e("Release Date (oldest first)", "educrat"); ?></option>
                <option value="course_title_az" <?php selected("course_title_az", $sort_by); ?>><?php esc_html_e("Course Title (a-z)", "educrat"); ?></option>
                <option value="course_title_za" <?php selected("course_title_za", $sort_by); ?>><?php esc_html_e("Course Title (z-a)", "educrat"); ?></option>
            </select>
        </form>

        <?php
        $courses_layout  = educrat_get_config('tutor_courses_layout');
        $courses_filter_layout = educrat_get_config('tutor_courses_filter_layout', '');
        if ( ($courses_layout == 'main' && $courses_filter_layout == 'offcanvas' && isset($has_course_filters) && $has_course_filters) || $filter_offcanvas ) { ?>
            <a href="javascript:void(0);" class="filter-offcanvas-btn"><i class="flaticon-filter-results-button"></i> <?php esc_html_e('Filter', 'educrat'); ?></a>
        <?php } ?>
        <?php if ( ($courses_layout == 'main' && $courses_filter_layout == 'top' && isset($has_course_filters) && $has_course_filters ) || $filter_top ) { ?>
            <a href="javascript:void(0);" class="filter-top-btn"><i class="flaticon-filter-results-button"></i> <?php esc_html_e('Filter', 'educrat'); ?></a>
        <?php } ?>
    </div>
</div>

<?php if ( ($courses_layout == 'main' && $courses_filter_layout == 'offcanvas' && isset($has_course_filters) && $has_course_filters) || $filter_offcanvas ) { ?>
    <div class="filter-offcanvas-sidebar">
        <div class="tutor-course-filter" tutor-course-filter>
            <?php tutor_load_template('course-filter.filters'); ?>
        </div>
    </div>
    <div class="filter-offcanvas-sidebar-overlay"></div>
<?php } ?>

<?php if ( ($courses_layout == 'main' && $courses_filter_layout == 'top' && isset($has_course_filters) && $has_course_filters ) || $filter_top ) { ?>
    <div class="tutor-filter-top-sidebar">
        <div class="tutor-course-filter" tutor-course-filter>
            <?php tutor_load_template('course-filter.filters'); ?>
        </div>
    </div>
<?php } ?>