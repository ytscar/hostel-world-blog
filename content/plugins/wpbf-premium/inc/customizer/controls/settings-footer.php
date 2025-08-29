<?php
/**
 * Footer customizer settings.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/* Fields - Widget footer */

// Widgets.
wpbf_customizer_field()
	->id( 'footer_widgets' )
	->type( 'radio-buttonset' )
	->label( __( 'Footer Widgets', 'wpbfpremium' ) )
	->defaultValue( 'disabled' )
	->choices( array(
		'disabled' => 0,
		'one'      => 1,
		'two'      => 2,
		'three'    => 3,
		'four'     => 4,
		'five'     => 5,
	) )
	->priority( 0 )
	->partialRefresh( array(
		'footer_widgets' => array(
			'selector'        => '.wpbf-widget-footer',
			'render_callback' => function () {
				return wpbf_construct_widget_footer();
			},
		),
	) )
	->addToSection( 'wpbf_widget_footer_options' );

// Width.
wpbf_customizer_field()
	->id( 'footer_widgets_width' )
	->type( 'dimension' )
	->label( __( 'Footer Width', 'wpbfpremium' ) )
	->description( __( 'Default: 1200px', 'wpbfpremium' ) )
	->priority( 1 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'footer_widgets',
			'operator' => '!=',
			'value'    => 'disabled',
		),
	) )
	->addToSection( 'wpbf_widget_footer_options' );

// Separator.
wpbf_customizer_field()
	->id( 'footer_widgets_separator' )
	->type( 'divider' )
	->priority( 1 )
	->activeCallback( [
		[
			'id'       => 'footer_widgets',
			'operator' => '!=',
			'value'    => 'disabled',
		],
	] )
	->addToSection( 'wpbf_widget_footer_options' );

// Background color.
wpbf_customizer_field()
	->id( 'footer_widgets_bg_color' )
	->type( 'color' )
	->label( __( 'Background Color', 'wpbfpremium' ) )
	->defaultValue( '#f5f5f7' )
	->transport( 'postMessage' )
	->priority( 1 )
	->properties( [
		'mode' => 'alpha',
	] )
	->activeCallback( array(
		array(
			'id'       => 'footer_widgets',
			'operator' => '!=',
			'value'    => 'disabled',
		),
	) )
	->addToSection( 'wpbf_widget_footer_options' );

// Headline color.
wpbf_customizer_field()
	->id( 'footer_widgets_headline_color' )
	->type( 'color' )
	->label( __( 'Headline Color', 'wpbfpremium' ) )
	->transport( 'postMessage' )
	->priority( 2 )
	->properties( [
		'mode' => 'alpha',
	] )
	->activeCallback( array(
		array(
			'id'       => 'footer_widgets',
			'operator' => '!=',
			'value'    => 'disabled',
		),
	) )
	->addToSection( 'wpbf_widget_footer_options' );

// Font color.
wpbf_customizer_field()
	->id( 'footer_widgets_font_color' )
	->type( 'color' )
	->label( __( 'Font Color', 'wpbfpremium' ) )
	->transport( 'postMessage' )
	->priority( 2 )
	->properties( [
		'mode' => 'alpha',
	] )
	->activeCallback( array(
		array(
			'id'       => 'footer_widgets',
			'operator' => '!=',
			'value'    => 'disabled',
		),
	) )
	->addToSection( 'wpbf_widget_footer_options' );

// Accent color.
wpbf_customizer_field()
	->id( 'footer_widgets_accent_color' )
	->type( 'color' )
	->label( __( 'Accent Color', 'wpbfpremium' ) )
	->transport( 'postMessage' )
	->priority( 3 )
	->properties( [
		'mode' => 'alpha',
	] )
	->activeCallback( array(
		array(
			'id'       => 'footer_widgets',
			'operator' => '!=',
			'value'    => 'disabled',
		),
	) )
	->addToSection( 'wpbf_widget_footer_options' );

// Accent color alt.
wpbf_customizer_field()
	->id( 'footer_widgets_accent_color_alt' )
	->type( 'color' )
	->label( __( 'Hover', 'wpbfpremium' ) )
	->priority( 4 )
	->properties( [
		'mode' => 'alpha',
	] )
	->activeCallback( array(
		array(
			'id'       => 'footer_widgets',
			'operator' => '!=',
			'value'    => 'disabled',
		),
	) )
	->addToSection( 'wpbf_widget_footer_options' );


