<?php
/**
 * Book Review Shortcode
 */
add_shortcode( 'rswpbs_reviews', 'rswpbs_books_review_shortcode' );
function rswpbs_books_review_shortcode( $atts ) {
	$atts = shortcode_atts( array(
			'review_per_page'		=> '8',
			'review_layout' 		=> 'slider',
			'layout_style' 		=> 'default',
			'section_heading' 		=> '',
			'section_sub_heading' 		=> '',
			'select_book'			=>	'',
			'show_slider_nagivation'	=>	'true',
			'show_section_title'	=>	'true',
			'show_quote'	=>	'true',
			'show_ratings'	=>	'true',
			'show_reviewer'	=>	'true',
			'large_screen' => '3',
			'medium_screen' => '2',
			'small_screen' => '1',
			'container' => 'rswpbs-container',
		), $atts
	);

	ob_start();

	$query_args = array(
	 	'posts_per_page' => intval($atts['review_per_page']),
	  	'post_type' => array('book_reviews'),
	);

	if(!empty($atts['select_book'])){
		$selectBooks = explode(',', $atts['select_book']);
		$query_args['meta_key'] = '_rswpbs_reviewed_book';
		$query_args['meta_value'] = $selectBooks;
	}

	$wrapper_class = 'rswpbs-row rswpbs-testimonial-masonry';
	$item_class = 'rswpbs-col-md-4 testimonial-item-col';
	if ($atts['review_layout'] == 'slider'):
		$wrapper_class = 'rswpbs-row review_slider_active';
		$item_class = 'single_review_item rswpbs-col-md-4';
	endif;
	if ('classic' == $atts['layout_style']) {
		$item_class .= ' layout-style-classic';
	}

	$item_margin = 'testimonial__item-inner';
	// if ($atts['review_layout'] == 'grid'):
	// 	$item_margin  = 'item-margin-off';
	// endif;

	$reviewQuery = new WP_Query($query_args);
	if ($reviewQuery->have_posts()) :
	?>
	<div class="rswpbs-customer-review-wrapper">
		<div class="<?php echo esc_attr($atts['container']); ?>">
			<?php
			if ('true' == $atts['show_section_title'] || 'true' == $atts['show_slider_nagivation']) :
			?>
			<div class="rswpbs-row rswpbs_customer-review-section-heading">
				<div class="rswpbs-col-md-8 align-self-center">
					<?php
					if ('true' == $atts['show_section_title']) :
					?>
					<div class="customer-reveiw-section-title">
						<h4><?php echo esc_html( $atts['section_sub_heading'] );?></h4>
						<h2><?php echo esc_html( $atts['section_heading'] );?></h2>
					</div>
					<?php endif;?>
				</div>
				<?php
				if ('slider'== $atts['review_layout'] && 'true' == $atts['show_slider_nagivation']) :
				?>
				<div class="rswpbs-col-md-4 align-self-center">
					<div class="rswpbs_customer-review-slider-pagination">
						<div class="review-slider-prev">
							<i class="fa-solid fa-angle-left prev-btn"></i>
						</div>
						<div class="review-slider-next">
							<i class="fa-solid fa-angle-right next-btn"></i>
						</div>
					</div>
				</div>
				<?php
				endif;
				?>
			</div>
			<?php
			endif;
			 ?>
			<div class="<?php echo esc_attr( $wrapper_class );?> mt-5" data-lscreen="<?php echo esc_attr($atts['large_screen']);?>" data-mscreen="<?php echo esc_attr($atts['medium_screen']);?>" data-sscreen="<?php echo esc_attr($atts['small_screen']);?>">
				<?php
				while( $reviewQuery->have_posts()) :
					$reviewQuery->the_post();
					$getRatings = get_post_meta( get_the_ID(), '_rswpbs_rating', true );
					$reviewerName = get_post_meta( get_the_ID(), '_rswpbs_reviewer_name', true );
				?>
				<div class="<?php echo esc_attr( $item_class ); ?> efe_customer_review_de">
					<div class="testimonial__item-inner">
						<?php
						$reviewerEmail = get_post_meta( get_the_ID(), '_rswpbs_reviewer_email', true);
						$reviewerName = get_post_meta( get_the_ID(), '_rswpbs_reviewer_name', true);
						$reviewerImage = get_avatar($reviewerEmail, 70, 'wavatar', $reviewerName );
						if(has_post_thumbnail() && !empty(get_the_post_thumbnail_url())) {
							$reviewerImage = get_the_post_thumbnail( get_the_ID() );
						}
						$reviewerRating = get_post_meta(get_the_ID(), '_rswpbs_rating', true);
						if ('default' == $atts['layout_style']) :
							if (!empty(get_the_title( get_the_ID() ))):
							?>
							<h5 class="review-title"><?php echo esc_html( get_the_title(get_the_ID()) );?></h5>
							<?php endif;
							if (!empty($reviewerRating)) :
							?>
							<div class="client-rating">
								<?php
								for ($i=0; $i < $reviewerRating; $i++) {
									echo wp_kses_post('<span class="fa-regular fa-star fa-solid"></span>');
								}
								?>
							</div>
							<?php
							endif;
						elseif('classic' == $atts['layout_style']):
							?>
							<div class="ratings-and-quote-wrapper d-flex justify-content-between">
								<?php
								if ('true' == $atts['show_quote']) :
								 ?>
								<div class="quote align-self-center"><i class="fa-solid fa-quote-left"></i></div>
								<?php
								endif;
								if (!empty($reviewerRating) && 'true' == $atts['show_ratings']) :
								?>
								<div class="rattings-wrapper align-self-center">
									<div class="client-rating">
										<?php
										for ($i=0; $i < $reviewerRating; $i++) {
											echo wp_kses_post('<span class="fa-regular fa-star fa-solid"></span>');
										}
										?>
									</div>
								</div>
								<?php
								endif;
								?>
							</div>
							<?php
						endif;
						if (!empty(get_the_title( get_the_ID() ))) :
						?>
						<div class="client-feedback">
							<?php rswpbs_short_and_long_content(260); ?>
						</div>
						<?php
						endif;
						if ('true' == $atts['show_reviewer']) :
						?>
						<div class="reviewer-wrapper">
							<?php
							if (!empty($reviewerImage)) :?>
							<div class="client-image">
								<?php
									echo wp_kses_post( $reviewerImage );
								?>
							</div>
							<?php endif;
							if(!empty($reviewerName)) :
							?>
							<div class="name-and-date">
								<h4 class="client-name"><?php echo esc_html( $reviewerName );?></h4>
								<div class="review-time">
									<?php rswpbs_ctp_pub_time(); ?>
								</div>
							</div>
							<?php endif;?>
						</div>
						<?php
						endif;
						?>
					</div>
				</div>
				<?php endwhile;?>
			</div>
		</div>
	</div>
	<?php
	endif;
	wp_reset_postdata();
	return ob_get_clean();
}