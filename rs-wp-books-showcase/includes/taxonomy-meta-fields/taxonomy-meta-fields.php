<?php
/**
 * Add custom meta fields for the “book-author” taxonomy.
 */

// 1. Display fields on the add term screen.
add_action( 'book-author_add_form_fields', 'my_book_author_add_meta_fields', 10, 2 );
function my_book_author_add_meta_fields( $taxonomy ) { ?>
    <div class="form-field term-group">
        <label for="author_description"><?php _e( 'Author Description', 'rswpbs' ); ?></label>
        <?php
        // Note: No initial content on add screen.
        wp_editor( '', 'author_description', array(
            'textarea_name' => 'author_description',
            'media_buttons' => true,
            'teeny'         => false,
        ) );
        ?>
    </div>
    <div class="form-field author-picture-form-field term-group">
        <label for="author_picture"><?php _e( 'Author Picture', 'rswpbs' ); ?></label>
        <div id="author_picture_container">
            <img id="author_picture_preview" src="" style="max-width:150px; display:none;" />
        </div>
        <input type="hidden" name="author_picture" id="author_picture" value="" />
        <p>
            <button id="upload_author_picture_button" class="button"><?php _e( 'Select Image', 'rswpbs' ); ?></button>
            <button id="remove_author_picture_button" class="button" style="display:none;"><?php _e( 'Remove Image', 'rswpbs' ); ?></button>
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
                <?php
                if ( ! isset( $author_social_profiles ) || empty( $author_social_profiles ) ) { ?>
                    <tr>
                        <td class="drag-handle"><span class="dashicons dashicons-move"></span></td>
                        <td>
                            <input type="text" name="author_social_profiles[0][website_icon]" placeholder="fa-brands fa-facebook" />
                        </td>
                        <td>
                            <input type="text" name="author_social_profiles[0][profile_link]" placeholder="facebook.com/username" />
                        </td>
                        <td>
                            <button class="button remove-row"><?php _e( 'Remove', 'rswpbs' ); ?></button>
                        </td>
                    </tr>
                <?php } else {
                    foreach ( $author_social_profiles as $index => $profile ) { ?>
                        <tr>
                            <td class="drag-handle"><span class="dashicons dashicons-move"></span></td>
                            <td>
                                <input type="text" name="author_social_profiles[<?php echo $index; ?>][website_icon]" value="<?php echo esc_attr( $profile['website_icon'] ); ?>" />
                            </td>
                            <td>
                                <input type="text" name="author_social_profiles[<?php echo $index; ?>][profile_link]" value="<?php echo esc_attr( $profile['profile_link'] ); ?>" />
                            </td>
                            <td>
                                <button class="button remove-row"><?php _e( 'Remove', 'rswpbs' ); ?></button>
                            </td>
                        </tr>
                    <?php }
                }
                ?>
            </tbody>
        </table>
        <p>
            <button id="add_social_profile_row" class="button"><?php _e( 'Add Row', 'rswpbs' ); ?></button>
        </p>
        <p class="description"><?php _e( 'Manage social profiles. Drag rows by the handle to reorder.', 'rswpbs' ); ?></p>
    </div>
<?php
}

