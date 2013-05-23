<?php

$guid = get_input('container_guid');
$entity = get_entity($guid);

if (!$entity instanceof hjAlbum) {
	return false;
}

elgg_push_context('gallery-upload');

$title = elgg_echo('hj:gallery:upload:toalbum', array($entity->getTitle()));

elgg_push_breadcrumb($title);

$content = hj_framework_view_form("edit:object:hjalbum:upload", array(
	'entity' => $entity,
		));

$layout = elgg_view_layout('framework/entity', array(
	'entity' => $entity,
	'title' => $title,
	'content' => $content,
		));

echo elgg_view_page($title, $layout);

elgg_pop_context();