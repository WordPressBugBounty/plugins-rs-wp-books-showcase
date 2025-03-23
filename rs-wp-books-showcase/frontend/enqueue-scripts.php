<?php
/**
 * Book Showcase Enqueue Assets
 */
add_action('enqueue_block_editor_assets', 'rswpbs_assets');
add_action('wp_enqueue_scripts', 'rswpbs_assets');
function rswpbs_assets(){
	global $post;
 	$getRswpThemesSlug = get_stylesheet();
	$enable_grid = true;
	/**
	 * Conditional Check For Slider Assets
	 */
	$writersPortfolioSections = get_theme_mod( 'frontpage_sections', array( 'books-slider', 'about-section', 'books-gallery' ) );
	$appAlpSections = get_theme_mod( 'author_landing_page_sections', array( 'books-slider', 'about-section', 'books-gallery' ) );

	$showSlider = get_theme_mod('books_slider_on_off', false); //Writers Portfolio Theme Option
	$get_blog_top_content = get_theme_mod('blog_top_book_section_type', 'books_carousel');

	$enableSliderAssets = false;
	if( 'writers-portfolio' == $getRswpThemesSlug && is_page_template( 'author-landing-page.php' ) && in_array('books-slider', $writersPortfolioSections) ) {
		$enableSliderAssets = true;
	}elseif ( 'author-portfolio' == $getRswpThemesSlug && is_home() && true == $showSlider ) {
		$enableSliderAssets = true;
	}elseif ( 'author-portfolio-pro' == $getRswpThemesSlug && is_home() && 'books_carousel' == $get_blog_top_content && true == $showSlider ) {
		$enableSliderAssets = true;
	}elseif ('author-portfolio-pro' == $getRswpThemesSlug && is_page_template( 'author-landing-page.php' ) && in_array('books-slider', $appAlpSections)) {
		$enableSliderAssets = true;
	}elseif ('author-portfolio-pro-child' == $getRswpThemesSlug && is_page_template( 'author-landing-page.php' ) && in_array('books-slider', $appAlpSections)) {
		$enableSliderAssets = true;
	}elseif( (is_page() || is_single()) && has_shortcode( $post->post_content , 'rswpbs_book_slider' ) ) {
		$enableSliderAssets = true;
	}elseif (is_singular('shortcode')) {
		$enableSliderAssets = true;
	}elseif (is_singular('book')) {
		$enableSliderAssets = true;
	}

 	/**
 	 * Enqueue Fontawesome CSS
 	 */
	wp_enqueue_style( 'fontawesome-v6', RSWPBS_PLUGIN_URL . 'frontend/assets/css/fontawesome.css' );

	/**
	 * Enqueue Selectize CSS
	 */
	wp_enqueue_style( 'rswpbs-selectize', RSWPBS_PLUGIN_URL . 'frontend/assets/css/selectize.css' );

	/**
	 * Enqueue rswpbs-Bootstrap GRID css
	 */
	if (true === $enable_grid) {
		wp_enqueue_style( 'rswpbs-grid', RSWPBS_PLUGIN_URL . 'includes/assets/css/rswpbs-grid.css' );
	}

	/**
	 * Enqueue Slick Slider CSS
	 */
	wp_register_style( 'slick', RSWPBS_PLUGIN_URL . 'frontend/assets/css/slick.css' );

	if ( true === $enableSliderAssets && !wp_style_is('slick' ) ) {
		wp_enqueue_style('slick');
	}

	/**
	 * Enqueue RS WP BOOK SHOWCASE Main CSS File
	 */
	wp_enqueue_style( 'rswpbs-style', RSWPBS_PLUGIN_URL . 'frontend/assets/css/style.css' );

	/**
	 * Enqueue Masonry js
	 */
	if (!wp_script_is( 'masonry' )) {
		wp_enqueue_script('masonry');
	}

	/**
	 * Enqueue Slick Slider JS
	 */
	wp_register_script('slick', RSWPBS_PLUGIN_URL . 'frontend/assets/js/slick.js', array('jquery'), '2.3.4', true);

	if ( true === $enableSliderAssets && !wp_script_is( 'slick' ) ) {
		wp_enqueue_script('slick');
	}
	if (class_exists('Rswpbs_Pro') && function_exists('rswpthemes_cptwoointegration')) {
		wp_enqueue_script('rswpbs-ajax-add-to-cart', RSWPBS_PLUGIN_URL . 'frontend/assets/js/ajax-add-to-cart.js', array('jquery'), null, true);
	}
	/**
	 * Enqueue Selectize JS
	 */
	wp_enqueue_script('rswpbs-selectize', RSWPBS_PLUGIN_URL . 'frontend/assets/js/selectize.js', array('jquery'), '1.0', true);
	/**
	 * Enqueue RSWPBS Slider Initiate JS
	 */
	wp_register_script('rswpbs-slider', RSWPBS_PLUGIN_URL . 'frontend/assets/js/slider.js', array('jquery'), '1.0', true);
	if ( true === $enableSliderAssets && !wp_script_is( 'rswpbs-slider' ) ) {
		wp_enqueue_script('rswpbs-slider');
	}

	/**
	 * Enqueue RSWPBS Custom Scripts
	 */
	wp_enqueue_script('rswpbs-custom-scripts', RSWPBS_PLUGIN_URL . 'frontend/assets/js/custom.js', array('jquery'), '1.0', true);


	/**
	 * Review Slider Speed Optimization
	 */
	wp_register_script('rswpbs-review-slider-script', RSWPBS_PLUGIN_URL . 'frontend/assets/js/review-slider.js', array('jquery'), '1.0', true);
	wp_register_style( 'rswpbs-review-slider-style', RSWPBS_PLUGIN_URL . 'frontend/assets/css/review-slider.css' );
	if( (is_page() || is_single()) && has_shortcode( $post->post_content , 'rswpbs_reviews' ) ){
		wp_enqueue_script('rswpbs-review-slider-script');
		// wp_enqueue_style('rswpbs-review-slider-style');
	}
	/**
	 * Enqueue RSWPBS Review Form JS
	 */
	if (!is_admin() || !wp_doing_ajax() ) {
		if ('book' === get_post_type() && is_singular('book')) {
			wp_enqueue_script('rswpbs-review-form', RSWPBS_PLUGIN_URL . 'frontend/assets/js/review-form.js', array('jquery'), '1.0', true);
			wp_localize_script( 'rswpbs-review-form', 'rswpbs', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
			) );
		}
	}
}



function handle_ajax_add_to_cart() {
    // Check if the action and product ID are set
    if (!isset($_POST['add-to-cart']) || !isset($_POST['action']) || $_POST['action'] !== 'handle_ajax_add_to_cart') {
        wp_send_json_error(array('message' => 'Invalid product or missing action.'));
    }

    // Sanitize and get product ID
    $product_id = intval($_POST['add-to-cart']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Try to add product to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity);

    if ($added) {
        wp_send_json_success(array('message' => 'Product added to cart.'));
    } else {
        wp_send_json_error(array('message' => 'Unable to add product to cart.'));
    }
}
add_action('wp_ajax_nopriv_handle_ajax_add_to_cart', 'handle_ajax_add_to_cart');
add_action('wp_ajax_handle_ajax_add_to_cart', 'handle_ajax_add_to_cart');

add_action('wp_footer', 'rswpbs_book_added_to_cart_notice');

function rswpbs_book_added_to_cart_notice() {
    ?>
    <div id="cart-popup-message" class="cart-popup-message" style="display: none;">
	    Product successfully added to the cart!
	</div>
    <?php
}

