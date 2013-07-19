<?php

$guid = get_input('container_guid');
$entity = get_entity($guid);

if (!$entity instanceof hjAlbum) {
	return false;
}

elgg_push_context('gallery-upload');

$title = elgg_echo('hj:gallery:upload:toalbum', array($entity->title));

elgg_push_breadcrumb($title);

$content = elgg_view_form("gallery/upload", array(), array(
	'entity' => $entity,
		));

$layout = elgg_view_layout('content', array(
	'entity' => $entity,
	'title' => $title,
	'content' => $content,
	'filter' => false,
		));

echo elgg_view_page($title, $layout);

elgg_pop_context();