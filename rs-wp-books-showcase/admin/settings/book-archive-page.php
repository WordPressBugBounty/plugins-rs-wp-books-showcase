<?php
/**
 * Book Archive Page Settings
 */

// Hook to add submenu page
add_action('admin_menu', 'rswpbs_book_archive_settings_page');
function rswpbs_book_archive_settings_page() {
    add_submenu_page(
        'edit.php?post_type=book',
        __('Archive Page', 'rswpbs'),
        __('Archive Page', 'rswpbs'),
        'manage_options',
        'rswpbs-settings-book-archive',
        'rswp_book_showcase_books_archive_page'
    );
}

// Render settings page
function rswp_book_showcase_books_archive_page() {
    $fields = [
        'rswpbs_show_book_archive_page_header' => __('Show Archive Page Header', 'rswpbs'),
        'rswpbs_books_archive_page_title' => __('Archive Page Title', 'rswpbs'),
        'rswpbs_books_archive_page_description' => __('Archive Page Description', 'rswpbs'),
        'rswpbs_books_per_page' => __('Books Per Page', 'rswpbs'),
        'rswpbs_show_search_section' => __('Show Advanced Search', 'rswpbs'),
        'rswpbs_show_sorting_section' => __('Show Sorting', 'rswpbs'),
        'rswpbs_books_per_row' => __('Books Per Row', 'rswpbs'),
        'rswpbs_book_cover_position' => __('Cover Position', 'rswpbs'),
        'rswpbs_show_book_title' => __('Show Title', 'rswpbs'),
        'rswpbs_show_author_name' => __('Show Author', 'rswpbs'),
        'rswpbs_show_price' => __('Show Price', 'rswpbs'),
        'rswpbs_show_description' => __('Show Description', 'rswpbs'),
        'rswpbs_show_buy_now_button' => __('Show Buy Button', 'rswpbs')
    ];
    ?>
    <div class="wrap rswpbs-archive-page-setting-tab">
        <h1><?php esc_html_e('RS WP Book Showcase Settings', 'rswpbs'); ?></h1>
        <?php
        rswpbs_settings_tabs( 'books_archive' );
        ?>
        <h2 class="rswpbs-subtitle">Book Archive Page Settings</h2>
        <p class="rswpbs-subdescription">This Settings will apply to Book Category, Author, and Series Archive Pages</p>
        <form method="post" action="options.php">
            <?php settings_fields('rswpbs_book_archive_settings_group'); ?>
            <table class="form-table rswpbs-settings-table">
                <?php
                $counter = 0;
                echo '<tr>';
                foreach ($fields as $id => $label) {
                    echo '<td class="rswpbs-box">';
                    echo '<label for="' . esc_attr($id) . '">' . esc_html($label) . ': </label>';
                    rswpbs_render_setting_field(['id' => $id]);
                    echo '</td>';

                    $counter++;
                    if ($counter % 3 == 0) {
                        echo '</tr><tr>';
                    }
                }
                echo '</tr>';
                ?>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Hook to register settings with default values
add_action('admin_init', 'rswpbs_book_archive_register_settings');

function rswpbs_book_archive_register_settings() {
    $settings = [
        'rswpbs_show_book_archive_page_header' => ['label' => __('Show Header', 'rswpbs'), 'default' => '1'],
        'rswpbs_books_archive_page_title' => ['label' => __('Archive Title', 'rswpbs'), 'default' => __('Books', 'rswpbs')],
        'rswpbs_books_archive_page_description' => ['label' => __('Archive Description', 'rswpbs'), 'default' => __('Browse all books available in our collection.', 'rswpbs')],
        'rswpbs_books_per_page' => ['label' => __('Books Per Page', 'rswpbs'), 'default' => '8'],
        'rswpbs_show_search_section' => ['label' => __('Show Search', 'rswpbs'), 'default' => '1'],
        'rswpbs_show_sorting_section' => ['label' => __('Show Sorting', 'rswpbs'), 'default' => '1'],
        'rswpbs_books_per_row' => ['label' => __('Books Per Row', 'rswpbs'), 'default' => '4'],
        'rswpbs_book_cover_position' => ['label' => __('Cover Position', 'rswpbs'), 'default' => 'left'],
        'rswpbs_show_book_title' => ['label' => __('Show Title', 'rswpbs'), 'default' => '1'],
        'rswpbs_show_author_name' => ['label' => __('Show Author', 'rswpbs'), 'default' => '1'],
        'rswpbs_show_price' => ['label' => __('Show Price', 'rswpbs'), 'default' => '1'],
        'rswpbs_show_description' => ['label' => __('Show Description', 'rswpbs'), 'default' => '1'],
        'rswpbs_show_buy_now_button' => ['label' => __('Show Buy Button', 'rswpbs'), 'default' => '1']
    ];

    foreach ($settings as $key => $data) {
        register_setting('rswpbs_book_archive_settings_group', $key);

        // Set default values if not already set
        if (get_option($key) === false) {
            update_option($key, $data['default']);
        }
    }
}

// General function to render settings fields
function rswpbs_render_setting_field($args) {
    $id = $args['id'];
    $defaults = [
        'rswpbs_books_per_page' => '8',
        'rswpbs_books_per_row' => '4',
        'rswpbs_book_cover_position' => 'left',
    ];

    $value = get_option($id, $defaults[$id] ?? '');

    if (strpos($id, 'show_') !== false) {
        printf(
            '<input type="checkbox" name="%1$s" value="1" %2$s />',
            esc_attr($id),
            checked(1, $value, false)
        );
    } elseif ($id === 'rswpbs_books_per_page') {
        printf(
            '<input type="number" name="%1$s" value="%2$s" min="1" />',
            esc_attr($id),
            esc_attr($value)
        );
    } elseif ($id === 'rswpbs_books_per_row' || $id === 'rswpbs_book_cover_position') {
        $options = $id === 'rswpbs_books_per_row' ? [1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 6 => 'Six'] : ['top' => 'Top', 'left' => 'Left', 'right' => 'Right'];
        echo '<select name="' . esc_attr($id) . '">';
        foreach ($options as $key => $label) {
            printf('<option value="%s" %s>%s</option>', esc_attr($key), selected($value, $key, false), esc_html($label));
        }
        echo '</select>';
    } else {
        printf(
            '<input type="text" name="%1$s" value="%2$s" />',
            esc_attr($id),
            esc_attr($value)
        );
    }
}

// Helper function to fetch options with default values
function rswpbs_get_option($option_name) {
    $defaults = [
        'rswpbs_show_book_archive_page_header' => '1',
        'rswpbs_books_archive_page_title' => 'Books',
        'rswpbs_books_archive_page_description' => 'Browse all books available in our collection.',
        'rswpbs_books_per_page' => '10',
        'rswpbs_show_search_section' => '1',
        'rswpbs_show_sorting_section' => '1',
        'rswpbs_books_per_row' => '3',
        'rswpbs_book_cover_position' => 'left',
        'rswpbs_show_book_title' => '1',
        'rswpbs_show_author_name' => '1',
        'rswpbs_show_price' => '1',
        'rswpbs_show_description' => '1',
        'rswpbs_show_buy_now_button' => '1'
    ];

    return get_option($option_name, $defaults[$option_name] ?? '');
}
