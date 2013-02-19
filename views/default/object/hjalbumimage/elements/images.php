<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	return true;
}

$images = $entity->getContainedFiles(0);

elgg_push_context('activity');
echo elgg_view_entity_list($images, array(
	'size' => 'medium',
	'list_type' => 'gallery'
));
elgg_pop_context();