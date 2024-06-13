<?php
/**
 * Settings for LP Woo Payment
 */

$desc_guest_checkout = sprintf(
	'%s<br><strong><i style="color: red">%s <a href="%s">WooCommerce Setting</a> %s"</i></strong>',
	__( 'Enable to redirect to page Checkout when add to cart', 'learnpress-woo-payment' ),
	__( 'To enable Guest checkout, please go to', 'learners-woo-payment' ),
	home_url( 'wp-admin/admin.php?page=wc-settings&tab=account' ),
	__( 'then enable 2 options: "Allow customers to place orders without an account" and "Allow customers to create an account during checkout', 'learners-woo-payment' )
);

$desc_enable = sprintf(
	'%s <br/> <a href="%s">%s</a>',
	__(
		'If enabled system will use Payment, Checkout page, Options of Woocommerce instead of Learnpress',
		'learnpress-woo-payment'
	),
	add_query_arg(
		array(
			'page' => 'wc-settings',
			'tab'  => 'checkout',
		),
		admin_url( 'admin.php' )
	),
	__( 'Set Woocommerce Payment methods', 'learners-woo-payment' )
);

$settings = array(
	array(
		'title' => esc_html__( 'General', 'learnpress' ),
		'type'  => 'title',
	),
	array(
		'title'   => __( 'Enable', 'learnpress-woo-payment' ),
		'id'      => '[enable]',
		'default' => 'yes',
		'type'    => 'checkbox',
		'class'   => 'woo_payment_enabled',
		'desc'    => $desc_enable,
	),
	array(
		'title'   => __( 'Buy courses via Product', 'learnpress-woo-payment' ),
		'id'      => 'buy_course_via_product',
		'default' => 'no',
		'type'    => 'checkbox',
		'class'   => '',
		'desc'    => __(
			'If enable system will access assign courses to product, and user want enroll/buy course must buy via product',
			'learnpress-woo-payment'
		),
	),
	array(
		'title'   => esc_html__( 'Redirect to Woo checkout', 'learnpress-woo-payment' ),
		'id'      => 'redirect_to_checkout',
		'default' => 'no',
		'type'    => 'checkbox',
		'class'   => '',
		'desc'    => $desc_guest_checkout,
	),
	array(
		'type' => 'sectionend',
	),
);

return apply_filters( 'lp-woo/settings', $settings );
