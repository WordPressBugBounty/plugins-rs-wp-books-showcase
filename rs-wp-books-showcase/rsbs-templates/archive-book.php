<?php
/**
 * Archive Template For Book Post Type
 */
get_header();

$showSearchSection = rswpbs_show_archive_page_search();
$bookPerPage = rswpbs_archive_page_books_per_page();
$show_sorting_section = rswpbs_show_archive_page_sorting();
$bookCoverPosition = rswpbs_archive_page_book_cover_position();
$booksPerRow = rswpbs_archive_page_books_per_row();
$showBookTItle = rswpbs_show_archive_page_book_title();
$showBookAuthor = rswpbs_show_archive_page_book_author();
$showBookPrice = rswpbs_show_archive_page_book_price();
$showBookDescription = rswpbs_show_archive_page_book_description();
$showBuyNowBtn = rswpbs_show_archive_page_book_buy_button();

?>
<div class="rswpbs-archive-pages-wrapper">
	<div class="rswpbs-container">
		<?php
		do_action('rswpbs_archive_before_book_loop');
		echo do_shortcode("[rswpbs_book_gallery books_per_page=\"$bookPerPage\" books_per_row=\"$booksPerRow\" categories_include='false' categories_exclude='false' authors_include='false' authors_exclude='false' exclude_books='false' order='DESC' orderby='date' show_pagination='true' show_author=\"$showBookAuthor\" show_title=\"$showBookTItle\" title_type='title' show_image='true' image_type='book_cover' image_position=\"$bookCoverPosition\" show_excerpt=\"$showBookDescription\" excerpt_type='excerpt' excerpt_limit='30' show_price=\"$showBookPrice\" show_buy_button=\"$showBuyNowBtn\" show_msl='false' msl_title_align='center' content_align='center' show_search_form=\"$showSearchSection\" show_sorting_form=\"$show_sorting_section\"]");
		do_action('rswpbs_archive_after_book_loop');
		?>
	</div>
</div>
<?php
get_footer();
