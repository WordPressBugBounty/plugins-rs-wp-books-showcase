<?php
// Enqueue AJAX script
function rswpbs_enqueue_admin_scripts($hook) {
    wp_enqueue_script('rswpbs-setup-book-gallery', RSWPBS_PLUGIN_URL . '/admin/setup-book-gallery-page/setup-book-gallery-page.js', array('jquery'), null, true);

    wp_localize_script('rswpbs-setup-book-gallery', 'rswpbs_setup_book_gallery', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('rswpbs_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'rswpbs_enqueue_admin_scripts');

// Function to check if "Books" page exists and update option
function rswpbs_update_books_page_status() {
    global $wpdb;

    // Check if a page named "Books" or "Book" exists
    $existing_page_id = $wpdb->get_var(
        "SELECT ID FROM $wpdb->posts WHERE post_name IN ('books', 'book') AND post_type = 'page' AND post_status IN ('publish', 'draft', 'trash')"
    );

    if ($existing_page_id) {
        update_option('rswpbs_books_page_exists', true);
    } else {
        update_option('rswpbs_books_page_exists', false);
    }
}

// Hook to update status when a page is created or deleted
add_action('wp_insert_post', 'rswpbs_update_books_page_status');
add_action('before_delete_post', 'rswpbs_update_books_page_status');

// Handle AJAX request to create Book Gallery page
function rswpbs_ajax_setup_book_gallery() {
    check_ajax_referer('rswpbs_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'rswpbs')));
    }

    // Check if page already exists
    global $wpdb;
    $existing_page_id = $wpdb->get_var(
        "SELECT ID FROM $wpdb->posts WHERE post_name IN ('books', 'book') AND post_type = 'page' AND post_status IN ('publish', 'draft', 'trash')"
    );

    if ($existing_page_id) {
        $page_status = get_post_status($existing_page_id);

        if ($page_status === 'trash') {
            wp_delete_post($existing_page_id, true); // Force delete
            $existing_page_id = null;
        }
    }

    // Create new page if needed
    if (!$existing_page_id) {
        $page_id = wp_insert_post(array(
            'post_title'    => 'Books',
            'post_name'     => 'books',
            'post_content'  => '<!-- wp:rswpbs/book-block /-->',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ));

        if ($page_id) {
            update_option('rswpbs_book_gallery_page_id', $page_id);

            // ✅ Update page existence status
            rswpbs_update_books_page_status();

            wp_send_json_success(array(
                'message' => __('The Book Gallery page has been created successfully.', 'rswpbs'),
                'page_url' => get_permalink($page_id),
                'page_title' => get_the_title($page_id),
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to create the Book Gallery page.', 'rswpbs')));
        }
    } else {
        rswpbs_update_books_page_status();

        wp_send_json_success(array(
            'message' => __('A Book Gallery page already exists.', 'rswpbs'),
            'page_url' => get_permalink($existing_page_id),
            'page_title' => get_the_title($existing_page_id),
        ));
    }
}
add_action('wp_ajax_rswpbs_setup_book_gallery_page', 'rswpbs_ajax_setup_book_gallery');

// Show Admin Notice about Book Archive Removal (Only if "Books" page is missing)
function rswpbs_book_archive_not_available_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if "Books" page exists
    $books_page_exists = get_option('rswpbs_books_page_exists', false);
    if ($books_page_exists) {
        return; // Hide notice if the page exists
    }

    ?>
    <div id="rswpbs-setup-books-page-notice" class="notice notice-warning">
        <h3><strong>🚨 <?php echo esc_html__('Action Required! Important Notice:', 'rswpbs'); ?></strong></h3>
        <p><?php echo esc_html__('The book archive page (previously available at', 'rswpbs'); ?>
            <code>/books/</code> <?php echo esc_html__('is no longer accessible due to recent plugin updates.', 'rswpbs'); ?>
        </p>
        <p><?php echo esc_html__('To continue showcasing your books, let us create a new', 'rswpbs'); ?>
            <strong><?php echo esc_html__('Books', 'rswpbs'); ?></strong>
            <?php echo esc_html__('page for you with just one click!', 'rswpbs'); ?>
        </p>
        <p>
            <button id="rswpbs-create-page" class="button button-primary">
                📖 <?php echo esc_html__('Create Books Page', 'rswpbs'); ?>
            </button>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'rswpbs_book_archive_not_available_notice');

// Show Admin Notice if Single Book Page is 404
function rswpbs_check_book_single_page_404() {
    if (!current_user_can('manage_options')) {
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

    if (!is_wp_error($response)) {
        $status_code = wp_remote_retrieve_response_code($response);
        if ($status_code == 404) {
            ?>
            <div class="notice notice-error">
                <p><strong>🚨 <?php echo esc_html__('Action Required!:', 'rswpbs'); ?></strong>
                    <?php echo esc_html__('Your single book pages are not accessible (404 Not Found).', 'rswpbs'); ?>
                </p>
                <p>
                    <?php echo esc_html__('To fix this, go to', 'rswpbs'); ?>
                    <a href="<?php echo esc_url(admin_url('options-permalink.php')); ?>" target="_blank">
                        <strong><?php echo esc_html__('Permalink Settings', 'rswpbs'); ?></strong>
                    </a>
                    <?php echo esc_html__('and click', 'rswpbs'); ?>
                    <strong><?php echo esc_html__('Save Changes', 'rswpbs'); ?></strong>
                    <?php echo esc_html__('without making any changes.', 'rswpbs'); ?>
                </p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'rswpbs_check_book_single_page_404');
