<?php
// File: opt-in.php
$emailAlreadySent = get_option('rswpthemes_optin_email_sent');
$apiKeyRegistered = get_option('rswpthemes_api_key_registered');
if ($apiKeyRegistered && $emailAlreadySent) {
    return;
}

add_action('admin_init', 'rswpbs_control_optin_notice');
function rswpbs_control_optin_notice() {
    $user_id = get_current_user_id();
    $dismissed_forever = get_user_meta($user_id, 'rswpbs_amz_notice_dismissed_forever', true);
    $dismissed_time = get_user_meta($user_id, 'rswpbs_amz_notice_dismissed_time', true);
    $optin_success = get_option('rswpbs_optin_success');
    $hide_notice_transient = get_transient('hide_notice_for_3_days');
    $notice_show = true;

    if (!$dismissed_forever && !$dismissed_time) {
        $notice_show = false;
    }

    if ('1' == $optin_success) {
        $notice_show = false;
    } elseif ('1' == $hide_notice_transient) {
        $time_since_transient = current_time('timestamp') - get_option('rswpbs_optin_unsuccess_time');
        if ($time_since_transient >= 3 * 24 * 60 * 60) {
            $notice_show = true;
            update_option('rswpbs_optin_unsuccess_time', current_time('timestamp'));
        } else {
            $notice_show = false;
        }
    }

    if (isset($_GET['opt_in_unsuccess'])) {
        $notice_show = false;
    }

    if ($notice_show) {
        add_action('admin_notices', 'rswpbs_optin_notice');
    }
}

add_action('admin_init', 'rswpbs_handle_opt_notice_button_clicks');
function rswpbs_handle_opt_notice_button_clicks() {
    if (isset($_GET['opt_in_success'])) {
        update_option('rswpbs_optin_success', '1');
    } elseif (isset($_GET['opt_in_unsuccess'])) {
        set_transient('hide_notice_for_3_days', true, 3 * 24 * 60 * 60);
        update_option('rswpbs_optin_unsuccess_time', current_time('timestamp'));
    }
}

add_action('wp_ajax_rswpbs_update_activation_time', 'rswpbs_update_activation_time');
function rswpbs_update_activation_time() {
    if (current_user_can('manage_options')) {
        update_option('rswpbs_optin_success', '1');
        wp_send_json_success();
    } else {
        wp_send_json_error(array('error' => 'Permission denied'));
    }
}

function rswpbs_optin_notice() {
    ?>
    <div class="notice notice-info rs-wp-book-showcase-notice-container is-dismissible">
        <div class="rs-wp-book-showase-opt-in-wrapper">
            <div class="rs-wp-book-showase-optin-inner">
                <div class="rs-wp-book-showase-opt-in-logo-col">
                    <div class="rs-wp-book-showcase-logo">
                        <img src="<?php echo esc_url(RSWPBS_PLUGIN_URL . 'admin/assets/img/rs-wp-book-showcase-logo.png'); ?>" alt="<?php esc_attr_e('RS WP BOOK SHOWCASE', 'rswpbs'); ?>">
                    </div>
                </div>
                <div class="rs-wp-book-showase-opt-in-content-col">
                    <h4><?php esc_html_e('Love using RS WP BOOK SHOWCASE?', 'rswpbs'); ?></h4>
                    <p><?php esc_html_e('Become a super contributor by opting in to share non-sensitive plugin data and to receive periodic email updates from us.', 'rswpbs'); ?></p>
                    <div class="opt-in-buttons-wrapper">
                        <a href="?opt_in_success" id="yes-i-would-love-to" class="button button-primary"><?php esc_html_e('Sure! I\'d love to help', 'rswpbs'); ?></a>
                        <a href="?opt_in_unsuccess" id="no-thank-you" class="button"><?php esc_html_e('No Thanks', 'rswpbs'); ?></a>
                        <a href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/'); ?>" id="upgrade-to-pro" class="button"><?php esc_html_e('Upgrade To Pro', 'rswpbs'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function rswpbs_send_email() {
    $admin_email = get_option('admin_email');
    if (empty($admin_email)) {
        return new WP_Error('no_admin_email', 'Admin email not found.');
    }

    $user_id = get_current_user_id();
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $website_url = untrailingslashit(home_url());
    $api_key = get_option('rswpthemes_api_key');
    $plugin_name = 'RS WP Book Showcase';
    $active_theme = wp_get_theme();
    $theme_name = $active_theme->get('Name');
    $theme_version = $active_theme->get('Version');

    $active_plugins = get_option('active_plugins', array());
    $plugins_list = array();
    foreach ($active_plugins as $plugin) {
        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin, false, false);
        $plugins_list[] = array(
            'name' => $plugin_data['Name'],
            'version' => $plugin_data['Version'],
        );
    }

    $response = wp_remote_post('https://rswpthemes.com/wp-json/rswpthemes/v1/collect_email/', array(
        'method' => 'POST',
        'timeout' => 10,
        'headers' => array(
            'Content-Type' => 'application/json',
            'X-RSWPTHEMES-API-Key' => $api_key,
        ),
        'body' => json_encode(array(
            'email' => $admin_email,
            'website_name' => get_bloginfo('name'),
            'website_url' => $website_url,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'plugin_name' => $plugin_name,
            'theme' => array(
                'name' => $theme_name,
                'version' => $theme_version,
            ),
            'plugins' => $plugins_list,
        )),
    ));

    if (!is_wp_error($response)) {
        // Store locally as CPT
        $existing_subscriber = new WP_Query(array(
            'post_type' => 'rswpthemes_subscriber',
            'meta_query' => array(
                array(
                    'key' => 'email',
                    'value' => $admin_email,
                ),
                array(
                    'key' => 'website_url',
                    'value' => $website_url,
                ),
            ),
            'posts_per_page' => 1,
        ));

        if (!$existing_subscriber->have_posts()) {
            $post_id = wp_insert_post(array(
                'post_type' => 'rswpthemes_subscriber',
                'post_title' => $admin_email,
                'post_status' => 'publish',
            ));

            if (!is_wp_error($post_id)) {
                update_post_meta($post_id, 'email', $admin_email);
                update_post_meta($post_id, 'website_url', $website_url);
                update_post_meta($post_id, 'first_name', $first_name);
                update_post_meta($post_id, 'last_name', $last_name);
                update_post_meta($post_id, 'plugin_name', $plugin_name);
                update_post_meta($post_id, 'theme_name', $theme_name);
                update_post_meta($post_id, 'theme_version', $theme_version);
                update_post_meta($post_id, 'plugins', wp_json_encode($plugins_list));
                update_post_meta($post_id, 'timestamp', current_time('mysql'));
            }
        }
    }

    return $response;
}

