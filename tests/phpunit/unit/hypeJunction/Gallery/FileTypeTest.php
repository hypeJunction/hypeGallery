<?php

namespace hypeJunction\Gallery;

use Elgg\UnitTestCase;

/**
 * Unit tests for the pure mimetype -> Elgg simple-type mapping in
 * lib/functions.php (get_simple_type). No DB / boot required.
 */
class FileTypeTest extends UnitTestCase {

	public function up() {
		$functions = dirname(__DIR__, 5) . '/lib/functions.php';
		if (!function_exists(__NAMESPACE__ . '\\get_simple_type')) {
			require_once $functions;
		}
	}

	public function down() {}

	/**
	 * get_simple_type() classifies mimetypes into Elgg simple types, defaulting
	 * to 'general' for anything unrecognised.
	 */
	public function testGetSimpleTypeMapping(): void {
		$this->assertSame('image', get_simple_type('image/png'));
		$this->assertSame('image', get_simple_type('image/jpeg'));
		$this->assertSame('audio', get_simple_type('audio/mpeg'));
		$this->assertSame('audio', get_simple_type('application/ogg'));
		$this->assertSame('video', get_simple_type('video/mp4'));
		$this->assertSame('document', get_simple_type('application/pdf'));
		$this->assertSame('document', get_simple_type('application/msword'));
		$this->assertSame('document', get_simple_type('text/plain'));
		$this->assertSame('general', get_simple_type('application/octet-stream'));
	}
}
