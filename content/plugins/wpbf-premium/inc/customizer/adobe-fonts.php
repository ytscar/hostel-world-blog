<?php
/**
 * Adobe Fonts integration.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Load Adobe Fonts if enabled.
 */
function wpbf_load_typekit() {

	$typekit_id     = get_theme_mod( 'typekit_id' );
	$typekit_enable = get_theme_mod( 'enable_typekit' );

	if ( ! empty( $typekit_id ) && $typekit_enable ) {
		wp_enqueue_style( 'wpbf-adobe-fonts', 'https://use.typekit.net/' . esc_attr( preg_replace( '/[^0-9a-z]+/', '', $typekit_id ) ) . '.css', '', WPBF_PREMIUM_VERSION );
	}

}
add_action( 'wp_enqueue_scripts', 'wpbf_load_typekit', 0 );

/**
 * Extend Kirki font choice with Adobe Fonts font group.
 *
 * @param array $custom_choice The custom choice.
 *
 * @return array The updated custom choice.
 */
function wpbf_typekit_font_group( $custom_choice ) {

	$typekit_id     = get_theme_mod( 'typekit_id' );
	$typekit_enable = get_theme_mod( 'enable_typekit' );
	$typekit_fonts  = get_theme_mod( 'typekit_fonts' );
	$variants       = array();

	if ( $typekit_enable && ! empty( $typekit_id ) && ! empty( $typekit_fonts ) ) {

		foreach ( $typekit_fonts as $key => $typekit_font ) {

			$children[] = array(
				'id'   => $typekit_font['font_css_name'],
				'text' => $typekit_font['font_name'],
			);
			$variants[$typekit_font['font_css_name']] = $typekit_font['font_variants'];

		}

		$custom_choice['families']['typekit'] = array(
			'text'     => esc_attr__( 'Typekit Fonts', 'wpbfpremium' ),
			'children' => $children,
		);

		$custom_choice['variants'] = $variants;

	}

	return $custom_choice;

}
add_filter( 'wpbf_kirki_font_choices', 'wpbf_typekit_font_group', 20 );

/**
 * Manipulate Google Fonts optgroup.
 *
 * Not currently in use.
 *
 * @param array $custom_choice The custom choice.
 *
 * @return array The updated custom choice.
 */
function wpbf_custom_google_fonts( $custom_choice ) {

	$custom_choice = array(
		'google' => array( 'popularity', 4 ),
	);

	return $custom_choice;

}
// add_filter( 'wpbf_kirki_font_choices', 'wpbf_custom_google_fonts', 20 );

/**
 * Elementor integration.
 *
 * Add font groups.
 *
 * @param array $font_groups The font groups.
 *
 * @return array The updated font groups.
 */
function wpbf_typekit_font_elementor_group( $font_groups ) {

	$typekit_font_base             = 'wpbf-typekit-fonts';
	$new_group[$typekit_font_base] = __( 'Typekit Fonts', 'wpbfpremium' );
	$font_groups                   = $new_group + $font_groups;

	return $font_groups;

}
add_filter( 'elementor/fonts/groups', 'wpbf_typekit_font_elementor_group' );

/**
 * Elementor integration.
 *
 * Add fonts.
 *
 * @param array $fonts The fonts.
 *
 * @return array The updated fonts.
 */
function wpbf_add_elementor_typekit_fonts( $fonts ) {

	$typekit_font_base = 'wpbf-typekit-fonts';
	$typekit_enable    = get_theme_mod( 'enable_typekit' );
	$typekit_fonts     = get_theme_mod( 'typekit_fonts' );

	if ( $typekit_enable && ! empty( $typekit_fonts ) ) {

		foreach ( $typekit_fonts as $key => $typekit_font ) {
			$fonts[$typekit_font['font_css_name']] = $typekit_font_base;
		}
	}

	return $fonts;

}
add_filter( 'elementor/fonts/additional_fonts', 'wpbf_add_elementor_typekit_fonts' );

/**
 * Beaver Builder integration.
 *
 * @param array $bb_fonts The Beaver Builder fonts.
 *
 * @return array The updated Beaver Builder fonts.
 */
function wpbf_bb_typekit_fonts( $bb_fonts ) {

	$typekit_enable = get_theme_mod( 'enable_typekit' );
	$typekit_fonts  = get_theme_mod( 'typekit_fonts' );

	if ( $typekit_enable && ! empty( $typekit_fonts ) ) {

		$fonts = array();

		foreach ( $typekit_fonts as $key => $typekit_font ) {
			$fonts[$typekit_font['font_css_name']] = array(
				'fallback' => 'Verdana, Arial, sans-serif',
				'weights'  => array( '100', '200', '300', '400', '500', '600', '700', '800', '900' ),
			);
		}

		$bb_fonts = array_merge( $bb_fonts, $fonts );

	}

	return $bb_fonts;

}
add_filter( 'fl_theme_system_fonts', 'wpbf_bb_typekit_fonts' );
add_filter( 'fl_builder_font_families_system', 'wpbf_bb_typekit_fonts' );
