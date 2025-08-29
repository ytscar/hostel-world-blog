<?php
/**
 * Gutenberg editor styles.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration/Gutenberg
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Custom fonts.
$custom_fonts = get_theme_mod( 'custom_fonts' );

if ( $custom_fonts && get_theme_mod( 'enable_custom_fonts' ) ) {
	wpbf_premium_font_face_css( $custom_fonts );
}
