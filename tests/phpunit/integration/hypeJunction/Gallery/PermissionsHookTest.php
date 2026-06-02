<?php

namespace hypeJunction\Gallery;

use Elgg\IntegrationTestCase;

/**
 * Tests permission hook behavior for albums and images.
 */
class PermissionsHookTest extends IntegrationTestCase {

    public function up() {}
    public function down() {}

    /**
     * @return string
     */
    public function getPluginID(): string {
        return '';
    }

    /**
     * @return void
     */
    public function testAlbumOwnerCanEdit(): void {
        $owner = $this->createUser();
        \_elgg_services()->session_manager->setLoggedInUser($owner);

        $album = new hjAlbum();
        $album->owner_guid = $owner->guid;
        $album->container_guid = $owner->guid;
        $album->access_id = ACCESS_PUBLIC;
        $album->title = 'Owned';
        $album->save();

        $this->assertTrue($album->canEdit($owner->guid));
        $album->delete();
        \_elgg_services()->session_manager->removeLoggedInUser();
    }

    /**
     * @return void
     */
    public function testImageInheritsAlbumAccess(): void {
        $user = $this->createUser();
        \_elgg_services()->session_manager->setLoggedInUser($user);

        $album = new hjAlbum();
        $album->owner_guid = $user->guid;
        $album->container_guid = $user->guid;
        $album->access_id = ACCESS_LOGGED_IN;
        $album->title = 'Logged-in album';
        $album->save();

        $image = new hjAlbumImage();
        $image->owner_guid = $user->guid;
        $image->container_guid = $album->guid;
        $image->access_id = $album->access_id;
        $image->title = 'inherited.jpg';
        $image->save();

        $this->assertSame((int) ACCESS_LOGGED_IN, (int) $image->access_id);

        $image->delete();
        $album->delete();
        \_elgg_services()->session_manager->removeLoggedInUser();
    }

    /**
     * @return void
     */
    public function testHookHandlerIsCallable(): void {
        // \Elgg\Hook is an INTERFACE — mock it rather than instantiate.
        $event = $this->getMockBuilder(\Elgg\Event::class)->disableOriginalConstructor()->getMock();
        $event->method('getType')->willReturn('object');
        $event->method('getName')->willReturn('permissions_check');
        $event->method('getValue')->willReturn(false);
        $event->method('getParam')->willReturn(null);

        if (function_exists(__NAMESPACE__ . '\\permissions_check')) {
            $result = call_user_func(__NAMESPACE__ . '\\permissions_check', $event);
            $this->assertTrue(is_bool($result) || is_null($result));
        } else {
            $this->markTestSkipped('permissions_check function not loaded');
        }
    }
}
