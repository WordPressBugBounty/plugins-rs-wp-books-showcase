jQuery(document).ready(function($) {
    // Helper to maintain cell widths while dragging.
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    // Initialize sortable on the table body for social profiles.
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
                if (name) {
                    var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', newName);
                }
            });
        });
    }

    // Add a new row for social profiles.
    $('#add_social_profile_row').on('click', function(e) {
        e.preventDefault();
        var $tbody = $('#author_social_profiles tbody');
        var $lastRow = $tbody.find('tr:last');
        var $newRow = $lastRow.clone();
        $newRow.find('input').val('');
        $tbody.append($newRow);
        updateRowIndices();
    });

    // Remove a row for social profiles.
    $('#author_social_profiles').on('click', '.remove-row', function(e) {
        e.preventDefault();
        var $tbody = $('#author_social_profiles tbody');
        if ($tbody.find('tr').length > 1) {
            $(this).closest('tr').remove();
            updateRowIndices();
        } else {
            $(this).closest('tr').find('input').val('');
        }
    });

    // --- REUSABLE Media Uploader ---
    // This is now generic and works for any field with the correct classes.
    var mediaUploader;

    $('body').on('click', '.upload-picture-button', function(e) {
        e.preventDefault();
        var $button = $(this);

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: { text: 'Choose Image' },
            library: { type: 'image' },
            multiple: false
        });

        mediaUploader.on('open', function() {
            // Re-assign the on-select callback every time the uploader opens.
            // This ensures we're targeting the fields relative to the clicked button.
            mediaUploader.off('select').on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var $fieldWrapper = $button.closest('.form-field, .term-group-wrap');

                $fieldWrapper.find('.picture-url-input').val(attachment.url);
                $fieldWrapper.find('.picture-preview').attr('src', attachment.url).show();
                $fieldWrapper.find('.remove-picture-button').show();
            });
        });

        mediaUploader.open();
    });

    $('body').on('click', '.remove-picture-button', function(e) {
        e.preventDefault();
        var $fieldWrapper = $(this).closest('.form-field, .term-group-wrap');

        $fieldWrapper.find('.picture-url-input').val('');
        $fieldWrapper.find('.picture-preview').hide();
        $(this).hide();
    });
});