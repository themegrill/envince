<?php
/**
 * Handles the theme's theme customizer functionality.
 *
 * @package    envince
 * @author     Rajeeb Banstola <rajeebthegreat@gmail.com>
 * @copyright  Copyright (c) 2014, Rajeeb Banstola
 * @link       http://rajeebbanstola.com.np
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-3.0.html
 */

/* Custom Controls */
add_action( 'customize_register', 'envince_custom_controls' );

/**
 * Loads custom control for layout settings
 */
function envince_custom_controls() {

	require_once get_template_directory() . '/inc/admin/customize-control-layout.php';
	require_once get_template_directory() . '/inc/admin/customize-control-important-links.php';

}

/* Theme Customizer setup. */
add_action( 'customize_register', 'envince_customize_register' );

/**
 * Sets up the theme customizer sections, controls, and settings.
 *
 * @since  1.0.0
 * @access public
 * @param  object  $wp_customize
 * @return void
 */
function envince_customize_register( $wp_customize ) {

	/* Load JavaScript files. */
	add_action( 'customize_preview_init', 'envince_enqueue_customizer_scripts' );

	/* Enable live preview for WordPress theme features. */
	$wp_customize->get_setting( 'blogname' )->transport              = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport       = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'header_image' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'background_image' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'background_position_x' )->transport = 'postMessage';
	$wp_customize->get_setting( 'background_repeat' )->transport     = 'postMessage';
	$wp_customize->get_setting( 'background_attachment' )->transport = 'postMessage';

	/* Remove the WordPress display header text control. */
	$wp_customize->remove_control( 'display_header_text' );

	/* Adds ThemeGrill Important Links Section */
	$wp_customize->add_section('envince_important_links',
		array(
			'priority' => 1,
			'title'    => esc_html__('Envince Important Links', 'envince'),
		)
	);

	$wp_customize->add_setting('envince_important_links',
		array(
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'envince_links_sanitize'
		)
	);

	$wp_customize->add_control(
		new envince_Important_Links(
			$wp_customize, 'important_links', array(
				'label'    => esc_html__('Important Links', 'envince'),
				'section'  => 'envince_important_links',
				'settings' => 'envince_important_links'
			)
		)
	);

	/* Add 'site_title' setting */
	$wp_customize->add_setting(
		'envince_site_title',
		array(
			'default'  	           => '1',
			'sanitize_callback'    => 'envince_sanitize_checkbox',
		)
	);

	/* Add 'site_title' control */
	$wp_customize->add_control(
		'envince_site_title',
		array(
			'label'    	 => esc_html__( 'Display Site Title', 'envince' ),
			'section' 	 => 'title_tagline',
			'type'    	 => 'checkbox'
		)
	);

	/* Add 'site_description' setting */
	$wp_customize->add_setting(
		'envince_site_description',
		array(
			'default'  	           => '1',
			'sanitize_callback'    => 'envince_sanitize_checkbox',
		)
	);

	/* Add 'site_description' control */
	$wp_customize->add_control(
		'envince_site_description',
		array(
			'label'    	 => esc_html__( 'Display Site Tagline', 'envince' ),
			'section' 	 => 'title_tagline',
			'type'    	 => 'checkbox'
		)
	);

	if ( ! function_exists('the_custom_logo') ) {
		/* Add the 'logo' upload setting. */
		$wp_customize->add_setting(
			'envince_logo',
			array(
				'default'  			   => '',
				'capability'           => 'edit_theme_options',
				'transport'            => 'postMessage',
				'sanitize_callback'	   => 'esc_url_raw',
			)
		);

		/* Add the upload control for the 'envince_logo' setting. */
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'envince_logo',
				array(
					'label'    => esc_html__( 'Logo Upload', 'envince' ),
					'section'  => 'title_tagline',
					'settings' => 'envince_logo',
				)
			)
		);
	}

	/* Add 'layout' section */
	$wp_customize->add_section(
			'envince_layout',
			array(
				'title'      	=> esc_html__( 'Layout', 'envince' ),
				'description'	=> 'Select main content and sidebar layout for blog.(Note: Layout for individual posts and pages can be selected in the respective posts and pages.',
				'priority'   	=> 50,
				'capability'	=> 'edit_theme_options'
			)
		);

	/* Add 'sidebar layout' setting. */
	$wp_customize->add_setting(
			'envince_sidebar',
			array(
				'default'  			   => 'content-sidebar',
				'capability'           => 'edit_theme_options',
				'transport'            => 'refresh',
				'sanitize_callback'	   => 'envince_sanitize_layout_sidebar',
			)
		);

	/* Add 'sidebar layout' control. */
	$wp_customize->add_control(
		new Layout_Picker_Custom_Control(
			$wp_customize,
			'envince_sidebar',
			array(
				'label'    => esc_html__( 'Layout Sidebar', 'envince' ),
				'section'  => 'envince_layout'
			)
		)
	);

	/* Add 'layout style' setting. */
	$wp_customize->add_setting(
			'envince_layout_style',
			array(
				'default'  			   => 'boxed',
				'capability'           => 'edit_theme_options',
				'transport'            => 'refresh',
				'sanitize_callback'	   =>  'envince_sanitize_layout_style',
			)
		);

	/* Add 'layout style' control. */
	$wp_customize->add_control(
		'envince_layout_style',
		array(
			'label'    	 => esc_html__( 'Layout Style', 'envince' ),
			'section' 	 => 'envince_layout',
			'type'    	 => 'select',
			'choices'    => array(
				'wide'  => 'Wide Layout',
				'boxed' => 'Boxed Layout',
			)
		)
	);

	/* Add 'layout width' setting. */
	$wp_customize->add_setting(
			'envince_layout_width',
			array(
				'default'  			   => '1170',
				'capability'           => 'edit_theme_options',
				'transport'            => 'refresh',
				'sanitize_callback'	   =>  'envince_sanitize_layout_width',
			)
		);

	/* Add 'layout width' control. */
	$wp_customize->add_control(
		'envince_layout_width',
		array(
			'label'    => esc_html__( 'Layout Width', 'envince' ),
			'section'  => 'envince_layout',
			'type'     => 'select',
			'choices'  => array(
				'1600' => '1600px',
				'1170' => '1170px (Default)',
				'992'  => '992px',
				'768'  => '768px',
			)
		)
	);

	$wp_customize->add_setting(
		'envince_footer_widgets',
		array(
			'default'            => 4,
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'envince_sanitize_integer'
		)
	);

	$wp_customize->add_control(
		'envince_footer_widgets',
		array(
			'label'    => esc_html__( 'Choose the number of widget area you want in footer', 'envince' ),
			'section'  => 'envince_layout',
			'type'     => 'select',
			'choices'    => array(
				'1' => esc_html__('1 Footer Widget Area', 'envince'),
				'2' => esc_html__('2 Footer Widget Area', 'envince'),
				'3' => esc_html__('3 Footer Widget Area', 'envince'),
				'4' => esc_html__('4 Footer Widget Area', 'envince')
			),
		)
	);

	/* Add 'header_info' section */
	$wp_customize->add_section(
		'envince_header_info',
		array(
			'title'      => esc_html__( 'Header Info', 'envince' ),
			'priority'   => 60,
			'capability' => 'edit_theme_options'
		)
	);

	/* Add the 'phone info' setting. */
	$wp_customize->add_setting(
		'envince_phone_info',
		array(
			'default'  			   => '',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'	   => 'envince_sanitize_integer',
		)
	);

	/* Add 'phone info' control. */
	$wp_customize->add_control(
		'envince_phone_info',
		array(
			'label'    => esc_html__( 'Phone Number', 'envince' ),
			'section'  => 'envince_header_info',
			'settings' => 'envince_phone_info',
		)
	);

	/* Add the 'email info' setting. */
	$wp_customize->add_setting(
		'envince_email_info',
		array(
			'default'  			   => '',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'	   =>  'sanitize_email',
		)
	);

	/* Add 'email info' control. */
	$wp_customize->add_control(
		'envince_email_info',
		array(
			'label'    => esc_html__( 'Email', 'envince' ),
			'section'  => 'envince_header_info',
			'settings' => 'envince_email_info',
		)
	);

	/* Add the 'location info' setting. */
	$wp_customize->add_setting(
		'envince_location_info',
		array(
			'default'  			   => '',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'	   =>  'envince_sanitize_text',
		)
	);

	/* Add the upload control for the 'location info' setting. */
	$wp_customize->add_control(
		'envince_location_info',
		array(
			'label'    => esc_html__( 'Location', 'envince' ),
			'section'  => 'envince_header_info',
			'settings' => 'envince_location_info',
		)
	);

	/* Category Color Panel */
	$wp_customize->add_panel(
		'envince_category_color_panel',
		array(
			'priority'     => 200,
			'title'        => __('Category Color Options', 'envince'),
			'capability'   => 'edit_theme_options',
			'description'  => __('Change the color of each category items as you want.', 'envince')
		)
	);

	$wp_customize->add_section(
		'envince_category_color_setting',
		array(
			'priority' => 10,
			'title'    => __('Category Color Settings', 'envince'),
			'panel'    => 'envince_category_color_panel'
		)
	);

	$i = 1;
	$args = array(
		'orderby'    => 'id',
		'hide_empty' =>  0
	);

	$categories       = get_categories( $args );
	$wp_category_list = array();
	foreach ( $categories as $category_list ) {
		$wp_category_list[$category_list->cat_ID] = $category_list->cat_name;

		$wp_customize->add_setting(
			'envince_category_color_'.get_cat_id($wp_category_list[$category_list->cat_ID]),
			array(
				'default'              => '',
				'capability'           => 'edit_theme_options',
				'sanitize_callback'    => 'envince_color_option_hex_sanitize',
				'sanitize_js_callback' => 'envince_color_escaping_option_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
					'envince_category_color_'.get_cat_id($wp_category_list[$category_list->cat_ID]),
					array(
						'label'    => sprintf(__('%s', 'envince'), $wp_category_list[$category_list->cat_ID] ),
						'section'  => 'envince_category_color_setting',
						'settings' => 'envince_category_color_'.get_cat_id($wp_category_list[$category_list->cat_ID]),
						'priority' => $i
					)
				)
		);
		$i++;
	}

	/* Add 'miscellaneous settings' section */
	$wp_customize->add_section(
		'envince_miscellaneous',
		array(
			'title'      => esc_html__( 'Miscellaneous Settings', 'envince' ),
			'priority'   => 100,
			'capability' => 'edit_theme_options'
		)
	);

	/* Add the 'featured image setting for single post/page' setting. */
	$wp_customize->add_setting(
		'estore_remove_featured_image',
		array(
			'default'  			   => '',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'	   => 'envince_sanitize_checkbox',
		)
	);

	/* Add 'remove featured image' control. */
	$wp_customize->add_control(
		'estore_remove_featured_image',
		array(
			'label'    => esc_html__( 'Remove Featured Image from Single Post', 'envince' ),
			'section'  => 'envince_miscellaneous',
			'settings' => 'estore_remove_featured_image',
			'type'     => 'checkbox'
		)
	);
} // customizer section end

