<?php
/**
 * GenerateBlocks integration.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Generateblocks integration.
 */
function wpbf_generateblocks_integration( $content ) {

	$args = array(
		'numberposts' => -1,  
		'post_type'   => 'wpbf_hooks',
	);

	$custom_sections = get_posts( $args );

	// Bail out early if no custom sections exist.
	if ( empty( $custom_sections ) ) {
		return $content;
	}

	foreach ( $custom_sections as $custom_section ) {

		// Only go forward if we have blocks.
	    if ( has_blocks( $custom_section ) ) {

	    	// Stop if custom section wasn't published or is password protected.
	        if ( 'publish' !== $custom_section->post_status || ! empty( $custom_section->post_password ) ) {
	            return $content;
	        }

	        // Extend $content with the custom section content.
	        $content .= $custom_section->post_content;

	    }

	}

    return $content;

}
add_filter( 'generateblocks_do_content', 'wpbf_generateblocks_integration' );
