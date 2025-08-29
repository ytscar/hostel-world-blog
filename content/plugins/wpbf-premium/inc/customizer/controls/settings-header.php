<?php
/**
 * Header customizer settings.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/* Sections */

// Transparent header.
wpbf_customizer_section()
	->id( 'wpbf_transparent_header_options' )
	->title( __( 'Transparent Header', 'wpbfpremium' ) )
	->priority( 350 )
	->addToPanel( 'header_panel' );

// Sticky navigation.
wpbf_customizer_section()
	->id( 'wpbf_sticky_menu_options' )
	->title( __( 'Sticky Navigation', 'wpbfpremium' ) )
	->priority( 400 )
	->tabs( array(
		'general' => array(
			'label' => esc_html__( 'General', 'wpbfpremium' ),
		),
		'design'  => array(
			'label' => esc_html__( 'Design', 'wpbfpremium' ),
		),
	) )
	->addToPanel( 'header_panel' );

// Navigation hover effects.
wpbf_customizer_section()
	->id( 'wpbf_menu_effect_options' )
	->title( __( 'Navigation Hover Effects', 'wpbfpremium' ) )
	->priority( 500 )
	->addToPanel( 'header_panel' );

// Call to Action button.
wpbf_customizer_section()
	->id( 'wpbf_cta_button_options' )
	->title( __( 'Call to Action Button', 'wpbfpremium' ) )
	->priority( 600 )
	->tabs( array(
		'general' => array(
			'label' => esc_html__( 'General', 'wpbfpremium' ),
		),
		'design'  => array(
			'label' => esc_html__( 'Design', 'wpbfpremium' ),
		),
	) )
	->addToPanel( 'header_panel' );

/* Fields - Transparent header */

// Logo.
wpbf_customizer_field()
	->id( 'menu_transparent_logo' )
	->type( 'image' )
	->label( __( 'Logo', 'wpbfpremium' ) )
	->priority( 0 )
	->activeCallback( array(
		array(
			'id'       => 'custom_logo',
			'operator' => '!=',
			'value'    => '',
		),
	) )
	->partialRefresh( array(
		'menu_transparent_logo' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_transparent_header_options' );

// Width.
wpbf_customizer_field()
	->id( 'menu_transparent_width' )
	->type( 'dimension' )
	->tab( 'general' )
	->label( __( 'Transparent Header Width', 'wpbfpremium' ) )
	->description( __( 'Default: 1200px', 'wpbfpremium' ) )
	->priority( 0 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_transparent_header_options' );

// Separator.
wpbf_customizer_field()
	->id( 'menu_transparent_logo_separator' )
	->type( 'divider' )
	->priority( 0 )
	->addToSection( 'wpbf_transparent_header_options' );

// Background color.
wpbf_customizer_field()
	->id( 'menu_transparent_background_color' )
	->type( 'color' )
	->label( __( 'Background Color', 'wpbfpremium' ) )
	->priority( 1 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_transparent_header_options' );

// Font color.
wpbf_customizer_field()
	->id( 'menu_transparent_font_color' )
	->type( 'color' )
	->label( __( 'Font Color', 'wpbfpremium' ) )
	->priority( 2 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_transparent_header_options' );

// Font color alt.
wpbf_customizer_field()
	->id( 'menu_transparent_font_color_alt' )
	->type( 'color' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_transparent_header_options' );

// Logo color.
wpbf_customizer_field()
	->id( 'menu_transparent_logo_color' )
	->type( 'color' )
	->label( __( 'Logo Color', 'wpbfpremium' ) )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'custom_logo',
			'operator' => '==',
			'value'    => '',
		),
	) )
	->addToSection( 'wpbf_transparent_header_options' );

// Logo color alt.
wpbf_customizer_field()
	->id( 'menu_transparent_logo_color_alt' )
	->type( 'color' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'custom_logo',
			'operator' => '==',
			'value'    => '',
		),
	) )
	->addToSection( 'wpbf_transparent_header_options' );

// Tagline color.
wpbf_customizer_field()
	->id( 'menu_transparent_tagline_color' )
	->type( 'color' )
	->label( __( 'Tagline Color', 'wpbfpremium' ) )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'custom_logo',
			'operator' => '==',
			'value'    => '',
		),
		array(
			'id'       => 'menu_logo_description',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_transparent_header_options' );

// Off canvas headline.
wpbf_customizer_field()
	->id( 'menu_transparent_off_canvas_headline' )
	->type( 'headline' )
	->label( esc_html__( 'Off Canvas Settings', 'wpbfpremium' ) )
	->priority( 4 )
	->activeCallback( [
		[
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left' ),
		],
	] )
	->addToSection( 'wpbf_transparent_header_options' );

// Full screen headline.
wpbf_customizer_field()
	->id( 'menu_transparent_full_screen_headline' )
	->type( 'headline' )
	->label( esc_html__( 'Full Screen Settings', 'wpbfpremium' ) )
	->priority( 5 )
	->activeCallback( [
		[
			'id'       => 'menu_position',
			'operator' => '==',
			'value'    => 'menu-full-screen',
		],
	] )
	->addToSection( 'wpbf_transparent_header_options' );

// Off canvas hamburger color.
wpbf_customizer_field()
	->id( 'menu_transparent_hamburger_color' )
	->type( 'color' )
	->label( __( 'Icon Color', 'wpbfpremium' ) )
	->priority( 6 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ),
		),
	) )
	->addToSection( 'wpbf_transparent_header_options' );

