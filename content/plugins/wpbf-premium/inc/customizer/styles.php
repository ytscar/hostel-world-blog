<?php
/**
 * Styles.
 *
 * Holds Customizer CSS styles.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Hook dynamic CSS to wpbf_before_customizer_css.
 */
function wpbf_premium_before_customizer_css() {

	$breakpoint_medium = wpbf_breakpoint_medium() . 'px';
	$breakpoint_mobile = wpbf_breakpoint_mobile() . 'px';

	// CSS variables.
	$base_color_global       = ( $val = get_theme_mod( 'base_color_global' ) ) === '#f5f5f7' ? false : $val;
	$base_color_alt_global   = ( $val = get_theme_mod( 'base_color_alt_global' ) ) === '#dedee5' ? false : $val;
	$brand_color_global      = ( $val = get_theme_mod( 'brand_color_global' ) ) === '#3e4349' ? false : $val;
	$brand_color_alt_global  = ( $val = get_theme_mod( 'brand_color_alt_global' ) ) === '#6d7680' ? false : $val;
	$accent_color_global     = ( $val = get_theme_mod( 'accent_color_global' ) ) === '#3ba9d2' ? false : $val;
	$accent_color_alt_global = ( $val = get_theme_mod( 'accent_color_alt_global' ) ) === '#79c4e0' ? false : $val;

	if ( $base_color_global || $base_color_alt_global || $brand_color_global || $brand_color_alt_global || $accent_color_global || $accent_color_alt_global ) {
		echo ':root {';

		if ( $base_color_global ) {
			echo sprintf( '--base-color-alt: %s;', esc_attr( $base_color_global ) );
		}

		if ( $base_color_alt_global ) {
			echo sprintf( '--base-color: %s;', esc_attr( $base_color_alt_global ) );
		}

		if ( $brand_color_global ) {
			echo sprintf( '--brand-color: %s;', esc_attr( $brand_color_global ) );
		}

		if ( $brand_color_alt_global ) {
			echo sprintf( '--brand-color-alt: %s;', esc_attr( $brand_color_alt_global ) );
		}

		if ( $accent_color_global ) {
			echo sprintf( '--accent-color: %s;', esc_attr( $accent_color_global ) );
		}

		if ( $accent_color_alt_global ) {
			echo sprintf( '--accent-color-alt: %s;', esc_attr( $accent_color_alt_global ) );
		}

		echo '}';
	}

	$color_palette = wpbf_color_palette();

	if ( ! empty( $color_palette ) ) {

		$i = 0;

		foreach ( $color_palette as $color => $value ) {

			++$i;
			echo '.has-wpbf-palette-color-' . $i . '-color {';
				echo sprintf( 'color: %s;', esc_attr( $value ) );
			echo '}';

			echo '.has-wpbf-palette-color-' . $i . '-background-color, .has-wpbf-palette-color-' . $i . '-background-color.has-background-dim {';
				echo sprintf( 'background-color: %s;', esc_attr( $value ) );
			echo '}';

		}
	}

	// Custom fonts.
	$custom_fonts = get_theme_mod( 'custom_fonts' );

	if ( $custom_fonts && get_theme_mod( 'enable_custom_fonts' ) ) {
		wpbf_premium_font_face_css( $custom_fonts );
	}

	// Page font settings.
	$page_line_height       = ( $val = get_theme_mod( 'page_line_height' ) ) === '1.7' ? false : $val;
	$page_bold_color        = get_theme_mod( 'page_bold_color' );
	$page_font_size         = json_decode( get_theme_mod( 'page_font_size' ), true );
	$page_font_size_desktop = wpbf_get_theme_mod_value( $page_font_size, 'desktop', '16px' );
	$page_font_size_tablet  = wpbf_get_theme_mod_value( $page_font_size, 'tablet' );
	$page_font_size_mobile  = wpbf_get_theme_mod_value( $page_font_size, 'mobile' );

	// .wp-block-latest-comments__comment is here because WordPress sets it to 1.1 and we have to set it back to the themes default.
	// Now we need to override it again.
	if ( $page_line_height ) {
		echo 'input, optgroup, textarea, button, body, .wp-block-latest-comments__comment {';
		echo sprintf( 'line-height: %s;', esc_attr( $page_line_height ) );
		echo '}';
	}

	if ( $page_bold_color ) {
		echo 'b, strong {';
		echo sprintf( 'color: %s;', esc_attr( $page_bold_color ) );
		echo '}';
	}

	if ( $page_font_size_desktop ) {
		$suffix = is_numeric( $page_font_size_desktop ) ? 'px' : '';
		echo 'body {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_font_size_desktop ) . $suffix );
		echo '}';
	}

	if ( $page_font_size_tablet ) {
		$suffix = is_numeric( $page_font_size_tablet ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo 'body {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_font_size_tablet ) . $suffix );
		echo '}';
		echo '}';
	}

	if ( $page_font_size_mobile ) {
		$suffix = is_numeric( $page_font_size_mobile ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_mobile ) . ') {';
		echo 'body {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_font_size_mobile ) . $suffix );
		echo '}';
		echo '}';
	}

	// Menu font settings.
	$menu_letter_spacing = ( $val = get_theme_mod( 'menu_letter_spacing' ) ) === '0' ? false : $val;
	$menu_text_transform = ( $val = get_theme_mod( 'menu_text_transform' ) ) === 'none' ? false : $val;

	if ( ! is_bool( $menu_letter_spacing ) || $menu_text_transform ) {
		echo '.wpbf-menu, .wpbf-mobile-menu {';
		if ( ! is_bool( $menu_letter_spacing ) ) {
			echo sprintf( 'letter-spacing: %s;', esc_attr( $menu_letter_spacing ) . 'px' );
		}
		if ( $menu_text_transform ) {
			echo sprintf( 'text-transform: %s;', esc_attr( $menu_text_transform ) );
		}
		echo '}';
	}

	// Sub Menu font settings.
	$sub_menu_letter_spacing = ( $val = get_theme_mod( 'sub_menu_letter_spacing' ) ) === '0' ? false : $val;
	$sub_menu_text_transform = ( $val = get_theme_mod( 'sub_menu_text_transform' ) ) === 'none' ? false : $val;

	if ( ! is_bool( $sub_menu_letter_spacing ) || $sub_menu_text_transform ) {
		echo '.wpbf-menu .sub-menu, .wpbf-mobile-menu .sub-menu {';
		if ( ! is_bool( $sub_menu_letter_spacing ) ) {
			echo sprintf( 'letter-spacing: %s;', esc_attr( $sub_menu_letter_spacing ) . 'px' );
		}
		if ( $sub_menu_text_transform ) {
			echo sprintf( 'text-transform: %s;', esc_attr( $sub_menu_text_transform ) );
		}
		echo '}';
	}

	// H1 font settings.
	$page_h1_font_color        = get_theme_mod( 'page_h1_font_color' );
	$page_h1_line_height       = get_theme_mod( 'page_h1_line_height' );
	$page_h1_letter_spacing    = get_theme_mod( 'page_h1_letter_spacing' );
	$page_h1_text_transform    = ( $val = get_theme_mod( 'page_h1_text_transform' ) ) === 'none' ? false : $val;
	$page_h1_font_size         = json_decode( get_theme_mod( 'page_h1_font_size' ), true );
	$page_h1_font_size_desktop = wpbf_get_theme_mod_value( $page_h1_font_size, 'desktop', '32px' );
	$page_h1_font_size_tablet  = wpbf_get_theme_mod_value( $page_h1_font_size, 'tablet' );
	$page_h1_font_size_mobile  = wpbf_get_theme_mod_value( $page_h1_font_size, 'mobile' );

	if ( $page_h1_font_color || $page_h1_line_height || $page_h1_letter_spacing || $page_h1_text_transform ) {
		echo 'h1, h2, h3, h4, h5, h6 {';
		if ( $page_h1_font_color ) {
			echo sprintf( 'color: %s;', esc_attr( $page_h1_font_color ) );
		}
		if ( $page_h1_line_height ) {
			echo sprintf( 'line-height: %s;', esc_attr( $page_h1_line_height ) );
		}
		if ( $page_h1_letter_spacing ) {
			echo sprintf( 'letter-spacing: %s;', esc_attr( $page_h1_letter_spacing ) . 'px' );
		}
		if ( $page_h1_text_transform ) {
			echo sprintf( 'text-transform: %s;', esc_attr( $page_h1_text_transform ) );
		}
		echo '}';
	}

	if ( $page_h1_font_size_desktop ) {
		$suffix = is_numeric( $page_h1_font_size_desktop ) ? 'px' : '';
		echo 'h1 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h1_font_size_desktop ) . $suffix );
		echo '}';
	}

	if ( $page_h1_font_size_tablet ) {
		$suffix = is_numeric( $page_h1_font_size_tablet ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo 'h1 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h1_font_size_tablet ) . $suffix );
		echo '}';
		echo '}';
	}

	if ( $page_h1_font_size_mobile ) {
		$suffix = is_numeric( $page_h1_font_size_mobile ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_mobile ) . ') {';
		echo 'h1 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h1_font_size_mobile ) . $suffix );
		echo '}';
		echo '}';
	}

	// H2 font settings.
	$page_h2_toggle            = get_theme_mod( 'page_h2_toggle' );
	$page_h2_line_height       = get_theme_mod( 'page_h2_line_height' );
	$page_h2_letter_spacing    = get_theme_mod( 'page_h2_letter_spacing' );
	$page_h2_text_transform    = get_theme_mod( 'page_h2_text_transform', 'none' );
	$page_h2_font_color        = get_theme_mod( 'page_h2_font_color' );
	$page_h2_font_size         = json_decode( get_theme_mod( 'page_h2_font_size' ), true );
	$page_h2_font_size_desktop = wpbf_get_theme_mod_value( $page_h2_font_size, 'desktop', '28px' );
	$page_h2_font_size_tablet  = wpbf_get_theme_mod_value( $page_h2_font_size, 'tablet' );
	$page_h2_font_size_mobile  = wpbf_get_theme_mod_value( $page_h2_font_size, 'mobile' );

	if ( $page_h2_toggle ) {

		if ( $page_h2_line_height || ! is_bool( $page_h2_letter_spacing ) || $page_h2_text_transform ) {

			echo 'h2 {';
			if ( $page_h2_line_height ) {
				echo sprintf( 'line-height: %s;', esc_attr( $page_h2_line_height ) );
			}
			if ( ! is_bool( $page_h2_letter_spacing ) ) {
				echo sprintf( 'letter-spacing: %s;', esc_attr( $page_h2_letter_spacing ) . 'px' );
			}
			if ( $page_h2_text_transform ) {
				echo sprintf( 'text-transform: %s;', esc_attr( $page_h2_text_transform ) );
			}
			echo '}';

		}
	}

	if ( $page_h2_font_color ) {
		echo 'h2 {';
		echo sprintf( 'color: %s;', esc_attr( $page_h2_font_color ) );
		echo '}';
	}

	if ( $page_h2_font_size_desktop ) {
		$suffix = is_numeric( $page_h2_font_size_desktop ) ? 'px' : '';
		echo 'h2 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h2_font_size_desktop ) . $suffix );
		echo '}';
	}

	if ( $page_h2_font_size_tablet ) {
		$suffix = is_numeric( $page_h2_font_size_tablet ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo 'h2 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h2_font_size_tablet ) . $suffix );
		echo '}';
		echo '}';
	}

	if ( $page_h2_font_size_mobile ) {
		$suffix = is_numeric( $page_h2_font_size_mobile ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_mobile ) . ') {';
		echo 'h2 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h2_font_size_mobile ) . $suffix );
		echo '}';
		echo '}';
	}

	// H3 font settings.
	$page_h3_toggle            = get_theme_mod( 'page_h3_toggle' );
	$page_h3_line_height       = get_theme_mod( 'page_h3_line_height' );
	$page_h3_letter_spacing    = get_theme_mod( 'page_h3_letter_spacing' );
	$page_h3_text_transform    = get_theme_mod( 'page_h3_text_transform', 'none' );
	$page_h3_font_color        = get_theme_mod( 'page_h3_font_color' );
	$page_h3_font_size         = json_decode( get_theme_mod( 'page_h3_font_size' ), true );
	$page_h3_font_size_desktop = wpbf_get_theme_mod_value( $page_h3_font_size, 'desktop', '24px' );
	$page_h3_font_size_tablet  = wpbf_get_theme_mod_value( $page_h3_font_size, 'tablet' );
	$page_h3_font_size_mobile  = wpbf_get_theme_mod_value( $page_h3_font_size, 'mobile' );

	if ( $page_h3_toggle ) {

		if ( $page_h3_line_height || ! is_bool( $page_h3_letter_spacing ) || $page_h3_text_transform ) {

			echo 'h3 {';
			if ( $page_h3_line_height ) {
				echo sprintf( 'line-height: %s;', esc_attr( $page_h3_line_height ) );
			}
			if ( ! is_bool( $page_h3_letter_spacing ) ) {
				echo sprintf( 'letter-spacing: %s;', esc_attr( $page_h3_letter_spacing ) . 'px' );
			}
			if ( $page_h3_text_transform ) {
				echo sprintf( 'text-transform: %s;', esc_attr( $page_h3_text_transform ) );
			}
			echo '}';

		}
	}

	if ( $page_h3_font_color ) {
		echo 'h3 {';
		echo sprintf( 'color: %s;', esc_attr( $page_h3_font_color ) );
		echo '}';
	}

	if ( $page_h3_font_size_desktop ) {
		$suffix = is_numeric( $page_h3_font_size_desktop ) ? 'px' : '';
		echo 'h3 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h3_font_size_desktop ) . $suffix );
		echo '}';
	}

	if ( $page_h3_font_size_tablet ) {
		$suffix = is_numeric( $page_h3_font_size_tablet ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo 'h3 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h3_font_size_tablet ) . $suffix );
		echo '}';
		echo '}';
	}

	if ( $page_h3_font_size_mobile ) {
		$suffix = is_numeric( $page_h3_font_size_mobile ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_mobile ) . ') {';
		echo 'h3 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h3_font_size_mobile ) . $suffix );
		echo '}';
		echo '}';
	}

	// H4 font settings.
	$page_h4_toggle            = get_theme_mod( 'page_h4_toggle' );
	$page_h4_line_height       = get_theme_mod( 'page_h4_line_height' );
	$page_h4_letter_spacing    = get_theme_mod( 'page_h4_letter_spacing' );
	$page_h4_text_transform    = get_theme_mod( 'page_h4_text_transform', 'none' );
	$page_h4_font_color        = get_theme_mod( 'page_h4_font_color' );
	$page_h4_font_size         = json_decode( get_theme_mod( 'page_h4_font_size' ), true );
	$page_h4_font_size_desktop = wpbf_get_theme_mod_value( $page_h4_font_size, 'desktop', '20px' );
	$page_h4_font_size_tablet  = wpbf_get_theme_mod_value( $page_h4_font_size, 'tablet' );
	$page_h4_font_size_mobile  = wpbf_get_theme_mod_value( $page_h4_font_size, 'mobile' );

	if ( $page_h4_toggle ) {

		if ( $page_h4_line_height || ! is_bool( $page_h4_letter_spacing ) || $page_h4_text_transform ) {

			echo 'h4 {';
			if ( $page_h4_line_height ) {
				echo sprintf( 'line-height: %s;', esc_attr( $page_h4_line_height ) );
			}
			if ( ! is_bool( $page_h4_letter_spacing ) ) {
				echo sprintf( 'letter-spacing: %s;', esc_attr( $page_h4_letter_spacing ) . 'px' );
			}
			if ( $page_h4_text_transform ) {
				echo sprintf( 'text-transform: %s;', esc_attr( $page_h4_text_transform ) );
			}
			echo '}';

		}
	}

	if ( $page_h4_font_color ) {
		echo 'h4 {';
		echo sprintf( 'color: %s;', esc_attr( $page_h4_font_color ) );
		echo '}';
	}

	if ( $page_h4_font_size_desktop ) {
		$suffix = is_numeric( $page_h4_font_size_desktop ) ? 'px' : '';
		echo 'h4 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h4_font_size_desktop ) . $suffix );
		echo '}';
	}

	if ( $page_h4_font_size_tablet ) {
		$suffix = is_numeric( $page_h4_font_size_tablet ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo 'h4 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h4_font_size_tablet ) . $suffix );
		echo '}';
		echo '}';
	}

	if ( $page_h4_font_size_mobile ) {
		$suffix = is_numeric( $page_h4_font_size_mobile ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_mobile ) . ') {';
		echo 'h4 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h4_font_size_mobile ) . $suffix );
		echo '}';
		echo '}';
	}

	// H5 font settings.
	$page_h5_toggle            = get_theme_mod( 'page_h5_toggle' );
	$page_h5_line_height       = get_theme_mod( 'page_h5_line_height' );
	$page_h5_letter_spacing    = get_theme_mod( 'page_h5_letter_spacing' );
	$page_h5_text_transform    = get_theme_mod( 'page_h5_text_transform', 'none' );
	$page_h5_font_color        = get_theme_mod( 'page_h5_font_color' );
	$page_h5_font_size         = json_decode( get_theme_mod( 'page_h5_font_size' ), true );
	$page_h5_font_size_desktop = wpbf_get_theme_mod_value( $page_h5_font_size, 'desktop', '16px' );
	$page_h5_font_size_tablet  = wpbf_get_theme_mod_value( $page_h5_font_size, 'tablet' );
	$page_h5_font_size_mobile  = wpbf_get_theme_mod_value( $page_h5_font_size, 'mobile' );

	if ( $page_h5_toggle ) {

		if ( $page_h5_line_height || ! is_bool( $page_h5_letter_spacing ) || $page_h5_text_transform ) {

			echo 'h5 {';
			if ( $page_h5_line_height ) {
				echo sprintf( 'line-height: %s;', esc_attr( $page_h5_line_height ) );
			}
			if ( ! is_bool( $page_h5_letter_spacing ) ) {
				echo sprintf( 'letter-spacing: %s;', esc_attr( $page_h5_letter_spacing ) . 'px' );
			}
			if ( $page_h5_text_transform ) {
				echo sprintf( 'text-transform: %s;', esc_attr( $page_h5_text_transform ) );
			}
			echo '}';

		}
	}

	if ( $page_h5_font_color ) {
		echo 'h5 {';
		echo sprintf( 'color: %s;', esc_attr( $page_h5_font_color ) );
		echo '}';
	}

	if ( $page_h5_font_size_desktop ) {
		$suffix = is_numeric( $page_h5_font_size_desktop ) ? 'px' : '';
		echo 'h5 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h5_font_size_desktop ) . $suffix );
		echo '}';
	}

	if ( $page_h5_font_size_tablet ) {
		$suffix = is_numeric( $page_h5_font_size_tablet ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo 'h5 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h5_font_size_tablet ) . $suffix );
		echo '}';
		echo '}';
	}

	if ( $page_h5_font_size_mobile ) {
		$suffix = is_numeric( $page_h5_font_size_mobile ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_mobile ) . ') {';
		echo 'h5 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h5_font_size_mobile ) . $suffix );
		echo '}';
		echo '}';
	}

	// H6 font settings.
	$page_h6_toggle            = get_theme_mod( 'page_h6_toggle' );
	$page_h6_line_height       = get_theme_mod( 'page_h6_line_height' );
	$page_h6_letter_spacing    = get_theme_mod( 'page_h6_letter_spacing' );
	$page_h6_text_transform    = get_theme_mod( 'page_h6_text_transform', 'none' );
	$page_h6_font_color        = get_theme_mod( 'page_h6_font_color' );
	$page_h6_font_size         = json_decode( get_theme_mod( 'page_h6_font_size' ), true );
	$page_h6_font_size_desktop = wpbf_get_theme_mod_value( $page_h6_font_size, 'desktop', '16px' );
	$page_h6_font_size_tablet  = wpbf_get_theme_mod_value( $page_h6_font_size, 'tablet' );
	$page_h6_font_size_mobile  = wpbf_get_theme_mod_value( $page_h6_font_size, 'mobile' );

	if ( $page_h6_toggle ) {

		if ( $page_h6_line_height || ! is_bool( $page_h6_letter_spacing ) || $page_h6_text_transform ) {

			echo 'h6 {';
			if ( $page_h6_line_height ) {
				echo sprintf( 'line-height: %s;', esc_attr( $page_h6_line_height ) );
			}
			if ( ! is_bool( $page_h6_letter_spacing ) ) {
				echo sprintf( 'letter-spacing: %s;', esc_attr( $page_h6_letter_spacing ) . 'px' );
			}
			if ( $page_h6_text_transform ) {
				echo sprintf( 'text-transform: %s;', esc_attr( $page_h6_text_transform ) );
			}
			echo '}';

		}
	}

	if ( $page_h6_font_color ) {
		echo 'h6 {';
		echo sprintf( 'color: %s;', esc_attr( $page_h6_font_color ) );
		echo '}';
	}

	if ( $page_h6_font_size_desktop ) {
		$suffix = is_numeric( $page_h6_font_size_desktop ) ? 'px' : '';
		echo 'h6 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h6_font_size_desktop ) . $suffix );
		echo '}';
	}

	if ( $page_h6_font_size_tablet ) {
		$suffix = is_numeric( $page_h6_font_size_tablet ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_medium ) . ') {';
		echo 'h6 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h6_font_size_tablet ) . $suffix );
		echo '}';
		echo '}';
	}

	if ( $page_h6_font_size_mobile ) {
		$suffix = is_numeric( $page_h6_font_size_mobile ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_mobile ) . ') {';
		echo 'h6 {';
		echo sprintf( 'font-size: %s;', esc_attr( $page_h6_font_size_mobile ) . $suffix );
		echo '}';
		echo '}';
	}

}
add_action( 'wpbf_before_customizer_css', 'wpbf_premium_before_customizer_css', 10 );

/**
 * Hook dynamic CSS to wpbf_after_customizer_css.
 */
function wpbf_premium_after_customizer_css() {

	$breakpoint_desktop = wpbf_breakpoint_desktop() . 'px';
	$breakpoint_medium  = wpbf_breakpoint_medium() . 'px';
	$breakpoint_mobile  = wpbf_breakpoint_mobile() . 'px';

	// Blog Layouts.
	$archives = apply_filters( 'wpbf_archives', array( 'archive' ) );

	foreach ( $archives as $archive ) {

		$layout        = get_theme_mod( $archive . '_layout' );
		$space_between = get_theme_mod( $archive . '_post_space_between' );

		if ( 'grid' === $layout && $space_between ) {
			echo '.wpbf-' . $archive . '-content .wpbf-post-grid .wpbf-article-wrapper {';
			echo sprintf( 'margin-bottom: %s;', esc_attr( $space_between ) . 'px' );
			echo '}';
		}
	}

	// Mobile navigation.
	$mobile_menu_options       = get_theme_mod( 'mobile_menu_options', 'menu-mobile-hamburger' );
	$mobile_menu_width         = ( $val = get_theme_mod( 'mobile_menu_width' ) ) === '320px' ? false : $val;
	$mobile_menu_bg_color      = get_theme_mod( 'mobile_menu_bg_color' );
	$mobile_menu_overlay       = get_theme_mod( 'mobile_menu_overlay' );
	$mobile_menu_overlay_color = ( $val = get_theme_mod( 'mobile_menu_overlay_color' ) ) === 'rgba(0,0,0,0.5)' ? false : $val;
	$mobile_menu_padding       = json_decode( get_theme_mod( 'mobile_menu_padding' ), true );
	$mobile_menu_padding_right = wpbf_get_theme_mod_value( $mobile_menu_padding, 'right', 20 );
	$mobile_menu_padding_left  = wpbf_get_theme_mod_value( $mobile_menu_padding, 'left', 20 );

	if ( 'menu-mobile-off-canvas' === $mobile_menu_options ) {

		if ( $mobile_menu_width || $mobile_menu_bg_color ) {
			echo '.wpbf-mobile-menu-off-canvas .wpbf-mobile-menu-container {';
			if ( $mobile_menu_width ) {
				echo sprintf( 'width: %s;', esc_attr( $mobile_menu_width ) );
				echo sprintf( 'right: %s;', '-' . esc_attr( $mobile_menu_width ) );
			}
			if ( $mobile_menu_bg_color ) {
				echo sprintf( 'background-color: %s;', esc_attr( $mobile_menu_bg_color ) );
			}
			echo '}';
		}

		if ( $mobile_menu_overlay && $mobile_menu_overlay_color ) {
			echo '.wpbf-mobile-menu-overlay {';
			echo sprintf( 'background: %s;', esc_attr( $mobile_menu_overlay_color ) );
			echo '}';
		}

		if ( $mobile_menu_padding_right || $mobile_menu_padding_left ) {
			echo '.wpbf-mobile-menu-off-canvas .wpbf-close {';
			if ( $mobile_menu_padding_right ) {
				echo sprintf( 'padding-right: %s;', esc_attr( $mobile_menu_padding_right ) . 'px' );
			}
			if ( $mobile_menu_padding_left ) {
				echo sprintf( 'padding-left: %s;', esc_attr( $mobile_menu_padding_left ) . 'px' );
			}
			echo '}';
		}
	}

	// Stacked advanced.
	$menu_position            = get_theme_mod( 'menu_position' );
	$menu_width               = ( $val = get_theme_mod( 'menu_width' ) ) === '1200px' ? false : $val;
	$menu_stacked_bg_color    = ( $val = get_theme_mod( 'menu_stacked_bg_color' ) ) === '#ffffff' ? false : $val;
	$menu_stacked_logo_height = ( $val = get_theme_mod( 'menu_stacked_logo_height' ) ) === '20' ? false : $val;

	if ( 'menu-stacked-advanced' === $menu_position ) {

		if ( $menu_width ) {
			echo '.wpbf-menu-stacked-advanced-wrapper .wpbf-container {';
			echo sprintf( 'max-width: %s;', esc_attr( $menu_width ) );
			echo '}';
		}

		if ( $menu_stacked_bg_color ) {
			echo '.wpbf-menu-stacked-advanced-wrapper {';
			echo sprintf( 'background-color: %s;', esc_attr( $menu_stacked_bg_color ) );
			echo '}';
		}

		if ( $menu_stacked_logo_height ) {
			echo '.wpbf-menu-stacked-advanced-wrapper {';
			echo sprintf( 'padding-top: %s;', esc_attr( $menu_stacked_logo_height ) . 'px' );
			echo sprintf( 'padding-bottom: %s;', esc_attr( $menu_stacked_logo_height ) . 'px' );
			echo '}';
		}
	}

	// Off canvas & full screen navigation.
	$menu_off_canvas_hamburger_size      = ( $val = get_theme_mod( 'menu_off_canvas_hamburger_size' ) ) === '16px' ? false : $val;
	$menu_padding                        = ( $val = get_theme_mod( 'menu_padding' ) ) === '20' ? false : $val;
	$menu_off_canvas_bg_color            = ( $val = get_theme_mod( 'menu_off_canvas_bg_color' ) ) === '#ffffff' ? false : $val;
	$menu_off_canvas_hamburger_color     = ( $val = get_theme_mod( 'menu_off_canvas_hamburger_color' ) ) === '#6d7680' ? false : $val;
	$menu_off_canvas_submenu_arrow_color = get_theme_mod( 'menu_off_canvas_submenu_arrow_color' );
	$menu_off_canvas_width               = ( $val = get_theme_mod( 'menu_off_canvas_width' ) ) === '400' ? false : $val;
	$menu_overlay                        = get_theme_mod( 'menu_overlay' );
	$menu_overlay_color                  = ( $val = get_theme_mod( 'menu_overlay_color' ) ) === 'rgba(0,0,0,.5)' ? false : $val;

	if ( $menu_off_canvas_hamburger_size && in_array( $menu_position, array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ) ) ) {
		echo '.wpbf-menu-toggle {';
		echo sprintf( 'font-size: %s;', esc_attr( $menu_off_canvas_hamburger_size ) );
		echo '}';
	}

	if ( get_theme_mod( 'menu_padding' ) && in_array( $menu_position, array( 'menu-off-canvas', 'menu-off-canvas-left' ) ) ) {
		echo '.wpbf-menu > .menu-item > a {';
		echo 'padding-left: 0px;';
		echo 'padding-right: 0px;';
		echo '}';
	}

	if ( $menu_off_canvas_bg_color && in_array( $menu_position, array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ) ) ) {
		echo '.wpbf-menu-off-canvas, .wpbf-menu-full-screen {';
		echo sprintf( 'background-color: %s;', esc_attr( $menu_off_canvas_bg_color ) );
		echo '}';
	}

	if ( $menu_off_canvas_hamburger_color && in_array( $menu_position, array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ) ) ) {
		echo '.wpbf-nav-item, .wpbf-nav-item a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_off_canvas_hamburger_color ) );
		echo '}';
	}

	if ( $menu_off_canvas_submenu_arrow_color && in_array( $menu_position, array( 'menu-off-canvas', 'menu-off-canvas-left' ) ) ) {
		echo '.wpbf-menu-off-canvas .wpbf-submenu-toggle {';
		echo sprintf( 'color: %s;', esc_attr( $menu_off_canvas_submenu_arrow_color ) );
		echo '}';
	}

	if ( $menu_off_canvas_width && 'menu-off-canvas' === $menu_position ) {

		echo '.wpbf-menu-off-canvas-right {';
		echo sprintf( 'width: %s;', esc_attr( $menu_off_canvas_width ) . 'px' );
		echo sprintf( 'right: %s;', '-' . esc_attr( $menu_off_canvas_width ) . 'px' );
		echo '}';

		echo '.wpbf-push-menu-right.active {';
		echo sprintf( 'left: %s;', '-' . esc_attr( $menu_off_canvas_width ) . 'px' );
		echo '}';

		echo '.wpbf-push-menu-right.active .wpbf-navigation-active {';
		echo sprintf( 'left: %s;', '-' . esc_attr( $menu_off_canvas_width ) . 'px !important' );
		echo '}';

	}

	if ( $menu_off_canvas_width && 'menu-off-canvas-left' === $menu_position ) {

		echo '.wpbf-menu-off-canvas-left {';
		echo sprintf( 'width: %s;', esc_attr( $menu_off_canvas_width ) . 'px' );
		echo sprintf( 'left: %s;', '-' . esc_attr( $menu_off_canvas_width ) . 'px' );
		echo '}';

		echo '.wpbf-push-menu-left.active {';
		echo sprintf( 'left: %s;', esc_attr( $menu_off_canvas_width ) . 'px' );
		echo '}';

		echo '.wpbf-push-menu-left.active .wpbf-navigation-active {';
		echo sprintf( 'left: %s;', esc_attr( $menu_off_canvas_width ) . 'px !important' );
		echo '}';

	}

	if ( 'menu-full-screen' === $menu_position && $menu_padding ) {
		echo '.wpbf-menu-full-screen .wpbf-menu > .menu-item > a {';
		echo sprintf( 'padding-top: %s;', esc_attr( $menu_padding ) . 'px' );
		echo sprintf( 'padding-bottom: %s;', esc_attr( $menu_padding ) . 'px' );
		echo '}';
	}

	if ( $menu_overlay && $menu_overlay_color && in_array( $menu_position, array( 'menu-off-canvas', 'menu-off-canvas-left' ) ) ) {
		echo '.wpbf-menu-overlay {';
		echo sprintf( 'background: %s;', esc_attr( $menu_overlay_color ) );
		echo '}';
	}

	// Transparent header.
	$has_custom_logo                            = has_custom_logo();
	$menu_transparent_width                     = ( $val = get_theme_mod( 'menu_transparent_width' ) ) === '1200px' ? false : $val;
	$menu_transparent_background_color          = get_theme_mod( 'menu_transparent_background_color' );
	$menu_transparent_font_color                = get_theme_mod( 'menu_transparent_font_color' );
	$menu_transparent_font_color_alt            = get_theme_mod( 'menu_transparent_font_color_alt' );
	$menu_transparent_logo_color                = get_theme_mod( 'menu_transparent_logo_color' );
	$menu_transparent_logo_color_alt            = get_theme_mod( 'menu_transparent_logo_color_alt' );
	$menu_transparent_tagline_color             = get_theme_mod( 'menu_transparent_tagline_color' );
	$mobile_menu_hamburger_bg_color             = get_theme_mod( 'mobile_menu_hamburger_bg_color' );
	$menu_transparent_hamburger_bg_color_mobile = get_theme_mod( 'menu_transparent_hamburger_bg_color_mobile' );
	$menu_transparent_hamburger_color_mobile    = get_theme_mod( 'menu_transparent_hamburger_color_mobile' );
	$menu_transparent_hamburger_color           = get_theme_mod( 'menu_transparent_hamburger_color' );

	if ( $menu_transparent_width ) {
		echo '.wpbf-navigation-transparent .wpbf-nav-wrapper {';
		echo sprintf( 'max-width: %s;', esc_attr( $menu_transparent_width ) );
		echo '}';
	}

	if ( $menu_transparent_background_color ) {
		echo '.wpbf-navigation-transparent, .wpbf-navigation-transparent .wpbf-mobile-nav-wrapper {';
		echo sprintf( 'background-color: %s;', esc_attr( $menu_transparent_background_color ) );
		echo '}';
	}

	if ( $menu_transparent_font_color ) {
		echo '.wpbf-navigation-transparent .wpbf-menu > .menu-item > a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_transparent_font_color ) );
		echo '}';
	}

	if ( $menu_transparent_font_color_alt ) {
		echo '.wpbf-navigation-transparent .wpbf-menu > .menu-item > a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $menu_transparent_font_color_alt ) );
		echo '}';
		echo '.wpbf-navigation-transparent .wpbf-menu > .current-menu-item > a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_transparent_font_color_alt ) . '!important' );
		echo '}';
	}

	if ( $menu_transparent_logo_color && ! $has_custom_logo ) {
		echo '.wpbf-navigation-transparent .wpbf-logo a, .wpbf-navigation-transparent .wpbf-mobile-logo a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_transparent_logo_color ) );
		echo '}';
	}

	if ( $menu_transparent_logo_color_alt && ! $has_custom_logo ) {
		echo '.wpbf-navigation-transparent .wpbf-logo a:hover, .wpbf-navigation-transparent .wpbf-mobile-logo a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $menu_transparent_logo_color_alt ) );
		echo '}';
	}

	if ( $menu_transparent_tagline_color && ! $has_custom_logo && $menu_logo_description ) {
		echo '.wpbf-navigation-transparent .wpbf-tagline {';
		echo sprintf( 'color: %s;', esc_attr( $menu_transparent_tagline_color ) );
		echo '}';
	}

	if ( $menu_transparent_hamburger_color ) {
		echo '.wpbf-navigation-transparent .wpbf-nav-item, .wpbf-navigation-transparent .wpbf-nav-item a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_transparent_hamburger_color ) );
		echo '}';
	}

	// Transparent header mobile.
	if ( in_array( $mobile_menu_options, array( 'menu-mobile-hamburger', 'menu-mobile-off-canvas' ) ) ) {

		if ( $menu_transparent_hamburger_color_mobile ) {
			echo '.wpbf-navigation-transparent .wpbf-mobile-nav-item, .wpbf-navigation-transparent .wpbf-mobile-nav-item a {';
			echo sprintf( 'color: %s;', esc_attr( $menu_transparent_hamburger_color_mobile ) );
			echo '}';
		}

		if ( $mobile_menu_hamburger_bg_color && $menu_transparent_hamburger_bg_color_mobile ) {
			echo '.wpbf-navigation-transparent .wpbf-mobile-menu-toggle {';
			echo sprintf( 'background-color: %s;', esc_attr( $menu_transparent_hamburger_bg_color_mobile ) );
			echo '}';
		}
	}

	// Sticky navigation.
	$menu_sticky                            = get_theme_mod( 'menu_sticky' );
	$menu_active_hide_logo                  = get_theme_mod( 'menu_active_hide_logo' );
	$menu_active_logo_size                  = json_decode( get_theme_mod( 'menu_active_logo_size' ), true );
	$menu_active_logo_size_desktop          = wpbf_get_theme_mod_value( $menu_active_logo_size, 'desktop' );
	$menu_active_logo_size_tablet           = wpbf_get_theme_mod_value( $menu_active_logo_size, 'tablet' );
	$menu_active_logo_size_mobile           = wpbf_get_theme_mod_value( $menu_active_logo_size, 'mobile' );
	$menu_active_width                      = ( $val = get_theme_mod( 'menu_active_width' ) ) === '1200px' ? false : $val;
	$menu_active_height                     = ( $val = get_theme_mod( 'menu_active_height' ) ) === '20' ? false : $val;
	$menu_active_stacked_bg_color           = ( $val = get_theme_mod( 'menu_active_stacked_bg_color' ) ) === '#f5f5f7' ? false : $val;
	$menu_active_bg_color                   = ( $val = get_theme_mod( 'menu_active_bg_color' ) ) === '#f5f5f7' ? false : $val;
	$menu_active_font_color                 = get_theme_mod( 'menu_active_font_color' );
	$menu_active_font_color_alt             = get_theme_mod( 'menu_active_font_color_alt' );
	$menu_active_logo_color                 = get_theme_mod( 'menu_active_logo_color' );
	$menu_active_logo_color_alt             = get_theme_mod( 'menu_active_logo_color_alt' );
	$menu_logo_description                  = get_theme_mod( 'menu_logo_description' );
	$menu_active_tagline_color              = get_theme_mod( 'menu_active_tagline_color' );
	$menu_active_box_shadow                 = get_theme_mod( 'menu_active_box_shadow' );
	$menu_active_box_shadow_blur            = ( $val = get_theme_mod( 'menu_active_box_shadow_blur' ) ) ? $val . 'px' : '5px';
	$menu_active_box_shadow_color           = ( $val = get_theme_mod( 'menu_active_box_shadow_color' ) ) ? $val : 'rgba(0,0,0,.15)';
	$menu_active_off_canvas_hamburger_color = get_theme_mod( 'menu_active_off_canvas_hamburger_color' );
	$menu_active_mobile_disabled            = get_theme_mod( 'menu_active_mobile_disabled' );
	$mobile_menu_active_hamburger_color     = get_theme_mod( 'mobile_menu_active_hamburger_color' );
	$mobile_menu_active_hamburger_bg_color  = get_theme_mod( 'mobile_menu_active_hamburger_bg_color' );

	if ( $menu_sticky && $menu_active_hide_logo ) {

		if ( 'menu-stacked' === $menu_position ) {
			echo '.wpbf-navigation-active .wpbf-logo {';
			echo 'display: none;';
			echo '}';
			echo '.wpbf-navigation-active nav {';
			echo 'margin-top: 0 !important;';
			echo '}';
		}

		if ( 'menu-stacked-advanced' === $menu_position ) {
			echo '.wpbf-navigation-active .wpbf-menu-stacked-advanced-wrapper {';
			echo 'display: none;';
			echo '}';
		}

		if ( 'menu-centered' === $menu_position ) {
			echo '.wpbf-navigation-active .logo-container {';
			echo 'display: none !important;';
			echo '}';
		}
	}

	if ( $menu_active_logo_size_desktop ) {
		$suffix = is_numeric( $menu_active_logo_size_desktop ) ? 'px' : '';
		echo '.wpbf-navigation-active .wpbf-logo img {';
		echo sprintf( 'width: %s;', esc_attr( $menu_active_logo_size_desktop ) . $suffix );
		echo '}';
	}

	if ( $menu_active_logo_size_tablet ) {
		$suffix = is_numeric( $menu_active_logo_size_tablet ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_desktop ) . ') {';
		echo '.wpbf-navigation-active .wpbf-mobile-logo img {';
		echo sprintf( 'width: %s;', esc_attr( $menu_active_logo_size_tablet ) . $suffix );
		echo '}';
		echo '}';
	}

	if ( $menu_active_logo_size_mobile ) {
		$suffix = is_numeric( $menu_active_logo_size_mobile ) ? 'px' : '';
		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_mobile ) . ') {';
		echo '.wpbf-navigation-active .wpbf-mobile-logo img {';
		echo sprintf( 'width: %s;', esc_attr( $menu_active_logo_size_mobile ) . $suffix );
		echo '}';
		echo '}';
	}

	if ( $menu_active_width ) {
		echo '.wpbf-navigation-active .wpbf-nav-wrapper {';
		echo sprintf( 'max-width: %s;', esc_attr( $menu_active_width ) );
		echo '}';
	}

	if ( $menu_active_height ) {

		echo '.wpbf-navigation-active .wpbf-nav-wrapper {';
		echo sprintf( 'padding-top: %s;', esc_attr( $menu_active_height ) . 'px' );
		echo sprintf( 'padding-bottom: %s;', esc_attr( $menu_active_height ) . 'px' );
		echo '}';

		if ( 'menu-stacked' === $menu_position ) {
			echo '.wpbf-navigation-active .wpbf-menu-stacked nav {';
			echo sprintf( 'margin-top: %s;', esc_attr( $menu_active_height ) . 'px' );
			echo '}';
		}
	}

	if ( $menu_active_stacked_bg_color && 'menu-stacked-advanced' === $menu_position ) {
		echo '.wpbf-navigation-active .wpbf-menu-stacked-advanced-wrapper {';
		echo sprintf( 'background-color: %s;', esc_attr( $menu_active_stacked_bg_color ) );
		echo '}';
	}

	if ( $menu_active_bg_color ) {
		echo '.wpbf-navigation-active, .wpbf-navigation-active .wpbf-mobile-nav-wrapper {';
		echo sprintf( 'background-color: %s;', esc_attr( $menu_active_bg_color ) );
		echo '}';
	}

	if ( $menu_active_logo_color && ! $has_custom_logo ) {
		echo '.wpbf-navigation-active .wpbf-logo a, .wpbf-navigation-active .wpbf-mobile-logo a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_active_logo_color ) );
		echo '}';
	}

	if ( $menu_active_logo_color_alt && ! $has_custom_logo ) {
		echo '.wpbf-navigation-active .wpbf-logo a:hover, .wpbf-navigation-active .wpbf-mobile-logo a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $menu_active_logo_color_alt ) );
		echo '}';
	}

	if ( $menu_active_tagline_color && ! $has_custom_logo && $menu_logo_description ) {
		echo '.wpbf-navigation-active .wpbf-tagline {';
		echo sprintf( 'color: %s;', esc_attr( $menu_active_tagline_color ) );
		echo '}';
	}

	if ( $menu_active_font_color ) {
		echo '.wpbf-navigation-active .wpbf-menu > .menu-item > a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_active_font_color ) );
		echo '}';
	}

	if ( $menu_active_font_color_alt ) {
		echo '.wpbf-navigation-active .wpbf-menu > .menu-item > a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $menu_active_font_color_alt ) );
		echo '}';
		echo '.wpbf-navigation-active .wpbf-menu > .current-menu-item > a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_active_font_color_alt ) . '!important' );
		echo '}';
	}

	if ( $menu_sticky && $menu_active_box_shadow ) {
		echo '.wpbf-navigation.wpbf-navigation-active {';
		echo sprintf( 'box-shadow: 0px 0px %1$s 0px %2$s;', esc_attr( $menu_active_box_shadow_blur ), esc_attr( $menu_active_box_shadow_color ) );
		echo '}';
	}

	// Sticky off canvas navigation.
	if ( in_array( $menu_position, array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ) ) && $menu_active_off_canvas_hamburger_color ) {
		echo '.wpbf-navigation-active .wpbf-nav-item, .wpbf-navigation-active .wpbf-nav-item a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_active_off_canvas_hamburger_color ) );
		echo '}';
	}

	// Mobile sticky navigation.
	if ( in_array( $mobile_menu_options, array( 'menu-mobile-hamburger', 'menu-mobile-off-canvas' ) ) ) {

		if ( $mobile_menu_active_hamburger_color ) {
			echo '.wpbf-navigation-active .wpbf-mobile-nav-item, .wpbf-navigation-active .wpbf-mobile-nav-item a {';
			echo sprintf( 'color: %s;', esc_attr( $mobile_menu_active_hamburger_color ) );
			echo '}';
		}

		if ( $mobile_menu_hamburger_bg_color && $mobile_menu_active_hamburger_bg_color ) {
			echo '.wpbf-navigation-active .wpbf-mobile-menu-toggle {';
			echo sprintf( 'background-color: %s;', esc_attr( $mobile_menu_active_hamburger_bg_color ) );
			echo '}';
		}
	}

	// Disable on mobile.
	if ( $menu_sticky && $menu_active_mobile_disabled ) {

		echo '@media screen and (max-width: ' . esc_attr( $breakpoint_desktop ) . ') {';
		echo '.wpbf-navigation-active {';
		echo 'display: none !important;';
		echo '}';
		echo '}';

	}

	// Call to Action button.
	$cta_button_border_radius                    = get_theme_mod( 'cta_button_border_radius' );
	$cta_button_background_color                 = get_theme_mod( 'cta_button_background_color' );
	$cta_button_background_color_alt             = get_theme_mod( 'cta_button_background_color_alt' );
	$cta_button_font_color                       = get_theme_mod( 'cta_button_font_color' );
	$cta_button_font_color_alt                   = get_theme_mod( 'cta_button_font_color_alt' );
	$cta_button_transparent_background_color     = get_theme_mod( 'cta_button_transparent_background_color' );
	$cta_button_transparent_background_color_alt = get_theme_mod( 'cta_button_transparent_background_color_alt' );
	$cta_button_transparent_font_color           = get_theme_mod( 'cta_button_transparent_font_color' );
	$cta_button_transparent_font_color_alt       = get_theme_mod( 'cta_button_transparent_font_color_alt' );
	$cta_button_sticky_background_color          = get_theme_mod( 'cta_button_sticky_background_color' );
	$cta_button_sticky_background_color_alt      = get_theme_mod( 'cta_button_sticky_background_color_alt' );
	$cta_button_sticky_font_color                = get_theme_mod( 'cta_button_sticky_font_color' );
	$cta_button_sticky_font_color_alt            = get_theme_mod( 'cta_button_sticky_font_color_alt' );

	if ( $cta_button_border_radius ) {
		echo '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a {';
		echo sprintf( 'border-radius: %s;', esc_attr( $cta_button_border_radius ) . 'px' );
		echo '}';
	}

	if ( $cta_button_background_color || $cta_button_font_color ) {
		echo '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a, .wpbf-mobile-menu .wpbf-cta-menu-item a {';
		if ( $cta_button_background_color ) {
			echo sprintf( 'background: %s;', esc_attr( $cta_button_background_color ) );
		}
		if ( $cta_button_font_color ) {
			echo sprintf( 'color: %s;', esc_attr( $cta_button_font_color ) );
		}
		echo '}';

		if ( ! $cta_button_font_color_alt ) {
			echo '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a:hover, .wpbf-mobile-menu .wpbf-cta-menu-item a:hover {';
			echo sprintf( 'color: %s;', esc_attr( $cta_button_font_color ) );
			echo '}';
		}

		if ( ! $cta_button_background_color_alt ) {
			echo '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a:hover, .wpbf-mobile-menu .wpbf-cta-menu-item a:hover {';
			echo sprintf( 'background: %s;', esc_attr( $cta_button_background_color ) );
			echo '}';
		}

		if ( $cta_button_font_color ) {
			echo '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item.current-menu-item a {';
			echo sprintf( 'color: %s;', esc_attr( $cta_button_font_color ) . '!important' );
			echo '}';
		}
	}

	if ( $cta_button_background_color_alt || $cta_button_font_color_alt ) {
		echo '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a:hover, .wpbf-mobile-menu .wpbf-cta-menu-item a:hover {';
		if ( $cta_button_background_color_alt ) {
			echo sprintf( 'background: %s;', esc_attr( $cta_button_background_color_alt ) );
		}
		if ( $cta_button_font_color_alt ) {
			echo sprintf( 'color: %s;', esc_attr( $cta_button_font_color_alt ) );
		}
		echo '}';

		if ( $cta_button_font_color_alt ) {
			echo '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item.current-menu-item a:hover {';
			echo sprintf( 'color: %s;', esc_attr( $cta_button_font_color_alt ) . '!important' );
			echo '}';
		}
	}

	if ( $cta_button_transparent_background_color || $cta_button_transparent_font_color ) {
		echo '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item a {';
		if ( $cta_button_transparent_background_color ) {
			echo sprintf( 'background: %s;', esc_attr( $cta_button_transparent_background_color ) );
		}
		if ( $cta_button_transparent_font_color ) {
			echo sprintf( 'color: %s;', esc_attr( $cta_button_transparent_font_color ) );
		}
		echo '}';

		if ( ! $cta_button_transparent_font_color_alt ) {
			echo '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item a:hover {';
			echo sprintf( 'color: %s;', esc_attr( $cta_button_transparent_font_color ) );
			echo '}';
		}

		if ( ! $cta_button_transparent_background_color_alt ) {
			echo '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item a:hover {';
			echo sprintf( 'background: %s;', esc_attr( $cta_button_transparent_background_color ) );
			echo '}';
		}

		if ( $cta_button_transparent_font_color ) {
			echo '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item.current-menu-item a {';
			echo sprintf( 'color: %s;', esc_attr( $cta_button_transparent_font_color ) . '!important' );
			echo '}';
		}
	}

	if ( $cta_button_transparent_background_color_alt || $cta_button_transparent_font_color_alt ) {
		echo '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item a:hover {';
		if ( $cta_button_transparent_background_color_alt ) {
			echo sprintf( 'background: %s;', esc_attr( $cta_button_transparent_background_color_alt ) );
		}
		if ( $cta_button_transparent_font_color_alt ) {
			echo sprintf( 'color: %s;', esc_attr( $cta_button_transparent_font_color_alt ) );
		}
		echo '}';

		if ( $cta_button_transparent_font_color_alt ) {
			echo '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item.current-menu-item a:hover {';
			echo sprintf( 'color: %s;', esc_attr( $cta_button_transparent_font_color_alt ) . '!important' );
			echo '}';
		}
	}

	if ( $cta_button_sticky_background_color || $cta_button_sticky_font_color ) {
		echo '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item a {';
		if ( $cta_button_sticky_background_color ) {
			echo sprintf( 'background: %s;', esc_attr( $cta_button_sticky_background_color ) );
		}
		if ( $cta_button_sticky_font_color ) {
			echo sprintf( 'color: %s;', esc_attr( $cta_button_sticky_font_color ) );
		}
		echo '}';

		if ( ! $cta_button_sticky_font_color_alt ) {
			echo '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item a:hover {';
			echo sprintf( 'color: %s;', esc_attr( $cta_button_sticky_font_color ) );
			echo '}';
		}

		if ( ! $cta_button_sticky_background_color_alt ) {
			echo '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item a:hover {';
			echo sprintf( 'background: %s;', esc_attr( $cta_button_sticky_background_color ) );
			echo '}';
		}

		if ( $cta_button_sticky_font_color ) {
			echo '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item.current-menu-item a {';
			echo sprintf( 'color: %s;', esc_attr( $cta_button_sticky_font_color ) . '!important' );
			echo '}';
		}
	}

	if ( $cta_button_sticky_background_color_alt || $cta_button_sticky_font_color_alt ) {
		echo '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item a:hover {';
		if ( $cta_button_sticky_background_color_alt ) {
			echo sprintf( 'background: %s;', esc_attr( $cta_button_sticky_background_color_alt ) );
		}
		if ( $cta_button_sticky_font_color_alt ) {
			echo sprintf( 'color: %s;', esc_attr( $cta_button_sticky_font_color_alt ) );
		}
		echo '}';

		if ( $cta_button_sticky_font_color_alt ) {
			echo '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item.current-menu-item a:hover {';
			echo sprintf( 'color: %s;', esc_attr( $cta_button_sticky_font_color_alt ) . '!important' );
			echo '}';
		}
	}

	// Navigation hover effects.
	$menu_effect                 = get_theme_mod( 'menu_effect', 'none' );
	$menu_font_color_alt         = get_theme_mod( 'menu_font_color_alt' );
	$menu_effect_color           = get_theme_mod( 'menu_effect_color' );
	$menu_effect_underlined_size = ( $val = get_theme_mod( 'menu_effect_underlined_size' ) ) === '2' ? false : $val;
	$menu_effect_boxed_radius    = get_theme_mod( 'menu_effect_boxed_radius' );
	$menu_effect_padding         = $menu_padding * 2 - 10;

	// Underlined.
	if ( 'underlined' === $menu_effect && ( $menu_effect_underlined_size || $menu_effect_color ) ) {

		echo '.wpbf-menu-effect-underlined > .menu-item > a:after {';
		if ( $menu_effect_underlined_size ) {
			echo sprintf( 'height: %s;', esc_attr( $menu_effect_underlined_size ) . 'px' );
		}
		if ( $menu_effect_color ) {
			echo sprintf( 'background-color: %s;', esc_attr( $menu_effect_color ) );
		}
		echo '}';

	}

	// Boxed.
	if ( 'boxed' === $menu_effect && ( $menu_effect_boxed_radius || $menu_effect_color ) ) {

		echo '.wpbf-menu-effect-boxed > .menu-item > a:before {';
		if ( $menu_effect_color ) {
			echo sprintf( 'background-color: %s;', esc_attr( $menu_effect_color ) );
		}
		if ( $menu_effect_boxed_radius ) {
			echo sprintf( 'border-radius: %s;', esc_attr( $menu_effect_boxed_radius ) . 'px' );
		}
		echo '}';

	}

	// Modern.
	if ( 'modern' === $menu_effect && $menu_effect_color ) {

		echo '.wpbf-menu-effect-modern > .menu-item > a:after {';
			echo sprintf( 'background-color: %s;', esc_attr( $menu_effect_color ) );
		echo '}';

	}

	if ( 'modern' === $menu_effect && $menu_padding ) {

		// Modern hover.
		echo '.wpbf-menu-effect-modern > .menu-item > a:hover:after {';
		echo 'width: -moz-calc(100% - ' . esc_attr( $menu_effect_padding ) . 'px);';
		echo 'width: -webkit-calc(100% - ' . esc_attr( $menu_effect_padding ) . 'px);';
		echo 'width: -o-calc(100% - ' . esc_attr( $menu_effect_padding ) . 'px);';
		echo 'width: calc(100% - ' . esc_attr( $menu_effect_padding ) . 'px);';
		echo '}';

		// Modern current menu item.
		echo '.wpbf-menu-effect-modern > .current-menu-item > a:after {';
		echo 'width: -moz-calc(100% - ' . esc_attr( $menu_effect_padding ) . 'px);';
		echo 'width: -webkit-calc(100% - ' . esc_attr( $menu_effect_padding ) . 'px);';
		echo 'width: -o-calc(100% - ' . esc_attr( $menu_effect_padding ) . 'px);';
		echo 'width: calc(100% - ' . esc_attr( $menu_effect_padding ) . 'px);';
		echo '}';

	}

	// Footer.
	$footer_sticky = get_theme_mod( 'footer_sticky' );
	$page_boxed    = get_theme_mod( 'page_boxed' );

	$footer_widgets          = ( $val = get_theme_mod( 'footer_widgets' ) ) === 'disabled' ? false : $val;
	$footer_width            = ( $val = get_theme_mod( 'footer_widgets_width' ) ) === '1200px' ? false : $val;
	$footer_bg_color         = ( $val = get_theme_mod( 'footer_widgets_bg_color' ) ) === '#f5f5f7' ? false : $val;
	$footer_headline_color   = get_theme_mod( 'footer_widgets_headline_color' );
	$footer_font_color       = get_theme_mod( 'footer_widgets_font_color' );
	$footer_accent_color     = get_theme_mod( 'footer_widgets_accent_color' );
	$footer_accent_color_alt = get_theme_mod( 'footer_widgets_accent_color_alt' );
	$footer_font_size        = ( $val = get_theme_mod( 'footer_widgets_font_size' ) ) === '14px' ? false : $val;

	if ( $footer_sticky && ! $page_boxed ) {

		?>

		html{
			height: 100%;
		}

		body, #container{
			display: flex;
			flex-direction: column;
			height: 100%;
		}

		#content{
			flex: 1 0 auto;
		}

		.wpbf-page-footer{
			flex: 0 0 auto;
		}

		<?php

	}

	if ( $footer_widgets && $footer_width ) {
		echo '.wpbf-inner-widget-footer {';
		echo sprintf( 'max-width: %s;', esc_attr( $footer_width ) );
		echo '}';
	}

	if ( $footer_widgets && $footer_headline_color ) {

		echo '.wpbf-widget-footer .wpbf-widgettitle {';
		echo sprintf( 'color: %s;', esc_attr( $footer_headline_color ) );
		echo '}';

	}

	if ( $footer_widgets && ( $footer_bg_color || $footer_font_color || $footer_font_size ) ) {

		echo '.wpbf-widget-footer {';

		if ( $footer_bg_color ) {
			echo sprintf( 'background-color: %s;', esc_attr( $footer_bg_color ) );
		}

		if ( $footer_font_color ) {
			echo sprintf( 'color: %s;', esc_attr( $footer_font_color ) );
		}

		if ( $footer_font_size ) {
			echo sprintf( 'font-size: %s;', esc_attr( $footer_font_size ) );
		}

		echo '}';

	}

	if ( $footer_widgets && $footer_accent_color ) {

		echo '.wpbf-widget-footer a {';
		echo sprintf( 'color: %s;', esc_attr( $footer_accent_color ) );
		echo '}';

	}

	if ( $footer_widgets && $footer_accent_color_alt ) {

		echo '.wpbf-widget-footer a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $footer_accent_color_alt ) );
		echo '}';

	}

	// Social.
	$social_shapes               = get_theme_mod( 'social_shapes' );
	$social_styles               = get_theme_mod( 'social_styles' );
	$social_background_color     = ( $val = get_theme_mod( 'social_background_color' ) ) === '#f5f5f7' ? false : $val;
	$social_background_color_alt = get_theme_mod( 'social_background_color_alt' );
	$social_color                = ( $val = get_theme_mod( 'social_color' ) ) === '#aaaaaa' ? false : $val;
	$social_color_alt            = get_theme_mod( 'social_color_alt' );
	$social_font_size            = ( $val = get_theme_mod( 'social_font_size' ) ) === '14' ? false : $val;

	if ( 'wpbf-social-shape-plain' !== $social_shapes && 'wpbf-social-style-filled' !== $social_styles ) {

		if ( $social_background_color ) {
			echo '.wpbf-social-icons a {';
			echo sprintf( 'background: %s;', esc_attr( $social_background_color ) );
			echo '}';
		}

		if ( $social_background_color_alt ) {
			echo '.wpbf-social-icons a:hover {';
			echo sprintf( 'background: %s;', esc_attr( $social_background_color_alt ) );
			echo '}';
		}
	}

	if ( 'wpbf-social-style-grey' === $social_styles ) {

		if ( $social_color ) {
			echo '.wpbf-social-icons a, .wpbf-social-icons a:hover {';
			echo sprintf( 'color: %s;', esc_attr( $social_color ) );
			echo '}';
		}

		if ( $social_color_alt ) {
			echo '.wpbf-social-icons a:hover {';
			echo sprintf( 'color: %s;', esc_attr( $social_color_alt ) );
			echo '}';
		}
	}

	if ( $social_font_size ) {
		echo '.wpbf-social-icon {';
		echo sprintf( 'font-size: %s;', esc_attr( $social_font_size ) . 'px' );
		echo '}';
	}

}
add_action( 'wpbf_after_customizer_css', 'wpbf_premium_after_customizer_css', 10 );
