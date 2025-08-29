<?php
/**
 * Mega menu module.
 *
 * @package Page Builder Framework Premium Add-On
 */

namespace Wpbf\Premium\Modules\MegaMenu;

use Wpbf\Premium\Modules\Base\Base_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup mega menu module.
 */
class Mega_Menu_Module extends Base_Module {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		parent::__construct();

		$this->url = WPBF_PREMIUM_URI . 'inc/modules/mega-menu';

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
		$instance = new self();
		$instance->setup();
	}

	/**
	 * Setup mega menu module.
	 */
	public function setup() {

		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'menu_item_custom_fields' ), 10, 5 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'update_nav_menu_item' ), 10, 3 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );

		// The module output.
		require_once __DIR__ . '/class-mega-menu-output.php';
		Mega_Menu_Output::init();

	}

	/**
	 * Add "Menu Item Settings" button to nav menu item in the menu editor.
	 *
	 * @param int      $item_id Menu item ID.
	 * @param WP_Post  $item Menu item data object.
	 * @param int      $depth Depth of menu item. Used for padding.
	 * @param stdClass $args An object of menu item arguments.
	 * @param int      $id Nav menu ID.
	 */
	public function menu_item_custom_fields( $item_id, $item, $depth, $args, $id ) {

		$field_name   = '_wpbf_menu_item_' . esc_attr( $item->ID ) . '_mega_menu_dropdown_custom_width';
		$meta_key     = '_wpbf_mega_menu_dropdown_custom_width';
		$custom_width = get_post_meta( $item->ID, $meta_key, true );
		?>

		<input type="hidden" class="wpbf-mega-menu-custom-width-db" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $custom_width ); ?>">

		<p class="description-wide wpbf-menu-item-settings-button-wrapper">
			<button type="button" class="button wpbf-menu-item-settings-button" data-nav-menu-id="<?php echo esc_attr( $id ); ?>" data-menu-item-id="<?php echo esc_attr( $item_id ); ?>">
				<?php _e( 'Menu Item Settings', 'wpbfpremium' ); ?>
			</button>
		</p>

		<?php

	}

	/**
	 * Save our menu item's custom fields.
	 *
	 * @param int   $menu_id (Required) The ID of the menu. Required. If "0", makes the menu item a draft orphan.
	 * @param int   $menu_item_db_id (Required) The ID of the menu item. If "0", creates a new menu item.
	 * @param array $args (Optional) The menu item's data.
	 */
	public function update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {

		$field_name = '_wpbf_menu_item_' . esc_attr( $menu_item_db_id ) . '_mega_menu_dropdown_custom_width';
		$meta_key   = '_wpbf_mega_menu_dropdown_custom_width';

		if ( isset( $_POST[ $field_name ] ) ) {
			$custom_width = sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) );

			update_post_meta( $menu_item_db_id, $meta_key, $custom_width );
		} else {
			delete_post_meta( $menu_item_db_id, $meta_key );
		}

	}

	/**
	 * Enqueue admin styles.
	 */
	public function admin_styles() {

		$current_screen = get_current_screen();

		if ( 'nav-menus' !== $current_screen->id ) {
			return;
		}

		wp_enqueue_style( 'wpbf-menu-item-settings', $this->url . '/assets/css/menu-item-settings.css', array(), WPBF_PREMIUM_VERSION );

	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_scripts() {

		$current_screen = get_current_screen();

		if ( 'nav-menus' !== $current_screen->id ) {
			return;
		}

		wp_enqueue_script( 'wpbf-menu-item-settings', $this->url . '/assets/js/menu-item-settings.js', array( 'jquery' ), WPBF_PREMIUM_VERSION, true );

		wp_localize_script( 'wpbf-menu-item-settings', 'wpbfMenuItemSettings', array() );

	}

	/**
	 * Function to run on admin_footer hook.
	 */
	public function admin_footer() {

		$current_screen = get_current_screen();

		if ( 'nav-menus' !== $current_screen->id ) {
			return;
		}

		require __DIR__ . '/templates/popup.php';

	}

}