// 2. Display fields on the edit term screen.
add_action( 'book-author_edit_form_fields', 'rswpbs_book_author_edit_meta_fields', 10, 2 );
function rswpbs_book_author_edit_meta_fields( $term ) {
    // Retrieve existing values.
    $author_description     = get_term_meta( $term->term_id, 'rswpbs_book_author_description', true );
    $author_picture         = get_term_meta( $term->term_id, 'rswpbs_book_author_picture', true );
    $author_social_profiles = get_term_meta( $term->term_id, 'rswpbs_book_author_social_profiles', true );

    if ( ! is_array( $author_social_profiles ) ) {
        $author_social_profiles = array();
    }
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="author_description"><?php _e( 'Author Description', 'rswpbs' ); ?></label></th>
        <td>
            <?php
            wp_editor( $author_description, 'author_description', array(
                'textarea_name' => 'author_description',
                'media_buttons' => true,
                'teeny'         => false,
            ) );
            ?>
        </td>
    </tr>
    <tr class="form-field  term-group-wrap">
        <th scope="row"><label for="author_picture"><?php _e( 'Author Picture', 'rswpbs' ); ?></label></th>
        <td>
            <div id="author_picture_container">
                <img id="author_picture_preview" src="<?php echo esc_url( $author_picture ); ?>" style="max-width:150px; <?php echo ( $author_picture ? '' : 'display:none;' ); ?>" />
            </div>
            <input type="hidden" name="author_picture" id="author_picture" value="<?php echo esc_attr( $author_picture ); ?>" />
            <p>
                <button id="upload_author_picture_button" class="button"><?php _e( 'Select Image', 'rswpbs' ); ?></button>
                <button id="remove_author_picture_button" class="button" style="display:<?php echo ( $author_picture ? 'inline-block' : 'none' ); ?>"><?php _e( 'Remove Image', 'rswpbs' ); ?></button>
            </p>
            <p class="description"><?php _e( 'Select an image from the media library.', 'rswpbs' ); ?></p>
        </td>
    </tr>
    <tr class="form-field author-social-profiles-field-group term-group-wrap">
        <th scope="row"><?php _e( 'Author Social Profiles', 'rswpbs' ); ?></th>
        <td>
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
                    <?php
                    if ( ! isset( $author_social_profiles ) || empty( $author_social_profiles ) ) { ?>
                        <tr>
                            <td class="drag-handle"><span class="dashicons dashicons-move"></span></td>
                            <td>
                                <input type="text" name="author_social_profiles[0][website_icon]" placeholder="fa-brands fa-facebook" />
                            </td>
                            <td>
                                <input type="text" name="author_social_profiles[0][profile_link]" placeholder="facebook.com/username" />
                            </td>
                            <td>
                                <button class="button remove-row"><?php _e( 'Remove', 'rswpbs' ); ?></button>
                            </td>
                        </tr>
                    <?php } else {
                        foreach ( $author_social_profiles as $index => $profile ) { ?>
                            <tr>
                                <td class="drag-handle"><span class="dashicons dashicons-move"></span></td>
                                <td>
                                    <input type="text" name="author_social_profiles[<?php echo $index; ?>][website_icon]" value="<?php echo esc_attr( $profile['website_icon'] ); ?>" />
                                </td>
                                <td>
                                    <input type="text" name="author_social_profiles[<?php echo $index; ?>][profile_link]" value="<?php echo esc_attr( $profile['profile_link'] ); ?>" />
                                </td>
                                <td>
                                    <button class="button remove-row"><?php _e( 'Remove', 'rswpbs' ); ?></button>
                                </td>
                            </tr>
                        <?php }
                    }
                    ?>
                </tbody>
            </table>
            <p>
                <button id="add_social_profile_row" class="button"><?php _e( 'Add Row', 'rswpbs' ); ?></button>
            </p>
            <p class="description"><?php _e( 'Manage social profiles. Drag rows to reorder.', 'rswpbs' ); ?></p>
        </td>
    </tr>
<?php
}

// 3. Save the custom meta when a term is created or edited.
add_action( 'created_book-author', 'save_book_author_custom_meta', 10, 2 );
add_action( 'edited_book-author',  'save_book_author_custom_meta', 10, 2 );
function save_book_author_custom_meta( $term_id, $tt_id ) {
    // $author_picture = get_field();
    if ( isset( $_POST['author_description'] ) ) {
        update_term_meta( $term_id, 'rswpbs_book_author_description', wp_kses_post( $_POST['author_description'] ) );
    }
    if ( isset( $_POST['author_picture'] ) ) {
        update_term_meta( $term_id, 'rswpbs_book_author_picture', sanitize_text_field( $_POST['author_picture'] ) );
    }

    if ( isset( $_POST['author_social_profiles'] ) ) {
        $profiles = array();
        foreach ( $_POST['author_social_profiles'] as $profile ) {
            // Only save if at least one field is filled.
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

// 4. Enqueue admin script to handle repeater row add/remove functionality.
add_action( 'admin_enqueue_scripts', 'rswpbs_taxonomy_meta_fields' );
function rswpbs_taxonomy_meta_fields( $hook ) {
    if ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] === 'book-author' ) {
        wp_enqueue_script( 'rswpbs-taxonomy-meta-fields', RSWPBS_PLUGIN_URL . '/includes/taxonomy-meta-fields/taxonomy-meta-fields.js', array( 'jquery' ), '1.0', true );
    }
}


// add_action('acf/save_post', 'sync_acf_to_native_for_book_author', 99);
// function sync_acf_to_native_for_book_author($post_id) {
//     // error_log("$post_id");
//     // Check if this is a taxonomy term. For taxonomy terms, ACF sets $post_id as "term_{term_id}"
//     if (strpos($post_id, 'term_') === 0) {
//         $term_id = str_replace('term_', '', $post_id);
//         $term = get_term($term_id);
//         // error_log("Array data: " . print_r($term, true));
//         // Ensure we're only syncing for the "book-author" taxonomy.
//         if ($term && $term->taxonomy === 'book-author') {
//             $context = 'term_' . $term_id; // Use this context to get ACF values.
//             // Retrieve ACF field values.
//             $author_description = get_field('author_description', $context);
//             $author_picture     = get_field('author_picture', $context);
//             $author_social_profiles = get_field('author_social_profiles', $context);

//             // If the image field returns an array, extract the URL.
//             if (is_array($author_picture) && isset($author_picture['url'])) {
//                 $author_picture = $author_picture['url'];
//             }
//             error_log("Author Picture data: $author_picture ");
//             // Update your native term meta fields so they always mirror the ACF values.
//             update_term_meta($term_id, 'rswpbs_book_author_description', wp_kses_post($author_description));
//             update_term_meta($term_id, 'rswpbs_book_author_picture', esc_url_raw($author_picture));
//             update_term_meta($term_id, 'rswpbs_book_author_social_profiles', $author_social_profiles);
//         }
//     }
// }
