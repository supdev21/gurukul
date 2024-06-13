<?php

defined( 'ABSPATH' ) || exit();


$profile = LP_Profile::instance($instructor->ID);

$user = $profile->get_user();
$statistic = $profile->get_statistic_info();

?>

<div <?php post_class('instructor-grid'); ?>>
    <div class="instructor-grid-inside-v2">
        <!-- instructor thumbnail -->
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
        ?>
        <div class="inner">
            <h3 class="instructor-name"><a href="<?php echo learn_press_user_profile_link( $instructor->ID ); ?>"><?php echo trim($user->get_display_name()); ?></a></h3>
            
            <?php
            $job_title = get_user_meta($instructor->ID, '_user_job_title', true);
            if ( $job_title ) {
            ?>
                <div class="job-title"><?php echo esc_html($job_title); ?></div>
            <?php } 
            $socials = $user->get_profile_socials($user->get_id());

            if ( !empty($socials) ) {
                ?>
                <div class="socials">
                    <?php echo implode('', $socials); ?>
                </div>
                <?php
            }
            ?>
            <a href="<?php echo learn_press_user_profile_link( $instructor->ID ); ?>" class="btn btn-theme btn-outline"><?php echo esc_html__('View Profile','educrat'); ?></a>
        </div>
    </div>
</div>