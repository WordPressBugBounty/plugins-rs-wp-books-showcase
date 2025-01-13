<?php
add_action('in_admin_header', function () {
	$getCurrentScreen = get_current_screen();
	if ('book_page_rswpbs-tutorial' === $getCurrentScreen->id) {
		remove_all_actions('admin_notices');
  		remove_all_actions('all_admin_notices');
	}
}, 1000);

function rswpbs_tutorial_page() {
    // Add the settings page
    add_submenu_page(
        'edit.php?post_type=book', // Parent menu slug
        'RS WP Book Showcase Tutorials', // Page title
        'Tutorials & Shortcodes', // Menu title
        'manage_options', // Capability
        'rswpbs-tutorial', // Menu slug
        'rswpbs_tutorial_page_content', // Callback function
        5 //Position
    );

    if (!class_exists('Rswpbs_Pro')) :
        add_submenu_page(
            'edit.php?post_type=book', // Parent menu slug
            'RS WP Book Showcase CSV IMPORT', // Page title
            'CSV IMPORT', // Menu title
            'manage_options', // Capability
            'rswpbs-csv-import', // Menu slug
            'rswpbs_csv_import_dummy_content' // Callback function
        );
    endif;
}
add_action( 'admin_menu', 'rswpbs_tutorial_page', 10);

