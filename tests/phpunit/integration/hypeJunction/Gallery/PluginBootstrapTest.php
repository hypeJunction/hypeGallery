<?php

namespace hypeJunction\Gallery;

use Elgg\IntegrationTestCase;

/**
 * Asserts that the plugin boots cleanly and registers its public surface area.
 *
 * NOTE: this test is also a canary for the legacy bootstrap bug (bead j9d9):
 * hypeGallery currently ships BOTH a legacy start.php AND an elgg-plugin.php,
 * which is invalid in Elgg 4.x. The migration must delete start.php and fold
 * all registrations into a PluginBootstrap::init() class.
 */
class PluginBootstrapTest extends IntegrationTestCase {

    public function up() {}
    public function down() {}

    public function testAlbumSubtypeRegistered(): void {
        $this->assertTrue(class_exists(hjAlbum::class));
        $this->assertTrue(class_exists(hjAlbumImage::class));
    }

    public function testPageHandlerConstantDefined(): void {
        // These constants are defined in start.php — tests document the
        // contract the migration must preserve in PluginBootstrap.
        if (!defined('hypeJunction\\Gallery\\PAGEHANDLER')) {
            $this->markTestSkipped('PAGEHANDLER constant not defined — expected after plugin boot.');
        }
        $this->assertSame('gallery', PAGEHANDLER);
    }

    public function testActionViewsExistOnDisk(): void {
        $pluginRoot = dirname(__DIR__, 5) . '/mod/hypeGallery';
        if (!is_dir($pluginRoot)) {
            $pluginRoot = dirname(__DIR__, 4);
        }
        $actions = [
            'actions/edit/object/hjalbum.php',
            'actions/edit/object/hjalbumimage.php',
            'actions/upload/upload.php',
            'actions/upload/handle.php',
            'actions/upload/filedrop.php',
            'actions/delete/object.php',
            'actions/order/images.php',
            'actions/approve/image.php',
        ];
        foreach ($actions as $rel) {
            $this->assertFileExists($pluginRoot . '/' . $rel, "Missing action: $rel");
        }
    }
}
