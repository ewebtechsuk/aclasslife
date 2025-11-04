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
    <div class="hero-media" aria-hidden="true">
        <img src="https://images.unsplash.com/photo-1518552781905-280ee7f1e1fd?auto=format&fit=crop&w=1920&q=80" alt="Glistening supercar parked beside a private jet on the runway">
        <span class="hero-overlay"></span>
    </div>
    <div class="container hero-container">
        <div class="hero-content">
            <h1>Elevate Your Lifestyle. Beyond Limitation.</h1>
            <p class="hero-subheadline">Supercars · Private Jets · Luxury Yachts — Crafted For The Connoisseur.</p>
            <a href="<?php echo esc_url( site_url( '/inventory' ) ); ?>" class="button hero-cta">Explore Our Elite Fleet</a>
        </div>
    </div>
</section>

<!-- Services / What We Offer -->
<section class="homepage-services">
    <div class="container">
        <h2 class="section-title">What We Offer</h2>
        <div class="services-grid">
            <!-- Service 1 -->
            <article class="service-item">
                <img src="https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=900&q=80" alt="Supercars" loading="lazy">
                <div class="service-copy">
                    <h3>Supercars</h3>
                    <p>Indulge in rare hypercars and bespoke configurations curated from the world’s most sought-after marques. From acquisition to discreet delivery, every detail is handled with precision.</p>
                </div>
            </article>
            <!-- Service 2 -->
            <article class="service-item">
                <img src="https://images.unsplash.com/photo-1506368083636-6defb67639d8?auto=format&fit=crop&w=900&q=80" alt="Private Jets" loading="lazy">
                <div class="service-copy">
                    <h3>Private Jets</h3>
                    <p>Secure intercontinental travel with tailored jet charters and aircraft management crafted to your itinerary. Our aviation team orchestrates every journey for absolute ease and privacy.</p>
                </div>
            </article>
            <!-- Service 3 -->
            <article class="service-item">
                <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80" alt="Luxury Yachts" loading="lazy">
                <div class="service-copy">
                    <h3>Luxury Yachts</h3>
                    <p>Sail iconic coastlines aboard superyachts matched to your lifestyle and crew preferences. Bespoke itineraries, concierge provisioning, and acquisition advisory create unforgettable voyages.</p>
                </div>
            </article>
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
        }
        wp_reset_postdata();
        ?>
        <a href="<?php echo esc_url( site_url( '/inventory' ) ); ?>" class="button fleet-cta">See Full Fleet</a>
    </div>
</section>

<!-- Testimonials / Social Proof -->
<section class="homepage-testimonials">
    <div class="container">
        <h2>What Our Clients Say</h2>
        <div class="testimonials-grid">
            <article class="testimonial">
                <blockquote>
                    <p>“A Class Life orchestrated our Monaco weekend with unparalleled finesse—from the Gulfstream arrival to the keys of a limited-production coupé.”</p>
                </blockquote>
                <footer>
                    <div class="testimonial-client">
                        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/testimonials/man-3.jpg' ) ); ?>" alt="Portrait of Oliver Hart from London" loading="lazy">
                        <div>
                            <strong>Oliver Hart</strong>
                            <span>London Capital Investments · UK</span>
                        </div>
                    </div>
                </footer>
            </article>
            <article class="testimonial">
                <blockquote>
                    <p>“Their team curated a seamless Mediterranean voyage—chartered jet, villa arrival, and a yacht that exceeded every expectation.”</p>
                </blockquote>
                <footer>
                    <div class="testimonial-client">
                        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/testimonials/woman-5.jpg' ) ); ?>" alt="Portrait of Layla Al Maktoum from Dubai" loading="lazy">
                        <div>
                            <strong>Layla Al Maktoum</strong>
                            <span>Azure Holdings · UAE</span>
                        </div>
                    </div>
                </footer>
            </article>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="homepage-cta">
    <div class="container">
        <h2>Ready to Experience the Extraordinary?</h2>
        <p>Contact us today and let us tailor the perfect luxury solution for you.</p>
        <a href="<?php echo esc_url( site_url( '/contact' ) ); ?>" class="button button-gold">Get In Touch</a>
    </div>
</section>

<?php get_footer(); ?>
