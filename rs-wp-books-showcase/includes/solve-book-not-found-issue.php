<?php
function rswpbs_check_book_single_page_404() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Skip if already fixed (using a transient to avoid repeated checks)
    if (get_transient('rswpbs_permalink_fixed')) {
        return;
    }

    global $wpdb;

    // Find a published book post
    $book_id = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_status = %s LIMIT 1",
        'book',
        'publish'
    ));

    if (!$book_id) {
        return; // No book found, no need to check
    }

    $book_url = get_permalink($book_id);
    if (!$book_url) {
        return; // If permalink is empty, exit
    }

    $response = wp_remote_get(esc_url_raw($book_url));
    if (is_wp_error($response)) {
        return; // Skip if network error
    }

    $status_code = wp_remote_retrieve_response_code($response);
    if ($status_code == 404) {
        // Set permalink structure to /%postname%/
        update_option('permalink_structure', '/%postname%/');

        // Flush rewrite rules
        flush_rewrite_rules(true);

        // Set a transient to prevent repeated checks for 20 minutes
        set_transient('rswpbs_permalink_fixed', true, 20 * MINUTE_IN_SECONDS);

        // Show success notice
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>✅ <?php echo esc_html__('Fixed!', 'rswpbs'); ?></strong>
                <?php echo esc_html__('We noticed your single book pages weren’t working (404 error), so we updated your permalink structure to "Post Name" and fixed it for you!', 'rswpbs'); ?>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'rswpbs_check_book_single_page_404');