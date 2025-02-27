<?php
/**
 * Book Header Section
 * It contains Book Image and Book Main Description
 * Such as image, title, price, short description, buy button, multiple purcahse links
 */
function rswpbs_book_header_section(){
	$bookImageType = 'book_cover';
	$showSampleContent = 'false';
	$showMsl = 'false';
	$showAddToCart = 'false';
	$getBookImageType = get_option('rswpbs_single_image_type', 'book_cover');
	$showRatings = get_option('rswpbs_show_ratings_on_single_page', 1);
	$showRatings =  ($showRatings === NULL || $showRatings == true) ? 'true' : 'false';
	$showExcerpt = get_option('rswpbs_show_excerpt_on_single_page', 1);
	$showExcerpt =  ($showExcerpt === NULL || $showExcerpt == true) ? 'true' : 'false';
	$showPrice = get_option('rswpbs_show_price_on_single_page', 1);
	$showPrice =  ($showPrice === NULL || $showPrice == true) ? 'true' : 'false';
	$showBuyBtn = get_option('rswpbs_show_buy_button_on_single_page', 1);
	$showBuyBtn =  ($showBuyBtn === NULL || $showBuyBtn == true) ? 'true' : 'false';
	$showAuthor = get_option('rswpbs_show_author_on_single_page', 1);
	$showAuthor = ($showAuthor === NULL || $showAuthor == true) ? 'true' : 'false';

	if (class_exists('Rswpbs_Pro')) {
		$showSampleContent = get_option('rswpbs_show_sample_content_on_single_page', 0);
		$showSampleContent =  ($showSampleContent === NULL || $showSampleContent == true) ? 'true' : 'false';
		$showAddToCart = get_option('rswpbs_show_addtocart_on_single_page', 0);
		$showAddToCart =  ($showAddToCart === NULL || $showAddToCart == true) ? 'true' : 'false';
		$showMsl = get_option('rswpbs_show_msl_on_single_page', 0);
		$showMsl =  ($showMsl === NULL || $showMsl == true) ? 'true' : 'false';
		if ('book_mockup' == $getBookImageType) {
			$bookImageType = 'book_mockup';
		}
	}

	echo do_shortcode("[rswpbs_single_book show_sample_content=\"$showSampleContent\" show_title=\"true\" image_type=\"$bookImageType\" show_ratings=\"$showRatings\" show_author=\"$showAuthor\" show_buy_button=\"$showBuyBtn\" show_add_to_cart_btn=\"$showAddToCart\" show_description=\"$showExcerpt\" show_price=\"$showPrice\" show_msl=\"$showMsl\" msl_title_align=\"center\"]");
}