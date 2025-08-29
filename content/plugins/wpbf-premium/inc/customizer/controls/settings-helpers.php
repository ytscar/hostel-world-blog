<?php
/**
 * Premium Add-On settings helpers.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

if ( ! function_exists( 'wpbf_kirki_sanitize_helper' ) ) {

	/**
	 * Kirki sanitization helper.
	 *
	 * @param string $callback The sanitization callback.
	 *
	 * @return array The sanitized json.
	 */
	function wpbf_kirki_sanitize_helper( $callback ) {

		return function ( $value ) use ( $callback ) {

			if ( ! empty( $value ) ) {
				$value = json_decode( trim( $value ), true );
				$value = array_map( $callback, $value );
				$value = json_encode( $value );
			}

			return $value;

		};

	}

}

function wpbf_social_choices() {
	return array(
		'facebook'   => __( 'Facebook', 'wpbfpremium' ),
		'twitter'    => __( 'Twitter (X)', 'wpbfpremium' ),
		'pinterest'  => __( 'Pinterest', 'wpbfpremium' ),
		'youtube'    => __( 'Youtube', 'wpbfpremium' ),
		'instagram'  => __( 'Instagram', 'wpbfpremium' ),
		'vimeo'      => __( 'Vimeo', 'wpbfpremium' ),
		'soundcloud' => __( 'Soundcloud', 'wpbfpremium' ),
		'linkedin'   => __( 'LinkedIn', 'wpbfpremium' ),
		'yelp'       => __( 'Yelp', 'wpbfpremium' ),
		'behance'    => __( 'Behance', 'wpbfpremium' ),
		'spotify'    => __( 'Spotify', 'wpbfpremium' ),
		'reddit'     => __( 'Reddit', 'wpbfpremium' ),
		'rss'        => __( 'RSS', 'wpbfpremium' ),
		'github'     => __( 'GitHub', 'wpbfpremium' ),
		'messenger'  => __( 'Facebook Messenger', 'wpbfpremium' ),
		'whatsapp'   => __( 'WhatsApp', 'wpbfpremium' ),
		'snapchat'   => __( 'Snapchat', 'wpbfpremium' ),
		'xing'       => __( 'Xing', 'wpbfpremium' ),
		'tiktok'     => __( 'TikTok', 'wpbfpremium' ),
		'patreon'    => __( 'Patreon', 'wpbfpremium' ),
		'dribbble'   => __( 'Dribbble', 'wpbfpremium' ),
		'tumblr'     => __( 'Tumblr', 'wpbfpremium' ),
		'email'      => __( 'Email', 'wpbfpremium' ),
	);
}
