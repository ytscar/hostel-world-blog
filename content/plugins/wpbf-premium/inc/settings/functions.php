<?php
/**
 * Theme settings functions.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Settings
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Dequeue responsive CSS.
 *
 * If custom breakpoints exist, let's remove default responsive styles.
 */
function wpbf_dequeue_responsive_css() {

	if ( wpbf_has_responsive_breakpoints() ) {

		wp_dequeue_style( 'wpbf-responsive' );
		wp_deregister_style( 'wpbf-responsive' );

		// WooCommerce
		if ( class_exists( 'WooCommerce' ) ) {

			wp_dequeue_style( 'wpbf-woocommerce-smallscreen' );
			wp_deregister_style( 'wpbf-woocommerce-smallscreen' );

		}

	}

}
add_action( 'wp_enqueue_scripts', 'wpbf_dequeue_responsive_css', 100 );

/**
 * White Label (Theme).
 *
 * @param array $themes The themes array.
 *
 * @return array The updated themes array.
 */
function wpbf_white_label_theme( $themes ) {

	$wpbf_settings = is_multisite() ? get_blog_option( 1, 'wpbf_settings' ) : get_option( 'wpbf_settings' );

	$theme_data = array(
		'name'           => isset( $wpbf_settings['wpbf_theme_name'] ) ? $wpbf_settings['wpbf_theme_name'] : '',
		'description'    => isset( $wpbf_settings['wpbf_theme_description'] ) ? $wpbf_settings['wpbf_theme_description'] : '',
		'tags'           => isset( $wpbf_settings['wpbf_theme_tags'] ) ? $wpbf_settings['wpbf_theme_tags'] : '',
		'company_name'   => isset( $wpbf_settings['wpbf_theme_company_name'] ) ? $wpbf_settings['wpbf_theme_company_name'] : '',
		'company_url'    => isset( $wpbf_settings['wpbf_theme_company_url'] ) ? $wpbf_settings['wpbf_theme_company_url'] : '',
		'screenshot_url' => isset( $wpbf_settings['wpbf_theme_screenshot'] ) ? $wpbf_settings['wpbf_theme_screenshot'] : '',
	);

	if ( ! empty( $theme_data['name'] ) ) {

		$themes['page-builder-framework']['name'] = $theme_data['name'];

		foreach ( $themes as $theme_key => $theme ) {

			if ( isset( $theme['parent'] ) && 'Page Builder Framework' == $theme['parent'] ) {
				$themes[$theme_key]['parent'] = $theme_data['name'];
			}

		}

	}

	if ( ! empty( $theme_data['description'] ) ) {
		$themes['page-builder-framework']['description'] = $theme_data['description'];
	}

	if ( ! empty( $theme_data['tags'] ) ) {
		$themes['page-builder-framework']['tags'] = $theme_data['tags'];
	}

	if ( ! empty( $theme_data['company_name'] ) ) {
		$company_url = empty( $theme_data['company_url'] ) ? '#' : $theme_data['company_url'];
		$themes['page-builder-framework']['author'] = $theme_data['company_name'];
		$themes['page-builder-framework']['authorAndUri'] = '<a href="' . $company_url . '">' . $theme_data['company_name'] . '</a>';
	}

	if ( ! empty( $theme_data['screenshot_url'] ) ) {
		$themes['page-builder-framework']['screenshot'] = array( $theme_data['screenshot_url'] );
	}

	return $themes;

}
add_filter( 'wp_prepare_themes_for_js', 'wpbf_white_label_theme' );

/**
 * White Label (Premium Add-On).
 *
 * @param array $plugins The plugins array.
 *
 * @return array The updated plugins array.
 */
