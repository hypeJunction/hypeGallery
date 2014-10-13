<?php

namespace hypeJunction\Gallery;

use ElggCoreUnitTest;

require_once __DIR__ . '/_lib.php';

class GalleryPermissionsTest extends ElggCoreUnitTest {

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

		$this->owner = GalleryTestLib::createUser();

		$this->friend = GalleryTestLib::createUser();
		$this->owner->addFriend($this->friend->getGUID());

		$this->third_party = GalleryTestLib::createUser();

		$this->group_owner = GalleryTestLib::createUser();
		$this->member_in_group = GalleryTestLib::createUser();

		$this->group = GalleryTestLib::createGroup($this->group_owner->getGUID());
		$this->group->join($this->owner);
		$this->group->join($this->member_in_group);

		$this->user_album = GalleryTestLib::createAlbum($this->owner->getGUID(), $this->owner->getGUID());
		$this->group_album = GalleryTestLib::createAlbum($this->owner->getGUID(), $this->group->getGUID());

		$this->ia = elgg_set_ignore_access(false);
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {

		elgg_set_ignore_access($this->ia);

		$this->group->delete();
		$this->group_owner->delete();

		$this->owner->delete();
		$this->friend->delete();
		$this->member_in_group->delete();
		$this->third_party->delete();

		$this->user_album->delete();
		$this->group_album->delete();
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		// all __destruct() code should go above here
		parent::__destruct();
	}

	/**
	 * Test edit permissions on an album contained by user
	 */
	public function testCanEditUserAlbum() {

		$this->assertTrue($this->user_album->canEdit($this->owner->getGUID()));

		$this->assertFalse($this->user_album->canEdit($this->friend->getGUID()));
		$this->assertFalse($this->user_album->canEdit($this->member_in_group->getGUID()));
		$this->assertFalse($this->user_album->canEdit($this->third_party->getGUID()));
	}

	/**
	 * Test edit permissions
	 */
	public function testCanEditGroupAlbum() {

		$this->assertTrue($this->group_album->canEdit($this->owner->getGUID()));
		$this->assertTrue($this->group_album->canEdit($this->group->getOwnerGUID()));
		$this->assertFalse($this->group_album->canEdit($this->friend->getGUID()));
		$this->assertFalse($this->group_album->canEdit($this->member_in_group->getGUID()));
		$this->assertFalse($this->group_album->canEdit($this->third_party->getGUID()));
	}

	/**
	 * Test if other users can add images to an album with add permissions set to private
	 */
	public function testCanAddImagesToPrivateUserAlbum() {

		$this->user_album->permission = 'private';

		$this->assertFalse($this->user_album->canWriteToContainer($this->friend->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertFalse($this->user_album->canWriteToContainer($this->member_in_group->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertFalse($this->user_album->canWriteToContainer($this->third_party->getGUID(), 'object', hjAlbumImage::SUBTYPE));
	}

	/**
	 * Test if other users can add images to an album with add permissions set to friends
	 */
	public function testCanAddImagesToFriendsUserAlbum() {

		$this->user_album->permission = 'friends';

		$this->assertTrue($this->user_album->canWriteToContainer($this->friend->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertFalse($this->user_album->canWriteToContainer($this->member_in_group->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertFalse($this->user_album->canWriteToContainer($this->third_party->getGUID(), 'object', hjAlbumImage::SUBTYPE));
	}

	/**
	 * Test if other users can add images to an album with add permissions set to public
	 */
	public function testCanAddImagesToPublicUserAlbum() {

		$this->user_album->permission = 'public';

		$this->assertTrue($this->user_album->canWriteToContainer($this->friend->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertTrue($this->user_album->canWriteToContainer($this->member_in_group->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertTrue($this->user_album->canWriteToContainer($this->third_party->getGUID(), 'object', hjAlbumImage::SUBTYPE));
	}

	/**
	 * Test if other users can add images to a group album with permission set to private
	 */
	public function testCanAddImagesToPrivateGroupAlbum() {

		$this->group_album->permission = 'private';

		$this->assertTrue($this->group_album->canWriteToContainer($this->group->getOwnerGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertFalse($this->group_album->canWriteToContainer($this->friend->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertFalse($this->group_album->canWriteToContainer($this->member_in_group->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertFalse($this->group_album->canWriteToContainer($this->third_party->getGUID(), 'object', hjAlbumImage::SUBTYPE));
	}

	/**
	 * Test if other users can add images to a group album with permission set to group
	 */
	public function testCanAddImagesToMembersGroupAlbum() {

		$this->group_album->permission = 'group';

		$this->assertTrue($this->group_album->canWriteToContainer($this->group->getOwnerGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertFalse($this->group_album->canWriteToContainer($this->friend->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertTrue($this->group_album->canWriteToContainer($this->member_in_group->getGUID(), 'object', hjAlbumImage::SUBTYPE));
		$this->assertFalse($this->group_album->canWriteToContainer($this->third_party->getGUID(), 'object', hjAlbumImage::SUBTYPE));
	}

}
