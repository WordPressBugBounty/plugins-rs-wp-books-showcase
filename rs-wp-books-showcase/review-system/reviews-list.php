<?php
add_action('rswpbs_book_page_after', 'rswpbs_book_reviews', 10);
function rswpbs_book_reviews(){
	$showReviewList = true;
	$showReviewSectionTitle = true;
	if (class_exists('Rswpbs_Pro')) {
		$showReviewList = get_field('show_book_reviews', 'option');
		$showReviewSectionTitle = get_field('show_book_reviews_section_title', 'option');
		$showReviewSectionTitle = ($showReviewSectionTitle === NULL || $showReviewSectionTitle === true) ? 'true' : 'false';
	}else{
		$showReviewList = true;
	}
	if (true === $showReviewList) :
	$reviewsArgs = array(
		'post_type'	=> 'book_reviews',
		'posts_per_page' => 10,
		'meta_key'	=> '_rswpbs_reviewed_book',
		'meta_value'	=> get_the_ID(),
		'post_status'	=> 'publish',
	);
	$reviewesQuery = new WP_Query($reviewsArgs);
	if ($reviewesQuery->have_posts()) :
	?>
	<div class="rswpbs-book-reviews-area">
		<div class="rswpbs-container">
			<div class="rswpbs-row">
				<div class="rswpbs-col-md-12">
					<div class="rswpbs-book-reviews-inner">
						<?php
						if('true' == $showReviewSectionTitle) :
						?>
						<div class="section-title-area">
							<div class="rswpbs-row">
								<div class="rswpbs-col-md-12 text-center text-md-left">
									<div class="section-title">
										<h2><?php echo rswpbs_static_text_readers_feedback();?></h2>
									</div>
								</div>
							</div>
						</div>
						<?php
						endif;
						?>
						<div class="book-review-list">
							<div class="rswpbs-row rswpbs-testimonial-masonry">
								<?php
								while( $reviewesQuery->have_posts()) :
									$reviewesQuery->the_post();
									$testimonialColumn = 'rswpbs-col-12 rswpbs-col-md-6 rswpbs-col-lg-4';
								 ?>
								<div class="rswpbs-col-12 rswpbs-col-md-6 rswpbs-col-lg-4 testimonial-item-col">
									<div class="testimonial__item-inner">
										<?php
										$reviewerEmail = get_post_meta( get_the_ID(), '_rswpbs_reviewer_email', true);
										$reviewerName = get_post_meta( get_the_ID(), '_rswpbs_reviewer_name', true);
										$reviewerImage = get_the_post_thumbnail( get_the_ID(), 'full' );
										if (!empty($reviewerEmail)) {
											$reviewerImage = get_avatar($reviewerEmail, 70, 'wavatar', $reviewerName );
										}
										if (!empty(get_the_title( get_the_ID() ))):
										?>
										<h5 class="review-title"><?php echo esc_html( get_the_title(get_the_ID()) );?></h5>
										<?php endif;
										$reviewerRating = get_post_meta(get_the_ID(), '_rswpbs_rating', true);
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
										if (!empty(get_the_title( get_the_ID() ))):
										?>
										<div class="client-feedback">
											<?php echo rswpbs_short_and_long_content(260); ?>
										</div>
										<?php
										endif; ?>
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
									</div>
								</div>
								<?php endwhile; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	endif;
	wp_reset_postdata();
	endif;
}