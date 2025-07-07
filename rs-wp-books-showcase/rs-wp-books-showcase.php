<?php
/**
 * Plugin Name:       RS WP Book Showcase
 * Plugin URI:        https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/
 * Description:       Premier WordPress book gallery plugin, offering advanced search options and multiple layouts for effortless book showcasing.
 * Version:           6.7.45
 * Requires at least: 4.9
 * Requires PHP:      7.1
 * Author:            RS WP THEMES
 * Author URI:        https://rswpthemes.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rswpbs
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if (!defined('RSWPBS_PLUGIN_PATH')) {
    define('RSWPBS_PLUGIN_PATH', plugin_dir_path( __file__ ));
}

if (!defined('RSWPBS_PLUGIN_URL')) {
    define('RSWPBS_PLUGIN_URL', plugin_dir_url( __file__ ));
}

if (!defined('RSWPBS_TEXT_DOMAIN')) {
    define('RSWPBS_TEXT_DOMAIN', 'rswpbs');
}

class Rswpbs{
    public function __construct(){
        require_once RSWPBS_PLUGIN_PATH . '/includes/opt-in/opt-in.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/opt-in/deactivation-feedback.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/init.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/register-cpt.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/settings-dummy-menu.php';
        /**
         * Custom Metabox
         */
        require_once RSWPBS_PLUGIN_PATH . '/admin/register-cmb.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/metabox/msl-cmb-function-only.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/metabox/formats-cmb-function-only.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/metabox/sample-content-cmb-function-only.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/metabox/book-mockup-meta-box.php';

        require_once RSWPBS_PLUGIN_PATH . '/admin/setup-book-gallery-page/setup-book-gallery-page.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/import-books-from-json.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/settings/general-settings.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/settings/book-archive-page.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/settings/book-single-page.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/settings/change-static-text.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/settings/colors-settings.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/settings/advanced-search-form.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/detect-amz-affiliate-id.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/import-books-from-csv/import-books-from-csv-menu-page.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/import-books-from-json/import-books-from-json-menu-page.php';
        require_once RSWPBS_PLUGIN_PATH . '/frontend/archive-page-options.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/download-image-from-url.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/default-loop-modify.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/solve-book-not-found-issue.php';
        require_once RSWPBS_PLUGIN_PATH . '/admin/tutorial.php';
        require_once RSWPBS_PLUGIN_PATH . '/frontend/enqueue-scripts.php';
        require_once RSWPBS_PLUGIN_PATH . '/frontend/rswpbs-shortcodes.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/template-hook.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/widgets/featured-book.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/widgets/books-list.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/register-rest-api-for-plugin-status.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/functions.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/AdvancedSearch.php';
        require_once RSWPBS_PLUGIN_PATH . '/includes/static-text.php';
        require_once RSWPBS_PLUGIN_PATH . '/review-system/review.php';
        require_once RSWPBS_PLUGIN_PATH . '/blocks/book-gallery/book-gallery-block.php';

        /**
         * RSWPBS Taxonomy Meta Fields
         */
        require_once RSWPBS_PLUGIN_PATH . '/includes/taxonomy-meta-fields/taxonomy-meta-fields.php';
        /**
         * Book Template Parts
         */
        require_once RSWPBS_PLUGIN_PATH . '/rsbs-templates/template-parts/book-content-section.php';
        require_once RSWPBS_PLUGIN_PATH . '/rsbs-templates/template-parts/book-header-section.php';
        /**
         * Themes Compatibility Files
         */
        require_once RSWPBS_PLUGIN_PATH . '/includes/themes-compatibility/oceanwp.php';

        /**
         * Hooks
         */
        add_action('plugin_loaded', [$this, 'rswpbs_plugin_loaded']);
        add_filter('body_class', [$this, 'rswpbs_body_classes']);
    }
    public function rswpbs_plugin_loaded(){
        $metafieldForBook = new Rswpbs_Cmb_For_Book();
        $registerBookPostType = new Rswpbs_Register_Book_Post_Type();
        load_plugin_textdomain(
            'rswpbs',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages/'
        );
        require_once RSWPBS_PLUGIN_PATH . '/admin/woocommerce-fields/downloadable-cmb.php';
    }
   public function rswpbs_body_classes($default_classes){
        $getThemeName = get_stylesheet();
        /**
         * Apply This Class if Newsy Theme Active
         */
        if ('newsy' === $getThemeName) {
            if (is_rswpbs_page()) {
                $default_classes[] = 'newsy-rswpbs-page boxed';
            }
        }
        $default_classes[] = 'rs-wp-books-showcase-activated';
        if (is_singular( 'book' )) {
            $default_classes[] = 'rs-wp-books-showcase-single-page rswp-book-showcase-page';
        }elseif (is_tax( 'book-author' )) {
            $default_classes[] = 'rs-wp-books-showcase-author-tax-page rswp-book-showcase-page';
        }elseif (is_tax('book-category')) {
            $default_classes[] = 'rs-wp-books-showcase-category-tax-page rswp-book-showcase-page';
        }elseif (is_post_type_archive('book')) {
            $default_classes[] = 'rs-wp-books-showcase-archive-page rswp-book-showcase-page';
        }
        if (has_rswpbs_shortcodes()) {
            $default_classes[] = 'rswp-book-showcase-page';
        }

        return $default_classes;
    }

}

