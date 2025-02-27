<?php
if (wp_is_block_theme()) {
  return;
}

add_filter('template_include', 'rswpbs_archive_template');

function rswpbs_archive_template( $template ) {

  if ( is_post_type_archive('book') ) {
    $theme_files = array('archive-book.php', 'rsbs-templates/archive-book.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return RSWPBS_PLUGIN_PATH . 'rsbs-templates/archive-book.php';
    }
  }
  if(is_tax( 'book-author' )){
    $theme_files = array('taxonomy-book-author.php', 'rsbs-templates/taxonomy-book-author.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return RSWPBS_PLUGIN_PATH . 'rsbs-templates/taxonomy-book-author.php';
    }
  }
  if(is_tax( 'book-category' )){
    $theme_files = array('taxonomy-book-category.php', 'rsbs-templates/taxonomy-book-category.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return RSWPBS_PLUGIN_PATH . 'rsbs-templates/taxonomy-book-category.php';
    }
  }
  if(is_tax( 'book-series' )){
    $theme_files = array('taxonomy-book-series.php', 'rsbs-templates/taxonomy-book-series.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return RSWPBS_PLUGIN_PATH . 'rsbs-templates/taxonomy-book-series.php';
    }
  }
  return $template;
}

function rswpbs_load_book_template( $template ) {
    global $post;
    if ( 'book' === $post->post_type ) {
        $theme_files = array('single-book.php', 'rsbs-templates/single-book.php');
        $exists_in_theme = locate_template($theme_files, false);
        if ( $exists_in_theme != '' ) {
          return $exists_in_theme;
        } else {
          return RSWPBS_PLUGIN_PATH . 'rsbs-templates/single-book.php';
        }
    }

    return $template;
}

add_filter( 'single_template', 'rswpbs_load_book_template' );


add_action( 'rswpbs_author_taxonomy_page_header', 'rswpbs_author_taxonomy_page_header_author_info', 10 );

function rswpbs_author_taxonomy_page_header_author_info(){
  $currentPageId = get_queried_object_id();
  if (0 === $currentPageId ) {
    return;
  }
  $currentAuthorObj = get_term($currentPageId);
  $descriptions = $currentAuthorObj->description;
  $headingClass = '';
  if (empty($descriptions)) {
    $headingClass = 'mb-0';
  }
  ?>
    <div class="row">
      <div class="col-md-12">
        <div class="rswpthemes-book-showcase-page-title">
          <h1 class="rswpthemes-book-author-name <?php echo esc_attr($headingClass);?>">
            <?php
            echo esc_html($currentAuthorObj->name);
            ?>
          </h1>
          <div class="author-details">
            <p><?php echo wp_kses_post($currentAuthorObj->description); ?></p>
          </div>
        </div>
      </div>
    </div>
  <?php
}
/**
 * Function rswpbs_archive_page_header For Showing Book Archive Page Header
 */
add_action('rswpbs_archive_before_book_loop', 'rswpbs_archive_page_header', 10);
function rswpbs_archive_page_header(){
  $showArchiveHeader = true;
  $archivePageTitle = '';
  $archivePageDesc = '';
  if (class_exists('Rswpbs_Pro')) :
    $showArchiveHeader = rswpbs_show_archie_page_header();
    $showArchiveHeader = ($showArchiveHeader === NULL || $showArchiveHeader === true) ? true : false;
    $archivePageTitle = rswpbs_archive_page_title();
    $archivePageDesc = rswpbs_archive_page_description();
  endif;
  $headingClass = '';
  if (true == $showArchiveHeader) :
    if (empty($ArchivePageDesc)) {
      $headingClass = 'mb-0';
    }
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="rswpthemes-book-showcase-page-title">
          <?php
          if (!empty($archivePageTitle)) {
            echo '<h1 class='.$headingClass.'>'. esc_html($archivePageTitle) .'</h1>';
          }else{
            echo '<h1 class='.$headingClass.'>'. post_type_archive_title('', false) .'</h1>';
          }
          if (!empty($archivePageDesc)) {
            echo '<p>'. $archivePageDesc .'</p>';
          }else{
            echo get_the_post_type_description();
          }
          ?>
        </div>
      </div>
    </div>
    <?php
  endif;
}