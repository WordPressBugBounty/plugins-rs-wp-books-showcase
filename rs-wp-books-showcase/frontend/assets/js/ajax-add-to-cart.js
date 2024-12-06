jQuery(document).ready(function($) {
    // Event listener for clicking Add to Cart buttons anywhere on the page
    $(document).on('click', '.single_add_to_cart_button, .loop_add_to_cart_button', function(e) {
        e.preventDefault(); // Prevent default form submission

        var $button = $(this);
        var product_id = $button.val(); // Get product ID from button value

        // If we're on the single product page, get quantity from the form
        var quantity = $button.closest('form.cart').find('input.qty').val() || 1; // Default to 1 if not found

        const data = {
            product_id: product_id,
            quantity: quantity,
        };

        $button.addClass('disabled');

        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), // WooCommerce AJAX URL
            data: data,
            dataType: 'json',
            beforeSend: function(xhr) {
                // Optional: Set button to loading state
                $button.prop('disabled', true);
            },
            complete: function(res) {
                // Optional: Remove loading state
                $button.prop('disabled', false);
            },
            success: function(res) {
                if (res.error && res.product_url) {
                    window.location = res.product_url;
                    return;
                }
                // Trigger WooCommerce event to update cart fragments, etc.
                $(document.body).trigger('added_to_cart', [res.fragments, res.cart_hash]);

                // Optional: Show confirmation message
                $('#cart-popup-message').fadeIn(300).delay(1500).fadeOut(300);
                $button.removeClass('disabled');
            },
            error: function(xhr, status, error) {
                alert('There was an error adding the product to the cart: ' + error);
                $button.removeClass('disabled');
            }
        });
    });
});