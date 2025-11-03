<?php
/**
 * Single template for CPT: aclasslife_listing
 * Theme: hostinger-ai-theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

/** ---------- Helpers ---------- */
function alp_first_meta(array $keys, $fallback = '') {
    $id = get_the_ID();
    foreach ($keys as $k) {
        $v = get_post_meta($id, $k, true);
        if ($v !== '' && $v !== null) return is_scalar($v) ? $v : wp_json_encode($v);
    }
    return $fallback;
}
function alp_clean_number($v) {
    if ($v === '' || $v === null) return '';
    $v = preg_replace('/[^\d\.\,]/', '', (string)$v);
    $v = str_replace(',', '', $v);
    return is_numeric($v) ? $v : '';
}
function alp_gallery_ids() {
    // Try plugin/ACF/gallery conventions
    $id = get_the_ID();
    $ids = get_post_meta($id, '_alp_gallery', true);
    if (is_string($ids) && strpos($ids, ',') !== false) {
        $ids = array_filter(array_map('absint', explode(',', $ids)));
    }
    if (empty($ids) && function_exists('get_field')) {
        $acf = get_field('gallery', $id);
        if (is_array($acf)) {
            $ids = array_map(static function($i){ return is_array($i)?absint($i['ID']??0):absint($i); }, $acf);
            $ids = array_filter($ids);
        }
    }
    return is_array($ids) ? $ids : [];
}
function alp_term_badges($tax, $class = 'alp-badges') {
    $terms = get_the_terms(get_the_ID(), $tax);
    if (empty($terms) || is_wp_error($terms)) return '';
    $out = '<div class="'.esc_attr($class).'">';
    foreach ($terms as $t) $out .= '<span class="alp-badge">'.esc_html($t->name).'</span>';
    return $out.'</div>';
}

/** ---------- Data ---------- */
$price_raw = alp_first_meta(['price','_price','listing_price','asking_price','price_formatted']);
$price_num = alp_clean_number($price_raw);
$location  = alp_first_meta(['location','_location','city','_city','country','_country']);
$status    = alp_first_meta(['status','_status','availability','_availability','sale_status']);

$phone     = alp_first_meta(['agent_phone','_agent_phone','contact_phone']);
$email     = alp_first_meta(['agent_email','_agent_email','contact_email']);
$agent     = alp_first_meta(['agent_name','_agent_name','broker_name','_broker']);

$specs = [
    'Year'         => alp_first_meta(['year','_year','year_built']),
    'Make'         => alp_first_meta(['make','_make','brand']),
    'Model'        => alp_first_meta(['model','_model']),
    'Hours'        => alp_first_meta(['hours','_hours']),
    'Mileage'      => alp_first_meta(['mileage','_mileage']),
    'Range'        => alp_first_meta(['range','_range']),
    'Speed'        => alp_first_meta(['speed','_speed']),
    'Length'       => alp_first_meta(['length','_length','loa']),
    'Beam'         => alp_first_meta(['beam','_beam']),
    'Cabins'       => alp_first_meta(['cabins','_cabins']),
    'Guests'       => alp_first_meta(['guests','_guests']),
    'Crew'         => alp_first_meta(['crew','_crew']),
    'Bedrooms'     => alp_first_meta(['bedrooms','_bedrooms']),
    'Bathrooms'    => alp_first_meta(['bathrooms','_bathrooms']),
    'Area (sqft)'  => alp_first_meta(['area','_area','sqft']),
    'Parking'      => alp_first_meta(['parking','_parking']),
    'Reference'    => alp_first_meta(['reference','_reference','ref']),
];

