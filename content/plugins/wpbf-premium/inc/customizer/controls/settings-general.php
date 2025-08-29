<?php
/**
 * General customizer settings.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/* Sections */

// Theme colors.
wpbf_customizer_section()
	->id( 'wpbf_global_options' )
	->title( __( 'Theme Colors', 'wpbfpremium' ) )
	->priority( 250 )
	->addToPanel( 'layout_panel' );

// Social media icons.
wpbf_customizer_section()
	->id( 'wpbf_social_icons_options' )
	->title( __( 'Social Media Icons', 'wpbfpremium' ) )
	->priority( 1100 )
	->tabs( array(
		'general' => array(
			'label' => esc_html__( 'General', 'page-builder-framework' ),
		),
		'design'  => array(
			'label' => esc_html__( 'Design', 'page-builder-framework' ),
		),
	) )
	->addToPanel( 'layout_panel' );

/* Fields - Theme colors */

// Headline.
wpbf_customizer_field()
	->id( 'theme_colors_headline' )
	->type( 'headline' )
	// ->label( esc_html__( 'Theme Colors', 'wpbfpremium' ) )
	->description( esc_html__( 'These settings allow you to change the themes default color palette.', 'wpbfpremium' ) )
	->priority( 0 )
	->addToSection( 'wpbf_global_options' );

