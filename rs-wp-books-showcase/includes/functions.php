<?php
/**
 * All Fields Date Of Book Post Type
 */

function rswpbs_prefix(){
	return '_rsbs_';
}
function rswpbs_get_book_desc($bookId = null, $word_count = 30) {
    if ($bookId === null) {
        $bookId = get_the_ID();
    }
    if (is_singular('book')) {
        $word_count = 100;
    }
    $short_description = get_post_meta($bookId, rswpbs_prefix() . 'short_description', true);

    // If not on the single book page, strip HTML tags
    if (!is_singular('book')) {
        $short_description = strip_tags($short_description);
    }

    // Get an array of words from the description
    $words = explode(' ', $short_description);
    // Trim the array to the specified word count
    $trimmed_words = array_slice($words, 0, $word_count);
    // Join the trimmed words back into a string
    $trimmed_description = implode(' ', $trimmed_words);

    // Wrap the trimmed description in a <p> tag
    return wp_kses_post('<p>' . $trimmed_description . '</p>');
}

function rswpbs_get_single_book_excerpt($bookId = null) {
    // Default to current post ID if none provided
    if ($bookId === null) {
        $bookId = get_the_ID();
    }

    // Check if "Show Excerpt" is enabled
    $show_excerpt = get_option('rswpbs_show_excerpt_on_single_page', 1); // Default to 1 (enabled)
    if (!$show_excerpt) {
        return ''; // Return empty string if excerpt is disabled
    }

    // Get excerpt settings
    $excerpt_type = get_option('rswpbs_single_page_excerpt_type', 'excerpt'); // Corrected option name to match settings
    $excerpt_limit = get_option('rswpbs_single_page_excerpt_limit', 150); // Corrected option name to match settings

    // Process the description based on excerpt type
    if ($excerpt_type === 'excerpt') {
        // Get the short description for excerpt type
        $short_description = get_post_meta($bookId, rswpbs_prefix() . 'short_description', true);
        if (empty($short_description)) {
            return ''; // Return empty if no description exists
        }

        // Trim to character limit
        $trimmed_description = substr($short_description, 0, $excerpt_limit);
        // Ensure we don't cut off in the middle of a word
        if (strlen($short_description) > $excerpt_limit) {
            $last_space = strrpos($trimmed_description, ' ');
            if ($last_space !== false) {
                $trimmed_description = substr($trimmed_description, 0, $last_space);
            }
            $trimmed_description .= '...'; // Add ellipsis if trimmed
        }
    } else {
        // Full content using get_the_content()
        $trimmed_description = get_the_content(null, false, $bookId);
        if (empty($trimmed_description)) {
            return ''; // Return empty if no content exists
        }
        // Apply content filters to process shortcodes, formatting, etc.
        $trimmed_description = apply_filters('the_content', $trimmed_description);
    }

    // Return the description wrapped in a <p> tag, allowing basic HTML
    return wp_kses_post($trimmed_description); // No need to manually add <p> since the_content may already include tags
}

function rswpbs_get_book_weight($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$book_weight = get_post_meta( $bookId, rswpbs_prefix() . 'book_weight', true );
	return $book_weight;
}

function rswpbs_get_book_dimension($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$book_dimension = get_post_meta( $bookId, rswpbs_prefix() . 'book_dimension', true );
	return $book_dimension;
}

function rswpbs_get_book_file_size($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$book_file_size = get_post_meta( $bookId, rswpbs_prefix() . 'book_file_size', true );
	return $book_file_size;
}

function rswpbs_get_book_file_format($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$book_file_format = get_post_meta($bookId, rswpbs_prefix() . 'book_file_format', true);
	if (!class_exists('Rswpbs_Pro')) {
		$book_file_format = '';
	}
	return $book_file_format;
}

function rswpbs_get_simultaneous_device_usage($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$simultaneous_device_usage = get_post_meta($bookId, rswpbs_prefix() . 'simultaneous_device_usage', true);
	if (!class_exists('Rswpbs_Pro')) {
		$simultaneous_device_usage = '';
	}
	return $simultaneous_device_usage;
}

