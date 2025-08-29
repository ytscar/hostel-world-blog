<?php
/**
 * Options.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Load metaboxes.
 */
function wpbf_premium_metabox_setup() {

	add_action( 'add_meta_boxes', 'wpbf_premium_transparent_header_metabox', 20 );

}
add_action( 'load-post.php', 'wpbf_premium_metabox_setup' );
add_action( 'load-post-new.php', 'wpbf_premium_metabox_setup' );

/**
 * Transparent header metabox.
 */
function wpbf_premium_transparent_header_metabox() {

	// Get public post types.
	$post_types = get_post_types( array( 'public' => true ) );

	// Remove certain post types from array.
	unset( $post_types['wpbf_hooks'], $post_types['elementor_library'], $post_types['fl-builder-template'] );

	// Add transparent header metabox.
	add_meta_box( 'wpbf_header', __( 'Transparent Header', 'wpbfpremium' ), 'wpbf_transparent_header_metabox_callback', $post_types, 'side', 'default' );

}

/**
 * Transparent header metabox callback.
 *
 * @param object $post The post object.
 */
function wpbf_transparent_header_metabox_callback( $post ) {

	wp_nonce_field( "wpbf_premium_post_{$post->ID}_options_nonce", 'wpbf_premium_options_nonce' );

	$stored_meta = get_post_meta( $post->ID );

	// Set default value.
	if ( ! isset( $stored_meta['wpbf_premium_options'][0] ) ) {
		$stored_meta['wpbf_premium_options'][0] = false;
	}

	$stored_meta = $stored_meta['wpbf_premium_options'][0];

	if ( strpos( $stored_meta, 'transparent-header' ) !== false ) {
		$transparent_header = 'transparent-header';
	} else {
		$transparent_header = false;
	}
	?>

	<div>
		<input id="transparent-header" type="checkbox" name="wpbf_premium_options[]" value="transparent-header" <?php checked( $transparent_header, 'transparent-header' ); ?> />
		<label for="transparent-header"><?php _e( 'Transparent Header', 'wpbfpremium' ); ?></label>
	</div>

	<?php

}

/**
 * Save postmeta data.
 *
 * @param int $post_id The post ID.
 */
function wpbf_premium_save_postmeta( $post_id ) {

	$is_autosave    = wp_is_post_autosave( $post_id );
	$is_revision    = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST['wpbf_premium_options_nonce'] ) && wp_verify_nonce( $_POST['wpbf_premium_options_nonce'], "wpbf_premium_post_{$post_id}_options_nonce" ) ) ? true : false;

	// Stop here if is autosave, revision or nonce is invalid.
	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	// Stop here if current user can't edit posts.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	$options = array();

	if ( isset( $_POST['wpbf_premium_options'] ) ) {

		// Transparent header.
		if ( in_array( 'transparent-header', $_POST['wpbf_premium_options'] ) !== false ) {
			$options[] = 'transparent-header';
		}
	}

	update_post_meta( $post_id, 'wpbf_premium_options', $options );

}
add_action( 'save_post', 'wpbf_premium_save_postmeta', 10, 2 );

/**
 * Add premium options to our quick edit area in posts screen.
 *
 * @see wp-content/themes/page-builder-framework/inc/quick-edit.php
 *
 * @param string $column_name Name of the column to edit.
 * @param string $post_type The post type slug, or current screen name if this is a taxonomy list table.
 * @param string $taxonomy The taxonomy name, if any.
 */
function wpbf_premium_posts_quick_edit_options( $column_name, $post_type, $taxonomy ) {

	?>

	<h4>
		<?php _e( 'Header', 'wpbfpremium' ); ?>
	</h4>

	<div class="wpbf-quick-edit-fields-area" data-wpbf-fields-group-name="premium-options" data-wpbf-fields-group-type="checkbox">

		<div class="wpbf-quick-edit-field wpbf-quick-edit-checkbox-field">
			<div class="wpbf-quick-edit-control">
				<input type="checkbox" name="wpbf_premium_options[]" value="transparent-header" class="wpbf-quick-edit-use-preset" data-wpbf-preset-name="premium-options" />
			</div>
			<label for="" class="wpbf-quick-edit-label">
				<?php _e( 'Transparent Header', 'wpbfpremium' ); ?>
			</label>
		</div>

	</div>

	<?php

}
add_action( 'wpbf_post_list_quick_edit_column_4', 'wpbf_premium_posts_quick_edit_options', 20, 3 );

/**
 * Add nonce field for premium options to our quick edit area in posts screen.
 *
 * @see wp-content/themes/Page-Builder-Framework/inc/quick-edit.php
 */
function wpbf_premium_posts_quick_edit_nonce_field() {

	?>

	<input type="hidden" name="wpbf_premium_options_nonce" class="wpbf-quick-edit-nonce-field">

	<?php

}
add_action( 'wpbf_post_list_quick_edit_nonce_field', 'wpbf_premium_posts_quick_edit_nonce_field' );

/**
 * Add posts quick edit preset values via data attributes.
 *
 * @see wp-content/themes/Page-Builder-Framework/inc/quick-edit.php
 *
 * @param string $custom_attr Existing custom data-* attributes.
 * @param int    $post_id The current post ID.
 *
 * @return string The modified custom data-* attributes.
 */
function wpbf_premium_posts_quick_edit_preset_data_attr( $custom_attr, $post_id ) {

	$premium_options = get_post_meta( $post_id, 'wpbf_premium_options', true );
	$premium_options = $premium_options ? $premium_options : array();
	$field_values    = implode( ',', $premium_options );
	$options_nonce   = wp_create_nonce( "wpbf_premium_post_{$post_id}_options_nonce" );

	$custom_attr .= ' data-wpbf-premium-options="' . esc_attr( $field_values ) . '"';
	$custom_attr .= ' data-wpbf-premium-options-nonce="' . esc_attr( $options_nonce ) . '"';

	return $custom_attr;

}
add_filter( 'wpbf_post_list_quick_edit_preset_data_attr', 'wpbf_premium_posts_quick_edit_preset_data_attr', 20, 2 );
