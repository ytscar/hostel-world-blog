<?php
/**
 * WooCommerce integration.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// WooCommerce customizer settings.
require_once WPBF_PREMIUM_DIR . 'inc/integration/woocommerce/wpbf-kirki-woocommerce.php';

// WooCommerce functions.
require_once WPBF_PREMIUM_DIR . 'inc/integration/woocommerce/woocommerce-functions.php';

// WooCommerce quick view.
require_once WPBF_PREMIUM_DIR . 'inc/integration/woocommerce/woocommerce-quick-view.php';

// WooCommerce customizer styles.
require_once WPBF_PREMIUM_DIR . 'inc/integration/woocommerce/woocommerce-styles.php';

// WooCommerce responsive styles.
require_once WPBF_PREMIUM_DIR . 'inc/integration/woocommerce/woocommerce-responsive-styles.php';

/**
 * Enqueue scripts & styles.
 */
function wpbf_premium_woocommerce_scripts() {

	if ( ! apply_filters( 'wpbf_woocommerce_scripts', true ) ) {
		return;
	}

	wp_enqueue_style( 'wpbf-premium-woocommerce', WPBF_PREMIUM_URI . 'css/wpbf-premium-woocommerce.css', '', WPBF_PREMIUM_VERSION );
	wp_enqueue_script( 'wpbf-premium-woocommerce', WPBF_PREMIUM_URI . 'js/wpbf-premium-woocommerce.js', array( 'jquery' ), WPBF_PREMIUM_VERSION, true );

	if ( 'disabled' !== get_theme_mod( 'woocommerce_loop_quick_view' ) ) {

		$wpbf_settings = get_option( 'wpbf_settings' );

		wp_enqueue_script( 'wpbf-premium-woocommerce-quick-view', WPBF_PREMIUM_URI . 'js/wpbf-premium-woocommerce-quick-view.js', array( 'jquery' ), WPBF_PREMIUM_VERSION, true );

		wp_add_inline_script(
			'wpbf-premium-woocommerce-quick-view',
			'var wpbf_quick_view = {ajaxurl: "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '", cart_redirect_after_add: ' . ( isset( $wpbf_settings['wpbf_cart_redirect_after_add'] ) ? 'true' : 'false' ) . '};',
			'before'
		);

	}

	if ( is_shop() || is_product_category() || is_product_taxonomy() ) {

		// Hacky way to remove infinite scroll, imagesloaded & isotope from WooCommerce archives.
		wp_deregister_script( 'wpbf-infinite-scroll' );
		wp_deregister_script( 'wpbf-isotope' );
		wp_deregister_script( 'wpbf-imagesloaded' );

	}

	if ( 'enabled' === get_theme_mod( 'woocommerce_loop_infinite_scroll' ) && ( is_shop() || is_product_category() || is_product_taxonomy() ) ) {

		wp_enqueue_script( 'wpbf-premium-woocommerce-infinite-scroll', WPBF_PREMIUM_URI . 'js/wpbf-premium-woo-infinite-scroll.js', array( 'jquery' ), WPBF_PREMIUM_VERSION, true );

		wp_localize_script(
			'wpbf-premium-woocommerce-infinite-scroll',
			'wpbf_infinte_scroll_object',
			array(
				'next_Selector'    => 'a.next.page-numbers',
				'item_Selector'    => '.product.wpbf-post',
				'content_Selector' => '.products',
				'image_loader'     => WPBF_PREMIUM_URI . 'assets/img/loader.gif',
			)
		);

	}

}
add_action( 'wp_enqueue_scripts', 'wpbf_premium_woocommerce_scripts', 11 );
