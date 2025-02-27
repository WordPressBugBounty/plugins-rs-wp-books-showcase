<?php
/**
 * Register Custom Post Type
 */
class Rswpbs_Register_Book_Post_Type {
	private $post_type = 'book';
	public function __construct(){
		add_action( 'init', array($this, 'register_book_post_type'));
		add_action( 'init', array($this, 'register_book_cat_taxonomy'));
		add_action( 'init', array($this, 'register_book_series_taxonomy'));
		add_action( 'init', array($this, 'register_book_author_taxonomy'));
		add_filter( 'manage_book_posts_columns', array($this, 'book_showcase_custom_column') );
	}
	/**
	 * Register Book Post Type
	 */
	public function register_book_post_type(){
			$labels = array(
			'name'                  => _x( 'Books', 'Book', RSWPBS_TEXT_DOMAIN ),
			'singular_name'         => _x( 'Books Showcase', 'Books', RSWPBS_TEXT_DOMAIN ),
			'menu_name'             => __( 'RS WP Books Showcase', RSWPBS_TEXT_DOMAIN ),
			'name_admin_bar'        => __( 'RS WP Books Showcase', RSWPBS_TEXT_DOMAIN ),
			'archives'              => __( 'Book Archives', RSWPBS_TEXT_DOMAIN ),
			'attributes'            => __( 'Book Attributes', RSWPBS_TEXT_DOMAIN ),
			'parent_item_colon'     => __( 'Parent Book:', RSWPBS_TEXT_DOMAIN ),
			'all_items'             => __( 'All Books', RSWPBS_TEXT_DOMAIN ),
			'add_new_item'          => __( 'Add New Book', RSWPBS_TEXT_DOMAIN ),
			'add_new'               => __( 'Add New Book', RSWPBS_TEXT_DOMAIN ),
			'new_item'              => __( 'New Book', RSWPBS_TEXT_DOMAIN ),
			'edit_item'             => __( 'Edit Book', RSWPBS_TEXT_DOMAIN ),
			'update_item'           => __( 'Update Book', RSWPBS_TEXT_DOMAIN ),
			'view_item'             => __( 'View Book', RSWPBS_TEXT_DOMAIN ),
			'view_items'            => __( 'View Books', RSWPBS_TEXT_DOMAIN ),
			'search_items'          => __( 'Search Book', RSWPBS_TEXT_DOMAIN ),
			'not_found'             => __( 'Not found', RSWPBS_TEXT_DOMAIN ),
			'not_found_in_trash'    => __( 'Not found in Trash', RSWPBS_TEXT_DOMAIN ),
			'featured_image'        => __( 'Book Front Cover', RSWPBS_TEXT_DOMAIN ),
			'set_featured_image'    => __( 'Set Book Front Cover', RSWPBS_TEXT_DOMAIN ),
			'remove_featured_image' => __( 'Remove featured image', RSWPBS_TEXT_DOMAIN ),
			'use_featured_image'    => __( 'Use as featured image', RSWPBS_TEXT_DOMAIN ),
			'insert_into_item'      => __( 'Insert into item', RSWPBS_TEXT_DOMAIN ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', RSWPBS_TEXT_DOMAIN ),
			'items_list'            => __( 'Items list', RSWPBS_TEXT_DOMAIN ),
			'items_list_navigation' => __( 'Items list navigation', RSWPBS_TEXT_DOMAIN ),
			'filter_items_list'     => __( 'Filter items list', RSWPBS_TEXT_DOMAIN ),
		);
		$args = array(
			'label'                 => __( 'Books Showcase', RSWPBS_TEXT_DOMAIN ),
			'description'           => __( 'Click on any book cover to learn more.', RSWPBS_TEXT_DOMAIN ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'author' ),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'menu_icon'     		=> RSWPBS_PLUGIN_URL . 'admin/assets/icon/book-icon.png',
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'exclude_from_search'   => false,
			'has_archive'           => false,
			'show_in_rest'           => true,
			'publicly_queryable'    => true,
			'capability_type'       => array('book', 'books'),
			// 'rewrite' 				=> array('slug' => 'books'),
			'map_meta_cap'			=> true,
		);

		register_post_type( $this->post_type , $args );
	}

	/**
	 * Register Book Categories
	 */
	public function register_book_cat_taxonomy(){
			$labels = array(
			'name'                       => _x( 'Book Categories', 'Book Categories', RSWPBS_TEXT_DOMAIN ),
			'singular_name'              => _x( 'Book Category', 'Book Category', RSWPBS_TEXT_DOMAIN ),
			'menu_name'                  => __( 'Book Categories', RSWPBS_TEXT_DOMAIN ),
			'all_items'                  => __( 'All Book Categories', RSWPBS_TEXT_DOMAIN ),
			'parent_item'                => __( 'Parent Item', RSWPBS_TEXT_DOMAIN ),
			'parent_item_colon'          => __( 'Parent Item:', RSWPBS_TEXT_DOMAIN ),
			'new_item_name'              => __( 'New Item Name', RSWPBS_TEXT_DOMAIN ),
			'add_new_item'               => __( 'Add New Item', RSWPBS_TEXT_DOMAIN ),
			'edit_item'                  => __( 'Edit Item', RSWPBS_TEXT_DOMAIN ),
			'update_item'                => __( 'Update Item', RSWPBS_TEXT_DOMAIN ),
			'view_item'                  => __( 'View Item', RSWPBS_TEXT_DOMAIN ),
			'separate_items_with_commas' => __( 'Separate items with commas', RSWPBS_TEXT_DOMAIN ),
			'add_or_remove_items'        => __( 'Add or remove items', RSWPBS_TEXT_DOMAIN ),
			'choose_from_most_used'      => __( 'Choose from the most used', RSWPBS_TEXT_DOMAIN ),
			'popular_items'              => __( 'Popular Items', RSWPBS_TEXT_DOMAIN ),
			'search_items'               => __( 'Search Items', RSWPBS_TEXT_DOMAIN ),
			'not_found'                  => __( 'Not Found', RSWPBS_TEXT_DOMAIN ),
			'no_terms'                   => __( 'No items', RSWPBS_TEXT_DOMAIN ),
			'items_list'                 => __( 'Items list', RSWPBS_TEXT_DOMAIN ),
			'items_list_navigation'      => __( 'Items list navigation', RSWPBS_TEXT_DOMAIN ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'           => true,
			'capabilities' => array(
			    'manage_terms' => 'manage_book_category',
			    'edit_terms' => 'edit_book_category',
			    'delete_terms' => 'delete_book_category',
			    'assign_terms' => 'assign_book_category',
			),
		);
		register_taxonomy( 'book-category', array( $this->post_type ), $args );
	}
	/**
	 * Register Book Series Taxonomy
	 */
	public function register_book_series_taxonomy(){
			$labels = array(
			'name'                       => _x( 'Book Series', 'Book Series', RSWPBS_TEXT_DOMAIN ),
			'singular_name'              => _x( 'Book series', 'Book series', RSWPBS_TEXT_DOMAIN ),
			'menu_name'                  => __( 'Book Series', RSWPBS_TEXT_DOMAIN ),
			'all_items'                  => __( 'All Book Series', RSWPBS_TEXT_DOMAIN ),
			'parent_item'                => __( 'Parent Item', RSWPBS_TEXT_DOMAIN ),
			'parent_item_colon'          => __( 'Parent Item:', RSWPBS_TEXT_DOMAIN ),
			'new_item_name'              => __( 'New Item Name', RSWPBS_TEXT_DOMAIN ),
			'add_new_item'               => __( 'Add New Item', RSWPBS_TEXT_DOMAIN ),
			'edit_item'                  => __( 'Edit Item', RSWPBS_TEXT_DOMAIN ),
			'update_item'                => __( 'Update Item', RSWPBS_TEXT_DOMAIN ),
			'view_item'                  => __( 'View Item', RSWPBS_TEXT_DOMAIN ),
			'separate_items_with_commas' => __( 'Separate items with commas', RSWPBS_TEXT_DOMAIN ),
			'add_or_remove_items'        => __( 'Add or remove items', RSWPBS_TEXT_DOMAIN ),
			'choose_from_most_used'      => __( 'Choose from the most used', RSWPBS_TEXT_DOMAIN ),
			'popular_items'              => __( 'Popular Items', RSWPBS_TEXT_DOMAIN ),
			'search_items'               => __( 'Search Items', RSWPBS_TEXT_DOMAIN ),
			'not_found'                  => __( 'Not Found', RSWPBS_TEXT_DOMAIN ),
			'no_terms'                   => __( 'No items', RSWPBS_TEXT_DOMAIN ),
			'items_list'                 => __( 'Items list', RSWPBS_TEXT_DOMAIN ),
			'items_list_navigation'      => __( 'Items list navigation', RSWPBS_TEXT_DOMAIN ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_in_rest'              => true,
			'show_tagcloud'              => true,
			'capabilities' => array(
			    'manage_terms' => 'manage_book_series',
			    'edit_terms' => 'edit_book_series',
			    'delete_terms' => 'delete_book_series',
			    'assign_terms' => 'assign_book_series',
			),
		);
		register_taxonomy( 'book-series', array( $this->post_type ), $args );
	}
	public function register_book_author_taxonomy(){
			$labels = array(
			'name'                       => _x( 'Book Authors', 'Book Authors', 'rs-books-gallery' ),
			'singular_name'              => _x( 'Book Author', 'Book Author', 'rs-books-gallery' ),
			'menu_name'                  => __( 'Book Authors', 'rs-books-gallery' ),
			'all_items'                  => __( 'All Book Authors', 'rs-books-gallery' ),
			'parent_item'                => __( 'Parent Book Author', 'rs-books-gallery' ),
			'parent_item_colon'          => __( 'Parent Book Author:', 'rs-books-gallery' ),
			'new_item_name'              => __( 'New Book Author Name', 'rs-books-gallery' ),
			'add_new_item'               => __( 'Add New Book Author', 'rs-books-gallery' ),
			'edit_item'                  => __( 'Edit Book Author', 'rs-books-gallery' ),
			'update_item'                => __( 'Update Book Author', 'rs-books-gallery' ),
			'view_item'                  => __( 'View Book Author', 'rs-books-gallery' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'rs-books-gallery' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'rs-books-gallery' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'rs-books-gallery' ),
			'popular_items'              => __( 'Popular Book Authors', 'rs-books-gallery' ),
			'search_items'               => __( 'Search Items', 'rs-books-gallery' ),
			'not_found'                  => __( 'Not Found', 'rs-books-gallery' ),
			'no_terms'                   => __( 'No items', 'rs-books-gallery' ),
			'items_list'                 => __( 'Items list', 'rs-books-gallery' ),
			'items_list_navigation'      => __( 'Items list navigation', 'rs-books-gallery' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_in_rest'          => true,
			'show_tagcloud'              => true,
			'capabilities' => array(
			    'manage_terms' => 'manage_book_author',
			    'edit_terms' => 'edit_book_author',
			    'delete_terms' => 'delete_book_author',
			    'assign_terms' => 'assign_book_author',
			)
		);
		register_taxonomy( 'book-author', array( $this->post_type ), $args );
	}
	public function book_showcase_custom_column($columns)
	{
		unset(
			$columns['title'],
			$columns['date'],
			$columns['taxonomy-book-category'],
			$columns['taxonomy-book-author'],
			$columns['taxonomy-book-series']
		);
		$columns['title']	= __( 'Book Name', RSWPBS_TEXT_DOMAIN );
		$columns['taxonomy-book-author']	= __( 'Book Author', RSWPBS_TEXT_DOMAIN );
		$columns['taxonomy-book-author']	= $columns['taxonomy-book-author'];
		$columns['taxonomy-book-series']	= __( 'Book Series', RSWPBS_TEXT_DOMAIN );
		$columns['taxonomy-book-series']	= $columns['taxonomy-book-series'];
		$columns['taxonomy-book-category']	= __( 'Book Categories', RSWPBS_TEXT_DOMAIN );
		$columns['taxonomy-book-category']	= $columns['taxonomy-book-category'];
		$columns['date']	= __( 'Date', RSWPBS_TEXT_DOMAIN );
		$columns['date']	= $columns['date'];
		return $columns;
	}

}

