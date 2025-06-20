<?php
function rswpbs_paged(){
    $paged = 1;
    if ( get_query_var( 'paged' ) ) {
        $paged = get_query_var( 'paged' );
    } elseif ( get_query_var( 'page' ) ) {
        $paged = get_query_var( 'page' );
    } else {
        $paged = 1;
    }
    return $paged;
}

function rswpbs_search_fields() {
    $search_fields = array(
        'name' => (isset($_GET['book_name']) ? wp_kses_post(rawurldecode($_GET['book_name'])) : ''),
        'author' => (isset($_GET['author']) ? wp_kses_post(rawurldecode($_GET['author'])) : ''),
        'category' => (isset($_GET['category']) ? wp_kses_post(rawurldecode($_GET['category'])) : ''),
        'series' => (isset($_GET['series']) ? wp_kses_post(rawurldecode($_GET['series'])) : ''),
        'format' => (isset($_GET['format']) ? wp_kses_post(rawurldecode($_GET['format'])) : ''),
        'publisher' => (isset($_GET['publisher']) ? wp_kses_post(rawurldecode($_GET['publisher'])) : ''),
        'publish_year' => (isset($_GET['publish_year']) ? wp_kses_post(rawurldecode($_GET['publish_year'])) : ''),
        'sortby' => (isset($_GET['sortby']) ? sanitize_text_field($_GET['sortby']) : ''),
        'language' => (isset($_GET['language']) ? wp_kses_post(rawurldecode($_GET['language'])) : ''),
        'isbn' => (isset($_GET['isbn']) ? wp_kses_post(rawurldecode($_GET['isbn'])) : ''),
        'isbn_10' => (isset($_GET['isbn_10']) ? wp_kses_post(rawurldecode($_GET['isbn_10'])) : ''),
    );

    if (is_tax('book-category')) {
        $taxPageObj = get_queried_object();
        $taxPageSlug = $taxPageObj->slug;
        $search_fields['category'] = wp_kses_post(rawurldecode($taxPageSlug));
    }
    if (is_tax('book-series')) {
        $taxPageObj = get_queried_object();
        $taxPageSlug = $taxPageObj->slug;
        $search_fields['series'] = wp_kses_post(rawurldecode($taxPageSlug));
    }

    return $search_fields;
}

function rswpbs_static_search_string($params = array()) {
    $baseUrl = rswpbs_search_page_base_url();
    $search_fields = array(
        'name' => (isset($params['book_name']) ? sanitize_text_field($params['book_name']) : ''),
        'author' => (isset($params['author']) ? sanitize_text_field($params['author']) : 'all'),
        'category' => (isset($params['category']) ? sanitize_text_field($params['category']) : 'all'),
        'series' => (isset($params['series']) ? sanitize_text_field($params['series']) : 'all'),
        'format' => (isset($params['format']) ? sanitize_text_field($params['format']) : 'all'),
        'publisher' => (isset($params['publisher']) ? sanitize_text_field($params['publisher']) : 'all'),
        'publish_year' => (isset($params['publish_year']) ? sanitize_text_field($params['publish_year']) : 'all'),
        // Add new fields
        'language' => (isset($params['language']) ? sanitize_text_field($params['language']) : 'all'),
        'isbn' => (isset($params['isbn']) ? sanitize_text_field($params['isbn']) : ''),
        'isbn_10' => (isset($params['isbn_10']) ? sanitize_text_field($params['isbn_10']) : ''),
    );

    $query_string = http_build_query(array(
        'search_type' => 'book',
        'book_name' => strtolower($search_fields['name']),
        'author' => strtolower($search_fields['author']),
        'category' => strtolower($search_fields['category']),
        'series' => strtolower($search_fields['series']),
        'format' => strtolower($search_fields['format']),
        'publisher' => strtolower($search_fields['publisher']),
        'publish_year' => strtolower($search_fields['publish_year']),
        'language' => strtolower($search_fields['language']),
        'isbn' => strtolower($search_fields['isbn']),
        'isbn_10' => strtolower($search_fields['isbn_10']),
    ));
    return $baseUrl . '?' . $query_string;
}

