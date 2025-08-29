<?php
/**
 * Blog Layouts.
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Premium Add-On blog & custom post type archives.
 *
 * @param boolean $get_cpts If we want to get post types instead of archives.
 *
 * @return array The archives/post types.
 */
function wpbf_get_blog_layout_settings( $get_cpts = false ) {

	$archives = array();

	$wpbf_settings = get_option( 'wpbf_settings' );

	if ( isset( $wpbf_settings['wpbf_blog_layouts'] ) ) {

		$saved_archives = $wpbf_settings['wpbf_blog_layouts'];

		foreach ( $saved_archives as $saved_archive ) {

			// Turn available post type archives into custom post types.
			if ( $get_cpts && strpos( $saved_archive, '-' ) ) {
				$saved_archive = substr( $saved_archive, 0, strpos( $saved_archive, '-' ) );
			}

			$archives[] = $saved_archive;
		}

	}

	return $archives;

};

/**
 * Extend existing blog layouts.
 *
 * Add grid layout.
 *
 * @param array $blog_layouts The blog layouts.
 *
 * @return array The updated blog layouts.
 */
add_filter( 'wpbf_blog_layouts', function ( $blog_layouts ) {

	$blog_layouts['grid'] = __( 'Grid', 'wpbfpremium' );

	return $blog_layouts;

} );

/**
 * Add archives.
 *
 * Add archives to wpbf_archives based on global settings.
 *
 * @param array $archives The archives.
 *
 * @return array The updated archives.
 */
add_filter( 'wpbf_archives', function ( $archives ) {

	// Add Premium Archives to Archives array
	$archives = array_merge( $archives, wpbf_get_blog_layout_settings() );

	return $archives;

} );

/**
 * Sidebar layout.
 *
 * @param string $sidebar The sidebar position.
 *
 * @return string The updated sidebar position.
 */
add_filter( 'wpbf_sidebar_layout', function ( $sidebar ) {

	$saved_archives = wpbf_get_blog_layout_settings( $get_cpts = true );

	foreach ( $saved_archives as $saved_archive ) {

		switch ( $saved_archive ) {

		case 'blog':

			if ( is_home() ) {
				$blog_sidebar_position = get_theme_mod( 'blog_sidebar_layout', 'global' );
				$sidebar = $blog_sidebar_position !== 'global' ? $blog_sidebar_position : $sidebar;
			}

			break;

		case 'search':

			if ( is_search() ) {
				$search_sidebar_position = get_theme_mod( 'search_sidebar_layout', 'global' );
				$sidebar = $search_sidebar_position !== 'global' ? $search_sidebar_position : $sidebar;
			}

			break;

		case 'tag':

			if ( is_tag() ) {
				$tag_sidebar_position = get_theme_mod( 'tag_sidebar_layout', 'global' );
				$sidebar = $tag_sidebar_position !== 'global' ? $tag_sidebar_position : $sidebar;
			}

			break;

		case 'category':

			if ( is_category() ) {
				$category_sidebar_position = get_theme_mod( 'category_sidebar_layout', 'global' );
				$sidebar = $category_sidebar_position !== 'global' ? $category_sidebar_position : $sidebar;
			}

			break;

		case 'author':

			if ( is_author() ) {
				$author_sidebar_position = get_theme_mod( 'author_sidebar_layout', 'global' );
				$sidebar = $author_sidebar_position !== 'global' ? $author_sidebar_position : $sidebar;
			}

			break;

		case 'date':

			if ( is_date() ) {
				$date_sidebar_position = get_theme_mod( 'date_sidebar_layout', 'global' );
				$sidebar = $date_sidebar_position !== 'global' ? $date_sidebar_position : $sidebar;
			}

			break;

		default:

			if ( is_post_type_archive( $saved_archive ) ) {
				$cpt_sidebar_layout = get_theme_mod( $saved_archive . '-archive_sidebar_layout', 'global' );
				$sidebar = $cpt_sidebar_layout !== 'global' ? $cpt_sidebar_layout : $sidebar;
			}

			// Apply to related taxonomies.
			$taxonomies = get_object_taxonomies( $saved_archive, 'names' );

			if ( ! empty( $taxonomies ) ) {
				foreach ( $taxonomies as $taxonomy ) {
					if ( is_tax( $taxonomy ) ) {
						$cpt_sidebar_layout = get_theme_mod( $saved_archive . '-archive_sidebar_layout', 'global' );
						$sidebar = $cpt_sidebar_layout !== 'global' ? $cpt_sidebar_layout : $sidebar;
					}
				}
			}

			break;

		}

	}

	return $sidebar;

} );

