<?php
/**
 * Theme mods.
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Remove 404 page.
 */
function wpbf_remove_404() {

	if ( get_theme_mod( '404_custom' ) ) {
		remove_action( 'wpbf_404', 'wpbf_do_404' );
	}

}
add_action( 'wp', 'wpbf_remove_404' );

/**
 * Add custom 404 page.
 */
function wpbf_custom_404() {

	$custom_404 = get_theme_mod( '404_custom' );

	if ( $custom_404 ) {
		echo do_shortcode( $custom_404 );
	}

}
add_action( 'wpbf_404', 'wpbf_custom_404' );

/**
 * Add custom footer.
 */
function wpbf_custom_footer() {

	$custom_footer = get_theme_mod( 'footer_custom' );

	if ( $custom_footer ) {
		echo do_shortcode( $custom_footer );
	}

}
add_action( 'wpbf_before_footer', 'wpbf_custom_footer' );

/**
 * Head scripts.
 */
function wpbf_custom_head_scripts_823932() {

	$head_scripts = get_theme_mod( 'head_scripts' );

	if ( $head_scripts ) {
		echo $head_scripts;
	}

}
add_action( 'wp_head', 'wpbf_custom_head_scripts_823932' );

/**
 * Header scripts.
 */
function wpbf_custom_header_scripts_103802138() {

	$header_scripts = get_theme_mod( 'header_scripts' );

	if ( $header_scripts ) {
		echo $header_scripts;
	}

}
add_action( 'wpbf_body_open', 'wpbf_custom_header_scripts_103802138' );

/**
 * Footer scripts.
 */
function wpbf_custom_footer_scripts_0848420() {

	$footer_scripts = get_theme_mod( 'footer_scripts' );

	if ( $footer_scripts ) {
		echo $footer_scripts;
	}

}
add_action( 'wp_footer', 'wpbf_custom_footer_scripts_0848420' );

/**
 * Off canvas search icon
 *
 * Adds a search icon to the off canvas navigation.
 */
function wpbf_search_menu_icon_off_canvas() {

	// Stop here if menu search icon is not enabled.
	if ( ! get_theme_mod( 'menu_search_icon' ) ) {
		return;
	}

	// Stop here if off canvas menu is not selected.
	if ( ! wpbf_is_off_canvas_menu() || 'menu-off-canvas-left' === get_theme_mod( 'menu_position' ) ) {
		return;
	}

	echo wpbf_search_menu_item( false, false );

}
add_action( 'wpbf_before_menu_toggle', 'wpbf_search_menu_icon_off_canvas' );

/**
 * Include pre header in sticky navigation.
 */
function wpbf_pre_header_sticky() {

	if ( get_theme_mod( 'pre_header_sticky' ) ) {
		remove_action( 'wpbf_pre_header', 'wpbf_do_pre_header' );
		add_action( 'wpbf_before_main_navigation', 'wpbf_do_pre_header' );
	}

}
add_action( 'wp', 'wpbf_pre_header_sticky' );

/**
 * Call to Action button.
 *
 * Construct Call to Action button.
 *
 * @return string Call to Action button.
 */
function wpbf_cta_button() {

	// Vars.
	$button_text   = get_theme_mod( 'cta_button_text' ) ? get_theme_mod( 'cta_button_text' ) : __( 'Call to Action', 'wpbfpremium' );
	$button_link   = get_theme_mod( 'cta_button_url' ) ? get_theme_mod( 'cta_button_url' ) : '#';
	$button_target = get_theme_mod( 'cta_button_target' ) ? ' target="_blank"' : false;

	// Construct.
	$cta_button  = '<li class="menu-item wpbf-cta-menu-item">';
	$cta_button .= '<a' . $button_target . ' href="' . esc_url( $button_link ) . '">' . esc_html( $button_text ) . '</a>';
	$cta_button .= '</li>';

	return $cta_button;

}

/**
 * Add desktop Call to Action button.
 *
 * @param string $items The menu items.
 * @param array  $args The menu arguments.
 *
 * @return string The menu.
 */
function wpbf_cta_menu_item( $items, $args ) {

	// Stop here if we're on an off canvas menu.
	if ( function_exists( 'wpbf_is_off_canvas_menu' ) && wpbf_is_off_canvas_menu() ) {
		return $items;
	}

	// Add Call to Action button to main menu.
	if ( 'main_menu' === $args->theme_location && get_theme_mod( 'cta_button' ) ) {
		$items .= wpbf_cta_button();
	}

	return $items;

}
add_filter( 'wp_nav_menu_items', 'wpbf_cta_menu_item', apply_filters( 'wpbf_cta_menu_item_priority', 50 ), 2 );

/**
 * Add mobile Call to Action button.
 *
 * @param string $items The menu items.
 * @param array  $args The menu arguments.
 *
 * @return string The menu.
 */
function wpbf_cta_mobile_menu_item( $items, $args ) {

	// Add Call to Action button to mobile menu.
	if ( 'mobile_menu' === $args->theme_location && get_theme_mod( 'cta_button_mobile' ) ) {
		$items .= wpbf_cta_button();
	}

	return $items;

}
add_filter( 'wp_nav_menu_items', 'wpbf_cta_mobile_menu_item', apply_filters( 'wpbf_cta_menu_item_priority', 50 ), 2 );
