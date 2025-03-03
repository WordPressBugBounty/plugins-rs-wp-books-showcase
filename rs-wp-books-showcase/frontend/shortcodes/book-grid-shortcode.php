<?php
add_shortcode( 'rswpbs_book_gallery', 'rswpbs_books_showcase_grid_layout' );
function rswpbs_books_showcase_grid_layout( $atts ) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'layout'	=> 'default',
			'show_search_form'	=> 'true',
			'show_sorting_form'	=> 'true',
			'filtering_menu'	=> 'false',
			'categories_include' => '',
	        'categories_exclude' => '',
	        'authors_include' => '',
	        'authors_exclude' => '',
	        'series_include' => '',
	        'series_exclude' => '',
	        'exclude_books' => '',
	        'book_offset' => '',
	        'order' => '',
	        'orderby' => '',
			'show_image' => 'true',
			'image_position' => 'top',
			'image_type' => 'book_cover',
			'show_title' => 'true',
			'title_type' => 'book_name',
			'show_author' => 'true',
			'show_price' => 'true',
			'show_buy_button'	=> 'true',
	        'show_add_to_cart_btn' => 'false',
			'show_excerpt'	=> 'true',
			'excerpt_limit'	=> '60',
			'books_per_page'	=> '8',
			'books_per_row'	=> '3',
			'show_pagination' => 'true',
	        'show_msl' => 'true',
	        'msl_title_align' => 'center',
	        'show_masonry_layout' => 'true',
	        'content_align' => 'center',
	        'show_read_more_button' => 'false',
	        'search_fields_col' => '4',
	        'height_stretch' => 'true',
		),$atts
	);
	ob_start();

	$paged = rswpbs_paged();
	$bookPerPage = 8;
	if (!empty($atts['books_per_page'])) {
		$bookPerPage = intval($atts['books_per_page']);
	}

	$bookQueryArgs = array(
		'post_type'	=> 'book',
		'posts_per_page' => $bookPerPage,
		'paged'	=> $paged,
	);
	if (!empty($atts['orderby'])) {
		$bookQueryArgs['orderby'] = $atts['orderby'];
	}
	if (!empty($atts['order'])) {
		$bookQueryArgs['order'] = $atts['order'];
	}
	if (!empty($atts['exclude_books'])) {
		$booksIds = array_map('intval', explode(',' , $atts['exclude_books']));
		$bookQueryArgs['post__not_in'] = $booksIds;
	}
	$includeCategory = false;
	if (!empty($atts['categories_include']) && $atts['categories_include'] !== 'false' && $atts['categories_include'] !== 'true') {
		$includeCategory = true;
	}
	if (true === $includeCategory) {
		$includeBookCategories = array_map('intval', explode(',' , $atts['categories_include']));
		$bookQueryArgs['tax_query'] = array(
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
		$bookQueryArgs['tax_query'] = array(
	        array(
	            'taxonomy' => 'book-author',
	            'field'    => 'term_id',
	            'terms'    => $includeBookAuthors,
	        ),
	    );
	}
	$includeSeries = false;
	if (!empty($atts['series_include']) && $atts['series_include'] !== 'false' && $atts['series_include'] !== 'true') {
		$includeSeries = true;
	}
	if (true === $includeSeries) {
		$includeBookSeries = array_map('intval', explode(',' , $atts['series_include']));
		$bookQueryArgs['tax_query'] = array(
	        array(
	            'taxonomy' => 'book-series',
	            'field'    => 'term_id',
	            'terms'    => $includeBookSeries,
	        ),
	    );
	}
	if (true === $includeCategory && true === $includeAuthors && true === $includeSeries) {
		$bookQueryArgs['tax_query'] = array(
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
	        array(
	            'taxonomy' => 'book-series',
	            'field'    => 'term_id',
	            'terms'    => $includeBookSeries,
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
	$excludeSeries = false;
	if (!empty($atts['series_exclude']) && $atts['series_exclude'] !== 'false' && $atts['series_exclude'] !== 'true') {
		$excludeSeries = true;
	}
	if (true === $excludeCategories || true === $excludeAuthors || true === $excludeSeries) {
		$excludeBookCategories = array_map('intval', explode(',' , $atts['categories_exclude']));
		$excludeBookAuthors = array_map('intval', explode(',' , $atts['authors_exclude']));
		$excludeBookSeries = array_map('intval', explode(',' , $atts['series_exclude']));
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
	    if (true === $excludeSeries) {
	        $exclude_tax_query[] = array(
	            'taxonomy' => 'book-series',
	            'field'    => 'term_id',
	            'terms'    => $excludeBookSeries,
	            'operator' => 'NOT IN',
	        );
	    }
	    $bookQueryArgs['tax_query']['relation'] = 'AND';
		$bookQueryArgs['tax_query'] = array_merge($bookQueryArgs['tax_query'], $exclude_tax_query);
	}

	if (!empty($atts['book_offset'])) {
		$bookQueryArgs['offset'] = $atts['book_offset'];
	}

	if (isset($_GET['search_type']) && 'book' === $_GET['search_type']) {
	    $bookQueryArgs = array_merge( $bookQueryArgs, rswpbs_search_query_args() );
	    $bookQueryArgs = array_merge( $bookQueryArgs, rswpbs_sorting_form_args() );
	}elseif (isset($_GET['sortby']) && 'default' != $_GET['sortby']) {
		$bookQueryArgs = array_merge( $bookQueryArgs, rswpbs_sorting_form_args() );
	}

	$book_container_classes = '';
	$thumbnail_wrapper_classes	= '';
	$content_wrapper_classes	= '';
	if ('left' === $atts['image_position']) {
		$book_container_classes = ' rswpbs-row mr-0 ml-0 book-list-layout thumbnail-position-left';
		$thumbnail_wrapper_classes	= ' book-cover-column rswpbs-col-md-6 rswpbs-col-lg-4 pl-0 pr-0 pr-md-4 pr-lg-4 pr-xl-4';
		$content_wrapper_classes	= ' book-content-column rswpbs-col-md-6 rswpbs-col-lg-8';
	}elseif ('right' === $atts['image_position']) {
		$book_container_classes = ' rswpbs-row flex-row-reverse mr-0 ml-0 book-list-layout thumbnail-position-right';
		$thumbnail_wrapper_classes	= ' book-cover-column rswpbs-col-md-6 rswpbs-col-lg-4 pl-0 pl-xl-4 pl-lg-4 pl-md-4 pr-0 text-right';
		$content_wrapper_classes	= ' book-content-column rswpbs-col-md-6 rswpbs-col-lg-8';
	}elseif ('top' === $atts['image_position']) {
		$thumbnail_wrapper_classes	= ' thumbnail-position-top';
		$book_container_classes = ' book-grid-layout';
	}

	$contentAlign = ' content-align-center';
	if ($atts['content_align'] == 'left') {
		$contentAlign = ' content-align-left';
	}elseif ($atts['content_align'] == 'right') {
		$contentAlign = ' content-align-right';
	}elseif ($atts['content_align'] == 'center') {
		$contentAlign = ' content-align-center';
	}

	$booksPerRow = $atts['books_per_row'];
	$bookColumnClases = 'rswpbs-col-lg-3 rswpbs-col-md-4 book-single-column';
	if ('1' == $booksPerRow) {
		$bookColumnClases = 'rswpbs-col-lg-12 book-single-column';
	}elseif('2' == $booksPerRow){
		$bookColumnClases = 'rswpbs-col-md-6 book-single-column';
	}elseif('3' == $booksPerRow){
		$bookColumnClases = 'rswpbs-col-lg-6 rswpbs-col-xl-4 rswpbs-col-md-6 book-single-column';
	}elseif('4' == $booksPerRow){
		$bookColumnClases = 'rswpbs-col-lg-4 rswpbs-col-xl-3 rswpbs-col-md-6 book-single-column';
	}elseif('6' == $booksPerRow){
		$bookColumnClases = 'rswpbs-col-lg-3 rswpbs-col-xl-2 rswpbs-col-md-4 book-single-column';
	}
	$heightStretch = $atts['height_stretch'];
	if ('true' == $heightStretch) {
		$bookColumnClases .= ' align-self-stretch book-col-have-margin';
	}

	$sectionClasses = array();
	if ( ('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '1' == $booksPerRow ) {
		$sectionClasses[] = ' book-gallery-list-layout-1-col';
	}elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '2' == $booksPerRow) {
		$sectionClasses[] = ' book-gallery-list-layout-2-col';
		$bookColumnClases = 'rswpbs-col-md-12 rswpbs-col-lg-6 book-single-column';
	}elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '3' == $booksPerRow) {
		$sectionClasses[] = ' book-gallery-list-layout-3-col';
	}elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '4' == $booksPerRow) {
		$sectionClasses[] = ' book-gallery-list-layout-4-col';
	}elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '5' == $booksPerRow) {
		$sectionClasses[] = ' book-gallery-list-layout-5-col';
	}elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '6' == $booksPerRow) {
		$sectionClasses[] = ' book-gallery-list-layout-6-col';
	}
	$sectionClasses[] = ' ' . $atts['layout'];

	$sectionClasses = implode(' ', $sectionClasses);

	$content_wrapper_classes .= $contentAlign;

	$wrapperRowClass = '';
	if ( class_exists('Rswpbs_Pro') && 'true' == $atts['show_masonry_layout'] && 'false' == $heightStretch) {
		$wrapperRowClass = ' masonry_layout_active_for_books';
	}

	$booksQuery = new WP_Query($bookQueryArgs);

	$display_sorting_form_wrapper = true;
	if ('true' == $atts['show_search_form']) :
		$display_sorting_form_wrapper = false;
		$search_fields_col = $atts['search_fields_col'];
	?>
	<div class="rswpbs-advanced-search-form-area">
		<?php echo do_shortcode("[rswpbs_advanced_search fields_col=\"$search_fields_col\"]"); ?>
	</div>
	<?php
	endif;
	if ('true' == $atts['show_sorting_form']) :
	?>
	<div class="rswpbs-books-shorting-form-area">
		<?php rswpbs_shorting_form_global($booksQuery, $bookPerPage, $display_sorting_form_wrapper); ?>
	</div>
	<?php
	endif;
	if ('category' == $atts['filtering_menu']) :
	?>
	<div class="rswpbs-books-filtering-menu-area">
		<?php echo rswpbs_book_filtering_menu_category(); ?>
	</div>
	<?php
	endif;

	$layoutArgs = array(
		'showBookImage' => $atts['show_image'],
		'bookImageType' => $atts['image_type'],
		'showBookTitle' => $atts['show_title'],
		'bookTitleType' => $atts['title_type'],
		'showBookAuthor' => $atts['show_author'],
		'showBookPrice' => $atts['show_price'],
		'showBookExcerpt' => $atts['show_excerpt'],
		'excerptLimit' => $atts['excerpt_limit'],
		'showBookBuyBtn' => $atts['show_buy_button'],
		'showMsl' => $atts['show_msl'],
		'mslTitleAlign' => $atts['msl_title_align'],
		'bookColumnClases' => $bookColumnClases,
		'book_container_classes' => $book_container_classes,
		'thumbnail_wrapper_classes' => $thumbnail_wrapper_classes,
		'content_wrapper_classes' => $content_wrapper_classes,
		'show_read_more_button' => $atts['show_read_more_button'],
		'heightStretch' => $atts['height_stretch'],
		'showAddToCartBtn' => $atts['show_add_to_cart_btn'],
	);
	?>
	<div class="rswpbs-books-showcase-area<?php echo esc_attr($sectionClasses);?>">
		<!-- Start Books Loop Container -->
		<div class="rswpbs-books-showcase-book-loop-container">
			<div class="rswpbs-row<?php echo esc_attr($wrapperRowClass);?>">
				<?php
				if (is_rswpbs_page()) :
					if (have_posts())  :
						while(have_posts()) :
							the_post();
							echo rswpbs_loop_layout($layoutArgs);
						endwhile;
					endif;
				else:
					if ($booksQuery->have_posts())  :
						while($booksQuery->have_posts()) :
							$booksQuery->the_post();
							echo rswpbs_loop_layout($layoutArgs);
						endwhile;
					endif;
				endif;
				?>
			</div>
			<?php
			if ('true' == $atts['show_pagination']) :
			?>
			<div class="rswpbs-row">
				<div class="rswpbs-col-md-12">
					<div class="rswpbs-books-pagination">
						<?php
						if (is_rswpbs_page()) {
							rswpbs_navigation();
						}else{
						 	rswpbs_ct_pagination($booksQuery, $paged);
						}
						?>
					</div>
				</div>
			</div>
			<?php
			endif;
			?>
		</div>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}
