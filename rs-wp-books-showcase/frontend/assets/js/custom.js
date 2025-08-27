(function($) {
    // This part remains unchanged
    $('.search-field select').select2({
        searchField: ['text', 'value'],
        persist: false,
        create: false,
        allowEmptyOption: true,
        allowClear: true,
        placeholder: "Search or select"
    });

    $('#rswpbs-sort').change(function() {
        $('#rswpbs-sortby').val(this.value);
        $('#rswpthemes-books-search-form, #rswpthemes-book-sort-form').submit();
    });

    $('.reset-search-form').click(function(event) {
        event.preventDefault();
        history.pushState({}, "", window.location.pathname);
        location.reload();
    });

})(jQuery);

function rswpbsMasonryInit() {
    if (typeof jQuery.fn.masonry !== 'undefined') {
        jQuery('.masonry_layout_active_for_books').masonry({
            itemSelector: '.book-single-column',
        });
        jQuery('.rswpbs-authors-masonry').masonry({
            itemSelector: '.rswpbs-author-col',
        });
    }
}

function rswpbsTestimonialMasonryInit() {
    if (typeof jQuery.fn.masonry !== 'undefined') {
        jQuery('.rswpbs-testimonial-masonry').masonry({
            itemSelector: '.testimonial-item-col',
        });
    }
}

jQuery(window).on('load', function() {
    rswpbsMasonryInit();
    rswpbsTestimonialMasonryInit();
});

jQuery(document).ready(function($) {
    rswpbsMasonryInit();
    rswpbsTestimonialMasonryInit();

    // --- NEW POPUP MODAL LOGIC ---

    // Use event delegation for the "Read More" link.
    // This ensures it works reliably, even with sliders or AJAX-loaded content.
    $(document.body).on('click', '.rswpbs-testimonial-read-more', function(e) {
        e.preventDefault();
        var modalId = $(this).data('modal-id');
        $('#' + modalId).fadeIn(200);
    });

    // Use event delegation for the close button ('x').
    $(document.body).on('click', '.rswpbs-popup-close', function() {
        $(this).closest('.rswpbs-review-popup').fadeOut(200);
    });

    // Close the popup when clicking on the dark background overlay.
    $(document).on('click', function(event) {
        // The `is` check ensures we're clicking the background itself,
        // not a child element within the popup content area.
        if ($(event.target).is('.rswpbs-review-popup')) {
            $(event.target).fadeOut(200);
        }
    });

    // Close the popup when the 'Escape' key is pressed.
    $(document).on('keydown', function(event) {
        if (event.key === "Escape") {
            $('.rswpbs-review-popup').fadeOut(200);
        }
    });

    // --- END OF NEW LOGIC ---

    if (window.acf) {
        window.acf.addAction('render_block_preview/type=rswp-book-gallery', rswpbsMasonryInit);
    }
});

jQuery(window).on('scroll', function() {
    rswpbsMasonryInit();
    rswpbsTestimonialMasonryInit();
});