/**
 * Sanitize Integer
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function envince_sanitize_integer( $int ) {
	if( is_numeric( $int ) ) {
		return intval( $int );
	} else {
		return '';
	}
}

/**
 * Sanitize text
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function envince_sanitize_text( $txt ) {
	return wp_kses_post( force_balance_tags( $txt ) );
}
/**
 * Sanitize text
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function envince_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}
/**
 * Sanitize layout sidebar radiobutton
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function envince_sanitize_layout_sidebar( $layout_sidebar ) {
	$valid = array(
		'full-width' 				=> 'full-width',
		'sidebar-content' 			=> 'sidebar-content',
		'content-sidebar' 			=> 'content-sidebar',
		'sidebar-sidebar-content' 	=> 'sidebar-sidebar-content',
		'sidebar-content-sidebar' 	=> 'sidebar-content-sidebar',
		'content-sidebar-sidebar' 	=> 'content-sidebar-sidebar',
	);

	if ( array_key_exists( $layout_sidebar, $valid ) ) {
		return $layout_sidebar;
	} else {
		return '';
	}
}

/**
 * Sanitize layout style
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function envince_sanitize_layout_style( $layout_style ) {
	$valid = array(
		'wide' 		=> 'Wide Layout',
		'boxed' 	=> 'Boxed Layout',
	);

	if ( array_key_exists( $layout_style, $valid ) ) {
		return $layout_style;
	} else {
		return '';
	}
}
/**
 * Sanitize layout width
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function envince_sanitize_layout_width( $layout_width ) {
	$valid = array(
	   '1600' => '1600px',
		'1170' => '1170px (Default)',
		'992' => '992px',
		'768' => '768px',
	);

	if ( array_key_exists( $layout_width, $valid ) ) {
		return $layout_width;
	} else {
		return '';
	}
}
/**
 * Sanitize color option
 *
 * @since  1.0.1
 * @access public
 * @return sanitized color output
 */
