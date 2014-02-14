<?php

namespace hypeJunction\Gallery;

if (!elgg_is_xhr()) {
	forward(REFERER);
}

$entity = elgg_extract('entity', $vars);

if (elgg_instanceof($entity, 'object', 'hjalbum')) {
	$album = $entity;
	$current = false;
} else {
	$album = get_entity($entity->container_guid);
	$current = $entity->guid;
}

$dbprefix = elgg_get_config('dbprefix');
$subtype_id = get_subtype_id('object', 'hjalbumimage');
$access = get_access_sql_suffix('e');

$data = get_data("SELECT e.guid as guid, oe.title as title FROM {$dbprefix}entities e JOIN {$dbprefix}objects_entity oe ON e.guid = oe.guid WHERE e.subtype = $subtype_id AND e.container_guid = $album->guid AND $access");

print(json_encode(array(
			'img' => $data,
			'album_guid' => $album->guid,
			'album_title' => $album->title
)));
forward();
