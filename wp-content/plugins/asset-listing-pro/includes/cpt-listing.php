<?php
/**
 * Custom post type registration for Asset Listing Pro.
 *
 * @package Asset_Listing_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register the AClassLife Listing custom post type.
 */
function alp_register_aclasslife_listing_cpt() {
    $labels = array(
        'name'                  => _x( 'Asset Listings', 'Post type general name', 'asset-listing-pro' ),
        'singular_name'         => _x( 'Asset Listing', 'Post type singular name', 'asset-listing-pro' ),
        'menu_name'             => _x( 'Asset Listings', 'Admin Menu text', 'asset-listing-pro' ),
        'name_admin_bar'        => _x( 'Asset Listing', 'Add New on Toolbar', 'asset-listing-pro' ),
        'add_new'               => __( 'Add New', 'asset-listing-pro' ),
        'add_new_item'          => __( 'Add New Asset Listing', 'asset-listing-pro' ),
        'new_item'              => __( 'New Asset Listing', 'asset-listing-pro' ),
        'edit_item'             => __( 'Edit Asset Listing', 'asset-listing-pro' ),
        'view_item'             => __( 'View Asset Listing', 'asset-listing-pro' ),
        'all_items'             => __( 'All Asset Listings', 'asset-listing-pro' ),
        'search_items'          => __( 'Search Asset Listings', 'asset-listing-pro' ),
        'parent_item_colon'     => __( 'Parent Asset Listings:', 'asset-listing-pro' ),
        'not_found'             => __( 'No asset listings found.', 'asset-listing-pro' ),
        'not_found_in_trash'    => __( 'No asset listings found in Trash.', 'asset-listing-pro' ),
        'featured_image'        => __( 'Listing Image', 'asset-listing-pro' ),
        'set_featured_image'    => __( 'Set listing image', 'asset-listing-pro' ),
        'remove_featured_image' => __( 'Remove listing image', 'asset-listing-pro' ),
        'use_featured_image'    => __( 'Use as listing image', 'asset-listing-pro' ),
        'archives'              => __( 'Asset listing archives', 'asset-listing-pro' ),
        'insert_into_item'      => __( 'Insert into listing', 'asset-listing-pro' ),
        'uploaded_to_this_item' => __( 'Uploaded to this listing', 'asset-listing-pro' ),
        'filter_items_list'     => __( 'Filter listings list', 'asset-listing-pro' ),
        'items_list_navigation' => __( 'Listings list navigation', 'asset-listing-pro' ),
        'items_list'            => __( 'Listings list', 'asset-listing-pro' ),
    );

    $supports = array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'custom-fields' );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_rest'       => true,
        'menu_position'      => 26,
        'menu_icon'          => 'dashicons-building',
        'supports'           => $supports,
        'rewrite'            => array(
            'slug'       => 'asset-listing',
            'with_front' => false,
        ),
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
    );

    register_post_type( 'aclasslife_listing', $args );
}
add_action( 'init', 'alp_register_aclasslife_listing_cpt' );

/**
 * Register taxonomy for asset types.
 */
