<?php
/**
 * Plugin Name: DeepL for WordPress : translation plugin PRO
 * Description: Get DeepL translation magic right inside your WordPress editor (with a paid DeepL Pro account)
 * Version: 2.7.1
 * Plugin Slug: wpdeeplpro
 * Author: Fluenx
 * Author URI: https://www.fluenx.com/
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Text Domain: wpdeeplpro
 * Domain Path: /languages
 */

//return;
defined( 'WPDEEPLPRO_NAME' ) 		or define( 'WPDEEPLPRO_NAME', 		plugin_basename( __FILE__ ) ); // plugin name as known by WP.
defined( 'WPDEEPLPRO_DIR' ) 		or define( 'WPDEEPLPRO_DIR', 		dirname( __FILE__ ) ); // our directory.
defined( 'WPDEEPLPRO_PATH' ) 		or define( 'WPDEEPLPRO_PATH', 		realpath( __DIR__ ) ); // our directory.
defined( 'WPDEEPLPRO_URL' ) 		or define( 'WPDEEPLPRO_URL', 		plugins_url( '', __FILE__ ) );
$plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ), false );
defined( 'WPDEEPLPRO_VERSION' ) 	or define( 'WPDEEPLPRO_VERSION', 	$plugin_data['Version'] );

defined('WPDEEPLPRO_DEBUG')			or define('WPDEEPLPRO_DEBUG', false );

try {
	include_once trailingslashit( ABSPATH ) . 'wp-admin/includes/plugin.php';
	if( 
		( is_admin() || get_option('wpdeepl_allow_front_end') == 'yes' )
		&& 
		is_plugin_active( 'wpdeepl/wpdeepl.php' ) ) {

		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'keypress-api-client.php';
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'keypress-wpdeeplpro.php';
		global $KeyPressWPDeepLPro;
    	$KeyPressWPDeepLPro = new KeyPressWPDeepLPro();

		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'deeplpro-configuration.class.php' ;

 		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'admin/deeplpro-admin-hooks.php' ;

		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'includes/deeplpro-plugin-install.php' ;
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'includes/deeplpro-functions.php' ;
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'includes/deeplpro-polylang.php' ;
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'includes/deeplpro-duplicate-post.php' ;
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'includes/deeplpro-acf.php' ;
		

		
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'includes/deeplpro-post-translation.php' ;
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'includes/deeplpro-comments.php';
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'includes/deeplpro-bulk-actions.php' ;


		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'client/deeplapi-glossary.class.php';
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'client/deeplapi-glossary-functions.php';
		
	


	}
} catch ( Exception $e ) {
	if( current_user_can( 'manage_options' ) ) {
		print_r( $e );
		die( __( 'Error loading WPDeepL', 'wpdeepl' ) );
	}
}

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'deeplpro_plugin_action_links' );
function deeplpro_plugin_action_links( $links ) {
 $links = array_merge(
 	array(
 		'<a href="' . esc_url( admin_url( '/options-general.php?page=deepl_settings&tab=pro' ) ) . '">' . __( 'Settings', 'wpdeepl' ) . '</a>'
 	),
 	$links
 );
 return $links;
}

register_activation_hook( __FILE__, 'deeplpro_plugin_activate' );
function deeplpro_plugin_activate() {
	if( !function_exists('deeplpro_install_plugin' ) )
		include_once  trailingslashit( WPDEEPLPRO_PATH ) . 'includes/deeplpro-plugin-install.php' ;
	deeplpro_install_plugin();
	deeplpro_maybe_activate_wpdeepl();
}

register_deactivation_hook( __FILE__, 'deeplpro_plugin_deactivate' );
function deeplpro_plugin_deactivate() {
}


add_action( 'plugins_loaded', 'deeplpro_init', 45 );
function deeplpro_init() {
	if( !defined( 'WPDEEPL_FILES' ) ) {
		return;
	}
	
	if( !is_admin() ) {
		return;
	}

	load_plugin_textdomain( 'wpdeepl', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action('admin_notices', 'deeplpro_admin_notices' );
function deeplpro_admin_notices() {
	include_once trailingslashit( ABSPATH ) . 'wp-admin/includes/plugin.php';
	if ( ! is_plugin_active( 'wpdeepl/wpdeepl.php' ) and current_user_can( 'activate_plugins' ) ) {


		   echo '<div class="notice notice-error is-dismissible">
             <p>';
            printf( 
            	__('WPDeepL Pro extension needs the <a target="_blank" href="%s">WPDeepL plugin</a> installed and activated to run. Please install or activate', 'wpdeepl' ), 
            	'https://fr.wordpress.org/plugins/wpdeepl/' 
            );
            echo '</p>
         </div>';
	}
}

add_action('after_plugin_row_wpdeeplpro/wpdeeplpro.php', 'deeplpro_missing_main_plugin', 10, 3);
function deeplpro_missing_main_plugin($plugin_file, $plugin_data, $plugin_status){
	include_once trailingslashit( ABSPATH ) . 'wp-admin/includes/plugin.php';
	if ( ! is_plugin_active( 'wpdeepl/wpdeepl.php' ) and current_user_can( 'activate_plugins' ) ) {
		$slug = dirname($plugin_file);

		$wp_list_table = _get_list_table('WP_Plugins_List_Table');
		?>
		<tr class="plugin-update-tr" id="<?php echo $slug; ?>-missing-main"><td colspan="<?php echo $wp_list_table->get_column_count(); ?>" class="plugin-error colspanchange">
			<div class="notice inline notice-error">
				<p><?php 
				printf( 
            	__('WPDeepL Pro extension needs the <a target="_blank" href="%s">WPDeepL plugin</a> installed and activated to run. Please install or activate', 'wpdeepl' ), 
            	'https://fr.wordpress.org/plugins/wpdeepl/' 
            );
        ?></p>
			</div>
		</tr>
		<?php
	}
}	