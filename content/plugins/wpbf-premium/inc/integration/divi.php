<?php
/**
 * Divi Builder integration.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Auto add custom sections to Divi cpt support.
 *
 * Caused issues with Beaver Builder, let's revert this just in case.
 */
function wpbf_divi_cpt_support() {
	// Divi uses 2 option meta.
	$divi_integrations = array(
		'et_divi_builder_plugin' => 'et_pb_post_type_integration',
		'et_pb_builder_options'  => 'post_type_integration_main_et_pb_post_type_integration',
	);

	foreach ( $divi_integrations as $option_name => $integration_key ) {
		$options    = get_option( $option_name, array() );
		$post_types = isset( $options[ $integration_key ] ) ? $options[ $integration_key ] : array();

		if ( ! isset( $post_types['wpbf_hooks'] ) || 'on' !== $post_types['wpbf_hooks'] ) {
			$options[ $integration_key ]['wpbf_hooks'] = 'on';

			update_option( $option_name, $options, true );
		}
	}
}
// add_action( 'admin_init', 'wpbf_divi_cpt_support' );

/**
 * Global color palette.
 *
 * @param array $palette The palette.
 *
 * @return array The updated palette.
 */
function wpbf_divi_color_palette( $palette ) {

	$color_palette = wpbf_color_palette();

	if ( ! empty( $color_palette ) ) {
		$palette = $color_palette;
	}

	return $palette;

}
add_filter( 'et_pb_get_default_color_palette', 'wpbf_divi_color_palette' );
