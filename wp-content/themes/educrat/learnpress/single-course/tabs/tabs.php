<?php
/**
 * Template for displaying tab nav of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/tabs/tabs.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.1
 */

defined( 'ABSPATH' ) || exit();

$tabs = learn_press_get_course_tabs();

if ( empty( $tabs ) ) {
	return;
}

$active_tab = learn_press_cookie_get( 'course-tab' );

if ( ! $active_tab ) {
	$tab_keys   = array_keys( $tabs );
	$active_tab = reset( $tab_keys );
}

// Show status course
$lp_user = learn_press_get_current_user();

if ( $lp_user && ! $lp_user instanceof LP_User_Guest ) {
	$can_view_course = $lp_user->can_view_content_course( get_the_ID() );

	if ( ! $can_view_course->flag ) {
		if ( LP_BLOCK_COURSE_FINISHED === $can_view_course->key ) {
			learn_press_display_message(
				esc_html__( 'You finished this course. This course has been blocked', 'educrat' ),
				'warning'
			);
		} elseif ( LP_BLOCK_COURSE_DURATION_EXPIRE === $can_view_course->key ) {
			learn_press_display_message(
				esc_html__( 'This course has been blocked reason by expire', 'educrat' ),
				'warning'
			);
		}
	}
}

$layout_type = educrat_course_layout_type();

if ( $layout_type == 'v1' || $layout_type == 'v2' || $layout_type == 'v3' ) {
	?>
	<ul id="course-tabs-spy" class="course-single-tab nav nav-pills sticky-top">
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<?php
			$classes = array( 'nav-item course-nav course-nav-tab-' . esc_attr( $key ) );

			$a_classes = ['nav-link'];
			if ( $active_tab === $key ) {
				$a_classes[] = 'active';
			}
			?>

			<li class="<?php echo implode( ' ', $classes ); ?>">
				<a class="<?php echo implode( ' ', $a_classes ); ?>" href="#tab-<?php echo esc_attr($key); ?>-id"><?php echo trim($tab['title']); ?></a>
			</li>
		<?php endforeach; ?>

	</ul>

	<div class="course-tabs-scrollspy">
		<?php
		foreach ( $tabs as $key => $tab ) : ?>
			<div class="course-panel" id="tab-<?php echo esc_attr($key); ?>-id">
				<div class="content">
					<?php
					if ( is_callable( $tab['callback'] ) ) {
						call_user_func( $tab['callback'], $key, $tab );
					} else {
						do_action( 'learn-press/course-tab-content', $key, $tab );
					}
					?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php } else if($layout_type == 'v6'){ ?>
	<div id="learn-press-course-tabs" class="course-tabs">
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<input type="radio" name="learn-press-course-tab-radio" id="tab-<?php echo esc_attr($key); ?>-input"
				<?php checked( $active_tab === $key ); ?> value="<?php echo esc_attr($key); ?>"/>
		<?php endforeach; ?>

		<ul class="course-single-tab nav nav-pills sticky-top" data-tabs="<?php echo count( $tabs ); ?>">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<?php
				$classes = array( 'course-nav-tab-' . esc_attr( $key ) );

				if ( $active_tab === $key ) {
					$classes[] = 'active';
				}
				?>

				<li class="<?php echo implode( ' ', $classes ); ?>">
					<label for="tab-<?php echo esc_attr($key); ?>-input"><?php echo trim($tab['title']); ?></label>
				</li>
			<?php endforeach; ?>

		</ul>

		<div class="course-tab-panels">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="course-tab-panel-<?php echo esc_attr( $key ); ?> course-tab-panel p-0" id="<?php echo esc_attr( $tab['id'] ); ?>">
					<?php
					if ( is_callable( $tab['callback'] ) ) {
						call_user_func( $tab['callback'], $key, $tab );
					} else {
						do_action( 'learn-press/course-tab-content', $key, $tab );
					}
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php } else { ?>
	<div id="learn-press-course-tabs" class="course-tabs">
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<input type="radio" name="learn-press-course-tab-radio" id="tab-<?php echo esc_attr($key); ?>-input"
				<?php checked( $active_tab === $key ); ?> value="<?php echo esc_attr($key); ?>"/>
		<?php endforeach; ?>

		<ul class="course-single-tab nav nav-pills sticky-top" data-tabs="<?php echo count( $tabs ); ?>">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<?php
				$classes = array( 'course-nav-tab-' . esc_attr( $key ) );

				if ( $active_tab === $key ) {
					$classes[] = 'active';
				}
				?>

				<li class="<?php echo implode( ' ', $classes ); ?>">
					<label for="tab-<?php echo esc_attr($key); ?>-input"><?php echo trim($tab['title']); ?></label>
				</li>
			<?php endforeach; ?>

		</ul>

		<div class="course-tab-panels">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="course-tab-panel-<?php echo esc_attr( $key ); ?> course-tab-panel p-0" id="<?php echo esc_attr( $tab['id'] ); ?>">
					<?php
					if ( is_callable( $tab['callback'] ) ) {
						call_user_func( $tab['callback'], $key, $tab );
					} else {
						do_action( 'learn-press/course-tab-content', $key, $tab );
					}
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php }