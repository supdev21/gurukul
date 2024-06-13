<?php
/**
 * Course Loop End
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$display_mode = educrat_get_config('tutor_courses_display_mode', 'grid');
$columns = educrat_get_config('tutor_courses_columns', 3);
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
<div class="<?php echo esc_attr($classes); ?>">