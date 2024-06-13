<?php

defined( 'ABSPATH' ) || exit();

class LP_WC_Hooks {
	private static $instance;
	/**
	 * Localize script lp woo
	 *
	 * @var array $localize_lp_woo
	 */
	protected $localize_lp_woo = array();

	protected function __construct() {
		$this->localize_lp_woo = apply_filters(
			'learnpress/localize/lp_woo',
			array(
				'url_ajax'                          => admin_url( 'admin-ajax.php' ),
				'woo_enable_signup_and_login_from_checkout' => get_option( 'woocommerce_enable_signup_and_login_from_checkout' ),
				'woocommerce_enable_guest_checkout' => get_option( 'woocommerce_enable_guest_checkout' ),
			)
		);

		$this->hooks();
	}

	protected function hooks() {
		//add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'redirect_to_checkout' ), 10, 1 );
		add_action( 'woocommerce_order_status_changed', array( $this, 'lp_order_update_status' ), 10, 3 );
		add_action( 'learn-press/order/status-changed', array( $this, 'lp_woo_update_status_order_for_woo' ), 10, 4 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'create_lp_order' ), 10, 2 );
		add_action( 'woocommerce_store_api_checkout_update_order_meta', array( $this, 'create_lp_order_from_api' ), 10, 1 );
		// For case when payment via stripe of woo - completed order didn't complete LP Order
		add_action( 'woocommerce_thankyou', array( $this, 'update_lp_order_status' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'admin_notices', array( $this, 'wc_order_notice' ), 99 );
		add_filter( 'learn-press/order-payment-method-title', array( $this, 'lp_woo_payment_method_title' ), 10, 2 );
		add_filter( 'woocommerce_cart_item_quantity', array( $this, 'disable_quantity_box' ), 10, 3 );
		// Add LP Order key on the email Woo
		add_action( 'woocommerce_email_order_meta', array( $this, 'show_lp_order_key_on_wc_order' ), 99 );
		// Add Lp Order key on the order woo detail
		add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'show_lp_order_key_on_wc_order' ), 10 );

		if ( ! LP_Gateway_Woo::is_by_courses_via_product() ) {
			add_filter( 'woocommerce_json_search_found_products', array( $this, 'wc_json_search_found_products_and_courses' ) );
			add_filter( 'woocommerce_get_order_item_classname', array( $this, 'get_classname_lp_wc_order' ), 10, 3 );
			// add_filter( 'woocommerce_get_product_from_item', array( $this, 'set_type_product_course_from_wc_order_item' ), 10, 3 );
			add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'order_item_line' ), 10, 4 );
			add_filter( 'woocommerce_product_class', array( $this, 'product_class' ), 10, 4 );
			// Remove button purchase course
			remove_action( 'learn-press/course-buttons', Learnpress::instance()->template( 'course' )->func( 'course_purchase_button' ), 10 );
		}

		// Add button "add to cart" on archive course page
		add_action( 'learn-press/course-buttons', array( $this, 'btn_add_to_cart' ) );
		add_action( 'learn-press/after-courses-loop-item', array( $this, 'btn_add_to_cart' ), 55 );

		// Add tab to profile.
		add_filter( 'learn-press/profile-tabs', array( $this, 'profile_tabs_woo_order' ) );

		// Update customer_id for woocommerce_order when LP user enter Recover key
		add_action( 'learn-press/order/recovered-successful', array( $this, 'update_woocommerce_order_by_recovered_key' ), 10, 2 );
	}

	/**
	 * Show LP Order key in order Woo
	 *
	 * @param WC_Order $wc_order
	 *
	 * @return void
	 * @sicne 4.0.7
	 * @author hoangvlm
	 */
	public function show_lp_order_key_on_wc_order( $wc_order ) {
		$order_id = $wc_order->get_id();

		if ( ! $order_id ) {
			return;
		}

		$lp_order_id = get_post_meta( $order_id, '_learn_press_order_id', true );
		if ( ! $lp_order_id ) {
			return;
		}

		$lp_order = learn_press_get_order( $lp_order_id );
		if ( ! $lp_order ) {
			return;
		}

		$order_key = $lp_order->get_order_key();

		ob_start();
		?>
		<br class="clear" />
		<div class="lp_woo_order_key">
			<h3><?php esc_html_e( 'LP Order key:', 'learnpress-woo-payment' ); ?> <span style="font-weight:normal; color: rgba(0,128,0,0.61)">
					<?php echo $order_key; ?>
				</span>
			</h3>
		</div>
		<?php
		echo ob_get_clean();
	}

	/**
	 * Enable redirect checkout
	 *
	 * @param string|bool $url
	 * @return string|bool
	 */
	public function redirect_to_checkout( $url ) {
		if ( 'yes' === LP_Settings::get_option( 'woo-payment_redirect_to_checkout', 'no' ) ) {
			$url = wc_get_checkout_url();
		}
		return $url;
	}

	/**
	 * Get the product class name.
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @param int
	 *
	 * @return string
	 */
	public function product_class( $classname, $product_type, $post_type, $product_id ): string {
		if ( LP_COURSE_CPT === get_post_type( $product_id ) ) {
			$classname = 'WC_Product_LP_Course';
		}

		return $classname;
	}

	/**
	 * Disable select quantity product has post_type 'lp_course'
	 *
	 * @param string $product_quantity
	 * @param string $cart_item_key
	 * @param array  $cart_item
	 *
	 * @return string
	 */
	public function disable_quantity_box( string $product_quantity, string $cart_item_key, array $cart_item ): string {
		$product_id       = $cart_item['product_id'] ?? 0;
		$quantity_disable = false;

		if ( get_class( $cart_item['data'] ) === 'WC_Product_LP_Course' ) {
			$quantity_disable = true;
		} elseif ( LP_Gateway_Woo::is_by_courses_via_product() ) {
			$product_has_courses = get_post_meta( $product_id, LP_Woo_Assign_Course_To_Product::$meta_key_lp_woo_courses_assigned, true );
			if ( ! empty( $product_has_courses ) ) {
				$quantity_disable = true;
			}
		}

		if ( $quantity_disable ) {
			$product_quantity = sprintf(
				'<span style="text-align: center; display: block">%s</span>',
				$cart_item['quantity']
			);
		}

		return $product_quantity;
	}

	/**
	 * Update LearnPress order status when WooCommerce updated status
	 *
	 * @param int    $wc_order_id
	 * @param string $old_status
	 * @param string $new_status
	 *
	 * @throws Exception
	 */
	public function lp_order_update_status( int $wc_order_id = 0, string $old_status = '', string $new_status = '' ) {
		if ( $old_status === $new_status ) {
			return;
		}

		$lp_order_id = get_post_meta( $wc_order_id, '_learn_press_order_id', true );

		if ( ! empty( $lp_order_id ) ) {
			$lp_order = learn_press_get_order( $lp_order_id );

			if ( $lp_order ) {
				$lp_order_status_need_update = 'wc-' === substr( $new_status, 0, 3 ) ? substr(
					$new_status,
					3
				) : $new_status;

				$lp_order->update_status( $lp_order_status_need_update, false );
			}
		}
	}

	/**
	 * Update status of Woo order if exists
	 *
	 * @param int    $lp_order_id
	 * @param string $old_status
	 * @param string $new_status
	 */
	public function lp_woo_update_status_order_for_woo( int $lp_order_id, string $old_status, string $new_status ) {
		if ( empty( $lp_order_id ) ) {
			return;
		}

		$woo_order_id = get_post_meta( $lp_order_id, '_woo_order_id', true );

		if ( ! empty( $woo_order_id ) ) {
			$woo_order = wc_get_order( $woo_order_id );

			if ( $woo_order ) {
				$wc_order_status_need_update = 'wc-' . str_replace( 'lp-', '', $new_status );

				$woo_order->update_status( $wc_order_status_need_update );
			}
		}
	}

	/**
	 * Create LP order base on WC order data
	 *
	 * @param $wc_order_id
	 * @param $posted
	 *
	 * @throws Exception
	 */
	public function create_lp_order( $wc_order_id, $posted ) {
		$lp_woo_order = new LP_Woo_Order( 0, $wc_order_id );
		$lp_woo_order->create_lp_order();
	}

	/**
	 * Create LP order base on WC order data when wc_order is created from API since WC-7.2
	 * This is similar to existing core hook woocommerce_checkout_update_order_meta.
	 * WC is using a new action:
	 * - To keep the interface focused (only pass $order, not passing request data).
	 * - This also explicitly indicates these orders are from checkout block/StoreAPI.
	 * @see https://github.com/woocommerce/woocommerce-gutenberg-products-block/pull/3686
	 *
	 * @param $wc_order $order WC Order object.
	 *
	 * @throws Exception
	 */
	public function create_lp_order_from_api( $wc_order ) {
		if ( ! $wc_order instanceof \WC_Order ) {
			return;
		}

		$wc_order_id  = $wc_order->get_id();
		$lp_woo_order = new LP_Woo_Order( 0, $wc_order_id );
		$lp_woo_order->create_lp_order();
	}

	/**
	 * For case when payment via stripe of woo - completed order didn't complete LP Order
	 *
	 * @param $order_id
	 * @throws Exception
	 * @author minhpd
	 * @since 4.0.2
	 * @version 1.0.0
	 */
	public function update_lp_order_status( $order_id ) {
		$wc_order = wc_get_order( $order_id );
		if ( ! $wc_order ) {
			return;
		}
		$status      = $wc_order->get_status();
		$lp_order_id = get_post_meta( $order_id, '_learn_press_order_id', true );

		if ( ! $lp_order_id ) {
			return;
		}

		$lp_order = learn_press_get_order( $lp_order_id );
		if ( ! $lp_order ) {
			return;
		}

		$lp_order->update_status( $status );
	}

	public function load_assets() {
		$version_asset = LP_ADDON_WOO_PAYMENT_VER;
		if ( LP_Debug::is_debug() ) {
			$version_asset = LP_Assets::$_version_assets;
		}

		wp_register_style(
			'lp-woo-css',
			plugins_url( '/', LP_ADDON_WOO_PAYMENT_FILE ) . 'assets/lp_woo' . LP_Assets::$_min_assets . '.css',
			array(),
			$version_asset
		);
		wp_register_script(
			'lp-woo-payment-js',
			plugins_url( '/', LP_ADDON_WOO_PAYMENT_FILE ) . 'assets/lp_woo' . LP_Assets::$_min_assets . '.js',
			array( 'jquery' ),
			$version_asset,
			[
				'in_footer' => true,
				'strategy' => 'defer'
			]
		);

		wp_localize_script( 'lp-woo-payment-js', 'localize_lp_woo_js', $this->localize_lp_woo );

		if ( ! LP_Gateway_Woo::is_by_courses_via_product() && ( LP_PAGE_COURSES === LP_Page_Controller::page_current() || LP_PAGE_PROFILE === LP_Page_Controller::page_current() ) ) {
			wp_enqueue_style( 'lp-woo-css' );
			wp_enqueue_script( 'lp-woo-payment-js' );
		}
	}

	/**
	 * Show Add-to-cart course button if is enabled
	 *
	 * @editor tungnx
	 * @throws Exception
	 * @version 1.0.1
	 * @since  4.0.2
	 */
	public function btn_add_to_cart( $course = null ) {
		if ( LP_Gateway_Woo::is_by_courses_via_product() ) { // For theme can easily use
			return;
		}

		if ( empty( $course ) ) {
			$course = learn_press_get_course();
		}

		if ( ! $course ) {
			return;
		}

		// Check user purchased or enrolled or finished
		$current_user = get_current_user_id();
		$user         = learn_press_get_user( $current_user );

		if ( ! $user ) {
			return;
		}

		if ( $course->get_external_link() ) {
			return;
		}

		$can_purchase = $user->can_purchase_course( $course->get_id() );
		if ( ! $can_purchase || is_wp_error( $can_purchase ) ) {
			return;
		}

		// Course is not require enrolling.
		if ( $course->is_no_required_enroll() ) {
			return;
		}

		if ( $course->is_free() ) {
			if ( LP_PAGE_SINGLE_COURSE !== LP_Page_Controller::page_current() ) {
				do_action( 'learnpress/woo-payment/course-free/btn_add_to_cart_before', $course ); // For the Eduma theme add button "Read more"
			}

			return;
		}

		wp_enqueue_style( 'lp-woo-css' );
		wp_enqueue_script( 'lp-woo-payment-js' );

		$is_added_to_cart = $this->is_added_in_cart( $course->get_id() );
		if ( $is_added_to_cart instanceof WP_Error ) {
			return;
		}

		LP_Addon_Woo_Payment_Preload::$addon->get_template( 'add-course-to-cart', compact( 'course', 'is_added_to_cart' ) );
	}

	/**
	 * @param $course_id
	 * @param $quantity
	 * @param $item_data
	 *
	 * @return bool|string
	 * @throws Exception
	 * @deprecated 4.1.0
	 */
	public function add_course_to_cart( $course_id, $quantity, $item_data ) {
		_deprecated_function( __FUNCTION__, '4.1.0', 'LP_Gateway_Woo::add_to_cart()' );
		$cart          = WC()->cart;
		$cart_id       = $cart->generate_cart_id( $course_id, 0, array(), $item_data );
		$cart_item_key = $cart->find_product_in_cart( $cart_id );
		if ( $cart_item_key ) {
			$cart->remove_cart_item( $cart_item_key );
		}

		return $cart->add_to_cart( absint( $course_id ), absint( $quantity ), 0, array(), $item_data );
	}

	/**
	 * Return true if a course is already added into WooCommerce cart
	 *
	 * @param int $course_id
	 * @author tungnx
	 * @version 1.0.2
	 * @since 3.2.1
	 *
	 * @return bool|WP_Error
	 */
	public function is_added_in_cart( int $course_id ) {
		global $wpdb;

		try {
			// Don't use WC_Cart on here, make error null something
			// $cart       = new WC_Cart();
			// $key_cart_item = $cart->generate_cart_id( $course_id );
			$wc_session    = new WC_Session_Handler();
			$session       = $wc_session->get_session_cookie();
			$key_cart_item = md5( implode( '_', array( $course_id ) ) );

			if ( empty( $session ) ) {
				return false;
			}

			$cookie_hash = $session[0] ?? '';

			if ( empty( $cookie_hash ) ) {
				return false;
			}

			$query = $wpdb->prepare(
				"
				SELECT session_value FROM {$wpdb->prefix}woocommerce_sessions
				WHERE session_key = %s
				AND session_value LIKE %s
				",
				$cookie_hash,
				'%' . $key_cart_item . '%'
			);

			$result = $wpdb->get_var( $query );

			if ( empty( $result ) ) {
				return false;
			}

			$result_arr  = maybe_unserialize( $result );
			$result_cart = maybe_unserialize( $result_arr['cart'] );

			if ( array_key_exists( $key_cart_item, $result_cart ) ) {
				return true;
			} else {
				return false;
			}
		} catch ( Throwable $e ) {
			return new WP_Error( $e->getMessage() );
		}
	}

	/**
	 * Add Woocommerce Order on tab profile
	 */
	public function profile_tabs_woo_order( $tabs ) {
		$tabs['lp_orders_woocommerce'] = array(
			'title'    => esc_html__( 'Order Woocommerce', 'learnpress-woo-payment' ),
			'slug'     => 'orders_woocommerce',
			'callback' => array( $this, 'profile_tabs_woo_order_content' ),
			'priority' => 25,
			'icon'     => '<i class="fas fa-shopping-cart" aria-hidden="true"></i>',
		);

		return $tabs;
	}

	/**
	 * Content of profile order woocommerce page.
	 */
	public function profile_tabs_woo_order_content() {
		global $wp;
		$url   = home_url( $wp->request );
		$parts = explode( '/', $url );

		$total_records  = wc_get_customer_order_count( get_current_user_id() );
		$posts_per_page = get_option( 'posts_per_page' );
		$total_pages    = ceil( $total_records / $posts_per_page );

		$paged = end( $parts ) === 'orders_woocommerce' ? 1 : end( $parts );
		$from  = ( $paged - 1 ) * $posts_per_page + 1;
		$to    = $from + $posts_per_page - 1;
		$to    = min( $to, $total_records );
		if ( $total_records < 1 ) {
			$from = 0;
		}

		$offset = array( $from, $to );
		/*
		$customer_orders = get_posts(
			array(
				'meta_key'       => '_customer_user',
				'meta_value'     => get_current_user_id(),
				'post_type'      => wc_get_order_types( 'view-orders' ),
				'posts_per_page' => $posts_per_page,
				'paged'          => $paged,
				'post_status'    => array_keys( wc_get_order_statuses() ),
			)
		);*/
		$customer_orders = wc_get_orders(
			array(
				'customer' => get_current_user_id(),
				'limit'    => $posts_per_page,
				'page'     => $paged,
				'type'     => wc_get_order_types( 'view-orders' ),
			)
		);

		$format_text        = __( 'Displaying {{from}} to {{to}} of {{total}} {{item_name}}.', 'learnpress-woo-payment' );
		$output_format_text = str_replace(
			array( '{{from}}', '{{to}}', '{{total}}', '{{item_name}}' ),
			array(
				$offset[0],
				$offset[1],
				$total_records,
				'items',
			),
			$format_text
		);

		LP_Addon_Woo_Payment_Preload::$addon->get_template( 'wc-order-profile', compact( 'customer_orders', 'format_text', 'output_format_text', 'total_pages', 'paged' ) );
	}

	/**
	 * Show related LP Order on WC Order
	 */
	public function wc_order_notice() {
		global $post, $pagenow;
		if ( $pagenow !== 'post.php' || empty( $post ) ) {
			return;
		}

		$post_type = get_post_type( $post->ID );

		if ( 'shop_order' === $post_type ) {
			$lp_order_id = get_post_meta( $post->ID, '_learn_press_order_id', true );
			if ( ! $lp_order_id ) {
				return;
			}

			$lp_order = learn_press_get_order( $lp_order_id );
			?>
			<div class="notice notice-warning woo-payment-order-notice">
				<p>
					<?php
					echo sprintf(
						'%s %s',
						__( 'This order is related to LearnPress order', 'learnpress-woo-payment' ),
						$lp_order ? '<a href="' . get_edit_post_link( $lp_order_id ) . '">#' . $lp_order_id . '</a>' : '#' . $lp_order_id
					);
					?>
				</p>
			</div>
			<?php
		} elseif ( LP_ORDER_CPT === $post_type ) {
			$wc_order_id = get_post_meta( $post->ID, '_woo_order_id', true );
			if ( ! $wc_order_id ) {
				return;
			}

			$wc_order = wc_get_order( $wc_order_id );
			?>
			<div class="notice notice-warning woo-payment-order-notice">
				<p>
					<?php
					echo sprintf(
						'%s %s<br>%s',
						__( 'This order is related to Woocommerce order', 'learnpress-woo-payment' ),
						$wc_order ? '<a href="' . get_edit_post_link( $wc_order_id ) . '">#' . $wc_order_id . '</a>' : '#' . $wc_order_id,
						__( 'If you want to change status of this Order, you must go to Woocommerce Order related and change', 'learnpress-woo-payment' )
					);
					?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Add item line meta data contains our course_id from product_id in cart.
	 * Since WC 3.x order item line product_id always is 0 if it is not a REAL product.
	 * Need to track course_id for creating LP order in WC hook after this action.
	 *
	 * @param $item
	 * @param $cart_item_key
	 * @param $values
	 * @param $order
	 */
	public function order_item_line( $item, $cart_item_key, $values, $order ) {
		if ( LP_COURSE_CPT === get_post_type( $values['product_id'] ) ) {
			$item->add_meta_data( '_course_id', $values['product_id'], true );
		}
	}

	/**
	 * @param $product
	 * @param $item
	 * @param $order
	 *
	 * @return mixed|WC_Product_LP_Course
	 * @throws Exception
	 */
	public function set_type_product_course_from_wc_order_item( $product, $item, $order ) {
		if ( get_class( $item ) !== 'WC_Order_Item_LP_Course' ) {
			$course_id = wc_get_order_item_meta( $item->get_id(), '_course_id', true );
			if ( $course_id && LP_COURSE_CPT === get_post_type( $course_id ) ) {
				$product = new WC_Product_LP_Course( $course_id );
			}
		}

		return $product;
	}

	/**
	 * Get classname WC_Order_Item_LP_Course
	 *
	 * @throws Exception
	 */
	public function get_classname_lp_wc_order( $classname, $item_type, $id ) {
		if ( in_array( $item_type, array( 'line_item', 'product' ) ) ) {
			$course_id = wc_get_order_item_meta( $id, '_course_id' );
			if ( $course_id && LP_COURSE_CPT === get_post_type( $course_id ) ) {
				$classname = 'WC_Order_Item_LP_Course';
			}
		}

		return $classname;
	}

	/**
	 * Set payment method title for LP Order
	 *
	 * @since 4.0.2
	 * @version 1.0.1
	 * @param $title
	 * @param $lp_order
	 *
	 * @return mixed|string
	 */
	public function lp_woo_payment_method_title( $title, $lp_order ) {
		$woo_order_id = get_post_meta( $lp_order->get_id(), '_woo_order_id', true );

		if ( ! empty( $woo_order_id ) ) {
			$wc_order             = wc_get_order( $woo_order_id );
			$payment_method_title = '';
			if ( $wc_order ) {
				$payment_method_title = $wc_order->get_payment_method_title();
				$link_woo_order       = get_edit_post_link( $woo_order_id );
				$title                = sprintf( '<a href="%s" >Woo #%d: %s</a>', $link_woo_order, $woo_order_id, $payment_method_title );
			} else {
				$title = sprintf( 'Woo #%d: %s %s', $woo_order_id, $payment_method_title, __( 'Deleted', 'learnpress-woo-payment' ) );
			}
		}

		return $title;
	}

	/**
	 * For on WC Coupon data
	 *
	 * @param $products
	 *
	 * @return mixed
	 */
	public function wc_json_search_found_products_and_courses( $products ) {
		global $wpdb;
		$term = wc_clean( empty( $term ) ? stripslashes( $_GET['term'] ) : $term );
		$sql  = $wpdb->prepare(
			"
			SELECT ID, post_title FROM {$wpdb->posts}
			WHERE post_title LIKE %s
			AND post_type = 'lp_course'
			AND post_status = 'publish'",
			'%' . $wpdb->esc_like( $term ) . '%'
		);

		$rows = $wpdb->get_results( $sql );

		foreach ( $rows as $row ) {
			$products[ $row->ID ] = $row->post_title . ' (' . __( 'Course', 'learnpress-woo-payment' ) . ' #' . $row->ID . ')';
		}

		return $products;
	}

	/**
	 * [update_woocommerce_order_by_recovered_key update woocommerce order's customer_id when user enter LP Order recover key]
	 * @param $lp_order_id [lp order id]
	 * @param $user_id  [lp order user]
	 * @since 2.1.0
	 */
	public function update_woocommerce_order_by_recovered_key( $lp_order_id, $user_id ) {
		$woo_order_id = get_post_meta( $lp_order_id, '_woo_order_id', true );
		try {
			if ( ! empty( $woo_order_id ) ) {
				$wc_order = wc_get_order( $woo_order_id );
				if ( $wc_order ) {
					$wc_order->set_customer_id( $user_id );
					$wc_order->save();
				}
			}
		} catch ( Throwable $e ) {
			error_log( __METHOD__ . ': ' . $e->getMessage() );
		}
	}

	public static function instance(): LP_WC_Hooks {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
