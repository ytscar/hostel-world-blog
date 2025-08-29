<?php
/**
 * Init.
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Theme settings page.
 */
function wpbf_premium_settings() {
	add_theme_page( __( 'Theme Settings', 'wpbfpremium' ), __( 'Theme Settings', 'wpbfpremium' ), 'manage_options', 'wpbf-premium', 'wpbf_premium_settings_callback' );
}
add_action( 'admin_menu', 'wpbf_premium_settings' );

/**
 * Theme settings page callback.
 */
function wpbf_premium_settings_callback() {
	require_once WPBF_PREMIUM_DIR . 'inc/settings/settings-page.php';
}

/**
 * Admin scripts & styles.
 */
function wpbf_premium_admin_scripts() {

	if ( is_rtl() ) {
		// RTL.
		// wp_enqueue_style( 'wpbf-premium-admin-rtl', WPBF_PREMIUM_URI . 'css/wpbf-premium-admin-rtl.css', '', WPBF_PREMIUM_VERSION );
	}

	$current_screen = get_current_screen();

	// Only enqueue on "Theme Settings" page.
	if ( 'appearance_page_wpbf-premium' === $current_screen->id ) {

		wp_enqueue_style( 'heatbox', WPBF_PREMIUM_URI . 'assets/css/heatbox.css', array(), WPBF_PREMIUM_VERSION );
		wp_enqueue_style( 'wpbf-admin-page', WPBF_PREMIUM_URI . 'assets/css/admin-page.css', array(), WPBF_PREMIUM_VERSION );

		wp_enqueue_script( 'wpbf-theme-settings', WPBF_PREMIUM_URI . 'js/theme-settings.js', array( 'jquery' ), WPBF_PREMIUM_VERSION, true );
		wp_enqueue_script( 'wp-color-picker-alpha', WPBF_PREMIUM_URI . 'js/color-picker.js', array( 'wp-color-picker', 'wp-i18n' ), WPBF_PREMIUM_VERSION, true );

		wp_enqueue_style( 'wp-color-picker' );

	}

}
add_action( 'admin_enqueue_scripts', 'wpbf_premium_admin_scripts' );

/**
 * Change inline style location.
 *
 * @return string The stylesheet handle.
 */
function wpbf_premium_change_inline_style_location() {
	return 'wpbf-premium';
}
add_filter( 'wpbf_add_inline_style', 'wpbf_premium_change_inline_style_location' );

// Backwards compatibility.
require_once WPBF_PREMIUM_DIR . '/inc/backwards-compatibility.php';

// Helpers.
require_once WPBF_PREMIUM_DIR . 'inc/helpers.php';

// Customizer settings.
require_once WPBF_PREMIUM_DIR . 'inc/customizer/customizer-settings.php';

// Custom fonts integration.
require_once WPBF_PREMIUM_DIR . 'inc/customizer/custom-fonts.php';

// Adobe Fonts integration.
require_once WPBF_PREMIUM_DIR . 'inc/customizer/adobe-fonts.php';

// Customizer functions.
require_once WPBF_PREMIUM_DIR . 'inc/customizer/customizer-functions.php';

// Styles.
require_once WPBF_PREMIUM_DIR . 'inc/customizer/styles.php';

// Gutenberg integration.
require_once WPBF_PREMIUM_DIR . 'inc/integration/gutenberg/gutenberg.php';

// Responsive styles.
require_once WPBF_PREMIUM_DIR . 'inc/customizer/responsive.php';

// Settings.
require_once WPBF_PREMIUM_DIR . 'inc/settings/post-type-settings.php';
require_once WPBF_PREMIUM_DIR . 'inc/settings/global-settings.php';
require_once WPBF_PREMIUM_DIR . 'inc/settings/blog-layout-settings.php';
require_once WPBF_PREMIUM_DIR . 'inc/settings/performance-settings.php';
require_once WPBF_PREMIUM_DIR . 'inc/settings/breakpoint-settings.php';
require_once WPBF_PREMIUM_DIR . 'inc/settings/white-label-settings.php';

