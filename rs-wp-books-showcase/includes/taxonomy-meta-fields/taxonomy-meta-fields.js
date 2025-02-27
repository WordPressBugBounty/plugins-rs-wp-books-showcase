jQuery(document).ready(function($) {
    // Helper to maintain cell widths while dragging.
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    // Initialize sortable on the table body.
    $('#author_social_profiles tbody').sortable({
        helper: fixHelper,
        update: function(event, ui) {
            updateRowIndices();
        }
    }).disableSelection();

    // Function to update row indices for proper name attributes.
    function updateRowIndices() {
        $('#author_social_profiles tbody tr').each(function(index) {
            $(this).find('input').each(function() {
                var name = $(this).attr('name');
                var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', newName);
            });
        });
    }

    // Add a new row when clicking the "Add Row" button.
    $('#add_social_profile_row').on('click', function(e) {
        e.preventDefault();
        console.log(e.target);
        var $tbody = $('#author_social_profiles tbody');
        var $lastRow = $tbody.find('tr:last');
        var $newRow = $lastRow.clone();
        // Clear input values in the new row.
        $newRow.find('input').val('');
        $tbody.append($newRow);
        updateRowIndices();
    });

    // Remove a row when clicking the "Remove" button.
    $('#author_social_profiles').on('click', '.remove-row', function(e) {
        e.preventDefault();
        var $tbody = $('#author_social_profiles tbody');
        if ($tbody.find('tr').length > 1) {
            $(this).closest('tr').remove();
            updateRowIndices();
        } else {
            // If it’s the only row, just clear its inputs.
            $(this).closest('tr').find('input').val('');
        }
    });

    // --- Media Uploader for Author Picture ---
    var mediaUploader;
    $('#upload_author_picture_button').on('click', function(e) {
        e.preventDefault();
        // If the uploader object has already been created, reopen it.
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        // Create the media uploader.
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: { text: 'Choose Image' },
            library: { type: 'image' },
            multiple: false
        });
        // When an image is selected, run a callback.
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#author_picture').val(attachment.url);
            $('#author_picture_preview').attr('src', attachment.url).show();
            $('#remove_author_picture_button').show();
        });
        // Open the uploader dialog.
        mediaUploader.open();
    });

    $('#remove_author_picture_button').on('click', function(e) {
        e.preventDefault();
        $('#author_picture').val('');
        $('#author_picture_preview').hide();
        $(this).hide();
    });

});