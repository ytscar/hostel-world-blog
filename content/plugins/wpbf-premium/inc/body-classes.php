<?php
/**
 * Body classes.
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Body classes.
 *
 * Add body classes based on certain conditions.
 *
 * @param array $classes The body classes.
 *
 * @return array The updated body classes.
 */
function wpbf_premium_body_classes( $classes ) {

	$push_menu     = get_theme_mod( 'menu_off_canvas_push' );
	$menu_position = get_theme_mod( 'menu_position' );

	if ( $push_menu && 'menu-off-canvas' === $menu_position ) {
		$classes[] = 'wpbf-push-menu-right';
	}

	if ( $push_menu && 'menu-off-canvas-left' === $menu_position ) {
		$classes[] = 'wpbf-push-menu-left';
	}

	if ( wpbf_has_responsive_breakpoints() ) {

		$classes[] = 'wpbf-responsive-breakpoints';

		$classes[] = 'wpbf-mobile-breakpoint-' . wpbf_breakpoint_mobile();
		$classes[] = 'wpbf-medium-breakpoint-' . wpbf_breakpoint_medium();
		$classes[] = 'wpbf-desktop-breakpoint-' . wpbf_breakpoint_desktop();

	}

	return $classes;

}
add_filter( 'body_class', 'wpbf_premium_body_classes' );