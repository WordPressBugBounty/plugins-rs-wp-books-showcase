<?php
/**
 * General Settings - Secure Version
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Returns an associative array of allowed currencies.
 *
 * Duplicate values have been removed.
 *
 * @return array
 */
function rswpbs_get_allowed_currencies() {
    return array(
        '$'     => '$',
        '€'     => '€',
        '¥'     => '¥',
        '£'     => '£',
        'A$'    => 'A$',
        'C$'    => 'C$',
        'CHF'   => 'CHF',
        'kr'    => 'kr',
        'NZ$'   => 'NZ$',
        'Mex$'  => 'Mex$',
        'S$'    => 'S$',
        'HK$'   => 'HK$',
        '₩'     => '₩',
        '₺'     => '₺',
        '₹'     => '₹',
        'R$'    => 'R$',
        'R'     => 'R',
        '₽'     => '₽',
        'إ'     => 'إ',
        'RM'    => 'RM',
        'KM'    => 'KM',
        '฿'     => '฿',
        'Rp'    => 'Rp',
        'zł'    => 'zł',
        'CLP$'  => 'CLP$',
        '₱'     => '₱',
        'COL$'  => 'COL$',
        '₫'     => '₫',
        'ARS$'  => 'ARS$',
        'EGP£'  => 'EGP£',
        'S/'    => 'S/',
        'Kč'    => 'Kč',
        'Ft'    => 'Ft',
        '₪'     => '₪',
        'NT$'   => 'NT$',
        '₴'     => '₴',
        '₡'     => '₡',
        'ا'     => 'ا',
        'Rs'    => 'Rs',
        'ZK'    => 'ZK',
        'B$'    => 'B$',
        'лв'    => 'лв',
        'lei'   => 'lei',
        'kn'    => 'kn',
        'RD$'   => 'RD$',
        'L'     => 'L',
        'रू'    => 'रू',
        'N$'    => 'N$',
        'U'     => 'U',
        'ق'     => 'ق',
        'J$'    => 'J$',
        'TT$'   => 'TT$',
        'TSh'   => 'TSh',
        'P'     => 'P',
        '₾'     => '₾',
        'USh'   => 'USh',
        '₸'     => '₸',
        '֏'     => '֏',
        'ден'   => 'ден',
        'FCFA'  => 'FCFA',
        '₵'     => '₵',
        'Bds$'  => 'Bds$',
        'BSD$'  => 'BSD$',
        'FJ$'   => 'FJ$',
        'CFA'   => 'CFA',
        '₨'     => '₨',
        '₭'     => '₭',
        'ETB'   => 'ETB',
        'CUP$'  => 'CUP$',
        'KSh'   => 'KSh',
    );
}

/**
 * Sanitization callback for Price Currency.
 *
 * @param string $value The submitted currency.
 * @return string Allowed currency or default.
 */
function rswpbs_sanitize_price_currency( $value ) {
    $allowed = rswpbs_get_allowed_currencies();
    if ( isset( $allowed[ $value ] ) ) {
        return $value;
    }
    return '$'; // Return default if invalid.
}

/**
 * Sanitization callback for the Book Search Page field.
 *
 * @param mixed $value The submitted value.
 * @return int Sanitized page ID.
 */
function rswpbs_sanitize_book_search_page( $value ) {
    return absint( $value );
}

/**
 * Sanitization callback for checkbox fields.
 *
 * @param mixed $value The submitted value.
 * @return int Returns 1 if checked, 0 otherwise.
 */
function rswpbs_sanitize_checkbox( $value ) {
    return ( $value == 1 ) ? 1 : 0;
}

/**
 * Sanitization callback for roles allowed to manage books.
 *
 * @param array $value The submitted roles.
 * @return array Sanitized array of role slugs.
 */
function rswpbs_sanitize_roles_to_manage_books( $value ) {
    if ( ! is_array( $value ) ) {
        return array();
    }
    $valid_roles = array_keys( wp_roles()->roles );
    return array_intersect( $value, $valid_roles ); // Only keep valid role slugs
}

/**
 * Add the General Settings submenu under the Book custom post type.
 */
add_action( 'admin_menu', 'rswpbs_general_settings_page' );
function rswpbs_general_settings_page() {
    add_submenu_page(
        'edit.php?post_type=book', // Custom post type slug.
        esc_html__( 'RS WP Book Showcase Settings', 'rswpbs' ),
        esc_html__( 'Book Showcase Settings', 'rswpbs' ),
        'manage_options',
        'rswpbs-settings',
        'rswp_book_showcase_settings_page'
    );
}

