<?php
// Render the meta box content
function rswpbs_render_msl_meta_box( $post ) {
    // Retrieve existing data
    $rows = get_post_meta( $post->ID, 'rswpbs_also_available_website_list', true );
    if ( ! is_array( $rows ) || empty( $rows ) ) {
        // Ensure one empty row is present by default
        $rows = array(
            array(
                'website_image' => '',
                'website_name'  => '',
                'book_url'      => '',
            )
        );
    }
    ?>
    <div id="msl-repeater-wrapper" class="repeater-wrapper">
        <h2 class="repeater-heading">Multiple Purchase Links</h2>
        <div class="msl-repeater-header repeater-header rswpbs-row book-field-container">
            <div class="sort-handle rswpbs-col-1">Sort</div>
            <div class="msl-field rswpbs-col-4 msl-image-col">Website Image</div>
            <div class="msl-field rswpbs-col-2 msl-website-name-col">Website Name</div>
            <div class="msl-field rswpbs-col-4 msl-link-col">Book URL</div>
            <div class="repeater-action rswpbs-col-1 msl-action-col">Action</div>
        </div>
        <div id="msl-repeater-container">
            <?php foreach ( $rows as $index => $row ) : ?>
                <div class="msl-row rswpbs-row book-field-container">
                    <div class="sort-handle rswpbs-col-1" style="cursor:move;">
                        <span class="dashicons dashicons-move"></span>
                    </div>
                    <div class="msl-field rswpbs-col-4 msl-image-col">
                        <input type="text" name="also_available_website_list[<?php echo esc_attr( $index ); ?>][website_image]" value="<?php echo esc_url( $row['website_image'] ); ?>" class="msl-uploaded-image-url" readonly="readonly" />
                        <button type="button" class="msl-upload-image-button button">Upload Image</button>
                    </div>
                    <div class="msl-field rswpbs-col-2 msl-website-name-col">
                        <input type="text" name="also_available_website_list[<?php echo esc_attr( $index ); ?>][website_name]" value="<?php echo esc_attr( $row['website_name'] ); ?>" />
                    </div>
                    <div class="msl-field rswpbs-col-4 msl-link-col">
                        <input type="text" name="also_available_website_list[<?php echo esc_attr( $index ); ?>][book_url]" value="<?php echo esc_attr( $row['book_url'] ); ?>" />
                    </div>
                    <div class="repeater-action rswpbs-col-1 msl-action-col">
                        <button type="button" class="msl-remove-row button">Remove</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
       <div class="rswpbs-row justify-content-end">
            <div class="col-md-3">
                <div class="add-action-button-wrapper">
                    <button type="button" id="add-new-msl" class="button">Add New</button>
                </div>
            </div>
        </div>

    </div>
    <script>
    (function($) {
        $(document).ready(function(){
            var $container = $('#msl-repeater-container');

            // Enable sorting
            $container.sortable({
                handle: '.sort-handle',
                update: function(event, ui) {
                    updateRowIndexes();
                }
            });

            function updateRowIndexes() {
                $container.find('.msl-row').each(function(index){
                    $(this).find('input').each(function(){
                        var name = $(this).attr('name');
                        name = name.replace(/also_available_website_list\[\d+\]/, 'also_available_website_list['+index+']');
                        $(this).attr('name', name);
                    });
                });
            }

            $('#add-new-msl').on('click', function(e) {
                e.preventDefault();
                var index = $container.find('.msl-row').length;
                var newRow = '<div class="msl-row rswpbs-row book-field-container">' +
                    '<div class="sort-handle rswpbs-col-1" style="cursor:move;"><span class="dashicons dashicons-move"></span></div>' +
                    '<div class="msl-field rswpbs-col-4 msl-image-col">' +
                        '<input type="text" name="also_available_website_list['+index+'][website_image]" value="" class="msl-uploaded-image-url" readonly="readonly" />' +
                        '<button type="button" class="msl-upload-image-button button">Upload Image</button>' +
                    '</div>' +
                    '<div class="msl-field rswpbs-col-2 msl-website-name-col">' +
                        '<input type="text" name="also_available_website_list['+index+'][website_name]" />' +
                    '</div>' +
                    '<div class="msl-field rswpbs-col-4 msl-link-col">' +
                        '<input type="text" name="also_available_website_list['+index+'][book_url]" />' +
                    '</div>' +
                    '<div class="repeater-action rswpbs-col-1 msl-action-col"><button type="button" class="msl-remove-row button">Remove</button></div>' +
                    '</div>';
                $container.append(newRow);
            });

            $container.on('click', '.msl-remove-row', function(e){
                e.preventDefault();
                $(this).closest('.msl-row').remove();
                updateRowIndexes();
            });

            $(document).on('click', '.msl-upload-image-button', function(e) {
                e.preventDefault();
                var $button = $(this);
                var mediaUploader = wp.media({
                    title: 'Choose Image',
                    button: { text: 'Choose Image' },
                    multiple: false
                });
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $button.siblings('input.msl-uploaded-image-url').val( attachment.url );
                });
                mediaUploader.open();
            });
        });
    })(jQuery);
    </script>

    <?php
}
