<?php
/**
 * Displays full book overview section with dynamic information fields
 */
function rswpbs_book_content_section($bookId = null) {
    if (null == $bookId) {
        $bookId = get_the_ID();
    }
    $publishersQueryLink = rswpbs_static_search_string(array('publisher' => rswpbs_get_book_publisher_name($bookId)));
    do_action('rswpbs_before_book_overview_section');

    // Define all book information fields with their properties
    $book_info_fields = [
        'availability' => [
            'value' => rswpbs_get_book_availability_status($bookId),
            'label' => rswpbs_static_text_availability(),
            'condition' => function($value) { return !empty($value) && $value !== 'blank'; }
        ],
        'original_title' => [
            'value' => rswpbs_get_book_original_name($bookId),
            'label' => rswpbs_static_text_original_title(),
            'wrapper' => function($value) {
                $url = rswpbs_get_book_original_url();
                return $url ? "<a href='" . esc_url($url) . "'>" . esc_html($value) . "</a>" : esc_html($value);
            }
        ],
        'categories' => [
            'value' => rswpbs_get_book_categories($bookId),
            'label' => rswpbs_static_text_categories(),
            'escape' => 'wp_kses_post'
        ],
        'series' => [
            'value' => rswpbs_get_book_series($bookId),
            'label' => rswpbs_static_text_series(),
            'escape' => 'wp_kses_post'
        ],
        'publish_date' => [
            'value' => rswpbs_get_book_publish_date($bookId),
            'label' => rswpbs_static_text_publish_date()
        ],
        'publish_year' => [
            'value' => rswpbs_get_book_publish_year($bookId),
            'label' => rswpbs_static_text_published_year(),
            'condition' => function() { return !empty(rswpbs_get_book_publish_date()); }
        ],
        'publisher' => [
            'value' => "<a href='" . esc_url($publishersQueryLink) . "'>" . esc_html(rswpbs_get_book_publisher_name($bookId)) . "</a>",
            'label' => rswpbs_static_text_publisher_name(),
            'escape' => false
        ],
        'pages' => [
            'value' => rswpbs_get_book_pages($bookId),
            'label' => rswpbs_static_text_total_pages()
        ],
        'isbn' => [
            'value' => rswpbs_get_book_isbn($bookId),
            'label' => rswpbs_static_text_isbn()
        ],
        'isbn_10' => [
            'value' => rswpbs_get_book_isbn_10($bookId),
            'label' => rswpbs_static_text_isbn_10()
        ],
        'isbn_13' => [
            'value' => rswpbs_get_book_isbn_13($bookId),
            'label' => rswpbs_static_text_isbn_13()
        ],
        'asin' => [
            'value' => rswpbs_get_book_asin($bookId),
            'label' => rswpbs_static_text_asin()
        ],
        'format' => [
            'value' => rswpbs_get_book_format($bookId),
            'label' => rswpbs_static_text_format()
        ],
        'country' => [
            'value' => rswpbs_get_book_country($bookId),
            'label' => rswpbs_static_text_country()
        ],
        'language' => [
            'value' => rswpbs_get_book_language($bookId),
            'label' => rswpbs_static_text_language()
        ],
        'translator' => [
            'value' => rswpbs_get_book_translator($bookId),
            'label' => rswpbs_static_text_translator()
        ],
        'file_size' => [
            'value' => rswpbs_get_book_file_size($bookId),
            'label' => rswpbs_static_text_file_size()
        ],
        'dimension' => [
            'value' => rswpbs_get_book_dimension($bookId),
            'label' => rswpbs_static_text_dimension()
        ],
        'weight' => [
            'value' => rswpbs_get_book_weight($bookId),
            'label' => rswpbs_static_text_weight()
        ],
        'file_format' => [
            'value' => rswpbs_get_book_file_format($bookId),
            'label' => rswpbs_static_text_file_format()
        ],
        'simultaneous_device_usage' => [
            'value' => rswpbs_get_simultaneous_device_usage($bookId),
            'label' => rswpbs_static_text_simultaneous_device_usage()
        ],
        'text_to_speech' => [
            'value' => rswpbs_get_book_text_to_speech($bookId),
            'label' => rswpbs_static_text_text_to_speech()
        ],
        'screen_reader' => [
            'value' => rswpbs_get_screen_reader($bookId),
            'label' => rswpbs_static_text_screen_reader()
        ],
        'enhanced_typesetting' => [
            'value' => rswpbs_get_enhanced_typesetting($bookId),
            'label' => rswpbs_static_text_enhanced_typesetting()
        ],
        'x_ray' => [
            'value' => rswpbs_get_x_ray($bookId),
            'label' => rswpbs_static_text_x_ray()
        ],
        'word_wise' => [
            'value' => rswpbs_get_word_wise($bookId),
            'label' => rswpbs_static_text_word_wise()
        ],
        'sticky_notes' => [
            'value' => rswpbs_get_sticky_notes($bookId),
            'label' => rswpbs_static_text_sticky_notes()
        ],
        'print_length' => [
            'value' => rswpbs_get_print_length($bookId),
            'label' => rswpbs_static_text_print_length()
        ],
        'avg_rate' => [
            'value' => rswpbs_get_avg_rate($bookId),
            'label' => rswpbs_static_text_avarage_ratings(),
            'escape' => 'wp_kses_post'
        ],
        'reading_date' => [
            'value' => rswpbs_get_book_reading_date($bookId),
            'label' => rswpbs_static_text_reading_date(),
            'escape' => 'wp_kses_post'
        ]
    ];

    ?>
    <div class="rswpbs-book-overview-section">
        <div class="rswpbs-row">
            <div class="rswpbs-col-lg-8 pl-0">
                <div class="rswpbs-book-overview">
                    <?php
                    $post = get_post($bookId);
                    $content = '';
                    if ($post) {
                        $content = get_the_content(null, false, $post);
                        echo $content = apply_filters('the_content', $content);
                    }
                    ?>
                </div>
            </div>
            <div class="rswpbs-col-lg-4 pl-0 pr-0">
                <div class="rswpbs-book-information-container">
                    <?php
                    do_action('rswpbs_before_book_information');

                    foreach ($book_info_fields as $field) {
                        $value = $field['value'];

                        // Check conditions
                        if (empty($value)) continue;
                        if (isset($field['condition']) && !$field['condition']($value)) continue;

                        // Apply wrapper if exists
                        if (isset($field['wrapper'])) {
                            $value = $field['wrapper']($value);
                        }
                        // Escape value unless specified otherwise
                        else if (!isset($field['escape']) || $field['escape'] === 'esc_html') {
                            $value = esc_html($value);
                        }
                        else if ($field['escape'] === 'wp_kses_post') {
                            $value = wp_kses_post($value);
                        }
                        // If escape is false, leave as is

                        if (!empty($value)) :
                        ?>
                        <div class="information-list">
                            <div class="information-label">
                                <h4><?php echo esc_html($field['label']); ?></h4>
                            </div>
                            <div class="information-content">
                                <h4><?php echo $value; ?></h4>
                            </div>
                        </div>
                        <?php
                        endif;
                    }
                    do_action('rswpbs_after_book_information');
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    do_action('rswpbs_after_book_overview_section');
}