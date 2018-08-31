<?php
/**
 * Functions for configuring demo importer.
 *
 * @package Importer/Functions
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Setup demo importer config.
 *
 * @deprecated 1.5.0
 *
 * @param  array $demo_config Demo config.
 * @return array
 */
function envince_demo_importer_packages( $packages ) {
	$new_packages = array(
		'envince-free'     => array(
			'name'    => esc_html__( 'Envince', 'envince' ),
			'preview' => 'https://demo.themegrill.com/envince/',
		),
		'envince-pro'      => array(
			'name'     => __( 'Envince Pro', 'envince' ),
			'preview'  => 'https://demo.themegrill.com/envince-pro/',
			'pro_link' => 'https://themegrill.com/themes/envince/',
		),
		'envince-pro-food' => array(
			'name'     => __( 'Envince Pro Food', 'envince' ),
			'preview'  => 'https://demo.themegrill.com/envince-pro-food/',
			'pro_link' => 'https://themegrill.com/themes/envince/',
		),
	);

	return array_merge( $new_packages, $packages );
}

add_filter( 'themegrill_demo_importer_packages', 'envince_demo_importer_packages' );

/**
 * After demo imported AJAX action.
 *
 * @see envince_set_cat_colors()
 */
add_filter( 'themegrill_customizer_demo_import_settings', 'envince_set_cat_colors', 20, 3 );

/**
 * Set categories color settings in theme customizer.
 *
 * Note: Used rarely, if theme_mod keys are based on term ID.
 *
 * @param  array  $data
 * @param  array  $demo_data
 * @param  string $demo_id
 *
 * @return array
 */
function envince_set_cat_colors( $data, $demo_data, $demo_id ) {
	$cat_colors    = array();
	$cat_prevent   = array();
	$wp_categories = array();

	// Format the data based on demo ID.
	switch ( $demo_id ) {
		case 'envince-pro-food':
			$wp_categories = array(
				1  => 'Uncategorized',
				2  => 'Italian food',
				4  => 'Mexican food',
				5  => 'American food',
				6  => 'japanese cuisine',
				8  => 'Featured items',
				9  => 'Dessert',
				12 => 'Drinks and Beverages',
			);
			break;
	}

	// Fetch categories color settings.
	foreach ( $wp_categories as $term_id => $term_name ) {
		if ( ! empty( $data['mods'][ 'envince_category_color_' . $term_id ] ) ) {
			$cat_colors[ 'envince_category_color_' . $term_id ] = $data['mods'][ 'envince_category_color_' . $term_id ];
		}
	}

	// Set categories color settings properly.
	foreach ( $wp_categories as $term_id => $term_name ) {
		if ( ! empty( $data['mods'][ 'envince_category_color_' . $term_id ] ) ) {
			$term  = get_term_by( 'name', $term_name, 'category' );
			$color = $cat_colors[ 'envince_category_color_' . $term_id ];

			if ( is_object( $term ) && $term->term_id ) {
				$cat_prevent[]                                              = $term->term_id;
				$data['mods'][ 'envince_category_color_' . $term->term_id ] = $color;

				// Prevent deleting stored color settings.
				if ( ! in_array( $term_id, $cat_prevent ) ) {
					unset( $data['mods'][ 'envince_category_color_' . $term_id ] );
				}
			}
		}
	}

	return $data;
}
