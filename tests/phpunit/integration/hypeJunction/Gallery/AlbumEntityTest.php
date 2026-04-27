<?php

namespace hypeJunction\Gallery;

use Elgg\IntegrationTestCase;

/**
 * Integration tests for hjAlbum entity CRUD and class mapping.
 */
class AlbumEntityTest extends IntegrationTestCase {

    public function up() {}
    public function down() {}

    public function getPluginID(): string {
        // Skip the plugin-active check — tests the entity class directly.
        return '';
    }

    public function testAlbumClassMapping(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);

        $album = new hjAlbum();
        $album->owner_guid = $user->guid;
        $album->container_guid = $user->guid;
        $album->access_id = ACCESS_PUBLIC;
        $album->title = 'Test Album';
        $this->assertNotFalse($album->save());

        _elgg_services()->entityCache->delete($album->guid);
        $loaded = get_entity($album->guid);

        $this->assertInstanceOf(hjAlbum::class, $loaded);
        $this->assertSame('hjalbum', $loaded->getSubtype());
        $this->assertSame('Test Album', $loaded->title);

        $album->delete();
        _elgg_services()->session_manager->removeLoggedInUser();
    }

    public function testAlbumMetadataPersists(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);

        $album = new hjAlbum();
        $album->owner_guid = $user->guid;
        $album->container_guid = $user->guid;
        $album->access_id = ACCESS_PUBLIC;
        $album->title = 'Vacation 2024';
        $album->description = 'Summer trip';
        $album->location = 'Lisbon';
        $album->date = '2024-08-01';
        $this->assertNotFalse($album->save());

        _elgg_services()->entityCache->delete($album->guid);
        $loaded = get_entity($album->guid);

        $this->assertSame('Summer trip', $loaded->description);
        $this->assertSame('Lisbon', $loaded->location);
        $this->assertSame('2024-08-01', $loaded->date);

        $album->delete();
        _elgg_services()->session_manager->removeLoggedInUser();
    }

    public function testAlbumCountImagesReturnsZeroInitially(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);

        $album = new hjAlbum();
        $album->owner_guid = $user->guid;
        $album->container_guid = $user->guid;
        $album->access_id = ACCESS_PUBLIC;
        $album->title = 'Empty Album';
        $this->assertNotFalse($album->save());

        $this->assertSame(0, (int) $album->countImages());

        $album->delete();
        _elgg_services()->session_manager->removeLoggedInUser();
    }

    public function testAlbumUrlReturnsFriendlySlug(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);

        $album = new hjAlbum();
        $album->owner_guid = $user->guid;
        $album->container_guid = $user->guid;
        $album->access_id = ACCESS_PUBLIC;
        $album->title = 'My Trip';
        $album->save();

        $url = $album->getURL('view');
        $this->assertStringContainsString('gallery/view/' . $album->guid, $url);

        $this->assertStringContainsString(
            'gallery/edit/' . $album->guid,
            $album->getURL('edit')
        );
        $this->assertStringContainsString(
            'gallery/manage/' . $album->guid,
            $album->getURL('manage')
        );

        $album->delete();
        _elgg_services()->session_manager->removeLoggedInUser();
    }

    public function testNonOwnerCannotEditAlbum(): void {
        $owner = $this->createUser();
        $other = $this->createUser();

        elgg_get_session()->setLoggedInUser($owner);

        $album = new hjAlbum();
        $album->owner_guid = $owner->guid;
        $album->container_guid = $owner->guid;
        $album->access_id = ACCESS_PUBLIC;
        $album->title = 'Private Album';
        $album->save();

        $this->assertTrue($album->canEdit($owner->guid));
        $this->assertFalse($album->canEdit($other->guid));

        $album->delete();
        _elgg_services()->session_manager->removeLoggedInUser();
    }
}
