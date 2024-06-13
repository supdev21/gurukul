<?php
$product_item = isset($product_item) ? $product_item : 'inner';

$mdcol = 12/$columns;
$smcol = 12/$columns_tablet;
$xscol = 12/$columns_mobile;


wc_set_loop_prop( 'loop', 0 );
wc_set_loop_prop( 'columns', $columns );

$classes = array();
$classes[] = 'col-xl-'.esc_attr($mdcol).' col-md-'.esc_attr($smcol).' col-'.esc_attr( $xscol );

?>
<div class="products products-grid">
	<div class="row row-products">
		
		<?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
			<div <?php post_class( $classes ); ?>>
			 	<?php wc_get_template_part( 'item-product/'.$product_item ); ?>
			</div>
		<?php endwhile; ?>

	</div>
</div>
<?php wp_reset_postdata(); ?>