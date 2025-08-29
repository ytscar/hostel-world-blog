<?php
/**
 * Global settings metabox.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Premium Add-On settings.
 */
function wpbf_premium() {

	// Vars.
	$color_palette_settings_link = ! wpbf_is_white_labeled() && is_main_site() ? '<a href="https://wp-pagebuilderframework.com/docs/global-color-palette/" target="_blank" class="dashicons dashicons-editor-help help-icon"></a>' : '';
	$template_settings_link      = ! wpbf_is_white_labeled() && is_main_site() ? '<a href="https://wp-pagebuilderframework.com/docs/global-template-settings/" target="_blank" class="dashicons dashicons-editor-help help-icon"></a>' : '';

	// Register Setting.
	register_setting( 'wpbf-premium-group', 'wpbf_settings' );

	// Sections.
	add_settings_section( 'global-color-palette-section', sprintf( __( 'Global Color Palette %1s', 'wpbfpremium' ), $color_palette_settings_link ), '', 'wpbf-global-color-palette-settings' );
	add_settings_section( 'global-template-settings-section', sprintf( __( 'Global Template Settings %1s', 'wpbfpremium' ), $template_settings_link ), '', 'wpbf-global-template-settings' );

	// Fields.
	add_settings_field( 'wpbf_color_palette', __( 'Colors', 'wpbfpremium' ), 'wpbf_color_palette_callback', 'wpbf-global-color-palette-settings', 'global-color-palette-section' );

	add_settings_field( 'wpbf_fullwidth_global', __( 'Full Width', 'wpbfpremium' ), 'wpbf_fullwidth_global_callback', 'wpbf-global-template-settings', 'global-template-settings-section' );
	add_settings_field( 'wpbf_removetitle_global', __( 'Remove Title', 'wpbfpremium' ), 'wpbf_removetitle_global_callback', 'wpbf-global-template-settings', 'global-template-settings-section' );
	add_settings_field( 'wpbf_remove_featured_image_global', __( 'Remove Featured Image', 'wpbfpremium' ), 'wpbf_remove_featured_image_callback', 'wpbf-global-template-settings', 'global-template-settings-section' );
	add_settings_field( 'wpbf_transparent_header_global', __( 'Transparent Header', 'wpbfpremium' ), 'wpbf_transparent_header_global_callback', 'wpbf-global-template-settings', 'global-template-settings-section' );

}
add_action( 'admin_init', 'wpbf_premium' );

/*
 * Global colors callback.
 */
function wpbf_color_palette_callback() {

	// Get saved colors from the database.
	$colors = wpbf_color_palette( $print_defaults = true, $print_empty = true );
	?>

	<div class="setting-fields">

		<?php
		foreach ( $colors as $color => $value ) {
			echo '<div class="setting-field">';
			echo '<input type="text" name="wpbf_settings[color_palette][]" value="' . esc_attr( $value ) . '" class="color-picker" data-alpha-enabled="true" />';
			echo '</div>';
		}
		?>

	</div>

	<?php

}

/**
 * Full width callback.
 */