function wpbf_white_label_plugin( $plugins ) {

	$wpbf_settings = is_multisite() ? get_blog_option( 1, 'wpbf_settings' ) : get_option( 'wpbf_settings' );
	$add_on        = 'wpbf-premium/wpbf-premium.php';

	$plugin_data = array(
		'name'         => isset( $wpbf_settings['wpbf_plugin_name'] ) ? $wpbf_settings['wpbf_plugin_name'] : '',
		'description'  => isset( $wpbf_settings['wpbf_plugin_description'] ) ? $wpbf_settings['wpbf_plugin_description'] : '',
		'company_name' => isset( $wpbf_settings['wpbf_theme_company_name'] ) ? $wpbf_settings['wpbf_theme_company_name'] : '',
		'company_url'  => isset( $wpbf_settings['wpbf_theme_company_url'] ) ? $wpbf_settings['wpbf_theme_company_url'] : '',
	);

	if ( ! empty( $plugin_data['name'] ) ) {
		$plugins[$add_on]['Name'] = $plugin_data['name'];
		$plugins[$add_on]['Title'] = $plugin_data['name'];
	}

	if ( ! empty( $plugin_data['description'] ) ) {
		$plugins[$add_on]['Description'] = $plugin_data['description'];
	}

	if ( ! empty( $plugin_data['company_name'] ) ) {
		$plugins[$add_on]['Author'] = $plugin_data['company_name'];
		$plugins[$add_on]['AuthorName'] = $plugin_data['company_name'];
	}

	if ( ! empty( $plugin_data['company_url'] ) ) {
		$plugins[$add_on]['AuthorURI'] = $plugin_data['company_url'];
		$plugins[$add_on]['PluginURI'] = $plugin_data['company_url'];
	}

	return $plugins;

}
add_filter( 'all_plugins', 'wpbf_white_label_plugin' );

/**
 * Update Premium Add-On plugin name.
 *
 * @param string $plugin_name The plugin name.
 *
 * @return string The updated plugin name based on White Label settings.
 */
function wpbf_white_label_plugin_name( $plugin_name ) {

	$wpbf_settings = is_multisite() ? get_blog_option( 1, 'wpbf_settings' ) : get_option( 'wpbf_settings' );

	if ( ! empty( $wpbf_settings['wpbf_plugin_name'] ) ) {
		return $wpbf_settings['wpbf_plugin_name'];
	}

	return $plugin_name;

}
add_filter( 'wpbf_premium_plugin_name', 'wpbf_white_label_plugin_name' );

/**
 * Update theme name.
 *
 * @param string $theme_name The theme name.
 *
 * @return string The updated theme name based on White Label settings.
 */
function wpbf_white_label_theme_name( $theme_name ) {

	$wpbf_settings = is_multisite() ? get_blog_option( 1, 'wpbf_settings' ) : get_option( 'wpbf_settings' );

	if ( ! empty( $wpbf_settings['wpbf_theme_name'] ) ) {
		return $wpbf_settings['wpbf_theme_name'];
	}

	return $theme_name;

}
add_filter( 'wpbf_premium_theme_name', 'wpbf_white_label_theme_name' );

/**
 * Trigger white label filters.
 *
 * If plugin name or theme name is white labeled, let's trigger our filters.
 */
function wpbf_disable_review_notice() {

	$wpbf_settings = is_multisite() ? get_blog_option( 1, 'wpbf_settings' ) : get_option( 'wpbf_settings' );

	if ( ! empty( $wpbf_settings['wpbf_plugin_name'] ) || ! empty( $wpbf_settings['wpbf_theme_name'] ) ) {
		// ! Deprecated filter, do not use it.
		add_filter( 'wpbf_premium_review_notice', '__return_false' );

		add_filter( 'wpbf_white_labeled', '__return_true' );
	}

}
add_action( 'admin_init', 'wpbf_disable_review_notice', 0 );

/**
 * Performance settings.
 */
$wpbf_settings = get_option( 'wpbf_settings' );

