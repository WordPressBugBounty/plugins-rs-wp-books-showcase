<?php
function rswpbs_archive_page_title(){
    $value = get_option('rswpbs_books_archive_page_title', __( 'Books', 'rswpbs' ));
    return $value;
}
function rswpbs_archive_page_description(){
    $value = get_option('rswpbs_books_archive_page_description', __( 'Browse all books available in our collection.', 'rswpbs' ));
    return $value;
}
function rswpbs_archive_page_book_cover_position(){
    $value = get_option('rswpbs_book_cover_position');
    return $value;
}
function rswpbs_archive_page_books_per_page(){
    $value = get_option('rswpbs_books_per_page', 8);
    return $value;
}
function rswpbs_archive_page_books_per_row(){
    $value = get_option('rswpbs_books_per_row');
    return $value;
}
function rswpbs_show_archie_page_header() {
    $value = get_option('rswpbs_show_book_archive_page_header', 1);
    return ($value === NULL || $value == true) ? 'true' : 'false';
}
function rswpbs_show_archive_page_search(){
    $value = get_option('rswpbs_show_search_section', 1);
    return ($value === NULL || $value == true) ? 'true' : 'false';
}
function rswpbs_show_archive_page_sorting() {
    $value = get_option('rswpbs_show_sorting_section', 1);
    return ($value === NULL || $value == true) ? 'true' : 'false';
}
function rswpbs_show_archive_page_book_title() {
    $value = get_option('rswpbs_show_book_title', 1);
    return ($value === NULL || $value == true) ? 'true' : 'false';
}
function rswpbs_show_archive_page_book_author() {
    $value = get_option('rswpbs_show_author_name', 1);
    return ($value === NULL || $value == true) ? 'true' : 'false';
}
function rswpbs_show_archive_page_book_price() {
    $value = get_option('rswpbs_show_price', 1);
    return ($value === NULL || $value == true) ? 'true' : 'false';
}
function rswpbs_show_archive_page_book_description() {
    $value = get_option('rswpbs_show_description', 1);
    return ($value === NULL || $value == true) ? 'true' : 'false';
}
function rswpbs_show_archive_page_book_buy_button() {
    $value = get_option('rswpbs_show_buy_now_button', 1);
    return ($value === NULL || $value == true) ? 'true' : 'false';
}