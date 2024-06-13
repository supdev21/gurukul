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
$selected = isset($_GET['filter-price']) ? $_GET['filter-price'] : '';

?>
<div class="filter-price-widget">
    <form action="<?php echo esc_url($url); ?>" method="get">

        <?php if ( $layout == 'select' ) { ?>
            <select name="filter-price" class="filter-price">
                <?php
                    $query_args = array(
                        'post_type' => LP_COURSE_CPT,
                        'posts_per_page' => 1,
                        'fields' => 'ids'
                    );
                    $loop = new WP_Query($query_args);
                    $courses_count = $loop->found_posts;
                    ?>
                    <option value="" <?php selected($selected, ''); ?>><?php echo esc_html_e('All', 'educrat'); ?> (<?php echo esc_html($courses_count); ?>)</option>

                    <?php
                    $meta_query = array(array(
                        'relation' => 'OR',
                        array(
                            'key' => '_lp_price',
                            'value' => '0',
                            'compare' => '=',
                        ),
                        array(
                            'key' => '_lp_price',
                            'value' => '',
                            'compare' => '=',
                        ),
                        array(
                            'key' => '_lp_price',
                            'compare' => 'NOT EXISTS',
                        )
                    ));
                    $query_args = array(
                        'post_type' => LP_COURSE_CPT,
                        'posts_per_page' => 1,
                        'meta_query' => $meta_query,
                        'fields' => 'ids'
                    );
                    $loop = new WP_Query($query_args);
                    $courses_count = $loop->found_posts;
                    ?>
                    <option value="free" <?php selected($selected, 'free'); ?>><?php echo esc_html_e('Free', 'educrat'); ?> (<?php echo esc_html($courses_count); ?>)</option>

                    <?php
                    $meta_query = array( array(
                        'key' => '_lp_price',
                        'value' => '0',
                        'compare' => '>',
                    ));
                    $query_args = array(
                        'post_type' => LP_COURSE_CPT,
                        'posts_per_page' => 1,
                        'meta_query' => $meta_query,
                        'fields' => 'ids'
                    );
                    $loop = new WP_Query($query_args);
                    $courses_count = $loop->found_posts;
                    ?>
                    <option value="paid" <?php selected($selected, 'paid'); ?>><?php echo esc_html_e('Paid', 'educrat'); ?> (<?php echo esc_html($courses_count); ?>)</option>
            </select>
        <?php } else { ?>

        	<ul class="price-list course-list-check">
                <li>
                    <?php
                    $query_args = array(
                        'post_type' => LP_COURSE_CPT,
                        'posts_per_page' => 1,
                        'fields' => 'ids'
                    );
                    $loop = new WP_Query($query_args);
                    $courses_count = $loop->found_posts;
                    ?>
                    <input id="filter-price-all" type="radio" class="d-none" name="filter-price" value="" <?php checked($selected, ''); ?>>
                    <label for="filter-price-all"><?php echo esc_html_e('All', 'educrat'); ?> <span class="count">(<?php echo esc_html($courses_count); ?>)</span></label>
                </li>
                <li>
                    <?php
                    $meta_query = array(array(
                        'relation' => 'OR',
                        array(
                            'key' => '_lp_price',
                            'value' => '0',
                            'compare' => '=',
                        ),
                        array(
                            'key' => '_lp_price',
                            'value' => '',
                            'compare' => '=',
                        ),
                        array(
                            'key' => '_lp_price',
                            'compare' => 'NOT EXISTS',
                        )
                    ));
                    $query_args = array(
                        'post_type' => LP_COURSE_CPT,
                        'posts_per_page' => 1,
                        'meta_query' => $meta_query,
                        'fields' => 'ids'
                    );
                    $loop = new WP_Query($query_args);
                    $courses_count = $loop->found_posts;
                    ?>
                    <input id="filter-price-free" type="radio" class="d-none" name="filter-price" value="free" <?php checked($selected, 'free'); ?>>
                    <label for="filter-price-free"><?php echo esc_html_e('Free', 'educrat'); ?> <span class="count">(<?php echo esc_html($courses_count); ?>)</span></label>
                </li>
                <li>
                    <?php
                    $meta_query = array( array(
                        'key' => '_lp_price',
                        'value' => '0',
                        'compare' => '>',
                    ));
                    $query_args = array(
                        'post_type' => LP_COURSE_CPT,
                        'posts_per_page' => 1,
                        'meta_query' => $meta_query,
                        'fields' => 'ids'
                    );
                    $loop = new WP_Query($query_args);
                    $courses_count = $loop->found_posts;
                    ?>
                    <input id="filter-price-paid" type="radio" class="d-none" name="filter-price" value="paid" <?php checked($selected, 'paid'); ?>>
                    <label for="filter-price-paid"><?php echo esc_html_e('Paid', 'educrat'); ?> <span class="count">(<?php echo esc_html($courses_count); ?>)</span></label>
                </li>
            </ul>
        <?php } ?>
        <input type="hidden" name="post_type" value="<?php echo esc_attr( LP_COURSE_CPT ); ?>">
        <input type="hidden" name="taxonomy" value="<?php echo esc_attr( get_queried_object()->taxonomy ?? $_GET['taxonomy'] ?? '' ); ?>">
        <input type="hidden" name="term_id" value="<?php echo esc_attr( get_queried_object()->term_id ?? $_GET['term_id'] ?? '' ); ?>">
        <input type="hidden" name="term" value="<?php echo esc_attr( get_queried_object()->slug ?? $_GET['term'] ?? '' ); ?>">
        
        <?php educrat_query_string_form_fields( null, array( 'submit', 'paged', 'filter-price' ) ); ?>
    </form>
</div>
<?php echo trim($after_widget); ?>