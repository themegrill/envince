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
 * @param  array $packages
 * @return array
 */
function envince_demo_importer_packages( $packages ) {
	$new_packages = array(
		'envince-free' => array(
			'name'    => esc_html__( 'Envince', 'envince' ),
			'preview' => 'https://demo.themegrill.com/envince/',
		),
	);

	return array_merge( $new_packages, $packages );
}
add_filter( 'themegrill_demo_importer_packages', 'envince_demo_importer_packages' );
