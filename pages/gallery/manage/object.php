<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof hjAlbum) {
	return false;
}

$title = elgg_echo('hj:gallery:manage:album', array($entity->getTitle()));

elgg_push_breadcrumb($title);

$content = elgg_view("framework/gallery/manage/object/hjalbum", array(
	'entity' => $entity
		));

$layout = elgg_view_layout('one_sidebar', array(
	'title' => $title,
	'content' => $content,
		));

echo elgg_view_page($title, $layout);

