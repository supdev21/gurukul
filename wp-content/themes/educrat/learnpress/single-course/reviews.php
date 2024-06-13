<?php

global $post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}
global $post;
$total_rating = Educrat_Course_Review::get_ratings_average( $post->ID );
$comment_ratings = Educrat_Course_Review::get_detail_ratings( $post->ID );
$total = Educrat_Course_Review::get_total_reviews( $post->ID );
?>
<div id="reviews">


	<div class="row">
		<div class="col-md-6 col-sm-12">
			
			<div class="box-info-white">
				<h3 class="title"><?php echo esc_html__('Feedback', 'educrat'); ?></h3>
				<div class="d-md-flex">
					<div class="detail-average-rating flex-column d-flex align-items-center justify-content-center">
						<div class="average-value"><?php echo number_format((float)$total_rating, 1, '.', ''); ?></div>
						<div class="average-star">
							<?php Educrat_Course_Review::print_review( $total_rating ); ?>
						</div>
						<div class="total-rating">
							<?php $total ? printf( _n( '%1$s rating', '%1$s ratings', $total, 'educrat' ), number_format_i18n( $total ) ) : esc_html_e( '0 rating', 'educrat' ); ?>
						</div>
					</div>

					<div class="detail-rating">
						<?php for ( $i = 5; $i >= 1; $i -- ) : ?>
							<div class="item-rating">
								<div class="list-rating">
									
									<div class="value-content">
										<div class="progress">
											<div class="progress-bar progress-bar-success" style="<?php echo esc_attr(( $total && !empty( $comment_ratings[$i]->quantity ) ) ? esc_attr( 'width: ' . ( $comment_ratings[$i]->quantity / $total * 100 ) . '%' ) : 'width: 0%'); ?>">
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
										            
										            <ul class="review-stars filled" style="width: <?php echo trim($i*20) ?>%">
										                <li><span class="fa fa-star"></span></li>
										                <li><span class="fa fa-star"></span></li>
										                <li><span class="fa fa-star"></span></li>
										                <li><span class="fa fa-star"></span></li>
										                <li><span class="fa fa-star"></span></li>
										            </ul>
										        </div>
										        <div class="ms-auto">
													<?php echo trim( ( $total && !empty( $comment_ratings[$i]->quantity ) ) ?  number_format(( $comment_ratings[$i]->quantity / $total * 100 ), 0) . '%' : '0%' ); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endfor; ?>
					</div>
				</div>
			</div>
			

		</div>



		<div class="col-md-6 col-sm-12 commentformsec">

			<?php $commenter = wp_get_current_commenter(); ?>
			<div id="review_form_wrapper" class="commentform box-info-white">
				<div class="reply_comment_form hidden">
					<?php
						$comment_form = array(
							'title_reply'          => esc_html__( 'Reply comment', 'educrat' ),
							'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'educrat' ),
							'comment_notes_before' => '',
							'comment_notes_after'  => '',
							'fields'               => array(
								'author' => '<div class="row"><div class="col-12 col-sm-6"><div class="form-group">'.
								            '<label>'.esc_attr__('Name', 'educrat').'</label>
								            <input id="author" class="form-control" placeholder="'.esc_attr__( 'Your Name', 'educrat' ).'" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></div></div>',
								'email'  => '<div class="col-12 col-sm-6"><div class="form-group">' .
								            '<label>'.esc_attr__('Email', 'educrat').'</label>
								            <input id="email" placeholder="'.esc_attr__( 'your@mail.com', 'educrat' ).'" class="form-control" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></div></div>',
								            'url' => '<div class="col-12 col-sm-6 d-none"><div class="form-group"><label>'.esc_html__( 'Website', 'educrat' ).'</label>
		                                            <input id="url" name="url" placeholder="'.esc_attr__( 'Your Website', 'educrat' ).'" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '"  />
		                                       	</div></div></div>',
							),
							'label_submit'  => esc_html__( 'Submit', 'educrat' ),
							'logged_in_as'  => '',
							'comment_field' => '',
							'title_reply_before' => '<h4 class="title comment-reply-title">',
							'title_reply_after'  => '</h4>',
							'class_form'  => 'comment-form-theme',
							'class_submit' => 'btn btn-theme'
						);

						$comment_form['comment_field'] .= '<div class="form-group"><label>'.esc_attr__('Reviews', 'educrat').'</label><textarea placeholder="'.esc_attr__( 'Write Reviews', 'educrat' ).'" id="comment" class="form-control" name="comment" cols="45" rows="5" aria-required="true" placeholder="'.esc_attr__( 'Write Reviews', 'educrat' ).'"></textarea></div>';
						
						$comment_form['must_log_in'] = '<p class="must-log-in">' .  esc_html__( 'You must be logged in to reply this review.', 'educrat' ) . '</p>';
						
						educrat_comment_form($comment_form);
					?>
				</div>
				<div id="review_form">
					<?php
						$comment_form = array(
							'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'educrat' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'educrat' ), get_the_title() ),
							'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'educrat' ),
							'comment_notes_before' => '',
							'comment_notes_after'  => '',
							'fields'               => array(
								'author' => '<div class="row"><div class="col-12 col-sm-6"><div class="form-group">'.
								            '<label>'.esc_attr__('Name', 'educrat').'</label>
								            <input id="author" placeholder="'.esc_attr__( 'Your Name', 'educrat' ).'" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></div></div>',
								'email'  => '<div class="col-12 col-sm-6"><div class="form-group">' .
								            '<label>'.esc_attr__('Email', 'educrat').'</label>
								            <input id="email" placeholder="'.esc_attr__( 'your@mail.com', 'educrat' ).'" class="form-control" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></div></div>',
								            'url' => '<div class="col-12 col-sm-6 d-none"><div class="form-group"><label>'.esc_html__( 'Website', 'educrat' ).'</label>
		                                            <input id="url" placeholder="'.esc_attr__( 'Your Website', 'educrat' ).'" name="url" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '"  />
		                                       	</div></div></div>',
							),
							'label_submit'  => esc_html__( 'Submit Review', 'educrat' ),
							'logged_in_as'  => '',
							'comment_field' => '',
							'title_reply_before' => '<h4 class="title comment-reply-title">',
							'title_reply_after'  => '</h4>',
							'class_form'  => 'comment-form-theme',
							'class_submit' => 'btn btn-theme'
						);

						
						//$comment_form['must_log_in'] = '<div class="must-log-in">' .  esc_html__( 'You must be logged in to post a review.', 'educrat' ) . '</div>';

						$comment_form['must_log_in'] = '<div class="must-log-in custombtn"><p>You must be logged in to post a review</p><p><a class="btn-login btn btn-sm btn-theme" href="https://gurukul.definedgesecurities.com/lp-profile/" title="Log in">