/**
 * Blog & custom post type layout.
 *
 * @param array $blog_layout The blog layout.
 *
 * @return array The updated blog layout.
 */
add_filter( 'wpbf_blog_layout', function ( $blog_layout ) {

	$saved_archives = wpbf_get_blog_layout_settings( $get_cpts = true );

	foreach ( $saved_archives as $saved_archive ) {

		switch ( $saved_archive ) {

		case 'blog':

			if ( is_home() ) {

				$template_parts_header  = get_theme_mod( 'blog_sortable_header', array( 'title', 'meta', 'featured' ) );
				$template_parts_content = get_theme_mod( 'blog_sortable_content', array( 'excerpt' ) );
				$template_parts_footer  = get_theme_mod( 'blog_sortable_footer', array( 'readmore', 'categories' ) );
				$blog_layout            = get_theme_mod( 'blog_layout', 'default' );
				$style                  = get_theme_mod( 'blog_post_style', 'plain' );
				$stretched              = get_theme_mod( 'blog_boxed_image_streched', false );

				if ( $blog_layout !== 'beside' && $style == 'boxed' && $stretched ) {
					$style .= ' stretched';
				}

				$blog_layout = array(
					'blog_layout'            => $blog_layout,
					'template_parts_header'  => $template_parts_header,
					'template_parts_content' => $template_parts_content,
					'template_parts_footer'  => $template_parts_footer,
					'style'                  => $style,
				);

			}

			break;

		case 'search':

			if ( is_search() ) {

				$template_parts_header  = get_theme_mod( 'search_sortable_header', array( 'title', 'meta', 'featured' ) );
				$template_parts_content = get_theme_mod( 'search_sortable_content', array( 'excerpt' ) );
				$template_parts_footer  = get_theme_mod( 'search_sortable_footer', array( 'readmore', 'categories' ) );
				$blog_layout            = get_theme_mod( 'search_layout', 'default' );
				$style                  = get_theme_mod( 'search_post_style', 'plain' );
				$stretched              = get_theme_mod( 'search_boxed_image_streched', false );

				if ( $blog_layout !== 'beside' && $style == 'boxed' && $stretched ) {
					$style .= ' stretched';
				}

				$blog_layout = array(
					'blog_layout'            => $blog_layout,
					'template_parts_header'  => $template_parts_header,
					'template_parts_content' => $template_parts_content,
					'template_parts_footer'  => $template_parts_footer,
					'style'                  => $style,
				);

			}

			break;

		case 'category':

			if ( is_category() ) {

				$template_parts_header  = get_theme_mod( 'category_sortable_header', array( 'title', 'meta', 'featured' ) );
				$template_parts_content = get_theme_mod( 'category_sortable_content', array( 'excerpt' ) );
				$template_parts_footer  = get_theme_mod( 'category_sortable_footer', array( 'readmore', 'categories' ) );
				$blog_layout            = get_theme_mod( 'category_layout', 'default' );
				$style                  = get_theme_mod( 'category_post_style', 'plain' );
				$stretched              = get_theme_mod( 'category_boxed_image_streched', false );

				if ( $blog_layout !== 'beside' && $style == 'boxed' && $stretched ) {
					$style .= ' stretched';
				}

				$blog_layout = array(
					'blog_layout'            => $blog_layout,
					'template_parts_header'  => $template_parts_header,
					'template_parts_content' => $template_parts_content,
					'template_parts_footer'  => $template_parts_footer,
					'style'                  => $style,
				);

			}

			break;

		case 'tag':

			if ( is_tag() ) {

				$template_parts_header  = get_theme_mod( 'tag_sortable_header', array( 'title', 'meta', 'featured' ) );
				$template_parts_content = get_theme_mod( 'tag_sortable_content', array( 'excerpt' ) );
				$template_parts_footer  = get_theme_mod( 'tag_sortable_footer', array( 'readmore', 'categories' ) );
				$blog_layout            = get_theme_mod( 'tag_layout', 'default' );
				$style                  = get_theme_mod( 'tag_post_style', 'plain' );
				$stretched              = get_theme_mod( 'tag_boxed_image_streched', false );

				if ( $blog_layout !== 'beside' && $style == 'boxed' && $stretched ) {
					$style .= ' stretched';
				}

				$blog_layout = array(
					'blog_layout'            => $blog_layout,
					'template_parts_header'  => $template_parts_header,
					'template_parts_content' => $template_parts_content,
					'template_parts_footer'  => $template_parts_footer,
					'style'                  => $style,
				);

			}

			break;

		case 'author':

			if ( is_author() ) {

				$template_parts_header  = get_theme_mod( 'author_sortable_header', array( 'title', 'meta', 'featured' ) );
				$template_parts_content = get_theme_mod( 'author_sortable_content', array( 'excerpt' ) );
				$template_parts_footer  = get_theme_mod( 'author_sortable_footer', array( 'readmore', 'categories' ) );
				$blog_layout            = get_theme_mod( 'author_layout', 'default' );
				$style                  = get_theme_mod( 'author_post_style', 'plain' );
				$stretched              = get_theme_mod( 'author_boxed_image_streched', false );

				if ( $blog_layout !== 'beside' && $style == 'boxed' && $stretched ) {
					$style .= ' stretched';
				}

				$blog_layout = array(
					'blog_layout'            => $blog_layout,
					'template_parts_header'  => $template_parts_header,
					'template_parts_content' => $template_parts_content,
					'template_parts_footer'  => $template_parts_footer,
					'style'                  => $style,
				);

			}

			break;

		case 'date':

			if ( is_date() ) {

				$template_parts_header  = get_theme_mod( 'date_sortable_header', array( 'title', 'meta', 'featured' ) );
				$template_parts_content = get_theme_mod( 'date_sortable_content', array( 'excerpt' ) );
				$template_parts_footer  = get_theme_mod( 'date_sortable_footer', array( 'readmore', 'categories' ) );
				$blog_layout            = get_theme_mod( 'date_layout', 'default' );
				$style                  = get_theme_mod( 'date_post_style', 'plain' );
				$stretched              = get_theme_mod( 'date_boxed_image_streched', false );

				if ( $blog_layout !== 'beside' && $style == 'boxed' && $stretched ) {
					$style .= ' stretched';
				}

				$blog_layout = array(
					'blog_layout'            => $blog_layout,
					'template_parts_header'  => $template_parts_header,
					'template_parts_content' => $template_parts_content,
					'template_parts_footer'  => $template_parts_footer,
					'style'                  => $style,
				);

			}

			break;

		default:

			if ( is_post_type_archive( $saved_archive ) ) {

				$template_parts_header  = get_theme_mod( $saved_archive . '-archive_sortable_header', array( 'title', 'meta', 'featured' ) );
				$template_parts_content = get_theme_mod( $saved_archive . '-archive_sortable_content', array( 'excerpt' ) );
				$template_parts_footer  = get_theme_mod( $saved_archive . '-archive_sortable_footer', array( 'readmore', 'categories' ) );
				$blog_layout            = get_theme_mod( $saved_archive . '-archive_layout', 'default' );
				$style                  = get_theme_mod( $saved_archive . '-archive_post_style', 'plain' );
				$stretched              = get_theme_mod( $saved_archive . '-archive_boxed_image_streched', false );

				if ( $blog_layout !== 'beside' && $style == 'boxed' && $stretched ) {
					$style .= ' stretched';
				}

				$blog_layout = array(
					'blog_layout'            => $blog_layout,
					'template_parts_header'  => $template_parts_header,
					'template_parts_content' => $template_parts_content,
					'template_parts_footer'  => $template_parts_footer,
					'style'                  => $style,
				);

			}

			// Apply to related taxonomies.
			$taxonomies = get_object_taxonomies( $saved_archive, 'names' );

			if ( ! empty( $taxonomies ) ) {

				foreach ( $taxonomies as $taxonomy ) {

					if ( is_tax( $taxonomy ) ) {

						$template_parts_header  = get_theme_mod( $saved_archive . '-archive_sortable_header', array( 'title', 'meta', 'featured' ) );
						$template_parts_content = get_theme_mod( $saved_archive . '-archive_sortable_content', array( 'excerpt' ) );
						$template_parts_footer  = get_theme_mod( $saved_archive . '-archive_sortable_footer', array( 'readmore', 'categories' ) );
						$blog_layout            = get_theme_mod( $saved_archive . '-archive_layout', 'default' );
						$style                  = get_theme_mod( $saved_archive . '-archive_post_style', 'plain' );
						$stretched              = get_theme_mod( $saved_archive . '-archive_boxed_image_streched', false );

						if ( $blog_layout !== 'beside' && $style == 'boxed' && $stretched ) {
							$style .= ' stretched';
						}

						$blog_layout = array(
							'blog_layout'            => $blog_layout,
							'template_parts_header'  => $template_parts_header,
							'template_parts_content' => $template_parts_content,
							'template_parts_footer'  => $template_parts_footer,
							'style'                  => $style,
						);

					}

				}

			}

			break;

		}

	}

	return $blog_layout;

} );

