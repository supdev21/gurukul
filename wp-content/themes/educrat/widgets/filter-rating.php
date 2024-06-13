<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
extract( $args );

extract( $args );
extract( $instance );
echo trim($before_widget);
$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}

$courses_page_id  = learn_press_get_page_id( 'courses' );
$url = get_permalink($courses_page_id);
$selected = isset($_GET['filter-rating']) ? $_GET['filter-rating'] : '';

?>
<div class="filter-rating-widget">
    <form action="<?php echo esc_url($url); ?>" method="get">
        <?php if ( $layout == 'select' ) { ?>
            <select name="filter-rating" class="filter-rating">
                <option value=""><?php esc_html_e('All Ratings', 'educrat'); ?></option>
                <?php for ($i=5; $i >= 1; $i--) { ?>
                    <option value="<?php echo esc_attr($i); ?>" <?php selected($selected, $i); ?>><?php echo esc_html($i); ?>+</option>
                <?php } ?>
            </select>
        <?php } else { ?>
        	<ul class="rating-list course-list-check">
                <?php for ($i=5; $i >= 1; $i--) { ?>

                    <?php
                    $meta_query = array(
                        array(
                            'key' => '_average_rating',
                            'value' => $i,
                            'compare' => '>=',
                        ),
                        array(
                            'key' => '_average_rating',
                            'value' => ($i + 1),
                            'compare' => '<',
                        ),
                    );
                    $query_args = array(
                        'post_type' => LP_COURSE_CPT,
                        'posts_per_page' => 1,
                        'meta_query' => $meta_query,
                        'fields' => 'ids'
                    );
                    $loop = new WP_Query($query_args);
                    $courses_count = $loop->found_posts;
                    ?>

                    <li>
                        <input id="filter-rating-<?php echo esc_attr($i); ?>" type="radio" class="d-none" name="filter-rating" value="<?php echo esc_html($i); ?>" <?php checked($selected, $i); ?>>
                        <label for="filter-rating-<?php echo esc_attr($i); ?>">
                            <?php Educrat_Course_Review::print_review($i); ?>

                            <span class="count">(<?php echo esc_html($courses_count); ?>)</span>
                        </label>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>

        <input type="hidden" name="post_type" value="<?php echo esc_attr( LP_COURSE_CPT ); ?>">
        <input type="hidden" name="taxonomy" value="<?php echo esc_attr( get_queried_object()->taxonomy ?? $_GET['taxonomy'] ?? '' ); ?>">
        <input type="hidden" name="term_id" value="<?php echo esc_attr( get_queried_object()->term_id ?? $_GET['term_id'] ?? '' ); ?>">
        <input type="hidden" name="term" value="<?php echo esc_attr( get_queried_object()->slug ?? $_GET['term'] ?? '' ); ?>">
        
        <?php educrat_query_string_form_fields( null, array( 'submit', 'paged', 'filter-rating' ) ); ?>
    </form>
</div>
<?php echo trim($after_widget); ?>