// Premium settings output.
require_once WPBF_PREMIUM_DIR . 'inc/settings/functions.php';

// Body classes.
require_once WPBF_PREMIUM_DIR . 'inc/body-classes.php';

// Archive Layouts.
require_once WPBF_PREMIUM_DIR . 'inc/archive-layouts.php';

// Post Layouts.
require_once WPBF_PREMIUM_DIR . 'inc/post-layouts.php';

// Deprecated.
require_once WPBF_PREMIUM_DIR . 'inc/deprecated.php';

// Shortcodes.
require_once WPBF_PREMIUM_DIR . 'inc/shortcodes.php';

// Theme mods.
require_once WPBF_PREMIUM_DIR . 'inc/theme-mods.php';

// Related posts.
require_once WPBF_PREMIUM_DIR . 'inc/related-posts.php';

// Customizer Export/Import & Customizer Reset
require_once WPBF_PREMIUM_DIR . 'inc/integration/customizer-import-export.php';

// Custom Sections.
require_once WPBF_PREMIUM_DIR . 'inc/class-custom-sections.php';

/**
 * Plugins loaded.
 *
 * Load specific integrations after plugins are loaded
 * to make sure they exist when we check for them.
 */
function wpbf_premium_plugins_loaded() {

	// Beaver Builder.
	if ( class_exists( 'FLBuilderLoader' ) ) {
		require_once WPBF_PREMIUM_DIR . 'inc/integration/beaver-builder.php';
	}

	// Beaver Themer.
	if ( class_exists( 'FLThemeBuilderLoader' ) && class_exists( 'FLBuilderLoader' ) ) {
		require_once WPBF_PREMIUM_DIR . 'inc/integration/beaver-themer.php';
	}

	// WooCommerce.
	if ( class_exists( 'WooCommerce' ) ) {
		require_once WPBF_PREMIUM_DIR . '/inc/integration/woocommerce.php';
	}

	if ( class_exists( 'Easy_Digital_Downloads' ) ) {
		require_once WPBF_PREMIUM_DIR . '/inc/integration/edd.php';
	}

	if ( class_exists( 'LifterLMS' ) ) {
		require_once WPBF_PREMIUM_DIR . '/inc/integration/lifterlms.php';
	}

	if ( defined( 'GENERATEBLOCKS_VERSION' ) ) {
		require_once WPBF_PREMIUM_DIR . '/inc/integration/generateblocks.php';
	}

	$mega_menu = get_option( 'wpbf_mega_menu', array() );

	$GLOBALS['wpbf_mega_menu'] = $mega_menu;

}
add_action( 'plugins_loaded', 'wpbf_premium_plugins_loaded' );

// Base Modules.
require_once WPBF_PREMIUM_DIR . 'inc/modules/base/class-base-module.php';
require_once WPBF_PREMIUM_DIR . 'inc/modules/base/class-base-output.php';

// Modules.
require_once WPBF_PREMIUM_DIR . 'inc/modules/mega-menu/class-mega-menu-module.php';

// Init the modules.
Wpbf\Premium\Modules\MegaMenu\Mega_Menu_Module::init();

/**
 * Elementor integration.
 */
function wpbf_elementor_integration() {
	require_once WPBF_PREMIUM_DIR . 'inc/integration/elementor.php';
}
add_action( 'elementor/init', 'wpbf_elementor_integration' );

/**
 * Elementor Pro integration.
 */
function wpbf_elementor_pro_integration() {
	require_once WPBF_PREMIUM_DIR . 'inc/integration/elementor-pro.php';
}
add_action( 'elementor_pro/init', 'wpbf_elementor_pro_integration' );

/**
 * Divi integration.
 */
function wpbf_divi_integration() {

	if ( ! function_exists( 'et_pb_is_pagebuilder_used' ) ) {
		return;
	}

	require_once WPBF_PREMIUM_DIR . 'inc/integration/divi.php';

}
add_action( 'init', 'wpbf_divi_integration' );
