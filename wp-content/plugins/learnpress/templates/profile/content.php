<?php
/**
 * Template for displaying user profile content.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/profile/content.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.1
 */

defined( 'ABSPATH' ) || exit();

/**
 * @var LP_Profile_Tab $profile_tab
 */
if ( ! isset( $user ) || ! isset( $tab_key ) || ! isset( $profile ) || ! isset( $profile_tab ) ) {
	return;
}
?>

<?php 

$currentUser = wp_get_current_user();
$checkUser = $currentUser->roles[0];
$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$bb = basename( $actual_link);

?>

<article id="profile-content" class="lp-profile-content check_<?php echo $checkUser; ?>_<?php echo $bb; ?>">
	<?php learn_press_print_messages( true ); ?>
	<div id="profile-content-<?php echo esc_attr( $tab_key ); ?>">
		<?php do_action( 'learn-press/before-profile-content', $tab_key, $profile_tab, $user ); ?>

		<?php
		if ( empty( $profile_tab->get( 'sections' ) ) ) {
			if ( $profile_tab->get( 'callback' ) && is_callable( $profile_tab->get( 'callback' ) ) ) {
				echo call_user_func_array( $profile_tab->get( 'callback' ), array( $tab_key, $profile_tab, $user ) );
			} else {
				do_action( 'learn-press/profile-content', $tab_key, $profile_tab, $user );
			}
		} else {
			foreach ( $profile_tab->get( 'sections' ) as $key => $section ) {
				if ( $profile->get_current_section( '', false, false ) === $section['slug'] ) {
					if ( isset( $section['callback'] ) && is_callable( $section['callback'] ) ) {
						echo call_user_func_array( $section['callback'], array( $key, $section, $user ) );
					} else {
						do_action( 'learn-press/profile-section-content', $key, $section, $user );
					}
				}
			}
		}
		?>

		<?php do_action( 'learn-press/after-profile-content' ); ?>
	</div>
</article>
