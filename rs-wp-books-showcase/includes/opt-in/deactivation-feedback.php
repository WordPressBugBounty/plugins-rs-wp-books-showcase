<?php
add_action('admin_enqueue_scripts', 'rswpbs_deactivation_popup_scripts');
function rswpbs_deactivation_popup_scripts($hook) {
    // Enqueue jQuery (already included in WordPress admin)
    wp_enqueue_script('rswpbs-deactivation-feedback', RSWPBS_PLUGIN_URL . '/includes/opt-in/deactivation-feedback.js', array('jquery'), '1.0', true);
    wp_localize_script('rswpbs-deactivation-feedback', 'rswpbs_deactivation', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'plugin_slug' => 'rs-wp-books-showcase', // Adjust to your plugin's slug
    ));

    // Add inline CSS for the popup
    wp_enqueue_style('rswpbs-deactivation-style', RSWPBS_PLUGIN_URL . '/includes/opt-in/deactivation-feedback.css');
}

// AJAX handler to collect deactivation feedback
add_action('wp_ajax_rswpbs_collect_deactivation_feedback', 'rswpbs_collect_deactivation_feedback');

function rswpbs_collect_deactivation_feedback() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('error' => 'Permission denied'));
    }

    $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : 'Not specified';

    $admin_email = get_option('admin_email');
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
    error_log('Plugins list sent (deactivation): ' . print_r($plugins_list, true)); // Debug log

    $subscriber_data = array(
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
        'deactivation_reason' => $reason,
    );

    $api_url = 'https://rswpthemes.com/wp-json/rswpthemes/v1/collect_email/';
    $response = wp_remote_post($api_url, array(
        'method' => 'POST',
        'timeout' => 10,
        'headers' => array(
            'Content-Type' => 'application/json',
            'X-RSWPTHEMES-API-Key' => $api_key,
        ),
        'body' => json_encode($subscriber_data),
    ));

    if (is_wp_error($response)) {
        wp_send_json_error(array('error' => 'Failed to send feedback: ' . $response->get_error_message()));
    } else {
        wp_send_json_success(array('message' => 'Feedback collected successfully'));
    }
}

// Add the popup HTML to the footer of the plugins page
add_action('admin_footer-plugins.php', 'rswpbs_deactivation_popup_html');

function rswpbs_deactivation_popup_html() {
    ?>
    <div id="rswpbs-deactivation-feedback" style="display: none;">
        <div class="rswpbs-feedback-overlay"></div>
        <div class="rswpbs-feedback-content">
            <h2>Quick Feedback</h2>
            <p>If you have a moment, please share why you are deactivating RS WP Book Showcase:</p>
            <form id="rswpbs-deactivation-form">
                <label><input type="radio" name="reason" value="no-longer-need"> I no longer need the plugin</label><br>
                <label><input type="radio" name="reason" value="better-plugin"> I found a better plugin</label><br>
                <label><input type="radio" name="reason" value="not-working"> I couldn't get the plugin to work</label><br>
                <label><input type="radio" name="reason" value="temporary"> It's a temporary deactivation</label><br>
                <label><input type="radio" name="reason" value="other"> Other</label><br>
                <div class="rswpbs-feedback-buttons">
                    <button type="submit" class="button button-primary">Submit & Deactivate</button>
                    <a href="#" class="button rswpbs-skip-deactivate">Skip & Deactivate</a>
                </div>
            </form>
        </div>
    </div>
    <?php
}