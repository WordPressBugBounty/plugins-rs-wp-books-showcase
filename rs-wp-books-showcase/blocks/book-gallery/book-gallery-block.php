<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render callback for the Book Block.
 *
 * @param array $attributes Block attributes.
 * @return string Block HTML.
 */
function rswpbs_render_book_block($attributes) {
    // Get attributes with fallback defaults.
    $booksPerPage = isset($attributes['booksPerPage']) ? absint($attributes['booksPerPage']) : 10;
    $booksPerRow = isset($attributes['booksPerRow']) ? absint($attributes['booksPerRow']) : 4;
    $order = isset($attributes['order']) ? sanitize_text_field($attributes['order']) : 'DESC';
    $orderby = isset($attributes['orderby']) ? sanitize_text_field($attributes['orderby']) : 'date';

    // Category and author filters
    $categoriesInclude = isset($attributes['categoriesInclude']) ? sanitize_text_field($attributes['categoriesInclude']) : '';
    $categoriesExclude = isset($attributes['categoriesExclude']) ? sanitize_text_field($attributes['categoriesExclude']) : '';
    $authorsInclude = isset($attributes['authorsInclude']) ? sanitize_text_field($attributes['authorsInclude']) : '';
    $authorsExclude = isset($attributes['authorsExclude']) ? sanitize_text_field($attributes['authorsExclude']) : '';
    $excludeBooks = isset($attributes['excludeBooks']) ? sanitize_text_field($attributes['excludeBooks']) : '';
    $seriesInclude = isset($attributes['seriesInclude']) ? sanitize_text_field($attributes['seriesInclude']) : '';
    $seriesExclude = isset($attributes['seriesExclude']) ? sanitize_text_field($attributes['seriesExclude']) : '';

    // Display settings
    $showPagination = isset($attributes['showPagination']) ? ($attributes['showPagination'] ? 'true' : 'false') : 'true';
    $showAuthor = isset($attributes['showAuthor']) ? ($attributes['showAuthor'] ? 'true' : 'false') : 'true';
    $showTitle = isset($attributes['showTitle']) ? ($attributes['showTitle'] ? 'true' : 'false') : 'true';
    $titleType = isset($attributes['titleType']) ? sanitize_text_field($attributes['titleType']) : 'title';
    $showImage = isset($attributes['showImage']) ? ($attributes['showImage'] ? 'true' : 'false') : 'true';
    $imageType = isset($attributes['imageType']) ? sanitize_text_field($attributes['imageType']) : 'book_cover';
    $imagePosition = isset($attributes['imagePosition']) ? sanitize_text_field($attributes['imagePosition']) : 'top';

    // Excerpt settings
    $showExcerpt = isset($attributes['showExcerpt']) ? ($attributes['showExcerpt'] ? 'true' : 'false') : 'true';
    $excerptType = isset($attributes['excerptType']) ? sanitize_text_field($attributes['excerptType']) : 'excerpt';
    $excerptLimit = isset($attributes['excerptLimit']) ? absint($attributes['excerptLimit']) : 30;

    // Price and buttons
    $showPrice = isset($attributes['showPrice']) ? ($attributes['showPrice'] ? 'true' : 'false') : 'true';
    $showBuyButton = isset($attributes['showBuyButton']) ? ($attributes['showBuyButton'] ? 'true' : 'false') : 'true';
    $showReadMoreButton = isset($attributes['showReadMoreButton']) ? ($attributes['showReadMoreButton'] ? 'true' : 'false') : 'false';

    // Additional settings
    $showMsl = isset($attributes['showMsl']) ? ($attributes['showMsl'] ? 'true' : 'false') : 'false';
    $mslTitleAlign = isset($attributes['mslTitleAlign']) ? sanitize_text_field($attributes['mslTitleAlign']) : 'center';
    $contentAlign = isset($attributes['contentAlign']) ? sanitize_text_field($attributes['contentAlign']) : 'center';

    // Forms and layout
    $showSearchForm = isset($attributes['showSearchForm']) ? ($attributes['showSearchForm'] ? 'true' : 'false') : 'true';
    $showSortingForm = isset($attributes['showSortingForm']) ? ($attributes['showSortingForm'] ? 'true' : 'false') : 'true';
    $showMasonryLayout = isset($attributes['showMasonryLayout']) ? ($attributes['showMasonryLayout'] ? 'true' : 'false') : 'false';
    $heightStretch = isset($attributes['heightStretch']) ? ($attributes['heightStretch'] ? 'true' : 'false') : 'true';

    // Alignment
    $align = isset($attributes['align']) ? sanitize_text_field($attributes['align']) : 'center';

    // Button styling
    $buttonBackgroundColorNormal = isset($attributes['buttonBackgroundColorNormal']) ? sanitize_hex_color($attributes['buttonBackgroundColorNormal']) : '#0073aa';
    $buttonTextColorNormal = isset($attributes['buttonTextColorNormal']) ? sanitize_hex_color($attributes['buttonTextColorNormal']) : '#ffffff';
    $buttonBorderRadiusNormal = isset($attributes['buttonBorderRadiusNormal']) ? absint($attributes['buttonBorderRadiusNormal']) : 4;
    $buttonPaddingNormal = isset($attributes['buttonPaddingNormal']) ? sanitize_text_field($attributes['buttonPaddingNormal']) : '10px 20px';
    $buttonBackgroundColorHover = isset($attributes['buttonBackgroundColorHover']) ? sanitize_hex_color($attributes['buttonBackgroundColorHover']) : '#005d87';
    $buttonTextColorHover = isset($attributes['buttonTextColorHover']) ? sanitize_hex_color($attributes['buttonTextColorHover']) : '#ffffff';
    $buttonBorderRadiusHover = isset($attributes['buttonBorderRadiusHover']) ? absint($attributes['buttonBorderRadiusHover']) : 4;
    $buttonPaddingHover = isset($attributes['buttonPaddingHover']) ? sanitize_text_field($attributes['buttonPaddingHover']) : '10px 20px';

    // Construct the shortcode dynamically
    $shortcode = "[rswpbs_book_gallery
        books_per_page=\"$booksPerPage\"
        books_per_row=\"$booksPerRow\"
        categories_include=\"$categoriesInclude\"
        categories_exclude=\"$categoriesExclude\"
        authors_include=\"$authorsInclude\"
        authors_exclude=\"$authorsExclude\"
        exclude_books=\"$excludeBooks\"
        series_include=\"$seriesInclude\"
        series_exclude=\"$seriesExclude\"
        order=\"$order\"
        orderby=\"$orderby\"
        show_pagination=\"$showPagination\"
        show_author=\"$showAuthor\"
        show_title=\"$showTitle\"
        title_type=\"$titleType\"
        show_image=\"$showImage\"
        image_type=\"$imageType\"
        image_position=\"$imagePosition\"
        show_excerpt=\"$showExcerpt\"
        excerpt_type=\"$excerptType\"
        excerpt_limit=\"$excerptLimit\"
        show_price=\"$showPrice\"
        show_buy_button=\"$showBuyButton\"
        show_read_more_button=\"$showReadMoreButton\"
        show_msl=\"$showMsl\"
        msl_title_align=\"$mslTitleAlign\"
        content_align=\"$contentAlign\"
        show_search_form=\"$showSearchForm\"
        show_sorting_form=\"$showSortingForm\"
        show_masonry_layout=\"$showMasonryLayout\"
        height_stretch=\"$heightStretch\"
        align=\"$align\"
        button_background_color_normal=\"$buttonBackgroundColorNormal\"
        button_text_color_normal=\"$buttonTextColorNormal\"
        button_border_radius_normal=\"$buttonBorderRadiusNormal\"
        button_padding_normal=\"$buttonPaddingNormal\"
        button_background_color_hover=\"$buttonBackgroundColorHover\"
        button_text_color_hover=\"$buttonTextColorHover\"
        button_border_radius_hover=\"$buttonBorderRadiusHover\"
        button_padding_hover=\"$buttonPaddingHover\"]";

    // Apply alignment class
    $block_classes = 'wp-block-rswpbs-book-block';
    if ($align) {
        $block_classes .= ' align' . esc_attr($align);
    }

    // Output the shortcode wrapped in a div with alignment class
    ob_start();
    echo '<div class="' . esc_attr($block_classes) . '">';
    echo do_shortcode($shortcode);
    echo '</div>';
    return ob_get_clean();
}

