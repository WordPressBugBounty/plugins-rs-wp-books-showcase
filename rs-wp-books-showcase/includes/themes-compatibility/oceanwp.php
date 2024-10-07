<?php
/**
 * REMOVE OCEAN WP PAGE HEADER FROM RS WP BOOK SHOWCASE PAGES
 */

function remove_ocean_page_header_on_rswpbs_pages() {
    if ( is_rswpbs_page() ) {
        remove_action( 'ocean_page_header', 'oceanwp_page_header_template' );
    }
}
add_action( 'wp', 'remove_ocean_page_header_on_rswpbs_pages' );
