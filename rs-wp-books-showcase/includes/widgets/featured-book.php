<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Adds widget: Rs Author Info Box
class Rswpbs_Featured_Books_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'featured_books',
			esc_html__( '[ RS WP THEMES ] Featured Books', 'rswpbs' )
		);

	}
	private $widget_fields = array(
			array(
				'label' => 'Book Slug',
				'id' => 'book_slug',
				'type' => 'text',
			),
		);

	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		$bookSlug = $instance['book_slug'];
		$featuredBookArgs = array();
		if (!empty($bookSlug)) {
			$featuredBookArgs = array(
				'name'           => $bookSlug,
			    'post_type'      => 'book',
			    'post_status'    => 'publish',
			    'posts_per_page' => 1
			);
		}
		$getBook = get_posts($featuredBookArgs);
		?>
		<div class="rswpthemes-featured-book-area-wrapper">
			<?php
			if ($getBook) :
				foreach($getBook as $book) :
					$bookImage = rswpbs_get_book_image($book->ID);
					$bookName = rswpbs_get_book_name($book->ID);
					$bookAuthor = rswpbs_get_book_author($book->ID);
					$external_website_lists = rswpbs_ext_website_list($book->ID);

			?>
			<div class="rswpthemes-featured-book-area-inner">
				<div class="book-image">
					<a href="<?php echo esc_url(get_the_permalink($book->ID)); ?>"><?php echo wp_kses_post($bookImage); ?></a>
				</div>
				<div class="book-name">
					<h2><a href="<?php echo esc_url(get_the_permalink($book->ID));?>"><?php echo esc_html($bookName);?></a></h2>
				</div>
				<div class="book-author">
					<h4><strong><?php echo esc_html(rswpbs_static_text_by()); ?></strong> <?php echo wp_kses_post($bookAuthor); ?> </h4>
				</div>
				<?php
				if(!empty(rswpbs_get_book_buy_btn($book->ID))) : ?>
				<div class="rswpthemes-featured-book-purchase-button">
					<?php echo rswpbs_get_book_buy_btn($book->ID); ?>
				</div>
				<?php endif;?>
			</div>
			<?php
			endforeach;
		endif; ?>
		</div>
		<?php
		echo $args['after_widget'];
		wp_reset_postdata();
	}


	public function field_generator( $instance ) {

		$widget_fields = $this->widget_fields;
		foreach ( $widget_fields as $widget_field ) {

			$default = '';
			if ( isset($widget_field['default']) ) {
				$default = $widget_field['default'];
			}
			$widget_value = ! empty( $instance[$widget_field['id']] ) ? $instance[$widget_field['id']] : '';
			switch ( $widget_field['type'] ) {
				default:
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) ); ?>"><?php echo esc_html( $widget_field['label'] ); ?></label>
						<input class="widefat" type="text" name="<?php echo esc_attr($this->get_field_name($widget_field['id']));?>" id="<?php echo esc_attr($this->get_field_id($widget_field['id'])); ?>" value="<?php echo esc_attr($widget_value);?>">
					</p>
					<?php
			}
		}
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'rswpbs' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		$this->field_generator( $instance );

	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$widget_fields = $this->widget_fields;
		foreach ($widget_fields as $widget_field ) {
			switch ( $widget_field['type'] ) {
				default:
					$instance[$widget_field['id']] = ( ! empty( $new_instance[$widget_field['id']] ) ) ? sanitize_text_field( $new_instance[$widget_field['id']] ) : '';
			}
		}
		return $instance;
	}
}

function rswpbs_featured_books_widget_register() {
	register_widget( 'Rswpbs_Featured_Books_Widget' );
}
add_action( 'widgets_init', 'rswpbs_featured_books_widget_register' );