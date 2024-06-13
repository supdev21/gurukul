<?php 
global $post;
$thumbsize = !isset($args['thumbsize']) ? educrat_get_config( 'blog_item_thumbsize', 'full' ) : $args['thumbsize'];
$thumb = educrat_display_post_thumb($thumbsize);
?>
<article <?php post_class('post post-layout post-grid v2'); ?>>
    <?php
        if ( !empty($thumb) ) {
            ?>
            <div class="top-image">
                <?php
                    echo trim($thumb);
                ?>
             </div>
            <?php
        }
    ?>
    <div class="col-content">
        <?php educrat_post_categories_first($post); ?>
        <?php if (get_the_title()) { ?>
            <h4 class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
        <?php } ?>
        <div class="date"><?php the_time( get_option('date_format', 'd M, Y') ); ?></div>
    </div>
</article>