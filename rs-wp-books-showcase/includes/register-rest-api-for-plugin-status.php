<?php
function rswpbs_check_plugin_status() {
    // Replace 'plugin-folder/plugin-main-file.php' with your actual plugin path
    $plugin_slug = 'rs-wp-books-showcase-pro/rs-wp-books-showcase-pro.php';

    return rest_ensure_response([
        'isActive' => is_plugin_active($plugin_slug),
    ]);
}

// Register REST API Route
function rswpbs_register_plugin_status_route() {
    register_rest_route('rswpbs/v1', '/plugin-status/', array(
        'methods'  => 'GET',
        'callback' => 'rswpbs_check_plugin_status',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'rswpbs_register_plugin_status_route');
