<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

if (!elgg_instanceof($entity, 'object', 'hjalbumimage')) {
	return true;
}

$title = elgg_view('object/hjalbumimage/elements/title', $vars);

$details .= elgg_view('object/hjalbumimage/elements/tags', $vars);

	if (HYPEGALLERY_CATEGORIES) {
		$details .= elgg_view('object/hjalbumimage/elements/categories', $vars);
	}
	if (HYPEGALLERY_COPYRIGHTS) {
		$details .= elgg_view('object/hjalbumimage/elements/copyright', $vars);
	}
	if (HYPEGALLERY_INTERFACE_LOCATION) {
		$details .= elgg_view('object/hjalbumimage/elements/location', $vars);
	}
	if (HYPEGALLERY_INTERFACE_CALENDAR) {
		$details .= elgg_view('object/hjalbumimage/elements/date', $vars);
	}

if ($full) {

	elgg_push_context('image-full-view');

	if (HYPEGALLERY_TAGGING) {
		$image = elgg_view('framework/gallery/phototag/image', $vars);
	} else {
		$vars['size'] = 'master';
		$image = elgg_view('object/hjalbumimage/elements/icon', $vars);
	}
	
	$description = elgg_view('object/hjalbumimage/elements/description', $vars);

	$comments = elgg_view('object/hjalbumimage/elements/comments', $vars);
	echo elgg_view_module('main', '', $description . $image . $details, array(
		'footer' => $comments
	));
	elgg_pop_context();
} else {
	$vars['size'] = 'medium';
	$cover = elgg_view('object/hjalbumimage/elements/icon', $vars);
	$briefdescription = elgg_view('object/hjalbumimage/elements/briefdescription', $vars);
	echo elgg_view_image_block($cover, $title . $briefdescription . $details);
}