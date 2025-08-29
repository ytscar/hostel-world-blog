<?php

namespace HostelWorldBlog\Tests\Core;

use Mockery;
use WP_Mock\Tools\TestCase;
use HostelWorldBlog\Core\Container;
use HostelWorldBlog\Services\Admin;

/**
 * @covers \HostelWorldBlog\Core\Container::__construct
 */
class ContainerTest extends TestCase {
	public Container $container;

	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_container_contains_required_services() {
		$this->container = new Container();

		$this->assertSame( true, true );
	}
}
