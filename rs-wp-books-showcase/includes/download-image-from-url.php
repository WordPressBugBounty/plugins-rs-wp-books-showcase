<?php
/**
 * Download an image from a URL and attach it to a post.
 *
 * @param string $image_url The URL of the image to download.
 * @param int $post_id The ID of the post to which the image will be attached.
 * @return int|WP_Error The attachment ID on success, WP_Error on failure.
 */
function rswpbs_set_featured_image_from_url($post_id, $image_url) {
    // Set upload folder
    $upload_dir = wp_upload_dir();

    // Get image data
    $image_data = file_get_contents($image_url);

    // Generate a unique file name
    $filename = basename(wp_unique_filename($upload_dir['path'], sanitize_file_name(basename($image_url))));

    // Check folder permission and define file location
    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    // Create the image file on the server
    file_put_contents($file, $image_data);

    // Check image file type
    $wp_filetype = wp_check_filetype($filename, null);

    // Set attachment data
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name($filename),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Create the attachment
    $attach_id = wp_insert_attachment($attachment, $file, $post_id);

    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);

    // Assign metadata to attachment
    wp_update_attachment_metadata($attach_id, $attach_data);

    // Assign featured image to post
    set_post_thumbnail($post_id, $attach_id);

    // Return the attachment ID for further use if needed
    return $attach_id;
}
