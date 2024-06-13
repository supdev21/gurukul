<?php

global $post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="course-faqs box-info-white" id="course-faqs-accordion">
	<?php $i=1; foreach ( $faqs as $faq ) {
		if ( !empty($faq['question']) && !empty($faq['answer']) ) {
	?>
		<div class="accordion-item">
			<h2 class="accordion-header" id="accordion-heading<?php echo esc_attr($i); ?>">
				<button class="accordion-button <?php echo esc_attr($i == 1 ? '' : 'collapsed'); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-collapse<?php echo esc_attr($i); ?>" aria-expanded="<?php echo esc_attr($i == 1 ? 'true' : 'false'); ?>" aria-controls="accordion-collapse<?php echo esc_attr($i); ?>">
				<?php echo trim($faq['question']); ?>
				</button>
			</h2>
			<div id="accordion-collapse<?php echo esc_attr($i); ?>" class="accordion-collapse collapse <?php echo esc_attr($i == 1 ? 'show' : ''); ?>" aria-labelledby="accordion-heading<?php echo esc_attr($i); ?>" data-bs-parent="#course-faqs-accordion">
				<div class="accordion-body">
					<?php echo trim($faq['answer']); ?>
				</div>
			</div>
		</div>
	<?php $i++;} ?>
	<?php } ?>
</div>