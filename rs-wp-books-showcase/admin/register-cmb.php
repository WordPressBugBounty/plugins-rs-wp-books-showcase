<?php
/**
 * Register Custom Meta Box For Book Post Type
 */

class Rswpbs_Cmb_For_Book
{

	private $prefix = '_rsbs_';
	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'register_meta_boxes_for_book'));
		add_action('save_post', array($this, 'book_information_save'));
	}

	public function register_meta_boxes_for_book()
	{
		/**
		 * Added New Meta Box For Book Information
		 */
		add_meta_box(
			'book_information',
			esc_html__( 'Book Information', 'rswpbs' ),
			array($this, 'book_information_fields_render'),
			'book',
			'advanced',
			'high'
		);
	}
	/**
	 * Pro Feild Tag
	 */
	public function book_pro_field()
	{
		if (!class_exists('Rswpbs_Pro')) {
			?>
			<div class="set_overlay_for_pro_field">
				<div class="pro_tag_text">
					<a target="_blank" href="<?php echo esc_url('https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/'); ?>" class="pro_badge"><?php esc_html_e('Upgrade To Pro', 'rswpbs'); ?></a>
				</div>
			</div>
			<?php
		}
		return;
	}
	/**
	 * Creating Book Information Meta Box Fields
	 */
	public function book_information_fields_render($post)
	{
		wp_nonce_field( 'Book_Information_Data', 'Book_Information_Nonce' );
		$original_book_name = get_post_meta( $post->ID, $this->prefix . 'original_book_name', true );
		$original_book_url = get_post_meta( $post->ID, $this->prefix . 'original_book_url', true );
		$book_name = get_post_meta( $post->ID, $this->prefix . 'book_name', true );
		$book_price = str_replace('$', '', get_post_meta( $post->ID, $this->prefix . 'book_price', true ));
		$book_sale_price = str_replace('$', '', get_post_meta( $post->ID, $this->prefix . 'book_sale_price', true ));
		$book_isbn = get_post_meta( $post->ID, $this->prefix . 'book_isbn', true );
		$book_asin = get_post_meta( $post->ID, $this->prefix . 'book_asin', true );
		$book_isbn_10 = get_post_meta( $post->ID, $this->prefix . 'book_isbn_10', true );
		$book_isbn_13 = get_post_meta( $post->ID, $this->prefix . 'book_isbn_13', true );
		$book_translator = get_post_meta( $post->ID, $this->prefix . 'book_translator', true );
		$book_file_size = get_post_meta( $post->ID, $this->prefix . 'book_file_size', true );
		$simultaneous_device_usage = get_post_meta( $post->ID, $this->prefix . 'simultaneous_device_usage', true );
		$book_file_format = get_post_meta( $post->ID, $this->prefix . 'book_file_format', true );
		$book_text_to_speech = get_post_meta( $post->ID, $this->prefix . 'book_text_to_speech', true );
		$screen_reader = get_post_meta( $post->ID, $this->prefix . 'screen_reader', true );
		$enhanced_typesetting = get_post_meta( $post->ID, $this->prefix . 'enhanced_typesetting', true );
		$x_ray = get_post_meta( $post->ID, $this->prefix . 'x_ray', true );
		$word_wise = get_post_meta( $post->ID, $this->prefix . 'word_wise', true );
		$sticky_notes = get_post_meta( $post->ID, $this->prefix . 'sticky_notes', true );
		$print_length = get_post_meta( $post->ID, $this->prefix . 'print_length', true );
		$book_dimension = get_post_meta( $post->ID, $this->prefix . 'book_dimension', true );
		$book_weight = get_post_meta( $post->ID, $this->prefix . 'book_weight', true );
		$book_publish_date = get_post_meta( $post->ID, $this->prefix . 'book_publish_date', true );
		$book_publish_year = get_post_meta( $post->ID, $this->prefix . 'book_publish_year', true );
		$book_publisher_name = get_post_meta( $post->ID, $this->prefix . 'book_publisher_name', true );
		$book_country = get_post_meta( $post->ID, $this->prefix . 'book_country', true );
		$book_language = get_post_meta( $post->ID, $this->prefix . 'book_language', true );
		$book_format = get_post_meta( $post->ID, $this->prefix . 'book_format', true );
		$book_pages = get_post_meta( $post->ID, $this->prefix . 'book_pages', true );
		$short_description = get_post_meta( $post->ID, $this->prefix . 'short_description', true );
		$average_book_rating = get_post_meta( $post->ID, $this->prefix . 'average_book_rating', true );
		$book_availability_status = get_post_meta( $post->ID, $this->prefix . 'book_availability_status', true );
		$total_book_ratings = get_post_meta( $post->ID, $this->prefix . 'total_book_ratings', true );
		$book_rating_links = get_post_meta( $post->ID, $this->prefix . 'book_rating_links', true );
		$book_reading_date = get_post_meta( $post->ID, $this->prefix . 'book_reading_date', true );
		$buy_btn_text = get_post_meta( $post->ID, $this->prefix . 'buy_btn_text', true );
		$buy_btn_link = get_post_meta( $post->ID, $this->prefix . 'buy_btn_link', true );
		$book_reading_age = get_post_meta( $post->ID, $this->prefix . 'book_reading_age', true );
		$book_grade_level = get_post_meta( $post->ID, $this->prefix . 'book_grade_level', true );
		$book_lexile_measure = get_post_meta( $post->ID, $this->prefix . 'book_lexile_measure', true );
		if (class_exists('Rswpbs_Pro')) {
			$buy_btn_shortcode = get_post_meta( $post->ID, $this->prefix . 'buy_btn_shortcode', true );
		}

		$bookCategories = get_terms(
			'book-category',
			array(
				'hide_empty' => false
			)
		);

		$bookAuthors = get_terms(
			'book-author',
			array(
				'hide_empty' => false
			)
		);
	?>
		<div class="book-information-wrapper">
			<div class="rswpbs-row">
				<div class="rswpbs-col-lg-12">
					<div class="rswpbs-row">
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="original-book-name"><?php esc_html_e( 'Original Book Name', 'rswpbs' );?></label>
								<input type="text" name="original_book_name" class="w-100 regular-text" id="original-book-name" value="<?php echo esc_attr($original_book_name);?>" placeholder="Original Book Name">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="original-book-url"><?php esc_html_e( 'Original Book URL', 'rswpbs' );?></label>
								<input type="text" name="original_book_url" class="w-100 regular-text" id="original-book-url" value="<?php echo esc_attr($original_book_url);?>" placeholder="<?php esc_attr_e('Original Book Url', 'rswpbs'); ?>">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-name"><?php esc_html_e( 'Book Name', 'rswpbs' );?></label>
								<input type="text" name="book_name" class="w-100 regular-text" id="book-name" value="<?php echo esc_attr($book_name);?>" placeholder="Book Name">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="publish-date"><?php esc_html_e( 'Publish Date', 'rswpbs' );?></label>
								<input type="date" name="book_publish_date" class="w-100 regular-text" id="publish-date" value="<?php echo esc_attr($book_publish_date);?>">
								<input type="hidden" name="book_publish_year" class="w-100 regular-text" id="publish-year" value="<?php echo esc_attr($book_publish_year);?>">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="publisher-name"><?php esc_html_e( 'Publisher Name', 'rswpbs' );?></label>
								<input type="text" name="book_publisher_name" class="w-100 regular-text" id="publisher-name" value="<?php echo esc_attr($book_publisher_name);?>" placeholder="Publisher Name">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="reading-age"><?php esc_html_e( 'Reading Age', 'rswpbs' );?></label>
								<input type="text" name="book_reading_age" class="w-100 regular-text" id="reading-age" value="<?php echo esc_attr($book_reading_age);?>" placeholder="Example: 8+ years">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="grade-level"><?php esc_html_e( 'Grade level', 'rswpbs' );?></label>
								<input type="text" name="book_grade_level" class="w-100 regular-text" id="grade-level" value="<?php echo esc_attr($book_grade_level);?>" placeholder="Example: 3 - 6">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="lexile-measure"><?php esc_html_e( 'Lexile measure', 'rswpbs' );?></label>
								<input type="text" name="book_lexile_measure" class="w-100 regular-text" id="lexile-measure" value="<?php echo esc_attr($book_lexile_measure);?>" placeholder="Example: 880L">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="publish-country"><?php esc_html_e( 'Country', 'rswpbs' );?></label>
								<input type="text" name="book_country" class="w-100 regular-text" id="book-country" value="<?php echo esc_attr($book_country); ?>" placeholder="Country">
							</div>
						</div>
						<div class="rswpbs-col-lg-12 mb-20">
							<div class="book-field-container">
								<label for="short-description"><?php esc_html_e( 'Book Short Description', 'rswpbs' );?></label>
								<textarea type="text" rows="5" class="w-100 regular-text" name="short_description" id="short-description" value="<?php echo esc_attr($short_description); ?>" placeholder="Short Description"><?php echo wp_kses_post($short_description); ?></textarea>
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="publish-language"><?php esc_html_e( 'Book language', 'rswpbs' );?></label>
								<input type="text" name="book_language" class="w-100 regular-text" id="book-language" value="<?php echo esc_attr($book_language); ?>" placeholder="Book language">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-format"><?php esc_html_e( 'Book Format', 'rswpbs' );?></label>
								<input type="text" name="book_format" class="w-100 regular-text" id="book-format" value="<?php echo esc_attr($book_format); ?>" placeholder="Example: Hardcover">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="publish-date"><?php esc_html_e( 'Book Pages', 'rswpbs' );?></label>
								<input type="text" name="book_pages" class="w-100 regular-text" id="book-pages" value="<?php echo esc_attr($book_pages); ?>" placeholder="Book Pages">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-isbn"><?php esc_html_e( 'ISBN', 'rswpbs' );?></label>
								<input type="text" name="book_isbn" class="w-100 regular-text" id="book-isbn" value="<?php echo esc_attr($book_isbn); ?>" placeholder="Book ISBN">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-isbn-10"><?php esc_html_e( 'ISBN-10', 'rswpbs' );?></label>
								<input type="text" name="book_isbn_10" class="w-100 regular-text" id="book-isbn-10" value="<?php echo esc_attr($book_isbn_10); ?>" placeholder="ISBN 10">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-isbn-13"><?php esc_html_e( 'ISBN-13', 'rswpbs' );?></label>
								<input type="text" name="book_isbn_13" class="w-100 regular-text" id="book-isbn-13" value="<?php echo esc_attr($book_isbn_13); ?>" placeholder="ISBN 13">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-translator"><?php esc_html_e( 'Translator Name', 'rswpbs' );?></label>
								<input type="text" name="book_translator" class="w-100 regular-text" id="book-translator" value="<?php echo esc_attr($book_translator); ?>" placeholder="Translator">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-dimension"><?php esc_html_e( 'Dimension', 'rswpbs' );?></label>
								<input type="text" name="book_dimension" class="w-100 regular-text" id="book-dimension" value="<?php echo esc_attr($book_dimension); ?>" placeholder="Book Dimension">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-weight"><?php esc_html_e( 'Weight', 'rswpbs' );?></label>
								<input type="text" name="book_weight" class="w-100 regular-text" id="book-weight" value="<?php echo esc_attr($book_weight); ?>" placeholder="Book Weight">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-file-size"><?php esc_html_e( 'File size (If Ebook)', 'rswpbs' );?></label>
								<input type="text" name="book_file_size" class="w-100 regular-text" id="book-file-size" value="<?php echo esc_attr($book_file_size); ?>" placeholder="File Size">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="simultaneous-device-usage"><?php esc_html_e( 'Simultaneous device usage', 'rswpbs' );?></label>
								<input type="text" name="simultaneous_device_usage" class="w-100 regular-text" id="simultaneous-device-usage" value="<?php echo esc_attr($simultaneous_device_usage); ?>" placeholder="Up to 5 simultaneous devices, per publisher limits">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-file-format"><?php esc_html_e( 'File Format (If Ebook)', 'rswpbs' );?></label>
								<input type="text" name="book_file_format" class="w-100 regular-text" id="book-file-format" value="<?php echo esc_attr($book_file_format); ?>" placeholder="PDF">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-asin"><?php esc_html_e( 'ASIN', 'rswpbs' );?></label>
								<input type="text" name="book_asin" class="w-100 regular-text" id="book-asin" value="<?php echo esc_attr($book_asin); ?>" placeholder="ASIN">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="book-text-to-speech"><?php esc_html_e( 'Text-To-Speech', 'rswpbs' );?></label>
								<select name="book_text_to_speech" class="w-100" id="book-text-to-speech">
									<option value="blank" <?php echo selected( $book_text_to_speech, 'blank', false );?>>
										<?php esc_html_e( 'Choose an option', 'rswpbs' );?>
									</option>
									<option value="enabled" <?php echo selected( $book_text_to_speech, 'enabled', false );?>>
										<?php esc_html_e( 'Enabled', 'rswpbs' );?>
									</option>
									<option value="not_enabled" <?php echo selected( $book_text_to_speech, 'not_enabled', false);?>>
										<?php esc_html_e( 'Not Enabled', 'rswpbs' );?>
									</option>
								</select>
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="screen-reader"><?php esc_html_e( 'Screen Reader', 'rswpbs' );?></label>
								<select name="screen_reader" class="w-100" id="screen-reader">
									<option value="blank" <?php echo selected( $screen_reader, 'blank', false );?>>
										<?php esc_html_e( 'Choose an option', 'rswpbs' );?>
									</option>
									<option value="supported" <?php echo selected( $screen_reader, 'supported', false );?>>
										<?php esc_html_e( 'Supported', 'rswpbs' );?>
									</option>
									<option value="unsupported" <?php echo selected( $screen_reader, 'unsupported', false);?>>
										<?php esc_html_e( 'Unsupported', 'rswpbs' );?>
									</option>
								</select>
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="enhanced-typesetting"><?php esc_html_e( 'Enhanced typesetting', 'rswpbs' );?></label>
								<select name="enhanced_typesetting" class="w-100" id="enhanced-typesetting">
									<option value="blank" <?php echo selected( $enhanced_typesetting, 'blank', false );?>>
										<?php esc_html_e( 'Choose an option', 'rswpbs' );?>
									</option>
									<option value="enabled" <?php echo selected( $enhanced_typesetting, 'enabled', false );?>>
										<?php esc_html_e( 'Enabled', 'rswpbs' );?>
									</option>
									<option value="not_enabled" <?php echo selected( $enhanced_typesetting, 'not_enabled', false);?>>
										<?php esc_html_e( 'Not Enabled', 'rswpbs' );?>
									</option>
								</select>
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="x-ray"><?php esc_html_e( 'X-Ray', 'rswpbs' );?></label>
								<select name="x_ray" class="w-100" id="x-ray">
									<option value="blank" <?php echo selected( $x_ray, 'blank', false );?>>
										<?php esc_html_e( 'Choose an option', 'rswpbs' );?>
									</option>
									<option value="enabled" <?php echo selected( $x_ray, 'enabled', false );?>>
										<?php esc_html_e( 'Enabled', 'rswpbs' );?>
									</option>
									<option value="not_enabled" <?php echo selected( $x_ray, 'not_enabled', false);?>>
										<?php esc_html_e( 'Not Enabled', 'rswpbs' );?>
									</option>
								</select>
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="word-wise"><?php esc_html_e( 'Word Wise', 'rswpbs' );?></label>
								<select name="word_wise" class="w-100" id="word-wise">
									<option value="blank" <?php echo selected( $word_wise, 'blank', false );?>>
										<?php esc_html_e( 'Choose an option', 'rswpbs' );?>
									</option>
									<option value="enabled" <?php echo selected( $word_wise, 'enabled', false );?>>
										<?php esc_html_e( 'Enabled', 'rswpbs' );?>
									</option>
									<option value="not_enabled" <?php echo selected( $word_wise, 'not_enabled', false);?>>
										<?php esc_html_e( 'Not Enabled', 'rswpbs' );?>
									</option>
								</select>
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="sticky-notes"><?php esc_html_e( 'Sticky Notes', 'rswpbs' );?></label>
								<input type="text" name="sticky_notes" class="w-100 regular-text" id="sticky-notes" value="<?php echo esc_attr($sticky_notes); ?>" placeholder="On Kindle Scribe">
							</div>
						</div>
						<div class="rswpbs-col-lg-4 mb-20">
							<div class="book-field-container">
								<label for="print-length"><?php esc_html_e( 'Print length', 'rswpbs' );?></label>
								<input type="text" name="print_length" class="w-100 regular-text" id="print-length" value="<?php echo esc_attr($print_length); ?>" placeholder="128 Pages">
							</div>
						</div>
						<div class="rswpbs-col-lg-3">
							<div class="book-field-container">
								<label for="book-reading-date"><?php esc_html_e( 'Reading Date', 'rswpbs' );?></label>
								<input type="text" name="book_reading_date" class="w-100 regular-text" id="book-reading-date" value="<?php echo esc_attr($book_reading_date); ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="rswpbs-row mb-4">
				<div class="rswpbs-col-lg-3">
					<div class="book-field-container">
						<label for="book-availability-status"><?php esc_html_e( 'Book Availability Status', 'rswpbs' );?></label>
						<select name="book_availability_status" class="w-100" id="book-availability-status">
							<option value="blank" <?php echo selected( $book_availability_status, 'blank', false );?>>
								<?php esc_html_e( 'Choose an option', 'rswpbs' );?>
							</option>
							<option value="available" <?php echo selected( $book_availability_status, 'available', false );?>>
								<?php esc_html_e( 'Available', 'rswpbs' );?>
							</option>
							<option value="upcoming" <?php echo selected( $book_availability_status, 'upcoming', false);?>>
								<?php esc_html_e( 'Upcoming', 'rswpbs' );?>
							</option>
						</select>
					</div>
				</div>
				<div class="rswpbs-col-lg-3">
					<div class="book-field-container">
						<label for="average-book-rating"><?php esc_html_e( 'Average Book Rating', 'rswpbs' );?></label>
						<select name="average_book_rating" class="w-100" id="average-book-rating">
							<option value="nan" <?php echo selected( $average_book_rating, 'nan', false );?>>
								<?php esc_html_e( 'Rate This Book', 'rswpbs' );?>
							</option>
							<option value="5" <?php echo selected( $average_book_rating, '5', false );?>>
								<?php esc_html_e( '5 Star', 'rswpbs' );?>
							</option>
							<option value="4.5" <?php echo selected( $average_book_rating, '4.5', false );?>>
								<?php esc_html_e( '4.5 Star', 'rswpbs' );?>
							</option>
							<option value="4" <?php echo selected( $average_book_rating, '4', false );?>>
								<?php esc_html_e( '4 Star', 'rswpbs' );?>
							</option>
							<option value="3.5" <?php echo selected( $average_book_rating, '3.5', false);?>>
								<?php esc_html_e( '3.5 Star', 'rswpbs' );?>
							</option>
							<option value="3" <?php echo selected( $average_book_rating, '3', false);?>>
								<?php esc_html_e( '3 Star', 'rswpbs' );?>
							</option>
							<option value="2.5" <?php echo selected( $average_book_rating, '2.5', false);?>>
								<?php esc_html_e( '2.5 Star', 'rswpbs' );?>
							</option>
							<option value="2" <?php echo selected( $average_book_rating, '2', false);?>>
								<?php esc_html_e( '2 Star', 'rswpbs' );?>
							</option>
							<option value="1.5" <?php echo selected( $average_book_rating, '1.5', false);?>>
								<?php esc_html_e( '1.5 Star', 'rswpbs' );?>
							</option>
							<option value="1" <?php echo selected( $average_book_rating, '1', false);?>>
								<?php esc_html_e( '1 Star', 'rswpbs' );?>
							</option>
						</select>
					</div>
				</div>
				<div class="rswpbs-col-lg-3">
					<div class="book-field-container">
						<label for="total-book-ratings"><?php esc_html_e( 'Total Book Ratings', 'rswpbs' );?></label>
						<input type="text" name="total_book_ratings" class="w-100 regular-text" id="total-book-ratings" value="<?php echo esc_attr($total_book_ratings); ?>" placeholder="4500">
					</div>
				</div>
				<div class="rswpbs-col-lg-3">
					<div class="book-field-container">
						<label for="book-rating-links"><?php esc_html_e( 'Book Rating Links', 'rswpbs' );?></label>
						<input type="text" name="book_rating_links" class="w-100 regular-text" id="book-rating-links" value="<?php echo esc_attr($book_rating_links); ?>">
					</div>
				</div>

			</div>
			<div class="rswpbs-row">
				<div class="rswpbs-col-lg-12">
					<div class="rswpbs-row">
						<div class="rswpbs-col-lg-6 mb-20">
							<div class="rswpbs-row">
								<div class="rswpbs-col-lg-12 mb-20">
									<div class="book-field-container">
										<label for="book-price"><?php esc_html_e( 'Book Price', 'rswpbs' );?></label>
										<div class="rswpbs-row">
											<?php
											$currenySign = get_option( 'rswpbs_price_currency', '$' );
											?>
											<div class="rswpbs-col-md-6">
												<div class="currency-sign"><?php echo esc_html($currenySign);?></div>
												<input type="text" name="book_price" class="w-100 regular-text" id="book-price" value="<?php echo esc_attr($book_price);?>" placeholder="Book Regular Price">
											</div>
											<div class="rswpbs-col-md-6">
												<div class="currency-sign"><?php echo esc_html($currenySign);?></div>
												<input type="text" name="book_sale_price" class="w-100 regular-text" id="book-sale-price" value="<?php echo esc_attr($book_sale_price);?>" placeholder="Book Sale Price">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="rswpbs-col-lg-6 mb-20">
							<div class="rswpbs-row">
								<div class="rswpbs-col-lg-12 mb-20">
									<div class="book-field-container">
										<div class="rswpbs-row">
											<div class="rswpbs-col-md-6">
												<label for="buy-now-btn-text"><?php esc_html_e( 'Buy Button Text', 'rswpbs' );?></label>
												<input type="text" name="buy_btn_text" class="w-100 regular-text" id="buy-now-btn-text" value="<?php echo esc_attr($buy_btn_text);?>" placeholder="Buy Now">
											</div>
											<div class="rswpbs-col-md-6">
												<label for="buy-now-btn-link"><?php esc_html_e( 'Buy Button Link', 'rswpbs' );?></label>
												<input type="text" name="buy_btn_link" class="w-100 regular-text" id="buy-now-btn-link" value="<?php echo esc_attr($buy_btn_link);?>" placeholder="Book Purchase Link">
											</div>
										</div>
									</div>
								</div>
								<?php
								if (class_exists('Rswpbs_Pro')) :
								?>
								<div class="rswpbs-col-lg-12 mb-20">
									<div class="book-field-container">
										<div class="rswpbs-row">
											<div class="rswpbs-col-md-12">
												<label for="buy-now-btn-link"><?php esc_html_e( 'Buy Button ShortCode', 'rswpbs' );?></label>
												<p><?php esc_html_e( 'Shortcode Data Will Be Shown Instead of Buy Now Button.', 'rswpbs' );?></p>
												<input type="text" name="buy_btn_shortcode" class="w-100 regular-text" id="buy-now-btn-shortcode" value="<?php echo esc_attr($buy_btn_shortcode);?>" placeholder="Book Purchase Link">
											</div>
										</div>
									</div>
								</div>
								<?php
								endif;
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$proFeaturesClass = 'free-active';
			$showFeatureDesc = true;
			if (class_exists('Rswpbs_Pro')) {
				$proFeaturesClass = 'pro-active';
				$showFeatureDesc = false;
			}
			 ?>
			<div class="rswpbs-pro-field-wrapper <?php echo esc_attr($proFeaturesClass);?>">
				<?php
				if (true === $showFeatureDesc) :
				?>
				<div class="rswpbs-pro-field-description">
					<p>The Formats feature in RS WP Book Showcase lets you showcase multiple book formats like eBook, paperback, or audiobook with pricing, purchase links, and images. Whether you're an author selling directly or using affiliate links, this feature makes book listings more engaging, user-friendly, and easy to manage with drag-and-drop sorting.</p>
				</div>
				<?php
				endif;
				?>
				<div class="rswpbs-repeater-field-inner">
					<?php
					echo $this->book_pro_field();
					rswpbs_render_formats_meta_box($post);
					?>
				</div>
			</div>
			<div class="rswpbs-pro-field-wrapper <?php echo esc_attr($proFeaturesClass);?>">
				<?php
				if (true === $showFeatureDesc) :
				?>
				<div class="rswpbs-pro-field-description">
					<p>The Multiple Purchase Links feature in RS WP Book Showcase allows users to add multiple store links for a book, such as Amazon, Barnes & Noble, Apple Books, and Google Play. With image upload, custom sorting, and easy link management, it ensures a seamless buying experience for readers and increases book sales potential.</p>
				</div>
				<?php
				endif;
				?>
				<div class="rswpbs-repeater-field-inner">
					<?php
					echo $this->book_pro_field();
					rswpbs_render_msl_meta_box($post);
					?>
				</div>
			</div>
			<div class="rswpbs-pro-field-wrapper <?php echo esc_attr($proFeaturesClass);?>">
				<?php
				if (true === $showFeatureDesc) :
				?>
				<div class="rswpbs-pro-field-description">
					<p>The Sample Content feature in RS WP Book Showcase Pro allows you to provide book previews in multiple formats, including PDF, images, audio, and video. By clicking the Add New button, you can easily upload and organize sample content, helping visitors explore your book before purchasing. This feature enhances reader engagement and boosts sales.</p>
				</div>
				<?php
				endif;
				?>
				<div class="rswpbs-repeater-field-inner">
					<?php
					echo $this->book_pro_field();
					render_book_sample_content_box($post);
					?>
				</div>
			</div>
			<div class="rswpbs-pro-field-wrapper <?php echo esc_attr($proFeaturesClass);?>">
				<?php
				if (true === $showFeatureDesc) :
				?>
				<div class="rswpbs-pro-field-description">
					<p>The Downloadable Books feature in RS WP Book Showcase Pro integrates WooCommerce digital downloads, allowing authors and publishers to sell books directly from their websites. Whether selling physical books or eBooks, this feature transforms book listings into WooCommerce products, enabling cart, checkout, and order management with any supported payment gateway. Perfect for building a fully functional book store, it offers options for download limits, expiry dates, and secure file delivery—providing a seamless shopping experience for customers.</p>
				</div>
				<?php
				endif;
				?>
				<div class="rswpbs-repeater-field-inner">
					<?php
					echo $this->book_pro_field();
					rswpbs_product_downloadable_meta_box_output($post);
					?>
				</div>
			</div>
		</div>
	<?php
	}

	/**
	 * Saving Book Information Meta Fields Data
	 */
	public function book_information_save($post_id)
	{
		if (! isset($_POST['Book_Information_Nonce'])) {
			return $post_id;
		}
		if (! wp_verify_nonce( $_POST['Book_Information_Nonce'], 'Book_Information_Data' ) ) {
			return $post_id;
		}
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		$meta_fields = array(
			'original_book_name' => (isset($_POST['original_book_name']) ? sanitize_text_field($_POST['original_book_name']) : ''),
			'original_book_url' => (isset($_POST['original_book_url']) ? sanitize_text_field($_POST['original_book_url']) : ''),
			'book_name' => (isset($_POST['book_name']) ? sanitize_text_field($_POST['book_name']) : ''),
			'book_price' => (isset($_POST['book_price']) ? str_replace('$', '', sanitize_text_field($_POST['book_price'])) : ''),
			'book_sale_price' => (isset($_POST['book_sale_price']) ? str_replace('$', '', sanitize_text_field($_POST['book_sale_price'])) : ''),
			'book_country' => (isset($_POST['book_country']) ? sanitize_text_field($_POST['book_country']) : ''),
			'book_language' => (isset($_POST['book_language']) ? sanitize_text_field($_POST['book_language']) : ''),
			'book_publish_date' => (isset($_POST['book_publish_date']) ? sanitize_text_field($_POST['book_publish_date']) : ''),
			'book_publish_year' => (isset($_POST['book_publish_year']) ? sanitize_text_field($_POST['book_publish_year']) : ''),
			'book_publisher_name' => (isset($_POST['book_publisher_name']) ? sanitize_text_field($_POST['book_publisher_name']) : ''),
			'book_reading_age' => (isset($_POST['book_reading_age']) ? sanitize_text_field($_POST['book_reading_age']) : ''),
			'book_grade_level' => (isset($_POST['book_grade_level']) ? sanitize_text_field($_POST['book_grade_level']) : ''),
			'book_lexile_measure' => (isset($_POST['book_lexile_measure']) ? sanitize_text_field($_POST['book_lexile_measure']) : ''),
			'short_description' => (isset($_POST['short_description']) ? wp_kses_post($_POST['short_description']) : ''),
			'book_pages' => (isset($_POST['book_pages']) ? sanitize_text_field($_POST['book_pages']) : ''),
			'book_isbn' => (isset($_POST['book_isbn']) ? sanitize_text_field($_POST['book_isbn']) : ''),
			'book_isbn_10' => (isset($_POST['book_isbn_10']) ? sanitize_text_field($_POST['book_isbn_10']) : ''),
			'book_isbn_13' => (isset($_POST['book_isbn_13']) ? sanitize_text_field($_POST['book_isbn_13']) : ''),
			'book_asin' => (isset($_POST['book_asin']) ? sanitize_text_field($_POST['book_asin']) : ''),
			'book_translator' => (isset($_POST['book_translator']) ? sanitize_text_field($_POST['book_translator']) : ''),
			'book_dimension' => (isset($_POST['book_dimension']) ? sanitize_text_field($_POST['book_dimension']) : ''),
			'book_file_size' => (isset($_POST['book_file_size']) ? sanitize_text_field($_POST['book_file_size']) : ''),
			'simultaneous_device_usage' => (isset($_POST['simultaneous_device_usage']) ? sanitize_text_field($_POST['simultaneous_device_usage']) : ''),
			'book_file_format' => (isset($_POST['book_file_format']) ? sanitize_text_field($_POST['book_file_format']) : ''),
			'book_text_to_speech' => (isset($_POST['book_text_to_speech']) ? sanitize_text_field($_POST['book_text_to_speech']) : ''),
			'screen_reader' => (isset($_POST['screen_reader']) ? sanitize_text_field($_POST['screen_reader']) : ''),
			'enhanced_typesetting' => (isset($_POST['enhanced_typesetting']) ? sanitize_text_field($_POST['enhanced_typesetting']) : ''),
			'x_ray' => (isset($_POST['x_ray']) ? sanitize_text_field($_POST['x_ray']) : ''),
			'word_wise' => (isset($_POST['word_wise']) ? sanitize_text_field($_POST['word_wise']) : ''),
			'sticky_notes' => (isset($_POST['sticky_notes']) ? sanitize_text_field($_POST['sticky_notes']) : ''),
			'print_length' => (isset($_POST['print_length']) ? sanitize_text_field($_POST['print_length']) : ''),
			'book_weight' => (isset($_POST['book_weight']) ? sanitize_text_field($_POST['book_weight']) : ''),
			'book_format' => (isset($_POST['book_format']) ? sanitize_text_field($_POST['book_format']) : ''),
			'book_availability_status' => (isset($_POST['book_availability_status']) ? sanitize_text_field($_POST['book_availability_status']) : ''),
			'total_book_ratings' => (isset($_POST['total_book_ratings']) ? sanitize_text_field($_POST['total_book_ratings']) : ''),
			'average_book_rating' => (isset($_POST['average_book_rating']) ? sanitize_text_field($_POST['average_book_rating']) : ''),
			'book_rating_links' => (isset($_POST['book_rating_links']) ? sanitize_text_field($_POST['book_rating_links']) : ''),
			'book_reading_date' => (isset($_POST['book_reading_date']) ? sanitize_text_field($_POST['book_reading_date']) : ''),
			'buy_btn_text' => (isset($_POST['buy_btn_text']) ? sanitize_text_field($_POST['buy_btn_text']) : ''),
			'buy_btn_link' => (isset($_POST['buy_btn_link']) ? sanitize_text_field($_POST['buy_btn_link']) : ''),
			'buy_btn_shortcode' => (isset($_POST['buy_btn_shortcode']) ? sanitize_text_field($_POST['buy_btn_shortcode']) : ''),
		);

		if (empty($meta_fields['book_sale_price'])) {
			$meta_fields['book_query_price'] = $meta_fields['book_price'];
		}else{
			$meta_fields['book_query_price'] = $meta_fields['book_sale_price'];
		}
		foreach($meta_fields as $key => $value):
			$keyWithPrefix = $this->prefix . $key;
			$prevValue = get_post_meta($post_id, $keyWithPrefix, true);
			if ($prevValue) {
				update_post_meta($post_id, $keyWithPrefix, $value, $prevValue);
			}else{
				add_post_meta( $post_id, $keyWithPrefix, $value );
			}
			if ( !$value) {
				delete_post_meta( $post_id, $keyWithPrefix );
			}
		endforeach;
	}
}