//Downloadble Fields For Products
jQuery(document).ready(function($) {

    function toggleDownloadableFields() {
        if ($('.book-field-container #_downloadable').is(':checked')) {
            $('.book-field-container.downloadable_files').show();
            $('.downloadable_files_limit_expiry').show();
        } else {
            $('.book-field-container.downloadable_files').hide();
            $('.downloadable_files_limit_expiry').hide();
        }
    }

    // Toggle fields on page load
    toggleDownloadableFields();

    // Toggle fields when checkbox changes
    $('#_downloadable').change(function () {
        toggleDownloadableFields();
    });
    // Make the rows sortable
    $('.downloadable_files tbody').sortable({
        items: 'tr',
        cursor: 'move',
        axis: 'y',
        handle: '.sort'
    });

    // Handle adding a new row
    $('.downloadable_files').on('click', 'a.insert', function(e) {
        e.preventDefault();

        // Duplicate the "tr" row with empty inputs
        var newRow = $(this).data('row');
        $('.downloadable_files tbody').append(newRow);
    });

    // Handle deleting a row
    $('.downloadable_files').on('click', 'a.delete', function(e) {
        e.preventDefault();

        $(this).closest('tr').remove();
    });
});