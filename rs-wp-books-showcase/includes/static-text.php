<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Retrieve static text dynamically using get_option().
 *
 * @param string $option_name The option key for the static text.
 * @param string $default The default text value.
 * @return string The translated or custom static text.
 */
function rswpbs_get_static_text( $option_name, $default ) {
    // Get the stored option or return the default value.
    $text = get_option( $option_name, $default );

    // Apply WordPress localization function.
    return __( $text, 'rswpbs' );
}

/**
 * Predefined list of static texts with their corresponding function names.
 */
$static_texts = [
    'rswpbs_text_by' => ['rswpbs_static_text_by', 'By'],
    'rswpbs_text_books' => ['rswpbs_static_text_books', 'Books'],
    'rswpbs_text_books_by' => ['rswpbs_static_text_books_by', 'Books By'],
    'rswpbs_text_view_book' => ['rswpbs_static_text_read_more', 'View Book'],
    'rswpbs_text_load_more' => ['rswpbs_static_text_load_more', 'Load More'],
    'rswpbs_text_add_to_cart' => ['rswpbs_static_text_add_to_cart', 'Add To Cart'],
    'rswpbs_text_price' => ['rswpbs_static_text_price', 'Price:'],
    'rswpbs_text_also_available_on' => ['rswpbs_static_text_also_available_on', 'Also Available At'],
    'rswpbs_text_all_formats_editions' => ['rswpbs_static_text_all_formats_and_editions', 'All Formats & Editions'],
    'rswpbs_text_availability' => ['rswpbs_static_text_availability', 'Availability'],
    'rswpbs_text_original_title' => ['rswpbs_static_text_original_title', 'Original Title'],
    'rswpbs_text_categories' => ['rswpbs_static_text_categories', 'Categories'],
    'rswpbs_text_publish_date' => ['rswpbs_static_text_publish_date', 'Publish Date'],
    'rswpbs_text_published_year' => ['rswpbs_static_text_published_year', 'Published Year'],
    'rswpbs_text_publisher_name' => ['rswpbs_static_text_publisher_name', 'Publisher Name'],
    'rswpbs_text_total_pages' => ['rswpbs_static_text_total_pages', 'Total Pages'],
    'rswpbs_text_isbn' => ['rswpbs_static_text_isbn', 'ISBN'],
    'rswpbs_text_isbn_10' => ['rswpbs_static_text_isbn_10', 'ISBN 10'],
    'rswpbs_text_isbn_13' => ['rswpbs_static_text_isbn_13', 'ISBN 13'],
    'rswpbs_text_asin' => ['rswpbs_static_text_asin', 'ASIN'],
    'rswpbs_text_country' => ['rswpbs_static_text_country', 'Country'],
    'rswpbs_text_translator' => ['rswpbs_static_text_translator', 'Translator'],
    'rswpbs_text_language' => ['rswpbs_static_text_language', 'Language'],
    'rswpbs_text_format' => ['rswpbs_static_text_format', 'Format'],
    'rswpbs_text_dimension' => ['rswpbs_static_text_dimension', 'Dimension'],
    'rswpbs_text_weight' => ['rswpbs_static_text_weight', 'Weight'],
    'rswpbs_text_average_ratings' => ['rswpbs_static_text_avarage_ratings', 'Average Ratings'],
    'rswpbs_text_reading_date' => ['rswpbs_static_text_reading_date', 'Reading Date'],
    'rswpbs_text_file_size' => ['rswpbs_static_text_file_size', 'File Size'],
    'rswpbs_text_file_format' => ['rswpbs_static_text_file_format', 'File Format'],
    'rswpbs_text_simultaneous_device_usage' => ['rswpbs_static_text_simultaneous_device_usage', 'Simultaneous Device Usage'],
    'rswpbs_text_text_to_speech' => ['rswpbs_static_text_text_to_speech', 'Text-To-Speech'],
    'rswpbs_text_screen_reader' => ['rswpbs_static_text_screen_reader', 'Screen Reader'],
    'rswpbs_text_enhanced_typesetting' => ['rswpbs_static_text_enhanced_typesetting', 'Enhanced Typesetting'],
    'rswpbs_text_x_ray' => ['rswpbs_static_text_x_ray', 'X-Ray'],
    'rswpbs_text_word_wise' => ['rswpbs_static_text_word_wise', 'Word Wise'],
    'rswpbs_text_sticky_notes' => ['rswpbs_static_text_sticky_notes', 'Sticky Notes'],
    'rswpbs_text_print_length' => ['rswpbs_static_text_print_length', 'Print Length'],
    'rswpbs_text_book_name' => ['rswpbs_static_text_book_name', 'Book Name'],
    'rswpbs_text_all_authors' => ['rswpbs_static_text_all_authors', 'All Authors'],
    'rswpbs_text_all_publishers' => ['rswpbs_static_text_all_publishers', 'All Publishers'],
    'rswpbs_text_all_categories' => ['rswpbs_static_text_all_categories', 'All Categories'],
    'rswpbs_text_all_series' => ['rswpbs_static_text_all_series', 'All Series'],
    'rswpbs_text_series' => ['rswpbs_static_text_series', 'Series'],
    'rswpbs_text_all_formats' => ['rswpbs_static_text_all_formats', 'All Formats'],
    'rswpbs_text_all_years' => ['rswpbs_static_text_all_years', 'All Years'],
    'rswpbs_text_search' => ['rswpbs_static_text_search', 'Search'],
    'rswpbs_text_not_allowed_for_review' => ['rswpbs_static_text_not_allowed_for_review', 'You are not allowed to submit a review. Please'],
    'rswpbs_text_submit_your_review' => ['rswpbs_static_text_submit_your_review', 'Submit Your Review'],
    'rswpbs_text_log_in' => ['rswpbs_static_text_log_in', 'Log In'],
    'rswpbs_text_review_title' => ['rswpbs_static_text_review_title', 'Review Title:'],
    'rswpbs_text_full_name' => ['rswpbs_static_text_full_name', 'Full Name:'],
    'rswpbs_text_email_address' => ['rswpbs_static_text_email_address', 'Email Address:'],
    'rswpbs_text_rating' => ['rswpbs_static_text_rating', 'Rating:'],
    'rswpbs_text_review' => ['rswpbs_static_text_review', 'Review:'],
    'rswpbs_text_submit' => ['rswpbs_static_text_submit', 'Submit'],
    'rswpbs_text_readers_feedback' => ['rswpbs_static_text_readers_feedback', 'Readers Feedback'],
];

/**
 * Generate functions dynamically based on the list.
 */
foreach ( $static_texts as $option_key => $data ) {
    list( $function_name, $default_text ) = $data;

    eval("
        function {$function_name}() {
            return rswpbs_get_static_text('{$option_key}', '{$default_text}');
        }
    ");
}
