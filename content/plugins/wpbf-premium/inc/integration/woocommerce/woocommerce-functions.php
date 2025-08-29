<?php
/**
 * WooCommerce functions.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Remove WooCommerce post type from settings page post type array.
 *
 * @param array $post_types The post types
 *
 * @return array The updated post types
 */
function wpbf_woo_remove_settings_post_type( $post_types ) {

	unset( $post_types['product'] );

	return $post_types;

}
add_filter( 'wpbf_blog_layouts_archive_array', 'wpbf_woo_remove_settings_post_type' );

/**
 * Add WooCommerce hooks to custom sections.
 *
 * @param array $hooks The custom section hooks
 *
 * @return array The updated custom section hooks
 */
function wpbf_woo_custom_section_hooks( $hooks ) {

	$custom_hooks = array(
		'WooCommerce Shop Page' => array(
			'woocommerce_before_main_content',
			'woocommerce_archive_description',
			'woocommerce_before_shop_loop',
			'woocommerce_before_shop_loop_item',
			'woocommerce_before_shop_loop_item_title',
			'woocommerce_shop_loop_item_title',
			'woocommerce_after_shop_loop_item_title',
			'woocommerce_after_shop_loop_item',
			'woocommerce_after_shop_loop',
			'woocommerce_after_main_content',
		),
		'WooCommerce Product Page' => array(
			'woocommerce_before_single_product',
			'woocommerce_before_single_product_summary',
			'woocommerce_product_thumbnails',
			'woocommerce_single_product_summary',
			'woocommerce_before_add_to_cart_form',
			'woocommerce_before_variations_form',
			'woocommerce_before_add_to_cart_button',
			'woocommerce_before_single_variation',
			'woocommerce_single_variation',
			'woocommerce_after_single_variation',
			'woocommerce_after_add_to_cart_button',
			'woocommerce_after_variations_form',
			'woocommerce_after_add_to_cart_form',
			'woocommerce_product_meta_start',
			'woocommerce_product_meta_end',
			'woocommerce_share',
			'woocommerce_after_single_product_summary',
			'woocommerce_after_single_product',
		),
		'WooCommerce Cart Page' => array(
			'woocommerce_before_cart',
			'woocommerce_before_cart_table',
			'woocommerce_before_cart_contents',
			'woocommerce_cart_contents',
			'woocommerce_cart_coupon',
			'woocommerce_after_cart_contents',
			'woocommerce_after_cart_table',
			'woocommerce_cart_collaterals',
			'woocommerce_before_cart_totals',
			'woocommerce_cart_totals_before_shipping',
			'woocommerce_before_shipping_calculator',
			'woocommerce_after_shipping_calculator',
			'woocommerce_cart_totals_after_shipping',
			'woocommerce_cart_totals_before_order_total',
			'woocommerce_cart_totals_after_order_total',
			'woocommerce_proceed_to_checkout',
			'woocommerce_after_cart_totals',
			'woocommerce_after_cart',
		),
		'WooCommerce Checkout Page' => array(
			'woocommerce_before_checkout_form',
			'woocommerce_checkout_before_customer_details',
			'woocommerce_before_checkout_billing_form',
			'woocommerce_after_checkout_billing_form',
			'woocommerce_before_checkout_shipping_form',
			'woocommerce_after_checkout_shipping_form',
			'woocommerce_before_order_notes',
			'woocommerce_after_order_notes',
			'woocommerce_checkout_after_customer_details',
			'woocommerce_checkout_before_order_review',
			'woocommerce_review_order_before_cart_contents',
			'woocommerce_review_order_after_cart_contents',
			'woocommerce_review_order_before_shipping',
			'woocommerce_review_order_after_shipping',
			'woocommerce_review_order_before_order_total',
			'woocommerce_review_order_after_order_total',
			'woocommerce_review_order_before_payment',
			'woocommerce_review_order_before_submit',
			'woocommerce_review_order_after_submit',
			'woocommerce_review_order_after_payment',
			'woocommerce_checkout_after_order_review',
			'woocommerce_after_checkout_form',
		),
		'WooCommerce Login/Register Form' => array(
			'woocommerce_before_customer_login_form',
			'woocommerce_login_form_start',
			'woocommerce_login_form',
			'woocommerce_login_form_end',
			'woocommerce_register_form_start',
			'woocommerce_register_form',
			'woocommerce_register_form_end',
			'woocommerce_after_customer_login_form',
		),
		'WooCommerce Account Page' => array(
			'woocommerce_before_account_navigation',
			'woocommerce_account_navigation',
			'woocommerce_after_account_navigation',
			'woocommerce_account_content',
			'woocommerce_account_dashboard',
			'woocommerce_before_account_orders',
			'woocommerce_before_account_orders_pagination',
			'woocommerce_after_account_orders',
			'woocommerce_before_account_downloads',
			'woocommerce_before_available_downloads',
			'woocommerce_after_available_downloads',
			'woocommerce_after_account_downloads',
			'woocommerce_before_edit_account_address_form',
			'woocommerce_after_edit_account_address_form',
			'woocommerce_before_account_payment_methods',
			'woocommerce_after_account_payment_methods',
			'woocommerce_before_edit_account_form',
			'woocommerce_edit_account_form_start',
			'woocommerce_edit_account_form',
			'woocommerce_edit_account_form_end',
			'woocommerce_after_edit_account_form',
		),
		'WooCommerce Quick View Modal' => array(
			'wpbf_woo_quick_view_before_gallery',
			'wpbf_woo_quick_view_after_gallery',
			'wpbf_woo_quick_view_before_title',
			'wpbf_woo_quick_view_after_title',
			'wpbf_woo_quick_view_before_rating',
			'wpbf_woo_quick_view_after_rating',
			'wpbf_woo_quick_view_before_price',
			'wpbf_woo_quick_view_after_price',
			'wpbf_woo_quick_view_before_excerpt',
			'wpbf_woo_quick_view_after_excerpt',
			'wpbf_woo_quick_view_before_add_to_cart',
			'wpbf_woo_quick_view_after_add_to_cart',
			'wpbf_woo_quick_view_before_meta',
			'wpbf_woo_quick_view_after_meta',
		),
		'WooCommerce Off Canvas Sidebar' => array(
			'wpbf_woo_off_canvas_sidebar_open',
			'wpbf_woo_off_canvas_sidebar_close',
		),

	);

	$hooks = array_merge( $hooks, $custom_hooks );

	return $hooks;

}
add_filter( 'wpbf_custom_section_hooks', 'wpbf_woo_custom_section_hooks' );

