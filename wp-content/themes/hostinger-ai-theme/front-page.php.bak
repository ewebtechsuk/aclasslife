<?php
/**
 * Front Page template for luxury experience layout.
 *
 * @package Hostinger_AI_Theme
 */

get_header();
?>

<!-- Hero Section -->
<section class="homepage-hero">
    <div class="hero-background">
        <!-- you may replace with a video background or large image -->
        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/hero-luxury.jpg' ) ); ?>" alt="Luxury supercar, yacht &amp; private jet">
    </div>
    <div class="hero-content">
        <div class="hero-text">
            <h1>Experience Luxury Above &amp; Beyond</h1>
            <p>Supercars • Private Jets • Luxury Yachts – Curated for the Elite</p>
            <a href="<?php echo esc_url( site_url( '/inventory' ) ); ?>" class="button hero-cta">View Exclusive Fleet</a>
        </div>
    </div>
</section>

<!-- Services / What We Offer -->
<section class="homepage-services">
    <div class="container">
        <h2>What We Offer</h2>
        <div class="services-grid">
            <!-- Service 1 -->
            <div class="service-item">
                <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/service-supercars.jpg' ) ); ?>" alt="Supercars">
                <h3>Supercars</h3>
                <p>Access the world’s most iconic and rare supercars, privately brokered and delivered.</p>
            </div>
            <!-- Service 2 -->
            <div class="service-item">
                <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/service-private-jets.jpg' ) ); ?>" alt="Private Jets">
                <h3>Private Jets</h3>
                <p>Charter or purchase private jets with full service for global travel without compromise.</p>
            </div>
            <!-- Service 3 -->
            <div class="service-item">
                <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/service-yachts.jpg' ) ); ?>" alt="Luxury Yachts">
                <h3>Luxury Yachts</h3>
                <p>Explore the finest yachts in the world, tailored charters and exclusive sales.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Fleet / Inventory -->
<section class="homepage-fleet">
    <div class="container">
        <h2>Featured Inventory</h2>
        <?php
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 3,
            'meta_key'       => '_featured',
            'meta_value'     => 'yes',
        );

        $loop = new WP_Query( $args );

        if ( $loop->have_posts() ) {
            echo '<div class="fleet-grid">';

            while ( $loop->have_posts() ) {
                $loop->the_post();
                $product = wc_get_product( get_the_ID() );

                echo '<div class="fleet-item">';
                echo '<a href="' . esc_url( get_permalink() ) . '">';
                echo woocommerce_get_product_thumbnail( 'medium' );
                echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
                echo '</a>';
                echo '<p>' . esc_html( wp_trim_words( get_the_excerpt(), 20, '...' ) ) . '</p>';

                if ( $product instanceof WC_Product ) {
                    echo '<p class="fleet-price">' . wp_kses_post( $product->get_price_html() ) . '</p>';
                }

                echo '<a class="button" href="' . esc_url( get_permalink() ) . '">View Details</a>';
                echo '</div>';
            }

            echo '</div>';
            wp_reset_postdata();
        }
        ?>
        <a href="<?php echo esc_url( site_url( '/inventory' ) ); ?>" class="button fleet-cta">See Full Fleet</a>
    </div>
</section>

<!-- Testimonials / Social Proof -->
<section class="homepage-testimonials">
    <div class="container">
        <h2>What Our Clients Say</h2>
        <div class="testimonials-slider">
            <blockquote>
                <p>“A Class Life handled everything—jet, yacht, supercar—all in one seamless experience.”</p>
                <footer>&mdash; London Capital Investments, UK</footer>
            </blockquote>
            <blockquote>
                <p>“The fleet and service were world-class. We were able to pick our jet and car in days.”</p>
                <footer>&mdash; Spear Removals Ltd., UAE</footer>
            </blockquote>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="homepage-cta">
    <div class="container">
        <h2>Ready to Experience the Extraordinary?</h2>
        <p>Contact us today and let us tailor the perfect luxury solution for you.</p>
        <a href="<?php echo esc_url( site_url( '/contact' ) ); ?>" class="button button-invert">Get In Touch</a>
    </div>
</section>

<?php get_footer(); ?>
