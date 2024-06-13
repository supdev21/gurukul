<?php
/**
 * Instructor List Item
 *
 * Portrait/Default layout
 *
 *
 * @since v2.0.2
 */

extract( $args );
$instructor = isset( $instructor ) ? $instructor : array();
?>
<div class="instructor-grid">
    <div class="instructor-grid-inside">
        <div class="position-relative">
            <div class="instructor-cover">
                <a href="<?php echo esc_url( tutor_utils()->profile_url( $instructor->ID, true ) ); ?>" class="cover-inner position-relative">
                    <img class="tutor-instructor-cover-photo" src="<?php echo esc_url( get_avatar_url( $instructor->ID, array( 'size' => 500 ) ) ); ?>" alt="<?php echo esc_attr( $instructor->display_name ); ?>">
                </a>
            </div>
            <?php
                $socials = Educrat_Apus_Userinfo::tutor_socials_profile();
                if ($socials) {
                    ?>
                    <div class="socials">
                        <?php foreach ($socials as $k => $v) {
                            $value = get_user_meta($instructor->ID, '_user_tutor_'.$k, true);
                            if ( $value ) {
                                
                                switch ( $k ) {
                                    case 'facebook':
                                        $i = '<i class="fab fa-facebook-f"></i>';
                                        break;
                                    case 'twitter':
                                        $i = '<i class="fab fa-twitter"></i>';
                                        break;
                                    case 'googleplus':
                                        $i = '<i class="fab fa-google-plus-g"></i>';
                                        break;
                                    case 'youtube':
                                        $i = '<i class="fab fa-youtube"></i>';
                                        break;
                                    default:
                                        $i = sprintf( '<i class="fab fa-%s"></i>', $k );
                                }
                                echo sprintf( '<a href="%s">%s</a>', esc_url_raw( $value ), $i );
                                
                            }
                        ?>
                            
                        <?php } ?>
                    </div>
                    <?php
                }
            ?>
        </div>
        <div class="inner">
            
            <h3 class="instructor-name">
                <a href="<?php echo esc_url( tutor_utils()->profile_url( $instructor->ID, true ) ); ?>">
                    <?php echo esc_html( $instructor->display_name ); ?>
                </a>
            </h3>
            
            <?php
                $job = get_user_meta($instructor->ID, '_tutor_profile_job_title', true);
                if ( $job ) {
            ?>
                <div class="job-title"><?php echo trim($job); ?></div>
            <?php } ?>

            <?php
            $ratings = tutor_utils()->get_instructor_ratings( $instructor->ID );

            $courses_count  = absint( tutor_utils()->get_course_count_by_instructor( $instructor->ID ) );
            $students_count = absint( tutor_utils()->get_total_students_by_instructor( $instructor->ID ) );
            ?>
            <div class="instructor-bottom">
                <div class="d-inline-block">
                    <?php Educrat_Tutor_Course_Review::print_review_star($ratings->rating_avg); ?>
                </div>
                <div class="d-inline-block">
                    <i class="flaticon-online-learning-5"></i>
                    <?php trim( $students_count ); ?>
                    <?php $students_count > 1 ? esc_html_e( 'Students', 'educrat' ) : esc_html_e( 'Student', 'educrat' ); ?>
                </div>
                <div class="d-inline-block">
                    <i class="flaticon-play-1"></i>
                    <?php echo esc_html( $courses_count ); ?>
                    <?php $courses_count > 1 ? esc_html_e( 'Courses', 'educrat' ) : esc_html_e( 'Course', 'educrat' ); ?>
                </div>
            </div>
        </div>

    </div>
</div>