function rswpbs_tutorial_page_content() {
    ?>
    <div class="tutorial-page-wrapper">
        <div class="tutorial-page-inner">
            <div class="rswpbs-container">
                <div class="rswpbs-row justify-content-between">
                    <div class="rswpbs-col-lg-6 tutorial-left-column">
                        <div class="page-header-section">
                             <div class="welcome-column">
                                <h1><?php esc_html_e('Thank you for choosing RS WP BOOK SHOWCASE!', 'rswpbs'); ?></h1>
                                <p><?php esc_html_e('Whether you\'re a free user or enjoying the premium version, your choice matters to us. We are committed to providing top-notch support to all our users.', 'rswpbs'); ?></p>
                                <div class="buttons">
                                    <a class="documentation" href="<?php echo esc_url('https://rswpthemes.com/documentations/');?>"><?php esc_html_e( 'Documentation', 'rswpbs' );?></a>
                                    <a class="plugin-demo" href="<?php echo esc_url('https://demo.rswpthemes.com/rs-wp-book-showcase-demo/');?>"><?php esc_html_e( 'live Demo With Shortcode', 'rswpbs' );?></a>
                                    <?php
                                    if (!class_exists('Rswpbs_Pro')) :
                                    ?>
                                    <a class="upgrade-to-pro" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a>
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="tutorial-single-section">
                            <div class="shortcode-title mt-5">
                               <h2><?php esc_html_e('Book Gallery Shortcode', RSWPBS_TEXT_DOMAIN); ?></h2>
                               <p><?php esc_html_e('Copy this shortcode and past it in your books page.', RSWPBS_TEXT_DOMAIN);?></p>
                                <?php
                                if (!class_exists('Rswpbs_Pro')) :
                                ?>
                                <p><?php esc_html_e('Upgrade to the Pro version to unlock powerful features! With Elementor Widget or Gutenberg Blocks, you\'ll have complete visual control over your book gallery\'s layout and query settings. Elevate your design and customization options – experience the enhanced flexibility of the Pro version today!', 'rswpbs'); ?><a href="<?php echo esc_url( 'https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/' );?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a></p>
                               <?php
                                endif;
                               ?>
                            </div>
                            <div class="shortcode-wrapper">
                               <div class="shortcode-container">
                                    <pre>[rswpbs_book_gallery books_per_page="8" books_per_row="4" categories_include="false" categories_exclude="false" authors_include="false" authors_exclude="false" exclude_books="false" order="DESC" orderby="date" show_pagination="true" show_author="true" show_title="true" title_type="title" show_image="true" image_type="book_cover" image_position="top" show_excerpt="true" excerpt_type="excerpt" excerpt_limit="30" show_price="true" show_buy_button="true" show_msl="false" msl_title_align="center" content_align="center" show_search_form="true" show_sorting_form="true" show_read_more_button="false" show_masonry_layout="false" height_stretch="true"]</pre>
                               </div>
                            </div>
                        </div>
                        <div class="tutorial-single-section">
                            <div class="shortcode-title">
                               <h2><?php esc_html_e('Book Slider Shortcode', RSWPBS_TEXT_DOMAIN); ?></h2>
                               <p><?php esc_html_e('Copy this shortcode and past it anywhere of the page/post where you want to show book slider.', RSWPBS_TEXT_DOMAIN);?></p>
                                <?php
                                if (!class_exists('Rswpbs_Pro')) :
                                ?>
                               <p><?php esc_html_e('Upgrade to the Pro version to unlock powerful features! With Elementor Widget or Gutenberg Blocks, you\'ll have complete visual control over your book slider\'s layout and query settings. Elevate your design and customization options – experience the enhanced flexibility of the Pro version today!', 'rswpbs'); ?><a href="<?php echo esc_url( 'https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/' );?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a></p>
                               <?php
                                endif;
                               ?>
                            </div>
                            <div class="shortcode-wrapper">
                               <div class="shortcode-container">
                                    <pre>[rswpbs_book_slider books_per_page="8" categories_include="false" categories_exclude="false" authors_include="false" authors_exclude="false" exclude_books="false" order="DESC" orderby="date" show_author="true" show_title="true" title_type="title" show_image="true" image_type="book_cover" image_position="top" show_excerpt="true" excerpt_type="excerpt" excerpt_limit="30" show_price="true" show_buy_button="true" show_msl="false" msl_title_align="center" content_align="center" sts_l_screen="4" sts_m_screen="3" sts_s_screen="1" slider_style="carousel" show_read_more_button="false"]</pre>
                               </div>
                            </div>
                        </div>
                        <div class="tutorial-single-section">
                            <div class="shortcode-title">
                               <h2><?php esc_html_e('Pages Shortcodes', RSWPBS_TEXT_DOMAIN); ?></h2>
                               <p><?php esc_html_e('These Shortcodes mostly created to create block theme template for book single page, book category, book author, book series archive pages.', RSWPBS_TEXT_DOMAIN);?></p>
                                <?php
                                if (!class_exists('Rswpbs_Pro')) :
                                ?>
                               <p><?php esc_html_e('Upgrade to the Pro version to unlock powerful features! With Elementor Widget or Gutenberg Blocks', 'rswpbs'); ?><a href="<?php echo esc_url( 'https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/' );?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a></p>
                               <?php
                                endif;
                               ?>
                            </div>
                            <div class="shortcode-wrapper">
                               <div class="shortcode-container">
                                    <pre>[rswpbs_book_single_page]</pre>
                               </div>
                            </div>
                            <div class="shortcode-wrapper">
                               <div class="shortcode-container">
                                    <pre>[rswpbs_book_author_page]</pre>
                               </div>
                            </div>
                            <div class="shortcode-wrapper">
                               <div class="shortcode-container">
                                    <pre>[rswpbs_book_category_page]</pre>
                               </div>
                            </div>
                            <div class="shortcode-wrapper">
                               <div class="shortcode-container">
                                    <pre>[rswpbs_book_series_page]</pre>
                               </div>
                            </div>
                        </div>
                        <div class="tutorial-single-section">
                            <div class="shortcode-title">
                               <h2><?php esc_html_e('Authors Shortcode', RSWPBS_TEXT_DOMAIN); ?></h2>
                               <p><?php esc_html_e('Copy this shortcode and past it anywhere of the page/post where you want to show All Book Authors.', RSWPBS_TEXT_DOMAIN);?></p>
                                <?php
                                if (!class_exists('Rswpbs_Pro')) :
                                ?>
                               <p><?php esc_html_e('Upgrade to the Pro version to unlock powerful features! With Elementor Widget or Gutenberg Blocks', 'rswpbs'); ?><a href="<?php echo esc_url( 'https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/' );?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a></p>
                               <?php
                                endif;
                               ?>
                            </div>
                            <div class="shortcode-wrapper">
                               <div class="shortcode-container">
                                    <pre>[rswpbs_author_shortcode show_description="true" show_book_count="true" authors_per_row="4"]</pre>
                               </div>
                            </div>
                        </div>
                        <div class="tutorial-single-section">
                            <div class="shortcode-title">
                               <h2><?php esc_html_e('Single Book Shortcode', RSWPBS_TEXT_DOMAIN); ?></h2>
                               <p><?php esc_html_e('Copy this shortcode and past it anywhere of the page/post where you want to a single book. just make sure you have added book_id', RSWPBS_TEXT_DOMAIN);?></p>
                                <?php
                                if (!class_exists('Rswpbs_Pro')) :
                                ?>
                               <p><?php esc_html_e('Upgrade to the Pro version to unlock powerful features! With Elementor Widget or Gutenberg Blocks', 'rswpbs'); ?><a href="<?php echo esc_url( 'https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/' );?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a></p>
                               <?php
                                endif;
                               ?>
                            </div>
                            <div class="shortcode-wrapper">
                               <div class="shortcode-container">
                                    <pre>[rswpbs_single_book show_sample_content="true" show_title="true" show_ratings="true" show_description="true" image_type="book_cover" show_price="true" show_msl="true" book_id="Your Book ID Here"]</pre>
                               </div>
                            </div>
                        </div>
                    </div>
                    <div class="rswpbs-col-lg-6 tutorial-right-column">
                        <div class="page-header-section">
                            <div class="support-column">
                                <h4><?php esc_html_e('Have questions or suggestions?', 'rswpbs'); ?></h4>
                                <?php
                               echo sprintf('Don\'t hesitate to reach out – we\'re here to help! Our dedicated support team is available 24/7 via live chat. Visit our website at <a href="%s">%s</a> and find the live chat option at the bottom right corner.', esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/'), 'https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/')
                                ?>
                                <p><?php esc_html_e('We value your feedback and are excited to enhance your experience with our plugin. Your success is our priority.', 'rswpbs'); ?></p>
                                <p><?php esc_html_e( 'Thank you for being a part of the RS WP BOOK SHOWCASE community!', 'rswpbs' );?></p>
                            </div>
                        </div>
                        <div class="tutorial-single-section">
                             <div class="video-title">
                               <h2><?php esc_html_e('To get started, you could watch this video.', RSWPBS_TEXT_DOMAIN); ?></h2>
                            </div>
                            <div class="video-wrapper">
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/fI23SU26cSs?si=CfLbtlhSYr3WLWtY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="tutorial-single-section">
                            <div class="shortcode-title">
                               <h2><?php esc_html_e('Book Gallery and Book Slider Gutenberg Block ( Pro ).', RSWPBS_TEXT_DOMAIN); ?></h2>
                                <?php
                                if (!class_exists('Rswpbs_Pro')) :
                                ?>
                                <p><?php esc_html_e('This Block Is Available In The Pro Version Only.', RSWPBS_TEXT_DOMAIN);?><a class="upgradeToProLink" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', RSWPBS_TEXT_DOMAIN );?></a></p>
                               <?php
                                endif;
                               ?>
                            </div>
                            <div class="video-title">
                               <h3><?php esc_html_e('How to Use Gutenberg Block.', RSWPBS_TEXT_DOMAIN); ?></h3>
                            </div>
                            <div class="video-wrapper">
                               <iframe width="560" height="315" src="https://www.youtube.com/embed/84gda4bjCa0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="tutorial-single-section">
                            <div class="shortcode-title">
                               <h2><?php esc_html_e('Display Books Using Elementor Page Builder ( Pro ).', RSWPBS_TEXT_DOMAIN); ?></h2>
                                <?php
                                if (!class_exists('Rswpbs_Pro')) :
                                ?>
                                <p><?php esc_html_e('This Widget is Available In The Pro Version Only.');?><a class="upgradeToProLink" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', RSWPBS_TEXT_DOMAIN );?></a></p>
                               <?php
                                endif;
                               ?>
                            </div>
                            <div class="video-title">
                               <h3><?php esc_html_e('Design a Stunning Book Gallery with Elementor: A Step-by-Step Guide', RSWPBS_TEXT_DOMAIN); ?></h3>
                            </div>
                            <div class="video-wrapper">
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/XiOyJ9x061E" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Settings Page Content
 */
function rswpbs_csv_import_dummy_content(){
    ?>
    <div class="rswpbs-dummy-settings-page">
        <div class="rswpbs-container">
            <div class="settings-image-wrapper">
                <img src="<?php echo esc_url(RSWPBS_PLUGIN_URL . 'admin/assets/img/csv-import.jpg');?>" alt="<?php esc_attr_e('settings page image', 'rswpbs');?>">
                <div class="upgrade-to-pro-btn">
                    <a target="_blank" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a>
                </div>
            </div>
        </div>
    </div>
    <?php
}