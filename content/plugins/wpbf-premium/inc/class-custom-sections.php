<?php
/**
 * Custom Sections.
 *
 * @package Page Builder Framework Premium Add-On
 */

namespace WPBF;

ob_start();

/**
 * Custom Sections.
 */
class Custom_Sections {

	/**
	 * Post types which are not going to be used with Custom Sections.
	 *
	 * All these should be removed using a filter in the respective integration or plugin.
	 *
	 * @var array
	 */
	private $unused_post_types = array(
		'attachment',
		'wpbf_hooks',
		'fl-builder-template',
		'elementor_library',
		'mailpoet_page',
		'udb_admin_page',
	);

	/**
	 * Post types which are handled/ managed manually (excluded from dynamic handling).
	 *
	 * @var array
	 */
	private $static_post_types = array(
		'post',
		'page',

		/**
		 * WooCommerce's "product" & EDD's "download" are not totally static.
		 *
		 * It's static because we select & manage the taxonomies being shown
		 * in the inclusion & exclusion rules.
		 *
		 * But their post listing are called together with other dynamic post types.
		 */
		'product',
		'download',
	);

	/**
	 * Taxonomies excluded from dynamic handling.
	 *
	 * @var array
	 */
	private $excluded_taxonomies = array(
		'post_tag',
		'post_category',
		'download_category',
		'download_tag',
		'product_cat',
		'product_tag',
	);

	/**
	 * Post types which are handled dynamically.
	 *
	 * @var array
	 */
	private $dynamic_post_types = array();

	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_cpt' ) );
		add_action( 'admin_menu', array( $this, 'menu_item' ), 100 );
		add_action( 'admin_head', array( $this, 'fix_current_item' ) );
		add_filter( 'manage_wpbf_hooks_posts_columns', array( $this, 'register_columns' ) );
		add_action( 'manage_wpbf_hooks_posts_custom_column', array( $this, 'add_columns' ), 10, 2 );

		add_action( 'add_meta_boxes', array( $this, 'meta_box' ) );

