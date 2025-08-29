<?php
/**
 * Index.
 *
 * @package Page Builder Framework
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$grid_gap = get_theme_mod( 'sidebar_gap', 'large' );

get_header();
add_filter('theme_mod_archive_layout', function ($name) {
	return 'grid';
});

add_filter('theme_mod_archive_sidebar_layout', function ($name) {
    return 'none';
});

add_filter('theme_mod_archive_grid_gap', function ($name) {
    return 'medium';
});

add_filter('theme_mod_archive_grid_masonry', function ($name) {
    return 1;
});
add_filter('theme_mod_archive_post_style', function ($name) {
    return 'boxed';
});
add_filter( "get_authors", function($term) {
	$term -> description = null;
	return $term;
})
?>

<div id="content">

    <?php do_action( 'wpbf_content_open' ); ?>

    <?php wpbf_inner_content(); ?>

    <?php do_action( 'wpbf_inner_content_open' ); ?>

	<div class="wpbf-grid wpbf-main-grid wpbf-grid-<?php echo esc_attr( $grid_gap ); ?>">

        <?php do_action( 'wpbf_sidebar_left' ); ?>

		<main id="main" class="wpbf-main wpbf-medium-3-3<?php echo wpbf_archive_class(); ?>">

            <?php do_action( 'wpbf_main_content_open' ); ?>

            <?php if ( have_posts() ) : ?>

                <?php wpbf_archive_header(); ?>

                <?php do_action( 'wpbf_before_loop' ); ?>

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php get_template_part( 'inc/template-parts/article' ); ?>

                <?php endwhile; ?>

                <?php do_action( 'wpbf_after_loop' ); ?>

            <?php else : ?>

                <?php get_template_part( 'inc/template-parts/article-none' ); ?>

            <?php endif; ?>

            <?php do_action( 'wpbf_posts_pagination' ); ?>

            <?php do_action( 'wpbf_main_content_close' ); ?>

		</main>

        <?php do_action( 'wpbf_sidebar_right' ); ?>

	</div>

    <?php do_action( 'wpbf_inner_content_close' ); ?>

    <?php wpbf_inner_content_close(); ?>

    <?php do_action( 'wpbf_content_close' ); ?>

</div>

<?php get_footer(); ?>
