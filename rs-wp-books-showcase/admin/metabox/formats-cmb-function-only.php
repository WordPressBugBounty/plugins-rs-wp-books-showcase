<?php
// Render the meta box content
function rswpbs_render_formats_meta_box($post) {
    // Retrieve existing data
    $rows = get_post_meta( $post->ID, 'rswpbs_book_formats', true );
    if ( ! is_array( $rows ) || empty( $rows ) ) {
        // Ensure one empty row is present by default
        $rows = array(
            array(
                'format_image' => '',
                'name'  => '',
                'price'      => '',
                'link'      => '',
            )
        );
    }
    ?>
    <div id="formats-repeater-wrapper" class="repeater-wrapper">
        <h2 class="repeater-heading">Formats</h2>
        <div class="format-headers repeater-header book-field-container rswpbs-row">
            <div class="sort-handle rswpbs-col-1">Sort</div>
            <div class="format-field rswpbs-col-3 format-image-col">Format Image</div>
            <div class="format-field rswpbs-col-2 format-name-col">Format Name</div>
            <div class="format-field rswpbs-col-2 format-price-col">Format Price</div>
            <div class="format-field rswpbs-col-3 format-link-col">Format Link</div>
            <div class="format-actions rswpbs-col-1 format-button-col">Action</div>
        </div>
        <div id="formats-repeater-container">
            <?php foreach ( $rows as $index => $row ) : ?>
                <div class="format-row book-field-container rswpbs-row" data-index="<?php echo esc_attr($index); ?>">
                    <div class="sort-handle rswpbs-col-1">
                        <span class="dashicons dashicons-move"></span>
                    </div>
                    <div class="format-field rswpbs-col-3 format-image-col">
                        <input type="text" name="book_formats[<?php echo esc_attr( $index ); ?>][format_image]" value="<?php echo esc_url( $row['format_image'] ); ?>" class="format-uploaded-image-url" readonly="readonly"/>
                        <button type="button" class="format-upload-image-button button">Upload Image</button>
                    </div>
                    <div class="format-field rswpbs-col-2 format-name-col">
                        <input type="text" name="book_formats[<?php echo esc_attr( $index ); ?>][name]" value="<?php echo esc_attr( $row['name'] ); ?>" />
                    </div>
                    <div class="format-field rswpbs-col-2 format-price-col">
                        <input type="text" name="book_formats[<?php echo esc_attr( $index ); ?>][price]" value="<?php echo esc_attr( $row['price'] ); ?>" />
                    </div>
                    <div class="format-field rswpbs-col-3 format-link-col">
                        <input type="text" name="book_formats[<?php echo esc_attr( $index ); ?>][link]" value="<?php echo esc_attr( $row['link'] ); ?>" />
                    </div>
                    <div class="format-actions rswpbs-col-1 format-button-col">
                        <button type="button" class="remove-row button">Remove</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="rswpbs-row justify-content-end">
            <div class="col-md-3">
                <div class="add-action-button-wrapper">
                    <button type="button" id="add-new-format" class="button">Add New</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    (function($) {
        $(document).ready(function(){
            var $container = $('#formats-repeater-container');

            // Enable sorting
            $container.sortable({
                handle: '.sort-handle',
                update: function() {
                    updateRowIndexes();
                }
            });

            function updateRowIndexes() {
                $container.find('.format-row').each(function(index){
                    $(this).attr('data-index', index);
                    $(this).find('input').each(function(){
                        var name = $(this).attr('name');
                        name = name.replace(/book_formats\[\d+\]/, 'book_formats['+index+']');
                        $(this).attr('name', name);
                    });
                });
            }

            $('#add-new-format').on('click', function(e) {
                e.preventDefault();
                var index = $container.find('.format-row').length;
                var newRow = '<div class="format-row book-field-container rswpbs-row" data-index="'+index+'">' +
                    '<div class="sort-handle rswpbs-col-1"><span class="dashicons dashicons-move"></span></div>' +
                    '<div class="format-field rswpbs-col-3 format-image-col">' +
                        '<input type="text" name="book_formats['+index+'][format_image]" value="" class="format-uploaded-image-url" readonly="readonly"/>' +
                        '<button type="button" class="format-upload-image-button button">Upload Image</button>' +
                    '</div>' +
                    '<div class="format-field rswpbs-col-2 format-name-col">' +
                        '<input type="text" name="book_formats['+index+'][name]" />' +
                    '</div>' +
                    '<div class="format-field rswpbs-col-2 format-price-col">' +
                        '<input type="text" name="book_formats['+index+'][price]" />' +
                    '</div>' +
                    '<div class="format-field rswpbs-col-3 format-link-col">' +
                        '<input type="text" name="book_formats['+index+'][link]" />' +
                    '</div>' +
                    '<div class="format-actions rswpbs-col-1 format-button-col"><button type="button" class="remove-row button">Remove</button></div>' +
                    '</div>';
                $container.append(newRow);
            });

            $container.on('click', '.remove-row', function(e){
                e.preventDefault();
                $(this).closest('.format-row').remove();
                updateRowIndexes();
            });

            $(document).on('click', '.format-upload-image-button', function(e) {
                e.preventDefault();
                var $button = $(this);
                var mediaUploader = wp.media({
                    title: 'Choose Image',
                    button: { text: 'Choose Image' },
                    multiple: false
                });
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $button.siblings('input.format-uploaded-image-url').val( attachment.url );
                });
                mediaUploader.open();
            });
        });
    })(jQuery);
    </script>
    <?php
}