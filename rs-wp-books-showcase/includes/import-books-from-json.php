<?php
/**
 * Import Books from a given JSON URL, skipping duplicates based on title.
 *
 * @param string $json_file_url The URL of the JSON file.
 * @return int|WP_Error         The number of imported books, or WP_Error on failure.
 */
if (function_exists('rswpbs_free_import_books_from_url')) {
    return;
}
function rswpbs_free_import_books_from_url($json_file_url) {
    // Fetch the JSON file
    $response = wp_remote_get($json_file_url);
    if (is_wp_error($response)) {
        return new WP_Error(
            'fetch_error',
            'Unable to retrieve the JSON file. Error: ' . $response->get_error_message()
        );
    }

    // Decode JSON
    $json_input = wp_remote_retrieve_body($response);
    $books = json_decode($json_input, true);

    // Validate JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        return new WP_Error('invalid_json', 'Invalid JSON. Please check your file.');
    }
    if (empty($books) || !is_array($books)) {
        return new WP_Error('empty_data', 'No book data found in the JSON.');
    }

    $import_count = 0;
    global $wpdb;

    // Loop through each book
    foreach ($books as $book) {
        // --- Sanitize & assign main fields ---
        $book_title = !empty($book['title']) ? sanitize_text_field($book['title']) : '';
        if (empty($book_title)) {
            continue; // Skip if no title
        }

        // Check if a book with this title already exists
        $existing_book = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'book' AND post_status = 'publish'",
                $book_title
            )
        );

        if ($existing_book) {
            // Skip this book if it already exists
            continue;
        }

        $book_author = !empty($book['author']) ? sanitize_text_field($book['author']) : '';
        $book_description = !empty($book['description']) ? wp_kses_post($book['description']) : '';
        $book_url = !empty($book['url']) ? esc_url_raw($book['url']) : '';
        $book_cover_image = !empty($book['coverImage']) ? esc_url_raw($book['coverImage']) : '';
        $bookPrice = !empty($book['price']) ? sanitize_text_field($book['price']) : '';

        // Remove escaped quotes
        $book_description = preg_replace('/\\\"/', '"', $book_description);
        // Keep safe HTML
        $book_description = wp_kses_post($book_description);

        // Create the Book post
        $post_data = array(
            'post_title'   => $book_title,
            'post_content' => $book_description,
            'post_status'  => 'publish',
            'post_type'    => 'book',
        );
        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            // Log error and skip this book
            error_log("Error creating post: " . $post_id->get_error_message());
            continue;
        }

        // Set Featured Image if available
        if (!empty($book_cover_image) && function_exists('rswpbs_set_featured_image_from_url')) {
            rswpbs_set_featured_image_from_url($post_id, $book_cover_image);
        }

        // Set Book Author taxonomy
        if (!empty($book_author)) {
            $bookAuthors = array_map('trim', explode(',', $book_author));
            foreach ($bookAuthors as $authorName) {
                $authorName = strtolower($authorName); // Convert to lowercase
                $term = term_exists($authorName, 'book-author');
                if (!$term) {
                    $term = wp_insert_term($authorName, 'book-author');
                }
                if (!is_wp_error($term) && isset($term['term_id'])) {
                    wp_set_post_terms($post_id, $term['term_id'], 'book-author', true);
                }
            }
        }

        // Set Book Category taxonomy from JSON "category" field
        if (!empty($book['category'])) {
            $category_str = $book['category'];
            $categories = array_map('trim', explode('>', $category_str));
            if (!empty($categories) && strtolower($categories[0]) === 'books') {
                array_shift($categories);
            }
            $parent_term_id = 0;
            $term_ids = array();
            foreach ($categories as $cat_name) {
                if (empty($cat_name)) {
                    continue;
                }
                $cat_name = strtolower($cat_name); // Convert to lowercase
                $term = term_exists($cat_name, 'book-category', $parent_term_id);
                if (!$term) {
                    $term = wp_insert_term($cat_name, 'book-category', array('parent' => $parent_term_id));
                }
                if (!is_wp_error($term) && isset($term['term_id'])) {
                    $parent_term_id = $term['term_id'];
                    $term_ids[] = $term['term_id'];
                }
            }
            if (!empty($term_ids)) {
                wp_set_post_terms($post_id, $term_ids, 'book-category', true);
            }
        }

        // Prepare meta fields
        $rswpbsMetaFeidls = array();

        // Book name
        if (!empty($book_title)) {
            $rswpbsMetaFeidls['_rsbs_book_name'] = $book_title;
        }

        // Check for "details" array
        if (!empty($book['details']) && is_array($book['details'])) {
            $details = $book['details'];

            // Example map
            $details_map = array(
                'ASIN'         => '_rsbs_book_asin',
                'Publisher'    => '_rsbs_book_publisher_name',
                'Language'     => '_rsbs_book_language',
                'Paperback'    => '_rsbs_book_pages',
                'ISBN-13'      => '_rsbs_book_isbn_13',
                'Dimensions'   => '_rsbs_book_dimension',
                'Item Weight'  => '_rsbs_book_weight',
                'File size'    => '_rsbs_book_file_size',
                'Print length' => '_rsbs_print_length',
                'Publication date'  => '_rsbs_book_publish_date',
                'ISBN-10'      => '_rsbs_book_isbn_10',
                'Simultaneous device usage' => '_rsbs_simultaneous_device_usage',
                'Reading age'  => '_rsbs_book_reading_age',
                'Lexile measure' => '_rsbs_book_lexile_measure',
                'Grade level'  => '_rsbs_book_grade_level',
            );

            $select_options = array(
                'Text-to-Speech'      => '_rsbs_book_text_to_speech',
                'Screen Reader'        => '_rsbs_screen_reader',
                'Enhanced typesetting' => '_rsbs_enhanced_typesetting',
                'X-Ray'                => '_rsbs_x_ray',
                'Word Wise'            => '_rsbs_word_wise',
            );

            // Handle checkbox or "yes/no" type fields
            foreach ($select_options as $json_key => $meta_key) {
                if (isset($details[$json_key])) {
                    $value = strtolower($details[$json_key]);
                    $value = str_replace(' ', '_', $value);
                    $rswpbsMetaFeidls[$meta_key] = sanitize_text_field($value);
                }
            }

            // Handle direct mapping
            foreach ($details_map as $json_key => $meta_key) {
                if (!empty($details[$json_key])) {
                    $rswpbsMetaFeidls[$meta_key] = sanitize_text_field($details[$json_key]);
                }
            }

            // Check if "Paperback" or "Hardcover" exists
            if (isset($details['Paperback'])) {
                $rswpbsMetaFeidls['_rsbs_book_format'] = 'Paperback';
                $pagesString = $details['Paperback'];
                $pages = preg_replace('/\D/', '', $pagesString);
                if (!empty($pages)) {
                    $rswpbsMetaFeidls['_rsbs_book_pages'] = $pages;
                }
            }

            if (isset($details['Hardcover'])) {
                $rswpbsMetaFeidls['_rsbs_book_format'] = 'Hardcover';
                $pagesString = $details['Hardcover'];
                $pages = preg_replace('/\D/', '', $pagesString);
                if (!empty($pages)) {
                    $rswpbsMetaFeidls['_rsbs_book_pages'] = $pages;
                }
            }

            // Extract date from Publisher if it has parentheses
            if (!empty($details['Publisher'])) {
                $publisher = $details['Publisher'];
                $pattern = '/\((.*?)\)/';
                if (preg_match($pattern, $publisher, $matches)) {
                    $dateString = trim($matches[1]);
                    try {
                        $dateObj = new DateTime($dateString);
                        $iso_date = $dateObj->format('Y-m-d');
                        $rswpbsMetaFeidls['_rsbs_book_publish_date'] = $iso_date;
                    } catch (Exception $e) {
                        // Handle invalid date
                    }
                    // Remove parentheses to get the publisher name
                    $publisherName = trim(preg_replace($pattern, '', $publisher));
                } else {
                    $publisherName = $publisher;
                }
                $rswpbsMetaFeidls['_rsbs_book_publisher_name'] = sanitize_text_field($publisherName);
            }
        }

        // Process variations and merge into book_formats
        $bookFormats = array();
        if (isset($book['variations']) && is_array($book['variations'])) {
            foreach ($book['variations'] as $variation) {
                if (!empty($variation['format']) || !empty($variation['price']) || !empty($variation['url'])) {
                    $bookFormats[] = array(
                        'format_image' => '',
                        'name'  => sanitize_text_field(isset($variation['format']) ? $variation['format'] : ''),
                        'price' => sanitize_text_field(isset($variation['price']) ? $variation['price'] : ''),
                        'link'  => esc_url_raw(isset($variation['url']) ? $variation['url'] : ''),
                    );
                }
            }
        }

        // Update or delete post meta
        if (!empty($bookFormats)) {
            update_post_meta($post_id, 'rswpbs_book_formats', $bookFormats);
        } else {
            delete_post_meta($post_id, 'rswpbs_book_formats');
        }
        // Buy button link & price
        if (!empty($book_url)) {
            $rswpbsMetaFeidls['_rsbs_buy_btn_link'] = $book_url;
        }
        if (!empty($bookPrice)) {
            $rswpbsMetaFeidls['_rsbs_book_price'] = str_replace('$', '', $bookPrice);
        }

        // Default buy button text
        $rswpbsMetaFeidls['_rsbs_buy_btn_text'] = 'Buy Now';

        // Update meta fields
        foreach ($rswpbsMetaFeidls as $meta_key => $meta_value) {
            update_post_meta($post_id, $meta_key, $meta_value);
        }

        $import_count++;
    }

    // Return the number of books imported
    return $import_count;
}