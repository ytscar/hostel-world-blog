<?php
/**
 * Plugin Name: Hostel World Blog
 * Plugin URI:  https://github.com/ytscar/hostel-world-blog
 * Description: Base repo & plugin for fixing bugs and resolving issues in the blog website.
 * Version:     1.0.0
 * Author:      ytscar
 * Author URI:  https://github.com/ytscar
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: hostel-world-blog
 * Domain Path: /languages
 *
 * @package HostelWorldBlog
 */

namespace ytscar\HostelWorldBlog;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

define( 'HOSTEL_WORLD_BLOG_AUTOLOAD', __DIR__ . '/vendor/autoload.php' );

// Composer Check.
if ( ! file_exists( HOSTEL_WORLD_BLOG_AUTOLOAD ) ) {
	add_action(
		'admin_notices',
		function () {
			vprintf(
				/* translators: Plugin directory path. */
				esc_html__( 'Fatal Error: Composer not setup in %s', 'hostel-world-blog' ),
				[ __DIR__ ]
			);
		}
	);

	return;
}

// Run Plugin.
require_once HOSTEL_WORLD_BLOG_AUTOLOAD;
( \HostelWorldBlog\Plugin::get_instance() )->run();