function rswpbs_get_book_text_to_speech($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$book_text_to_speech = get_post_meta($bookId, rswpbs_prefix() . 'book_text_to_speech', true);
	if (!class_exists('Rswpbs_Pro')) {
		$book_text_to_speech = '';
	}elseif(class_exists('Rswpbs_Pro') && 'blank' == $book_text_to_speech){
		$book_text_to_speech = '';
	}elseif('enabled' == $book_text_to_speech) {
		$book_text_to_speech = __( 'Enabled', 'rswpbs' );;
	}elseif('not_enabled' == $book_text_to_speech) {
		$book_text_to_speech = __( 'Not Enabled', 'rswpbs' );;
	}
	return $book_text_to_speech;
}

function rswpbs_get_screen_reader($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$screen_reader = get_post_meta($bookId, rswpbs_prefix() . 'screen_reader', true);
	if (!class_exists('Rswpbs_Pro')) {
		$screen_reader = '';
	}elseif(class_exists('Rswpbs_Pro') && 'blank' == $screen_reader){
		$screen_reader = '';
	}elseif('enabled' == $screen_reader) {
		$screen_reader = __( 'Enabled', 'rswpbs' );;
	}elseif('not_enabled' == $screen_reader) {
		$screen_reader = __( 'Not Enabled', 'rswpbs' );;
	}
	return $screen_reader;
}

function rswpbs_get_enhanced_typesetting($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$enhanced_typesetting = get_post_meta($bookId, rswpbs_prefix() . 'enhanced_typesetting', true);
	if (!class_exists('Rswpbs_Pro')) {
		$enhanced_typesetting = '';
	}elseif(class_exists('Rswpbs_Pro') && 'blank' == $enhanced_typesetting){
		$enhanced_typesetting = '';
	}elseif('enabled' == $enhanced_typesetting) {
		$enhanced_typesetting = __( 'Enabled', 'rswpbs' );;
	}elseif('not_enabled' == $enhanced_typesetting) {
		$enhanced_typesetting = __( 'Not Enabled', 'rswpbs' );;
	}
	return $enhanced_typesetting;
}

function rswpbs_get_x_ray($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$x_ray = get_post_meta($bookId, rswpbs_prefix() . 'x_ray', true);
	if (!class_exists('Rswpbs_Pro')) {
		$x_ray = '';
	}elseif(class_exists('Rswpbs_Pro') && 'blank' == $x_ray){
		$x_ray = '';
	}elseif('enabled' == $x_ray) {
		$x_ray = __( 'Enabled', 'rswpbs' );;
	}elseif('not_enabled' == $x_ray) {
		$x_ray = __( 'Not Enabled', 'rswpbs' );;
	}
	return $x_ray;
}

function rswpbs_get_word_wise($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$word_wise = get_post_meta($bookId, rswpbs_prefix() . 'word_wise', true);
	if (!class_exists('Rswpbs_Pro')) {
		$word_wise = '';
	}elseif(class_exists('Rswpbs_Pro') && 'blank' == $word_wise){
		$word_wise = '';
	}elseif('enabled' == $word_wise) {
		$word_wise = __( 'Enabled', 'rswpbs' );;
	}elseif('not_enabled' == $word_wise) {
		$word_wise = __( 'Not Enabled', 'rswpbs' );;
	}
	return $word_wise;
}

function rswpbs_get_sticky_notes($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$sticky_notes = get_post_meta($bookId, rswpbs_prefix() . 'sticky_notes', true);
	if (!class_exists('Rswpbs_Pro')) {
		$sticky_notes = '';
	}
	return $sticky_notes;
}

function rswpbs_get_print_length($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$print_length = get_post_meta($bookId, rswpbs_prefix() . 'print_length', true);
	if (!class_exists('Rswpbs_Pro')) {
		$print_length = '';
	}
	return $print_length;
}

function rswpbs_get_book_translator($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$book_translator = get_post_meta( $bookId, rswpbs_prefix() . 'book_translator', true );
	return $book_translator;
}

function rswpbs_get_book_format($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$book_format = get_post_meta( $bookId, rswpbs_prefix() . 'book_format', true );
	return $book_format;
}

function rswpbs_get_book_name($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookName = get_post_meta( $bookId, rswpbs_prefix() . 'book_name', true );
	return $bookName;
}

function rswpbs_get_book_original_name($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$originalBookName = get_post_meta( $bookId, rswpbs_prefix() . 'original_book_name', true );
	if (!class_exists('Rswpbs_Pro')) {
		$originalBookName = '';
	}
	return $originalBookName;
}
function rswpbs_get_book_original_url($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$originalBookUrl = get_post_meta( $bookId, rswpbs_prefix() . 'original_book_url', true );
	if (!class_exists('Rswpbs_Pro')) {
		$originalBookUrl = '';
	}
	return $originalBookUrl;
}

