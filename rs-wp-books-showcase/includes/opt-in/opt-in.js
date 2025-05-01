(function($) {
    jQuery(document).ready(function($) {
        $('.rs-wp-book-showase-opt-in-wrapper a#yes-i-would-love-to').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: rswpthemes_opt_ins.ajaxurl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'rswpbs_collect_email'
                },
                success: function(response) {
                    if (response.success) {
                        $.ajax({
                            url: rswpthemes_opt_ins.ajaxurl,
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                action: 'rswpbs_update_activation_time'
                            },
                            success: function(response) {
                                console.log('Activation time updated successfully.');
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating activation time: ' + error);
                            }
                        });
                        $('.rs-wp-book-showcase-notice-container').remove();
                    } else {
                        console.error('Error collecting email: ' + (response.data.error || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error: ' + error);
                }
            });
        });
    });
})(jQuery);