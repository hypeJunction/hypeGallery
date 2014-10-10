<?php

namespace hypeJunction\Gallery;

$guid = get_input('guid');
$entity = get_entity($guid);

if (!elgg_instanceof($entity)) {
	return false;
}

$entity->views++;

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

$title = $entity->title;

elgg_push_breadcrumb($title);

register_entity_title_buttons($entity);

$sidebar = elgg_view('framework/gallery/sidebar', array(
	'entity' => $entity
		));

$content = elgg_view_entity($entity, array(
	'full_view' => true,
	'list_type' => 'list'
		));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => false
		));

echo elgg_view_page($title, $layout, 'default', array(
	'entity' => $entity
));

