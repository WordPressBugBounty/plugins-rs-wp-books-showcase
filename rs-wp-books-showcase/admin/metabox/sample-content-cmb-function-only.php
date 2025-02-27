<?php
// 2. Render the meta box.
function render_book_sample_content_box( $post ) {
    wp_nonce_field( 'rswpbs_save_book_sample_content', 'rswpbs_book_sample_content_nonce' );

    // Retrieve saved meta data.
    $sample_content = get_post_meta( $post->ID, 'rswpbs_book_sample_content', true );
    if ( ! is_array( $sample_content ) || empty( $sample_content ) ) {
        // Show one empty row by default.
        $sample_content = array(
            array(
                'upload_pdf_file' => '',
                'upload_image'    => '',
                'audio_url'       => '',
                'video_url'       => '',
            )
        );
    }
    ?>
    <div id="sample-content-repeater-wrapper" class="repeater-wrapper">
        <h2 class="repeater-heading">Sample Content</h2>
        <div class="sample-content-header repeater-header rswpbs-row book-field-container">
            <div class="sort-handle rswpbs-col-1"></div>
            <div class="field-group rswpbs-col-3">Upload PDF File</div>
            <div class="field-group rswpbs-col-3">Upload Image</div>
            <div class="field-group rswpbs-col-3">Insert Audio URL</div>
            <div class="field-group rswpbs-col-3">Video URL</div>
            <div class="field-group rswpbs-col-1 action-col">Action</div>
        </div>
        <div id="sample-content-container" class="sample-content-container">
            <?php foreach ( $sample_content as $index => $row ) : ?>
                <div class="sample-content-row rswpbs-row book-field-container">
                    <div class="sort-handle rswpbs-col-1"><span class="dashicons dashicons-move"></span></div>

                    <!-- PDF File Field -->
                    <div class="field-group rswpbs-col-3">
                        <input type="text" id="upload_pdf_file_<?php echo $index; ?>" class="upload-field upload-pdf-file" name="sample_content[<?php echo $index; ?>][upload_pdf_file]" value="<?php echo esc_attr( $row['upload_pdf_file'] ); ?>" readonly />
                        <button type="button" class="sample-content-select-pdf button" data-target="upload_pdf_file_<?php echo $index;?>">Select PDF</button>
                    </div>

                    <!-- Image Field -->
                    <div class="field-group rswpbs-col-3">
                        <input type="text" id="upload_image_<?php echo $index; ?>" class="upload-field upload-image-field" name="sample_content[<?php echo $index; ?>][upload_image]" value="<?php echo esc_attr( $row['upload_image'] ); ?>" readonly />
                        <button type="button" class="sample-content-select-image button" data-target="upload_image_<?php echo $index;?>">Select Image</button>
                    </div>

                    <!-- Audio Field -->
                    <div class="field-group rswpbs-col-3">
                        <input type="text" id="audio_url_<?php echo $index; ?>" class="upload-field upload-audio-field" name="sample_content[<?php echo $index; ?>][audio_url]" value="<?php echo esc_attr( $row['audio_url'] ); ?>" readonly />
                        <button type="button" class="sample-content-select-audio button" data-target="audio_url_<?php echo $index;?>">Select Audio</button>
                    </div>

                    <!-- Video URL Field -->
                    <div class="field-group rswpbs-col-3">
                        <input type="text" id="video_url<?php echo $index; ?>" class="upload-field upload-video-field" name="sample_content[<?php echo $index; ?>][video_url]" value="<?php echo esc_attr( $row['video_url'] ); ?>" readonly />
                        <button type="button" class="sample-content-select-video button" data-target="video_url_<?php echo $index;?>">Select Video</button>
                    </div>

                    <!-- Remove Row -->
                    <div class="field-group action-col rswpbs-col-1">
                        <button type="button" class="remove-row button">Remove</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="rswpbs-row justify-content-end">
            <div class="col-md-3">
                <div class="add-action-button-wrapper">
                    <button type="button" id="add-sample-content-row" class="button">Add New</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($){

        var container = $('#sample-content-container');
        var rowIndex = container.find('.sample-content-row').length;

        function reIndexRows() {
            container.find('.sample-content-row').each(function(index) {
                $(this).find('input.upload-pdf-file').attr('id', 'upload_pdf_file_' + index);
                $(this).find('button.sample-content-select-pdf').attr('data-target', 'upload_pdf_file_' + index);

                $(this).find('input.upload-image-field').attr('id', 'upload_image_' + index);
                $(this).find('button.sample-content-select-image').attr('data-target', 'upload_image_' + index);

                $(this).find('input.upload-audio-field').attr('id', 'audio_url_' + index);
                $(this).find('button.sample-content-select-audio').attr('data-target', 'audio_url_' + index);

                $(this).find('input').each(function(){
                    var name = $(this).attr('name');
                    name = name.replace(/sample_content\[\d+\]/, 'sample_content[' + index + ']');
                    $(this).attr('name', name);
                });
            });
            rowIndex = container.find('.sample-content-row').length;
        }

        container.sortable({
            items: ".sample-content-row",
            handle: ".sort-handle",
            update: function(event, ui) {
                reIndexRows();
            }
        });

        $('#add-sample-content-row').on('click', function(e){
            e.preventDefault();
            var newRow = $('.sample-content-row:first').clone();
            newRow.find('input').val('');
            container.append(newRow);
            reIndexRows();
        });

        container.on('click', '.remove-row', function(){
            if(container.find('.sample-content-row').length > 1){
                $(this).closest('.sample-content-row').remove();
                reIndexRows();
            }
        });


        // Media uploader for PDF files.
        $(document).on('click', '.sample-content-select-pdf', function(e) {
            e.preventDefault();
            var button = $(this);
            var targetID = button.data('target');

            var mediaFrame = wp.media({
                title: 'Select PDF File',
                button: { text: 'Use this file' },
                // library: { type: 'application/pdf' },
                multiple: false
            });
            mediaFrame.on('select', function() {
                var attachment = mediaFrame.state().get('selection').first().toJSON();
                    $('#' + targetID).val(attachment.url);
                // if ( attachment.mime && attachment.mime.indexOf('pdf') !== -1 ) {
                // } else {
                //     alert('Please select a valid PDF file.');
                // }
            });
            mediaFrame.open();
        });

        // Media uploader for Audio files.
        $(document).on('click', '.sample-content-select-audio', function(e) {
            e.preventDefault();
            var button = $(this);
            var targetID = button.data('target');

            var mediaFrame = wp.media({
                title: 'Select Audio File',
                button: { text: 'Use this file' },
                // library: { type: 'audio' },
                multiple: false
            });
            mediaFrame.on('select', function() {
                var attachment = mediaFrame.state().get('selection').first().toJSON();
                $('#' + targetID).val(attachment.url);
                // if ( attachment.mime && attachment.mime.indexOf('audio') !== -1 ) {
                // } else {
                //     alert('Please select a valid audio file.');
                // }
            });
            mediaFrame.open();
        });

        // Media uploader for Audio files.
        $(document).on('click', '.sample-content-select-video', function(e) {
            e.preventDefault();
            var button = $(this);
            var targetID = button.data('target');

            var mediaFrame = wp.media({
                title: 'Select video File',
                button: { text: 'Use this file' },
                // library: { type: 'video' },
                multiple: false
            });
            mediaFrame.on('select', function() {
                var attachment = mediaFrame.state().get('selection').first().toJSON();
                    $('#' + targetID).val(attachment.url);
                // if ( attachment.mime && attachment.mime.indexOf('video') !== -1 ) {
                // } else {
                //     alert('Please select a valid Video file.');
                // }
            });
            mediaFrame.open();
        });

        // Media uploader for Images.
        $(document).on('click', '.sample-content-select-image', function(e) {
            e.preventDefault();
            var button = $(this);
            var targetID = button.data('target');

            var mediaFrame = wp.media({
                title: 'Select Image',
                button: { text: 'Use this image' },
                // library: { type: 'image' },
                multiple: false
            });
            mediaFrame.on('select', function() {
                var attachment = mediaFrame.state().get('selection').first().toJSON();
                $('#' + targetID).val(attachment.url);
                // Update preview.
                $('#preview_' + targetID).html('<img src="'+attachment.url+'" alt="Preview" />');
                // if ( attachment.mime && attachment.mime.indexOf('image') !== -1 ) {
                // } else {
                //     alert('Please select a valid image file.');
                // }
            });
            mediaFrame.open();
        });

    });
    </script>
    <?php
}
