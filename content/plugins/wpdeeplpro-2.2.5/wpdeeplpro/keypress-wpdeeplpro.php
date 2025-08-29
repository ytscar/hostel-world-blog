<?php

class KeyPressWPDeepLPro extends KeyPressAPIClient {
	const APIVERSION = 'v1';
	const PLUGIN_SKU = 'DEEPLPRO';
	const PLUGIN_SLUG = 'wpdeeplpro';
	const PLUGIN_TEXT_DOMAIN = 'wpdeepl';
	const PLUGIN_TITLE = 'DeepL Pro';
	const PLUGIN_FILE = 'wpdeeplpro/wpdeeplpro.php';
	const PLUGIN_ADMIN_PAGE = '/options-general.php?page=deepl_settings&tab=pro';
	//const URL_RENEWAL = 'https://solutions.fluenx.com/produit/eco-participation-pour-woocommerce/';

	const OPTIONKEY_LICENSE = 'keypress_wpdeeplpro_key';
	const OPTIONKEY_SALT = 'keypress_wpdeeplpro_salt';
	const OPTIONKEY_EXPIRES = 'keypress_wpdeeplpro_expires';
	const OPTIONKEY_STATUS = 'keypress_wpdeeplpro_status';
	const MIN_TIME_BETWEEN_PINGS = 3600;


	public $post_option_key = 'wpdeepl_keypress_wpdeeplpro_key';

	protected function setUpPluginKeyPress() {
		// display box in admin
		add_filter( 'deepl_admin_configuration', array( $this, 'deeplpro_add_keypress_admin_box' ), 90, 1 );

		// the hook to which hook the license activation
		add_action('admin_init', array( $this, 'shouldWeActOnLicenseKeyChange' ), 10, 1 );
	}

	function deeplpro_add_keypress_admin_box( $settings ) {

		if( isset( $settings['pro'] ) ) {
			$settings['pro']['footer']['actions'][] = array( $this, 'keypress_admin_page_display_box' );
		}
		return $settings;

	}

	static function getCurrentVersion() {
		if( defined('WPDEEPLPRO_VERSION') )
			return WPDEEPLPRO_VERSION;
		$plugin_data = get_plugin_data( trailingslashit( WPDEEPLPRO_PATH ) . 'wpdeeplpro.php' );
    	define('WPDEEPLPRO_VERSION', $plugin_data['Version'] ); 
		return WPDEEPLPRO_VERSION;
	}


}
