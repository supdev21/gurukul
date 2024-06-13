<?php
/**
 * event post type
 *
 * @package    apus-simple-event
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class ApusSimpleEvent_PostType_Event{

	/**
	 * init action and filter data to define resource post type
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'definition' ) );
		add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
	}
	/**
	 *
	 */
	public static function definition() {
		
		$labels = array(
			'name'                  => __( 'Apus Events', 'apus-simple-event' ),
			'singular_name'         => __( 'Event', 'apus-simple-event' ),
			'add_new'               => __( 'Add New Event', 'apus-simple-event' ),
			'add_new_item'          => __( 'Add New Event', 'apus-simple-event' ),
			'edit_item'             => __( 'Edit Event', 'apus-simple-event' ),
			'new_item'              => __( 'New Event', 'apus-simple-event' ),
			'all_items'             => __( 'All Events', 'apus-simple-event' ),
			'view_item'             => __( 'View Event', 'apus-simple-event' ),
			'search_items'          => __( 'Search Event', 'apus-simple-event' ),
			'not_found'             => __( 'No Events found', 'apus-simple-event' ),
			'not_found_in_trash'    => __( 'No Events found in Trash', 'apus-simple-event' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Apus Events', 'apus-simple-event' ),
		);

		$labels = apply_filters( 'apussimpleevent_postype_resource_labels' , $labels );

		register_post_type( 'simple_event',
			array(
				'labels'            => $labels,
				'supports'          => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
				'public'            => true,
				'has_archive'       => true,
				'rewrite'           => array( 'slug' => __( 'simple-event', 'apus-simple-event' ) ),
				'menu_position'     => 51,
				'categories'        => array(),
				'show_in_menu'  	=> true,
			)
		);
	}
	/**
	 *
	 */
	public static function metaboxes( array $metaboxes ) {
		$prefix = APUSSIMPLEEVENT_EVENT_PREFIX;
		
		$metaboxes[ $prefix . 'info' ] = array(
			'id'                        => $prefix . 'info',
			'title'                     => __( 'Event Information', 'apus-simple-event' ),
			'object_types'              => array( 'simple_event' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_info_fields()
		);
		
		return $metaboxes;
	}
	/**
	 *
	 */	
	public static function metaboxes_info_fields() {
		$prefix = APUSSIMPLEEVENT_EVENT_PREFIX;

		$fields = array(
			array(
			    'name' => __( 'Start Date', 'apus-simple-event' ),
			    'id'   => $prefix.'startdate',
			    'type' => 'text_date_timestamp'
			),
			array(
			    'name' => __( 'Finish Date', 'apus-simple-event' ),
			    'id'   => $prefix.'finishdate',
			    'type' => 'text_date_timestamp'
			),
			array(
			    'name' => __( 'Time', 'apus-simple-event' ),
			    'id' => $prefix.'time',
			    'type' => 'text',
			    'desc' => __( 'Eg: 8h -> 17h', 'apus-simple-event' ),
			),
			array(
			    'name' => __( 'Address', 'apus-simple-event' ),
			    'id' => $prefix.'address',
			    'type' => 'text',
			),
			array(
				'id'                => $prefix . 'map',
				'name'              => __( 'Location', 'apus-simple-event' ),
				'type'              => 'apus_map',
				'sanitization_cb'   => 'apus_map_sanitise',
                'split_values'      => true,
			),
		);
		
		return apply_filters( 'apussimpleevent_postype_event_metaboxes_fields_management' , $fields, $prefix );
	}
}

ApusSimpleEvent_PostType_Event::init();