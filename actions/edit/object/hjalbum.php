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
$access_id = get_input('access_id', elgg_get_default_access());

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
	return elgg_error_response(elgg_echo('gallery:save:error'));
}

// Update image access if album access has changed
if ($guid && $previous_access_id !== $album->access_id) {
	$images = new ElggBatch('elgg_get_entities', [
		'types' => 'object',
		'subtypes' => hjAlbumImage::SUBTYPE,
		'container_guids' => $album->guid,
		'limit' => 0
	]);
	foreach ($images as $image) {
		$image->access_id = $album->access_id;
		$image->save();
	}
}

if ($location) {
	$album->location = $location;
	$coordinates = elgg_trigger_event_results('geocode', 'location', ['location' => $location], null);
	if ($coordinates) {
		$album->setLatLong($coordinates['lat'], $coordinates['long']);
	}
}

$album->date = $date;
$album->categories = is_array($categories) ? $categories : elgg_string_to_array((string) $categories);
$album->tags = is_array($tags) ? $tags : elgg_string_to_array((string) $tags);
$album->permission = $permission;

$album->save();

set_input('container_guid', $album->guid);

include elgg_get_root_path() . 'mod/hypegallery/actions/upload/handle.php';
include elgg_get_root_path() . 'mod/hypegallery/actions/upload/describe.php';

elgg_clear_sticky_form('edit:object:hjalbum');

return elgg_ok_response(
	['guid' => $album->guid],
	elgg_echo('gallery:save:success'),
	"gallery/manage/{$album->guid}"
);
