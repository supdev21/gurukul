<?php
/**
 * Template for displaying loop course of section.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/loop-section.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.2
 */

defined( 'ABSPATH' ) || exit();

/**
 * @var LP_Course_Section $section
 */
if ( ! isset( $section ) || ! isset( $can_view_content_course )
	 || ! isset( $user_course ) || ! isset( $user ) ) {
	return;
}

$course = learn_press_get_the_course();

if ( ! apply_filters( 'learn-press/section-visible', true, $section, $course ) ) {
	return;
}

/**
 * List items of section
 *
 * @var LP_Course_Item[]
 */
$items = $section->get_items();
?>

<li class="section closed" <?php //$section->main_class(); ?>
	id="section-<?php echo esc_attr( $section->get_slug() ); ?>"
	data-id="<?php echo esc_attr( $section->get_slug() ); ?>"
	data-section-id="<?php echo esc_attr( $section->get_id() ); ?>">
	<?php do_action( 'learn-press/before-section-summary', $section, $course->get_id() ); ?>

	<div class="section-header">
		<div class="section-left">
			<h5 class="section-title">
				<?php
				$title = $section->get_title();
				echo ! $title ? _x( 'Untitled', 'template title empty', 'educrat' ) : $title;
				?>

				<?php $description = $section->get_description(); ?>

				<?php if ( $description ) : ?>
					<p class="section-desc"><?php echo wpautop($description); ?></p>
				<?php endif; ?>
			</h5>

			<span class="section-toggle">
				<i class="fas fa-caret-down"></i>
				<i class="fas fa-caret-up"></i>
			</span>
		</div>

		<?php if ( $user->has_enrolled_or_finished( $section->get_course_id() ) ) : ?>
			<?php $percent = $user_course->get_percent_completed_items( '', $section->get_id() ); ?>

			<div class="section-meta">
				<div class="learn-press-progress" title="<?php echo esc_attr( sprintf( esc_html__( 'Section progress %s%%', 'educrat' ), round( $percent, 2 ) ) ); ?>">
					<div class="learn-press-progress__active" data-value="<?php echo esc_attr($percent); ?>"></div>
				</div>
			</div>

			<?php do_action( 'learnpress/single-course/section-header/after', $section ); ?>
		<?php endif; ?>
	</div>

	<?php do_action( 'learn-press/before-section-content', $section, $course->get_id() ); ?>

	<?php if ( ! $items ) : ?>
		<?php learn_press_display_message( esc_html__( 'No items in this section', 'educrat' ) ); ?>
	<?php else : ?>

		<ul class="section-content">
			<div class="inner">
				<?php
				foreach ( $items as $item ) :
					$can_view_item = $user->can_view_item( $item->get_id(), $can_view_content_course );
					?>
					<li class="<?php echo esc_attr( implode( ' ', $item->get_class() ) ); ?>" data-id="<?php echo esc_attr( $item->get_id() ); ?>">

						<?php
						do_action( 'learn-press/before-section-loop-item', $item, $section, $course );

						$item_link = $can_view_item->flag ? $item->get_permalink() : false;
						$item_link = apply_filters( 'learn-press/section-item-permalink', $item_link, $item, $section, $course );
						?>

						<a class="section-item-link" href="<?php echo trim($item_link ? esc_url_raw( $item_link ) : 'javascript:void(0);'); ?>">

							<?php
							do_action( 'learn-press/before-section-loop-item-title', $item, $section, $course );

							learn_press_get_template(
								'single-course/section/' . $item->get_template(),
								array(
									'item'    => $item,
									'section' => $section,
								)
							);

							do_action( 'learn-press/after-section-loop-item-title', $item, $section, $course );
							?>
						</a>

						<?php do_action( 'learn-press/after-section-loop-item', $item, $section, $course ); ?>
					</li>
				<?php endforeach; ?>
			</div>
		</ul>
	<?php endif; ?>

	<?php do_action( 'learn-press/after-section-summary', $section, $course->get_id() ); ?>
</li>
