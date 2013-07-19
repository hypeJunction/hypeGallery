<?php

function hj_gallery_get_files($options = array()) {

	$defaults = array(
		'types' => 'object',
		'count' => false,
		'limit' => get_input('limit', 10),
		'offset' => get_input('offset', 0),
		'metadata_names' => array('simpletype'),
		'order_by_metadata' => array('name' => 'priority', 'direction' => 'ASC')
	);

	$options = array_merge($defaults, $options);

	return elgg_get_entities_from_metadata($options);
}

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

function hj_gallery_handle_uploaded_files($entity) {



	return $images;
}