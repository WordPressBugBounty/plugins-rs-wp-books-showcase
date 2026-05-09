<?php
/**
 * Publisher Migration Class
 * Handles database updates for the Book Publisher taxonomy.
 */

if (!defined('ABSPATH')) {
	exit;
}

class Rswpbs_Publisher_Migration
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		add_action('admin_notices', array($this, 'migration_notice'));
		add_action('admin_menu', array($this, 'migration_menu'));
		add_action('wp_ajax_rswpbs_process_publisher_migration', array($this, 'process_migration'));
	}

	/**
	 * Display migration notice if database update is required.
	 */
	public function migration_notice()
	{
		if (get_option('rswpbs_publisher_db_version') === '1.0') {
			return;
		}

		$migration_url = admin_url('admin.php?page=rswpbs-publisher-migration');
		?>
		<div class="rswpbs-migration notice notice-error is-dismissible">
			<p><strong>⚠️ <?php _e('Action Required: Database Update for RS WP Book Showcase.', 'rswpbs'); ?></strong></p>
			<p>
				<?php _e('RS WP Book Showcase requires a database update to upgrade Book Publishers. Please run the updater to ensure your data is correctly migrated to the new taxonomy system.', 'rswpbs'); ?>
			</p>
			<p>
				<a href="<?php echo esc_url($migration_url); ?>" class="button button-primary">
					<?php _e('Click here to run the updater', 'rswpbs'); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Register the migration menu page.
	 */
	public function migration_menu()
	{
		$hook = add_submenu_page(
			'',
			__('Publisher Migration', 'rswpbs'),
			__('Publisher Migration', 'rswpbs'),
			'manage_options',
			'rswpbs-publisher-migration',
			array($this, 'migration_page_callback')
		);

		// টাইটেল null ইস্যু ফিক্স করার জন্য load hook ব্যবহার করা হলো
		add_action("load-$hook", array($this, 'set_migration_page_title'));
	}

	/**
	 * Set global title to prevent strip_tags(null) deprecated warning
	 */
	public function set_migration_page_title()
	{
		global $title;
		$title = __('Publisher Migration', 'rswpbs');
	}
	/**
	 * Migration page callback.
	 */
	public function migration_page_callback()
	{
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e('Book Publisher Migration', 'rswpbs'); ?></h1>
			<hr class="wp-header-end">

			<div class="card rswpbs-migration-card"
				style="max-width: 800px; margin-top: 20px; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); background: #fff; border: 1px solid #ccd0d4;">
				<h2 style="margin-top: 0;"><?php _e('Database Update Required', 'rswpbs'); ?></h2>
				<p style="font-size: 14px; line-height: 1.5; color: #50575e;">
					<?php _e('We have introduced a new dedicated taxonomy for Book Publishers. To ensure your existing data remains organized and searchable, we need to migrate your current publisher information into this new system.', 'rswpbs'); ?>
				</p>
				<p style="font-size: 14px; line-height: 1.5; color: #50575e;">
					<?php _e('This process may take a few moments depending on the number of books in your collection. Please do not close this page until the migration is complete.', 'rswpbs'); ?>
				</p>

				<div id="rswpbs-migration-ui" style="margin-top: 30px;">
					<div id="rswpbs-migration-inner-ui">
						<button id="rswpbs-start-migration" class="button button-primary button-hero">
							<?php _e('Start Migration', 'rswpbs'); ?>
						</button>

						<div id="rswpbs-progress-container" style="display: none; margin-top: 40px;">
							<div class="progress-outer"
								style="background: #f0f0f1; border-radius: 10px; height: 16px; width: 100%; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);">
								<div id="rswpbs-progress-inner"
									style="background: linear-gradient(90deg, #2271b1 0%, #3598db 100%); height: 100%; width: 0%; transition: width 0.4s ease; border-radius: 10px;">
								</div>
							</div>
							<div style="display: flex; justify-content: space-between; margin-top: 15px; font-weight: 500;">
								<span id="rswpbs-migration-status" style="color: #2c3338;">
									<?php _e('Processing 0 out of X books...', 'rswpbs'); ?>
								</span>
								<span id="rswpbs-migration-percentage" style="color: #2271b1;">0%</span>
							</div>
						</div>
					</div>
				</div>

				<div id="rswpbs-migration-complete"
					style="display: none; margin-top: 30px; padding: 20px; background: #edfaef; border-left: 4px solid #46b450; border-radius: 4px;">
					<p style="margin: 0; color: #1e4620; font-weight: 500;">
						<?php _e('Migration successfully completed! Your database is now up to date.', 'rswpbs'); ?>
					</p>
				</div>
			</div>
		</div>

		<script>
			jQuery(document).ready(function ($) {
				function runMigration(offset) {
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'rswpbs_process_publisher_migration',
							offset: offset,
							security: '<?php echo wp_create_nonce("rswpbs_migration_nonce"); ?>'
						},
						success: function (response) {
							if (response.success) {
								var data = response.data;
								var percentage = data.percentage || 0;
								var status = data.status || '';

								$('#rswpbs-progress-inner').css('width', percentage + '%');
								$('#rswpbs-migration-percentage').text(percentage + '%');
								$('#rswpbs-migration-status').text(status);

								if (!data.is_complete) {
									runMigration(data.next_offset);
								} else {
									$('#rswpbs-migration-inner-ui').hide();
									$('#rswpbs-migration-complete').fadeIn();
									$('<div style="margin-top: 30px;"><a href="<?php echo admin_url(); ?>" class="button button-secondary button-hero"><?php _e("Return to Dashboard", "rswpbs"); ?></a></div>').insertAfter('#rswpbs-migration-complete');
								}
							} else {
								alert('Migration failed: ' + (response.data || 'Unknown error'));
								$('#rswpbs-start-migration').prop('disabled', false).text('<?php _e("Retry Migration", "rswpbs"); ?>');
							}
						},
						error: function () {
							alert('Migration failed due to a server error.');
							$('#rswpbs-start-migration').prop('disabled', false).text('<?php _e("Retry Migration", "rswpbs"); ?>');
						}
					});
				}

				$('#rswpbs-start-migration').on('click', function () {
					var $btn = $(this);
					$btn.prop('disabled', true).text('<?php _e('Migrating...', 'rswpbs'); ?>');
					$('#rswpbs-progress-container').fadeIn();

					runMigration(0);
				});
			});
		</script>
		<?php
	}
	/**
	 * AJAX handler for processing the publisher migration.
	 */
	public function process_migration()
	{
		check_ajax_referer('rswpbs_migration_nonce', 'security');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('Unauthorized access.', 'rswpbs'));
		}

		$offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
		$batch_size = 10;

		$args = array(
			'post_type' => 'book',
			'posts_per_page' => $batch_size,
			'offset' => $offset,
			'fields' => 'ids',
			'post_status' => 'any',
			'ignore_sticky_posts' => true,
			'orderby' => 'ID',
			'order' => 'ASC',
		);

		$query = new WP_Query($args);
		$post_ids = $query->posts;
		$total_posts = intval($query->found_posts);

		if (!empty($post_ids)) {
			foreach ($post_ids as $post_id) {
				$publisher_name = get_post_meta($post_id, '_rsbs_book_publisher_name', true);

				if (!empty($publisher_name)) {
					$term = get_term_by('name', $publisher_name, 'book-publisher');

					if (!$term) {
						$term_result = wp_insert_term($publisher_name, 'book-publisher');
						if (!is_wp_error($term_result)) {
							$term_id = $term_result['term_id'];
						} else {
							continue;
						}
					} else {
						$term_id = $term->term_id;
					}

					if (isset($term_id)) {
						wp_set_object_terms($post_id, intval($term_id), 'book-publisher');
					}
				}
			}

			$next_offset = $offset + $batch_size;
			$percentage = min(100, round(($next_offset / $total_posts) * 100));
			$status = sprintf(__('Processing %d out of %d books...', 'rswpbs'), min($next_offset, $total_posts), $total_posts);

			wp_send_json_success(array(
				'is_complete' => false,
				'next_offset' => $next_offset,
				'percentage' => $percentage,
				'status' => $status,
			));
		} else {
			update_option('rswpbs_publisher_db_version', '1.0');
			wp_send_json_success(array(
				'is_complete' => true,
				'percentage' => 100,
				'status' => __('Migration finished!', 'rswpbs'),
			));
		}
	}
}

new Rswpbs_Publisher_Migration();
