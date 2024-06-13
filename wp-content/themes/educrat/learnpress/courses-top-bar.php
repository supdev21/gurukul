<?php
/**
 * Template for displaying top-bar in archive course page.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.1
 */

defined( 'ABSPATH' ) || exit;

$courses_filter_layout = educrat_get_config('courses_filter_layout', '');
$courses_layout  = educrat_get_config('courses_layout');
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
	<?php educrat_course_loop_found_post(); ?>
	<div class="lp-courses-filter d-flex align-items-center ms-auto">
		<?php educrat_course_loop_orderby(); ?>

		<?php if ( ( $courses_layout == 'main' && $courses_filter_layout == 'offcanvas' && is_active_sidebar( 'courses-filter' ) ) || $filter_offcanvas ) { ?>
			<a href="javascript:void(0);" class="filter-offcanvas-btn"><i class="flaticon-filter-results-button"></i> <?php esc_html_e('Filter', 'educrat'); ?></a>
		<?php } ?>
	</div>
</div>

<?php if ( ( $courses_layout == 'main' && $courses_filter_layout == 'offcanvas' && is_active_sidebar( 'courses-filter' ) ) || $filter_offcanvas ) { ?>
	<div class="filter-offcanvas-sidebar">
		<aside class="sidebar sidebar-right" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
			<?php dynamic_sidebar( 'courses-filter' ); ?>
		</aside>
  	</div>
  	<div class="filter-offcanvas-sidebar-overlay"></div>
<?php } ?>

<?php if ( ($courses_layout == 'main' && $courses_filter_layout == 'top' && is_active_sidebar( 'courses-top-filter' )) || $filter_top ) { ?>
  	<div class="filter-top-sidebar">
		<?php dynamic_sidebar( 'courses-top-filter' ); ?>
  	</div>
<?php } ?>