<?php

$entity = elgg_extract('entity', $vars, false);

if (!elgg_instanceof($entity, 'object', 'hjalbum')) {
	return true;
}

$title = elgg_view('object/hjalbum/elements/title', $vars);

$vars['size'] = 'large';
$info = elgg_view('object/hjalbum/elements/cover', $vars);

$info .= elgg_view('object/hjalbum/elements/image_count', $vars);
$info .= elgg_view('object/hjalbum/elements/author', $vars);
$info .= elgg_view('object/hjalbum/elements/time_created', $vars);

$description = elgg_view('object/hjalbum/elements/description', $vars);

$details .= elgg_view('object/hjalbum/elements/tags', $vars);
if (HYPEGALLERY_CATEGORIES) {
	$details .= elgg_view('object/hjalbum/elements/categories', $vars);
}
if (HYPEGALLERY_COPYRIGHTS) {
	$details .= elgg_view('object/hjalbum/elements/copyright', $vars);
}
if (HYPEGALLERY_INTERFACE_LOCATION) {
	$details .= elgg_view('object/hjalbum/elements/location', $vars);
}
if (HYPEGALLERY_INTERFACE_CALENDAR) {
	$details .= elgg_view('object/hjalbum/elements/date', $vars);
}

$menu = elgg_view('object/hjalbum/elements/menu', $vars);
if (get_input('details')) {
	$title = elgg_view_image_block('', $title, array(
		'image_alt' => $menu
	));
	$images = elgg_view('object/hjalbum/elements/images', $vars);
	$body = elgg_view_image_block($info . $details, $images);
	echo elgg_view_module('aside', $title, $body, array('footer' => $description, 'class' => 'elgg-module-album-detailed'));
} else {
	echo elgg_view_module('album', $title, $info);
}
