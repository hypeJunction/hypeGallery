<?php

namespace hypeJunction\Gallery;

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
$permissions = get_input('permissions', 'private');

$album = new hjAlbum($guid);
if (!$guid) {
	$album->owner_guid = $owner_guid;
	$album->container_guid = $container_guid;
}
$album->title = $title;
$album->description = $description;
$album->access_id = $access_id;

if (!$album->save()) {
	register_error(elgg_echo('gallery:save:error'));
	forward(REFERER);
} else {
	system_message(elgg_echo('gallery:save:success'));
}

if ($location) {
	$album->location = $location;
	$coordinates = elgg_geocode_location($location);
	if ($coordinates) {
		$album->setLatLong($coordinates['lat'], $coordinates['long']);
	}
}

$album->date = $date;
$album->categories = string_to_tag_array($categories);
$album->tags = string_to_tag_array($tags);
$album->permission = $permissions;

$album->save();

set_input('container_guid', $album->guid);

include elgg_get_root_path() . 'mod/hypeGallery/actions/upload/handle.php';
include elgg_get_root_path() . 'mod/hypeGallery/actions/upload/describe.php';

elgg_clear_sticky_form('edit:object:hjalbum');

forward("gallery/manage/$album->guid");
