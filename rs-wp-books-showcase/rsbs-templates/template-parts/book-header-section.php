<?php
/**
 * Book Header Section
 * It contains Book Image and Book Main Description
 * Such as image, title, price, short description, buy button, multiple purcahse links
 */
function rswpbs_book_header_section(){
	$bookImageType = 'book_cover';
	$showSampleContent = 'true';
	$showRatings = 'true';
	$showExcerpt = 'true';
	$showPrice = 'true';
	$showBuyBtn = 'true';
	$showMsl = 'true';
	$showAuthor = 'true';
	if (class_exists('Rswpbs_Pro')) {
		$getBookImageType = get_field('single_image_type', 'option');
		$showSampleContent = get_field('show_sample_content_on_single_page', 'option');
		$showSampleContent =  ($showSampleContent === NULL || $showSampleContent === true) ? 'true' : 'false';
		$showRatings = get_field('show_ratings_on_single_page', 'option');
		$showRatings =  ($showRatings === NULL || $showRatings === true) ? 'true' : 'false';
		$showExcerpt = get_field('show_excerpt_on_single_page', 'option');
		$showExcerpt =  ($showExcerpt === NULL || $showExcerpt === true) ? 'true' : 'false';
		$showPrice = get_field('show_price_on_single_page', 'option');
		$showPrice =  ($showPrice === NULL || $showPrice === true) ? 'true' : 'false';
		$showBuyBtn = get_field('show_buy_button_on_single_page', 'option');
		$showBuyBtn =  ($showBuyBtn === NULL || $showBuyBtn === true) ? 'true' : 'false';
		$showMsl = get_field('show_msl_on_single_page', 'option');
		$showMsl =  ($showMsl === NULL || $showMsl === true) ? 'true' : 'false';
		$showAuthor = get_field('show_author_on_single_page', 'option');
		$showAuthor = ($showAuthor === NULL || $showAuthor === true) ? 'true' : 'false';
		if ('book_mockup' == $getBookImageType) {
			$bookImageType = 'book_mockup';
		}
	}
	echo do_shortcode("[rswpbs_single_book show_sampl_content=\"$showSampleContent\" show_title=\"true\" image_type=\"$bookImageType\" show_ratings=\"$showRatings\" show_author=\"$showAuthor\" show_buy_button=\"$showBuyBtn\" show_description=\"$showExcerpt\" show_price=\"$showPrice\" show_msl=\"$showMsl\" msl_title_align=\"center\"]");
}