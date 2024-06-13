<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $post;

if ( ! $course = LP_Global::course() ) {
	return;
}

extract( $args );
extract( $args );
extract( $instance );
$title = apply_filters('widget_title', $instance['title']);
echo trim($before_widget);
if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}

$duration = $course->get_data( 'duration' );
$language = get_post_meta($post->ID, '_lp_language', true);
$skill_level = get_post_meta($post->ID, '_lp_level', true);
$certificate = get_post_meta($post->ID, '_lp_certificate', true);
$max_students = get_post_meta($post->ID, '_lp_max_students', true);
$more_info = get_post_meta($post->ID, '_lp_more_info', true);

?>
<div class="course-info-widget">
    
    <?php
    $layout_type = educrat_course_layout_type();

    if ( $layout_type == 'v1' || $layout_type == 'v2' || $layout_type == 'v3' || $layout_type == 'v4' ) {
        learn_press_get_template( 'single-course/video.php' );
    }
    ?>
    <div class="bottom-inner">
    	<?php if ( !learn_press_is_learning_course() ) { ?>
    		<div class="course-price d-flex align-items-center">
                <div class="sale-price"><?php echo trim($course->get_course_price_html()); ?></div>
                <?php if(!empty($course->get_origin_price_html()) && !empty($course->get_sale_price()) ){ ?>
                    <div class="origin-price ms-auto"><?php echo trim($course->get_origin_price_html()); ?></div>
                <?php } ?>
            </div>
    	<?php } ?>
        
        <?php learn_press_get_template( 'single-course/buttons.php' ); ?>

    	<div class="inner">
        	<ul class="lp-course-info-fields">
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
        	    <ul class="lp-course-info-fields style2">
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
<?php echo trim($after_widget);