<?php
/**
 * functions
 *
 * @package    apus-simple-event
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * batch including all files in a path.
 *
 * @param String $path : PATH_DIR/*.php or PATH_DIR with $ifiles not empty
 */
function apussimpleevent_includes( $path, $ifiles=array() ){
    if( !empty($ifiles) ){
         foreach( $ifiles as $key => $file ){
            $file  = $path.'/'.$file; 
            if(is_file($file)){
                require($file);
            }
         }   
    }else {
        $files = glob($path);
        foreach ($files as $key => $file) {
            if(is_file($file)){
                require($file);
            }
        }
    }
}

/**
 *
 */
function apussimpleevent_event( $id ){
    global $event; 

    $event = new ApusSimpleEvent_Event( $id );

    return $event; 
}

function apussimpleevent_map_init_scripts() {
    $key = apply_filters( 'apussimpleevent_map_api_key', 'AIzaSyAgLtmIukM56mTfet5MEoPsng51Ws06Syc' );
    wp_enqueue_script('gmap3-api-js', '//maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key='.$key);
    wp_enqueue_script('gmap3-js', APUSSIMPLEEVENT_PLUGIN_URL.'assets/gmap3.js', array( 'jquery'  ), '20131022', true);
}
add_action('wp_enqueue_scripts', 'apussimpleevent_map_init_scripts');
