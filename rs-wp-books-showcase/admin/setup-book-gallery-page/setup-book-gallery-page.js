jQuery(document).ready(function ($) {
    $('#rswpbs-create-page').on('click', function () {
        var button = $(this);
        button.prop('disabled', true).text('Setting up...');

        $.ajax({
            url: rswpbs_setup_book_gallery.ajax_url,
            type: 'POST',
            data: {
                action: 'rswpbs_setup_book_gallery_page',
                nonce: rswpbs_setup_book_gallery.nonce
            },
            success: function (response) {
                button.prop('disabled', false).text('Setup Book Gallery'); // Reset button

                if (response.success) {
                    var notice = $('#rswpbs-setup-books-page-notice');
                    notice.html(`
                        <p><strong>🎉 Awesome!</strong> Your Book Gallery is ready! Check it out here:
                            <a href="${response.data.page_url}" target="_blank">${response.data.page_title}</a>.
                        </p>
                        <p>${response.data.message}</p>
                    `);

                    // If books are found, add a "Yes" button
                    if (response.data.books_found) {
                        notice.append(`
                            <p>Want to add more books to make it even better?</p>
                            <p>
                                <button id="rswpbs-import-more" class="button button-secondary">
                                    Yes, Add More Books
                                </button>
                            </p>
                        `);

                        // Handle "Yes" button click
                        $('#rswpbs-import-more').on('click', function () {
                            var importButton = $(this);
                            importButton.prop('disabled', true).text('Importing...');

                            $.ajax({
                                url: rswpbs_setup_book_gallery.ajax_url,
                                type: 'POST',
                                data: {
                                    action: 'rswpbs_import_more_books',
                                    nonce: rswpbs_setup_book_gallery.nonce
                                },
                                success: function (importResponse) {
                                    if (importResponse.success) {
                                        notice.append(`
                                            <p><strong>✅ Success:</strong> Added ${importResponse.data.import_count} more books to your gallery!</p>
                                        `);
                                    } else {
                                        notice.append(`
                                            <p style="color: red;">${importResponse.data.message}</p>
                                        `);
                                    }
                                    importButton.remove(); // Remove the "Yes" button after import
                                },
                                error: function () {
                                    notice.append(`<p style="color: red;">Something went wrong while importing more books.</p>`);
                                    importButton.prop('disabled', false).text('Yes, Add More Books');
                                }
                            });
                        });
                    }
                } else {
                    $('#rswpbs-setup-books-page-notice').append(`<p style="color: red;">${response.data.message}</p>`);
                }
            },
            error: function () {
                button.prop('disabled', false).text('Setup Book Gallery');
                $('#rswpbs-setup-books-page-notice').append(`<p style="color: red;">Something went wrong. Please try again.</p>`);
            }
        });
    });
});