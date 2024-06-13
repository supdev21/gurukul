<?php
	// Initialize argument variables
	!isset($course_filter) 		? $course_filter	 = false : 0;
	!isset($supported_filters) 	? $supported_filters = tutor_utils()->get_option( 'supported_course_filters', array() ) : 0;
	!isset($loop_content_only) 	? $loop_content_only = false : 0;
	!isset($column_per_row)		? $column_per_row 	 = tutor_utils()->get_option( 'courses_col_per_row', 3 ) : 0;
	!isset($course_per_page)	? $course_per_page	 = tutor_utils()->get_option( 'courses_per_page', 12 ) : 0;
	!isset($show_pagination)	? $show_pagination	 = true : 0;
	!isset($current_page)		? $current_page	 	 = 1 : 0;

	// Hide pagination is there is no page after first one
	$pages_count = 0;
	if(isset($the_query)){
		$pages_count = $the_query->max_num_pages;
	} else {
		global $wp_query;
	 	$pages_count = $wp_query->max_num_pages;
	}
	$pages_count<2 ? $show_pagination=false : 0;

	// Set in global variable to avoid too many stack to pass to other templates
	$GLOBALS['tutor_course_archive_arg'] = compact(
		'course_filter',
		'supported_filters',
		'loop_content_only',
		'column_per_row',
		'course_per_page',
		'show_pagination'
	);
	$sidebar_configs = educrat_tutor_get_course_archive_layout_configs();
	// Render the loop
	ob_start();
	do_action( 'tutor_course/archive/before_loop' );

	if ( (isset($the_query) && $the_query->have_posts()) || have_posts() ) {
		/* Start the Loop */

		tutor_course_loop_start();
		$item = educrat_get_config('tutor_courses_item_style');

		if ( defined('EDUCRAT_DEMO_MODE') && EDUCRAT_DEMO_MODE ) {
		    if (!empty($_GET['course']) && ($_GET['course'] =='style1') ){
		        $item = '';
		    } elseif (!empty($_GET['course']) && ($_GET['course'] =='style2') ){
		        $item = '2';
		    } elseif (!empty($_GET['course']) && ($_GET['course'] =='style3') ){
		        $item = '3';
		    } elseif (!empty($_GET['course']) && ($_GET['course'] =='style4') ){
		        $item = '4';
		        $sidebar_configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-12' );
		        unset($sidebar_configs['right']);
		        unset($sidebar_configs['left']);
		        $classes = 'col-lg-4 col-md-6 col-12';
		        $checkmain = 'only_main';
		    } elseif (!empty($_GET['course']) && ($_GET['course'] =='list1') ){
		        $item = 'list';
		        $sidebar_configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-12' );
		        unset($sidebar_configs['right']);
		        unset($sidebar_configs['left']);
		        $classes = 'col-lg-6 col-12';
		        $checkmain = 'only_main';
		    } elseif (!empty($_GET['course']) && ($_GET['course'] =='list2') ){
		        $item = 'list-v2';
		        $sidebar_configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-12' );
		        unset($sidebar_configs['right']);
		        unset($sidebar_configs['left']);
		        $classes = 'col-lg-6 col-12';
		        $checkmain = 'only_main';
		    } elseif (!empty($_GET['course']) && ($_GET['course'] =='list3') ){
		        $item = 'list-v3';
		        $classes = 'col-12';
		    } elseif (!empty($_GET['course']) && ($_GET['course'] =='list4') ){
		        $item = 'list-v4';
		        $classes = 'col-12';
		    } elseif (!empty($_GET['course']) && ($_GET['course'] =='list5') ){
		        $item = 'list-v5';
		        $classes = 'col-12';
		    } elseif (!empty($_GET['course']) && ($_GET['course'] =='filtertop') ){
		        $item = '';
		        $sidebar_configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-12' );
		        unset($sidebar_configs['right']);
		        unset($sidebar_configs['left']);
		        $classes = 'col-lg-3 col-md-6 col-12';
		        $checkmain = 'only_main';
		    }
		}


		if ( !empty($item) ) {
		    $item = '-'.$item;
		}

		while ( isset($the_query) ? $the_query->have_posts() : have_posts() ){
			isset($the_query) ? $the_query->the_post() : the_post();

			/**
			 * @hook tutor_course/archive/before_loop_course
			 * @type action
			 * Usage Idea, you may keep a loop within a wrap, such as bootstrap col
			 */
			do_action( 'tutor_course/archive/before_loop_course' );

			get_template_part( 'tutor/loop/course/course'.$item );
			/**
			 * @hook tutor_course/archive/after_loop_course
			 * @type action
			 * Usage Idea, If you start any div before course loop, you can end it here, such as </div>
			 */
			do_action( 'tutor_course/archive/after_loop_course' );
		}

		tutor_course_loop_end();
	} else {

		/**
		 * No course found
		 */
		// tutor_load_template('course-none');
		tutor_utils()->tutor_empty_state( tutor_utils()->not_found_text() );
	}

	do_action( 'tutor_course/archive/after_loop' );

	if($show_pagination) {
		// Load the pagination now
		global $wp_query;

		$current_url = wp_doing_ajax() ? $_SERVER['HTTP_REFERER'] : tutor()->current_url;
		$push_link = add_query_arg( array_merge( $_POST, $GLOBALS['tutor_course_archive_arg'] ), $current_url );

		$data = wp_doing_ajax(  ) ? $_POST : $_GET;
		$pagination_data = array(
			'total_page'  => isset($the_query) ? $the_query->max_num_pages : $wp_query->max_num_pages,
			'per_page'    => $course_per_page,
			'paged'       => $current_page,
			'data_set'	  => array('push_state_link'=>$push_link),
			'ajax'		  => array_merge($data, array(
				'loading_container' => '.tutor-course-filter-loop-container',
				'action' => 'tutor_course_filter_ajax',
			))
		);

		tutor_load_template_from_custom_path(
			tutor()->path . 'templates/dashboard/elements/pagination.php',
			$pagination_data
		);
	}
	
	$course_loop = ob_get_clean();

	if (isset($loop_content_only) && $loop_content_only==true) {
		echo trim($course_loop);
		return;
	}

	$course_archive_arg = isset($GLOBALS['tutor_course_archive_arg']) ? $GLOBALS['tutor_course_archive_arg']['column_per_row'] : null;
	$columns = $course_archive_arg === null ? tutor_utils()->get_option( 'courses_col_per_row', 3 ) : $course_archive_arg;
	$has_course_filters = $course_filter && count($supported_filters);

	$supported_filters_keys = array_keys( $supported_filters );

	$courses_layout  = educrat_get_config('tutor_courses_layout');
    $courses_filter_layout = educrat_get_config('tutor_courses_filter_layout', '');

    $add_class = $filter_offcanvas = $filter_top = '';
    if ( defined('EDUCRAT_DEMO_MODE') && EDUCRAT_DEMO_MODE && !empty($_GET['course']) ) {
		if( ($_GET['course'] =='style4') || ($_GET['course'] =='list1') || ($_GET['course'] =='list2') ){
			$filter_offcanvas = true;
		}
		if( $_GET['course'] =='filtertop' ){
			$filter_top = true;
		}
	}

    if ( ($courses_layout == 'main' && $courses_filter_layout == 'offcanvas' && isset($has_course_filters) && $has_course_filters) || $filter_offcanvas ) {
    	$add_class = 'filter-offcanvas';
    }
    if ( ($courses_layout == 'main' && $courses_filter_layout == 'top' && isset($has_course_filters) && $has_course_filters) || $filter_top ) {
    	$add_class = 'filter-top';
    }
