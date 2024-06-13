<?php
/**
 * Template for displaying content of archive courses page.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;


get_header();

$sidebar_configs = educrat_get_course_archive_layout_configs();
$checkmain = educrat_get_config('sidebar-course_layout', '');
if($checkmain == 'main'){
	$checkmain = 'only_main';
}
educrat_render_breadcrumbs();

$display_mode = educrat_courses_get_display_mode();
$columns = educrat_get_config('courses_columns', 3);
$inner = $classes = $mobile_icon_sidebar = '';
if ( $display_mode == 'list' ) {
	$bcol = 12/$columns;
	$classes = 'col-lg-'.$bcol.' col-12';
	$inner = 'list';
} else {
	$bcol = 12/$columns;
	$classes = 'col-lg-'.$bcol.' col-md-6 col-12';
	$inner = educrat_get_config('courses_item_style', '');
}

if ( defined('EDUCRAT_DEMO_MODE') && EDUCRAT_DEMO_MODE ) {
	if (!empty($_GET['course']) && ($_GET['course'] =='style1') ){
	    $inner = '';
	} elseif (!empty($_GET['course']) && ($_GET['course'] =='style2') ){
	    $inner = '2';
	} elseif (!empty($_GET['course']) && ($_GET['course'] =='style3') ){
	    $inner = '3';
	} elseif (!empty($_GET['course']) && ($_GET['course'] =='style4') ){
	    $inner = '4';
	    $sidebar_configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-12' );
	    $sidebar_configs['right']['class'] = $sidebar_configs['left']['class'] = 'hidden';
	    $classes = 'col-lg-4 col-md-6 col-12';
	    $checkmain = 'only_main';
	} elseif (!empty($_GET['course']) && ($_GET['course'] =='list1') ){
		$inner = 'list';
	    $sidebar_configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-12' );
	    $sidebar_configs['right']['class'] = $sidebar_configs['left']['class'] = 'hidden';
	    $classes = 'col-lg-6 col-12';
	    $checkmain = 'only_main';
	} elseif (!empty($_GET['course']) && ($_GET['course'] =='list2') ){
		$inner = 'list-v2';
	    $sidebar_configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-12' );
	    $sidebar_configs['right']['class'] = $sidebar_configs['left']['class'] = 'hidden';
	    $classes = 'col-lg-6 col-12';
	    $checkmain = 'only_main';
	} elseif (!empty($_GET['course']) && ($_GET['course'] =='list3') ){
		$inner = 'list-v3';
	    $classes = 'col-12';
	} elseif (!empty($_GET['course']) && ($_GET['course'] =='list4') ){
		$inner = 'list-v4';
	    $classes = 'col-12';
	} elseif (!empty($_GET['course']) && ($_GET['course'] =='list5') ){
		$inner = 'list-v5';
	    $classes = 'col-12';
	} elseif (!empty($_GET['course']) && ($_GET['course'] =='filtertop') ){
	    $inner = '';
	    $sidebar_configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-12' );
	    $sidebar_configs['right']['class'] = $sidebar_configs['left']['class'] = 'hidden';
	    $classes = 'col-lg-3 col-md-6 col-12';
	    $checkmain = 'only_main';
	}
}
?>

<section id="main-container" class="main-content <?php echo apply_filters('educrat_course_content_class', 'container');?> inner <?php echo esc_attr($checkmain); ?>">
	<?php educrat_before_content( $sidebar_configs ); ?>
	<div class="row">
		<?php educrat_display_sidebar_left( $sidebar_configs ); ?>

		<div id="main-content" class="col-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
			<main id="main" class="site-main layout-courses display-mode-<?php echo esc_attr($display_mode); ?>" role="main">
	
				<?php
				/**
				 * LP Hook
				 */
				do_action( 'learn-press/before-courses-loop' );

				LP()->template( 'course' )->begin_courses_loop();
				
				while ( have_posts() ) :
					the_post();
					?>
					<div class="<?php echo esc_attr($classes); ?>">
						<?php learn_press_get_template_part( 'content-course', $inner ); ?>
					</div>
					<?php

				endwhile;

				LP()->template( 'course' )->end_courses_loop();

				/**
				 * @since 3.0.0
				 */
				do_action( 'learn-press/after-courses-loop' );


				/**
				 * LP Hook
				 */
				// do_action( 'learn-press/after-main-content' );

				educrat_paging_nav();
				
				?>

			</main><!-- .site-main -->
		</div><!-- .content-area -->
		
		<?php educrat_display_sidebar_right( $sidebar_configs ); ?>
		
	</div>
</section>

<?php

get_footer();
