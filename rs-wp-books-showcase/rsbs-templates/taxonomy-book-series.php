<?php
/**
 * Book Author Taxonomy Page
 */
get_header();
$bookSeriesArchivePageId = get_queried_object_id();
if (0 === $bookSeriesArchivePageId) {
	return;
}
$currentCatObj = get_term($bookSeriesArchivePageId);

$showSearchSection = 'true';
$bookPerPage = 8;
$show_sorting_section = 'true';
$bookCoverPosition = 'top';
$booksPerRow = '4';
$showBookTItle = 'true';
$showBookAuthor = 'true';
$showBookPrice = 'true';
$showBookDescription = 'true';
$showBuyNowBtn = 'true';
if (class_exists('Rswpbs_Pro')) {
	$showSearchSection = get_field('show_search_section', 'option');
	$showSearchSection =  ($showSearchSection === NULL || $showSearchSection === true) ? 'true' : 'false';
	$bookPerPage = get_field('books_per_page', 'option');
	$show_sorting_section = get_field('show_sorting_section', 'option');
	$show_sorting_section = ($show_sorting_section === NULL || $show_sorting_section === true) ? 'true' : 'false';
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

?>
<div class="rswpbs-archive-pages-wrapper">
	<div class="rswpbs-container">
		<div class="rswpbs-book-category-container-inner">
			<?php
			$showBookArchivePageHeader = true;
			if (class_exists('Rswpbs_Pro') && function_exists('get_field')) {
				$showBookArchivePageHeader = get_field('show_book_archive_page_header', 'option');
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
				echo do_shortcode("[rswpbs_book_gallery books_per_page=\"$bookPerPage\" books_per_row=\"$booksPerRow\" categories_include='false' series_include=\"$bookSeriesArchivePageId\" categories_exclude='false' authors_include='false' authors_exclude='false' exclude_books='false' order='DESC' orderby='date' show_pagination='true' show_author=\"$showBookAuthor\" show_title=\"$showBookTItle\" title_type='title' show_image='true' image_type='book_cover' image_position=\"$bookCoverPosition\" show_excerpt=\"$showBookDescription\" excerpt_type='excerpt' excerpt_limit='30' show_price=\"$showBookPrice\" show_buy_button=\"$showBuyNowBtn\" show_msl='false' msl_title_align='center' content_align='center' show_search_form=\"$showSearchSection\" show_sorting_form=\"$show_sorting_section\"]");
				?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
?>