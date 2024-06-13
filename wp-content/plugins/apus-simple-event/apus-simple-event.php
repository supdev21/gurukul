<?php
/**
 * Plugin Name: Apus Simple Event
 * Plugin URI: http://apusthemes.com/apus-simple-event/
 * Description: Apus Simple Event is a simple event plugin, allow you can manager event easy
 * Version: 1.0.0
 * Author: ApusTheme
 * Author URI: http://apusthemes.com
 * Requires at least: 3.8
 * Tested up to: 4.1
 *
 * Text Domain: apus-simple-event
 * Domain Path: /languages/
 *
 * @package apus-simple-event
 * @category Plugins
 * @author ApusTheme
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists("ApusSimpleEvent") ){
	
	final class ApusSimpleEvent{

		private static $instance;

		public static function getInstance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof ApusSimpleEvent ) ) {
				self::$instance = new ApusSimpleEvent;
				self::$instance->setup_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 *
		 */
		public function setup_constants(){
			// Plugin version
			if ( ! defined( 'APUSSIMPLEEVENT_VERSION' ) ) {
				define( 'APUSSIMPLEEVENT_VERSION', '1.0.0' );
			}

			// Plugin Folder Path
			if ( ! defined( 'APUSSIMPLEEVENT_PLUGIN_DIR' ) ) {
				define( 'APUSSIMPLEEVENT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'APUSSIMPLEEVENT_PLUGIN_URL' ) ) {
				define( 'APUSSIMPLEEVENT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'APUSSIMPLEEVENT_PLUGIN_FILE' ) ) {
				define( 'APUSSIMPLEEVENT_PLUGIN_FILE', __FILE__ );
			}

			// Template Folder
			if ( ! defined( 'APUSSIMPLEEVENT_THEME_FILE' ) ) {
				define( 'APUSSIMPLEEVENT_THEME_FILE', get_template_directory() );
			}

			define( 'APUSSIMPLEEVENT_EVENT_PREFIX', 'apussimpleevent_event_' );
		}

		public function setup_cmb2_url() {
			return APUSSIMPLEEVENT_PLUGIN_URL . 'inc/vendors/cmb2/libraries';
		}

		public function includes() {
			/**
			 * Get the CMB2 bootstrap!
			 */
			if ( !class_exists('CMB2_Bootstrap_212') && file_exists( APUSSIMPLEEVENT_PLUGIN_DIR . 'inc/vendors/cmb2/libraries/init.php' ) ) {
				require_once APUSSIMPLEEVENT_PLUGIN_DIR . 'inc/vendors/cmb2/libraries/init.php';
				//Customize CMB2 URL
				add_filter( 'cmb2_meta_box_url', array($this, 'setup_cmb2_url') );
			}
			// cmb2 custom field
			if ( ! class_exists( 'Taxonomy_MetaData_CMB2' ) ) {
				require_once APUSSIMPLEEVENT_PLUGIN_DIR . 'inc/vendors/cmb2/taxonomy/Taxonomy_MetaData_CMB2.php';
			}
			require_once APUSSIMPLEEVENT_PLUGIN_DIR . 'inc/vendors/cmb2/custom-fields/map/map.php';

			
			require_once APUSSIMPLEEVENT_PLUGIN_DIR . 'inc/class-template-loader.php';
			require_once APUSSIMPLEEVENT_PLUGIN_DIR . 'inc/mixes-functions.php';
			
			apussimpleevent_includes( APUSSIMPLEEVENT_PLUGIN_DIR . 'inc/post-types/*.php' );
			apussimpleevent_includes( APUSSIMPLEEVENT_PLUGIN_DIR . 'inc/taxonomies/*.php' );
			
			require_once APUSSIMPLEEVENT_PLUGIN_DIR . 'inc/class-apussimpleevent-event.php';
		}
		/**
		 *
		 */
		public function load_textdomain() {
			// Set filter for ApusSimpleEvent's languages directory
			$lang_dir = dirname( plugin_basename( APUSSIMPLEEVENT_PLUGIN_FILE ) ) . '/languages/';
			$lang_dir = apply_filters( 'apussimpleevent_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'apus-simple-event' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'apus-simple-event', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/apussimpleevent/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/apussimpleevent folder
				load_textdomain( 'apus-simple-event', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/apussimpleevent/languages/ folder
				load_textdomain( 'apus-simple-event', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'apus-simple-event', false, $lang_dir );
			}
		}
	}
}

function ApusSimpleEvent() {
	return ApusSimpleEvent::getInstance();
}

ApusSimpleEvent();
