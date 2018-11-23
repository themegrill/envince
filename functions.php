<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    envince
 * @subpackage Functions
 * @version    1.0.0
 * @author     Rajeeb Banstola <rajeebthegreat@gmail.com>
 * @copyright  Copyright (c) 2014, Rajeeb Banstola
 * @link       http://rajeebbanstola.com.np
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Get the template directory and make sure it has a trailing slash. */
$envince_dir = trailingslashit( get_template_directory() );

/* Load the Hybrid Core framework and theme files. */
require_once( $envince_dir . 'library/hybrid.php' );

/* Launch the Hybrid Core framework. */
new Hybrid();

/* Load the About page */
if ( is_admin() ) {
	require get_template_directory() . '/inc/admin/class-envince-admin.php';
}

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'envince_theme_setup', 5 );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function envince_theme_setup() {

	/* Load files. */
	require_once( trailingslashit( get_template_directory() ) . 'inc/theme.php' );

	/* Load stylesheets. */
	add_theme_support(
		'hybrid-core-styles',
		array( 'style', 'parent', 'gallery' )
	);

	/* Enable custom template hierarchy. */
	add_theme_support( 'hybrid-core-template-hierarchy' );

	/* The best thumbnail/image script ever. */
	add_theme_support( 'get-the-image' );

	/* Breadcrumbs. Yay! */
	add_theme_support( 'breadcrumb-trail' );

	/* Pagination. */
	add_theme_support( 'loop-pagination' );

	/* Nicer [gallery] shortcode implementation. */
	add_theme_support( 'cleaner-gallery' );

	/* Better captions for themes to style. */
	add_theme_support( 'cleaner-caption' );

	/* Automatically add feed links to <head>. */
	add_theme_support( 'automatic-feed-links' );

	/* Support for title tag since WP 4.1 */
	add_theme_support( 'title-tag' );

	// Gutenberg layout support.
	add_theme_support( 'align-wide' );

	/* Post formats. */
	add_theme_support(
		'post-formats',
		array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' )
	);

	/* Adds the support for the Custom Logo introduced in WordPress 4.5 */
	add_theme_support( 'custom-logo',
		array(
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Support for selective refresh widgets in Customizer
	add_theme_support( 'customize-selective-refresh-widgets' );

	/* Handle content width for embeds and images. */
	hybrid_set_content_width( 1280 );
}

/* Load bootstrap css and js files */
add_action( 'wp_enqueue_scripts', 'envince_scripts', 1 );

/**
 * Tells WordPress to load the scripts needed for the framework using the wp_enqueue_script() function.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function envince_scripts() {

	$suffix = hybrid_get_min_suffix();

	wp_register_style( 'envince-googlefonts', '//fonts.googleapis.com/css?family=Raleway|Open+Sans' );
	wp_enqueue_style( 'envince-googlefonts' );

	/* Load the bootstrap files */
	wp_enqueue_style(
		'bootstrap',
		trailingslashit( get_template_directory_uri() ) . "css/bootstrap{$suffix}.css"
	);

	wp_enqueue_script(
		'bootstrap',
		trailingslashit( get_template_directory_uri() ) . "js/bootstrap{$suffix}.js",
		array( 'jquery' ),
		null,
		true
	);

	/* Load the font awesome icons */
	wp_enqueue_style(
		'font-awesome',
		trailingslashit( get_template_directory_uri() ) . "css/font-awesome{$suffix}.css"
	);

}

/* Displays the site logo */
if ( ! function_exists( 'envince_the_custom_logo' ) ) {
	/**
	 * Displays the optional custom logo.
	 */
	function envince_the_custom_logo() {
		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}
	}
}

/**
 * Load Demo Importer Configs.
 */
if ( class_exists( 'TG_Demo_Importer' ) ) {
	require get_template_directory() . '/inc/demo-config.php';
}

/**
 * Display the related posts
 */
if ( ! function_exists( 'envince_related_posts_function' ) ) {

	function envince_related_posts_function() {
		wp_reset_postdata();
		global $post;

		// Define shared post arguments
		$args = array(
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'ignore_sticky_posts'    => 1,
			'orderby'                => 'rand',
			'post__not_in'           => array( $post->ID ),
			'posts_per_page'         => 3,
		);

		// Related by categories.
		if ( get_theme_mod( 'envince_related_posts', 'categories' ) == 'categories' ) {
			$cats                 = wp_get_post_categories( $post->ID, array( 'fields' => 'ids' ) );
			$args['category__in'] = $cats;
		}

		// Related by tags.
		if ( get_theme_mod( 'envince_related_posts', 'categories' ) == 'tags' ) {
			$tags            = wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) );
			$args['tag__in'] = $tags;

			// If no tags added, return.
			if ( ! $tags ) {
				$break = true;
			}
		}

		$query = ! isset( $break ) ? new WP_Query( $args ) : new WP_Query;

		return $query;

	}
}
