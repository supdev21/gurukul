<?php
/**
 * map custom field
 *
 * @package    apus-simple-event
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class ApusSimpleEvent_Field_Map {

	/**
	 * Current version number
	 */
	const VERSION = '1.0.0';

	/**
	 * Initialize the plugin by hooking into CMB2
	 */
	public static function init() {
		add_filter( 'cmb2_render_apus_map', array( __CLASS__, 'render_map' ), 10, 5 );
		add_filter( 'cmb2_sanitize_apus_map', array( __CLASS__, 'sanitize_map' ), 10, 4 );
	}

	/**
	 * Render field
	 */
	public static function render_map( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		self::setup_admin_scripts();
		echo '<input type="text" class="large-text apus-map-search" id="' . $field->args( 'id' ) . '" 
				name="'.$field->args( '_name' ).'[addess]" value="'.(isset( $field_escaped_value['address'] ) ? $field_escaped_value['address'] : '').'"/>';
		echo '<div class="apus-map"></div>';
		$field_type_object->_desc( true, true );

		echo $field_type_object->input( array(
			'type'       => 'text',
			'name'       => $field->args( '_name' ) . '[latitude]',
			'value'      => isset( $field_escaped_value['latitude'] ) ? $field_escaped_value['latitude'] : '',
			'class'      => 'apus-map-latitude',
			'desc'       => '',
		) );
		echo $field_type_object->input( array(
			'type'       => 'text',
			'name'       => $field->args( '_name' ) . '[longitude]',
			'value'      => isset( $field_escaped_value['longitude'] ) ? $field_escaped_value['longitude'] : '',
			'class'      => 'apus-map-longitude',
			'desc'       => '',
		) );
	}

	/**
	 * Optionally save the latitude/longitude values into two custom fields
	 */
	public static function sanitize_map( $override_value, $value, $object_id, $field_args ) {
		if ( isset( $field_args['split_values'] ) && $field_args['split_values'] ) {
			if ( ! empty( $value['latitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_latitude', $value['latitude'] );
			}

			if ( ! empty( $value['longitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_longitude', $value['longitude'] );
			}

			if ( ! empty( $value['address'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_address', $value['address'] );
			}
		}

		return $value;
	}

	/**
	 * Enqueue scripts and styles
	 */
	public static function setup_admin_scripts() {
		$key = apply_filters( 'apussimpleevent_map_api_key', 'AIzaSyAgLtmIukM56mTfet5MEoPsng51Ws06Syc' );
		wp_register_script( 'googlemap_admin_js', '//maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places&amp;key='.$key, null, null );
		wp_enqueue_script( 'apussimpleevent-google-maps', plugins_url( 'js/script.js', __FILE__ ), array( 'googlemap_admin_js' ), self::VERSION );
		wp_enqueue_style( 'apussimpleevent-google-maps', plugins_url( 'css/style.css', __FILE__ ), array(), self::VERSION );
	}
}

ApusSimpleEvent_Field_Map::init();
