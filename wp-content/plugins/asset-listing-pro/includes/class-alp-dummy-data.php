<?php
/**
 * Dummy data seeder for Asset Listing Pro.
 *
 * @package Asset_Listing_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Populate the custom post type with curated demo listings.
 */
class ALP_Dummy_Data {

    /**
     * Boot the dummy data utilities.
     */
    public static function init() {
        add_action( 'admin_init', array( __CLASS__, 'maybe_seed' ) );

        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            \WP_CLI::add_command( 'alp seed-dummy-listings', array( __CLASS__, 'wp_cli_seed' ) );
        }
    }

    /**
     * Trigger the seeder when the plugin is activated.
     */
    public static function activate() {
        self::seed();
    }

    /**
     * Run the seeder if it has not yet been executed.
     */
    public static function maybe_seed() {
        if ( get_option( 'alp_dummy_listings_seeded', false ) ) {
            return;
        }

        self::seed();
    }

    /**
     * WP-CLI callback to seed the dummy listings.
     *
     * @param array $args       Positional arguments.
     * @param array $assoc_args Associative arguments.
     */
    public static function wp_cli_seed( $args, $assoc_args ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
        $force = isset( $assoc_args['force'] );

        if ( $force ) {
            delete_option( 'alp_dummy_listings_seeded' );
        }

        self::seed();

        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            \WP_CLI::success( 'Dummy listings created.' );
        }
    }

    /**
     * Create the demo listings and assign taxonomy terms, metadata, and thumbnails.
     */
    public static function seed() {
        $listings = self::get_listings();

        if ( empty( $listings ) ) {
            return;
        }

        foreach ( $listings as $listing ) {
            self::create_or_update_listing( $listing );
        }

        update_option( 'alp_dummy_listings_seeded', current_time( 'mysql' ) );
    }

    /**
     * Insert or update a single listing entry.
     *
     * @param array $listing Listing configuration.
     */
    private static function create_or_update_listing( $listing ) {
        if ( empty( $listing['title'] ) ) {
            return;
        }

        $defaults = array(
            'title'       => '',
            'content'     => '',
            'type'        => 'Jet',
            'price'       => 0,
            'location'    => '',
            'specs'       => array(),
            'description' => '',
        );

        $listing = wp_parse_args( $listing, $defaults );

        $post = get_page_by_title( $listing['title'], OBJECT, 'aclasslife_listing' );

        $author_id = get_current_user_id();
        if ( ! $author_id ) {
            $admin_user = get_users(
                array(
                    'role'   => 'administrator',
                    'number' => 1,
                    'fields' => array( 'ID' ),
                )
            );

            if ( ! empty( $admin_user ) ) {
                $author_id = (int) $admin_user[0]->ID;
            } else {
                $author_id = 1;
            }
        }

        $post_data = array(
            'post_title'   => $listing['title'],
            'post_content' => $listing['content'],
            'post_type'    => 'aclasslife_listing',
            'post_status'  => 'publish',
            'post_author'  => $author_id,
        );

        if ( $post instanceof WP_Post ) {
            $post_data['ID'] = $post->ID;
            wp_update_post( $post_data );
            $post_id = $post->ID;
        } else {
            $post_data['post_name'] = sanitize_title( $listing['title'] );
            $post_id                 = wp_insert_post( $post_data );
        }

        if ( is_wp_error( $post_id ) || ! $post_id ) {
            return;
        }

        $term_id = self::ensure_asset_type_term( $listing['type'] );
        if ( $term_id ) {
            wp_set_post_terms( $post_id, array( $term_id ), 'asset_type', false );
        }

        update_post_meta( $post_id, '_acl_listing_price', (float) $listing['price'] );
        update_post_meta( $post_id, '_acl_listing_location', sanitize_text_field( $listing['location'] ) );
        update_post_meta( $post_id, '_acl_specifications', self::format_specifications( $listing['specs'] ) );
        update_post_meta( $post_id, '_acl_commission_status', 'unpaid' );

    }

    /**
     * Ensure the asset type taxonomy term exists and return its ID.
     *
     * @param string $type Asset type label.
     * @return int Term ID.
     */
    private static function ensure_asset_type_term( $type ) {
        $type = sanitize_text_field( $type );

        $term = get_term_by( 'name', $type, 'asset_type' );
        if ( $term && ! is_wp_error( $term ) ) {
            return (int) $term->term_id;
        }

        $result = wp_insert_term( $type, 'asset_type' );
        if ( is_wp_error( $result ) ) {
            return 0;
        }

        return (int) $result['term_id'];
    }

    /**
     * Convert the specification array into HTML list markup.
     *
     * @param array $specs Specification entries.
     * @return string
     */
    private static function format_specifications( $specs ) {
        if ( empty( $specs ) || ! is_array( $specs ) ) {
            return '';
        }

        $items = array();
        foreach ( $specs as $spec ) {
            if ( empty( $spec['label'] ) || empty( $spec['value'] ) ) {
                continue;
            }

            $items[] = sprintf(
                '<li><strong>%s:</strong> %s</li>',
                esc_html( $spec['label'] ),
                esc_html( $spec['value'] )
            );
        }

        if ( empty( $items ) ) {
            return '';
        }

        return '<ul>' . implode( '', $items ) . '</ul>';
    }

    /**
     * Provide the curated dummy listings.
     *
     * @return array
     */
    private static function get_listings() {
        return array(
            // Jets.
            array(
                'title'   => '2020 Gulfstream G650ER Private Jet',
                'content' => self::generate_description(
                    'An immaculate, low-time Gulfstream G650ER featuring bespoke cabin finishes and fully managed maintenance ' .
                    'records. The aircraft delivers non-stop routes from London to Singapore with a quiet, spacious interior ideal for executive travel.'
                ),
                'type'     => 'Jet',
                'price'    => 65000000,
                'location' => 'London, United Kingdom',
                'specs'    => array(
                    array(
                        'label' => 'Model',
                        'value' => 'Gulfstream G650ER',
                    ),
                    array(
                        'label' => 'Year',
                        'value' => '2020',
                    ),
                    array(
                        'label' => 'Hours flown',
                        'value' => '1,250',
                    ),
                ),
            ),
            array(
                'title'   => '2019 Bombardier Global 7500 Long Range Jet',
                'content' => self::generate_description(
                    'Configured for up to 16 passengers, this Global 7500 showcases an advanced Nuage chaise lounge suite and a dedicated crew rest area. '
                    . 'The airframe remains under manufacturer warranty and includes the Smart Link Plus connected diagnostics package.'
                ),
                'type'     => 'Jet',
                'price'    => 72000000,
                'location' => 'Montreal, Canada',
                'specs'    => array(
                    array(
                        'label' => 'Model',
                        'value' => 'Bombardier Global 7500',
                    ),
                    array(
                        'label' => 'Year',
                        'value' => '2019',
                    ),
                    array(
                        'label' => 'Hours flown',
                        'value' => '1,850',
                    ),
                ),
            ),
            array(
                'title'   => '2022 Dassault Falcon 8X Tri-Jet',
                'content' => self::generate_description(
                    'Delivered new in 2022, this Falcon 8X combines exceptional runway performance with a custom Pierrejean-design interior. '
                    . 'Cabin connectivity, Ka-band Wi-Fi, and full warranty coverage remain in effect for seamless global missions.'
                ),
                'type'     => 'Jet',
                'price'    => 59000000,
                'location' => 'Paris, France',
                'specs'    => array(
                    array(
                        'label' => 'Model',
                        'value' => 'Dassault Falcon 8X',
                    ),
                    array(
                        'label' => 'Year',
                        'value' => '2022',
                    ),
                    array(
                        'label' => 'Hours flown',
                        'value' => '620',
                    ),
                ),
            ),
            array(
                'title'   => '2018 Embraer Lineage 1000E Executive Jet',
                'content' => self::generate_description(
                    'Featuring five distinct cabin zones, this Lineage 1000E offers a full-size master suite with shower and walk-in wardrobe. '
                    . 'The aircraft is enrolled on MSP Gold and Embraer Executive Care for predictable operating costs.'
                ),
                'type'     => 'Jet',
                'price'    => 48000000,
                'location' => 'São Paulo, Brazil',
                'specs'    => array(
                    array(
                        'label' => 'Model',
                        'value' => 'Embraer Lineage 1000E',
                    ),
                    array(
                        'label' => 'Year',
                        'value' => '2018',
                    ),
                    array(
                        'label' => 'Hours flown',
                        'value' => '2,400',
                    ),
                ),
            ),
            array(
                'title'   => '2021 Cessna Citation Longitude Business Jet',
                'content' => self::generate_description(
                    'A beautifully maintained Citation Longitude with Garmin G5000 avionics, factory paint protection, and refreshed club seating. '
                    . 'Ideal for transcontinental missions with outstanding climb performance and low operating costs.'
                ),
                'type'     => 'Jet',
                'price'    => 28000000,
                'location' => 'Wichita, United States',
                'specs'    => array(
                    array(
                        'label' => 'Model',
                        'value' => 'Cessna Citation Longitude',
                    ),
                    array(
                        'label' => 'Year',
                        'value' => '2021',
                    ),
                    array(
                        'label' => 'Hours flown',
                        'value' => '540',
                    ),
                ),
            ),
            array(
                'title'   => '2017 Boeing Business Jet 2 VIP Configuration',
                'content' => self::generate_description(
                    'Configured with a private master suite, conference lounge, and cinema room, this BBJ2 provides unrivalled range and comfort. '
                    . 'The aircraft benefits from a recent 6C check and upgraded Collins Venue cabin management system.'
                ),
                'type'     => 'Jet',
                'price'    => 125000000,
                'location' => 'Dubai, United Arab Emirates',
                'specs'    => array(
                    array(
                        'label' => 'Model',
                        'value' => 'Boeing Business Jet 2',
                    ),
                    array(
                        'label' => 'Year',
                        'value' => '2017',
                    ),
                    array(
                        'label' => 'Hours flown',
                        'value' => '3,850',
                    ),
                ),
            ),
            array(
                'title'   => '2023 HondaJet Elite II Corporate Jet',
                'content' => self::generate_description(
                    'A factory-fresh HondaJet Elite II offering the Quiet Gallery interior package, increased range tanks, and intuitive Garmin G3000 avionics. '
                    . 'Perfect for regional executives seeking efficiency without sacrificing cabin comfort.'
                ),
                'type'     => 'Jet',
                'price'    => 7800000,
                'location' => 'Tokyo, Japan',
                'specs'    => array(
                    array(
                        'label' => 'Model',
                        'value' => 'HondaJet Elite II',
                    ),
                    array(
                        'label' => 'Year',
                        'value' => '2023',
                    ),
                    array(
                        'label' => 'Hours flown',
                        'value' => '120',
                    ),
                ),
            ),

            // Yachts.
            array(
                'title'   => '2016 Lürssen 95m Titania Superyacht',
                'content' => self::generate_description(
                    'Titania delivers extraordinary entertaining spaces across seven decks, including a beach club spa, elevator, and dual master suites. '
                    . 'Extensive refits in 2021 upgraded audio-visual systems and zero-speed stabilisers for unmatched comfort.'
                ),
                'type'     => 'Yacht',
                'price'    => 115000000,
                'location' => 'Monaco, Monaco',
                'specs'    => array(
                    array(
                        'label' => 'Length',
                        'value' => '95 m',
                    ),
                    array(
                        'label' => 'Year built',
                        'value' => '2016',
                    ),
                    array(
                        'label' => 'Cabins',
                        'value' => '7',
                    ),
                ),
            ),
            array(
                'title'   => '2018 Feadship 85m Infinity Explorer',
                'content' => self::generate_description(
                    'Infinity Explorer is an oceangoing Feadship with an 8,000nm range, fully certified helideck, and panoramic sky lounge. '
                    . 'A 2022 refit introduced a wellness deck with hammam, beauty salon, and climate-controlled tender garage.'
                ),
                'type'     => 'Yacht',
                'price'    => 92000000,
                'location' => 'Amsterdam, Netherlands',
                'specs'    => array(
                    array(
                        'label' => 'Length',
                        'value' => '85 m',
                    ),
                    array(
                        'label' => 'Year built',
                        'value' => '2018',
                    ),
                    array(
                        'label' => 'Cabins',
                        'value' => '6',
                    ),
                ),
            ),
            array(
                'title'   => '2020 Benetti 70m Luminosity Hybrid Yacht',
                'content' => self::generate_description(
                    'Luminosity showcases hybrid propulsion, triple-level glass atrium, and a 264-square-metre beach club. '
                    . 'Her sustainable design blends ocean-view gymnasiums with a botanical wellness centre for extended voyages.'
                ),
                'type'     => 'Yacht',
                'price'    => 65000000,
                'location' => 'Genoa, Italy',
                'specs'    => array(
                    array(
                        'label' => 'Length',
                        'value' => '70 m',
                    ),
                    array(
                        'label' => 'Year built',
                        'value' => '2020',
                    ),
                    array(
                        'label' => 'Cabins',
                        'value' => '8',
                    ),
                ),
            ),
            array(
                'title'   => '2014 Oceanco 88m Nirvana Charter Yacht',
                'content' => self::generate_description(
                    'Nirvana features a dramatic two-level main salon, Balinese-inspired spa, and six-metre contraflow pool with convertible dance floor. '
                    . 'Twin helidecks and a cinema suite create a premier charter platform with proven global cruising.'
                ),
                'type'     => 'Yacht',
                'price'    => 98000000,
                'location' => 'Dubai, United Arab Emirates',
                'specs'    => array(
                    array(
                        'label' => 'Length',
                        'value' => '88 m',
                    ),
                    array(
                        'label' => 'Year built',
                        'value' => '2014',
                    ),
                    array(
                        'label' => 'Cabins',
                        'value' => '6',
                    ),
                ),
            ),
            array(
                'title'   => '2021 Sunseeker 52m Ocean Club',
                'content' => self::generate_description(
                    'This tri-deck Sunseeker Ocean Club features folding sea terraces, a bespoke beach club, and a Portuguese bridge lounge. '
                    . 'Twin MTU engines deliver a 20-knot cruise with economical range for Mediterranean adventures.'
                ),
                'type'     => 'Yacht',
                'price'    => 34000000,
                'location' => 'Southampton, United Kingdom',
                'specs'    => array(
                    array(
                        'label' => 'Length',
                        'value' => '52 m',
                    ),
                    array(
                        'label' => 'Year built',
                        'value' => '2021',
                    ),
                    array(
                        'label' => 'Cabins',
                        'value' => '5',
                    ),
                ),
            ),
            array(
                'title'   => '2019 Heesen 60m Serenity Performance Yacht',
                'content' => self::generate_description(
                    'Serenity combines fast displacement hull technology with a glass-wrapped sky lounge and expansive sundeck jacuzzi. '
                    . 'The yacht carries a 13-metre chase boat and touch-and-go helipad for remote exploration.'
                ),
                'type'     => 'Yacht',
                'price'    => 48000000,
                'location' => 'Nice, France',
                'specs'    => array(
                    array(
                        'label' => 'Length',
                        'value' => '60 m',
                    ),
                    array(
                        'label' => 'Year built',
                        'value' => '2019',
                    ),
                    array(
                        'label' => 'Cabins',
                        'value' => '6',
                    ),
                ),
            ),
            array(
                'title'   => '2015 Azimut 35m Mediterranean Cruiser',
                'content' => self::generate_description(
                    'A stylish Azimut 35M with Zebrano wood interiors, convertible sky lounge, and stabilised cruising. '
                    . 'Recent upgrades include tropical air-conditioning, new teak decking, and a Williams 505 tender.'
                ),
                'type'     => 'Yacht',
                'price'    => 7800000,
                'location' => 'Barcelona, Spain',
                'specs'    => array(
                    array(
                        'label' => 'Length',
                        'value' => '35 m',
                    ),
                    array(
                        'label' => 'Year built',
                        'value' => '2015',
                    ),
                    array(
                        'label' => 'Cabins',
                        'value' => '4',
                    ),
                ),
            ),

            // Mansions.
            array(
                'title'   => '2022 Bel Air Skyline Estate',
                'content' => self::generate_description(
                    'Perched above Los Angeles, the Bel Air Skyline Estate showcases a suspended infinity pool, private wellness pavilion, and panoramic canyon views. '
                    . 'Smart home integration controls lighting, climate, and security across every wing of the residence.'
                ),
                'type'     => 'Mansion',
                'price'    => 150000000,
                'location' => 'Los Angeles, United States',
                'specs'    => array(
                    array(
                        'label' => 'Size',
                        'value' => '28,000 sq ft',
                    ),
                    array(
                        'label' => 'Bedrooms',
                        'value' => '12',
                    ),
                    array(
                        'label' => 'Bathrooms',
                        'value' => '18',
                    ),
                ),
            ),
            array(
                'title'   => '2019 Knightsbridge Regency Manor',
                'content' => self::generate_description(
                    'A distinguished London manor with dual reception halls, subterranean swimming pool, and private cinema. '
                    . 'Meticulously restored period detailing blends with contemporary wellness amenities and a secure panic room.'
                ),
                'type'     => 'Mansion',
                'price'    => 82000000,
                'location' => 'London, United Kingdom',
                'specs'    => array(
                    array(
                        'label' => 'Size',
                        'value' => '18,500 sq ft',
                    ),
                    array(
                        'label' => 'Bedrooms',
                        'value' => '8',
                    ),
                    array(
                        'label' => 'Bathrooms',
                        'value' => '11',
                    ),
                ),
            ),
            array(
                'title'   => '2020 Palm Jumeirah Signature Villa',
                'content' => self::generate_description(
                    'Situated on a premier Palm Jumeirah frond, this signature villa features a private beach, indoor-outdoor entertaining areas, and marble-clad spa suites. '
                    . 'Floor-to-ceiling glazing frames uninterrupted skyline vistas across the Arabian Gulf.'
                ),
                'type'     => 'Mansion',
                'price'    => 45000000,
                'location' => 'Dubai, United Arab Emirates',
                'specs'    => array(
                    array(
                        'label' => 'Size',
                        'value' => '12,500 sq ft',
                    ),
                    array(
                        'label' => 'Bedrooms',
                        'value' => '7',
                    ),
                    array(
                        'label' => 'Bathrooms',
                        'value' => '9',
                    ),
                ),
            ),
            array(
                'title'   => '2018 Sentosa Cove Waterfront Mansion',
                'content' => self::generate_description(
                    'A rare corner plot residence in Sentosa Cove boasting dual private berths, designer koi ponds, and a rooftop entertainment terrace. '
                    . 'The interior showcases imported Italian stonework and a custom glass elevator serving all levels.'
                ),
                'type'     => 'Mansion',
                'price'    => 38000000,
                'location' => 'Sentosa, Singapore',
                'specs'    => array(
                    array(
                        'label' => 'Size',
                        'value' => '9,800 sq ft',
                    ),
                    array(
                        'label' => 'Bedrooms',
                        'value' => '6',
                    ),
                    array(
                        'label' => 'Bathrooms',
                        'value' => '8',
                    ),
                ),
            ),
            array(
                'title'   => '2021 Sydney Harbourview Contemporary Residence',
                'content' => self::generate_description(
                    'An architectural statement overlooking Sydney Harbour with floating staircases, 20-metre lap pool, and climate-controlled wine gallery. '
                    . 'Extensive terracing provides seamless indoor-outdoor entertaining across multiple levels.'
                ),
                'type'     => 'Mansion',
                'price'    => 27000000,
                'location' => 'Sydney, Australia',
                'specs'    => array(
                    array(
                        'label' => 'Size',
                        'value' => '8,600 sq ft',
                    ),
                    array(
                        'label' => 'Bedrooms',
                        'value' => '5',
                    ),
                    array(
                        'label' => 'Bathrooms',
                        'value' => '7',
                    ),
                ),
            ),
            array(
                'title'   => '2017 Lake Como Historic Palazzo',
                'content' => self::generate_description(
                    'Nestled on the shores of Lake Como, this restored palazzo offers terraced gardens, private dock, and frescoed salons dating back to the 18th century. '
                    . 'Modern upgrades include geothermal climate control and a subterranean wellness retreat.'
                ),
                'type'     => 'Mansion',
                'price'    => 56000000,
                'location' => 'Como, Italy',
                'specs'    => array(
                    array(
                        'label' => 'Size',
                        'value' => '14,200 sq ft',
                    ),
                    array(
                        'label' => 'Bedrooms',
                        'value' => '9',
                    ),
                    array(
                        'label' => 'Bathrooms',
                        'value' => '10',
                    ),
                ),
            ),
        );
    }

    /**
     * Build a multi-paragraph marketing description.
     *
     * @param string $summary Summary paragraph.
     * @return string
     */
    private static function generate_description( $summary ) {
        $intro = 'Discover an exceptional opportunity to acquire a flagship asset curated for discerning buyers. ' .
            'Every element has been considered to elevate comfort, style, and long-term value.';

        $closing = 'Connect with the AClassLife acquisitions team to arrange a confidential viewing itinerary and review the detailed provenance dossier.';

        return sprintf('<p>%s</p><p>%s</p><p>%s</p>', wp_kses_post( $intro ), wp_kses_post( $summary ), wp_kses_post( $closing ) );
    }
}
