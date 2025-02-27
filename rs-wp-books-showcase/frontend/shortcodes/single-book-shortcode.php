<?php
/**
 * Book Small Details Shortcode
 */
// [rswpbs_single_book show_sample_content="true" show_title="false" show_ratings="true" show_description="true" image_type="book_cover" show_price="true" show_msl="true" book_id=""]
add_shortcode('rswpbs_single_book', 'rswpbs_single_book_shortcode');
function rswpbs_single_book_shortcode($atts){
	$atts = shortcode_atts(array(
		'show_sample_content'	=> 'true',
		'show_title'	=> 'true',
		'show_ratings'	=> 'true',
		'show_author'	=> 'true',
		'show_description'	=> 'true',
		'image_type'	=> 'book_cover',
		'show_price'	=> 'true',
		'show_buy_button'	=> 'true',
		'show_add_to_cart_btn'	=> 'false',
		'show_msl'	=> 'true',
		'msl_title_align'	=> 'center',
		'book_id'	=> '',
	), $atts);

	if (is_singular('book')) {
		$bookID = get_the_ID();
	}else{
		$bookID = intval($atts['book_id']);
	}

	ob_start();
	if (!empty($bookID) || 0 != $bookID) :

	$showSampleContent = false;
	if ('true' == $atts['show_sample_content']) {
		$showSampleContent = true;
	}

	$bookImageWrapperColumnClass = 'rswpbs-col-md-12 rswpbs-col-12 pl-0 pr-1';
	$bookImageWrapperRowClass = '';
	if ('true' == $showSampleContent) {
		$bookImageWrapperColumnClass = 'rswpbs-col-md-10 rswpbs-col-10 p-0 pl-2 pl-md-3 pl-lg-3';
		$bookImageWrapperRowClass = ' rswpbs-row m-0';
	}

	$bookImageMainCol = 'rswpbs-col-md-5';
	$bookContentMainCol = 'rswpbs-col-md-7 pl-lg-3';
	$containerExtraClass = ' others-pages';
	$titleTag = 'h2';
	if (is_singular('book')) {
		$bookImageMainCol = 'rswpbs-col-lg-5 rswpbs-col-md-5';
		$bookContentMainCol = 'rswpbs-col-lg-6 rswpbs-col-md-7 pl-lg-3';
		$containerExtraClass = ' book-single-page';
		$titleTag = 'h1';
	}
	?>
	<div class="rswpthemes-book-single-header-content-container<?php echo esc_attr($containerExtraClass);?>">
		<?php do_action('rswpbs_single_book_before_header_row'); ?>
		<div class="rswpbs-row justify-content-center m-0">
			<div class="<?php echo esc_attr($bookImageMainCol);?> text-center">
				<?php do_action('rswpbs_single_book_before_thumbnail'); ?>
				<div class="rswpthemes-book-image-wrapper-row<?php echo esc_attr($bookImageWrapperRowClass);?>">
					<?php
					if (true == $showSampleContent) {
						rswpbs_pro_sample_content($bookID);
					}
					?>
					<div class="<?php echo esc_attr($bookImageWrapperColumnClass);?>">
						<div class="rswpthemes-book-image-wrapper">
							<?php
							if (is_singular('book')) {
								if ('book_cover' == $atts['image_type']) {
							 		echo wp_kses_post(rswpbs_get_book_image($bookID));
								}else{
									if (class_exists('Rswpbs_Pro')) {
										rswpbs_book_mockup_image($bookID);
									}
								}
							}else{
								if ('book_cover' == $atts['image_type']) {
							 		echo '<a href="'.get_the_permalink($bookID).'">'.wp_kses_post(rswpbs_get_book_image($bookID)).'</a>';
								}else{
									if (class_exists('Rswpbs_Pro')) {
										echo '<a href="'.get_the_permalink($bookID).'">'.wp_kses_post(rswpbs_book_mockup_image($bookID)).'</a>';
									}
								}
							}
							?>
						</div>
					</div>
				</div>
				<?php do_action('rswpbs_single_book_after_thumbnail'); ?>
			</div>
			<div class="<?php echo esc_attr($bookContentMainCol);?>">
				<div class="rswpthemes-book-content-wrapper">
					<?php
					do_action('rswpbs_before_single_book_main_details');
					if(!empty(rswpbs_get_book_name($bookID)) && 'true' == $atts['show_title']) :
						if (is_singular('book')) {
							echo '<h1 class="book-name">'.esc_html( rswpbs_get_book_name($bookID) ).'</h1>';
						}else{
							echo '<h2 class="book-name"><a href="'.get_the_permalink( $bookID ).'">'.esc_html( rswpbs_get_book_name($bookID) ).'</a></h2>';
						}
					endif;
					if(!empty(rswpbs_get_book_author($bookID)) && 'true' == $atts['show_author']) : ?>
					<h4 class="book-author"><strong><?php echo rswpbs_static_text_by(); ?> </strong>
						<?php
						echo wp_kses_post(rswpbs_get_book_author($bookID));
						?>
					</h4>
					<?php endif;
					if(!empty(rswpbs_get_avg_rate($bookID)) && 'true' == $atts['show_ratings']) : ?>
					<div class="book-ratings">
						<?php
						echo wp_kses_post(rswpbs_get_avg_rate($bookID));
						?>
					</div>
					<?php endif;
					if(!empty(rswpbs_get_book_desc($bookID)) && 'true' == $atts['show_description']) :
					?>
					<div class="rswpthemes-book-short-description mb-3">
						<?php echo wp_kses_post( rswpbs_get_book_desc($bookID) );?>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_price($bookID)) && 'true' == $atts['show_price']) :
					?>
					<div class="book-price d-flex justify-content-start">
						<strong><?php echo rswpbs_static_text_price(); ?></strong>&nbsp;&nbsp;<?php echo wp_kses_post(rswpbs_get_book_price($bookID)); ?>
					</div>
					<?php endif;
					?>
					<div class="book-single-page-buttons-wrapper">
						<?php
						if ( function_exists('rswpthemes_cptwoointegration') && true == $atts['show_add_to_cart_btn']) :
							echo shortcode_exists('cptwoointegration_cart_button') ? do_shortcode( "[cptwoointegration_cart_button/]" ) : '' ;
						endif;
						if ('true' == $atts['show_buy_button']) :
							if (!empty(rswpbs_get_book_buy_btn($bookID)) && empty(rswpbs_get_book_buy_btn_shortcode($bookID))) :
							?>
							<div class="rswpthemes-buy-now-button-wrapper d-flex justify-content-start">
								<?php echo rswpbs_get_book_buy_btn($bookID); ?>
							</div>
							<?php
							endif;
							if (!empty(rswpbs_get_book_buy_btn_shortcode($bookID))) :
							?>
							<div class="rswpthemes-buy-now-button-wrapper d-flex justify-content-start">
								<?php echo rswpbs_get_book_buy_btn_shortcode($bookID); ?>
							</div>
							<?php
							endif;
						endif;
						?>
					</div>
					<?php
					if ('true' == $atts['show_msl'] && class_exists('Rswpbs_Pro')) {
						echo rswpbs_pro_book_also_available_web_list($bookID, $atts['msl_title_align']);
					}
					do_action('rswpbs_after_single_book_main_details');
					?>
				</div>
			</div>
		</div>
		<?php do_action('rswpbs_single_book_after_header_row'); ?>
	</div>
	<?php
	endif;
	return ob_get_clean();
}