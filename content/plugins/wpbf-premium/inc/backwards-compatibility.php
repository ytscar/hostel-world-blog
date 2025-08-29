<?php
/**
 * Backwards compatibility.
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Convert custom controls.
 *
 * From here downwards we convert previous custom/responsive customizer controls to be saved in a single theme_mod.
 * This and the entire backwards compatibility might be removed after 1 year.
 */

// This theme mod existed a long time ago and is now causing issues with the new JSON below.
// If it exists, we will have to update & convert it first, before checking for the new, responsive settings.
$menu_active_logo_size = get_theme_mod( 'menu_active_logo_size' );

if ( is_numeric( $menu_active_logo_size ) ) {

	$theme_mod_array = array(
		'desktop' => $menu_active_logo_size,
		'tablet'  => false,
		'mobile'  => false,
	);

	$theme_mod_array = json_encode( $theme_mod_array, true );

	set_theme_mod( 'menu_active_logo_size', $theme_mod_array );

}

// Sticky navigation logo size.
$menu_active_logo_size_desktop = get_theme_mod( 'menu_active_logo_size_desktop' );
$menu_active_logo_size_tablet  = get_theme_mod( 'menu_active_logo_size_tablet' );
$menu_active_logo_size_mobile  = get_theme_mod( 'menu_active_logo_size_mobile' );

if ( $menu_active_logo_size_desktop || $menu_active_logo_size_tablet || $menu_active_logo_size_mobile ) {

	$theme_mod_array = array(
		'desktop' => $menu_active_logo_size_desktop,
		'tablet'  => $menu_active_logo_size_tablet,
		'mobile'  => $menu_active_logo_size_mobile,
	);

	$theme_mod_array = json_encode( $theme_mod_array, true );

	set_theme_mod( 'menu_active_logo_size', $theme_mod_array );

	remove_theme_mod( 'menu_active_logo_size_desktop' );
	remove_theme_mod( 'menu_active_logo_size_tablet' );
	remove_theme_mod( 'menu_active_logo_size_mobile' );

}

// Font size (text).
$page_font_size_desktop = get_theme_mod( 'page_font_size_desktop' );
$page_font_size_tablet  = get_theme_mod( 'page_font_size_tablet' );
$page_font_size_mobile  = get_theme_mod( 'page_font_size_mobile' );

if ( $page_font_size_desktop || $page_font_size_tablet || $page_font_size_mobile ) {

	$theme_mod_array = array(
		'desktop' => $page_font_size_desktop,
		'tablet'  => $page_font_size_tablet,
		'mobile'  => $page_font_size_mobile,
	);

	$theme_mod_array = json_encode( $theme_mod_array, true );

	set_theme_mod( 'page_font_size', $theme_mod_array );

	remove_theme_mod( 'page_font_size_desktop' );
	remove_theme_mod( 'page_font_size_tablet' );
	remove_theme_mod( 'page_font_size_mobile' );

}

// Font size (h1).
$page_h1_font_size_desktop = get_theme_mod( 'page_h1_font_size_desktop' );
$page_h1_font_size_tablet  = get_theme_mod( 'page_h1_font_size_tablet' );
$page_h1_font_size_mobile  = get_theme_mod( 'page_h1_font_size_mobile' );

if ( $page_h1_font_size_desktop || $page_h1_font_size_tablet || $page_h1_font_size_mobile ) {

	$theme_mod_array = array(
		'desktop' => $page_h1_font_size_desktop,
		'tablet'  => $page_h1_font_size_tablet,
		'mobile'  => $page_h1_font_size_mobile,
	);

	$theme_mod_array = json_encode( $theme_mod_array, true );

	set_theme_mod( 'page_h1_font_size', $theme_mod_array );

	remove_theme_mod( 'page_h1_font_size_desktop' );
	remove_theme_mod( 'page_h1_font_size_tablet' );
	remove_theme_mod( 'page_h1_font_size_mobile' );

}