/**
 * Render the General Settings page.
 */
function rswp_book_showcase_settings_page() {
    // Verify user capability.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'rswpbs' ) );
    }

    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
    ?>
    <div class="wrap rswpbs-general-settings-tab">
        <h1><?php esc_html_e( 'RS WP Book Showcase Settings', 'rswpbs' ); ?></h1>
        <?php rswpbs_settings_tabs( $active_tab ); ?>
        <form method="post" action="options.php">
            <?php
            wp_nonce_field( 'rswpbs_settings_nonce', 'rswpbs_settings_nonce_field' );
            settings_fields( 'rswpbs_general_settings_group' );
            do_settings_sections( 'rswpbs_general_settings_page' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Register General Settings along with their sanitization callbacks.
 */
function rswpbs_register_general_settings() {
    // Register settings.
    register_setting( 'rswpbs_general_settings_group', 'rswpbs_price_currency', 'rswpbs_sanitize_price_currency' );
    register_setting( 'rswpbs_general_settings_group', 'rswpbs_amazon_tracking_id', 'sanitize_text_field' );
    register_setting( 'rswpbs_general_settings_group', 'rswpbs_enable_editor_for_author_description', 'rswpbs_sanitize_checkbox' );
    register_setting( 'rswpbs_general_settings_group', 'rswpbs_enable_woo_features_for_books', 'rswpbs_sanitize_checkbox' );
    register_setting( 'rswpbs_general_settings_group', 'rswpbs_roles_to_manage_books', array(
        'sanitize_callback' => 'rswpbs_sanitize_roles_to_manage_books',
        'default' => array( 'administrator' ),
    ) );

    // Add Settings Section.
    add_settings_section(
        'rswpbs_general_settings_section',
        esc_html__( 'General Settings', 'rswpbs' ),
        '__return_false',
        'rswpbs_general_settings_page'
    );

    // Add Fields.
    add_settings_field(
        'rswpbs_price_currency',
        esc_html__( 'Price Currency', 'rswpbs' ),
        'rswpbs_price_currency_callback',
        'rswpbs_general_settings_page',
        'rswpbs_general_settings_section'
    );

    add_settings_field(
        'rswpbs_amazon_tracking_id',
        esc_html__( 'Amazon Tracking ID', 'rswpbs' ),
        'rswpbs_amazon_tracking_id_callback',
        'rswpbs_general_settings_page',
        'rswpbs_general_settings_section'
    );

    add_settings_field(
        'rswpbs_enable_editor_for_author_description',
        esc_html__( 'Enable Editor For Author Description', 'rswpbs' ),
        'rswpbs_enable_editor_for_author_description_callback',
        'rswpbs_general_settings_page',
        'rswpbs_general_settings_section'
    );

    add_settings_field(
        'rswpbs_enable_woo_features_for_books',
        esc_html__( 'Allow Customers to Buy All Books via WooCommerce', 'rswpbs' ),
        'rswpbs_enable_woo_features_for_books_callback',
        'rswpbs_general_settings_page',
        'rswpbs_general_settings_section'
    );

    add_settings_field(
        'rswpbs_roles_to_manage_books',
        esc_html__( 'Roles Allowed to Manage Books', 'rswpbs' ),
        'rswpbs_roles_to_manage_books_callback',
        'rswpbs_general_settings_page',
        'rswpbs_general_settings_section'
    );
}
add_action( 'admin_init', 'rswpbs_register_general_settings' );

/**
 * Callback for Price Currency field.
 */
function rswpbs_price_currency_callback() {
    $allowed  = rswpbs_get_allowed_currencies();
    $selected = get_option( 'rswpbs_price_currency', '$' );
    echo '<select name="rswpbs_price_currency">';
    foreach ( $allowed as $key => $label ) {
        echo '<option value="' . esc_attr( $key ) . '" ' . selected( $selected, $key, false ) . '>' . esc_html( $label ) . '</option>';
    }
    echo '</select>';
}

/**
 * Callback for Amazon Tracking ID field.
 */
function rswpbs_amazon_tracking_id_callback() {
    $is_pro = rswpbs_is_pro_active(); // Check if Pro version is active
    $tracking_id = get_option( 'rswpbs_amazon_tracking_id', 'lft01-20' ); // Default value set

    if ( $is_pro ) {
        // Show editable input for Pro users
        ?>
        <input type="text" name="rswpbs_amazon_tracking_id" value="<?php echo esc_attr( $tracking_id ); ?>" class="regular-text" placeholder="Enter your Amazon Tracking ID">
        <p class="description"><?php esc_html_e( 'Your Amazon Tracking ID is used to earn commissions from Amazon affiliate links.', 'rswpbs' ); ?></p>
        <?php
    } else {
        // Show locked input for Free users
        ?>
        <input type="text" value="<?php echo esc_attr( $tracking_id ); ?>" class="regular-text amz-trcking-input-free" disabled>
        <p class="amz-trcking-id-description" style="color: red;">
            <?php esc_html_e( 'Available in Pro version only.', 'rswpbs' ); ?> <a href="<?php echo esc_url( 'https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/' ); ?>" target="_blank">
            <?php esc_html_e( 'Upgrade to Pro', 'rswpbs' ); ?>
        </a>
        </p>
        <?php
    }
}

/**
 * Callback for Enable Editor For Author Description field.
 */
function rswpbs_enable_editor_for_author_description_callback() {
    $is_pro = rswpbs_is_pro_active();
    $checked = get_option( 'rswpbs_enable_editor_for_author_description', 0 );
    if ($is_pro) {
        echo '<input type="checkbox" name="rswpbs_enable_editor_for_author_description" value="1" ' . checked( 1, $checked, false ) . ' />';
    } else {
        echo '<input type="checkbox" disabled />';
        echo ' <span style="color: red;">' . esc_html__( 'Available in Pro version only.', 'rswpbs' ) . '</span>';
        echo ' <a href="'.esc_url('https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/').'" target="_blank">' . esc_html__( 'Upgrade to Pro', 'rswpbs' ) . '</a>';
    }
}

/**
 * Callback for WooCommerce features for books field.
 */
function rswpbs_enable_woo_features_for_books_callback() {
    $is_pro = rswpbs_is_pro_active(); // Assuming you define this constant in the Pro version
    $checked = get_option( 'rswpbs_enable_woo_features_for_books', 0 );

    if ( $is_pro ) {
        echo '<input type="checkbox" name="rswpbs_enable_woo_features_for_books" value="1" ' . checked( 1, $checked, false ) . ' />';
    } else {
        echo '<input type="checkbox" disabled />';
        echo ' <span style="color: red;">' . esc_html__( 'Available in Pro version only.', 'rswpbs' ) . '</span>';
        echo ' <a href="'.esc_url('https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/').'" target="_blank">' . esc_html__( 'Upgrade to Pro', 'rswpbs' ) . '</a>';
    }
}

/**
 * Callback for Roles Allowed to Manage Books field.
 */
function rswpbs_roles_to_manage_books_callback() {
    $is_pro = rswpbs_is_pro_active();
    $roles = wp_roles()->roles;
    $selected_roles = get_option( 'rswpbs_roles_to_manage_books', array('administrator') );
    if ( ! is_array( $selected_roles ) ) {
        $selected_roles = array();
    }

    if ( $is_pro ) {
        // Show editable checkboxes for Pro users
        foreach ( $roles as $role_slug => $role ) {
            ?>
            <label>
                <input type="checkbox" name="rswpbs_roles_to_manage_books[]" value="<?php echo esc_attr( $role_slug ); ?>" <?php checked( in_array( $role_slug, $selected_roles ) ); ?>>
                <?php echo esc_html( $role['name'] ); ?>
            </label><br>
            <?php
        }
        ?>
        <p class="description"><?php esc_html_e( 'Select the user roles that are allowed to manage books via the frontend.', 'rswpbs' ); ?></p>
        <?php
    } else {
        // Show disabled checkboxes for Free users
        foreach ( $roles as $role_slug => $role ) {
            ?>
            <label>
                <input type="checkbox" disabled <?php checked( in_array( $role_slug, $selected_roles ) ); ?>>
                <?php echo esc_html( $role['name'] ); ?>
            </label><br>
            <?php
        }
        ?>
        <p class="description" style="color: red;">
            <?php esc_html_e( 'Selecting roles to manage books is available in the Pro version only.', 'rswpbs' ); ?>
            <a href="<?php echo esc_url( 'https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/' ); ?>" target="_blank">
                <?php esc_html_e( 'Upgrade to Pro', 'rswpbs' ); ?>
            </a>
        </p>
        <?php
    }
}