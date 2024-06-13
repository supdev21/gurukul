<?php
	// Utility data
	$is_enrolled           = apply_filters( 'tutor_alter_enroll_status', tutor_utils()->is_enrolled() );
	$lesson_url            = tutor_utils()->get_course_first_lesson();
	$is_administrator      = tutor_utils()->has_user_role( 'administrator' );
	$is_instructor         = tutor_utils()->is_instructor_of_this_course();
	$course_content_access = (bool) get_tutor_option( 'course_content_access_for_ia' );
	$is_privileged_user    = $course_content_access && ( $is_administrator || $is_instructor );
	$tutor_course_sell_by  = apply_filters( 'tutor_course_sell_by', null );
	$is_public             = get_post_meta( get_the_ID(), '_tutor_is_public_course', true ) == 'yes';

	// Monetization info
	$monetize_by              = tutor_utils()->get_option( 'monetize_by' );
	$is_purchasable           = tutor_utils()->is_course_purchasable();

	// Get login url if
	$is_tutor_login_disabled = ! tutor_utils()->get_option( 'enable_tutor_native_login', null, true, true );
	$auth_url                = $is_tutor_login_disabled ? ( isset( $_SERVER['REQUEST_SCHEME'] ) ? wp_login_url( $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) : '' ) : '';
	$default_meta = array(
		array(
			'icon_class' => 'flaticon-user',
			'label'      => esc_html__( 'Total Enrolled', 'educrat' ),
			'value'      => tutor_utils()->get_option( 'enable_course_total_enrolled' ) ? tutor_utils()->count_enrolled_users_by_course() : null,
		),
		array(
			'icon_class' => 'flaticon-clock',
			'label'      => esc_html__( 'Duration', 'educrat' ),
			'value'      => get_tutor_option( 'enable_course_duration' ) ? ( get_tutor_course_duration_context() ? get_tutor_course_duration_context() : false ) : null,
		),
		array(
			'icon_class' => 'tutor-icon-refresh-o',
			'label'      => esc_html__( 'Last Updated', 'educrat' ),
			'value'      => get_tutor_option( 'enable_course_update_date' ) ? get_the_modified_date( get_option( 'date_format' ) ) : null,
		),
	);

	// Add level if enabled
	if(tutor_utils()->get_option('enable_course_level', true, true)) {
		array_unshift($default_meta, array(
			'icon_class' => 'flaticon-bar-chart',
			'label'      => esc_html__( 'Level', 'educrat' ),
			'value'      => get_tutor_course_level( get_the_ID() ),
		));
	}

	// Right sidebar meta data
	$sidebar_meta = apply_filters('tutor/course/single/sidebar/metadata', $default_meta, get_the_ID() );
	$login_url = tutor_utils()->get_option( 'enable_tutor_native_login', null, true, true ) ? '' : wp_login_url( tutor()->current_url );
?>

