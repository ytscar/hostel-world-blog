<?php
/**
 * Customizer metabox.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Output customizer links.
 */
function wpbf_do_customizer_links() {

	$customizer_links = array(
		array(
			'text' => __( 'Logo', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bsection%5D=title_tagline' ),
		),
		array(
			'text' => __( 'Site Navigation', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bsection%5D=wpbf_menu_options' ),
		),
		array(
			'text' => __( 'Header', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bpanel%5D=header_panel' ),
		),
		array(
			'text' => __( 'Footer', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bsection%5D=wpbf_footer_options' ),
		),
		array(
			'text' => __( 'Layout', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bsection%5D=wpbf_page_options' ),
		),
		array(
			'text' => __( 'Sidebar', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bsection%5D=wpbf_sidebar_options' ),
		),
		array(
			'text' => __( 'Blog', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bpanel%5D=blog_panel' ),
		),
		array(
			'text' => __( 'Post Layout', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bsection%5D=wpbf_single_options' ),
		),
		array(
			'text' => __( 'Typography', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bpanel%5D=typo_panel' ),
		),
		array(
			'text' => __( 'Theme Buttons', 'wpbfpremium' ),
			'url'  => admin_url( 'customize.php?autofocus%5Bsection%5D=wpbf_button_options' ),
		),
	);

	foreach ( $customizer_links as $link_item ) {
		?>

		<li>
			<a href="<?php echo esc_url( $link_item['url'] ); ?>">
				<?php echo esc_html( $link_item['text'] ); ?>
			</a>
		</li>

		<?php
	}

}
add_action( 'wpbf_customizer_links', 'wpbf_do_customizer_links' );
?>

<h2>
	<?php _e( 'Customizer Settings', 'wpbfpremium' ); ?>
</h2>

<ul class="wpbf-customizer-list">

	<?php
	do_action( 'wpbf_before_customizer_links' );
	do_action( 'wpbf_customizer_links' );
	do_action( 'wpbf_after_customizer_links' );
	?>

	<li>
		<h3>
			<?php _e( 'Launch WordPress Customizer', 'wpbfpremium' ); ?>
		</h3>
		<p>
			<?php
			// translators: %s: Theme name.
			printf( __( 'Explore all of the %s features.', 'wpbfpremium' ), apply_filters( 'wpbf_premium_theme_name', WPBF_PREMIUM_THEME_NAME ) );
			?>
		</p>
		<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" target="_blank" class="button button-larger button-primary"><?php _e( 'Customize', 'wpbfpremium' ); ?></a>
	</li>

</ul>
