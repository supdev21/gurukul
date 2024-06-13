<?php
/**
 * event category
 *
 * @package    apus-simple-event
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class ApusSimpleEvent_Taxonomy_Event_Tags{

	/**
	 *
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'definition' ) );
	}

	/**
	 *
	 */
	public static function definition() {
		
		$labels = array(
			'name'              => __( 'Event Tags', 'apus-simple-event' ),
			'singular_name'     => __( 'Event Tag', 'apus-simple-event' ),
			'search_items'      => __( 'Search Event Tags', 'apus-simple-event' ),
			'all_items'         => __( 'All Event Tags', 'apus-simple-event' ),
			'parent_item'       => __( 'Parent Event Tag', 'apus-simple-event' ),
			'parent_item_colon' => __( 'Parent Event Tag:', 'apus-simple-event' ),
			'edit_item'         => __( 'Edit Event Tag', 'apus-simple-event' ),
			'update_item'       => __( 'Update Event Tag', 'apus-simple-event' ),
			'add_new_item'      => __( 'Add New Event Tag', 'apus-simple-event' ),
			'new_item_name'     => __( 'New Event Tag', 'apus-simple-event' ),
			'menu_name'         => __( 'Event Tags', 'apus-simple-event' ),
		);

		register_taxonomy( 'simple_event_tags', 'simple_event', array(
			'labels'            => apply_filters( 'apussimpleevent_taxomony_event_tags_labels', $labels ),
			'hierarchical'      => false,
			'query_var'         => 'apus-event-tags',
			'rewrite'           => array( 'slug' => __( 'simple-event-tag', 'apus-simple-event' ) ),
			'public'            => true,
			'show_ui'           => true,
		) );
	}
}

ApusSimpleEvent_Taxonomy_Event_Tags::init();