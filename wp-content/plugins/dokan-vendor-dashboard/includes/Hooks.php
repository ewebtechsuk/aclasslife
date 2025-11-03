<?php

namespace WeDevs\DokanVendorDashboard;

use WC_Product;

/**
 * Initiates necessary hooks.
 *
 * @since 1.0.0
 *
 * @return void
 */
class Hooks {
    public function __construct() {
        add_filter( 'dokan_get_edit_product_url', [ $this, 'update_edit_product_url' ], 10, 2 );
    }

    /**
     * Changes product edit url for vendor dashboard.
     *
     * @param string     $url
     * @param WC_Product $product
     *
     * @return string $url
     */
    public function update_edit_product_url( $url, $product ) {
        $url = dokan_get_navigation_url( 'products' ) . 'update/' . $product->get_id();

        return $url;
    }
}
