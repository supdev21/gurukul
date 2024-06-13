<?php
$post_format = get_post_format();
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="inner">
        <div class="entry-content-detail <?php echo esc_attr((!has_post_thumbnail())?'not-img-featured':'' ); ?>">

            <div class="single-info">
                    <div class="inner-detail">
                        <div class="top-detail-info">
                            <?php educrat_post_categories($post); ?>
                            <?php if (get_the_title()) { ?>
                                <h1 class="detail-title">
                                    <?php the_title(); ?>
                                </h1>
                            <?php } ?>
                            <div class="date"><?php the_time( get_option('date_format', 'd M, Y') ); ?></div>
                        </div>
                    </div>
                    <?php if(has_post_thumbnail()) { ?>
                        <div class="entry-thumb">
                            <?php
                                $thumb = educrat_post_thumbnail();
                                echo trim($thumb);
                            ?>
                        </div>
                    <?php } ?>
                    <div class="inner-detail">
                        <div class="entry-description">
                            <?php
                                the_content();
                            ?>
                        </div>
                        <?php
                        wp_link_pages( array(
                            'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'educrat' ) . '</span>',
                            'after'       => '</div>',
                            'link_before' => '<span>',
                            'link_after'  => '</span>',
                            'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'educrat' ) . ' </span>%',
                            'separator'   => '',
                        ) );
                        ?>
                    
                        <?php  
                            $posttags = get_the_tags();
                        ?>
                        <?php if( !empty($posttags) || educrat_get_config('show_blog_social_share', false) ){ ?>
                            <div class="tag-social d-md-flex align-items-center">
                                <?php if( educrat_get_config('show_blog_social_share', false) ) { ?>
                                    <?php get_template_part( 'template-parts/sharebox' ); ?>
                                <?php } ?>
                                <?php if(!empty($posttags)){ ?>
                                    <div class="<?php echo esc_attr( (educrat_get_config('show_blog_social_share', false))?'ms-auto':'' ); ?>">
                                        <?php educrat_post_tags(); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php get_template_part( 'template-parts/author-bio' ); ?>
                        
                        <?php
                            //Previous/next post navigation.
                            the_post_navigation( array(
                                'next_text' => 
                                    '<div class="inner inner-right"><i class="flaticon-next"></i>'.
                                    '<div class="navi">' . esc_html__( 'Next', 'educrat' ) . '</div>'.
                                    '<span class="title-direct">%title</span></div>',
                                'prev_text' => 
                                    '<div class="inner inner-left"><i class="flaticon-back"></i>'.
                                    '<div class="navi">' . esc_html__( 'Prev', 'educrat' ) . '</div>'.
                                    '<span class="title-direct">%title</span></div>',
                            ) );
                        ?>
                    </div>
            </div>
        </div>
    </div>
</article>