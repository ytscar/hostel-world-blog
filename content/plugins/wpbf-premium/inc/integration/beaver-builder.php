<?php
/**
 * Beaver Builder integration.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Global color palette.
 *
 * @param array $colors The colors.
 *
 * @return array The updated colors.
 */
function wpbf_beaver_builder_color_palette( $colors ) {

	$color_palette = wpbf_color_palette();

	if ( ! empty( $color_palette ) ) {
		$colors = $color_palette;
	}

	return $colors;

}
add_filter( 'fl_builder_color_presets', 'wpbf_beaver_builder_color_palette' );

/**
 * Remove Beaver Builder post type from settings page post type array.
 *
 * @param array $post_types The post types
 *
 * @return array The updated post types
 */
function wpbf_beaver_builder_remove_settings_post_type( $post_types ) {

	unset( $post_types['fl-builder-template'] );

	return $post_types;

}
add_filter( 'wpbf_template_settings_post_type_array', 'wpbf_beaver_builder_remove_settings_post_type' );
add_filter( 'wpbf_blog_layouts_archive_array', 'wpbf_beaver_builder_remove_settings_post_type' );