if ( isset( $wpbf_settings['wpbf_clean_head'] ) ) {

	$wpbf_performance = $wpbf_settings['wpbf_clean_head'];

	// Compile inline CSS.
	if ( in_array( 'css_file', $wpbf_performance ) ) {
		add_filter( 'wpbf_css_output', function () { return 'file'; } );
	}

	// Enable SVG's.
	if ( in_array( 'enable_svg', $wpbf_performance ) ) {
		add_filter( 'wpbf_svg', '__return_true' );
	}

	// Serve Gravatars locally.
	if ( in_array( 'local_gravatars', $wpbf_performance ) ) {
		add_filter( 'wpbf_local_gravatars', '__return_true' );
	}

	// Remove feed links.
	if ( in_array( 'remove_feed', $wpbf_performance ) ) {

		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );

	}

	// Remove RSD link.
	if ( in_array( 'remove_rsd', $wpbf_performance ) ) {
		remove_action( 'wp_head', 'rsd_link' );
	}

	// Remove wlwmanifest.
	if ( in_array( 'remove_wlwmanifest', $wpbf_performance ) ) {
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}

	// Remove WordPress generator tag.
	if ( in_array( 'remove_generator', $wpbf_performance ) ) {
		remove_action( 'wp_head', 'wp_generator' );
	}

	// Remove shortlink.
	if ( in_array( 'remove_shortlink', $wpbf_performance ) ) {
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
	}

	// Disable RSS feed.
	if ( in_array( 'disable_rss_feed', $wpbf_performance ) ) {

		function wpbf_disable_rss_feed() {
			wp_redirect( esc_url( home_url( '/' ) ) );
			die;
		}

		add_action( 'do_feed', 'wpbf_disable_rss_feed', 1 );
		add_action( 'do_feed_rdf', 'wpbf_disable_rss_feed', 1 );
		add_action( 'do_feed_rss', 'wpbf_disable_rss_feed', 1 );
		add_action( 'do_feed_rss2', 'wpbf_disable_rss_feed', 1 );
		add_action( 'do_feed_atom', 'wpbf_disable_rss_feed', 1 );
		add_action( 'do_feed_rss2_comments', 'wpbf_disable_rss_feed', 1 );
		add_action( 'do_feed_atom_comments', 'wpbf_disable_rss_feed', 1 );

	}

	// Disable emojis.
	if ( in_array( 'disable_emojis', $wpbf_performance ) ) {

		/**
		 * Remove emojis from TinyMCE plugins.
		 *
		 * @param array $plugins The plugins array.
		 *
		 * @return array The updated plugins array.
		 */
		function wpbf_disable_emojis_tinymce( $plugins ) {

			if ( in_array( 'wpemoji', $plugins ) ) {
				$plugins = array_diff( $plugins, array( 'wpemoji' ) );
			}

			return $plugins;

		}

		/**
		 * Disable emojis on init.
		 */
		function wpbf_disable_emojis() {

			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			add_filter( 'tiny_mce_plugins', 'wpbf_disable_emojis_tinymce' );
			add_filter( 'emoji_svg_url', '__return_false' );

		}
		add_action( 'init', 'wpbf_disable_emojis' );

	}

	// Disable embeds.
	if ( in_array( 'disable_embeds', $wpbf_performance ) ) {

		/**
		 * Remove embeds from TinyMCE plugins.
		 *
		 * @param array $plugins The plugins array.
		 *
		 * @return array The updated plugins array.
		 */
		function wpbf_disable_embeds_tinymce( $plugins ) {

			if ( in_array( 'wpembed', $plugins ) ) {
				$plugins = array_diff( $plugins, array( 'wpembed' ) );
			}

			return $plugins;

		}

		/**
		 * Disable embeds rewrite rules.
		 *
		 * Details: https://codex.wordpress.org/Plugin_API/Filter_Reference/rewrite_rules_array
		 *
		 * @param array $rules The rewrite rules.
		 *
		 * @return array The updated rewrite rules.
		 */
		function wpbf_disable_embeds_rewrite_rules( $rules ) {

			foreach ( $rules as $rule => $rewrite ) {
				if ( false !== strpos( $rewrite, 'embed=true' ) ) {
					unset( $rules[$rule] );
				}
			}

			return $rules;

		}

		/**
		 * Disable embeds on init.
		 */
		function wpbf_disable_embeds() {

			global $wp;
			$wp->public_query_vars = array_diff( $wp->public_query_vars, array( 'embed' ) );

			remove_action( 'rest_api_init', 'wp_oembed_register_route' );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );

			remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
			remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

			add_filter( 'embed_oembed_discover', '__return_false' );
			add_filter( 'tiny_mce_plugins', 'wpbf_disable_embeds_tinymce' );
			add_filter( 'rewrite_rules_array', 'wpbf_disable_embeds_rewrite_rules' );

		}
		add_action( 'init', 'wpbf_disable_embeds', 9999 );

	}

	// Remove jQuery migrate.
	if ( in_array( 'remove_jquery_migrate', $wpbf_performance ) ) {

		/**
		 * Remove jQuery migrate.
		 *
		 * @param object $scripts The WP_Scripts object.
		 */
		function wpbf_remove_jquery_migrate( $scripts ) {

			if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {

				$script = $scripts->registered['jquery'];

				if ( $script->deps ) {
					$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
				}

			}

		}
		add_filter( 'wp_default_scripts', 'wpbf_remove_jquery_migrate' );

	}

	// Remove WooCommerce scripts & styles.
	if ( in_array( 'remove_woo_scripts', $wpbf_performance ) ) {

		/**
		 * Remove WooCommerce scripts & styles.
		 *
		 * @param bool $scripts Wether to load scripts or not.
		 *
		 * @return bool $scripts Wether to load scripts or not.
		 */
		function wpbf_remove_woo_scripts( $scripts ) {

			// Stop if WooCommerce is not active.
			if ( ! class_exists( 'WooCommerce' ) ) {
				return $scripts;
			}

			$scripts = false;

			// Let's keep our scripts & styles on WooCommerce related pages.
			if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
				$scripts = true;
			}

			return $scripts;

		}
		add_filter( 'wpbf_woocommerce_scripts', 'wpbf_remove_woo_scripts' );

	}

}

