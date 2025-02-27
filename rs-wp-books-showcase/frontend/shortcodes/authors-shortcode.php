<?php
/**
 * Author Shortcode
 */
add_shortcode('rswpbs_author_shortcode', 'rswpbs_author_shortcode_output');
function rswpbs_author_shortcode_output($atts){
	$atts = shortcode_atts(
		array(
			'layout'	=> 'standard_layout',
			'show_description' => 'true',
			'show_book_count' => 'true',
			'authors_per_row'	=> '4',
		),$atts
	);
	ob_start();

	$authorsPerRow = $atts['authors_per_row'];
	$authorColumnClases = 'rswpbs-col-lg-3 rswpbs-col-md-4 author-single-column';
	if ('1' == $authorsPerRow) {
		$authorColumnClases = 'rswpbs-col-lg-12 author-single-column';
	}elseif('2' == $authorsPerRow){
		$authorColumnClases = 'rswpbs-col-md-6 author-single-column';
	}elseif('3' == $authorsPerRow){
		$authorColumnClases = 'rswpbs-col-lg-6 rswpbs-col-xl-4 rswpbs-col-md-6 author-single-column';
	}elseif('4' == $authorsPerRow){
		$authorColumnClases = 'rswpbs-col-lg-4 rswpbs-col-xl-3 rswpbs-col-md-6 author-single-column';
	}elseif('6' == $authorsPerRow){
		$authorColumnClases = 'rswpbs-col-lg-3 rswpbs-col-xl-2 rswpbs-col-md-4 author-single-column';
	}
	?>
	<div class="rswpbs-authors-shortcode-wrapper">
		<div class="authors-shortcode-row rswpbs-row rswpbs-authors-masonry">
			<?php
			$bookAuthorsTerms = get_terms( array(
				'taxonomy'	=> 'book-author',
				'hide_empty'	=> false,
			) );
			if (!is_wp_error($bookAuthorsTerms)) :
				foreach($bookAuthorsTerms as $author) :
					$termLink = get_term_link( $author->term_id, 'book-author' );
					$post_count = wp_count_posts(array(
			            'taxonomy' => 'book-author',
			            'term'     => $author->slug,
			        ));
					$authorID = 'book-author_'.$author->term_id;
					$isRswpbsPro = false;
					$authorImage = '';
					if (class_exists('Rswpbs_Pro')) {
						$isRswpbsPro = true;
						$authorImage = get_term_meta($author->term_id, 'rswpbs_book_author_picture', true);
					}

					?>
					<div class="<?php echo esc_attr($authorColumnClases);?> rswpbs-author-col">
						<div class="rswpbs-single-author-wrapper">
							<?php
							if (true === $isRswpbsPro) :
								if (!empty($authorImage)) :
								?>
								<div class="rswpbs-author-profile-picture-wrapper">
									<div class="author-profile-picture-container">
										<a href="<?php echo esc_url($termLink);?>">
											<img src="<?php echo $authorImage;?>" alt="<?php echo esc_attr($author->name);?>">
										</a>
									</div>
									<?php
									if (true === $isRswpbsPro) :
									?>
									<div class="author-social-links-wrapper">
										<?php
										if (function_exists('rswpbs_pro_book_author_social_links')) {
											rswpbs_pro_book_author_social_links($authorID);
										}
										?>
									</div>
									<?php
									endif;
									?>
								</div>
								<?php
								endif;
							endif;
							if (true === $isRswpbsPro && empty($authorImage)) :
								rswpbs_pro_book_author_social_links($authorID);
							endif;
							?>
							<div class="author-name">
								<h2><a href="<?php echo esc_url($termLink);?>"><?php echo wp_kses_post($author->name);?></a></h2>
							</div>
							<?php
							if ('true' == $atts['show_book_count']) :
							?>
							<div class="author-book-count">
								<h5>
								<?php
								$post_count = get_term($author->term_id, 'book-author')->count;
								$bookText = 'Book';
								if ($post_count > 1) {
								    $bookText .= 's';
								}
								$bookCountHtml = '<a href="%3$s">%1$d %2$s</a>';
								echo sprintf($bookCountHtml, $post_count, __( $bookText, 'rswpbs' ), esc_url($termLink));
								?>
								</h5>
							</div>
							<?php
							endif;
							if ('true' == $atts['show_description'] && !empty($author->description)) :
							?>
							<div class="author-description">
								<p><?php echo wp_kses_post($author->description);?></p>
							</div>
							<?php
							endif;
							?>
							<div class="view-author-profile-button">
								<a href="<?php echo esc_url($termLink);?>"><?php esc_html_e( 'View Profile', 'rswpbs' );?></a>
							</div>
						</div>
					</div>
					<?php
				endforeach;
			endif;
			?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
