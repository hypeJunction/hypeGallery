<?php

$entity = elgg_extract('entity', $vars, false);

if (!elgg_instanceof($entity, 'object', 'hjalbumimage')) {
	return true;
}

$title = elgg_view('object/hjalbumimage/elements/title', $vars);

$info .= elgg_view('object/hjalbumimage/elements/author', $vars);
$info .= elgg_view('object/hjalbumimage/elements/time_created', $vars);

$details = elgg_view('object/hjalbumimage/elements/description', $vars);

$details .= elgg_view('object/hjalbumimage/elements/tags', $vars);

if (HYPEGALLERY_COPYRIGHTS) {
	$details .= elgg_view('object/hjalbumimage/elements/copyright', $vars);
}
if (HYPEGALLERY_INTERFACE_LOCATION) {
	$details .= elgg_view('object/hjalbumimage/elements/location', $vars);
}
if (HYPEGALLERY_INTERFACE_CALENDAR) {
	$details .= elgg_view('object/hjalbumimage/elements/date', $vars);
}

$menu = elgg_view('object/hjalbumimage/elements/menu', $vars);

if (get_input('details')) {

	$vars['size'] = 'master';
	$info = elgg_view('object/hjalbumimage/elements/icon', $vars) . $info;

	$title = elgg_view_image_block('', $title, array(
		'image_alt' => $menu
			));
	echo elgg_view_module('info', $title, $info . $details, array('class' => 'elgg-module-albumimage-detailed'));

} else {

	$vars['size'] = 'large';
	$info = elgg_view('object/hjalbumimage/elements/icon', $vars) . $info;

	echo elgg_view_module('album', $title, $info);
}
