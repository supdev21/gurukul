<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$course = learn_press_get_course();

$video_url = get_post_meta($post->ID, '_lp_video_url', true);
if ( !empty($video_url) ) {
?>
    <div class="course-video box-info-white">
        <?php
            if ( strpos($video_url, 'www.aparat.com') !== false ) {
                $path = parse_url($video_url, PHP_URL_PATH);
                $matches = preg_split("/\/v\//", $path);
                
                if ( !empty($matches[1]) ) {
                    $output = '<iframe src="http://www.aparat.com/video/video/embed/videohash/'. $matches[1] . '/vt/frame"
                                allowFullScreen="true"
                                webkitallowfullscreen="true"
                                mozallowfullscreen="true"
                                height="720"
                                width="1280" >
                                </iframe>';

                    echo trim($output);
                }
            } else {
                echo apply_filters( 'the_content', '[embed width="1280" height="720"]' . esc_attr( $video_url ) . '[/embed]' );
            }
        ?>
    </div>
<?php } ?>