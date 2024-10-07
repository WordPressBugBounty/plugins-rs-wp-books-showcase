(function($) {
    $('.search-field select').selectize();
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
    $('.rswpbs-testimonial-read-more').click(function(e) {
        e.preventDefault();
        var reviewDescription = $(this).closest('.review-description');
        reviewDescription.find('.review-short-content').hide();
        reviewDescription.find('.read-more').hide();
        reviewDescription.find('.review-full-content').show();
        rswpbsTestimonialMasonryInit();
    });
    $('.rswpbs-testimonial-show-less').click(function(e) {
        e.preventDefault();
        var reviewDescription = $(this).closest('.review-description');
        reviewDescription.find('.review-full-content').hide();
        reviewDescription.find('.review-short-content').show();
        reviewDescription.find('.read-more').show();
        rswpbsTestimonialMasonryInit();
    });
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=rswp-book-gallery', rswpbsMasonryInit);
    }
});

jQuery(window).on('scroll', function() {
    rswpbsMasonryInit();
    rswpbsTestimonialMasonryInit();
});
