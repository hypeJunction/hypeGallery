<?php

namespace hypeJunction\Gallery;

use ElggCoreUnitTest;

require_once __DIR__ . '/_lib.php';

class GalleryEntitiesTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		// all __destruct() code should go above here
		parent::__destruct();
	}

	/**
	 * Test gallery entity classes have been registered correctly
	 */
	public function testSubtypeClasses() {

		$album_class = get_subtype_class('object', hjAlbum::SUBTYPE);
		$image_class = get_subtype_class('object', hjAlbumImage::SUBTYPE);

		$this->assertEqual($album_class, get_class(new hjAlbum));
		$this->assertEqual($image_class, get_class(new hjAlbumImage));
	}

	/**
	 * Test count of images in album
	 */
	public function testAlbumImageCount() {

		$user = GalleryTestLib::createUser();
		$album = GalleryTestLib::createAlbum($user->guid);

		$expected_count = 5;

		for ($i = 0; $i < $expected_count; $i++) {
			GalleryTestLib::createImage($user->guid, $album->guid);
		}

		$actual_count = $album->countImages();

		$this->assertEqual($actual_count, $expected_count);

		$user->delete();
	}

	/**
	 * Test image priority
	 */
	public function testImagePriority() {

		$user = GalleryTestLib::createUser();
		$image = GalleryTestLib::createImage($user->guid);

		$this->assertTrue(isset($image->priority));

		$image->delete();
	}

}
