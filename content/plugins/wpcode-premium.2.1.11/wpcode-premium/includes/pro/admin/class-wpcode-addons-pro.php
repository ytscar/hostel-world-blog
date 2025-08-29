<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contains helper methods specific to the addons.
 *
 * @since 2.0.7
 */
class WPCode_Addons_Pro extends WPCode_Addons {
	/**
	 * The licensing URL.
	 *
	 * @since 2.0.7
	 *
	 * @var string
	 */
	protected $licensing_url = 'https://licensing.wpcode.com/v1/';

	/**
	 * The addons URL.
	 *
	 * @since 2.0.7
	 *
	 * @var string
	 */
	protected $addons_url = 'https://licensing.wpcode.com/keys/pro/wpcode-premium.json';


	/**
	 * Pro constructor.
	 */
	public function __construct() {
		add_action( 'wpcode_loaded', array( $this, 'register_update_check' ) );
	}

	/**
	 * Returns our addons.
	 *
	 * @param boolean $flushCache Whether to flush the cache.
	 *
	 * @return array An array of addon data.
	 * @since 2.0.7
	 *
	 */
	public function get_addons( $flushCache = false ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$addons = wpcode()->file_cache->get( 'addons', DAY_IN_SECONDS );
		if ( false === $addons || $flushCache ) {
			$response = wp_remote_get( $this->get_addons_url() );
			if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
				$addons = json_decode( wp_remote_retrieve_body( $response ) );
			}

			if ( ! $addons || ! empty( $addons->error ) ) {
				$addons = $this->get_default_addons();
			}

			wpcode()->file_cache->set( 'addons', $addons );
		} else {
			$addons = json_decode( wp_json_encode( $addons ) );
		}

		if ( is_null( $addons ) ) {
			$addons = $this->get_default_addons();
		}

		// Compute some data we need elsewhere.
		$allPlugins            = get_plugins();
		$installedPlugins      = array_keys( $allPlugins );
		$shouldCheckForUpdates = false;
		$currentUpdates        = get_site_transient( 'update_plugins' );
		foreach ( $addons as $key => $addon ) {
			$addons[ $key ]->basename          = $this->get_addon_basename( $addon->sku );
			$addons[ $key ]->installed         = in_array( $addons[ $key ]->basename, $installedPlugins, true );
			$addons[ $key ]->isActive          = is_plugin_active( $addons[ $key ]->basename );
			$addons[ $key ]->canInstall        = $this->can_install();
			$addons[ $key ]->canActivate       = $this->can_activate();
			$addons[ $key ]->canUpdate         = $this->can_update();
			$addons[ $key ]->capability        = $this->get_manage_capability( $addon->sku );
			$addons[ $key ]->minimumVersion    = $this->get_minimum_version( $addon->sku );
			$addons[ $key ]->installedVersion  = ! empty( $allPlugins[ $addons[ $key ]->basename ]['Version'] ) ? $allPlugins[ $addons[ $key ]->basename ]['Version'] : '';
			$addons[ $key ]->hasMinimumVersion = version_compare( $addons[ $key ]->installedVersion, $addons[ $key ]->minimumVersion, '>=' );
			$addons[ $key ]->requiresUpgrade   = ! wpcode()->license->is_addon_allowed( $addon->sku, $this->is_network_admin() );

			// Get some details from the update info.
			$updateDetails                 = isset( $currentUpdates->response[ $addons[ $key ]->basename ] ) ? $currentUpdates->response[ $addons[ $key ]->basename ] : null;
			$addons[ $key ]->updateVersion = ! empty( $updateDetails ) ? $updateDetails->version : null;

			if ( ! $addons[ $key ]->hasMinimumVersion ) {
				if ( ! isset( $currentUpdates->response[ $addons[ $key ]->basename ] ) ) {
					$shouldCheckForUpdates = true;
				}
			}
		}

		// If we don't have a minimum version set, let's force a check for updates.
		if ( $shouldCheckForUpdates && false === get_transient( 'wpcode_addon_check_for_updates' ) ) {
			set_transient( 'wpcode_addon_check_for_updates', true, HOUR_IN_SECONDS );
			delete_site_transient( 'update_plugins' );
		}

		return $addons;
	}

	/**
	 * Get the download URL for the given addon.
	 *
	 * @param string $sku The addon sku.
	 * @param bool   $network Whether to get the network download URL.
	 *
	 * @return string      The download url for the addon.
	 * @since 2.0.7
	 *
	 */
	public function get_download_url( $sku, $network = false ) {
		$downloadUrl = get_transient( 'wpcode_addons_' . $sku . '_download_url' );
		if ( false !== $downloadUrl ) {
			return $downloadUrl;
		}

		$downloadUrl = '';
		$payload     = array(
			'license'     => wpcode()->license->get( $network ),
			'domain'      => wpcode_get_site_domain(),
			'sku'         => $sku,
			'version'     => WPCODE_VERSION,
			'php_version' => PHP_VERSION,
			'wp_version'  => get_bloginfo( 'version' )
		);

		$request = wp_remote_post( $this->get_licensing_url() . 'addons/download-url/', array(
			'body' => $payload
		) );

		if ( 200 === wp_remote_retrieve_response_code( $request ) ) {
			$response = json_decode( wp_remote_retrieve_body( $request ) );
		}

		if ( ! empty( $response->downloadUrl ) ) {
			$downloadUrl = $response->downloadUrl;
		}

		$cacheTime = empty( $downloadUrl ) ? 10 * MINUTE_IN_SECONDS : HOUR_IN_SECONDS;
		set_transient( 'wpcode_addons_' . $sku . '_download_url', $downloadUrl, $cacheTime );

		return $downloadUrl;
	}

	/**
	 * Get the URL to check licenses.
	 *
	 * @return string The URL.
	 * @since 2.0.7
	 *
	 */
	private function get_licensing_url() {
		if ( defined( 'WPCODE_LICENSING_URL' ) ) {
			return WPCODE_LICENSING_URL;
		}

		return $this->licensing_url;
	}

	/**
	 * Returns the minimum versions needed for addons.
	 * If the version is lower, we need to display a warning and disable the addon.
	 *
	 * @param string $slug A slug to check minimum versions for.
	 *
	 * @return string       The minimum version.
	 * @since 2.0.7
	 *
	 */
	public function get_minimum_version( $slug ) {
		$minimumVersions = [
			'wpcode-pixel' => '0.0.1',
		];

		if ( ! empty( $slug ) && ! empty( $minimumVersions[ $slug ] ) ) {
			return $minimumVersions[ $slug ];
		}

		return '0.0.1';
	}

	/**
	 * Check for updates for all addons.
	 *
	 * @return void
	 * @since 2.0.7
	 *
	 */
	public function register_update_check() {
		foreach ( $this->get_addons() as $addon ) {
			// No need to check for updates if the addon is not installed.
			if ( ! $addon->installed ) {
				continue;
			}

			new WPCode_Updates( [
				'plugin_slug' => $addon->sku,
				'plugin_path' => $addon->basename,
				'version'     => $addon->installedVersion,
				'key'         => wpcode()->license->get(),
			] );
		}
	}

	public function is_network_admin() {
		return is_multisite() && is_network_admin();
	}
}
