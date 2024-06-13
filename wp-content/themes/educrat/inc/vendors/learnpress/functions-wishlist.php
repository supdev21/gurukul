<?php
/**
 * wishlist
 *
 * @package    educrat
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class Educrat_Wishlist {
	
	public static function init() {
		add_action( 'wp_ajax_educrat_add_wishlist', array(__CLASS__, 'add_wishlist') );
		add_action( 'wp_ajax_nopriv_educrat_add_wishlist', array(__CLASS__, 'add_wishlist') );
		add_action( 'wp_ajax_educrat_remove_wishlist', array(__CLASS__, 'remove_wishlist') );
		add_action( 'wp_ajax_nopriv_educrat_remove_wishlist', array(__CLASS__, 'remove_wishlist') );
	}

	public static function add_wishlist() {
		check_ajax_referer( 'educrat-ajax-nonce', 'security' );
		$result = array();
		if ( isset($_POST['post_id']) && $_POST['post_id'] ) {
			self::save_wishlist($_POST['post_id']);
			$result['status'] = 'success';
			$result['text'] = esc_html__( 'Added to wishlist', 'educrat' );
		} else {
			$result['status'] = 'error';
		}
		echo json_encode($result);
		die();
	}

	public static function remove_wishlist() {
		check_ajax_referer( 'educrat-ajax-nonce', 'security' );
		$result = array();
		if ( isset($_POST['post_id']) && $_POST['post_id'] ) {
			
			$course_id = $_POST['post_id'];
		    $newfavorite = array();
	        if ( isset($_COOKIE['course_wishlist']) ) {
	            $favorite = explode( ',', $_COOKIE['course_wishlist'] );
	            foreach ($favorite as $key => $value) {
	                if ( $course_id != $value ) {
	                    unset($favorite[$key]);
	                    $newfavorite[] = $value;
	                }
	            }
	        }
	        setcookie( 'course_wishlist', implode(',', $newfavorite) , time()+3600*24*10, '/' );
	        $_COOKIE['course_wishlist'] = implode(',', $newfavorite);



			$result['status'] = 'success';
			$result['text'] = esc_html__( 'Add to wishlist', 'educrat' );
		} else {
			$result['status'] = 'error';
		}
		echo json_encode($result);
		die();
	}

	public static function get_wishlist() {
		if ( isset($_COOKIE['course_wishlist']) && !empty($_COOKIE['course_wishlist']) ) {
            return explode( ',', $_COOKIE['course_wishlist'] );
        }
        return array();
	}

	public static function save_wishlist($course_id) {
		$wishlist = array();
        if ( isset($_COOKIE['course_wishlist']) ) {
            $wishlist = explode( ',', $_COOKIE['course_wishlist'] );
            if ( !self::check_added_wishlist($course_id, $wishlist) ) {
                $wishlist[] = $course_id;
            }
        } else {
            $wishlist = array( $course_id );
        }
		setcookie( 'course_wishlist', implode(',', $wishlist), time()+3600*24*10, '/' );
        $_COOKIE['course_wishlist'] = implode(',', $wishlist);
	}

	public static function check_added_wishlist($course_id) {
		if ( empty($course_id) ) {
			return false;
		}

		if ( isset($_COOKIE['course_wishlist']) && !empty($_COOKIE['course_wishlist']) ) {
            $favorites = explode( ',', $_COOKIE['course_wishlist'] );
            if ( in_array($course_id, $favorites) ) {
	            return true;
	        }
        }
    	return false;
	}

	public static function get_courses( $ids, $post_per_page = -1, $paged = 1 ) {
		if ( empty($ids) || !is_array($ids) ) {
			return false;
		}
		$args = array(
			'post_type' => LP_COURSE_CPT,
			'posts_per_page' => $post_per_page,
			'ignore_sticky_posts' => true,
			'paged' => $paged,
			'post__in' => $ids
		);

		$wp_query = new WP_Query( $args );
		return $wp_query;
	}

	public static function display_wishlist_btn( $post , $icon = true) {
		$post_id = $post->ID;
		$class = '';
		$icon_class = 'flaticon-heart';
		$text = esc_html__( 'Add to wishlist', 'educrat' );
		
		$added = self::check_added_wishlist($post_id);
		if ($added) {
			$class = 'apus-wishlist-added';
			$icon_class = 'flaticon-heart';
			$text = esc_html__( 'Added to wishlist', 'educrat' );
		} else {
			$class = 'apus-wishlist-add';
		}
		?>
		<?php if( !empty($icon) && $icon === 'icon' ) { ?>
			<a href="#apus-wishlist-add" class="btn-wishlist-course only-icon <?php echo esc_attr($class); ?>" data-id="<?php echo esc_attr($post_id); ?>">
				<i class="<?php echo esc_attr($icon_class); ?>"></i>
			</a>
		<?php } else { ?>
			<a href="#apus-wishlist-add" class="btn-wishlist-course <?php echo esc_attr($class); ?>" data-id="<?php echo esc_attr($post_id); ?>">
				<i class="<?php echo esc_attr($icon_class); ?>"></i><span class="wishlist-text"><?php echo esc_html($text); ?></span>
			</a>
		<?php } ?>
		<?php
	}
}

Educrat_Wishlist::init();