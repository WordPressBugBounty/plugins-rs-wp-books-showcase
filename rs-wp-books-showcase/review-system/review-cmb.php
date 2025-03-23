<?php
/**
 * Review Meta Box
 */
function rswpbs_book_review_meta_box() {
	 add_meta_box(
	    'rswpbs_book_review_meta_box',       // ID of the meta box
	    'Book Review Details',        // Title of the meta box
	    'rswpbs_book_review_meta_box_cb',    // Callback function
	    'book_reviews',                // Post type
	    'advanced',                       // Context
	    'default'                     // Priority
	);
}
add_action('add_meta_boxes', 'rswpbs_book_review_meta_box');

function rswpbs_book_review_meta_box_cb($post) {
	wp_nonce_field('rswpbs_save_book_review_meta_box', 'rswpbs_book_review_meta_nonce_field');

	// get the current values of the custom fields
	$reviewer_name = get_post_meta($post->ID, '_rswpbs_reviewer_name', true);
	$reviewer_email = get_post_meta($post->ID, '_rswpbs_reviewer_email', true);
	$rating = get_post_meta($post->ID, '_rswpbs_rating', true);
	$reviewed_book = get_post_meta($post->ID, '_rswpbs_reviewed_book', true);
	$bookQueryArgs = array(
			'post_type'	=> 'book',
			'posts_per_page' => -1,
			// 'post__not_in'	=> array($reviewed_book),
		);
	$getBooks = new WP_Query($bookQueryArgs);

	// output the form fields
	?>
	<div class="rswpbs-row">
		<div class="rswpbs-col-lg-3">
			<div class="rswpbs-review-cmb-field">
				<label for="reviewed_book"><?php esc_html_e( 'This Review Was Submitted in:', 'rswpbs' );?></label><br>
				<select name="reviewed_book" id="reviewed_book">
					<option value="--"><?php echo esc_html_e( 'Select A Book', 'rswpbs' );?></option>
					<?php
					while($getBooks->have_posts()) :
						$getBooks->the_post();
						$bookID = get_the_ID();
						?>
						<option value="<?php echo esc_attr($bookID);?>" <?php selected($bookID, $reviewed_book); ?>>
							<?php echo esc_html( get_the_title() );?>
						</option>
						<?php
					endwhile;
					?>
				</select>
				<?php
				if (!empty($reviewed_book)) :
				?>
				<p><strong><?php esc_html_e( 'Visit Book: ', 'rswpbs' );?></strong><a href="<?php echo get_the_permalink($reviewed_book);?>"><?php echo get_the_title( $reviewed_book );?></a></p>
				<?php endif; ?>
			</div>
		</div>
		<div class="rswpbs-col-lg-3">
			<div class="rswpbs-review-cmb-field">
				<label for="name"><?php esc_html_e( 'Full Name:', 'rswpbs' );?></label><br>
				<input type="text" name="reviewer_name" id="name" value="<?php echo esc_attr($reviewer_name); ?>">
			</div>
		</div>
		<div class="rswpbs-col-lg-3">
			<div class="rswpbs-review-cmb-field">
				<label for="email"><?php esc_html_e( 'Email Address:', 'rswpbs' );?></label><br>
				<input type="email" name="reviewer_email" id="email" value="<?php echo esc_attr($reviewer_email); ?>">
			</div>
		</div>
		<div class="rswpbs-col-lg-3">
			<div class="rswpbs-review-cmb-field">
				<label for="rating"><?php esc_html_e( 'Rating:', 'rswpbs' );?></label><br>
				<select name="rating" id="rating">
					<option value="1" <?php selected($rating, 1); ?>>1</option>
					<option value="2" <?php selected($rating, 2); ?>>2</option>
					<option value="3" <?php selected($rating, 3); ?>>3</option>
					<option value="4" <?php selected($rating, 4); ?>>4</option>
					<option value="5" <?php selected($rating, 5); ?>>5</option>
				</select>
			</div>
		</div>
	</div>
	<?php
}


function rswpbs_save_book_review_meta_box($post_id) {
	// check for nonce
	if (!isset($_POST['rswpbs_book_review_meta_nonce_field']) || !wp_verify_nonce($_POST['rswpbs_book_review_meta_nonce_field'], 'rswpbs_save_book_review_meta_box')) {
		return;
	}
	// check for permissions
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}
	// save the custom fields
	if (isset($_POST['reviewed_book'])) {
		update_post_meta($post_id, '_rswpbs_reviewed_book', intval($_POST['reviewed_book']));
	}
	if (isset($_POST['reviewer_name'])) {
		update_post_meta($post_id, '_rswpbs_reviewer_name', sanitize_text_field($_POST['reviewer_name']));
	}
	if (isset($_POST['reviewer_email'])) {
		update_post_meta($post_id, '_rswpbs_reviewer_email', sanitize_email($_POST['reviewer_email']));
	}
	if (isset($_POST['rating'])) {
		update_post_meta($post_id, '_rswpbs_rating', intval($_POST['rating']));
	}
}
add_action('save_post', 'rswpbs_save_book_review_meta_box');