?>

<div class="tutor-wrap tutor-wrap-parent tutor-courses-wrap tutor-container course-archive-page <?php echo esc_attr($add_class); ?>" data-tutor_courses_meta="<?php echo esc_attr( json_encode($GLOBALS['tutor_course_archive_arg']) ); ?>">
	<?php educrat_before_content( $sidebar_configs ); ?>
	<div class="row">
	<?php if ( $has_course_filters ): ?>
		<?php if ( isset($sidebar_configs['left']) ) { ?>
			<div class="sidebar-wrapper sidebar-course col-lg-3 col-12">
				<aside class="sidebar sidebar-left">
					<div class="close-sidebar-btn d-lg-none"> <i class="ti-close"></i> <span><?php echo esc_html__('Close','educrat') ?></span></div>
					<div class="tutor-course-filter" tutor-course-filter>
						<?php tutor_load_template('course-filter.filters'); ?>
					</div>
				</aside>
			</div>
		<?php } ?>

		<?php if ( isset($sidebar_configs['left']) || isset($sidebar_configs['right']) ) { ?>
			<div class="col-lg-9 col-12">
				<?php tutor_load_template('course-filter.course-archive-filter-bar', array('has_course_filters' => $has_course_filters) ); ?>
				<div class="tutor-pagination-wrapper tutor-pagination-wrapper-replaceable" tutor-course-list-container>
					<?php echo trim($course_loop); ?>
				</div>
			</div>
		<?php } else { ?>
			<div class="col-12">
				<?php tutor_load_template('course-filter.course-archive-filter-bar', array('has_course_filters' => $has_course_filters) ); ?>
				<div class="tutor-pagination-wrapper tutor-pagination-wrapper-replaceable" tutor-course-list-container>
					<?php echo trim($course_loop); ?>
				</div>
			</div>
		<?php } ?>

		<?php if ( isset($sidebar_configs['right']) ) { ?>
			<div class="sidebar-wrapper sidebar-course col-lg-3 col-12">
				<aside class="sidebar sidebar-right">
					<div class="close-sidebar-btn d-lg-none"> <i class="ti-close"></i> <span><?php echo esc_html__('Close','educrat') ?></span></div>
					<div class="tutor-course-filter" tutor-course-filter>
						<?php tutor_load_template('course-filter.filters'); ?>
					</div>
				</aside>
			</div>
		<?php } ?>

	<?php else: ?>
		<div class="tutor-col-12 tutor-pagination-wrapper tutor-pagination-wrapper-replaceable" tutor-course-list-container>
			<?php echo trim($course_loop); ?>
		</div>
	<?php endif; ?>
	</div>
</div>


<?php 
	if ( ! is_user_logged_in() ) {
		tutor_load_template_from_custom_path( tutor()->path . '/views/modal/login.php' );
	}
?>