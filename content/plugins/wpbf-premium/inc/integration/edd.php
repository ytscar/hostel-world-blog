<?php
/**
 * EDD integration.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Enqueue scripts & styles.
 */
function wpbf_premium_edd_scripts() {

	// We only (currently) need these if the cart menu item was not disabled on desktops & the cart menu dropdown was not disabled.
	if ( 'hide' !== get_theme_mod( 'edd_menu_item_dropdown' ) && 'hide' !== get_theme_mod( 'edd_menu_item_desktop' ) ) {

		wp_enqueue_style( 'wpbf-premium-edd', WPBF_PREMIUM_URI . 'css/wpbf-premium-edd.css', '', WPBF_PREMIUM_VERSION );
		wp_enqueue_script( 'wpbf-premium-edd', WPBF_PREMIUM_URI . 'js/wpbf-premium-edd.js', array( 'jquery' ), WPBF_PREMIUM_VERSION, true );

	}

}
add_action( 'wp_enqueue_scripts', 'wpbf_premium_edd_scripts', 11 );

/**
 * Remove EDD post type from settings page post type array.
 *
 * Should we handle archives through Blog Layouts or come up with something custom in the future?
 *
 * @param array $post_types The post types
 *
 * @return array The updated post types
 */
function wpbf_edd_remove_settings_post_type( $post_types ) {

	unset( $post_types['download'] );

	return $post_types;

}
add_filter( 'wpbf_blog_layouts_archive_array', 'wpbf_edd_remove_settings_post_type' );

/**
 * Extend EDD menu item class.
 *
 * @param string $css_classes The css classes.
 *
 * @return string The updated css classes.
 */
function wpbf_edd_menu_item_class_children( $css_classes ) {

	if ( ! empty( edd_get_cart_contents() ) && 'hide' !== get_theme_mod( 'edd_menu_item_dropdown' ) ) {
		$css_classes .= ' menu-item-has-children';
	}

	return $css_classes;

}
add_filter( 'wpbf_edd_menu_item_classes', 'wpbf_edd_menu_item_class_children' );

/**
 * Add EDD menu item dropdown.
 *
 * @return string The menu item dropdown.
 */
function wpbf_edd_do_menu_item_dropdown() {

	$label           = apply_filters( 'wpbf_edd_menu_item_label', __( 'Cart', 'wpbfpremium' ) );
	$cart_items      = edd_get_cart_contents();
	$checkout_url    = edd_get_checkout_uri();

	// Construct.
	$menu_item = '';

	if ( $cart_items && 'hide' !== get_theme_mod( 'edd_menu_item_dropdown' ) ) {

		$menu_item .= '<ul class="wpbf-edd-sub-menu">';
		$menu_item .= '<li>';
		$menu_item .= '<div class="wpbf-edd-sub-menu-table-wrap">';
		$menu_item .= '<table class="wpbf-table">';
		$menu_item .= '<thead>';
		$menu_item .= '<tr>';
		$menu_item .= '<th>' . __( 'Product/s', 'wpbfpremium' ) . '</th>';
		$menu_item .= '<th>' . __( 'Quantity', 'wpbfpremium' ) . '</th>';
		$menu_item .= '</tr>';
		$menu_item .= '</thead>';

		$menu_item .= '<tbody>';

		foreach ( $cart_items as $cart_item ) {

			$cart_item_id = $cart_item['id'];
			$quantity     = $cart_item['quantity'];
			$item_name    = get_the_title( $cart_item_id );
			$image        = get_the_post_thumbnail( $cart_item_id );
			$link         = get_permalink( $cart_item_id );

			$menu_item .= '<tr>';
			$menu_item .= '<td>';
			$menu_item .= '<div class="wpbf-edd-sub-menu-product-wrap">';

			if ( $image ) {
				$menu_item .= '<a class="wpbf-edd-sub-menu-image-wrap" href="' . esc_url( $link ) . '">';
				$menu_item .= $image;
				$menu_item .= '</a>';
			}

			$menu_item .= '<a class="wpbf-edd-sub-menu-title-wrap" href="' . esc_url( $link ) . '">';
			$menu_item .= $item_name;
			$menu_item .= '</a>';
			$menu_item .= '</div>';
			$menu_item .= '</td>';
			$menu_item .= '<td>';
			$menu_item .= $quantity;
			$menu_item .= '</td>';
			$menu_item .= '</tr>';

		}

		$menu_item .= '</tbody>';
		$menu_item .= '</table>';
		$menu_item .= '</div>';
		$menu_item .= '<div class="wpbf-edd-sub-menu-button-wrap">';
		$menu_item .= '<a href="' . esc_url( $checkout_url ) . '" class="wpbf-button wpbf-button-primary">' . __( 'Checkout', 'wpbfpremium' ) . '</a>';
		$menu_item .= '</div>';
		$menu_item .= '</li>';
		$menu_item .= '</ul>';

	}

	return $menu_item;

}
add_filter( 'wpbf_edd_menu_item_dropdown', 'wpbf_edd_do_menu_item_dropdown' );

/**
 * Dynamic EDD CSS.
 */
function wpbf_premium_edd_customizer_css() {

	$breakpoint_medium      = wpbf_breakpoint_medium() . 'px';
	$edd_menu_item_dropdown = 'hide' !== get_theme_mod( 'edd_menu_item_dropdown' ) ? true : false;

	if ( $edd_menu_item_dropdown ) {
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo '.wpbf-edd-menu-item .wpbf-edd-sub-menu {';
		echo 'display: none !important;';
		echo '}';
		echo '}';
	}

}
add_action( 'wpbf_after_customizer_css', 'wpbf_premium_edd_customizer_css', 20 );

/**
 * EDD Customizer Controls.
 */
function wpbf_kirki_premium_edd() {

	// Stop here if theme is outdated.
	if ( wpbf_premium_is_theme_outdated() ) {
		return;
	}

	wpbf_customizer_field()
		->id( 'separator-11214' )
		->type( 'custom' )
		->defaultValue( '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8">' )
		->priority( 40 )
		->addToSection( 'wpbf_edd_menu_item_options' );

	// Menu item dropdown.
	wpbf_customizer_field()
		->id( 'edd_menu_item_dropdown' )
		->type( 'select' )
		->label( __( 'Cart Dropdown', 'wpbfpremium' ) )
		->defaultValue( 'show' )
		->priority( 50 )
		->choices( array(
			'show' => __( 'Enable', 'wpbfpremium' ),
			'hide' => __( 'Disable', 'wpbfpremium' ),
		) )
		->addToSection( 'wpbf_edd_menu_item_options' );

}
add_action( 'after_setup_theme', 'wpbf_kirki_premium_edd' );