/**
 * Archive titles.
 *
 * @param string $title The archive title.
 *
 * @return string The updated archive title.
 */
function wpbf_premium_archive_title( $title ) {

	$saved_archives = wpbf_get_blog_layout_settings( $get_cpts = true );

	foreach ( $saved_archives as $saved_archive ) {

		switch ( $saved_archive ) {

		case 'category':

			if ( is_category() ) {

				$archive_headline = get_theme_mod( 'category_headline' );

				if ( 'hide_prefix' === $archive_headline ) {
					$title = single_cat_title( '', false );
				} elseif ( 'hide' === $archive_headline ) {
					$title = false;
				} else {
					$title = sprintf( __( 'Category: %s' ), single_cat_title( '', false ) );
				}

			}

			break;

		case 'tag':

			if ( is_tag() ) {

				$archive_headline = get_theme_mod( 'tag_headline' );

				if ( 'hide_prefix' === $archive_headline ) {
					$title = single_tag_title( '', false );
				} elseif ( 'hide' === $archive_headline ) {
					$title = false;
				} else {
					$title = sprintf( __( 'Tag: %s' ), single_tag_title( '', false ) );
				}

			}

			break;

		case 'date':

			if ( is_date() ) {

				$archive_headline = get_theme_mod( 'date_headline' );

				$date   = get_the_date( 'F Y' );
				$period = sprintf( __( 'Month: %s' ), $date );

				if ( is_year() ) {
					$date   = get_the_date( 'Y' );
					$period = sprintf( __( 'Year: %s' ), $date );
				}

				if ( is_day() ) {
					$date   = get_the_date( 'F j, Y' );
					$period = sprintf( __( 'Day: %s' ), $date );
				}

				if ( 'hide_prefix' === $archive_headline ) {
					$title = $date;
				} elseif ( 'hide' === $archive_headline ) {
					$title = false;
				} else {
					$title = $period;
				}

			}

			break;

		default:

			$archive_headline = get_theme_mod( $saved_archive . '-archive_headline' );

			if ( is_post_type_archive( $saved_archive ) ) {

				if ( 'hide_prefix' === $archive_headline ) {
					$title = post_type_archive_title( '', false );
				} elseif ( 'hide' === $archive_headline ) {
					$title = false;
				} else {
					$title = sprintf( __( 'Archives: %s' ), post_type_archive_title( '', false ) );
				}

			}

			// Apply to related taxonomies.
			$taxonomies = get_object_taxonomies( $saved_archive, 'names' );

			if ( ! empty( $taxonomies ) ) {

				foreach ( $taxonomies as $taxonomy ) {
					if ( is_tax( $taxonomy ) ) {
						if ( 'hide_prefix' === $archive_headline ) {
							$title = single_term_title( '', false );
						} elseif ( 'hide' === $archive_headline ) {
							$title = false;
						} else {
							$tax   = get_taxonomy( get_queried_object()->taxonomy );
							$title = sprintf( __( '%1$s: %2$s' ), $tax->labels->singular_name, single_term_title( '', false ) );
						}
					}
				}

			}

			break;

		}

	}

	return $title;

};
add_filter( 'get_the_archive_title', 'wpbf_premium_archive_title', 20 );

