<?php
/**
 * Default Loop Modify
 */
add_action('pre_get_posts', 'rswpbs_modify_book_query', 99);
function rswpbs_modify_book_query($query) {
    if ( !is_admin() && $query->is_main_query() && ( is_post_type_archive( 'book' ) || is_tax( array( 'book-author', 'book-category', 'book-series' ) ) ) ) {
    	$bookPerPage = 8;
    	if (class_exists('Rswpbs_Pro')) :
    		$bookPerPage = get_field('books_per_page', 'option');
			$bookPerPage = ($bookPerPage === NULL ) ? 8 : $bookPerPage;
		endif;
        $query->set('posts_per_page', $bookPerPage);
    }
    wp_reset_query();
}