// Base color.
wpbf_customizer_field()
	->id( 'base_color_alt_global' )
	->type( 'color' )
	->label( __( 'Light Color', 'wpbfpremium' ) )
	->tooltip( __( 'Used where a slighty darker color is necessary, compared to Light Color (Secondary).', 'wpbfpremium' ) )
	->defaultValue( '#dedee5' )
	->priority( 0 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_global_options' );

// Base color alt.
wpbf_customizer_field()
	->id( 'base_color_global' )
	->type( 'color' )
	->label( __( 'Light Color (Secondary)', 'wpbfpremium' ) )
	->tooltip( __( 'Used mostly as a background color on many elements, such as sidebar widgets. ', 'wpbfpremium' ) )
	->defaultValue( '#f5f5f7' )
	->priority( 0 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_global_options' );

// Separator.
wpbf_customizer_field()
	->id( 'base_color_separator' )
	->type( 'divider' )
	->priority( 0 )
	->addToSection( 'wpbf_global_options' );

// Brand color.
wpbf_customizer_field()
	->id( 'brand_color_global' )
	->type( 'color' )
	->label( __( 'Dark Color', 'wpbfpremium' ) )
	->tooltip( __( 'Used mostly for headlines or where high contrast is required. ', 'wpbfpremium' ) )
	->defaultValue( '#3e4349' )
	->priority( 0 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_global_options' );

// Brand color alt.
wpbf_customizer_field()
	->id( 'brand_color_alt_global' )
	->type( 'color' )
	->label( __( 'Dark Color (Secondary)', 'wpbfpremium' ) )
	->tooltip( __( 'Used mostly for regular text. ', 'wpbfpremium' ) )
	->defaultValue( '#6d7680' )
	->priority( 0 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_global_options' );

// Separator.
wpbf_customizer_field()
	->id( 'brand_color_separator' )
	->type( 'divider' )
	->priority( 0 )
	->addToSection( 'wpbf_global_options' );

// Accent color.
wpbf_customizer_field()
	->id( 'accent_color_global' )
	->type( 'color' )
	->label( __( 'Accent Color', 'wpbfpremium' ) )
	->tooltip( __( 'Used mostly for links. ', 'wpbfpremium' ) )
	->defaultValue( '#3ba9d2' )
	->priority( 0 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_global_options' );

// Accent color alt.
wpbf_customizer_field()
	->id( 'accent_color_alt_global' )
	->type( 'color' )
	->label( __( 'Accent Color (Hover)', 'wpbfpremium' ) )
	->tooltip( __( 'Should be either slightly darker or lighter than Accent Color. ', 'wpbfpremium' ) )
	->defaultValue( '#79c4e0' )
	->priority( 0 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_global_options' );

/* Fields - 404 */

// Separator.
wpbf_customizer_field()
	->id( 'divider-404' )
	->type( 'divider' )
	->priority( 100 )
	->addToSection( 'wpbf_404_options' );

// 404.
wpbf_customizer_field()
	->id( '404_custom' )
	->type( 'code' )
	->label( __( 'Custom 404 Page', 'wpbfpremium' ) )
	->description( __( 'Replace the default 404 page with a saved Beaver Builder or Elementor template. <br><br><strong>Example:</strong><br>[elementor-template id="xxx"]<br>[fl_builder_insert_layout id="xxx"]', 'wpbfpremium' ) )
	->priority( 100 )
	->properties( array(
		'language' => 'html',
	) )
	->addToSection( 'wpbf_404_options' );

/* Fields - Social media icons */

// Social sortable.
wpbf_customizer_field()
	->id( 'social_sortable' )
	->type( 'sortable' )
	->label( __( 'Social Media Icons', 'wpbfpremium' ) )
	->description( __( 'Display social media icons anywhere on your site by using the [social] shortcode.', 'wpbfpremium' ) )
	->tab( 'general' )
	->defaultValue( array() )
	->choices( wpbf_social_choices() )
	->priority( 1 )
	->partialRefresh( array(
		'social_sortable' => array(
			'container_inclusive' => true,
			'selector'            => '.wpbf-social-icons',
			'render_callback'     => function () {
				return wpbf_social();
			},
		),
	) )
	->addToSection( 'wpbf_social_icons_options' );

$choices = wpbf_social_choices();

foreach ( $choices as $social_media_id => $social_media_name ) {

	wpbf_customizer_field()
		->id( $social_media_id . '_link' )
		->type( 'email' === $social_media_id ? 'email' : 'url' )
		->label( 'email' === $social_media_id ? $social_media_name : $social_media_name . ' ' . __( 'URL', 'wpbfpremium' ) )
		->tab( 'general' )
		->priority( 10 )
		->transport( 'postMessage' )
		->activeCallback( array(
			array(
				'id'       => 'social_sortable',
				'operator' => 'in',
				'value'    => $social_media_id,
			),
		) )
		->addToSection( 'wpbf_social_icons_options' );

}

// Social shapes.
wpbf_customizer_field()
	->id( 'social_shapes' )
	->type( 'select' )
	->label( __( 'Style', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( 'wpbf-social-shape-plain' )
	->priority( 20 )
	->choices( array(
		'wpbf-social-shape-plain'   => __( 'Plain', 'wpbfpremium' ),
		'wpbf-social-shape-rounded' => __( 'Rounded', 'wpbfpremium' ),
		'wpbf-social-shape-boxed'   => __( 'Boxed', 'wpbfpremium' ),
	) )
	->partialRefresh( array(
		'social_shapes' => array(
			'container_inclusive' => true,
			'selector'            => '.wpbf-social-icons',
			'render_callback'     => function () {
				return wpbf_social();
			},
		),
	) )
	->addToSection( 'wpbf_social_icons_options' );

// Social styles.
wpbf_customizer_field()
	->id( 'social_styles' )
	->type( 'select' )
	->label( __( 'Color', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( 'wpbf-social-style-default' )
	->priority( 20 )
	->choices( array(
		'wpbf-social-style-default' => __( 'Accent Color', 'wpbfpremium' ),
		'wpbf-social-style-grey'    => __( 'Custom', 'wpbfpremium' ),
		'wpbf-social-style-brand'   => __( 'Brand Colors', 'wpbfpremium' ),
		'wpbf-social-style-filled'  => __( 'Filled', 'wpbfpremium' ),
	) )
	->partialRefresh( array(
		'social_styles' => array(
			'container_inclusive' => true,
			'selector'            => '.wpbf-social-icons',
			'render_callback'     => function () {
				return wpbf_social();
			},
		),
	) )
	->addToSection( 'wpbf_social_icons_options' );

// Social size.
wpbf_customizer_field()
	->id( 'social_sizes' )
	->type( 'select' )
	->label( __( 'Size', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( 'wpbf-social-size-small' )
	->priority( 20 )
	->choices( array(
		'wpbf-social-size-small' => __( 'Small', 'wpbfpremium' ),
		'wpbf-social-size-large' => __( 'Large', 'wpbfpremium' ),
	) )
	->partialRefresh( array(
		'social_sizes' => array(
			'container_inclusive' => true,
			'selector'            => '.wpbf-social-icons',
			'render_callback'     => function () {
				return wpbf_social();
			},
		),
	) )
	->addToSection( 'wpbf_social_icons_options' );

// Separator.
wpbf_customizer_field()
	->id( 'social_media_icons_design_separator' )
	->type( 'divider' )
	->tab( 'design' )
	->priority( 20 )
	->addToSection( 'wpbf_social_icons_options' );

// Social background color.
wpbf_customizer_field()
	->id( 'social_background_color' )
	->type( 'color' )
	->label( __( 'Background color', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( '#f5f5f7' )
	->transport( 'postMessage' )
	->priority( 20 )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'social_shapes',
			'operator' => '!=',
			'value'    => 'wpbf-social-shape-plain',
		),
		array(
			'id'       => 'social_styles',
			'operator' => '!=',
			'value'    => 'wpbf-social-style-filled',
		),
	) )
	->addToSection( 'wpbf_social_icons_options' );

// Social background color hover.
wpbf_customizer_field()
	->id( 'social_background_color_alt' )
	->type( 'color' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( '#f5f5f7' )
	->transport( 'postMessage' )
	->priority( 20 )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'social_shapes',
			'operator' => '!=',
			'value'    => 'wpbf-social-shape-plain',
		),
		array(
			'id'       => 'social_styles',
			'operator' => '!=',
			'value'    => 'wpbf-social-style-filled',
		),
	) )
	->addToSection( 'wpbf_social_icons_options' );

// Social icon color.
wpbf_customizer_field()
	->id( 'social_color' )
	->type( 'color' )
	->label( __( 'Icon Color', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( '#aaaaaa' )
	->transport( 'postMessage' )
	->priority( 20 )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'social_styles',
			'operator' => '==',
			'value'    => 'wpbf-social-style-grey',
		),
	) )
	->addToSection( 'wpbf_social_icons_options' );

// Social icon color hover.
wpbf_customizer_field()
	->id( 'social_color_alt' )
	->type( 'color' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( '#f5f5f7' )
	->transport( 'postMessage' )
	->priority( 20 )
	->properties( array(
		'mode' => 'alpha',
	) )
	->activeCallback( array(
		array(
			'id'       => 'social_styles',
			'operator' => '==',
			'value'    => 'wpbf-social-style-grey',
		),
	) )
	->addToSection( 'wpbf_social_icons_options' );

// Social font size.
wpbf_customizer_field()
	->id( 'social_font_size' )
	->type( 'slider' )
	->label( __( 'Icon Size', 'wpbfpremium' ) )
	->tab( 'design' )
	->defaultValue( 14 )
	->transport( 'postMessage' )
	->priority( 20 )
	->properties( array(
		'min'  => 12,
		'max'  => 32,
		'step' => 1,
	) )
	->addToSection( 'wpbf_social_icons_options' );
