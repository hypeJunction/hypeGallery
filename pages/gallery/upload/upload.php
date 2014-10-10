<?php

namespace hypeJunction\Gallery;

$guid = get_input('container_guid');
$entity = get_entity($guid);

if (!$entity instanceof hjAlbum) {
	return false;
}


$ancestry = get_ancestry($entity->guid);

foreach ($ancestry as $ancestor) {
	if (elgg_instanceof($ancestor, 'site')) {
		// do nothing
	} else if (elgg_instanceof($ancestor, 'group')) {
		elgg_set_page_owner_guid($ancestor->guid);
		elgg_push_breadcrumb($ancestor->name, $ancestor->getURL());
	} else if (elgg_instanceof($ancestor, 'object')) {
		elgg_push_breadcrumb($ancestor->title, $ancestor->getURL());
	}
}

register_entity_title_buttons($entity);

$title = elgg_echo('gallery:upload:toalbum');


elgg_push_breadcrumb($entity->title, $entity->getURL());
elgg_push_breadcrumb($title);

elgg_push_context('gallery-upload');


$content = elgg_view_form("gallery/upload", array(
	'enctype' => 'multipart/form-data'
		), array(
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
