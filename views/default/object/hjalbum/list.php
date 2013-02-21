<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

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

if ($full) {
	elgg_push_context('album-full-view');
	$images = elgg_view('framework/gallery/list/images', array(
		'list_id' => "ai$entity->guid",
		'getter_options' => array(
			'container_guids' => array($entity->guid),
			'types' => 'object',
			'subtypes' => array('hjalbumimage'),
		)
			));
	$comments = elgg_view('object/hjalbum/elements/comments', $vars);

	echo elgg_view_module('main', '', $description . $details . $images, array(
		'footer' => $comments
	));
	elgg_pop_context();
} else {
	$vars['size'] = 'medium';
	$cover = elgg_view('framework/bootstrap/object/elements/icon', $vars);
	$count = elgg_view('object/hjalbum/elements/image_count', $vars);
	$briefdescription = elgg_view('framework/bootstrap/object/elements/briefdescription', $vars);
	echo elgg_view_image_block($cover, $title . $briefdescription . $info . $details);
}