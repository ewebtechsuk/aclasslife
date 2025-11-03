<?php
/**
 * Plugin Name: Asset Listing Pro
 * Description: Provides asset listing management, submissions, and commission tracking.
 * Version: 1.0.0
 * Author: AClassLife
 * Text Domain: asset-listing-pro
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'ALP_PLUGIN_VERSION' ) ) {
    define( 'ALP_PLUGIN_VERSION', '1.0.0' );
}

if ( ! defined( 'ALP_PLUGIN_DIR' ) ) {
    define( 'ALP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ALP_PLUGIN_URL' ) ) {
    define( 'ALP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

require_once ALP_PLUGIN_DIR . 'includes/cpt-listing.php';
require_once ALP_PLUGIN_DIR . 'includes/frontend-submission.php';
require_once ALP_PLUGIN_DIR . 'includes/class-alp-dummy-data.php';

/**
 * Core plugin bootstrap for hooks and shared functionality.
 */
class Asset_Listing_Pro {

    /**
     * Initialize hooks.
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        add_action( 'init', array( $this, 'register_meta_fields' ) );
        add_action( 'admin_menu', array( $this, 'register_admin_menus' ) );
        add_action( 'admin_post_acl_update_commission_status', array( $this, 'handle_commission_status_update' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_action( 'admin_notices', array( $this, 'render_admin_notices' ) );
        add_filter( 'alp_format_price', array( $this, 'filter_format_price' ), 10, 2 );
    }

    /**
     * Load internationalization files.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'asset-listing-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * Register meta fields for listings.
     */
    public function register_meta_fields() {
        $args = array(
            'type'              => 'number',
            'single'            => true,
            'sanitize_callback' => array( $this, 'sanitize_price_meta' ),
            'show_in_rest'      => true,
            'auth_callback'     => function() {
                return current_user_can( 'edit_posts' );
            },
        );

        register_post_meta( 'aclasslife_listing', '_acl_listing_price', $args );

        register_post_meta(
            'aclasslife_listing',
            '_acl_listing_location',
            array(
                'type'         => 'string',
                'single'       => true,
                'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        register_post_meta(
            'aclasslife_listing',
            '_acl_specifications',
            array(
                'type'         => 'string',
                'single'       => true,
                'show_in_rest' => true,
                'sanitize_callback' => array( $this, 'sanitize_specifications_meta' ),
            )
        );

        register_post_meta(
            'aclasslife_listing',
            '_acl_commission_status',
            array(
                'type'         => 'string',
                'single'       => true,
                'default'      => 'unpaid',
                'show_in_rest' => true,
                'sanitize_callback' => array( $this, 'sanitize_commission_status' ),
            )
        );
    }

    /**
     * Sanitize price meta value.
     *
     * @param mixed $value Price value.
     * @return float
     */
    public function sanitize_price_meta( $value ) {
        $value = is_scalar( $value ) ? (float) $value : 0;
        return round( $value, 2 );
    }

    /**
     * Sanitize specification data allowing limited HTML.
     *
     * @param string $value Specification text.
     * @return string
     */
    public function sanitize_specifications_meta( $value ) {
        $allowed_tags = array(
            'p'      => array(),
            'strong' => array(),
            'em'     => array(),
            'ul'     => array(),
            'ol'     => array(),
            'li'     => array(),
            'br'     => array(),
        );

        return wp_kses( $value, $allowed_tags );
    }

    /**
     * Sanitize commission status meta value.
     *
     * @param string $value Commission status.
     * @return string
     */
    public function sanitize_commission_status( $value ) {
        $value = sanitize_key( $value );
        return in_array( $value, array( 'unpaid', 'pending', 'paid' ), true ) ? $value : 'unpaid';
    }

    /**
     * Register admin menu pages for dashboards.
     */
    public function register_admin_menus() {
        add_menu_page(
            __( 'Asset Listings', 'asset-listing-pro' ),
            __( 'Asset Listings', 'asset-listing-pro' ),
            'manage_options',
            'asset-listing-pro',
            array( $this, 'render_admin_dashboard' ),
            'dashicons-media-spreadsheet',
            26
        );

        add_submenu_page(
            'asset-listing-pro',
            __( 'Seller Dashboard', 'asset-listing-pro' ),
            __( 'Seller Dashboard', 'asset-listing-pro' ),
            'edit_posts',
            'asset-listing-pro-seller',
            array( $this, 'render_seller_dashboard' )
        );

        add_submenu_page(
            'asset-listing-pro',
            __( 'Commissions', 'asset-listing-pro' ),
            __( 'Commissions', 'asset-listing-pro' ),
            'manage_options',
            'asset-listing-pro-commissions',
            array( $this, 'render_commission_dashboard' )
        );
    }

    /**
     * Output the admin dashboard summary.
     */
    public function render_admin_dashboard() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'asset-listing-pro' ) );
        }

        $total_listings = wp_count_posts( 'aclasslife_listing' );
        $pending         = isset( $total_listings->pending ) ? (int) $total_listings->pending : 0;
        $published       = isset( $total_listings->publish ) ? (int) $total_listings->publish : 0;
        $draft           = isset( $total_listings->draft ) ? (int) $total_listings->draft : 0;

        $total_revenue = $this->calculate_total_commission_revenue();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Asset Listing Overview', 'asset-listing-pro' ); ?></h1>
            <p><?php esc_html_e( 'Track listing submissions and their review status.', 'asset-listing-pro' ); ?></p>
            <div class="alp-dashboard-grid">
                <div class="alp-card">
                    <h2><?php esc_html_e( 'Pending Listings', 'asset-listing-pro' ); ?></h2>
                    <p><?php echo esc_html( $pending ); ?></p>
                </div>
                <div class="alp-card">
                    <h2><?php esc_html_e( 'Published Listings', 'asset-listing-pro' ); ?></h2>
                    <p><?php echo esc_html( $published ); ?></p>
                </div>
                <div class="alp-card">
                    <h2><?php esc_html_e( 'Draft Listings', 'asset-listing-pro' ); ?></h2>
                    <p><?php echo esc_html( $draft ); ?></p>
                </div>
                <div class="alp-card">
                    <h2><?php esc_html_e( 'Commission Revenue', 'asset-listing-pro' ); ?></h2>
                    <p><?php echo esc_html( $this->format_price( $total_revenue ) ); ?></p>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render the seller dashboard inside the admin area.
     */
    public function render_seller_dashboard() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'asset-listing-pro' ) );
        }

        $current_user = wp_get_current_user();
        $args         = array(
            'post_type'      => 'aclasslife_listing',
            'author'         => $current_user->ID,
            'post_status'    => array( 'pending', 'publish', 'draft' ),
            'posts_per_page' => 20,
        );
        $listings     = new WP_Query( $args );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Seller Dashboard', 'asset-listing-pro' ); ?></h1>
            <p><?php esc_html_e( 'Review your submissions and their commission status.', 'asset-listing-pro' ); ?></p>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Title', 'asset-listing-pro' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'asset-listing-pro' ); ?></th>
                        <th><?php esc_html_e( 'Price', 'asset-listing-pro' ); ?></th>
                        <th><?php esc_html_e( 'Commission Status', 'asset-listing-pro' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if ( $listings->have_posts() ) : ?>
                    <?php while ( $listings->have_posts() ) : $listings->the_post(); ?>
                        <?php
                        $price              = get_post_meta( get_the_ID(), '_acl_listing_price', true );
                        $commission_status  = get_post_meta( get_the_ID(), '_acl_commission_status', true );
                        ?>
                        <tr>
                            <td><a href="<?php echo esc_url( get_edit_post_link( get_the_ID() ) ); ?>"><?php the_title(); ?></a></td>
                            <td><?php
                            $status_object = get_post_status_object( get_post_status() );
                            echo esc_html( $status_object ? $status_object->label : ucfirst( get_post_status() ) );
                            ?></td>
                            <td><?php echo esc_html( $this->format_price( $price ) ); ?></td>
                            <td><?php echo esc_html( ucfirst( $commission_status ? $commission_status : 'unpaid' ) ); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                        <tr>
                            <td colspan="4"><?php esc_html_e( 'No listings found.', 'asset-listing-pro' ); ?></td>
                        </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
        wp_reset_postdata();
    }

    /**
     * Render the commission management dashboard.
     */
    public function render_commission_dashboard() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'asset-listing-pro' ) );
        }

        $query = new WP_Query(
            array(
                'post_type'      => 'aclasslife_listing',
                'posts_per_page' => 20,
                'meta_query'     => array(
                    array(
                        'key'     => '_acl_commission_status',
                        'value'   => array( 'unpaid', 'pending' ),
                        'compare' => 'IN',
                    ),
                ),
            )
        );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Commission Workflow', 'asset-listing-pro' ); ?></h1>
            <p><?php esc_html_e( 'Update commission statuses once an order has been processed.', 'asset-listing-pro' ); ?></p>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Listing', 'asset-listing-pro' ); ?></th>
                        <th><?php esc_html_e( 'Seller', 'asset-listing-pro' ); ?></th>
                        <th><?php esc_html_e( 'Price', 'asset-listing-pro' ); ?></th>
                        <th><?php esc_html_e( 'Commission Status', 'asset-listing-pro' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'asset-listing-pro' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <?php
                        $price     = get_post_meta( get_the_ID(), '_acl_listing_price', true );
                        $status    = get_post_meta( get_the_ID(), '_acl_commission_status', true );
                        $author    = get_userdata( get_post_field( 'post_author', get_the_ID() ) );
                        ?>
                        <tr>
                            <td><a href="<?php echo esc_url( get_edit_post_link( get_the_ID() ) ); ?>"><?php the_title(); ?></a></td>
                            <td><?php echo esc_html( $author ? $author->display_name : __( 'Unknown', 'asset-listing-pro' ) ); ?></td>
                            <td><?php echo esc_html( $this->format_price( $price ) ); ?></td>
                            <td><?php echo esc_html( ucfirst( $status ? $status : 'unpaid' ) ); ?></td>
                            <td>
                                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                                    <?php wp_nonce_field( 'acl_update_commission_status', 'acl_commission_nonce' ); ?>
                                    <input type="hidden" name="action" value="acl_update_commission_status" />
                                    <input type="hidden" name="listing_id" value="<?php echo esc_attr( get_the_ID() ); ?>" />
                                    <select name="commission_status">
                                        <option value="unpaid" <?php selected( $status, 'unpaid' ); ?>><?php esc_html_e( 'Unpaid', 'asset-listing-pro' ); ?></option>
                                        <option value="pending" <?php selected( $status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'asset-listing-pro' ); ?></option>
                                        <option value="paid" <?php selected( $status, 'paid' ); ?>><?php esc_html_e( 'Paid', 'asset-listing-pro' ); ?></option>
                                    </select>
                                    <button type="submit" class="button button-primary"><?php esc_html_e( 'Update', 'asset-listing-pro' ); ?></button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                        <tr>
                            <td colspan="5"><?php esc_html_e( 'No commission records available.', 'asset-listing-pro' ); ?></td>
                        </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
        wp_reset_postdata();
    }

    /**
     * Display admin notices for commission updates.
     */
    public function render_admin_notices() {
        if ( empty( $_GET['acl_commission_message'] ) ) {
            return;
        }

        $code = sanitize_key( wp_unslash( $_GET['acl_commission_message'] ) );
        $message = '';
        $type = 'info';

        if ( 'updated' === $code ) {
            $message = __( 'Commission status updated.', 'asset-listing-pro' );
            $type    = 'success';
        } elseif ( 'invalid' === $code ) {
            $message = __( 'Unable to update commission status. Please try again.', 'asset-listing-pro' );
            $type    = 'error';
        } else {
            return;
        }

        printf( '<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>', esc_attr( $type ), esc_html( $message ) );
    }

    /**
     * Process commission status updates from the admin dashboard.
     */
    public function handle_commission_status_update() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to perform this action.', 'asset-listing-pro' ) );
        }

        check_admin_referer( 'acl_update_commission_status', 'acl_commission_nonce' );

        $listing_id = isset( $_POST['listing_id'] ) ? absint( $_POST['listing_id'] ) : 0;
        $status     = isset( $_POST['commission_status'] ) ? sanitize_key( wp_unslash( $_POST['commission_status'] ) ) : 'unpaid';

        if ( ! $listing_id ) {
            $redirect = wp_get_referer();
            if ( ! $redirect ) {
                $redirect = admin_url( 'admin.php?page=asset-listing-pro-commissions' );
            }

            wp_safe_redirect( add_query_arg( 'acl_commission_message', 'invalid', $redirect ) );
            exit;
        }

        update_post_meta( $listing_id, '_acl_commission_status', $this->sanitize_commission_status( $status ) );

        do_action( 'acl_listing_commission_status_updated', $listing_id, $status );

        $redirect = wp_get_referer();
        if ( ! $redirect ) {
            $redirect = admin_url( 'admin.php?page=asset-listing-pro-commissions' );
        }

        wp_safe_redirect( add_query_arg( 'acl_commission_message', 'updated', $redirect ) );
        exit;
    }

    /**
     * Enqueue frontend scripts and styles.
     */
    public function enqueue_frontend_assets() {
        if ( ! is_admin() ) {
            wp_enqueue_style( 'asset-listing-pro', ALP_PLUGIN_URL . 'assets/css/asset-listing-pro.css', array(), ALP_PLUGIN_VERSION );
            wp_enqueue_script( 'asset-listing-pro', ALP_PLUGIN_URL . 'assets/js/asset-listing-pro.js', array( 'jquery' ), ALP_PLUGIN_VERSION, true );
            wp_localize_script(
                'asset-listing-pro',
                'alpSettings',
                array(
                    'currencySymbol' => $this->get_currency_symbol(),
                )
            );
        }
    }

    /**
     * Enqueue admin scripts for dashboards.
     */
    public function enqueue_admin_assets( $hook ) {
        if ( false === strpos( $hook, 'asset-listing-pro' ) ) {
            return;
        }

        wp_enqueue_style( 'asset-listing-pro-admin', ALP_PLUGIN_URL . 'assets/css/asset-listing-pro.css', array(), ALP_PLUGIN_VERSION );
    }

    /**
     * Calculate total commission revenue from paid listings.
     *
     * @return float
     */
    private function calculate_total_commission_revenue() {
        $query = new WP_Query(
            array(
                'post_type'      => 'aclasslife_listing',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'meta_query'     => array(
                    array(
                        'key'   => '_acl_commission_status',
                        'value' => 'paid',
                    ),
                ),
            )
        );

        $total = 0.0;
        foreach ( $query->posts as $post_id ) {
            $price  = (float) get_post_meta( $post_id, '_acl_listing_price', true );
            $total += $price;
        }

        return $total;
    }

    /**
     * Retrieve the active currency symbol.
     *
     * @return string
     */
    private function get_currency_symbol() {
        if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
            return get_woocommerce_currency_symbol();
        }

        $symbol = '$';

        return apply_filters( 'alp_currency_symbol', $symbol );
    }

    /**
     * Format price using WooCommerce helper if available.
     *
     * @param float|string $price Price value.
     * @return string
     */
    public function filter_format_price( $formatted, $price ) {
        return $this->format_price( $price );
    }

    /**
     * Format price using WooCommerce helper if available.
     *
     * @param float|string $price Price value.
     * @return string
     */
    private function format_price( $price ) {
        $price = (float) $price;

        if ( function_exists( 'wc_price' ) ) {
            return wp_strip_all_tags( wc_price( $price ) );
        }

        return $this->get_currency_symbol() . number_format_i18n( $price, 2 );
    }
}

register_activation_hook( __FILE__, array( 'ALP_Dummy_Data', 'activate' ) );

new Asset_Listing_Pro();

// Frontend utilities.
ACL_Listing_Submission::get_instance();
ALP_Dummy_Data::init();
