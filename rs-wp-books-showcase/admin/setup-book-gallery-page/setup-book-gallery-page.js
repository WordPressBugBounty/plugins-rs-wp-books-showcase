jQuery(document).ready(function ($) {
    $('#rswpbs-create-page').on('click', function () {
        var button = $(this);
        button.prop('disabled', true).text('Creating...');

        $.ajax({
            url: rswpbs_setup_book_gallery.ajax_url,
            type: 'POST',
            data: {
                action: 'rswpbs_setup_book_gallery_page',
                nonce: rswpbs_setup_book_gallery.nonce
            },
            success: function (response) {
                if (response.success) {
                    $('#rswpbs-setup-books-page-notice').html(`
                        <p><strong>📚 Success:</strong> The Book Gallery page has been created.</p>
                        <p>Your new <strong>Book Gallery</strong> page is available at:
                            <a href="${response.data.page_url}" target="_blank">${response.data.page_title}</a>.
                        </p>
                    `);
                } else {
                    $('#rswpbs-notice').append(`<p style="color: red;">${response.data.message}</p>`);
                }
            },
            error: function () {
                $('#rswpbs-notice').append(`<p style="color: red;">Something went wrong. Please try again.</p>`);
            }
        });
    });
});
