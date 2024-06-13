<?php
/**
 * Template for displaying extra info as toggle
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $checked ) ) {
	$checked = false;
}
?>

<div class="apus-course-extra-box">
	<h3 class="title">
		<?php echo esc_html( $title ); ?>
	</h3>

	<div class="content">
		<ul>
			<?php foreach ( $items as $item ) : ?>
				<li><?php echo trim($item); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>