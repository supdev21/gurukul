<?php
$product_item = isset($product_item) ? $product_item : 'inner';
$show_nav = isset($show_nav) ? $show_nav : false;
$show_pagination = isset($show_pagination) ? $show_pagination : false;

$columns = !empty($columns) ? $columns : 3;
$columns_tablet = !empty($columns_tablet) ? $columns_tablet : 2;
$columns_mobile = !empty($columns_mobile) ? $columns_mobile : 1;

$slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
$slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
$slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;

$rows = isset($rows) ? $rows : 1;

$slick_top = (!empty($slick_top)) ? $slick_top : '';
?>
<div class="slick-carousel products <?php echo esc_attr($slick_top); ?> <?php echo esc_attr( ( $columns >= $loop->post_count ) ? 'hidden-dots':'' ); ?>"
    data-carousel="slick"
    data-items="<?php echo esc_attr($columns); ?>"
    data-large="<?php echo esc_attr( $columns_tablet ); ?>"
    data-medium="2"
    data-small="<?php echo esc_attr($columns_mobile); ?>"

    data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>"
    data-slidestoscroll_smallmedium="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
    data-slidestoscroll_extrasmall="<?php echo esc_attr($slides_to_scroll_mobile); ?>"

    data-pagination="<?php echo esc_attr( $show_pagination ? 'true' : 'false' ); ?>" data-nav="<?php echo esc_attr( $show_nav ? 'true' : 'false' ); ?>" data-rows="<?php echo esc_attr( $rows ); ?>">

    <?php wc_set_loop_prop( 'loop', 0 ); ?>
    <?php while ( $loop->have_posts() ): $loop->the_post(); global $product; ?>
        <div class="item">
            <div class="product clearfix">
                <?php wc_get_template_part( 'item-product/'.$product_item ); ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<?php wp_reset_postdata(); ?>