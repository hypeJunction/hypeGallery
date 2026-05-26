<?php

namespace hypeJunction\Gallery;

$entity = \elgg_extract('entity', $vars);
$river_time = \elgg_extract('river_time', $vars);

if (!$entity->$river_time) {
	echo \elgg_list_entities([
		'types' => 'object',
		'subtypes' => [hjAlbumImage::SUBTYPE],
		'container_guid' => $entity->guid,
		'limit' => 9,
		'size' => 'medium',
		'list_type' => 'gallery',
		'item_class' => 'elgg-photo mas',
		'pagination' => false
	]);
} else {
	// Metadata is JSON-encoded. Legacy serialize()d entries are migrated by
	// \hypeJunction\Gallery\Upgrades\EncodeRiverMetadataAsJson.
	$raw = $entity->$river_time;
	$guids = json_decode((string) $raw, true);
	if (is_array($guids)) {
		echo \elgg_list_entities([
			'guids' => $guids,
			'size' => 'medium',
			'list_type' => 'gallery',
			'item_class' => 'elgg-photo mas',
			'pagination' => false
		]);
	}
}
