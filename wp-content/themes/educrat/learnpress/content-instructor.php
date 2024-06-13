<?php

defined( 'ABSPATH' ) || exit();


$profile = LP_Profile::instance($instructor->ID);

$user = $profile->get_user();
$statistic = $profile->get_statistic_info();

?>

<div <?php post_class('instructor-grid'); ?>>
    <div class="instructor-grid-inside">
        <!-- instructor thumbnail -->
        <div class="position-relative">
        <?php
            $image = $user->get_profile_picture();
            if ( $image ) {
                ?>
                <div class="instructor-cover">
                    <a class="cover-inner position-relative" href="<?php echo learn_press_user_profile_link( $instructor->ID ); ?>">
                        <?php echo trim($image); ?>
                    </a>
                </div>
                <?php
            } else {
                ?>
                <div class="instructor-cover no-image">
                    <a class="cover-inner position-relative" href="<?php echo learn_press_user_profile_link( $instructor->ID ); ?>">
                        
                    </a>
                </div>
                <?php
            }
            
            $socials = $user->get_profile_socials($user->get_id());
            if ( !empty($socials) ) {
                ?>
                <div class="socials">
                    <?php echo implode('', $socials); ?>
                </div>
                <?php
            }

        ?>
        </div>
        <div class="inner">
            <h3 class="instructor-name"><a href="<?php echo learn_press_user_profile_link( $instructor->ID ); ?>"><?php echo trim($user->get_display_name()); ?></a></h3>
            
            <?php
            $job_title = get_user_meta($instructor->ID, '_user_job_title', true);
            if ( $job_title ) {
            ?>
                <div class="job-title"><?php echo esc_html($job_title); ?></div>
            <?php } ?>

            <div class="instructor-bottom">
                <?php
                $total_users = $statistic['total_users'];
                $total_courses = $statistic['total_courses'];
                
                $rating_avg = Educrat_Course_Review::get_total_rating_by_user($user->ID);
                ?>
                <div class="d-inline-block">
                    <?php Educrat_Course_Review::print_review_star($rating_avg); ?>
                </div>
                <div class="d-inline-block">
                    <i class="flaticon-online-learning-5"></i>
                    <?php echo sprintf( _n( '%d Student', '%d Students', $total_users, 'educrat' ), $total_users ); ?>
                </div>
                <div class="d-inline-block">
                    <i class="flaticon-play-1"></i>
                    <?php echo sprintf( _n( '%d Course', '%d Courses', $total_courses, 'educrat' ), $total_courses ); ?>
                </div>
            </div>
            
        </div>
    </div>
</div>