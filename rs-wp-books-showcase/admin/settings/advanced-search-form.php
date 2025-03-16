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
        add_action('admin_enqueue_scripts', [$this, 'enqueue_sortable_scripts']);
        // add_action('admin_init', [$this, 'migrate_acf_search_form_to_options_once']);
    }
    public function enqueue_sortable_scripts($hook) {
        // Only load on your settings page
        if ($hook !== 'book_page_' . self::$menu_slug) {
            return;
        }
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery');
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
                $proClass = ($is_pro_active) ? ' rswpbs-pro-active' : ' rswpbs-free-active';
                ?>

                <!-- Field Visibility -->
                <h2><?php esc_html_e('Field Visibility', 'rswpbs'); ?></h2>
                <table class="form-table rswpbs-settings-table<?php echo esc_attr($proClass); ?>">
                    <tbody>
                        <tr>
                            <?php
                            $fields = $this->get_default_field_order();
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

                <!-- Field Sorting -->
                <h2><?php esc_html_e('Field Order', 'rswpbs'); ?></h2>
                <p><?php esc_html_e('View the current order of fields in the search form.', 'rswpbs'); ?></p>
                <ul id="rswpbs-sortable-fields" class="sortable-fields<?php echo esc_attr($proClass);?>">
                    <?php
                    $field_order = get_option('rswpbs_search_field_order', $this->get_default_field_order());
                    // Ensure field_order is an array
                    if (!is_array($field_order)) {
                        $field_order = $this->get_default_field_order();
                    }
                    foreach ($field_order as $field) {
                        $label = ucwords(str_replace(['rswpbs_', '_'], ['', ' '], $field));
                        echo '<li class="ui-state-default" data-field="' . esc_attr($field) . '">';
                        echo '<span class="sortable-handle"><span class="dashicons dashicons-move"></span></span> ';
                        echo esc_html($label) . '</li>';
                    }
                    ?>
                </ul>
                <?php if (!$is_pro_active): ?>
                    <p class="upgrade-message"><?php esc_html_e('Field sorting is a Pro feature. Upgrade to customize the order of fields by dragging.', 'rswpbs'); ?></p>
                    <p><a href="<?php echo esc_url('https://rswpthemes.com/rs-wp-book-showcase-wordpress-plugin/'); ?>" target="_blank"><?php esc_html_e('Upgrade to Pro', 'rswpbs'); ?></a></p>
                <?php endif; ?>
                <!-- Hidden input to store the order as a comma-separated string (only relevant for Pro) -->
                <input type="hidden" name="rswpbs_search_field_order" id="rswpbs-field-order" value="<?php echo esc_attr(implode(',', $field_order)); ?>">

                <?php submit_button(); ?>
            </form>
        </div>

        <?php if ($is_pro_active): ?>
        <script>
        jQuery(document).ready(function($) {
            $("#rswpbs-sortable-fields").sortable({
                // handle: '.sortable-handle', // Restrict dragging to the handle
                update: function(event, ui) {
                    var order = [];
                    $("#rswpbs-sortable-fields li").each(function() {
                        order.push($(this).data("field"));
                    });
                    // Update the hidden input with the new order
                    $("#rswpbs-field-order").val(order.join(","));
                },
                start: function(event, ui) {
                    // Do nothing in Pro version (full functionality)
                }
            });
            $("#rswpbs-sortable-fields").disableSelection();
        });
        </script>
        <?php else: ?>
        <script>
        jQuery(document).ready(function($) {
            $("#rswpbs-sortable-fields").sortable({
                disabled: true, // Disable sorting in free version
                start: function(event, ui) {
                    alert('<?php echo esc_js(__('Field sorting is a Pro feature. Upgrade to customize the order of fields.', 'rswpbs')); ?>');
                    return false; // Prevent sorting
                }
            });
            $("#rswpbs-sortable-fields").disableSelection();
        });
        </script>
        <?php endif; ?>
        <style>
        .sortable-fields {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 60%;
        }
        .sortable-fields li {
            margin: 0 3px 3px 3px;
            padding: 0.4em;
            padding-left: 1.5em;
            font-size: 1.4em;
            background: #f9f9f9;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
        }
        .sortable-fields li:hover {
            background: #f1f1f1;
        }
        .sortable-handle {
            margin-right: 10px;
            color: #666;
        }
        .sortable-handle i {
            font-size: 1.2em;
        }
        .upgrade-message {
            color: #dc3232;
            font-style: italic;
        }
        </style>
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
                'default' => 1
            ]);
        }

        // Register the field order option
        register_setting(self::$option_group, 'rswpbs_search_field_order', [
            'sanitize_callback' => [$this, 'sanitize_field_order'],
            'default' => $this->get_default_field_order()
        ]);

        add_settings_section(
            'rswpbs_search_form_settings_section',
            __('Search Form Settings', 'rswpbs'),
            '__return_false',
            self::$settings_page
        );
    }
    public function get_default_field_order() {
        return [
            'rswpbs_show_name_field', 'rswpbs_show_category_field', 'rswpbs_show_author_field',
            'rswpbs_show_formats_field', 'rswpbs_show_years_field', 'rswpbs_show_series_field',
            'rswpbs_show_publishers_field', 'rswpbs_show_language_field', 'rswpbs_show_isbn_field',
            'rswpbs_show_isbn_10_field', 'rswpbs_show_reset_icon'
        ];
    }

    public function sanitize_field_order($input) {
        $default_order = $this->get_default_field_order();
        if (empty($input)) {
            return $default_order;
        }
        // If input is a string (from the hidden input), convert it to an array
        if (is_string($input)) {
            $input = explode(',', $input);
        }
        // Ensure only valid fields are included
        return array_intersect($input, $default_order);
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