/**
 * Image flip post class.
 *
 * @param array $classes The post classes.
 *
 * @return array The updated post classes.
 */
function wpbf_woo_loop_image_flip_post_class( $classes ) {

	// Stop here if image flip is disabled.
	if ( 'disabled' === get_theme_mod( 'woocommerce_loop_image_flip' ) ) {
		return $classes;
	}

	if ( 'product' == get_post_type() ) {

		global $product;

		$attachment_ids = $product->get_gallery_image_ids();

		if ( $attachment_ids ) {
			$classes[] = 'wpbf-woo-has-gallery';
		}

	}

	return $classes;

}
add_filter( 'post_class', 'wpbf_woo_loop_image_flip_post_class', 30 );

/**
 * Image flip.
 *
 * Construct image flip.
 */
function wpbf_woo_loop_image_flip_construct() {

	// Stop here if image flip is disabled.
	if ( 'disabled' === get_theme_mod( 'woocommerce_loop_image_flip' ) ) {
		return;
	}

	global $product;

	$attachment_ids = $product->get_gallery_image_ids();

	if ( $attachment_ids ) {

		$attachment_ids        = array_values( $attachment_ids );
		$secondary_image_id    = $attachment_ids['0'];
		$secondary_image_alt   = get_post_meta( $secondary_image_id, '_wp_attachment_image_alt', true );
		$secondary_image_title = get_the_title( $secondary_image_id );

		echo wp_get_attachment_image( $secondary_image_id, 'shop_catalog', '',
			array(
				'class' => 'attachment-woocommerce_thumbnail wp-post-image wp-post-image-secondary',
				'alt'   => $secondary_image_alt,
			)
		);

	}

}
add_action( 'woocommerce_before_shop_loop_item_title', 'wpbf_woo_loop_image_flip_construct', 11 );

/**
 * Extend WooCommerce menu item class.
 *
 * @param string $css_classes The css classes.
 *
 * @return string The updated css classes.
 */
