<?php

namespace hypeJunction\Gallery;

use ElggBatch;
use stdClass;

if (!elgg_is_xhr()) {
	forward(REFERER);
}

$entity = elgg_extract('entity', $vars);

if ($entity instanceof hjAlbum) {
	$album = $entity;
	$current = false;
} else {
	$album = get_entity($entity->container_guid);
	$current = $entity->guid;
}

// In Elgg 3.0 objects_entity subtable was removed; title is now on the
// entities table directly.
$images = new ElggBatch('elgg_get_entities', [
	'types' => 'object',
	'subtypes' => hjAlbumImage::SUBTYPE,
	'container_guids' => $album->guid,
	'limit' => 0,
]);

$data = array();
foreach ($images as $img) {
	$img_data = new stdClass();
	$img_data->guid = $img->guid;
	$img_data->title = $img->title;
	$data[] = $img_data;
}

print(json_encode(array(
			'img' => $data,
			'album_guid' => $album->guid,
			'album_title' => $album->title
)));
forward();
