<?php
if (!class_exists('Rswpbs_Pro')) :
    add_action('admin_init', 'rswpbs_control_optin_notice');
endif;

function rswpbs_control_optin_notice() {
    $optin_success = get_option('rswpbs_optin_success');
    $hide_notice_transient = get_transient('hide_notice_for_3_days');

    $notice_show = true;

    if ('1' == $optin_success) {
        $notice_show = false;
    } elseif ('1' == $hide_notice_transient) {
        // Check if more than 3 days have passed since setting the transient
        $time_since_transient = current_time('timestamp') - get_option('rswpbs_optin_unsuccess_time');

        if ($time_since_transient >= 3 * 24 * 60 * 60) {
            $notice_show = true;
            // Update the option to record the time of the last "No Thanks" button click
            update_option('rswpbs_optin_unsuccess_time', current_time('timestamp'));
        } else {
            $notice_show = false;
        }
    }
    if (isset($_GET['opt_in_unsuccess'])) {
        $notice_show = false;
    }
    if ( true == $notice_show) {
        add_action('admin_notices', 'rswpbs_optin_notice');
    }
}

/**
 * Handle Button Clicks
 */
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


/**
 * Admin Notice HTML Markup
 */
function rswpbs_optin_notice() {
    ?>
    <div class="notice notice-info rs-wp-book-showcase-notice-container is-dismissible">
        <div class="rs-wp-book-showase-opt-in-wrapper">
            <div class="rs-wp-book-showase-optin-inner">
                <div class="rs-wp-book-showase-opt-in-logo-col">
                    <div class="rs-wp-book-showcase-logo">
                        <img src="<?php echo esc_url( RSWPBS_PLUGIN_URL . 'includes/assets/img/rs-wp-book-showcase-logo.png' );?>" alt="<?php esc_attr_e('RS WP BOOK SHOWCASE', 'rswpbs');?>">
                    </div>
                </div>
                <div class="rs-wp-book-showase-opt-in-content-col">
                    <h4><?php esc_html_e( 'Love using RS WP BOOK SHOWCASE?', 'rswpbs' );?></h4>
                    <p><?php esc_html_e('Become a super contributor by opting in to share non-sensitive plugin data and to receive periodic email updates from us.', 'rswpbs'); ?></p>
                    <a href="?opt_in_success" id="yes-i-would-love-to" class="button button-primary"><?php esc_html_e('Sure! I\'d love to help', 'rswpbs'); ?></a>
                    <a href="?opt_in_unsuccess" id="no-thank-you" class="button"><?php esc_html_e( 'No Thanks', 'rswpbs' );?></a>
                    <a href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>" id="upgrade-to-pro" class="button"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a>
                </div>
            </div>
        </div>
    </div>
    <?php
}

add_action('wp_ajax_rswpbs_collect_email', 'rswpbs_collect_email');
add_action('wp_ajax_nopriv_rswpbs_collect_email', 'rswpbs_collect_email');
function rswpbs_collect_email() {
    $admin_email = get_option('admin_email');
    if (!empty($admin_email)) {
        $to = 'rswpthemes@gmail.com';
        $subject = 'RS WP BOOK SHOWCASE NEW SUBSCRIBER';
        $website_name = get_bloginfo('name');
        $website_url = get_bloginfo('url');

        $user_id = get_current_user_id();
        $first_name = get_user_meta($user_id, 'first_name', true);
        $last_name = get_user_meta($user_id, 'last_name', true);

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $first_name . ' ' . $last_name . ' <' . $admin_email . '>'
        );

        $message = "Website Name: " . $website_name . "<br>";
        $message .= "Website URL: " . $website_url . "<br>";
        $message .= "Full Name: " . $first_name . " " . $last_name . "<br>";
        $message .= "Email Address: " . $admin_email . "<br>";

        $sent = wp_mail($to, $subject, $message, $headers);
        if ($sent) {
            $response = array(
                'success' => true,
                'data' => array()
            );
        } else {
            $response = array(
                'success' => false,
                'data' => array(
                    'error' => 'Failed to send the email.'
                )
            );
        }
    } else {
        $response = array(
            'success' => false,
            'data' => array(
                'error' => 'Admin email not found.'
            )
        );
    }
    wp_send_json($response);
    wp_die();
}
function rswpbs_opt_in_script() {
    wp_enqueue_script('rswpthemes-opt-ins', RSWPBS_PLUGIN_URL . '/includes/opt-in/opt-in.js', array('jquery'), '1.0', true);
    wp_localize_script( 'rswpthemes-opt-ins', 'rswpthemes_opt_ins',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        )
    );
}
add_action('admin_enqueue_scripts', 'rswpbs_opt_in_script', 99);
