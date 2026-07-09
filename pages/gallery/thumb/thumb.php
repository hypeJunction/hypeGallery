<?php

namespace hypeJunction\Gallery;

$guid = get_input('guid');
$entity = $guid ? get_entity((int) $guid) : null;

if (!$entity instanceof \ElggEntity || !$entity->canEdit()) {
	return false;
}

$title = elgg_echo('gallery:image:editthumb');

$content = elgg_view('framework/gallery/tools/cropper', [
	'entity' => $entity,
]);

echo elgg_view_page($title, elgg_view_layout('one_column', [
	'title' => $title,
	'content' => $content,
]));
