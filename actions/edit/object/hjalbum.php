<?php

namespace hypeJunction\Gallery;

use ElggBatch;

elgg_make_sticky_form('edit:object:hjalbum');

// Attributes
$guid = get_input('guid', null);
$owner_guid = get_input('owner_guid', elgg_get_logged_in_user_guid());
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());
$title = get_input('title', elgg_echo('album:untitled'));
$description = get_input('description', '');
$access_id = get_input('access_id', get_default_access());

// Metadata
$location = get_input('location', '');
$categories = get_input('categories', '');
$date = get_input('date', '');
$tags = get_input('tags', '');
$permission = get_input('permission', 'private');

$album = new hjAlbum($guid);
if (!$guid) {
	$album->owner_guid = $owner_guid;
	$album->container_guid = $container_guid;
}
$album->title = $title;
$album->description = $description;
$previous_access_id = $entity->access_id;
$album->access_id = $access_id;

if (!$album->save()) {
	register_error(elgg_echo('gallery:save:error'));
	forward(REFERER);
} else {
	// Update image access if album access has changed
	if ($guid && $previous_access_id !== $album->access_id) {
		$images = new ElggBatch('elgg_get_entities', array(
			'types' => 'object',
			'subtypes' => hjAlbumImage::SUBTYPE,
			'container_guids' => $album->guid,
			'limit' => 0
		));
		foreach ($images as $image) {
			$image->access_id = $album->access_id;
			$image->save();
		}
	}
	system_message(elgg_echo('gallery:save:success'));
}

if ($location) {
	$album->location = $location;
	$coordinates = elgg_trigger_plugin_hook('geocode', 'location', array('location' => $location));
	if ($coordinates) {
		$album->setLatLong($coordinates['lat'], $coordinates['long']);
	}
}

$album->date = $date;
$album->categories = string_to_tag_array($categories);
$album->tags = string_to_tag_array($tags);
$album->permission = $permission;

$album->save();

set_input('container_guid', $album->guid);

include elgg_get_root_path() . 'mod/hypeGallery/actions/upload/handle.php';
include elgg_get_root_path() . 'mod/hypeGallery/actions/upload/describe.php';

elgg_clear_sticky_form('edit:object:hjalbum');

forward("gallery/manage/$album->guid");
