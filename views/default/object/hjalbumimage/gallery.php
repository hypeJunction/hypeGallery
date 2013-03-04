<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

if (!elgg_instanceof($entity, 'object', 'hjalbumimage')) {
	return true;
}

if ($full) {
	echo elgg_view('object/hjalbumimage/list', $vars);
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

$vars['size'] = 'large';
$icon = elgg_view('object/hjalbumimage/elements/icon', $vars);

$output =  elgg_view_module('album', $title, $icon . $info);

if (elgg_in_context('gallery-view')) {
	if (get_input('details') == 'summary') {

		$vars['size'] = 'master';
		$info = elgg_view('object/hjalbumimage/elements/icon', $vars) . $info;

		$title = elgg_view_image_block('', $title, array(
			'image_alt' => $menu
				));
		$output = elgg_view_module('aside', $title, $info . $details, array('class' => 'elgg-module-albumimage-summary'));
	} else if (get_input('details') == 'full') {

		$vars['size'] = 'master';
		$info = elgg_view('object/hjalbumimage/elements/icon', $vars) . $info;

		$title = elgg_view_image_block('', $title, array(
			'image_alt' => $menu
				));

		$output = elgg_view_module('aside', $title, $info . $details, array('class' => 'elgg-module-albumimage-full'));
	}
}

echo $output;
