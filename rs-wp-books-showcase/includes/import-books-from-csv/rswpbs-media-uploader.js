jQuery(document).ready(function($) {
    // Check if Pro is inactive
    if ($('#upload_csv_button').is(':disabled')) {
        $('#selected_csv_file_name').css('display', 'none');
    }

    $('#upload_csv_button').click(function(e) {
        if ($(this).is(':disabled')) {
            alert('🚀 This feature is only available in RS WP Book Showcase Pro.');
            return;
        }

        e.preventDefault();
        var mediaUploader = wp.media({
            title: 'Select CSV File',
            button: { text: 'Use this file' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            if (attachment.url.endsWith('.csv')) {
                $('#rswpbs_upload_csv_file').val(attachment.id);
                $('#selected_csv_file_name').html('📂 <strong>Selected file:</strong> ' + attachment.filename);
            } else {
                alert('Please select a valid CSV file.');
            }
        });

        mediaUploader.open();
    });
});
