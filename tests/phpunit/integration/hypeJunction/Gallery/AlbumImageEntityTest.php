<?php

namespace hypeJunction\Gallery;

use Elgg\IntegrationTestCase;

/**
 * Integration tests for hjAlbumImage entity (extends ElggFile).
 */
class AlbumImageEntityTest extends IntegrationTestCase {

    public function up() {}
    public function down() {}

    public function getPluginID(): string {
        return '';
    }

    private function makeAlbum($user): hjAlbum {
        \_elgg_services()->session_manager->setLoggedInUser($user);
        $album = new hjAlbum();
        $album->owner_guid = $user->guid;
        $album->container_guid = $user->guid;
        $album->access_id = ACCESS_PUBLIC;
        $album->title = 'Parent Album';
        $album->save();
        return $album;
    }

    public function testImageClassMapping(): void {
        $user = $this->createUser();
        \_elgg_services()->session_manager->setLoggedInUser($user);
        $album = $this->makeAlbum($user);

        $image = new hjAlbumImage();
        $image->owner_guid = $user->guid;
        $image->container_guid = $album->guid;
        $image->access_id = ACCESS_PUBLIC;
        $image->title = 'photo.jpg';
        $this->assertNotFalse($image->save());

        \_elgg_services()->entityCache->delete($image->guid);
        $loaded = get_entity($image->guid);

        $this->assertInstanceOf(hjAlbumImage::class, $loaded);
        $this->assertSame('hjalbumimage', $loaded->getSubtype());

        $image->delete();
        $album->delete();
        \_elgg_services()->session_manager->removeLoggedInUser();
    }

    public function testImageSaveAssignsDefaultPriority(): void {
        $user = $this->createUser();
        \_elgg_services()->session_manager->setLoggedInUser($user);
        $album = $this->makeAlbum($user);

        $image = new hjAlbumImage();
        $image->owner_guid = $user->guid;
        $image->container_guid = $album->guid;
        $image->access_id = ACCESS_PUBLIC;
        $image->title = 'photo.jpg';
        $image->save();

        $this->assertSame(0, (int) $image->priority);

        $image->delete();
        $album->delete();
        \_elgg_services()->session_manager->removeLoggedInUser();
    }

    public function testAlbumCountImagesAfterAdd(): void {
        $user = $this->createUser();
        \_elgg_services()->session_manager->setLoggedInUser($user);
        $album = $this->makeAlbum($user);

        $image1 = new hjAlbumImage();
        $image1->owner_guid = $user->guid;
        $image1->container_guid = $album->guid;
        $image1->access_id = ACCESS_PUBLIC;
        $image1->title = 'a.jpg';
        $image1->save();

        $image2 = new hjAlbumImage();
        $image2->owner_guid = $user->guid;
        $image2->container_guid = $album->guid;
        $image2->access_id = ACCESS_PUBLIC;
        $image2->title = 'b.jpg';
        $image2->save();

        $this->assertSame(2, (int) $album->countImages());

        $image1->delete();
        $image2->delete();
        $album->delete();
        \_elgg_services()->session_manager->removeLoggedInUser();
    }

    public function testImageUrlsContainGuid(): void {
        $user = $this->createUser();
        \_elgg_services()->session_manager->setLoggedInUser($user);
        $album = $this->makeAlbum($user);

        $image = new hjAlbumImage();
        $image->owner_guid = $user->guid;
        $image->container_guid = $album->guid;
        $image->access_id = ACCESS_PUBLIC;
        $image->title = 'photo.jpg';
        $image->save();

        $this->assertStringContainsString((string) $image->guid, $image->getURL());
        $this->assertStringContainsString((string) $image->guid, $image->getActionURL('download'));

        $image->delete();
        $album->delete();
        \_elgg_services()->session_manager->removeLoggedInUser();
    }
}
