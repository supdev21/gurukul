<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 8.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					echo '<li>' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'educrat' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'educrat' ) ) . '</li>';
				}
			?>
		</ul>
	<?php endif; ?>
	<div class="form-row place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'educrat' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'educrat' ); ?>"><?php esc_html_e( 'Update totals', 'educrat' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
		
<?php
		/*============ Place Order Button Code ===========*/

		$user_ucc = WC()->session->get('user_id');
		//echo $user_ucc;
		if($user_ucc != 0){

?>
		<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="btn btn-theme d-block alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

<?php

		}else{

			$special_course_ids = array(11165,11449); // 11431, 11433 Replace these with the IDs of your specific LearnPress courses
		    $course_in_cart = false;
			$cartbag = WC()->cart->get_cart();

			/*echo "<pre>";
			print_r($cartbag);
			echo "<pre>";*/ 
		    
			foreach ($cartbag as $cartbag_item_key => $cartbag_item) {
		        $prod_id = $cartbag_item['product_id'];
		       
		        if (in_array($prod_id, $special_course_ids)) {

		            $course_in_cart = true;

		            $postname = $cartbag_item['data']->post;

		            $post_title = $postname->post_title;

		            $ctitle = wc_get_product($prod_id);

		            	echo '<div class="message-onpay"><h4><strong>Notice :- </storng></h4>';
						echo '<ul class="pay-before-points">
						<li>1) The ‘'.$post_title.'’ course is a FREE course available only for Definedge Securities Demat Account Holders. If you have a demat account with us, kindly verify it by checking the "Verify your demat account" box above to enroll for the course.</li>
						<li>2) If you dont have Demat account , please remove this free course from your <a href="'.home_url().'/cart-2">Cart</a> OR <a href="https://signin.definedgesecurities.com/" target="_blank()">Open Demat account</a> with us.</li>
						</ul></div>';

					break;
		            
		        } 
		        
		    }

		    if (!$course_in_cart) {
		        	
		        ?>
		        	<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="btn btn-theme d-block alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

		        <?php
		    }
			
		}
		/*============ End Place Order Button Code ===========*/

?>


		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
