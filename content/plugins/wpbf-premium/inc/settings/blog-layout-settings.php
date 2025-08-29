<?php
/**
 * Blog layout settings metabox.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Premium Add-On settings.
 */
function wpbf_premium_blog_layout_settings() {

	// Vars.
	$blog_layout_settings = ! wpbf_is_white_labeled() && is_main_site() ? '<a href="https://wp-pagebuilderframework.com/docs/advanced-blog-layouts/" target="_blank" class="dashicons dashicons-editor-help help-icon"></a>' : '';

	// Sections.
	add_settings_section( 'blog-layout-settings-section', sprintf( __( 'Archive Layouts %1s', 'wpbfpremium' ), $blog_layout_settings ), '', 'wpbf-blog-layout-settings' );
	add_settings_section( 'post-layout-settings-section', sprintf( __( 'Post Layouts %1s', 'wpbfpremium' ), $blog_layout_settings ), '', 'wpbf-post-layout-settings' );

	// Fields.
	add_settings_field( 'wpbf_blog_layouts', __( 'Archive Layout Settings', 'wpbfpremium' ) . '<p class="description">' . __( 'Enable additional Archive Layout Settings in the Customizer.', 'wpbfpremium' ) . '</p>', 'wpbf_archive_layouts_callback', 'wpbf-blog-layout-settings', 'blog-layout-settings-section' );
	add_settings_field( 'wpbf_post_layouts', __( 'Post Layout Settings', 'wpbfpremium' ) . '<p class="description">' . __( 'Enable additional Post Layout Settings in the Customizer.', 'wpbfpremium' ) . '</p>', 'wpbf_post_layouts_callback', 'wpbf-post-layout-settings', 'post-layout-settings-section' );

}
add_action( 'admin_init', 'wpbf_premium_blog_layout_settings' );

/**
 * Blog layouts callback.
 */
function wpbf_archive_layouts_callback() {

	$archives = wpbf_blog_layouts_archive_array( $third_party = true );
	$settings = get_option( 'wpbf_settings' );

	// Default archives.
	$default_archives = array(
		'blog'   => __( 'Blog Page', 'wpbfpremium' ),
		'search' => __( 'Search Results', 'wpbfpremium' ),
	);

	// Merge default archives array with wpbf_blog_layouts_archive_array array.
	$archives = array_merge( $default_archives, $archives );
	?>

	<div class="setting-fields">

		<?php
		$number = 0;

		// Loop through archives.
		foreach ( $archives as $archive => $value ) {

			$blog_layouts = false;

			if ( isset( $settings['wpbf_blog_layouts'] ) && in_array( $archive, $settings['wpbf_blog_layouts'], true ) ) {
				$blog_layouts = $archive;
			}

			$post_type_title = str_replace( array( '_', '-' ), ' ', $value );
			?>

			<div class="setting-field">
				<label for="wpbf_settings_wpbf_blog_layouts_<?php echo esc_attr( $number ); ?>" class="label checkbox-label">
					<?php echo esc_html( ucwords( $post_type_title ) ); ?>
					<input type="checkbox" name="wpbf_settings[wpbf_blog_layouts][]" id="wpbf_settings_wpbf_blog_layouts_<?php echo esc_attr( $number ); ?>" value="<?php echo esc_attr( $archive ); ?>" <?php checked( $blog_layouts, $archive ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
			$number++;
		}
		?>

	</div>

	<div class="setting-fields wpbf-blog-layouts-advanced-wrapper" style="display: none;">

		<h3><?php _e( 'Advanced', 'wpbfpremium' ); ?></h3>

		<?php
		$number = 100;

		// Advanced settings array.
		$advanced_default_archives = array(
			'category' => __( 'Categories', 'wpbfpremium' ),
			'tag'      => __( 'Tags', 'wpbfpremium' ),
			'author'   => __( 'Author Archives' ),
			'date'     => __( 'Date Archives', 'wpbfpremium' ),
		);

		// Loop through advanced settings array.
		foreach ( $advanced_default_archives as $advanced_default_archive => $value ) {

			$blog_layouts = false;

			if ( isset( $settings['wpbf_blog_layouts'] ) && in_array( $advanced_default_archive, $settings['wpbf_blog_layouts'] ) ) {
				$blog_layouts = $advanced_default_archive;
			}

			$post_type_title = str_replace( array( '_', '-' ), ' ', $value );
			?>

			<div class="setting-field">
				<label for="wpbf_settings_wpbf_blog_layouts_<?php echo esc_attr( $number ); ?>" class="label checkbox-label">
					<?php echo esc_html( ucwords( $post_type_title ) ); ?>
					<input type="checkbox" name="wpbf_settings[wpbf_blog_layouts][]" id="wpbf_settings_wpbf_blog_layouts_<?php echo esc_attr( $number ); ?>" value="<?php echo esc_attr( $advanced_default_archive ); ?>" <?php checked( $blog_layouts, $advanced_default_archive ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
			$number++;
		}
		?>

	</div>
	<a href="javascript:void(0)" class="wpbf-blog-layouts-advanced wpbf-advanced-link"><?php _e( '+ Advanced', 'wpbfpremium' ); ?></a>

	<?php
}

/**
 * Post layouts callback.
 */
function wpbf_post_layouts_callback() {

	$post_types = wpbf_template_settings_post_type_array( $third_party = true );
	$settings   = get_option( 'wpbf_settings' );
	?>

	<div class="setting-fields">

		<?php
		$number = 0;

		// Loop through archives.
		foreach ( $post_types as $post_type => $value ) {

			$post_layouts = false;

			if ( isset( $settings['wpbf_post_layouts'] ) && in_array( $post_type, $settings['wpbf_post_layouts'], true ) ) {
				$post_layouts = $post_type;
			}

			$post_type_title = str_replace( array( '_', '-' ), ' ', $value );
			?>

			<div class="setting-field">
				<label for="wpbf_settings_wpbf_post_layouts_<?php echo esc_attr( $number ); ?>" class="label checkbox-label">
					<?php echo esc_html( ucwords( $post_type_title ) ); ?>
					<input type="checkbox" name="wpbf_settings[wpbf_post_layouts][]" id="wpbf_settings_wpbf_post_layouts_<?php echo esc_attr( $number ); ?>" value="<?php echo esc_attr( $post_type ); ?>" <?php checked( $post_layouts, $post_type ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
			$number++;
		}

		if ( empty( $post_types ) ) {
			_e( 'No Custom Post Types available.', 'wpbfpremium' );
		}
		?>

	</div>

	<?php
}
