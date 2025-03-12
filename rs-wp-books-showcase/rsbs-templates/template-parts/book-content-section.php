<?php
/**
 * This section contain full book overview
 */

function rswpbs_book_content_section(){
	$publishersQueryLink = rswpbs_static_search_string(array('publisher' => rswpbs_get_book_publisher_name()));
	do_action('rswpbs_before_book_overview_section');
	?>
	<div class="rswpbs-book-overview-section">
		<div class="rswpbs-row">
			<div class="rswpbs-col-lg-8 pl-0">
				<div class="rswpbs-book-overview">
					<?php the_content(); ?>
				</div>
			</div>
			<div class="rswpbs-col-lg-4 pl-0 pr-0">
				<div class="rswpbs-book-information-container">
					<?php
					do_action('rswpbs_before_book_information');
					$availability = rswpbs_get_book_availability_status();
					if (!empty($availability) && 'blank' !== $availability):
					 ?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_availability();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html( $availability );?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_original_name())):
						$originalBookUrl = rswpbs_get_book_original_url();
						$originalBookWrapperStart = '';
						$originalBookWrapperEnd = '';
						if (!empty($originalBookUrl)) {
							$originalBookWrapperStart = '<a href="'.$originalBookUrl.'">';
							$originalBookWrapperEnd = '</a>';
						}
					 ?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_original_title();?></h4>
						</div>
						<div class="information-content">
							<h4>
							<?php
							echo $originalBookWrapperStart;
							echo esc_html( rswpbs_get_book_original_name() );
							echo $originalBookWrapperEnd;
							?>
							</h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_categories())):
					 ?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_categories();?></h4>
						</div>
						<div class="information-content">
							<h4>
								<?php
								echo wp_kses_post(rswpbs_get_book_categories());
								?>
							</h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_series_name())):
					 ?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_series();?></h4>
						</div>
						<div class="information-content">
							<h4><?php
							echo wp_kses_post(rswpbs_get_book_series());
							?></h4>
						</div>
					</div>
					<?php endif;
					if(!empty(rswpbs_get_book_publish_date())) :
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_publish_date();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_publish_date()); ?></h4>
						</div>
					</div>
					<?php endif;
					if(!empty(rswpbs_get_book_publish_date()) && !empty(rswpbs_get_book_publish_year())) :
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_published_year();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_publish_year()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_publisher_name())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_publisher_name();?></h4>
						</div>
						<div class="information-content">
							<h4><a href="<?php echo esc_url( $publishersQueryLink );?>"><?php echo esc_html(rswpbs_get_book_publisher_name()); ?></a></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_pages())) :
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_total_pages();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_pages()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_isbn())) :
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_isbn();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_isbn()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_isbn_10())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_isbn_10();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_isbn_10()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_isbn_13())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_isbn_13();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_isbn_13()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_asin())) :
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_asin();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_asin()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_format())) :
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_format();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_format()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_country())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_country();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_country()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_language())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_language();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_language()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_translator())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_translator();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_translator()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_file_size())) :
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_file_size();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_file_size()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_dimension())) :
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_dimension();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_dimension()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_weight())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_weight();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_weight()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_file_format())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_file_format();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_file_format()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_simultaneous_device_usage())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_simultaneous_device_usage();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_simultaneous_device_usage()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_text_to_speech())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_text_to_speech();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_book_text_to_speech()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_screen_reader())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_screen_reader();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_screen_reader()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_enhanced_typesetting())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_enhanced_typesetting();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_enhanced_typesetting()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_x_ray())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_x_ray();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_x_ray()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_word_wise())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_word_wise();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_word_wise()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_sticky_notes())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_sticky_notes();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_sticky_notes()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_print_length())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_print_length();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo esc_html(rswpbs_get_print_length()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_avg_rate())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_avarage_ratings();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo wp_kses_post(rswpbs_get_avg_rate()); ?></h4>
						</div>
					</div>
					<?php endif;
					if (!empty(rswpbs_get_book_reading_date())):
					?>
					<div class="information-list">
						<div class="information-label">
							<h4><?php echo rswpbs_static_text_reading_date();?></h4>
						</div>
						<div class="information-content">
							<h4><?php echo wp_kses_post(rswpbs_get_book_reading_date()); ?></h4>
						</div>
					</div>
					<?php endif;
					do_action('rswpbs_after_book_information');
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	do_action('rswpbs_after_book_overview_section');
}