<?php
/**
 * event get data
 *
 * @package    apus-simple-event
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ApusSimpleEvent_Event {
	/**
	 *
	 */
	protected $post_id;
	protected $metabox_info;

	/**
	 * Constructor 
	 */
	public function __construct( $post_id ){
		$this->post_id = $post_id;
	}

	/**
	 * Gets Amenities
	 *
	 * @access public
	 * @param string $all
	 * @return array
	 */
	public function getMetaFullInfo(){

		if( empty($this->metabox_info) ){
			$fields = ApusSimpleEvent_PostType_Event::metaboxes_info_fields();
			foreach( $fields as $field ){
				$id = str_replace( APUSSIMPLEEVENT_EVENT_PREFIX, "", $field['id']);
				$name = isset($field['name']) ? $field['name'] : '';
				$this->metabox_info[$id] = array( 'label' => $name, 'value' => get_post_meta($this->post_id, $field['id'], true)  ); 
			}
		}
		return $this->metabox_info;	
	}

	public function theAuthor(){
		echo get_the_author();
	}

	public function renderAuthorLink() {
		echo '<a href="'.get_the_author_link().'" class="author-link">' . get_the_author() . '</a>';
	}
	/**
	 * Gets categories
	 *
	 * @access public
	 * @return array
	 */
	public function getCategoryTax(){
		$terms = wp_get_post_terms( $this->post_id, 'simple_event_category' );
		return $terms; 
	}
	
	/**
	 * Gets meta box value
	 *
	 * @access public
	 * @param $key
	 * @param $single
	 * @return string
	 */
	public function getMetaboxValue( $key, $single = true ) {
		return get_post_meta( $this->post_id, APUSSIMPLEEVENT_EVENT_PREFIX.$key, $single ); 
	}
	
}