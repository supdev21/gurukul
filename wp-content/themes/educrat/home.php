<?php
get_header();
$sidebar_configs = educrat_get_blog_layout_configs();
$columns = educrat_get_config('blog_columns', 1);
$layout = educrat_get_config( 'blog_display_mode', 'list' );
educrat_render_breadcrumbs();

$thumbsize = !isset($thumbsize) ? educrat_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;
$checksidebar = (educrat_get_config('blog_single_layout') == 'main') ?'blog-only-main style-'.$layout:'has-sidebar';

if ( defined('EDUCRAT_DEMO_MODE') && EDUCRAT_DEMO_MODE ) {
	if (!empty($_GET['style']) && ($_GET['style'] =='gridsidebar') ){
	    $columns = 3;
	    $layout = 'grid-v2';
	    $sidebar_configs['main'] = array( 'class' => 'col-lg-9 col-12' );
	    $sidebar_configs['right']['sidebar'] = 'blog-sidebar';
	    $sidebar_configs['right']['class'] = 'col-lg-3 col-12';
	    $thumbsize = '450x375';
	} elseif (!empty($_GET['style']) && ($_GET['style'] =='list') ){
		$columns = 1;
	    $layout = 'list';
	    $thumbsize = '520x400';
	    $checksidebar = 'blog-only-main style-list';
	}
}
?>
<section id="main-container" class="main-content home-page-default <?php echo esc_attr($checksidebar); ?> <?php echo apply_filters('educrat_blog_content_class', 'container');?> inner">
	<?php educrat_before_content( $sidebar_configs ); ?>
	<div class="row responsive-medium">
		<?php educrat_display_sidebar_left( $sidebar_configs ); ?>

		<div id="main-content" class="col-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
			<div id="main" class="site-main layout-blog" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header d-none">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<?php
				if ( empty($sidebar_configs['left']) && empty($sidebar_configs['right']) && educrat_get_config('blog_archive_top_categories', false) ){
				?>
					<div class="blog-header-categories">
						<?php
						$terms = get_terms(array(
							'taxonomy' => 'category',
							'hide_empty' => true,
						));
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
							$selected = '';
							if ( is_category() ) {
								global $wp_query;
								$term =	$wp_query->queried_object;
								if ( isset( $term->term_id) ) {
									$selected = $term->term_id;
								}
							}
							?>
						    <ul class="categories-list">
							    <?php foreach ( $terms as $term ) { ?>
							        <li><a href="<?php echo get_term_link($term); ?>" class="<?php echo esc_attr($term->term_id == $selected ? 'active' : ''); ?>"><?php echo esc_html($term->name); ?></a></li>
							    <?php } ?>
						    </ul>
						<?php } ?>
					</div>
				<?php }
				get_template_part( 'template-posts/layouts/'.$layout, null, array('columns' => $columns, 'thumbsize' => $thumbsize) );

				// Previous/next page navigation.
				educrat_paging_nav();

			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-posts/content', 'none' );

			endif;
			?>

			</div><!-- .site-main -->
		</div><!-- .content-area -->
		
		<?php educrat_display_sidebar_right( $sidebar_configs ); ?>
		
	</div>
</section>
<?php get_footer(); ?>