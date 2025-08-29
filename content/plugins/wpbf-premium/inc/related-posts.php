<?php
/**
 * Related posts.
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Display related posts.
 */
function wpbf_related_posts() {

	$singles = apply_filters( 'wpbf_singles', array( 'single' ) );

	foreach ( $singles as $single ) {

		$singular = $single;
		$singular = 'single' === $single ? 'post' : $singular;

		if ( ! is_singular( $singular ) ) {
			continue;
		}

		// Stop here if Related Posts module is not enabled.
		if ( ! get_theme_mod( $single . '_related_posts', false ) ) {
			continue;
		}

		// Vars.
		$post     = 'single' === $single ? 'post' : $single;
		$headline = get_theme_mod( $single . '_related_posts_headline', __( 'Related Posts', 'wpbfpremium' ) );
		$layout   = get_theme_mod( $single . '_related_posts_layout', 'grid' );

		// Args. vars.
		$showposts  = get_theme_mod( $single . '_related_posts_showposts', 3 );
		$orderby    = get_theme_mod( $single . '_related_posts_orderby', 'date' );
		$order      = get_theme_mod( $single . '_related_posts_order', 'DESC' );
		$authors    = get_theme_mod( $single . '_related_posts_authors' );
		$categories = get_theme_mod( $single . '_related_posts_categories' );
		$post_types = get_theme_mod( $single . '_related_posts_post_types' );
		$post_types = empty( $post_types ) ? $post : explode( ',', esc_attr( $post_types ) );

		$args = array(
			'showposts'    => intval( $showposts ),
			'orderby'      => esc_attr( $orderby ),
			'order'        => esc_attr( $order ),
			'post_type'    => $post_types,
			'post__not_in' => array( get_the_ID() ),
		);

		// Extend $args based on available options.
		if ( $authors ) {
			$args['author'] = trim( $authors );
			$args['author'] = rtrim( $authors, ',' );
			$args['author'] = esc_attr( $authors );
		}

		if ( $categories ) {
			$args['cat'] = trim( $categories );
			$args['cat'] = rtrim( $args['cat'], ',' );
			$args['cat'] = esc_attr( $args['cat'] );
		}

		// Make $args filterable.
		$args = apply_filters( 'related_posts_query_args', $args );

		$query = new WP_Query( $args );

		// Stop here if we don't have a loop.
		if ( ! $query->have_posts() ) {
			return;
		}

		?>

		<section class="wpbf-related-posts-section">

			<h4 class="wpbf-related-posts-headline"><?php echo esc_html( $headline ); ?></h4>

			<?php if ( 'list' === $layout ) { ?>

				<ul class="wpbf-related-posts wpbf-related-posts-list">

					<?php
					if ( $query->have_posts() ) :
						while ( $query->have_posts() ) :
							$query->the_post();
							?>

							<li>
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</li>

							<?php
						endwhile;
					endif;
					?>

				</ul>

				<?php
			} else {

				/**
				 * The is_array check was added because we forgot to add 'save_as_json' => true in the related customizer field.
				 * The field is defined inside settings-blog-layouts.php file in Premium Add-On.
				 */
				$products_per_row = get_theme_mod( $single . '_related_posts_grid_columns' );
				$products_per_row = is_array( $products_per_row ) ? $products_per_row : json_decode( $products_per_row, true );

				$desktop_breakpoint = wpbf_get_theme_mod_value( $products_per_row, 'desktop', 3, true );
				$tablet_breakpoint  = wpbf_get_theme_mod_value( $products_per_row, 'tablet', 2, true );
				$mobile_breakpoint  = wpbf_get_theme_mod_value( $products_per_row, 'mobile', 1, true );
				$grid_gap           = get_theme_mod( $single . '_related_posts_grid_gap', 'medium' );
				$sortable           = get_theme_mod( $single . '_related_posts_grid_sortable', array( 'featured', 'meta', 'title' ) );

				?>

				<ul class="wpbf-grid wpbf-grid-<?php echo esc_attr( $grid_gap ); ?> wpbf-grid-1-<?php echo esc_attr( $mobile_breakpoint ); ?> wpbf-grid-small-1-<?php echo esc_attr( $tablet_breakpoint ); ?> wpbf-grid-large-1-<?php echo esc_attr( $desktop_breakpoint ); ?> wpbf-related-posts wpbf-related-posts-grid">

					<?php
					if ( $query->have_posts() ) :
						while ( $query->have_posts() ) :
							$query->the_post();
							?>

							<li>
								<article class="wpbf-related-posts-article">

								<?php

								if ( is_array( $sortable ) && ! empty( $sortable ) ) {

									foreach ( $sortable as $value ) {

										switch ( $value ) {
											case 'featured':
												?>
												<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
												<?php
												break;
											case 'meta':
												wpbf_article_meta();
												break;
											case 'title':
												?>
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
												<?php
												break;
											default:
												break;
										}
									}
								}

								?>

								</article>
							</li>

							<?php
						endwhile;
					endif;
					?>

				</ul>

				<?php
			}
			?>
		</section>

		<?php
	}

}
add_action( 'wpbf_post_links', 'wpbf_related_posts', 20 );
