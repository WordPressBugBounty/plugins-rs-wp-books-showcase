<?php
/**
 * Full Width Book Slider Shortcode
 */

//[rswpbs_full_width_book_slider show_title="" show_author="true" show_description="true" show_button_one="true" show_button_two="true" show_image="true" read_more_text="See Details"]

add_shortcode('rswpbs_full_width_book_slider', 'rswpbs_full_width_book_slider_shortcode');
function rswpbs_full_width_book_slider_shortcode($atts) {
    // Define default settings
    $default_settings = array(
        'show_title'        => 'true',
        'show_author'       => 'true',
        'show_description'  => 'true',
        'show_button_one'   => 'true',
        'show_button_two'   => 'true',
        'show_image'        => 'true',
        'read_more_text'    => 'View Details',
        'book_ids'          => '',
        'slider_attr' => '',
    );

    // Merge shortcode attributes with default settings
    $settings = shortcode_atts($default_settings, $atts);


    $sliderAttrJson = $settings['slider_attr'];

$attributesString = $sliderAttrJson;

// Convert the string into an associative array
$attributesArray = array_map(
    fn($attribute) => explode('=', $attribute),
    explode('; ', $attributesString)
);

// Build the HTML attributes string
$htmlAttributes = '';
foreach ($attributesArray as $attribute) {
    $htmlAttributes .= $attribute[0] . '="' . esc_attr($attribute[1]) . '" ';
}

// Trim the trailing space
$htmlAttributes = trim($htmlAttributes);




    // Split the book_ids string into an array of IDs
    $bookIds = array_map('intval', explode(',', $settings['book_ids']));

    // Prepare WP_Query arguments
    $args = array(
        'post_type'      => 'book',
        'posts_per_page' => 10, // Fetch all posts
    );

    if (!empty($settings['book_ids'])) {
        $args['post__in'] = $bookIds;
        $args['orderby'] = 'post__in';
    }

// var_dump($args);
    // Run the query
    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        $noBooks = esc_html__( 'No books available to display.', 'rswpbs' );
        return '<p>'.$noBooks.'</p>';
    }

    ob_start();
    ?>
    <div class="awt-full-width-book-slider-section">
        <div class="container awt-full-width-books-slider-wrapper">
            <div class="slider-wrapper featured-book-slider-activate" <?php echo $htmlAttributes;?>>
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="slider-item">
                    <div class="rswpbs-row align-items-center featured-book-item featured-book-row">
                        <div class="rswpbs-col-lg-8 rswpbs-col-md-6 pr-xl-5 align-self-center featured-book-column featured-book-content-column">
                            <div class="featured-book-content-wrapper">
                                <div class="featured-book-content-container">
                                    <?php if ('true' == $settings['show_title']) : ?>
                                    <h2 class="book-title">
                                        <a href="<?php the_permalink(); ?>"><?php echo rswpbs_get_book_name(get_the_ID()); ?></a>
                                    </h2>
                                    <?php endif; ?>

                                    <?php if ('true' == $settings['show_author']) : ?>
                                    <h4 class="book-author">(<?php echo rswpbs_get_book_author(); ?>)</h4>
                                    <?php endif; ?>

                                    <?php if ('true' == $settings['show_description']) : ?>
                                    <p class="book-desc"><?php echo rswpbs_get_excerpt(250, get_the_excerpt()); ?></p>
                                    <?php endif; ?>

                                    <?php if ('true' == $settings['show_button_one'] || 'true' == $settings['show_button_two']) : ?>
                                    <div class="book-buttons">
                                        <?php if ('true' == $settings['show_button_one']) : ?>
                                        <div class="book-buy-btn">
                                            <?php echo rswpbs_get_book_buy_btn(); ?>
                                        </div>
                                        <?php endif;
                                        ?>
                                        <?php if ('true' == $settings['show_button_two']) :
                                            $readMoreText = $atts['read_more_text'];
                                            if (empty($readMoreText)) {
                                                $readMoreText = esc_html__('View Details', 'rswpbs');
                                            }
                                            ?>
                                        <div class="book-details-btn">
                                            <a href="<?php the_permalink(); ?>"><?php echo esc_html( $readMoreText ); ?></a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($settings['show_image']) : ?>
                        <div class="rswpbs-col-lg-4 rswpbs-col-md-6 align-self-center featured-book-column featured-book-cover-column">
                            <div class="featured-book-cover-wrapper">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php

    // Reset post data
    wp_reset_postdata();

    return ob_get_clean();
}

