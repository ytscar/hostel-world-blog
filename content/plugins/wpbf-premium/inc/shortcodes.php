<?php
/**
 * Helpers.
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Current year shortcode.
 *
 * @return string The current year.
 */
function wpbf_current_year( $atts ) {
	return '<span class="wpbf-current-year">' . date( 'Y' ) . '</span>';
}
add_shortcode( 'wpbf_year', 'wpbf_current_year' );

/**
 * Footer branding.
 *
 * @param array $theme_author The theme author data.
 *
 * @return array The theme author data.
 */
function wpbf_footer_branding( $theme_author ) {

	$wpbf_settings            = is_multisite() ? get_blog_option( 1, 'wpbf_settings' ) : get_option( 'wpbf_settings' );
	$footer_theme_author_name = get_theme_mod( 'footer_theme_author_name' );
	$footer_theme_author_url  = get_theme_mod( 'footer_theme_author_url' );

	if ( ! empty( $wpbf_settings['wpbf_theme_company_name'] ) ) {
		$theme_author['name'] = $wpbf_settings['wpbf_theme_company_name'];
	}

	if ( ! empty( $wpbf_settings['wpbf_theme_company_url'] ) ) {
		$theme_author['url'] = $wpbf_settings['wpbf_theme_company_url'];
	}

	if ( $footer_theme_author_name ) {
		$theme_author['name'] = $footer_theme_author_name;
	}

	if ( $footer_theme_author_url ) {
		$theme_author['url'] = $footer_theme_author_url;
	}

	return $theme_author;

}
add_filter( 'wpbf_theme_author', 'wpbf_footer_branding' );

/**
 * Social icon shortcode.
 *
 * @return string The social media icons.
 */
function wpbf_social() {

	$output             = '';
	$all_social_icons   = wpbf_social_choices();
	$saved_social_icons = get_theme_mod( 'social_sortable', array() );
	$icon_shape         = ' ' . get_theme_mod( 'social_shapes' );
	$icon_style         = ' ' . get_theme_mod( 'social_styles' );
	$icon_size          = ' ' . get_theme_mod( 'social_sizes' );

	// Stop here if we don't have any social icons.
	if ( empty( $saved_social_icons ) ) {
		return $output;
	}

	// Check saved social icons against all social icons to get their names.
	$saved_social_icons = array_flip( $saved_social_icons );
	$social_icons       = array_intersect_key( $all_social_icons, $saved_social_icons );

	// Opening wrapper.
	$output .= '<div class="wpbf-social-icons' . esc_attr( $icon_shape . $icon_style . $icon_size ) . '">';

	foreach ( $social_icons as $social_icon => $value ) {

		$link_target = esc_url( get_theme_mod( $social_icon . '_link' ) );

		if ( 'email' === $social_icon ) {
			$link_target = 'mailto:' . sanitize_email( get_theme_mod( $social_icon . '_link' ) );
		}

		$output .= '<a class="wpbf-social-icon wpbf-social-' . esc_attr( $social_icon ) . '" target="_blank" href="' . $link_target . '" title="' . esc_attr( $value ) . '">';

		if ( wpbf_svg_enabled() ) {
			$output .= wpbf_svg( $social_icon );
		} else {
			$output .= '<i class="wpbff wpbff-' . esc_attr( $social_icon ) . '" aria-hidden="true"></i>';
		}

		$output .=	'</a>';

	}

	// Closing wrapper.
	$output .= '</div>';

	return $output;

}
add_shortcode( 'social', 'wpbf_social' );

/**
 * Responsive Youtube & Vimeo video shortcode.
 *
 * @param array $atts The shortcode attributes.
 *
 * @return string The HTML markup.
 */
function wpbf_responsive_video( $atts ) {

	extract(
		shortcode_atts(
			array(
				'src'    => 'https://www.youtube.com/embed/GH28y-XjHdo',
				'opt_in' => false,
			),
			$atts
		)
	);

	if ( $opt_in ) {

		$host      = false;
		$thumbnail = false;

		if ( strpos( $src, 'youtube' ) !== false ) {

			$host = 'YouTube';
			preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $src, $match );
			$id        = $match[1];
			$thumbnail = 'https://img.youtube.com/vi/' . $id . '/maxresdefault.jpg';

		} elseif ( strpos( $src, 'vimeo' ) !== false ) {

			$host = 'Vimeo';

		}

		if ( $host ) {
			// translators: %s Host url.
			$message = sprintf( __( 'Click the button below to load the video from %s.', 'wpbfpremium' ), $host );
		} else {
			// translators: %1$s Docs url.
			$message = sprintf( __( 'Something went wrong. Please make sure you enter the embed-url as the src tag for the shortcode. <a href="%1$s" target="_blank">Help</a>', 'wpbfpremium' ), 'https://wp-pagebuilderframework.com/docs/shortcodes/#video' );
		}

		$video  = '<div class="wpbf-video-opt-in wpbf-text-center wpbf-margin-bottom">';
		$video .= '<p>' . $message . '</p>';
		$video .= $thumbnail ? '<img class="wpbf-margin-bottom wpbf-video-opt-in-image" src="' . $thumbnail . '">' : false;
		$video .= $host ? '<a href="#" class="wpbf-button wpbf-button-primary wpbf-video-opt-in-button">' . __( 'Load Video', 'wpbfpremium' ) . '</a>' : false;
		$video .= '</div>';
		$video .= '<div class="wpbf-responsive-embed opt-in" data-wpbf-video="' . esc_url( $src ) . '">';
		$video .= '<iframe width="1600" height="900" src="" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		$video .= '</div>';

	} else {

		$video  = '<div class="wpbf-responsive-embed">';
		$video .= '<iframe width="1600" height="900" src="' . esc_url( $src ) . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		$video .= '</div>';

	}

	return $video;

}
add_shortcode( 'wpbf-responsive-video', 'wpbf_responsive_video' );

/**
 * Breadcrumbs shortcode.
 */
function wpbf_breadcrumbs_shortcode( $args = array() ) {

	// Use Yoast Breadcrumbs if enabled.
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		$yoast_titles = get_option( 'wpseo_titles', array() );

		if ( isset( $yoast_titles['breadcrumbs-enable'] ) && $yoast_titles['breadcrumbs-enable'] == 1 ) {
			return yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
		}
	}

	// Use Rank Math SEO Breadcrumbs if enabled.
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		return rank_math_the_breadcrumbs();
	}

	// Use SEOPress Breadcrumbs if enabled.
	if ( function_exists( 'seopress_display_breadcrumbs' ) ) {
		seopress_display_breadcrumbs();
	}

	$args = array(
		'echo' => false,
	);

	$breadcrumb = apply_filters( 'breadcrumb_trail_object', null, $args );

	if ( ! is_object( $breadcrumb ) )
		$breadcrumb = new WPBF_Breadcrumbs( $args );

	return $breadcrumb->trail();

}
add_shortcode( 'wpbf-breadcrumbs', 'wpbf_breadcrumbs_shortcode' );

/**
 * WooCommerce menu item shortcode.
 */
function wpbf_woo_menu_item_shortcode() {
	echo wpbf_woo_menu_item( $markup = 'div' );
}
add_shortcode( 'wpbf-woo-menu-item', 'wpbf_woo_menu_item_shortcode' );

/**
 * EDD menu item shortcode.
 */
function wpbf_edd_menu_item_shortcode() {
	echo wpbf_edd_menu_item( $markup = 'div' );
}
add_shortcode( 'wpbf-edd-menu-item', 'wpbf_edd_menu_item_shortcode' );
