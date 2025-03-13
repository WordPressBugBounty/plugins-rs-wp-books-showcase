<?php
/**
 * Admin Notice For Amazon Affiliate Marketer
 */
function rswpbs_amz_admin_notice() {

    $active_theme = wp_get_theme();
    $showNotice = true;
    if ($active_theme->get('Name') === 'Book Author Template') {
        $showNotice = false;
        if ( false !== get_option('book_author_template_notice_dismissed_forever') || false !== get_option('book_author_template_notice_remind_time')) {
            $showNotice = true;
        }
    }elseif ($active_theme->get('Name') === 'Author Portfolio') {
        $showNotice = false;
        if ( false !== get_option('author_portfolio_notice_dismissed_forever') || false !== get_option('author_portfolio_notice_remind_time')) {
            $showNotice = true;
        }
    }elseif ($active_theme->get('Name') === 'Author Personal Blog') {
        $showNotice = false;
        if ( false !== get_option('author_personal_blog_notice_dismissed_forever') || false !== get_option('author_personal_blog_notice_remind_time')) {
            $showNotice = true;
        }
    }elseif ($active_theme->get('Name') === 'Book Author Blog') {
        $showNotice = false;
        if ( false !== get_option('author_personal_blog_notice_dismissed_forever') || false !== get_option('author_personal_blog_notice_remind_time')) {
            $showNotice = true;
        }
    }

    $user_id = get_current_user_id();
    $dismissed_forever = get_user_meta($user_id, 'rswpbs_amz_notice_dismissed_forever', true);
    $dismissed_time = get_user_meta($user_id, 'rswpbs_amz_notice_dismissed_time', true);

    if($showNotice) :
        // Check if not dismissed forever and either never dismissed or 3 days have passed
        if (!$dismissed_forever && (!$dismissed_time || (time() - $dismissed_time) > (3 * 86400))) : // 3 * 86400 = 3 days
            ?>
            <div class="notice notice-info is-dismissible rswpbs-amz-admin-notice">
                <h3 class="amz-notice-heading"><?php echo esc_html__('🚀 Effortless Book Catalog + Affiliate Earnings! 📚💰', 'rswpbs'); ?></h3>
                <p class="amz-notice-sub-heading"><strong>
                    <?php echo esc_html__('Want to monetize your website effortlessly? Now, you can import 1,000+ books from Amazon to your website site in just 10 minutes – no manual work needed!', 'rswpbs'); ?></strong>
                </p>
                <ul>
                    <li><?php echo esc_html__('✅ ', 'rswpbs'); ?><strong><?php echo esc_html__('Instant Book Catalog –', 'rswpbs'); ?></strong> <?php echo esc_html__('Add hundreds (or thousands) of books with just a few clicks. No need to manually enter titles, descriptions, or images!', 'rswpbs'); ?></li>
                    <li><?php echo esc_html__('✅ ', 'rswpbs'); ?><strong><?php echo esc_html__('Earn Commissions Automatically –', 'rswpbs'); ?></strong> <?php echo esc_html__('Insert your Amazon Tracking ID and earn every time someone buys a book through your website.', 'rswpbs'); ?></li>
                    <li><?php echo esc_html__('✅ ', 'rswpbs'); ?><strong><?php echo esc_html__('Works for Any Niche –', 'rswpbs'); ?></strong> <?php echo esc_html__('Whether your site is about business, fitness, self-improvement, cooking, tech, or anything else, you can recommend relevant books to your audience.', 'rswpbs'); ?></li>
                    <li><?php echo esc_html__('✅ ', 'rswpbs'); ?><strong><?php echo esc_html__('The Bigger Your Catalog, The More You Earn –', 'rswpbs'); ?></strong> <?php echo esc_html__('A large book collection = higher chances of sales & commissions!', 'rswpbs'); ?></li>
                    <li><?php echo esc_html__('✅ ', 'rswpbs'); ?><strong><?php echo esc_html__('No Tech Skills Needed –', 'rswpbs'); ?></strong> <?php echo esc_html__('Set up everything easily with our step-by-step video guide included in the Import Books from Amazon page.', 'rswpbs'); ?></li>
                </ul>
                <p><strong><?php echo esc_html__('Get Started in Just a Few Clicks!', 'rswpbs'); ?></strong></p>
                <div class="rswpbs-amz-admin-notice-btn-wrapper">
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=book&page=rswpbs-settings')); ?>" class="button button-primary">
                        <?php esc_html_e('Enter Tracking ID', 'rswpbs'); ?>
                    </a>
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=book&page=import-books-from-json')); ?>" class="import-books-from-amazon-btn button button-secondary"><span class="dashicons dashicons-amazon"></span>
                        <?php esc_html_e('Import Books from Amazon', 'rswpbs'); ?>
                    </a>
                </div>
                <p><strong><?php echo esc_html__('💰 Start building your book catalog today and turn your website into a passive income machine!', 'rswpbs'); ?></strong></p>
                <div class="rswpbs-notice-dismiss-links">
                    <a href="#" class="rswpbs-dismiss-forever" data-nonce="<?php echo wp_create_nonce('rswpbs_amz_dismiss_forever'); ?>">Dismiss Forever</a> |
                    <a href="#" class="rswpbs-remind-later" data-nonce="<?php echo wp_create_nonce('rswpbs_amz_remind_later'); ?>">Remind Me Later</a>
                </div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    // Handle Dismiss Forever
                    $('.rswpbs-dismiss-forever').on('click', function(e) {
                        e.preventDefault();
                        $.post(ajaxurl, {
                            action: 'rswpbs_amz_dismiss_forever',
                            security: $(this).data('nonce')
                        }, function() {
                            $('.rswpbs-amz-admin-notice').slideUp();
                        });
                    });

                    // Handle Remind Me Later
                    $('.rswpbs-remind-later').on('click', function(e) {
                        e.preventDefault();
                        $.post(ajaxurl, {
                            action: 'rswpbs_amz_remind_later',
                            security: $(this).data('nonce')
                        }, function() {
                            $('.rswpbs-amz-admin-notice').slideUp();
                        });
                    });

                    // Handle default dismiss button
                    $('.rswpbs-amz-admin-notice').on('click', '.notice-dismiss', function() {
                        $.post(ajaxurl, {
                            action: 'rswpbs_amz_remind_later',
                            security: '<?php echo wp_create_nonce("rswpbs_amz_remind_later"); ?>'
                        });
                    });
                });
            </script>
            <?php
        endif;
    endif;
}
add_action('admin_notices', 'rswpbs_amz_admin_notice');

