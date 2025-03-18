jQuery(document).ready(function ($) {
    $('#rswpbs-create-page').on('click', function () {
        var button = $(this);
        var originalButtonText = button.text();
        var newButtonText = (originalButtonText === 'Yes, Add Books Now') ? 'Adding Books...' : 'Setting up...';
        button.prop('disabled', true).text(newButtonText);

        // Add loading message after the button
        button.after('<span class="rswpbs-loading-msg" style="margin-left: 10px; color: #0073aa;">Please do not close or reload this page.</span>');

        $.ajax({
            url: rswpbs_setup_book_gallery.ajax_url,
            type: 'POST',
            data: {
                action: 'rswpbs_setup_book_gallery_page',
                nonce: rswpbs_setup_book_gallery.nonce
            },
            success: function (response) {
                button.prop('disabled', false).text(originalButtonText); // Revert to original text
                $('.rswpbs-loading-msg').remove(); // Remove loading message

                if (response.success) {
                    var notice = $('#rswpbs-setup-books-page-notice');
                    notice.html(`
                        <p>${response.data.message} <br> Check it out here:
                            <a href="${response.data.page_url}" target="_blank">${response.data.page_title}</a>.
                        </p>
                    `);

                    if (response.data.books_found) {
                        notice.append(`
                            <p>Ready to grow your gallery? Add more books to impress your visitors!</p>
                            <p>
                                <button id="rswpbs-import-more" class="button button-secondary">
                                    Yes, Add More Books
                                </button>
                            </p>
                        `);

                        $('#rswpbs-import-more').on('click', function () {
                            var importButton = $(this);
                            var importOriginalText = importButton.text();
                            importButton.prop('disabled', true).text('Adding Books...');

                            // Add loading message after the import button
                            importButton.after('<span class="rswpbs-loading-msg" style="margin-left: 10px; color: #0073aa;">Please do not close or reload this page.</span>');

                            $.ajax({
                                url: rswpbs_setup_book_gallery.ajax_url,
                                type: 'POST',
                                data: {
                                    action: 'rswpbs_import_more_books',
                                    nonce: rswpbs_setup_book_gallery.nonce
                                },
                                success: function (importResponse) {
                                    $('.rswpbs-loading-msg').remove(); // Remove loading message
                                    if (importResponse.success) {
                                        notice.append(`
                                            <p><strong>✅ Success:</strong> Added ${importResponse.data.import_count} more books to your gallery!</p>
                                        `);
                                        notice.append(`
                                            <p class="amz-notice-sub-heading"><strong>
                                                Looking to grow your gallery? Easily import over 1,000 books from Amazon in 10 minutes or upload a CSV file with your own collection to personalize it for your visitors!
                                            </strong></p>
                                            <div class="rswpbs-amz-admin-notice-btn-wrapper">
                                                <a href="${rswpbs_setup_book_gallery.admin_urls.settings}" class="button button-primary">
                                                    Import Books From CSV
                                                </a>
                                                <a href="${rswpbs_setup_book_gallery.admin_urls.import}" class="import-books-from-amazon-btn button button-secondary"><span class="dashicons dashicons-amazon"></span>
                                                    Import Books from Amazon
                                                </a>
                                            </div>
                                        `);
                                    } else {
                                        notice.append(`
                                            <p style="color: red;">${importResponse.data.message}</p>
                                        `);
                                    }
                                    importButton.remove();
                                },
                                error: function () {
                                    $('.rswpbs-loading-msg').remove(); // Remove loading message
                                    importButton.prop('disabled', false).text(importOriginalText); // Revert to original text
                                    notice.append(`<p style="color: red;">Something went wrong while importing more books.</p>`);
                                }
                            });
                        });
                    } else if (response.data.show_monetize_prompt) {
                        notice.append(`
                            <p class="amz-notice-sub-heading"><strong>
                                Looking to grow your gallery? Easily import over 1,000 books from Amazon in 10 minutes or upload a CSV file with your own collection to personalize it for your visitors!
                            </strong></p>
                            <div class="rswpbs-amz-admin-notice-btn-wrapper">
                                <a href="${rswpbs_setup_book_gallery.admin_urls.settings}" class="button button-primary">
                                    Import Books From CSV
                                </a>
                                <a href="${rswpbs_setup_book_gallery.admin_urls.import}" class="import-books-from-amazon-btn button button-secondary"><span class="dashicons dashicons-amazon"></span>
                                    Import Books from Amazon
                                </a>
                            </div>
                        `);
                    }
                } else {
                    $('#rswpbs-setup-books-page-notice').append(`<p style="color: red;">${response.data.message}</p>`);
                }
            },
            error: function () {
                $('.rswpbs-loading-msg').remove(); // Remove loading message
                button.prop('disabled', false).text(originalButtonText); // Revert to original text
                $('#rswpbs-setup-books-page-notice').append(`<p style="color: red;">Something went wrong. Please try again.</p>`);
            }
        });
    });
});