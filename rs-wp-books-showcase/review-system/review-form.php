<?php
/**
 * Check Users Review Submit Permision.
 */
function rswpbs_check_reviewer_permision(){
    $getReviewPermission = false;
    if (class_exists('Rswpbs_Pro') && function_exists('get_field')) {
      $getReviewPermission = get_field('submit_review_without_login', 'option');
    }
    $allowLoggedOutUserToSubmitReview = false;
    if ( $getReviewPermission === true ) {
        $allowLoggedOutUserToSubmitReview = true;
    } elseif ( $getReviewPermission === false ) {
        if (is_user_logged_in()) {
          $allowLoggedOutUserToSubmitReview = true;
        }else{
          $allowLoggedOutUserToSubmitReview = false;
        }
    }
    return $allowLoggedOutUserToSubmitReview;
}

/**
 * Review Form For Front End
 */
add_shortcode( 'rswpbs_review_form', 'rswpbs_review_form_callback' );
function rswpbs_review_form_callback(){
  ob_start();
  global $post;
  $currentPostId = $post->ID;
  $currentUserName = '';
  $currentUserEmail = '';
  if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $currentUserName = $current_user->display_name;
    $currentUserEmail = $current_user->user_email;
  }
  ?>
  <div class="rswpbs-review-form-wrapper">
  	<div class="review-section-title">
	    <h3><?php echo rswpbs_static_text_submit_your_review(); ?>
      <?php
      if ( false === rswpbs_check_reviewer_permision() ) {
        echo sprintf('<small>%1$s <a href="%3$s">%2$s</a></small>',
          rswpbs_static_text_not_allowed_for_review(),
          rswpbs_static_text_log_in(),
          esc_url(wp_login_url()),
        );
      }
      ?>
      </h3>
	</div>
    <form action="" id="rswpbs-review-form">
      <?php wp_nonce_field( 'rswpbs_submit_review', '_rswpbs_submit_review_nonce' ); ?>
      <div class="current-post-id">
        <input type="hidden" name="current_post_id" value="<?php echo esc_attr($currentPostId);?>">
      </div>
      <div class="rswpbs-review-form-inner rswpbs-row">
        <div class="rswpbs-review-form-field rswpbs-col-md-4 mb-4">
        	<label for="review_title"><?php echo rswpbs_static_text_review_title(); ?></label>
            <input class="form-control" type="text" name="review_title" id="review_title" required>
        </div>
        <div class="rswpbs-review-form-field rswpbs-col-md-4 mb-4">
        	<label for="reviewer_name"><?php echo rswpbs_static_text_full_name(); ?></label>
            <input class="form-control" value="<?php echo esc_attr($currentUserName);?>" type="text" name="reviewer_name" id="reviewer_name" required>
        </div>
        <div class="rswpbs-review-form-field rswpbs-col-md-4 mb-4">
        	<label for="reviewer_email"><?php echo rswpbs_static_text_email_address(); ?></label>
            <input class="form-control" value="<?php echo esc_attr($currentUserEmail);?>" id="reviewer_email" type="email" name="reviewer_email" required>
        </div>
        <div class="rswpbs-review-form-field rswpbs-col-md-12 mb-4">
          <label for="rating"><?php echo rswpbs_static_text_rating(); ?></label>
           <span class="stars">
		        <i class="fa-regular fa-star" data-value="1"></i>
		        <i class="fa-regular fa-star" data-value="2"></i>
		        <i class="fa-regular fa-star" data-value="3"></i>
		        <i class="fa-regular fa-star" data-value="4"></i>
		        <i class="fa-regular fa-star" data-value="5"></i>
		        <input type="hidden" name="rating" id="rating" value="" required>
	      </span>
        </div>
        <div class="rswpbs-review-form-field rswpbs-col-md-12 mb-4">
          <label for="reviewer_comment"><?php echo rswpbs_static_text_review(); ?></label>
          <textarea class="form-control" id="reviewer_comment" name="reviewer_comment" cols="30" rows="5" required></textarea>
        </div>
        <div class="rswpbs-review-form-field rswpbs-col-md-12 mb-4">
          <input type="submit" value="<?php echo rswpbs_static_text_submit();?>" class="submit-btn">
        </div>
      </div>
    </form>
  </div>
  <?php
  return ob_get_clean();
}

/**
 * Review Form Ajax Handler
 */

add_action('wp_ajax_nopriv_rswpbs_submit_review_form', 'rswpbs_submit_review_form');
add_action('wp_ajax_rswpbs_submit_review_form', 'rswpbs_submit_review_form');

function rswpbs_submit_review_form(){
    $formFeilds = array();
    wp_parse_str( $_POST['rswpbs_submit_review_form'], $formFeilds );
    if ( true === rswpbs_check_reviewer_permision() && wp_verify_nonce( $formFeilds['_rswpbs_submit_review_nonce'], 'rswpbs_submit_review' ) ) {
      $review_title = sanitize_text_field($formFeilds['review_title']);
        $reviewer_name = sanitize_text_field($formFeilds['reviewer_name']);
        $reviewer_email = sanitize_email($formFeilds['reviewer_email']);
        $rating = intval($formFeilds['rating']);
        $reviewer_comment = wp_kses_post($formFeilds['reviewer_comment']);
        $currentPostId = intval($formFeilds['current_post_id']);
        if (empty($review_title)) {
          $error = 'Please enter your name.';
        } elseif (empty($reviewer_name)) {
          $error = 'Please enter your name.';
        } elseif (empty($reviewer_email)) {
          $error = 'Please enter your email.';
        } elseif (!is_email($reviewer_email)) {
          $error = 'Please enter a valid email.';
        } elseif (empty($rating) || $rating < 1 || $rating > 5) {
          $error = 'Please enter a valid rating.';
        } elseif (empty($reviewer_comment)) {
          $error = 'Please enter your review.';
        }
        $review = array(
            'post_title' => $review_title,
            'post_content' => $reviewer_comment,
            'post_type' => 'book_reviews',
            'post_status' => 'pending',
            'meta_input' => array(
              '_rswpbs_reviewer_name' => $reviewer_name,
              '_rswpbs_reviewer_email' => $reviewer_email,
              '_rswpbs_rating' => $rating,
              '_rswpbs_reviewed_book' => $currentPostId,
          )
        );
        $post_id = wp_insert_post($review);
        if ($post_id) {
            wp_send_json_success(array('message' => 'Your review has been submitted and is pending approval.'));
        }else{
            wp_send_json_error(array('message' => $error));
        }

    }else{
      wp_send_json_error( array(
            'message' => 'You are not allowed to submit review please login and then submit review',
          ) );
      wp_die('Invalid request');
    }
  wp_die();
}

add_action('rswpbs_book_page_after', 'rswpbs_book_review_form', 15);
function rswpbs_book_review_form(){
  $showReviewForm = true;
  if (class_exists('Rswpbs_Pro')) {
    $showReviewForm = get_field('show_review_form', 'option');
  }else{
    $showReviewForm = true;
  }
  if ( true === $showReviewForm ) :
    ?>
    <div class="rswpbs-book-review-form-area">
      <div class="rswpbs-container">
        <div class="rswpbs-row">
          <div class="rswpbs-col-md-12">
            <?php echo do_shortcode('[rswpbs_review_form]'); ?>
          </div>
        </div>
      </div>
    </div>
    <?php
  endif;
}