<?php
/**
 * Breakpoint settings metabox.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Premium Add-On settings.
 */
function wpbf_premium_breakpoint_settings() {

	// Sections.
	add_settings_section( 'breakpoints-section', __( 'Breakpoints', 'wpbfpremium' ), '', 'wpbf-breakpoint-settings' );

	// Fields.
	add_settings_field( 'wpbf_breakpoint_desktop', __( 'Desktop', 'wpbfpremium' ), 'wpbf_breakpoint_desktop_callback', 'wpbf-breakpoint-settings', 'breakpoints-section' );
	add_settings_field( 'wpbf_breakpoint_medium', __( 'Tablet', 'wpbfpremium' ), 'wpbf_breakpoint_medium_callback', 'wpbf-breakpoint-settings', 'breakpoints-section' );
	add_settings_field( 'wpbf_breakpoint_mobile', __( 'Mobile', 'wpbfpremium' ), 'wpbf_breakpoint_mobile_callback', 'wpbf-breakpoint-settings', 'breakpoints-section' );

}
add_action( 'admin_init', 'wpbf_premium_breakpoint_settings' );

/**
 * Mobile breakpoint callback.
 */
function wpbf_breakpoint_mobile_callback() {

	$wpbf_settings     = get_option( 'wpbf_settings' );
	$breakpoint_mobile = ! empty( $wpbf_settings['wpbf_breakpoint_mobile'] ) ? $wpbf_settings['wpbf_breakpoint_mobile'] : false;

	echo '<label><input type="text" name="wpbf_settings[wpbf_breakpoint_mobile]" value="' . esc_attr( $breakpoint_mobile ) . '" placeholder="480px" size="10" /> <span class="description">' . __( 'Default: until 480px for mobiles.', 'wpbfpremium' ) . '</span></label>';

}

/**
 * Medium breakpoint callback.
 */
function wpbf_breakpoint_medium_callback() {

	$wpbf_settings     = get_option( 'wpbf_settings' );
	$breakpoint_medium = ! empty( $wpbf_settings['wpbf_breakpoint_medium'] ) ? $wpbf_settings['wpbf_breakpoint_medium'] : false;

	echo '<label><input type="text" name="wpbf_settings[wpbf_breakpoint_medium]" value="' . esc_attr( $breakpoint_medium ) . '" placeholder="768px" size="10" /> <span class="description">' . __( 'Default: above 768px for tablets.', 'wpbfpremium' ) . '</span></label>';

}

/**
 * Desktop breakpoint callback.
 */
function wpbf_breakpoint_desktop_callback() {

	$wpbf_settings      = get_option( 'wpbf_settings' );
	$breakpoint_desktop = ! empty( $wpbf_settings['wpbf_breakpoint_desktop'] ) ? $wpbf_settings['wpbf_breakpoint_desktop'] : false;

	echo '<label><input type="text" name="wpbf_settings[wpbf_breakpoint_desktop]" value="' . esc_attr( $breakpoint_desktop ) . '" placeholder="1024px" size="10" /> <span class="description">' . __( 'Default: above 1024px for desktops.', 'wpbfpremium' ) . '</span></label>';

}
