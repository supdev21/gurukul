<?php
/**
 * Plugin Name: Email Template Customizer for WooCommerce
 * Plugin URI: https://villatheme.com/extensions/woocommerce-email-template-customizer/
 * Description: Make your WooCommerce emails become professional.
 * Version: 1.2.2
 * Author: VillaTheme
 * Author URI: https://villatheme.com
 * Text Domain: viwec-email-template-customizer
 * Domain Path: /languages
 * Copyright 2019-2024 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 6.4.3
 * WC requires at least: 6.0
 * WC tested up to: 8.6.0
 * Requires PHP: 7.0
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//compatible with 'High-Performance order storage (COT)'
add_action( 'before_woocommerce_init', function () {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
});
if ( is_plugin_active( 'woocommerce-email-template-customizer/woocommerce-email-template-customizer.php' ) ) {
	return;
}

define( 'VIWEC_VER', '1.2.2' );
define( 'VIWEC_NAME', 'WooCommerce Email Template Customizer' );

if ( ! class_exists( 'Woo_Email_Template_Customizer' ) ) {
	class Woo_Email_Template_Customizer {
		public $err_message;
		public $wp_version_require = '5.0';
		public $wc_version_require = '6.0';
		public $php_version_require = '7.0';

		public function __construct() {
			$this->condition_init();

			add_action( 'admin_notices', array( $this, 'admin_notices_condition' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_actions_link' ), 99 );
		}

		public function condition_init() {
			global $wp_version;
			if ( version_compare( $this->wp_version_require, $wp_version, '>' ) ) {
				$this->err_message = __( 'Please upgrade WordPress version to', 'viwec-email-template-customizer' ) . ' ' . $this->wp_version_require;

				return;
			}

			if ( version_compare( $this->php_version_require, phpversion(), '>' ) ) {
				$this->err_message = __( 'Please upgrade php version to', 'viwec-email-template-customizer' ) . ' ' . $this->php_version_require;

				return;
			}

			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				$this->err_message = __( 'Please install and activate WooCommerce to use', 'viwec-email-template-customizer' );
				unset( $_GET['activate'] );  // phpcs:ignore WordPress.Security.NonceVerification
				deactivate_plugins( plugin_basename( __FILE__ ) );

				return;
			}

			$wc_version = get_option( 'woocommerce_version' );
			if ( version_compare( $this->wc_version_require, $wc_version, '>' ) ) {
				$this->err_message = __( 'Please upgrade WooCommerce version to', 'viwec-email-template-customizer' ) . ' ' . $this->wc_version_require;

				return;
			}

			$this->define();

			if ( is_file( VIWEC_INCLUDES . 'init.php' ) ) {
				require_once VIWEC_INCLUDES . 'init.php';
				register_activation_hook( __FILE__, array( $this, 'viwec_activate' ) );
			}
		}

		public function define() {
			$plugin_url = plugin_dir_url( __FILE__ );

			define( 'VIWEC_SLUG', 'woocommerce-email-template-customizer' );
			define( 'VIWEC_DIR', plugin_dir_path( __FILE__ ) );
			define( 'VIWEC_INCLUDES', VIWEC_DIR . "includes" . DIRECTORY_SEPARATOR );
			define( 'VIWEC_SUPPORT', VIWEC_INCLUDES . "support" . DIRECTORY_SEPARATOR );
			define( 'VIWEC_TEMPLATES', VIWEC_INCLUDES . "templates" . DIRECTORY_SEPARATOR );
			define( 'VIWEC_LANGUAGES', VIWEC_DIR . "languages" . DIRECTORY_SEPARATOR );

			define( 'VIWEC_CSS', $plugin_url . "assets/css/" );
			define( 'VIWEC_JS', $plugin_url . "assets/js/" );
			define( 'VIWEC_IMAGES', $plugin_url . "assets/img/" );
		}

		public function viwec_activate() {
			$check_exist = get_posts( [ 'post_type' => 'viwec_template', 'numberposts' => 1 ] );

			if ( empty( $check_exist ) ) {
				$default_subject = \VIWEC\INC\Email_Samples::default_subject();
				$templates       = \VIWEC\INC\Email_Samples::sample_templates();
				$site_title      = get_option( 'blogname' );
				foreach ( $templates as $key => $template ) {
					$args     = [
						'post_title'  => $default_subject[ $key ] ? str_replace( '{site_title}', $site_title, $default_subject[ $key ] ) : '',
						'post_status' => 'publish',
						'post_type'   => 'viwec_template',
					];
					$post_id  = wp_insert_post( $args );
					$template = $template['basic']['data'];
					$template = str_replace( '\\', '\\\\', $template );
					update_post_meta( $post_id, 'viwec_settings_type', $key );
					update_post_meta( $post_id, 'viwec_email_structure', $template );
				}
				update_option( 'viwec_email_update_button', true, 'no' );
			}
		}

		public function admin_notices_condition() {
			if ( $this->err_message ) {
				?>
                <div id="message" class="error">
                    <p><?php echo esc_html( $this->err_message . ' ' . __( 'to use', 'viwec-email-template-customizer' ) . ' ' . VIWEC_NAME ); ?></p>
                </div>
				<?php
			}
		}

		public function plugin_actions_link( $links ) {
			if ( ! $this->err_message ) {
				$settings_link = '<a href="' . admin_url( 'edit.php?post_type=viwec_template' ) . '">' . __( 'Settings', 'viwec-email-template-customizer' ) . '</a>';
				array_unshift( $links, $settings_link );
			}

			return $links;
		}
	}

	new Woo_Email_Template_Customizer();
}