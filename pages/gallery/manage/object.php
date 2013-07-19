<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof hjAlbum) {
	return false;
}

elgg_push_context('gallery-manage');

$title = elgg_echo('hj:gallery:manage:album', array($entity->title));

$content = elgg_view("framework/gallery/manage/object/hjalbum", array(
	'entity' => $entity
		));

$layout = elgg_view_layout('content', array(
	'entity' => $entity,
	'title' => $title,
	'content' => $content,
	'filter' => false,
		));

echo elgg_view_page($title, $layout);

elgg_pop_context();