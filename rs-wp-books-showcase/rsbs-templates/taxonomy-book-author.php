<?php
/**
 * Book Author Taxonomy Page
 */
get_header();
$archive_page_settings = get_option('book_layouts_settings');
$currentArchivePageId = get_queried_object_id();

$bookPerPage = 8;
$bookCoverPosition = 'top';
$booksPerRow = '4';
$showBookTItle = 'true';
$showBookAuthor = 'true';
$showBookPrice = 'true';
$showBookDescription = 'true';
$showBuyNowBtn = 'true';
if (class_exists('Rswpbs_Pro')) {
	$bookPerPage = get_field('books_per_page', 'option');
	$bookCoverPosition = get_field('book_cover_position', 'option');
	$booksPerRow = get_field('books_per_row', 'option');
    $showBookTItle = get_field('show_book_title', 'option');
    $showBookTItle = ($showBookTItle === NULL || $showBookTItle === true) ? 'true' : 'false';
    $showBookAuthor = get_field('show_author_name', 'option');
    $showBookAuthor = ($showBookAuthor === NULL || $showBookAuthor === true) ? 'true' : 'false';
    $showBookPrice = get_field('show_price', 'option');
    $showBookPrice = ($showBookPrice === NULL || $showBookPrice === true) ? 'true' : 'false';
    $showBookDescription = get_field('show_description', 'option');
    $showBookDescription = ($showBookDescription === NULL || $showBookDescription === true) ? 'true' : 'false';
    $showBuyNowBtn = get_field('show_buy_now_button', 'option');
    $showBuyNowBtn = ($showBuyNowBtn === NULL || $showBuyNowBtn === true) ? 'true' : 'false';
}

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
