jQuery(document).ready(function($) {
    // Check if Pro is inactive
    if ($('#upload_json_button').is(':disabled')) {
        $('#selected_json_file_name').css('display', 'none');
    }

    $('#upload_json_button').click(function(e) {
        if ($(this).is(':disabled')) {
            alert('🚀 This feature is only available in RS WP Book Showcase Pro.');
            return;
        }

        e.preventDefault();
        var mediaUploader = wp.media({
            title: 'Select JSON File',
            button: { text: 'Use this file' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            if (attachment.url.endsWith('.json')) {
                $('#rswpbs_upload_json_file').val(attachment.id);
                $('#selected_json_file_name').html('📂 <strong>Selected file:</strong> ' + attachment.filename);
            } else {
                alert('Please select a valid JSON file.');
            }
        });

        mediaUploader.open();
    });
});
