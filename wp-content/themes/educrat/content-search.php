<?php 
global $post;
$thumbsize = !isset($args['thumbsize']) ? educrat_get_config( 'blog_item_thumbsize', 'full' ) : $args['thumbsize'];
$thumb = educrat_display_post_thumb($thumbsize);
?>
<article <?php post_class('post post-layout post-list-item'); ?>>
    <div class="d-sm-flex align-items-center">
        <?php
        if ( !empty($thumb) ) {
            ?>
            <div class="top-image flex-shrink-0">
                <?php
                    echo trim($thumb);
                ?>
             </div>
            <?php
        } ?>
        <div class="col-content flex-grow-1">
            <div class="d-flex">
                <?php educrat_post_categories_first($post); ?>
                <div class="date"><?php the_time( get_option('date_format', 'd M, Y') ); ?></div>
            </div>
            <?php if (get_the_title()) { ?>
                <h4 class="entry-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h4>
            <?php } ?>
            <div class="description"><?php echo educrat_substring( get_the_excerpt(),19, '...' ); ?></div>
            <a class="btn btn-readmore d-none d-md-inline-block" href="<?php the_permalink(); ?>"><?php echo esc_html__('Read More','educrat') ?></a>
        </div>
    </div>
</article>