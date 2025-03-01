<?php
/**
 * RS WP Book Showcase Colors Settings - Secure Version
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Sanitizes a hex color.
 *
 * @param string $color The color value.
 * @return string Valid hex color if valid, empty string otherwise.
 */
function rswpbs_sanitize_hex_color( $color ) {
    if ( empty( $color ) ) {
        return '';
    }
    // Ensure the color is a valid 6-digit hex color.
    if ( preg_match( '/^#[a-f0-9]{6}$/i', $color ) ) {
        return $color;
    }
    return '';
}

/**
 * Add Colors submenu under the Book custom post type.
 */
add_action( 'admin_menu', 'rswpbs_colors_settings_menu_page' );
function rswpbs_colors_settings_menu_page() {
    add_submenu_page(
        'edit.php?post_type=book',
        esc_html__( 'Colors', 'rswpbs' ),
        esc_html__( 'Colors', 'rswpbs' ),
        'manage_options',
        'rswpbs-settings-colors',
        'rswpbs_colors_page'
    );
}

/**
 * Display the Colors Settings page.
 */
function rswpbs_colors_page() {
    // Check if the current user has the required capability.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'rswpbs' ) );
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'RS WP Book Showcase Settings', 'rswpbs' ); ?></h1>
        <?php rswpbs_settings_tabs( 'colors' ); ?>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'rswpbs_colors_settings_group' );
            do_settings_sections( 'rswpbs_colors_settings_page' );
            submit_button();
            ?>
        </form>
        <script>
            jQuery(document).ready(function($){
                $('.color-picker').wpColorPicker();
            });
        </script>
    </div>
    <?php
}

/**
 * Register settings, sections, and fields for colors.
 */
function rswpbs_colors_register_settings() {
    // Register settings with sanitization.
    register_setting( 'rswpbs_colors_settings_group', 'rswpbs_primary_color', 'rswpbs_sanitize_hex_color' );
    register_setting( 'rswpbs_colors_settings_group', 'rswpbs_button_background_color', 'rswpbs_sanitize_hex_color' );
    register_setting( 'rswpbs_colors_settings_group', 'rswpbs_button_text_color', 'rswpbs_sanitize_hex_color' );
    register_setting( 'rswpbs_colors_settings_group', 'secondary_color', 'rswpbs_sanitize_hex_color' );
    register_setting( 'rswpbs_colors_settings_group', 'rswpbs_pages_bg_color', 'rswpbs_sanitize_hex_color' );
    register_setting( 'rswpbs_colors_settings_group', 'rswpbs_container_bg_color', 'rswpbs_sanitize_hex_color' );
    register_setting( 'rswpbs_colors_settings_group', 'bookinfo_bg_color', 'rswpbs_sanitize_hex_color' );

    // Add Section.
    add_settings_section(
        'rswpbs_colors_settings_section',
        esc_html__( 'Color Settings', 'rswpbs' ),
        '__return_false',
        'rswpbs_colors_settings_page'
    );

    // Add Fields.
    add_settings_field( 'rswpbs_primary_color', esc_html__( 'Primary Color', 'rswpbs' ), 'rswpbs_primary_color_callback', 'rswpbs_colors_settings_page', 'rswpbs_colors_settings_section' );
    add_settings_field( 'rswpbs_button_background_color', esc_html__( 'Button Background Color', 'rswpbs' ), 'rswpbs_button_background_color_callback', 'rswpbs_colors_settings_page', 'rswpbs_colors_settings_section' );
    add_settings_field( 'rswpbs_button_text_color', esc_html__( 'Button Text Color', 'rswpbs' ), 'rswpbs_button_text_color_callback', 'rswpbs_colors_settings_page', 'rswpbs_colors_settings_section' );
    add_settings_field( 'secondary_color', esc_html__( 'Gray Color', 'rswpbs' ), 'rswpbs_secondary_color_callback', 'rswpbs_colors_settings_page', 'rswpbs_colors_settings_section' );
    add_settings_field( 'rswpbs_pages_bg_color', esc_html__( 'Pages Background Color', 'rswpbs' ), 'rswpbs_pages_bg_color_callback', 'rswpbs_colors_settings_page', 'rswpbs_colors_settings_section' );
    add_settings_field( 'rswpbs_container_bg_color', esc_html__( 'Container Background Color', 'rswpbs' ), 'rswpbs_container_bg_color_callback', 'rswpbs_colors_settings_page', 'rswpbs_colors_settings_section' );
    add_settings_field( 'bookinfo_bg_color', esc_html__( 'Book Info Background Color', 'rswpbs' ), 'rswpbs_bookinfo_bg_color_callback', 'rswpbs_colors_settings_page', 'rswpbs_colors_settings_section' );
}
add_action( 'admin_init', 'rswpbs_colors_register_settings' );

/**
 * Callback functions for each color field.
 */
function rswpbs_primary_color_callback() {
    $value = get_option( 'rswpbs_primary_color', '' );
    echo '<input type="text" name="rswpbs_primary_color" class="color-picker" value="' . esc_attr( $value ) . '" data-default-color="#000000">';
}

function rswpbs_button_background_color_callback() {
    $value = get_option( 'rswpbs_button_background_color', '' );
    echo '<input type="text" name="rswpbs_button_background_color" class="color-picker" value="' . esc_attr( $value ) . '" data-default-color="#000000">';
}

function rswpbs_button_text_color_callback() {
    $value = get_option( 'rswpbs_button_text_color', '' );
    echo '<input type="text" name="rswpbs_button_text_color" class="color-picker" value="' . esc_attr( $value ) . '" data-default-color="#ffffff">';
}

function rswpbs_secondary_color_callback() {
    $value = get_option( 'secondary_color', '' );
    echo '<input type="text" name="secondary_color" class="color-picker" value="' . esc_attr( $value ) . '" data-default-color="#808080">';
}

function rswpbs_pages_bg_color_callback() {
    $value = get_option( 'rswpbs_pages_bg_color', '' );
    echo '<input type="text" name="rswpbs_pages_bg_color" class="color-picker" value="' . esc_attr( $value ) . '" data-default-color="#f5f5f5">';
}

function rswpbs_container_bg_color_callback() {
    $value = get_option( 'rswpbs_container_bg_color', '' );
    echo '<input type="text" name="rswpbs_container_bg_color" class="color-picker" value="' . esc_attr( $value ) . '" data-default-color="#ffffff">';
}

function rswpbs_bookinfo_bg_color_callback() {
    $value = get_option( 'bookinfo_bg_color', '' );
    echo '<input type="text" name="bookinfo_bg_color" class="color-picker" value="' . esc_attr( $value ) . '" data-default-color="#f0f0f0">';
}

/**
 * Enqueue the WP Color Picker assets on the settings page.
 */
function rswpbs_enqueue_color_picker( $hook ) {
    // Only load on our plugin settings page.
    if ( false === strpos( $hook, 'rswpbs-settings-colors' ) ) {
        return;
    }
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'rswpbs_enqueue_color_picker' );