// Handle dismiss forever
function rswpbs_amz_dismiss_forever() {
    check_ajax_referer('rswpbs_amz_dismiss_forever', 'security');
    update_user_meta(get_current_user_id(), 'rswpbs_amz_notice_dismissed_forever', true);
    wp_die();
}
add_action('wp_ajax_rswpbs_amz_dismiss_forever', 'rswpbs_amz_dismiss_forever');

// Handle remind me later
function rswpbs_amz_remind_later() {
    check_ajax_referer('rswpbs_amz_remind_later', 'security');
    update_user_meta(get_current_user_id(), 'rswpbs_amz_notice_dismissed_time', time());
    wp_die();
}
add_action('wp_ajax_rswpbs_amz_remind_later', 'rswpbs_amz_remind_later');

// 1. Register the submenu page under "Books"
add_action( 'admin_menu', 'rswpbs_json_import_submenu' );
function rswpbs_json_import_submenu() {
    add_submenu_page(
        'edit.php?post_type=book',           // Parent slug
        'Import Books From Amazon',            // Page title
        'Import Books From Amazon',            // Menu title
        'manage_options',                    // Capability
        'import-books-from-json',            // Menu slug
        'rswpbs_import_books_from_json_page' // Callback
    );
}

/**
 * Callback that renders the "Import Books From JSON" page.
 * The form posts to admin-post.php, with action="rswpbs_import_books_from_json".
 */
