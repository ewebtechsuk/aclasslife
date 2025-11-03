<?php

namespace WeDevs\DokanVendorDashboard\REST;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WeDevs\Dokan\Abstracts\DokanRESTController;

/**
 * Vendor dashboard plugin swithcer Controller Class
 *
 * @since 1.0.2
 */
class DashboardSwitcher extends DokanRESTController {

    const UI_SWITCHING_VERSION = 'dokan_vendor_dashboard_ui_switching_version';
    const UI_SWITCH_KEY = 'dokan_vendor_dashboard_ui_switching';

    /**
     * Endpoint namespace
     *
     * @var string
     */
    protected $namespace = 'dokan-vendor-dashboard/v1';

    /**
     * Route name
     *
     * @var string
     */
    protected $base = 'ui-switch';

    /**
     * Register the routes.
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace, '/' . $this->base, [
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'set_ui_switching' ],
                    'args'                => [
                        'status' => [
                            'required'    => true,
                            'description' => __( 'If on vendor dashboard ui will active, if false ui will be de activated', 'dokan-vendor-dashboard' ),
                            'type'        => 'string',
                            'enum'        => [ 'on', 'off' ],
                            'context'     => [ 'view', 'edit' ],
                            'default'     => 'on',
                        ],
                    ],
                    'permission_callback' => 'is_user_logged_in',
                ],
            ]
        );
    }

    /**
     * Updates vendor switching data.
     *
     * @since DOKAN_SINCE
     *
     * @param WP_REST_Request $request
     *
     * @return void
     */
    public function set_ui_switching( $request ) {
        $status = $request->get_param( 'status' );

        if ( 'off' === $status ) {
            update_user_meta( dokan_get_current_user_id(), self::UI_SWITCHING_VERSION, DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION );
        }

        update_user_meta( dokan_get_current_user_id(), self::UI_SWITCH_KEY, $status );

        return $status;
    }
}
