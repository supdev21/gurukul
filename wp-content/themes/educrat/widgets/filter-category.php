<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

extract( $args );

$t_args = array(
    'taxonomy' => 'course_category',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
);
$terms = get_terms($t_args);
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
    extract( $args );
    extract( $instance );
    echo trim($before_widget);
    $title = apply_filters('widget_title', $instance['title']);

    if ( $title ) {
        echo trim($before_title)  . trim( $title ) . $after_title;
    }

    $courses_page_id  = learn_press_get_page_id( 'courses' );
    $url = get_permalink($courses_page_id);
    $selected = isset($_GET['filter-category']) ? $_GET['filter-category'] : get_queried_object()->term_id ?? $_GET['term_id'] ?? '';
?>
    <div class="filter-categories-widget">
        <form action="<?php echo esc_url($url); ?>" method="get">
            <?php if ( $layout == 'select' ) { ?>
                <select name="filter-category" class="filter-category">
                    <?php
                    $query_args = array(
                        'post_type' => LP_COURSE_CPT,
                        'posts_per_page' => 1,
                        'fields' => 'ids'
                    );
                    $loop = new WP_Query($query_args);
                    $courses_count = $loop->found_posts;
                    ?>
                    <option value=""><?php esc_html_e('All Categories', 'educrat'); ?> (<?php echo esc_html($courses_count); ?>)</option>
                    <?php foreach ($terms as $term) { ?>
                        <option value="<?php echo esc_attr($term->term_id); ?>" <?php selected($selected, $term->term_id); ?>><?php echo esc_html($term->name); ?>(<?php echo esc_html($term->count); ?>)</option>
                    <?php } ?>
                </select>
            <?php } else { ?>
            	<ul class="course-category-list course-list-check">
                    <?php foreach ($terms as $term) {
                        $checked = '';
                        if ( !empty($selected) && is_array($selected) ) {
                            if ( in_array($term->term_id, $selected) ) {
                                $checked = 'checked="checked"';
                            }
                        } elseif (!empty($selected)) {
                            $checked = checked($selected, $term->term_id, false);
                        }
                    ?>
                        <li>
                            <input id="filter-category-<?php echo esc_attr($term->term_id); ?>" class="d-none" type="checkbox" name="filter-category[]" value="<?php echo esc_attr($term->term_id); ?>" <?php echo trim($checked); ?>>
                            <label for="filter-category-<?php echo esc_attr($term->term_id); ?>"><?php echo esc_html($term->name); ?> <span class="count">(<?php echo esc_html($term->count); ?>)</span></label>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <?php educrat_query_string_form_fields( null, array( 'submit', 'paged', 'filter-category' ) ); ?>
        </form>
    </div>
<?php echo trim($after_widget); }