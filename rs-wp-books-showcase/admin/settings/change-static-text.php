<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RSWPBS_Static_Texts {
    private static $instance = null;
    private $settings_free = [];
    private $settings_pro = [];
    private $settings = [];
    private $acf_fields = [];



    /**
     * Singleton Pattern - Get Instance
     */
    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor - Hooks Initialization
     */
    private function __construct() {
        $this->init_settings();
        $this->init_acf_fields();

        add_action( 'admin_menu', [ $this, 'add_settings_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    /**
     * Add Settings Page to Admin Menu
     */
    public function add_settings_menu() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        add_submenu_page(
            'edit.php?post_type=book',
            esc_html__( 'Translations', 'rswpbs' ),
            esc_html__( 'Translations', 'rswpbs' ),
            'manage_options',
            'rswpbs-settings-static-text',
            [ $this, 'render_settings_page' ]
        );
    }

    /**
     * Register Settings
     */
    public function register_settings() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        foreach ( $this->settings as $key => $default ) {
            register_setting(
                'rswpbs_static_texts_settings_group',
                $key,
                [
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => $default,
                ]
            );
        }
    }

    /**
     * Render the Settings Page
     */
    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'rswpbs' ) );
        }
        $is_pro_active = rswpbs_is_pro_active();
        $proclass = ($is_pro_active) ? ' rswpbs-pro-active' : '';
        ?>
        <div class="wrap rswpbs-static-text-change-tab">
            <h1><?php esc_html_e( 'RS WP Book Showcase Translations', 'rswpbs' ); ?></h1>
            <?php
            // If you use tabs, ensure the function exists.
            if ( function_exists( 'rswpbs_settings_tabs' ) ) {
                rswpbs_settings_tabs( 'static_texts' );
            }
            ?>
            <form method="post" action="options.php">
                <?php settings_fields( 'rswpbs_static_texts_settings_group' ); ?>
                <table class="form-table rswpbs-settings-table<?php echo esc_attr($proclass);?>">
                    <tbody>
                        <?php
                        $counter = 0;
                        foreach ( $this->settings as $key => $default ) {
                            $value = get_option( $key, $default );

                            // Check if this setting is a Pro feature
                            $is_pro_setting = isset($this->settings_pro[$key]);
                            $disabled_attr = ($is_pro_setting && ! $is_pro_active) ? 'disabled' : '';
                            $proclass = ($is_pro_setting && ! $is_pro_active) ? 'pro-feature' : '';

                            // Open a new row every 4 items
                            if ($counter % 4 === 0) {
                                echo '<tr>';
                            }

                            echo '<td class="rswpbs-box '.$proclass.'" style="vertical-align: top; padding: 10px;">';
                            $label = ucwords( str_replace( '_', ' ', str_replace( 'rswpbs_', '', $key ) ) );

                            // Input field (Pro settings will be disabled if Pro is not active)
                            echo '<label for="' . esc_attr( $key ) . '">Text: "' . esc_html( $default ) . '"</label><br>';
                            echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text" ' . $disabled_attr . ' />';

                            // Show upgrade notice **only for Pro settings**
                            if ($is_pro_setting && ! $is_pro_active) {
                                echo '<a href="'.esc_url('https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/').'" target="_blank">🔒 This is a Pro feature. Upgrade to unlock.</a>';
                            }

                            echo '</td>';

                            $counter++;

                            // Close the row every 4 items
                            if ($counter % 4 === 0) {
                                echo '</tr>';
                            }
                        }

                        // Close any unclosed row
                        if ($counter % 4 !== 0) {
                            echo '</tr>';
                        }
                        ?>


                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <style>
            .rswpbs-settings-table {
                width: 100%;
                border-collapse: collapse;
            }
            .rswpbs-settings-table td input {
                margin-left: 0;
            }
            .rswpbs-settings-table td {
                padding: 15px 20px;
                border: 1px solid #ddd;
                text-align: left;
                background: #f9f9f9;
                border-radius: 5px;
            }
            .rswpbs-box {
                padding: 10px;
                background: #ffffff;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
            }
        </style>
        <?php
    }

    /**
     * Initialize Settings with Default Values
     */
    private function init_settings() {


        $this->settings_free = [
            'rswpbs_text_by' => 'By',
            'rswpbs_text_books' => 'Books',
            'rswpbs_text_books_by' => 'Books By',
            'rswpbs_text_view_book' => 'View Book',
            'rswpbs_text_load_more' => 'Load More',
            'rswpbs_text_add_to_cart' => 'Add To Cart',
            'rswpbs_text_price' => 'Price:',
            'rswpbs_text_also_available_on' => 'Also Available At',
            'rswpbs_text_all_formats_editions' => 'All Formats & Editions',
            'rswpbs_text_availability' => 'Availability',
            'rswpbs_text_original_title' => 'Original Title',
            'rswpbs_text_categories' => 'Categories',
            'rswpbs_text_publish_date' => 'Publish Date',
            'rswpbs_text_published_year' => 'Published Year',
            'rswpbs_text_publisher_name' => 'Publisher Name',
        ];
        $this->settings_pro = [
            'rswpbs_text_total_pages' => 'Total Pages',
            'rswpbs_text_isbn' => 'ISBN',
            'rswpbs_text_isbn_10' => 'ISBN 10',
            'rswpbs_text_isbn_13' => 'ISBN 13',
            'rswpbs_text_asin' => 'ASIN',
            'rswpbs_text_country' => 'Country',
            'rswpbs_text_translator' => 'Translator',
            'rswpbs_text_language' => 'Language',
            'rswpbs_text_format' => 'Format',
            'rswpbs_text_dimension' => 'Dimension',
            'rswpbs_text_weight' => 'Weight',
            'rswpbs_text_average_ratings' => 'Average Ratings',
            'rswpbs_text_file_size' => 'File Size',
            'rswpbs_text_file_format' => 'File Format',
            'rswpbs_text_series' => 'Series',
            'rswpbs_text_all_series' => 'All Series',
            'rswpbs_text_all_authors' => 'All Authors',
            'rswpbs_text_all_publishers' => 'All Publishers',
            'rswpbs_text_all_categories' => 'All Categories',
            'rswpbs_text_all_formats' => 'All Formats',
            'rswpbs_text_all_years' => 'All Years',
            'rswpbs_text_search' => 'Search',
            'rswpbs_text_simultaneous_device_usage' => 'Simultaneous Device Usage',
            'rswpbs_text_screen_reader' => 'Screen Reader',
            'rswpbs_text_book_name' => 'Book Name',
            'rswpbs_text_word_wise' => 'Word Wise',
            'rswpbs_text_print_length'  => 'Print Length',
            'rswpbs_text_sticky_notes'  => 'Sticky Notes',
            'rswpbs_text_x_ray' => 'X-Ray',
            'rswpbs_text_enhanced_typesetting'  => 'Enhanced Typesetting',
            'rswpbs_text_text_to_speech'    => 'Text-To-Speech',
            'rswpbs_text_submit_your_review' => 'Submit Your Review',
            'rswpbs_text_not_allowed_for_review' => 'You are not allowed to submit a review. Please',
            'rswpbs_text_log_in' => 'Log In',
            'rswpbs_text_review_title' => 'Review Title:',
            'rswpbs_text_full_name' => 'Full Name:',
            'rswpbs_text_email_address' => 'Email Address:',
            'rswpbs_text_rating' => 'Rating:',
            'rswpbs_text_review' => 'Review:',
            'rswpbs_text_submit' => 'Submit',
            'rswpbs_text_readers_feedback' => 'Readers Feedback',
        ];

        $this->settings = array_merge($this->settings_free, $this->settings_pro);
    }

    /**
     * Initialize ACF Migration Fields (ALL FIELDS INCLUDED)
     */
    private function init_acf_fields() {
        $this->acf_fields = [
            'text_by'                       => 'rswpbs_text_by',
            'text_books'                    => 'rswpbs_text_books',
            'text_books_by'                 => 'rswpbs_text_books_by',
            'text_view_book'                => 'rswpbs_text_view_book',
            'text_load_more'                => 'rswpbs_text_load_more',
            'text_add_to_cart'              => 'rswpbs_text_add_to_cart',
            'text_price'                    => 'rswpbs_text_price',
            'text_also_available_on'        => 'rswpbs_text_also_available_on',
            'text_all_formats_&_editions'   => 'rswpbs_text_all_formats_editions',
            'text_availability'             => 'rswpbs_text_availability',
            'text_original_title'           => 'rswpbs_text_original_title',
            'text_categories'               => 'rswpbs_text_categories',
            'text_publish_date'             => 'rswpbs_text_publish_date',
            'text_published_year'           => 'rswpbs_text_published_year',
            'text_publisher_name'           => 'rswpbs_text_publisher_name',
            'text_total_pages'              => 'rswpbs_text_total_pages',
            'text_isbn'                     => 'rswpbs_text_isbn',
            'text_isbn_10'                  => 'rswpbs_text_isbn_10',
            'text_isbn_13'                  => 'rswpbs_text_isbn_13',
            'text_asin'                     => 'rswpbs_text_asin',
            'text_country'                  => 'rswpbs_text_country',
            'text_translator'               => 'rswpbs_text_translator',
            'text_language'                 => 'rswpbs_text_language',
            'text_format'                   => 'rswpbs_text_format',
            'text_dimension'                => 'rswpbs_text_dimension',
            'text_weight'                   => 'rswpbs_text_weight',
            'text_avarage_ratings'          => 'rswpbs_text_average_ratings',
            'text_file_size'                => 'rswpbs_text_file_size',
            'text_file_format'              => 'rswpbs_text_file_format',
            'text_simultaneous_device_usage'=> 'rswpbs_text_simultaneous_device_usage',
            'text_text-to-speech'           => 'rswpbs_text_text_to_speech',
            'text_screen_reader'            => 'rswpbs_text_screen_reader',
            'text_enhanced_typesetting'     => 'rswpbs_text_enhanced_typesetting',
            'text_x-ray'                    => 'rswpbs_text_x_ray',
            'text_word_wise'                => 'rswpbs_text_word_wise',
            'text_sticky_notes'             => 'rswpbs_text_sticky_notes',
            'text_print_length'             => 'rswpbs_text_print_length',
            'text_book_name'                => 'rswpbs_text_book_name',
            'text_all_authors'              => 'rswpbs_text_all_authors',
            'text_all_publishers'           => 'rswpbs_text_all_publishers',
            'text_all_categories'           => 'rswpbs_text_all_categories',
            'text_all_formats'              => 'rswpbs_text_all_formats',
            'text_all_years'                => 'rswpbs_text_all_years',
            'text_search'                   => 'rswpbs_text_search',
            'text_submit_your_review'       => 'rswpbs_text_submit_your_review',
            'text_not_allowed_for_review'   => 'rswpbs_text_not_allowed_for_review',
            'text_log_in'                   => 'rswpbs_text_log_in',
            'text_review_title'             => 'rswpbs_text_review_title',
            'text_full_name'                => 'rswpbs_text_full_name',
            'text_email_address'            => 'rswpbs_text_email_address',
            'text_rating'                   => 'rswpbs_text_rating',
            'text_review'                   => 'rswpbs_text_review',
            'text_submit'                   => 'rswpbs_text_submit',
            'text_readers_feedback'         => 'rswpbs_text_readers_feedback',
            'text_series'                   => 'rswpbs_text_series',
            'text_all_series'               => 'rswpbs_text_all_series',
        ];
    }
}

// Initialize the Class
RSWPBS_Static_Texts::get_instance();
