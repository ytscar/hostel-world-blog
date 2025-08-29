<?php
/**
 * Dynamic WooCommerce CSS.
 *
 * Holds Customizer WooCommerce CSS styles.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

function wpbf_premium_woo_customizer_css() {

	$breakpoint_desktop = wpbf_breakpoint_desktop() . 'px';
	$breakpoint_medium  = wpbf_breakpoint_medium() . 'px';

	// Menu item dropdown buttons.
	$button_bg_color               = get_theme_mod( 'button_bg_color' );
	$button_text_color             = get_theme_mod( 'button_text_color' );
	$button_bg_color_alt           = get_theme_mod( 'button_bg_color_alt' );
	$button_text_color_alt         = get_theme_mod( 'button_text_color_alt' );
	$button_primary_bg_color       = get_theme_mod( 'button_primary_bg_color' );
	$button_primary_text_color     = get_theme_mod( 'button_primary_text_color' );
	$button_primary_bg_color_alt   = get_theme_mod( 'button_primary_bg_color_alt' );
	$button_primary_text_color_alt = get_theme_mod( 'button_primary_text_color_alt' );

	if ( $button_bg_color || $button_text_color ) {

		echo '.wpbf-woo-menu-item .wpbf-button {';

		if ( $button_bg_color ) {
			echo sprintf( 'background: %s;', esc_attr( $button_bg_color ) );
		}

		if ( $button_text_color ) {
			echo sprintf( 'color: %s !important;', esc_attr( $button_text_color ) );
		}

		echo '}';

	}

	if ( $button_bg_color_alt || $button_text_color_alt ) {

		echo '.wpbf-woo-menu-item .wpbf-button:hover {';

		if ( $button_bg_color_alt ) {
			echo sprintf( 'background: %s;', esc_attr( $button_bg_color_alt ) );
		}

		if ( $button_text_color_alt ) {
			echo sprintf( 'color: %s !important;', esc_attr( $button_text_color_alt ) );
		}

		echo '}';

	}

	if ( $button_primary_bg_color || $button_primary_text_color ) {

		echo '.wpbf-woo-menu-item .wpbf-button-primary {';

		if ( $button_primary_bg_color ) {
			echo sprintf( 'background: %s;', esc_attr( $button_primary_bg_color ) );
		}

		if ( $button_primary_text_color ) {
			echo sprintf( 'color: %s !important;', esc_attr( $button_primary_text_color ) );
		}

		echo '}';

	}

	if ( $button_primary_bg_color_alt || $button_primary_text_color_alt ) {

		echo '.wpbf-woo-menu-item .wpbf-button-primary:hover {';

		if ( $button_primary_bg_color_alt ) {
			echo sprintf( 'background: %s;', esc_attr( $button_primary_bg_color_alt ) );
		}

		if ( $button_primary_text_color_alt ) {
			echo sprintf( 'color: %s !important;', esc_attr( $button_primary_text_color_alt ) );
		}

		echo '}';

	}

	// Quick view.
	$woocommerce_loop_quick_view_overlay_color    = ( $val = get_theme_mod( 'woocommerce_loop_quick_view_overlay_color' ) ) === 'rgba(0,0,0,.8)' ? false : $val;
	$woocommerce_loop_quick_view_font_size        = ( $val = get_theme_mod( 'woocommerce_loop_quick_view_font_size' ) ) === '14px' ? false : $val;
	$woocommerce_loop_quick_view_font_color       = ( $val = get_theme_mod( 'woocommerce_loop_quick_view_font_color' ) ) === '#ffffff' ? false : $val;
	$woocommerce_loop_quick_view_background_color = ( $val = get_theme_mod( 'woocommerce_loop_quick_view_background_color' ) ) === 'rgba(0,0,0,.7)' ? false : $val;

	if ( $woocommerce_loop_quick_view_overlay_color ) {
		echo '.wpbf-woo-quick-view-modal {';
		echo sprintf( 'background: %s;', esc_attr( $woocommerce_loop_quick_view_overlay_color ) );
		echo '}';
	}

	if ( $woocommerce_loop_quick_view_font_size || $woocommerce_loop_quick_view_font_color || $woocommerce_loop_quick_view_background_color ) {

		echo '.wpbf-woo-quick-view {';

		if ( $woocommerce_loop_quick_view_font_size ) {
			echo sprintf( 'font-size: %s;', esc_attr( $woocommerce_loop_quick_view_font_size ) );
		}

		if ( $woocommerce_loop_quick_view_font_color ) {
			echo sprintf( 'color: %s;', esc_attr( $woocommerce_loop_quick_view_font_color ) );
		}

		if ( $woocommerce_loop_quick_view_background_color ) {
			echo sprintf( 'background-color: %s;', esc_attr( $woocommerce_loop_quick_view_background_color ) );
		}

		echo '}';

	}

	if ( $woocommerce_loop_quick_view_font_color ) {
		echo '.wpbf-woo-quick-view:hover {';
		echo sprintf( 'color: %s;', esc_attr( $woocommerce_loop_quick_view_font_color ) );
		echo '}';
	}

	// Menu Item Dropdown
	$woocommerce_menu_item_dropdown = 'hide' !== get_theme_mod( 'woocommerce_menu_item_dropdown' ) ? true : false;

	if ( $woocommerce_menu_item_dropdown ) {
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo '.wpbf-woo-menu-item .wpbf-woo-sub-menu {';
		echo 'display: none !important;';
		echo '}';
		echo '}';
	}

	// Cart Popup
	$woocommerce_menu_item_dropdown_popup = get_theme_mod( 'woocommerce_menu_item_dropdown_popup' );

	if ( $woocommerce_menu_item_dropdown_popup ) {
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo '.wpbf-woo-menu-item-popup-overlay {';
		echo 'display: none !important;';
		echo '}';
		echo '}';
	}

	// Off Canvas Sidebar
	$woocommerce_loop_off_canvas_sidebar_font_color       = ( $val = get_theme_mod( 'woocommerce_loop_off_canvas_sidebar_font_color' ) ) === '#ffffff' ? false : $val;
	$woocommerce_loop_off_canvas_sidebar_background_color = get_theme_mod( 'woocommerce_loop_off_canvas_sidebar_background_color' );
	$woocommerce_loop_off_canvas_sidebar_overlay_color    = ( $val = get_theme_mod( 'woocommerce_loop_off_canvas_sidebar_overlay_color' ) ) === 'rgba(0,0,0,.2)' ? false : $val;

	if ( $woocommerce_loop_off_canvas_sidebar_font_color || $woocommerce_loop_off_canvas_sidebar_background_color ) {

		echo '.wpbf-woo-off-canvas-sidebar-button {';

		if ( $woocommerce_loop_off_canvas_sidebar_font_color ) {
			echo sprintf( 'color: %s;', esc_attr( $woocommerce_loop_off_canvas_sidebar_font_color ) );
		}

		if ( $woocommerce_loop_off_canvas_sidebar_background_color ) {
			echo sprintf( 'background-color: %s;', esc_attr( $woocommerce_loop_off_canvas_sidebar_background_color ) );
		}

		echo '}';

	} elseif ( $button_primary_bg_color || $button_primary_text_color ) {

		echo '.wpbf-woo-off-canvas-sidebar-button {';

		if ( $button_primary_text_color ) {
			echo sprintf( 'color: %s;', esc_attr( $button_primary_text_color ) );
		}

		if ( $button_primary_bg_color ) {
			echo sprintf( 'background-color: %s;', esc_attr( $button_primary_bg_color ) );
		}

		echo '}';

	}

	if ( $woocommerce_loop_off_canvas_sidebar_overlay_color ) {
		echo '.wpbf-woo-off-canvas-sidebar-overlay {';
		echo sprintf( 'background-color: %s;', esc_attr( $woocommerce_loop_off_canvas_sidebar_overlay_color ) );
		echo '}';
	}

	$woocommerce_loop_sale_position = get_theme_mod( 'woocommerce_loop_sale_position' );

	// Image flip.
	if ( 'inside' === $woocommerce_loop_sale_position || 'none' === $woocommerce_loop_sale_position ) {

		echo '.woocommerce ul.products li.product .wpbf-woo-loop-thumbnail-wrapper {';
		echo 'overflow: hidden;';
		echo '}';

	}

}
add_action( 'wpbf_after_customizer_css', 'wpbf_premium_woo_customizer_css', 20 );
