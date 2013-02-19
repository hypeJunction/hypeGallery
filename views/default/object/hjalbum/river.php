<?php

$entity = elgg_extract('entity', $vars);

echo elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => array('hjalbumimage'),
	'container_guid' => $entity->guid,
	'limit' => 9,
	'size' => 'medium',
	'list_type' => 'gallery',
	'pagination' => false
));