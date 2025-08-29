<?php
/**
 * WooCommerce quick view.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

namespace WPBF\WooCommerce;

/**
 * Class to setup quick view module.
 */
class Quickview {

	/**
	 * URL of this module.s
	 *
	 * @var string
	 */
	public $url;

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = WPBF_PREMIUM_URI . '/inc/integration/woocommerce';

	}

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup quick view module.
	 */
	public function setup() {

		// Quick view AJAX.
		add_action( 'wp_ajax_wpbf_load_product_quick_view', array( self::get_instance(), 'get_quick_view' ) );
		add_action( 'wp_ajax_nopriv_wpbf_load_product_quick_view', array( self::get_instance(), 'get_quick_view' ) );

		// Prevent redirection inside `wpbf_add_to_cart` ajax request.
		add_filter( 'wp_redirect', array( self::get_instance(), 'prevent_redirection' ), 20, 2 );

		// Add quick view button.
		add_action( 'woocommerce_before_shop_loop_item_title', array( self::get_instance(), 'add_quick_view_button' ), 11 );

		// Add empty div to the footer to populate div with response from ajax request.
		add_action( 'wp_footer', array( self::get_instance(), 'quick_view_wrapper' ) );

		$this->quickview_content();

		add_action( 'wp_ajax_wpbf_add_to_cart', array( self::get_instance(), 'add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_wpbf_add_to_cart', array( self::get_instance(), 'add_to_cart' ) );

	}

	/**
	 * Prevent redirection inside `wpbf_add_to_cart` ajax request.
	 *
	 * @param string $location The existing redirection url.
	 * @param int    $status The redirection http status.
	 *
	 * @return string The modified redirection url.
	 */
	public function prevent_redirection( $location, $status ) {

		if ( ! wp_doing_ajax() || ! isset( $_POST['action'] ) || 'wpbf_add_to_cart' !== $_POST['action'] ) {
			return $location;
		}

		return false;

	}

	/**
	 * Construct quick view popup.
	 */
	public function quick_view_wrapper() {

		// Stop here if quick view is disabled or we're not on a shop archive.
		if ( 'disabled' === get_theme_mod( 'woocommerce_loop_quick_view' ) ) {
			return;
		}

		// Load necessary JS.
		$this->quick_view_scripts();
		?>

		<div class="wpbf-woo-quick-view-modal wpbf-clearfix">

			<?php if ( wpbf_svg_enabled() ) { ?>
				<span class="wpbf-close">
					<?php echo wpbf_svg( 'times' ); ?>
				</span>
			<?php } else { ?>
				<i class="wpbf-close wpbff wpbff-times" arial-hidden="true"></i>
			<?php } ?>

			<div class="wpbf-woo-quick-view-modal-content">
				<div class="wpbf-woo-quick-view-modal-main">
					<div id="wpbf-woo-quick-view-content" class="woocommerce single-product"></div>
				</div>
			</div>
		</div>

		<?php

	}

	/**
	 * Enqueue necessary styles & scripts.
	 */
	public function quick_view_scripts() {

		/**
		 * Enable Zoom for profuct image.
		 * Enable Auto Change Image for products with mutiple images, when product variation is selected.
		 */
		wp_enqueue_script( 'wc-add-to-cart-variation' );

		if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {

			if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
				wp_enqueue_script( 'zoom' );
			}

			if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {

				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_style( 'photoswipe-default-skin' );

				if ( has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
					add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
				}
			}

			wp_enqueue_script( 'wc-single-product' );

		}

	}

	/**
	 * Handling the ajax add to cart.
	 *
	 * @see wp-content/plugins/woocommerce/includes/class-wc-form-handler.php
	 */
	public function add_to_cart() {

		$wpbf_settings        = get_option( 'wpbf_settings' );
		$woo_should_redirect  = get_option( 'woocommerce_cart_redirect_after_add' );
		$wpbf_should_redirect = isset( $wpbf_settings['wpbf_cart_redirect_after_add'] ) ? true : false;

		// If product was successfully added to cart.
		if ( wc_notice_count( 'success' ) > 0 ) {
			$notices = wc_get_notices( 'success' );

			// If we're not going to redirect, then clear all wc notices.
			if ( 'yes' !== $woo_should_redirect || ! $wpbf_should_redirect ) {
				wc_clear_notices();
			}

			wp_send_json_success( $notices );
		} else {
			$notice_notices = wc_get_notices( 'notice' );
			$error_notices  = wc_get_notices( 'error' );
			$notices        = array_merge( $error_notices, $error_notices );

			// If we're not going to redirect, then clear all wc notices.
			if ( 'yes' !== $woo_should_redirect || ! $wpbf_should_redirect ) {
				wc_clear_notices();
			}

			wp_send_json_error( $notices, 401 );
		}

	}

	/**
	 * Ajax handler view.
	 */
	public function get_quick_view() {

		if ( ! isset( $_GET['product_id'] ) ) {
			wp_send_json_error( __( 'Product id is required', 'wpbfpremium' ), 401 );
		}

		$product_id = absint( $_GET['product_id'] );

		if ( ! get_post( $product_id ) ) {
			wp_send_json_error( __( "Product not found", 'wpbfpremium' ), 401 );
		}

		// Set the main wp query for the product.
		wp( 'p=' . $product_id . '&post_type=product' );

		// Remove product thumbnails gallery.
		remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

		ob_start();

		while ( have_posts() ) :
			the_post();
			?>

			<div class="product">
				<div id="product-<?php the_ID(); ?>" <?php post_class( 'product' ); ?>>
					<?php do_action( 'wpbf_woo_quick_view_product_image' ); ?>
					<div class="summary entry-summary">
						<div class="summary-content">
							<?php do_action( 'wpbf_woo_quick_view_product_summary' ); ?>
						</div>
					</div>
				</div>
			</div>

			<?php
		endwhile;

		$output = ob_get_clean();

		wp_send_json_success( $output );

	}

	/**
	 * Construct quick view content.
	 */
	public function quickview_content() {

		// Image.
		add_action( 'wpbf_woo_quick_view_product_image', 'woocommerce_show_product_images' );

		// Title.
		do_action( 'wpbf_woo_quick_view_before_title' );
		add_action( 'wpbf_woo_quick_view_product_summary', 'woocommerce_template_single_title' );
		do_action( 'wpbf_woo_quick_view_after_title' );

		// Rating.
		do_action( 'wpbf_woo_quick_view_before_rating' );
		add_action( 'wpbf_woo_quick_view_product_summary', 'woocommerce_template_single_rating' );
		do_action( 'wpbf_woo_quick_view_after_rating' );

		// Price.
		do_action( 'wpbf_woo_quick_view_before_price' );
		add_action( 'wpbf_woo_quick_view_product_summary', 'woocommerce_template_single_price' );
		do_action( 'wpbf_woo_quick_view_after_price' );

		// Excerpt.
		do_action( 'wpbf_woo_quick_view_before_excerpt' );
		add_action( 'wpbf_woo_quick_view_product_summary', 'woocommerce_template_single_excerpt' );
		do_action( 'wpbf_woo_quick_view_after_excerpt' );

		// Quantity & add to cart button.
		do_action( 'wpbf_woo_quick_view_before_add_to_cart' );
		add_action( 'wpbf_woo_quick_view_product_summary', 'woocommerce_template_single_add_to_cart' );
		do_action( 'wpbf_woo_quick_view_after_add_to_cart' );

		// Meta.
		do_action( 'wpbf_woo_quick_view_before_meta' );
		add_action( 'wpbf_woo_quick_view_product_summary', 'woocommerce_template_single_meta' );
		do_action( 'wpbf_woo_quick_view_after_meta' );

	}

	/**
	 * Quick view button.
	 *
	 * Add quick view button to products.
	 */
	public function add_quick_view_button( $product_id = 0 ) {

		// Stop here if quick view is disabled or we're not on a shop archive.
		if ( 'disabled' === get_theme_mod( 'woocommerce_loop_quick_view' ) ) {
			return;
		}

		global $product;

		$product_id = $product->get_id();

		echo '<a href="javascript:void(0)" id="product_id_' . $product_id . '" class="wpbf-woo-quick-view" data-product_id="' . $product_id . '" aria-hidden="true">' . esc_attr( apply_filters( 'wpbf_woo_quick_view_label', __( 'Quick View', 'wpbfpremium' ) ) ) . '</a>';

	}

}

Quickview::init();
