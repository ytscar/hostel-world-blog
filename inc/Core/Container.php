<?php
/**
 * Container class.
 *
 * This class is responsible for registering the
 * plugin's services.
 *
 * @package HostelWorldBlog
 */

namespace HostelWorldBlog\Core;

use HostelWorldBlog\Interfaces\Kernel;
use HostelWorldBlog\Services\Admin;

class Container implements Kernel {
	/**
	 * Services.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed[]
	 */
	public static array $services = [];

	/**
	 * Prepare Singletons.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		static::$services = [];
	}

	/**
	 * Register Service.
	 *
	 * Establish singleton version for each Service
	 * concrete class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( static::$services as $service ) {
			( $service::get_instance() )->register();
		}
	}
}
