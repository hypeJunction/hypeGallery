<?php

namespace hypeJunction\Gallery;

use Elgg\IntegrationTestCase;

/**
 * Integration test for entity_menu_setup array-return contract.
 *
 * REGRESSION (refs 48b0713, FC-6x7x-03): in Elgg 7.x a register,menu:entity
 * handler receives (and must return) a plain PHP array — never a Collection it
 * mutates with ->add(). entity_menu_setup must append ElggMenuItem entries with
 * []= keyed by name and return the array.
 */
class AlbumMenuTest extends IntegrationTestCase {

	public function up() {
		if (!function_exists(__NAMESPACE__ . '\\entity_menu_setup')) {
			require_once dirname(__DIR__, 5) . '/lib/hooks.php';
		}
	}

	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	public function testEntityMenuSetupReturnsArrayForOwnedAlbum(): void {
		$owner = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($owner);

		$album = new hjAlbum();
		$album->owner_guid = $owner->guid;
		$album->container_guid = $owner->guid;
		$album->access_id = ACCESS_PUBLIC;
		$album->title = 'Menu Album';
		$album->save();

		$event = $this->getMockBuilder(\Elgg\Event::class)
			->disableOriginalConstructor()
			->getMock();
		$event->method('getValue')->willReturn([]);
		$event->method('getParam')->willReturnCallback(
			static function ($name, $default = null) use ($album) {
				return $name === 'entity' ? $album : $default;
			}
		);

		$result = entity_menu_setup($event);

		$this->assertIsArray($result, 'entity_menu_setup must return an array, not a Collection');

		// Owner can write to and edit the album → all four items present, keyed by name.
		$this->assertArrayHasKey('upload', $result);
		$this->assertArrayHasKey('manage', $result);
		$this->assertArrayHasKey('edit', $result);
		$this->assertArrayHasKey('delete', $result);

		$this->assertInstanceOf(\ElggMenuItem::class, $result['edit']);
		$this->assertStringContainsString(
			"gallery/manage/$album->guid",
			$result['manage']->getHref()
		);

		$album->delete();
		_elgg_services()->session_manager->removeLoggedInUser();
	}
}