$rsbookShowcase = new Rswpbs();

function rswpbs_set_sttings_default_value(){
    $default_settings = array(
        'show_books_page_header' => false,
        'show_search_form' => true,
        'show_sorting_section' => true,
        'book_per_page' => 8,
        'book_column' => 4,
        'show_book_title' => true,
        'show_book_author_name' => true,
        'show_book_price' => true,
        'show_book_buy_now_button' => true,
        'show_book_short_description' => true,
    );
    update_option( 'book_layouts_settings', $default_settings );
}
/**
 * Register the custom book author role on plugin activation.
 */
function rswpbs_register_book_author_role() {
    if ( ! get_role( 'rswpbs_book_author' ) ) {
        add_role( 'rswpbs_book_author', __( 'Book Author', 'rswpbs' ), array(
            'read'         => true,
            'edit_books'   => true,
            'upload_files' => true,
        ) );
    }
}

/**
 * Assign capabilities to selected roles.
 */
function rswpbs_set_book_author_role() {
    $selected_roles = get_option( 'rswpbs_roles_to_manage_books', array( 'administrator' ) );

    // Always include rswpbs_book_author
    if ( ! in_array( 'rswpbs_book_author', $selected_roles ) ) {
        $selected_roles[] = 'rswpbs_book_author';
    }

    foreach ( $selected_roles as $the_role ) {
        $role = get_role( $the_role );
        if ( ! $role ) {
            continue;
        }

        // Basic capabilities
        $role->add_cap( 'read' );
        $role->add_cap( 'edit_books' );
        $role->add_cap( 'edit_published_books' );
        $role->add_cap( 'delete_published_books' );

        if ( 'rswpbs_book_author' === $the_role ) {
            $role->add_cap( 'publish_books', false );
            $role->add_cap( 'edit_others_books', false );
            $role->add_cap( 'read_private_books', false );
            $role->add_cap( 'edit_private_books', false );
            $role->add_cap( 'delete_private_books', false );
            $role->add_cap( 'upload_files' );
            $role->add_cap( 'delete_others_books', false );
            $role->add_cap( 'manage_book_author', false );
            $role->add_cap( 'delete_book_author', false );
            $role->add_cap( 'manage_book_category', false );
            $role->add_cap( 'delete_book_category', false );
            $role->add_cap( 'manage_book_series', false );
            $role->add_cap( 'delete_book_series', false );
        } else {
            $role->add_cap( 'publish_books', true );
            $role->add_cap( 'delete_books', true );
            $role->add_cap( 'delete_book', true );
            $role->add_cap( 'edit_others_books', true );
            $role->add_cap( 'delete_others_books', true );
            $role->add_cap( 'read_private_books', true );
            $role->add_cap( 'edit_private_books', true );
            $role->add_cap( 'delete_private_books', true );
            $role->add_cap( 'manage_book_author', true );
            $role->add_cap( 'delete_book_author', true );
            $role->add_cap( 'manage_book_category', true );
            $role->add_cap( 'delete_book_category', true );
            $role->add_cap( 'manage_book_series', true );
            $role->add_cap( 'delete_book_series', true );
            $role->add_cap( 'upload_files' );
        }

        $role->add_cap( 'edit_book_author' );
        $role->add_cap( 'assign_book_author' );
        $role->add_cap( 'edit_book_category' );
        $role->add_cap( 'assign_book_category' );
        $role->add_cap( 'edit_book_series' );
        $role->add_cap( 'assign_book_series' );
    }
}
/**
 * Remove capabilities from deselected roles.
 */
