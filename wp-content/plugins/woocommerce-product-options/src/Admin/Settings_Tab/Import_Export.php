<?php


namespace Barn2\Plugin\WC_Product_Options\Admin\Settings_Tab;

use Barn2\Plugin\WC_Product_Options\Dependencies\Lib\Plugin\Licensed_Plugin;
use Barn2\Plugin\WC_Product_Options\Dependencies\Lib\Registerable;

/**
 * The Import_Export settings tab.
 *
 * @package   Barn2\woocommerce-product-options
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
class Import_Export implements Registerable {

	const TAB_ID       = 'import_export';
	const OPTION_GROUP = 'wc_product_options_import_export';
	const MENU_SLUG    = 'wpo_options';

	private $title;
	private $plugin;

	/**
	 * Get things started.
	 *
	 * @param Licensed_Plugin $plugin
	 */
	public function __construct( Licensed_Plugin $plugin ) {
		$this->plugin = $plugin;
		$this->title  = __( 'Import/Export', 'woocommerce-product-options' );
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register() {
	}

	/**
	 * Register the settings.
	 */
	public function output() {
		print( '<div id="barn2-wpo-import-export-root"></div>' );
	}

	/**
	 * Get the tab title.
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

}
