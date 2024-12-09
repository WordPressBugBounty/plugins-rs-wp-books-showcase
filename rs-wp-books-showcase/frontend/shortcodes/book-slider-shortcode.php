<?php
/**
 * Book Slider Shortcode
 */
function rswpbs_pro_book_slider_old_atts_to_new($atts){
    if (isset($atts['book_cover_position'])) {
        $atts['image_position'] = $atts['book_cover_position'];
        unset($atts['book_cover_position']);
    }
    if (isset($atts['show_description'])) {
        $atts['show_excerpt'] = $atts['show_description'];
        unset($atts['show_description']);
    }
    if (isset($atts['descriptions_limit'])) {
        $atts['excerpt_limit'] = $atts['descriptions_limit'];
        unset($atts['descriptions_limit']);
    }
    if (isset($atts['ordery'])) {
        $atts['orderby'] = $atts['ordery'];
        unset($atts['ordery']);
    }
    return $atts;
}

add_shortcode( 'rswpbs_book_slider', 'rswpbs_book_slider_shortcode' );
function rswpbs_book_slider_shortcode( $atts ) {
	$atts = rswpbs_pro_book_slider_old_atts_to_new($atts);
    $atts = shortcode_atts( array(
        'slider_style' => 'carousel',
        'books_per_page' => '6',
        'categories_include' => '',
        'categories_exclude' => '',
        'authors_include' => '',
        'authors_exclude' => '',
        'include_books' => '',
        'exclude_books' => '',
        'book_offset' => '',
        'show_author' => 'true',
        'show_title' => 'true',
        'title_type' => 'book_name',
        'excerpt_type' => 'excerpt',
        'show_excerpt' => 'true',
        'excerpt_limit' => '60',
        'show_image' => 'true',
        'image_type' => 'book_cover',
        'image_position' => 'top',
        'show_price' => 'true',
        'show_buy_button' => 'true',
        'show_add_to_cart_btn' => 'true',
        'sts_l_screen' => '4',
        'sts_m_screen' => '3',
        'sts_s_screen' => '1',
        'show_msl' => 'true',
        'msl_title_align' => 'center',
        'content_align' => 'center',
        'order' => 'DESC',
        'orderby' => 'date',
        'show_read_more_button' => 'false',
    ), $atts );
    ob_start();

    $booksQargs = array(
			'post_type'	=> array('book'),
		);
	if (!empty($atts['books_per_page'])) {
		$booksQargs['posts_per_page'] = intval($atts['books_per_page']);
	}

	if (!empty($atts['orderby'])) {
		$booksQargs['orderby'] = $atts['orderby'];
		if('price' == $atts['orderby']){
			$booksQargs['meta_key'] = '_rsbs_book_query_price';
			$booksQargs['orderby'] = 'meta_value_num';
		}
	}
	if (!empty($atts['order'])) {
		$booksQargs['order'] = $atts['order'];
	}
	if (!empty($atts['exclude_books']) && $atts['exclude_books'] !== 'false') {
		$excludeBooksIds = array_map('intval', explode(',' , $atts['exclude_books']));
		$booksQargs['post__not_in'] = $excludeBooksIds;
	}
	if (!empty($atts['include_books']) && $atts['include_books'] !== 'false') {
		$includeBooksIds = array_map('intval', explode(',' , $atts['include_books']));
		$booksQargs['post__in'] = $includeBooksIds;
	}
	$includeCategory = false;
	if (!empty($atts['categories_include']) && $atts['categories_include'] !== 'false' && $atts['categories_include'] !== 'true') {
		$includeCategory = true;
	}
	if (true === $includeCategory) {
		$includeBookCategories = array_map('intval', explode(',' , $atts['categories_include']));
		$booksQargs['tax_query'] = array(
		        array(
		            'taxonomy' => 'book-category',
		            'field'    => 'term_id',
		            'terms'    => $includeBookCategories,
		        ),
		    );
	}

	$includeAuthors = false;
	if (!empty($atts['authors_include']) && $atts['authors_include'] !== 'false' && $atts['authors_include'] !== 'true') {
		$includeAuthors = true;
	}

	if (true === $includeAuthors) {
		$includeBookAuthors = array_map('intval', explode(',' , $atts['authors_include']));
		$booksQargs['tax_query'] = array(
	        array(
	            'taxonomy' => 'book-author',
	            'field'    => 'term_id',
	            'terms'    => $includeBookAuthors,
	        ),
	    );
	}
	if (true === $includeCategory && true === $includeAuthors) {
		$booksQargs['tax_query'] = array(
        	'relation' => 'AND',
	        array(
	            'taxonomy' => 'book-author',
	            'field'    => 'term_id',
	            'terms'    => $includeBookAuthors,
	        ),
	        array(
	            'taxonomy' => 'book-category',
	            'field'    => 'term_id',
	            'terms'    => $includeBookCategories,
	        ),
    	);
	}
	$excludeCategories = false;
	if (!empty($atts['categories_exclude']) && $atts['categories_exclude'] !== 'false' && $atts['categories_exclude'] !== 'true') {
		$excludeCategories = true;
	}
	$excludeAuthors = false;
	if (!empty($atts['authors_exclude']) && $atts['authors_exclude'] !== 'false' && $atts['authors_exclude'] !== 'true') {
		$excludeAuthors = true;
	}

	if (true === $excludeCategories || true === $excludeAuthors) {
		$excludeBookCategories = array_map('intval', explode(',' , $atts['categories_exclude']));
		$excludeBookAuthors = array_map('intval', explode(',' , $atts['authors_exclude']));
 		$exclude_tax_query = array();
 		if (true === $excludeAuthors) {
	        $exclude_tax_query[] = array(
	            'taxonomy' => 'book-author',
	            'field'    => 'term_id',
	            'terms'    => $excludeBookAuthors,
	            'operator' => 'NOT IN',
	        );
	    }
	    if (true === $excludeCategories) {
	        $exclude_tax_query[] = array(
	            'taxonomy' => 'book-category',
	            'field'    => 'term_id',
	            'terms'    => $excludeBookCategories,
	            'operator' => 'NOT IN',
	        );
	    }
	    $booksQargs['tax_query']['relation'] = 'AND';
		$booksQargs['tax_query'] = array_merge($booksQargs['tax_query'], $exclude_tax_query);
	}

	if (!empty($atts['book_offset'])) {
		$booksQargs['offset'] = $atts['book_offset'];
	}

	$sliderItemClasses = array();

	if ($atts['show_author'] == 'true') {
	    $sliderItemClasses[] = 'has-book-author';
	} else {
	    $sliderItemClasses[] = 'no-book-author';
	}
	if ($atts['show_title'] == 'true') {
	    $sliderItemClasses[] = 'has-book-title';
	} else {
	    $sliderItemClasses[] = 'no-book-title';
	}

	if ($atts['show_excerpt'] == 'true') {
	    $sliderItemClasses[] = 'has-book-description';
	} else {
	    $sliderItemClasses[] = 'no-book-description';
	}

	if ($atts['show_image'] == 'true') {
	    $sliderItemClasses[] = 'has-book-image';
	} else {
	    $sliderItemClasses[] = 'no-book-image';
	}

	if ($atts['show_price'] == 'true') {
	    $sliderItemClasses[] = 'has-book-price';
	} else {
	    $sliderItemClasses[] = 'no-book-price';
	}

	if ($atts['show_buy_button'] == 'true') {
	    $sliderItemClasses[] = 'has-book-buy-button';
	} else {
	    $sliderItemClasses[] = 'no-book-buy-button';
	}

	if ($atts['content_align'] == 'left') {
		$sliderItemClasses[] = 'slider-content-left';
	}elseif ($atts['content_align'] == 'right') {
		$sliderItemClasses[] = 'slider-content-right';
	}elseif ($atts['content_align'] == 'center') {
		$sliderItemClasses[] = 'slider-content-center';
	}
	if ('featured' !== $atts['slider_style'] && !empty($atts['sts_l_screen'])) {
	    $sliderItemClasses[] = 'slider-large-screen-item-' . $atts['sts_l_screen'];
	}
	if ('featured' == $atts['slider_style']) {
		$sliderItemClasses[] = 'featured-slider';
	}elseif ('carousel' == $atts['slider_style']) {
		$sliderItemClasses[] = 'carousel-slider';
	}
	$sliderItemClasess_string = implode(' ', $sliderItemClasses);

	$loopContentWrapper = false;
	if ($atts['show_author'] === 'true' || $atts['show_title'] === 'true' || $atts['show_excerpt'] === 'true' || $atts['show_image'] === 'true' || $atts['show_price'] === 'true' || $atts['show_buy_button'] === 'true') {
	    $loopContentWrapper = true;
	}

	$book_container_classes = 'container-full';
	$book_list_row_classes = '';
	$thumbnail_wrapper_classes	= '';
	$content_wrapper_classes	= '';
	if ('left' === $atts['image_position']) {
		$book_list_row_classes = ' rswpbs-row mr-0 ml-0 align-items-center justify-content-between book-list-layout thumbnail-position-left';
		$book_container_classes = 'container';
		$thumbnail_wrapper_classes	= ' book-cover-column rswpbs-col-md-6 rswpbs-col-lg-5 rswpbs-col-xl-4 pr-4 pl-0';
		$content_wrapper_classes	= ' book-content-column rswpbs-col-md-6 rswpbs-col-lg-7 rswpbs-col-xl-8';
		if ('featured' === $atts['slider_style']) {
			$thumbnail_wrapper_classes	= ' book-cover-column rswpbs-col-md-6 rswpbs-col-lg-5 rswpbs-col-xl-5 pr-5 pl-0';
			$content_wrapper_classes	= ' book-content-column rswpbs-col-md-6 rswpbs-col-lg-7 rswpbs-col-xl-7';
		}
	}elseif ('right' === $atts['image_position']) {
		$book_list_row_classes = ' rswpbs-row mr-0 ml-0 align-items-center justify-content-between flex-row-reverse book-list-layout thumbnail-position-right';
		$book_container_classes = 'container';
		$thumbnail_wrapper_classes	= ' book-cover-column rswpbs-col-md-6 rswpbs-col-lg-5 rswpbs-col-xl-4 pr-0 pl-4 text-right';
		$content_wrapper_classes	= ' book-content-column rswpbs-col-md-6 rswpbs-col-lg-7 rswpbs-col-xl-8';
		if ('featured' === $atts['slider_style']) {
			$thumbnail_wrapper_classes	= ' book-cover-column rswpbs-col-md-6 rswpbs-col-lg-5 rswpbs-col-xl-5 pr-0 pl-5 text-right';
			$content_wrapper_classes	= ' book-content-column rswpbs-col-md-6 rswpbs-col-lg-7 rswpbs-col-xl-7';
		}
	}elseif ('top' === $atts['image_position']) {
		$book_container_classes = 'container-full';
		$thumbnail_wrapper_classes	= ' thumbnail-position-top';
	}

	$getSlider_attributes = array(
	    'lscreen' => $atts['sts_l_screen'],
	    'mscreen' => $atts['sts_m_screen'],
	    'sscreen' => $atts['sts_s_screen'],
	);
	$sliderAttr = '';
	if ('featured' !== $atts['slider_style']) {
		foreach ($getSlider_attributes as $key => $value) {
		    $sliderAttr .= ' data-' . $key . '="' . esc_attr($value) . '"';
		}
	}
	$bookQuery = new WP_Query($booksQargs);
	$activateSlider = '';
	if ($bookQuery->post_count > 1) {
	    $activateSlider = ' book-slider-activate row';
	}
    ?>
    <div class="rswpbs-book-slider <?php echo esc_attr($sliderItemClasess_string);?>">
    	<div class="container">
    		<div class="rswpbs-book-slider__slider-wrapper-row<?php echo esc_attr($activateSlider);?>" <?php echo $sliderAttr;?>>
    			<?php
    			if ($bookQuery->have_posts()) :
    				while( $bookQuery->have_posts() ) :
    					$bookQuery->the_post();
		    			?>
		    			<div class="rswpbs-book-slider__slider-item">
		    				<div class="rswpthemes-book-container<?php echo esc_attr($book_list_row_classes);?>">
								<?php
								if ('true' == $atts['show_image']) :
								?>
								<div class="rswpthemes-book-loop-image<?php echo esc_attr($thumbnail_wrapper_classes);?>">
									<a href="<?php the_permalink(); ?>"><?php
									if ('book_cover' == $atts['image_type']) :
										echo wp_kses_post(rswpbs_get_book_image(get_the_ID()));
									else:
										if (class_exists('Rswpbs_Pro')) {
											$bookMockup = get_field('mockup_image');
											?>
											<img src="<?php echo esc_url($bookMockup);?>" alt="<?php echo esc_attr(get_the_title());?>">
											<?php
										}
									endif;
									?></a>
								</div>
								<?php
								endif;
								if (true === $loopContentWrapper) :
								?>
								<div class="rswpthemes-book-loop-content-wrapper<?php echo esc_attr($content_wrapper_classes);?>">
									<?php
									if ('true' == $atts['show_title']):
									?>
									<h2 class="book-title">
										<a href="<?php the_permalink(); ?>"><?php
											if ('book_name' == $atts['title_type']) :
											 echo esc_html(rswpbs_get_book_name(get_the_ID()));
											else:
												echo get_the_title(get_the_ID());
											endif;
											?>
										</a>
									</h2>
									<?php
									endif;
									if ('true' == $atts['show_author']) :
									?>
									<h4 class="book-author"><strong><?php echo rswpbs_static_text_by(); ?></strong>
										<?php
										echo wp_kses_post(rswpbs_get_book_author(get_the_ID()));
										?>
									</h4>
									<?php
									endif;
									if ('true' == $atts['show_price']) :
									?>
									<div class="book-price d-flex">
										<?php echo wp_kses_post(rswpbs_get_book_price(get_the_ID())); ?>
									</div>
									<?php endif;
									if ('true' == $atts['show_excerpt']) :
										if ('excerpt' == $atts['excerpt_type'] && !empty(rswpbs_get_book_desc())) :
										?>
										<div class="book-desc d-flex">
									      <p><?php echo wp_kses_post(rswpbs_get_book_desc(get_the_ID(), intval($atts['excerpt_limit']))); ?></p>
									    </div>
									    <?php
										elseif('fullcontent' == $atts['excerpt_type']):
											?>
											<div class="book-full-content-as-excerpt">
												<?php the_content(); ?>
											</div>
											<?php
										endif;
									endif;
									?>
									<div class="rswpbs-book-buttons-wrapper">
									<?php
									if (function_exists('rswpthemes_cptwoointegration') && 'true' == $atts['show_add_to_cart_btn']) :
								    	$product_id = get_the_ID();
								    	?>
								    	<div class="book-add-to-cart-btn">
								    		<div class="cptwoointegration-cart-btn-wrapper">
										        <form class="cart" method="post">
										            <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
										            <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>" class="loop_add_to_cart_button button alt">
										                <?php esc_html_e('Add to Cart', 'text-domain'); ?>
										            </button>
										        </form>
										    </div>
								    	</div>
								    	<?php
									endif;
									if ('true' == $atts['show_buy_button']) :
								    ?>
								    <div class="book-buy-btn d-flex">
								      <?php echo wp_kses_post(rswpbs_get_book_buy_btn()); ?>
								    </div>
								    <?php
									endif;
									if ('true' == $atts['show_read_more_button']) :
								    ?>
								    <div class="rswpbs-loop-read-more-button">
								    	<a href="<?php the_permalink();?>"><?php echo rswpbs_static_text_read_more();?></a>
								    </div>
								    <?php
									endif;
									?>
									</div>
									<?php
									if (class_exists('Rswpbs_Pro') && 'true' == $atts['show_msl']) :
								    ?>
								    <div class="book-multiple-sales-links d-flex">
								      <?php echo rswpbs_pro_book_also_available_web_list(get_the_ID(), $atts['msl_title_align']); ?>
								    </div>
								    <?php
									endif;
								    ?>
								</div>
								<?php
								endif;
								?>
							</div>
		    			</div>
		    			<?php
    				endwhile;
    			endif;
    			?>
    		</div>
    	</div>
    </div>
    <?php
    wp_reset_query();
   return ob_get_clean();
}
