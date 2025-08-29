<?php
/**
 * License metabox.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$license_key    = get_option( 'wpbf_premium_license_key' );
$license_status = get_option( 'wpbf_premium_license_status' );
?>

<div class="heatbox">
	<h2>
		<?php _e( 'Status', 'wpbfpremium' ); ?>:
		<?php if ( wpbf_license_key_mismatch() ) { ?>
			<span style="color: tomato; font-weight: 700; font-style: italic;"><?php _e( 'Mismatch!', 'wpbfpremium' ); ?></span>
		<?php } elseif ( ! empty( $license_status ) && 'valid' === $license_status ) { ?>
			<span style="color:#6dbb7a; font-weight: 700; font-style: italic;"><?php _e( 'Active', 'wpbfpremium' ); ?></span>
		<?php } else { ?>
			<span style="color: tomato; font-weight: 700; font-style: italic;"><?php _e( 'Inactive', 'wpbfpremium' ); ?></span>
		<?php } ?>
	</h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<?php _e( 'License Key', 'wpbfpremium' ); ?>
				</th>
				<td>
					<input id="wpbf_premium_license_key" name="wpbf_premium_license_key" type="password" class="regular-text" value="<?php echo esc_attr( $license_key ); ?>" />
					<p class="description" for="wpbf_premium_license_key">
						<?php
						// translators: %s: Plugin name.
						printf( __( 'Enter your %s license key.', 'wpbfpremium' ), apply_filters( 'wpbf_premium_plugin_name', WPBF_PREMIUM_PLUGIN_NAME ) );
						?>
					</p>
				</td>
			</tr>
			<?php if ( ! empty( $license_key ) ) { ?>
			<tr>
				<th>
					<?php _e( 'Activate License', 'wpbfpremium' ); ?>
					<?php if ( ! wpbf_is_white_labeled() ) { ?>
					<a href="https://wp-pagebuilderframework.com/docs-category/installation/" target="_blank" class="dashicons dashicons-editor-help"></a>
					<?php } ?>
				</th>
				<td>
					<?php if ( ! empty( $license_status ) && 'valid' === $license_status ) { ?>
						<?php wp_nonce_field( 'wpbf_premium_nonce', 'wpbf_premium_nonce' ); ?>
						<input type="submit" class="button-primary" name="wpbf_premium_license_activate" value="<?php _e( 'Revalidate', 'wpbfpremium' ); ?>"/>
						<input type="submit" class="button-secondary" name="wpbf_premium_license_deactivate" value="<?php _e( 'Deactivate License', 'wpbfpremium' ); ?>"/>
					<?php } else { ?>
						<?php wp_nonce_field( 'wpbf_premium_nonce', 'wpbf_premium_nonce' ); ?>
						<input type="submit" class="button-secondary" name="wpbf_premium_license_activate" value="<?php _e( 'Activate License', 'wpbfpremium' ); ?>"/>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