/**
 * Grid layout open.
 */
function wpbf_blog_layout_grid() {

	$blog_layout        = get_theme_mod( 'archive_layout', 'default' );
	$grid               = json_decode( get_theme_mod( 'archive_grid' ), true );
	$mobile_breakpoint  = wpbf_get_theme_mod_value( $grid, 'mobile', 1, true );
	$tablet_breakpoint  = wpbf_get_theme_mod_value( $grid, 'tablet', 2, true );
	$desktop_breakpoint = wpbf_get_theme_mod_value( $grid, 'desktop', 3, true );
	$grid_gap           = get_theme_mod( 'archive_grid_gap', 'small' );
	$masonry            = get_theme_mod( 'archive_grid_masonry' ) ? ' wpbf-post-grid-masonry' : false;

	$grid = 'grid' === $blog_layout ? '<div class="wpbf-grid wpbf-post-grid' . $masonry . ' wpbf-grid-' . esc_attr( $grid_gap ) . ' wpbf-grid-1-' . esc_attr( $mobile_breakpoint ) . ' wpbf-grid-small-1-' . esc_attr( $tablet_breakpoint ) . ' wpbf-grid-large-1-' . esc_attr( $desktop_breakpoint ) . '">' : false;

	echo apply_filters( 'wpbf_blog_layout_grid', $grid );

}
add_action( 'wpbf_before_loop', 'wpbf_blog_layout_grid' );

/**
 * Grid layout close.
 */
