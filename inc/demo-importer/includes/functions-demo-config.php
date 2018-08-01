<?php
/**
 * Functions for configuring demo importer.
 *
 * @author   ThemeGrill
 * @category Admin
 * @package  Importer/Functions
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setup demo importer packages.
 *
 * @deprecated 1.5.0
 *
 * @param  array $packages
 * @return array
 */
function envince_demo_importer_packages( $packages ) {
	$new_packages = array(
		'envince-free' => array(
			'name'    => esc_html__( 'Envince', 'envince' ),
			'preview' => 'https://demo.themegrill.com/envince/',
		),
		'envince-pro'  => array(
			'name'     => __( 'Envince Pro', 'envince' ),
			'preview'  => 'https://demo.themegrill.com/envince-pro/',
			'pro_link' => 'https://themegrill.com/themes/envince/'
		),
		'envince-pro-food'  => array(
			'name'     => __( 'Envince Pro Food', 'envince' ),
			'preview'  => 'https://demo.themegrill.com/envince-pro-food/',
			'pro_link' => 'https://themegrill.com/themes/envince/'
		)
	);

	return array_merge( $new_packages, $packages );
}
add_filter( 'themegrill_demo_importer_packages', 'envince_demo_importer_packages' );
