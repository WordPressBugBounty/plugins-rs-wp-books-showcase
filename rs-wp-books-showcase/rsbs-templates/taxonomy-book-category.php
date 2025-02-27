<?php
/**
 * Book Author Taxonomy Page
 */
get_header();
$bookCategoryArchivePageId = get_queried_object_id();
if (0 === $bookCategoryArchivePageId) {
	return;
}
$currentCatObj = get_term($bookCategoryArchivePageId);

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
		<div class="rswpbs-book-category-container-inner">
			<?php
			$showBookArchivePageHeader = true;
			if (class_exists('Rswpbs_Pro')) {
				$showBookArchivePageHeader = get_option('rswpbs_show_book_archive_page_header', 1);
			}
			$descriptions = $currentCatObj->description;
			if (true == $showBookArchivePageHeader) :
				$headingClass = '';
				if (empty($descriptions)) {
					$headingClass = ' pb-0 mb-0';
				}
			?>
			<div class="rswpbs-row">
				<div class="rswpbs-col-md-12">
					<div class="rswpthemes-book-showcase-page-title">
						<h1 class="rswpthemes-book-category-name<?php echo esc_attr($headingClass);?>"><?php echo esc_html($currentCatObj->name); ?></h1>
						<div class="cateogry-details">
							<p><?php echo wp_kses_post($currentCatObj->description); ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php
			endif;
			?>
			<div class="books-container-row">
				<?php
				echo do_shortcode("[rswpbs_book_gallery books_per_page=\"$bookPerPage\" books_per_row=\"$booksPerRow\" categories_include=\"$bookCategoryArchivePageId\" categories_exclude='false' authors_include='false' authors_exclude='false' exclude_books='false' order='DESC' orderby='date' show_pagination='true' show_author=\"$showBookAuthor\" show_title=\"$showBookTItle\" title_type='title' show_image='true' image_type='book_cover' image_position=\"$bookCoverPosition\" show_excerpt=\"$showBookDescription\" excerpt_type='excerpt' excerpt_limit='30' show_price=\"$showBookPrice\" show_buy_button=\"$showBuyNowBtn\" show_msl='false' msl_title_align='center' content_align='center' show_search_form=\"$showSearchSection\" show_sorting_form=\"$show_sorting_section\"]");
				?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
?>