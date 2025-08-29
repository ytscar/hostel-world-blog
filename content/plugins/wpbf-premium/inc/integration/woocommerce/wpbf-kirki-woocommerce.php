<?php
/**
 * WooCommerce.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Extend the WordPress customizer.
 */
function wpbf_kirki_premium_woocommerce() {

	if ( ! function_exists( 'wpbf_customizer_field' ) ) {
		return;
	}

	// Stop here if theme is outdated.
	if ( wpbf_premium_is_theme_outdated() ) {
		return;
	}

	/* Fields – menu item */

	// Menu item icon.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_icon' )
		->type( 'select' )
		->label( __( 'Icon', 'wpbfpremium' ) )
		->defaultValue( 'cart' )
		->priority( 0 )
		->choices( array(
			'cart'   => __( 'Cart', 'wpbfpremium' ),
			'basket' => __( 'Basket', 'wpbfpremium' ),
			'bag'    => __( 'Bag', 'wpbfpremium' ),
			'bag-2'  => __( 'Bag 2', 'wpbfpremium' ),
		) )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	// Separator.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_icon_separator' )
		->type( 'divider' )
		->priority( 0 )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	// Menu item text.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_label' )
		->type( 'select' )
		->label( __( '"Cart" Text', 'wpbfpremium' ) )
		->defaultValue( 'show' )
		->priority( 20 )
		->choices( array(
			'show' => __( 'Show', 'wpbfpremium' ),
			'hide' => __( 'Hide', 'wpbfpremium' ),
		) )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	// Menu item custom text.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_custom_label' )
		->type( 'text' )
		->label( __( '"Cart" Text', 'wpbfpremium' ) )
		->defaultValue( 'Cart' )
		->priority( 20 )
		->transport( 'postMessage' )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_menu_item_label',
				'operator' => '==',
				'value'    => 'show',
			),
		) )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	// Menu item amount.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_amount' )
		->type( 'select' )
		->label( __( 'Amount', 'wpbfpremium' ) )
		->defaultValue( 'show' )
		->priority( 21 )
		->choices( array(
			'show' => __( 'Show', 'wpbfpremium' ),
			'hide' => __( 'Hide', 'wpbfpremium' ),
		) )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	// Separator.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_amount_separator' )
		->type( 'divider' )
		->priority( 22 )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	// Menu item dropdown.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_dropdown' )
		->type( 'select' )
		->label( __( 'Cart Dropdown', 'wpbfpremium' ) )
		->defaultValue( 'show' )
		->priority( 23 )
		->choices( array(
			'show' => __( 'Enable', 'wpbfpremium' ),
			'hide' => __( 'Disable', 'wpbfpremium' ),
		) )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	// Cart button.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_dropdown_cart_button' )
		->type( 'select' )
		->label( __( 'Cart Button', 'wpbfpremium' ) )
		->defaultValue( 'show' )
		->priority( 24 )
		->choices( array(
			'show' => __( 'Show', 'wpbfpremium' ),
			'hide' => __( 'Hide', 'wpbfpremium' ),
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_menu_item_dropdown',
				'operator' => '!=',
				'value'    => 'hide',
			),
		) )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	// Checkout button.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_dropdown_checkout_button' )
		->type( 'select' )
		->label( __( 'Checkout Button', 'wpbfpremium' ) )
		->defaultValue( 'show' )
		->priority( 25 )
		->choices( array(
			'show' => __( 'Show', 'wpbfpremium' ),
			'hide' => __( 'Hide', 'wpbfpremium' ),
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_menu_item_dropdown',
				'operator' => '!=',
				'value'    => 'hide',
			),
		) )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	// Display on add to cart.
	wpbf_customizer_field()
		->id( 'woocommerce_menu_item_dropdown_popup' )
		->type( 'toggle' )
		->label( __( 'Cart Popup', 'wpbfpremium' ) )
		->tooltip( __( 'Display the cart dropdown for a short period of time after a product was added to the cart. Works only in combination with Sticky Navigation.', 'wpbfpremium' ) )
		->defaultValue( false )
		->priority( 26 )
		->choices( array(
			'show' => __( 'Enable', 'wpbfpremium' ),
			'hide' => __( 'Disable', 'wpbfpremium' ),
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_menu_item_dropdown',
				'operator' => '!=',
				'value'    => 'hide',
			),
			array(
				'id'       => 'menu_sticky',
				'operator' => '==',
				'value'    => true,
			),
		) )
		->addToSection( 'wpbf_woocommerce_menu_item_options' );

	/* Fields – shop & archive pages (loop) */

	$shop_priority = 60;

	// Separator.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_separator_premium_0' )
		->type( 'divider' )
		->priority( $shop_priority++ )
		->addToSection( 'woocommerce_product_catalog' );

	// Image flip.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_image_flip' )
		->type( 'select' )
		->label( __( 'Image Flip', 'wpbfpremium' ) )
		->description( __( 'Displays the first image of your product gallery (if available) when hovering over the product thumbnail.', 'wpbfpremium' ) )
		->defaultValue( 'enabled' )
		->priority( $shop_priority++ )
		->choices( array(
			'enabled'  => __( 'Enabled', 'wpbfpremium' ),
			'disabled' => __( 'Disabled', 'wpbfpremium' ),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Infinite scroll.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_infinite_scroll' )
		->type( 'select' )
		->label( __( 'Infinite Scroll', 'wpbfpremium' ) )
		->defaultValue( 'disabled' )
		->priority( $shop_priority++ )
		->choices( array(
			'disabled' => __( 'Disabled', 'wpbfpremium' ),
			'enabled'  => __( 'Enabled', 'wpbfpremium' ),
			// 'button'   => __( 'Load More Button', 'wpbfpremium' ),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Separator.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_separator_premium_1' )
		->type( 'divider' )
		->priority( $shop_priority++ )
		->addToSection( 'woocommerce_product_catalog' );

	// Quick view.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_quick_view' )
		->type( 'select' )
		->label( __( 'Quick View', 'wpbfpremium' ) )
		->defaultValue( 'enabled' )
		->priority( $shop_priority++ )
		->choices( array(
			'enabled'  => __( 'Enabled', 'wpbfpremium' ),
			'disabled' => __( 'Disabled', 'wpbfpremium' ),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Quick view font size.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_quick_view_font_size' )
		->type( 'input-slider' )
		->label( __( 'Font Size', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( '14px' )
		->properties( array(
			'min'  => 0,
			'max'  => 50,
			'step' => 1,
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_loop_quick_view',
				'operator' => '!=',
				'value'    => 'disabled',
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Quick view font color.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_quick_view_font_color' )
		->type( 'color' )
		->label( __( 'Font Color', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( '#ffffff' )
		->properties( array(
			'mode' => 'alpha',
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_loop_quick_view',
				'operator' => '!=',
				'value'    => 'disabled',
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Quick view background color.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_quick_view_background_color' )
		->type( 'color' )
		->label( __( 'Background Color', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( 'rgba(0,0,0,.7)' )
		->properties( array(
			'mode' => 'alpha',
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_loop_quick_view',
				'operator' => '!=',
				'value'    => 'disabled',
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Quick view overlay color.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_quick_view_overlay_color' )
		->type( 'color' )
		->label( __( 'Overlay Background Color', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( 'rgba(0,0,0,.8)' )
		->properties( array(
			'mode' => 'alpha',
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_loop_quick_view',
				'operator' => '!=',
				'value'    => 'disabled',
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Separator.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_separator_premium_2' )
		->type( 'divider' )
		->priority( $shop_priority++ )
		->addToSection( 'woocommerce_product_catalog' );

	// Off canvas sidebar.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_off_canvas_sidebar' )
		->type( 'select' )
		->label( __( 'Off Canvas Sidebar', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( 'disabled' )
		->choices( array(
			'enabled'  => __( 'Enabled', 'wpbfpremium' ),
			'disabled' => __( 'Disabled', 'wpbfpremium' ),
		) )
		->partialRefresh( array(
			'woocommerce_loop_off_canvas_sidebar' => array(
				'container_inclusive' => true,
				'selector'            => '.wpbf-woo-off-canvas-sidebar-button',
				'render_callback'     => function () {
					return wpbf_woo_off_canvas_sidebar();
				},
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Off sidebar icon.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_off_canvas_sidebar_icon' )
		->type( 'select' )
		->label( __( 'Icon', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( 'search' )
		->choices( array(
			'search'    => __( 'Search', 'wpbfpremium' ),
			'hamburger' => __( 'Hamburger', 'wpbfpremium' ),
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_loop_off_canvas_sidebar',
				'operator' => '!=',
				'value'    => 'disabled',
			),
		) )
		->partialRefresh( array(
			'woocommerce_loop_off_canvas_sidebar_icon' => array(
				'container_inclusive' => true,
				'selector'            => '.wpbf-woo-off-canvas-sidebar-button',
				'render_callback'     => function () {
					return wpbf_woo_off_canvas_sidebar();
				},
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Off canvas sidebar label.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_off_canvas_sidebar_label' )
		->type( 'text' )
		->label( __( 'Label', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( 'Filter' )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_loop_off_canvas_sidebar',
				'operator' => '!=',
				'value'    => 'disabled',
			),
		) )
		->partialRefresh( array(
			'woocommerce_loop_off_canvas_sidebar_label' => array(
				'container_inclusive' => true,
				'selector'            => '.wpbf-woo-off-canvas-sidebar-button',
				'render_callback'     => function () {
					return wpbf_woo_off_canvas_sidebar();
				},
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Off canvas sidebar font color.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_off_canvas_sidebar_font_color' )
		->type( 'color' )
		->label( __( 'Font Color', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( '#ffffff' )
		->properties( array(
			'mode' => 'alpha',
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_loop_off_canvas_sidebar',
				'operator' => '!=',
				'value'    => 'disabled',
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Off canvas sidebar background color.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_off_canvas_sidebar_background_color' )
		->type( 'color' )
		->label( __( 'Background Color', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( '' )
		->properties( array(
			'mode' => 'alpha',
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_loop_off_canvas_sidebar',
				'operator' => '!=',
				'value'    => 'disabled',
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	// Off canvas sidebar overlay color.
	wpbf_customizer_field()
		->id( 'woocommerce_loop_off_canvas_sidebar_overlay_color' )
		->type( 'color' )
		->label( __( 'Overlay Background Color', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( $shop_priority++ )
		->defaultValue( 'rgba(0,0,0,.2)' )
		->properties( array(
			'mode' => 'alpha',
		) )
		->activeCallback( array(
			array(
				'id'       => 'woocommerce_loop_off_canvas_sidebar',
				'operator' => '!=',
				'value'    => 'disabled',
			),
		) )
		->addToSection( 'woocommerce_product_catalog' );

	/* Fields – checkout page */

	// Distraction free.
	wpbf_customizer_field()
		->id( 'woocommerce_distraction_free_checkout' )
		->type( 'toggle' )
		->label( __( 'Distraction Free Checkout', 'wpbfpremium' ) )
		->defaultValue( 0 )
		->priority( 1 )
		->addToSection( 'woocommerce_checkout' );

}
add_action( 'after_setup_theme', 'wpbf_kirki_premium_woocommerce' );