$map_iframe = alp_first_meta(['map_iframe','_map_iframe']);
$lat        = alp_first_meta(['lat','_lat']);
$lng        = alp_first_meta(['lng','_lng']);
$gallery    = alp_gallery_ids();
?>
<main id="primary" class="alp-single-listing">

    <section class="alp-hero container">
        <div class="alp-breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span>›</span>
            <a href="<?php echo esc_url( get_post_type_archive_link('aclasslife_listing') ); ?>">Asset Listings</a><span>›</span>
            <span><?php the_title(); ?></span>
        </div>

        <h1 class="alp-title"><?php the_title(); ?></h1>

        <div class="alp-hero-meta">
            <?php if ($price_raw): ?><div class="alp-price"><?php echo esc_html($price_raw); ?></div><?php endif; ?>
            <?php if ($location): ?><div class="alp-location"><?php echo esc_html($location); ?></div><?php endif; ?>
            <?php if ($status): ?><span class="alp-badge"><?php echo esc_html($status); ?></span><?php endif; ?>
        </div>

        <?php echo alp_term_badges('asset_type'); ?>

        <div class="alp-cta-row">
            <?php echo do_shortcode('[alp_make_offer_button]'); ?>
            <?php if ($phone): ?><a class="alp-btn-outline" href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $phone)); ?>">Call agent</a><?php endif; ?>
            <a class="alp-btn-outline" href="#alp-book-viewing">Schedule viewing</a>
        </div>
    </section>

    <section class="alp-media container">
        <?php if ($gallery): ?>
            <div class="alp-media-grid">
                <?php foreach ($gallery as $i => $aid): ?>
                    <a class="alp-media-item" href="<?php echo esc_url(wp_get_attachment_image_url($aid,'full')); ?>" data-lightbox="listing">
                        <?php echo wp_get_attachment_image($aid, $i===0 ? 'xl':'large', false, ['loading' => $i===0?'eager':'lazy']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php elseif (has_post_thumbnail()): ?>
            <div class="alp-media-featured"><?php the_post_thumbnail('xl', ['loading'=>'eager']); ?></div>
        <?php endif; ?>
    </section>

    <section class="alp-keyfacts container">
        <div class="alp-card">
            <ul class="alp-facts">
                <?php foreach ($specs as $label => $val): if (!$val) continue; ?>
                    <li><span><?php echo esc_html($label); ?></span><strong><?php echo esc_html($val); ?></strong></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="alp-card alp-sticky-cta">
            <?php if ($price_raw): ?><div class="alp-sticky-price"><?php echo esc_html($price_raw); ?></div><?php endif; ?>
            <?php echo do_shortcode('[alp_make_offer_button]'); ?>
            <a class="alp-btn-secondary" href="#alp-contact">Ask a question</a>
        </div>
    </section>

    <section class="alp-content container">
        <div class="alp-main">
            <?php while (have_posts()): the_post(); the_content(); endwhile; ?>

            <?php
            $features = alp_first_meta(['features','_features']);
            if ($features) :
                $items = array_filter(array_map('trim', explode(',', wp_strip_all_tags($features))));
                if ($items): ?>
                    <h2>Features</h2>
                    <ul class="alp-features">
                        <?php foreach ($items as $f): ?><li><?php echo esc_html($f); ?></li><?php endforeach; ?>
                    </ul>
                <?php endif; endif; ?>

            <?php if ($map_iframe || ($lat && $lng)) : ?>
                <h2>Location</h2>
                <div class="alp-map">
                    <?php if ($map_iframe) {
                        echo wp_kses($map_iframe, [
                            'iframe'=>['src'=>true,'width'=>true,'height'=>true,'style'=>true,'loading'=>true,'referrerpolicy'=>true,'allowfullscreen'=>true]
                        ]);
                    } else {
                        $src = sprintf('https://maps.google.com/maps?q=%s,%s&z=15&output=embed',
                            rawurlencode($lat), rawurlencode($lng));
                        echo '<iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="'.esc_url($src).'" width="100%" height="420"></iframe>';
                    } ?>
                </div>
            <?php endif; ?>

            <div id="alp-book-viewing" class="alp-card alp-book">
                <h2>Book a viewing</h2>
                <?php echo do_shortcode('[contact-form-7 id="book-viewing" title="Book a viewing"]'); ?>
            </div>
        </div>

        <aside class="alp-aside" id="alp-contact">
            <div class="alp-card alp-agent">
                <h3>Your agent</h3>
                <div class="alp-agent-row">
                    <div class="alp-agent-photo">
                        <?php
                        $agent_id = absint(alp_first_meta(['agent_user_id','_agent_user_id'], 0));
                        if ($agent_id) echo get_avatar($agent_id, 96);
                        ?>
                    </div>
                    <div>
                        <?php if ($agent): ?><div class="alp-agent-name"><?php echo esc_html($agent); ?></div><?php endif; ?>
                        <?php if ($email): ?><a class="alp-link" href="mailto:<?php echo esc_attr($email); ?>">Email</a><?php endif; ?>
                        <?php if ($phone): ?><a class="alp-link" href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $phone)); ?>">Call</a><?php endif; ?>
                    </div>
                </div>
                <div class="alp-agent-cta">
                    <?php echo do_shortcode('[alp_make_offer_button]'); ?>
                </div>
            </div>
        </aside>
    </section>

    <?php
    // Related by asset_type (fallback to latest)
    $term_ids = [];
    $terms = get_the_terms(get_the_ID(), 'asset_type');
    if (!empty($terms) && !is_wp_error($terms)) $term_ids = wp_list_pluck($terms, 'term_id');

    $rel_args = [
        'post_type' => 'aclasslife_listing',
        'posts_per_page' => 3,
        'post__not_in' => [ get_the_ID() ],
    ];
    if ($term_ids) {
        $rel_args['tax_query'] = [[
            'taxonomy' => 'asset_type',
            'field'    => 'term_id',
            'terms'    => $term_ids,
        ]];
    }
    $rel = new WP_Query($rel_args);
    if ($rel->have_posts()): ?>
        <section class="alp-related container">
            <h2>Similar listings</h2>
            <div class="alp-related-grid">
                <?php while ($rel->have_posts()): $rel->the_post(); ?>
                    <article class="alp-related-card">
                        <a href="<?php the_permalink(); ?>">
                            <div class="alp-thumb"><?php the_post_thumbnail('medium_large'); ?></div>
                            <h3><?php the_title(); ?></h3>
                            <div class="alp-related-meta">
                                <span><?php echo esc_html(get_post_meta(get_the_ID(), 'price', true)); ?></span>
                                <span><?php echo esc_html(get_post_meta(get_the_ID(), 'location', true)); ?></span>
                            </div>
                        </a>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // Minimal JSON-LD Product schema (helps CTR vs competitors)
    $images = [];
    if ($gallery) foreach ($gallery as $aid) $images[] = wp_get_attachment_image_url($aid,'full');
    elseif (has_post_thumbnail()) $images[] = get_the_post_thumbnail_url(get_the_ID(),'full');

    $schema = [
        '@context'=>'https://schema.org',
        '@type'=>'Product',
        'name'=>get_the_title(),
        'description'=> wp_strip_all_tags( get_the_excerpt() ?: get_the_content() ),
        'image'=>$images,
        'brand'=> alp_first_meta(['make','brand'], ''),
        'sku'  => alp_first_meta(['reference','ref','_sku'], ''),
        'offers'=>[
            '@type'=>'Offer',
            'url'=> get_permalink(),
            'availability'=> $status ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder',
        ],
    ];
    if ($price_num) { $schema['offers']['price'] = $price_num; $schema['offers']['priceCurrency'] = alp_first_meta(['currency','_currency'],'USD'); }
    ?>
    <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); ?></script>

</main>

<?php get_footer(); ?>