<div class="course-info-widget">
	<?php
    $layout_type = educrat_tutor_course_layout_type();

    if ( $layout_type == 'v1' || $layout_type == 'v2' || $layout_type == 'v3' || $layout_type == 'v4' ) {
        tutor_utils()->has_video_in_single() ? tutor_course_video() : get_tutor_course_thumbnail('large');
    }
    ?>
	<div class="bottom-inner">
		<?php
		if ( $is_enrolled || $is_privileged_user) {
			ob_start();

			// Course Info
			$completed_percent   = tutor_utils()->get_course_completed_percent();
			$is_completed_course = tutor_utils()->is_completed_course();
			$retake_course       = tutor_utils()->can_user_retake_course();
			$course_id           = get_the_ID();
			$course_progress     = tutor_utils()->get_course_completed_percent( $course_id, 0, true );
			?>
			<!-- course progress -->
			<?php if ( tutor_utils()->get_option('enable_course_progress_bar', true, true) && is_array( $course_progress ) && count( $course_progress ) ) : ?>
				<div class="tutor-course-progress-wrapper tutor-mb-16">
					<h6 class="tutor-mb-8 tutor-mt-0">
						<?php esc_html_e( 'Course Progress', 'educrat' ); ?>
					</h6>
					<div class="list-item-progress">
						<div class="tutor-mb-8">
							<span class="progress-steps">
								<?php echo esc_html( $course_progress['completed_count'] ); ?>/
								<?php echo esc_html( $course_progress['total_count'] ); ?>
							</span>
							<span class="progress-percentage">
								<?php echo esc_html( $course_progress['completed_percent'] . '%' ); ?>
								<?php esc_html_e( 'Complete', 'educrat' ); ?>
							</span>
						</div>
						<div class="tutor-progress-bar" style="--tutor-progress-value:<?php echo esc_attr( $course_progress['completed_percent'] ); ?>%;">
							<span class="tutor-progress-value" area-hidden="true"></span>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php
			$start_content = '';

			// The user is enrolled anyway. No matter manual, free, purchased, woocommerce, edd, membership
			do_action( 'tutor_course/single/actions_btn_group/before' );

			// Show Start/Continue/Retake Button
			if ( $lesson_url ) {
				$button_class = 'tutor-btn ' .
								( $retake_course ? 'tutor-btn-outline-primary' : 'tutor-btn-primary' ) .
								' tutor-btn-block' .
								( $retake_course ? ' tutor-course-retake-button' : '' );

				// Button identifier class
				$button_identifier = 'start-continue-retake-button';
				$tag               = $retake_course ? 'button' : 'a';
				ob_start();
				?>
					<<?php echo trim($tag); ?> <?php echo trim($retake_course ? 'disabled="disabled"' : ''); ?> href="<?php echo esc_url( $lesson_url ); ?>" class="<?php echo esc_attr( $button_class . ' ' . $button_identifier ); ?>" data-course_id="<?php echo esc_attr( get_the_ID() ); ?>">
					<?php
					if ( $retake_course ) {
						esc_html_e( 'Retake This Course', 'educrat' );
					} elseif ( $completed_percent <= 0 ) {
						esc_html_e( 'Start Learning', 'educrat' );
					} else {
						esc_html_e( 'Continue Learning', 'educrat' );
					}
					?>
					</<?php echo trim($tag); ?>>
					<?php
					$start_content = ob_get_clean();
			}
			echo apply_filters( 'tutor_course/single/start/button', $start_content, get_the_ID() );

			// Show Course Completion Button.
			if ( ! $is_completed_course ) {
				ob_start();
				?>
				<form method="post" class="tutor-mt-20">
					<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>

					<input type="hidden" value="<?php echo esc_attr( get_the_ID() ); ?>" name="course_id"/>
					<input type="hidden" value="tutor_complete_course" name="tutor_action"/>

					<button type="submit" class="tutor-btn tutor-btn-outline-primary tutor-btn-block" name="complete_course_btn" value="complete_course">
						<?php esc_html_e( 'Complete Course', 'educrat' ); ?>
					</button>
				</form>
				<?php
				echo apply_filters( 'tutor_course/single/complete_form', ob_get_clean() );
			}

			?>
				<?php
					// check if has enrolled date.
					$post_date = is_object( $is_enrolled ) && isset( $is_enrolled->post_date ) ? $is_enrolled->post_date : '';
					if ( '' !== $post_date ) :
					?>
					<div class="tutor-mt-8">
						<span class="tutor-fs-5 tutor-color-success tutor-icon-purchase-mark tutor-mr-8"></span>
						<span class="tutor-enrolled-info-text">
							<?php esc_html_e( 'You enrolled in this course on', 'educrat' ); ?>
							<span class="tutor-fs-7 tutor-fw-bold tutor-color-success tutor-ml-4 tutor-enrolled-info-date">
								<?php
									echo esc_html( tutor_i18n_get_formated_date( $post_date, get_option( 'date_format' ) ) );
								?>
							</span>
						</span>
					</div>
				<?php endif; ?>
			<?php
			do_action( 'tutor_course/single/actions_btn_group/after' );
			echo apply_filters( 'tutor/course/single/entry-box/is_enrolled', ob_get_clean(), get_the_ID() );
		} else if ( $is_public ) {
			// Get the first content url
			$first_lesson_url = tutor_utils()->get_course_first_lesson( get_the_ID(), tutor()->lesson_post_type );
			!$first_lesson_url ? $first_lesson_url = tutor_utils()->get_course_first_lesson( get_the_ID() ) : 0;
			ob_start();
			?>
				<a href="<?php echo esc_url( $first_lesson_url ); ?>" class="tutor-btn tutor-btn-primary tutor-btn-lg tutor-btn-block">
					<?php esc_html_e( 'Start Learning', 'educrat' ); ?>
				</a>
			<?php
			echo apply_filters( 'tutor/course/single/entry-box/is_public', ob_get_clean(), get_the_ID() );
		} else {
			// The course enroll options like purchase or free enrolment
			$price = apply_filters( 'get_tutor_course_price', null, get_the_ID() );

			if ( tutor_utils()->is_course_fully_booked( null ) ) {
				ob_start();
				?>
					<div class="tutor-alert tutor-warning tutor-mt-28">
						<div class="tutor-alert-text">
							<span class="tutor-icon-circle-info tutor-alert-icon tutor-mr-12" area-hidden="true"></span>
							<span>
								<?php esc_html_e( 'This course is full right now. We limit the number of students to create an optimized and productive group dynamic.', 'educrat' ); ?>
							</span>
						</div>
					</div>
				<?php
				echo apply_filters( 'tutor/course/single/entry-box/fully_booked', ob_get_clean(), get_the_ID() );
			} elseif ( $is_purchasable && $price && $tutor_course_sell_by ) {
				// Load template based on monetization option
				ob_start();
				tutor_load_template( 'single.course.add-to-cart-' . $tutor_course_sell_by );
				echo apply_filters( 'tutor/course/single/entry-box/purchasable', ob_get_clean(), get_the_ID() );


			} else {
				ob_start();
				?>
					<div class="tutor-course-single-pricing">
						<div class="course-price">
							<?php esc_html_e( 'Free', 'educrat' ); ?>
						</div>
					</div>

					<div class="tutor-course-single-btn-group <?php echo is_user_logged_in() ? '' : 'tutor-course-entry-box-login'; ?>" data-login_url="<?php echo trim($login_url); ?>">
						<form class="tutor-enrol-course-form" method="post">
							<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
							<input type="hidden" name="tutor_course_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
							<input type="hidden" name="tutor_course_action" value="_tutor_course_enroll_now">
							<button type="submit" class="tutor-btn tutor-btn-primary tutor-btn-lg tutor-btn-block tutor-mt-24 tutor-enroll-course-button tutor-static-loader">
								<?php esc_html_e( 'Enroll now', 'educrat' ); ?>
							</button>
						</form>
					</div>
					
				<?php
				echo apply_filters( 'tutor/course/single/entry-box/free', ob_get_clean(), get_the_ID() );
			}
		}

		?>

        <div class="tutor-course-bookmark">
		    <?php
		        $course_id = get_the_ID();
		        $is_wish_listed = tutor_utils()->is_wishlisted( $course_id );
		        
		        $action_class = '';
		        if ( is_user_logged_in() ) {
		            $action_class = apply_filters('tutor_wishlist_btn_class', 'tutor-course-wishlist-btn');
		        } else {
		            $action_class = apply_filters('tutor_popup_login_class', 'tutor-open-login-modal');
		        }
		        
				echo '<a href="javascript:;" class="'. esc_attr( $action_class ) .' save-bookmark-btn btn btn-theme btn-outline w-100" data-course-id="'. esc_attr( $course_id ) .'">
		            <i class="' . ( $is_wish_listed ? 'tutor-icon-bookmark-bold' : 'tutor-icon-bookmark-line') . '"></i>
		            '.esc_html__('Wishlist', 'educrat').'
		        </a>';
			?>
		</div>

        <?php
		do_action('tutor_course/single/entry/after', get_the_ID());
		?>
		<!-- Course Info -->
		<ul class="tutor-course-info-fields">
			<?php foreach ( $sidebar_meta as $key => $meta ) : ?>
				<?php
				if ( ! $meta['value'] ) {
					continue;
				}
				?>
				<li>
					<label>
	                    <i class="<?php echo esc_attr( $meta['icon_class'] ); ?>"></i>
	                    <?php echo esc_html($meta['label']); ?>
	                </label>
					<span class="tutor-label">
						<?php echo wp_kses_post( $meta['value'] ); ?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php get_template_part('template-parts/sharebox-course'); ?>
	</div>
</div>

<?php
if ( ! is_user_logged_in() ) {
	tutor_load_template_from_custom_path( tutor()->path . '/views/modal/login.php' );
}
?>
