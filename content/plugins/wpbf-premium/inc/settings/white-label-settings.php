<?php
/**
 * White label settings metabox.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Premium Add-On settings.
 */
function wpbf_premium_white_label_settings() {

	// Vars.
	$white_label_settings_link = ! wpbf_is_white_labeled() ? '<a href="https://wp-pagebuilderframework.com/docs/white-label/" target="_blank" class="dashicons dashicons-editor-help help-icon"></a>' : '';
	$misc_settings_link        = ! wpbf_is_white_labeled() ? '<a href="https://wp-pagebuilderframework.com/docs/white-label/" target="_blank" class="dashicons dashicons-editor-help help-icon"></a>' : '';

	// Sections.
	add_settings_section( 'white-label-company-section', sprintf( __( 'Company Details %s', 'wpbfpremium' ), $white_label_settings_link ), '', 'wpbf-white-label-company-settings' );
	add_settings_section( 'white-label-plugin-section', __( 'Premium Add-On', 'wpbfpremium' ), '', 'wpbf-white-label-plugin-settings' );
	add_settings_section( 'white-label-theme-section', __( 'Page Builder Framework', 'wpbfpremium' ), '', 'wpbf-white-label-theme-settings' );
	add_settings_section( 'white-label-misc-section', sprintf( __( 'Misc %s', 'wpbfpremium' ), $misc_settings_link ), '', 'wpbf-white-label-misc-settings' );

	// Fields.
	add_settings_field( 'wpbf_theme_company_name', __( 'Company Name', 'wpbfpremium' ), 'wpbf_theme_company_name_callback', 'wpbf-white-label-company-settings', 'white-label-company-section' );
	add_settings_field( 'wpbf_theme_company_url', __( 'Company URL', 'wpbfpremium' ), 'wpbf_theme_company_url_callback', 'wpbf-white-label-company-settings', 'white-label-company-section' );
	add_settings_field( 'wpbf_company_logo', __( 'Company Logo', 'wpbfpremium' ), 'wpbf_company_logo_callback', 'wpbf-white-label-company-settings', 'white-label-company-section' );

	add_settings_field( 'wpbf_theme_name', __( 'Name', 'wpbfpremium' ), 'wpbf_theme_name_callback', 'wpbf-white-label-theme-settings', 'white-label-theme-section' );
	add_settings_field( 'wpbf_theme_description', __( 'Description', 'wpbfpremium' ), 'wpbf_theme_description_callback', 'wpbf-white-label-theme-settings', 'white-label-theme-section' );
	add_settings_field( 'wpbf_theme_tags', __( 'Tags', 'wpbfpremium' ), 'wpbf_theme_tags_callback', 'wpbf-white-label-theme-settings', 'white-label-theme-section' );
	add_settings_field( 'wpbf_theme_screenshot', __( 'Screenshot', 'wpbfpremium' ), 'wpbf_theme_screenshot_callback', 'wpbf-white-label-theme-settings', 'white-label-theme-section' );

	add_settings_field( 'wpbf_plugin_name', __( 'Name', 'wpbfpremium' ), 'wpbf_plugin_name_callback', 'wpbf-white-label-plugin-settings', 'white-label-plugin-section' );
	add_settings_field( 'wpbf_plugin_description', __( 'Description', 'wpbfpremium' ), 'wpbf_plugin_description_callback', 'wpbf-white-label-plugin-settings', 'white-label-plugin-section' );

	add_settings_field( 'wpbf_hide_whitelabel_section', __( 'Hide White Label Tab', 'wpbfpremium' ), 'wpbf_hide_whitelabel_section_callback', 'wpbf-white-label-misc-settings', 'white-label-misc-section' );

}
add_action( 'admin_init', 'wpbf_premium_white_label_settings' );

/**
 * Theme name callback.
 */
function wpbf_theme_name_callback() {

	$wpbf_settings = get_option( 'wpbf_settings' );
	$theme_name    = isset( $wpbf_settings['wpbf_theme_name'] ) ? $wpbf_settings['wpbf_theme_name'] : false;

	echo '<input class="all-options" type="text" name="wpbf_settings[wpbf_theme_name]" value="' . esc_html( $theme_name ) . '" />';

}

/**
 * Theme description callback.
 */
function wpbf_theme_description_callback() {

	$wpbf_settings     = get_option( 'wpbf_settings' );
	$theme_description = isset( $wpbf_settings['wpbf_theme_description'] ) ? $wpbf_settings['wpbf_theme_description'] : false;

	echo '<input class="regular-text" type="text" name="wpbf_settings[wpbf_theme_description]" value="' . esc_html( $theme_description ) . '" />';

}

/**
 * Theme tags callback.
 */
