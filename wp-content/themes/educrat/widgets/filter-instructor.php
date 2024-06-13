<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
extract( $args );
$instructors = educrat_course_get_instructors();

if ( ! empty( $instructors ) ) {
    extract( $args );
    extract( $instance );
    echo trim($before_widget);
    $title = apply_filters('widget_title', $instance['title']);

    if ( $title ) {
        echo trim($before_title)  . trim( $title ) . $after_title;
    }

    $courses_page_id  = learn_press_get_page_id( 'courses' );
    $url = get_permalink($courses_page_id);
    $selected = isset($_GET['filter-instructor']) ? $_GET['filter-instructor'] : '';
    
?>
    <div class="filter-instructors-widget">
        <form action="<?php echo esc_url($url); ?>" method="get">
            <?php if ( $layout == 'select' ) { ?>
                <select name="filter-instructor" class="filter-instructor">
                    <?php
                    $query_args = array(
                        'post_type' => LP_COURSE_CPT,
                        'posts_per_page' => 1,
                        'fields' => 'ids'
                    );
                    $loop = new WP_Query($query_args);
                    $courses_count = $loop->found_posts;
                    ?>
                    <option value=""><?php esc_html_e('All Instructors', 'educrat'); ?> (<?php echo esc_html($courses_count); ?>)</option>
                    <?php foreach ($instructors as $instructor) {
                        $query_args = array(
                            'post_type' => LP_COURSE_CPT,
                            'posts_per_page' => 1,
                            'author' => $instructor->ID,
                            'fields' => 'ids'
                        );
                        $loop = new WP_Query($query_args);
                        $courses_count = $loop->found_posts;
                    ?>
                        <option value="<?php echo esc_attr($instructor->ID); ?>" <?php selected($selected, $instructor->ID); ?>><?php echo esc_html($instructor->display_name); ?> (<?php echo esc_html($courses_count); ?>)</option>
                    <?php } ?>
                </select>
            <?php } else { ?>
            	<ul class="instructor-list course-list-check">
                    <?php foreach ($instructors as $instructor) {
                        $query_args = array(
                            'post_type' => LP_COURSE_CPT,
                            'posts_per_page' => 1,
                            'author' => $instructor->ID,
                            'fields' => 'ids'
                        );
                        $loop = new WP_Query($query_args);
                        $courses_count = $loop->found_posts;

                        $checked = '';
                        if ( !empty($selected) && is_array($selected) ) {
                            if ( in_array($instructor->ID, $selected) ) {
                                $checked = 'checked="checked"';
                            }
                        } elseif (!empty($selected)) {
                            $checked = checked($selected, $instructor->ID, false);
                        }
                    ?>
                        <li>
                            <input id="filter-instructor-<?php echo esc_attr($instructor->ID); ?>" type="checkbox" class="d-none" name="filter-instructor[]" value="<?php echo esc_attr($instructor->ID); ?>" <?php echo trim($checked); ?>>
                            <label for="filter-instructor-<?php echo esc_attr($instructor->ID); ?>"><?php echo esc_html($instructor->display_name); ?> <span class="count">(<?php echo esc_html($courses_count); ?>)</span></label>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <?php educrat_query_string_form_fields( null, array( 'submit', 'paged', 'filter-instructor' ) ); ?>
        </form>
    </div>
<?php echo trim($after_widget); }