function wpbf_blog_layout_grid_close() {

	$blog_layout = get_theme_mod( 'archive_layout', 'default' );

	$grid = 'grid' === $blog_layout ? '</div>' : false;

	echo apply_filters( 'wpbf_blog_layout_grid_close', $grid );

}
add_action( 'wpbf_after_loop', 'wpbf_blog_layout_grid_close' );

/**
 * Filter grid layout open.
 *
 * @param string $grid The grid layout.
 *
 * @return string The updated grid layout.
 */
add_filter( 'wpbf_blog_layout_grid', function ( $grid ) {

	$saved_archives = wpbf_get_blog_layout_settings( $get_cpts = true );

	foreach ( $saved_archives as $saved_archive ) {

		switch ( $saved_archive ) {

		case 'blog':

			if ( is_home() ) {

				$blog_layout        = get_theme_mod( 'blog_layout', 'default' );
				$grid               = json_decode( get_theme_mod( 'blog_grid' ), true );
				$mobile_breakpoint  = wpbf_get_theme_mod_value( $grid, 'mobile', 1, true );
				$tablet_breakpoint  = wpbf_get_theme_mod_value( $grid, 'tablet', 2, true );
				$desktop_breakpoint = wpbf_get_theme_mod_value( $grid, 'desktop', 3, true );
				$grid_gap           = get_theme_mod( 'blog_grid_gap', 'small' );
				$masonry            = get_theme_mod( 'blog_grid_masonry' ) ? ' wpbf-post-grid-masonry' : false;

				$grid = 'grid' === $blog_layout ? '<div class="wpbf-grid wpbf-post-grid' . $masonry . ' wpbf-grid-' . esc_attr( $grid_gap ) . ' wpbf-grid-1-' . esc_attr( $mobile_breakpoint ) . ' wpbf-grid-small-1-' . esc_attr( $tablet_breakpoint ) . ' wpbf-grid-large-1-' . esc_attr( $desktop_breakpoint ) . '">' : false;

			}

			break;

		case 'search':

			if ( is_search() ) {

				$blog_layout        = get_theme_mod( 'search_layout', 'default' );
				$grid               = json_decode( get_theme_mod( 'search_grid' ), true );
				$mobile_breakpoint  = wpbf_get_theme_mod_value( $grid, 'mobile', 1, true );
				$tablet_breakpoint  = wpbf_get_theme_mod_value( $grid, 'tablet', 2, true );
				$desktop_breakpoint = wpbf_get_theme_mod_value( $grid, 'desktop', 3, true );
				$grid_gap           = get_theme_mod( 'search_grid_gap', 'small' );
				$masonry            = get_theme_mod( 'search_grid_masonry' ) ? ' wpbf-post-grid-masonry' : false;

				$grid = 'grid' === $blog_layout ? '<div class="wpbf-grid wpbf-post-grid' . $masonry . ' wpbf-grid-' . esc_attr( $grid_gap ) . ' wpbf-grid-1-' . esc_attr( $mobile_breakpoint ) . ' wpbf-grid-small-1-' . esc_attr( $tablet_breakpoint ) . ' wpbf-grid-large-1-' . esc_attr( $desktop_breakpoint ) . '">' : false;

			}

			break;

		case 'category':

			if ( is_category() ) {

				$blog_layout        = get_theme_mod( 'category_layout', 'default' );
				$grid               = json_decode( get_theme_mod( 'category_grid' ), true );
				$mobile_breakpoint  = wpbf_get_theme_mod_value( $grid, 'mobile', 1, true );
				$tablet_breakpoint  = wpbf_get_theme_mod_value( $grid, 'tablet', 2, true );
				$desktop_breakpoint = wpbf_get_theme_mod_value( $grid, 'desktop', 3, true );
				$grid_gap           = get_theme_mod( 'category_grid_gap', 'small' );
				$masonry            = get_theme_mod( 'category_grid_masonry' ) ? ' wpbf-post-grid-masonry' : false;

				$grid = 'grid' === $blog_layout ? '<div class="wpbf-grid wpbf-post-grid' . $masonry . ' wpbf-grid-' . esc_attr( $grid_gap ) . ' wpbf-grid-1-' . esc_attr( $mobile_breakpoint ) . ' wpbf-grid-small-1-' . esc_attr( $tablet_breakpoint ) . ' wpbf-grid-large-1-' . esc_attr( $desktop_breakpoint ) . '">' : false;

			}

			break;

		case 'tag':

			if ( is_tag() ) {

				$blog_layout        = get_theme_mod( 'tag_layout', 'default' );
				$grid               = json_decode( get_theme_mod( 'tag_grid' ), true );
				$mobile_breakpoint  = wpbf_get_theme_mod_value( $grid, 'mobile', 1, true );
				$tablet_breakpoint  = wpbf_get_theme_mod_value( $grid, 'tablet', 2, true );
				$desktop_breakpoint = wpbf_get_theme_mod_value( $grid, 'desktop', 3, true );
				$grid_gap           = get_theme_mod( 'tag_grid_gap', 'small' );
				$masonry            = get_theme_mod( 'tag_grid_masonry' ) ? ' wpbf-post-grid-masonry' : false;

				$grid = 'grid' === $blog_layout ? '<div class="wpbf-grid wpbf-post-grid' . $masonry . ' wpbf-grid-' . esc_attr( $grid_gap ) . ' wpbf-grid-1-' . esc_attr( $mobile_breakpoint ) . ' wpbf-grid-small-1-' . esc_attr( $tablet_breakpoint ) . ' wpbf-grid-large-1-' . esc_attr( $desktop_breakpoint ) . '">' : false;

			}

			break;

		case 'author':

			if ( is_author() ) {

				$blog_layout        = get_theme_mod( 'author_layout', 'default' );
				$grid               = json_decode( get_theme_mod( 'author_grid' ), true );
				$mobile_breakpoint  = wpbf_get_theme_mod_value( $grid, 'mobile', 1, true );
				$tablet_breakpoint  = wpbf_get_theme_mod_value( $grid, 'tablet', 2, true );
				$desktop_breakpoint = wpbf_get_theme_mod_value( $grid, 'desktop', 3, true );
				$grid_gap           = get_theme_mod( 'author_grid_gap', 'small' );
				$masonry            = get_theme_mod( 'author_grid_masonry' ) ? ' wpbf-post-grid-masonry' : false;

				$grid = 'grid' === $blog_layout ? '<div class="wpbf-grid wpbf-post-grid' . $masonry . ' wpbf-grid-' . esc_attr( $grid_gap ) . ' wpbf-grid-1-' . esc_attr( $mobile_breakpoint ) . ' wpbf-grid-small-1-' . esc_attr( $tablet_breakpoint ) . ' wpbf-grid-large-1-' . esc_attr( $desktop_breakpoint ) . '">' : false;

			}

			break;

		case 'date':

			if ( is_date() ) {

				$blog_layout        = get_theme_mod( 'date_layout', 'default' );
				$grid               = json_decode( get_theme_mod( 'date_grid' ), true );
				$mobile_breakpoint  = wpbf_get_theme_mod_value( $grid, 'mobile', 1, true );
				$tablet_breakpoint  = wpbf_get_theme_mod_value( $grid, 'tablet', 2, true );
				$desktop_breakpoint = wpbf_get_theme_mod_value( $grid, 'desktop', 3, true );
				$grid_gap           = get_theme_mod( 'date_grid_gap', 'small' );
				$masonry            = get_theme_mod( 'date_grid_masonry' ) ? ' wpbf-post-grid-masonry' : false;

				$grid = 'grid' === $blog_layout ? '<div class="wpbf-grid wpbf-post-grid' . $masonry . ' wpbf-grid-' . esc_attr( $grid_gap ) . ' wpbf-grid-1-' . esc_attr( $mobile_breakpoint ) . ' wpbf-grid-small-1-' . esc_attr( $tablet_breakpoint ) . ' wpbf-grid-large-1-' . esc_attr( $desktop_breakpoint ) . '">' : false;

			}

			break;

		default:

			if ( is_post_type_archive( $saved_archive ) ) {

				$blog_layout        = get_theme_mod( $saved_archive . '-archive_layout', 'default' );
				$grid               = json_decode( get_theme_mod( $saved_archive . '-archive_grid' ), true );
				$mobile_breakpoint  = wpbf_get_theme_mod_value( $grid, 'mobile', 1, true );
				$tablet_breakpoint  = wpbf_get_theme_mod_value( $grid, 'tablet', 2, true );
				$desktop_breakpoint = wpbf_get_theme_mod_value( $grid, 'desktop', 3, true );
				$grid_gap           = get_theme_mod( $saved_archive . '-archive_grid_gap', 'small' );
				$masonry            = get_theme_mod( $saved_archive . '-archive_grid_masonry' ) ? ' wpbf-post-grid-masonry' : false;

				$grid = 'grid' === $blog_layout ? '<div class="wpbf-grid wpbf-post-grid' . $masonry . ' wpbf-grid-' . esc_attr( $grid_gap ) . ' wpbf-grid-1-' . esc_attr( $mobile_breakpoint ) . ' wpbf-grid-small-1-' . esc_attr( $tablet_breakpoint ) . ' wpbf-grid-large-1-' . esc_attr( $desktop_breakpoint ) . '">' : false;

			}

			// Apply to related taxonomies.
			$taxonomies = get_object_taxonomies( $saved_archive, 'names' );

			if ( ! empty( $taxonomies ) ) {

				foreach ( $taxonomies as $taxonomy ) {

					if ( is_tax( $taxonomy ) ) {

						$blog_layout        = get_theme_mod( $saved_archive . '-archive_layout', 'default' );
						$grid               = json_decode( get_theme_mod( $saved_archive . '-archive_grid' ), true );
						$mobile_breakpoint  = wpbf_get_theme_mod_value( $grid, 'mobile', 1, true );
						$tablet_breakpoint  = wpbf_get_theme_mod_value( $grid, 'tablet', 2, true );
						$desktop_breakpoint = wpbf_get_theme_mod_value( $grid, 'desktop', 3, true );
						$grid_gap           = get_theme_mod( $saved_archive . '-archive_grid_gap', 'small' );
						$masonry            = get_theme_mod( $saved_archive . '-archive_grid_masonry' ) ? ' wpbf-post-grid-masonry' : false;

						$grid = 'grid' === $blog_layout ? '<div class="wpbf-grid wpbf-post-grid' . $masonry . ' wpbf-grid-' . esc_attr( $grid_gap ) . ' wpbf-grid-1-' . esc_attr( $mobile_breakpoint ) . ' wpbf-grid-small-1-' . esc_attr( $tablet_breakpoint ) . ' wpbf-grid-large-1-' . esc_attr( $desktop_breakpoint ) . '">' : false;

					}

				}

			}

			break;

		}

	}

	return $grid;

} );

