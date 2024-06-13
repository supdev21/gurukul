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

$s       = LP_Request::get( 'c_search' );
?>
<div class="widget_search">
    <form class="search-courses" method="get" action="<?php echo esc_url( learn_press_get_page_link( 'courses' ) ); ?>">
            <input type="hidden" name="post_type" value="<?php echo esc_attr( LP_COURSE_CPT ); ?>">
            <input type="hidden" name="taxonomy" value="<?php echo esc_attr( get_queried_object()->taxonomy ?? $_GET['taxonomy'] ?? '' ); ?>">
            <input type="hidden" name="term_id" value="<?php echo esc_attr( get_queried_object()->term_id ?? $_GET['term_id'] ?? '' ); ?>">
            <input type="hidden" name="term" value="<?php echo esc_attr( get_queried_object()->slug ?? $_GET['term'] ?? '' ); ?>">
            <input type="text" class="form-control" placeholder="<?php esc_attr_e( 'Search courses...', 'educrat' ); ?>" name="c_search" value="<?php echo esc_attr( $s ); ?>">
            <button class="btn" type="submit"><i class="flaticon-search"></i></button>

            <?php educrat_query_string_form_fields( null, array( 'submit', 'paged', 'c_search' ) ); ?>
    </form>
</div>
<?php echo trim($after_widget);