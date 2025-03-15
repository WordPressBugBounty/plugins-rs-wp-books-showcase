<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Book Single Page Settings
 */
add_action( 'admin_menu', 'rswpbs_book_single_settings_page' );
function rswpbs_book_single_settings_page() {
    // Ensure the current user has proper capability.
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    add_submenu_page(
        'edit.php?post_type=book',
        esc_html__( 'Single Page', 'rswpbs' ),
        esc_html__( 'Single Page', 'rswpbs' ),
        'manage_options',
        'rswpbs-settings-book-single',
        'rswpbs_book_single_page'
    );
}

function rswpbs_book_single_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'rswpbs' ) );
    }
    wp_enqueue_script( 'jquery' ); // Ensure jQuery is loaded
    ?>
    <div class="wrap rswpbs-single-page-settings-tab">
        <h1><?php esc_html_e( 'RS WP Book Showcase Single Page Settings', 'rswpbs' ); ?></h1>
        <?php
        // If you use tabs, ensure the function exists.
        if ( function_exists( 'rswpbs_settings_tabs' ) ) {
            rswpbs_settings_tabs( 'book_single' );
        }
        ?>
        <form method="post" action="options.php">
            <?php settings_fields( 'rswpbs_book_single_settings_group' );
            $is_pro_active = rswpbs_is_pro_active();
            $proclass = ($is_pro_active) ? ' rswpbs-pro-active' : '';
            ?>
            <table class="form-table rswpbs-settings-table<?php echo esc_attr($proclass);?>">
                <tbody>
                    <?php
                    // Define all your fields.
                    $fields = array(
                        array(
                            'id'    => 'rswpbs_show_ratings_on_single_page',
                            'label' => esc_html__( 'Show Ratings', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_excerpt_on_single_page',
                            'label' => esc_html__( 'Show Excerpt', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'      => 'rswpbs_single_page_excerpt_type',
                            'label'   => esc_html__( 'Excerpt Type', 'rswpbs' ),
                            'type'    => 'select',
                            'options' => array(
                                'excerpt'      => esc_html__( 'Excerpt', 'rswpbs' ),
                                'full_content' => esc_html__( 'Full Content', 'rswpbs' ),
                            ),
                        ),
                        array(
                            'id'    => 'rswpbs_single_page_excerpt_limit',
                            'label' => esc_html__( 'Excerpt Limit', 'rswpbs' ),
                            'type'  => 'text',
                        ),
                        array(
                            'id'    => 'rswpbs_show_author_on_single_page',
                            'label' => esc_html__( 'Show Author', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_price_on_single_page',
                            'label' => esc_html__( 'Show Price', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_buy_button_on_single_page',
                            'label' => esc_html__( 'Show Buy Button', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'      => 'rswpbs_single_image_type',
                            'label'   => esc_html__( 'Book Cover Type', 'rswpbs' ),
                            'type'    => 'select',
                            'options' => array(
                                'book_cover'  => esc_html__( 'Book Cover', 'rswpbs' ),
                                'book_mockup' => esc_html__( 'Book Mockup', 'rswpbs' ),
                            ),
                        ),
                        array(
                            'id'    => 'rswpbs_show_book_long_description',
                            'label' => esc_html__( 'Show Book Long Description', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_book_reviews',
                            'label' => esc_html__( 'Show Book Reviews', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_review_form',
                            'label' => esc_html__( 'Show Review Form', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_book_reviews_section_title',
                            'label' => esc_html__( 'Show Reviews Section Title', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_submit_review_without_login',
                            'label' => esc_html__( 'Allow Logged Out Users To Submit Review Form', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_addtocart_on_single_page',
                            'label' => esc_html__( 'Show Add To Cart Button', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_msl_on_single_page',
                            'label' => esc_html__( 'Show Multiple Purchase Links', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_sample_content_on_single_page',
                            'label' => esc_html__( 'Show Sample Content', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                        array(
                            'id'    => 'rswpbs_show_book_formats_on_single_page',
                            'label' => esc_html__( 'Show Book Formats', 'rswpbs' ),
                            'type'  => 'checkbox',
                        ),
                    );

                    $counter = 0;

                    $pro_fields = array(
                        'rswpbs_show_addtocart_on_single_page' => esc_html__( 'Show Add To Cart Button', 'rswpbs' ),
                        'rswpbs_show_msl_on_single_page'       => esc_html__( 'Show Multiple Purchase Links', 'rswpbs' ),
                        'rswpbs_show_sample_content_on_single_page' => esc_html__( 'Show Sample Content', 'rswpbs' ),
                        'rswpbs_submit_review_without_login' => esc_html__( 'Allow Logged Out Users To Submit Review Form', 'rswpbs' ),
                        'rswpbs_show_book_formats_on_single_page' => esc_html__( 'Show Book Formats', 'rswpbs' ),
                    );

                    $pro_plugin_active = rswpbs_is_pro_active();

                    foreach ( $fields as $field ) {
                        if ( 0 === $counter % 4 ) {
                            echo '<tr>';
                        }

                        echo '<td class="rswpbs-box" style="vertical-align: top; padding: 10px;">';

                        $is_pro_feature = isset( $pro_fields[ $field['id'] ] );

                        // Output the label.
                        echo '<label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . ': </label>';

                        // Get the saved option value.
                        $value = get_option( $field['id'] );

                        // If it's a Pro feature and the Pro plugin is not active, uncheck it.
                        if ( ! $pro_plugin_active && $is_pro_feature ) {
                            $value = 0; // Force checkbox to be unchecked
                            update_option( $field['id'], 0 ); // Ensure it's saved as unchecked
                        }

                        // Display the appropriate field type.
                        switch ( $field['type'] ) {
                            case 'checkbox':
                                echo '<input type="checkbox" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" value="1" ' . checked( 1, $value, false ) . ( ! $pro_plugin_active && $is_pro_feature ? ' disabled' : '' ) . '>';
                                if ( ! $pro_plugin_active && $is_pro_feature ) {
                                    echo '<br><small style="color: red;">' . esc_html__( 'This feature is available in Pro version.', 'rswpbs' ) . ' <a href="'.esc_url('https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/').'" target="_blank">' . esc_html__( 'Upgrade Now', 'rswpbs' ) . '</a></small>';
                                }
                                break;

                            case 'text':
                                if ( $field['id'] === 'rswpbs_single_page_excerpt_limit' ) {
                                    echo '<input type="number" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ? $value : 150 ) . '" min="1" step="1">';
                                    echo ' <small>' . esc_html__( '(characters)', 'rswpbs' ) . '</small>';
                                } else {
                                    echo '<input type="text" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '">';
                                }
                                break;

                            case 'select':
                                echo '<select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '">';
                                foreach ( $field['options'] as $option_value => $option_label ) {
                                    $selected = ( $value === $option_value || ( !$value && $option_value === ( $field['id'] === 'rswpbs_single_page_excerpt_type' ? 'excerpt' : 'book_cover' ) ) ) ? 'selected' : '';
                                    echo '<option value="' . esc_attr( $option_value ) . '" ' . $selected . '>' . esc_html( $option_label ) . '</option>';
                                }
                                echo '</select>';
                                break;
                        }

                        echo '</td>';
                        $counter++;

                        if ( 0 === $counter % 4 ) {
                            echo '</tr>';
                        }
                    }

                    ?>
                </tbody>
            </table>
            <?php submit_button(); ?>
        </form>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var $showExcerpt = $('#rswpbs_show_excerpt_on_single_page');
            var $excerptType = $('#rswpbs_single_page_excerpt_type').closest('td');
            var $excerptLimit = $('#rswpbs_single_page_excerpt_limit').closest('td');

            // Initial state
            toggleFields();

            // On change of "Show Excerpt"
            $showExcerpt.on('change', function() {
                toggleFields();
            });

            // On change of "Excerpt Type"
            $('#rswpbs_single_page_excerpt_type').on('change', function() {
                toggleExcerptLimit();
            });

            function toggleFields() {
                if ($showExcerpt.is(':checked')) {
                    $excerptType.show();
                    toggleExcerptLimit();
                } else {
                    $excerptType.hide();
                    $excerptLimit.hide();
                }
            }

            function toggleExcerptLimit() {
                if ($('#rswpbs_single_page_excerpt_type').val() === 'excerpt') {
                    $excerptLimit.show();
                } else {
                    $excerptLimit.hide();
                }
            }
        });
        </script>
    </div>
    <style>
        .rswpbs-settings-table {
            width: 100%;
            border-collapse: collapse;
        }
        .rswpbs-settings-table td input, .rswpbs-settings-table td select {
            margin-left: 10px;
        }
        .rswpbs-settings-table td {
            padding: 15px 20px;
            border: 1px solid #ddd;
            text-align: left;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .rswpbs-box {
            padding: 10px;
            background: #ffffff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
        }
    </style>
    <?php
}

add_action( 'admin_init', 'rswpbs_book_single_register_settings' );
function rswpbs_book_single_register_settings() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Register settings with sanitization callbacks.
    $settings = array(
        'rswpbs_show_sample_content_on_single_page' => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_show_ratings_on_single_page'         => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_show_excerpt_on_single_page'         => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_single_page_excerpt_type'                        => 'rswpbs_sanitize_excerpt_type',
        'rswpbs_single_page_excerpt_limit'                       => 'rswpbs_sanitize_excerpt_limit',
        'rswpbs_show_author_on_single_page'          => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_show_price_on_single_page'           => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_show_addtocart_on_single_page'       => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_show_buy_button_on_single_page'      => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_show_msl_on_single_page'             => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_single_image_type'                   => 'rswpbs_sanitize_single_image_type',
        'rswpbs_show_book_long_description'          => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_show_book_reviews'                   => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_show_book_reviews_section_title'     => 'sanitize_text_field',
        'rswpbs_show_review_form'                    => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_submit_review_without_login'         => 'rswpbs_sanitize_checkbox_book_single_page',
        'rswpbs_show_book_formats_on_single_page'    => 'rswpbs_sanitize_checkbox_book_single_page',
    );

    foreach ( $settings as $setting => $sanitize_callback ) {
        register_setting( 'rswpbs_book_single_settings_group', $setting, array(
            'sanitize_callback' => $sanitize_callback,
            'default'           => ( $setting === 'rswpbs_single_page_excerpt_limit' ? 150 : ( $setting === 'rswpbs_single_page_excerpt_type' ? 'excerpt' : 1 ) ),
        ) );
    }

    // Add a settings section.
    add_settings_section(
        'rswpbs_book_single_settings_section',
        esc_html__( 'Book Single Page Settings', 'rswpbs' ),
        '__return_false',
        'rswpbs_book_single_settings_page'
    );

    // Add checkbox fields.
    rswpbs_add_single_page_setting_field( 'rswpbs_show_sample_content_on_single_page', esc_html__( 'Show Sample Content', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_ratings_on_single_page', esc_html__( 'Show Ratings', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_excerpt_on_single_page', esc_html__( 'Show Excerpt', 'rswpbs' ) );
    add_settings_field(
        'rswpbs_single_page_excerpt_type',
        esc_html__( 'Excerpt Type', 'rswpbs' ),
        'rswpbs_single_page_excerpt_type_callback',
        'rswpbs_book_single_settings_page',
        'rswpbs_book_single_settings_section'
    );
    add_settings_field(
        'rswpbs_single_page_excerpt_limit',
        esc_html__( 'Excerpt Limit', 'rswpbs' ),
        'rswpbs_single_page_excerpt_limit_callback',
        'rswpbs_book_single_settings_page',
        'rswpbs_book_single_settings_section'
    );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_author_on_single_page', esc_html__( 'Show Author', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_price_on_single_page', esc_html__( 'Show Price', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_addtocart_on_single_page', esc_html__( 'Show Add To Cart Button', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_buy_button_on_single_page', esc_html__( 'Show Buy Button', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_msl_on_single_page', esc_html__( 'Show Multiple Purchase Links', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_book_long_description', esc_html__( 'Show Book Long Description', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_book_reviews', esc_html__( 'Show Book Reviews', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_book_reviews_section_title', esc_html__( 'Show Reviews Section Title', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_review_form', esc_html__( 'Show Review Form', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_submit_review_without_login', esc_html__( 'Allow Logged Out Users To Submit Review Form', 'rswpbs' ) );
    rswpbs_add_single_page_setting_field( 'rswpbs_show_book_formats_on_single_page', esc_html__( 'Show Book Formats', 'rswpbs' ) );

    // Add a special select field.
    add_settings_field(
        'rswpbs_single_image_type',
        esc_html__( 'Book Cover Type', 'rswpbs' ),
        'rswpbs_single_image_type_callback',
        'rswpbs_book_single_settings_page',
        'rswpbs_book_single_settings_section'
    );
}

function rswpbs_add_single_page_setting_field( $option_name, $label ) {
    add_settings_field(
        $option_name,
        $label,
        'rswpbs_single_page_checkbox_callback',
        'rswpbs_book_single_settings_page',
        'rswpbs_book_single_settings_section',
        array( 'option_name' => $option_name )
    );
}

function rswpbs_single_page_checkbox_callback( $args ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $option_name = $args['option_name'];
    $checked     = get_option( $option_name, 0 );
    printf(
        '<input type="checkbox" name="%1$s" value="1" %2$s />',
        esc_attr( $option_name ),
        checked( 1, $checked, false )
    );
    echo ' ' . esc_html__( 'Show', 'rswpbs' );
}

function rswpbs_single_image_type_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $options  = array(
        'book_cover'  => esc_html__( 'Book Cover', 'rswpbs' ),
        'book_mockup' => esc_html__( 'Book Mockup', 'rswpbs' ),
    );
    $selected = get_option( 'rswpbs_single_image_type', 'book_cover' );

    echo '<select name="rswpbs_single_image_type">';
    foreach ( $options as $key => $label ) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr( $key ),
            selected( $selected, $key, false ),
            esc_html( $label )
        );
    }
    echo '</select>';
}

function rswpbs_single_page_excerpt_type_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $options  = array(
        'excerpt'      => esc_html__( 'Excerpt', 'rswpbs' ),
        'full_content' => esc_html__( 'Full Content', 'rswpbs' ),
    );
    $selected = get_option( 'rswpbs_single_page_excerpt_type', 'excerpt' );

    echo '<select name="rswpbs_single_page_excerpt_type" id="rswpbs_single_page_excerpt_type">';
    foreach ( $options as $key => $label ) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr( $key ),
            selected( $selected, $key, false ),
            esc_html( $label )
        );
    }
    echo '</select>';
}

function rswpbs_single_page_excerpt_limit_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $value = get_option( 'rswpbs_single_page_excerpt_limit', 150 );
    echo '<input type="number" id="rswpbs_single_page_excerpt_limit" name="rswpbs_single_page_excerpt_limit" value="' . esc_attr( $value ) . '" min="1" step="1">';
    echo ' <small>' . esc_html__( '(Enter the number of characters)', 'rswpbs' ) . '</small>';
}

// Sanitization callback for checkboxes.
function rswpbs_sanitize_checkbox_book_single_page( $input ) {
    return ( isset( $input ) && $input == 1 ) ? 1 : 0;
}

// Sanitization callback for the select field.
function rswpbs_sanitize_single_image_type( $input ) {
    $valid = array( 'book_cover', 'book_mockup' );
    return in_array( $input, $valid, true ) ? $input : 'book_cover';
}

function rswpbs_sanitize_excerpt_type( $input ) {
    $valid = array( 'excerpt', 'full_content' );
    return in_array( $input, $valid, true ) ? $input : 'excerpt';
}

function rswpbs_sanitize_excerpt_limit( $input ) {
    $input = absint( $input );
    return $input > 0 ? $input : 150;
}