function rswpbs_get_book_pages($bookId = null) {
    if ($bookId === null) {
        $bookId = get_the_ID();
    }
    $bookPages = get_post_meta($bookId, rswpbs_prefix() . 'book_pages', true);

    // If the value is empty, return 0 or null (depending on your preference)
    if (empty($bookPages)) {
        return 0; // Or return null if you prefer
    }

    // Extract only the numeric part
    // Remove any non-numeric characters except for digits
    $numericPages = preg_replace('/[^0-9]/', '', $bookPages);

    // Convert to an integer
    $numericPages = (int) $numericPages;

    // If the result is 0 (e.g., no numbers found), return 0 or null
    if ($numericPages === 0) {
        return 0; // Or return null if you prefer
    }

    return $numericPages;
}

function rswpbs_get_book_publish_date($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookPublishDate = get_post_meta( $bookId, rswpbs_prefix() . 'book_publish_date', true );
	return $bookPublishDate;
}

function rswpbs_get_book_publish_year($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookPublishYear = get_post_meta( $bookId, rswpbs_prefix() . 'book_publish_year', true );
	return $bookPublishYear;
}

function rswpbs_get_book_publisher_name($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookPublisherName = get_post_meta( $bookId, rswpbs_prefix() . 'book_publisher_name', true );
	return $bookPublisherName;
}

function rswpbs_get_book_country($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookcountry = get_post_meta( $bookId, rswpbs_prefix() . 'book_country', true );
	return $bookcountry;
}

function rswpbs_get_book_language($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookLanguage = get_post_meta( $bookId, rswpbs_prefix() . 'book_language', true );
	return $bookLanguage;
}

function rswpbs_get_book_isbn($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookIsbn = get_post_meta( $bookId, rswpbs_prefix() . 'book_isbn', true );
	return $bookIsbn;
}

function rswpbs_get_book_isbn_10($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookIsbn_10 = get_post_meta( $bookId, rswpbs_prefix() . 'book_isbn_10', true );
	return $bookIsbn_10;
}

function rswpbs_get_book_isbn_13($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookIsbn_13 = get_post_meta( $bookId, rswpbs_prefix() . 'book_isbn_13', true );
	return $bookIsbn_13;
}

function rswpbs_get_book_asin($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookAsin = get_post_meta( $bookId, rswpbs_prefix() . 'book_asin', true );
	return $bookAsin;
}

function rswpbs_get_book_author($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$aIndex = 0;
	$getBookAuthors = get_the_terms( $bookId, 'book-author' );
	if (is_array($getBookAuthors)) {
		$countAuthors = count($getBookAuthors);
	}
	$bookauthor = '';
	if (!empty($getBookAuthors)) :
		foreach($getBookAuthors as $author){
			$aIndex++;
			$bookauthor .= '<a href="'.esc_url(get_term_link($author->term_id)).'">'.esc_html($author->name).'</a>';
			if ($aIndex !== $countAuthors) {
				$bookauthor .= ', ';
			}
		}
	endif;
	return $bookauthor;
}

function rswpbs_get_book_author_id($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookAuthors = get_the_terms( $bookId, 'book-author' );
	$authorIndex = 0;
	$outline = 0;
	if (is_array($bookAuthors) && !empty($bookAuthors)) {
		foreach($bookAuthors as $author){
			$authorIndex++;
			if (1 === $authorIndex) {
				$outline = $author->term_id;
			}
		}
	}
	return $outline;
}

function rswpbs_get_book_author_ids($bookId = null) {
	if ($bookId === null) {
		$bookId = get_the_ID();
	}

	$bookAuthors = get_the_terms($bookId, 'book-author');
	$authorIds = [];

	if (is_array($bookAuthors) && !empty($bookAuthors)) {
		foreach ($bookAuthors as $author) {
			$authorIds[] = $author->term_id;
		}
	}

	return implode(',', $authorIds);
}


