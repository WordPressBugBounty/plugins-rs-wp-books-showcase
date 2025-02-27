<?php
// Add a submenu page under "Books" post type
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=book', // Parent menu (Books post type)
        'Import Books From CSV', // Page title
        'Import Books From CSV', // Menu title
        'manage_options', // Capability
        'import-books-from-csv', // Menu slug
        'rswpbs_import_books_from_csv_page' // Callback function
    );
});

// Render the settings page
function rswpbs_import_books_from_csv_page() {
    ?>
    <div class="wrap">
        <h1>Import Books From CSV</h1>
        <?php
        // Show the settings tabs
        rswp_book_showcase_settings_tabs('import_books_from_csv');

        // Show admin notices if books are imported
        if (isset($_GET['imported'])) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($_GET['imported'] . ' books have been imported successfully!') . '</p></div>';
        }
        ?>
        <div class="rswpbs-csv-upload-container <?php echo (!rswpbs_is_pro_active()) ? 'free-version' : ''; ?>">
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php
                // Check if the Pro version is active
                if (!rswpbs_is_pro_active()) {
                    echo '<div class="rswpbs-csv-pro-message"><p>🚀 This feature is available only in <a href="'.esc_url('https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/').'" target="_blank">RS WP Book Showcase Pro</a>. Upgrade to unlock CSV Import.</p></div>';
                }
                ?>
                <p id="selected_csv_file_name" class="rswpbs-file-name">No file selected</p>
                <input type="hidden" name="action" value="rswpbs_import_books">
                <input type="hidden" id="rswpbs_upload_csv_file" name="rswpbs_upload_csv_file">
                <div class="rswpbs-upload-section">
                    <button type="button" class="button button-primary" id="upload_csv_button" <?php echo (!rswpbs_is_pro_active()) ? 'disabled' : ''; ?>>
                        <span class="dashicons dashicons-upload"></span> Select CSV File
                    </button>
                    <button type="submit" class="button button-secondary rswpbs-import-button" <?php echo (!rswpbs_is_pro_active()) ? 'disabled' : ''; ?>>
                        <span class="dashicons dashicons-migrate"></span> Import Books
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

// Register settings
add_action('admin_init', function() {
    register_setting('import_books_csv_settings', 'upload_csv_file');

    add_settings_section(
        'import_books_csv_section',
        'Upload CSV File',
        null,
        'import-books-from-csv'
    );

    add_settings_field(
        'upload_csv_file',
        'Choose CSV File',
        'rswpbs_upload_csv_file_field',
        'import-books-from-csv',
        'import_books_csv_section'
    );
});

// Render the file upload input field

// Render Media Uploader Button
function rswpbs_upload_csv_file_field() {
    // Retrieve the file URL from the option.
    $file_url = get_option('rswpbs_upload_csv_file', '');
    ?>
    <p id="selected_csv_file_name" style="margin-top:10px;"></p>
    <input type="hidden" name="rswpbs_upload_csv_file" id="rswpbs_upload_csv_file" value="<?php echo esc_attr($file_url); ?>" />
    <button type="button" class="button" id="upload_csv_button">Select CSV File</button>
    <?php
}

// Enqueue WordPress Media Uploader
add_action('admin_enqueue_scripts', function($hook) {
    // var_dump($hook);
    // Load only on the "Import Books From CSV" page
    if ($hook !== 'book_page_import-books-from-csv') {
        return;
    }
    wp_enqueue_media(); // Load the media uploader script
    wp_enqueue_script('rswpbs-csv-file-uploader', RSWPBS_PLUGIN_URL . 'includes/import-books-from-csv/rswpbs-media-uploader.js', ['jquery'], null, true);
});
