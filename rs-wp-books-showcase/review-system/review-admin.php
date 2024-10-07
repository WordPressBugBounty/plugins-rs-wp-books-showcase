<?php
// Step 5: add a custom column to the "Book Reviews" admin screen
function add_book_review_status_column($columns) {
  unset($columns['date']);
  unset($columns['author']);
  $columns['reviewed_book'] = 'Reviewed Book';
  $columns['rating'] = 'Rating';
  $columns['status'] = 'Review Status';
  $columns['date'] = 'Submited On';
  return $columns;
}
add_filter('manage_book_reviews_posts_columns', 'add_book_review_status_column');
// add_filter('manage_edit-book_reviews_sortable_columns', 'add_book_review_status_column');
function add_book_review_status_column_content($column, $post_id) {
  if ($column == 'status') {
    $status = get_post_status($post_id);
    if ($status == 'pending') {
      $actions = array(
        'approve' => '<a href="' . admin_url("admin-post.php?action=approve_review&post=$post_id") . '">Approve</a>',
      );
      echo '<strong>Pending</strong>';
      echo '<br>';
      echo implode(' | ', $actions);
    } else {
      echo '<strong>Approved</strong>';
    }
  }elseif ($column == 'reviewed_book') {
    $review_book_id = get_post_meta( $post_id, '_rswpbs_reviewed_book', true );
    if (!empty($review_book_id)) :
      ?>
        <a href="<?php echo get_the_permalink($review_book_id);?>"><?php echo get_the_title( $review_book_id );?></a>
      <?php
    endif;
  }elseif( $column == 'rating' ){
    $book_rating = get_post_meta($post_id, '_rswpbs_rating', true);
    for ($i = 1; $i <= $book_rating; $i++) {
      echo '<span class="dashicons dashicons-star-filled"></span>';
    }
  }
}
add_action('manage_book_reviews_posts_custom_column', 'add_book_review_status_column_content', 10, 2);


// Step 6: handle the "approve_review" action
function rswpbs_approve_book_review() {
	ob_start(); // start output buffering

	$post_id = intval($_GET['post']);
	$review = get_post($post_id);
	if ($review->post_type == 'book_reviews' && $review->post_status == 'pending') {
	    wp_update_post(array(
	      'ID' => $post_id,
	      'post_status' => 'publish'
	    ));
	}
	wp_redirect(admin_url('edit.php?post_type=book_reviews'));
	exit;

	ob_end_flush(); // send the output buffer and turn off output buffering
}
add_action('admin_post_approve_review', 'rswpbs_approve_book_review');