function rswpbs_get_book_categories($bookId = null, $sep = true) {
	if ($bookId === null) {
		$bookId = get_the_ID();
	}

	$bookCategories = get_the_terms($bookId, 'book-category');
	$cIndex = 0;

	$outline = '';

	if ($bookCategories && is_array($bookCategories)) { // Check if $bookCategories is not empty and is an array
		$countCats = count($bookCategories);

		foreach ($bookCategories as $category) {
			$cIndex++;
			$outline .= '<a href="'.esc_url(get_term_link($category->term_id)).'">'.esc_html($category->name).'</a>';

			if ($sep && $cIndex !== $countCats) {
				$outline .= ', '; // Added a comma separator except for the last item
			}
		}
	}

	return $outline;
}
function rswpbs_get_book_series_name($bookId = null, $sep = true) {
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookSeries = get_the_terms($bookId, 'book-series');
	$cIndex = 0;

	$outline = '';

	if ($bookSeries && is_array($bookSeries)) { // Check if $bookSeries is not empty and is an array
		foreach ($bookSeries as $series) {
			$outline = esc_html($series->name);
		}
	}
	return $outline;
}
function rswpbs_get_book_series_slug($bookId = null, $sep = true) {
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookSeries = get_the_terms($bookId, 'book-series');
	$cIndex = 0;

	$outline = '';

	if ($bookSeries && is_array($bookSeries)) { // Check if $bookSeries is not empty and is an array
		foreach ($bookSeries as $series) {
			$outline = esc_html($series->slug);
		}
	}
	return $outline;
}

function rswpbs_get_book_series($bookId = null, $sep = true) {
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookSeries = get_the_terms($bookId, 'book-series');
	$cIndex = 0;

	$outline = '';

	if ($bookSeries && is_array($bookSeries)) { // Check if $bookSeries is not empty and is an array
		foreach ($bookSeries as $series) {
			$outline .= '<a href="'.esc_url(get_term_link($series->term_id)).'">'.esc_html($series->name).'</a>';
		}
	}
	return $outline;
}

function rswpbs_get_book_image($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$bookImage = get_the_post_thumbnail($bookId, 'full');
	return $bookImage;
}

function rswpbs_get_book_price($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$currenySign = '$';
	if (class_exists('Rswpbs_Pro')) {
		$currenySign = get_option( 'rswpbs_price_currency', '$' );
		if (null === $currenySign) {
			$currenySign = '$';
		}
	}
	$bookRegularPrice = str_replace('$', '', get_post_meta( $bookId, rswpbs_prefix() . 'book_price', true ));
	$bookSalePrice = str_replace('$', '', get_post_meta( $bookId, rswpbs_prefix() . 'book_sale_price', true ));
	$bookPrice = '';
	if (!empty($bookRegularPrice)) {
		$bookPrice .= '<div class="regular-price'.(!empty($bookSalePrice) ? ' previous-price' : '').'">
						<strong>'.$currenySign.''.esc_html($bookRegularPrice).'</strong>
					</div>';
	}
	if (!empty($bookSalePrice)) {
		$bookPrice .= '<div class="sale-price"><strong>'.$currenySign.''.esc_html($bookSalePrice).'</strong></div>';
	}

	return $bookPrice;
}
function rswpbs_get_book_price_except_markup($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$currenySign = '$';
	if (class_exists('Rswpbs_Pro')) {
		$currenySign = get_option( 'rswpbs_price_currency', '$' );
	}
	$bookRegularPrice = str_replace('$', '', get_post_meta( $bookId, rswpbs_prefix() . 'book_price', true ));
	$bookSalePrice = str_replace('$', '', get_post_meta( $bookId, rswpbs_prefix() . 'book_sale_price', true ));
	if (empty($bookSalePrice)) {
		$bookPrice = $bookRegularPrice;
	}else{
		$bookPrice = $bookSalePrice;
	}
	return $bookPrice;
}

function rswpbs_get_book_buy_btn($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$buy_btn_text = get_post_meta( $bookId, rswpbs_prefix() . 'buy_btn_text', true );
	$buy_btn_link = get_post_meta( $bookId, rswpbs_prefix() . 'buy_btn_link', true );

	$amazonTrackingID = get_option( 'rswpbs_amazon_tracking_id', 'lft01-20' );

	$buy_btn_link = rswpbs_modify_amazon_url($buy_btn_link, $amazonTrackingID);

	$output = '';
	if (!empty($buy_btn_text)) :
		$output = '<a href="'.esc_url($buy_btn_link).'" target="_blank" class="rswpbs-book-buy-now-button">'.$buy_btn_text.'</a>';
	endif;
	return $output;
}

