<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

if (!elgg_instanceof($entity, 'object', 'hjalbumimage')) {
	return true;
}

$title = elgg_view('framework/bootstrap/object/elements/title', $vars);

if ($full) {
	$vars['size'] = 'tiny';
	$cover = elgg_view('framework/bootstrap/object/elements/icon', $vars);
	$comments = elgg_view('object/hjalbumimage/elements/comments', $vars);

	$title = elgg_view_image_block($cover, $title);
	$description = elgg_view('framework/bootstrap/object/elements/description', $vars);
	$images = elgg_view('framework/gallery/list/images', array(
		'list_id' => "ai$entity->guid",
		'container_guids' => array($entity->guid),
		'subtypes' => array('hjalbumimageimage')
			));

	echo elgg_view_module('album', $title, $description . $images, array(
		'footer' => $comments
	));
} else {

	$vars['size'] = 'medium';
	$cover = elgg_view('framework/bootstrap/object/elements/icon', $vars);
	$count = elgg_view('object/hjalbumimage/elements/image_count', $vars);
	$briefdescription = elgg_view('framework/bootstrap/object/elements/briefdescription', $vars);
	echo elgg_view_image_block($cover, $title . $briefdescription . $count);
}