function rswpbs_remove_unused_book_author_role_caps() {
    $all_roles = wp_roles()->roles;
    $selected_roles = get_option( 'rswpbs_roles_to_manage_books', array( 'administrator' ) );
    $selected_roles[] = 'rswpbs_book_author';

    foreach ( $all_roles as $role_slug => $role ) {
        if ( in_array( $role_slug, $selected_roles ) ) {
            continue;
        }

        $role_obj = get_role( $role_slug );
        if ( $role_obj ) {
            $caps = array(
                'read',
                'edit_books',
                'edit_published_books',
                'delete_published_books',
                'publish_books',
                'delete_books',
                'delete_book',
                'edit_others_books',
                'delete_others_books',
                'read_private_books',
                'edit_private_books',
                'delete_private_books',
                'upload_files',
                'manage_book_author',
                'delete_book_author',
                'edit_book_author',
                'assign_book_author',
                'manage_book_category',
                'delete_book_category',
                'edit_book_category',
                'assign_book_category',
                'manage_book_series',
                'delete_book_series',
                'edit_book_series',
                'assign_book_series',
            );

            foreach ( $caps as $cap ) {
                $role_obj->remove_cap( $cap );
            }
        }
    }
}

/**
 * Activation hook to set up roles and capabilities.
 */
register_activation_hook( __FILE__, 'rswpbs_plugin_activation' );
function rswpbs_plugin_activation() {
    rswpbs_register_book_author_role();
    rswpbs_set_book_author_role();
    rswpbs_set_sttings_default_value();
    set_transient( 'rswpbs_delayed_redirect_transient', true, 10 );
    flush_rewrite_rules();
}

/**
 * Update capabilities when roles setting is changed.
 */
add_action( 'update_option_rswpbs_roles_to_manage_books', 'rswpbs_remove_unused_book_author_role_caps', 5 );
add_action( 'update_option_rswpbs_roles_to_manage_books', 'rswpbs_set_book_author_role', 10 );

/**
 * Turn False woocommerce_prevent_admin_access
 */
add_filter('woocommerce_prevent_admin_access', 'rswpbs_allow_admin_access', 20, 1);
function rswpbs_allow_admin_access($prevent_admin_access) {
    if(current_user_can( 'rswpbs_book_author' )){
        $prevent_admin_access = false;
    }
    return $prevent_admin_access;
}

if (!class_exists('Rswpbs_Pro')) :
    function rswpbs_upgrade_to_pro_link_pal( $links ) {
        $upgradetoProLink = sprintf('<a target="_blank" class="rswpbs-pal-link-ugtp" href="%1$s">%2$s</a>', esc_url( 'https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/' ), esc_html__('Book Gallery Upgrade to Pro', 'rswpbs') );
        $settings = sprintf('<a href="%1$s">%2$s</a>', esc_url(admin_url('edit.php?post_type=book&page=rswpbs-settings')), esc_html__('Settings', RSWPBS_PLUGIN_PATH));
        array_splice( $links, 0, 0, $upgradetoProLink );
        array_splice( $links, 1, 0, $settings );
        return $links;
    }
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'rswpbs_upgrade_to_pro_link_pal' );
endif;

