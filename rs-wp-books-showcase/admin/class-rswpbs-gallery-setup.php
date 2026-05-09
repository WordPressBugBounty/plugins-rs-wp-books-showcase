<?php
/**
 * Gallery Setup Wizard Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class Rswpbs_Gallery_Setup
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'setup_menu'));
        add_action('wp_ajax_rswpbs_process_gallery_setup', array($this, 'process_setup'));
        add_action('wp_ajax_rswpbs_save_optin_data', array($this, 'save_optin_data'));
    }

    public function setup_menu()
    {
        $hook = add_submenu_page(
            '', // Hidden menu
            __('Setup Book Gallery', 'rswpbs'),
            __('Setup Book Gallery', 'rswpbs'),
            'manage_options',
            'rswpbs-gallery-setup',
            array($this, 'setup_page_callback')
        );
        add_action("load-$hook", array($this, 'set_setup_page_title'));
    }

    public function set_setup_page_title()
    {
        global $title;
        $title = __('Setup Book Gallery', 'rswpbs');
    }

    public function setup_page_callback()
    {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php _e('Setup Book Gallery', 'rswpbs'); ?></h1>
            <hr class="wp-header-end">

            <div class="card"
                style="max-width: 800px; margin-top: 20px; padding: 30px; border-radius: 8px; background: #fff; border: 1px solid #ccd0d4;">

                <div id="rswpbs-setup-step-1">
                    <h2 style="margin-top: 0;"><?php _e('Choose Setup Type', 'rswpbs'); ?></h2>
                    <p style="color: #50575e;"><?php _e('How would you like to set up your Book Gallery page?', 'rswpbs'); ?>
                    </p>

                    <div style="display: flex; gap: 20px; margin-top: 30px;">
                        <div style="flex: 1; border: 1px solid #c3c4c7; padding: 20px; border-radius: 6px; text-align: center;">
                            <h3 style="margin-top:0;">0 Books</h3>
                            <p style="color: #646970;">
                                <?php _e('Create only the Book Gallery page. No books will be added.', 'rswpbs'); ?>
                            </p>
                            <button class="button button-secondary rswpbs-run-setup"
                                data-type="empty"><?php _e('Setup with 0 Books', 'rswpbs'); ?></button>
                        </div>

                        <div
                            style="flex: 1; border: 2px solid #2271b1; padding: 20px; border-radius: 6px; text-align: center; background: #f6f7f7;">
                            <h3 style="margin-top:0; color: #2271b1;">Few Dummy Books</h3>
                            <p style="color: #646970;">
                                <?php _e('Create the gallery page AND import dummy books to visualize the layout.', 'rswpbs'); ?>
                            </p>
                            <button class="button button-primary rswpbs-run-setup"
                                data-type="dummy"><?php _e('Setup with Dummy Books', 'rswpbs'); ?></button>
                        </div>
                    </div>
                </div>

                <div id="rswpbs-setup-processing" style="display: none; text-align: center; padding: 40px 0;">
                    <span class="spinner is-active" style="float: none;"></span>
                    <h3 style="margin-top: 15px;"><?php _e('Setting up your gallery...', 'rswpbs'); ?></h3>
                </div>

                <div id="rswpbs-setup-complete" style="display: none; margin-top: 20px; text-align: center;">
                    <div
                        style="padding: 20px; background: #edfaef; border: 1px solid #46b450; border-radius: 4px; margin-bottom: 30px;">
                        <h3 style="margin: 0; color: #1e4620;">🎉 <?php _e('Gallery Setup Complete!', 'rswpbs'); ?></h3>
                    </div>

                    <div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 40px;">
                        <a href="#" id="rswpbs-view-page-btn" target="_blank" class="button button-primary button-hero">👁️
                            <?php _e('View Books Page', 'rswpbs'); ?></a>
                        <a href="<?php echo admin_url('post-new.php?post_type=book'); ?>"
                            class="button button-secondary button-hero">➕ <?php _e('Add New Book', 'rswpbs'); ?></a>
                    </div>

                    <div id="rswpbs-optin-section"
                        style="display: none; background: #f0f0f1; padding: 30px; border-radius: 6px; text-align: left;">
                        <h3 style="margin-top: 0;">📬 <?php _e('Stay Updated!', 'rswpbs'); ?></h3>
                        <p><?php _e('Subscribe to get the latest updates, tips, and new features about RS WP Book Showcase plugin.', 'rswpbs'); ?>
                        </p>
                        <form id="rswpbs-optin-form" style="display: flex; gap: 10px; margin-top: 15px;">
                            <input type="text" id="rswpbs_optin_name" placeholder="Your Name" required
                                style="flex: 1; padding: 8px;">
                            <input type="email" id="rswpbs_optin_email" placeholder="Your Email" required
                                style="flex: 1; padding: 8px;">
                            <button type="submit" class="button button-primary"
                                style="padding: 0 20px;"><?php _e('Subscribe', 'rswpbs'); ?></button>
                        </form>
                        <p id="rswpbs-optin-msg" style="display: none; color: #46b450; font-weight: bold; margin-top: 15px;">
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                // Setup Processing
                $('.rswpbs-run-setup').on('click', function () {
                    var setupType = $(this).data('type');
                    $('#rswpbs-setup-step-1').hide();
                    $('#rswpbs-setup-processing').fadeIn();

                    runSetupBatch(0, setupType);
                });

                function runSetupBatch(offset, setupType) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'rswpbs_process_gallery_setup',
                            setup_type: setupType,
                            offset: offset,
                            security: '<?php echo wp_create_nonce("rswpbs_setup_nonce"); ?>'
                        },
                        success: function (response) {
                            if (response.success) {
                                if (response.data.is_complete) {
                                    $('#rswpbs-setup-processing').hide();
                                    $('#rswpbs-setup-complete').fadeIn();
                                    $('#rswpbs-view-page-btn').attr('href', response.data.page_url);
                                } else {
                                    // Update progress message
                                    $('#rswpbs-setup-processing h3').text(response.data.progress_message);
                                    // Run next batch
                                    runSetupBatch(response.data.next_offset, setupType);
                                }
                            } else {
                                alert('Setup failed: ' + response.data.message);
                                $('#rswpbs-setup-processing').hide();
                                $('#rswpbs-setup-step-1').fadeIn();
                            }
                        },
                        error: function () {
                            alert('An error occurred during the setup process.');
                            $('#rswpbs-setup-processing').hide();
                            $('#rswpbs-setup-step-1').fadeIn();
                        }
                    });
                }

                // Email Opt-in Processing
                $('#rswpbs-optin-form').on('submit', function (e) {
                    e.preventDefault();
                    var name = $('#rswpbs_optin_name').val();
                    var email = $('#rswpbs_optin_email').val();
                    var btn = $(this).find('button');

                    btn.prop('disabled', true).text('Subscribing...');

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'rswpbs_save_optin_data',
                            name: name,
                            email: email,
                            security: '<?php echo wp_create_nonce("rswpbs_optin_nonce"); ?>'
                        },
                        success: function (response) {
                            if (response.success) {
                                $('#rswpbs-optin-form').hide();
                                $('#rswpbs-optin-msg').text('✅ ' + response.data.message).fadeIn();
                            }
                        }
                    });
                });
            });
        </script>
        <?php
    }

    public function process_setup()
    {
        check_ajax_referer('rswpbs_setup_nonce', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Unauthorized.', 'rswpbs')));
        }

        $setup_type = sanitize_text_field($_POST['setup_type']);
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
        $batch_size = 5; // Process 5 books at a time
        global $wpdb;

        // 1. Create Page (Only at the start)
        if ($offset === 0) {
            $existing_page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name IN ('books', 'book') AND post_type = 'page' AND post_status IN ('publish', 'draft', 'trash')");

            if ($existing_page_id) {
                $page_status = get_post_status($existing_page_id);
                if ($page_status === 'trash') {
                    wp_delete_post($existing_page_id, true);
                    $existing_page_id = null;
                }
            }

            if (!$existing_page_id) {
                $page_id = wp_insert_post(array(
                    'post_title' => 'Books',
                    'post_name' => 'books',
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                ));
                update_option('rswpbs_book_gallery_page_id', $page_id);
            } else {
                $page_id = $existing_page_id;
            }

            // Add to Menu
            $menus = wp_get_nav_menus();
            foreach ($menus as $menu) {
                $menu_id = $menu->term_id;
                $menu_items = wp_get_nav_menu_items($menu_id);
                $already_in_menu = false;
                foreach ($menu_items as $item) {
                    if ($item->object_id == $page_id && $item->object == 'page') {
                        $already_in_menu = true;
                        break;
                    }
                }
                if (!$already_in_menu) {
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => 'Books',
                        'menu-item-object-id' => $page_id,
                        'menu-item-object' => 'page',
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish',
                    ));
                }
            }
        } else {
            $page_id = get_option('rswpbs_book_gallery_page_id');
        }

        $is_complete = true;
        $next_offset = 0;
        $progress_message = __('Processing...', 'rswpbs');

        // 2. Import JSON Books if 'dummy' is selected
        if ($setup_type === 'dummy') {
            $json_path = RSWPBS_PLUGIN_PATH . 'admin/assets/json/books.json';

            if (file_exists($json_path)) {
                $json_data = file_get_contents($json_path);
                $books = json_decode($json_data, true);

                if (is_array($books)) {
                    $total_books = count($books);
                    $batch = array_slice($books, $offset, $batch_size);

                    if (!empty($batch)) {
                        if (function_exists('rswpbs_import_single_book')) {
                            foreach ($batch as $book) {
                                rswpbs_import_single_book($book);
                            }
                        }

                        $next_offset = $offset + $batch_size;
                        $is_complete = ($next_offset >= $total_books);

                        $current_count = min($next_offset, $total_books);
                        $progress_message = sprintf(__('Importing books: %d / %d...', 'rswpbs'), $current_count, $total_books);
                    }
                }
            }
        }

        wp_send_json_success(array(
            'is_complete' => $is_complete,
            'next_offset' => $next_offset,
            'progress_message' => $progress_message,
            'page_url' => get_permalink($page_id)
        ));
    }

    /**
     * AJAX Handler for saving Opt-in Data to WP Database
     */
    public function save_optin_data()
    {
        check_ajax_referer('rswpbs_optin_nonce', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error();
        }

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);

        if (is_email($email)) {
            // Get existing subscribers from options table
            $subscribers = get_option('rswpbs_optin_subscribers', array());

            // Add new subscriber
            $subscribers[] = array(
                'name' => $name,
                'email' => $email,
                'date' => current_time('mysql')
            );

            // Save back to database
            update_option('rswpbs_optin_subscribers', $subscribers);

            wp_send_json_success(array('message' => __('Thanks for subscribing! Your email has been saved.', 'rswpbs')));
        } else {
            wp_send_json_error(array('message' => __('Invalid email.', 'rswpbs')));
        }
    }
}

new Rswpbs_Gallery_Setup();