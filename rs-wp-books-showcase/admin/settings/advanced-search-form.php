<?php
/**
 * Secure Search Form Settings
 */
class RSWPBS_Search_Form_Settings {

    private static $instance = null;
    private static $option_group = 'rswpbs_search_form_settings_group';
    private static $menu_slug = 'rswpbs-settings-search-form';
    private static $settings_page = 'rswpbs_search_form_settings_page';

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'add_settings_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        // add_action('admin_init', [$this, 'migrate_acf_search_form_to_options_once']);
    }

    public function add_settings_menu() {
        add_submenu_page(
            'edit.php?post_type=book',
            __('Search Form', 'rswpbs'),
            __('Search Form', 'rswpbs'),
            'manage_options',
            self::$menu_slug,
            [$this, 'render_settings_page']
        );
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized access.', 'rswpbs'));
        }
        ?>
        <div class="wrap rswpbs-advanced-seacrch-form-settings">
            <h1><?php esc_html_e('Search Form Settings', 'rswpbs'); ?></h1>
            <?php rswpbs_settings_tabs('search_form'); ?>
            <form method="post" action="options.php">
                <?php
                settings_fields(self::$option_group);
                do_settings_sections(self::$settings_page);
                wp_nonce_field(self::$option_group . '_nonce', self::$option_group . '_nonce_field');
                $is_pro_active = function_exists('rswpbs_is_pro_active') && rswpbs_is_pro_active();
                $proClass = ($is_pro_active) ? ' rswpbs-pro-active' : '';
                ?>
                <table class="form-table rswpbs-settings-table<?php echo esc_attr($proClass);?>">
                    <tbody>
                        <tr>
                            <?php
                            $fields = [
                                'rswpbs_show_name_field',
                                'rswpbs_show_category_field',
                                'rswpbs_show_formats_field',
                                'rswpbs_show_years_field',
                                'rswpbs_show_author_field',
                                'rswpbs_show_series_field',
                                'rswpbs_show_publishers_field',
                                'rswpbs_show_language_field',
                                'rswpbs_show_isbn_field',
                                'rswpbs_show_isbn_10_field',
                                'rswpbs_show_reset_icon'
                            ];
                            $count = 0;
                            foreach ($fields as $field) {
                                if ($count % 4 == 0 && $count > 0) {
                                    echo '</tr><tr>';
                                }
                                echo '<td class="rswpbs-box">';
                                $this->render_checkbox_field(['option_name' => $field]);
                                echo '</td>';
                                $count++;
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
                <?php
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        $fields = [
            'rswpbs_show_name_field', 'rswpbs_show_category_field', 'rswpbs_show_author_field',
            'rswpbs_show_formats_field', 'rswpbs_show_years_field', 'rswpbs_show_series_field',
            'rswpbs_show_publishers_field', 'rswpbs_show_language_field', 'rswpbs_show_isbn_field',
            'rswpbs_show_isbn_10_field', 'rswpbs_show_reset_icon',
        ];

        foreach ($fields as $field) {
            register_setting(self::$option_group, $field, [
                'sanitize_callback' => 'absint',
                'default' => 1 // Ensure default is 1 (visible)
            ]);
        }

        add_settings_section(
            'rswpbs_search_form_settings_section',
            __('Search Form Settings', 'rswpbs'),
            '__return_false',
            self::$settings_page
        );
    }

    public function render_checkbox_field($args) {
        $option_name = $args['option_name'];
        $is_pro_active = function_exists('rswpbs_is_pro_active') && rswpbs_is_pro_active();

        // Get the current value, default to 1 (checked) for all fields
        $checked = get_option($option_name, 1);

        // If Pro is not active, force all fields to be checked and disable the checkbox
        if (!$is_pro_active) {
            $checked = 1; // Force checked state
            $disabled_attr = 'disabled'; // Disable the checkbox
        } else {
            $disabled_attr = ''; // Enable the checkbox if Pro is active
        }

        // Render the checkbox
        echo '<label>' . ucwords(str_replace(['rswpbs_', '_'], ['', ' '], $option_name)) . ': ';
        echo '<input type="checkbox" name="' . esc_attr($option_name) . '" value="1" ' . checked(1, $checked, false) . ' ' . $disabled_attr . ' />';
        echo '</label>';

        // Show upgrade message if Pro is not active
        if (!$is_pro_active) {
            echo '<p><a href="' . esc_url('https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/') . '" target="_blank">🔒 Upgrade to Pro to hide fields.</a></p>';
        }
    }
}

RSWPBS_Search_Form_Settings::get_instance();
