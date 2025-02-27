<?php
function add_mockup_image_metabox() {
    add_meta_box(
        'mockup_image_metabox',
        'Mockup Image',
        'render_mockup_image_metabox',
        'book',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_mockup_image_metabox');

function render_mockup_image_metabox($post) {
    wp_nonce_field('save_mockup_image_metabox', 'mockup_image_nonce');
    $mockup_image = get_post_meta($post->ID, '_rswpbs_mockup_image', true);
    ?>
    <p>
        <img id="mockup_image_preview" src="<?php echo esc_url($mockup_image); ?>" style="max-width:100%; <?php echo empty($mockup_image) ? 'display:none;' : ''; ?>" />
    </p>
    <p>
        <input type="text" id="mockup_image" name="mockup_image" value="<?php echo esc_attr($mockup_image); ?>" style="width:100%;" />
        <button type="button" class="button mockup-image-upload">Upload Image</button>
    </p>
    <script>
        jQuery(document).ready(function($) {
            $('.mockup-image-upload').click(function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: 'Select or Upload an Image',
                    button: { text: 'Use this image' },
                    multiple: false
                }).open().on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#mockup_image').val(attachment.url);
                    $('#mockup_image_preview').attr('src', attachment.url).show();
                });
            });
        });
    </script>
    <?php
}

function save_mockup_image_metabox($post_id) {
    if (!isset($_POST['mockup_image_nonce']) || !wp_verify_nonce($_POST['mockup_image_nonce'], 'save_mockup_image_metabox')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['mockup_image'])) {
        update_post_meta($post_id, '_rswpbs_mockup_image', esc_url($_POST['mockup_image']));
    }
}
add_action('save_post', 'save_mockup_image_metabox');