// Font size.
wpbf_customizer_field()
	->id( 'footer_widgets_font_size' )
	->type( 'input-slider' )
	->label( __( 'Font Size', 'wpbfpremium' ) )
	->defaultValue( '14px' )
	->choices( array(
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	) )
	->priority( 11 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'footer_widgets',
			'operator' => '!=',
			'value'    => 'disabled',
		),
	) )
	->addToSection( 'wpbf_widget_footer_options' );


/* Fields - Footer */

// Sticky.
wpbf_customizer_field()
	->id( 'footer_sticky' )
	->type( 'toggle' )
	->tab( 'general' )
	->label( __( 'Sticky Footer', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( 0 )
	->activeCallback( array(
		array(
			'id'       => 'page_boxed',
			'operator' => '!=',
			'value'    => true,
		),
		array(
			'id'       => 'footer_layout',
			'operator' => '!=',
			'value'    => 'none',
		),
	) )
	->addToSection( 'wpbf_footer_options' );

// Separator.
wpbf_customizer_field()
	->id( 'footer_sticky_separator' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( 0 )
	->activeCallback( array(
		array(
			'id'       => 'page_boxed',
			'operator' => '!=',
			'value'    => true,
		),
		array(
			'id'       => 'footer_layout',
			'operator' => '!=',
			'value'    => 'none',
		),
	) )
	->addToSection( 'wpbf_footer_options' );

// Separator.
wpbf_customizer_field()
	->id( 'footer_theme_author_separator' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( 4 )
	->activeCallback( array(
		array(
			'id'       => 'footer_layout',
			'operator' => '!=',
			'value'    => 'none',
		),
	) )
	->addToSection( 'wpbf_footer_options' );

// Theme author.
wpbf_customizer_field()
	->id( 'footer_theme_author_name' )
	->type( 'text' )
	->label( __( 'Theme Author', 'wpbfpremium' ) )
	->transport( 'postMessage' )
	->priority( 4 )
	->tab( 'general' )
	->activeCallback( array(
		array(
			'id'       => 'footer_layout',
			'operator' => '!=',
			'value'    => 'none',
		),
	) )
	->partialRefresh( array(
		'footer_theme_author_name' => array(
			'container_inclusive' => true,
			'selector'            => '#footer',
			'render_callback'     => function () {
				return get_template_part( 'inc/template-parts/footer' );
			},
		),
	) )
	->addToSection( 'wpbf_footer_options' );

// Theme author URL.
wpbf_customizer_field()
	->id( 'footer_theme_author_url' )
	->type( 'text' )
	->tab( 'general' )
	->label( __( 'URL', 'wpbfpremium' ) )
	->transport( 'postMessage' )
	->priority( 4 )
	->activeCallback( array(
		array(
			'id'       => 'footer_layout',
			'operator' => '!=',
			'value'    => 'none',
		),
	) )
	->addToSection( 'wpbf_footer_options' );

// Separator.
wpbf_customizer_field()
	->id( 'footer_theme_author_url_separator' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( 4 )
	->activeCallback( array(
		array(
			'id'       => 'footer_layout',
			'operator' => '!=',
			'value'    => 'none',
		),
	) )
	->addToSection( 'wpbf_footer_options' );

// Separator.
wpbf_customizer_field()
	->id( 'footer_custom_separator' )
	->type( 'divider' )
	->tab( 'general' )
	->priority( 20 )
	->addToSection( 'wpbf_footer_options' );

// Custom footer.
wpbf_customizer_field()
	->id( 'footer_custom' )
	->type( 'code' )
	->label( __( 'Custom Footer', 'wpbfpremium' ) )
	->description( __( 'Add a saved Beaver Builder or Elementor template to the footer area of your website. <br><br><strong>Example:</strong><br>[elementor-template id="xxx"]<br>[fl_builder_insert_layout id="xxx"]', 'wpbfpremium' ) )
	->transport( 'postMessage' )
	->priority( 20 )
	->tab( 'general' )
	->properties( array(
		'language' => 'html',
	) )
	->addToSection( 'wpbf_footer_options' );
