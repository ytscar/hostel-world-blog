<?php

defined( 'ABSPATH' ) || die( "Can't access directly" );

if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

if ( ! wpbf_premium_is_theme_outdated() ) {
	return;
}

ob_start();
?>

<p>
	<?php _e( 'Your version of <strong>Page Builder Framework</strong> is outdated and no longer compatible with the latest version of the <strong>Premium Add-On.</strong>', 'wpbfpremium' ); ?>
	<?php _e( 'The minimum required theme version is <strong>' . WPBF_MIN_VERSION . '.</strong> Please update Page Builder Framework to the latest version.', 'wpbfpremium' ); ?>
</p>
<p>
	<a href="<?php echo esc_url( admin_url( 'themes.php' ) ); ?>" class="button button-primary">
		<?php _e( 'View Updates', 'wpbfpremium' ); ?>
	</a>
</p>

<?php
$wpbf_theme_outdated_notice = ob_get_clean();

if ( function_exists( 'wpbf_customizer_section' ) ) {
	// Section.
	wpbf_customizer_section()
		->id( 'wpbf_premium_addon' )
		->type( 'expanded' )
		->title( __( 'Compatibility Warning', 'wpbfpremium' ) )
		->priority( 1 )
		->add();

	// Field.
	wpbf_customizer_field()
		->id( 'wpbf_theme_outdated_notice' )
		->type( 'custom' )
		->defaultValue( $wpbf_theme_outdated_notice )
		->priority( 1 )
		->addToSection( 'wpbf_premium_addon' );
} elseif ( class_exists( '\Kirki' ) ) {
	// Panel.
	\Kirki::add_section( 'wpbf_premium_addon', array(
		'title'    => __( 'Compatibility Warning', 'wpbfpremium' ),
		'priority' => 1,
		'type'     => 'expanded',
	) );

	// Field.
	\Kirki::add_field( 'wpbf', array(
		'type'     => 'custom',
		'settings' => 'wpbf_theme_outdated_notice',
		'section'  => 'wpbf_premium_addon',
		'default'  => $wpbf_theme_outdated_notice,
		'priority' => 1,
	) );
}
