<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
$sidebar_configs = educrat_get_event_layout_configs();

educrat_render_breadcrumbs();

$display_mode = educrat_get_config( 'events_display_mode', 'grid' );
$columns = educrat_get_config( 'events_columns', 3 );
$bcols = $columns ? 12/$columns : 4;

if ( defined('EDUCRAT_DEMO_MODE') && EDUCRAT_DEMO_MODE ) {
	if (!empty($_GET['style']) && ($_GET['style'] =='full') ){
	    $columns = 3;
	    $events_item_style = 'v1';
	    $sidebar_configs['main'] = array( 'class' => 'col-12' );
	    $sidebar_configs['left']['class'] = 'd-none';
	    $sidebar_configs['left'] = $sidebar_configs['right'] = false;
	}
}

?>
<section id="main-container" class="main-content  <?php echo apply_filters('educrat_event_content_class', 'container');?> inner">
	<?php educrat_before_content( $sidebar_configs ); ?>
	<div class="row">
		<?php educrat_display_sidebar_left( $sidebar_configs ); ?>

		<div id="main-content" class="col-sm-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
			<main id="main" class="site-main layout-event" role="main">

			<?php if ( have_posts() ) :
				$events_item_style = educrat_get_config('events_item_style', '');
				if ( !empty($events_item_style) ) {
					$events_item_style = '-'.$events_item_style;
				} else {
					$events_item_style = '';
				}

				if ( empty($sidebar_configs['left']) && empty($sidebar_configs['right']) )	{
			?>
				<div class="event-header-categories">
					<?php
					$terms = get_terms(array(
						'taxonomy' => 'simple_event_category',
						'hide_empty' => true,
					));
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
						$selected = '';
						if ( is_tax('simple_event_category') ) {
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
			<?php } else { ?>
				<div class="event-header d-sm-flex align-items-center">
					<?php
					educrat_event_loop_result_count();
					educrat_event_loop_orderby();
					?>
				</div>
			<?php } ?>
				
				<div class="row">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="col-sm-<?php echo esc_attr($bcols); ?>">
							<?php echo ApusSimpleEvent_Template_Loader::get_template_part( 'loop/inner' . $events_item_style ); ?>
						</div>
					<?php endwhile; ?>
				</div>

				<?php

				// Previous/next page navigation.
				educrat_paging_nav();

			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-posts/content', 'none' );
			endif;
			?>

			</main><!-- .site-main -->
		</div><!-- .content-area -->
		
		<?php educrat_display_sidebar_right( $sidebar_configs ); ?>
		
	</div>
</section>
<?php get_footer();