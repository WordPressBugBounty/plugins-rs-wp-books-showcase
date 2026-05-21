<?php
if (!wp_is_block_theme()) {
  return;
}

/**
 * ১. সাইট এডিটরে (Appearance > Editor > Templates) বাই ডিফল্ট সব টেমপ্লেট (Single, Archive & Taxonomies) তৈরি রাখা
 */
add_filter( 'get_block_templates', 'rswpbs_register_default_fse_templates', 10, 3 );

function rswpbs_register_default_fse_templates( $query_result, $query, $template_type ) {
    // যদি ব্লক থিম না হয় অথবা টেমপ্লেট টাইপ না মিলে, তবে ডিফল্ট রিটার্ন
    if ( ! wp_is_block_theme() || 'wp_template' !== $template_type ) {
        return $query_result;
    }

    $theme_slug = wp_get_theme()->get_stylesheet();

    // প্লাগিনের সবগুলো টেমপ্লেট স্লাগ
    $my_templates = array(
        'single-book',
        'archive-book',
        'taxonomy-book-category',
        'taxonomy-book-author',
        'taxonomy-book-series',
        'taxonomy-book-publisher'
    );

    foreach ( $my_templates as $slug ) {

        // চেক করা হচ্ছে কোনো নির্দিষ্ট স্লাগ খোঁজা হচ্ছে কিনা এবং সেটি আমাদের লিস্টে আছে কিনা
        if ( isset( $query['slug__in'] ) && ! in_array( $slug, $query['slug__in'], true ) ) {
            continue;
        }

        // অলরেডি ডাটাবেজে ইউজার নিজে কাস্টমাইজড করে সেভ করে রেখেছে কিনা চেক
        $already_exists = false;
        foreach ( $query_result as $existing_template ) {
            if ( $existing_template->slug === $slug ) {
                $already_exists = true;
                break;
            }
        }

        // যদি আগে থেকে তৈরি করা না থাকে, তবে আমরা ডিফল্ট অবজেক্ট পুশ করব
        if ( ! $already_exists && class_exists( 'WP_Block_Template' ) ) {

            $title = __( 'Book Template', 'rswpbs' );
            $shortcode = '';

            // ডায়নামিক টাইটেল ও আলাদা আলাদা শর্টকোড নির্ধারণ
            if ( 'single-book' === $slug ) {
                $title = __( 'Single Book (RSWPBS)', 'rswpbs' );
                $shortcode = '[rswpbs_book_single_page]';
            } elseif ( 'archive-book' === $slug ) {
                $title = __( 'Book Archive (RSWPBS)', 'rswpbs' );
                $shortcode = '[rswpbs_book_archive_page]'; // মেইন আর্কাইভের জন্য
            } elseif ( 'taxonomy-book-category' === $slug ) {
                $title = __( 'Book Category Archive (RSWPBS)', 'rswpbs' );
                $shortcode = '[rswpbs_book_category_page]';
            } elseif ( 'taxonomy-book-author' === $slug ) {
                $title = __( 'Book Author Archive (RSWPBS)', 'rswpbs' );
                $shortcode = '[rswpbs_book_author_page]';
            } elseif ( 'taxonomy-book-series' === $slug ) {
                $title = __( 'Book Series Archive (RSWPBS)', 'rswpbs' );
                $shortcode = '[rswpbs_book_series_page]';
            } elseif ( 'taxonomy-book-publisher' === $slug ) {
                $title = __( 'Book Publisher Archive (RSWPBS)', 'rswpbs' );
                $shortcode = '[rswpbs_book_publisher_page]'; // ধরে নিচ্ছি এই শর্টকোডটি আছে
            }

            $template = new WP_Block_Template();
            $template->id             = $theme_slug . '//' . $slug;
            $template->theme          = $theme_slug;
            $template->slug           = $slug;
            $template->source         = 'plugin';
            $template->type           = 'wp_template';
            $template->title          = $title;
            $template->status         = 'publish';
            $template->has_theme_file = false;

            // কন্টেন্ট স্ট্রাকচার
            $template->content = '
            <!-- wp:template-part {"slug":"header","tagName":"header"} /-->
            <!-- wp:shortcode -->
            ' . $shortcode . '
            <!-- /wp:shortcode -->
            <!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
                ';

            $query_result[] = $template;
        }
    }

    return $query_result;
}

/**
 * ২. ফ্রন্টএন্ডে রেন্ডার করার সময় এই ডিফল্ট টেমপ্লেটগুলো অটো-লোড করানো
 */
add_filter( 'pre_get_block_template', 'rswpbs_load_default_fse_templates_frontend', 10, 3 );

function rswpbs_load_default_fse_templates_frontend( $block_template, $id, $template_type ) {
    if ( ! wp_is_block_theme() || is_admin() || 'wp_template' !== $template_type || ! class_exists( 'WP_Block_Template' ) ) {
        return $block_template;
    }

    // যদি ইউজার নিজে সাইট এডিটর থেকে এটি মডিফাই করে সেভ করে থাকে, তবে ইউজারের কাস্টম ডিজাইনই লোড হবে
    if ( null !== $block_template && $block_template->source === 'custom' ) {
        return $block_template;
    }

    $theme_slug = wp_get_theme()->get_stylesheet();

    $slug = '';
    $shortcode = '';

    // কন্ডিশনাল রিকোয়েস্ট ম্যাপিং (প্রতিটি ট্যাক্সনমির জন্য আলাদা শর্টকোড এবং সঠিক ফলব্যাক কন্ডিশন)
    if ( $id === $theme_slug . '//single-book' || is_singular( 'book' ) ) {
        $slug = 'single-book';
        $shortcode = '[rswpbs_book_single_page]';
    } elseif ( $id === $theme_slug . '//archive-book' || is_post_type_archive( 'book' ) ) {
        $slug = 'archive-book';
        $shortcode = '[rswpbs_book_archive_page]';
    } elseif ( $id === $theme_slug . '//taxonomy-book-category' || is_tax( 'book-category' ) ) {
        $slug = 'taxonomy-book-category';
        $shortcode = '[rswpbs_book_category_page]';
    } elseif ( $id === $theme_slug . '//taxonomy-book-author' || is_tax( 'book-author' ) ) {
        $slug = 'taxonomy-book-author';
        $shortcode = '[rswpbs_book_author_page]';
    } elseif ( $id === $theme_slug . '//taxonomy-book-series' || is_tax( 'book-series' ) ) {
        $slug = 'taxonomy-book-series';
        $shortcode = '[rswpbs_book_series_page]';
    } elseif ( $id === $theme_slug . '//taxonomy-book-publisher' || is_tax( 'book-publisher' ) ) {
        $slug = 'taxonomy-book-publisher';
        $shortcode = '[rswpbs_book_publisher_page]';
    }

    // যদি আমাদের টেমপ্লেটগুলোর কোনো একটির সাথে ম্যাচ করে
    if ( ! empty( $slug ) && ! empty( $shortcode ) ) {

        $template = new WP_Block_Template();
        $template->id             = $theme_slug . '//' . $slug;
        $template->theme          = $theme_slug;
        $template->slug           = $slug;
        $template->source         = 'plugin';
        $template->type           = 'wp_template';
        $template->status         = 'publish';
        $template->has_theme_file = false;

        $template->content = '
            <!-- wp:template-part {"slug":"header","tagName":"header"} /-->
            <!-- wp:shortcode -->
            ' . $shortcode . '
            <!-- /wp:shortcode -->
            <!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
            ';

        return $template;
    }
    return $block_template;
}