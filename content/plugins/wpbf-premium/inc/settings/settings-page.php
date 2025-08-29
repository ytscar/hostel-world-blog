<?php
/**
 * Theme settings page template.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$active_tab                  = isset( $_GET['tab'] ) ? $_GET['tab'] : 'settings';
$column_layout_class         = ! wpbf_is_white_labeled() && is_main_site() ? ' heatbox-column-container' : '';
$column_layout_wrapper_open  = ! wpbf_is_white_labeled() && is_main_site() ? '<div class="heatbox-main">' : '';
$column_layout_wrapper_close = ! wpbf_is_white_labeled() && is_main_site() ? '</div>' : '';

$wpbf_settings = is_multisite() ? get_blog_option( 1, 'wpbf_settings' ) : get_option( 'wpbf_settings' );
$company_logo  = ! empty( $wpbf_settings['wpbf_company_logo'] ) ? $wpbf_settings['wpbf_company_logo'] : WPBF_THEME_URI . '/img/page-builder-framework-logo.png';

// Since this setting is new, let's hide the logo if the theme is white labeled but no logo is set.
$company_logo = wpbf_is_white_labeled() && empty( $wpbf_settings['wpbf_company_logo'] ) ? false : $company_logo;

// If we don't have a custom logo and we're on a subsite, let's remove the logo.
$company_logo = ! is_main_site() && empty( $wpbf_settings['wpbf_company_logo'] ) ? false : $company_logo;
?>

<div class="wrap heatbox-wrap wpbf-theme-settings-page">

	<div class="heatbox-header heatbox-has-tab-nav heatbox-margin-bottom">

		<div class="heatbox-container heatbox-container-center">

			<div class="logo-container">

				<div>
					<span class="title">
						<?php echo esc_html( get_admin_page_title() ); ?>
						<span class="version"><?php echo WPBF_PREMIUM_VERSION; ?></span>
					</span>
				</div>

				<div class="heatbox-logo-wide">
					<img src="<?php echo esc_url( $company_logo ); ?>">
				</div>

			</div>

			<?php if ( 'settings' === $active_tab ) { ?>

			<nav>
				<ul class="heatbox-tab-nav">
					<li class="heatbox-tab-nav-item settings-panel active"><a href="#settings"><?php _e( 'Getting Started', 'wpbfpremium' ); ?></a></li>
					<li class="heatbox-tab-nav-item global-panel"><a href="#global"><?php _e( 'Global Settings', 'wpbfpremium' ); ?></a></li>
					<li class="heatbox-tab-nav-item performance-panel"><a href="#performance"><?php _e( 'Performance', 'wpbfpremium' ); ?></a></li>
					<li class="heatbox-tab-nav-item blog-layouts-panel"><a href="#blog-layouts"><?php _e( 'Blog Layouts', 'wpbfpremium' ); ?></a></li>
					<li class="heatbox-tab-nav-item breakpoints-panel"><a href="#breakpoints"><?php _e( 'Breakpoints', 'wpbfpremium' ); ?></a></li>
					<?php if ( ! get_transient( 'wpbf_white_label_section_hidden' ) && is_main_site() ) { ?>
					<li class="heatbox-tab-nav-item white-label-panel"><a href="#white-label"><?php _e( 'White Label', 'wpbfpremium' ); ?></a></li>
					<?php } ?>
					<?php if ( is_main_site() ) { ?>
					<li class="heatbox-tab-nav-item license-panel"><a href="?page=wpbf-premium&tab=license#license"><?php _e( 'License', 'wpbfpremium' ); ?></a></li>
					<?php } ?>
				</ul>
			</nav>

			<?php } elseif ( 'license' === $active_tab ) { ?>

			<nav>
				<ul class="heatbox-tab-nav">
					<li class="heatbox-tab-nav-item settings-panel"><a href="?page=wpbf-premium&tab=settings#settings"><?php _e( 'Getting Started', 'wpbfpremium' ); ?></a></li>
					<li class="heatbox-tab-nav-item global-panel"><a href="?page=wpbf-premium&tab=settings#global"><?php _e( 'Global Settings', 'wpbfpremium' ); ?></a></li>
					<li class="heatbox-tab-nav-item performance-panel"><a href="?page=wpbf-premium&tab=settings#performance"><?php _e( 'Performance', 'wpbfpremium' ); ?></a></li>
					<li class="heatbox-tab-nav-item blog-layouts-panel"><a href="?page=wpbf-premium&tab=settings#blog-layouts"><?php _e( 'Blog Layouts', 'wpbfpremium' ); ?></a></li>
					<li class="heatbox-tab-nav-item breakpoints-panel"><a href="?page=wpbf-premium&tab=settings#breakpoints"><?php _e( 'Breakpoints', 'wpbfpremium' ); ?></a></li>
					<?php if ( ! get_transient( 'wpbf_white_label_section_hidden' ) && is_main_site() ) { ?>
					<li class="heatbox-tab-nav-item white-label-panel"><a href="?page=wpbf-premium&tab=settings#white-label"><?php _e( 'White Label', 'wpbfpremium' ); ?></a></li>
					<?php } ?>
					<?php if ( is_main_site() ) { ?>
					<li class="heatbox-tab-nav-item license-panel active"><a href="#license"><?php _e( 'License', 'wpbfpremium' ); ?></a></li>
					<?php } ?>
				</ul>
			</nav>

			<?php } ?>

		</div>

	</div>

	<form method="post" action="options.php" class="wpbf-settings-form">

		<div class="heatbox-container heatbox-container-center<?php echo $column_layout_class; ?>">

			<?php echo $column_layout_wrapper_open; ?>

				<?php if ( 'settings' === $active_tab ) { ?>

					<?php settings_fields( 'wpbf-premium-group' ); ?>

				<div class="heatbox-admin-panel wpbf-settings-panel" data-tab-id="settings" style="display: block;">

					<!-- Faking H1 tag to place admin notices -->
					<h1 style="display: none;"></h1>

					<div class="heatbox wpbf-customizer-metabox">
						<?php require __DIR__ . '/metaboxes/customizer.php'; ?>
					</div>

					<?php require __DIR__ . '/metaboxes/clear-font-cache.php'; ?>

				</div>

				<div class="heatbox-admin-panel wpbf-global-panel" data-tab-id="global">

					<div class="heatbox wpbf-global-template-settings-metabox">
						<?php do_settings_sections( 'wpbf-global-template-settings' ); ?>
					</div>

					<div class="heatbox wpbf-global-color-palette-metabox">
						<?php do_settings_sections( 'wpbf-global-color-palette-settings' ); ?>
					</div>

					<?php submit_button( '', 'button button-primary button-larger' ); ?>

				</div>

				<div class="heatbox-admin-panel wpbf-blog-layouts-panel" data-tab-id="blog-layouts">

					<div class="heatbox wpbf-blog-layout-metabox">
						<?php do_settings_sections( 'wpbf-blog-layout-settings' ); ?>
					</div>

					<div class="heatbox wpbf-post-layout-metabox">
						<?php do_settings_sections( 'wpbf-post-layout-settings' ); ?>
					</div>

					<?php submit_button( '', 'button button-primary button-larger' ); ?>

				</div>

				<div class="heatbox-admin-panel wpbf-performance-panel" data-tab-id="performance">

					<div class="heatbox wpbf-performance-metabox">
						<?php do_settings_sections( 'wpbf-performance-settings' ); ?>
					</div>

					<?php submit_button( '', 'button button-primary button-larger' ); ?>

				</div>

				<div class="heatbox-admin-panel wpbf-breakpoints-panel" data-tab-id="breakpoints">

					<div class="heatbox wpbf-breakpoint-metabox">
						<?php do_settings_sections( 'wpbf-breakpoint-settings' ); ?>
					</div>

					<?php submit_button( '', 'button button-primary button-larger' ); ?>

				</div>

				<div class="heatbox-admin-panel wpbf-white-label-panel" data-tab-id="white-label">

					<div class="heatbox wpbf-white-label-company-metabox">
						<?php do_settings_sections( 'wpbf-white-label-company-settings' ); ?>
					</div>

					<div class="heatbox wpbf-white-label-plugin-metabox">
						<?php do_settings_sections( 'wpbf-white-label-plugin-settings' ); ?>
					</div>

					<div class="heatbox wpbf-white-label-theme-metabox">
						<?php do_settings_sections( 'wpbf-white-label-theme-settings' ); ?>
					</div>

					<div class="heatbox wpbf-white-label-misc-metabox">
						<?php do_settings_sections( 'wpbf-white-label-misc-settings' ); ?>
					</div>

					<?php submit_button( '', 'button button-primary button-larger' ); ?>

				</div>

				<?php } elseif ( 'license' === $active_tab ) { ?>

					<!-- Faking H1 tag to place admin notices -->
					<h1 style="display: none;"></h1>

					<?php
					settings_fields( 'wpbf_premium_license' );
					require __DIR__ . '/metaboxes/license.php';
					submit_button( '', 'button button-primary button-larger' );
					?>

				<?php } ?>

			<?php echo $column_layout_wrapper_close; ?>

			<?php if ( ! wpbf_is_white_labeled() && is_main_site() ) { ?>

			<div class="heatbox-sidebar">

				<?php
				require __DIR__ . '/metaboxes/documentation.php';
				require __DIR__ . '/metaboxes/community.php';
				require __DIR__ . '/metaboxes/resources.php';
				?>

			</div>

			<?php } ?>

		</div>

	</form>

</div>
