<?php
/**
 * Blog Layouts customizer settings.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/* Archive Layouts - Fields */

$archives = apply_filters( 'wpbf_archives', array( 'archive' ) );

foreach ( $archives as $archive ) {

	// Headline.
	wpbf_customizer_field()
		->id( $archive . '_grid_layout_headline' )
		->type( 'headline' )
		->label( esc_html__( 'Grid Layout Settings', 'wpbfpremium' ) )
		->priority( 100 )
		->activeCallback( [
			[
				'id'       => $archive . '_layout',
				'operator' => '==',
				'value'    => 'grid',
			],
		] )
		->addToSection( 'wpbf_' . $archive . '_options' );

	// Grid.
	wpbf_customizer_field()
		->id( $archive . '_grid' )
		->type( 'responsive-number' )
		->label( __( 'Posts per Row', 'wpbfpremium' ) )
		->priority( 110 )
		->defaultValue( array(
			'desktop' => 3,
			'tablet'  => 2,
			'mobile'  => 1,
		) )
		->activeCallback( [
			[
				'id'       => $archive . '_layout',
				'operator' => '==',
				'value'    => 'grid',
			],
		] )
		->properties( [
			'save_as_json' => true,
		] )
		->addToSection( 'wpbf_' . $archive . '_options' );

	// Gap.
	wpbf_customizer_field()
		->id( $archive . '_grid_gap' )
		->type( 'select' )
		->label( __( 'Grid Gap', 'wpbfpremium' ) )
		->priority( 120 )
		->defaultValue( 'small' )
		->choices( [
			'small'    => __( 'Small', 'wpbfpremium' ),
			'medium'   => __( 'Medium', 'wpbfpremium' ),
			'large'    => __( 'Large', 'wpbfpremium' ),
			'xlarge'   => __( 'xLarge', 'wpbfpremium' ),
			'collapse' => __( 'Collapse', 'wpbfpremium' ),
		] )
		->activeCallback( [
			[
				'id'       => $archive . '_layout',
				'operator' => '==',
				'value'    => 'grid',
			],
		] )
		->addToSection( 'wpbf_' . $archive . '_options' );

	// Masonry.
	wpbf_customizer_field()
		->id( $archive . '_grid_masonry' )
		->type( 'toggle' )
		->label( __( 'Masonry Effect', 'wpbfpremium' ) )
		->defaultValue( 0 )
		->priority( 130 )
		->activeCallback( array(
			array(
				'id'       => $archive . '_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		) )
		->addToSection( 'wpbf_' . $archive . '_options' );

	// Separator.
	wpbf_customizer_field()
		->id( $archive . '_infinite_scroll_separator' )
		->type( 'divider' )
		->priority( 140 )
		->addToSection( 'wpbf_' . $archive . '_options' );

	// Infinite Scroll.
	wpbf_customizer_field()
		->id( $archive . '_infinite_scroll' )
		->type( 'toggle' )
		->label( __( 'Infinite Scroll', 'wpbfpremium' ) )
		->defaultValue( 0 )
		->priority( 150 )
		->addToSection( 'wpbf_' . $archive . '_options' );

}

/* Fields – Typography (page) */

// Bold color.
wpbf_customizer_field()
	->id( 'page_bold_color' )
	->type( 'color' )
	->label( __( 'Bold Text Color', 'wpbfpremium' ) )
	->priority( 3 )
	->transport( 'postMessage' )
	->properties( [
		'mode' => 'alpha',
	] )
	->addToSection( 'wpbf_font_options' );

// Line height.
wpbf_customizer_field()
	->id( 'page_line_height' )
	->type( 'slider' )
	->label( __( 'Line Height', 'wpbfpremium' ) )
	->priority( 4 )
	->defaultValue( 1.7 )
	->transport( 'postMessage' )
	->properties( [
		'min'  => 1,
		'max'  => 5,
		'step' => 0.1,
	] )
	->addToSection( 'wpbf_font_options' );

/* Post Layouts - Fields */

$singles = apply_filters( 'wpbf_singles', array( 'single' ) );

foreach ( $singles as $single ) {

	$priority = 200;

	// Headline.
	wpbf_customizer_field()
		->id( $single . '_related_posts' )
		->type( 'headline-toggle' )
		->label( __( 'Related Posts', 'wpbfpremium' ) )
		->defaultValue( 0 )
		->priority( $priority++ )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Headline.
	wpbf_customizer_field()
		->id( $single . '_related_posts_headline' )
		->type( 'text' )
		->label( __( 'Headline', 'wpbfpremium' ) )
		->defaultValue( __( 'Related Posts', 'wpbfpremium' ) )
		->priority( $priority++ )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Layout.
	wpbf_customizer_field()
		->id( $single . '_related_posts_layout' )
		->type( 'select' )
		->label( __( 'Layout', 'wpbfpremium' ) )
		->defaultValue( 'grid' )
		->priority( $priority++ )
		->choices( [
			'grid' => __( 'Grid', 'wpbfpremium' ),
			'list' => __( 'List', 'wpbfpremium' ),
		] )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Separator.
	wpbf_customizer_field()
		->id( $single . '_related_posts_grid_separator' )
		->type( 'divider' )
		->priority( $priority++ )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
			[
				'id'       => $single . '_related_posts_layout',
				'operator' => '==',
				'value'    => 'grid',
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Sortable.
	wpbf_customizer_field()
		->id( $single . '_related_posts_grid_sortable' )
		->type( 'sortable' )
		->label( __( 'Content', 'wpbfpremium' ) )
		->defaultValue( [
			'featured',
			'meta',
			'title',
		] )
		->priority( $priority++ )
		->choices( [
			'featured' => __( 'Featured Image', 'wpbfpremium' ),
			'meta'     => __( 'Meta Data', 'wpbfpremium' ),
			'title'    => __( 'Title', 'wpbfpremium' ),
		] )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
			[
				'id'       => $single . '_related_posts_layout',
				'operator' => '==',
				'value'    => 'grid',
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Separator.
	wpbf_customizer_field()
		->id( $single . '_related_posts_grid_separator_2' )
		->type( 'divider' )
		->priority( $priority++ )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
			[
				'id'       => $single . '_related_posts_layout',
				'operator' => '==',
				'value'    => 'grid',
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Posts per row.
	wpbf_customizer_field()
		->id( $single . '_related_posts_grid_columns' )
		->type( 'responsive-number' )
		->label( __( 'Posts per Row', 'wpbfpremium' ) )
		->defaultValue( array(
			'desktop' => 3,
			'tablet'  => 2,
			'mobile'  => 1,
		) )
		->priority( $priority++ )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
			[
				'id'       => $single . '_related_posts_layout',
				'operator' => '==',
				'value'    => 'grid',
			],
		] )
		->properties( array(
			'save_as_json' => true,
		) )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Gap.
	wpbf_customizer_field()
		->id( $single . '_related_posts_grid_gap' )
		->type( 'select' )
		->label( __( 'Grid Gap', 'wpbfpremium' ) )
		->defaultValue( 'medium' )
		->priority( $priority++ )
		->choices( [
			'small'    => __( 'Small', 'wpbfpremium' ),
			'medium'   => __( 'Medium', 'wpbfpremium' ),
			'large'    => __( 'Large', 'wpbfpremium' ),
			'xlarge'   => __( 'xLarge', 'wpbfpremium' ),
			'collapse' => __( 'Collapse', 'wpbfpremium' ),
		] )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
			[
				'id'       => $single . '_related_posts_layout',
				'operator' => '==',
				'value'    => 'grid',
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Display Conditions headline.
	wpbf_customizer_field()
		->id( $single . '_related_posts_display_conditions_headline' )
		->type( 'headline' )
		->label( esc_html__( 'Display Conditions', 'wpbfpremium' ) )
		->priority( $priority++ )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Number of posts.
	wpbf_customizer_field()
		->id( $single . '_related_posts_showposts' )
		->type( 'number' )
		->label( __( 'Number of Posts', 'wpbfpremium' ) )
		->defaultValue( 3 )
		->priority( $priority++ )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Order by.
	wpbf_customizer_field()
		->id( $single . '_related_posts_orderby' )
		->type( 'select' )
		->label( __( 'Order by', 'wpbfpremium' ) )
		->defaultValue( 'date' )
		->priority( $priority++ )
		->choices( [
			'date'     => __( 'Date', 'wpbfpremium' ),
			'modified' => __( 'Last Modified', 'wpbfpremium' ),
			'title'    => __( 'Title', 'wpbfpremium' ),
			'rand'     => __( 'Random', 'wpbfpremium' ),
		] )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Order.
	wpbf_customizer_field()
		->id( $single . '_related_posts_order' )
		->type( 'select' )
		->label( __( 'Order', 'wpbfpremium' ) )
		->defaultValue( 'DESC' )
		->priority( $priority++ )
		->choices( [
			'DESC' => __( 'Descending', 'wpbfpremium' ),
			'ASC'  => __( 'Ascending', 'wpbfpremium' ),
		] )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Author.
	wpbf_customizer_field()
		->id( $single . '_related_posts_authors' )
		->type( 'text' )
		->label( __( 'Author', 'wpbfpremium' ) )
		->description( __( "ID or comma separated list of ID's", 'wpbfpremium' ) )
		->priority( $priority++ )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Category.
	wpbf_customizer_field()
		->id( $single . '_related_posts_categories' )
		->type( 'text' )
		->label( __( 'Category', 'wpbfpremium' ) )
		->description( __( "ID or comma separated list of ID's", 'wpbfpremium' ) )
		->priority( $priority++ )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

	// Post Type.
	wpbf_customizer_field()
		->id( $single . '_related_posts_post_types' )
		->type( 'text' )
		->label( __( 'Post Types', 'wpbfpremium' ) )
		->description( __( 'Post type or comma separated list of post types.', 'wpbfpremium' ) )
		->priority( $priority++ )
		->activeCallback( [
			[
				'id'       => $single . '_related_posts',
				'operator' => '==',
				'value'    => true,
			],
		] )
		->addToSection( 'wpbf_' . $single . '_options' );

}
