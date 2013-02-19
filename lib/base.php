<?php

function hj_gallery_get_image_tags($entity) {

	$tag_params = array(
		'type' => 'object',
		'subtype' => 'hjannotation',
		'container_guid' => $entity->guid,
		'metadata_name_value_pairs' => array(
			array('name' => 'annotation_name', 'value' => 'hjimagetag'),
			array('name' => 'annotation_value', 'value' => '', 'operand' => '!=')
		),
		'limit' => 0,
		'order_by' => 'e.time_created asc'
	);

	$tags = elgg_get_entities_from_metadata($tag_params);

	return $tags;
}

function hj_gallery_get_next_image($entity) {

	$next = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'hjalbumimage',
		'container_guid' => $entity->container_guid,
		'wheres' => array("e.guid < $entity->guid"),
		'limit' => 1
	));

	return ($next) ? $next[0] : false;

}

function hj_gallery_get_prev_image($entity) {

	$prev = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'hjalbumimage',
		'container_guid' => $entity->container_guid,
		'wheres' => array("e.guid > $entity->guid"),
		'limit' => 1
	));

	return ($prev) ? $prev[0] : false;
}