// Mobile menu headline.
wpbf_customizer_field()
	->id( 'menu_transparent_mobile_headline' )
	->type( 'headline' )
	->label( esc_html__( 'Mobile Menu Settings', 'wpbfpremium' ) )
	->priority( 7 )
	->addToSection( 'wpbf_transparent_header_options' );

// Disable on mobile.
wpbf_customizer_field()
	->id( 'menu_transparent_mobile_disabled' )
	->type( 'toggle' )
	->label( __( 'Disable Transparent Header', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( 8 )
	->addToSection( 'wpbf_transparent_header_options' );

// Mobile menu icon color.
wpbf_customizer_field()
	->id( 'menu_transparent_hamburger_color_mobile' )
	->type( 'color' )
	->label( __( 'Icon Color', 'wpbfpremium' ) )
	->priority( 9 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'mobile_menu_options',
			'operator' => '!=',
			'value'    => 'menu-mobile-default',
		),
	) )
	->addToSection( 'wpbf_transparent_header_options' );

// Mobile menu hamburger background color.
wpbf_customizer_field()
	->id( 'menu_transparent_hamburger_bg_color_mobile' )
	->type( 'color' )
	->label( __( 'Hamburger Icon Color', 'wpbfpremium' ) )
	->priority( 10 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'mobile_menu_options',
			'operator' => '!=',
			'value'    => 'menu-mobile-default',
		),
		array(
			'id'       => 'mobile_menu_hamburger_bg_color',
			'operator' => '!=',
			'value'    => '',
		),
	) )
	->addToSection( 'wpbf_transparent_header_options' );

/* Fields - Sticky navigation */

$i = 0;