function rswpbs_search_query_args(){
    $showNameField = get_option('rswpbs_show_name_field', 1);
    $showCategoryField = get_option('rswpbs_show_category_field', 1);
    $showFormatsField = get_option('rswpbs_show_formats_field', 1);
    $showYearsField = get_option('rswpbs_show_years_field', 1);
    $showAuthorField = get_option('rswpbs_show_author_field', 1);
    $showSeriesField = get_option('rswpbs_show_series_field', 1);
    $showPublishersField = get_option('rswpbs_show_publishers_field', 1);
    $showLanguageField = get_option('rswpbs_show_language_field', 1);
    $showIsbnField = get_option('rswpbs_show_isbn_field', 1);
    $showIsbn10Field = get_option('rswpbs_show_isbn_10_field', 1);

    $search_fields = rswpbs_search_fields();

    $paged = rswpbs_paged();
    $tax_query = array();
    $meta_query = array();
    $queryArgs = array();

    if (true == $showCategoryField) :
        if ( $search_fields['category'] != 'all' ) {
            $tax_query[] = array(
                'taxonomy'  =>  'book-category',
                'field' =>  'slug',
                'terms' =>  $search_fields['category']
            );
        }
    endif;
    if( true == $showSeriesField ) :
        if ( $search_fields['series'] != 'all' ) {
            $tax_query[] = array(
                'taxonomy'  =>  'book-series',
                'field' =>  'slug',
                'terms' =>  $search_fields['series']
            );
        }
    endif;
    if (true == $showAuthorField) :
        if ($search_fields['author'] != 'all') {
            $tax_query[] = array(
                'taxonomy'  =>  'book-author',
                'field' =>  'slug',
                'terms' =>  $search_fields['author']
            );
        }
    endif;
    if (true == $showNameField) :
        if (!empty($search_fields['name'])) {
            $queryArgs['s'] = $search_fields['name'];
        }
    endif;
    if (true == $showFormatsField) :
        if ($search_fields['format'] != 'all') {
            $meta_query[] = array(
                'key'     => '_rsbs_book_format',
                'value'   => $search_fields['format'],
                'compare' => 'LIKE',
            );
        }
    endif;
    if (true == $showPublishersField) :
        if ($search_fields['publisher'] != 'all') {
            $meta_query[] = array(
                'key'     => '_rsbs_book_publisher_name',
                'value'   => $search_fields['publisher'],
                'compare' => 'LIKE',
            );
        }
    endif;
    if (true == $showYearsField) :
        if ($search_fields['publish_year'] != 'all') {
            $meta_query[] = array(
                'key'     => '_rsbs_book_publish_year',
                'value'   => $search_fields['publish_year'],
                'compare' => 'LIKE',
            );
        }
    endif;

    // Add new fields: Language, ISBN, ISBN-10
    if (true == $showLanguageField) :
        if (!empty($search_fields['language']) && $search_fields['language'] != 'all') {
            $meta_query[] = array(
                'key'     => '_rsbs_book_language',
                'value'   => $search_fields['language'],
                'compare' => 'LIKE',
            );
        }
    endif;
    if (true == $showIsbnField) :
        if (!empty($search_fields['isbn'])) {
            $meta_query[] = array(
                'key'     => '_rsbs_book_isbn',
                'value'   => $search_fields['isbn'],
                'compare' => 'LIKE',
            );
        }
    endif;
    if (true == $showIsbn10Field) :
        if (!empty($search_fields['isbn_10'])) {
            $meta_query[] = array(
                'key'     => '_rsbs_book_isbn_10',
                'value'   => $search_fields['isbn_10'],
                'compare' => 'LIKE',
            );
        }
    endif;

    if (!empty($tax_query)) {
        $tax_query['relation'] = 'AND';
        $queryArgs['tax_query'] = $tax_query;
    }
    if (!empty($meta_query)) {
        $meta_query['relation'] = 'AND';
        $queryArgs['meta_query'] = $meta_query;
    }

    return $queryArgs;
}

function rswpbs_sorting_form_args(){
    $search_fields = rswpbs_search_fields();
    $queryArgs = array();
    if ('default' != $search_fields['sortby']) {
        switch ( $search_fields['sortby'] ) {
            case 'price_asc':
              $queryArgs['meta_key'] = '_rsbs_book_query_price';
              $queryArgs['orderby'] = 'meta_value_num';
              $queryArgs['order'] = 'ASC';
              break;
            case 'price_desc':
              $queryArgs['meta_key'] = '_rsbs_book_query_price';
              $queryArgs['orderby'] = 'meta_value_num';
              $queryArgs['order'] = 'DESC';
              break;
            case 'title_asc':
              $queryArgs['orderby'] = 'title';
              $queryArgs['order'] = 'ASC';
              break;
            case 'title_desc':
              $queryArgs['orderby'] = 'title';
              $queryArgs['order'] = 'DESC';
              break;
            case 'date_asc':
              $queryArgs['orderby'] = 'date';
              $queryArgs['order'] = 'ASC';
              break;
            case 'date_desc':
              $queryArgs['orderby'] = 'date';
              $queryArgs['order'] = 'DESC';
              break;
        }
    } else {
       $queryArgs['orderby'] = 'date';
       $queryArgs['order'] = 'DESC';
    }

    return $queryArgs;
}

function rswpbs_total_books_message($queryName, $bookPerPage){
    $total_books = $queryName->found_posts;
    $paged = rswpbs_paged();
    $current_page = $paged; // Replace with the current page number
    $start_index = ( $current_page - 1 ) * $bookPerPage + 1;
    $end_index = $start_index + $bookPerPage - 1;
    if ( $end_index > $total_books ) {
        $end_index = $total_books;
    }
    $message = 'Showing ' . $start_index . '-' . $end_index . ' of ' . $total_books . ' ' . rswpbs_static_text_books();
    return esc_html($message);
}