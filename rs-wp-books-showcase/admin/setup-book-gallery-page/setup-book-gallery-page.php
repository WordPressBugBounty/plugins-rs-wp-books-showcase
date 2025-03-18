<?php
// Enqueue AJAX script
add_action('admin_enqueue_scripts', 'rswpbs_enqueue_admin_scripts');
function rswpbs_enqueue_admin_scripts($hook) {
    wp_enqueue_script('rswpbs-setup-book-gallery', RSWPBS_PLUGIN_URL . '/admin/setup-book-gallery-page/setup-book-gallery-page.js', array('jquery'), null, true);

    wp_localize_script('rswpbs-setup-book-gallery', 'rswpbs_setup_book_gallery', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('rswpbs_nonce')
    ));
}

// Hook to update status when a page is created or deleted
add_action('wp_insert_post', 'rswpbs_update_books_page_status');
add_action('before_delete_post', 'rswpbs_update_books_page_status');
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

function rswpbs_ajax_setup_book_gallery() {
    check_ajax_referer('rswpbs_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Oops! It looks like you need admin powers to do this. Please check with your site administrator!', 'rswpbs')));
    }

    global $wpdb;

    // Check if "Books" page already exists
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

    // Create the "Books" page if it doesn’t exist
    if (!$existing_page_id) {
        $page_id = wp_insert_post(array(
            'post_title'    => 'Books',
            'post_name'     => 'books',
            'post_content'  => '<!-- wp:rswpbs/book-block /-->',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ));

        if (!$page_id) {
            wp_send_json_error(array('message' => __('Oh no! Something went wrong while creating your Book Gallery page. Could you try again?', 'rswpbs')));
        }
        update_option('rswpbs_book_gallery_page_id', $page_id);
        rswpbs_update_books_page_status();
    } else {
        $page_id = $existing_page_id;
    }

    // Add the "Books" page to all menus
    $menus = wp_get_nav_menus(); // Get all navigation menus
    foreach ($menus as $menu) {
        $menu_id = $menu->term_id;
        // Check if the page is already in the menu to avoid duplicates
        $menu_items = wp_get_nav_menu_items($menu_id);
        $already_in_menu = false;
        foreach ($menu_items as $item) {
            if ($item->object_id == $page_id && $item->object == 'page') {
                $already_in_menu = true;
                break;
            }
        }
        if (!$already_in_menu) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title'     => 'Books',
                'menu-item-object-id' => $page_id,
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
            ));
        }
    }

    // Check for existing books
    $book_count = $wpdb->get_var(
        "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'book' AND post_status = 'publish'"
    );

    $page_url = get_permalink($page_id);
    $page_title = get_the_title($page_id);

    if ($book_count > 0) {
        // Books exist, prompt user to add more
        wp_send_json_success(array(
            'message' => sprintf(__('Great news! We found %d books on your site—your gallery is already taking shape! Want to add more to make it even better?', 'rswpbs'), $book_count),
            'page_url' => $page_url,
            'page_title' => $page_title,
            'books_found' => true,
            'book_count' => $book_count
        ));
    } else {
        // No books found, automatically import from JSON
        $json_url = RSWPBS_PLUGIN_URL . 'admin/assets/json/books.json'; // Replace with your JSON URL
        $import_result = rswpbs_free_import_books_from_url($json_url);

        if (is_wp_error($import_result)) {
            wp_send_json_error(array('message' => __('Hmm, we hit a snag importing books: ', 'rswpbs') . $import_result->get_error_message() . __(' Let’s try that again soon!', 'rswpbs')));
        } else {
            wp_send_json_success(array(
                'message' => sprintf(__('No books yet? No worries! We’ve added %d books to kickstart your amazing gallery!', 'rswpbs'), $import_result),
                'page_url' => $page_url,
                'page_title' => $page_title,
                'books_found' => false
            ));
        }
    }
}
add_action('wp_ajax_rswpbs_setup_book_gallery_page', 'rswpbs_ajax_setup_book_gallery');

add_action('admin_notices', 'rswpbs_book_archive_not_available_notice');
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
        <p><?php
            printf(
                esc_html__('Thank you for activating the %s plugin! 🎉 We’re excited to help you showcase your books. Setting up a stunning book gallery is super easy—just click the Setup Book Gallery button below, and we’ll handle everything for you!', 'rswpbs'),
                '<strong>' . esc_html__('RS WP Book Showcase', 'rswpbs') . '</strong>'
            );
        ?></p>
        <p>
            <button id="rswpbs-create-page" class="button button-primary">
                📖 <?php echo esc_html__('Setup Book Gallery', 'rswpbs'); ?>
            </button>
        </p>
    </div>
    <?php
}

function rswpbs_ajax_import_more_books() {
    check_ajax_referer('rswpbs_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'rswpbs')));
    }

    $json_url = RSWPBS_PLUGIN_URL . 'admin/assets/json/books.json'; // Replace with your JSON URL
    $import_result = rswpbs_free_import_books_from_url($json_url);

    if (is_wp_error($import_result)) {
        wp_send_json_error(array('message' => __('Failed to import books: ', 'rswpbs') . $import_result->get_error_message()));
    } else {
        wp_send_json_success(array(
            'import_count' => $import_result,
            'message' => sprintf(__('Successfully imported %d more books!', 'rswpbs'), $import_result)
        ));
    }
}
add_action('wp_ajax_rswpbs_import_more_books', 'rswpbs_ajax_import_more_books');