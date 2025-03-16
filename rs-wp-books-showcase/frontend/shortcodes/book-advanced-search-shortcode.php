<?php
// Advanced Search Shortcode
add_shortcode('rswpbs_advanced_search', 'rswpbs_advanced_search');
function rswpbs_advanced_search($atts) {
    $atts = shortcode_atts([
        'show_name_field' => '',
        'fields_col' => '3'
    ], $atts);

    ob_start();

    // Fetch book metadata (unchanged)
    $books_query = get_posts(['post_type' => 'book', 'numberposts' => -1]);
    $metadata = [
        'formats' => [],
        'publishers' => [],
        'years' => [],
        'languages' => [],
        'isbns' => [],
        'isbn_10s' => []
    ];
    foreach ($books_query as $book) {
        $metadata['formats'][] = strtolower(rswpbs_get_book_format($book->ID));
        $metadata['publishers'][] = strtolower(rswpbs_get_book_publisher_name($book->ID));
        $metadata['years'][] = strtolower(rswpbs_get_book_publish_year($book->ID));
        $metadata['languages'][] = strtolower(get_post_meta($book->ID, '_rsbs_book_language', true));
        $metadata['isbns'][] = strtolower(get_post_meta($book->ID, '_rsbs_book_isbn', true));
        $metadata['isbn_10s'][] = strtolower(get_post_meta($book->ID, '_rsbs_book_isbn_10', true));
    }
    foreach ($metadata as &$data) {
        $data = array_filter(array_unique($data));
        sort($data);
    }

    // Taxonomies (unchanged)
    $taxonomies = [
        'authors' => get_terms(['taxonomy' => 'book-author', 'hide_empty' => false]),
        'series' => get_terms(['taxonomy' => 'book-series', 'hide_empty' => false]),
        'categories' => get_terms(['taxonomy' => 'book-category', 'hide_empty' => false])
    ];
    foreach ($taxonomies as &$terms) {
        usort($terms, fn($a, $b) => strcmp($a->name, $b->name));
    }

    // Field visibility (unchanged)
    $visibility = [
        'name' => get_option('rswpbs_show_name_field', 1),
        'category' => get_option('rswpbs_show_category_field', 1),
        'format' => get_option('rswpbs_show_formats_field', 1),
        'year' => get_option('rswpbs_show_years_field', 1),
    ];
    $visibility['author'] = get_option('rswpbs_show_author_field', 1);
    $visibility['series'] = get_option('rswpbs_show_series_field', 1);
    $visibility['publisher'] = get_option('rswpbs_show_publishers_field', 1);
    $visibility['language'] = get_option('rswpbs_show_language_field', 1);
    $visibility['isbn'] = get_option('rswpbs_show_isbn_field', 1);
    $visibility['isbn_10'] = get_option('rswpbs_show_isbn_10_field', 1);
    $visibility['reset'] = get_option('rswpbs_show_reset_icon', 1);

    // Column classes (unchanged)
    $col_classes = [
        '2' => ['field' => 'rswpbs-col-lg-6 rswpbs-col-6 rswpbs-col-md-4', 'btn' => 'rswpbs-col-lg-3 rswpbs-col-md-3 rswpbs-col-5', 'reset' => 'rswpbs-col-lg-2 rswpbs-col-md-2 rswpbs-col-3'],
        '3' => ['field' => 'rswpbs-col-lg-4 rswpbs-col-6 rswpbs-col-md-4', 'btn' => 'rswpbs-col-lg-3 rswpbs-col-md-3 rswpbs-col-5', 'reset' => 'rswpbs-col-lg-2 rswpbs-col-md-2 rswpbs-col-3'],
        '4' => ['field' => 'rswpbs-col-lg-3 rswpbs-col-6 rswpbs-col-md-4', 'btn' => 'rswpbs-col-lg-2 rswpbs-col-md-3 rswpbs-col-5', 'reset' => 'rswpbs-col-lg-1 rswpbs-col-md-2 rswpbs-col-3']
    ];
    $columns = $col_classes[$atts['fields_col']] ?? $col_classes['3'];

    $search_fields = rswpbs_search_fields();
    $form_class = class_exists('Rswpbs_Pro') ? 'rswpbs-pro-search-form-row' : 'rswpbs-free-search-form-row';

    // Get the field order
    $default_order = [
        'rswpbs_show_name_field', 'rswpbs_show_category_field', 'rswpbs_show_author_field',
        'rswpbs_show_formats_field', 'rswpbs_show_years_field', 'rswpbs_show_series_field',
        'rswpbs_show_publishers_field', 'rswpbs_show_language_field', 'rswpbs_show_isbn_field',
        'rswpbs_show_isbn_10_field', 'rswpbs_show_reset_icon'
    ];
    $field_order = get_option('rswpbs_search_field_order', $default_order);

    ?>
    <div class="rswpbs-books-showcase-search-form-container">
        <form class="rswpbs-books-search-form" id="rswpbs-books-search-form" action="<?php echo esc_url(rswpbs_search_page_base_url()); ?>" method="get">
            <input type="hidden" name="search_type" value="book">
            <input type="hidden" name="sortby" id="rswpbs-sortby" value="">
            <div class="rswpbs-search-form rswpbs-row justify-content-end justify-content-md-start <?php echo esc_attr($form_class); ?>">
                <?php
                // Render fields in the saved order
                foreach ($field_order as $field) {
                    switch ($field) {
                        case 'rswpbs_show_name_field':
                            if ($visibility['name']): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <input type="text" name="book_name" id="book-name" placeholder="<?php echo rswpbs_static_text_book_name(); ?>" value="<?php echo esc_attr($search_fields['name']); ?>">
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_author_field':
                            if ($visibility['author'] ?? false): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <select name="author" id="book-author" class="rswpbs-select-field">
                                            <option value="all"><?php echo rswpbs_static_text_all_authors(); ?></option>
                                            <?php foreach ($taxonomies['authors'] as $author): ?>
                                                <option value="<?php echo esc_attr($author->slug); ?>" <?php selected($author->slug, $search_fields['author']); ?>>
                                                    <?php echo esc_html($author->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_category_field':
                            if ($visibility['category']): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <select name="category" id="book-category" class="rswpbs-select-field">
                                            <option value="all"><?php echo rswpbs_static_text_all_categories(); ?></option>
                                            <?php foreach ($taxonomies['categories'] as $category): ?>
                                                <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($category->slug, $search_fields['category']); ?>>
                                                    <?php echo esc_html($category->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_series_field':
                            if ($visibility['series'] ?? false): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <select name="series" id="book-series" class="rswpbs-select-field">
                                            <option value="all"><?php echo rswpbs_static_text_all_series(); ?></option>
                                            <?php foreach ($taxonomies['series'] as $series): ?>
                                                <option value="<?php echo esc_attr($series->slug); ?>" <?php selected($series->slug, $search_fields['series']); ?>>
                                                    <?php echo esc_html($series->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_formats_field':
                            if ($visibility['format']): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <select name="format" id="book-format" class="rswpbs-select-field">
                                            <option value="all"><?php echo rswpbs_static_text_all_formats(); ?></option>
                                            <?php foreach ($metadata['formats'] as $format): ?>
                                                <option value="<?php echo esc_attr($format); ?>" <?php selected($format, $search_fields['format']); ?>>
                                                    <?php echo esc_html($format); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_publishers_field':
                            if ($visibility['publisher'] ?? false): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <select name="publisher" id="book-publisher" class="rswpbs-select-field">
                                            <option value="all"><?php echo rswpbs_static_text_all_publishers(); ?></option>
                                            <?php foreach ($metadata['publishers'] as $publisher): ?>
                                                <option value="<?php echo esc_attr($publisher); ?>" <?php selected($publisher, $search_fields['publisher']); ?>>
                                                    <?php echo esc_html($publisher); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_years_field':
                            if ($visibility['year']): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <select name="publish_year" id="publish_year" class="rswpbs-select-field">
                                            <option value="all"><?php echo rswpbs_static_text_all_years(); ?></option>
                                            <?php foreach ($metadata['years'] as $year): ?>
                                                <option value="<?php echo esc_attr($year); ?>" <?php selected($year, $search_fields['publish_year']); ?>>
                                                    <?php echo esc_html($year); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_language_field':
                            if ($visibility['language'] ?? false): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <select name="language" id="book-language" class="rswpbs-select-field">
                                            <option value="all"><?php echo esc_html__('All Languages', 'rswpbs'); ?></option>
                                            <?php foreach ($metadata['languages'] as $language): ?>
                                                <option value="<?php echo esc_attr($language); ?>" <?php selected($language, $search_fields['language']); ?>>
                                                    <?php echo esc_html($language); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_isbn_field':
                            if ($visibility['isbn'] ?? false): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <input type="text" name="isbn" id="book-isbn" placeholder="<?php echo esc_html__('ISBN', 'rswpbs'); ?>" value="<?php echo esc_attr($search_fields['isbn']); ?>">
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_isbn_10_field':
                            if ($visibility['isbn_10'] ?? false): ?>
                                <div class="<?php echo esc_attr($columns['field']); ?>">
                                    <div class="search-field">
                                        <input type="text" name="isbn_10" id="book-isbn-10" placeholder="<?php echo esc_html__('ISBN-10', 'rswpbs'); ?>" value="<?php echo esc_attr($search_fields['isbn_10']); ?>">
                                    </div>
                                </div>
                            <?php endif;
                            break;

                        case 'rswpbs_show_reset_icon':
                            if ($visibility['reset'] ?? false): ?>
                                <div class="<?php echo esc_attr($columns['reset']); ?>">
                                    <div class="search-field">
                                        <button type="button" class="reset-search-form"><i class="fa-solid fa-arrows-rotate"></i></button>
                                    </div>
                                </div>
                            <?php endif;
                            break;
                    }
                }
                ?>
                <div class="<?php echo esc_attr($columns['btn']); ?>">
                    <div class="search-field">
                        <input type="submit" value="<?php echo rswpbs_static_text_search(); ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}