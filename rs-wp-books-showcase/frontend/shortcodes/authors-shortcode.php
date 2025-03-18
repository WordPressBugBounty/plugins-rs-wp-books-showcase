<?php
/**
 * Author Shortcode
 */
add_shortcode('rswpbs_author_shortcode', 'rswpbs_author_shortcode_output');
function rswpbs_author_shortcode_output($atts){
    $atts = shortcode_atts(
        array(
            'layout' => 'standard_layout',
            'show_description' => 'true',
            'show_book_count' => 'true',
            'authors_per_row' => '4',
            'authors_per_page' => '4',
        ), $atts
    );

    ob_start();
    ?>
    <div class="rswpbs-authors-shortcode-wrapper"
         data-per-row="<?php echo esc_attr($atts['authors_per_row']); ?>"
         data-show-desc="<?php echo esc_attr($atts['show_description']); ?>"
         data-show-count="<?php echo esc_attr($atts['show_book_count']); ?>"
         data-per-page="<?php echo esc_attr($atts['authors_per_page']); ?>">

        <!-- Search Form -->
        <div class="rswpbs-author-search">
            <form class="rswpbs-author-search-form">
                <input type="text" name="author_search" placeholder="Search authors..." class="author-search-input">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Authors Container -->
        <div class="authors-shortcode-row rswpbs-row" id="authors-container">
            <?php echo rswpbs_get_authors_html($atts, 0, ''); ?>
        </div>

        <!-- Load More Button -->
        <div class="rswpbs-load-more-wrapper">
            <button id="rswpbs-load-more" class="rswpbs-load-more-btn">Load More</button>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
    jQuery(document).ready(function($) {
        var offset = <?php echo $atts['authors_per_page']; ?>;
        var perPage = <?php echo $atts['authors_per_page']; ?>;
        var atts = <?php echo json_encode($atts); ?>; // Fixed the atts variable declaration
        var searchQuery = '';

        // Load More Handler
        $('#rswpbs-load-more').on('click', function() {
            loadAuthors(offset, searchQuery);
        });

        // Search Handler
        $('.rswpbs-author-search-form').on('submit', function(e) {
            e.preventDefault();
            searchQuery = $('.author-search-input').val();
            offset = 0;
            $('#authors-container').empty();
            loadAuthors(0, searchQuery);
        });

        function loadAuthors(offsetVal, search) {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'load_more_authors',
                    offset: offsetVal,
                    search: search,
                    atts: atts // Now properly defined
                },
                success: function(response) {
                    if (response.success) {
                        $('#authors-container').append(response.data.html);
                        offset += perPage;

                        // Hide load more button if no more authors
                        if (!response.data.has_more) {
                            $('#rswpbs-load-more').hide();
                        } else {
                            $('#rswpbs-load-more').show();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error: ' + error);
                }
            });
        }
    });
    </script>
    <?php
    return ob_get_clean();
}

