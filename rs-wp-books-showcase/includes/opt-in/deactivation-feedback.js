jQuery(document).ready(function($) {
    // Intercept the deactivation link click
    $('tr[data-slug="rs-wp-books-showcase"] .deactivate a').on('click', function(e) {
        e.preventDefault();
        var deactivateLink = $(this).attr('href');
        $('#rswpbs-deactivation-feedback').data('deactivate-link', deactivateLink).fadeIn();
    });

    // Handle form submission
    $('#rswpbs-deactivation-form').on('submit', function(e) {
        e.preventDefault();
        var reason = $('input[name="reason"]:checked').val() || 'Not specified';

        $.ajax({
            url: rswpbs_deactivation.ajaxurl,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'rswpbs_collect_deactivation_feedback',
                reason: reason,
            },
            success: function(response) {
                if (response.success) {
                    // Proceed with deactivation
                    var deactivateLink = $('#rswpbs-deactivation-feedback').data('deactivate-link');
                    window.location.href = deactivateLink;
                } else {
                    alert('Error collecting feedback: ' + (response.data.error || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX error: ' + error);
            }
        });
    });

    // Handle skip and deactivate
    $('.rswpbs-skip-deactivate').on('click', function(e) {
        e.preventDefault();
        var deactivateLink = $('#rswpbs-deactivation-feedback').data('deactivate-link');
        window.location.href = deactivateLink;
    });
});