function wpbf_woo_menu_item_class_children( $css_classes ) {

	if ( WC()->cart->get_cart() && 'hide' !== get_theme_mod( 'woocommerce_menu_item_dropdown' ) ) {

		$css_classes .= ' menu-item-has-children';

		if ( get_theme_mod( 'woocommerce_menu_item_dropdown_popup' ) ) {
			$css_classes .= ' wpbf-woo-menu-item-popup';
		}

	}

	return $css_classes;

}
add_filter( 'wpbf_woo_menu_item_classes', 'wpbf_woo_menu_item_class_children' );

/**
 * Extend WooCommerce menu item.
 *
 * Add content before menu item.
 *
 * @return string The menu item.
 */
function wpbf_woo_menu_item_premium() {

	$label      = apply_filters( 'wpbf_woo_menu_item_label', __( 'Cart', 'wpbfpremium' ) );
	$cart_total = WC()->cart->get_cart_total();
	$separator  = apply_filters( 'wpbf_woo_menu_item_separator', __( '-', 'wpbfpremium' ) );

	// Construct.
	$menu_item = '';
	if ( 'hide' !== get_theme_mod( 'woocommerce_menu_item_label' ) ) $menu_item .= '<span class="wpbf-woo-menu-item-label">' . esc_html( $label ) . '</span>';
	if ( 'hide' !== get_theme_mod( 'woocommerce_menu_item_amount' ) ) $menu_item .= '<span class="wpbf-woo-menu-item-total">' . wp_kses_data( $cart_total ) . '</span>';
	if ( 'hide' !== get_theme_mod( 'woocommerce_menu_item_amount' ) ) $menu_item .= '<span class="wpbf-woo-menu-item-separator">' . esc_html( $separator ) . '</span>';

	return $menu_item;

}
add_filter( 'wpbf_woo_before_menu_item', 'wpbf_woo_menu_item_premium' );

/**
 * Add WooCommerce menu item dropdown.
 *
 * @return string The menu item dropdown.
 */
