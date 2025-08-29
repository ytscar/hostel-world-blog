<?php
/**
 * Elementor integration.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Remove Elementor post type from settings page post type array.
 *
 * @param array $post_types The post types
 *
 * @return array The updated post types
 */
function wpbf_elementor_remove_settings_post_type( $post_types ) {

	unset( $post_types['elementor_library'] );

	return $post_types;

}
add_filter( 'wpbf_template_settings_post_type_array', 'wpbf_elementor_remove_settings_post_type' );
add_filter( 'wpbf_blog_layouts_archive_array', 'wpbf_elementor_remove_settings_post_type' );

/**
 * Fix Elementor line-height issue.
 *
 * https://github.com/pojome/elementor/issues/3197
 */
function wpbf_elementor_line_height_fix() {

	$line_height_h1 = get_theme_mod( 'page_h1_line_height' );
	$line_height_h2 = get_theme_mod( 'page_h2_line_height' );
	$line_height_h3 = get_theme_mod( 'page_h3_line_height' );
	$line_height_h4 = get_theme_mod( 'page_h4_line_height' );
	$line_height_h5 = get_theme_mod( 'page_h5_line_height' );
	$line_height_h6 = get_theme_mod( 'page_h6_line_height' );

	if ( $line_height_h1 ) {
		echo 'h1.elementor-heading-title, h2.elementor-heading-title, h3.elementor-heading-title, h4.elementor-heading-title, h5.elementor-heading-title, h6.elementor-heading-title {';
		echo sprintf( 'line-height: %s;', esc_attr( $line_height_h1 ) );
		echo '}';
	}

	if ( $line_height_h2 ) {
		echo 'h2.elementor-heading-title {';
		echo sprintf( 'line-height: %s;', esc_attr( $line_height_h2 ) );
		echo '}';
	}

	if ( $line_height_h3 ) {
		echo 'h3.elementor-heading-title {';
		echo sprintf( 'line-height: %s;', esc_attr( $line_height_h3 ) );
		echo '}';
	}

	if ( $line_height_h4 ) {
		echo 'h4.elementor-heading-title {';
		echo sprintf( 'line-height: %s;', esc_attr( $line_height_h4 ) );
		echo '}';
	}

	if ( $line_height_h5 ) {
		echo 'h5.elementor-heading-title {';
		echo sprintf( 'line-height: %s;', esc_attr( $line_height_h5 ) );
		echo '}';
	}

	if ( $line_height_h6 ) {
		echo 'h6.elementor-heading-title {';
		echo sprintf( 'line-height: %s;', esc_attr( $line_height_h6 ) );
		echo '}';
	}

}
add_action( 'wpbf_before_customizer_css', 'wpbf_elementor_line_height_fix', 20 );

/**
 * Global color palette.
 *
 * @param array $config The configuration.
 *
 * @return array The updated configuration.
 */
function wpbf_elementor_color_palette( $config ) {

	$color_palette = wpbf_color_palette();

	if ( empty( $color_palette ) ) return $config;

	$colors_array = array();

	foreach ( $color_palette as $key => $color ) {
		$colors_array[ $key+1 ] = array( 'value' => $color );
	}

	if ( ! isset( $config['schemes'] ) ) {
		$config['schemes'] = array( 'items' => array() );
	}

	if ( ! isset( $config['schemes']['items']['color-picker'] ) ) {
		$config['schemes']['items']['color-picker'] = array( 'items' => array() );
	}

	$config['schemes']['items']['color-picker']['items'] = $colors_array;

	return $config;

}
add_filter( 'elementor/editor/localize_settings', 'wpbf_elementor_color_palette', 100 );

/**
 * Auto add custom sections to Elementor cpt support.
 *
 * Caused issues with Beaver Builder, let's revert this just in case.
 */
function wpbf_elementor_cpt_support() {
	$post_types = get_option( 'elementor_cpt_support', array() );

	if ( ! in_array( 'wpbf_hooks', $post_types, true ) ) {
		array_push( $post_types, 'wpbf_hooks' );
		update_option( 'elementor_cpt_support', $post_types, true );
	}
}
// add_action( 'admin_init', 'wpbf_elementor_cpt_support' );
