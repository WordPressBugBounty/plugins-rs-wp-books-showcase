<?php
function rswp_get_active_tab($tab, $active_tab) {
    return $tab === $active_tab ? 'nav-tab-active' : '';
}
function rswpbs_settings_tabs($active_tab) {
    ?>
    <h2 class="nav-tab-wrapper" id="rswpbs-nav-wrapper">
        <a href="edit.php?post_type=book&page=rswpbs-settings" class="nav-tab <?php echo rswp_get_active_tab('general', $active_tab); ?>"><?php esc_html_e( 'General Settings', 'rswpbs' ); ?></a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-book-archive" class="nav-tab <?php echo rswp_get_active_tab('books_archive', $active_tab); ?>"><?php esc_html_e( 'Books Archive Page', 'rswpbs' ); ?></a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-book-single" class="nav-tab <?php echo rswp_get_active_tab('book_single', $active_tab); ?>"><?php esc_html_e( 'Book Single Page Settings', 'rswpbs' ); ?></a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-static-text" class="nav-tab <?php echo rswp_get_active_tab('static_texts', $active_tab); ?>"><?php esc_html_e( 'Translations', 'rswpbs' ); ?></a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-colors" class="nav-tab <?php echo rswp_get_active_tab('colors', $active_tab); ?>"><?php esc_html_e( 'Colors', 'rswpbs' ); ?></a>
        <a href="edit.php?post_type=book&page=rswpbs-settings-search-form" class="nav-tab <?php echo rswp_get_active_tab('search_form', $active_tab); ?>"><?php esc_html_e( 'Search Form Fields', 'rswpbs' ); ?></a>
        <a href="edit.php?post_type=book&page=import-books-from-csv" class="nav-tab <?php echo rswp_get_active_tab('import_books_from_csv', $active_tab); ?>"><?php esc_html_e( 'Import Books From CSV', 'rswpbs' ); ?></a>
        <a href="edit.php?post_type=book&page=import-books-from-json" class="nav-tab <?php echo rswp_get_active_tab('import_books_from_json', $active_tab); ?>"><?php esc_html_e( 'Import Books From Amazon', 'rswpbs' ); ?></a>
    </h2>
    <?php
}