/**
 * Filter grid layout close.
 */
add_filter( 'wpbf_blog_layout_grid_close', function ( $grid ) {

	$saved_archives = wpbf_get_blog_layout_settings( $get_cpts = true );

	foreach ( $saved_archives as $saved_archive ) {

		switch ( $saved_archive ) {

		case 'blog':

			if ( is_home() ) {

				$blog_layout = get_theme_mod( 'blog_layout', 'default' );
				$grid = 'grid' === $blog_layout ? '</div>' : false;

			}

			break;

		case 'search':

			if ( is_search() ) {

				$blog_layout = get_theme_mod( 'search_layout', 'default' );
				$grid = 'grid' === $blog_layout ? '</div>' : false;

			}

			break;

		case 'category':

			if ( is_category() ) {

				$blog_layout = get_theme_mod( 'category_layout', 'default' );
				$grid = 'grid' === $blog_layout ? '</div>' : false;

			}

			break;

		case 'tag':

			if ( is_tag() ) {

				$blog_layout = get_theme_mod( 'tag_layout', 'default' );
				$grid = 'grid' === $blog_layout ? '</div>' : false;

			}

			break;

		case 'author':

			if ( is_author() ) {

				$blog_layout = get_theme_mod( 'author_layout', 'default' );
				$grid = 'grid' === $blog_layout ? '</div>' : false;

			}

			break;

		case 'date':

			if ( is_date() ) {

				$blog_layout = get_theme_mod( 'date_layout', 'default' );
				$grid = 'grid' === $blog_layout ? '</div>' : false;

			}

			break;

		default:

			if ( is_post_type_archive( $saved_archive ) ) {

				$blog_layout = get_theme_mod( $saved_archive . '-archive_layout', 'default' );
				$grid = 'grid' === $blog_layout ? '</div>' : false;

			}

			// Apply to related taxonomies.
			$taxonomies = get_object_taxonomies( $saved_archive, 'names' );

			if ( ! empty( $taxonomies ) ) {

				foreach ( $taxonomies as $taxonomy ) {

					if ( is_tax( $taxonomy ) ) {

						$blog_layout = get_theme_mod( $saved_archive . '-archive_layout', 'default' );
						$grid = 'grid' === $blog_layout ? '</div>' : false;

					}

				}

			}

			break;

		}

	}

	return $grid;

} );

