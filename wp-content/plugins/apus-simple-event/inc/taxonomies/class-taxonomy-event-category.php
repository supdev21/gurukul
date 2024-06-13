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
class ApusSimpleEvent_Taxonomy_Event_Category{

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
			'name'              => __( 'Event Categories', 'apus-simple-event' ),
			'singular_name'     => __( 'Event Category', 'apus-simple-event' ),
			'search_items'      => __( 'Search Event Categories', 'apus-simple-event' ),
			'all_items'         => __( 'All Event Categories', 'apus-simple-event' ),
			'parent_item'       => __( 'Parent Event Category', 'apus-simple-event' ),
			'parent_item_colon' => __( 'Parent Event Category:', 'apus-simple-event' ),
			'edit_item'         => __( 'Edit Event Category', 'apus-simple-event' ),
			'update_item'       => __( 'Update Event Category', 'apus-simple-event' ),
			'add_new_item'      => __( 'Add New Event Category', 'apus-simple-event' ),
			'new_item_name'     => __( 'New Event Category', 'apus-simple-event' ),
			'menu_name'         => __( 'Event Categories', 'apus-simple-event' ),
		);

		register_taxonomy( 'simple_event_category', 'simple_event', array(
			'labels'            => apply_filters( 'apussimpleevent_taxomony_event_category_labels', $labels ),
			'hierarchical'      => true,
			'query_var'         => 'apus-event-category',
			'rewrite'           => array( 'slug' => __( 'apus-event-category', 'apus-simple-event' ) ),
			'public'            => true,
			'show_ui'           => true,
		) );
	}
}

ApusSimpleEvent_Taxonomy_Event_Category::init();