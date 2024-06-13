<?php

global $post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( comments_open() || get_comments_number() ) :
	comments_template();
endif;