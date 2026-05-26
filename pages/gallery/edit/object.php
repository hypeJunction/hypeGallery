<?php

namespace hypeJunction\Gallery;

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof \ElggEntity || !$entity->canEdit()) {
	return false;
}

$ancestry = get_ancestry($entity->guid);

foreach ($ancestry as $ancestor) {
	if ($ancestor instanceof \ElggSite) {
		// do nothing
	} else if ($ancestor instanceof \ElggGroup) {
		\elgg_set_page_owner_guid($ancestor->guid);
		\elgg_push_breadcrumb($ancestor->name, $ancestor->getURL());
	} else if ($ancestor instanceof \ElggObject) {
		\elgg_push_breadcrumb($ancestor->title, $ancestor->getURL());
	}
}

$type = $entity->getType();
$subtype = $entity->getSubtype();

$title = \elgg_echo("gallery:edit:$type:$subtype");

\elgg_push_breadcrumb($entity->title, $entity->getURL());
\elgg_push_breadcrumb($title);

$content = \elgg_view_form("edit/$type/$subtype", array(
	'enctype' => 'multipart/form-data',
		), array(
	'entity' => $entity,
		));

$layout = \elgg_view_layout('one_sidebar', array(
	'title' => $title,
	'content' => $content,
		));

echo \elgg_view_page($title, $layout);