function rswpbs_get_book_buy_btn_shortcode($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$buy_btn_shortcode = get_post_meta( $bookId, rswpbs_prefix() . 'buy_btn_shortcode', true );
	$output = '';
	if (!empty($buy_btn_shortcode)) :
		$output = do_shortcode($buy_btn_shortcode);
	endif;
	return $output;
}

function rswpbs_get_book_availability_status($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$book_availability_status = get_post_meta( $bookId, rswpbs_prefix() . 'book_availability_status', true );
	return $book_availability_status;
}

function rswpbs_get_book_reading_date($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$book_reading_date = get_post_meta( $bookId, rswpbs_prefix() . 'book_reading_date', true );
	return $book_reading_date;
}

function rswpbs_get_avg_rate($bookId = null) {
    if ($bookId === null) {
        $bookId = get_the_ID();
    }
    $average_book_rating = get_post_meta($bookId, rswpbs_prefix() . 'average_book_rating', true);
    $html_output = '';

    if ('nan' !== $average_book_rating) {
        $totalRatings = get_post_meta($bookId, rswpbs_prefix() . 'total_book_ratings', true);
        $ratingLink = get_post_meta($bookId, rswpbs_prefix() . 'book_rating_links', true);

        // Use floatval to preserve decimal values
        $rounded_rating = round(floatval($average_book_rating) * 2) / 2;

        $html_output = '<div class="star-rating-inner">';
        for ($i = 1; $i <= 5; $i++) {
            if ($rounded_rating >= $i) {
                $html_output .= '<i class="fas fa-star"></i>';
            } elseif ($rounded_rating >= $i - 0.5) {
                $html_output .= '<i class="fas fa-star-half-alt"></i>';
            } else {
                $html_output .= '<i class="far fa-star"></i>';
            }
        }

        if (!empty($totalRatings)) {
            $html_output .= '<span class="total-ratings">' . esc_html__($totalRatings . ' ratings', 'text-domain') . '</span>';
        }

        $html_output .= '</div>';
        if ($ratingLink) {
            $html_output = '<a class="rating-link" href="' . esc_url($ratingLink) . '">' . $html_output . '</a>';
        }
    }

    return $html_output;
}


function rswpbs_ext_website_list($bookId = null, $extclass = 'website-list-container'){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	$external_website_lists = get_post_meta( $bookId, rswpbs_prefix() . 'external_website_lists', true );
	$outline = '<ul class="'.$extclass.'">';
	if($external_website_lists){
		foreach($external_website_lists as $external_website){
			$outline .= '<li><a target="_blank" href="'.esc_url($external_website['external_website_url']).'">'.esc_html( $external_website['external_website_name'] ).'</a></li>';
		}
	}
	$outline .= '</ul>';
	return $outline;
}

function rswpbs_author_profile_picture($bookId = null){
	if ($bookId === null) {
		$bookId = get_the_ID();
	}
	if ('book' === get_post_type() && is_singular( 'book' )) {
		$get_author_id = rswpbs_get_book_author_id(get_the_ID());
	}elseif(is_tax( 'book-author')){
		$get_author_id = get_queried_object_id();
	}
	$get_author = get_term($get_author_id);
	$authorImageId = get_term_meta($get_author->term_id, 'author_profile_picture', true );

	$outline = wp_get_attachment_image_url( $authorImageId, 'full' );
	return $outline;
}

function rswpbs_get_meta_data($meta_field_name){
	$metafielddata = array();
	$args = array(
		'post_type'	=>	'book',
		'numberposts'	=>	-1,
	);
	$booksQuery = get_posts( $args );
	foreach($booksQuery as $query) :
		$get_fields_data = get_post_meta($query->ID, rswpbs_prefix() . $meta_field_name, true );
		if (!in_array($get_fields_data, $metafielddata) && '' != $get_fields_data) {
			$metafielddata[] = strtolower($get_fields_data);
		}
	endforeach;
	return $metafielddata;
	wp_reset_query();
}

function rswpbs_navigation() {
	$next_icon            = __( 'Next', 'rswpbs' );;
	$prev_icon            = __( 'Prev', 'rswpbs' );;
	echo '<div class="rswpbs-pagination text-center">';
		the_posts_pagination(
			array(
				'mid_size'  => 2,
				'prev_text' => $prev_icon,
				'next_text' => $next_icon,
			)
		);
	echo '</div>';
}

