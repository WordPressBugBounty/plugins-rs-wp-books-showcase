<?php
add_shortcode('rs_wp_book_showcase_ajax', 'rs_wp_book_showcase_ajax_shortcode');

function rs_wp_book_showcase_ajax_shortcode($atts) {
    // Define shortcode attributes and their defaults
    $atts = shortcode_atts(
        array(
            'layout'    => 'default',
            'show_search_form'  => 'true',
            'show_sorting_form' => 'true',
            'filtering_menu'    => 'false',
            'order' => '',
            'orderby' => '',
            'show_image' => 'true',
            'image_position' => 'top',
            'image_type' => 'book_cover',
            'show_title' => 'true',
            'title_type' => 'book_name',
            'show_author' => 'true',
            'show_price' => 'true',
            'show_buy_button'   => 'true',
            'show_add_to_cart_btn' => 'false',
            'show_excerpt'  => 'true',
            'excerpt_limit' => '60',
            'books_per_page'    => '8',
            'books_per_row' => '3',
            'show_load_more_btn' => 'true',
            'show_msl' => 'true',
            'msl_title_align' => 'center',
            'show_masonry_layout' => 'true',
            'content_align' => 'center',
            'show_read_more_button' => 'false',
            'search_fields_col' => '4',
            'height_stretch' => 'true',
        ),
        $atts,
        'rs_wp_book_showcase_ajax'
    );
    ob_start();

    if ('true' == $atts['show_search_form']) :
        $search_fields_col = $atts['search_fields_col'];
    ?>
    <div class="rswpbs-advanced-search-form-area">
        <?php echo do_shortcode("[rswpbs_advanced_search fields_col=\"$search_fields_col\"]"); ?>
    </div>
    <?php
    endif;
    $search_fields = rswpbs_search_fields();
    ?>
    <div class="rswpbs-sorting-sections-wrapper">
        <div class="rswpbs-row justify-content-between">
            <div class="rswpbs-col-md-6 align-self-center">
                <div id="result-count"></div>
            </div>
            <div class="rswpbs-col-md-6 align-self-center">
                <div class="rswpbs-books-sorting-field" id="rswpbs-books-sorting-field">
                    <select id="rswpbs-sort">
                        <option value="default">
                            <?php esc_html_e('Default Sorting', 'rswpbs');?>
                        </option>
                        <option value="price_asc" <?php echo ($search_fields['sortby']=='price_asc' ? 'selected="selected"' : '' ); ?>>
                            <?php esc_html_e( 'Price (Low to High)', 'rswpbs' );?>
                        </option>
                        <option value="price_desc" <?php echo ($search_fields['sortby']=='price_desc' ? 'selected="selected"' : '' ); ?>>
                            <?php esc_html_e( 'Price (High to Low)', 'rswpbs' );?>
                        </option>
                        <option value="title_asc" <?php echo ($search_fields['sortby']=='title_asc' ? 'selected="selected"' : '' ); ?>>
                            <?php esc_html_e( 'Title (A-Z)', 'rswpbs' );?>
                        </option>
                        <option value="title_desc" <?php echo ($search_fields['sortby']=='title_desc' ? 'selected="selected"' : '' ); ?>>
                            <?php esc_html_e( 'Title (Z-A)', 'rswpbs' );?>
                        </option>
                        <option value="date_asc" <?php echo ($search_fields['sortby']=='date_asc' ? 'selected="selected"' : '' ); ?>>
                            <?php esc_html_e( 'Date (Oldest to Newest)', 'rswpbs' );?>
                        </option>
                        <option value="date_desc" <?php echo ($search_fields['sortby']=='date_desc' ? 'selected="selected"' : '' ); ?>>
                            <?php esc_html_e( 'Date (Newest to Oldest)', 'rswpbs' );?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="rswpbs-books-showcase-area">
        <div id="book-list-container" class="rswpbs-row">
            <?php
            echo rs_wp_book_showcase_ajax_handler($atts, $_GET);
            ?>
        </div>
    </div>
    <?php
    if (true == $atts['show_load_more_btn']) :
     ?>
    <div class="rswpbs-row">
        <div class="rswpbs-col-md-12">
            <div class="rswpbs-books-load-more text-center">
                <div id="load-more-button-container"><button id="load-more-books">Load More</button></div>
            </div>
        </div>
    </div>
    <?php
    endif;
    // Enqueue the necessary scripts
    wp_enqueue_script('ajax-book-search', RSWPBS_PLUGIN_URL . 'frontend/assets/js/ajax-book-search.js', array('jquery'), null, true);
    wp_localize_script('ajax-book-search', 'ajax_book_search_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'atts' => $atts,
        'posts_per_page' => $atts['books_per_page'],
        'paged' => 1
    ));

    return ob_get_clean();
}

