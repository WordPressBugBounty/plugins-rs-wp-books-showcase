<?php
/**
 * Check if a URL is an Amazon URL and lacks an affiliate tag.
 *
 * @param string $url The URL to check.
 * @return bool True if it's an Amazon URL without an affiliate tag, false otherwise.
 */
function rswpbs_is_amazon_url_without_tag($url) {
    if (empty($url)) {
        return false;
    }

    $amazon_domains = array(
        'amazon.com',
        'amzn.to',
        'amazon.co.uk',
        'amazon.ca',
        'amazon.in',
        // Add more regional domains as needed
    );

    $parsed_url = parse_url($url, PHP_URL_HOST);
    if (!$parsed_url) {
        return false;
    }

    $is_amazon = false;
    foreach ($amazon_domains as $domain) {
        if (stripos($parsed_url, $domain) !== false) {
            $is_amazon = true;
            break;
        }
    }

    if (!$is_amazon) {
        return false;
    }

    $query = parse_url($url, PHP_URL_QUERY);
    if (!$query) {
        return true; // No query string, so no tag
    }

    parse_str($query, $params);
    return !isset($params['tag']) || empty($params['tag']);
}


/**
 * Scan all books for Amazon URLs missing affiliate tags, with caching.
 *
 * @return array An array with the count and list of affected book IDs.
 */
function rswpbs_scan_books_for_missing_amazon_tags() {
    $transient_key = 'rswpbs_missing_amazon_tags';
    $cached_result = get_transient($transient_key);

    if ($cached_result !== false) {
        return $cached_result; // Return cached result if available
    }

    $args = array(
        'post_type' => 'book',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $book_ids = get_posts($args);
    $books_without_tags = array();
    $count = 0;

    foreach ($book_ids as $book_id) {
        $has_missing_tag = false;

        $buy_btn_link = get_post_meta($book_id, '_rsbs_buy_btn_link', true);
        if (rswpbs_is_amazon_url_without_tag($buy_btn_link)) {
            $has_missing_tag = true;
        }

        $website_list = get_post_meta($book_id, 'rswpbs_also_available_website_list', true);
        if (!empty($website_list) && is_array($website_list)) {
            foreach ($website_list as $website) {
                $book_url = isset($website['book_url']) ? $website['book_url'] : '';
                if (rswpbs_is_amazon_url_without_tag($book_url)) {
                    $has_missing_tag = true;
                    break;
                }
            }
        }

        $book_formats = get_post_meta($book_id, 'rswpbs_book_formats', true);
        if (!empty($book_formats) && is_array($book_formats)) {
            foreach ($book_formats as $format) {
                $format_link = isset($format['link']) ? $format['link'] : '';
                if (rswpbs_is_amazon_url_without_tag($format_link)) {
                    $has_missing_tag = true;
                    break;
                }
            }
        }

        if ($has_missing_tag) {
            $books_without_tags[] = $book_id;
            $count++;
        }
    }

    $result = array(
        'count' => $count,
        'book_ids' => $books_without_tags,
    );

    set_transient($transient_key, $result, 24 * HOUR_IN_SECONDS);
    return $result;
}

/**
 * Invalidate the transient when a book is saved.
 */
function rswpbs_invalidate_amazon_tag_transient($post_id) {
    if (get_post_type($post_id) !== 'book') {
        return;
    }

    delete_transient('rswpbs_missing_amazon_tags');
}
add_action('save_post', 'rswpbs_invalidate_amazon_tag_transient');

/**
 * Invalidate the transient when the affiliate tag is updated.
 */
function rswpbs_invalidate_transient_on_settings_update($option, $old_value, $new_value) {
    if ($option === 'rswpbs_amazon_affiliate_tag' && $old_value !== $new_value) {
        delete_transient('rswpbs_missing_amazon_tags');
    }
}
add_action('update_option', 'rswpbs_invalidate_transient_on_settings_update', 10, 3);
