<?php

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class Educrat_Course_Review {
	
	public static function init() {
		add_filter( 'comments_template', array( __CLASS__, 'comments_template_loader') );

		add_action( 'comment_post', array( __CLASS__, 'save_rating_comment'), 10, 3 );
		add_action( 'comment_unapproved_to_approved', array( __CLASS__,'save_ratings_average'), 10 );
		add_action( 'comment_approved_to_unapproved', array( __CLASS__,'save_ratings_average'), 10 );
	}

	public static function comments_template_loader($template) {
	    if ( get_post_type() === LP_COURSE_CPT ) {
	    	return get_template_directory() . '/learnpress/single-course/reviews.php';
	    }
	    return $template;
	}
	
	// comment list
	public static function course_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
	    set_query_var( 'comment', $comment );
	    set_query_var( 'args', $args );
	    set_query_var( 'depth', $depth );
	    get_template_part( 'learnpress/single-course/review', '', array('comment' => $comment, 'args' => $args, 'depth' => $depth) );
	}

	// add comment meta
	public static function save_rating_comment( $comment_id, $comment_approved, $commentdata ) {
	    $post_type = get_post_type($commentdata['comment_post_ID']);
	    if ( $post_type == LP_COURSE_CPT && isset($_POST['rating']) ) {
	        add_comment_meta( $comment_id, '_rating', $_POST['rating'] );
	        if ( !empty($commentdata['comment_approved'] ) ) {
	        	self::update_ratings_average($commentdata['comment_post_ID']);
	        }
	    }
	}

	public static function save_ratings_average($comment) {
		$post_id = $comment->comment_post_ID;
	    self::update_ratings_average($post_id);
	}

	public static function update_ratings_average($post_id) {
	    $post_type = get_post_type($post_id);
	    if ( $post_type == LP_COURSE_CPT ) {
	        $average_rating = self::get_total_rating( $post_id );
	        update_post_meta( $post_id, '_average_rating', $average_rating );

	        // author
	        $author_id = get_post_field ('post_author', $post_id);
	        $args = array(
	        	'fields' => 'ids',
	        	'author' => $author_id,
	        );
	        $courses = educrat_get_courses($args);
	        $author_average_rating = $nb_reviews = 0;
	        if ( !empty($courses) ) {
	        	foreach ($courses as $course_id) {
	        		$nb_reviews += Educrat_Course_Review::get_total_reviews( $course_id );
	        		$average_rating = get_post_meta( $post_id, '_average_rating', true );
	        		if ( !empty($average_rating) && $average_rating > 0 ) {
	        			$author_average_rating += $average_rating;
	        		}
	        	}
	        }
	        update_user_meta($author_id, '_average_rating', $author_average_rating);
	        update_user_meta($author_id, '_nb_reviews', $nb_reviews);
	    }
	}

	public static function get_ratings_average($post_id) {
	    return get_post_meta( $post_id, '_average_rating', true );
	}

	public static function get_review_comments( $args = array() ) {
	    $args = wp_parse_args( $args, array(
	        'status' => 'approve',
	        'post_id' => '',
	        'user_id' => '',
	        'post_type' => LP_COURSE_CPT,
	        'number' => 0
	    ));
	    extract($args);

	    $cargs = array(
	        'status' => 'approve',
	        'post_type' => $post_type,
	        'number' => $number,
	        'meta_query' => array(
	            array(
	               'key' => '_rating',
	               'value' => 0,
	               'compare' => '>',
	            )
	        )
	    );
	    if ( !empty($post_id) ) {
	        $cargs['post_id'] = $post_id;
	    }
	    if ( !empty($user_id) ) {
	        $cargs['user_id'] = $user_id;
	    }

	    $comments = get_comments( $cargs );
	    
	    return $comments;
	}

	public static function get_total_reviews( $post_id ) {
	    $args = array( 'post_id' => $post_id );
	    $comments = self::get_review_comments($args);

	    if (empty($comments)) {
	        return 0;
	    }
	    
	    return count($comments);
	}

	public static function get_total_rating( $post_id ) {
	    $args = array( 'post_id' => $post_id );
	    $comments = self::get_review_comments($args);
	    if (empty($comments)) {
	        return 0;
	    }
	    $total_review = 0;
	    foreach ($comments as $comment) {
	        $rating = intval( get_comment_meta( $comment->comment_ID, '_rating', true ) );
	        if ($rating) {
	            $total_review += (int)$rating;
	        }
	    }
	    return round($total_review/count($comments),2);
	}

	public static function get_total_rating_by_user( $user_id ) {
	    $args = array( 'user_id' => $user_id );
	    $comments = self::get_review_comments($args);

	    if (empty($comments)) {
	        return 0;
	    }
	    $total_review = 0;
	    foreach ($comments as $comment) {
	        $rating = intval( get_comment_meta( $comment->comment_ID, '_rating', true ) );
	        if ($rating) {
	            $total_review += (int)$rating;
	        }
	    }
	    return $total_review/count($comments);
	}

	public static function print_review( $rate, $type = '', $nb = 0 ) {
		$rate = $rate ? $rate : 0;
	    ?>
	    <div class="review-stars-rated-wrapper">
	        <div class="review-stars-rated">
	            <ul class="review-stars">
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	            </ul>
	            
	            <ul class="review-stars filled"  style="<?php echo esc_attr( 'width: ' . ( $rate * 20 ) . '%' ) ?>" >
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	            </ul>
	        </div>
	        <?php if ($type == 'detail') { ?>
	            <span class="nb-review"><?php echo sprintf(_n('(%d)', '(%d)', $nb, 'educrat'), $nb); ?></span>
	        <?php } elseif ($type == 'list') { ?>
	            <span class="nb-review"><?php echo sprintf('(%d)', $nb); ?></span>
	        <?php } ?>
	    </div>
	    <?php
	}
	
	public static function print_review_star( $rate, $type = '', $nb = 0 ) {
		$rate = $rate ? $rate : 0;
	    ?>
	    <div class="review-stars-rated-wrapper">
	        <i class="fa fa-star text-warning"></i>
            <span class="nb-rate"><?php echo number_format($rate, 1); ?></span>
            <?php if ( $type == 'detail' ) { ?>
            	<span class="nb-review">(<?php echo number_format($nb); ?>)</span>
            <?php } ?>
	    </div>
	    <?php
	}

	public static function get_detail_ratings( $post_id ) {
	    global $wpdb;
	    $comment_ratings = $wpdb->get_results( $wpdb->prepare(
	        "
	            SELECT cm2.meta_value AS rating, COUNT(*) AS quantity FROM $wpdb->posts AS p
	            INNER JOIN $wpdb->comments AS c ON (p.ID = c.comment_post_ID AND c.comment_approved=1)
	            INNER JOIN $wpdb->commentmeta AS cm2 ON cm2.comment_id = c.comment_ID AND cm2.meta_key=%s
	            WHERE p.ID=%d
	            GROUP BY cm2.meta_value",
	            '_rating',
	            $post_id
	        ), OBJECT_K
	    );
	    return $comment_ratings;
	}
}

Educrat_Course_Review::init();