function envince_color_option_hex_sanitize($color) {
	if ($unhashed = sanitize_hex_color_no_hash($color))
		return '#' . $unhashed;

	return $color;
}
/**
 * Escape sanitized color
 *
 * @since  1.0.1
 * @access public
 * @return escaped color output
 */
function envince_color_escaping_option_sanitize($input) {
	$input = esc_attr($input);
	return $input;
}
/**
 * Loads theme customizer JavaScript.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function envince_enqueue_customizer_scripts() {

	/* Use the .min script if SCRIPT_DEBUG is turned off. */
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script(
		'envince-customize',
		trailingslashit( get_template_directory_uri() ) . "js/customize{$suffix}.js",
		array( 'jquery' ),
		null,
		true
	);
}

/*
 * Custom Scripts
 */
add_action( 'customize_controls_print_footer_scripts', 'envince_customizer_custom_scripts' );

function envince_customizer_custom_scripts() { ?>
<style>
	/* Theme Instructions Panel CSS */
	li#accordion-section-envince_important_links h3.accordion-section-title, li#accordion-section-envince_important_links h3.accordion-section-title:focus { background-color: #289DCC !important; color: #fff !important; }
	li#accordion-section-envince_important_links h3.accordion-section-title:hover { background-color: #289DCC !important; color: #fff !important; }
	li#accordion-section-envince_important_links h3.accordion-section-title:after { color: #fff !important; }
	/* Upsell button CSS */
	.themegrill-pro-info,
	.customize-control-envince-important-links a {
		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#8fc800+0,8fc800+100;Green+Flat+%232 */
		background: #008EC2;
		color: #fff;
		display: block;
		margin: 15px 0 0;
		padding: 5px 0;
		text-align: center;
		font-weight: 600;
	}

	.customize-control-envince-important-links a{
		padding: 8px 0;
	}

	.themegrill-pro-info:hover,
	.customize-control-envince-important-links a:hover {
		color: #ffffff;
		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#006e2e+0,006e2e+100;Green+Flat+%233 */
		background:#2380BA;
	}
</style>
<?php
}
