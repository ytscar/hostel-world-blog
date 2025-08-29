<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The updates class to check for updates from our server.
 *
 * @since 2.1.0
 */
class WPCode_Updates {
	/**
	 * Plugin slug.
	 *
	 * @since 2.1.0
	 *
	 * @var bool|string
	 */
	public $plugin_slug = false;

	/**
	 * Plugin path.
	 *
	 * @since 2.1.0
	 *
	 * @var bool|string
	 */
	public $plugin_path = false;

	/**
	 * Version number of the plugin.
	 *
	 * @since 2.1.0
	 *
	 * @var bool|int
	 */
	public $version = false;

	/**
	 * License key for the plugin.
	 *
	 * @since 2.1.0
	 *
	 * @var bool|string
	 */
	public $key = false;

	/**
	 * Store the update data returned from the API.
	 *
	 * @since 2.1.0
	 *
	 * @var bool|object
	 */
	public $update = false;

	/**
	 * Store the plugin info details for the update.
	 *
	 * @since 2.1.0
	 *
	 * @var bool|object
	 */
	public $info = false;

	/**
	 * Primary class constructor.
	 *
	 * @param array $config Array of updater config args.
	 *
	 * @since 2.1.0
	 */
	public function __construct( array $config ) {
		// Set class properties.
		$accepted_args = array(
			'plugin_slug',
			'plugin_path',
			'version',
			'key',
		);

		foreach ( $accepted_args as $arg ) {
			$this->$arg = $config[ $arg ];
		}

		// If the user cannot update plugins, stop processing here.
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		// Load the updater hooks and filters.
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update_plugins_filter' ), 1000 );
		add_filter( 'http_request_args', array( $this, 'http_request_args' ), 10, 2 );
		add_filter( 'plugins_api', array( $this, 'plugins_api' ), 10, 3 );

	}

	/**
	 * Infuse plugin update details when WordPress runs its update checker.
	 *
	 * @param object $value The WordPress update object.
	 *
	 * @return object        Amended WordPress update object on success, default if object is empty.
	 * @since 2.1.0
	 */
	public function update_plugins_filter( $value ) {
		// If no update object exists, return early.
		if ( empty( $value ) ) {
			return $value;
		}

		// Run update check by pinging the external API. If it fails, return the default update object.
		if ( ! $this->update ) {
			$this->update = $this->check_for_updates();

			if ( ! $this->update || ! empty( $this->update->error ) ) {
				$this->update = false;

				return $value;
			}

			$this->update->description = preg_replace( '/\s+/', ' ', $this->update->description );
			$this->update->changelog   = preg_replace( '/\s+/', ' ', $this->update->changelog );
		}

		$this->update->icons      = (array) $this->update->icons;
		$this->update->wpcode     = true;
		$this->update->plugin     = $this->plugin_path;
		$this->update->oldVersion = $this->version;

		// Infuse the update object with our data if the version from the remote API is newer.
		if ( isset( $this->update->new_version ) && version_compare( $this->version, $this->update->new_version, '<' ) ) {
			// The $plugin_update object contains new_version, package, slug, and last_update keys.
			$value->response[ $this->plugin_path ] = $this->update;
		} else {
			$this->update->new_version              = $this->version;
			$this->update->plugin                   = $this->plugin_path;
			$value->no_update[ $this->plugin_path ] = $this->update;
		}

		// Return the update object.
		return $value;
	}

	/**
	 * Check for updates request.
	 *
	 * @return Object an object with the update information.
	 * @since 2.1.0
	 */
	public function check_for_updates() {
		$args = array(
			'license'     => $this->key,
			'domain'      => wpcode_get_site_domain(),
			'sku'         => $this->plugin_slug,
			'version'     => $this->version,
			'php_version' => PHP_VERSION,
			'wp_version'  => get_bloginfo( 'version' ),
		);

		return WPCode_License::api_request( 'update', $args );
	}

	/**
	 * Disable SSL verification to prevent download package failures.
	 *
	 * @param array $args Array of request args.
	 *
	 * @return array        Amended array of request args.
	 * @since 2.1.0
	 */
	public function http_request_args( $args ) {
		return $args;
	}

	/**
	 * Filter the plugins_api function to get our own custom plugin information
	 * from our private repo.
	 *
	 * @param object $api The original plugins_api object.
	 * @param string $action The action sent by plugins_api.
	 * @param array  $args Additional args to send to plugins_api.
	 *
	 * @return object         New stdClass with plugin information on success, default response on failure.
	 * @since 2.1.0
	 */
	public function plugins_api( $api, $action = '', $args = null ) {
		$plugin = ( 'plugin_information' === $action ) && isset( $args->slug ) && ( $this->plugin_slug === $args->slug );

		// If our plugin matches the request, set our own plugin data, else return the default response.
		if ( $plugin ) {
			return $this->set_plugins_api( $api );
		}

		return $api;
	}

	/**
	 * Ping a remote API to retrieve plugin information for WordPress to display.
	 *
	 * @param object $default_api The default API object.
	 *
	 * @return object             Return custom plugin information to plugins_api.
	 * @since 2.1.0
	 */
	public function set_plugins_api( $default_api ) {
		// Perform the remote request to retrieve our plugin information. If it fails, return the default object.
		if ( ! $this->info ) {
			$response = WPCode_License::api_request(
				'info',
				array(
					'license'     => $this->key,
					'domain'      => wpcode_get_site_domain(),
					'sku'         => $this->plugin_slug,
					'version'     => $this->version,
					'php_version' => PHP_VERSION,
					'wp_version'  => get_bloginfo( 'version' ),
				)
			);
			if ( empty( $response ) || ! empty( $response->error ) ) {
				$this->info = false;

				return $default_api;
			}

			$this->info = $response;
		}

		// Create a new stdClass object and populate it with our plugin information.
		$api                          = new \stdClass();
		$api->name                    = isset( $this->info->name ) ? $this->info->name : '';
		$api->slug                    = isset( $this->info->slug ) ? $this->info->slug : '';
		$api->version                 = isset( $this->info->version ) ? $this->info->version : '';
		$api->author                  = isset( $this->info->author ) ? $this->info->author : '';
		$api->author_profile          = isset( $this->info->author_profile ) ? $this->info->author_profile : '';
		$api->requires                = isset( $this->info->requires ) ? $this->info->requires : '';
		$api->tested                  = isset( $this->info->tested ) ? $this->info->tested : '';
		$api->last_updated            = isset( $this->info->last_updated ) ? $this->info->last_updated : '';
		$api->homepage                = isset( $this->info->homepage ) ? $this->info->homepage : '';
		$api->sections['description'] = isset( $this->info->description ) ? $this->info->description : '';
		$api->sections['changelog']   = isset( $this->info->changelog ) ? $this->info->changelog : '';
		$api->download_link           = isset( $this->info->download_link ) ? $this->info->download_link : '';
		$api->active_installs         = isset( $this->info->active_installs ) ? $this->info->active_installs : '';
		$api->banners                 = isset( $this->info->banners ) ? (array) $this->info->banners : '';

		// Return the new API object with our custom data.
		return $api;
	}
}
