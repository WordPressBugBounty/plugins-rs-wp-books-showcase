<?php
/**
 * Download an image from a URL and attach it as a featured image, renaming the file and setting alt text based on the post title.
 *
 * @param int $post_id The ID of the post to which the image will be attached.
 * @param string $image_url The URL of the image to download.
 * @return int|WP_Error The attachment ID on success, WP_Error on failure.
 */
function rswpbs_set_featured_image_from_url($post_id, $image_url) {
    // Get the post title to use for filename and alt text
    $post_title = get_the_title($post_id);
    if (empty($post_title)) {
        $post_title = 'untitled-post-' . $post_id; // Fallback if no title exists
    }

    // Sanitize the post title for use as a filename
    $sanitized_title = sanitize_file_name($post_title);

    // Set upload folder
    $upload_dir = wp_upload_dir();

    // Get image data
    $image_data = file_get_contents($image_url);
    if ($image_data === false) {
        return new WP_Error('download_failed', 'Failed to download image from URL.');
    }

    // Get the file extension from the URL
    $wp_filetype = wp_check_filetype(basename($image_url), null);
    $extension = !empty($wp_filetype['ext']) ? '.' . $wp_filetype['ext'] : '.jpg'; // Default to .jpg if unknown

    // Generate a unique filename based on the post title
    $filename = $sanitized_title . $extension;
    $unique_filename = wp_unique_filename($upload_dir['path'], $filename);

    // Check folder permission and define file location
    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $unique_filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $unique_filename;
    }

    // Create the image file on the server
    file_put_contents($file, $image_data);

    // Check image file type (for MIME type)
    $wp_filetype = wp_check_filetype($unique_filename, null);

    // Set attachment data
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => $post_title, // Use post title as attachment title
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Create the attachment
    $attach_id = wp_insert_attachment($attachment, $file, $post_id);
    if (is_wp_error($attach_id)) {
        return $attach_id; // Return error if attachment creation fails
    }

    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    wp_update_attachment_metadata($attach_id, $attach_data);

    // Set the alt text to the post title
    update_post_meta($attach_id, '_wp_attachment_image_alt', $post_title);

    // Assign featured image to post
    set_post_thumbnail($post_id, $attach_id);

    // Return the attachment ID
    return $attach_id;
}