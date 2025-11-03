<?php
/**
 * Frontend submission handlers and shortcodes.
 *
 * @package Asset_Listing_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Front-end submission controller.
 */
class ACL_Listing_Submission {

    /**
     * Singleton instance.
     *
     * @var ACL_Listing_Submission|null
     */
    private static $instance = null;

    /**
     * Form errors.
     *
     * @var array
     */
    private $errors = array();

    /**
     * Success message.
     *
     * @var string
     */
    private $message = '';

    /**
     * Cached form data for repopulation.
     *
     * @var array
     */
    private $form_data = array();

    /**
     * Retrieve singleton instance.
     *
     * @return ACL_Listing_Submission
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Hook registrations.
     */
    private function __construct() {
        add_shortcode( 'acl_listing_submission_form', array( $this, 'render_submission_form' ) );
        add_shortcode( 'acl_seller_dashboard', array( $this, 'render_seller_dashboard' ) );
        add_action( 'init', array( $this, 'maybe_handle_submission' ) );
    }

    /**
     * Process submission if a POST request was triggered.
     */
    public function maybe_handle_submission() {
        if ( ! isset( $_POST['acl_listing_submission_nonce'] ) ) {
            return;
        }

        if ( ! is_user_logged_in() ) {
            $this->errors[] = __( 'You must be logged in to submit a listing.', 'asset-listing-pro' );
            return;
        }

        if ( ! current_user_can( 'edit_posts' ) ) {
            $this->errors[] = __( 'You do not have permission to submit listings.', 'asset-listing-pro' );
            return;
        }

        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['acl_listing_submission_nonce'] ) ), 'acl_listing_submission' ) ) {
            $this->errors[] = __( 'Security check failed. Please try again.', 'asset-listing-pro' );
            return;
        }

        $title           = isset( $_POST['acl_listing_title'] ) ? sanitize_text_field( wp_unslash( $_POST['acl_listing_title'] ) ) : '';
        $description     = isset( $_POST['acl_listing_description'] ) ? wp_kses_post( wp_unslash( $_POST['acl_listing_description'] ) ) : '';
        $price_input     = isset( $_POST['acl_listing_price'] ) ? sanitize_text_field( wp_unslash( $_POST['acl_listing_price'] ) ) : '';
        $location        = isset( $_POST['acl_listing_location'] ) ? sanitize_text_field( wp_unslash( $_POST['acl_listing_location'] ) ) : '';
        $specifications  = isset( $_POST['acl_listing_specifications'] ) ? wp_kses_post( wp_unslash( $_POST['acl_listing_specifications'] ) ) : '';
        $asset_type      = isset( $_POST['acl_asset_type'] ) ? absint( wp_unslash( $_POST['acl_asset_type'] ) ) : 0;

        $this->form_data = array(
            'title'          => $title,
            'description'    => $description,
            'price'          => $price_input,
            'location'       => $location,
            'specifications' => $specifications,
            'asset_type'     => absint( $asset_type ),
        );

        if ( empty( $title ) ) {
            $this->errors[] = __( 'A title is required.', 'asset-listing-pro' );
        }

        if ( empty( $description ) ) {
            $this->errors[] = __( 'A description is required.', 'asset-listing-pro' );
        }

        $price = floatval( preg_replace( '/[^0-9.]/', '', (string) $price_input ) );
        if ( $price <= 0 ) {
            $this->errors[] = __( 'Enter a valid price for the listing.', 'asset-listing-pro' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        $post_data = array(
            'post_title'   => $title,
            'post_content' => $description,
            'post_type'    => 'aclasslife_listing',
            'post_status'  => 'pending',
            'post_author'  => get_current_user_id(),
        );

        $post_id = wp_insert_post( $post_data, true );

        if ( is_wp_error( $post_id ) ) {
            $this->errors[] = __( 'We were unable to save your listing. Please try again later.', 'asset-listing-pro' );
            return;
        }

        update_post_meta( $post_id, '_acl_listing_price', $price );

        if ( ! empty( $location ) ) {
            update_post_meta( $post_id, '_acl_listing_location', $location );
        }

        if ( ! empty( $specifications ) ) {
            update_post_meta( $post_id, '_acl_specifications', wp_kses_post( $specifications ) );
        }

        if ( $asset_type ) {
            wp_set_post_terms( $post_id, array( absint( $asset_type ) ), 'asset_type', false );
        }

        $this->handle_featured_image_upload( $post_id );

        if ( ! empty( $this->errors ) ) {
            wp_delete_post( $post_id, true );
            return;
        }

        $this->message = __( 'Thank you! Your listing has been submitted for review.', 'asset-listing-pro' );
        $this->form_data = array();
    }

    /**
     * Handle featured image upload.
     *
     * @param int $post_id Post ID.
     */
    private function handle_featured_image_upload( $post_id ) {
        if ( ! isset( $_FILES['acl_listing_image'] ) || empty( $_FILES['acl_listing_image']['name'] ) ) {
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $attachment_id = media_handle_upload( 'acl_listing_image', $post_id );

        if ( is_wp_error( $attachment_id ) ) {
            $this->errors[] = __( 'The image could not be uploaded. Please try again.', 'asset-listing-pro' );
            return;
        }

        set_post_thumbnail( $post_id, $attachment_id );
    }

    /**
     * Retrieve stored form value.
     *
     * @param string $key     Form field key.
     * @param string $default Default fallback.
     * @return string
     */
    private function get_form_value( $key, $default = '' ) {
        return isset( $this->form_data[ $key ] ) ? $this->form_data[ $key ] : $default;
    }

    /**
     * Render the submission form.
     *
     * @return string
     */
    public function render_submission_form() {
        if ( ! is_user_logged_in() ) {
            return '<p>' . esc_html__( 'Please log in to submit a new asset listing.', 'asset-listing-pro' ) . '</p>';
        }

        ob_start();

        if ( ! empty( $this->errors ) ) {
            echo '<div class="alp-alert alp-alert-error">';
            foreach ( $this->errors as $error ) {
                echo '<p>' . esc_html( $error ) . '</p>';
            }
            echo '</div>';
        }

        if ( ! empty( $this->message ) ) {
            echo '<div class="alp-alert alp-alert-success">' . esc_html( $this->message ) . '</div>';
        }

        $asset_types = get_terms(
            array(
                'taxonomy'   => 'asset_type',
                'hide_empty' => false,
            )
        );
        ?>
        <form class="alp-submission-form" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field( 'acl_listing_submission', 'acl_listing_submission_nonce' ); ?>
            <p>
                <label for="acl_listing_title"><?php esc_html_e( 'Title', 'asset-listing-pro' ); ?></label>
                <input type="text" id="acl_listing_title" name="acl_listing_title" value="<?php echo esc_attr( $this->get_form_value( 'title' ) ); ?>" required />
            </p>
            <p>
                <label for="acl_listing_description"><?php esc_html_e( 'Description', 'asset-listing-pro' ); ?></label>
                <textarea id="acl_listing_description" name="acl_listing_description" rows="6" required><?php echo esc_textarea( $this->get_form_value( 'description' ) ); ?></textarea>
            </p>
            <p>
                <label for="acl_listing_price"><?php esc_html_e( 'Price', 'asset-listing-pro' ); ?></label>
                <input type="number" id="acl_listing_price" name="acl_listing_price" step="0.01" min="0" value="<?php echo esc_attr( $this->get_form_value( 'price' ) ); ?>" required />
            </p>
            <p>
                <label for="acl_listing_location"><?php esc_html_e( 'Location', 'asset-listing-pro' ); ?></label>
                <input type="text" id="acl_listing_location" name="acl_listing_location" value="<?php echo esc_attr( $this->get_form_value( 'location' ) ); ?>" />
            </p>
            <p>
                <label for="acl_listing_specifications"><?php esc_html_e( 'Specifications', 'asset-listing-pro' ); ?></label>
                <textarea id="acl_listing_specifications" name="acl_listing_specifications" rows="4"><?php echo esc_textarea( $this->get_form_value( 'specifications' ) ); ?></textarea>
            </p>
            <p>
                <label for="acl_asset_type"><?php esc_html_e( 'Asset Type', 'asset-listing-pro' ); ?></label>
                <select id="acl_asset_type" name="acl_asset_type">
                    <option value=""><?php esc_html_e( 'Select a type', 'asset-listing-pro' ); ?></option>
                    <?php
                    if ( ! empty( $asset_types ) && ! is_wp_error( $asset_types ) ) {
                        foreach ( $asset_types as $type ) {
                            printf( '<option value="%1$s" %3$s>%2$s</option>', esc_attr( $type->term_id ), esc_html( $type->name ), selected( (int) $this->get_form_value( 'asset_type' ), (int) $type->term_id, false ) );
                        }
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="acl_listing_image"><?php esc_html_e( 'Listing Image', 'asset-listing-pro' ); ?></label>
                <input type="file" id="acl_listing_image" name="acl_listing_image" accept="image/*" />
            </p>
            <p>
                <button type="submit" class="alp-button"><?php esc_html_e( 'Submit Listing', 'asset-listing-pro' ); ?></button>
            </p>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Render seller dashboard shortcode.
     *
     * @return string
     */
    public function render_seller_dashboard() {
        if ( ! is_user_logged_in() ) {
            return '<p>' . esc_html__( 'Please log in to view your listings.', 'asset-listing-pro' ) . '</p>';
        }

        $query = new WP_Query(
            array(
                'post_type'      => 'aclasslife_listing',
                'post_status'    => array( 'pending', 'publish', 'draft' ),
                'author'         => get_current_user_id(),
                'posts_per_page' => 20,
            )
        );

        ob_start();
        ?>
        <div class="alp-seller-dashboard">
            <h2><?php esc_html_e( 'My Asset Listings', 'asset-listing-pro' ); ?></h2>
            <?php if ( $query->have_posts() ) : ?>
                <table class="alp-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Title', 'asset-listing-pro' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'asset-listing-pro' ); ?></th>
                            <th><?php esc_html_e( 'Price', 'asset-listing-pro' ); ?></th>
                            <th><?php esc_html_e( 'Commission Status', 'asset-listing-pro' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ( $query->have_posts() ) :
                        $query->the_post();
                        $price             = get_post_meta( get_the_ID(), '_acl_listing_price', true );
                        $commission_status = get_post_meta( get_the_ID(), '_acl_commission_status', true );
                        ?>
                        <tr>
                            <td><?php the_title(); ?></td>
                            <td><?php
                                $status_object = get_post_status_object( get_post_status() );
                                echo esc_html( $status_object ? $status_object->label : ucfirst( get_post_status() ) );
                            ?></td>
                            <td><?php echo esc_html( apply_filters( 'alp_format_price', number_format_i18n( (float) $price, 2 ), $price ) ); ?></td>
                            <td><?php echo esc_html( ucfirst( $commission_status ? $commission_status : 'unpaid' ) ); ?></td>
                        </tr>
                        <?php
                    endwhile;
                    ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p><?php esc_html_e( 'You have not submitted any listings yet.', 'asset-listing-pro' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
}
