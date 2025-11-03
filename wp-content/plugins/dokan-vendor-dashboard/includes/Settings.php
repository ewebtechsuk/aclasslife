<?php

namespace WeDevs\DokanVendorDashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Settings related to the plugin.
 *
 * @since 1.0.0
 */
class Settings {

    const UI_SWITCHING_KEY = 'dokan_vendor_dashboard_ui_switching';

	/**
	 * Initiates necessary hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'dokan_settings_general_site_options', [ $this, 'add_setting_to_enable_vendor_dashboard' ] );
	}

	/**
	 * Add settings to enable interactive vendor dashboard ui.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function add_setting_to_enable_vendor_dashboard( $settings ) {
		$settings['vendor_dashboard_ui'] = [
			'name'    => 'vendor_dashboard_ui',
			'label'   => __( 'Use full page vendor dashboard ui', 'dokan-vendor-dashboard' ),
			'desc'    => __( 'Enable vendor dashboard full page interactive user interface', 'dokan-vendor-dashboard' ),
			'type'    => 'switcher',
			'default' => 'on',
		];

		return $settings;
	}

	/**
	 * Is switched to new dashboard.
	 *
	 * @since 1.0.0
	 *
	 * @todo we can manage this from options table and a toggling button.
	 *
	 * @return boolean
	 */
	public static function is_switched_new_dashboard() {
		return 'on' === dokan_get_option( 'vendor_dashboard_ui', 'dokan_general', 'on' );
	}

    /**
     * Returns true if vendor switching is on or no setting found. This means load vendor dashboard plugin ui.
     *
     * @since 1.0.2
     *
     * @return bool
     */
    public static function should_load_vendor_dashboard_ui(): bool {
        $should_load = self::get_ui_switching();

        return apply_filters( 'dokan_should_load_vendor_dashboard_ui', self::is_switched_new_dashboard() && $should_load );
    }

    /**
     * Returns user ui switching.
     *
     * @since 1.0.2
     *
     * @return mixed
     */
    public static function get_ui_switching() {
        return 'off' !== get_user_meta( dokan_get_current_user_id(), self::UI_SWITCHING_KEY, true );
    }
}
