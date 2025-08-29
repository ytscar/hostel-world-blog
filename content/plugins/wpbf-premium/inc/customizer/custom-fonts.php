<?php
/**
 * Custom fonts.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Custom fonts optroup.
 *
 * @param array $custom_choice The custom choice.
 *
 * @return array The updated custom choice.
 */
function wpbf_custom_font_group_824520( $custom_choice ) {

	$custom_fonts_enable = get_theme_mod( 'enable_custom_fonts' );
	$custom_fonts        = get_theme_mod( 'custom_fonts' );
	$variants            = array();

	if ( $custom_fonts_enable && ! empty( $custom_fonts ) ) {

		foreach ( $custom_fonts as $key => $custom_font ) {

			$children[] = array(
				'id'   => $custom_font['font_css_name'],
				'text' => $custom_font['font_name'],
			);
			$variants[@$custom_font['font_css_name']] = array( 'regular' );

		}

		$custom_choice['families']['wpbf_premium_custom_fonts'] = array(
			'text'     => esc_attr__( 'Custom Fonts', 'wpbfpremium' ),
			'children' => $children,
		);

		$custom_choice['variants'] = $variants;

	}

	return $custom_choice;

}
add_filter( 'wpbf_kirki_font_choices', 'wpbf_custom_font_group_824520', 10 );

/**
 * Elementor integration.
 *
 * Add font groups.
 *
 * @param array $font_groups The font groups.
 *
 * @return array The updated font groups.
 */
function wpbf_custom_font_elementor_group( $font_groups ) {

	$custom_font_base             = 'wpbf-custom-fonts';
	$new_group[$custom_font_base] = __( 'Custom Fonts', 'wpbfpremium' );
	$font_groups                  = $new_group + $font_groups;

	return $font_groups;

}
add_filter( 'elementor/fonts/groups', 'wpbf_custom_font_elementor_group' );

/**
 * Elementor integration.
 *
 * Add fonts.
 *
 * @param array $fonts The fonts.
 *
 * @return array The updated fonts.
 */
function wpbf_add_elementor_custom_fonts( $fonts ) {

	$custom_font_base    = 'wpbf-custom-fonts';
	$custom_fonts_enable = get_theme_mod( 'enable_custom_fonts' );
	$custom_fonts        = get_theme_mod( 'custom_fonts' );

	if ( $custom_fonts_enable && ! empty( $custom_fonts ) ) {

		foreach ( $custom_fonts as $key => $custom_font ) {
			$fonts[$custom_font['font_css_name']] = $custom_font_base;
		}
	}

	return $fonts;

}
add_filter( 'elementor/fonts/additional_fonts', 'wpbf_add_elementor_custom_fonts' );

/**
 * Beaver Builder integration.
 *
 * @param array $bb_fonts The Beaver Builder fonts.
 *
 * @return array The updated Beaver Builder fonts.
 */
function wpbf_bb_custom_fonts( $bb_fonts ) {

	$custom_fonts_enable = get_theme_mod( 'enable_custom_fonts' );
	$custom_fonts        = get_theme_mod( 'custom_fonts' );

	if ( $custom_fonts_enable && ! empty( $custom_fonts ) ) {

		$fonts = array();

		foreach ( $custom_fonts as $key => $custom_font ) {
			$fonts[$custom_font['font_css_name']] = array(
				'fallback' => 'Verdana, Arial, sans-serif',
				'weights'  => array( '100', '200', '300', '400', '500', '600', '700', '800', '900' ),
			);
		}

		$bb_fonts = array_merge( $bb_fonts, $fonts );

	}

	return $bb_fonts;

}
add_filter( 'fl_theme_system_fonts', 'wpbf_bb_custom_fonts' );
add_filter( 'fl_builder_font_families_system', 'wpbf_bb_custom_fonts' );