function alp_register_asset_type_taxonomy() {
    $labels = array(
        'name'              => _x( 'Asset Types', 'taxonomy general name', 'asset-listing-pro' ),
        'singular_name'     => _x( 'Asset Type', 'taxonomy singular name', 'asset-listing-pro' ),
        'search_items'      => __( 'Search Asset Types', 'asset-listing-pro' ),
        'all_items'         => __( 'All Asset Types', 'asset-listing-pro' ),
        'parent_item'       => __( 'Parent Asset Type', 'asset-listing-pro' ),
        'parent_item_colon' => __( 'Parent Asset Type:', 'asset-listing-pro' ),
        'edit_item'         => __( 'Edit Asset Type', 'asset-listing-pro' ),
        'update_item'       => __( 'Update Asset Type', 'asset-listing-pro' ),
        'add_new_item'      => __( 'Add New Asset Type', 'asset-listing-pro' ),
        'new_item_name'     => __( 'New Asset Type Name', 'asset-listing-pro' ),
        'menu_name'         => __( 'Asset Types', 'asset-listing-pro' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array(
            'slug' => 'asset-type',
        ),
    );

    register_taxonomy( 'asset_type', array( 'aclasslife_listing' ), $args );
}
add_action( 'init', 'alp_register_asset_type_taxonomy' );

/**
 * Determine if the current user can manage listing meta data.
 *
 * @return bool
 */
function alp_can_manage_listing_meta() {
    return current_user_can( 'edit_posts' );
}

/**
 * Sanitize text based listing meta values.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function alp_sanitize_listing_model_meta( $value ) {
    return sanitize_text_field( (string) $value );
}

/**
 * Normalize numeric strings into positive floating point values.
 *
 * @param mixed $value Raw value.
 * @return float|null
 */
function alp_sanitize_listing_numeric_meta( $value ) {
    if ( is_array( $value ) ) {
        return null;
    }

    $value = trim( (string) $value );

    if ( '' === $value ) {
        return null;
    }

    $value = str_replace( ' ', '', $value );

    if ( false !== strpos( $value, ',' ) && false !== strpos( $value, '.' ) ) {
        $value = str_replace( ',', '', $value );
    } elseif ( false !== strpos( $value, ',' ) ) {
        $value = str_replace( ',', '.', $value );
    }

    if ( ! is_numeric( $value ) ) {
        return null;
    }

    $number = (float) $value;

    return $number >= 0 ? $number : null;
}

/**
 * Sanitize flight hours meta values.
 *
 * @param mixed $value Raw value.
 * @return float|null
 */
function alp_sanitize_listing_hours_meta( $value ) {
    $sanitized = alp_sanitize_listing_numeric_meta( $value );

    return null !== $sanitized ? round( $sanitized, 1 ) : null;
}

/**
 * Sanitize vessel length meta values.
 *
 * @param mixed $value Raw value.
 * @return float|null
 */
function alp_sanitize_listing_length_meta( $value ) {
    $sanitized = alp_sanitize_listing_numeric_meta( $value );

    return null !== $sanitized ? round( $sanitized, 2 ) : null;
}

/**
 * Sanitize jewellery carat weight meta values.
 *
 * @param mixed $value Raw value.
 * @return float|null
 */
function alp_sanitize_listing_carats_meta( $value ) {
    $sanitized = alp_sanitize_listing_numeric_meta( $value );

    return null !== $sanitized ? round( $sanitized, 2 ) : null;
}

/**
 * Sanitize gemstone text meta values.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function alp_sanitize_listing_gemstone_meta( $value ) {
    return sanitize_text_field( (string) $value );
}

/**
 * Register asset type specific meta fields.
 */
function alp_register_asset_type_meta_fields() {
    register_post_meta(
        'aclasslife_listing',
        '_acl_listing_model',
        array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'alp_sanitize_listing_model_meta',
            'auth_callback'     => 'alp_can_manage_listing_meta',
        )
    );

    register_post_meta(
        'aclasslife_listing',
        '_acl_listing_hours',
        array(
            'type'              => 'number',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'alp_sanitize_listing_hours_meta',
            'auth_callback'     => 'alp_can_manage_listing_meta',
        )
    );

    register_post_meta(
        'aclasslife_listing',
        '_acl_listing_length',
        array(
            'type'              => 'number',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'alp_sanitize_listing_length_meta',
            'auth_callback'     => 'alp_can_manage_listing_meta',
        )
    );

    register_post_meta(
        'aclasslife_listing',
        '_acl_listing_carats',
        array(
            'type'              => 'number',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'alp_sanitize_listing_carats_meta',
            'auth_callback'     => 'alp_can_manage_listing_meta',
        )
    );

    register_post_meta(
        'aclasslife_listing',
        '_acl_listing_gemstone',
        array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'alp_sanitize_listing_gemstone_meta',
            'auth_callback'     => 'alp_can_manage_listing_meta',
        )
    );
}
add_action( 'init', 'alp_register_asset_type_meta_fields' );
