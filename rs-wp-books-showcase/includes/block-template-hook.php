<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * =========================================================
 * RSWPBS - Plugin Block Templates
 * =========================================================
 */

add_action( 'init', 'rswpbs_create_block_templates' );

function rswpbs_create_block_templates() {

    if ( ! wp_is_block_theme() ) {
        return;
    }

    /**
     * Prevent repeated runs
     */
    if ( get_option( 'rswpbs_templates_created_v3' ) ) {
        return;
    }

    $theme_slug = wp_get_theme()->get_stylesheet();

    $templates = array(

        'single-book' => array(
            'title'   => 'Single Book (RSWPBS)',
            'content' => '
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:shortcode -->
[rswpbs_book_single_page]
<!-- /wp:shortcode -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
',
        ),

        'archive-book' => array(
            'title'   => 'Book Archive (RSWPBS)',
            'content' => '
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:shortcode -->
[rswpbs_book_archive_page]
<!-- /wp:shortcode -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
',
        ),

        'taxonomy-book-category' => array(
            'title'   => 'Book Category Archive (RSWPBS)',
            'content' => '
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:shortcode -->
[rswpbs_book_category_page]
<!-- /wp:shortcode -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
',
        ),

        'taxonomy-book-author' => array(
            'title'   => 'Book Author Archive (RSWPBS)',
            'content' => '
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:shortcode -->
[rswpbs_book_author_page]
<!-- /wp:shortcode -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
',
        ),

        'taxonomy-book-series' => array(
            'title'   => 'Book Series Archive (RSWPBS)',
            'content' => '
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:shortcode -->
[rswpbs_book_series_page]
<!-- /wp:shortcode -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
',
        ),

        'taxonomy-book-publisher' => array(
            'title'   => 'Book Publisher Archive (RSWPBS)',
            'content' => '
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:shortcode -->
[rswpbs_book_publisher_page]
<!-- /wp:shortcode -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
',
        ),

    );

    foreach ( $templates as $slug => $template ) {

        $existing = get_page_by_path( $slug, OBJECT, 'wp_template' );

        if ( $existing ) {
            continue;
        }

        wp_insert_post(
            array(
                'post_type'    => 'wp_template',
                'post_status'  => 'publish',
                'post_title'   => $template['title'],
                'post_name'    => $slug,
                'post_content' => $template['content'],
                'tax_input'    => array(
                    'wp_theme' => array( $theme_slug ),
                ),
            )
        );
    }

    update_option( 'rswpbs_templates_created_v3', 1 );
}