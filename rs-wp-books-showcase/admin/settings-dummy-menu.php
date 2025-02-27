<?php
function rswp_get_active_tab($tab, $active_tab) {
    return $tab === $active_tab ? 'nav-tab-active' : '';
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
        <a href="edit.php?post_type=book&page=import-books-from-csv" class="nav-tab <?php echo rswp_get_active_tab('import_books_from_csv', $active_tab); ?>">Import Books From CSV</a>
        <a href="edit.php?post_type=book&page=import-books-from-json" class="nav-tab <?php echo rswp_get_active_tab('import_books_from_json', $active_tab); ?>">Import Books From JSON</a>
    </h2>
    <?php
}