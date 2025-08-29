<?php
/**
 * Mega menu output.
 *
 * @package Page Builder Framework Premium Add-On
 */

namespace Wpbf\Premium\Modules\MegaMenu;

use Wpbf\Premium\Modules\Base\Base_Output;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup mega menu output.
 */
class Mega_Menu_Output extends Base_Output {

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

		add_filter( 'wp_nav_menu', array( $this, 'mega_menu_styles' ), 10, 2 );
		add_filter( 'nav_menu_css_class', array( $this, 'check_classes' ), 10, 4 );

	}

	/**
	 * Filters a menu item’s starting output.
	 *
	 * @see wp_nav_menu()
	 *
	 * @param string   $nav_menu The HTML content for the navigation menu.
	 * @param stdClass $args An object containing wp_nav_menu() arguments.
	 */
	public function mega_menu_styles( $nav_menu, $args ) {

		if ( 'mobile_menu' === $args->theme_location ) {
			return $nav_menu;
		}

		if ( ! is_object( $args->menu ) || ! property_exists( $args->menu, 'term_id' ) ) {
			return $nav_menu;
		}

		$menu_items = get_posts(
			array(
				'post_type'      => 'nav_menu_item',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'meta_key'       => '_menu_item_menu_item_parent',
				'meta_value'     => 0,
				'tax_query'      => array(
					array(
						'taxonomy' => 'nav_menu',
						'field'    => 'term_id',
						'terms'    => $args->menu->term_id,
					),
				),
			)
		);

		if ( ! $menu_items ) {
			return $nav_menu;
		}

		ob_start();
		?>

		<style class="wpbf-mega-menu-styles">
			<?php
			foreach ( $menu_items as $menu_item ) {
				$id = $menu_item->ID;

				$class_names = get_post_meta( $id, '_menu_item_classes', true );

				if ( ! in_array( 'wpbf-mega-menu-custom-width', $class_names, true ) ) {
					continue;
				}

				$custom_width = get_post_meta( $id, '_wpbf_mega_menu_dropdown_custom_width', true );
				$custom_width = $custom_width ? $custom_width : '400px';
				?>

				.wpbf-mega-menu-custom-width.menu-item-<?php echo esc_attr( $id ); ?> > .sub-menu {
					width: <?php echo esc_attr( $custom_width ); ?>;
				}

				<?php
			}
			?>
		</style>

		<?php
		return ob_get_clean() . $nav_menu;

	}

	/**
	 * Check menu item's class names.
	 * Remove mega menu's class names if it's not a top level menu item.
	 *
	 * @see wp-includes/class-walker-nav-menu.php
	 *
	 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
	 * @param WP_Post  $item    The current menu item.
	 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
	 * @param int      $depth Depth of menu item. Used for padding.
	 *
	 * @return string[]
	 */
	public function check_classes( $classes, $item, $args, $depth ) {

		if ( 0 === $depth ) {
			return $classes;
		}

		$class_names = array();

		foreach ( $classes as $class_name ) {
			if ( ! empty( $class_name ) && false === stripos( $class_name, 'wpbf-mega-menu' ) ) {
				array_push( $class_names, $class_name );
			}
		}

		$classes = $class_names;

		return $classes;

	}

}