function rswpbs_upgrade_to_pro_admin_menu() {
    if (!class_exists('Rswpbs_Pro')) :
        add_submenu_page(
            'edit.php?post_type=book',
            '',
            'Upgrade to Pro',
            'manage_options',
            esc_url( 'https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/' ),
            ''
        );
    endif;
}
add_action( 'admin_menu', 'rswpbs_upgrade_to_pro_admin_menu' );

// Hook to handle the delayed redirect
// add_action('admin_init', 'rswpbs_do_delayed_redirect');
function rswpbs_do_delayed_redirect() {
    $getRswpThemesSlug = get_stylesheet();
    $first_time_activation = get_option('rswpbs_first_time_activation');

    $showNotice = true;
    $rswpThemes = array('writers-portfolio', 'fitness-blog', 'tech-blogging', 'faith-blog', 'minimalblog', 'one-elementor', 'food-blogger', 'book-blogger', 'author-blog', 'author-portfolio', 'author-portfolio-pro', 'electronic-theme', 'book-author-blog', 'book-author-template', 'book-review', 'author-personal-blog' , 'book-review-blog');
    if (in_array($getRswpThemesSlug, $rswpThemes)) {
        $showNotice = false;
    }
    if (true === $showNotice) :
        if (get_transient('rswpbs_delayed_redirect_transient')) {
            // Check if the plugin is active
            if (is_plugin_active('rs-wp-books-showcase/rs-wp-books-showcase.php')) {
                // Apply the filter to decide whether to perform the redirect
                $perform_redirect = apply_filters('rswpbs_should_perform_redirect', !$first_time_activation);

                if ($perform_redirect) {
                    // Set the flag to indicate activation has occurred
                    update_option('rswpbs_first_time_activation', true);

                    // Redirect to the specified page
                    wp_redirect(admin_url('/edit.php?post_type=book&page=rswpbs-tutorial'));
                    exit;
                }
            }
            // Clear the transient after redirect
            delete_transient('rswpbs_delayed_redirect_transient');
        }
    endif;
}

/**
 * Remove Block Editor For Book Post Type
 */
add_filter( 'use_block_editor_for_post_type', 'disable_block_editor_for_selected_post_types', 10, 2 );

function disable_block_editor_for_selected_post_types( $use_block_editor, $post_type ) {
    $disabled_post_types = [ 'book', 'book_reviews' ]; // Add all post types you want to disable the block editor for
    return in_array( $post_type, $disabled_post_types, true ) ? false : $use_block_editor;
}

function rswpbs_modify_amazon_url($url, $affiliate_tag = 'lft01-20') {
    // Parse the URL
    $parsed_url = parse_url($url);

    // Check if it's a full Amazon URL (not a short URL)
    if (!isset($parsed_url['host']) || strpos($parsed_url['host'], 'amazon.') === false) {
        return $url; // Not an Amazon URL
    }

    // Remove trailing slash from the path if it exists
    $clean_path = rtrim($parsed_url['path'], '/');

    // Parse query parameters
    parse_str($parsed_url['query'] ?? '', $query_params);

    // Check if 'tag' (affiliate tracking) exists, if not, add it
    if (!isset($query_params['tag'])) {
        $query_params['tag'] = $affiliate_tag;
    }

    // Rebuild the query string
    $new_query = http_build_query($query_params);

    // Reconstruct the full URL without trailing slash
    $new_url = "{$parsed_url['scheme']}://{$parsed_url['host']}{$clean_path}?" . $new_query;

    return $new_url;
}

/**
 * Check if RS WP Book Showcase Pro is active.
 *
 * @return bool True if the pro plugin is active, false otherwise.
 */
function rswpbs_is_pro_active() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Ensure function is available
    return is_plugin_active( 'rs-wp-books-showcase-pro/rs-wp-books-showcase-pro.php' );
}