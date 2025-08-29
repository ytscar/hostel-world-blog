<?php
/**
 * Customizer Export/Import integration.
 *
 * https://de.wordpress.org/plugins/customizer-reset/
 * https://de.wordpress.org/plugins/customizer-export-import/
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Add keys to Customizer Export/Import.
 *
 * @param array $keys The keys.
 *
 * @return array The updated keys.
 */
function wpbf_export_option_keys( $keys ) {

	$keys[] = 'wpbf';
	$keys[] = 'wpbf_settings';

	return $keys;

}
add_filter( 'customizer_export_option_keys', 'wpbf_export_option_keys' );
add_filter( 'cei_export_option_keys', 'wpbf_export_option_keys' );
