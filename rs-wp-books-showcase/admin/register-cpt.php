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
			'name'                  => _x( 'Books', 'Book', 'rswpbs' ),
			'singular_name'         => _x( 'Books Showcase', 'Books', 'rswpbs' ),
			'menu_name'             => __( 'RS WP Books Showcase', 'rswpbs' ),
			'name_admin_bar'        => __( 'RS WP Books Showcase', 'rswpbs' ),
			'archives'              => __( 'Book Archives', 'rswpbs' ),
			'attributes'            => __( 'Book Attributes', 'rswpbs' ),
			'parent_item_colon'     => __( 'Parent Book:', 'rswpbs' ),
			'all_items'             => __( 'All Books', 'rswpbs' ),
			'add_new_item'          => __( 'Add New Book', 'rswpbs' ),
			'add_new'               => __( 'Add New Book', 'rswpbs' ),
			'new_item'              => __( 'New Book', 'rswpbs' ),
			'edit_item'             => __( 'Edit Book', 'rswpbs' ),
			'update_item'           => __( 'Update Book', 'rswpbs' ),
			'view_item'             => __( 'View Book', 'rswpbs' ),
			'view_items'            => __( 'View Books', 'rswpbs' ),
			'search_items'          => __( 'Search Book', 'rswpbs' ),
			'not_found'             => __( 'Not found', 'rswpbs' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'rswpbs' ),
			'featured_image'        => __( 'Book Front Cover', 'rswpbs' ),
			'set_featured_image'    => __( 'Set Book Front Cover', 'rswpbs' ),
			'remove_featured_image' => __( 'Remove featured image', 'rswpbs' ),
			'use_featured_image'    => __( 'Use as featured image', 'rswpbs' ),
			'insert_into_item'      => __( 'Insert into item', 'rswpbs' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'rswpbs' ),
			'items_list'            => __( 'Items list', 'rswpbs' ),
			'items_list_navigation' => __( 'Items list navigation', 'rswpbs' ),
			'filter_items_list'     => __( 'Filter items list', 'rswpbs' ),
		);
		$args = array(
			'label'                 => __( 'Books Showcase', 'rswpbs' ),
			'description'           => __( 'Click on any book cover to learn more.', 'rswpbs' ),
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
			'name'                       => _x( 'Book Categories', 'Book Categories', 'rswpbs' ),
			'singular_name'              => _x( 'Book Category', 'Book Category', 'rswpbs' ),
			'menu_name'                  => __( 'Book Categories', 'rswpbs' ),
			'all_items'                  => __( 'All Book Categories', 'rswpbs' ),
			'parent_item'                => __( 'Parent Item', 'rswpbs' ),
			'parent_item_colon'          => __( 'Parent Item:', 'rswpbs' ),
			'new_item_name'              => __( 'New Item Name', 'rswpbs' ),
			'add_new_item'               => __( 'Add New Item', 'rswpbs' ),
			'edit_item'                  => __( 'Edit Item', 'rswpbs' ),
			'update_item'                => __( 'Update Item', 'rswpbs' ),
			'view_item'                  => __( 'View Item', 'rswpbs' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'rswpbs' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'rswpbs' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'rswpbs' ),
			'popular_items'              => __( 'Popular Items', 'rswpbs' ),
			'search_items'               => __( 'Search Items', 'rswpbs' ),
			'not_found'                  => __( 'Not Found', 'rswpbs' ),
			'no_terms'                   => __( 'No items', 'rswpbs' ),
			'items_list'                 => __( 'Items list', 'rswpbs' ),
			'items_list_navigation'      => __( 'Items list navigation', 'rswpbs' ),
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
			'name'                       => _x( 'Book Series', 'Book Series', 'rswpbs' ),
			'singular_name'              => _x( 'Book series', 'Book series', 'rswpbs' ),
			'menu_name'                  => __( 'Book Series', 'rswpbs' ),
			'all_items'                  => __( 'All Book Series', 'rswpbs' ),
			'parent_item'                => __( 'Parent Item', 'rswpbs' ),
			'parent_item_colon'          => __( 'Parent Item:', 'rswpbs' ),
			'new_item_name'              => __( 'New Item Name', 'rswpbs' ),
			'add_new_item'               => __( 'Add New Item', 'rswpbs' ),
			'edit_item'                  => __( 'Edit Item', 'rswpbs' ),
			'update_item'                => __( 'Update Item', 'rswpbs' ),
			'view_item'                  => __( 'View Item', 'rswpbs' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'rswpbs' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'rswpbs' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'rswpbs' ),
			'popular_items'              => __( 'Popular Items', 'rswpbs' ),
			'search_items'               => __( 'Search Items', 'rswpbs' ),
			'not_found'                  => __( 'Not Found', 'rswpbs' ),
			'no_terms'                   => __( 'No items', 'rswpbs' ),
			'items_list'                 => __( 'Items list', 'rswpbs' ),
			'items_list_navigation'      => __( 'Items list navigation', 'rswpbs' ),
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
		$columns['title']	= __( 'Book Name', 'rswpbs' );
		$columns['taxonomy-book-author']	= __( 'Book Author', 'rswpbs' );
		$columns['taxonomy-book-author']	= $columns['taxonomy-book-author'];
		$columns['taxonomy-book-series']	= __( 'Book Series', 'rswpbs' );
		$columns['taxonomy-book-series']	= $columns['taxonomy-book-series'];
		$columns['taxonomy-book-category']	= __( 'Book Categories', 'rswpbs' );
		$columns['taxonomy-book-category']	= $columns['taxonomy-book-category'];
		$columns['date']	= __( 'Date', 'rswpbs' );
		$columns['date']	= $columns['date'];
		return $columns;
	}

}