function rswpbs_import_books_from_json_page() {
    // Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permissions to access this page.' );
    }

    // Check if Pro is active (if you have a Pro check)
    $is_pro_active = function_exists( 'rswpbs_is_pro_active' ) && rswpbs_is_pro_active();

    // Display any admin notices from the redirect
    if ( isset( $_GET['import_error'] ) ) {
        echo '<div class="notice notice-error"><p>' . esc_html( $_GET['import_error'] ) . '</p></div>';
    }
    if ( isset( $_GET['import_success'] ) ) {
        echo '<div class="notice notice-success"><p>' . esc_html( $_GET['import_success'] ) . '</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Import Books from JSON</h1>
        <?php
        // If you have custom tabs
        if ( function_exists( 'rswpbs_settings_tabs' ) ) {
            rswpbs_settings_tabs( 'import_books_from_json' );
        }
        ?>
        <div class="rswpbs-json-upload-container <?php echo (!rswpbs_is_pro_active()) ? 'free-version' : ''; ?>">
            <?php
            if ( ! $is_pro_active ) {
                echo '<div class="rswpbs-csv-pro-message">
                        <p>🚀 This feature is available only in
                           <a href="https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/" target="_blank">
                           RS WP Book Showcase Pro</a>. Upgrade to unlock Amazon Import feature.
                        </p>
                      </div>';
            }
            ?>
            <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                <?php
                wp_nonce_field( 'rswpbs_json_import_nonce', 'rswpbs_json_import_nonce_field' );
                ?>
                <input type="hidden" name="action" value="rswpbs_import_books_from_json">
                <input type="hidden" id="rswpbs_upload_json_file" name="rswpbs_upload_json_file" value="">
                <p id="selected_json_file_name" style="margin: 1em 0; font-style: italic;">
                    <?php esc_html_e( 'No file selected', 'rswpbs' ); ?>
                </p>
                <div class="rswpbs-upload-section">
                    <button type="button" class="button button-primary" id="upload_json_button" <?php echo ( ! $is_pro_active ) ? 'disabled' : ''; ?>>
                        <span class="dashicons dashicons-upload"></span> <?php esc_html_e( 'Select JSON File', 'rswpbs' ); ?>
                    </button>
                    <button type="submit" class="button button-secondary rswpbs-import-button" <?php echo ( ! $is_pro_active ) ? 'disabled' : ''; ?>>
                        <span class="dashicons dashicons-migrate"></span> <?php esc_html_e( 'Import Books', 'rswpbs' ); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="tutorial-video-wrapper">
        <p><?php esc_html_e('Watch the video below to learn how to import Amazon books into your website effortlessly. It takes just 3 minutes but will save you hours of manual work!', 'rswpbs'); ?></p>
        <p> <?php esc_html_e('After watching this tutorial, you\'ll be able to add 1,000+ Amazon books to your website in just a few minutes.', 'rswpbs'); ?> </p>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/F88TSjXuU7o?si=6ns-IK4BlosQIwVi" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
    <?php
}


/**
 * Plugin Name: Import Books From JSON (Processing)
 * Description: Handles the form submission for JSON imports via admin_post.
 */

// Hook to handle form submission
add_action( 'admin_post_rswpbs_import_books_from_json', 'rswpbs_handle_json_import' );

function rswpbs_handle_json_import() {
    // 1. Check nonce
    if ( ! isset( $_POST['rswpbs_json_import_nonce_field'] ) ||
         ! wp_verify_nonce( $_POST['rswpbs_json_import_nonce_field'], 'rswpbs_json_import_nonce' ) ) {
        rswpbs_json_import_redirect_error( 'Security check failed.' );
    }

    // 2. Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        rswpbs_json_import_redirect_error( 'You do not have permission to import books.' );
    }

    // 3. Get the attachment ID
    $attachment_id = isset( $_POST['rswpbs_upload_json_file'] ) ? absint( $_POST['rswpbs_upload_json_file'] ) : 0;
    if ( ! $attachment_id ) {
        rswpbs_json_import_redirect_error( 'No JSON file was selected.' );
    }

    // 4. Get the file URL from the attachment ID
    $json_url = wp_get_attachment_url( $attachment_id );
    if ( ! $json_url ) {
        rswpbs_json_import_redirect_error( 'Could not retrieve the file URL for attachment ID: ' . $attachment_id );
    }

    // 5. Call your import function (fetch the JSON, create posts, etc.)
    $result = rswpbs_import_books_from_url( $json_url ); // define this function yourself

    if ( is_wp_error( $result ) ) {
        // If there's an error, redirect back with an error message
        rswpbs_json_import_redirect_error( $result->get_error_message() );
    } else {
        // $result should be the count of imported books
        rswpbs_json_import_redirect_success( "Books imported successfully: $result" );
    }
}

/**
 * Helper to redirect back to the JSON import page with an error message.
 */
function rswpbs_json_import_redirect_error( $msg ) {
    $redirect_url = add_query_arg(
        array(
            'post_type'      => 'book',
            'page'           => 'import-books-from-json',
            'import_error'   => urlencode( $msg ),
        ),
        admin_url( 'edit.php' )
    );
    wp_safe_redirect( $redirect_url );
    exit;
}

/**
 * Helper to redirect back to the JSON import page with a success message.
 */
function rswpbs_json_import_redirect_success( $msg ) {
    $redirect_url = add_query_arg(
        array(
            'post_type'      => 'book',
            'page'           => 'import-books-from-json',
            'import_success' => urlencode( $msg ),
        ),
        admin_url( 'edit.php' )
    );
    wp_safe_redirect( $redirect_url );
    exit;
}

add_action('admin_enqueue_scripts', function($hook) {
    // The $hook for our submenu is "book_page_import-books-from-json"
    if ( $hook !== 'book_page_import-books-from-json' ) {
        return;
    }

    // Enqueue WordPress media scripts
    wp_enqueue_media();

    // Enqueue your custom JS that opens the media library, etc.
    wp_enqueue_script(
        'rswpbs-json-file-uploader',
        RSWPBS_PLUGIN_URL . 'includes/import-books-from-json/import-books-from-json.js',
        array('jquery'),
        null,
        true
    );
});