function rs_wp_book_showcase_ajax_handler($atts, $search_criteria = array(), $paged = 1) {
    /**
     * Query Args For Ajax Search
     */
    $query_args = array(
        'post_type' => 'book',
        'posts_per_page' => isset($atts['books_per_page']) ? intval($atts['books_per_page']) : 5,
        'paged' => $paged,
        's' => isset($search_criteria['book_name']) ? sanitize_text_field($search_criteria['book_name']) : '',
        'tax_query' => array(
            'relation' => 'AND',
        ),
        'meta_query' => array(
            'relation' => 'AND',
        ),
    );

    if (!empty($search_criteria['author']) && $search_criteria['author'] !== 'all') {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'book-author',
            'field' => 'slug',
            'terms' => sanitize_text_field($search_criteria['author']),
            'operator' => 'IN'
        );
    }
    if (!empty($search_criteria['category']) && $search_criteria['category'] !== 'all') {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'book-category',
            'field' => 'slug',
            'terms' => sanitize_text_field($search_criteria['category']),
            'operator' => 'IN'
        );
    }
    if (!empty($search_criteria['series']) && $search_criteria['series'] !== 'all') {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'book-series',
            'field' => 'slug',
            'terms' => sanitize_text_field($search_criteria['series']),
            'operator' => 'IN'
        );
    }
    if (!empty($search_criteria['format']) && $search_criteria['format'] !== 'all') {
        $query_args['meta_query'][] = array(
            'key' => '_rsbs_book_format',
            'value' => sanitize_text_field($search_criteria['format']),
            'compare' => 'LIKE'
        );
    }
    if (!empty($search_criteria['publisher']) && $search_criteria['publisher'] !== 'all') {
        $query_args['meta_query'][] = array(
            'key' => '_rsbs_book_publisher_name',
            'value' => sanitize_text_field($search_criteria['publisher']),
            'compare' => 'LIKE'
        );
    }

    if (!empty($search_criteria['publish_year']) && $search_criteria['publish_year'] !== 'all') {
        $query_args['meta_query'][] = array(
            'key' => '_rsbs_book_publish_year',
            'value' => sanitize_text_field($search_criteria['publish_year']),
            'compare' => 'LIKE'
        );
    }

    if (!empty($search_criteria['sort'])) {
        switch ($search_criteria['sort']) {
            case 'price_asc':
                $query_args['meta_key'] = '_rsbs_book_query_price';
                $query_args['orderby'] = 'meta_value_num';
                $query_args['order'] = 'ASC';
                break;
            case 'price_desc':
                $query_args['meta_key'] = '_rsbs_book_query_price';
                $query_args['orderby'] = 'meta_value_num';
                $query_args['order'] = 'DESC';
                break;
            case 'title_asc':
                $query_args['orderby'] = 'title';
                $query_args['order'] = 'ASC';
                break;
            case 'title_desc':
                $query_args['orderby'] = 'title';
                $query_args['order'] = 'DESC';
                break;
            case 'date_asc':
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'ASC';
                break;
            case 'date_desc':
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'DESC';
                break;
        }
    }

    /**
     * Book Layout Control
     */

    $book_container_classes = '';
    $thumbnail_wrapper_classes  = '';
    $content_wrapper_classes    = '';
    if ('left' === $atts['image_position']) {
        $book_container_classes = ' rswpbs-row mr-0 ml-0 book-list-layout thumbnail-position-left';
        $thumbnail_wrapper_classes  = ' book-cover-column rswpbs-col-md-6 rswpbs-col-lg-4 pl-0 pr-0 pr-md-4 pr-lg-4 pr-xl-4';
        $content_wrapper_classes    = ' book-content-column rswpbs-col-md-6 rswpbs-col-lg-8';
    }elseif ('right' === $atts['image_position']) {
        $book_container_classes = ' rswpbs-row flex-row-reverse mr-0 ml-0 book-list-layout thumbnail-position-right';
        $thumbnail_wrapper_classes  = ' book-cover-column rswpbs-col-md-6 rswpbs-col-lg-4 pl-0 pl-xl-4 pl-lg-4 pl-md-4 pr-0 text-right';
        $content_wrapper_classes    = ' book-content-column rswpbs-col-md-6 rswpbs-col-lg-8';
    }elseif ('top' === $atts['image_position']) {
        $thumbnail_wrapper_classes  = ' thumbnail-position-top';
        $book_container_classes = ' book-grid-layout';
    }

    $contentAlign = ' content-align-center';
    if ($atts['content_align'] == 'left') {
        $contentAlign = ' content-align-left';
    }elseif ($atts['content_align'] == 'right') {
        $contentAlign = ' content-align-right';
    }elseif ($atts['content_align'] == 'center') {
        $contentAlign = ' content-align-center';
    }

    $booksPerRow = $atts['books_per_row'];
    $bookColumnClases = 'rswpbs-col-lg-3 rswpbs-col-md-4 book-single-column';
    if ('1' == $booksPerRow) {
        $bookColumnClases = 'rswpbs-col-lg-12 book-single-column';
    }elseif('2' == $booksPerRow){
        $bookColumnClases = 'rswpbs-col-md-6 book-single-column';
    }elseif('3' == $booksPerRow){
        $bookColumnClases = 'rswpbs-col-lg-6 rswpbs-col-xl-4 rswpbs-col-md-6 book-single-column';
    }elseif('4' == $booksPerRow){
        $bookColumnClases = 'rswpbs-col-lg-4 rswpbs-col-xl-3 rswpbs-col-md-6 book-single-column';
    }elseif('6' == $booksPerRow){
        $bookColumnClases = 'rswpbs-col-lg-3 rswpbs-col-xl-2 rswpbs-col-md-4 book-single-column';
    }
    $heightStretch = $atts['height_stretch'];
    if ('true' == $heightStretch) {
        $bookColumnClases .= ' align-self-stretch book-col-have-margin';
    }

    $sectionClasses = array();
    if ( ('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '1' == $booksPerRow ) {
        $sectionClasses[] = ' book-gallery-list-layout-1-col';
    }elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '2' == $booksPerRow) {
        $sectionClasses[] = ' book-gallery-list-layout-2-col';
        $bookColumnClases = 'rswpbs-col-md-12 rswpbs-col-lg-6 book-single-column';
    }elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '3' == $booksPerRow) {
        $sectionClasses[] = ' book-gallery-list-layout-3-col';
    }elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '4' == $booksPerRow) {
        $sectionClasses[] = ' book-gallery-list-layout-4-col';
    }elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '5' == $booksPerRow) {
        $sectionClasses[] = ' book-gallery-list-layout-5-col';
    }elseif (('left' == $atts['image_position'] || 'right' == $atts['image_position']) && '6' == $booksPerRow) {
        $sectionClasses[] = ' book-gallery-list-layout-6-col';
    }
    $sectionClasses[] = ' ' . $atts['layout'];

    $sectionClasses = implode(' ', $sectionClasses);

    $content_wrapper_classes .= $contentAlign;

    $wrapperRowClass = '';
    if ( class_exists('Rswpbs_Pro') && 'true' == $atts['show_masonry_layout'] && 'false' == $heightStretch) {
        $wrapperRowClass = ' masonry_layout_active_for_books';
    }

    $layoutArgs = array(
        'showBookImage' => $atts['show_image'],
        'bookImageType' => $atts['image_type'],
        'showBookTitle' => $atts['show_title'],
        'bookTitleType' => $atts['title_type'],
        'showBookAuthor' => $atts['show_author'],
        'showBookPrice' => $atts['show_price'],
        'showBookExcerpt' => $atts['show_excerpt'],
        'excerptLimit' => $atts['excerpt_limit'],
        'showBookBuyBtn' => $atts['show_buy_button'],
        'showMsl' => $atts['show_msl'],
        'mslTitleAlign' => $atts['msl_title_align'],
        'bookColumnClases' => $bookColumnClases,
        'book_container_classes' => $book_container_classes,
        'thumbnail_wrapper_classes' => $thumbnail_wrapper_classes,
        'content_wrapper_classes' => $content_wrapper_classes,
        'show_read_more_button' => $atts['show_read_more_button'],
        'heightStretch' => $atts['height_stretch'],
        'showAddToCartBtn' => $atts['show_add_to_cart_btn'],
    );
    extract($layoutArgs);
    /**
     * End Book Layout Control
     */

    $bookQuery = new WP_Query($query_args);
    echo '<div id="totalBooks" data-total-books="'.$bookQuery->found_posts.'"></div>';
    if ($bookQuery->have_posts()) :
        while ($bookQuery->have_posts()) :
            $bookQuery->the_post();
            ?>
            <div class="<?php echo esc_attr($bookColumnClases);?>">
                <div class="rswpbs-book-container<?php echo esc_attr($book_container_classes);?>">
                    <?php
                    if ('true' == $showBookImage) :
                    ?>
                    <div class="rswpbs-book-loop-image<?php echo esc_attr($thumbnail_wrapper_classes);?>">
                        <a href="<?php the_permalink(); ?>">
                        <?php
                            if ('book_cover' == $bookImageType) :
                                echo rswpbs_get_book_image(get_the_ID());
                            else:
                                if (class_exists('Rswpbs_Pro')) {
                                    rswpbs_book_mockup_image(get_the_ID());
                                }
                            endif;
                            ?>
                        </a>
                    </div>
                    <?php
                    endif;
                    ?>
                    <div class="rswpbs-book-loop-content-wrapper<?php echo esc_attr($content_wrapper_classes);?>">
                        <?php
                        if ('true' == $showBookTitle):
                        ?>
                        <h2 class="book-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                if ('book_name' == $bookTitleType) :
                                 echo esc_html(rswpbs_get_book_name(get_the_ID()));
                                else:
                                    the_title();
                                endif;
                                ?>
                            </a>
                        </h2>
                        <?php
                        endif;
                        if ('true' == $showBookAuthor) :
                        ?>
                        <h4 class="book-author"><strong><?php echo rswpbs_static_text_by(); ?></strong>
                            <?php
                            echo wp_kses_post(rswpbs_get_book_author(get_the_ID()));
                            ?>
                        </h4>
                        <?php
                        endif;
                        if ('true' == $showBookPrice) :
                        ?>
                        <div class="book-price d-flex">
                            <?php echo wp_kses_post(rswpbs_get_book_price(get_the_ID())); ?>
                        </div>
                        <?php endif;
                        if ('true' == $showBookExcerpt && !empty(rswpbs_get_book_desc())) :
                        ?>
                        <div class="book-desc d-flex">
                          <?php echo wp_kses_post(rswpbs_get_book_desc(get_the_ID(), $excerptLimit)); ?>
                        </div>
                        <?php
                        endif;
                        ?>
                        <div class="rswpbs-book-buttons-wrapper">
                        <?php
                        if ('true' == $showAddToCartBtn) :
                            $product_id = get_the_ID();
                            ?>
                            <div class="book-add-to-cart-btn">
                                <div class="cptwoointegration-cart-btn-wrapper">
                                    <form class="cart" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
                                        <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>" class="loop_add_to_cart_button button alt">
                                            <?php esc_html_e('Add to Cart', 'text-domain'); ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        endif;
                        if ('true' == $showBookBuyBtn) :
                            $product_id = get_the_ID();
                        ?>
                        <div class="book-buy-btn d-flex">
                          <?php
                          echo wp_kses_post(rswpbs_get_book_buy_btn());
                           ?>
                        </div>
                        <?php
                        endif;
                        if ('true' == $show_read_more_button) :
                        ?>
                        <div class="rswpbs-loop-read-more-button">
                            <a href="<?php the_permalink();?>"><?php echo esc_html(rswpbs_static_text_read_more());?></a>
                        </div>
                        <?php
                        endif;
                        ?>
                        </div>
                        <?php
                        if ( class_exists('Rswpbs_Pro') && 'true' == $showMsl) :
                        ?>
                        <div class="book-multiple-sales-links d-flex">
                         <?php echo rswpbs_pro_book_also_available_web_list(get_the_ID(), $mslTitleAlign); ?>
                        </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
    else:
        echo 'No posts found.';
    endif;

    // echo json_encode($response);

    wp_reset_postdata();  // Reset the global $post object
}

// AJAX handler for the search form and load more
function rs_wp_book_showcase_ajax_callback() {
    $search_criteria = $_POST;
    $atts = isset($_POST['atts']) ? $_POST['atts'] : array();
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    rs_wp_book_showcase_ajax_handler($atts, $search_criteria, $paged);
    wp_die();
}
add_action('wp_ajax_rs_wp_book_showcase', 'rs_wp_book_showcase_ajax_callback');
add_action('wp_ajax_nopriv_rs_wp_book_showcase', 'rs_wp_book_showcase_ajax_callback');
