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

function rswpbs_search_fields(){
	$search_fields = array(
		'name' => (isset($_GET['book_name']) ? sanitize_text_field($_GET['book_name']) : ''),
		'author' => (isset($_GET['author']) ? sanitize_text_field($_GET['author']) : ''),
		'category' => (isset($_GET['category']) ? sanitize_text_field($_GET['category']) : ''),
        'series' => (isset($_GET['series']) ? sanitize_text_field($_GET['series']) : ''),
		'format' => (isset($_GET['format']) ? sanitize_text_field($_GET['format']) : ''),
		'publisher' => (isset($_GET['publisher']) ? sanitize_text_field($_GET['publisher']) : ''),
		'publish_year' => (isset($_GET['publish_year']) ? sanitize_text_field($_GET['publish_year']) : ''),
		'sortby' => (isset($_GET['sortby']) ? sanitize_text_field($_GET['sortby']) : ''),
	);

    if (is_tax('book-category')) {
        $taxPageObj = get_queried_object();
        $taxPageSlug = $taxPageObj->slug;
        $search_fields['category'] = $taxPageSlug;
    }
    if (is_tax('book-series')) {
        $taxPageObj = get_queried_object();
        $taxPageSlug = $taxPageObj->slug;
        $search_fields['series'] = $taxPageSlug;
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
    ));
    return $baseUrl . '?' . $query_string;
}

function rswpbs_search_query_args(){
    $showNameField = get_option( 'rswpbs_show_name_field' ,1);
    $showAuthorField = false;
    $showCategoryField = get_option( 'rswpbs_show_category_field' ,1);
    $showSeriesField = false;
    $showFormatsField = get_option( 'rswpbs_show_formats_field' ,1);
    $showYearsField = get_option( 'rswpbs_show_years_field' ,1);
    $ShowResetIcon = false;
    $showPublishersField = false;

    if (class_exists('Rswpbs_Pro')) {
        $showAuthorField = get_option( 'rswpbs_show_author_field' ,1);
        $showSeriesField = get_option( 'rswpbs_show_series_field' ,1);
        $ShowResetIcon = get_option( 'rswpbs_show_reset_icon' ,1);
        $showPublishersField = get_option( 'rswpbs_show_publishers_field' ,1);
    }

    $search_fields = rswpbs_search_fields();

    $paged = rswpbs_paged();
    $tax_query = array();
    $meta_query = array();
    $queryArgs = array();
    if (true == $showCategoryField) :
        if ( $search_fields['category'] != 'all' ) {
            $tax_query[] = array(
                'taxonomy'	=>	'book-category',
                'field'	=>	'slug',
                'terms'	=>	$search_fields['category']
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
                'taxonomy'	=>	'book-author',
                'field'	=>	'slug',
                'terms'	=>	$search_fields['author']
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
    }else{
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
	$message = 'Showing ' . $start_index . '-' . $end_index . ' of ' . $total_books . ' ' .rswpbs_static_text_books();
	return esc_html($message);
}