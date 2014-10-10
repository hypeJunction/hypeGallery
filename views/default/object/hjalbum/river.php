<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);
$river_time = elgg_extract('river_time', $vars);

if (!$entity->$river_time) {

	echo elgg_list_entities(array(
		'types' => 'object',
		'subtypes' => array('hjalbumimage'),
		'container_guid' => $entity->guid,
		'limit' => 9,
		'size' => 'medium',
		'list_type' => 'gallery',
		'item_class' => 'elgg-photo mas',
		'pagination' => false
	));
} else {

	$guids = unserialize($entity->$river_time);
	foreach ($guids as $guid) {
		if (get_entity($guid)) {
			$images[] = get_entity($guid);
		}
	}

	echo elgg_view_entity_list($images, array(
		'size' => 'medium',
		'list_type' => 'gallery',
		'item_class' => 'elgg-photo mas',
		'pagination' => false
	));
}