// Toggle.
wpbf_customizer_field()
	->id( 'menu_sticky' )
	->type( 'toggle' )
	->label( esc_html__( 'Sticky Navigation', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( $i++ )
	->tab( 'general' )
	->partialRefresh( [
		'menu_sticky' => [
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		],
	] )
	->addToSection( 'wpbf_sticky_menu_options' );

// Divider.
wpbf_customizer_field()
	->id( 'menu_active_toggle_separator' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( $i++ )
	->activeCallback( [
		[
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		],
	] )
	->addToSection( 'wpbf_sticky_menu_options' );

// Logo.
wpbf_customizer_field()
	->id( 'menu_active_logo' )
	->type( 'image' )
	->tab( 'general' )
	->label( __( 'Logo', 'wpbfpremium' ) )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'custom_logo',
			'operator' => '!=',
			'value'    => '',
		),
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->partialRefresh( array(
		'menu_active_logo' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Logo size.
wpbf_customizer_field()
	->id( 'menu_active_logo_size' )
	->type( 'responsive-input-slider' )
	->tab( 'general' )
	->label( __( 'Logo Width', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'custom_logo',
			'operator' => '!=',
			'value'    => '',
		),
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->properties( array(
		'min'          => 0,
		'max'          => 500,
		'step'         => 1,
		'save_as_json' => true,
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Divider.
wpbf_customizer_field()
	->id( 'menu_active_logo_separator' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( $i++ )
	->activeCallback( [
		[
			'id'       => 'custom_logo',
			'operator' => '!=',
			'value'    => '',
		],
		[
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		],
	] )
	->addToSection( 'wpbf_sticky_menu_options' );

// Hide logo.
wpbf_customizer_field()
	->id( 'menu_active_hide_logo' )
	->type( 'toggle' )
	->tab( 'general' )
	->label( __( 'Hide Logo', 'wpbfpremium' ) )
	->description( __( 'Hide logo from Sticky Navigation.', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-stacked', 'menu-stacked-advanced', 'menu-centered' ),
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Width.
wpbf_customizer_field()
	->id( 'menu_active_width' )
	->type( 'dimension' )
	->tab( 'general' )
	->label( __( 'Sticky Navigation Width', 'wpbfpremium' ) )
	->description( __( 'Default: 1200px', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Height.
wpbf_customizer_field()
	->id( 'menu_active_height' )
	->type( 'slider' )
	->tab( 'general' )
	->label( __( 'Menu Height', 'wpbfpremium' ) )
	->defaultValue( 20 )
	->priority( $i++ )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->properties( array(
		'min'  => 5,
		'max'  => 80,
		'step' => 1,
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Box shadow.
wpbf_customizer_field()
	->id( 'menu_active_box_shadow' )
	->type( 'toggle' )
	->tab( 'design' )
	->label( __( 'Box Shadow', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Box shadow blur.
wpbf_customizer_field()
	->id( 'menu_active_box_shadow_blur' )
	->type( 'slider' )
	->tab( 'design' )
	->label( __( 'Blur', 'wpbfpremium' ) )
	->defaultValue( 5 )
	->priority( $i++ )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'menu_active_box_shadow',
			'operator' => '==',
			'value'    => 1,
		),
	) )
	->properties( array(
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Box shadow color.
wpbf_customizer_field()
	->id( 'menu_active_box_shadow_color' )
	->type( 'color' )
	->label( __( 'Color', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( 'rgba(0,0,0,.15)' )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'menu_active_box_shadow',
			'operator' => '==',
			'value'    => 1,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Divider.
wpbf_customizer_field()
	->id( 'menu_active_box_shadow_divider' )
	->type( 'divider' )
	->tab( 'design' )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Stacked background color.
wpbf_customizer_field()
	->id( 'menu_active_stacked_bg_color' )
	->type( 'color' )
	->label( __( 'Logo Area Background Color', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( '#ffffff' )
	->priority( $i++ )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'menu_position',
			'operator' => '==',
			'value'    => 'menu-stacked-advanced',
		),
		array(
			'id'       => 'menu_active_hide_logo',
			'operator' => '==',
			'value'    => false,
		),
	) )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Background color.
wpbf_customizer_field()
	->id( 'menu_active_bg_color' )
	->type( 'color' )
	->label( __( 'Background Color', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( '#f5f5f7' )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Font color.
wpbf_customizer_field()
	->id( 'menu_active_font_color' )
	->type( 'color' )
	->label( __( 'Font Color', 'wpbfpremium' ) )
	->tab( 'design' )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Font color alt.
wpbf_customizer_field()
	->id( 'menu_active_font_color_alt' )
	->type( 'color' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->tab( 'design' )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Logo color.
wpbf_customizer_field()
	->id( 'menu_active_logo_color' )
	->type( 'color' )
	->label( __( 'Logo Color', 'wpbfpremium' ) )
	->tab( 'design' )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'custom_logo',
			'operator' => '==',
			'value'    => '',
		),
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Logo color alt.
wpbf_customizer_field()
	->id( 'menu_active_logo_color_alt' )
	->type( 'color' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->tab( 'design' )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'custom_logo',
			'operator' => '==',
			'value'    => '',
		),
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Tagline color.
wpbf_customizer_field()
	->id( 'menu_active_tagline_color' )
	->type( 'color' )
	->label( __( 'Tagline Color', 'wpbfpremium' ) )
	->tab( 'design' )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'custom_logo',
			'operator' => '==',
			'value'    => '',
		),
		array(
			'id'       => 'menu_logo_description',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Divider.
wpbf_customizer_field()
	->id( 'menu_active_animation_separator' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Delay.
wpbf_customizer_field()
	->id( 'menu_active_delay' )
	->type( 'dimension' )
	->tab( 'general' )
	->label( __( 'Delay', 'wpbfpremium' ) )
	->description( __( 'Set a delay after the sticky navigation should appear. Default: 300px', 'wpbfpremium' ) )
	->defaultValue( '' )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->partialRefresh( array(
		'menu_active_delay' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Animation.
wpbf_customizer_field()
	->id( 'menu_active_animation' )
	->type( 'select' )
	->tab( 'general' )
	->label( __( 'Animation', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->choices( array(
		'none'   => __( 'None', 'wpbfpremium' ),
		'fade'   => __( 'Fade In', 'wpbfpremium' ),
		'slide'  => __( 'Slide Down', 'wpbfpremium' ),
		'scroll' => __( 'Hide on Scroll', 'wpbfpremium' ),
		'shrink' => __( 'Shrink', 'wpbfpremium' ),
	) )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->partialRefresh( array(
		'menu_active_animation' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Animation duration.
wpbf_customizer_field()
	->id( 'menu_active_animation_duration' )
	->type( 'slider' )
	->tab( 'general' )
	->label( __( 'Duration', 'wpbfpremium' ) )
	->description( __( 'Default: 250', 'wpbfpremium' ) )
	->defaultValue( 200 )
	->properties( array(
		'min'  => 50,
		'max'  => 1000,
		'step' => 10,
	) )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'menu_active_animation',
			'operator' => '!==',
			'value'    => 'none',
		),
		array(
			'id'       => 'menu_active_animation',
			'operator' => '!==',
			'value'    => 'scroll',
		),
		array(
			'id'       => 'menu_active_animation',
			'operator' => '!==',
			'value'    => 'shrink',
		),
	) )
	->partialRefresh( array(
		'menu_active_animation_duration' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Off canvas headline.
wpbf_customizer_field()
	->id( 'active_off_canvas_headline' )
	->type( 'headline' )
	->tab( 'design' )
	->label( esc_html__( 'Off Canvas Settings', 'wpbfpremium' ) )
	->priority( $i++ )
	->activeCallback( [
		[
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		],
		[
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left' ),
		],
	] )
	->addToSection( 'wpbf_sticky_menu_options' );

// Full screen headline.
wpbf_customizer_field()
	->id( 'active_full_screen_headline' )
	->type( 'headline' )
	->tab( 'design' )
	->label( esc_html__( 'Full Screen Settings', 'wpbfpremium' ) )
	->priority( $i++ )
	->activeCallback( [
		[
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		],
		[
			'id'       => 'menu_position',
			'operator' => '==',
			'value'    => 'menu-full-screen',
		],
	] )
	->addToSection( 'wpbf_sticky_menu_options' );

// Off canvas hamburger color.
wpbf_customizer_field()
	->id( 'menu_active_off_canvas_hamburger_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Icon Color', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ),
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Mobile menu headline.
wpbf_customizer_field()
	->id( 'active_mobile_menu_headline' )
	->type( 'headline' )
	->tab( 'design' )
	->label( esc_html__( 'Mobile Menu Settings', 'wpbfpremium' ) )
	->priority( $i++ )
	->activeCallback( [
		[
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		],
		[
			'id'       => 'mobile_menu_options',
			'operator' => 'in',
			'value'    => array( 'menu-mobile-hamburger', 'menu-mobile-off-canvas' ),
		],
	] )
	->addToSection( 'wpbf_sticky_menu_options' );

// Disable on mobile.
wpbf_customizer_field()
	->id( 'menu_active_mobile_disabled' )
	->type( 'toggle' )
	->tab( 'general' )
	->label( __( 'Disable Sticky Navigation', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Mobile menu icon color.
wpbf_customizer_field()
	->id( 'mobile_menu_active_hamburger_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Icon Color', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'mobile_menu_options',
			'operator' => 'in',
			'value'    => array( 'menu-mobile-hamburger', 'menu-mobile-off-canvas' ),
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

// Mobile menu hamburger background color.
wpbf_customizer_field()
	->id( 'mobile_menu_active_hamburger_bg_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Hamburger Icon Color', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'mobile_menu_options',
			'operator' => 'in',
			'value'    => array( 'menu-mobile-hamburger', 'menu-mobile-off-canvas' ),
		),
		array(
			'id'       => 'mobile_menu_hamburger_bg_color',
			'operator' => '!=',
			'value'    => '',
		),
	) )
	->addToSection( 'wpbf_sticky_menu_options' );

/* Fields - Navigation hover effects */

// Effect.
wpbf_customizer_field()
	->id( 'menu_effect' )
	->type( 'select' )
	->label( __( 'Hover Effect', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->priority( 1 )
	->choices( array(
		'none'       => __( 'None', 'wpbfpremium' ),
		'underlined' => __( 'Underline', 'wpbfpremium' ),
		'boxed'      => __( 'Box', 'wpbfpremium' ),
		'modern'     => __( 'Modern', 'wpbfpremium' ),
	) )
	->partialRefresh( array(
		'menu_effect' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_menu_effect_options' );

// Animation.
wpbf_customizer_field()
	->id( 'menu_effect_animation' )
	->type( 'select' )
	->label( __( 'Animation', 'wpbfpremium' ) )
	->defaultValue( 'fade' )
	->priority( 1 )
	->choices( array(
		'fade'  => __( 'Fade', 'wpbfpremium' ),
		'slide' => __( 'Slide', 'wpbfpremium' ),
		'grow'  => __( 'Grow', 'wpbfpremium' ),
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_effect',
			'operator' => '!=',
			'value'    => 'none',
		),
		array(
			'id'       => 'menu_effect',
			'operator' => '!=',
			'value'    => 'modern',
		),
	) )
	->partialRefresh( array(
		'menu_effect_animation' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_menu_effect_options' );

// Alignment.
wpbf_customizer_field()
	->id( 'menu_effect_alignment' )
	->type( 'radio-image' )
	->label( __( 'Alignment', 'wpbfpremium' ) )
	->defaultValue( 'center' )
	->choices( array(
		'left'   => WPBF_PREMIUM_URI . '/inc/customizer/img/align-left.jpg',
		'center' => WPBF_PREMIUM_URI . '/inc/customizer/img/align-center.jpg',
		'right'  => WPBF_PREMIUM_URI . '/inc/customizer/img/align-right.jpg',
	) )
	->priority( 2 )
	->activeCallback( array(
		array(
			'id'       => 'menu_effect_animation',
			'operator' => '==',
			'value'    => 'slide',
		),
		array(
			'id'       => 'menu_effect',
			'operator' => '!=',
			'value'    => 'modern',
		),
		array(
			'id'       => 'menu_effect',
			'operator' => '!=',
			'value'    => 'none',
		),
	) )
	->partialRefresh( array(
		'menu_effect_alignment' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_menu_effect_options' );

// Size (underlined).
Wpbf_customizer_field()
	->id( 'menu_effect_underlined_size' )
	->type( 'slider' )
	->label( __( 'Size', 'wpbfpremium' ) )
	->description( __( 'Default: 2', 'wpbfpremium' ) )
	->defaultValue( 2 )
	->properties( array(
		'min'  => 1,
		'max'  => 5,
		'step' => 1,
	) )
	->priority( 3 )
	->activeCallback( array(
		array(
			'id'       => 'menu_effect',
			'operator' => '==',
			'value'    => 'underlined',
		),
	) )
	->addToSection( 'wpbf_menu_effect_options' );

// Border radius (boxed).
wpbf_customizer_field()
	->id( 'menu_effect_boxed_radius' )
	->type( 'slider' )
	->label( __( 'Border Radius', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	) )
	->priority( 4 )
	->activeCallback( array(
		array(
			'id'       => 'menu_effect',
			'operator' => '==',
			'value'    => 'boxed',
		),
	) )
	->addToSection( 'wpbf_menu_effect_options' );

// Color.
wpbf_customizer_field()
	->id( 'menu_effect_color' )
	->type( 'color' )
	->label( __( 'Color', 'wpbfpremium' ) )
	->priority( 5 )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_effect',
			'operator' => '!=',
			'value'    => 'none',
		),
	) )
	->addToSection( 'wpbf_menu_effect_options' );

/* Fields - Call to Action button */

$i = 0;

// Toggle.
wpbf_customizer_field()
	->id( 'cta_button' )
	->type( 'toggle' )
	->tab( 'general' )
	->label( __( 'Call to Action Button', 'wpbfpremium' ) )
	->tooltip( __( 'Add a Call to Action button to your main header navigation.', 'wpbfpremium' ) )
	->defaultValue( false )
	->priority( $i++ )
	->partialRefresh( array(
		'cta_button' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Mobile toggle.
wpbf_customizer_field()
	->id( 'cta_button_mobile' )
	->type( 'toggle' )
	->tab( 'general' )
	->label( __( 'Enable on Mobile', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->partialRefresh( array(
		'cta_button_mobile' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Separator.
wpbf_customizer_field()
	->id( 'cta_button_divider' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( $i++ )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Button text.
wpbf_customizer_field()
	->id( 'cta_button_text' )
	->type( 'text' )
	->tab( 'general' )
	->label( __( 'Button Text', 'wpbfpremium' ) )
	->defaultValue( 'Call to Action' )
	->priority( $i++ )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Button link.
wpbf_customizer_field()
	->id( 'cta_button_url' )
	->type( 'url' )
	->tab( 'general' )
	->label( __( 'URL', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Target.
wpbf_customizer_field()
	->id( 'cta_button_target' )
	->type( 'toggle' )
	->tab( 'general' )
	->label( __( 'Open in a new Tab', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( $i++ )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Border radius.
wpbf_customizer_field()
	->id( 'cta_button_border_radius' )
	->type( 'slider' )
	->tab( 'design' )
	->label( __( 'Border Radius', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Background color.
wpbf_customizer_field()
	->id( 'cta_button_background_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Background Color', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Background color hover.
wpbf_customizer_field()
	->id( 'cta_button_background_color_alt' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Font color.
wpbf_customizer_field()
	->id( 'cta_button_font_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Font Color', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Font color hover.
wpbf_customizer_field()
	->id( 'cta_button_font_color_alt' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Transparent header headline.
wpbf_customizer_field()
	->id( 'cta_button_transparent_header_headline' )
	->type( 'headline' )
	->tab( 'design' )
	->label( esc_html__( 'Transparent Header', 'wpbfpremium' ) )
	->priority( $i++ )
	->activeCallback( [
		[
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		],
	] )
	->addToSection( 'wpbf_cta_button_options' );

// Background color.
wpbf_customizer_field()
	->id( 'cta_button_transparent_background_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Background Color', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Background color hover.
wpbf_customizer_field()
	->id( 'cta_button_transparent_background_color_alt' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Font color.
wpbf_customizer_field()
	->id( 'cta_button_transparent_font_color_alt' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Font Color', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Font color hover.
wpbf_customizer_field()
	->id( 'cta_button_transparent_font_color_alt' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Sticky navigation headline.
wpbf_customizer_field()
	->id( 'cta_button_sticky_header_headline' )
	->type( 'headline' )
	->tab( 'design' )
	->label( esc_html__( 'Sticky Navigation', 'wpbfpremium' ) )
	->priority( $i++ )
	->activeCallback( [
		[
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		],
		[
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		],
	] )
	->addToSection( 'wpbf_cta_button_options' );

// Background color.
wpbf_customizer_field()
	->id( 'cta_button_sticky_background_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Background Color', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Background color hover.
wpbf_customizer_field()
	->id( 'cta_button_sticky_background_color_alt' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Font color.
wpbf_customizer_field()
	->id( 'cta_button_sticky_font_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Font Color', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

// Font color hover.
wpbf_customizer_field()
	->id( 'cta_button_sticky_font_color_alt' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->priority( $i++ )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'id'       => 'cta_button',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_cta_button_options' );

/* Fields - Pre header */

// Toggle.
wpbf_customizer_field()
	->id( 'pre_header_sticky' )
	->type( 'toggle' )
	->tab( 'general' )
	->label( __( 'Sticky Pre Header', 'wpbfpremium' ) )
	->defaultValue( true )
	->priority( 0 )
	->activeCallback( array(
		array(
			'id'       => 'pre_header_layout',
			'operator' => '!=',
			'value'    => 'none',
		),
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->partialRefresh( array(
		'pre_header_sticky' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_pre_header_options' );

// Separator.
wpbf_customizer_field()
	->id( 'pre_header_sticky_separator' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( 0 )
	->activeCallback( array(
		array(
			'id'       => 'pre_header_layout',
			'operator' => '!=',
			'value'    => 'none',
		),
		array(
			'id'       => 'menu_sticky',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_pre_header_options' );

/* Fields - Stacked navigation (advanced) */

// Headline.
wpbf_customizer_field()
	->id( 'stacked_advanced_headline' )
	->type( 'headline' )
	->label( esc_html__( 'Stacked (Advanced) Settings', 'wpbfpremium' ) )
	->priority( 100 )
	->activeCallback( [
		[
			'id'       => 'menu_position',
			'operator' => '==',
			'value'    => 'menu-stacked-advanced',
		],
	] )
	->addToSection( 'wpbf_menu_options' );

// Alignment.
wpbf_customizer_field()
	->id( 'menu_alignment' )
	->type( 'radio-image' )
	->tab( 'general' )
	->label( __( 'Menu Alignment', 'wpbfpremium' ) )
	->defaultValue( 'left' )
	->priority( 110 )
	->choices( array(
		'left'   => WPBF_PREMIUM_URI . '/inc/customizer/img/align-left.jpg',
		'center' => WPBF_PREMIUM_URI . '/inc/customizer/img/align-center.jpg',
		'right'  => WPBF_PREMIUM_URI . '/inc/customizer/img/align-right.jpg',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => '==',
			'value'    => 'menu-stacked-advanced',
		),
	) )
	->partialRefresh( array(
		'menu_alignment' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Logo height.
wpbf_customizer_field()
	->id( 'menu_stacked_logo_height' )
	->type( 'slider' )
	->tab( 'general' )
	->label( __( 'Logo Area Height', 'wpbfpremium' ) )
	->defaultValue( 20 )
	->transport( 'postMessage' )
	->priority( 120 )
	->properties( array(
		'min'  => 5,
		'max'  => 80,
		'step' => 1,
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => '==',
			'value'    => 'menu-stacked-advanced',
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Editor.
wpbf_customizer_field()
	->id( 'menu_stacked_wysiwyg' )
	->type( 'editor' )
	->tab( 'general' )
	->label( __( 'Content beside Logo', 'wpbfpremium' ) )
	->defaultValue( '' )
	->priority( 130 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => '==',
			'value'    => 'menu-stacked-advanced',
		),
		array(
			'id'       => 'menu_alignment',
			'operator' => '!=',
			'value'    => 'center',
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Background color.
wpbf_customizer_field()
	->id( 'menu_stacked_bg_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Logo Area Background Color', 'wpbfpremium' ) )
	->defaultValue( '#ffffff' )
	->priority( 140 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => '==',
			'value'    => 'menu-stacked-advanced',
		),
	) )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_menu_options' );

/* Fields - Off canvas */

// Off canvas headline.
wpbf_customizer_field()
	->id( 'off_canvas_headline' )
	->type( 'headline' )
	->label( esc_html__( 'Off Canvas Settings', 'wpbfpremium' ) )
	->priority( 200 )
	->activeCallback( [
		[
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left' ),
		],
	] )
	->addToSection( 'wpbf_menu_options' );

// Full screen headline.
wpbf_customizer_field()
	->id( 'full_screen_headline' )
	->type( 'headline' )
	->tab( 'design' )
	->label( esc_html__( 'Full Screen Settings', 'wpbfpremium' ) )
	->priority( 200 )
	->activeCallback( [
		[
			'id'       => 'menu_position',
			'operator' => '==',
			'value'    => 'menu-full-screen',
		],
	] )
	->addToSection( 'wpbf_menu_options' );

// Off canvas hamburger color.
wpbf_customizer_field()
	->id( 'menu_off_canvas_hamburger_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Hamburger Icon Color', 'wpbfpremium' ) )
	->defaultValue( '#6d7680' )
	->priority( 210 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ),
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Off canvas hamburger size.
wpbf_customizer_field()
	->id( 'menu_off_canvas_hamburger_size' )
	->type( 'input-slider' )
	->tab( 'design' )
	->label( __( 'Hamburger Icon Size', 'wpbfpremium' ) )
	->defaultValue( '18px' )
	->transport( 'postMessage' )
	->priority( 220 )
	->properties( array(
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ),
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Separator.
wpbf_customizer_field()
	->id( 'menu_off_canvas_hamburger_size_separator' )
	->type( 'divider' )
	->tab( 'design' )
	->priority( 230 )
	->activeCallback( [
		[
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ),
		],
	] )
	->addToSection( 'wpbf_menu_options' );

// Push menu.
wpbf_customizer_field()
	->id( 'menu_off_canvas_push' )
	->type( 'toggle' )
	->tab( 'general' )
	->label( __( 'Push Menu', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( 240 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left' ),
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Off canvas background color.
wpbf_customizer_field()
	->id( 'menu_off_canvas_bg_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Background Color', 'wpbfpremium' ) )
	->defaultValue( '#ffffff' )
	->priority( 250 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ),
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Off canvas submenu arrow color.
wpbf_customizer_field()
	->id( 'menu_off_canvas_submenu_arrow_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Sub Menu Arrow Color', 'wpbfpremium' ) )
	->priority( 260 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left' ),
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Menu width.
wpbf_customizer_field()
	->id( 'menu_off_canvas_width' )
	->type( 'slider' )
	->tab( 'general' )
	->label( __( 'Menu Width', 'wpbfpremium' ) )
	->defaultValue( 400 )
	->priority( 270 )
	->transport( 'postMessage' )
	->properties( array(
		'min'  => 300,
		'max'  => 500,
		'step' => 10,
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left' ),
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Separator.
wpbf_customizer_field()
	->id( 'menu_overlay_separator' )
	->type( 'divider' )
	->tab( 'design' )
	->priority( 280 )
	->activeCallback( [
		[
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left' ),
		],
	] )
	->addToSection( 'wpbf_menu_options' );

// Off canvas overlay.
wpbf_customizer_field()
	->id( 'menu_overlay' )
	->type( 'toggle' )
	->tab( 'design' )
	->label( __( 'Overlay', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( 290 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left' ),
		),
	) )
	->addToSection( 'wpbf_menu_options' );

// Off canvas overlay color.
wpbf_customizer_field()
	->id( 'menu_overlay_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Overlay Background Color', 'wpbfpremium' ) )
	->defaultValue( 'rgba(0,0,0,.5)' )
	->priority( 300 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => 'in',
			'value'    => array( 'menu-off-canvas', 'menu-off-canvas-left' ),
		),
		array(
			'id'       => 'menu_overlay',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_menu_options' );

/* Fields - Custom menu */

if ( is_plugin_active( 'bb-plugin/fl-builder.php' ) || is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {

	// Separator.
	wpbf_customizer_field()
		->id( 'menu_custom_separator' )
		->type( 'divider' )
		->tab( 'general' )
		->priority( 999998 )
		->addToSection( 'wpbf_menu_options' );

	// Custom menu.
	wpbf_customizer_field()
		->id( 'menu_custom' )
		->type( 'code' )
		->tab( 'general' )
		->label( __( 'Custom Menu', 'wpbfpremium' ) )
		->description( __( 'Replace the default menu with a saved Beaver Builder or Elementor template. <br><br><strong>Example:</strong><br>[elementor-template id="xxx"]<br>[fl_builder_insert_layout id="xxx"]', 'wpbfpremium' ) )
		->transport( 'postMessage' )
		->priority( 999999 )
		->properties( array(
			'language' => 'html',
		) )
		->addToSection( 'wpbf_menu_options' );

}

/* Fields - Submenu */

// Separator.
wpbf_customizer_field()
	->id( 'sub_menu_animation_separator' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( 20 )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => '!=',
			'value'    => 'menu-off-canvas',
		),
		array(
			'id'       => 'menu_position',
			'operator' => '!=',
			'value'    => 'menu-off-canvas-left',
		),
		array(
			'id'       => 'menu_position',
			'operator' => '!=',
			'value'    => 'menu-full-screen',
		),
	) )
	->addToSection( 'wpbf_sub_menu_options' );

// Animation.
wpbf_customizer_field()
	->id( 'sub_menu_animation' )
	->type( 'select' )
	->tab( 'general' )
	->label( __( 'Sub Menu Animation', 'wpbfpremium' ) )
	->defaultValue( 'fade' )
	->choices( array(
		'fade'     => __( 'Fade', 'wpbfpremium' ),
		'down'     => __( 'Down', 'wpbfpremium' ),
		'up'       => __( 'Up', 'wpbfpremium' ),
		'zoom-in'  => __( 'Zoom In', 'wpbfpremium' ),
		'zoom-out' => __( 'Zoom Out', 'wpbfpremium' ),
	) )
	->priority( 21 )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => '!=',
			'value'    => 'menu-off-canvas',
		),
		array(
			'id'       => 'menu_position',
			'operator' => '!=',
			'value'    => 'menu-off-canvas-left',
		),
		array(
			'id'       => 'menu_position',
			'operator' => '!=',
			'value'    => 'menu-full-screen',
		),
	) )
	->partialRefresh( array(
		'sub_menu_animation' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_sub_menu_options' );

// Animation duration.
wpbf_customizer_field()
	->id( 'sub_menu_animation_duration' )
	->type( 'slider' )
	->tab( 'general' )
	->label( __( 'Duration', 'wpbfpremium' ) )
	->description( __( 'Default: 250', 'wpbfpremium' ) )
	->defaultValue( 250 )
	->priority( 22 )
	->choices( array(
		'min'  => 50,
		'max'  => 1000,
		'step' => 10,
	) )
	->activeCallback( array(
		array(
			'id'       => 'menu_position',
			'operator' => '!=',
			'value'    => 'menu-off-canvas',
		),
		array(
			'id'       => 'menu_position',
			'operator' => '!=',
			'value'    => 'menu-off-canvas-left',
		),
		array(
			'id'       => 'menu_position',
			'operator' => '!=',
			'value'    => 'menu-full-screen',
		),
	) )
	->partialRefresh( array(
		'sub_menu_animation_duration' => array(
			'container_inclusive' => true,
			'selector'            => '#header',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/header' );
			},
		),
	) )
	->addToSection( 'wpbf_sub_menu_options' );

/* Fields - Mobile menu */

// Separator.
wpbf_customizer_field()
	->id( 'mobile_menu_overlay_separator' )
	->type( 'headline' )
	->label( __( 'Off Canvas Settings', 'wpbfpremium' ) )
	->priority( 29 )
	->activeCallback( [
		[
			'id'       => 'mobile_menu_options',
			'operator' => '==',
			'value'    => 'menu-mobile-off-canvas',
		],
	] )
	->addToSection( 'wpbf_mobile_menu_options' );

// Off canvas width.
wpbf_customizer_field()
	->id( 'mobile_menu_width' )
	->type( 'dimension' )
	->label( __( 'Menu Width', 'wpbfpremium' ) )
	->description( __( 'Default: 320px', 'wpbfpremium' ) )
	->defaultValue( '320px' )
	->priority( 30 )
	->transport( 'postMessage' )
	->tab( 'general' )
	->activeCallback( array(
		array(
			'id'       => 'mobile_menu_options',
			'operator' => '==',
			'value'    => 'menu-mobile-off-canvas',
		),
	) )
	->addToSection( 'wpbf_mobile_menu_options' );

// Off canvas overlay.
wpbf_customizer_field()
	->id( 'mobile_menu_overlay' )
	->type( 'toggle' )
	->tab( 'design' )
	->label( __( 'Overlay', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( 31 )
	->activeCallback( array(
		array(
			'id'       => 'mobile_menu_options',
			'operator' => '==',
			'value'    => 'menu-mobile-off-canvas',
		),
	) )
	->addToSection( 'wpbf_mobile_menu_options' );

// Off canvas overlay color.
wpbf_customizer_field()
	->id( 'mobile_menu_overlay_color' )
	->type( 'color' )
	->tab( 'design' )
	->label( __( 'Background Color', 'wpbfpremium' ) )
	->defaultValue( 'rgba(0,0,0,.5)' )
	->priority( 32 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'mobile_menu_options',
			'operator' => '==',
			'value'    => 'menu-mobile-off-canvas',
		),
		array(
			'id'       => 'mobile_menu_overlay',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_mobile_menu_options' );
