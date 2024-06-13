<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Educrat
 * @since Educrat 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div class="inner-detail">
	<div id="comments" class="comments-area">
		<?php if ( have_comments() ) : ?>
			<div class="box-comment">
		        <h3 class="comments-title"><?php comments_number( esc_html__('0 Comments', 'educrat'), esc_html__('1 Comment', 'educrat'), esc_html__('% Comments', 'educrat') ); ?></h3>
				<ol class="comment-list">
					<?php wp_list_comments('callback=educrat_comment_item'); ?>
				</ol><!-- .comment-list -->

				<?php educrat_comment_nav(); ?>
			</div>	
		<?php endif; // have_comments() ?>

		<?php
			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'educrat' ); ?></p>
		<?php endif; ?>

		<?php
	        $aria_req = ( $req ? " aria-required='true'" : '' );
	        $comment_args = array(
	                        'title_reply'=> esc_html__('Leave a Comment','educrat'),
	                        'comment_field' => '<div class="form-group space-comment">
	                        <label>'.esc_attr__('Comment', 'educrat').'</label>
	                                                <textarea rows="7" id="comment" class="form-control" placeholder="'.esc_attr__('Enter Your Comment', 'educrat').'" name="comment"'.$aria_req.'></textarea>
	                                            </div>',
	                        'fields' => apply_filters(
	                        	'comment_form_default_fields',
		                    		array(
		                                'author' => '<div class="row"><div class="col-12 col-sm-6"><div class="form-group ">
		                                            <label>'.esc_attr__('Name', 'educrat').'</label>
		                                            <input type="text" name="author" class="form-control" id="author" placeholder="'.esc_attr__('Your Name', 'educrat').'" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' />
		                                            </div></div>',
		                                'email' => ' <div class="col-12 col-sm-6"><div class="form-group ">
		                                            <label>'.esc_attr__('Email', 'educrat').'</label>
		                                            <input id="email"  name="email" class="form-control" type="text" placeholder="'.esc_attr__('Your Email', 'educrat').'" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" ' . $aria_req . ' />
		                                            </div></div>',
		                                'Website' => ' <div class="col-12 col-sm-4 d-none"><div class="form-group ">
		                                            <input id="website" name="website" placeholder="'.esc_attr__('Website', 'educrat').'" class="form-control" type="text" value="' . esc_attr(  $commenter['comment_author_url'] ) . '" ' . $aria_req . ' />
		                                            </div></div></div>',
		                            )
								),
		                        'label_submit' => esc_html__('Submit Comment', 'educrat'),
								'comment_notes_before' => '',
								'comment_notes_after' => '',
								'title_reply_before' => '<h4 class="comment-reply-title">',
								'title_reply_after'  => '</h4>',
								'class_form'  => 'comment-form-theme',
								'class_submit' => 'btn btn-theme btn-inverse'
	                        );
	    ?>

		<?php educrat_comment_form($comment_args); ?>
	</div><!-- .comments-area -->
</div>