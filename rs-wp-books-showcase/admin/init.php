<?php
/**
 * Admin Functions
 */

add_action('admin_enqueue_scripts', 'rswpbs_admin_assets');
function rswpbs_admin_assets(){
	$getCurrentScreen = get_current_screen();
	if (is_admin()) {
		if ('book' === $getCurrentScreen->id || 'book_reviews' === $getCurrentScreen->id || 'book_page_rswpbs-tutorial' === $getCurrentScreen->id || 'book_page_rswpbs-page-settings' === $getCurrentScreen->id) {
			wp_enqueue_style( 'rswpbs-grid', RSWPBS_PLUGIN_URL . 'includes/assets/css/rswpbs-grid.css' );
		}
		$wooSupport = false;
		if (class_exists('Rswpbs_Pro')) {
			$wooSupport = get_option( 'rswpbs_enable_woo_features_for_books', 0 );
		}
		if (function_exists('rswpthemes_cptwoointegration') && true == $wooSupport && 'book' === $getCurrentScreen->id) {
			wp_enqueue_script('rswpbs-downloadable-cmb', RSWPBS_PLUGIN_URL . 'admin/assets/js/downloadable-files-cmb.js', array('jquery'), '1.0', true);
		}
		if ('book-author' === $getCurrentScreen->taxonomy || 'book' === $getCurrentScreen->id || 'book_reviews' === $getCurrentScreen->id || 'book_page_book-settings' === $getCurrentScreen->id || 'book_page_rswpbs-tutorial' === $getCurrentScreen->id) {
			wp_enqueue_style( 'rswpbs-admin-meta-style', RSWPBS_PLUGIN_URL . 'admin/assets/css/style-for-meta-box.css' );
			wp_enqueue_script('rs-wp-book-showcase-admin-custom', RSWPBS_PLUGIN_URL . 'admin/assets/js/admin-custom.js', array('jquery'), '1.0', true);
			wp_enqueue_media();
		}
	}
}

add_action('admin_enqueue_scripts', 'rswpbs_menu_style');
function rswpbs_menu_style(){
	wp_enqueue_style( 'rswpbs-custom-menu-style', RSWPBS_PLUGIN_URL . 'admin/assets/css/admin-style.css' );
	$data = "
	.taxonomy-book-author .term-description-wrap {
	    display: none;
	}
	";
	$enableWysiwygForDesc = false;
    if (class_exists('Rswpbs_Pro')) {
        $getWysiwygPermission = get_option( 'rswpbs_enable_editor_for_author_description', 0 );
        if (null == $getWysiwygPermission || false == $getWysiwygPermission) {
            $enableWysiwygForDesc = false;
        }elseif (true == $getWysiwygPermission) {
            $enableWysiwygForDesc = true;
        }
    }
    if (true === $enableWysiwygForDesc) {
		wp_add_inline_style( 'rswpbs-custom-menu-style', $data );
    }
}
