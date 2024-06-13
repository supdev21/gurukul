<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;

$relate_count = educrat_get_config('tutor_number_course_related', 4);
$relate_columns = educrat_get_config('tutor_related_course_columns', 4);
$terms = get_the_terms( $post->ID, 'course-category' );
$termids = array();

if ($terms) {
    foreach($terms as $term) {
        $termids[] = $term->term_id;
    }
}

$args = array(
    'post_type' => 'courses',
    'posts_per_page' => $relate_count,
    'post__not_in' => array( $post->ID ),
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'course-category',
            'field' => 'id',
            'terms' => $termids,
            'operator' => 'IN'
        )
    )
);
$relates = new WP_Query( $args );
if( $relates->have_posts() ):
    $item_style = educrat_get_config('tutor_courses_item_style', '');
?>
<div class="widget widget-courses-related">
    <h3 class="widget-title">
        <?php esc_html_e( 'Related Courses', 'educrat' ); ?>
    </h3>
    <div class="related-courses-content widget-content">
        <div class="slick-carousel" data-carousel="slick" data-small="1" data-smallest="1" data-medium="2" data-large="2" data-items="<?php echo esc_attr($relate_columns); ?>" data-pagination="false" data-nav="true">
            <?php while ( $relates->have_posts() ) : $relates->the_post(); ?>
                <?php get_template_part( 'tutor/loop/course/course', $item_style ); ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</div>
<?php endif; ?>