function wpbf_fullwidth_global_callback() {

	// Vars.
	$post_types = wpbf_template_settings_post_type_array();
	$settings   = get_option( 'wpbf_settings' );
	?>

	<div class="setting-fields">

		<?php
		$number = 0;

		// Loop through post types.
		foreach ( $post_types as $post_type ) {

			$full_width_global = false;

			if ( isset( $settings['wpbf_fullwidth_global'] ) && in_array( $post_type, $settings['wpbf_fullwidth_global'], true ) ) {
				$full_width_global = $post_type;
			}

			$post_type_title = str_replace( array( '_', '-' ), ' ', $post_type );

			?>

			<div class="setting-field">
				<label for="wpbf_settings_wpbf_fullwidth_global_<?php echo esc_attr( $number ); ?>" class="label checkbox-label">
					<?php echo esc_html( ucwords( $post_type_title ) ); ?>
					<input type="checkbox" name="wpbf_settings[wpbf_fullwidth_global][]" id="wpbf_settings_wpbf_fullwidth_global_<?php echo esc_attr( $number ); ?>" value="<?php echo esc_attr( $post_type ); ?>" <?php checked( $full_width_global, $post_type ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
			$number++;

		}
		?>

	</div>

	<?php

}

/**
 * Remove title callback.
 */
function wpbf_removetitle_global_callback() {

	// Vars.
	$post_types = wpbf_template_settings_post_type_array();
	$settings   = get_option( 'wpbf_settings' );
	?>

	<div class="setting-fields">

		<?php
		$number = 0;

		// Loop through post types.
		foreach ( $post_types as $post_type ) {

			$remove_title_global = false;

			if ( isset( $settings['wpbf_removetitle_global'] ) && in_array( $post_type, $settings['wpbf_removetitle_global'], true ) ) {
				$remove_title_global = $post_type;
			}

			$post_type_title = str_replace( array( '_', '-' ), ' ', $post_type );

			?>

			<div class="setting-field">
				<label for="wpbf_settings_wpbf_removetitle_global_<?php echo esc_attr( $number ); ?>" class="label checkbox-label">
					<?php echo esc_html( ucwords( $post_type_title ) ); ?>
					<input type="checkbox" name="wpbf_settings[wpbf_removetitle_global][]" id="wpbf_settings_wpbf_removetitle_global_<?php echo esc_attr( $number ); ?>" value="<?php echo esc_attr( $post_type ); ?>" <?php checked( $remove_title_global, $post_type ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
			$number++;

		}
		?>

	</div>

	<?php

}

/**
 * Remove featured image callback.
 */
function wpbf_remove_featured_image_callback() {

	// Vars.
	$post_types = wpbf_template_settings_post_type_array();
	$settings   = get_option( 'wpbf_settings' );
	?>

	<div class="setting-fields">

		<?php
		$number = 0;

		// Loop through post types.
		foreach ( $post_types as $post_type ) {

			$remove_featured_image_global = false;

			if ( isset( $settings['wpbf_remove_featured_image_global'] ) && in_array( $post_type, $settings['wpbf_remove_featured_image_global'], true ) ) {
				$remove_featured_image_global = $post_type;
			}

			$post_type_title = str_replace( array( '_', '-' ), ' ', $post_type );

			?>

			<div class="setting-field">
				<label for="wpbf_settings_wpbf_remove_featured_image_global_<?php echo esc_attr( $number ); ?>" class="label checkbox-label">
					<?php echo esc_html( ucwords( $post_type_title ) ); ?>
					<input type="checkbox" name="wpbf_settings[wpbf_remove_featured_image_global][]" id="wpbf_settings_wpbf_remove_featured_image_global_<?php echo esc_attr( $number ); ?>" value="<?php echo esc_attr( $post_type ); ?>" <?php checked( $remove_featured_image_global, $post_type ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
			$number++;

		}
		?>

	</div>

	<?php

}

/**
 * Transparent header callback.
 */
function wpbf_transparent_header_global_callback() {

	$post_types = wpbf_template_settings_post_type_array();
	$settings   = get_option( 'wpbf_settings' );
	?>

	<div class="setting-fields">

		<?php
		$number = 0;

		// Loop through post types.
		foreach ( $post_types as $post_type ) {

			$transparent_header_global = false;

			if ( isset( $settings['wpbf_transparent_header_global'] ) && in_array( $post_type, $settings['wpbf_transparent_header_global'], true ) ) {
				$transparent_header_global = $post_type;
			}

			$post_type_title = str_replace( array( '_', '-' ), ' ', $post_type );

			?>

			<div class="setting-field">
				<label for="wpbf_settings_wpbf_transparent_header_global_<?php echo esc_attr( $number ); ?>" class="label checkbox-label">
					<?php echo esc_html( ucwords( $post_type_title ) ); ?>
					<input type="checkbox" name="wpbf_settings[wpbf_transparent_header_global][]" id="wpbf_settings_wpbf_transparent_header_global_<?php echo esc_attr( $number ); ?>" value="<?php echo esc_attr( $post_type ); ?>" <?php checked( $transparent_header_global, $post_type ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
			$number++;

		}
		?>

	</div>

	<div class="setting-fields wpbf-transparent-header-advanced-wrapper">

		<h3><?php _e( 'Archives', 'wpbfpremium' ); ?></h3>

		<?php
		$number = 100;

		// Advanced settings array.
		$advanced_settings = array(
			'404_page'      => __( '404 Page', 'wpbfpremium' ),
			'front_page'    => __( 'Blog Page', 'wpbfpremium' ),
			'search'        => __( 'Search Results', 'wpbfpremium' ),
			'archives'      => __( 'All Archives', 'wpbfpremium' ),
			'post_archives' => __( 'Post Archives', 'wpbfpremium' ),
		);

		// Merge advanced settings array with wpbf_template_settings_post_type_array array.
		$advanced_settings = array_merge( $advanced_settings, wpbf_template_settings_post_type_array( $third_party = true, $as_archives = true ) );

		// Loop through advanced settings array.
		foreach ( $advanced_settings as $advanced_setting => $value ) {

			$transparent_header_global = false;

			if ( isset( $settings['wpbf_transparent_header_global'] ) && in_array( $advanced_setting, $settings['wpbf_transparent_header_global'], true ) ) {
				$transparent_header_global = $advanced_setting;
			}

			$post_type_title = str_replace( array( '_', '-' ), ' ', $value );

			?>

			<div class="setting-field">
				<label for="wpbf_settings_wpbf_transparent_header_global_<?php echo esc_attr( $number ); ?>" class="label checkbox-label">
					<?php echo esc_html( ucwords( $post_type_title ) ); ?>
					<input type="checkbox" name="wpbf_settings[wpbf_transparent_header_global][]" id="wpbf_settings_wpbf_transparent_header_global_<?php echo esc_attr( $number ); ?>" value="<?php echo esc_attr( $advanced_setting ); ?>" <?php checked( $transparent_header_global, $advanced_setting ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
			$number++;

		}
		?>

	</div>
	<a href="javascript:void(0)" class="wpbf-transparent-header-advanced wpbf-advanced-link"><?php _e( '+ Advanced', 'wpbfpremium' ); ?></a>

	<?php

}