add_action('wp_ajax_rswpbs_collect_email', 'rswpbs_collect_email');
add_action('wp_ajax_nopriv_rswpbs_collect_email', 'rswpbs_collect_email');
function rswpbs_collect_email() {
    $response = rswpbs_send_email();
    if (is_wp_error($response)) {
        error_log('WP Remote Post Error: ' . $response->get_error_message());
        wp_send_json_error(array('error' => 'Failed to send request: ' . $response->get_error_message()));
    } else {
        wp_send_json_success(array('message' => 'Email stored successfully.'));
    }
}

add_action('admin_init', 'rswpbs_auto_send_email_if_opted_in');
function rswpbs_auto_send_email_if_opted_in() {
    $old_email_sent = get_option('rswpbs_optin_email_sent');
    $new_email_sent = get_option('rswpthemes_optin_email_sent');
    if (!empty($old_email_sent) && empty($new_email_sent)) {
        update_option('rswpthemes_optin_email_sent', $old_email_sent);
        delete_option('rswpbs_optin_email_sent');
    }

    if (get_option('rswpbs_optin_success') === '1' && !get_option('rswpthemes_optin_email_sent')) {
        $response = rswpbs_send_email();
        if (is_wp_error($response)) {
            error_log('Auto email send failed: ' . $response->get_error_message());
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code === 200) {
                update_option('rswpthemes_optin_email_sent', '1');
                error_log('Auto email sent successfully.');
            } else {
                error_log('Auto email send failed with response code: ' . $response_code);
            }
        }
    }
}

add_action('admin_enqueue_scripts', 'rswpbs_opt_in_script', 99);
function rswpbs_opt_in_script() {
    wp_enqueue_script('rswpthemes-opt-ins', RSWPBS_PLUGIN_URL . '/includes/opt-in/opt-in.js', array('jquery'), '1.0', true);
    wp_localize_script('rswpthemes-opt-ins', 'rswpthemes_opt_ins', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
    ));
}

add_action('admin_init', 'rswpbs_ensure_api_key_exists');
function rswpbs_ensure_api_key_exists() {
    $old_api_key = get_option('rswpbs_api_key');
    $old_api_key_registered = get_option('rswpbs_api_key_registered');
    $new_api_key = get_option('rswpthemes_api_key');
    $new_api_key_registered = get_option('rswpthemes_api_key_registered');

    if (!empty($old_api_key) && empty($new_api_key)) {
        update_option('rswpthemes_api_key', $old_api_key);
    }

    if (!empty($old_api_key_registered) && empty($new_api_key_registered)) {
        update_option('rswpthemes_api_key_registered', $old_api_key_registered);
    }

    delete_option('rswpbs_api_key');
    delete_option('rswpbs_api_key_registered');

    $existing_key = get_option('rswpthemes_api_key');
    $registered = get_option('rswpthemes_api_key_registered');

    if (!$existing_key) {
        $new_api_key = wp_generate_password(32, false, false);
        update_option('rswpthemes_api_key', $new_api_key);
        rswpthemes_register_api_key_on_server($new_api_key);
        update_option('rswpthemes_api_key_registered', '1');
    } elseif (!$registered) {
        rswpthemes_register_api_key_on_server($existing_key);
        update_option('rswpthemes_api_key_registered', '1');
    }
}

function rswpthemes_register_api_key_on_server($api_key) {
    $server_url = 'https://rswpthemes.com/wp-json/rswpthemes/v1/register_api_key/';
    $website_url = untrailingslashit(home_url());

    error_log("Registering API key for website: $website_url");

    $response = wp_remote_post($server_url, array(
        'method' => 'POST',
        'timeout' => 10,
        'headers' => array(
            'Content-Type' => 'application/json'
        ),
        'body' => json_encode(array(
            'api_key' => $api_key,
            'website_name' => get_bloginfo('name'),
            'website_url' => $website_url
        )),
    ));

    if (is_wp_error($response)) {
        error_log('Failed to register API key on the server: ' . $response->get_error_message());
    }
}