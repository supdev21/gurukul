<?php

/**
 * A single course loop
 *
 * @since v.1.0.0
 * @author themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

global $post, $authordata;

if ( tutor_utils()->is_course_purchasable() ) {
    if (tutor_utils()->has_wc()) {
        $course_id  = get_the_ID();
        $product_id = tutor_utils()->get_course_product_id( $course_id );
        $product    = wc_get_product( $product_id );
    }
}
?>

<div <?php post_class('course-layout-item'); ?>>
    <div class="course-list course-list-v3 v4 m-0">
        <div class="course-entry d-sm-flex align-items-center">

            <!-- course thumbnail -->
            <?php if ( $image = educrat_display_post_thumb('educrat-course-list') ) { ?>
                <div class="course-cover flex-shrink-0">
                    <div class="course-cover-thumb"> 
                        <?php echo trim($image); ?>

                        <?php
                        if ( !empty($product) && $product->is_on_sale() )  {    
                            echo '<span class="sale-label">' . esc_html__('Sale', 'educrat') . '</span>';
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>

            <div class="course-layout-content flex-grow-1">
                <div class="d-sm-flex align-items-center">
                    <div class="flex-grow-1 main-info">
                        <div class="course-info-top">
                            <!-- rating -->
                            <?php
                                $course_rating = tutor_utils()->get_course_rating();
                                $rating_avg = $course_rating->rating_avg;
                                $total = $course_rating->rating_count;
                                if($total > 0) {
                            ?>
                                <div class="wrapper_rating_avg d-flex align-items-center">
                                    <span class="rating_avg"><?php echo number_format($rating_avg, 1,".","."); ?></span>
                                    <?php Educrat_Tutor_Course_Review::print_review($rating_avg, 'list', $total); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- course title -->  
                        <h3 class="course-title">
                            <a href="<?php the_permalink(); ?>" class="text-inherit"><?php the_title(); ?></a>
                        </h3>
                        <div class="course-excerpt d-none d-md-block"><?php echo wp_trim_words( $post->post_content, 25, '.' ); ?></div>
                        <div class="course-meta-middle">
                            <!-- teacher -->
                            <div class="lp-course-author d-none d-sm-inline-block">
                                <div class="d-flex align-items-center">
                                    <div class="course-author__pull-left d-flex align-items-center justify-content-center">
                                        <?php echo get_avatar($authordata->ID, '100'); ?>
                                    </div>
                                    <div class="author-title"><?php echo get_the_author(); ?></div>
                                </div>
                            </div>
                            <!-- number lessons -->
                            <div class="course-lesson-number course-meta-field">
                                <i class="flaticon-document"></i>
                                <?php
                                    $lesson_count = tutor_utils()->get_lesson_count_by_course(get_the_ID());
                                    echo number_format($lesson_count);
                                ?>
                                <?php echo esc_html__('Lessons','educrat'); ?>
                            </div>

                            <!-- time -->
                            <?php
                            $duration = get_tutor_course_duration_context();
                            ?>
                            <div class="course-duration course-meta-field">
                                <i class="flaticon-wall-clock"></i>
                                <?php echo trim( $duration ); ?>
                            </div>
                            
                            <?php
                            $level = get_post_meta( get_the_ID(), '_tutor_course_level', true );
                            $label = '';

                            if ( ! empty( $level ) ) {
                                $label = tutor_utils()->course_levels( $level );
                            }

                            if ( $label ) {
                            ?>
                                <div class="course-level course-meta-field">
                                    <i class="flaticon-bar-chart"></i>
                                    <?php echo trim( $label ); ?>
                                </div>
                            <?php } ?>
                           
                        </div>

                    </div>
                    
                    
                    <?php
                    
                    if ( !empty($product) ) {
                        ?>
                        <div class="course-meta-field course-meta-price ms-auto flex-shrink-0">
                            <div class="course-price">
                                <?php echo wp_kses( $product->get_price_html(), array( 'span' => array( 'class' => true ), 'del' => array( 'class' => true ), 'ins' => array( 'class' => true ) ) ); ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                
            </div>
        </div>
    </div>
</div>