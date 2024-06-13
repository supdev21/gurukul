<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

			/*
			$userInfo = array(
                'sub' => 'd1597daf-0c8a-4ca2-9555-e34ef22e27b5',
                'email_verified' => 1,
                'ucc' => 3100069,
                'dob' => '01/01/2000',
                'mobile' => '9158855873',
                'name' => 'Amit Mahindkar',
                'preferred_username' => 3100069,
                'given_name' => 'Amit',
                'pan' => 'AESPJ2212K',
                'family_name' => 'Mahindkar',
                'email' => 'ankita.bhor@definedge.com',
                'user_groups' => array()
            );
            */

           /* $json = json_encode($userInfo);
            // Output the JSON string
            echo $json;*/


            /*echo 'User Info: <pre>';
            print_r($userInfo);
            echo '</pre>';*/


		if (isset($userInfo['ucc'])) {
            // Get the "ucc" value from the query parameter
            $ucc = $userInfo['ucc'];
            // Now you can use $ucc in your checkout page
            //echo 'UCC: ' . $ucc;

            WC()->session->set('user_id', $ucc);
        }else{

        	$ucc = 00000;
        	WC()->session->set('user_id', $ucc);
        }


do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}else{

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if(is_user_logged_in()){ ?>

		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="col2-set" id="customer_details">
				<div class="col-1">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="col-2">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>
			</div>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php endif; ?>

	<?php } else { ?>
		
		<div class="woocommerce-form-login-toggle">
	
			<div class="woocommerce-info other-login-regi">
				<!--<a class="nav-link" role="button" href="<?php //echo wp_login_url(get_permalink()); ?>">Login</a>-->
				<?php echo 'New User? Click here to'; ?>
        		<?php echo  do_shortcode('[openid_connect_generic_login_button]'); ?>	
    		</div>
		</div>

	<?php } ?>
	
	<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

	<?php
		/*session_start();
		if (isset($_SESSION['user_id'])) {
		    $user_id = $_SESSION['user_id'];

		    WC()->session->set('user_id', $user_id);

		    echo 'Authenticated User ID: ' . $user_id;
		}*/
	?>




	<?php

		if (isset($userInfo['ucc'])) {
            // Get the "ucc" value from the query parameter
            $ucc_demat = $userInfo['ucc'];

            if(is_user_logged_in()){
            	$current_user_id = get_current_user_id();
            }else{
            	$current_user_id = 0;
            }
            /*
            // Now you can use $ucc in your checkout page
            echo 'UCC: ' . $ucc;

            WC()->session->set('user_id', $ucc);*/

            // Array of specific course IDs
		    $specific_course_ids = array(11165,11449); // 11431, 11433 Replace these with the IDs of your specific LearnPress courses
		    $is_course_in_cart = false;
		    $cart = WC()->cart->get_cart();
		    /*echo '<pre>';
		    print_r($cart);
		    echo '</pre>';*/
		    foreach ($cart as $cart_item_key => $cart_item) {
		        $product_id = $cart_item['product_id'];
		        /*if ($product_id === $specific_course_id) {*/

		        if (in_array($product_id, $specific_course_ids)) {

		            $is_course_in_cart = true;

		            /* --------- insert data in custom table -------------*/

			        global $wpdb;
					$table_name = 'wp_demat_user_course';
					$data = array(
					    'free_course_id' => $product_id,
					    'ucc_user_id' => $ucc_demat,
					    'course_user' => $product_id.'_'.$ucc_demat.'_'.$current_user_id,
					    'sol_user_id' => $current_user_id,
					    'status' => 'not_buy_yet',
					    // Add more columns and values as needed
					);

					// Format of data (%s for string, %d for integer, %f for float)
					$data_format = array('%d','%d','%s','%d','%s');

					$wpdb->insert( $table_name, $data, $data_format );

					if ( $wpdb->last_error ) {
					    //echo "Error: " . $wpdb->last_error;
					    echo '<p class="buy-already">You already buy this course</p>';

					} else {
					    //echo "Data inserted successfully!";

					    $pass_data_on_order_success = $product_id.'_'.$ucc_demat.'_'.$current_user_id;
					    WC()->session->set('new_buy', $pass_data_on_order_success);

					}

			        /* --------- End insert data in custom table ------------*/	


		            //break;
		        }
		    }
		    // If any of the specific courses are in the cart or order, display the checkbox
		    if ($is_course_in_cart) {
		        echo '<div id="custom_checkout_checkbox_field"><h3>' . __('Do you have Demat Account?', 'woocommerce') . '</h3>';
		        woocommerce_form_field('custom_verification', array(
		            'type' => 'checkbox',
		            'class' => array('input-checkbox'),
		            'label' => __('If Yes, Please verify your Demat Account', 'woocommerce'),
		            'label_class' => array(),
		            'required' => false,
		            'default' => 1, // Set this parameter to 1 to check the checkbox by default
		        ), WC()->checkout->get_value('custom_verification'));
		        echo '</div>';

		        echo '<p class="verify-message"><span>&#10003;</span> Your demat account is verified</p>';

		    }

		    
        }else{

        	// Array of specific course IDs
		    $specific_course_ids = array(11165,11449); // 11431, 11433 Replace these with the IDs of your specific LearnPress courses
		    $is_course_in_cart = false;
		    $cart = WC()->cart->get_cart();
		    /*echo '<pre>';
		    print_r($cart);
		    echo '</pre>';*/
		    foreach ($cart as $cart_item_key => $cart_item) {
		        $product_id = $cart_item['product_id'];
		        /*if ($product_id === $specific_course_id) {*/
		        if (in_array($product_id, $specific_course_ids)) {
		            $is_course_in_cart = true;
		            break;
		        }
		    }
		    // If any of the specific courses are in the cart or order, display the checkbox
		    if ($is_course_in_cart) {
		        echo '<div id="custom_checkout_checkbox_field"><h3>' . __('Do you have Demat Account?', 'woocommerce') . '</h3>';
		        woocommerce_form_field('custom_verification', array(
		            'type' => 'checkbox',
		            'class' => array('input-checkbox'),
		            'label' => __('If Yes, Please verify your Demat Account', 'woocommerce'),
		            'label_class' => array(),
		            'required' => false,
		        ), WC()->checkout->get_value('custom_verification'));
		        echo '</div>';
		    }

		    /*echo '<p class="request-verify-message"><span>&#10003;</span> Please verify your demat account!</p>';
		    echo '<a href="">Open Demat Account</a>';*/
        }
		

	?>

	
	<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>
	
	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">

		<?php do_action( 'woocommerce_checkout_order_review' ); ?>

	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<?php } ?>