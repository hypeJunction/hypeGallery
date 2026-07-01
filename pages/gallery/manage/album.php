<?php

namespace hypeJunction\Gallery;

$guid = get_input('guid');
$entity = $guid ? get_entity((int) $guid) : null;

if (!$entity instanceof hjAlbum) {
	return false;
}

$ancestry = get_ancestry($entity->guid);

foreach ($ancestry as $ancestor) {
	if ($ancestor instanceof \ElggGroup) {
		elgg_set_page_owner_guid($ancestor->guid);
		elgg_push_breadcrumb($ancestor->name, $ancestor->getURL());
	} else if ($ancestor instanceof \ElggObject) {
		elgg_push_breadcrumb($ancestor->title, $ancestor->getURL());
	}
}

register_entity_title_buttons($entity);

$title = elgg_echo('gallery:manage:album');


elgg_push_breadcrumb($entity->title, $entity->getURL());
elgg_push_breadcrumb($title);

elgg_push_context('gallery-manage');

$content = elgg_view('framework/gallery/manage/album', [
	'entity' => $entity
]);

$layout = elgg_view_layout('content', [
	'entity' => $entity,
	'title' => $title,
	'content' => $content,
	'filter' => false,
]);

echo elgg_view_page($title, $layout);

elgg_pop_context();
