<?php
if ( !function_exists ('educrat_custom_styles') ) {
	function educrat_custom_styles() {
		global $post;	
		
		ob_start();	
		?>
		
			<?php if ( educrat_get_config('main_color') != "" ) {
				$main_color = educrat_get_config('main_color');
			} else {
				$main_color = '#6440FB';
			}

			if ( educrat_get_config('main_hover_color') != "" ) {
				$main_hover_color = educrat_get_config('main_hover_color');
			} else {
				$main_hover_color = '#5027fa';
			}

			if ( educrat_get_config('text_color') != "" ) {
				$text_color = educrat_get_config('text_color');
			} else {
				$text_color = '#4F547B';
			}

			if ( educrat_get_config('link_color') != "" ) {
				$link_color = educrat_get_config('link_color');
			} else {
				$link_color = '#140342';
			}

			if ( educrat_get_config('link_hover_color') != "" ) {
				$link_hover_color = educrat_get_config('link_hover_color');
			} else {
				$link_hover_color = '#6440FB';
			}

			if ( educrat_get_config('heading_color') != "" ) {
				$heading_color = educrat_get_config('heading_color');
			} else {
				$heading_color = '#140342';
			}

			$main_color_rgb = educrat_hex2rgb($main_color);
			
			// font
			$main_font = educrat_get_config('main-font');
			$main_font = !empty($main_font) ? json_decode($main_font, true) : array();
			$main_font_family = !empty($main_font['fontfamily']) ? $main_font['fontfamily'] : 'GT Walsheim Pro';
			$main_font_weight = !empty($main_font['fontweight']) ? $main_font['fontweight'] : 400;
			$main_font_size = !empty(educrat_get_config('main-font-size')) ? educrat_get_config('main-font-size').'px' : '15px';

			$main_font_arr = explode(',', $main_font_family);
			if ( count($main_font_arr) == 1 ) {
				$main_font_family = "'".$main_font_family."'";
			}
			
			$heading_font = educrat_get_config('heading-font');
			$heading_font = !empty($heading_font) ? json_decode($heading_font, true) : array();
			$heading_font_family = !empty($heading_font['fontfamily']) ? $heading_font['fontfamily'] : 'GT Walsheim Pro';
			$heading_font_weight = !empty($heading_font['fontweight']) ? $heading_font['fontweight'] : 700;

			$heading_font_arr = explode(',', $heading_font_family);
			if ( count($heading_font_arr) == 1 ) {
				$heading_font_family = "'".$heading_font_family."'";
			}
			?>
			:root {
			  --educrat-theme-color: <?php echo trim($main_color); ?>;
			  --educrat-text-color: <?php echo trim($text_color); ?>;
			  --educrat-link-color: <?php echo trim($link_color); ?>;
			  --educrat-link_hover_color: <?php echo trim($link_hover_color); ?>;
			  --educrat-heading-color: <?php echo trim($heading_color); ?>;
			  --educrat-theme-hover-color: <?php echo trim($main_hover_color); ?>;

			  --educrat-main-font: <?php echo trim($main_font_family); ?>;
			  --educrat-main-font-size: <?php echo trim($main_font_size); ?>;
			  --educrat-main-font-weight: <?php echo trim($main_font_weight); ?>;
			  --educrat-heading-font: <?php echo trim($heading_font_family); ?>;
			  --educrat-heading-font-weight: <?php echo trim($heading_font_weight); ?>;

			  --educrat-theme-color-005: <?php echo educrat_generate_rgba($main_color_rgb, 0.05); ?>
			  --educrat-theme-color-007: <?php echo educrat_generate_rgba($main_color_rgb, 0.07); ?>
			  --educrat-theme-color-010: <?php echo educrat_generate_rgba($main_color_rgb, 0.1); ?>
			  --educrat-theme-color-015: <?php echo educrat_generate_rgba($main_color_rgb, 0.15); ?>
			  --educrat-theme-color-020: <?php echo educrat_generate_rgba($main_color_rgb, 0.2); ?>
			  --educrat-theme-color-050: <?php echo educrat_generate_rgba($main_color_rgb, 0.5); ?>
			}
			
			<?php if (  educrat_get_config('header_mobile_color') != "" ) : ?>
				#apus-header-mobile {
					background-color: <?php echo esc_html( educrat_get_config('header_mobile_color') ); ?>;
				}
			<?php endif; ?>

	<?php
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) {
			if (!empty($line)) {
				$new_lines[] = trim($line);
			}
		}
		
		return implode($new_lines);
	}
}