// Font size (h2).
$page_h2_font_size_desktop = get_theme_mod( 'page_h2_font_size_desktop' );
$page_h2_font_size_tablet  = get_theme_mod( 'page_h2_font_size_tablet' );
$page_h2_font_size_mobile  = get_theme_mod( 'page_h2_font_size_mobile' );

if ( $page_h2_font_size_desktop || $page_h2_font_size_tablet || $page_h2_font_size_mobile ) {

	$theme_mod_array = array(
		'desktop' => $page_h2_font_size_desktop,
		'tablet'  => $page_h2_font_size_tablet,
		'mobile'  => $page_h2_font_size_mobile,
	);

	$theme_mod_array = json_encode( $theme_mod_array, true );

	set_theme_mod( 'page_h2_font_size', $theme_mod_array );

	remove_theme_mod( 'page_h2_font_size_desktop' );
	remove_theme_mod( 'page_h2_font_size_tablet' );
	remove_theme_mod( 'page_h2_font_size_mobile' );

}

// Font size (h3).
$page_h3_font_size_desktop = get_theme_mod( 'page_h3_font_size_desktop' );
$page_h3_font_size_tablet  = get_theme_mod( 'page_h3_font_size_tablet' );
$page_h3_font_size_mobile  = get_theme_mod( 'page_h3_font_size_mobile' );

if ( $page_h3_font_size_desktop || $page_h3_font_size_tablet || $page_h3_font_size_mobile ) {

	$theme_mod_array = array(
		'desktop' => $page_h3_font_size_desktop,
		'tablet'  => $page_h3_font_size_tablet,
		'mobile'  => $page_h3_font_size_mobile,
	);

	$theme_mod_array = json_encode( $theme_mod_array, true );

	set_theme_mod( 'page_h3_font_size', $theme_mod_array );

	remove_theme_mod( 'page_h3_font_size_desktop' );
	remove_theme_mod( 'page_h3_font_size_tablet' );
	remove_theme_mod( 'page_h3_font_size_mobile' );

}

// Font size (h4).
$page_h4_font_size_desktop = get_theme_mod( 'page_h4_font_size_desktop' );
$page_h4_font_size_tablet  = get_theme_mod( 'page_h4_font_size_tablet' );
$page_h4_font_size_mobile  = get_theme_mod( 'page_h4_font_size_mobile' );

if ( $page_h4_font_size_desktop || $page_h4_font_size_tablet || $page_h4_font_size_mobile ) {

	$theme_mod_array = array(
		'desktop' => $page_h4_font_size_desktop,
		'tablet'  => $page_h4_font_size_tablet,
		'mobile'  => $page_h4_font_size_mobile,
	);

	$theme_mod_array = json_encode( $theme_mod_array, true );

	set_theme_mod( 'page_h4_font_size', $theme_mod_array );

	remove_theme_mod( 'page_h4_font_size_desktop' );
	remove_theme_mod( 'page_h4_font_size_tablet' );
	remove_theme_mod( 'page_h4_font_size_mobile' );

}

// Font size (h5).
$page_h5_font_size_desktop = get_theme_mod( 'page_h5_font_size_desktop' );
$page_h5_font_size_tablet  = get_theme_mod( 'page_h5_font_size_tablet' );
$page_h5_font_size_mobile  = get_theme_mod( 'page_h5_font_size_mobile' );

if ( $page_h5_font_size_desktop || $page_h5_font_size_tablet || $page_h5_font_size_mobile ) {

	$theme_mod_array = array(
		'desktop' => $page_h5_font_size_desktop,
		'tablet'  => $page_h5_font_size_tablet,
		'mobile'  => $page_h5_font_size_mobile,
	);

	$theme_mod_array = json_encode( $theme_mod_array, true );

	set_theme_mod( 'page_h5_font_size', $theme_mod_array );

	remove_theme_mod( 'page_h5_font_size_desktop' );
	remove_theme_mod( 'page_h5_font_size_tablet' );
	remove_theme_mod( 'page_h5_font_size_mobile' );

}

