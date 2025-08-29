<?php
/**
 * Deprecated.
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Credit shortcode (deprecated).
 *
 * @param array $atts The shortcode attributes.
 *
 * @return string The credit output.
 */
function wpbf_footer_credit( $atts ) {

	extract(
		shortcode_atts(
			array(
				'url'  => 'https://wp-pagebuilderframework.com/',
				'name' => 'Page Builder Framework',
			),
			$atts
		)
	);

	return '<a href="' . esc_url( $url ) . '" rel="nofollow">' . esc_html( $name ) . '</a>';

}
add_shortcode( 'credit', 'wpbf_footer_credit' );
