<?php

namespace hypeJunction\Gallery\Tests\Integration;

use Elgg\IntegrationTestCase;
use hypeJunction\Gallery\hjAlbum;
use hypeJunction\Gallery\hjAlbumImage;

/**
 * @group integration
 */
class hjAlbumTest extends IntegrationTestCase {

    public function testAlbumSubtype() {
        $album = new hjAlbum();
        $this->assertEquals('hjalbum', $album->getSubtype());
    }

    public function testAlbumSubtypeConstant() {
        $this->assertEquals('hjalbum', hjAlbum::SUBTYPE);
    }

    public function testAlbumImageSubtype() {
        $image = new hjAlbumImage();
        $this->assertEquals('hjalbumimage', $image->getSubtype());
    }
}
