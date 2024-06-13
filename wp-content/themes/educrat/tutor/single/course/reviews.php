<?php
/**
 * Template for displaying course reviews
 *
 * @since v.1.0.0
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.5
 */

use TUTOR\Input;

$disable = ! get_tutor_option( 'enable_course_review' );
if ( $disable ) {
	return;
}

$per_page		= tutor_utils()->get_option( 'pagination_per_page', 10 );
$current_page	= max( 1, Input::post( 'current_page', 0, Input::TYPE_INT ) );
$offset			= ( $current_page - 1 ) * $per_page;

$current_user_id	= get_current_user_id();
$course_id			= Input::post( 'course_id', get_the_ID(), Input::TYPE_INT );
$is_enrolled		= tutor_utils()->is_enrolled( $course_id, $current_user_id );

$reviews		= tutor_utils()->get_course_reviews( $course_id, $offset, $per_page, false, array( 'approved' ), $current_user_id );
$reviews_total	= tutor_utils()->get_course_reviews( $course_id, null, null, true, array( 'approved' ), $current_user_id );
$rating			= tutor_utils()->get_course_rating( $course_id );
$my_rating		= tutor_utils()->get_reviews_by_user( 0, 0, 150, false, $course_id, array( 'approved', 'hold' ) );

if ( Input::has( 'course_id' ) ) {
	// It's load more
	tutor_load_template( 'single.course.reviews-loop', array( 'reviews' => $reviews ) );
	return;
}

do_action( 'tutor_course/single/enrolled/before/reviews' );
?>

<div class="tutor-pagination-wrapper-replaceable">
	<?php if ( ! is_array( $reviews ) || ! count( $reviews ) ): ?>
		<?php tutor_utils()->tutor_empty_state( esc_html__( 'No Review Yet', 'educrat' ) ); ?>
	<?php else: ?>
		<div class="box-info-white">
			<h3 class="title"><?php echo esc_html__('Feedback','educrat') ?></h3>
			<div class="d-md-flex">

				<div class="detail-average-rating flex-column d-flex align-items-center justify-content-center">
					<div class="average-value">
						<?php echo number_format( $rating->rating_avg, 1 ); ?>
					</div>
					<div class="average-star">
						<?php tutor_utils()->star_rating_generator_v2( $rating->rating_avg, null, false, '', 'xs' ); ?>
					</div>
					<div class="total-rating">
						<?php esc_html_e( 'Total ', 'educrat' ); ?>
						<?php echo trim($reviews_total); ?>
						<?php echo esc_html( _n( ' Rating', ' Ratings', count( $reviews ), 'educrat' ) ); ?>
					</div>
				</div>

				<div class="detail-rating">
					<?php foreach ( $rating->count_by_value as $key => $value ) : ?>
						<?php $rating_count_percent = ( $value > 0 ) ? ( $value * 100 ) / $rating->rating_count : 0; ?>
						<div class="item-rating">
							<div class="list-rating">
								<div class="value-content">
									<div class="progress">
										<div class="progress-bar progress-bar-success" style="<?php echo esc_attr( 'width: '.$rating_count_percent.'%' ); ?>">
										</div>
									</div>
									<div class="value">
										<div class="d-flex align-items-center">
											<div class="review-stars-rated">
									            <ul class="review-stars">
									                <li><span class="fa fa-star"></span></li>
									                <li><span class="fa fa-star"></span></li>
									                <li><span class="fa fa-star"></span></li>
									                <li><span class="fa fa-star"></span></li>
									                <li><span class="fa fa-star"></span></li>
									            </ul>
									            
									            <ul class="review-stars filled" style="width: <?php echo trim($key*20) ?>%">
									                <li><span class="fa fa-star"></span></li>
									                <li><span class="fa fa-star"></span></li>
									                <li><span class="fa fa-star"></span></li>
									                <li><span class="fa fa-star"></span></li>
									                <li><span class="fa fa-star"></span></li>
									            </ul>
									        </div>
									        <div class="ms-auto">
												<?php echo trim($rating_count_percent); ?>%
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="box-info-white <?php echo trim( ($is_enrolled)? '':'m-0' ); ?>">
			<h3 class="title"><?php echo trim(( $reviews_total > 1 ? esc_html__( 'Reviews', 'educrat' ) : esc_html__( 'Review', 'educrat' ) )); ?> <small>(<?php echo trim($reviews_total); ?>)</small></h3>
			<div class="tutor-reviews tutor-card-list tutor-pagination-content-appendable">
				<?php tutor_load_template('single.course.reviews-loop', array('reviews' => $reviews)); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="tutor-row tutor-mb-16">
		<div class="tutor-col">
			<?php if($is_enrolled): ?>
				<button class="btn btn-theme btn-sm btn-outline write-course-review-link-btn">
					<i class="tutor-icon-star-line tutor-mr-8"></i>
					<?php
						$is_new = !$my_rating || empty($my_rating->rating) || empty($my_rating->comment_content);
						$is_new ? esc_html_e('Write a review', 'educrat') : esc_html_e('Edit review', 'educrat');
					?>
				</button>
			<?php endif; ?>
		</div>

		<div class="tutor-col-auto">
			<?php
				$pagination_data = array(
					'total_items' => $reviews_total,
					'per_page'    => $per_page,
					'paged'       => $current_page,
					'layout'	  => array(
						'type' => 'load_more',
						'load_more_text' => esc_html__('Load More', 'educrat')
					),
					'ajax'		  => array(
						'action' => 'tutor_single_course_reviews_load_more',
						'course_id' => $course_id,
					)
				);
				$pagination_template_frontend = tutor()->path . 'templates/dashboard/elements/pagination.php';
				tutor_load_template_from_custom_path( $pagination_template_frontend, $pagination_data );
			?>
		</div>
	</div>
</div>

<?php if($is_enrolled): ?>
	<div class="tutor-course-enrolled-review-wrap tutor-pt-16">
		<div class="tutor-write-review-form" style="display: none;">
			<form method="post" class="comment-form-theme">
				<div class="tutor-star-rating-container">
					<input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>"/>
					<input type="hidden" name="review_id" value="<?php echo esc_attr($my_rating ? $my_rating->comment_ID : ''); ?>"/>
					<input type="hidden" name="action" value="tutor_place_rating"/>
					<div class="tutor-mb-16">
						<div class="tutor-ratings tutor-ratings-lg tutor-ratings-selectable" tutor-ratings-selectable>
							<?php
								tutor_utils()->star_rating_generator(tutor_utils()->get_rating_value($my_rating ? $my_rating->rating : 0));
							?>
						</div>
					</div>
					<div class="form-group">
						<textarea name="review" class="form-control" placeholder="<?php esc_html_e('write a review', 'educrat'); ?>"><?php echo stripslashes($my_rating ? $my_rating->comment_content : ''); ?></textarea>
					</div>
					<div class="form-group">
						<button type="submit" class="tutor_submit_review_btn btn btn-theme">
							<?php esc_html_e('Submit Review', 'educrat'); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php endif; ?>

<?php do_action( 'tutor_course/single/enrolled/after/reviews' ); ?>