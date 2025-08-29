<?php
/**
 * Plugin Name: Page Builder Framework Premium Addon
 * Plugin URI: https://wp-pagebuilderframework.com
 * Description: Premium Add-On for Page Builder Framework
 * Version: 2.10.2
 * Author: David Vongries
 * Author URI: https://mapsteps.com
 * Text Domain: wpbfpremium
 *
 * @package Page Builder Framework Premium Add-On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Constants.
define( 'WPBF_PREMIUM_THEME_DIR', get_template_directory() );
define( 'WPBF_PREMIUM_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPBF_PREMIUM_TEMPLATES_DIR', plugin_dir_path( __FILE__ ) . 'inc/templates/' );
define( 'WPBF_PREMIUM_URI', plugin_dir_url( __FILE__ ) );
define( 'WPBF_PREMIUM_LICENSE_PAGE', 'wpbf-premium&tab=license' );
define( 'WPBF_PREMIUM_STORE_URL', 'https://wp-pagebuilderframework.com' );
define( 'WPBF_PREMIUM_THEME_NAME', 'Page Builder Framework' );
define( 'WPBF_PREMIUM_PLUGIN_NAME', 'Page Builder Framework Premium Addon' );
define( 'WPBF_PREMIUM_ITEM_ID', 8707 );
define( 'WPBF_PREMIUM_VERSION', '2.10.2' );

// Minimum required theme version.
define( 'WPBF_MIN_VERSION', '2.11' );

// Load plugin updater if it doesn't exist.
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include dirname( __FILE__ ) . '/assets/edd/EDD_SL_Plugin_Updater.php';
}

/**
 * Plugin updater.
 */
function wpbf_premium_plugin_updater() {

	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}

	$license_key = trim( get_option( 'wpbf_premium_license_key' ) );

	$edd_updater = new EDD_SL_Plugin_Updater(
		WPBF_PREMIUM_STORE_URL,
		__FILE__,
		array(
			'version' => WPBF_PREMIUM_VERSION,
			'license' => $license_key,
			'item_id' => WPBF_PREMIUM_ITEM_ID,
			'author'  => 'David Vongries',
			'beta'    => false,
		)
	);

}
add_action( 'init', 'wpbf_premium_plugin_updater' );

/**
 * Load textdomain.
 */
