<?php
/**
 * Scripts & Styles customizer settings.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/* Panel */

// Scripts.
wpbf_customizer_panel()
	->id( 'scripts_panel' )
	->title( __( 'Scripts & Styles', 'wpbfpremium' ) )
	->priority( 6 )
	->add();

/* Sections */

// Header.
wpbf_customizer_section()
	->id( 'wpbf_header_scripts' )
	->title( __( 'Header', 'wpbfpremium' ) )
	->priority( 100 )
	->addToPanel( 'scripts_panel' );

// Footer.
wpbf_customizer_section()
	->id( 'wpbf_footer_scripts' )
	->title( __( 'Footer', 'wpbfpremium' ) )
	->priority( 200 )
	->addToPanel( 'scripts_panel' );

/* Fields */

// Head.
wpbf_customizer_field()
	->id( 'head_scripts' )
	->type( 'code' )
	->label( __( 'Head Code', 'wpbfpremium' ) )
	->description( __( 'Runs inside the head tag.', 'wpbfpremium' ) )
	->priority( 1 )
	->properties( array(
		'language' => 'html',
	) )
	->addToSection( 'wpbf_header_scripts' );

// Header.
wpbf_customizer_field()
	->id( 'header_scripts' )
	->type( 'code' )
	->label( __( 'Header Code', 'wpbfpremium' ) )
	->description( __( 'Runs after the opening body tag.', 'wpbfpremium' ) )
	->priority( 2 )
	->properties( array(
		'language' => 'html',
	) )
	->addToSection( 'wpbf_header_scripts' );

// Footer.
wpbf_customizer_field()
	->id( 'footer_scripts' )
	->type( 'code' )
	->label( __( 'Footer Code', 'wpbfpremium' ) )
	->description( __( 'Add Scripts (Google Analytics, etc.) here. Runs before the closing body tag (wp_footer hook).', 'wpbfpremium' ) )
	->priority( 1 )
	->properties( array(
		'language' => 'html',
	) )
	->addToSection( 'wpbf_footer_scripts' );