/**
 * Registers the Book Block using block.json.
 */
function rswpbs_register_book_block() {
    register_block_type(__DIR__ . '/block.json', array(
        'render_callback' => 'rswpbs_render_book_block',
    ));
}
add_action('init', 'rswpbs_register_book_block');

/**
 * Handles the shortcode rendering via REST API.
 *
 * @param WP_REST_Request $request REST request object.
 * @return string Rendered shortcode output.
 */
function rswpbs_render_shortcode($request) {
    $params = $request->get_params();

    // Ensure correct data types for attributes
    $params['books_per_page'] = isset($params['booksPerPage']) ? intval($params['booksPerPage']) : 8;
    $params['books_per_row'] = isset($params['booksPerRow']) ? intval($params['booksPerRow']) : 4;
    $params['categories_include'] = isset($params['categoriesInclude']) ? sanitize_text_field($params['categoriesInclude']) : '';
    $params['categories_exclude'] = isset($params['categoriesExclude']) ? sanitize_text_field($params['categoriesExclude']) : '';
    $params['authors_include'] = isset($params['authorsInclude']) ? sanitize_text_field($params['authorsInclude']) : '';
    $params['authors_exclude'] = isset($params['authorsExclude']) ? sanitize_text_field($params['authorsExclude']) : '';
    $params['exclude_books'] = isset($params['excludeBooks']) ? sanitize_text_field($params['excludeBooks']) : '';
    $params['order'] = isset($params['order']) ? sanitize_text_field($params['order']) : 'DESC';
    $params['orderby'] = isset($params['orderby']) ? sanitize_text_field($params['orderby']) : 'date';

    // Boolean values (Convert to 'true' or 'false')
    $params['show_pagination'] = isset($params['showPagination']) ? ($params['showPagination'] === 'true' ? 'true' : 'false') : 'true';
    $params['show_author'] = isset($params['showAuthor']) ? ($params['showAuthor'] === 'true' ? 'true' : 'false') : 'true';
    $params['show_title'] = isset($params['showTitle']) ? ($params['showTitle'] === 'true' ? 'true' : 'false') : 'true';
    $params['title_type'] = isset($params['titleType']) ? sanitize_text_field($params['titleType']) : 'title';
    $params['show_image'] = isset($params['showImage']) ? ($params['showImage'] === 'true' ? 'true' : 'false') : 'true';
    $params['image_type'] = isset($params['imageType']) ? sanitize_text_field($params['imageType']) : 'book_cover';
    $params['image_position'] = isset($params['imagePosition']) ? sanitize_text_field($params['imagePosition']) : 'top';

    // Excerpt settings
    $params['show_excerpt'] = isset($params['showExcerpt']) ? ($params['showExcerpt'] === 'true' ? 'true' : 'false') : 'true';
    $params['excerpt_type'] = isset($params['excerptType']) ? sanitize_text_field($params['excerptType']) : 'excerpt';
    $params['excerpt_limit'] = isset($params['excerptLimit']) ? absint($params['excerptLimit']) : 30;

    // Price and buttons
    $params['show_price'] = isset($params['showPrice']) ? ($params['showPrice'] === 'true' ? 'true' : 'false') : 'true';
    $params['show_buy_button'] = isset($params['showBuyButton']) ? ($params['showBuyButton'] === 'true' ? 'true' : 'false') : 'true';
    $params['show_read_more_button'] = isset($params['showReadMoreButton']) ? ($params['showReadMoreButton'] === 'true' ? 'true' : 'false') : 'false';

    // Additional settings
    $params['show_msl'] = isset($params['showMsl']) ? ($params['showMsl'] === 'true' ? 'true' : 'false') : 'false';
    $params['msl_title_align'] = isset($params['mslTitleAlign']) ? sanitize_text_field($params['mslTitleAlign']) : 'center';
    $params['content_align'] = isset($params['contentAlign']) ? sanitize_text_field($params['contentAlign']) : 'center';

    // Forms and layout
    $params['show_search_form'] = isset($params['showSearchForm']) ? ($params['showSearchForm'] === 'true' ? 'true' : 'false') : 'true';
    $params['show_sorting_form'] = isset($params['showSortingForm']) ? ($params['showSortingForm'] === True ? 'true' : 'false') : 'true';
    $params['show_masonry_layout'] = isset($params['showMasonryLayout']) ? ($params['showMasonryLayout'] === 'true' ? 'true' : 'false') : 'false';
    $params['height_stretch'] = isset($params['heightStretch']) ? ($params['heightStretch'] === 'true' ? 'true' : 'false') : 'true';

    // Alignment
    $params['align'] = isset($params['align']) ? sanitize_text_field($params['align']) : 'center';

    // Button styling
    $params['button_background_color_normal'] = isset($params['buttonBackgroundColorNormal']) ? sanitize_hex_color($params['buttonBackgroundColorNormal']) : '#0073aa';
    $params['button_text_color_normal'] = isset($params['buttonTextColorNormal']) ? sanitize_hex_color($params['buttonTextColorNormal']) : '#ffffff';
    $params['button_border_radius_normal'] = isset($params['buttonBorderRadiusNormal']) ? absint($params['buttonBorderRadiusNormal']) : 4;
    $params['button_padding_normal'] = isset($params['buttonPaddingNormal']) ? sanitize_text_field($params['buttonPaddingNormal']) : '10px 20px';
    $params['button_background_color_hover'] = isset($params['buttonBackgroundColorHover']) ? sanitize_hex_color($params['buttonBackgroundColorHover']) : '#005d87';
    $params['button_text_color_hover'] = isset($params['buttonTextColorHover']) ? sanitize_hex_color($params['buttonTextColorHover']) : '#ffffff';
    $params['button_border_radius_hover'] = isset($params['buttonBorderRadiusHover']) ? absint($params['buttonBorderRadiusHover']) : 4;
    $params['button_padding_hover'] = isset($params['buttonPaddingHover']) ? sanitize_text_field($params['buttonPaddingHover']) : '10px 20px';

    // Construct the shortcode string dynamically
    $shortcode = '[rswpbs_book_gallery';
    foreach ($params as $key => $value) {
        $shortcode .= " {$key}=\"{$value}\"";
    }
    $shortcode .= ']';

    return do_shortcode($shortcode);
}

