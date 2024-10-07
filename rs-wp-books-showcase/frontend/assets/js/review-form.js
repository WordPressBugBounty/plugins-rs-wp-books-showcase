(function($) {
    'use strict';

    // Handle the click event for the star icons
    $('.rswpbs-review-form-field .stars i').click(function() {
        // Get the value of the clicked star icon
        var value = $(this).data('value');

        // Set the value of the rating field
        $('.rswpbs-review-form-field #rating').val(value);

        // Set the selected class for the clicked star icon and all preceding icons
        $('.rswpbs-review-form-field .stars i').removeClass('selected fa-solid');
        $('.rswpbs-review-form-field .stars i:lt(' + value + ')').addClass('selected fa-solid');
    });

    $('#rswpbs-review-form').submit(function(event) {
        event.preventDefault();
        var form = $(this).serialize();

        // validate the form fields
        var reviewTitle = $('#review_title').val();
        var reviewerName = $('#reviewer_name').val();
        var reviewerEmail = $('#reviewer_email').val();
        var rating = $('#rating').val();
        var reviewerComment = $('#reviewer_comment').val();

        if (reviewTitle == '') {
            // show an error message
            $('#review_title').after('<p class="error-message">Please enter a review title</p>');
        } else {
            // remove the error message
            $('#review_title').siblings('.error-message').remove();
        }

        if (reviewerName == '') {
            // show an error message
            $('#reviewer_name').after('<p class="error-message">Please enter your full name</p>');
        } else {
            // remove the error message
            $('#reviewer_name').siblings('.error-message').remove();
        }

        if (reviewerEmail == '') {
            // show an error message
            $('#reviewer_email').after('<p class="error-message">Please enter your email address</p>');
        } else {
            // remove the error message
            $('#reviewer_email').siblings('.error-message').remove();
        }

        if (rating == '') {
            // show an error message
            $('#rating').after('<p class="error-message">Please select a rating</p>');
        } else {
            // remove the error message
            $('#rating').siblings('.error-message').remove();
        }

        if (reviewerComment == '') {
            // show an error message
            $('#reviewer_comment').after('<p class="error-message">Please enter your review</p>');
        } else {
            // remove the error message
            $('#reviewer_comment').siblings('.error-message').remove();
        }

        if (reviewTitle != '' && reviewerName != '' && reviewerEmail != '' && rating != '' && reviewerComment != '') {
            var formData = new FormData();
            formData.append('action', 'rswpbs_submit_review_form');
            formData.append('rswpbs_submit_review_form', form);
            $.ajax({
                url: rswpbs.ajaxurl,
                data: formData,
                type: 'post',
                cache: false,
                processData: false,
                contentType: false,
                success: function(result) {
                    $('#rswpbs-review-form').after('<p class="success-message">'+ result.data.message +'</p>');

                    // Reset the form fields
                    $('#review_title').val('');
                    $('#reviewer_name').val('');
                    $('#reviewer_email').val('');
                    $('#rating').val('');
                    $('#reviewer_comment').val('');
                    $('#reviewer_comment').val('');

                    // Remove the selected class from star icons
                    $('.rswpbs-review-form-field .stars i').removeClass('selected fa-solid');
                }
            });
        }
    });
})(jQuery);
