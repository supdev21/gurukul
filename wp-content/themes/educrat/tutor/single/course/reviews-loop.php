<ol class="comment-list">
    <?php foreach ( $reviews as $review ) : ?>
        <?php $profile_url = tutor_utils()->profile_url( $review->user_id, false ); ?>
        <li class="comment">
            <div class="the-comment">
                <div class="avatar">
                    <?php echo tutor_utils()->get_tutor_avatar( $review->user_id, 'md' ); ?>
                </div>
                <div class="comment-box">
                    <div class="inner-left">
                        <div class="d-flex flex-nowrap">
                            <h3 class="name-comment">
                                <a href="<?php echo esc_url( $profile_url ); ?>">
                                    <?php echo esc_html( $review->display_name ); ?>
                                </a>
                            </h3>
                            <div class="date"><?php echo sprintf( esc_html__( '%s ago', 'educrat' ), human_time_diff( strtotime( $review->comment_date ) ) ); ?></div>
                            <?php if($review->comment_status=='hold') : ?>
                                <div style="position:absolute; right:15px">
                                    <span class="tutor-badge-label label-warning">
                                        <?php echo esc_html__('Pending', 'educrat'); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php tutor_utils()->star_rating_generator_v2( $review->rating, null, true, 'xs' ); ?>
                    </div>
                    <div class="comment-text">
                        <?php echo htmlspecialchars( $review->comment_content ); ?>
                    </div>
                </div>        
            </div>
        </li>
    <?php endforeach; ?>
</ol>