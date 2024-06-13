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
$instructor             = isset( $instructor ) ? $instructor : array();
?>
<div class="instructor-grid">
    <div class="instructor-grid-inside-v2">
        <div class="instructor-cover">
            <a href="<?php echo esc_url( tutor_utils()->profile_url( $instructor->ID, true ) ); ?>" class="cover-inner position-relative">
                <img class="tutor-instructor-cover-photo" src="<?php echo esc_url( get_avatar_url( $instructor->ID, array( 'size' => 500 ) ) ); ?>" alt="<?php echo esc_attr( $instructor->display_name ); ?>">
            </a>
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
            <a href="<?php echo esc_url( tutor_utils()->profile_url( $instructor->ID, true ) ); ?>" class="btn btn-theme btn-outline">
                <?php echo esc_html__('View Profile','educrat'); ?>
            </a>
        </div>
    </div>
</div>