Log in </a></p></div>';
						

						$comment_form['comment_field'] = '<div class="choose-rating clearfix"><div class="choose-rating-inner">'.'

							<div class="form-group yourview"><div class="comment-form-rating"><label for="rating">' . esc_html__( 'What is it like to Course?', 'educrat' ) .'</label>
								<div class="review-stars-wrap">						
								<ul class="review-stars">
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
								</ul>
								<ul class="review-stars filled">
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
								</ul></div>
								<input type="hidden" value="5" name="rating" id="apus_input_rating">
								</div></div></div></div>
								' ;
						

						$comment_form['comment_field'] .= '<div class="form-group"><label>'.esc_attr__('Reviews', 'educrat').'</label><textarea id="comment" class="form-control" placeholder="'.esc_attr__( 'Write Reviews', 'educrat' ).'" name="comment" cols="45" rows="5" aria-required="true"></textarea></div>';
						
						educrat_comment_form($comment_form);
					?>
				</div>
			</div>
			

		</div>

	</div>

	<div class="row">
		<div class="col-md-12 col-sm-12">
			<?php if ( have_comments() ) : ?>
				<div id="comments" class="box-info-white comments-course">
					<h3 class="title"><?php echo sprintf(_n(esc_html__('Review', 'educrat').' <small>(%s)</small>', esc_html__('Reviews', 'educrat').' <small>(%s)</small>', $total), $total); ?></h3>
					<ol class="comment-list">
						<?php wp_list_comments( array( 'callback' => array( 'Educrat_Course_Review', 'course_comments' ) ) ); ?>
					</ol>

					<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
						echo '<nav class="apus-pagination">';
						paginate_comments_links( apply_filters( 'apus_comment_pagination_args', array(
							'prev_text' => '&larr;',
							'next_text' => '&rarr;',
							'type'      => 'list',
						) ) );
						echo '</nav>';
					endif; ?>
				</div>
			<?php endif; ?>
		</div>

	</div>

	
	
</div>