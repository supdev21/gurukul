
<?php 

$currentUser = wp_get_current_user();
$checkUser = $currentUser->roles[0];
$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$aa = basename( $actual_link);

?>
<aside id="profile-sidebar" class="check_<?php echo $checkUser ?>_<?php echo $aa; ?>">


	<?php do_action( 'learn-press/user-profile-tabs' ); ?>


</aside>
