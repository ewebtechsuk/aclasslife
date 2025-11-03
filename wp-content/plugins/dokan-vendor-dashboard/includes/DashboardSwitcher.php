<?php

namespace WeDevs\DokanVendorDashboard;

class DashboardSwitcher {

    /**
     * Class constructor.
     *
     * @since 1.0.2
     */
    public function __construct() {
        add_action( 'wp', [ $this, 'load_common_script' ] );
        add_action( 'dokan_dashboard_sidebar_end', [ $this, 'add_switch_dashboard_link' ] );
        add_action( 'rest_api_init', [ $this, 'rest_api_class_map' ] );
    }

    /**
     * Load common scripts.
     *
     * @since 1.0.2
     *
     * @return void
     */
    public function load_common_script() {
        wp_enqueue_script( 'vendor-dashboard-common-script', DOKAN_VENDOR_DASHBOARD_ASSETS . '/js/ui-switcher.js', [ 'jquery' ], DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION, true );
    }

    /**
     * Add a switch dashboard link.
     *
     * @since DOKAN_SINCE
     *
     * @return void
     */
    public function add_switch_dashboard_link() {
        if ( ! Settings::is_switched_new_dashboard() ) {
            return;
        }

        ?>
        <div class="dokan-dashboard-divider"></div>
        <div class="dokan-dashboard-switcher">
            <a href="#" data-status="<?php echo esc_attr( ! Settings::get_ui_switching() ? 'on' : 'off' ); ?>" class="old-dashboard-switch-ui"><?php esc_html_e( 'Switch to new Dashboard', 'dokan-vendor-dashboard' ); ?></a>
        </div>

        <style>
            .dokan-dashboard-divider{
                width: 100%;
                height: 1px;
                background-color: white;
                margin-bottom: 20px;
            }
            .dokan-dashboard-switcher {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }
        </style>
        <?php
    }

    /**
     * Rest api class map
     *
     * @param array $classes
     *
     * @since 1.0.2
     *
     * @return array
     */
    public function rest_api_class_map() {
        return dokan_vendor_dashboard()->dashboard_switcher_api->register_routes();
    }
}
