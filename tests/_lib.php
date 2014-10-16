<?php

namespace hypeJunction\Gallery;

use ElggGroup;
use ElggUser;

class GalleryTestLib {

	/**
	 * Create a fake user
	 *
	 * @return ElggUser
	 */
	public static function createUser() {
		$user = new ElggUser();
		$user->username = 'fake_user_' . rand();
		$user->email = 'fake_email@fake.com' . rand();
		$user->name = 'fake user ' . rand();
		$user->access_id = ACCESS_PUBLIC;
		$user->salt = generate_random_cleartext_password();
		$user->password = generate_user_password($user, rand());
		$user->owner_guid = 0;
		$user->container_guid = 0;
		$user->save();

		return $user;
	}

	/**
	 * Create a fake group
	 *
	 * @return \hypeJunction\Gallery\ElggGroup
	 */
	public static function createGroup($owner_guid = ELGG_ENTITIES_ANY_VALUE) {
		$group = new ElggGroup();
		$group->owner_guid = $owner_guid;
		$group->membership = ACCESS_PUBLIC;
		$group->access_id = ACCESS_PUBLIC;
		$group->save();

		if ($owner_guid) {
			$group->join(get_entity($owner_guid));
		}

		return $group;
	}

	/**
	 * Create a fake album
	 *
	 * @param int $owner_guid     GUID of the owner
	 * @param int $container_guid GUID of the container
	 * @return hjAlbum
	 */
	public static function createAlbum($owner_guid = ELGG_ENTITIES_ANY_VALUE, $container_guid = ELGG_ENTITIES_ANY_VALUE) {

		$album = new hjAlbum();
		$album->owner_guid = $owner_guid;
		$album->container_guid = $container_guid;
		$album->save();

		return $album;
	}

	/**
	 * Create a fake image
	 *
	 * @param int $owner_guid     GUID of the owner
	 * @param int $container_guid GUID of the container album
	 * @param hjAlbumImage $type
	 */
	public static function createImage($owner_guid = ELGG_ENTITIES_ANY_VALUE, $container_guid = ELGG_ENTITIES_ANY_VALUE, $type = null) {

		if (!$type) {
			$type = 'jpg';
		}

		$image = new hjAlbumImage();
		$image->owner_guid = $owner_guid;
		$image->container_guid = $container_guid;
		$image->setFilename("tests/testfile.$type");

		$file = self::getTestFile($type);
		$image->open('write');
		$image->write($file);
		$image->close();

		$image->save();

		return $image;
	}

	/**
	 * Get a test file
	 *
	 * @param string $type File type
	 * @return string
	 */
	private static function getTestFile($type = 'jpg') {

		switch ($type) {

			default :
			case 'jpg' :
			case 'jpeg' :
				return file_get_contents(__DIR__ . '/test_files/yolinux-mime-test.jpeg');

			case 'gif' :
				return file_get_contents(__DIR__ . '/test_files/yolinux-mime-test.gif');

			case 'png' :
				return file_get_contents(__DIR__ . '/test_files/yolinux-mime-test.png');
		}
	}

}
