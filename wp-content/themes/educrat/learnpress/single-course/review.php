<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( $args );

global $post;
$rating = intval( get_comment_meta( $comment->comment_ID, '_rating', true ) );

?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="the-comment">
		<div class="avatar">
			<?php echo get_avatar( $comment->user_id, '70', '' ); ?>
		</div>
		<div class="comment-box">
			<div class="inner-left">
				<div class="d-flex flex-nowrap">
					<h3 class="name-comment"><?php comment_author(); ?></h3> 
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<div class="date"><em><?php esc_html_e( 'Your comment is awaiting approval', 'educrat' ); ?></em></div>
					<?php else : ?>
						<div class="date">
							<?php echo get_comment_date( get_option('date_format', 'd M, Y') ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( empty($comment->comment_parent) ) { ?>
					<div class="star-rating clear" title="<?php echo sprintf(esc_attr__( 'Rated %d out of 5', 'educrat' ), $rating ) ?>">
						<?php Educrat_Course_Review::print_review($rating); ?>
					</div>
				<?php } ?>
			</div>
			<div itemprop="description" class="comment-text">
				<?php comment_text(); ?>
			</div>
			<div class="mt-1">
				<div id="comment-reply-wrapper-<?php comment_ID(); ?>" class="comment-author">
					<?php comment_reply_link(array_merge( $args, array(
						'reply_text' => '<i class="ti-back-left"></i>'.esc_html__('Reply', 'educrat'),
						'add_below' => 'comment-reply-wrapper',
						'depth' => 1,
						'max_depth' => $args['max_depth']
					))) ?>
				</div>
			</div>
		</div>
	</div>