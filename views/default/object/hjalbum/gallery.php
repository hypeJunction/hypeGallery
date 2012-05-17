<?php

$entity = elgg_extract('entity', $vars, false);

if (!elgg_instanceof($entity)) {
	return true;
}

$images = $entity->getContainedFiles('hjalbumimage');
$vars['image_count'] = sizeof($images);

echo elgg_view('object/hjalbum/elements/cover', $vars);