// Custom pagination function
function rswpbs_ct_pagination($mainQuery, $paged){
    $total_pages = $mainQuery->max_num_pages;
    if ($total_pages > 1){
        $current_page = max(1, $paged);
        echo paginate_links(array(
            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format' => '/page/%#%', // Change 'paged' to 'page'
            'current' => $current_page,
            'total' => $total_pages,
            'prev_text'    => '«',
            'next_text'    => '»',
        ));
    }
}

if ( ! function_exists( 'rswpbs_ctp_pub_time' ) ) {
    function rswpbs_ctp_pub_time() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<span class="published-time"><time class="entry-date published" datetime="%1$s">%2$s</time></span>';
            $time_string .= '<span class="updated-time"><time class="updated" datetime="%3$s">%4$s</time></span>';
        } else {
            $time_string = '<span class="published-time"><time class="entry-date published updated" datetime="%1$s">%2$s</time></span>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( DATE_W3C ) ),
            esc_html( get_the_modified_date() )
        );

        echo '<i class="time-wrapper">' . wp_kses_post( $time_string ) . '</i>';
    }
}


function rswpbs_shorting_form_global($queryName, $bookPerPage, $search_form_displayed = true){
	global $post;
	$actionUrl = rswpbs_search_page_base_url();
	$search_fields = rswpbs_search_fields();
	?>
	<div class="rswpbs-sorting-sections-wrapper">
		<div class="rswpbs-row justify-content-between">
		  <div class="rswpbs-col-md-6 rswpbs-col-7 align-self-center">
		    <?php
		      echo wp_kses_post(rswpbs_total_books_message($queryName, $bookPerPage));
		    ?>
		  </div>
		  <div class="rswpbs-col-md-6 rswpbs-col-5 align-self-center">
		    <div class="rswpbs-books-sorting-field" id="rswpbs-books-sorting-field">
		      <?php
		      if (true == $search_form_displayed) {
		        ?>
		        <form action="<?php echo esc_url($actionUrl);?>" method="get" id="rswpbs-book-sort-form">
		          <input type="hidden" name="sortby" id="rswpbs-sortby" value="">
		        <?php
		      }
		      ?>
		      <select id="rswpbs-sort">
		          <option value="default"><?php esc_html_e('Default Sorting', 'rswpbs');?></option>
		          <option value="price_asc"<?php echo ($search_fields['sortby'] == 'price_asc' ? 'selected="selected"' : ''); ?>><?php esc_html_e( 'Price (Low to High)', 'rswpbs' );?></option>
		          <option value="price_desc"<?php echo ($search_fields['sortby'] == 'price_desc' ? 'selected="selected"' : ''); ?>><?php esc_html_e( 'Price (High to Low)', 'rswpbs' );?></option>
		          <option value="title_asc"<?php echo ($search_fields['sortby'] == 'title_asc' ? 'selected="selected"' : ''); ?>><?php esc_html_e( 'Title (A-Z)', 'rswpbs' );?></option>
		          <option value="title_desc"<?php echo ($search_fields['sortby'] == 'title_desc' ? 'selected="selected"' : ''); ?>><?php esc_html_e( 'Title (Z-A)', 'rswpbs' );?></option>
		          <option value="date_asc"<?php echo ($search_fields['sortby'] == 'date_asc' ? 'selected="selected"' : ''); ?>><?php esc_html_e( 'Date (Oldest to Newest)', 'rswpbs' );?></option>
		          <option value="date_desc"<?php echo ($search_fields['sortby'] == 'date_desc' ? 'selected="selected"' : ''); ?>><?php esc_html_e( 'Date (Newest to Oldest)', 'rswpbs' );?></option>
		      </select>
		      <?php
		      if (true == $search_form_displayed) {
		        ?>
		        </form>
		        <?php
		      }
		       ?>
		    </div>
		  </div>
		</div>
	</div>
<?php
}

