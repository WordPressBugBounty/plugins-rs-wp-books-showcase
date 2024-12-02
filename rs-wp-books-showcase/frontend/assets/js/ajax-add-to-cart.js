// jQuery(document).ready(function($) {
//     $('.cptwooint-cart-btn-wrapper .add-to-cart-button').on('click', function(e) {
//         e.preventDefault();

//         var product_id = $(this).data('product-id');

//         $.ajax({
//             url: wc_add_to_cart_params.ajax_url,
//             type: 'POST',
//             data: {
//                 action: 'woocommerce_ajax_add_to_cart',
//                 product_id: product_id,
//             },
//             success: function(response) {
//                 if (response.error && response.product_url) {
//                     window.location = response.product_url;
//                     return;
//                 }

//                 // Update cart contents
//                 $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);

//                 // You could also add some feedback like showing a message
//                 alert('Product successfully added to cart!');
//             },
//         });
//     });
// });