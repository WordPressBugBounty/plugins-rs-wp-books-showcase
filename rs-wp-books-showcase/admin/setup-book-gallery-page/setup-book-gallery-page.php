<?php
// Hook to update status when a page is created or deleted
add_action('wp_insert_post', 'rswpbs_update_books_page_status');
add_action('before_delete_post', 'rswpbs_update_books_page_status');
function rswpbs_update_books_page_status()
{
    global $wpdb;

    $existing_page_id = $wpdb->get_var(
        "SELECT ID FROM $wpdb->posts WHERE post_name IN ('books', 'book') AND post_type = 'page' AND post_status IN ('publish', 'draft', 'trash')"
    );

    if ($existing_page_id) {
        update_option('rswpbs_books_page_exists', true);
    } else {
        update_option('rswpbs_books_page_exists', false);
    }
}

// 1. Notice Display Function
add_action('admin_notices', 'rswpbs_book_archive_not_available_notice');
function rswpbs_book_archive_not_available_notice()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (get_user_meta(get_current_user_id(), 'rswpbs_setup_notice_dismissed', true)) {
        return;
    }

    $books_page_exists = get_option('rswpbs_books_page_exists', false);

    global $wpdb;
    $book_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'book' AND post_status = 'publish'");

    if ($books_page_exists && $book_count > 0) {
        return;
    }

    $setup_url = admin_url('admin.php?page=rswpbs-gallery-setup');

    if (!$books_page_exists) {
        $message = sprintf(
            esc_html__('Thank you for activating the %s plugin! 🎉 We’re excited to help you showcase your books. Setting up a stunning book gallery is super easy—just click the Setup Book Gallery button below, and we’ll handle everything for you!', 'rswpbs'),
            '<strong>' . esc_html__('RS WP Book Showcase', 'rswpbs') . '</strong>'
        );
        $button_text = esc_html__('Setup Book Gallery', 'rswpbs');
    } else {
        $message = esc_html__('Oh no! Your book gallery page is empty right now—no books to show yet! Let’s fix that by adding a few books to get you started. Ready to bring your gallery to life?', 'rswpbs');
        $button_text = esc_html__('Setup Dummy Books', 'rswpbs');
    }
    ?>
    <div id="rswpbs-setup-books-page-notice" class="notice notice-warning is-dismissible"
        data-nonce="<?php echo wp_create_nonce('rswpbs_dismiss_notice_nonce'); ?>">
        <p><?php echo $message; ?></p>
        <p>
            <a href="<?php echo esc_url($setup_url); ?>" class="button button-primary">
                📖 <?php echo $button_text; ?>
            </a>
        </p>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#rswpbs-setup-books-page-notice').on('click', '.notice-dismiss', function () {
                var nonce = $('#rswpbs-setup-books-page-notice').data('nonce');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: { action: 'rswpbs_dismiss_setup_notice', nonce: nonce }
                });
            });
        });
    </script>
    <?php
}

// 2. AJAX Handler to save the dismissal state
add_action('wp_ajax_rswpbs_dismiss_setup_notice', 'rswpbs_dismiss_setup_notice_handler');
function rswpbs_dismiss_setup_notice_handler()
{
    check_ajax_referer('rswpbs_dismiss_notice_nonce', 'nonce');
    update_user_meta(get_current_user_id(), 'rswpbs_setup_notice_dismissed', 'yes');
    wp_send_json_success();
}