<?php

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity)) {
	return true;
}

$title = elgg_view('framework/bootstrap/object/elements/title', $vars);
echo $title;
if (get_input('photostream') || elgg_in_context('table-view')) {
	$album_title = elgg_view('object/hjalbum/elements/title', array(
		'entity' => $entity->getContainerEntity()
	));
	echo elgg_echo('hj:gallery:image:container', array($album_title));
}