<?php
/**
 * Performance settings metabox.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Premium Add-On settings.
 */
function wpbf_premium_performance_settings() {

	// Vars.
	$performance_settings_link = ! wpbf_is_white_labeled() && is_main_site() ? '<a href="https://wp-pagebuilderframework.com/docs/performance-settings/" target="_blank" class="dashicons dashicons-editor-help help-icon"></a>' : '';

	// Sections.
	add_settings_section( 'performance-settings-section', sprintf( __( 'Performance Settings %1s', 'wpbfpremium' ), $performance_settings_link ), '', 'wpbf-performance-settings' );

	// Fields.
	add_settings_field( 'wpbf_clean_head', __( 'Performance Settings', 'wpbfpremium' ), 'wpbf_performance_callback', 'wpbf-performance-settings', 'performance-settings-section' );

}
add_action( 'admin_init', 'wpbf_premium_performance_settings' );

/**
 * Performance callback.
 */
function wpbf_performance_callback() {

	$settings = get_option( 'wpbf_settings' );

	$removals = array(
		'css_file'              => __( 'Compile inline CSS', 'wpbfpremium' ),
		'enable_svg'            => __( 'Replace Icon Font with SVG\'s', 'wpbfpremium' ),
		'local_gravatars'       => __( 'Serve Gravatars locally', 'wpbfpremium' ),
		'remove_feed'           => __( 'Remove Feed Links', 'wpbfpremium' ),
		'remove_rsd'            => __( 'Remove RSD', 'wpbfpremium' ),
		'remove_wlwmanifest'    => __( 'Remove wlwmanifest', 'wpbfpremium' ),
		'remove_generator'      => __( 'Remove Generator', 'wpbfpremium' ),
		'remove_shortlink'      => __( 'Remove Shortlink', 'wpbfpremium' ),
		'disable_emojis'        => __( 'Disable Emojis', 'wpbfpremium' ),
		'disable_embeds'        => __( 'Disable Embeds', 'wpbfpremium' ),
		'remove_jquery_migrate' => __( 'Remove jQuery Migrate', 'wpbfpremium' ),
		'disable_rss_feed'      => __( 'Disable RSS Feed', 'wpbfpremium' ),
	);

	if ( class_exists( 'WooCommerce' ) ) {

		$woo_array = array(
			'remove_woo_scripts' => __( 'Remove WooCommerce scripts & styles from non-shop pages', 'wpbfpremium' ),
		);

		$removals = array_merge( $removals, $woo_array );

	}

	$removal_values = $removals;

	if ( isset( $settings['wpbf_clean_head'] ) ) {

		foreach ( $removals as $key => $value ) {
			$removal_values[ $key ] = in_array( $key, $settings['wpbf_clean_head'], true ) ? 1 : 0;
		}
	}

	?>

	<div class="setting-fields">

		<?php
		$number = 0;

		foreach ( $removal_values as $key => $value ) {
			?>

			<div class="setting-field">
				<label for="wpbf_settings_wpbf_clean_head_<?php echo esc_attr( $number ); ?>" class="label checkbox-label">
					<?php echo esc_html( ucfirst( $removals[ $key ] ) ); ?>
					<input type="checkbox" name="wpbf_settings[wpbf_clean_head][]" id="wpbf_settings_wpbf_clean_head_<?php echo esc_attr( $number ); ?>" value="<?php echo esc_attr( $key ); ?>" class="wpbf-performance-setting" <?php checked( $value, 1 ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
			$number++;

		}
		?>

	</div>
	<a href="javascript:void(0)" class="wpbf-performance-select-all wpbf-advanced-link"><?php _e( 'Select All', 'wpbfpremium' ); ?></a>

	<?php

}
