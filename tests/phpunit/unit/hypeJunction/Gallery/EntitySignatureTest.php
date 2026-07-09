<?php

namespace hypeJunction\Gallery;

use Elgg\UnitTestCase;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;
use hypeJunction\Gallery\Upgrades\EncodeRiverMetadataAsJson;

/**
 * Reflection-based regression guards for entity / upgrade / seeder contracts
 * that fatal at class-load or upgrade-run time on Elgg 7.x.
 *
 * These are the class-shape fixes the generic migration-failure-catalog guard
 * cannot express precisely (exact param types, exact returned values).
 */
class EntitySignatureTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	/**
	 * REGRESSION (ref a41f830): hjAlbumImage::delete() must be signature-
	 * compatible with ElggFile::delete(bool $recursive = true, ?bool $persistent = null)
	 * in 7.x. A wrong arity/type is a latent fatal ("Declaration must be compatible").
	 */
	public function testAlbumImageDeleteSignatureMatchesElggFile(): void {
		$method = new \ReflectionMethod(hjAlbumImage::class, 'delete');
		$params = $method->getParameters();

		$this->assertCount(2, $params, 'delete() must accept exactly ($recursive, $persistent)');

		$this->assertSame('recursive', $params[0]->getName());
		$this->assertSame('bool', (string) $params[0]->getType());
		$this->assertTrue($params[0]->isDefaultValueAvailable());
		$this->assertTrue($params[0]->getDefaultValue());

		$this->assertSame('persistent', $params[1]->getName());
		$this->assertSame('?bool', (string) $params[1]->getType());
		$this->assertTrue($params[1]->isDefaultValueAvailable());
		$this->assertNull($params[1]->getDefaultValue());

		$this->assertSame('bool', (string) $method->getReturnType());
	}

	/**
	 * REGRESSION (ref 94e883b): the river-metadata upgrade must extend the 6.x
	 * abstract AsynchronousUpgrade and implement run(Result $result, $offset): Result.
	 * `implements Batch` / wrong run() arity fatals on 6.x/7.x.
	 */
	public function testUpgradeBatchContract(): void {
		$this->assertTrue(
			is_subclass_of(EncodeRiverMetadataAsJson::class, AsynchronousUpgrade::class),
			'EncodeRiverMetadataAsJson must extend Elgg\\Upgrade\\AsynchronousUpgrade'
		);

		$upgrade = new EncodeRiverMetadataAsJson();
		$this->assertSame(2026041200, $upgrade->getVersion());

		$run = new \ReflectionMethod(EncodeRiverMetadataAsJson::class, 'run');
		$params = $run->getParameters();
		$this->assertCount(2, $params);
		$this->assertSame('result', $params[0]->getName());
		$this->assertSame(Result::class, (string) $params[0]->getType());
		$this->assertSame('offset', $params[1]->getName());
		$this->assertSame(Result::class, (string) $run->getReturnType());
	}

	/**
	 * REGRESSION (ref 7eae8c7): Seeder must satisfy the Elgg 6.1 Seed interface —
	 * getType(): string and getCountOptions(): array — and addSeed() must append
	 * itself (not replace) the seeds,database event value.
	 */
	public function testSeederShapeAndValues(): void {
		$this->assertSame('gallery', Seeder::getType());

		$ref = new \ReflectionClass(Seeder::class);
		$countOptions = $ref->getMethod('getCountOptions');
		$countOptions->setAccessible(true);
		$seeder = $ref->newInstanceWithoutConstructor();

		$this->assertSame(
			['type' => 'object', 'subtype' => 'hjalbum'],
			$countOptions->invoke($seeder)
		);

		$event = $this->getMockBuilder(\Elgg\Event::class)
			->disableOriginalConstructor()
			->getMock();
		$event->method('getValue')->willReturn(['Some\\Existing\\Seed']);

		$seeds = Seeder::addSeed($event);
		$this->assertContains('Some\\Existing\\Seed', $seeds, 'addSeed must preserve existing seeds');
		$this->assertContains(Seeder::class, $seeds, 'addSeed must append its own class');
	}
}