function rswpbs_is_rswpthemes(){
    $getRswpThemesSlug = get_stylesheet();
    if ('author-portfolio' === $getRswpThemesSlug) {
        return true;
    }elseif ('author-portfolio-pro' === $getRswpThemesSlug) {
        return true;
    }elseif ('book-author-blog' === $getRswpThemesSlug) {
        return true;
    }elseif ('author-personal-blog' === $getRswpThemesSlug) {
        return true;
    }elseif ('writers-portfolio' === $getRswpThemesSlug) {
        return true;
    }else{
        return false;
    }
}

function is_rswpbs_page() {

    if (is_post_type_archive('book')) {
        return true;
    }
    if (is_tax(array('book-category', 'book-author','book-series'))) {
        return true;
    }
    if (is_singular('book')) {
        return true;
    }


    return false;
}
function has_rswpbs_shortcodes(){
	global $post;
	if ( (is_page() || is_single() || is_singular() ) && has_shortcode( $post->post_content, 'rswpbs_book_gallery' ) ) {
    	return true;
    }
    return false;
}

function rswpbs_is_block_editor(){
	if (is_admin()) {
		global $current_screen;
		$current_screen = get_current_screen();
		if ( is_admin() && method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor() ) {
		    return true;
		}
	}
	return false;
}

function rswpbs_is_woocommerce_pages(){
    if (class_exists('WooCommerce')) {
    	if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        	return true;
    	}else{
    		return false;
    	}
    }else{
    	return false;
    }
}

function rswpbs_search_page_base_url() {
    global $post;
    $baseUrl = ''; // Default empty value

    // 🚀 If we're inside a page, use its own URL as the base
    if (is_page()) {
        $baseUrl = get_permalink($post->ID);
    }

    // 🚀 If Rswpbs_Pro is active, check the saved book search page
    if (class_exists('Rswpbs_Pro')) {
        $getActionPageUrl = get_option('rswpbs_book_search_page');
        if (!empty($getActionPageUrl) && null != $getActionPageUrl && 'default' !== $getActionPageUrl) {
            $baseUrl = get_permalink($getActionPageUrl);
        }
    }

    // 🚀 Ensure fallback to a manually created Books page
    if (empty($baseUrl)) {
        $book_page = get_page_by_path('books'); // Check for "Books" page first
        if (!$book_page) {
            $book_page = get_page_by_path('book'); // Check for "Book" page
        }
        if ($book_page) {
            $baseUrl = get_permalink($book_page->ID);
        }
    }

    // 🚀 Last fallback: If all else fails, use home URL
    if (empty($baseUrl)) {
        $baseUrl = home_url('/');
    }

    return $baseUrl;
}

function rswpbs_book_filtering_menu_category(){
	$allCategories = get_terms( array(
		'taxonomy'	=> 'book-category',
		'hide_empty'	=> true,
	) );
	if (!empty($allCategories)) {
		foreach ($allCategories as $category) {
			?>
			<a class="catItem" href="<?php echo esc_url(get_term_link($category->term_id));?>"><?php echo esc_html( $category->name );?></a>
			<?php
		}
	}
}

function rswpbs_get_excerpt($limit, $content) {
    // Check if content length is less than the limit
    if (strlen($content) <= $limit) {
        return $content; // Return full content
    }

    // If content length is greater than the limit, return excerpt
    $excerpt = preg_replace('/\[.*?\]/', '', $content);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, $limit);
    $excerpt = substr($excerpt, 0, strripos($excerpt, ' '));
    $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
    return $excerpt;
}

function rswpbs_short_and_long_content($charactersCount = 250) {
    $content = get_the_content(); // Get the full content
    $excerpt = rswpbs_get_excerpt($charactersCount, $content); // Get the excerpt
    ?>
    <div class="review-description feedback-text-container">
        <div class="review-short-content">
            <?php
            echo esc_html($excerpt);
            ?>
            <?php if (strlen($content) > $charactersCount) : ?>
                <a href="#" class="rswpbs-testimonial-read-more">Read More</a>
            <?php endif; ?>
        </div>
        <div class="review-full-content" style="display:none;">
            <?php the_content(); ?>
            <a href="#" class="rswpbs-testimonial-show-less">Show Less</a>
        </div>
    </div>
    <?php
}

function rswpbs_book_mockup_image($bookId = null){
	if ($bookId === null) {
        $bookId = get_the_ID();
    }
	$bookMockup = get_field('mockup_image', $bookId);
	?>
	<img src="<?php echo esc_url($bookMockup);?>" alt="<?php echo esc_attr(get_the_title());?>">
	<?php
}