// Font size (h6).
$page_h6_font_size_desktop = get_theme_mod( 'page_h6_font_size_desktop' );
$page_h6_font_size_tablet  = get_theme_mod( 'page_h6_font_size_tablet' );
$page_h6_font_size_mobile  = get_theme_mod( 'page_h6_font_size_mobile' );

if ( $page_h6_font_size_desktop || $page_h6_font_size_tablet || $page_h6_font_size_mobile ) {

	$theme_mod_array = array(
		'desktop' => $page_h6_font_size_desktop,
		'tablet'  => $page_h6_font_size_tablet,
		'mobile'  => $page_h6_font_size_mobile,
	);

	$theme_mod_array = json_encode( $theme_mod_array, true );

	set_theme_mod( 'page_h6_font_size', $theme_mod_array );

	remove_theme_mod( 'page_h6_font_size_desktop' );
	remove_theme_mod( 'page_h6_font_size_tablet' );
	remove_theme_mod( 'page_h6_font_size_mobile' );

}

$archives = apply_filters( 'wpbf_archives', array( 'archive' ) );

foreach ( $archives as $archive ) {

	// Archive grid.
	$grid_desktop = get_theme_mod( $archive . '_grid_desktop' );
	$grid_tablet  = get_theme_mod( $archive . '_grid_tablet' );
	$grid_mobile  = get_theme_mod( $archive . '_grid_mobile' );

	if ( $grid_desktop || $grid_tablet || $grid_mobile ) {

		$theme_mod_array = array(
			'desktop' => $grid_desktop,
			'tablet'  => $grid_tablet,
			'mobile'  => $grid_mobile,
		);

		$theme_mod_array = json_encode( $theme_mod_array, true );

		set_theme_mod( $archive . '_grid', $theme_mod_array );

		remove_theme_mod( $archive . '_grid_desktop' );
		remove_theme_mod( $archive . '_grid_tablet' );
		remove_theme_mod( $archive . '_grid_mobile' );

	}
}

/**
 * Disable featured images on pages globally by default.
 *
 * This will be removed in a future release including the wpbf_featured_image_compat option.
 */
function wpbf_disable_featured_image_on_pages_by_default() {

	if ( get_option( 'wpbf_featured_image_compat' ) ) {
		return;
	}

	$settings = get_option( 'wpbf_settings', array() );

	$default = array(
		'wpbf_remove_featured_image_global' => (
			array(
				0 => 'page',
			)
		),
	);

	if ( ! isset( $settings['wpbf_remove_featured_image_global'] ) ) {

		$settings = array_merge( $default, $settings );

		update_option( 'wpbf_settings', $settings );

		update_option( 'wpbf_featured_image_compat', true );

	}

}
add_action( 'init', 'wpbf_disable_featured_image_on_pages_by_default' );

/**
 * Assign "wpbf-mega-menu-container-width" class to existing menu item (before v2.7) which has mega menu enabled.
 *
 * @since 2.7
 *
 * This will be removed in a future release including the wpbf_featured_image_compat option.
 */
function wpbf_assign_default_mega_menu_width_type() {

	if ( get_option( 'wpbf_mega_menu_compat' ) ) {
		return;
	}

	$menu_items_query = new WP_Query(
		array(
			'post_type'      => 'nav_menu_item',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);

	if ( ! $menu_items_query->found_posts ) {
		return;
	}

	while ( $menu_items_query->have_posts() ) {
		$menu_items_query->the_post();

		$id = get_the_ID();

		$class_names = get_post_meta( $id, '_menu_item_classes', true );

		// If it's not a mega menu, then skip this and continue to next loop.
		if ( ! in_array( 'wpbf-mega-menu', $class_names, true ) ) {
			continue;
		}

		array_push( $class_names, 'wpbf-mega-menu-container-width' );
		update_post_meta( $id, '_menu_item_classes', $class_names );
	}

	update_option( 'wpbf_mega_menu_compat', true );

}
add_action( 'init', 'wpbf_assign_default_mega_menu_width_type' );