function wpbf_woo_do_menu_item_dropdown() {

	$label           = apply_filters( 'wpbf_woo_menu_item_label', __( 'Cart', 'wpbfpremium' ) );
	$cart_items      = WC()->cart->get_cart();
	$cart_url        = wc_get_cart_url();
	$checkout_url    = wc_get_checkout_url();
	$cart_button     = get_theme_mod( 'woocommerce_menu_item_dropdown_cart_button' );
	$checkout_button = get_theme_mod( 'woocommerce_menu_item_dropdown_checkout_button' );

	// Construct.
	$menu_item = '';

	if ( $cart_items && 'hide' !== get_theme_mod( 'woocommerce_menu_item_dropdown' ) ) {

		$menu_item .= '<ul class="wpbf-woo-sub-menu">';
		$menu_item .= '<li>';
		$menu_item .= '<div class="wpbf-woo-sub-menu-table-wrap">';
		$menu_item .= '<table class="wpbf-table">';
		$menu_item .= '<thead>';
		$menu_item .= '<tr>';
		$menu_item .= '<th>' . __( 'Product/s', 'wpbfpremium' ) . '</th>';
		$menu_item .= '<th>' . __( 'Quantity', 'wpbfpremium' ) . '</th>';
		$menu_item .= '</tr>';
		$menu_item .= '</thead>';

		$menu_item .= '<tbody>';

		foreach ( $cart_items as $cart_item => $values ) {

			$product   = wc_get_product( $values['data']->get_id() );
			$item_name = $product->get_name();
			$quantity  = $values['quantity'];
			$image     = $product->get_image();
			$link      = $product->get_permalink();
			// $price		= $product->get_price();

			$menu_item .= '<tr>';
			$menu_item .= '<td>';
			$menu_item .= '<div class="wpbf-woo-sub-menu-product-wrap">';

			$menu_item .= sprintf(
				'<a href="%s" class="wpbf-woo-sub-menu-remove" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
				esc_url( wc_get_cart_remove_url( $cart_item ) ),
				esc_attr( $product->get_id() ),
				esc_attr( $cart_item ),
				esc_attr( $product->get_sku() )
			);

			if ( $image ) {
				$menu_item .= '<a class="wpbf-woo-sub-menu-image-wrap" href="' . esc_url( $link ) . '">';
				$menu_item .= $image;
				$menu_item .= '</a>';
			}

			$menu_item .= '<a class="wpbf-woo-sub-menu-title-wrap" href="' . esc_url( $link ) . '">';
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

		$menu_item .= '<div class="wpbf-woo-sub-menu-summary-wrap">';
		$menu_item .= '<div>' . __( 'Subtotal', 'wpbfpremium' ) . '</div>';
		$menu_item .= '<div>' . WC()->cart->get_cart_subtotal() . '</div>';
		$menu_item .= '</div>';

		if ( 'hide' !== $cart_button || 'hide' !== $checkout_button ) {

			$menu_item .= '<div class="wpbf-woo-sub-menu-button-wrap">';
			if ( 'hide' !== $cart_button ) $menu_item .= '<a href="' . esc_url( $cart_url ) . '" class="wpbf-button">' . esc_html( $label ) . '</a>';
			if ( 'hide' !== $checkout_button ) $menu_item .= '<a href="' . esc_url( $checkout_url ) . '" class="wpbf-button wpbf-button-primary">' . __( 'Checkout', 'wpbfpremium' ) . '</a>';
			$menu_item .= '</div>';

		}

		$menu_item .= '</li>';
		$menu_item .= '</ul>';

	}

	return $menu_item;

}
add_filter( 'wpbf_woo_menu_item_dropdown', 'wpbf_woo_do_menu_item_dropdown' );

/**
 * Menu item dropdown popup overlay.
 */
function wpbf_woo_menu_item_dropdown_popup_overlay() {

	if ( get_theme_mod( 'woocommerce_menu_item_dropdown_popup' ) ) {
		echo '<div class="wpbf-woo-menu-item-popup-overlay"></div>';
	}

}
add_action( 'wpbf_body_close', 'wpbf_woo_menu_item_dropdown_popup_overlay' );

/**
 * Off canvas sidebar.
 *
 * Construct off canvas sidebar.
 */
function wpbf_woo_off_canvas_sidebar() {

	// Stop here if off canvas sidebar is not enabled.
	if ( 'enabled' !== get_theme_mod( 'woocommerce_loop_off_canvas_sidebar' ) ) {
		return;
	}

	echo '<div class="wpbf-woo-off-canvas-sidebar">';

	if ( wpbf_svg_enabled() ) {
		echo '<span class="wpbf-close">';
		echo wpbf_svg( 'times' );
		echo '</span>';
	} else {
		echo '<i class="wpbf-close wpbff wpbff-times" aria-hidden="true"></i>';
	}

	do_action( 'wpbf_woo_off_canvas_sidebar_open' );

	if ( ! dynamic_sidebar( 'wpbf-woocommerce-off-canvas-sidebar' ) ) {

		if ( current_user_can( 'edit_theme_options' ) ) {

		?>

		<div class="widget no-widgets">

			<?php _e( 'Your Off Canvas Sidebar Widgets will appear here.', 'wpbfpremium' ); ?><br>

			<?php if ( is_customize_preview() ) { ?>
				<a href="javascript:void(0)" onclick="parent.wp.customize.panel( 'widgets' ).focus()"><?php _e( 'Add Widgets', 'wpbfpremium' ); ?></a>
			<?php } else { ?>
				<a href='<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>'><?php _e( 'Add Widgets', 'wpbfpremium' ); ?></a>
			<?php } ?>

		</div>

		<?php

		}

	}

	do_action( 'wpbf_woo_off_canvas_sidebar_close' );
	echo '</div>';
	echo '<div class="wpbf-woo-off-canvas-sidebar-overlay"></div>';
	echo '<button class="wpbf-woo-off-canvas-sidebar-button" aria-hidden="true">';

	if ( wpbf_svg_enabled() ) {
		$icon = wpbf_svg( 'search' );
	} else {
		$icon = '<i class="wpbff wpbff-search"></i>';
	}

	echo apply_filters( 'wpbf_woo_off_canvas_sidebar_icon', $icon );
	echo '&nbsp;';
	echo apply_filters( 'wpbf_woo_off_canvas_sidebar_label', __( 'Filter', 'wpbfpremium' ) );
	echo '</button>';

}
add_action( 'woocommerce_before_shop_loop', 'wpbf_woo_off_canvas_sidebar', 10 );

/**
 * Off canvas sidebar widget area.
 */
function wpbf_woo_off_canvas_sidebar_widget_area() {

	// Stop here if off canvas sidebar is not enabled.
	if ( 'enabled' !== get_theme_mod( 'woocommerce_loop_off_canvas_sidebar' ) ) {
		return;
	}

	register_sidebar( array(
		'id'            => 'wpbf-woocommerce-off-canvas-sidebar',
		'name'          => __( 'WooCommerce Off Canvas Sidebar', 'wpbfpremium' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="wpbf-widgettitle">',
		'after_title'   => '</h4>',
		'description'   => __( 'This Off Canvas Sidebar is being displayed on WooCommerce Archive Pages.', 'wpbfpremium' ),
	) );

}
add_action( 'widgets_init', 'wpbf_woo_off_canvas_sidebar_widget_area' );

/**
 * Filter off canvas sidebar icon.
 *
 * @param string $icon The icon.
 *
 * @return string The new icon.
 */
function wpbf_woo_off_canvas_sidebar_icon( $icon ) {

	if ( 'hamburger' === get_theme_mod( 'woocommerce_loop_off_canvas_sidebar_icon' ) ) {
		if ( wpbf_svg_enabled() ) {
			$icon = wpbf_svg( 'hamburger' );
		} else {
			$icon = '<i class="wpbff wpbff-hamburger"></i>';
		}
	}

	return $icon;

}
add_filter( 'wpbf_woo_off_canvas_sidebar_icon', 'wpbf_woo_off_canvas_sidebar_icon' );

/**
 * Filter off canvas sidebar label.
 *
 * @param string $label The label.
 *
 * @return string The new label.
 */
function wpbf_woo_off_canvas_sidebar_label( $label ) {

	$newlabel = get_theme_mod( 'woocommerce_loop_off_canvas_sidebar_label' );

	if ( $newlabel ) {
		$label = esc_html( $newlabel );
	}

	return $label;

}
add_filter( 'wpbf_woo_off_canvas_sidebar_label', 'wpbf_woo_off_canvas_sidebar_label' );

/**
 * Distraction free checkout.
 */
function wpbf_woo_distraction_free_checkout() {

	// Stop here if we're not on the checkout page.
	if ( ! is_checkout() ) {
		return;
	}

	// Stop here if distraction free checkout is not enabled.
	if ( ! get_theme_mod( 'woocommerce_distraction_free_checkout' ) ) {
		return;
	}

	remove_action( 'wpbf_header', 'wpbf_do_header' );
	add_action( 'wpbf_header', 'wpbf_woo_do_distraction_free_checkout' );

}
add_action( 'wp', 'wpbf_woo_distraction_free_checkout' );

/**
 * Construct distraction free checkout.
 */
function wpbf_woo_do_distraction_free_checkout() {

	?>

	<header id="header" class="wpbf-page-header" itemscope="itemscope" itemtype="https://schema.org/WPHeader">

		<?php do_action( 'wpbf_header_open' ); ?>

		<div class="wpbf-navigation wpbf-distraction-free">

			<div class="wpbf-container wpbf-container-center wpbf-visible-large wpbf-nav-wrapper">

				<?php get_template_part( 'inc/template-parts/logo/logo' ); ?>

			</div>

			<div class="wpbf-container wpbf-mobile-menu-hamburger wpbf-hidden-large wpbf-mobile-nav-wrapper">

				<?php get_template_part( 'inc/template-parts/logo/logo-mobile' ); ?>

			</div>

		</div>

		<?php do_action( 'wpbf_header_close' ); ?>

	</header>

	<?php

}

/**
 * Apply custom cart menu item text/label.
 *
 * @param string $label The label.
 *
 * @return string The updated label.
 */
function wpbf_woo_menu_item_label( $label ) {

	$new_label = get_theme_mod( 'woocommerce_menu_item_custom_label' );

	if ( $new_label && 'Cart' !== $new_label ) {
		$label = $new_label;
	}

	return $label;

}
add_filter( 'wpbf_woo_menu_item_label', 'wpbf_woo_menu_item_label' );
