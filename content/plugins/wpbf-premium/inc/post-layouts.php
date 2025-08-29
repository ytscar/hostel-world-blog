<?php
/**
 * Post Layouts.
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Premium Add-On custom post types.
  *
 * @return array The post types array.
 */
function wpbf_get_post_layout_settings() {

	$post_types = array();

	$wpbf_settings = get_option( 'wpbf_settings' );

	if ( isset( $wpbf_settings['wpbf_post_layouts'] ) ) {

		$saved_post_types = $wpbf_settings['wpbf_post_layouts'];

		foreach ( $saved_post_types as $saved_post_type ) {
			$post_types[] = $saved_post_type;
		}

	}

	return $post_types;

};

/**
 * Add post types.
 *
 * Add post types to wpbf_singles based on global settings.
 *
 * @param array $post_types The post types.
 *
 * @return array The updated post types.
 */
add_filter( 'wpbf_singles', function ( $post_types ) {

	// Add Premium post types to post types array
	$post_types = array_merge( $post_types, wpbf_get_post_layout_settings() );

	return $post_types;

} );

/**
 * Sidebar layout.
 *
 * @param string $sidebar The sidebar position.
 *
 * @return string The updated sidebar position.
 */
add_filter( 'wpbf_sidebar_layout', function ( $sidebar ) {

	$saved_post_types = wpbf_get_post_layout_settings();

	foreach ( $saved_post_types as $saved_post_type ) {

		if ( is_singular( $saved_post_type ) ) {
			$cpt_sidebar_layout = get_theme_mod( $saved_post_type . '_sidebar_layout', 'global' );
			$sidebar = $cpt_sidebar_layout !== 'global' ? $cpt_sidebar_layout : $sidebar;
		}

	}

	return $sidebar;

} );

/**
 * Post layout.
 *
 * @param array $post_layout The post layout.
 *
 * @return array The updated post layout.
 */
add_filter( 'wpbf_post_layout', function ( $post_layout ) {

	$saved_post_types = wpbf_get_post_layout_settings();

	foreach ( $saved_post_types as $saved_post_type ) {

		if ( is_singular( $saved_post_type ) ) {

			$template_parts_header  = get_theme_mod( $saved_post_type . '_sortable_header', array( 'title', 'meta', 'featured' ) );
			$template_parts_footer  = get_theme_mod( $saved_post_type . '_sortable_footer', array( 'categories' ) );
			$post_layout            = 'default';
			$style                  = get_theme_mod( $saved_post_type . '_post_style', 'plain' );
			$style                 .= get_theme_mod( $saved_post_type . '_boxed_image_stretched', false ) ? ' stretched' : '';

			$post_layout = array(
				'post_layout'            => $post_layout,
				'template_parts_header'  => $template_parts_header,
				'template_parts_footer'  => $template_parts_footer,
				'style'                  => $style,
			);

		}

	}

	return $post_layout;

} );

/**
 * Post navigation.
 *
 * @param array $display_post_links The display status.
 */
add_filter( 'wpbf_display_post_links', function ( $display_post_links ) {

	$saved_post_types = wpbf_get_post_layout_settings();

	foreach ( $saved_post_types as $saved_post_type ) {

		if ( is_singular( $saved_post_type ) ) {

			if ( 'hide' === get_theme_mod( $saved_post_type . '_post_nav' ) ) {
				$display_post_links = false;
			} elseif ( 'hide' === get_theme_mod( 'single_post_nav' ) ) {
				$display_post_links = true; // Re-enable if post navigation was hidden globally before.
			}

		}

	}

	return $display_post_links;

}, 20 );

/**
 * Next post link.
 *
 * @param string $next The next post link.
 *
 * @return string The updated post link.
 */
add_filter( 'wpbf_next_post_link', function ( $next ) {

	$saved_post_types = wpbf_get_post_layout_settings();

	foreach ( $saved_post_types as $saved_post_type ) {

		if ( is_singular( $saved_post_type ) ) {

			$next = __( 'Next Post &rarr;', 'page-builder-framework' );

			if ( 'default' === get_theme_mod( $saved_post_type . '_post_nav', 'show' ) ) {
				$next = '%title &rarr;';
			}

		}

	}

	return $next;

}, 20 );

/**
 * Previous post link.
 *
 * @param string $prev The prev post link.
 *
 * @return string The updated post link.
 */
add_filter( 'wpbf_previous_post_link', function ( $prev ) {

	$saved_post_types = wpbf_get_post_layout_settings();

	foreach ( $saved_post_types as $saved_post_type ) {

		if ( is_singular( $saved_post_type ) ) {

			$prev = __( '&larr; Previous Post', 'page-builder-framework' );

			if ( 'default' === get_theme_mod( $saved_post_type . '_post_nav', 'show' ) ) {
				$prev = '&larr; %title';
			}

		}

	}

	return $prev;

}, 20 );