function wpbf_theme_tags_callback() {

	$wpbf_settings = get_option( 'wpbf_settings' );
	$theme_tags    = isset( $wpbf_settings['wpbf_theme_tags'] ) ? $wpbf_settings['wpbf_theme_tags'] : false;

	echo '<input class="regular-text" type="text" name="wpbf_settings[wpbf_theme_tags]" value="' . esc_html( $theme_tags ) . '" />';

}

/**
 * Theme company name callback.
 */
function wpbf_theme_company_name_callback() {

	$wpbf_settings      = get_option( 'wpbf_settings' );
	$theme_company_name = isset( $wpbf_settings['wpbf_theme_company_name'] ) ? $wpbf_settings['wpbf_theme_company_name'] : false;

	echo '<input class="all-options" type="text" name="wpbf_settings[wpbf_theme_company_name]" value="' . esc_html( $theme_company_name ) . '" />';

}

/**
 * Theme company url callback.
 */
function wpbf_theme_company_url_callback() {

	$wpbf_settings     = get_option( 'wpbf_settings' );
	$theme_company_url = isset( $wpbf_settings['wpbf_theme_company_url'] ) ? $wpbf_settings['wpbf_theme_company_url'] : false;

	echo '<input class="all-options" type="text" name="wpbf_settings[wpbf_theme_company_url]" value="' . esc_html( $theme_company_url ) . '" />';

}

/**
 * Theme company logo callback.
 */
function wpbf_company_logo_callback() {

	$wpbf_settings = get_option( 'wpbf_settings' );
	$company_logo  = isset( $wpbf_settings['wpbf_company_logo'] ) ? $wpbf_settings['wpbf_company_logo'] : false;

	if ( function_exists( 'wp_enqueue_media' ) ) {

		wp_enqueue_media();

	} else {

		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );

	}

	?>

	<input id="wpbf-company-logo" class="wpbf-company-logo-url regular-text" type="text" name="wpbf_settings[wpbf_company_logo]" value="<?php echo esc_url( $company_logo ); ?>">
	<a href="javascript:void(0)" class="wpbf-company-logo-upload button-secondary"><?php _e( 'Add or Upload File', 'wpbfpremium' ); ?></a>
	<a href="javascript:void(0)" class="wpbf-company-logo-remove button-secondary">x</a>

	<?php

}

/**
 * Theme screenshot callback.
 */
function wpbf_theme_screenshot_callback() {

	$wpbf_settings    = get_option( 'wpbf_settings' );
	$theme_screenshot = isset( $wpbf_settings['wpbf_theme_screenshot'] ) ? $wpbf_settings['wpbf_theme_screenshot'] : false;

	if ( function_exists( 'wp_enqueue_media' ) ) {

		wp_enqueue_media();

	} else {

		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );

	}

	?>

	<input id="wpbf-screenshot" class="wpbf-screenshot-url regular-text" type="text" name="wpbf_settings[wpbf_theme_screenshot]" value="<?php echo esc_url( $theme_screenshot ); ?>">
	<a href="javascript:void(0)" class="wpbf-screenshot-upload button-secondary"><?php _e( 'Add or Upload File', 'wpbfpremium' ); ?></a>
	<a href="javascript:void(0)" class="wpbf-screenshot-remove button-secondary">x</a>
	<br>
	<label for="wpbf-screenshot"><?php _e( 'Recommended image size: 1200px x 900px', 'wpbfpremium' ); ?></label>

	<?php

}

/**
 * Plugin name callback.
 */
function wpbf_plugin_name_callback() {

	$wpbf_settings = get_option( 'wpbf_settings' );
	$plugin_name   = isset( $wpbf_settings['wpbf_plugin_name'] ) ? $wpbf_settings['wpbf_plugin_name'] : false;

	echo '<input class="all-options" type="text" name="wpbf_settings[wpbf_plugin_name]" value="' . esc_html( $plugin_name ) . '" />';

}

/**
 * Plugin description callback.
 */
function wpbf_plugin_description_callback() {

	$wpbf_settings      = get_option( 'wpbf_settings' );
	$plugin_description = isset( $wpbf_settings['wpbf_plugin_description'] ) ? $wpbf_settings['wpbf_plugin_description'] : false;

	echo '<input class="regular-text" type="text" name="wpbf_settings[wpbf_plugin_description]" value="' . esc_html( $plugin_description ) . '" />';

}

/**
 * Hide White Label tag callback.
 */
function wpbf_hide_whitelabel_section_callback() {

	$wpbf_settings = get_option( 'wpbf_settings' );
	$hide_section  = isset( $wpbf_settings['wpbf_hide_white_label_section'] ) ? $wpbf_settings['wpbf_hide_white_label_section'] : false;

	?>

	<div class="setting-field">
		<label for="wpbf_hide_white_label_section" class="label checkbox-label">
			<?php _e( 'Hide', 'wpbfpremium' ); ?>
			<input type="checkbox" name="wpbf_settings[wpbf_hide_white_label_section]" id="wpbf_hide_white_label_section" value="1" <?php checked( 1, $hide_section ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}
