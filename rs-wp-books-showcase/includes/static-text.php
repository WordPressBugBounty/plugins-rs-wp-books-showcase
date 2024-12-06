<?php
function rswpbs_static_text_by(){
	$text = __( 'By:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_by', 'option')) {
			$text = get_field('text_by', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_books(){
	$text = __( 'Books', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_books', 'option')) {
			$text = get_field('text_books', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_price(){
	$text = __( 'Price:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_price', 'option')) {
			$text = get_field('text_price', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_all_formats_and_editions(){
	$text = __( 'All Formats & Editions:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_all_formats_&_editions', 'option')) {
			$text = get_field('text_all_formats_&_editions', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_also_available_on(){
	$text = __( 'Also Available At:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_also_available_on', 'option')) {
			$text = get_field('text_also_available_on', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_availability(){
	$text = __( 'Availability:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_availability', 'option')) {
			$text = get_field('text_availability', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_original_title(){
	$text = __( 'Original Title:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_original_title', 'option')) {
			$text = get_field('text_original_title', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_categories(){
	$text = __( 'Categories:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_categories', 'option')) {
			$text = get_field('text_categories', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_publish_date(){
	$text = __( 'Publish Date:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_publish_date', 'option')) {
			$text = get_field('text_publish_date', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_published_year(){
	$text = __( 'Published Year:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_published_year', 'option')) {
			$text = get_field('text_published_year', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_publisher_name(){
	$text = __( 'Publisher Name:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_publisher_name', 'option')) {
			$text = get_field('text_publisher_name', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_total_pages(){
	$text = __( 'Total Pages:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_total_pages', 'option')) {
			$text = get_field('text_total_pages', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_isbn(){
	$text = __( 'ISBN:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_isbn', 'option')) {
			$text = get_field('text_isbn', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_isbn_10(){
	$text = __( 'ISBN 10:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_isbn_10', 'option')) {
			$text = get_field('text_isbn_10', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_isbn_13(){
	$text = __( 'ISBN 13:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_isbn_13', 'option')) {
			$text = get_field('text_isbn_13', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_asin(){
	$text = __( 'ASIN:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_asin', 'option')) {
			$text = get_field('text_asin', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_country(){
	$text = __( 'Country:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_country', 'option')) {
			$text = get_field('text_country', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_translator(){
	$text = __( 'Translator:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_translator', 'option')) {
			$text = get_field('text_translator', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_language(){
	$text = __( 'Language:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_language', 'option')) {
			$text = get_field('text_language', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_format(){
	$text = __( 'Format:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_format', 'option')) {
			$text = get_field('text_format', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_dimension(){
	$text = __( 'Dimension:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_dimension', 'option')) {
			$text = get_field('text_dimension', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_weight(){
	$text = __( 'Weight:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_weight', 'option')) {
			$text = get_field('text_weight', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_avarage_ratings(){
	$text = __( 'Avarage Ratings:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_avarage_ratings', 'option')) {
			$text = get_field('text_avarage_ratings', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_file_size(){
	$text = __( 'File Size:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_file_size', 'option')) {
			$text = get_field('text_file_size', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_file_format(){
	$text = __( 'File Format:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_file_format', 'option')) {
			$text = get_field('text_file_format', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_simultaneous_device_usage(){
	$text = __( 'Simultaneous Device Usages:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_simultaneous_device_usage', 'option')) {
			$text = get_field('text_simultaneous_device_usage', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_text_to_speech(){
	$text = __( 'Text-To-Speech:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_text-to-speech', 'option')) {
			$text = get_field('text_text-to-speech', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_screen_reader(){
	$text = __( 'Screen Reader:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_screen_reader', 'option')) {
			$text = get_field('text_screen_reader', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_enhanced_typesetting(){
	$text = __( 'Enhanced Typesetting:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_enhanced_typesetting', 'option')) {
			$text = get_field('text_enhanced_typesetting', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_x_ray(){
	$text = __( 'X-Ray:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_x-ray', 'option')) {
			$text = get_field('text_x-ray', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_word_wise(){
	$text = __( 'Word Wise:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_word_wise', 'option')) {
			$text = get_field('text_word_wise', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_sticky_notes(){
	$text = __( 'Sticky Notes:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_sticky_notes', 'option')) {
			$text = get_field('text_sticky_notes', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_print_length(){
	$text = __( 'Print length:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_print_length', 'option')) {
			$text = get_field('text_print_length', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_book_name(){
	$text = __( 'Book Name', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_book_name', 'option')) {
			$text = get_field('text_book_name', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_all_authors(){
	$text = __( 'All Authors', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_all_authors', 'option')) {
			$text = get_field('text_all_authors', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_all_publishers(){
	$text = __( 'All Publishers', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_all_publishers', 'option')) {
			$text = get_field('text_all_publishers', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_all_categories(){
	$text = __( 'All Categories', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_all_categories', 'option')) {
			$text = get_field('text_all_categories', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_all_series(){
	$text = __( 'All Series', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_all_series', 'option')) {
			$text = get_field('text_all_series', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_series(){
	$text = __( 'Series', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_series', 'option')) {
			$text = get_field('text_series', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_all_formats(){
	$text = __( 'All Formats', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_all_formats', 'option')) {
			$text = get_field('text_all_formats', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_all_years(){
	$text = __( 'All Years', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_all_years', 'option')) {
			$text = get_field('text_all_years', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_search(){
	$text = __( 'Search', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		if (null !== get_field('text_search', 'option')) {
			$text = get_field('text_search', 'option');
		}
	}
	return $text;
}
function rswpbs_static_text_not_allowed_for_review(){
	$text = __( 'You are not allowed to submit review. please', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_not_allowed_for_review', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}
function rswpbs_static_text_submit_your_review(){
	$text = __( 'Submit Your Review', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_submit_your_review', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}
function rswpbs_static_text_log_in(){
	$text = __( 'Log In', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_log_in', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}
function rswpbs_static_text_review_title(){
	$text = __( 'Review Title:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_review_title', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}
function rswpbs_static_text_full_name(){
	$text = __( 'Full Name:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_full_name', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}
function rswpbs_static_text_email_address(){
	$text = __( 'Email Address:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_email_address', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}
function rswpbs_static_text_rating(){
	$text = __( 'Rating:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_rating', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}
function rswpbs_static_text_review(){
	$text = __( 'Review:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_review', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}
function rswpbs_static_text_submit(){
	$text = __( 'Submit:', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_submit', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}
function rswpbs_static_text_readers_feedback(){
	$text = __( 'Readers Feedback', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_readers_feedback', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}

function rswpbs_static_text_books_by(){
	$text = __( 'Books By', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_books_by', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}

function rswpbs_static_text_read_more(){
	$text = __( 'View Book', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_view_book', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}

function rswpbs_static_text_load_more(){
	$text = __( 'Load More', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_load_more', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}

function rswpbs_static_text_add_to_cart(){
	$text = __( 'Add To Cart', RSWPBS_TEXT_DOMAIN );
	if (class_exists('Rswpbs_Pro')) {
		$getDymaniText = get_field('text_add_to_cart', 'option');
		if (null !== $getDymaniText) {
			$text = $getDymaniText;
		}
	}
	return $text;
}

