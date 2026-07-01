<?php

namespace hypeJunction\Gallery;

$guid = get_input('guid');
$entity = $guid ? get_entity((int) $guid) : null;

if (!$entity instanceof \ElggEntity) {
	return false;
}

$entity->views++;

$ancestry = get_ancestry($entity->guid);

foreach ($ancestry as $ancestor) {
	if ($ancestor instanceof \ElggGroup) {
		elgg_set_page_owner_guid($ancestor->guid);
		elgg_push_breadcrumb($ancestor->name, $ancestor->getURL());
	} else if ($ancestor instanceof \ElggObject) {
		elgg_push_breadcrumb($ancestor->title, $ancestor->getURL());
	}
}

$title = $entity->title;

elgg_push_breadcrumb($title);

register_entity_title_buttons($entity);

$sidebar = elgg_view('framework/gallery/sidebar', [
	'entity' => $entity
]);

$content = elgg_view_entity($entity, [
	'full_view' => true,
	'list_type' => 'list'
]);

$layout = elgg_view_layout('content', [
	'title' => $title,
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => false
]);

echo elgg_view_page($title, $layout, 'default', [
	'entity' => $entity
]);

