<?php

namespace hypeJunction\Gallery;

use Elgg\UnitTestCase;

/**
 * Static guards over the ESM entry module js/framework/gallery/init.mjs.
 *
 * REGRESSION (refs 7231e7b, 5609090): the client JS was migrated from AMD
 * define() to ESM .mjs, and every dynamic import() specifier must be prefixed
 * with `js/` so it matches the Elgg 7 importmap key (view path minus .mjs).
 * A bare specifier ("framework/gallery/manager") fails to resolve at runtime.
 */
class EsmModuleTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	private function initMjs(): string {
		$path = dirname(__DIR__, 5) . '/views/default/js/framework/gallery/init.mjs';
		$this->assertFileExists($path, 'ESM entry init.mjs must exist');
		return (string) file_get_contents($path);
	}

	/**
	 * Every dynamic import() specifier in the ESM entry must be js/-prefixed.
	 */
	public function testDynamicImportsUseJsPrefixedSpecifiers(): void {
		$src = $this->initMjs();

		preg_match_all('/import\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $src, $m);
		$this->assertNotEmpty($m[1], 'init.mjs must lazy-import submodules via import()');

		foreach ($m[1] as $specifier) {
			$this->assertStringStartsWith(
				'js/',
				$specifier,
				"Dynamic import specifier '$specifier' must be js/-prefixed to match the Elgg 7 importmap"
			);
		}

		// The four keyed submodules must all be present.
		$this->assertContains('js/framework/gallery/manager', $m[1]);
		$this->assertContains('js/framework/gallery/tagger', $m[1]);
		$this->assertContains('js/framework/gallery/cropper', $m[1]);
		$this->assertContains('js/framework/gallery/popup', $m[1]);
	}

	/**
	 * The entry must be ESM (static import + import()), not an AMD define()/require() wrapper.
	 */
	public function testEntryIsEsmNotAmd(): void {
		$src = $this->initMjs();

		$this->assertMatchesRegularExpression(
			'/^\s*import\s+\$\s+from\s+[\'"]jquery[\'"]/m',
			$src,
			'init.mjs must statically import jquery as an ES module'
		);
		$this->assertDoesNotMatchRegularExpression(
			'/\bdefine\s*\(\s*\[/',
			$src,
			'AMD define([...]) wrapper must be gone after the ESM migration'
		);
		$this->assertDoesNotMatchRegularExpression(
			'/\brequire\s*\(\s*\[/',
			$src,
			'AMD require([...]) call must be gone after the ESM migration'
		);
	}
}
