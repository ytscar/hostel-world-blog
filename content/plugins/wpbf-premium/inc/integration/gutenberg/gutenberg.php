<?php
/**
 * Gutenberg integration.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration/Gutenberg
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Generate CSS.
 */
function wpbf_premium_gutenberg_css() {

	include_once WPBF_PREMIUM_DIR . 'inc/integration/gutenberg/gutenberg-styles.php';

}
add_action( 'wpbf_before_gutenberg_css', 'wpbf_premium_gutenberg_css' );