/**
 * Isotope for grid layout.
 */
function wpbf_isotope() {

	$masonry         = false;
	$infinite_scroll = false;
	$layout          = 'default';

	// Global archive settings.
	if ( is_archive() || is_home() || is_search() ) {
		$masonry         = get_theme_mod( 'archive_grid_masonry' );
		$infinite_scroll = get_theme_mod( 'archive_infinite_scroll' );
		$layout          = get_theme_mod( 'archive_layout', 'default' );
	}

	$saved_archives = wpbf_get_blog_layout_settings( $get_cpts = true );

	foreach ( $saved_archives as $saved_archive ) {

		switch ( $saved_archive ) {

		case 'blog':

			if ( is_home() ) {
				$masonry         = get_theme_mod( 'blog_grid_masonry' );
				$infinite_scroll = get_theme_mod( 'blog_infinite_scroll' );
				$layout          = get_theme_mod( 'blog_layout', 'default' );
			}

			break;

		case 'search':

			if ( is_search() ) {
				$masonry         = get_theme_mod( 'search_grid_masonry' );
				$infinite_scroll = get_theme_mod( 'search_infinite_scroll' );
				$layout          = get_theme_mod( 'search_layout', 'default' );
			}

			break;

		case 'category':

			if ( is_category() ) {
				$masonry         = get_theme_mod( 'category_grid_masonry' );
				$infinite_scroll = get_theme_mod( 'category_infinite_scroll' );
				$layout          = get_theme_mod( 'category_layout', 'default' );
			}

			break;

		case 'tag':

			if ( is_tag() ) {
				$masonry         = get_theme_mod( 'tag_grid_masonry' );
				$infinite_scroll = get_theme_mod( 'tag_infinite_scroll' );
				$layout          = get_theme_mod( 'tag_layout', 'default' );
			}

			break;

		case 'author':

			if ( is_author() ) {
				$masonry         = get_theme_mod( 'author_grid_masonry' );
				$infinite_scroll = get_theme_mod( 'author_infinite_scroll' );
				$layout          = get_theme_mod( 'author_layout', 'default' );
			}

			break;

		case 'date':

			if ( is_date() ) {
				$masonry         = get_theme_mod( 'date_grid_masonry' );
				$infinite_scroll = get_theme_mod( 'date_infinite_scroll' );
				$layout          = get_theme_mod( 'date_layout', 'default' );
			}

			break;

		default:

			if ( is_post_type_archive( $saved_archive ) ) {
				$masonry         = get_theme_mod( $saved_archive . '-archive_grid_masonry' );
				$infinite_scroll = get_theme_mod( $saved_archive . '-archive_infinite_scroll' );
				$layout          = get_theme_mod( $saved_archive . '-archive_layout', 'default' );
			}

			// Apply to related taxonomies.
			$taxonomies = get_object_taxonomies( $saved_archive, 'names' );

			if ( ! empty( $taxonomies ) ) {

				foreach ( $taxonomies as $taxonomy ) {

					if ( is_tax( $taxonomy ) ) {
						$masonry         = get_theme_mod( $saved_archive . '-archive_grid_masonry' );
						$infinite_scroll = get_theme_mod( $saved_archive . '-archive_infinite_scroll' );
						$layout          = get_theme_mod( $saved_archive . '-archive_layout', 'default' );
					}

				}

			}

			break;

		}

	}

	if ( $masonry && $infinite_scroll ) {
		wp_enqueue_script( 'wpbf-imagesloaded', WPBF_PREMIUM_URI . 'js/imagesloaded.js', array( 'jquery' ), WPBF_PREMIUM_VERSION, true );
	}

	if ( $masonry ) {
		wp_enqueue_script( 'wpbf-isotope', WPBF_PREMIUM_URI . 'js/isotope.js', array( 'jquery' ), '3.0.6', true );
	}

	if ( $infinite_scroll ) {

		wp_enqueue_script( 'wpbf-infinite-scroll', WPBF_PREMIUM_URI . 'js/infinite-scroll.js', array( 'jquery' ), WPBF_PREMIUM_VERSION, true );

		if ( 'grid' === $layout ) {

			wp_localize_script( 'wpbf-infinite-scroll', 'wpbf_infinte_scroll_object',
				array(
					'next_Selector'    => 'a.next.page-numbers',
					'item_Selector'    => '.wpbf-article-wrapper',
					'content_Selector' => '.wpbf-main',
					'image_loader'     => WPBF_PREMIUM_URI . 'assets/img/loader.gif',
					'isotope'          => $masonry ? true : false,
				)
			);

		} else {

			wp_localize_script( 'wpbf-infinite-scroll', 'wpbf_infinte_scroll_object',
				array(
					'next_Selector'    => 'a.next.page-numbers',
					'item_Selector'    => 'article.wpbf-post',
					'content_Selector' => '.wpbf-main',
					'image_loader'     => WPBF_PREMIUM_URI . 'assets/img/loader.gif',
				)
			);

		}

	}

}
add_action( 'wp_enqueue_scripts', 'wpbf_isotope' );
