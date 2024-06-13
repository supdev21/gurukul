<?php

global $post;

if ( empty($post->post_type) || $post->post_type != 'simple_event' ) {
	return;
}

extract( $args );
extract( $args );
extract( $instance );

$product_id = get_post_meta($post->ID, 'apussimpleevent_event_product_id', true);

$product    = wc_get_product( $product_id );

	echo trim($before_widget);
	echo '<div class="apus_event_product">';
	$title = apply_filters('widget_title', $instance['title']);

	if ( $title ) {
	    echo trim($before_title)  . trim( $title ) . $after_title;
	}
	?>
	<div class="widget_product">
	<?php
	if ( $product ) {
	?>
		
	        <div class="event-meta-price">
	            <?php echo wp_kses( $product->get_price_html(), array( 'span' => array( 'class' => true ), 'del' => array( 'class' => true ), 'ins' => array( 'class' => true ) ) ); ?>
	        </div>

	        <?php

	        global $woocommerce;
	        $added_cart = false;
			if ( $woocommerce->cart ) {
				foreach ( $woocommerce->cart->get_cart() as $key => $val ) {
					if ( $product_id == $val['product_id'] ) {
						$added_cart = true;
					}
				}
			}

	        if ( $added_cart ) {
				?>
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-theme">
						<?php esc_html_e( 'View Cart', 'educrat' ); ?>
					</a>
				<?php
			} else {
				?>
		        <form action="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>" method="post" enctype="multipart/form-data">
		            <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"  class="btn btn-theme">
		                <?php esc_html_e( 'Book Now', 'educrat' ); ?>
		            </button>
		        </form>
				<?php
			}
			?>
	    
<?php
	} else {
		?>
		<p class="tutor-alert-warning">
			<?php esc_html_e( 'Please make sure that your product exists and valid for this course', 'educrat' ); ?>
		</p>
		<?php
	}

	if ( educrat_get_config('show_event_social_share', false) ) { ?>
		<div class="social-share">
			<?php get_template_part( 'template-parts/sharebox' ); ?>
		</div>
	<?php } ?>

</div>
</div>
<?php echo trim($after_widget);

