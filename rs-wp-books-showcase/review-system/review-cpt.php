<?php
/**
 * Book Review Post Type
 */
function rswpbs_reg_book_reviews_post_type() {
  register_post_type('book_reviews', array(
    'labels' => array(
      'name' => 'Book Reviews',
      'singular_name' => 'Book Review',
      'add_new' => 'Add New',
      'add_new_item' => 'Add New Book Review',
      'edit_item' => 'Edit Book Review',
      'new_item' => 'New Book Review',
      'view_item' => 'View Book Review',
      'search_items' => 'Search Book Reviews',
      'not_found' => 'No book reviews found',
      'not_found_in_trash' => 'No book reviews found in trash',
      'parent_item_colon' => 'Parent Book Review:',
      'all_items' => 'All Book Reviews',
      'archives' => 'Book Reviews Archives',
      'insert_into_item' => 'Insert into book review',
      'uploaded_to_this_item' => 'Uploaded to this book review',
      'featured_image' => 'Set Reviewer Image',
      'set_featured_image' => 'Set Reviewer image',
      'remove_featured_image' => 'Remove Reviewer image',
      'use_featured_image' => 'Use as Reviewer image',
      'menu_name' => 'Book Reviews',
      'filter_items_list' => 'Filter book reviews list',
      'items_list_navigation' => 'Book reviews list navigation',
      'items_list' => 'Book reviews list',
      'name_admin_bar' => 'Book Review',
    ),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'book-reviews'),
    'supports' => array('title', 'editor', 'author', 'thumbnail'),
    'show_in_rest' => true,
    'menu_position' => 7,
    'menu_icon' => 'dashicons-star-filled',
  ));
}
add_action('init', 'rswpbs_reg_book_reviews_post_type');


function rswpbs_register_book_reviews_custom_fields() {
    register_rest_field('book_reviews', 'meta_data', [
        'get_callback'    => 'rswpbs_get_book_reviews_custom_fields',
        'update_callback' => null,
        'schema'          => null,
    ]);
}

function rswpbs_get_book_reviews_custom_fields($object) {
    return [
        'reviewer_name'  => get_post_meta($object['id'], '_rswpbs_reviewer_name', true),
        'reviewer_email' => get_post_meta($object['id'], '_rswpbs_reviewer_email', true),
        'rating'         => get_post_meta($object['id'], '_rswpbs_rating', true),
        'reviewed_book'  => get_post_meta($object['id'], '_rswpbs_reviewed_book', true),
    ];
}

add_action('rest_api_init', 'rswpbs_register_book_reviews_custom_fields');
