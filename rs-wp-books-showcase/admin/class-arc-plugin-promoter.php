<?php
/**
 * Cross-Promotion Class for WordPress Themes & Plugins.
 * This file will only load if the target plugin is NOT active.
 * Secured with proper escaping functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Check if plugin functions are available
if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Plugin Folder and Main File Name
$target_plugin_file = 'rs-arc-manager/rs-arc-manager.php';

// if plugin is not active
if ( is_plugin_active( $target_plugin_file ) ) {
    return;
}

if ( ! class_exists( 'RS_Arc_Manager_Promoter' ) ) {

    final class RS_Arc_Manager_Promoter {

        private $api_slug     = 'rs-arc-manager';
        private $plugin_file  = 'rs-arc-manager/rs-arc-manager.php';
        private $plugin_name  = 'RS ARC Manager';
        private $redirect_url = 'edit.php?post_type=rs_arc_campaign';
        private $menu_slug    = 'rs-promote-plugin';

        public function __construct() {
            add_action( 'admin_menu', array( $this, 'add_promotional_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'highlight_menu_item' ) );
            add_action( 'wp_ajax_rs_install_activate_plugin', array( $this, 'ajax_install_and_activate' ) );
        }

        /**
         * Crating Admin Menu
         */
        public function add_promotional_menu() {
            $page_hook = add_menu_page(
                sprintf( esc_html__( 'Install %s', 'rs-arc-manager' ), $this->plugin_name ),
                esc_html( $this->plugin_name ),
                'install_plugins',
                $this->menu_slug,
                array( $this, 'render_promotional_page' ),
                'dashicons-star-filled',
                99
            );

            add_action( 'admin_head-' . $page_hook, array( $this, 'hide_all_admin_notices' ) );
        }

        /**
         * Highlight Menu
         */
        public function highlight_menu_item() {
            $safe_menu_slug = esc_attr( $this->menu_slug );

            echo '<style>
                #toplevel_page_' . $safe_menu_slug . ' > a.menu-top {
                    background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%) !important;
                    color: #fff !important;
                    font-weight: 600 !important;
                    border-radius: 4px;
                    margin: 5px;
                }
                #toplevel_page_' . $safe_menu_slug . ' > a.menu-top:hover {
                    background: linear-gradient(135deg, #FF8E53 0%, #FF6B6B 100%) !important;
                }
                #toplevel_page_' . $safe_menu_slug . ' > a.menu-top .wp-menu-image::before,
                #toplevel_page_' . $safe_menu_slug . ' > a.menu-top .wp-menu-name {
                    color: #fff !important;
                }
            </style>';
        }

        /**
         * Removed All Admin Notification From This Page
         */
        public function hide_all_admin_notices() {
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );

            echo '<style>
                div.update-nag,
                div.updated,
                div.error,
                div.notice,
                .is-dismissible,
                .ext-admin-notice {
                    display: none !important;
                }
            </style>';
        }

        /**
         * Promption Page Layout
         */
        public function render_promotional_page() {
            ?>
            <div class="wrap" style="max-width: 800px; margin: 40px auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">

                <div style="font-size: 50px; color: #2271b1; margin-bottom: 15px;">
                    <span class="dashicons dashicons-book" style="font-size: 60px; width: 60px; height: 60px;"></span>
                </div>

                <h1 style="font-size: 30px; margin-bottom: 15px; color: #1d2327;">
                    <?php printf( esc_html__( 'Streamline Your Book Launch with %s', 'rs-arc-manager' ), esc_html( $this->plugin_name ) ); ?>
                </h1>

                <p style="font-size: 16px; color: #50575e; margin-bottom: 30px; font-weight: 400; line-height: 1.6;">
                    <?php esc_html_e( 'Managing Advance Review Copies (ARCs) shouldn\'t involve messy spreadsheets and endless manual emails. RS ARC Manager is built specifically for authors and publishers to handle the entire review copy process directly from the WordPress dashboard. Build your campaign, manage your readers, and track reviews effortlessly.', 'rs-arc-manager' ); ?>
                </p>

                <div style="text-align: left; background: #f8f9f9; padding: 30px; border-radius: 8px; margin-bottom: 35px; border: 1px solid #e2e4e7;">

                    <h3 style="font-size: 18px; color: #1d2327; margin-top: 0; margin-bottom: 20px;">
                        <?php esc_html_e( 'Core Features to Optimize Your Workflow:', 'rs-arc-manager' ); ?>
                    </h3>

                    <ul style="font-size: 15px; line-height: 1.8; color: #3c434a; margin-left: 0; list-style: none;">
                        <li style="margin-bottom: 12px;">
                            🚀 <strong><?php esc_html_e( 'Effortless Campaign Creation:', 'rs-arc-manager' ); ?></strong>
                            <?php esc_html_e( 'Easily set up dedicated ARC campaigns for your new book releases and manage everything from one place.', 'rs-arc-manager' ); ?>
                        </li>
                        <li style="margin-bottom: 12px;">
                            👥 <strong><?php esc_html_e( 'Reviewer Application Management:', 'rs-arc-manager' ); ?></strong>
                            <?php esc_html_e( 'Allow readers to apply for review copies. You have full control to review, approve, or reject applicants with ease.', 'rs-arc-manager' ); ?>
                        </li>
                        <li style="margin-bottom: 12px;">
                            📨 <strong><?php esc_html_e( 'Automated File Delivery:', 'rs-arc-manager' ); ?></strong>
                            <?php esc_html_e( 'Stop sending manual emails with attachments. Approved reviewers automatically receive secure links to download your book files (EPUB, PDF, or MOBI).', 'rs-arc-manager' ); ?>
                        </li>
                        <li style="margin-bottom: 12px;">
                            📊 <strong><?php esc_html_e( 'Built-in Review Tracking:', 'rs-arc-manager' ); ?></strong>
                            <?php esc_html_e( 'Keep your campaigns organized by allowing reviewers to submit their published review URLs. Easily track who actually read and reviewed your book.', 'rs-arc-manager' ); ?>
                        </li>
                    </ul>

                </div>

                <div style="display: flex; gap: 15px; justify-content: center; align-items: center; flex-wrap: wrap;">

                    <button id="rs-install-activate-btn" class="button button-primary button-hero" style="font-size: 16px; padding: 10px 30px; height: auto;">
                        <?php esc_html_e( 'Install & Activate Free Version', 'rs-arc-manager' ); ?>
                    </button>

                    <a href="<?php echo esc_url( 'https://rswpthemes.com/rs-arc-manager/' ); ?>" target="_blank" class="button button-secondary button-hero" style="font-size: 16px; padding: 10px 30px; height: auto;">
                        <?php esc_html_e( 'Explore Pro Features', 'rs-arc-manager' ); ?> <span class="dashicons dashicons-external" style="line-height: inherit; margin-left: 5px;"></span>
                    </a>

                    <a href="<?php echo esc_url( 'https://rswpthemes.com/documentation/rs-arc-manager-installation-general-settings/' ); ?>" target="_blank" class="button button-secondary button-hero" style="font-size: 16px; padding: 10px 30px; height: auto;">
                        <?php esc_html_e( 'Documentation', 'rs-arc-manager' ); ?> <span class="dashicons dashicons-media-document" style="line-height: inherit; margin-left: 5px;"></span>
                    </a>

                </div>

                <p id="rs-install-status" style="margin-top: 20px; font-weight: bold; display: none; color: #2271b1;"></p>
            </div>

            <script>
            jQuery(document).ready(function($) {
                $('#rs-install-activate-btn').on('click', function(e) {
                    e.preventDefault();
                    var $btn = $(this);
                    var $status = $('#rs-install-status');

                    $btn.prop('disabled', true).text('<?php echo esc_js( __( 'Installing... Please wait', 'rs-arc-manager' ) ); ?>');
                    $status.text('<?php echo esc_js( __( 'Downloading and installing the plugin...', 'rs-arc-manager' ) ); ?>').show();

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'rs_install_activate_plugin',
                            _wpnonce: '<?php echo esc_js( wp_create_nonce( 'rs_promote_nonce' ) ); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                $btn.text('<?php echo esc_js( __( 'Activated Successfully!', 'rs-arc-manager' ) ); ?>');
                                $status.css('color', 'green').text('<?php echo esc_js( __( 'Plugin is now active. Redirecting...', 'rs-arc-manager' ) ); ?>');
                                setTimeout(function() {
                                    window.location.href = '<?php echo esc_url_raw( admin_url( $this->redirect_url ) ); ?>';
                                }, 1500);
                            } else {
                                $btn.prop('disabled', false).text('<?php echo esc_js( __( 'Try Again', 'rs-arc-manager' ) ); ?>');
                                $status.css('color', 'red').text('<?php echo esc_js( __( 'Error: ', 'rs-arc-manager' ) ); ?>' + response.data);
                            }
                        },
                        error: function() {
                            $btn.prop('disabled', false).text('<?php echo esc_js( __( 'Try Again', 'rs-arc-manager' ) ); ?>');
                            $status.css('color', 'red').text('<?php echo esc_js( __( 'Something went wrong. Please try manually.', 'rs-arc-manager' ) ); ?>');
                        }
                    });
                });
            });
            </script>
            <?php
        }
        /**
         * Background Plugin Installetion
         */
        public function ajax_install_and_activate() {
            check_ajax_referer( 'rs_promote_nonce' );

            if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
                wp_send_json_error( esc_html__( 'You do not have permission to install or activate plugins.', 'rs-arc-manager' ) );
            }

            include_once ABSPATH . 'wp-admin/includes/file.php';
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            include_once ABSPATH . 'wp-admin/includes/plugin.php';

            WP_Filesystem();

            $plugin_slug = $this->api_slug;
            $plugin_file = $this->plugin_file;

            $installed_plugins = get_plugins();
            $is_installed = isset( $installed_plugins[ $plugin_file ] );

            if ( ! $is_installed ) {
                $api = plugins_api( 'plugin_information', array(
                    'slug'   => sanitize_text_field( $plugin_slug ), // Added sanitize
                    'fields' => array( 'sections' => false )
                ) );

                if ( is_wp_error( $api ) ) {
                    wp_send_json_error( esc_html__( 'Failed to fetch plugin information from WordPress.org.', 'rs-arc-manager' ) );
                }

                $upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
                $install_result = $upgrader->install( esc_url_raw( $api->download_link ) ); // Escaped download link

                if ( is_wp_error( $install_result ) || ! $install_result ) {
                    wp_send_json_error( esc_html__( 'Failed to install the plugin.', 'rs-arc-manager' ) );
                }
            }

            $activate_result = activate_plugin( sanitize_text_field( $plugin_file ) ); // Added sanitize

            if ( is_wp_error( $activate_result ) ) {
                wp_send_json_error( esc_html__( 'Installed, but failed to activate.', 'rs-arc-manager' ) );
            }

            wp_send_json_success( esc_html__( 'Success', 'rs-arc-manager' ) );
        }
    }

    new RS_Arc_Manager_Promoter();
}