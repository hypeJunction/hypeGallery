<?php

namespace hypeJunction\Gallery;

use Elgg\IntegrationTestCase;

/**
 * Integration test for register_entity_title_buttons().
 *
 * REGRESSION: the hjAlbumImage branch assigns null into $items when a feature
 * ternary is false (downloads or avatars disabled). The registration loop then
 * wrote $options['name'] onto that null, auto-vivifying an array with a name and
 * no text — and ElggMenuItem::factory() requires both. Result: a hard 500 on
 * every image page for any user who could see the title buttons, while the
 * album page (which never takes that branch) rendered fine.
 */
class TitleButtonsTest extends IntegrationTestCase {

	public function up() {
		if (!function_exists(__NAMESPACE__ . '\\register_entity_title_buttons')) {
			require_once dirname(__DIR__, 5) . '/lib/functions.php';
		}
	}

	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	public function testImageTitleButtonsSurviveDisabledFeatures(): void {
		$owner = $this->createUser();
		$album = $this->createObject(['subtype' => hjAlbum::SUBTYPE, 'owner_guid' => $owner->guid]);
		$image = $this->createObject([
			'subtype' => hjAlbumImage::SUBTYPE,
			'owner_guid' => $owner->guid,
			'container_guid' => $album->guid,
		]);

		// whatever the HYPEGALLERY_* constants say, no menu item may be built
		// from a null entry
		$this->assertTrue(register_entity_title_buttons($image));

		foreach (elgg_get_menu('title', ['entity' => $image]) as $item) {
			$this->assertNotEmpty($item->getName());
			$this->assertNotEmpty($item->getText());
		}
	}
}
