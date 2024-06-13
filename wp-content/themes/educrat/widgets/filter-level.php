<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
extract( $args );

$levels = lp_course_level();

if ( ! empty( $levels ) ) {
    extract( $args );
    extract( $instance );
    echo trim($before_widget);
    $title = apply_filters('widget_title', $instance['title']);

    if ( $title ) {
        echo trim($before_title)  . trim( $title ) . $after_title;
    }

    $courses_page_id  = learn_press_get_page_id( 'courses' );
    $url = get_permalink($courses_page_id);
    $selected = isset($_GET['filter-level']) ? $_GET['filter-level'] : '';
    
?>
    <div class="filter-levels-widget">
        <form action="<?php echo esc_url($url); ?>" method="get">
            <?php if ( $layout == 'select' ) { ?>
                <select name="filter-level" class="filter-level">
                    <?php foreach ($levels as $key => $title) {
                        if ( $key ) {
                            $meta_query = array(
                                array(
                                    'key' => '_lp_level',
                                    'value' => $key,
                                    'compare' => '=',
                                )
                            );
                        } else {
                            $meta_query = array();
                        }
                        $query_args = array(
                            'post_type' => LP_COURSE_CPT,
                            'posts_per_page' => 1,
                            'meta_query' => $meta_query,
                            'fields' => 'ids'
                        );
                        $loop = new WP_Query($query_args);
                        $courses_count = $loop->found_posts;
                    ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($selected, $key); ?>><?php echo esc_html($title); ?> (<?php echo esc_html($courses_count); ?>)</option>
                    <?php } ?>
                </select>
            <?php } else { ?>
            	<ul class="level-list course-list-check">
                    <?php foreach ($levels as $key => $title) {
                        if ( $key ) {
                            $meta_query = array(
                                array(
                                    'key' => '_lp_level',
                                    'value' => $key,
                                    'compare' => '=',
                                )
                            );
                        } else {
                            $meta_query = array();
                        }
                        $query_args = array(
                            'post_type' => LP_COURSE_CPT,
                            'posts_per_page' => 1,
                            'meta_query' => $meta_query,
                            'fields' => 'ids'
                        );
                        $loop = new WP_Query($query_args);
                        $courses_count = $loop->found_posts;

                        $checked = '';
                        if ( !empty($selected) && is_array($selected) ) {
                            if ( in_array($key, $selected) ) {
                                $checked = 'checked="checked"';
                            }
                        } elseif (!empty($selected)) {
                            $checked = checked($selected, $key, false);
                        }
                    ?>
                        <li>
                            <input id="filter-level-<?php echo esc_attr($key); ?>" type="checkbox" class="d-none" name="filter-level[]" value="<?php echo esc_attr($key); ?>" <?php echo trim($checked); ?>>
                            <label for="filter-level-<?php echo esc_attr($key); ?>"><?php echo esc_html($title); ?> <span class="count">(<?php echo esc_html($courses_count); ?>)</span></label>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            
            <input type="hidden" name="post_type" value="<?php echo esc_attr( LP_COURSE_CPT ); ?>">
            <input type="hidden" name="taxonomy" value="<?php echo esc_attr( get_queried_object()->taxonomy ?? $_GET['taxonomy'] ?? '' ); ?>">
            <input type="hidden" name="term_id" value="<?php echo esc_attr( get_queried_object()->term_id ?? $_GET['term_id'] ?? '' ); ?>">
            <input type="hidden" name="term" value="<?php echo esc_attr( get_queried_object()->slug ?? $_GET['term'] ?? '' ); ?>">
            
            <?php educrat_query_string_form_fields( null, array( 'submit', 'paged', 'filter-level' ) ); ?>
        </form>
    </div>
<?php echo trim($after_widget); }