		add_filter( 'post_updated_messages', array( $this, 'cpt_messages' ) );

		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );

		add_action( 'wp', array( $this, 'do_published_hooks' ) );
		add_action( 'wp', array( $this, 'frontend_show_hooks' ) );
		add_action( 'admin_bar_menu', array( $this, 'display_hooks' ), 999 );

		add_action( 'template_redirect', array( $this, 'cpt_redirect' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'hook_admin_scripts' ) );

	}

	/**
	 * Hook admin scripts.
	 */
	public function hook_admin_scripts() {

		wp_enqueue_style( 'wpbf-premium-hooks', WPBF_PREMIUM_URI . 'css/wpbf-premium-hooks.css', '', WPBF_PREMIUM_VERSION );

	}

	/**
	 * Check whether or not Gutenberg is active for the provided post ID.
	 *
	 * @param int $hook_id The post ID.
	 *
	 * @return boolean Whether or not Gutenberg is active.
	 */
	public function is_gutenberg_active( $hook_id ) {

		$gutenberg = true;

		if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) ) {
			$gutenberg = false;
		}

		if ( ! function_exists( 'has_blocks' ) || ! has_blocks( $hook_id ) ) {
			$gutenberg = false;
		}

		return $gutenberg;

	}

	/**
	 * Check whether or not Brizy is active for the provided post ID.
	 *
	 * @param int $hook_id The post ID.
	 *
	 * @return boolean Where or not Brizy is active.
	 */
	public function is_brizy_active( $hook_id ) {

		if ( class_exists( '\Brizy_Editor_Post' ) ) {

			if ( ! $this->supported_in_brizy_post_types() ) {
				return false;
			}

			try {
				$post = \Brizy_Editor_Post::get( $hook_id );

				if ( is_object( $post ) && method_exists( $post, 'uses_editor' ) && $post->uses_editor() ) {
					return true;
				}
			} catch ( \Exception $e ) {
				return false;
			}
		}

		return false;

	}

	/**
	 * Check whether custom section post type is checked in Brizy settings.
	 *
	 * @see wp-content/plugins/brizy/editor.php
	 */
	public function supported_in_brizy_post_types() {

		if ( ! class_exists( '\Brizy_Editor' ) ) {
			return false;
		}

		$brizy_editor         = \Brizy_Editor::get();
		$supported_post_types = $brizy_editor->supported_post_types();

		if ( in_array( 'wpbf_hooks', $supported_post_types, true ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Initialize Brizy frontend.
	 *
	 * This was taken from `is_view_page` condition inside `initialize_front_end` function
	 * in brizy/public/main.php file.
	 *
	 * What we don't use from that function:
	 * - template_include hook
	 * - preparePost private function
	 * - plugin_live_composer_fixes private function
	 * - remove `wpautop` filter from `the_content` (moved to our `render_brizy_content` function)
	 *
	 * @see wp-content/plugins/brizy/public/main.php
	 *
	 * @param int $post_id The post id.
	 */
	public function initialize_brizy_frontend( $post_id ) {

		$brizy_post   = \Brizy_Editor_Post::get( $post_id );
		$brizy_public = \Brizy_Public_Main::get( $brizy_post );

		// Insert the compiled head and content.
		add_filter( 'body_class', array( $brizy_public, 'body_class_frontend' ) );
		add_action( 'wp_head', array( $brizy_public, 'insert_page_head' ) );
		add_action( 'admin_bar_menu', array( $brizy_public, 'toolbar_link' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $brizy_public, '_action_enqueue_preview_assets' ), 9999 );
		// add_filter( 'the_content', array( $brizy_public, 'insert_page_content' ), -12000 );
		// add_action( 'brizy_template_content', array( $brizy_public, 'brizy_the_content' ) );

		// $this->render_brizy_content( $post_id );
	}

	/**
	 * Render Brizy content.
	 *
	 * @see wp-content/plugins/brizy/public/main.php
	 *
	 * @param int $post_id The post id.
	 */
	public function render_brizy_content( $post_id ) {

		// @see wp-content/plugins/brizy/public/main.php
		remove_filter( 'the_content', 'wpautop' );

		$post         = \Brizy_Editor_Post::get( $post_id );
		$brizy_public = \Brizy_Public_Main::get( $post );

		$brizy_public->brizy_the_content();

		// Let's bring back the filter after rendering the content.
		add_filter( 'the_content', 'wpautop' );

	}

	/**
	 * Do published hooks.
	 */
	public function do_published_hooks() {

		if ( is_singular( 'wpbf_hooks' ) ) {
			return;
		}

		$args = array(
			'post_type'        => 'wpbf_hooks',
			'no_found_rows'    => true,
			'post_status'      => 'publish',
			'numberposts'      => 100,
			'fields'           => 'ids',
			'order'            => 'ASC',
			'suppress_filters' => false,
		);

		$hooks   = get_posts( $args );
		$options = get_post_meta( get_the_ID(), 'wpbf_options', true );
		$options = ! empty( $options ) ? $options : array();

		foreach ( $hooks as $hook_id ) {

			$location = get_post_meta( $hook_id, '_wpbf_hook_location', true );
			$action   = get_post_meta( $hook_id, '_wpbf_hook_action', true );
			$priority = get_post_meta( $hook_id, '_wpbf_hook_priority', true );
			$priority = empty( $priority ) ? 10 : $priority;

			if ( ! empty( $location ) ) {

				if ( 'header' === $location ) {
					$action = 'wpbf_header';
				} elseif ( 'footer' === $location ) {
					$action = 'wpbf_footer';
				} elseif ( '404' === $location ) {
					$action = 'wpbf_404';
				}
			}

			// Stop, and continue to next loop if $action is empty.
			if ( empty( $action ) ) {
				continue;
			}

			$matched_display_rule = $this->matched_display_rules( $hook_id );

			// If current "singular" is going to display custom section, then remove the default actions.
			if ( $matched_display_rule ) {
				switch ( $action ) {
					case 'wpbf_header':
						remove_action( 'wpbf_header', 'wpbf_do_header' );
						break;
					case 'wpbf_footer':
						remove_action( 'wpbf_before_footer', 'wpbf_custom_footer' );
						remove_action( 'wpbf_footer', 'wpbf_do_footer' );
						break;
					case 'wpbf_404':
						remove_action( 'wpbf_404', 'wpbf_do_404' );
						break;
				}
			}

			$position_disabled = false;

			if ( 'header' === $location || 'footer' === $location ) {
				if ( in_array( 'remove-' . $location, $options, true ) ) {
					$position_disabled = true;
				}
			}

			// Stop, and continue to next loop if the rule is not passed.
			if ( ! $matched_display_rule || $position_disabled ) {
				continue;
			}

			add_action(
				'template_redirect',
				function () use ( $hook_id ) {

					if ( $this->is_brizy_active( $hook_id ) ) {
						$this->initialize_brizy_frontend( $hook_id );
					}

				}
			);

			add_action(
				'wp_enqueue_scripts',
				function() use ( $hook_id ) {

					if ( ! class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
						return;
					}

					$css_file = new \Elementor\Core\Files\CSS\Post( $hook_id );
					$css_file->enqueue();

				},
				500
			);

			add_action(
				$action,
				function () use ( $hook_id, $action ) {

					$hide_on_desktop = get_post_meta( $hook_id, '_wpbf_hide_on_desktop', true );
					$hide_on_tablet  = get_post_meta( $hook_id, '_wpbf_hide_on_tablet', true );
					$hide_on_mobile  = get_post_meta( $hook_id, '_wpbf_hide_on_mobile', true );

					$wrapper_classes  = 'wpbf-custom-section-' . get_post_field( 'post_name', $hook_id );
					$wrapper_classes .= $hide_on_desktop ? ' wpbf-hidden-large' : '';
					$wrapper_classes .= $hide_on_tablet ? ' wpbf-hidden-medium' : '';
					$wrapper_classes .= $hide_on_mobile ? ' wpbf-hidden-small' : '';

					/**
					 * Remove our wrapper from WooCommerce product loop.
					 * Reason being, this used to interfere with page builders.
					 */
					// add_filter( 'wpbf_woo_product_loop_wrapper', '__return_false' );

					echo '<div class="wpbf-custom-section ' . esc_attr( $wrapper_classes ) . '">';

					if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( $hook_id ) ) {
						if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
							// Elementor PRO support.
							echo do_shortcode( sprintf( '[elementor-template id="%s"]', $hook_id ) );
						} else {
							// Elementor Free support.
							$elementor_frontend = new \Elementor\Frontend();

							echo $elementor_frontend->get_builder_content_for_display( $hook_id );
						}
					} elseif ( $this->is_brizy_active( $hook_id ) ) {
						$this->render_brizy_content( $hook_id );
					} elseif ( function_exists( 'et_pb_is_pagebuilder_used' ) && et_pb_is_pagebuilder_used( $hook_id ) ) {
						// Divi support.
						$style_suffix = et_load_unminified_styles() ? '' : '.min';

						wp_enqueue_style( 'et-builder-modules-style', ET_BUILDER_URI . '/styles/frontend-builder-plugin-style' . $style_suffix . '.css', array(), ET_BUILDER_VERSION );

						$hook_post    = get_post( $hook_id );
						$hook_content = $hook_post->post_content;
						$hook_content = et_builder_get_layout_opening_wrapper() . $hook_content . et_builder_get_layout_closing_wrapper();
						$hook_content = et_builder_get_builder_content_opening_wrapper() . $hook_content . et_builder_get_builder_content_closing_wrapper();

						echo apply_filters( 'the_content', $hook_content );
					} elseif ( class_exists( '\FLBuilderModel' ) && \FLBuilderModel::is_builder_enabled( $hook_id ) ) {
						// Beaver Builder support.
						echo do_shortcode( sprintf( '[fl_builder_insert_layout id="%s"]', $hook_id ) );
					} elseif ( $this->is_gutenberg_active( $hook_id ) ) {
						if ( class_exists( '\UAGB_Init_Blocks' ) ) {
							$uagb = \UAGB_Init_Blocks::get_instance();
							add_action( 'enqueue_block_assets', array( $uagb, 'block_assets' ) );
						}

						// Gutenberg support.
						echo do_shortcode( do_blocks( get_post_field( 'post_content', $hook_id ) ) );
					} else {
						echo do_shortcode( get_post_field( 'post_content', $hook_id ) );
					}

					echo '</div>';

					/**
					 * Bring back our wrapper to WooCommerce product loop.
					 */
					// add_filter( 'wpbf_woo_product_loop_wrapper', '__return_true' );
				},
				absint( $priority )
			);

		} // End of $hooks foreach.

	}

	/**
	 * Check if current condition matches the display rules.
	 *
	 * @param int $hook_id The post ID.
	 *
	 * @return boolean Whether or not the current condition matches the display rules.
	 */
	public function matched_display_rules( $hook_id ) {

		$restrict_access = get_post_meta( $hook_id, '_wpbf_restrict_access', true );

		if ( empty( $restrict_access ) ) {
			// Run compatibility check.
			$restrict_access = get_post_meta( $hook_id, '_wpbf_restrict_logged_users', true );
			$restrict_access = 'true' === $restrict_access ? 'logged-in' : 'all';
		}

		// If only logged-in users are allowed, then restrict guest users.
		if ( 'logged-in' === $restrict_access && ! is_user_logged_in() ) {
			return false;
		}

		// If only guest users are allowed, then restrict logged-in users.
		if ( 'logged-out' === $restrict_access && is_user_logged_in() ) {
			return false;
		}

		$db_parent_rule = get_post_meta( $hook_id, '_wpbf_display_rule_parent', true );

		if ( empty( $db_parent_rule ) || ! is_array( $db_parent_rule ) ) {
			$db_parent_rule = array( 1 => 'entire_site' );
		}

		$db_exclusion_parent_rule = get_post_meta( $hook_id, '_wpbf_exclusion_display_rule_parent', true );

		if ( empty( $db_exclusion_parent_rule ) || ! is_array( $db_exclusion_parent_rule ) ) {
			$db_exclusion_parent_rule = array( 1 => '' );
		}

		$db_child_rule = get_post_meta( $hook_id, '_wpbf_display_rule_child', true );

		if ( empty( $db_child_rule ) || ! is_array( $db_child_rule ) ) {
			$db_child_rule = array();
		}

		$db_exclusion_child_rule = get_post_meta( $hook_id, '_wpbf_exclusion_display_rule_child', true );

		if ( empty( $db_exclusion_child_rule ) || ! is_array( $db_exclusion_child_rule ) ) {
			$db_exclusion_child_rule = array();
		}

		$post_id = is_singular() || is_front_page() ? get_queried_object_id() : 0;

		if ( ! $post_id ) {
			if ( 'page' === get_option( 'show_on_front' ) ) {
				$post_id = absint( get_option( 'page_for_posts' ) );
			}
		}

		$taxonomies = $this->get_filtered_taxonomies();

		foreach ( $db_exclusion_parent_rule as $key => $rule ) {
			if ( 'entire_site' === $rule ) {
				return false;
			}

			if ( 'all_archive' === $rule ) {
				if ( is_archive() ) {
					return false;
				}
			}

			if ( 'author_archive' === $rule ) {
				if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
					if ( is_author() ) {
						return false;
					}
				} else {

					$author_id = $db_exclusion_child_rule[ $key ];

					if ( is_author( $author_id ) ) {
						return false;
					}
				}
			}

			if ( 'date_archive' === $rule ) {
				if ( is_date() ) {
					return false;
				}
			}

			if ( 'blog_page' === $rule ) {
				if ( is_home() ) {
					return false;
				}
			}

			if ( 'search' === $rule && is_search() ) {
				return false;
			}

			if ( '404' === $rule ) {
				if ( is_404() ) {
					return false;
				}
			}

			if ( 'posts' === $rule ) {
				if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
					if ( is_singular( 'post' ) ) {
						return false;
					}
				} else {

					if ( $post_id == $db_exclusion_child_rule[ $key ] ) {
						return false;
					}
				}
			}

			if ( 'post_category' === $rule ) {
				if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
					if ( is_category() ) {
						return false;
					}
				} else {

					$category_id = $db_exclusion_child_rule[ $key ];

					if ( is_category( $category_id ) ) {
						return false;
					}
				}
			}

			if ( 'post_tag' === $rule ) {
				if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
					if ( is_tag() ) {
						return false;
					}
				} else {

					$tag_id = $db_exclusion_child_rule[ $key ];

					if ( is_tag( $tag_id ) ) {
						return false;
					}
				}
			}

			if ( 'post_archive' === $rule ) {
				if ( is_archive() && ! is_post_type_archive() ) {
					return false;
				}
			}

			$post_types = $this->get_filtered_post_types();

			// Work on Easy Digital Downlaods.
			if ( class_exists( 'Easy_Digital_Downloads' ) ) {
				unset( $post_types['download'] );
				if ( 'download' === $rule ) {
					if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
						if ( is_singular( 'download' ) ) {
							return false;
						}
					} else {

						if ( $post_id === absint( $db_exclusion_child_rule[ $key ] ) ) {
							return false;
						}
					}
				}

				if ( 'download_category' === $rule ) {
					if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
						if ( is_tax( 'download_category' ) ) {
							return false;
						}
					} else {
						$download_category_id = $db_exclusion_child_rule[ $key ];

						if ( is_tax( 'download_category', $download_category_id ) ) {
							return false;
						}
					}
				}

				if ( 'download_tag' === $rule ) {
					if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
						if ( is_tax( 'download_tag' ) ) {
							return false;
						}
					} else {

						$download_tag_id = $db_exclusion_child_rule[ $key ];

						if ( is_tax( 'download_tag', $download_tag_id ) ) {
							return false;
						}
					}
				}
			} // End of Easy Digital Downloads work.

			// Work on WooCommerce.
			if ( class_exists( 'WooCommerce' ) ) {
				unset( $post_types['product'] );
				if ( 'product' === $rule ) {
					if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
						if ( is_singular( 'product' ) ) {
							return false;
						}
					} else {

						if ( $post_id === absint( $db_exclusion_child_rule[ $key ] ) ) {
							return false;
						}
					}
				}

				if ( 'shop' === $rule ) {
					if ( is_shop() ) {
						return false;
					}
				}

				if ( 'product_cat' === $rule ) {
					if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
						if ( is_tax( 'product_cat' ) ) {
							return false;
						}
					} else {

						$product_cat_id = $db_exclusion_child_rule[ $key ];

						if ( is_tax( 'product_cat', $product_cat_id ) ) {
							return false;
						}
					}
				}

				if ( 'product_tag' === $rule ) {
					if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
						if ( is_tax( 'product_tag' ) ) {
							return false;
						}
					} else {

						$product_tag_id = $db_exclusion_child_rule[ $key ];

						if ( is_tax( 'product_tag', $product_tag_id ) ) {
							return false;
						}
					}
				}
			} // End of WooCommerce work.

			if ( 'pages' === $rule ) {
				if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
					if ( is_page() ) {
						return false;
					}
				} else {

					if ( $post_id === absint( $db_exclusion_child_rule[ $key ] ) ) {
						return false;
					}
				}
			}

			foreach ( array_keys( $post_types ) as $post_type_key ) {
				if ( $rule === $post_type_key ) {
					if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
						if ( is_singular( $post_type_key ) ) {
							return false;
						}
					} else {

						if ( $post_id === absint( $db_exclusion_child_rule[ $key ] ) ) {
							return false;
						}
					}
				}

				if ( $post_type_key . '_archive' === $rule ) {
					if ( is_post_type_archive( $post_type_key ) ) {
						return false;
					}
				}
			}

			foreach ( $taxonomies as $tax_name => $tax ) {
				if ( $tax_name === $rule ) {
					if ( 'all' === $db_exclusion_child_rule[ $key ] ) {
						if ( is_tax( $tax_name ) ) {
							return false;
						}
					} else {

						$term_id = $db_exclusion_child_rule[ $key ];

						if ( is_tax( $tax_name, $term_id ) ) {
							return false;
						}
					}
				}
			}
		}

		foreach ( $db_parent_rule as $key => $rule ) {
			if ( 'entire_site' === $rule ) {
				return true;
			}

			if ( 'all_archive' === $rule ) {
				if ( is_archive() ) {
					return true;
				}
			}

			if ( 'author_archive' === $rule ) {
				if ( 'all' === $db_child_rule[ $key ] ) {
					if ( is_author() ) {
						return true;
					}
				} else {

					$author_id = $db_child_rule[ $key ];

					if ( is_author( $author_id ) ) {
						return true;
					}
				}
			}

			if ( 'date_archive' === $rule ) {
				if ( is_date() ) {
					return true;
				}
			}

			if ( 'blog_page' === $rule ) {
				if ( is_home() ) {
					return true;
				}
			}

			if ( 'search' === $rule && is_search() ) {
				return true;
			}

			if ( '404' === $rule ) {
				if ( is_404() ) {
					return true;
				}
			}

			if ( 'posts' === $rule ) {
				if ( 'all' === $db_child_rule[ $key ] ) {
					if ( is_singular( 'post' ) ) {
						return true;
					}
				} else {
					if ( $post_id === absint( $db_child_rule[ $key ] ) ) {
						return true;
					}
				}
			}

			if ( 'post_category' === $rule ) {
				if ( 'all' === $db_child_rule[ $key ] ) {
					if ( is_category() ) {
						return true;
					}
				} else {
					$category_id = $db_child_rule[ $key ];

					if ( is_category( $category_id ) ) {
						return true;
					}
				}
			}

			if ( 'post_tag' === $rule ) {
				if ( 'all' === $db_child_rule[ $key ] ) {
					if ( is_tag() ) {
						return true;
					}
				} else {
					$tag_id = $db_child_rule[ $key ];

					if ( is_tag( $tag_id ) ) {
						return true;
					}
				}
			}

			if ( 'post_archive' === $rule ) {
				if ( is_archive() && ! is_post_type_archive() ) {
					return true;
				}
			}

			$post_types = $this->get_filtered_post_types();

			// Easy Digital Downloads work.
			if ( class_exists( 'Easy_Digital_Downloads' ) ) {
				unset( $post_types['download'] );
				if ( 'download' === $rule ) {
					if ( 'all' === $db_child_rule[ $key ] ) {
						if ( is_singular( 'download' ) ) {
							return true;
						}
					} else {
						if ( $post_id === absint( $db_child_rule[ $key ] ) ) {
							return true;
						}
					}
				}

				if ( 'download_category' === $rule ) {
					if ( 'all' === $db_child_rule[ $key ] ) {
						if ( is_tax( 'download_category' ) ) {
							return true;
						}
					} else {

						$download_category_id = $db_child_rule[ $key ];

						if ( is_tax( 'download_category', $download_category_id ) ) {
							return true;
						}
					}
				}

				if ( 'download_tag' === $rule ) {
					if ( 'all' === $db_child_rule[ $key ] ) {
						if ( is_tax( 'download_tag' ) ) {
							return true;
						}
					} else {

						$download_tag_id = $db_child_rule[ $key ];

						if ( is_tax( 'download_tag', $download_tag_id ) ) {
							return true;
						}
					}
				}
			} // End of Easy Digital Downloads work.

			// WooCommerce work.
			if ( class_exists( 'WooCommerce' ) ) {
				unset( $post_types['product'] );
				if ( 'product' === $rule ) {
					if ( 'all' === $db_child_rule[ $key ] ) {
						if ( is_singular( 'product' ) ) {
							return true;
						}
					} else {

						if ( $post_id === absint( $db_child_rule[ $key ] ) ) {
							return true;
						}
					}
				}

				if ( 'shop' === $rule ) {
					if ( is_shop() ) {
						return true;
					}
				}

				if ( 'product_cat' === $rule ) {
					if ( 'all' === $db_child_rule[ $key ] ) {
						if ( is_tax( 'product_cat' ) ) {
							return true;
						}
					} else {

						$product_cat_id = $db_child_rule[ $key ];

						if ( is_tax( 'product_cat', $product_cat_id ) ) {
							return true;
						}
					}
				}

				if ( 'product_tag' === $rule ) {
					if ( 'all' === $db_child_rule[ $key ] ) {
						if ( is_tax( 'product_tag' ) ) {
							return true;
						}
					} else {

						$product_tag_id = $db_child_rule[ $key ];

						if ( is_tax( 'product_tag', $product_tag_id ) ) {
							return true;
						}
					}
				}
			} // End of WooCommerce work.

			if ( 'pages' === $rule ) {
				if ( 'all' === $db_child_rule[ $key ] ) {
					if ( is_page() ) {
						return true;
					}
				} else {

					if ( $post_id === absint( $db_child_rule[ $key ] ) ) {
						return true;
					}
				}
			}

			foreach ( array_keys( $post_types ) as $post_type_key ) {
				if ( $rule === $post_type_key ) {
					if ( 'all' === $db_child_rule[ $key ] ) {
						if ( is_singular( $post_type_key ) ) {
							return true;
						}
					} else {

						if ( $post_id === absint( $db_child_rule[ $key ] ) ) {
							return true;
						}
					}
				}

				if ( $post_type_key . '_archive' === $rule ) {
					if ( is_post_type_archive( $post_type_key ) ) {
						return true;
					}
				}
			}

			foreach ( $taxonomies as $tax_name => $tax ) {
				if ( $tax_name === $rule ) {
					if ( 'all' === $db_child_rule[ $key ] ) {
						if ( is_tax( $tax_name ) ) {
							return true;
						}
					} else {

						$term_id = $db_child_rule[ $key ];

						if ( is_tax( $tax_name, $term_id ) ) {
							return true;
						}
					}
				}
			}
		}

		return false;

	}

	/**
	 * Make sure our admin menu item is highlighted.
	 */
	public function fix_current_item() {

		global $parent_file, $submenu_file, $post_type;

		if ( 'wpbf_hooks' === $post_type ) {
			$parent_file  = 'themes.php';
			$submenu_file = 'edit.php?post_type=wpbf_hooks';
		}

	}

	/**
	 * Register custom post type.
	 */
	public function register_cpt() {

		$labels = array(
			'name'          => _x( 'Custom Sections', 'Post Type General Name', 'wpbfpremium' ),
			'singular_name' => _x( 'Custom Section', 'Post Type Singular Name', 'wpbfpremium' ),
			'menu_name'     => __( 'Custom Sections', 'wpbfpremium' ),
			'all_items'     => __( 'All Custom Sections', 'wpbfpremium' ),
			'add_new_item'  => __( 'Add New Custom Section', 'wpbfpremium' ),
			'new_item'      => __( 'New Custom Section', 'wpbfpremium' ),
			'edit_item'     => __( 'Edit Custom Section', 'wpbfpremium' ),
			'update_item'   => __( 'Update Custom Section', 'wpbfpremium' ),
			'search_items'  => __( 'Search Custom Sections', 'wpbfpremium' ),
		);

		$args = array(
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'show_in_rest'        => true,
		);

		register_post_type( 'wpbf_hooks', $args );

	}

	/**
	 * Register custom post type columns.
	 *
	 * @param array $columns The columns.
	 *
	 * @return array The updated columns.
	 */
	public function register_columns( $columns ) {

		$columns['wpbf_hook_action'] = esc_html__( 'Location', 'wpbfpremium' );

		$new_columns = array();

		foreach ( $columns as $key => $value ) {

			if ( 'date' === $key ) {
				$new_columns['wpbf_hook_action'] = esc_html__( 'Location', 'wpbfpremium' );
			}

			$new_columns[ $key ] = $value;

		}

		return $new_columns;

	}

	/**
	 * Add content to custom post type columns.
	 *
	 * @param string $column The column.
	 * @param string $post_id The post ID.
	 */
	public function add_columns( $column, $post_id ) {

		if ( 'wpbf_hook_action' === $column ) {

			$location = get_post_meta( $post_id, '_wpbf_hook_location', true );
			$action   = get_post_meta( $post_id, '_wpbf_hook_action', true );

			if ( 'hooks' !== $location ) {
				echo ucfirst( $location );
			} else {
				echo $action;
			}
		}

	}

	/**
	 * CPT update messages.
	 *
	 * @param array $messages The update messages.
	 *
	 * @return array The updated wpbf_hooks update messages.
	 */
	public function cpt_messages( $messages ) {

		$post = get_post();

		$messages['wpbf_hooks'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Section updated.', 'wpbfpremium' ),
			2  => __( 'Custom field updated.' ),
			3  => __( 'Custom field deleted.' ),
			4  => __( 'Section updated.', 'wpbfpremium' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Section restored to revision from %s', 'wpbfpremium' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Section published.', 'wpbfpremium' ),
			7  => __( 'Section saved.', 'wpbfpremium' ),
			8  => __( 'Section submitted.', 'wpbfpremium' ),
			9  => sprintf(
				__( 'Section scheduled for: <strong>%1$s</strong>.', 'wpbfpremium' ),
				date_i18n( __( 'M j, Y @ G:i', 'wpbfpremium' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Section draft updated.', 'wpbfpremium' ),
		);

		return $messages;

	}

	/**
	 * Add metaboxes.
	 */
	public function meta_box() {

		add_meta_box(
			'wpbf_hooks_location',
			__( 'Location', 'wpbfpremium' ),
			array( $this, 'metabox_callback' ),
			'wpbf_hooks'
		);

		add_meta_box(
			'wpbf_hooks_display_rules',
			__( 'Display Rules', 'wpbfpremium' ),
			array( $this, 'display_rules_metabox_callback' ),
			'wpbf_hooks'
		);

		add_meta_box(
			'wpbf_hooks_breakpoint_rules',
			__( 'Responsive', 'wpbfpremium' ),
			array( $this, 'breakpoint_rules_metabox_callback' ),
			'wpbf_hooks'
		);

		add_meta_box(
			'wpbf_hooks_user_access',
			__( 'User Access', 'wpbfpremium' ),
			array( $this, 'user_access_metabox_callback' ),
			'wpbf_hooks'
		);

		add_meta_box( 'wpbf_hook_sidebar_settings', __( 'Theme Hooks', 'wpbfpremium' ), array( $this, 'sidebar_metabox_callback' ), 'wpbf_hooks', 'side', 'default' );

	}

	/**
	 * The hooks list.
	 */
	public function hook_list() {

		return apply_filters(
			'wpbf_custom_section_hooks',
			array(
				__( 'General', 'wpbfpremium' )    => array(
					'wpbf_body_open',
					'wpbf_content_open',
					'wpbf_inner_content_open',
					'wpbf_main_content_open',
					'wpbf_before_page_title',
					'wpbf_after_page_title',
					'wpbf_entry_content_open',
					'wpbf_entry_content_close',
					'wpbf_main_content_close',
					'wpbf_inner_content_close',
					'wpbf_content_close',
					'wpbf_body_close',
				),
				__( 'Pre Header', 'wpbfpremium' ) => array(
					'wpbf_before_pre_header',
					'wpbf_pre_header_open',
					'wpbf_pre_header_left_open',
					'wpbf_pre_header_left_close',
					'wpbf_pre_header_right_open',
					'wpbf_pre_header_right_close',
					'wpbf_pre_header_close',
					'wpbf_after_pre_header',
				),
				__( 'Header', 'wpbfpremium' )     => array(
					'wpbf_before_header',
					'wpbf_header_open',
					'wpbf_header_close',
					'wpbf_after_header',
				),
				__( 'Navigation', 'wpbfpremium' ) => array(
					'wpbf_before_main_navigation',
					'wpbf_before_main_menu',
					'wpbf_main_menu_open',
					'wpbf_main_menu_close',
					'wpbf_after_main_menu',
					'wpbf_before_mobile_menu',
					'wpbf_mobile_menu_open',
					'wpbf_mobile_menu_close',
					'wpbf_after_mobile_menu',
					'wpbf_after_main_navigation',
					'wpbf_before_menu_toggle',
					'wpbf_after_menu_toggle',
					'wpbf_before_mobile_toggle',
					'wpbf_after_mobile_toggle',
				),
				__( 'Logo', 'wpbfpremium' )       => array(
					'wpbf_before_logo',
					'wpbf_after_logo',
					'wpbf_before_mobile_logo',
					'wpbf_after_mobile_logo',
				),
				__( 'Archives', 'wpbfpremium' )   => array(
					'wpbf_before_loop',
					'wpbf_after_loop',
				),
				__( 'Sidebar', 'wpbfpremium' )    => array(
					'wpbf_before_sidebar',
					'wpbf_sidebar_open',
					'wpbf_after_sidebar',
					'wpbf_sidebar_close',
				),
				__( 'Footer', 'wpbfpremium' )     => array(
					'wpbf_before_footer',
					'wpbf_footer_open',
					'wpbf_footer_close',
					'wpbf_after_footer',
				),
				__( 'Post Meta', 'wpbfpremium' )  => array(
					'wpbf_before_article_meta',
					'wpbf_article_meta_open',
					'wpbf_before_author_meta',
					'wpbf_after_author_meta',
					'wpbf_before_date_meta',
					'wpbf_after_date_meta',
					'wpbf_before_comments_meta',
					'wpbf_after_comments_meta',
					'wpbf_article_meta_close',
					'wpbf_after_article_meta',
				),
				__( 'Posts', 'wpbfpremium' )      => array(
					'wpbf_before_article',
					'wpbf_article_open',
					'wpbf_before_post_links',
					'wpbf_post_links',
					'wpbf_after_post_links',
					'wpbf_article_close',
					'wpbf_after_article',
				),
				__( 'Comments', 'wpbfpremium' )   => array(
					'wpbf_before_comments',
					'wpbf_before_comment_form',
					'wpbf_after_comment_form',
					'wpbf_after_comments',
				),
			)
		);

	}

	/**
	 * Display theme hooks.
	 */
	public function frontend_show_hooks() {

		if ( ! isset( $_GET['wpbf_hooks'] ) ) {
			return;
		}

		$actions = array_reduce(
			$this->hook_list(),
			function ( $carry, $item ) {
				$carry = array_merge( $carry, $item );

				return $carry;
			},
			array()
		);

		foreach ( $actions as $action ) {

			add_action(
				$action,
				function () use ( $action ) {
					echo '<div style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding: 8px; margin: 5px; line-height: 1; border-radius: 4px; font-size: 13px; font-weight: 700; color: #000; background: #f9e880;">' . $action . '</div>';
				}
			);

		}

	}

	/**
	 * Theme hooks admin bar link.
	 *
	 * @param object $wp_admin_bar The wp admin bar object
	 */
	public function display_hooks( $wp_admin_bar ) {

		if ( apply_filters( 'wpbf_disable_hooks_guide', false ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) || is_admin() ) {
			return;
		}

		global $wp;
		$current_url = home_url( add_query_arg( array(), $wp->request ) );

		if ( ! isset( $_GET['wpbf_hooks'] ) ) {

			$args = array(
				'id'    => 'wpbf-hooks',
				'title' => __( 'Display Theme Hooks', 'wpbfpremium' ),
				'href'  => trailingslashit( $current_url ) . '?wpbf_hooks',
				'meta'  => array(
					'target' => '_self',
					'class'  => 'wpbf-hooks-inactive',
					'title'  => __( 'Display Theme Hooks', 'wpbfpremium' ),
				),
			);

		} else {

			$args = array(
				'id'    => 'wpbf-hooks',
				'title' => __( 'Hide Theme Hooks', 'wpbfpremium' ),
				'href'  => trailingslashit( $current_url ),
				'meta'  => array(
					'target' => '_self',
					'class'  => 'wpbf-hooks-active',
					'title'  => __( 'Hide Theme Hooks', 'wpbfpremium' ),
				),
			);

		}

		$wp_admin_bar->add_node( $args );

	}

	/**
	 * The metabox callback.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function metabox_callback( $post ) {

		wp_nonce_field( 'wpbf_hook_nonce', 'wpbf_hook_nonce' );

		$location = get_post_meta( $post->ID, '_wpbf_hook_location', true );
		$action   = get_post_meta( $post->ID, '_wpbf_hook_action', true );
		$priority = get_post_meta( $post->ID, '_wpbf_hook_priority', true );

		?>

		<table class="form-table wpbf-table">
			<tbody>
				<tr>
					<th class="wpbf-th">
						<label><?php esc_attr_e( 'Location', 'wpbfpremium' ); ?></label>
					</th>
					<td class="wpbf-td">
						<select id="wpbf_hook_location" name="wpbf_hook_location">
							<option value="hooks" <?php selected( 'hooks', $location ); ?>>Hooks</option>
							<option value="header" <?php selected( 'header', $location ); ?>>Header</option>
							<option value="footer" <?php selected( 'footer', $location ); ?>>Footer</option>
							<option value="404" <?php selected( '404', $location ); ?>>404 Page</option>
						</select>
					</td>
				</tr>
				<tr id="hooks-tr">
					<th class="wpbf-th">
						<label><?php esc_attr_e( 'Hooks', 'wpbfpremium' ); ?></label>
					</th>
					<td class="wpbf-td">
						<select name="wpbf_hook_action">
							<?php foreach ( $this->hook_list() as $optgroup => $hooks ) : ?>
								<optgroup label="<?php echo $optgroup; ?>">
									<?php foreach ( $hooks as $hook ) : ?>
										<option value="<?php echo $hook; ?>" <?php selected( $hook, $action ); ?>><?php echo $hook; ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>

						<?php if ( $this->supported_in_brizy_post_types() ) : ?>
							<p class="description">
								<?php _e( 'These hooks are not available on pages that have Brizy\'s "Blank Template" selected. Please switch to the "Default Template" instead.', 'wpbfpremium' ); ?>
							</p>
						<?php endif; ?>

					</td>
				</tr>
				<tr id="hooks-priority-tr">
					<th class="wpbf-th">
						<label><?php esc_attr_e( 'Priority', 'wpbfpremium' ); ?></label>
					</th>
					<td class="wpbf-td">
						<input type="text" placeholder="10" name="wpbf_hook_priority" value="<?php echo $priority; ?>">
					</td>
				</tr>
			</tbody>
		</table>

		<script>
			jQuery(document).on('ready', function () {
				var contextual_display = function () {
					jQuery('#hooks-tr, #hooks-priority-tr').hide();
					if (this.value === 'hooks') {
						jQuery('#hooks-tr, #hooks-priority-tr').show();
					}
				};
				jQuery('#wpbf_hook_location').change(contextual_display).change();
			})
		</script>

		<?php

	}

	/**
	 * Get posts.
	 *
	 * @param string $post_type The post type.
	 *
	 * @return array The post type array.
	 */
	public function get_posts( $post_type = 'post' ) {

		$posts = get_posts(
			array(
				'posts_per_page' => 1000,
				'post_type'      => $post_type,
			)
		);

		$all_label = sprintf( __( 'All %s', 'wpbfpremium' ), $this->get_post_types()[ $post_type ]['label'] );

		$posts = array_reduce(
			$posts,
			function ( $carry, $item ) {
				$carry[ $item->ID ] = $item->post_title;

				return $carry;
			},
			array( 'all' => $all_label )
		);

		return $posts;

	}

	/**
	 * Get public post types.
	 *
	 * @return array The public post types.
	 */
	public function get_post_types() {

		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		return array_reduce(
			$post_types,
			function ( $carry, \WP_Post_Type $item ) {
				$carry[ $item->name ] = array(
					'label'         => $item->label,
					'singular_name' => $item->labels->singular_name,
				);

				return $carry;
			}
		);

	}

	/**
	 * Get public post types.
	 *
	 * @param  string $post_type_slug The post type slug.
	 * @return array The public taxonomies.
	 */
	public function get_taxonomies( $post_type_slug = '' ) {

		if ( $post_type_slug ) {
			$taxonomies = get_object_taxonomies( $post_type_slug, 'objects' );
		} else {
			$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
		}

		if ( empty( $taxonomies ) ) {
			return array();
		} else {
			return array_reduce(
				$taxonomies,
				function ( $carry, \WP_Taxonomy $taxonomy ) {
					$carry[ $taxonomy->name ] = array(
						'label'         => $taxonomy->label,
						'singular_name' => $taxonomy->labels->singular_name,
					);

					return $carry;
				}
			);
		}

	}

	/**
	 * Get filtered taxonomies.
	 *
	 * @param  string $post_type_slug The post type slug.
	 * @return array The filtered taxonomies.
	 */
	public function get_filtered_taxonomies( $post_type_slug = '' ) {

		$taxonomies = $this->get_taxonomies( $post_type_slug );

		foreach ( $this->excluded_taxonomies as $tax_name ) {
			if ( isset( $taxonomies[ $tax_name ] ) ) {
				unset( $taxonomies[ $tax_name ] );
			}
		}

		return $taxonomies;

	}

	/**
	 * Get filtered post types.
	 *
	 * @return array The filtered post types.
	 */
	public function get_filtered_post_types() {

		$post_types = $this->get_post_types();

		// Remove un-used post types from being displayed in the inclusion & exclusion list.
		foreach ( $this->unused_post_types as $post_type ) {
			if ( isset( $post_types[ $post_type ] ) ) {
				unset( $post_types[ $post_type ] );
			}
		}

		// Remove static post types from dynamic handling, since we manage them manually.
		foreach ( $this->static_post_types as $post_type ) {
			// WooCommerce's "product" and EDD's "download" are not totally static.
			if ( 'product' !== $post_type && 'download' !== $post_type ) {
				if ( isset( $post_types[ $post_type ] ) ) {
					unset( $post_types[ $post_type ] );
				}
			}
		}

		return $post_types;

	}

	/**
	 * Display rule's js templates.
	 */
	public function display_rules_js_templates() {

		$post_types = $this->get_filtered_post_types();

		$post_categories = get_categories(
			array(
				'hide_empty' => false,
			)
		);

		$post_tags = get_tags(
			array(
				'hide_empty' => false,
			)
		);

		$rules = array(
			''        => __( 'Select...', 'wpbfpremium' ),
			'General' => array(
				'entire_site'    => __( 'Entire Site', 'wpbfpremium' ),
				'blog_page'      => __( 'Blog Page', 'wpbfpremium' ),
				'all_archive'    => __( 'All Archive', 'wpbfpremium' ),
				'author_archive' => __( 'Author Archive', 'wpbfpremium' ),
				'date_archive'   => __( 'Date Archive', 'wpbfpremium' ),
				'search'         => __( 'Search Results', 'wpbfpremium' ),
				'404'            => __( '404 Page', 'wpbfpremium' ),
			),
			'Page'    => array(
				'pages' => __( 'Pages', 'wpbfpremium' ),
			),
			'Post'    => array(
				'posts'         => __( 'Posts', 'wpbfpremium' ),
				'post_category' => __( 'Post Category', 'wpbfpremium' ),
				'post_tag'      => __( 'Post Tag', 'wpbfpremium' ),
				'post_archive'  => __( 'Post Archive', 'wpbfpremium' ),
			),
		);

		$old_post_types = $post_types;

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			unset( $post_types['download'] );

			$rules['Download']['download']          = __( 'Downloads', 'wpbfpremium' );
			$rules['Download']['download_category'] = __( 'Downloads Category', 'wpbfpremium' );
			$rules['Download']['download_tag']      = __( 'Downloads Tag', 'wpbfpremium' );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			unset( $post_types['product'] );

			$rules['Product']['product']     = __( 'Products', 'wpbfpremium' );
			$rules['Product']['shop']        = __( 'Shop Page', 'wpbfpremium' );
			$rules['Product']['product_cat'] = __( 'Product Category', 'wpbfpremium' );
			$rules['Product']['product_tag'] = __( 'Product Tag', 'wpbfpremium' );
		}

		foreach ( $post_types as $post_type_key => $post_type ) {
			$rules[ $post_type['singular_name'] ][ $post_type_key ] = $post_type['label'];
		}

		// Restore old post types value.
		$post_types = $old_post_types;

		$posts = $this->get_posts();

		$pages = $this->get_posts( 'page' );

		$authors = get_users( array( 'fields' => array( 'ID', 'user_login' ) ) );

		?>

		<script type="text/html" id="tmpl-display-rule-wrapper">
			<div class="rule-wrapper {{data.kind}}" data-index="{{data.index}}">
				<div class="parent-rule-select">
					{{{ data.parent_rule_tmp }}}
				</div>
				<div class="child-rule-select">
					{{{ data.child_rule_tmp }}}
				</div>
				<div class="rule-remove">
					<i class="remove-rule dashicons dashicons-no-alt"></i>
				</div>
			</div>
		</script>

		<!-- Start of parent's include-exclude -->
		<script type="text/html" id="tmpl-display-rule-parent">
			<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_parent' : 'wpbf_display_rule_parent'; #>
			<select class="{{data.kind}}" name="{{data.kind}}[{{data.index}}]">

				<?php foreach ( $rules as $key => $value ) : ?>
					<?php if ( is_array( $value ) ) : ?>

						<optgroup label="<?php echo $key; ?>">
							<?php foreach ( $value as $key2 => $value2 ) : ?>

								<option value="<?php echo $key2; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $key2; ?>') { #> selected <#}#>><?php echo $value2; ?></option>

								<?php if ( 'general' !== strtolower( $key ) && ! in_array( strtolower( $key ), $this->static_post_types, true ) ) : ?>

									<?php
									$taxonomies = $this->get_taxonomies( $key2 );
									?>

									<?php foreach ( $taxonomies as $tax_name => $tax ) : ?>

										<option value="<?php echo esc_attr( $tax_name ); ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo esc_attr( $tax_name ); ?>') { #> selected <#}#>><?php echo esc_attr( $tax['label'] ); ?></option>

									<?php endforeach; ?>

									<option value="<?php echo $key2; ?>_archive" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $key2; ?>_archive') { #> selected <#}#>><?php echo esc_attr( $key ); ?> <?php _e( 'Archive', 'wpbfpremium' ); ?></option>

								<?php endif; ?>

							<?php endforeach; ?>
						</optgroup>

					<?php else : ?>

						<option value="<?php echo $key; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $key; ?>') { #> selected <#}#>><?php echo $value; ?></option>

					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</script>
		<!-- End of parent's include-exclude -->

		<script type="text/html" id="tmpl-display-rule-posts">
			<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
			<select name="{{data.kind}}[{{data.index}}]">
				<?php foreach ( $posts as $key => $value ) : ?>
					<option value="<?php echo $key; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $key; ?>') { #> selected <#}#>><?php echo $value; ?></option>
				<?php endforeach; ?>
			</select>
		</script>

		<script type="text/html" id="tmpl-display-rule-post_category">
			<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
			<select name="{{data.kind}}[{{data.index}}]">
				<option value="all"
				<# if ( typeof data !== 'undefined' && data.saved_value == 'all') { #> selected <#}#>><?php _e( 'All Post Categories', 'wpbfpremium' ); ?></option>
				<?php foreach ( $post_categories as $post_category ) : ?>
					<option value="<?php echo $post_category->term_id; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $post_category->term_id; ?>') { #> selected <#}#>><?php echo $post_category->name; ?></option>
				<?php endforeach; ?>
			</select>
		</script>

		<script type="text/html" id="tmpl-display-rule-post_tag">
			<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
			<select name="{{data.kind}}[{{data.index}}]">
				<option value="all"
				<# if ( typeof data !== 'undefined' && data.saved_value == 'all') { #> selected <#}#>><?php _e( 'All Tags', 'wpbfpremium' ); ?></option>
				<?php foreach ( $post_tags as $post_tag ) : ?>
					<option value="<?php echo $post_tag->term_id; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $post_tag->term_id; ?>') { #> selected <#}#>><?php echo $post_tag->name; ?></option>
				<?php endforeach; ?>
			</select>
		</script>

		<script type="text/html" id="tmpl-display-rule-pages">
			<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
			<select name="{{data.kind}}[{{data.index}}]">
				<?php foreach ( $pages as $key => $value ) : ?>
					<option value="<?php echo $key; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $key; ?>') { #> selected <#}#>><?php echo $value; ?></option>
				<?php endforeach; ?>
			</select>
		</script>

		<!-- Start of custom post type's posts -->
		<?php foreach ( array_keys( $post_types ) as $post_type_key ) { ?>
			<script type="text/html" id="tmpl-display-rule-<?php echo esc_attr( $post_type_key ); ?>">
				<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>

				<select name="{{data.kind}}[{{data.index}}]">
					<?php foreach ( $this->get_posts( $post_type_key ) as $post_id => $post_title ) : ?>

						<option value="<?php echo $post_id; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $post_id; ?>') { #> selected <#}#>><?php echo $post_title; ?></option>

					<?php endforeach; ?>
				</select>
			</script>
		<?php } ?>
		<!-- End of custom post type's posts -->

		<?php
		$taxonomies = $this->get_filtered_taxonomies();
		?>

		<!-- Start of custom post type's terms -->
		<?php foreach ( $taxonomies as $tax_name => $tax ) : ?>
			<script type="text/html" id="tmpl-display-rule-<?php echo esc_attr( $tax_name ); ?>">
				<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
				<select name="{{data.kind}}[{{data.index}}]">
					<?php
					$terms = get_terms(
						array(
							'taxonomy'   => $tax_name,
							'hide_empty' => false,
						)
					);
					?>

					<option value="all"
					<# if ( typeof data !== 'undefined' && data.saved_value == 'all') { #> selected <#}#>><?php _e( 'All', 'wpbfpremium' ); ?> <?php echo esc_attr( $tax['label'] ); ?></option>

					<?php foreach ( $terms as $term ) : ?>
						<option value="<?php echo $term->term_id; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $term->term_id; ?>') { #> selected <#}#>><?php echo $term->name; ?></option>
					<?php endforeach; ?>
				</select>
			</script>
		<?php endforeach; ?>
		<!-- End of custom post type's terms -->

		<script type="text/html" id="tmpl-display-rule-author_archive">
			<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
			<select name="{{data.kind}}[{{data.index}}]">
				<option value="all"
				<# if ( typeof data !== 'undefined' && data.saved_value == 'all') { #> selected <#}#>><?php _e( 'All Authors', 'wpbfpremium' ); ?></option>
				<?php foreach ( $authors as $author ) : ?>
					<option value="<?php echo $author->ID; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $author->ID; ?>') { #> selected <#}#>><?php echo $author->user_login; ?></option>
				<?php endforeach; ?>
			</select>
		</script>

		<?php

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {

			$edd_categories = get_terms(
				array(
					'taxonomy'   => 'download_category',
					'hide_empty' => false,
				)
			);

			$edd_tags = get_terms(
				array(
					'taxonomy'   => 'download_tag',
					'hide_empty' => false,
				)
			);

			?>

			<script type="text/html" id="tmpl-display-rule-download_category">
				<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
				<select name="{{data.kind}}[{{data.index}}]">
					<option value="all"
					<# if ( typeof data !== 'undefined' && data.saved_value == 'all') { #> selected <#}#>><?php _e( 'All Downloads Categories', 'wpbfpremium' ); ?></option>
					<?php foreach ( $edd_categories as $edd_category ) : ?>
						<option value="<?php echo $edd_category->term_id; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $edd_category->term_id; ?>') { #> selected <#}#>><?php echo $edd_category->name; ?></option>
					<?php endforeach; ?>
				</select>
			</script>
			<script type="text/html" id="tmpl-display-rule-download_tag">
				<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
				<select name="{{data.kind}}[{{data.index}}]">
					<option value="all"
					<# if ( typeof data !== 'undefined' && data.saved_value == 'all') { #> selected <#}#>><?php _e( 'All Downloads Tags', 'wpbfpremium' ); ?></option>
					<?php foreach ( $edd_tags as $edd_tag ) : ?>
						<option value="<?php echo $edd_tag->term_id; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $edd_tag->term_id; ?>') { #> selected <#}#>><?php echo $edd_tag->name; ?></option>
					<?php endforeach; ?>
				</select>
			</script>

			<?php

		}

		if ( class_exists( 'WooCommerce' ) ) {

			$woo_categories = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
				)
			);

			$woo_tags = get_terms(
				array(
					'taxonomy'   => 'product_tag',
					'hide_empty' => false,
				)
			);

			?>

			<script type="text/html" id="tmpl-display-rule-product_cat">
				<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
				<select name="{{data.kind}}[{{data.index}}]">
					<option value="all"
					<# if ( typeof data !== 'undefined' && data.saved_value == 'all') { #> selected <#}#>><?php _e( 'All Product Categories', 'wpbfpremium' ); ?></option>
					<?php foreach ( $woo_categories as $woo_category ) : ?>
						<option value="<?php echo $woo_category->term_id; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $woo_category->term_id; ?>') { #> selected <#}#>><?php echo $woo_category->name; ?></option>
					<?php endforeach; ?>
				</select>
			</script>

			<script type="text/html" id="tmpl-display-rule-product_tag">
				<# data.kind = typeof data.kind !=='undefined' && data.kind === 'exclusion' ? 'wpbf_exclusion_display_rule_child' : 'wpbf_display_rule_child'; #>
				<select name="{{data.kind}}[{{data.index}}]">
					<option value="all"
					<# if ( typeof data !== 'undefined' && data.saved_value == 'all') { #> selected <#}#>><?php _e( 'All Post Tags', 'wpbfpremium' ); ?></option>
					<?php foreach ( $woo_tags as $woo_tag ) : ?>
						<option value="<?php echo $woo_tag->term_id; ?>" <# if ( typeof data !== 'undefined' && data.saved_value == '<?php echo $woo_tag->term_id; ?>') { #> selected <#}#>><?php echo $woo_tag->name; ?></option>
					<?php endforeach; ?>
				</select>
			</script>

			<?php

		}

	}

	/**
	 * The display rule's script.
	 *
	 * @param int $post_id The post ID.
	 */
	public function display_rules_script( $post_id ) {

		$db_parent_rule = get_post_meta( $post_id, '_wpbf_display_rule_parent', true );
		if ( empty( $db_parent_rule ) || ! is_array( $db_parent_rule ) ) {
			$db_parent_rule = array( 1 => 'entire_site' );
		}

		$db_exclusion_parent_rule = get_post_meta( $post_id, '_wpbf_exclusion_display_rule_parent', true );
		if ( empty( $db_exclusion_parent_rule ) || ! is_array( $db_exclusion_parent_rule ) ) {
			$db_exclusion_parent_rule = array( 1 => '' );
		}

		$db_child_rule = get_post_meta( $post_id, '_wpbf_display_rule_child', true );
		if ( empty( $db_child_rule ) || ! is_array( $db_child_rule ) ) {
			$db_child_rule = array();
		}

		$db_exclusion_child_rule = get_post_meta( $post_id, '_wpbf_exclusion_display_rule_child', true );
		if ( empty( $db_exclusion_child_rule ) || ! is_array( $db_exclusion_child_rule ) ) {
			$db_exclusion_child_rule = array();
		}

		$dynamic_archives = '';

		foreach ( $this->dynamic_post_types as $post_type_key => $post_type ) {
			$dynamic_archives .= ", '" . $post_type_key . "_archive'";
		}

		$no_child_rules = "'entire_site', 'search', 'all_archive', 'date_archive', 'blog_page', '404', 'post_archive', 'shop'" . $dynamic_archives;
		?>

		<script type="text/javascript">

			(function ($) {

				var db_parent_rule = JSON.parse('<?php echo wp_json_encode( $db_parent_rule ); ?>');
				var db_child_rule = JSON.parse('<?php echo wp_json_encode( $db_child_rule ); ?>');

				var db_exclusion_parent_rule = JSON.parse('<?php echo wp_json_encode( $db_exclusion_parent_rule ); ?>');
				var db_exclusion_child_rule = JSON.parse('<?php echo wp_json_encode( $db_exclusion_child_rule ); ?>');

				var create_rule_field = function (index, kind, parent_rule_saved_value, child_rule_value_saved_value) {

					parent_rule_saved_value = parent_rule_saved_value || '';
					kind = kind || 'inclusion';
					child_rule_value_saved_value = child_rule_value_saved_value || '';

					var child_rule_tmp = '',
						get_child_rule_tmp,
						parent_rule_tmp = wp.template('display-rule-parent'),
						rule_wrapper = wp.template('display-rule-wrapper');

					if (parent_rule_saved_value !== '' && _.contains([<?php echo $no_child_rules; ?>], parent_rule_saved_value) === false) {

						get_child_rule_tmp = wp.template('display-rule-' + parent_rule_saved_value);

						child_rule_tmp = get_child_rule_tmp({
							saved_value: child_rule_value_saved_value,
							kind: kind,
							index: index
						});

					}

					$('.container-' + kind).append(rule_wrapper({

						parent_rule_tmp: parent_rule_tmp({
							saved_value: parent_rule_saved_value,
							index: index,
							kind: kind
						}),
						child_rule_tmp: child_rule_tmp,
						index: index,
						kind: kind

					}));

				};

				var repeater = function () {

					$('.add-include-rule').click(function (e) {
						e.preventDefault();
						var last_index = $('.rule-wrapper.inclusion').eq(-1).data('index');
						if (typeof last_index === 'undefined') {
							last_index = 0;
						}
						create_rule_field(last_index + 1);
					});

					$('.add-exclude-rule').click(function (e) {
						e.preventDefault();
						var last_index = $('.rule-wrapper.exclusion').eq(-1).data('index');
						if (typeof last_index === 'undefined') {
							last_index = 0;
						}
						create_rule_field(last_index + 1, 'exclusion');
					});

				};

				var remove_rule_listener = function () {

					$(document).on('click', '.remove-rule', function (e) {
						e.preventDefault();
						$(this).parents('.rule-wrapper').remove();
					});

				};

				var parent_rule_change_listener = function () {

					$(document).on('change', '.wpbf_display_rule_parent, .wpbf_exclusion_display_rule_parent', function (e) {

						var template,
							rule_wrapper_obj,
							index,
							kind = 'inclusion',
							select_display_rule = this.value;

						rule_wrapper_obj = $(this).parents('.rule-wrapper');
						index = rule_wrapper_obj.data('index');

						if (e.currentTarget.className == 'wpbf_exclusion_display_rule_parent') {
							kind = 'exclusion';
						}

						template = '';
						if (_.contains([<?php echo $no_child_rules; ?>], select_display_rule) === false) {
							template = wp.template('display-rule-' + select_display_rule)({index: index, kind: kind});
						}

						rule_wrapper_obj.find('.child-rule-select').html(template);

					})

				};

				var on_load = function () {

					// inclusion
					$.each(db_parent_rule, function (index, parent_rule_saved_value) {
						create_rule_field(index, 'inclusion', parent_rule_saved_value, db_child_rule[index]);
					});

					// exclusion
					$.each(db_exclusion_parent_rule, function (index, parent_rule_saved_value) {
						create_rule_field(index, 'exclusion', parent_rule_saved_value, db_exclusion_child_rule[index]);
					});

				};

				$(function () {
					on_load();
					parent_rule_change_listener();
					repeater();
					remove_rule_listener();
				});

			})(jQuery)

		</script>

		<?php

	}

	/**
	 * Breakpoint rules metabox callback.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function breakpoint_rules_metabox_callback( $post ) {

		wp_nonce_field( 'wpbf_hook_nonce', 'wpbf_hook_nonce' );

		$hide_on_desktop = get_post_meta( $post->ID, '_wpbf_hide_on_desktop', true );
		$hide_on_tablet  = get_post_meta( $post->ID, '_wpbf_hide_on_tablet', true );
		$hide_on_mobile  = get_post_meta( $post->ID, '_wpbf_hide_on_mobile', true );
		?>

		<table class="form-table wpbf-table">
			<tbody>

				<tr>
					<th class="wpbf-th">
						<label for="wpbf_hide_on_desktop"><?php esc_attr_e( 'Hide on Desktop', 'wpbfpremium' ); ?></label>
					</th>
					<td class="wpbf-td">

						<div class="wpbf-setting-field">
							<label for="wpbf_hide_on_desktop" class="label checkbox-label">
								<input type="checkbox" name="wpbf_hide_on_desktop" id="wpbf_hide_on_desktop" value="1" <?php checked( $hide_on_desktop, 1 ); ?>>
								<div class="indicator"></div>
							</label>
						</div>

					</td>
				</tr>

				<tr>
					<th class="wpbf-th">
						<label for="wpbf_hide_on_tablet"><?php esc_attr_e( 'Hide on Tablet', 'wpbfpremium' ); ?></label>
					</th>
					<td class="wpbf-td">

						<div class="wpbf-setting-field">
							<label for="wpbf_hide_on_tablet" class="label checkbox-label">
								<input type="checkbox" name="wpbf_hide_on_tablet" id="wpbf_hide_on_tablet" value="1" <?php checked( $hide_on_tablet, 1 ); ?>>
								<div class="indicator"></div>
							</label>
						</div>

					</td>
				</tr>

				<tr>
					<th class="wpbf-th">
						<label for="wpbf_hide_on_mobile"><?php esc_attr_e( 'Hide on Mobile', 'wpbfpremium' ); ?></label>
					</th>
					<td class="wpbf-td">

						<div class="wpbf-setting-field">
							<label for="wpbf_hide_on_mobile" class="label checkbox-label">
								<input type="checkbox" name="wpbf_hide_on_mobile" id="wpbf_hide_on_mobile" value="1" <?php checked( $hide_on_mobile, 1 ); ?>>
								<div class="indicator"></div>
							</label>
						</div>

					</td>
				</tr>

			</tbody>
		</table>

		<?php

	}

	/**
	 * Display rules metabox callback.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function display_rules_metabox_callback( $post ) {

		wp_nonce_field( 'wpbf_hook_nonce', 'wpbf_hook_nonce' );

		?>

		<table class="form-table wpbf-table">
			<tbody>
				<tr>
					<th class="wpbf-th">
						<label><?php esc_attr_e( 'Include', 'wpbfpremium' ); ?></label>
					</th>
					<td class="wpbf-td">
						<div class="container-inclusion">
						</div>
						<button class="button add-include-rule"><?php _e( 'Add Rule', 'wpbfpremium' ); ?></button>
					</td>
				</tr>
				<tr>
					<th class="wpbf-th">
						<label><?php esc_attr_e( 'Exclude', 'wpbfpremium' ); ?></label>
					</th>
					<td class="wpbf-td">
						<div class="container-exclusion">
						</div>
						<button class="button add-exclude-rule"><?php _e( 'Add Rule', 'wpbfpremium' ); ?></button>
					</td>
				</tr>
			</tbody>
		</table>

		<?php
		$this->dynamic_post_types = $this->get_filtered_post_types();

		$this->display_rules_script( $post->ID );
		$this->display_rules_js_templates();

	}

	/**
	 * Logged-in metabox callback.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function user_access_metabox_callback( $post ) {

		wp_nonce_field( 'wpbf_hook_nonce', 'wpbf_hook_nonce' );

		$db_value = get_post_meta( $post->ID, '_wpbf_restrict_access', true );

		if ( empty( $db_value ) ) {
			// Run compatibility check.
			$db_value = get_post_meta( $post->ID, '_wpbf_restrict_logged_users', true );
			$db_value = 'true' === $db_value ? 'logged-in' : 'all';
		}
		?>

		<table class="form-table wpbf-table">
			<tbody>
				<tr>
					<th class="wpbf-th">
						<label for="wpbf_restrict_access"><?php esc_attr_e( 'Show to', 'wpbfpremium' ); ?></label>
					</th>
					<td class="wpbf-td">
						<div class="wpbf-field is-half">
							<select id="wpbf_restrict_access" name="wpbf_restrict_access">
								<option value="all" <?php selected( 'all', $db_value ); ?>>
									<?php esc_html_e( 'All Users', 'wpbfpremium' ); ?>
								</option>
								<option value="logged-in" <?php selected( 'logged-in', $db_value ); ?>>
									<?php esc_html_e( 'Logged-in Users', 'wpbfpremium' ); ?>
								</option>
								<option value="logged-out" <?php selected( 'logged-out', $db_value ); ?>>
									<?php esc_html_e( 'Visitors', 'wpbfpremium' ); ?>
								</option>
							</select>
						</div>
					</td>
				</tr>
			</tbody>
		</table>

		<?php

	}

	/**
	 * Sidebar metabox callback.
	 *
	 * Display a link to display theme hooks in the sidebar.
	 */
	public function sidebar_metabox_callback() {

		?>

		<p class="description">
			<?php _e( 'Display available theme hooks on the frontend of your website.', 'wpbfpremium' ); ?>
		</p>
		<a style="margin-top: 1em" target="_blank" href="<?php echo home_url( '?wpbf_hooks' ); ?>" class="button button-primary button-large">
			<?php _e( 'Display Theme Hooks', 'wpbfpremium' ); ?>
		</a>

		<?php

	}

	/**
	 * Save metabox data.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_meta_box_data( $post_id ) {

		if ( ! isset( $_POST['wpbf_hook_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['wpbf_hook_nonce'], 'wpbf_hook_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$this->save_hooks_metadata( $post_id );
		$this->save_breakpoint_rules( $post_id );
		$this->save_display_rules( $post_id );

		$restrict_access = sanitize_text_field( $_POST['wpbf_restrict_access'] );

		update_post_meta( $post_id, '_wpbf_restrict_access', $restrict_access );

	}

	/**
	 * Save hook's meta data.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_hooks_metadata( $post_id ) {

		update_post_meta( $post_id, '_wpbf_hook_location', sanitize_text_field( $_POST['wpbf_hook_location'] ) );

		if ( ! isset( $_POST['wpbf_hook_action'] ) ) {
			return;
		}

		$action   = sanitize_text_field( $_POST['wpbf_hook_action'] );
		$priority = sanitize_text_field( $_POST['wpbf_hook_priority'] );

		update_post_meta( $post_id, '_wpbf_hook_action', $action );
		update_post_meta( $post_id, '_wpbf_hook_priority', $priority );

	}

	/**
	 * Save breakpoint rules.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_breakpoint_rules( $post_id ) {

		if ( isset( $_POST['wpbf_hide_on_desktop'] ) ) {
			update_post_meta( $post_id, '_wpbf_hide_on_desktop', 1 );
		} else {
			delete_post_meta( $post_id, '_wpbf_hide_on_desktop' );
		}

		if ( isset( $_POST['wpbf_hide_on_tablet'] ) ) {
			update_post_meta( $post_id, '_wpbf_hide_on_tablet', 1 );
		} else {
			delete_post_meta( $post_id, '_wpbf_hide_on_tablet' );
		}

		if ( isset( $_POST['wpbf_hide_on_mobile'] ) ) {
			update_post_meta( $post_id, '_wpbf_hide_on_mobile', 1 );
		} else {
			delete_post_meta( $post_id, '_wpbf_hide_on_mobile' );
		}

	}

	/**
	 * Save display rules metadata.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_display_rules( $post_id ) {

		$display_rule_parent = isset( $_POST['wpbf_display_rule_parent'] ) ? array_map( 'sanitize_text_field', $_POST['wpbf_display_rule_parent'] ) : array();
		$display_rule_child  = '';

		if ( isset( $_POST['wpbf_display_rule_child'] ) ) {
			$display_rule_child = array_map( 'sanitize_text_field', $_POST['wpbf_display_rule_child'] );
		}

		$exclusion_display_rule_parent = isset( $_POST['wpbf_exclusion_display_rule_parent'] ) ? array_map( 'sanitize_text_field', $_POST['wpbf_exclusion_display_rule_parent'] ) : array();
		$exclusion_display_rule_child  = '';

		if ( isset( $_POST['wpbf_exclusion_display_rule_child'] ) ) {
			$exclusion_display_rule_child = array_map( 'sanitize_text_field', $_POST['wpbf_exclusion_display_rule_child'] );
		}

		update_post_meta( $post_id, '_wpbf_display_rule_parent', $display_rule_parent );
		update_post_meta( $post_id, '_wpbf_display_rule_child', $display_rule_child );

		update_post_meta( $post_id, '_wpbf_exclusion_display_rule_parent', $exclusion_display_rule_parent );
		update_post_meta( $post_id, '_wpbf_exclusion_display_rule_child', $exclusion_display_rule_child );

	}

	/**
	 * Create our admin menu item.
	 */
	public function menu_item() {

		add_submenu_page(
			'themes.php',
			esc_html__( 'Custom Sections', 'wpbfpremium' ),
			esc_html__( 'Custom Sections', 'wpbfpremium' ),
			apply_filters( 'wpbf_custom_sections_capability', 'manage_options' ),
			'edit.php?post_type=wpbf_hooks'
		);

	}

	/**
	 * Get instance of this class.
	 *
	 * @return object The current class instance.
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Redirect unallowed users.
	 */
	public function cpt_redirect() {

		if ( is_singular( 'wpbf_hooks' ) && ! current_user_can( 'edit_posts' ) ) {
			wp_safe_redirect( site_url(), 301 );
			die;
		}

	}

}
Custom_Sections::get_instance();