/**
 * Compile Inline CSS (Multisite support).
 */
function wpbf_compile_inline_css_multisite() {

	// Stop if we're on the main site of the network.
	// Will return on non-multisites, no additional checking required.
	if ( is_main_site() ) {
		return;
	}

	// Let's get the settings from the main site of the network.
	$main_site_id  = get_network()->site_id;
	$wpbf_settings = get_blog_option( $main_site_id, 'wpbf_settings' );

	// Stop here if we have no performance settings.
	if ( ! isset( $wpbf_settings['wpbf_clean_head'] ) ) {
		return;
	}

	$wpbf_performance = $wpbf_settings['wpbf_clean_head'];

	// Compile inline CSS.
	if ( in_array( 'css_file', $wpbf_performance ) ) {
		add_filter( 'wpbf_css_output', function () { return 'file'; } );
	}

}
add_action( 'init', 'wpbf_compile_inline_css_multisite' );

/**
 * Hide white label section.
 */
function wpbf_hide_white_label_section() {

	$wpbf_settings = get_option( 'wpbf_settings' );
	$transient     = get_transient( 'wpbf_white_label_section_hidden' );

	// Stop here if setting isn't checked.
	if ( ! isset( $wpbf_settings['wpbf_hide_white_label_section'] ) ) {
		return;
	}

	// Stop here if transient is already defined.
	if ( $transient ) {
		return;
	}

	set_transient( 'wpbf_white_label_section_hidden', 1, 0 );

}
add_action( 'admin_init', 'wpbf_hide_white_label_section' );

/**
 * Gutenberg color palette.
 *
 * Add Gutenberg color palette based on saved values.
 */
function wpbf_gutenberg_color_palette() {

	$color_palette = wpbf_color_palette();

	if ( empty( $color_palette ) ) return;

	$colors_array = array();
	$i            = 0;

	foreach ( $color_palette as $color ) {
		$i++;
		$colors_array[] = array(
			'name' => $color,
			'slug'  => 'wpbf-palette-color-' . $i,
			'color' => $color,
		);
	}

    add_theme_support( 'editor-color-palette', $colors_array );

}
add_action( 'after_setup_theme', 'wpbf_gutenberg_color_palette' );

/**
 * Disable featured image based on global settings.
 *
 * @param bool $remove_featured_image Wether to show or hide the featured image.
 *
 * @return bool.
 */
function wpbf_remove_featured_image( $remove_featured_image ) {

	$wpbf_settings = get_option( 'wpbf_settings' );

	// Get array of post types that are set to have the featured image removed under Appearance > Theme Settings > Global Templat Settings.
	$remove_featured_image = isset( $wpbf_settings['wpbf_remove_featured_image_global'] ) ? $wpbf_settings['wpbf_remove_featured_image_global'] : array();

	// If current post type has been set to have the featured image removed globally, set $remove_featured_image to true.
	$remove_featured_image = $remove_featured_image && in_array( get_post_type(), $remove_featured_image ) ? true : false;

	return $remove_featured_image;

}
add_filter( 'wpbf_remove_featured_image', 'wpbf_remove_featured_image' );