// Register the REST API route
function rswpbs_register_rest_routes() {
    register_rest_route('rswpbs/v1', '/render-shortcode', [
        'methods' => 'GET',
        'callback' => 'rswpbs_render_shortcode',
        'permission_callback' => '__return_true' // Allow public access
    ]);
}
add_action('rest_api_init', 'rswpbs_register_rest_routes');

/**
 * Enqueue dynamic styles for the block.
 */
function rswpbs_enqueue_dynamic_styles() {
    global $post;

    if (!$post || !has_block('rswpbs/book-block', $post->post_content)) {
        return; // Exit if the block is not present in the post content.
    }

    // Parse the post content to get all instances of the block.
    $blocks = parse_blocks($post->post_content);

    // Find the first instance of the rswpbs/book-block.
    $block_attributes = null;
    foreach ($blocks as $block) {
        if ($block['blockName'] === 'rswpbs/book-block') {
            $block_attributes = $block['attrs'];
            break; // Use the first instance of the block.
        }
    }

    if (!$block_attributes) {
        return; // Exit if no attributes are found.
    }

    // Generate dynamic CSS based on block attributes.
    $css = sprintf(
        '.wp-block-rswpbs-book-block .book-buy-button, .wp-block-rswpbs-book-block .book-read-more-button, .wp-block-rswpbs-book-block .book-add-to-cart-button {
            background-color: %s;
            color: %s;
            border-radius: %dpx;
            padding: %s;
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        .wp-block-rswpbs-book-block .book-buy-button:hover, .wp-block-rswpbs-book-block .book-read-more-button:hover, .wp-block-rswpbs-book-block .book-add-to-cart-button:hover {
            background-color: %s;
            color: %s;
            border-radius: %dpx;
            padding: %s;
        }',
        esc_attr($block_attributes['buttonBackgroundColorNormal'] ?? '#0073aa'),
        esc_attr($block_attributes['buttonTextColorNormal'] ?? '#ffffff'),
        absint($block_attributes['buttonBorderRadiusNormal'] ?? 4),
        esc_attr($block_attributes['buttonPaddingNormal'] ?? '10px 20px'),
        esc_attr($block_attributes['buttonBackgroundColorHover'] ?? '#005d87'),
        esc_attr($block_attributes['buttonTextColorHover'] ?? '#ffffff'),
        absint($block_attributes['buttonBorderRadiusHover'] ?? 4),
        esc_attr($block_attributes['buttonPaddingHover'] ?? '10px 20px')
    );

    wp_add_inline_style('rswpbs-book-block-styles', $css);
}
add_action('wp_enqueue_scripts', 'rswpbs_enqueue_dynamic_styles');

// /**
//  * Enqueue block assets.
//  */
// function rswpbs_enqueue_block_assets() {
//     wp_enqueue_style(
//         'rswpbs-book-block-styles',
//         plugins_url('build/style-index.css', __FILE__),
//         [],
//         filemtime(plugin_dir_path(__FILE__) . 'build/style-index.css')
//     );
// }
// add_action('enqueue_block_assets', 'rswpbs_enqueue_block_assets');