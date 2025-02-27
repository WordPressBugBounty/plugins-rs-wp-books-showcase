<?php
/**
 * Removed Default WooCommerce Product Metabox From Book Post Type
 */
function rswpbs_remove_woocommerce_meta_box_for_books() {
    remove_meta_box('woocommerce-product-data', 'book', 'normal');
}
add_action('add_meta_boxes', 'rswpbs_remove_woocommerce_meta_box_for_books', 40);

// add_action('add_meta_boxes', 'rswpbs_product_downloadable_meta_box');
// function rswpbs_product_downloadable_meta_box() {
//     add_meta_box(
//         'custom-downloadable-meta-box',
//         __('Downloadable Data', 'woocommerce'),
//         'rswpbs_product_downloadable_meta_box_output',
//         'book',
//         'normal',
//         'high'
//     );
// }

function rswpbs_product_downloadable_meta_box_output($post) {
    // Retrieve existing values
    $is_downloadable = get_post_meta($post->ID, '_downloadable', true);
    $download_files = get_post_meta($post->ID, '_downloadable_files', true);
    $download_limit = get_post_meta($post->ID, '_download_limit', true);
    $download_expiry = get_post_meta($post->ID, '_download_expiry', true);

    // Nonce field for security
    wp_nonce_field('rswpbs_downloadable_meta_box', 'rswpbs_downloadable_meta_box_nonce');
    if (!class_exists('Rswpbs_Pro')) {
        ?>
        <div class="set_overlay_for_pro_field">
            <div class="pro_tag_text">
                <a target="_blank" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/'); ?>" class="pro_badge"><?php esc_html_e('Upgrade To Pro', 'rswpbs'); ?></a>
            </div>
        </div>
        <?php
    }elseif(class_exists('Rswpbs_Pro') && !function_exists('rswpthemes_cptwoointegration')) {
        ?>
        <div class="rswpbs-row mb-3">
            <div class="rswpbs-col-md-12">
                <div class="book-field-container">
                   <p class="m-0" style="font-size: 14px;"><?php esc_html_e('Please log in to https://rswpthemes.com/my-account/downloads/ to download the file \'rswpthemes-cpt-woo-integration-xxx.zip\'. Once downloaded, install and activate it to enable this field.', 'rswpbs'); ?></p>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="rswpbs-row mb-3">
    	<div class="rswpbs-col-md-12">
    		<div class="book-field-container">
		        <label for="_downloadable">
		            <input type="checkbox" id="_downloadable" name="_downloadable" value="yes" <?php echo class_exists('Rswpbs_Pro') ? checked($is_downloadable, 'yes') : 'checked'; ?> />
		            <?php esc_html_e('Downloadable', 'woocommerce'); ?>
		        </label>
    		</div>
    	</div>
    </div>
    <div class="book-field-container downloadable_files">
        <label><?php esc_html_e('Downloadable Files', 'woocommerce'); ?></label>
        <table class="widefat wc-metaboxes">
            <thead>
                <tr>
                    <th class="sort">&nbsp;</th>
                    <th><?php esc_html_e('Name', 'woocommerce'); ?></th>
                    <th colspan="2"><?php esc_html_e('File URL', 'woocommerce'); ?></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody class="ui-sortable">
                <?php
                if (!empty($download_files)) {
                    foreach ($download_files as $key => $file) {
                        ?>
                        <tr>
                            <td class="sort"><span class="dashicons dashicons-menu-alt3"></span></td>
                            <td class="file_name">
                                <input type="text" class="input_text w-100 regular-text" placeholder="<?php esc_attr_e('File name', 'woocommerce'); ?>" name="_wc_file_names[]" value="<?php echo esc_attr($file['name']); ?>" />
                                <input type="hidden" name="_wc_file_hashes[]" value="<?php echo esc_attr($key); ?>" />
                            </td>
                            <td class="file_url">
                                <input type="text" class="input_text w-100 regular-text" placeholder="http://" name="_wc_file_urls[]" value="<?php echo esc_url($file['file']); ?>" />
                            </td>
                            <td class="file_url_choose" width="1%">
                                <a href="#" class="button upload_file_button" data-choose="<?php esc_attr_e('Choose file', 'woocommerce'); ?>" data-update="<?php esc_attr_e('Insert file URL', 'woocommerce'); ?>"><?php esc_html_e('Choose file', 'woocommerce'); ?></a>
                            </td>
                            <td width="1%">
                                <a href="#" class="delete"><?php esc_html_e('Delete', 'woocommerce'); ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                	<th colspan="3">&nbsp;</th>
                    <th colspan="2">
                        <a href="#" class="button insert" data-row="
						<tr>
						    <td class=&quot;sort&quot;><span class=&quot;dashicons dashicons-menu-alt3&quot;></span></td>
						    <td class=&quot;file_name&quot;>
						        <input type=&quot;text&quot; class=&quot;input_text w-100 regular-text&quot; placeholder=&quot;File name&quot; name=&quot;_wc_file_names[]&quot; value=&quot;&quot; />
						        <input type=&quot;hidden&quot; name=&quot;_wc_file_hashes[]&quot; value=&quot;&quot; />
						    </td>
						    <td class=&quot;file_url&quot;>
						        <input type=&quot;text&quot; class=&quot;input_text w-100 regular-text&quot; placeholder=&quot;http://&quot; name=&quot;_wc_file_urls[]&quot; value=&quot;&quot; />
						    </td>
						    <td class=&quot;file_url_choose&quot; width=&quot;1%&quot;><a href=&quot;#&quot; class=&quot;button upload_file_button&quot; data-choose=&quot;Choose file&quot; data-update=&quot;Insert file URL&quot;>Choose file</a></td>
						    <td width=&quot;1%&quot;><a href=&quot;#&quot; class=&quot;delete&quot;>Delete</a></td>
						</tr>
						">Add File</a>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="rswpbs-row downloadable_files_limit_expiry">
    	<div class="rswpbs-col-lg-6 mt-4">
		    <div class="book-field-container">
		        <label for="_download_limit"><?php esc_html_e('Download Limit', 'woocommerce'); ?></label>
		        <input type="number" placeholder="Unlimited" id="_download_limit" name="_download_limit" class="w-100 regular-text" value="<?php echo esc_attr($download_limit); ?>" />
		    </div>
		</div>
		<div class="rswpbs-col-lg-6 mt-4">
		    <div class="book-field-container">
		        <label for="_download_expiry"><?php esc_html_e('Download Expiry (days)', 'woocommerce'); ?></label>
		        <input type="number" placeholder="Never" id="_download_expiry" name="_download_expiry" class="w-100 regular-text" value="<?php echo esc_attr($download_expiry); ?>" />
		    </div>
		</div>
	</div>
    <?php
}