// Rest of the code (AJAX handler and HTML generator) remains the same
add_action('wp_ajax_load_more_authors', 'rswpbs_load_more_authors');
add_action('wp_ajax_nopriv_load_more_authors', 'rswpbs_load_more_authors');
function rswpbs_load_more_authors() {
    $atts = $_POST['atts'];
    $offset = intval($_POST['offset']);
    $search = sanitize_text_field($_POST['search']);

    $html = rswpbs_get_authors_html($atts, $offset, $search);

    // Get the total number of matching authors for the search
    $args = array(
        'taxonomy' => 'book-author',
        'hide_empty' => false,
        'search' => $search,
    );
    $total_authors = wp_count_terms('book-author', $args);

    // Calculate if there are more authors to load
    $per_page = intval($atts['authors_per_page']);
    $has_more = ($offset + $per_page) < $total_authors;

    // Only show "Load More" button if there are more than initial per_page results
    if ($offset === 0 && $total_authors <= $per_page) {
        $has_more = false; // Hide button if total results are less than or equal to per_page
    }

    wp_send_json_success(array(
        'html' => $html,
        'has_more' => $has_more
    ));
}
// Authors HTML Generator
function rswpbs_get_authors_html($atts, $offset, $search = '') {
    $authorsPerRow = $atts['authors_per_row'];
    $authorColumnClases = 'rswpbs-col-lg-3 rswpbs-col-md-4 author-single-column';
    if ('1' == $authorsPerRow) {
        $authorColumnClases = 'rswpbs-col-lg-12 author-single-column';
    } elseif('2' == $authorsPerRow) {
        $authorColumnClases = 'rswpbs-col-md-6 author-single-column';
    } elseif('3' == $authorsPerRow) {
        $authorColumnClases = 'rswpbs-col-lg-6 rswpbs-col-xl-4 rswpbs-col-md-6 author-single-column';
    } elseif('4' == $authorsPerRow) {
        $authorColumnClases = 'rswpbs-col-lg-4 rswpbs-col-xl-3 rswpbs-col-md-6 author-single-column';
    } elseif('6' == $authorsPerRow) {
        $authorColumnClases = 'rswpbs-col-lg-3 rswpbs-col-xl-2 rswpbs-col-md-4 author-single-column';
    }

    $args = array(
        'taxonomy' => 'book-author',
        'hide_empty' => false,
        'number' => $atts['authors_per_page'],
        'offset' => $offset
    );

    if (!empty($search)) {
        $args['search'] = $search;
    }

    $bookAuthorsTerms = get_terms($args);
    $output = '';

    if (!is_wp_error($bookAuthorsTerms) && !empty($bookAuthorsTerms)) {
        foreach($bookAuthorsTerms as $author) {
            $termLink = get_term_link($author->term_id, 'book-author');
            $authorID = 'book-author_'.$author->term_id;
            $isRswpbsPro = class_exists('Rswpbs_Pro');
            $authorImage = $isRswpbsPro ? get_term_meta($author->term_id, 'rswpbs_book_author_picture', true) : '';

            $output .= '<div class="' . esc_attr($authorColumnClases) . ' rswpbs-author-col">';
            $output .= '<div class="rswpbs-single-author-wrapper">';

            if ($isRswpbsPro && !empty($authorImage)) {
                $output .= '<div class="rswpbs-author-profile-picture-wrapper">';
                $output .= '<div class="author-profile-picture-container">';
                $output .= '<a href="' . esc_url($termLink) . '">';
                $output .= '<img src="' . $authorImage . '" alt="' . esc_attr($author->name) . '">';
                $output .= '</a>';
                $output .= '</div>';

                if ($isRswpbsPro) {
                    $output .= '<div class="author-social-links-wrapper">';
                    if (function_exists('rswpbs_pro_book_author_social_links')) {
                        ob_start();
                        rswpbs_pro_book_author_social_links($authorID);
                        $output .= ob_get_clean();
                    }
                    $output .= '</div>';
                }
                $output .= '</div>';
            }

            if ($isRswpbsPro && empty($authorImage)) {
                ob_start();
                rswpbs_pro_book_author_social_links($authorID);
                $output .= ob_get_clean();
            }

            $output .= '<div class="author-name">';
            $output .= '<h2><a href="' . esc_url($termLink) . '">' . wp_kses_post($author->name) . '</a></h2>';
            $output .= '</div>';

            if ('true' == $atts['show_book_count']) {
                $post_count = get_term($author->term_id, 'book-author')->count;
                $bookText = 'Book';
                if ($post_count > 1) {
                    $bookText .= 's';
                }
                $output .= '<div class="author-book-count">';
                $output .= '<h5>';
                $output .= sprintf('<a href="%3$s">%1$d %2$s</a>',
                    $post_count,
                    __($bookText, 'rswpbs'),
                    esc_url($termLink)
                );
                $output .= '</h5>';
                $output .= '</div>';
            }

            if ('true' == $atts['show_description'] && !empty($author->description)) {
                $output .= '<div class="author-description">';
                $output .= '<p>' . wp_kses_post($author->description) . '</p>';
                $output .= '</div>';
            }

            $output .= '<div class="view-author-profile-button">';
            $output .= '<a href="' . esc_url($termLink) . '">' . esc_html__('View Profile', 'rswpbs') . '</a>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
    }

    return $output;
}

