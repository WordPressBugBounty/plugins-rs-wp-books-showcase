<?php
/**
 * Add custom meta fields for taxonomies.
 */

// ===================================================================
// META FIELDS FOR "book-author" TAXONOMY
// ===================================================================

// 1a. Display fields on the "book-author" add term screen.
add_action( 'book-author_add_form_fields', 'my_book_author_add_meta_fields', 10, 2 );
function my_book_author_add_meta_fields( $taxonomy ) { ?>
    <div class="form-field term-group">
        <label for="author_description"><?php _e( 'Author Description', 'rswpbs' ); ?></label>
        <?php
        wp_editor( '', 'author_description', array(
            'textarea_name' => 'author_description',
            'media_buttons' => true,
            'teeny'         => false,
        ) );
        ?>
    </div>
    <div class="form-field author-picture-form-field term-group">
        <label for="author_picture"><?php _e( 'Author Picture', 'rswpbs' ); ?></label>
        <div class="picture-uploader-container">
            <img class="picture-preview" src="" style="max-width:150px; display:none;" />
        </div>
        <input type="hidden" name="author_picture" class="picture-url-input" value="" />
        <p>
            <button class="button upload-picture-button"><?php _e( 'Select Image', 'rswpbs' ); ?></button>
            <button class="button remove-picture-button" style="display:none;"><?php _e( 'Remove Image', 'rswpbs' ); ?></button>
        </p>
        <p class="description"><?php _e( 'Select an image from the media library.', 'rswpbs' ); ?></p>
    </div>
    <div class="form-field author-social-profiles-field-group term-group">
        <label><?php _e( 'Author Social Profiles', 'rswpbs' ); ?></label>
        <table id="author_social_profiles">
            <thead>
                <tr>
                    <th></th>
                    <th><?php _e( 'Website Icon', 'rswpbs' ); ?></th>
                    <th><?php _e( 'Profile Link', 'rswpbs' ); ?></th>
                    <th><?php _e( 'Actions', 'rswpbs' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="drag-handle"><span class="dashicons dashicons-move"></span></td>
                    <td><input type="text" name="author_social_profiles[0][website_icon]" placeholder="fa-brands fa-facebook" /></td>
                    <td><input type="text" name="author_social_profiles[0][profile_link]" placeholder="facebook.com/username" /></td>
                    <td><button class="button remove-row"><?php _e( 'Remove', 'rswpbs' ); ?></button></td>
                </tr>
            </tbody>
        </table>
        <p><button id="add_social_profile_row" class="button"><?php _e( 'Add Row', 'rswpbs' ); ?></button></p>
        <p class="description"><?php _e( 'Manage social profiles. Drag rows by the handle to reorder.', 'rswpbs' ); ?></p>
    </div>
<?php
}

// 1b. Display fields on the "book-author" edit term screen.
add_action( 'book-author_edit_form_fields', 'rswpbs_book_author_edit_meta_fields', 10, 2 );
function rswpbs_book_author_edit_meta_fields( $term ) {
    $author_description     = get_term_meta( $term->term_id, 'rswpbs_book_author_description', true );
    $author_picture         = get_term_meta( $term->term_id, 'rswpbs_book_author_picture', true );
    $author_social_profiles = get_term_meta( $term->term_id, 'rswpbs_book_author_social_profiles', true );
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="author_description"><?php _e( 'Author Description', 'rswpbs' ); ?></label></th>
        <td>
            <?php
            wp_editor( $author_description, 'author_description', array( 'textarea_name' => 'author_description', 'media_buttons' => true, 'teeny' => false ) );
            ?>
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="author_picture"><?php _e( 'Author Picture', 'rswpbs' ); ?></label></th>
        <td>
            <div class="picture-uploader-container">
                <img class="picture-preview" src="<?php echo esc_url( $author_picture ); ?>" style="max-width:150px; <?php echo ( $author_picture ? '' : 'display:none;' ); ?>" />
            </div>
            <input type="hidden" name="author_picture" class="picture-url-input" value="<?php echo esc_attr( $author_picture ); ?>" />
            <p>
                <button class="button upload-picture-button"><?php _e( 'Select Image', 'rswpbs' ); ?></button>
                <button class="button remove-picture-button" style="display:<?php echo ( $author_picture ? 'inline-block' : 'none' ); ?>;"><?php _e( 'Remove Image', 'rswpbs' ); ?></button>
            </p>
            <p class="description"><?php _e( 'Select an image from the media library.', 'rswpbs' ); ?></p>
        </td>
    </tr>
    <tr class="form-field author-social-profiles-field-group term-group-wrap">
        <th scope="row"><?php _e( 'Author Social Profiles', 'rswpbs' ); ?></th>
        <td>
            <table id="author_social_profiles">
                <thead><tr><th></th><th><?php _e( 'Website Icon', 'rswpbs' ); ?></th><th><?php _e( 'Profile Link', 'rswpbs' ); ?></th><th><?php _e( 'Actions', 'rswpbs' ); ?></th></tr></thead>
                <tbody>
                    <?php
                    if ( empty( $author_social_profiles ) ) { ?>
                        <tr>
                            <td class="drag-handle"><span class="dashicons dashicons-move"></span></td>
                            <td><input type="text" name="author_social_profiles[0][website_icon]" placeholder="fa-brands fa-facebook" /></td>
                            <td><input type="text" name="author_social_profiles[0][profile_link]" placeholder="facebook.com/username" /></td>
                            <td><button class="button remove-row"><?php _e( 'Remove', 'rswpbs' ); ?></button></td>
                        </tr>
                    <?php } else { foreach ( $author_social_profiles as $index => $profile ) { ?>
                        <tr>
                            <td class="drag-handle"><span class="dashicons dashicons-move"></span></td>
                            <td><input type="text" name="author_social_profiles[<?php echo $index; ?>][website_icon]" value="<?php echo esc_attr( $profile['website_icon'] ); ?>" /></td>
                            <td><input type="text" name="author_social_profiles[<?php echo $index; ?>][profile_link]" value="<?php echo esc_attr( $profile['profile_link'] ); ?>" /></td>
                            <td><button class="button remove-row"><?php _e( 'Remove', 'rswpbs' ); ?></button></td>
                        </tr>
                    <?php } } ?>
                </tbody>
            </table>
            <p><button id="add_social_profile_row" class="button"><?php _e( 'Add Row', 'rswpbs' ); ?></button></p>
            <p class="description"><?php _e( 'Manage social profiles. Drag rows to reorder.', 'rswpbs' ); ?></p>
        </td>
    </tr>
<?php
}

// 1c. Save custom meta for "book-author".
add_action( 'created_book-author', 'save_book_author_custom_meta', 10, 2 );
add_action( 'edited_book-author',  'save_book_author_custom_meta', 10, 2 );
function save_book_author_custom_meta( $term_id ) {
    if ( isset( $_POST['author_description'] ) ) {
        update_term_meta( $term_id, 'rswpbs_book_author_description', wp_kses_post( $_POST['author_description'] ) );
    }
    if ( isset( $_POST['author_picture'] ) ) {
        update_term_meta( $term_id, 'rswpbs_book_author_picture', sanitize_text_field( $_POST['author_picture'] ) );
    }
    if ( isset( $_POST['author_social_profiles'] ) ) {
        $profiles = array();
        foreach ( $_POST['author_social_profiles'] as $profile ) {
            if ( ! empty( $profile['website_icon'] ) || ! empty( $profile['profile_link'] ) ) {
                $profiles[] = array(
                    'website_icon' => sanitize_text_field( $profile['website_icon'] ),
                    'profile_link' => esc_url_raw( $profile['profile_link'] ),
                );
            }
        }
        update_term_meta( $term_id, 'rswpbs_book_author_social_profiles', $profiles );
    }
}

// ===================================================================
// META FIELDS FOR "book-series" TAXONOMY (NEW CODE)
// ===================================================================

// 2a. Display field on the "book-series" add term screen.
add_action( 'book-series_add_form_fields', 'my_book_series_add_meta_fields', 10, 2 );
function my_book_series_add_meta_fields( $taxonomy ) {
    ?>
    <div class="form-field series-picture-form-field term-group">
        <label for="series_picture"><?php _e( 'Series Picture', 'rswpbs' ); ?></label>
        <div class="picture-uploader-container">
            <img class="picture-preview" src="" style="max-width:150px; display:none;" />
        </div>
        <input type="hidden" name="series_picture" class="picture-url-input" value="" />
        <p>
            <button class="button upload-picture-button"><?php _e( 'Select Image', 'rswpbs' ); ?></button>
            <button class="button remove-picture-button" style="display:none;"><?php _e( 'Remove Image', 'rswpbs' ); ?></button>
        </p>
        <p class="description"><?php _e( 'Select an image for the series from the media library.', 'rswpbs' ); ?></p>
    </div>
    <?php
}

// 2b. Display field on the "book-series" edit term screen.
add_action( 'book-series_edit_form_fields', 'rswpbs_book_series_edit_meta_fields', 10, 2 );
function rswpbs_book_series_edit_meta_fields( $term ) {
    $series_picture = get_term_meta( $term->term_id, 'rswpbs_book_series_picture', true );
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="series_picture"><?php _e( 'Series Picture', 'rswpbs' ); ?></label></th>
        <td>
            <div class="picture-uploader-container">
                <img class="picture-preview" src="<?php echo esc_url( $series_picture ); ?>" style="max-width:150px; <?php echo ( $series_picture ? '' : 'display:none;' ); ?>" />
            </div>
            <input type="hidden" name="series_picture" class="picture-url-input" value="<?php echo esc_attr( $series_picture ); ?>" />
            <p>
                <button class="button upload-picture-button"><?php _e( 'Select Image', 'rswpbs' ); ?></button>
                <button class="button remove-picture-button" style="display:<?php echo ( $series_picture ? 'inline-block' : 'none' ); ?>;"><?php _e( 'Remove Image', 'rswpbs' ); ?></button>
            </p>
            <p class="description"><?php _e( 'Select an image for the series from the media library.', 'rswpbs' ); ?></p>
        </td>
    </tr>
    <?php
}

// 2c. Save custom meta for "book-series".
add_action( 'created_book-series', 'save_book_series_custom_meta', 10, 2 );
add_action( 'edited_book-series',  'save_book_series_custom_meta', 10, 2 );
function save_book_series_custom_meta( $term_id ) {
    if ( isset( $_POST['series_picture'] ) ) {
        update_term_meta( $term_id, 'rswpbs_book_series_picture', sanitize_text_field( $_POST['series_picture'] ) );
    }
}


// ===================================================================
// ENQUEUE SCRIPTS (UPDATED)
// ===================================================================

// 4. Enqueue admin script.
add_action( 'admin_enqueue_scripts', 'rswpbs_taxonomy_meta_fields' );
function rswpbs_taxonomy_meta_fields( $hook ) {
    // Only load on the term edit/add screens for our specific taxonomies.
    if ( 'term.php' === $hook || 'edit-tags.php' === $hook ) {
        if ( isset( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], array( 'book-author', 'book-series' ) ) ) {
            wp_enqueue_media(); // Make sure media uploader scripts are loaded.
            wp_enqueue_script( 'rswpbs-taxonomy-meta-fields', RSWPBS_PLUGIN_URL . '/includes/taxonomy-meta-fields/taxonomy-meta-fields.js', array( 'jquery', 'jquery-ui-sortable' ), '1.1', true );
        }
    }
}