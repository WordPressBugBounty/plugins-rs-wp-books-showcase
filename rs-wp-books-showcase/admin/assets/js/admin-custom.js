(function($) {

    /*Start Repeatable Field Scripts */
    /*Clone Empty Row*/
    $('#external-website-list-wrapper').on('click', '#add-new-row', function(e) {
        e.preventDefault();
        var thisParent = $(this).parents('#external-website-list-wrapper');
        var row = thisParent.find('.external-website-list-item');
        var emptyRow = thisParent.find('.external-website-list-item.empty-row').clone(false);
        row.last().find('#available-website-name').val('');
        row.last().find('#book-url').val('');
        emptyRow.removeClass('empty-row');
        emptyRow.removeClass('d-none');
        emptyRow.insertBefore('.external-website-list-item:last-child');
    });
    /*Remove Row*/
    $('#external-website-list-wrapper').on('click', '#remove-row button', function(e) {
        e.preventDefault();
        var externalWebsiteItemList = $('.external-website-list-item');
        var thisParent = $(this).parents('.external-website-list-item');
        if (thisParent.hasClass('empty-row')) {
            thisParent.find('#available-website-name').val('');
            thisParent.find('#book-url').val('');
        } else {
            thisParent.remove();
        }
    });

    /*End Repeatable Field Scripts*/

    /*Getting Only Year From Date*/
    var publishDate = $('#publish-date'),
        publishYear = $('#publish-year'),
        publishDateVal = publishDate.val(),
        getTimeStamp = new Date(publishDateVal);
    publishYear.val(getTimeStamp.getFullYear());

    publishDate.change(function() {
        publishDateVal = $(this).val();
        publishDateVal = publishDate.val(),
            getTimeStamp = new Date(publishDateVal);
        publishYear.val(getTimeStamp.getFullYear());
    });


    // Select the form field
    $('#book-price, #book-sale-price').on('keydown', function(e) {
        // Allow: backspace, delete, tab, escape, enter
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
            // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // Allow these keys
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

})(jQuery);

jQuery(document).ready(function($) {
    if (typeof tinymce !== "undefined") {
        tinymce.init({
            selector: "#short-description", // Target the textarea by its ID.
            menubar: false,
            toolbar: "bold italic underline | alignleft aligncenter alignright | bullist numlist | link",
            height: 200,
            branding: false
        });
    }
});

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