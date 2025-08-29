<?php
/**
 * Typography customizer settings.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/* Sections */

// Adobe Fonts.
wpbf_customizer_section()
	->id( 'wpbf_typekit_options' )
	->title( __( 'Adobe Fonts', 'wpbfpremium' ) )
	->priority( 800 )
	->addToPanel( 'typo_panel' );

// Custom fonts.
wpbf_customizer_section()
	->id( 'wpbf_custom_fonts_options' )
	->title( __( 'Custom Fonts', 'wpbfpremium' ) )
	->priority( 900 )
	->addToPanel( 'typo_panel' );

/* Fields - Adobe fonts */

// Toggle.
wpbf_customizer_field()
	->id( 'enable_typekit' )
	->type( 'toggle' )
	->label( esc_html__( 'Adobe Fonts', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( 1 )
	->addToSection( 'wpbf_typekit_options' );

// Separator.
wpbf_customizer_field()
	->id( 'enable_typekit_separator' )
	->type( 'divider' )
	->priority( 1 )
	->activeCallback( array(
		array(
			'id'       => 'enable_typekit',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_typekit_options' );

// Adobe Fonts ID.
wpbf_customizer_field()
	->id( 'typekit_id' )
	->type( 'text' )
	->label( __( 'Adobe Fonts ID', 'wpbfpremium' ) )
	->defaultValue( 'iel4zhm' )
	->priority( 2 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'enable_typekit',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_typekit_options' );

// Fonts.
wpbf_customizer_field()
	->id( 'typekit_fonts' )
	->type( 'repeater' )
	->label( __( 'Adobe Fonts', 'wpbfpremium' ) )
	->defaultValue( array(
		array(
			'font_name'     => 'Sofia Pro',
			'font_css_name' => 'sofia-pro',
			'font_variants' => array( 'regular', 'italic', '700', '700italic' ),
		),
	) )
	->properties( array(
		'row_label' => array(
			'type'  => 'text',
			'value' => __( 'Adobe Font', 'wpbfpremium' ),
		),
		'fields' => array(
			'font_name'     => array(
				'type'  => 'text',
				'label' => __( 'Name', 'wpbfpremium' ),
			),
			'font_css_name' => array(
				'type'  => 'text',
				'label' => __( 'Font Family', 'wpbfpremium' ),
			),
			'font_variants' => array(
				'type'     => 'select',
				'label'    => __( 'Variants', 'wpbfpremium' ),
				'multiple' => 18,
				'choices'  => array(
					'100'       => __( '100', 'wpbfpremium' ),
					'100italic' => __( '100italic', 'wpbfpremium' ),
					'200'       => __( '200', 'wpbfpremium' ),
					'200italic' => __( '200italic', 'wpbfpremium' ),
					'300'       => __( '300', 'wpbfpremium' ),
					'300italic' => __( '300italic', 'wpbfpremium' ),
					'regular'   => __( 'regular', 'wpbfpremium' ),
					'italic'    => __( 'italic', 'wpbfpremium' ),
					'500'       => __( '500', 'wpbfpremium' ),
					'500italic' => __( '500italic', 'wpbfpremium' ),
					'600'       => __( '600', 'wpbfpremium' ),
					'600italic' => __( '600italic', 'wpbfpremium' ),
					'700'       => __( '700', 'wpbfpremium' ),
					'700italic' => __( '700italic', 'wpbfpremium' ),
					'800'       => __( '800', 'wpbfpremium' ),
					'800italic' => __( '800italic', 'wpbfpremium' ),
					'900'       => __( '900', 'wpbfpremium' ),
					'900italic' => __( '900italic', 'wpbfpremium' ),
				),
			),
		),
	) )
	->priority( 3 )
	->activeCallback( array(
		array(
			'id'       => 'enable_typekit',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_typekit_options' );

/* Fields - Custom fonts */

// Toggle.
wpbf_customizer_field()
	->id( 'enable_custom_fonts' )
	->type( 'toggle' )
	->label( esc_html__( 'Custom Fonts', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->priority( 1 )
	->addToSection( 'wpbf_custom_fonts_options' );

// Separator.
wpbf_customizer_field()
	->id( 'enable_custom_fonts_separator' )
	->type( 'divider' )
	->priority( 1 )
	->activeCallback( array(
		array(
			'id'       => 'enable_custom_fonts',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_custom_fonts_options' );

// Fonts.
wpbf_customizer_field()
	->id( 'custom_fonts' )
	->type( 'repeater' )
	->label( __( 'Custom Fonts', 'wpbfpremium' ) )
	->defaultValue( array(
		array(
			'font_name'     => 'Kitten',
			'font_css_name' => 'kitten, sans-serif',
			'font_woff'     => false,
			'font_woff2'    => false,
			'font_ttf'      => false,
			'font_svg'      => false,
			'font_eot'      => false,
		),
	) )
	->properties( array(
		'row_label' => array(
			'type'  => 'text',
			'value' => __( 'Custom Font', 'wpbfpremium' ),
		),
		'fields' => array(
			'font_name'     => array(
				'type'  => 'text',
				'label' => __( 'Name', 'wpbfpremium' ),
			),
			'font_css_name' => array(
				'type'  => 'text',
				'label' => __( 'Font Family', 'wpbfpremium' ),
			),
			'font_woff'     => array(
				'type'      => 'upload',
				'mime_type' => array(),
				'label'     => __( 'Woff', 'wpbfpremium' ),
			),
			'font_woff2'    => array(
				'type'      => 'upload',
				'mime_type' => array(),
				'label'     => __( 'Woff2', 'wpbfpremium' ),
			),
			'font_ttf'      => array(
				'type'      => 'upload',
				'mime_type' => array(),
				'label'     => __( 'TTF', 'wpbfpremium' ),
			),
			'font_svg'      => array(
				'type'      => 'upload',
				'mime_type' => array(),
				'label'     => __( 'SVG', 'wpbfpremium' ),
			),
			'font_eot'      => array(
				'type'      => 'upload',
				'mime_type' => array(),
				'label'     => __( 'EOT', 'wpbfpremium' ),
			),
		),
	) )
	->priority( 3 )
	->activeCallback( array(
		array(
			'id'       => 'enable_custom_fonts',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_custom_fonts_options' );

/* Fields - Menu font */

// Separator.
wpbf_customizer_field()
	->id( 'menu_font_family_divider' )
	->type( 'divider' )
	->priority( 3 )
	->addToSection( 'wpbf_menu_font_options' );

// Letter spacing.
wpbf_customizer_field()
	->id( 'menu_letter_spacing' )
	->type( 'slider' )
	->label( __( 'Letter Spacing', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => -2,
		'max'  => 5,
		'step' => .5,
	) )
	->priority( 3 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_menu_font_options' );

// Text transform.
wpbf_customizer_field()
	->id( 'menu_text_transform' )
	->type( 'select' )
	->label( __( 'Text transform', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->choices( array(
		'none'       => __( 'None', 'wpbfpremium' ),
		'lowercase'  => __( 'Lowercase', 'wpbfpremium' ),
		'uppercase'  => __( 'Uppercase', 'wpbfpremium' ),
		'capitalize' => __( 'Capitalize', 'wpbfpremium' ),
	) )
	->priority( 4 )
	->addToSection( 'wpbf_menu_font_options' );

/* Fields - Sub Menu font */

// Separator.
wpbf_customizer_field()
	->id( 'sub_menu_font_family_divider' )
	->type( 'divider' )
	->priority( 3 )
	->addToSection( 'wpbf_sub_menu_font_options' );

// Letter spacing.
wpbf_customizer_field()
	->id( 'sub_menu_letter_spacing' )
	->type( 'slider' )
	->label( __( 'Letter Spacing', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => -2,
		'max'  => 5,
		'step' => .5,
	) )
	->priority( 3 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_sub_menu_font_options' );

// Text transform.
wpbf_customizer_field()
	->id( 'sub_menu_text_transform' )
	->type( 'select' )
	->label( __( 'Text transform', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->choices( array(
		'none'       => __( 'None', 'wpbfpremium' ),
		'lowercase'  => __( 'Lowercase', 'wpbfpremium' ),
		'uppercase'  => __( 'Uppercase', 'wpbfpremium' ),
		'capitalize' => __( 'Capitalize', 'wpbfpremium' ),
	) )
	->priority( 4 )
	->addToSection( 'wpbf_sub_menu_font_options' );

/* Fields - Text */

// Font size.
wpbf_customizer_field()
	->id( 'page_font_size' )
	->type( 'responsive-input-slider' )
	->label( __( 'Font Size', 'wpbfpremium' ) )
	->defaultValue( array(
		'desktop' => '16px',
		'tablet'  => '',
		'mobile'  => '',
	) )
	->properties( array(
		'min'          => 0,
		'max'          => 100,
		'step'         => 1,
		'save_as_json' => true,
	) )
	->priority( 1 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_boxed_image_streched',
			'operator' => '!=',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_font_options' );

/* Fields - H1 */

// Separator.
wpbf_customizer_field()
	->id( 'page_h1_toggle_divider' )
	->type( 'divider' )
	->priority( 2 )
	->addToSection( 'wpbf_h1_options' );

// Font size.
wpbf_customizer_field()
	->id( 'page_h1_font_size' )
	->type( 'responsive-input-slider' )
	->label( __( 'Font Size', 'wpbfpremium' ) )
	->defaultValue( array(
		'desktop' => '32px',
		'tablet'  => '',
		'mobile'  => '',
	) )
	->properties( array(
		'min'          => 0,
		'max'          => 100,
		'step'         => 1,
		'save_as_json' => true,
	) )
	->priority( 2 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_boxed_image_streched',
			'operator' => '!=',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h1_options' );

// Color.
wpbf_customizer_field()
	->id( 'page_h1_font_color' )
	->type( 'color' )
	->label( __( 'Color', 'wpbfpremium' ) )
	->defaultValue( '#000' )
	->priority( 3 )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_h1_options' );

// Line height.
wpbf_customizer_field()
	->id( 'page_h1_line_height' )
	->type( 'slider' )
	->label( __( 'Line Height', 'wpbfpremium' ) )
	->description( __( 'Default: 1.2', 'wpbfpremium' ) )
	->defaultValue( 1.2 )
	->properties( array(
		'min'  => 1,
		'max'  => 5,
		'step' => .1,
	) )
	->priority( 4 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_h1_options' );

// Letter spacing.
wpbf_customizer_field()
	->id( 'page_h1_letter_spacing' )
	->type( 'slider' )
	->label( __( 'Letter Spacing', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => -2,
		'max'  => 5,
		'step' => .5,
	) )
	->priority( 5 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_h1_options' );

// Text transform.
wpbf_customizer_field()
	->id( 'page_h1_text_transform' )
	->type( 'select' )
	->label( __( 'Text transform', 'wpbfpremium' ) )
	->description( __( 'Default: none', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->choices( array(
		'none'       => __( 'None', 'wpbfpremium' ),
		'lowercase'  => __( 'Lowercase', 'wpbfpremium' ),
		'uppercase'  => __( 'Uppercase', 'wpbfpremium' ),
		'capitalize' => __( 'Capitalize', 'wpbfpremium' ),
	) )
	->priority( 6 )
	->addToSection( 'wpbf_h1_options' );

/* Fields - H2 */

// Separator.
wpbf_customizer_field()
	->id( 'page_h2_toggle_divider' )
	->type( 'divider' )
	->priority( 2 )
	->addToSection( 'wpbf_h2_options' );

// Font size.
wpbf_customizer_field()
	->id( 'page_h2_font_size' )
	->type( 'responsive-input-slider' )
	->label( __( 'Font Size', 'wpbfpremium' ) )
	->description( __( 'Default: 28px', 'wpbfpremium' ) )
	->defaultValue( array(
		'desktop' => '28px',
		'tablet'  => '',
		'mobile'  => '',
	) )
	->properties( array(
		'min'          => 0,
		'max'          => 100,
		'step'         => 1,
		'save_as_json' => true,
	) )
	->priority( 2 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_h2_options' );

// Color.
wpbf_customizer_field()
	->id( 'page_h2_font_color' )
	->type( 'color' )
	->label( __( 'Color', 'wpbfpremium' ) )
	->defaultValue( '#000' )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_h2_options' );

// Line height.
wpbf_customizer_field()
	->id( 'page_h2_line_height' )
	->type( 'slider' )
	->label( __( 'Line Height', 'wpbfpremium' ) )
	->description( __( 'Default: 1.2', 'wpbfpremium' ) )
	->defaultValue( 1.2 )
	->properties( array(
		'min'  => 1,
		'max'  => 5,
		'step' => .1,
	) )
	->priority( 4 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h2_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h2_options' );

// Letter spacing.
wpbf_customizer_field()
	->id( 'page_h2_letter_spacing' )
	->type( 'slider' )
	->label( __( 'Letter Spacing', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => -2,
		'max'  => 5,
		'step' => .5,
	) )
	->priority( 5 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h2_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h2_options' );

// Text transform.
wpbf_customizer_field()
	->id( 'page_h2_text_transform' )
	->type( 'select' )
	->label( __( 'Text transform', 'wpbfpremium' ) )
	->description( __( 'Text transform', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->choices( array(
		'none'       => __( 'None', 'wpbfpremium' ),
		'lowercase'  => __( 'Lowercase', 'wpbfpremium' ),
		'uppercase'  => __( 'Uppercase', 'wpbfpremium' ),
		'capitalize' => __( 'Capitalize', 'wpbfpremium' ),
	) )
	->priority( 6 )
	->activeCallback( array(
		array(
			'id'       => 'page_h2_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h2_options' );

/* Fields - H3 */

// Separator.
wpbf_customizer_field()
	->id( 'page_h3_toggle_divider' )
	->type( 'divider' )
	->priority( 2 )
	->addToSection( 'wpbf_h3_options' );

// Font size.
wpbf_customizer_field()
	->id( 'page_h3_font_size' )
	->type( 'responsive-input-slider' )
	->label( __( 'Font Size', 'wpbfpremium' ) )
	->description( __( 'Default: 24px', 'wpbfpremium' ) )
	->defaultValue( array(
		'desktop' => '24px',
		'tablet'  => '',
		'mobile'  => '',
	) )
	->properties( array(
		'min'          => 0,
		'max'          => 100,
		'step'         => 1,
		'save_as_json' => true,
	) )
	->priority( 2 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_h3_options' );

// Color.
wpbf_customizer_field()
	->id( 'page_h3_font_color' )
	->type( 'color' )
	->label( __( 'Color', 'wpbfpremium' ) )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_h3_options' );

// Line height.
wpbf_customizer_field()
	->id( 'page_h3_line_height' )
	->type( 'slider' )
	->label( __( 'Line Height', 'wpbfpremium' ) )
	->description( __( 'Default: 1.2', 'wpbfpremium' ) )
	->defaultValue( 1.2 )
	->properties( array(
		'min'  => 1,
		'max'  => 5,
		'step' => .1,
	) )
	->priority( 4 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h3_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h3_options' );

// Letter spacing.
wpbf_customizer_field()
	->id( 'page_h3_letter_spacing' )
	->type( 'slider' )
	->label( __( 'Letter Spacing', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => -2,
		'max'  => 5,
		'step' => .5,
	) )
	->priority( 5 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h3_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h3_options' );

// Text transform.
wpbf_customizer_field()
	->id( 'page_h3_text_transform' )
	->type( 'select' )
	->label( __( 'Text transform', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->choices( array(
		'none'       => __( 'None', 'wpbfpremium' ),
		'lowercase'  => __( 'Lowercase', 'wpbfpremium' ),
		'uppercase'  => __( 'Uppercase', 'wpbfpremium' ),
		'capitalize' => __( 'Capitalize', 'wpbfpremium' ),
	) )
	->priority( 6 )
	->activeCallback( array(
		array(
			'id'       => 'page_h3_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h3_options' );

/* Fields - H4 */

// Separator.
wpbf_customizer_field()
	->id( 'page_h4_toggle_divider' )
	->type( 'divider' )
	->priority( 2 )
	->addToSection( 'wpbf_h4_options' );

// Font size.
wpbf_customizer_field()
	->id( 'page_h4_font_size' )
	->type( 'responsive-input-slider' )
	->label( __( 'Font Size', 'wpbfpremium' ) )
	->description( __( 'Default: 20px', 'wpbfpremium' ) )
	->defaultValue( array(
		'desktop' => '20px',
		'tablet'  => '',
		'mobile'  => '',
	) )
	->properties( array(
		'min'          => 0,
		'max'          => 100,
		'step'         => 1,
		'save_as_json' => true,
	) )
	->priority( 2 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_h4_options' );

// Color.
wpbf_customizer_field()
	->id( 'page_h4_font_color' )
	->type( 'color' )
	->label( __( 'Color', 'wpbfpremium' ) )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_h4_options' );

// Line height.
wpbf_customizer_field()
	->id( 'page_h4_line_height' )
	->type( 'slider' )
	->label( __( 'Line Height', 'wpbfpremium' ) )
	->description( __( 'Default: 1.2', 'wpbfpremium' ) )
	->defaultValue( 1.2 )
	->properties( array(
		'min'  => 1,
		'max'  => 5,
		'step' => .1,
	) )
	->priority( 4 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h4_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h4_options' );

// Letter spacing.
wpbf_customizer_field()
	->id( 'page_h4_letter_spacing' )
	->type( 'slider' )
	->label( __( 'Letter Spacing', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => -2,
		'max'  => 5,
		'step' => .5,
	) )
	->priority( 5 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h4_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h4_options' );

// Text transform.
wpbf_customizer_field()
	->id( 'page_h4_text_transform' )
	->type( 'select' )
	->label( __( 'Text transform', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->choices( array(
		'none'       => __( 'None', 'wpbfpremium' ),
		'lowercase'  => __( 'Lowercase', 'wpbfpremium' ),
		'uppercase'  => __( 'Uppercase', 'wpbfpremium' ),
		'capitalize' => __( 'Capitalize', 'wpbfpremium' ),
	) )
	->priority( 6 )
	->activeCallback( array(
		array(
			'id'       => 'page_h4_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h4_options' );

/* Fields - H5 */

// Separator.
wpbf_customizer_field()
	->id( 'page_h5_toggle_divider' )
	->type( 'divider' )
	->priority( 2 )
	->addToSection( 'wpbf_h5_options' );

// Font size.
wpbf_customizer_field()
	->id( 'page_h5_font_size' )
	->type( 'responsive-input-slider' )
	->label( __( 'Font Size', 'wpbfpremium' ) )
	->description( __( 'Default: 16px', 'wpbfpremium' ) )
	->defaultValue( array(
		'desktop' => '16px',
		'tablet'  => '',
		'mobile'  => '',
	) )
	->properties( array(
		'min'          => 0,
		'max'          => 100,
		'step'         => 1,
		'save_as_json' => true,
	) )
	->priority( 2 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_h5_options' );

// Color.
wpbf_customizer_field()
	->id( 'page_h5_font_color' )
	->type( 'color' )
	->label( __( 'Color', 'wpbfpremium' ) )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_h5_options' );

// Line height.
wpbf_customizer_field()
	->id( 'page_h5_line_height' )
	->type( 'slider' )
	->label( __( 'Line Height', 'wpbfpremium' ) )
	->description( __( 'Default: 1.2', 'wpbfpremium' ) )
	->defaultValue( 1.2 )
	->properties( array(
		'min'  => 1,
		'max'  => 5,
		'step' => .1,
	) )
	->priority( 4 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h5_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h5_options' );

// Letter spacing.
wpbf_customizer_field()
	->id( 'page_h5_letter_spacing' )
	->type( 'slider' )
	->label( __( 'Letter Spacing', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => -2,
		'max'  => 5,
		'step' => .5,
	) )
	->priority( 5 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h5_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h5_options' );

// Text transform.
wpbf_customizer_field()
	->id( 'page_h5_text_transform' )
	->type( 'select' )
	->label( __( 'Text transform', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->choices( array(
		'none'       => __( 'None', 'wpbfpremium' ),
		'lowercase'  => __( 'Lowercase', 'wpbfpremium' ),
		'uppercase'  => __( 'Uppercase', 'wpbfpremium' ),
		'capitalize' => __( 'Capitalize', 'wpbfpremium' ),
	) )
	->priority( 6 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h5_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h5_options' );

/* Fields - H6 */

// Separator.
wpbf_customizer_field()
	->id( 'page_h6_toggle_divider' )
	->type( 'divider' )
	->priority( 2 )
	->addToSection( 'wpbf_h6_options' );

// Font size.
wpbf_customizer_field()
	->id( 'page_h6_font_size' )
	->type( 'responsive-input-slider' )
	->label( __( 'Font Size', 'wpbfpremium' ) )
	->description( __( 'Default: 16px', 'wpbfpremium' ) )
	->defaultValue( array(
		'desktop' => '16px',
		'tablet'  => '',
		'mobile'  => '',
	) )
	->properties( array(
		'min'          => 0,
		'max'          => 100,
		'step'         => 1,
		'save_as_json' => true,
	) )
	->priority( 2 )
	->transport( 'postMessage' )
	->addToSection( 'wpbf_h6_options' );

// Color.
wpbf_customizer_field()
	->id( 'page_h6_font_color' )
	->type( 'color' )
	->label( __( 'Color', 'wpbfpremium' ) )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( array(
		'mode' => 'alpha',
	) )
	->addToSection( 'wpbf_h6_options' );

// Line height.
wpbf_customizer_field()
	->id( 'page_h6_line_height' )
	->type( 'slider' )
	->label( __( 'Line Height', 'wpbfpremium' ) )
	->description( __( 'Default: 1.2', 'wpbfpremium' ) )
	->defaultValue( 1.2 )
	->properties( array(
		'min'  => 1,
		'max'  => 5,
		'step' => .1,
	) )
	->priority( 4 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h6_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h6_options' );

// Letter spacing.
wpbf_customizer_field()
	->id( 'page_h6_letter_spacing' )
	->type( 'slider' )
	->label( __( 'Letter Spacing', 'wpbfpremium' ) )
	->description( __( 'Default: 0', 'wpbfpremium' ) )
	->defaultValue( 0 )
	->properties( array(
		'min'  => -2,
		'max'  => 5,
		'step' => .5,
	) )
	->priority( 5 )
	->transport( 'postMessage' )
	->activeCallback( array(
		array(
			'id'       => 'page_h6_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h6_options' );

// Text transform.
wpbf_customizer_field()
	->id( 'page_h6_text_transform' )
	->type( 'select' )
	->label( __( 'Text transform', 'wpbfpremium' ) )
	->defaultValue( 'none' )
	->choices( array(
		'none'       => __( 'None', 'wpbfpremium' ),
		'lowercase'  => __( 'Lowercase', 'wpbfpremium' ),
		'uppercase'  => __( 'Uppercase', 'wpbfpremium' ),
		'capitalize' => __( 'Capitalize', 'wpbfpremium' ),
	) )
	->priority( 6 )
	->activeCallback( array(
		array(
			'id'       => 'page_h6_toggle',
			'operator' => '==',
			'value'    => true,
		),
	) )
	->addToSection( 'wpbf_h6_options' );
