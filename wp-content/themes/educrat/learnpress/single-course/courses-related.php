<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;

$relate_count = educrat_get_config('number_course_related', 4);
$relate_columns = educrat_get_config('related_course_columns', 4);
$terms = get_the_terms( $post->ID, 'course_category' );
$termids = array();

if ($terms) {
    foreach($terms as $term) {
        $termids[] = $term->term_id;
    }
}

$args = array(
    'post_type' => LP_COURSE_CPT,
    'posts_per_page' => $relate_count,
    'post__not_in' => array( $post->ID ),
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'course_category',
            'field' => 'id',
            'terms' => $termids,
            'operator' => 'IN'
        )
    )
);
$relates = new WP_Query( $args );
if( $relates->have_posts() ):
?>
<div class="widget widget-courses-related">
    <h3 class="widget-title">
        <?php esc_html_e( 'Related Courses', 'educrat' ); ?>
    </h3>
    <div class="related-courses-content widget-content">
        <div class="slick-carousel" data-carousel="slick" data-small="1" data-smallest="1" data-medium="2" data-large="2" data-items="<?php echo esc_attr($relate_columns); ?>" data-pagination="false" data-nav="true">
            <?php while ( $relates->have_posts() ) : $relates->the_post(); ?>
                <?php learn_press_get_template_part( 'content-course' ); ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</div>
<?php endif; ?>


<?php  

           /* if (function_exists('ci_comment_rating_rating_field')) {
                ci_comment_rating_rating_field();
            }

            if (function_exists('ci_comment_rating_display_average_rating')) {
                //ci_comment_rating_display_average_rating();
            }*/

        ?>