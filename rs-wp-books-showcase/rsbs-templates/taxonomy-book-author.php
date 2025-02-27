<?php
/**
 * Book Author Taxonomy Page
 */
get_header();
$archive_page_settings = get_option('book_layouts_settings');
$currentArchivePageId = get_queried_object_id();

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

$currentAuthorObj = get_term($currentArchivePageId);
$authorName = $currentAuthorObj->name;
?>
<div class="rswpbs-archive-pages-wrapper">
	<div class="rswpbs-container">
		<div class="rswpbs-book-author-container-inner">
			<?php
			do_action( 'rswpbs_author_taxonomy_page_header' ); ?>
			<div class="rswpbs-book-author-page-book-container-section-title">
				<?php
				$title = sprintf('<h2 class="book-container-section-title">%s %s</h2>', rswpbs_static_text_books_by(), $authorName);
				echo wp_kses_post($title);
				?>
			</div>
			<?php
			echo do_shortcode("[rswpbs_book_gallery books_per_page=\"$bookPerPage\" books_per_row=\"$booksPerRow\" categories_include='false' categories_exclude='false' authors_include=\"$currentArchivePageId\" authors_exclude='false' exclude_books='false' order='DESC' orderby='date' show_pagination='true' show_author=\"$showBookAuthor\" show_title=\"$showBookTItle\" title_type='title' show_image='true' image_type='book_cover' image_position=\"$bookCoverPosition\" show_excerpt=\"$showBookDescription\" excerpt_type='excerpt' excerpt_limit='30' show_price=\"$showBookPrice\" show_buy_button=\"$showBuyNowBtn\" show_msl='false' msl_title_align='center' content_align='center' show_search_form='false' show_sorting_form='false']");
			?>
		</div>
	</div>
</div>
<?php

get_footer();
