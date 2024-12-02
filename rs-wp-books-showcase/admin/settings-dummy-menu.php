<?php
if (!class_exists('Rswpbs_Pro')) {
    add_action('admin_menu', 'rswp_book_showcase_settings_menu');
}

function rswp_book_showcase_settings_menu() {
    add_submenu_page(
        'edit.php?post_type=book', // Replace with your custom post type slug
        'RS WP Book Showcase Settings',
        'Book Showcase Settings',
        'manage_options',
        'rswpbs-settings',
        'rswp_book_showcase_settings_page'
    );

    add_submenu_page(
        'edit.php?post_type=book',
        'Archive Page',
        'Archive Page',
        'manage_options',
        'rswpbs-settings-book-archive',
        'rswp_book_showcase_books_archive_page'
    );

    add_submenu_page(
        'edit.php?post_type=book',
        'Single Page',
        'Single Page',
        'manage_options',
        'rswpbs-settings-book-single',
        'rswp_book_showcase_book_single_page'
    );

    add_submenu_page(
        'edit.php?post_type=book',
        'Translations',
        'Translations',
        'manage_options',
        'rswpbs-settings-static-text',
        'rswp_book_showcase_static_texts_page'
    );

    add_submenu_page(
        'edit.php?post_type=book',
        'Colors',
        'Colors',
        'manage_options',
        'rswpbs-settings-colors',
        'rswp_book_showcase_colors_page'
    );

    add_submenu_page(
        'edit.php?post_type=book',
        'Search Form',
        'Search Form',
        'manage_options',
        'rswpbs-settings-search-form',
        'rswp_book_showcase_search_form_page'
    );
}

function rswp_book_showcase_settings_tabs($active_tab) {
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="edit.php?post_type=book&page=rswpbs-settings" class="nav-tab <?php echo rswp_get_active_tab('general', $active_tab); ?>">General Settings</a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-book-archive" class="nav-tab <?php echo rswp_get_active_tab('books_archive', $active_tab); ?>">Books Archive Page</a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-book-single" class="nav-tab <?php echo rswp_get_active_tab('book_single', $active_tab); ?>">Book Single Page Settings</a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-static-text" class="nav-tab <?php echo rswp_get_active_tab('static_texts', $active_tab); ?>">Translations</a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-colors" class="nav-tab <?php echo rswp_get_active_tab('colors', $active_tab); ?>">Colors</a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-search-form" class="nav-tab <?php echo rswp_get_active_tab('search_form', $active_tab); ?>">Search Form Fields</a>
    </h2>
    <?php
}

function rswp_book_showcase_settings_page() {
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
    ?>
    <div class="wrap">
        <h1>RS WP Book Showcase Settings</h1>
        <?php rswp_book_showcase_settings_tabs($active_tab); ?>
        <div class="rswpbs-dummy-settings-page">
            <div class="rswpbs-container">
                <div class="settings-image-wrapper">
                    <div class="tab-content">
                        <?php if ($active_tab == 'general') : ?>
                            <img src="<?php echo RSWPBS_PLUGIN_URL . 'admin/assets/img/general-settings-min.jpg'; ?>" alt="General Settings">
                        <?php endif; ?>
                    </div>
                    <div class="upgrade-to-pro-btn">
                        <a target="_blank" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function rswp_get_active_tab($tab, $active_tab) {
    return $tab === $active_tab ? 'nav-tab-active' : '';
}

function rswp_book_showcase_books_archive_page() {
    ?>
    <div class="wrap">
        <h1>Books Archive Page</h1>
        <?php rswp_book_showcase_settings_tabs('books_archive'); ?>
        <div class="rswpbs-dummy-settings-page">
            <div class="rswpbs-container">
                <div class="settings-image-wrapper">
                    <img src="<?php echo RSWPBS_PLUGIN_URL . 'admin/assets/img/archive-page-settings-min.jpg'; ?>" alt="Books Archive Page">
                    <div class="upgrade-to-pro-btn">
                        <a target="_blank" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function rswp_book_showcase_book_single_page() {
    ?>
    <div class="wrap">
        <h1>Book Single Page Settings</h1>
        <?php rswp_book_showcase_settings_tabs('book_single'); ?>
        <div class="rswpbs-dummy-settings-page">
            <div class="rswpbs-container">
                <div class="settings-image-wrapper">
                    <img src="<?php echo RSWPBS_PLUGIN_URL . 'admin/assets/img/single-page-setting-min.jpg'; ?>" alt="Book Single Page Settings">
                    <div class="upgrade-to-pro-btn">
                        <a target="_blank" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function rswp_book_showcase_static_texts_page() {
    ?>
    <div class="wrap">
        <h1>Translations</h1>
        <?php rswp_book_showcase_settings_tabs('static_texts'); ?>
        <div class="rswpbs-dummy-settings-page">
            <div class="rswpbs-container">
                <div class="settings-image-wrapper">
                    <img src="<?php echo RSWPBS_PLUGIN_URL . 'admin/assets/img/change-static-text-min.jpg'; ?>" alt="Change Static Texts">
                    <div class="upgrade-to-pro-btn">
                        <a target="_blank" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function rswp_book_showcase_colors_page() {
    ?>
    <div class="wrap">
        <h1>Colors</h1>
        <?php rswp_book_showcase_settings_tabs('colors'); ?>
        <div class="rswpbs-dummy-settings-page">
            <div class="rswpbs-container">
                <div class="settings-image-wrapper">
                    <img src="<?php echo RSWPBS_PLUGIN_URL . 'admin/assets/img/colors-settings-min.jpg'; ?>" alt="Colors">
                    <div class="upgrade-to-pro-btn">
                        <a target="_blank" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function rswp_book_showcase_search_form_page() {
    ?>
    <div class="wrap">
        <h1>Search Form Fields</h1>
        <?php rswp_book_showcase_settings_tabs('search_form'); ?>
        <div class="rswpbs-dummy-settings-page">
            <div class="rswpbs-container">
                <div class="settings-image-wrapper">
                <img src="<?php echo RSWPBS_PLUGIN_URL . 'admin/assets/img/search-form-settings-min.jpg'; ?>" alt="Search Form Fields">
                <div class="upgrade-to-pro-btn">
                    <a target="_blank" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-books-showcase-wordpress-plugin/');?>"><?php esc_html_e( 'Upgrade To Pro', 'rswpbs' );?></a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
