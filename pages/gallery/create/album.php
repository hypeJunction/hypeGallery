<?php

namespace hypeJunction\Gallery;

$container_guid = get_input('container_guid');
$container = get_entity($container_guid);

if (!$container instanceof \ElggEntity || !$container->canWriteToContainer(0, 'object', hjAlbum::SUBTYPE)) {
	return false;
}

$title = elgg_echo('gallery:create:album');

elgg_push_breadcrumb($container->title, $container->getURL());
elgg_push_breadcrumb($title);

$content = elgg_view_form('edit/object/hjalbum', [
	'enctype' => 'multipart/form-data',
], [
	'container' => $container
]);

$layout = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $layout);