function wpbf_premium_textdomain() {
	load_plugin_textdomain( 'wpbfpremium', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'wpbf_premium_textdomain' );

// PAnD.
require_once WPBF_PREMIUM_DIR . 'assets/pand/persist-admin-notices-dismissal.php';

/**
 * Check if Page Builder Framework is white labeled.
 *
 * @return bool Whether PBF is white labeled or not.
 */
function wpbf_is_white_labeled() {

	if ( apply_filters( 'wpbf_white_labeled', false ) ) {
		return true;
	}

	// ! Deprecated filter, do not use this.
	if ( ! apply_filters( 'wpbf_premium_review_notice', true ) ) {
		return true;
	}

	return false;

}

/**
 * Plugin activation.
 */
function wpbf_premium_activation() {

	if ( ! current_user_can( 'activate_plugins' ) || 'true' == get_option( 'wpbf_premium_plugin_activated' ) ) {
		return;
	}

	add_option( 'wpbf_premium_install_date', current_time( 'mysql' ) );
	add_option( 'wpbf_site_url', $_SERVER['SERVER_NAME'] );
	add_option( 'wpbf_premium_plugin_activated', 'true' );

}
add_action( 'init', 'wpbf_premium_activation' );


/**
 * Helper transients.
 */
$theme = wp_get_theme();

// Set transient if Page Builder Framework is not active.
if ( 'Page Builder Framework' === $theme->name || 'Page Builder Framework' === $theme->parent_theme ) {
	delete_transient( 'wpbf_not_active' );
} else {
	set_transient( 'wpbf_not_active', true );
}

// Set transient if old version of Page Builder Framework is active.
if ( 'wpbf' === $theme->get( 'TextDomain' ) || 'wpbf' === $theme->get( 'Template' ) ) {
	set_transient( 'wpbf_old_theme', true );
} else {
	delete_transient( 'wpbf_old_theme' );
}

/**
 * Get license expiration date.
 *
 * @return string The expiration date.
function wpbf_premium_get_expiration_date() {

	$license_key = trim( get_option( 'wpbf_premium_license_key' ) );

	// Return false if we don't have a license key.
	if ( ! $license_key ) {
		return false;
	}

	// Return false if license key length doesn't match.
	if ( strlen( $license_key ) !== 32 ) {
		return false;
	}

	$url = home_url();
	$api = "https://wp-pagebuilderframework.com/?edd_action=check_license&item_id=8707&license={$license_key}&url={$url}";

	$request = wp_remote_get( $api );

	// return false if we have an error.
	if ( is_wp_error( $request ) ) {
		return false;
	}

	$body = wp_remote_retrieve_body( $request );

	$data = json_decode( $body, true );

	$expiration = isset( $data['expires'] ) ? $data['expires'] : false;

	return $expiration;

}
 */

/**
 * Save expiration date in transient.
 *
 * If we will use this again in the future, we might want to hook this into admin_init to prevent this from running
 * on every page load if some pages have messed with transients.
if ( ! get_transient( 'wpbf_expiration_date' ) ) {

	$expiration_date = wpbf_premium_get_expiration_date();

	if ( false !== $expiration_date ) {

		if ( 'lifetime' === $expiration_date ) {
			set_transient( 'wpbf_expiration_date', $expiration_date, 10 * DAY_IN_SECONDS );
		} else {
			set_transient( 'wpbf_expiration_date', $expiration_date, 2 * DAY_IN_SECONDS );
		}

	} else {
		// Set transient regardless if API call fails so that we don't end up checking on every page load.
		set_transient( 'wpbf_expiration_date', 'checking_failed', 4 * DAY_IN_SECONDS );
	}

}
 */

/**
 * License key mismatch.
 *
 * @return boolean
 */
function wpbf_license_key_mismatch() {

	$status           = get_option( 'wpbf_premium_license_status' );
	$current_site_url = get_option( 'wpbf_site_url' );

	// Stop if $current_site_url is not set.
	if ( ! $current_site_url ) {
		return false;
	}

	// Stop if there's no valid license key.
	if ( $status !== 'valid' ) {
		return false;
	}

	// Stop if domain hasn't changed.
	if ( $current_site_url === $_SERVER['SERVER_NAME'] ) {
		return false;
	}

	return true;

}

/**
 * Check if Page Builder Framework theme is outdated.
 *
 * @return boolean
 */
function wpbf_premium_is_theme_outdated() {

	// Stop here if WPBF_VERSION is not defined.
	if ( ! defined( 'WPBF_VERSION' ) ) {
		return;
	}

	// If Page Builder Framework is below the minimum required version, we are outdated.
	if ( ! version_compare( WPBF_VERSION, WPBF_MIN_VERSION, '>=' ) ) {
		return true;
	}

	return false;

}

/**
 * Display compatibility notice.
 */
function wpbf_premium_compatibility_notice() {

	// Stop here if current user can't manage options.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Stop here if theme is not outdated.
	if ( ! wpbf_premium_is_theme_outdated() ) {
		return;
	}

	?>

	<div class="notice notice-error wpbf-compatibility-notice">
		<div class="notice-body">
			<div class="notice-icon">
				<img src="<?php echo esc_url( WPBF_THEME_URI ); ?>/img/page-builder-framework-logo-blue.png" alt="Page Builder Framework Logo">
			</div>
			<div class="notice-content">
				<h2>
					<?php _e( 'Page Builder Framework - Compatibility Warning', 'wpbfpremium' ); ?>
				</h2>
				<p>
					<?php _e( 'Your version of <strong>Page Builder Framework</strong> is outdated and no longer compatible with the latest version of the <strong>Premium Add-On.</strong>', 'wpbfpremium' ); ?> <br>
					<?php _e( 'The minimum required theme version is <strong>' . WPBF_MIN_VERSION . '.</strong> Please update Page Builder Framework to the latest version.', 'wpbfpremium' ); ?>
				</p>
				<p>
					<a href="<?php echo esc_url( admin_url( 'themes.php' ) ); ?>" class="button button-primary">
						<?php _e( 'View Updates', 'wpbfpremium' ); ?>
					</a>
				</p>
			</div>
		</div>
	</div>

	<?php

}
add_action( 'admin_notices', 'wpbf_premium_compatibility_notice' );

/**
 * Admin notices.
 */
function wpbf_premium_admin_notices() {

	// Stop here if current user cannot manage options.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Notice if Premium Add-On is active but Page Builder Framework is not installed/activated.
	if ( get_transient( 'wpbf_not_active' ) ) {

		$class       = 'notice notice-error';
		$plugin_name = apply_filters( 'wpbf_premium_plugin_name', WPBF_PREMIUM_PLUGIN_NAME );
		$theme_name  = apply_filters( 'wpbf_premium_theme_name', WPBF_PREMIUM_THEME_NAME );
		// translators: %1$s: Theme name, %2$s: Plugin name.
		$message = sprintf( __( 'You need to install/activate the <strong>%1$s</strong> theme for <strong>%2$s</strong> to work!', 'wpbfpremium' ), $theme_name, $plugin_name );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

	}

	// Error notice if old version of Page Builder Framework is installed.
	if ( get_transient( 'wpbf_old_theme' ) ) {

		$class          = 'notice notice-error';
		$caution_button = '<span style="text-transform:uppercase; background: #dc3232; border-radius:3px; color: #fff; padding: 10px 15px; font-size: 12px; margin-right: 5px; display: inline-block;">Caution!</span>';
		$repo_link      = '<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank">WordPress repository</a>';
		// translators: %1$s: Caution button, %2$s: WordPress repository (link).
		$message = sprintf( __( '%1$s You are running an outdated version of Page Builder Framework that is no longer supported! Please always use the version from the official %2$s.', 'wpbfpremium' ), $caution_button, $repo_link );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

	}

	// Stop here if we're not on the main site.
	if ( ! is_main_site() ) {
		return;
	}

	// License key expired & activation notice.
	$status = get_option( 'wpbf_premium_license_status' );

	if ( 'expired' === $status ) {

		$class       = 'notice notice-error';
		$license_key = trim( get_option( 'wpbf_premium_license_key' ) );
		$renew_url   = 'https://wp-pagebuilderframework.com/checkout/?edd_license_key=' . $license_key . '&download_id=8707';
		$plugin_name = apply_filters( 'wpbf_premium_plugin_name', WPBF_PREMIUM_PLUGIN_NAME );
		// translators: %1%s: Plugin name, %2$s: Renewal URL.
		$message = sprintf( __( 'Your License for <strong>%1$s</strong> has expired. <a href="%2$s" target="_blank">Renew your License</a> to keep getting Feature Updates & Premium Support.', 'wpbfpremium' ), $plugin_name, $renew_url );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

	} elseif ( 'valid' !== $status ) {

		$class            = 'notice notice-error';
		$license_page_url = get_admin_url() . 'themes.php?page=' . WPBF_PREMIUM_LICENSE_PAGE . '';
		$docs_url         = 'https://wp-pagebuilderframework.com/docs-category/installation/';
		$plugin_name      = apply_filters( 'wpbf_premium_plugin_name', WPBF_PREMIUM_PLUGIN_NAME );
		// translators: %1$s: License page url, %2$s: Plugin name, %3$s: URL to the docs.
		$message = sprintf( __( 'Please <a href="%1$s">activate your license key</a> to receive updates for <strong>%2$s</strong>. <a href="%3$s" target="_blank">Help</a>', 'wpbfpremium' ), $license_page_url, $plugin_name, $docs_url );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

	}

	// License key mismatch.
	if ( wpbf_license_key_mismatch() ) {

		$class            = 'notice notice-error';
		$license_page_url = get_admin_url() . 'themes.php?page=' . WPBF_PREMIUM_LICENSE_PAGE . '';
		$docs_url         = 'https://wp-pagebuilderframework.com/docs-category/installation/';
		$plugin_name      = apply_filters( 'wpbf_premium_plugin_name', WPBF_PREMIUM_PLUGIN_NAME );
		// translators: %1$s: License page url, %2$s: Plugin name, %3$s: URL to the docs.
		$message  = '<strong>' . __( 'License key mismatch!', 'wpbfpremium' ) . '</strong>';
		$message .= '<br>';
		$message .= sprintf( __( 'Please <a href="%1$s">revalidate your license key</a> for <strong>%2$s</strong>.', 'wpbfpremium' ), $license_page_url, $plugin_name );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

	}

	// Stop here if Page Builder Framework or Premium Add-On are white labeled.
	if ( wpbf_is_white_labeled() ) {
		return;
	}

	// Stop here if review notice has been dismissed.
	if ( ! PAnD::is_admin_notice_active( 'review-theme-notice-forever' ) ) {
		return;
	}

	// Review notice.
	$install_date = get_option( 'wpbf_premium_install_date', '' );

	// Stop if there's no install date.
	if ( empty( $install_date ) ) {
		return;
	}

	$diff = round( ( time() - strtotime( $install_date ) ) / 24 / 60 / 60 );

	// Only go past this point if the Premium Add-On is running for more than 5 days.
	if ( $diff < 5 ) {
		return;
	}

	$emoji      = '😍';
	$review_url = 'https://wordpress.org/support/theme/page-builder-framework/reviews/?rate=5#new-post';
	$link_start = '<a href="' . $review_url . '" target="_blank">';
	$link_end   = '</a>';
	// translators: %1$s: Emoji, %2$s: Link start tag, %3$s: Link end tag.
	$notice   = sprintf( __( '%1$s Love using Page Builder Framework? - That\'s Awesome! Help us spread the word and leave us a %2$s 5-star review %3$s in the WordPress repository.', 'wpbfpremium' ), $emoji, $link_start, $link_end );
	$btn_text = __( 'Sure! You deserve it!', 'wpbfpremium' );
	$notice  .= '<br/>';
	$notice  .= "<a href=\"$review_url\" style=\"margin-top: 15px;\" target='_blank' class=\"button-primary\">$btn_text</a>";

	echo '<div data-dismissible="review-theme-notice-forever" class="notice notice-success is-dismissible">';
	echo '<p>' . $notice . '</p>';
	echo '</div>';

}
add_action( 'admin_init', array( 'PAnD', 'init' ) );
add_action( 'admin_notices', 'wpbf_premium_admin_notices' );

/**
 * Plugin deactivation.
 */
function wpbf_premium_deactivation() {

	delete_transient( 'wpbf_not_active' );
	delete_transient( 'wpbf_old_theme' );
	delete_transient( 'wpbf_expiration_date' );
	delete_transient( 'wpbf_white_label_section_hidden' );
	delete_option( 'wpbf_premium_install_date' );
	delete_option( 'wpbf_premium_plugin_activated' );
	delete_option( 'wpbf_site_url' );

	$wpbf_settings = get_option( 'wpbf_settings' );

	if ( isset( $wpbf_settings['wpbf_hide_white_label_section'] ) ) {
		unset( $wpbf_settings['wpbf_hide_white_label_section'] );
		update_option( 'wpbf_settings', $wpbf_settings );
	}

}
register_deactivation_hook( __FILE__, 'wpbf_premium_deactivation' );

// Stop here if Page Builder Framework is not active.
if ( get_transient( 'wpbf_not_active' ) ) {
	return;
}

/**
 * Enqueue scripts & styles.
 */
function wpbf_premium_scripts() {

	// Premium Add-On styles.
	wp_enqueue_style( 'wpbf-premium', WPBF_PREMIUM_URI . 'css/wpbf-premium.css', '', WPBF_PREMIUM_VERSION );

	// if ( is_rtl() ) {
		// RTL.
		// wp_enqueue_style( 'wpbf-premium-rtl', WPBF_PREMIUM_URI . 'css/wpbf-premium-rtl.css', '', WPBF_PREMIUM_VERSION );
	// }

	// Premium Add-On scripts.
	wp_enqueue_script( 'wpbf-premium', WPBF_PREMIUM_URI . 'js/site.js', array( 'jquery' ), WPBF_PREMIUM_VERSION, true );

	if ( in_array( get_theme_mod( 'sub_menu_animation' ), array( 'zoom-in', 'zoom-out' ), true ) ) {
		// jQuery transit.
		wp_enqueue_script( 'wpbf-sub-menu-animation', WPBF_PREMIUM_URI . 'js/jquery.transit.min.js', array( 'jquery', 'wpbf-site' ), '0.9.12', true );
	}

}
add_action( 'wp_enqueue_scripts', 'wpbf_premium_scripts', 11 );

// Required files.
require_once WPBF_PREMIUM_DIR . 'inc/init.php';
require_once WPBF_PREMIUM_DIR . 'assets/edd/license.php';
