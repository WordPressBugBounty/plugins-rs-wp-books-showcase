<?php
add_shortcode( 'rswpbs_book_single_page', 'rswpbs_book_single_shortcode' );
function rswpbs_book_single_shortcode($atts) {
	$atts = shortcode_atts(
		array(
			'layout'	=> 'default',
		),$atts
	);
	$showContentSection = true;
	if (class_exists('Rswpbs_Pro')) {
		$showContentSection = get_option('rswpbs_show_book_long_description', 1);
		$showContentSection = ($showContentSection === NULL || $showContentSection == true) ? 'true' : 'false';
	}
	ob_start();
	do_action('rswpbs_book_page_before');
	?>
	<div class="rswpbs-book-single-wrapper">
		<div class="rswpbs-container">
			<?php
			/**
			 * Book Header Section
			 */
			rswpbs_book_header_section();
			/**
			 * Book Content Section
			 */
			if('true' == $showContentSection) :
				rswpbs_book_content_section();
			endif;
			?>
		</div>
	</div>
	<!-- Testimonial Area -->
	<?php
	do_action('rswpbs_book_page_after');
	return ob_get_clean();
}